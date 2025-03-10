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
    "serverAddress": null,
    "serveByComus": true
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
- `serveByComus` : Indique si le jeu est servi par la plateforme officielle de Comus Party

##### `neededParametersFromComus` et `returnParametersToComus`

Liste des paramètres possibles pour `neededParametersFromComus` :

- `MODIFIED_SETTING_DATA` : Liste des paramètres modifiés par l'hôte de la partie
- `PLAYER_UUID` : Les uuids des joueurs dans la partie
- `PLAYER_NAME` : Les noms des joueurs dans la partie
- `PLAYER_STYLE` : Photo de profil et bannière des joueurs

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
    "type": "Type du paramètre",
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

## Format des données envoyé par Comus Party

> [!NOTE]  
> Tous les attributs sont *facultatifs* et **cumulables**.

### Paramètres modifiés par l'hôte de la partie

Dans le cas où `neededParametersFromComus` contient la valeur `MODIFIED_SETTING_DATA`, le système envoi un tableau
associatif au format *JSON* dans le paramètre `settings` :

```json
{
  "identifiantDuParam": "valeur"
}
```

### Joueurs

Dans le cas où `neededParametersFromComus` contient la valeur `PLAYER_UUID`, le système envoi un tableau associatif au
format *JSON* :

```json
[
  {
    "uuid": "uuidJoueur1",
    "token": "jetonJoueur1"
  },
  {
    "uuid": "uuidJoueur2",
    "token": "jetonJoueur2"
  }
]
```

La présence du token est obligatoire pour tous les jeux.

> [!NOTE]
> Cette partie est en cours de rédaction.

### Noms des joueurs

Dans le cas où `neededParametersFromComus` contient la valeur `PLAYER_NAME`, le système rajoute au tableau précédent les
noms des joueurs :

```json
[
  {
    "uuid": "uuidJoueur1",
    "token": "jetonJoueur1",
    "username": "Nom du joueur 1"
  },
  {
    "uuid": "uuidJoueur2",
    "token": "jetonJoueur2",
    "username": "Nom du joueur 2"
  }
]
```

> [!CAUTION]  
> Si l'attribut `PLAYER_UUID` n'est pas présent, l'attribut `PLAYER_NAME` ne sera pas pris en compte.

### Style des joueurs

Dans le cas où `neededParametersFromComus` contient la valeur `PLAYER_STYLE`, le système rajoute au tableau précédent
les styles des joueurs :

```json
[
  {
    "uuid": "uuidJoueur1",
    "token": "jetonJoueur1",
    "style": {
      "profilePicture": "urlPhotoProfilJoueur1",
      "banner": "urlBanniereJoueur1"
    }
  },
  {
    "uuid": "uuidJoueur2",
    "token": "jetonJoueur2",
    "style": {
      "profilePicture": "urlPhotoProfilJoueur2",
      "banner": "urlBanniereJoueur2"
    }
  }
]
```

> [!CAUTION]  
> Si l'attribut `PLAYER_UUID` n'est pas présent, l'attribut `PLAYER_STYLE` ne sera pas pris en compte.

## Format des données attendues par Comus Party

### Gagnants

Dans le cas où `returnParametersToComus` contient la valeur `WINNER_UUID`, le système attend un tableau associatif au
format *JSON* :

```json
[
  "uuid1",
  "uuid2"
]
```

### Scores

Dans le cas où `returnParametersToComus` contient la valeur `SCORES`, le système attend un tableau associatif au format
*JSON* :

```json
{
  "uuid1": 15,
  "uuid2": 7
}
```

# Développer un jeu

Chacun est libre de développer un jeu, cependant, certaines règles sont à respecter pour que celui-ci puisse s'intégrer
convenablement à Comus Party.

> [!WARNING]
> Les jeux sont servis depuis l'URL `https://games.comus-party.com/{idDuJeu}`. Veillez à ce que votre jeu n'utilise pas
> d'URL absolue pour les ressources et autres redirections.

## Logique de traitement du jeu

Chaque jeu traite indépendamment sa logique interne. Cependant, deux fonctions sont obligatoires pour le bon
fonctionnement du jeu et de Comus Party :

- La fonction d'instanciation
- La fonction de terminaison

### Fonction d'instanciation

La fonction d'instanciation est appelée lors de la création d'une partie.
Elle est situé à l'endpoint **`POST`** `/{code de la partie créée}/init`.
Il reçoit en corps de requête les informations demandées par le jeu pour lancer une partie dans
`neededParametersFromComus` ainsi qu'un attribut `token` contenant le jeton de la partie. Ce jeton est à renvoyer dans
les réponses du jeu pour identifier la partie.

Si la partie est créée avec succès, la fonction doit retourner un code de statut **`200`** et le corps de réponse *(
minimum)* suivant :

```json
{
  "success": true,
  "message": "Message confirmant la création"
}
```

Dans le cas contraire, la fonction doit retourner un code de statut autre que `200` et `300` et le corps de réponse *(
minimum)* suivant :

```json
{
  "success": false,
  "message": "Message d'erreur",
  "code": "Code d'erreur interne ou, à défaut, le code de statut HTTP"
}
```

### Fonction de terminaison

> [!NOTE]
> Cette partie est en cours de rédaction.
