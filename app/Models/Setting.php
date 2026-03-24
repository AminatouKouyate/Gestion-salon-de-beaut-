<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Modèle représentant les paramètres globaux de l'application.
 *
 * Gère les préférences de thème (couleur et mode sombre) configurées
 * par l'administrateur. Utilise un cache de 60 secondes pour éviter
 * les requêtes répétitives à la base de données.
 *
 * @package App\Models
 *
 * @property int $id Identifiant unique
 * @property string $color_theme Thème de couleur actif (ex: "rose-gold", "ocean-blue")
 * @property bool $dark_mode Indique si le mode sombre est activé
 * @property \Carbon\Carbon $created_at Date de création
 * @property \Carbon\Carbon $updated_at Date de dernière modification
 */
class Setting extends Model
{
    /**
     * Champs autorisés pour l'assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = ['color_theme', 'dark_mode'];

    /**
     * Conversions de types pour les attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dark_mode' => 'boolean',
    ];

    /**
     * Récupère l'instance unique des paramètres avec mise en cache.
     *
     * Utilise un cache de 60 secondes pour éviter les requêtes répétitives.
     * Si aucun enregistrement n'existe, retourne une instance par défaut.
     *
     * @return self
     */
    public static function instance(): self
    {
        return Cache::remember('app_settings', 60, function () {
            return static::first() ?? new static(['color_theme' => 'rose-gold', 'dark_mode' => false]);
        });
    }

    /**
     * Invalide le cache des paramètres.
     *
     * Doit être appelé après chaque modification des paramètres
     * pour garantir la cohérence des données affichées.
     *
     * @return void
     */
    public static function clearCache(): void
    {
        Cache::forget('app_settings');
    }
}
