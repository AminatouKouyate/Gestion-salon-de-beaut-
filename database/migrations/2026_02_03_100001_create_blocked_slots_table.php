<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute la migration.
     * Crée la table des créneaux bloqués (indisponibilités ponctuelles).
     */
    public function up(): void
    {
        Schema::create('blocked_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')
                ->comment('NULL = blocage global pour tout le salon');
            $table->datetime('start_datetime')->comment('Début du blocage');
            $table->datetime('end_datetime')->comment('Fin du blocage');
            $table->string('reason')->nullable()->comment('Raison du blocage');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()
                ->comment('Utilisateur ayant créé le blocage');
            $table->timestamps();

            $table->index(['employee_id', 'start_datetime', 'end_datetime']);
        });
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_slots');
    }
};
