## À propos de Petit Prono

Petit Prono est un outil qui permet de jouer à faire des pronos sur la Coupe du Monde de football 2026.<br><br>
L'idée vient de "Mon Petit Gazon" (props to them) qui avait lancé une appli du même genre pour le Mondial 2018 mais, entre temps, celle-ci est devenue une usine à gaz et a perdu de sa simplicité/convivialité (AMHA).<br><br>
J'ai donc recréé une sorte de "Mon Petit Prono" tel qu'il était (à peu près) à l'époque, histoire d'y rejouer avec des collègues.<br>

## Bases techniques

L'application est basée sur la stack TALL (Laravel v13, Livewire v4, TailwindCSS, Alpine.js), une base de donnée relationnelle est nécessaire pour la faire fonctionner (testé avec PHP v8.4, MySQL/MariaDB et PostgreSQL), ainsi que npm.<br>
Apres avoir cloné le repo et renseigné les infos nécessaires dans le fichier `.env`, il suffit de lancer les commandes suivantes pour avoir une application fonctionnelle :
```
composer update
php artisan key:generate
php artisan migrate
php artisan optimize
npm run dev (ou npm run build)
```

Les équipes/matches/résultats sont issus et actualisés à partir des API de **[Football Data](https://www.football-data.org/)**, une clé API est donc fortement conseillée pour faire fonctionner l'appli (le plan gratuit est suffisant). Voir le paragraphe [Actualisations](https://github.com/fluktegrute/PetitProno#actualisations) pour plus d'infos<br><br>
La création de compte et l'authentification peuvent être protégées par hCaptcha. Pour que ce soit effectif, il faudra renseigner une clé de site et une clé secrète (idem qu'au dessus, le plan gratuit suffit sauf si tu comptes avoir plus d'un million de requêtes par mois). Les variables d'environnement à renseigner sont : 
- **HCAPTCHA_SITEKEY**
- **HCAPTCHA_SECRET**

## Fonctionnement

### Jeu de base
Chaque joueur peut pronostiquer le résultat d'un match et choisir de jouer un de ses boosters sur ce match. <br>
Il ne peut le faire qu'avant le début du match (une tentative de création/modification de prono après le début du match associé entraine une pénalité pour tentatvie de tricherie).<br><br>
Si son prono est bon, il gagne des points en fonction du résultat du match (il recevra plus de points pour un score exact), sinon il ne gagne pas de points.<br>
S'il a joué un booster, son score pour le match est multiplié par le multiplicateur.<br><br>
Les joueurs peuvent également pronostiquer le vainqueur final, pour un bonus de points supplémentaires en fin de tournoi. Le vainqueur final ne peut être choisi qu'une fois, et avant le debut du premier match.<br>

### Ligues
Par défaut, chaque joueur fait partie du "classement général" qui regroupe tous les joueurs inscrits.<br><br>
Un joueur peut créer des ligues qui vont regrouper les joueurs qui les rejoignent. Le score d'un joueur dans une ligue est le même que dans le classement général, seuls les "adversaires" sont limités aux participants de la ligue.<br><br>
Du fait que n'importe qui peut rejoindre n'importe quelle ligue, le joueur qui a créé la ligue a la possibilité d'en exclure des membres.<br>
Il a également la possibilité de supprimer la ligue.<br>

### Actualisations
Il existe 2 routes permettant de mettre à jour l'appli depuis Football Data (sous réserve d'avoir fourni une clé API dans la variable d'environnement **API_KEY**) :
- ***update-teams*** : pour actualiser les équipes et leur classement en poule
- ***update-matches*** : pour actualiser les matches et leur résultat, ainsi qu'attribuer les points aux joueurs suite à leurs pronos

Ces routes étant accessibles sans authentification, pour éviter les crawls intempestifs il est nécessaire d'y ajouter un paramètre d'url nommé "*token*" avec la valeur de la variable d'environnement **UPDATE_TOKEN**.

## Personnalisation

Les paramètres suivants sont personnalisables : 
- Fuseau horaire des matches (variable d'environnement **APP_TIMEZONE**), par défaut Europe/Paris
- Nombre de points gagnés par les joueurs
    - pour un score exact (variable d'environnement **EXACT_SCORE_POINTS**), par défaut 30
    - pour un prono gagné (variable d'environnement **WINNING_PRONO_POINTS**), par défaut 10
    - pour le vainqueur final (variable d'environnement **WINNER_PRONO_POINTS**), par défaut 50
    - pour le multiplicateur des boosters (variable d'environnement **BOOSTER_MULTIPLIER**), par défaut 2
    - pour une tentative de tricherie (points perdus pour le coup) (variable d'environnement **CHEATING_POINTS_TO_REMOVE**), par défaut 15
- Nombre de boosters disponibles par joueur (variable d'environnement **INITIAL_BOOSTER_QUANTITY**), par défaut 3

## Screenshots
### Vue des matches / pronostics
![pb1](https://github.com/fluktegrute/PetitProno/assets/57525938/6f895360-c1dc-4301-a9fc-7c8bdc8746b6)<br><br>
### Vue du profil avec choix du vainqueur et possibilité de créer ou rejoindre une ligue
![pb2](https://github.com/fluktegrute/PetitProno/assets/57525938/df7fbf23-4d4e-4015-bd38-ab7a09701336)<br><br>
### Vue du classement des équipes
![pb3](https://github.com/fluktegrute/PetitProno/assets/57525938/8c6ccfb6-8674-44ed-8527-f4dc79ef47aa)<br><br>
### Vue d'une ligue
![pb4](https://github.com/fluktegrute/PetitProno/assets/57525938/6ed8e748-d593-44c8-be0a-8779b4e0738a)

## Licence

PetitProno est open-source et est concédé sous licence selon les termes de la [licence MIT](https://opensource.org/licenses/MIT).<br>
En plus de pouvoir l'utiliser, copier, partager, modifier etc, reçois des gros bisous si tu as lu jusqu'ici (et si tu es d'accord).