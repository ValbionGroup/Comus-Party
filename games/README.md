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
    "type": "Type du jeu"
  },
  "settings": {
    "isNode": true,
    "nodeServer": "URL du serveur Node",
    "chatEnabled": true
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

##### `neededParametersFromComus` et `returnParametersToComus`

Liste des paramètres possibles pour `neededParametersFromComus` :

- `MODIFIED_SETTING_DATA` : Liste des paramètres modifiés par l'hôte de la partie

Liste des paramètres possibles pour `returnParametersToComus` :

- `WINNER` : UUID du/des gagnants de la partie
- `SCORE` : Score des joueurs

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