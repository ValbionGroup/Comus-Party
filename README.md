<p align="center">
  <img width="200" src="" alt="Logo">
  <h1 align="center">Comus Party</h1>
  <h3 align="center">Application de mini-jeux en ligne</h3>
</p>
<p align="center">
  <img src="https://img.shields.io/github/actions/workflow/status/ValbionGroup/Comus-Party/tests.yml?label=Test&style=for-the-badge" alt="Test"><br/>
  <img src="https://img.shields.io/github/issues-pr/ValbionGroup/Comus-Party?label=Pull%20Request&style=flat-square" alt="Pull Request">
  <img src="https://img.shields.io/github/stars/ValbionGroup/Comus-Party?color=dark%20green&style=flat-square" alt="Stars">
  <img src="https://img.shields.io/codefactor/grade/github/ValbionGroup/Comus-Party/main?label=Qualite%20de%20Code&style=flat-square" alt="Qualité de Code">
</p>

---
## Informations

Comus Party est une application de mini-jeux en ligne, elle est développée dans le cadre de la SAE 3.01 du BUT Informatique à l'IUT de Bayonne et du Pays Basque.

### Fonctionnalités

* **Interface pour chaque type d'utilisateur**
* **Mode sombre/clair**
* ...

### Interface



### Informations techniques

Comus Party fonctionne avec Tailwind CSS pour l'interface ainsi que Twig en tant que framework. Les fichiers du site sont donc des fichiers twig (`fichier.twig`) et le reste en PHP (`fichier.php`).<br/>
Le projet comporte aussi des fichiers JavaScript pour les fonctionnalités dynamiques.

Le projet suit une architecture MVC (Modèle-Vue-Contrôleur) qui garantit une séparation claire des responsabilités, avec
une couche Modèle (`Models`) gérant les données, une couche Vue (`templates`) pour l'interface utilisateur, et une
couche Contrôleur (`Controllers`) assurant la coordination entre les deux. Cette organisation modulaire facilite la
maintenance et l'évolution du code tout en respectant les bonnes pratiques de développement.

#### Développer

Afin de développer sur Comus Party, il vous faudra suivre les étapes ci-dessous :

1. Cloner le projet
```bash
git clone https://github.com/ValbionGroup/Comus-Party.git
```

2. Copier le fichier `.env.example` puis renomer-le en `.env` et modifier les informations de connexion à la base de données ainsi que les autres informations nécessaires.

3. Créer la base de données et les tables nécessaires en respectant le schéma de la base de données. Vous pouvez utiliser le fichier `comusparty_export.sql` pour cela.

> [!WARNING]  
> Ce fichier insère des données, il est donc préférable de le modifier pour qu'il n'insère pas de données si vous souhaitez avoir une base de données vide.

4. Ensuite faites les commandes ci-dessous afin d'initialiser le projet :
```bash
composer install
npm install
npm run build # Si en production
npm run watch # Si en développement
```