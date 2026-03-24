<?php
/**
 * Script pour ajouter des commentaires français à tous les fichiers Blade
 * (hors dossier admin/ qui est traité séparément)
 */

$basePath = 'c:/Users/XPS/Documents/salon2/resources/views';

// Mapping: chemin relatif => [description, détail]
$comments = [
    // ============ CLIENTS / APPOINTMENTS ============
    'clients/appointments/calendar.blade.php' => [
        'Calendrier des rendez-vous client',
        'Affiche un calendrier interactif (FullCalendar) avec tous les rendez-vous du client, une légende des statuts et un modal de détails au clic sur un événement.'
    ],
    'clients/appointments/create.blade.php' => [
        'Création d\'un nouveau rendez-vous client',
        'Formulaire en 3 étapes : choix du service, sélection de la date et du créneau horaire (chargement AJAX), notes optionnelles. Inclut un récapitulatif latéral et une barre de recherche de services.'
    ],
    'clients/appointments/edit.blade.php' => [
        'Modification d\'un rendez-vous existant',
        'Formulaire de modification en 3 étapes identique à la création, pré-rempli avec les données du rendez-vous actuel. Permet de changer le service, la date, le créneau et les notes.'
    ],
    'clients/appointments/history.blade.php' => [
        'Historique des rendez-vous du client',
        'Affiche un tableau paginé de tous les rendez-vous passés avec la date, le service, le spécialiste, le prix, le statut et les actions (voir, télécharger facture).'
    ],
    'clients/appointments/index.blade.php' => [
        'Liste des rendez-vous du client',
        'Page principale des rendez-vous : statistiques rapides (à venir, en attente, terminés), cartes de rendez-vous avec actions (voir, modifier, annuler, payer) et lien vers l\'historique.'
    ],
    'clients/appointments/show.blade.php' => [
        'Détails d\'un rendez-vous client',
        'Affiche les informations complètes d\'un rendez-vous : service, date, heure, prix, spécialiste, notes, informations de paiement associé et actions disponibles (modifier, annuler, payer).'
    ],

    // ============ CLIENTS / CHATBOT ============
    'clients/chatbot/history.blade.php' => [
        'Historique des conversations du chatbot',
        'Affiche l\'historique des échanges entre le client et l\'assistant IA, groupés par date avec les messages utilisateur et les réponses du bot.'
    ],
    'clients/chatbot/index.blade.php' => [
        'Interface du chatbot - Assistant IA',
        'Interface de conversation en temps réel avec l\'assistant IA du salon. Inclut des suggestions rapides, des questions fréquentes, l\'envoi de messages via AJAX et l\'affichage des réponses avec formatage Markdown.'
    ],

    // ============ CLIENTS / NOTIFICATIONS ============
    'clients/notifications/index.blade.php' => [
        'Notifications du client',
        'Liste paginée des notifications du client avec icônes par type (rappel, confirmation, paiement, fidélité, promotion), marquage comme lu individuel ou global.'
    ],

    // ============ CLIENTS / PAYMENTS ============
    'clients/payments/create.blade.php' => [
        'Création d\'un paiement client',
        'Formulaire de paiement en 2 étapes : sélection du rendez-vous à payer et choix du mode de paiement (Stripe, PayPal, Orange Money, Wave, au salon). Récapitulatif latéral avec montant.'
    ],
    'clients/payments/index.blade.php' => [
        'Liste des paiements du client',
        'Historique complet des paiements avec statistiques (total, montant payé, en attente), tableau responsive avec vue desktop et cartes mobiles, actions (voir, facture, télécharger PDF, payer).'
    ],
    'clients/payments/invoice.blade.php' => [
        'Facture de paiement - Vue imprimable',
        'Page de facture autonome (sans layout) avec en-tête entreprise, informations client, détails du rendez-vous, tableau des services, totaux avec TVA, mode de paiement et boutons d\'impression/téléchargement.'
    ],
    'clients/payments/invoice-pdf.blade.php' => [
        'Facture de paiement - Version PDF',
        'Template de facture optimisé pour la génération PDF (DomPDF) avec mise en page simplifiée, informations du salon et du client, tableau des services et total.'
    ],
    'clients/payments/mobile.blade.php' => [
        'Paiement mobile (Orange Money / Wave)',
        'Page de paiement mobile permettant au client de finaliser un paiement via Orange Money ou Wave avec saisie du numéro de téléphone.'
    ],
    'clients/payments/show.blade.php' => [
        'Détails d\'un paiement client',
        'Affiche les informations complètes d\'un paiement : montant, méthode, date, service, spécialiste, transaction ID, rendez-vous associé et actions (payer, télécharger facture).'
    ],

    // ============ CLIENTS / SERVICE ============
    'clients/service/index.blade.php' => [
        'Catalogue des services - Espace client',
        'Liste filtrée des services du salon avec filtres par catégorie, prix, durée et genre. Cartes de services avec photos (galerie modal), promotions, prix et bouton de réservation.'
    ],
    'clients/service/public.blade.php' => [
        'Catalogue des services - Page publique',
        'Page publique accessible sans connexion affichant les services groupés par catégorie avec photos, prix et boutons d\'inscription/connexion pour réserver.'
    ],
    'clients/service/show.blade.php' => [
        'Détails d\'un service',
        'Page de détail d\'un service avec galerie de photos (navigation par flèches et swipe), description, prix, durée, promotions actives, employés disponibles et bouton de réservation.'
    ],

    // ============ CLIENTS (racine) ============
    'clients/client-register.blade.php' => [
        'Inscription client (formulaire simple)',
        'Formulaire d\'inscription client basique avec nom, email, mot de passe, téléphone et adresse. Utilise le layout dashboard.'
    ],
    'clients/create.blade.php' => [
        'Prise de rendez-vous (formulaire simplifié)',
        'Formulaire simplifié de prise de rendez-vous avec sélection de service, date et heure. Version basique utilisant le layout master.'
    ],
    'clients/dashboard.blade.php' => [
        'Tableau de bord client',
        'Page d\'accueil du client connecté : message de bienvenue, actions rapides (RDV, services, paiements, profil), statistiques (points fidélité, RDV à venir, total, dépenses), prochains rendez-vous, derniers paiements, programme fidélité et assistant IA.'
    ],
    'clients/edit.blade.php' => [
        'Modification d\'un client (admin)',
        'Formulaire de modification des informations d\'un client (nom, email, téléphone) par l\'administrateur.'
    ],
    'clients/index.blade.php' => [
        'Liste des clients (admin)',
        'Tableau paginé de tous les clients enregistrés avec nom, email, téléphone, date d\'inscription et actions (voir, modifier).'
    ],
    'clients/profile.blade.php' => [
        'Profil du client',
        'Page de gestion du profil client : photo, informations personnelles, allergies/sensibilités, changement de mot de passe, programme de fidélité (points, niveau, progression), statistiques et zone de désactivation du compte.'
    ],
    'clients/show.blade.php' => [
        'Détails d\'un client (admin)',
        'Affiche les informations détaillées d\'un client pour l\'administrateur.'
    ],

    // ============ EMPLOYEE ============
    'employee/appointments/calendar.blade.php' => [
        'Calendrier des rendez-vous employé',
        'Affiche un calendrier interactif avec les rendez-vous assignés à l\'employé connecté.'
    ],
    'employee/appointments/history.blade.php' => [
        'Historique des rendez-vous employé',
        'Liste paginée des rendez-vous passés de l\'employé avec détails et statuts.'
    ],
    'employee/appointments/index.blade.php' => [
        'Liste des rendez-vous employé',
        'Page principale des rendez-vous de l\'employé : liste des rendez-vous à venir et en cours avec actions disponibles.'
    ],
    'employee/appointments/show.blade.php' => [
        'Détails d\'un rendez-vous employé',
        'Affiche les informations complètes d\'un rendez-vous pour l\'employé : client, service, horaire, statut et actions de gestion.'
    ],
    'employee/dashboard.blade.php' => [
        'Tableau de bord employé',
        'Page d\'accueil de l\'employé connecté : statistiques, rendez-vous du jour, planning de la semaine et notifications récentes.'
    ],
    'employee/leaves/create.blade.php' => [
        'Demande de congé employé',
        'Formulaire de création d\'une demande de congé avec sélection des dates, type de congé et motif.'
    ],
    'employee/leaves/index.blade.php' => [
        'Liste des congés employé',
        'Affiche l\'historique des demandes de congé de l\'employé avec statuts (en attente, approuvé, refusé).'
    ],
    'employee/messages/create.blade.php' => [
        'Nouveau message employé',
        'Formulaire d\'envoi d\'un nouveau message à l\'administration du salon.'
    ],
    'employee/messages/index.blade.php' => [
        'Messagerie employé',
        'Liste des messages échangés entre l\'employé et l\'administration du salon.'
    ],
    'employee/messages/show.blade.php' => [
        'Détails d\'un message employé',
        'Affiche le contenu complet d\'un message avec l\'historique de la conversation.'
    ],
    'employee/notifications/index.blade.php' => [
        'Notifications de l\'employé',
        'Liste des notifications de l\'employé avec marquage comme lu.'
    ],
    'employee/password/edit.blade.php' => [
        'Changement de mot de passe employé',
        'Formulaire de modification du mot de passe de l\'employé avec vérification de l\'ancien mot de passe.'
    ],
    'employee/payments/create.blade.php' => [
        'Enregistrement d\'un paiement (employé)',
        'Formulaire permettant à l\'employé d\'enregistrer un paiement pour un rendez-vous terminé.'
    ],
    'employee/payments/history.blade.php' => [
        'Historique des paiements (employé)',
        'Liste des paiements enregistrés par l\'employé ou liés à ses rendez-vous.'
    ],
    'employee/payments/index.blade.php' => [
        'Liste des paiements (employé)',
        'Vue des paiements récents avec filtres et statistiques pour l\'employé.'
    ],
    'employee/payments/show.blade.php' => [
        'Détails d\'un paiement (employé)',
        'Affiche les informations complètes d\'un paiement : montant, méthode, statut et rendez-vous associé.'
    ],
    'employee/profile/index.blade.php' => [
        'Profil de l\'employé',
        'Page de gestion du profil employé : informations personnelles, spécialités, photo et statistiques.'
    ],
    'employee/schedule/history.blade.php' => [
        'Historique des plannings employé',
        'Affiche l\'historique des plannings passés de l\'employé.'
    ],
    'employee/schedule/index.blade.php' => [
        'Planning de l\'employé',
        'Vue du planning actuel de l\'employé avec les créneaux de travail et les rendez-vous.'
    ],
    'employee/schedules/days-off.blade.php' => [
        'Jours de repos de l\'employé',
        'Gestion des jours de repos et d\'indisponibilité de l\'employé.'
    ],
    'employee/schedules/index.blade.php' => [
        'Gestion des horaires employé',
        'Vue d\'ensemble des horaires de travail de l\'employé avec possibilité de modification.'
    ],
    'employee/schedules/working-hours.blade.php' => [
        'Heures de travail de l\'employé',
        'Configuration des heures de travail quotidiennes de l\'employé (heure de début et de fin par jour).'
    ],
    'employee/services/index.blade.php' => [
        'Services de l\'employé',
        'Liste des services que l\'employé est habilité à effectuer avec les détails de chaque prestation.'
    ],

    // ============ AUTH ============
    'auth/403.blade.php' => [
        'Page d\'erreur 403 - Accès interdit (auth)',
        'Page d\'erreur autonome affichée lorsqu\'un utilisateur tente d\'accéder à une ressource non autorisée dans l\'espace d\'authentification.'
    ],
    'auth/login.blade.php' => [
        'Page de connexion administrateur',
        'Formulaire de connexion pour l\'administrateur du salon avec email, mot de passe et lien de récupération.'
    ],
    'auth/register.blade.php' => [
        'Page d\'inscription (générale)',
        'Formulaire d\'inscription générale avec nom, email, mot de passe et confirmation.'
    ],
    'auth/welcome.blade.php' => [
        'Page d\'accueil publique du salon',
        'Landing page du salon KAARJA Beauté avec présentation des services, témoignages, galerie et appels à l\'action pour les différents espaces (client, employé, admin).'
    ],
    'auth/layout.blade.php' => [
        'Layout d\'authentification',
        'Template de base pour toutes les pages d\'authentification : structure en deux colonnes (panneau décoratif gauche et formulaire droit), styles communs et scripts partagés.'
    ],
    'auth/client-login.blade.php' => [
        'Page de connexion client',
        'Formulaire de connexion pour les clients du salon avec email, mot de passe, option "se souvenir de moi" et liens vers l\'inscription et la récupération de mot de passe.'
    ],
    'auth/client-register.blade.php' => [
        'Page d\'inscription client',
        'Formulaire d\'inscription pour les nouveaux clients avec nom, email, téléphone, mot de passe et confirmation.'
    ],
    'auth/client-forgot-password.blade.php' => [
        'Mot de passe oublié - Client',
        'Formulaire de demande de réinitialisation de mot de passe pour les clients avec saisie de l\'email.'
    ],
    'auth/client-reset-password.blade.php' => [
        'Réinitialisation du mot de passe - Client',
        'Formulaire de création d\'un nouveau mot de passe pour les clients après validation du lien de réinitialisation.'
    ],
    'auth/employee-login.blade.php' => [
        'Page de connexion employé',
        'Formulaire de connexion pour les employés du salon avec email, mot de passe et lien de récupération.'
    ],
    'auth/employee-forgot-password.blade.php' => [
        'Mot de passe oublié - Employé',
        'Formulaire de demande de réinitialisation de mot de passe pour les employés.'
    ],
    'auth/employee-reset-password.blade.php' => [
        'Réinitialisation du mot de passe - Employé',
        'Formulaire de création d\'un nouveau mot de passe pour les employés après validation du lien.'
    ],
    'auth/admin-forgot-password.blade.php' => [
        'Mot de passe oublié - Administrateur',
        'Formulaire de demande de réinitialisation de mot de passe pour l\'administrateur.'
    ],
    'auth/admin-reset-password.blade.php' => [
        'Réinitialisation du mot de passe - Administrateur',
        'Formulaire de création d\'un nouveau mot de passe pour l\'administrateur après validation du lien.'
    ],

    // ============ LAYOUTS ============
    'layouts/admin-master.blade.php' => [
        'Layout principal - Espace Administration',
        'Template de base pour toutes les pages d\'administration : en-tête HTML, chargement des polices et CSS, thèmes de couleurs (rose-gold, ocean-blue, emerald, royal-purple, sunset), barre de navigation, sidebar, gestion du thème sombre et scripts globaux.'
    ],
    'layouts/client-master.blade.php' => [
        'Layout principal - Espace Client',
        'Template de base pour toutes les pages de l\'espace client : en-tête HTML, polices, thèmes de couleurs, barre de navigation avec notifications, menu latéral responsive et scripts globaux.'
    ],
    'layouts/employee-master.blade.php' => [
        'Layout principal - Espace Employé',
        'Template de base pour toutes les pages de l\'espace employé : en-tête HTML, polices, thèmes de couleurs, barre de navigation, menu latéral et scripts globaux.'
    ],
    'layouts/master.blade.php' => [
        'Layout principal - Template de base',
        'Template HTML de base hérité par d\'autres layouts : preloader, métadonnées, chargement des CSS/JS (Bootstrap, Font Awesome, Chartist, calendrier), gestion du thème sombre et structure de page.'
    ],
    'layouts/dashboard.blade.php' => [
        'Layout tableau de bord (exemple)',
        'Page de démonstration du tableau de bord avec des cartes statistiques (produits vendus, profit, clients, satisfaction). Template de référence.'
    ],
    'layouts/error.blade.php' => [
        'Layout des pages d\'erreur',
        'Template de base pour les pages d\'erreur HTTP (401, 403, 404, 419, 429, 500, 503) avec affichage du code d\'erreur et du message.'
    ],
    'layouts/public.blade.php' => [
        'Layout des pages publiques',
        'Template de base pour les pages accessibles sans connexion : barre de navigation publique, alertes, pied de page avec informations du salon et scripts globaux.'
    ],
    'layouts/service.blade.php' => [
        'Layout page des services (prototype)',
        'Page de prototype/test pour les services avec des champs de formulaire de démonstration. Non utilisée en production.'
    ],

    // ============ PARTIALS ============
    'partials/header.blade.php' => [
        'En-tête de navigation (partial)',
        'Composant d\'en-tête avec la barre de navigation supérieure, recherche, notifications et menu utilisateur.'
    ],
    'partials/footer.blade.php' => [
        'Pied de page (partial)',
        'Composant du pied de page avec les informations de copyright et liens utiles.'
    ],
    'partials/sidebar.blade.php' => [
        'Barre latérale de navigation (partial)',
        'Composant de la barre latérale principale avec le menu de navigation adapté au rôle de l\'utilisateur.'
    ],
    'partials/navheader.blade.php' => [
        'En-tête de navigation avec hamburger (partial)',
        'Composant de l\'en-tête contenant le logo du salon et le bouton hamburger pour le menu mobile.'
    ],
    'partials/success.blade.php' => [
        'Message de succès (partial)',
        'Composant d\'affichage des messages de succès de session avec fermeture automatique.'
    ],
    'partials/error.blade.php' => [
        'Messages d\'erreur (partial)',
        'Composant d\'affichage des erreurs de validation et messages d\'erreur de session.'
    ],
    'partials/actions.blade.php' => [
        'Boutons d\'actions (partial)',
        'Composant réutilisable pour les boutons d\'actions standards (voir, modifier, supprimer) dans les tableaux.'
    ],
    'partials/appointment-actions.blade.php' => [
        'Actions sur les rendez-vous (partial)',
        'Composant des boutons d\'actions spécifiques aux rendez-vous (confirmer, annuler, compléter) selon le statut.'
    ],
    'partials/delete-confirm-modal.blade.php' => [
        'Modal de confirmation de suppression (partial)',
        'Composant modal réutilisable pour confirmer la suppression d\'un élément avec message personnalisable.'
    ],
    'partials/toggle-confirm-modal.blade.php' => [
        'Modal de confirmation d\'activation/désactivation (partial)',
        'Composant modal pour confirmer l\'activation ou la désactivation d\'un élément (client, employé, service).'
    ],
    'partials/profile-logout.blade.php' => [
        'Menu profil et déconnexion (partial)',
        'Composant du menu déroulant de profil avec lien vers le profil et bouton de déconnexion.'
    ],
    'partials/recent_appointments_table.blade.php' => [
        'Tableau des rendez-vous récents (partial)',
        'Composant affichant un tableau des rendez-vous les plus récents pour le tableau de bord.'
    ],
    'partials/sidebar/admin-menu.blade.php' => [
        'Menu sidebar - Administration (partial)',
        'Liens de navigation de la sidebar pour l\'espace administrateur : tableau de bord, clients, employés, services, rendez-vous, paiements, paramètres.'
    ],
    'partials/sidebar/client-menu.blade.php' => [
        'Menu sidebar - Client (partial)',
        'Liens de navigation de la sidebar pour l\'espace client : tableau de bord, rendez-vous, services, paiements, profil, chatbot.'
    ],
    'partials/sidebar/employee-menu.blade.php' => [
        'Menu sidebar - Employé (partial)',
        'Liens de navigation de la sidebar pour l\'espace employé : tableau de bord, rendez-vous, planning, services, congés, messages, profil.'
    ],

    // ============ EMAILS ============
    'emails/admin_payment_simulated.blade.php' => [
        'Email de notification - Paiement simulé',
        'Template d\'email envoyé à l\'administrateur lorsqu\'un paiement est simulé par un client, avec les détails du paiement et du rendez-vous.'
    ],

    // ============ ERRORS ============
    'errors/401.blade.php' => [
        'Page d\'erreur 401 - Non autorisé',
        'Page affichée lorsqu\'un utilisateur non authentifié tente d\'accéder à une ressource protégée.'
    ],
    'errors/403.blade.php' => [
        'Page d\'erreur 403 - Accès interdit',
        'Page affichée lorsqu\'un utilisateur authentifié n\'a pas les permissions nécessaires pour accéder à une ressource.'
    ],
    'errors/404.blade.php' => [
        'Page d\'erreur 404 - Page non trouvée',
        'Page affichée lorsque l\'URL demandée ne correspond à aucune route ou ressource existante.'
    ],
    'errors/419.blade.php' => [
        'Page d\'erreur 419 - Page expirée',
        'Page affichée lorsque le jeton CSRF a expiré, invitant l\'utilisateur à rafraîchir la page.'
    ],
    'errors/429.blade.php' => [
        'Page d\'erreur 429 - Trop de requêtes',
        'Page affichée lorsque l\'utilisateur a dépassé la limite de requêtes (rate limiting).'
    ],
    'errors/500.blade.php' => [
        'Page d\'erreur 500 - Erreur serveur',
        'Page affichée lors d\'une erreur interne du serveur, invitant l\'utilisateur à réessayer plus tard.'
    ],
    'errors/503.blade.php' => [
        'Page d\'erreur 503 - Service indisponible',
        'Page affichée lorsque l\'application est en mode maintenance ou temporairement indisponible.'
    ],

    // ============ ROOT ============
    'dashboard.blade.php' => [
        'Tableau de bord principal (page racine)',
        'Page de tableau de bord générique affichant un message de bienvenue avec le nom et le rôle de l\'utilisateur connecté, et un bouton de déconnexion.'
    ],
];

$count = 0;
$errors = [];

foreach ($comments as $relativePath => $info) {
    $fullPath = $basePath . '/' . $relativePath;
    
    if (!file_exists($fullPath)) {
        $errors[] = "FICHIER INTROUVABLE: $relativePath";
        continue;
    }
    
    $content = file_get_contents($fullPath);
    
    // Vérifier si le commentaire existe déjà
    if (strpos($content, '{{--') === 0 && strpos($content, 'Vue :') !== false) {
        echo "DEJA COMMENTE: $relativePath\n";
        continue;
    }
    
    $commentBlock = "{{--\n    Vue : {$info[0]}\n    Description : {$info[1]}\n--}}\n";
    
    $newContent = $commentBlock . $content;
    
    file_put_contents($fullPath, $newContent);
    $count++;
    echo "OK: $relativePath\n";
}

echo "\n=== RÉSULTAT ===\n";
echo "Fichiers commentés: $count\n";
echo "Erreurs: " . count($errors) . "\n";
foreach ($errors as $err) {
    echo "  - $err\n";
}
