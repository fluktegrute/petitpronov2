<?php

use Illuminate\Support\Facades\Schedule;

// Rappel 12h avant les matchs sans prono (toutes les heures)
Schedule::command('notifications:match-reminders')->hourly();

// Récap quotidien des résultats + classement (chaque soir à 22h)
Schedule::command('notifications:daily-recap')->dailyAt('22:00');
