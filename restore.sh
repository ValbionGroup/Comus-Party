#!/bin/bash

# filename: restore.sh
# description: Restore script to restore MariaDB database
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

sqlFile=$backupFolder/${dbName}-database-${currentDate}.sql
zipFile=$backupFolder/${dbName}-database-${currentDate}.zip

function log() {
  echo [$(date +'%Y-%m-%d %H:%M:%S')] $1 >> $logFile
}

### Restore database ###
echo ------------------------------------------------ >> $logFile
log "[INFO] Starting restore of database $dbName"

latestBackup=$(ls -t $backupFolder/*.zip 2>/dev/null | head -n 1)

if [ -z "$latestBackup" ]; then
  log "[ERROR] No backup files found in $backupFolder"
  exit 1
fi
log "[INFO] Latest backup file: $latestBackup"

log "[INFO] Unzipping backup file: $latestBackup"
unzipResult=$(unzip -o $latestBackup -d $backupFolder >> $logFile 2>&1)
unzipExitCode=$?
if [ $unzipExitCode -eq 0 ]; then
  log "[INFO] Backup file unzipped successfully"
else
  log "[ERROR] Unzipping backup file failed"
  log "[ERROR] unzip return non-zero code: $unzipExitCode"
  log "[ERROR] unzip output:"
  log "[DEBUG] $unzipResult"
  exit 1
fi

log "[INFO] Restoring database $dbName"
# Drop database
mariadbDropResult=$(mariadb -u $dbUser -h $dbHost -p$dbPass -e "DROP DATABASE IF EXISTS $dbName" >> $logFile 2>&1)
mariadbDropExitCode=$?
if [ $mariadbDropExitCode -eq 0 ]; then
  log "[INFO] Database $dbName dropped successfully"
else
  log "[ERROR] Dropping database $dbName failed"
  log "[ERROR] mariadb return non-zero code: $mariadbDropExitCode"
  log "[ERROR] mariadb output:"
  log "[DEBUG] $mariadbDropResult"
  exit 1
fi

# Create database
mariadbCreateResult=$(mariadb -u $dbUser -h $dbHost -p$dbPass -e "CREATE DATABASE $dbName" >> $logFile 2>&1)
mariadbCreateExitCode=$?
if [ $mariadbCreateExitCode -eq 0 ]; then
  log "[INFO] Database $dbName created successfully"
else
  log "[ERROR] Creating database $dbName failed"
  log "[ERROR] mariadb return non-zero code: $mariadbCreateExitCode"
  log "[ERROR] mariadb output:"
  log "[DEBUG] $mariadbCreateResult"
  exit 1
fi

# Restore database
mariadbRestoreResult=$(mariadb -u $dbUser -h $dbHost -p$dbPass $dbName < $sqlFile >> $logFile 2>&1)
mariadbRestoreExitCode=$?
if [ $mariadbRestoreExitCode -eq 0 ]; then
  log "[INFO] Database $dbName restored successfully"
else
  log "[ERROR] Restoring database $dbName failed"
  log "[ERROR] mariadb return non-zero code: $mariadbRestoreExitCode"
  log "[ERROR] mariadb output:"
  log "[DEBUG] $mariadbRestoreResult"
  exit 1
fi

log "[INFO] Restore process completed successfully"
echo ------------------------------------------------ >> $logFile
exit 0