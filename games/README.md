### Ajout de jeux

Pour ajouter un jeu, il suffit de glisser le dossier du jeu dans le dossier `games` avec comme nom `game{idDuJeu}`. Par
exemple, pour ajouter un jeu avec l'ID 42 (en base de données) il faudra créer un dossier `game42` dans le dossier
`games`.

Le dossier du jeu doit contenir un fichier `settings.json` qui contient les informations du jeu. Voici un exemple de
fichier `settings.json` :

```json
{
  "game": {
    "name": "Nom du jeu",
    "version": "Version du jeu",
    "description": "Description du jeu",
    "type": "Type du jeu",
    "author": "John Doe"
  },
  "settings": {
    "minPlayers": 2,
    "maxPlayers": 20,
    "allowChat": true,
    "allowVoice": false,
    "allowSpectators": true,
    "allowJoinInProgress": false,
    "allowLeaveInProgress": true,
    "isNode": true,
    "serverPort": null,
    "serverAddress": null
  },
  "modifiableSettings": {
    // Liste des paramètres modifiables par l'hôte de la partie
  },
  "neededParametersFromComus": [
    // Liste des paramètres nécessaires pour lancer une partie
  ],
  "returnParametersToComus": [
    // Liste des paramètres à retourner à Comus
  ]
}
```

#### Paramètres

##### Liste de `settings`

- `minPlayer` : Nombre minimum de joueurs pour lancer une partie
- `maxPlayer` : Nombre maximum de joueurs pour lancer une partie
- `allowChat` : Autoriser le chat dans l'interface de Comus
- `allowVoice` : Autoriser la voix dans l'interface de Comus
- `allowSpectators` : Autoriser les spectateurs à voir la partie
- `allowJoinInProgress` : Autoriser les joueurs à rejoindre une partie en cours
- `allowLeaveInProgress` : Autoriser les joueurs à quitter une partie en cours
- `isNode` : Le jeu est-il un jeu NodeJS
- `serverPort` : Port du serveur de jeu
- `serverAddress` : Adresse du serveur de jeu

##### `neededParametersFromComus` et `returnParametersToComus`

Liste des paramètres possibles pour `neededParametersFromComus` :

- `MODIFIED_SETTING_DATA` : Liste des paramètres modifiés par l'hôte de la partie
- `PLAYER_UUID` : Les uuids des joueurs dans la partie
- `PLAYER_NAME` : Les noms des joueurs dans la partie

Liste des paramètres possibles pour `returnParametersToComus` :

- `WINNER_UUID` : UUID du/des gagnants de la partie
- `SCORES` : Score des joueurs

##### Paramètres modifiables

Ajout de paramètres modifiables pour l'hôte de partie dans `modifiableSettings` :

```json
{
  "identifiantDuParam": {
    "name": "Nom du paramètre affiché",
    "description": "Description du paramètre pour aider l'hôte de la partie",
    "type": "Type du paramètre"
    ...
  }
}
```

Liste des types possibles :

- `text` : Champ de texte
- `number` : Champ numérique
- `select` : Liste déroulante
- `checkbox` : Case à cocher

Chaque type a des propriétés spécifiques :

- `text`
    - `default` : Valeur par défaut
    - `maxLength` : Longueur maximale du texte
    - `minLength` : Longueur minimale du texte
    - `pattern` : Expression régulière à respecter
- `number`
    - `default` : Valeur par défaut
    - `min` : Valeur minimale
    - `max` : Valeur maximale
    - `step` : Pas de la valeur
- `select`
    - `options` : Liste des options possibles
        - `value` : Valeur de l'option
        - `label` : Label affiché de l'option
    - `default` : Option par défaut


 ## Format des données envoyés par Comus Party

 ## Format des données attendus par Comus Party

 ### Scores

Dans le cas où `returnParametersToComus` contient la valeur `SCORES`, le système attend un tableau associatif au format *JSON* :
```json
{
  "uuid1": 15,
  "uuid2": 7
}
```
