<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Crée la table des paramètres globaux de l'application.
     *
     * Stocke les préférences de thème (couleur et mode sombre)
     * configurables par l'administrateur.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('color_theme', 30)->default('rose-gold');
            $table->boolean('dark_mode')->default(false);
            $table->timestamps();
        });

        // Insérer la ligne de paramètres par défaut
        DB::table('settings')->insert([
            'color_theme' => 'rose-gold',
            'dark_mode' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Supprime la table des paramètres.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
