## À propos de Petit Prono

Petit Prono est un outil de pronostics pour la Coupe du Monde de football 2026, pensé pour jouer entre amis ou collègues.<br><br>
L'idée vient de "Mon Petit Gazon" (props to them) qui avait lancé une appli du même genre pour le Mondial 2018 mais, entre temps, celle-ci est devenue une usine à gaz et a perdu de sa simplicité/convivialité (AMHA).<br><br>
J'ai donc recréé une sorte de "Mon Petit Prono" tel qu'il était (à peu près) à l'époque, histoire d'y rejouer avec des collègues.

## Bases techniques

L'application est basée sur la stack TALL (Laravel v13, Livewire v4, TailwindCSS, Alpine.js) avec le composant UI [Flux](https://fluxui.dev/). Une base de données relationnelle est nécessaire pour la faire fonctionner.<br>
Des API tierces sont utilisées :
- **[Football Data](https://www.football-data.org/)** pour les résultats et le calendrier des matches
- **[The Odds API](https://the-odds-api.com/)** pour les cotes
- **[hCaptcha](https://www.hcaptcha.com/)** pour la protection du formulaire d'inscription

## Prérequis

- PHP 8.2+
- Composer
- Node.js & NPM
- Une clé API chez Football-Data (le plan gratuit est suffisant)
- Une clé API chez The Odds API (le plan gratuit est suffisant)
- Un couple sitekey / secret chez hCaptcha (le plan gratuit est suffisant)

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
HCAPTCHA_SITEKEY=votre_sitekey_hcaptcha
HCAPTCHA_SECRET=votre_secret_hcaptcha
APP_TIMEZONE="Europe/Paris"
```
- Lancer les migrations et lier le stockage
```
php artisan migrate
php artisan storage:link
```

## Fonctionnement

### Jeu de base
Chaque joueur peut pronostiquer le score exact d'un match et choisir d'y jouer un de ses boosters.<br>
Les pronostics ne sont modifiables qu'avant le coup d'envoi : toute tentative de création ou modification après le début du match est détectée et entraîne une pénalité pour tentative de triche.<br><br>
Si le prono est exact (score parfait), le joueur gagne davantage de points. S'il a pronostiqué la bonne tendance (victoire, nul ou défaite), il gagne des points. Sinon, il n'en gagne pas.<br>
Les points attribués tiennent compte des cotes du match si elles sont disponibles.<br>
Si le joueur a activé un booster sur ce match, son score est multiplié par le multiplicateur configuré.

### Poney (vainqueur final)
Chaque joueur peut désigner son "poney", c'est-à-dire l'équipe qu'il pense vainqueur du tournoi.<br>
Ce choix n'est possible qu'avant le début du premier match et ne peut être fait qu'une seule fois.<br>
Si l'équipe choisie remporte le tournoi, le joueur reçoit un bonus de points en fin de compétition.

### Boosters
Chaque joueur dispose d'un nombre limité de boosters (3 par défaut). Jouer un booster sur un match multiplie le score obtenu pour ce match par le multiplicateur configuré (×2 par défaut). Les boosters non utilisés sont perdus en fin de tournoi.

### Ligues
Par défaut, chaque joueur fait partie du "classement général" qui regroupe tous les inscrits.<br><br>
Un joueur peut créer des ligues privées pour n'affronter qu'un groupe restreint. Le score dans une ligue est identique au score global : seul le périmètre des adversaires change.<br><br>
Pour rejoindre une ligue, il suffit de saisir le code d'invitation fourni au créateur lors de la création. Chaque ligue dispose d'un espace de discussion (messagerie) réservé à ses membres.

### Classements et statistiques
- **Classement général** : toutes les équipes triées selon les règles FIFA (points, différence de buts, buts marqués, résultats directs)
- **Bracket de phase finale** : visualisation du tableau des 16 équipes qualifiées jusqu'à la finale
- **Statistiques H2H** : historique des confrontations directes entre deux équipes, consultable depuis la page des matches
- **Tableau de bord** : récapitulatif personnel — mes stats (total de points, pronos exacts, pronos gagnants), prochains matches à pronostiquer, classement des joueurs dans mes ligues, derniers résultats, mon poney et mes boosters restants

### Profil utilisateur
Chaque joueur peut gérer son profil : photo de profil (avatar), mot de passe, authentification à deux facteurs (2FA), vérification d'e-mail et suppression de compte.

### Administration
Un panneau d'administration (accessible aux comptes avec le rôle `admin`) permet de gérer les données de l'application.

### Actualisations
Il existe plusieurs commandes Artisan permettant de mettre à jour l'appli :
- `php artisan matches:update` : actualise les matches, les équipes et leur classement en poule, et recalcule les points de tous les joueurs
- `php artisan odds:update` : actualise les cotes des matches (moyennées sur plusieurs bookmakers)
- `php artisan h2h:import` : importe l'historique des confrontations directes depuis un fichier CSV (option `--dateFrom=YYYY-MM-DD` pour filtrer par date)

Ces commandes sont conçues pour être planifiées (cron) afin que l'appli reste à jour automatiquement.

## Personnalisation

Les paramètres suivants sont personnalisables via des variables d'environnement :

| Variable | Défaut | Description |
|---|---|---|
| `APP_TIMEZONE` | `Europe/Paris` | Fuseau horaire d'affichage des matches |
| `EXACT_SCORE_POINTS` | `30` | Points pour un score exact |
| `WINNING_PRONO_POINTS` | `10` | Points pour une bonne tendance |
| `WINNER_PRONO_POINTS` | `50` | Bonus de points pour le vainqueur final |
| `BOOSTER_MULTIPLIER` | `2` | Multiplicateur appliqué par un booster |
| `INITIAL_BOOSTER_QUANTITY` | `3` | Nombre de boosters par joueur |
| `CHEATING_POINTS_TO_REMOVE` | `15` | Points retirés pour tentative de triche |

## Licence

PetitProno est open-source et est concédé sous licence selon les termes de la [licence MIT](https://opensource.org/licenses/MIT).<br>
En plus de pouvoir l'utiliser, copier, partager, modifier etc, reçois des gros bisous si tu as lu jusqu'ici (et si tu es d'accord).
