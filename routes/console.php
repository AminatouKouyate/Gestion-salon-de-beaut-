<?php

/**
 * Définition des commandes Artisan console et des tâches planifiées.
 *
 * Ce fichier enregistre les commandes console personnalisées
 * et configure le planificateur de tâches (scheduler) de l'application.
 */

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/**
 * Commande "inspire" : affiche une citation inspirante dans la console.
 */
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Tâche planifiée : envoi des rappels de rendez-vous tous les jours à 9h00.
 * Notifie les clients dont le rendez-vous est prévu le lendemain.
 */
Schedule::command('appointments:send-reminders')->dailyAt('09:00');
