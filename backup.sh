#!/bin/bash

# filename: backup.sh
# description: Backup script to backup MariaDB databases
# author: 713koukou-naiza & Lucas ESPIET (lespiet@iutbayonne.univ-pau.fr)
# date: 25-01-2025
# usage: ./backup.sh

source ./.env

# VARS
dbName=$DB_NAME
dbUser=$DB_USER
dbPass=$DB_PASS
dbHost=$DB_HOST
backupFolder=$BACKUP_PATH
logFile=$backupFolder/backup.log
currentDate=$(date +%Y-%m-%d_%H-%M-%S)
keepDays=$BACKUP_RETENTION

sqlFile=$backupFolder/${dbName}-database-${currentDate}.sql
zipFile=$backupFolder/${dbName}-database-${currentDate}.zip

function log() {
  echo [$(date +'%Y-%m-%d %H:%M:%S')] $1 >> $logFile
}

# Create backup folder if not exists
if [ ! -d $backupFolder ]; then
  mkdir -p $backupFolder
fi
touch $logFile


### Backup database ###
echo ------------------------------------------------ >> $logFile
log "[INFO] Starting backup of database $dbName"
log "[INFO] Backup file: $sqlFile"

mariadbDumpResult=$(mariadb-dump -u $dbUser -h $dbHost -p$dbPass $dbName >> $sqlFile 2>&1)
mariaDumpExitCode=$?
if [ $mariaDumpExitCode -eq 0 ]; then
  log "[INFO] Backup of database $dbName completed successfully"
  log "[INFO] SQL dump file: $sqlFile"
else
  log "[ERROR] Backup of database $dbName failed"
  log "[ERROR] mariadb-dump return non-zero code: $mariaDumpExitCode"
  log "[ERROR] mariadb-dump output:"
  log "[DEBUG] $mariadbDumpResult"
  exit 1
fi

### Compress backup ###
log "[INFO] Compressing backup file: $sqlFile"
zipResult=$(zip $zipFile $sqlFile >> $logFile 2>&1)
zipExitCode=$?
if [ $zipExitCode -eq 0 ]; then
  log "[INFO] Backup file compressed successfully"
  log "[INFO] Compressed file: $zipFile"
else
  log "[ERROR] Backup file compression failed"
  log "[ERROR] zip return non-zero code: $zipExitCode"
  log "[ERROR] zip output:"
  log "[DEBUG] $zipResult"
  exit 1
fi

### Delete SQL dump ###
log "[INFO] Deleting SQL dump file: $sqlFile"
rm $sqlFile

### Delete old backups ###
log "[INFO] Deleting backups older than $keepDays days"
find $backupFolder -name "*.zip" -type f -mtime +$keepDays -exec rm {} \;
log "[INFO] Old backups deleted successfully"

log "[INFO] Backup process completed successfully"
echo ------------------------------------------------ >> $logFile
exit 0