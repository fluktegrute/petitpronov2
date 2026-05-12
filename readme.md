## À propos de Petit Prono

Petit Prono est un outil qui permet de jouer à faire des pronos sur la Coupe du Monde de football 2026.<br><br>
L'idée vient de "Mon Petit Gazon" (props to them) qui avait lancé une appli du même genre pour le Mondial 2018 mais, entre temps, celle-ci est devenue une usine à gaz et a perdu de sa simplicité/convivialité (AMHA).<br><br>
J'ai donc recréé une sorte de "Mon Petit Prono" tel qu'il était (à peu près) à l'époque, histoire d'y rejouer avec des collègues.<br>

## Bases techniques

L'application est basée sur la stack TALL (Laravel v13, Livewire v4, TailwindCSS, Alpine.js), une base de donnée relationnelle est nécessaire pour la faire fonctionner.<br>
Des API tierces sont utilisées : 
- **[Football Data](https://www.football-data.org/)** pour les résultats et le calendrier des matches
- **[The Odds API](https://the-odds-api.com/)** pour les cotes

## Prérequis

- PHP 8.2+
- Composer 
- Node.js & NPM
- Une clé API chez Football-Data (le plan gratuit est suffisant)
- Une clé API chez The Odds API (le plan gratuit est suffisant)

## Installation

- Cloner le repo
- Installer les dépendances
```
composer install
npm install && npm run dev (ou npm run build)
```
- Configurer l'environnement
```
cp .env.example .env
php artisan key:generate
```
- Paramétrer la BdD dans le `.env` ainsi que les clés API et la timezone (pour avoir les matches à la bonne heure)
```
API_KEY=votre_clé_football_data
ODDS_API_KEY=votre_clé_the_odds_api
APP_TIMEZONE="Europe/Paris"
```
- Lancer les migrations et lier le stockage
```
php artisan migrate
php artisan storage:link
```

## Fonctionnement

### Jeu de base
Chaque joueur peut pronostiquer le résultat d'un match et choisir de jouer un de ses boosters sur ce match. <br>
Il ne peut le faire qu'avant le début du match (une tentative de création/modification de prono après le début du match associé entraine une pénalité pour tentatvie de tricherie).<br><br>
Si son prono est bon, il gagne des points en fonction du résultat du match et de la cote, sinon il ne gagne pas de points.<br>
S'il a joué un booster, son score pour le match est multiplié par le multiplicateur.<br><br>
Les joueurs peuvent également pronostiquer le vainqueur final, pour un bonus de points supplémentaires en fin de tournoi. Le vainqueur final ne peut être choisi qu'une fois, et avant le debut du premier match.<br>

### Ligues
Par défaut, chaque joueur fait partie du "classement général" qui regroupe tous les joueurs inscrits.<br><br>
Un joueur peut créer des ligues qui vont regrouper les joueurs qui les rejoignent. Le score d'un joueur dans une ligue est le même que dans le classement général, seuls les "adversaires" sont limités aux participants de la ligue.<br><br>
Pour rejoindre une ligue, il suffit de renseigner le code qui a été fourni au créateur de la ligue au moment de sa création.<br>

### Actualisations
Il existe plusieurs commandes Artisan permettant de mettre à jour l'appli :
- `php artisan matches:update` : pour actualiser les matches, les équipes et leur classement en poule, ainsi que les points des joueurs
- `php artisan odds:update` : pour actualiser les cotes des matches
- `php artisan h2h:import` : pour importer l'historique des matches (permet d'afficher les H2H et l'historique récent des Nations)

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

## TODO
- Update des points dans README (avec les cotes)
- Screenshots du README
- Intégration hCaptcha
- Visuel page de login

## Licence

PetitProno est open-source et est concédé sous licence selon les termes de la [licence MIT](https://opensource.org/licenses/MIT).<br>
En plus de pouvoir l'utiliser, copier, partager, modifier etc, reçois des gros bisous si tu as lu jusqu'ici (et si tu es d'accord).