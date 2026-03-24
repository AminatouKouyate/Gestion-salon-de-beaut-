<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant un produit en stock dans le salon de beauté.
 *
 * Gère l'inventaire des produits utilisés par le salon (shampoings, crèmes,
 * accessoires, etc.), avec un système d'alerte lorsque la quantité
 * descend en dessous d'un seuil défini.
 *
 * @package App\Models
 *
 * @property int $id Identifiant unique du produit
 * @property string|null $name Nom du produit (champ principal)
 * @property string|null $product_name Nom alternatif du produit (utilisé comme fallback)
 * @property string|null $category Catégorie du produit (ex: "Soins capillaires", "Manucure")
 * @property int $quantity Quantité actuelle en stock
 * @property int|null $alert_threshold Seuil d'alerte principal (quantité minimale avant réapprovisionnement)
 * @property int|null $alert_quantity Seuil d'alerte alternatif (utilisé comme fallback)
 * @property \Carbon\Carbon $created_at Date de création de l'enregistrement
 * @property \Carbon\Carbon $updated_at Date de dernière modification
 */
class Stock extends Model
{
    use HasFactory;

    /**
     * Champs autorisés pour l'assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',             // Nom principal du produit
        'product_name',     // Nom alternatif du produit (compatibilité)
        'category',         // Catégorie du produit (soins, accessoires, etc.)
        'quantity',         // Quantité disponible en stock
        'alert_threshold',  // Seuil d'alerte principal pour le réapprovisionnement
        'alert_quantity',   // Seuil d'alerte alternatif (fallback)
    ];

    /**
     * Accesseur : Récupère le nom du produit.
     *
     * Retourne le champ 'name' s'il est défini, sinon utilise 'product_name'
     * comme valeur de repli pour assurer la compatibilité avec les anciens enregistrements.
     *
     * @param string|null $value Valeur du champ 'name'
     * @return string|null
     */
    public function getNameAttribute($value)
    {
        return $value ?? $this->product_name;
    }

    /**
     * Accesseur : Récupère le seuil d'alerte du stock.
     *
     * Retourne 'alert_threshold' s'il est défini, sinon utilise 'alert_quantity'
     * comme valeur de repli, ou 0 par défaut.
     *
     * @param int|null $value Valeur du champ 'alert_threshold'
     * @return int
     */
    public function getAlertThresholdAttribute($value)
    {
        return $value ?? $this->alert_quantity ?? 0;
    }

    /**
     * Vérifie si le produit est en stock bas (quantité <= seuil d'alerte).
     *
     * Utilisé pour déclencher des alertes de réapprovisionnement.
     *
     * @return bool True si le stock est bas et nécessite un réapprovisionnement
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->alert_threshold;
    }

    /**
     * Scope : Filtre les produits dont le stock est bas.
     *
     * Sélectionne les produits dont la quantité est inférieure ou égale
     * au seuil d'alerte défini, en utilisant une comparaison de colonnes SQL.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'alert_threshold');
    }
}
