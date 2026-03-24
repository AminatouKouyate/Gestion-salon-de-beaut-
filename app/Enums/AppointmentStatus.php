<?php

namespace App\Enums;

/**
 * Énumération des statuts possibles d'un rendez-vous.
 *
 * Représente les différents états du cycle de vie d'un rendez-vous
 * dans le salon de beauté : en attente, confirmé, terminé, annulé ou absent.
 * Chaque statut dispose d'un libellé en français et d'une classe CSS Bootstrap.
 */
enum AppointmentStatus: string
{
    /** Rendez-vous en attente de confirmation */
    case Pending = 'pending';

    /** Rendez-vous confirmé par le salon */
    case Confirmed = 'confirmed';

    /** Rendez-vous terminé (prestation effectuée) */
    case Completed = 'completed';

    /** Rendez-vous annulé par le client ou le salon */
    case Canceled = 'canceled';

    /** Client absent au rendez-vous */
    case NoShow = 'no-show';

    /**
     * Retourne le libellé en français du statut.
     *
     * @return string  Le libellé traduit en français
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'En attente',
            self::Confirmed => 'Confirmé',
            self::Completed => 'Terminé',
            self::Canceled => 'Annulé',
            self::NoShow => 'Absent',
        };
    }

    /**
     * Retourne la classe CSS Bootstrap pour le badge de statut.
     *
     * Utilisée pour colorer les badges d'affichage du statut
     * dans les vues Blade (warning, info, success, danger, secondary).
     *
     * @return string  Le nom de la classe CSS Bootstrap
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Confirmed => 'info',
            self::Completed => 'success',
            self::Canceled => 'danger',
            self::NoShow => 'secondary',
        };
    }
}
