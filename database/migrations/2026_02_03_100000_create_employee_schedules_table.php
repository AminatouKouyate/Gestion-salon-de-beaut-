<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute la migration.
     * Crée la table des horaires de travail des employés.
     */
    public function up(): void
    {
        Schema::create('employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('day_of_week')->comment('0=dimanche, 1=lundi, ..., 6=samedi');
            $table->time('start_time')->comment('Heure de début de travail');
            $table->time('end_time')->comment('Heure de fin de travail');
            $table->time('break_start')->nullable()->comment('Début de la pause');
            $table->time('break_end')->nullable()->comment('Fin de la pause');
            $table->boolean('is_working')->default(true)->comment('Indique si l\'employé travaille ce jour');
            $table->timestamps();

            $table->unique(['employee_id', 'day_of_week']);
        });
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_schedules');
    }
};
