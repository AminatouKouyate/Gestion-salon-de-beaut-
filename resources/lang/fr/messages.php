<?php

/**
 * Traductions françaises pour les messages personnalisés de l'application.
 * Utilisés dans les contrôleurs et les vues.
 */

return [
    // Messages de succès
    'success' => [
        'created' => ':item créé(e) avec succès.',
        'updated' => ':item mis(e) à jour avec succès.',
        'deleted' => ':item supprimé(e) avec succès.',
        'saved' => 'Enregistrement effectué avec succès.',
    ],
    
    // Messages d'erreur
    'error' => [
        'not_found' => ':item introuvable.',
        'unauthorized' => 'Vous n\'êtes pas autorisé à effectuer cette action.',
        'already_exists' => ':item existe déjà.',
        'cannot_delete' => 'Impossible de supprimer :item.',
        'invalid_data' => 'Les données fournies sont invalides.',
    ],
    
    // Rendez-vous
    'appointment' => [
        'created' => 'Rendez-vous réservé avec succès.',
        'updated' => 'Rendez-vous modifié avec succès.',
        'canceled' => 'Rendez-vous annulé avec succès.',
        'deleted' => 'Rendez-vous supprimé avec succès.',
        'already_paid' => 'Ce rendez-vous a déjà été payé.',
        'cannot_modify' => 'Ce rendez-vous ne peut plus être modifié.',
        'cannot_cancel' => 'Ce rendez-vous ne peut plus être annulé.',
        'no_slot_available' => 'Aucun créneau disponible pour cet horaire.',
        'slot_taken' => 'Ce créneau est déjà réservé. Veuillez choisir un autre horaire.',
    ],
    
    // Paiements
    'payment' => [
        'created' => 'Paiement enregistré avec succès.',
        'pending' => 'Paiement en attente de validation.',
        'completed' => 'Paiement effectué avec succès.',
        'failed' => 'Le paiement a échoué. Veuillez réessayer.',
        'pay_at_salon' => 'Paiement enregistré - à régler au salon.',
    ],
    
    // Utilisateurs
    'user' => [
        'profile_updated' => 'Profil mis à jour avec succès.',
        'password_updated' => 'Mot de passe modifié avec succès.',
        'account_deactivated' => 'Votre compte a été désactivé.',
        'login_failed' => 'Identifiants incorrects.',
    ],
    
    // Congés
    'leave' => [
        'submitted' => 'Demande de congé soumise avec succès.',
        'approved' => 'Demande de congé approuvée.',
        'rejected' => 'Demande de congé refusée.',
        'already_processed' => 'Cette demande a déjà été traitée.',
    ],
    
    // Notifications
    'notification' => [
        'marked_read' => 'Notification marquée comme lue.',
        'all_marked_read' => 'Toutes les notifications ont été marquées comme lues.',
    ],
    
    // Statuts
    'status' => [
        'pending' => 'En attente',
        'confirmed' => 'Confirmé',
        'completed' => 'Terminé',
        'canceled' => 'Annulé',
        'paid' => 'Payé',
        'failed' => 'Échoué',
        'processing' => 'En cours',
        'approved' => 'Approuvé',
        'rejected' => 'Refusé',
        'active' => 'Actif',
        'inactive' => 'Inactif',
    ],
    
    // Jours de la semaine
    'days' => [
        'monday' => 'Lundi',
        'tuesday' => 'Mardi',
        'wednesday' => 'Mercredi',
        'thursday' => 'Jeudi',
        'friday' => 'Vendredi',
        'saturday' => 'Samedi',
        'sunday' => 'Dimanche',
    ],
    
    // Mois
    'months' => [
        'january' => 'Janvier',
        'february' => 'Février',
        'march' => 'Mars',
        'april' => 'Avril',
        'may' => 'Mai',
        'june' => 'Juin',
        'july' => 'Juillet',
        'august' => 'Août',
        'september' => 'Septembre',
        'october' => 'Octobre',
        'november' => 'Novembre',
        'december' => 'Décembre',
    ],
    
    // Boutons et actions
    'actions' => [
        'save' => 'Enregistrer',
        'cancel' => 'Annuler',
        'delete' => 'Supprimer',
        'edit' => 'Modifier',
        'view' => 'Voir',
        'add' => 'Ajouter',
        'search' => 'Rechercher',
        'filter' => 'Filtrer',
        'export' => 'Exporter',
        'import' => 'Importer',
        'back' => 'Retour',
        'next' => 'Suivant',
        'previous' => 'Précédent',
        'confirm' => 'Confirmer',
        'submit' => 'Soumettre',
        'login' => 'Se connecter',
        'logout' => 'Se déconnecter',
        'register' => 'S\'inscrire',
    ],
    
    // Labels généraux
    'labels' => [
        'name' => 'Nom',
        'email' => 'Email',
        'phone' => 'Téléphone',
        'address' => 'Adresse',
        'password' => 'Mot de passe',
        'password_confirm' => 'Confirmer le mot de passe',
        'date' => 'Date',
        'time' => 'Heure',
        'status' => 'Statut',
        'actions' => 'Actions',
        'description' => 'Description',
        'price' => 'Prix',
        'duration' => 'Durée',
        'category' => 'Catégorie',
        'amount' => 'Montant',
        'method' => 'Méthode',
        'notes' => 'Notes',
        'reason' => 'Motif',
    ],
];
