<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Modèle représentant un service proposé par le salon de beauté.
 *
 * Un service possède un nom, une description, un prix, une durée
 * et peut avoir une promotion active. Les services sont associés
 * aux employés qui peuvent les réaliser et aux rendez-vous.
 *
 * @package App\Models
 *
 * @property int $id Identifiant unique du service
 * @property string $name Nom du service (ex: "Coiffure femme", "Manucure")
 * @property string|null $description Description détaillée du service
 * @property float $price Prix normal en FCFA
 * @property float|null $promotion_price Prix promotionnel en FCFA
 * @property \Carbon\Carbon|null $promotion_start Date de début de la promotion
 * @property \Carbon\Carbon|null $promotion_end Date de fin de la promotion
 * @property string|null $promotion_label Libellé de la promotion (ex: "Soldes d'été -30%")
 * @property int $duration Durée du service en minutes
 * @property string|null $category Catégorie du service (coiffure, manucure, massage, etc.)
 * @property string|null $gender Genre ciblé (homme, femme, mixte)
 * @property array|null $photos Tableau des URLs des photos du service
 * @property bool $active Indique si le service est actuellement proposé
 * @property \Carbon\Carbon $created_at Date de création
 * @property \Carbon\Carbon $updated_at Date de dernière modification
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Appointment[] $appointments Rendez-vous utilisant ce service
 * @property-read \Illuminate\Database\Eloquent\Collection|Employee[] $employees Employés capables de réaliser ce service
 */
class Service extends Model
{
    use HasFactory;

    /**
     * Champs autorisés pour l'assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',              // Nom du service
        'description',       // Description détaillée
        'price',             // Prix normal en FCFA
        'promotion_price',   // Prix promotionnel (si applicable)
        'promotion_start',   // Date de début de la promotion
        'promotion_end',     // Date de fin de la promotion
        'promotion_label',   // Libellé de la promotion (ex: "Soldes d'été")
        'duration',          // Durée en minutes
        'category',          // Catégorie du service (coiffure, manucure, etc.)
        'gender',            // Genre ciblé (homme, femme, mixte)
        'photos',            // Photos du service (JSON array)
        'active',            // Service actif ou non
    ];

    /**
     * Définition des conversions de types pour les attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'promotion_price' => 'decimal:2',
        'promotion_start' => 'date',
        'promotion_end' => 'date',
        'photos' => 'array',
        'active' => 'boolean',
    ];

    /**
     * Relation : Tous les rendez-vous associés à ce service.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Scope : Filtre les services actifs uniquement.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Vérifie si le service a une promotion active.
     * La promotion est active si :
     * - Un prix promotionnel est défini
     * - La date actuelle est dans la période de promotion
     *
     * @return bool True si une promotion est active
     */
    public function hasActivePromotion(): bool
    {
        // Pas de prix promotionnel défini
        if (!$this->promotion_price) {
            return false;
        }

        $today = Carbon::today();

        // Vérifier si on est avant le début de la promotion
        if ($this->promotion_start && $today->lt($this->promotion_start)) {
            return false;
        }

        // Vérifier si on est après la fin de la promotion
        if ($this->promotion_end && $today->gt($this->promotion_end)) {
            return false;
        }

        return true;
    }

    /**
     * Récupère le prix actuel du service.
     * Retourne le prix promotionnel si une promotion est active,
     * sinon retourne le prix normal.
     *
     * @return float Prix actuel en FCFA
     */
    public function getCurrentPrice(): float
    {
        return $this->hasActivePromotion() ? $this->promotion_price : $this->price;
    }

    /**
     * Calcule le pourcentage de réduction de la promotion.
     *
     * @return int|null Pourcentage de réduction ou null si pas de promotion
     */
    public function getDiscountPercentage(): ?int
    {
        if (!$this->hasActivePromotion()) {
            return null;
        }

        return round((($this->price - $this->promotion_price) / $this->price) * 100);
    }

    /**
     * Scope : Filtre les services ayant une promotion active.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPromotion($query)
    {
        return $query->whereNotNull('promotion_price')
            ->where(function ($q) {
                $q->whereNull('promotion_start')
                    ->orWhere('promotion_start', '<=', Carbon::today());
            })
            ->where(function ($q) {
                $q->whereNull('promotion_end')
                    ->orWhere('promotion_end', '>=', Carbon::today());
            });
    }

    /**
     * Relation : Les employés capables de réaliser ce service.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }
}
