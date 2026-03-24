# Cahier des Charges — KAARJA Beaute

## 1. Presentation du projet

### 1.1 Contexte
KAARJA Beaute est une application web de gestion complete pour un salon de beaute base au Mali. Elle permet la gestion des rendez-vous, des services, des employes, des clients, des paiements et du planning, avec trois espaces distincts : **Administrateur**, **Employe** et **Client**.

### 1.2 Objectifs
- Digitaliser la gestion quotidienne du salon de beaute
- Permettre la prise de rendez-vous en ligne par les clients
- Faciliter la gestion du planning et des employes
- Automatiser le suivi des paiements et la facturation
- Offrir un programme de fidelite aux clients
- Fournir des rapports et statistiques a l'administrateur

### 1.3 Technologies utilisees

| Composant          | Technologie                        |
|--------------------|------------------------------------|
| Backend            | Laravel 12 (PHP 8.2+)              |
| Frontend           | Blade Templates + Bootstrap 4.6    |
| Base de donnees    | MySQL                              |
| Authentification   | Multi-guard (web, clients, employees) |
| Calendrier         | FullCalendar 5/6                   |
| Graphiques         | Chart.js                           |
| Polices            | Google Fonts (Poppins, Playfair Display) |
| Icones             | Font Awesome 4.7                   |
| Bundler            | Vite 7                             |
| CSS Framework      | Tailwind CSS 4 (via Vite)          |

---

## 2. Architecture de l'application

### 2.1 Structure multi-utilisateurs

L'application dispose de **3 espaces utilisateur** avec des guards d'authentification separes :

```
┌──────────────────────────────────────────────────────┐
│                   KAARJA Beaute                       │
├──────────────┬──────────────┬────────────────────────┤
│  Admin       │  Employe      │  Client                │
│  Guard: web  │  Guard:       │  Guard: clients        │
│              │  employees    │                        │
│  /admin/*    │  /employee/*  │  /client/*             │
└──────────────┴──────────────┴────────────────────────┘
```

### 2.2 Modeles de donnees (14 modeles)

| Modele              | Description                                      |
|---------------------|--------------------------------------------------|
| User                | Administrateur du systeme                        |
| Client              | Client du salon                                  |
| Employee            | Employe du salon                                 |
| Service             | Service propose (coiffure, manucure, etc.)       |
| Appointment         | Rendez-vous client-employe-service               |
| Payment             | Paiement lie a un rendez-vous                    |
| Stock               | Gestion des produits en stock                    |
| LeaveRequest        | Demande de conge d'un employe                    |
| EmployeeSchedule    | Planning hebdomadaire d'un employe               |
| BlockedSlot         | Creneau bloque dans le planning                  |
| EmployeeMessage     | Message employe vers l'administration            |
| EmployeeNotification| Notification pour les employes                   |
| ClientNotification  | Notification pour les clients                    |
| ChatMessage         | Message du chatbot client                        |

### 2.3 Base de donnees — Relations principales

```
Client (1) ──── (N) Appointment (N) ──── (1) Service
                        │
                   (1) Employee
                        │
                   (0..1) Payment
                        
Employee (N) ──── (N) Service  (table pivot: employee_service)
Employee (1) ──── (N) EmployeeSchedule
Employee (1) ──── (N) LeaveRequest
Employee (1) ──── (N) EmployeeMessage
Employee (1) ──── (N) EmployeeNotification

Client (1) ──── (N) Payment
Client (1) ──── (N) ClientNotification
Client (1) ──── (N) ChatMessage
```

---

## 3. Fonctionnalites detaillees

### 3.1 Espace Administrateur (`/admin/*`)

#### 3.1.1 Tableau de bord
- [x] Statistiques generales (clients, employes, RDV, revenus)
- [x] Graphiques et indicateurs de performance
- [x] Apercu des rendez-vous du jour

#### 3.1.2 Gestion des employes
- [x] CRUD complet (creer, lire, modifier, supprimer)
- [x] Activation / desactivation d'un employe
- [x] Attribution des services a chaque employe
- [x] Photo de profil
- [x] Indicatif telephonique Mali (+223)

#### 3.1.3 Gestion des clients
- [x] CRUD complet
- [x] Activation / desactivation d'un client
- [x] Historique des rendez-vous et paiements
- [x] Photo de profil
- [x] Gestion des allergies

#### 3.1.4 Gestion des services
- [x] CRUD complet
- [x] Categories de services
- [x] Prix et duree
- [x] Systeme de promotions (prix promo + dates)
- [x] Classification par genre (homme/femme/mixte)
- [x] Photos du service

#### 3.1.5 Gestion des rendez-vous
- [x] CRUD complet
- [x] Selection client + service + employe + date/heure
- [x] Verification de disponibilite des employes en temps reel
- [x] Statuts : En attente, Confirme, Termine, Annule, Absent

#### 3.1.6 Gestion des paiements
- [x] Consultation de tous les paiements
- [x] Detail d'un paiement

#### 3.1.7 Gestion des stocks
- [x] CRUD complet des produits
- [x] Suivi des quantites

#### 3.1.8 Gestion des conges
- [x] Consultation des demandes de conge
- [x] Approbation / rejet des demandes

#### 3.1.9 Messagerie interne
- [x] Reception des messages des employes
- [x] Reponse aux messages

#### 3.1.10 Rapports et statistiques
- [x] Tableau de bord analytique avec graphiques (Chart.js)
- [x] Export CSV des donnees

#### 3.1.11 Planning
- [x] Calendrier FullCalendar avec vue d'ensemble
- [x] Gestion des horaires de travail par employe
- [x] Blocage de creneaux horaires

#### 3.1.12 Profil administrateur
- [x] Modification des informations personnelles
- [x] Changement de mot de passe
- [x] Photo de profil

---

### 3.2 Espace Employe (`/employee/*`)

#### 3.2.1 Tableau de bord
- [x] Statistiques personnelles (RDV du jour, en attente, revenus)
- [x] Liste des prochains rendez-vous
- [x] Historique des paiements recents

#### 3.2.2 Gestion des rendez-vous
- [x] Liste des rendez-vous assignes (vues : A venir, Aujourd'hui, Semaine)
- [x] Calendrier FullCalendar interactif
- [x] Historique des rendez-vous passes
- [x] Changement de statut (En attente → Confirme → Termine)
- [x] Ajout de notes post-rendez-vous
- [x] Encaissement direct depuis l'historique

#### 3.2.3 Encaissement / Paiements
- [x] Liste des rendez-vous termines non payes
- [x] Encaissement en especes ou par carte
- [x] Historique des paiements effectues

#### 3.2.4 Consultation des services
- [x] Liste des services proposes par le salon

#### 3.2.5 Demandes de conge
- [x] Creation d'une demande de conge
- [x] Suivi de l'etat de la demande (en attente, approuve, rejete)

#### 3.2.6 Planning
- [x] Consultation du planning personnel (FullCalendar)
- [x] Visualisation des horaires de travail
- [x] Consultation des jours de conge

#### 3.2.7 Messagerie
- [x] Envoi de messages a l'administration
- [x] Consultation des messages envoyes et des reponses

#### 3.2.8 Notifications
- [x] Liste des notifications
- [x] Marquer comme lu (individuel / tout)

#### 3.2.9 Profil employe
- [x] Modification des informations personnelles
- [x] Changement de mot de passe (page dediee)
- [x] Photo de profil

---

### 3.3 Espace Client (`/client/*`)

#### 3.3.1 Tableau de bord
- [x] Message de bienvenue personnalise
- [x] Statistiques (total RDV, prochains RDV, paiements)
- [x] Programme de fidelite (points + niveau)
- [x] Prochains rendez-vous
- [x] Historique recent des paiements

#### 3.3.2 Prise de rendez-vous
- [x] Selection du service avec prix et duree
- [x] Selection optionnelle de l'employe
- [x] Attribution automatique d'un employe disponible
- [x] Selection de la date et du creneau horaire
- [x] Verification de disponibilite en temps reel (API JSON)
- [x] Resume recapitulatif avant validation
- [x] Modification d'un rendez-vous existant
- [x] Annulation d'un rendez-vous

#### 3.3.3 Calendrier
- [x] Vue calendrier FullCalendar avec tous les rendez-vous
- [x] Navigation mensuelle

#### 3.3.4 Historique
- [x] Liste des rendez-vous passes avec statuts

#### 3.3.5 Paiements
- [x] Liste de tous les paiements
- [x] Creation d'un paiement (selection du RDV + methode)
- [x] Methodes : Especes, Carte, Orange Money, Wave, Stripe
- [x] Simulation de paiement
- [x] Facture (affichage + telechargement PDF)
- [x] Paiement mobile (Orange Money, Wave) avec callback
- [x] Webhooks : Stripe, Orange Money, Wave

#### 3.3.6 Consultation des services
- [x] Liste des services avec categories, prix, duree
- [x] Detail d'un service
- [x] Affichage des promotions en cours

#### 3.3.7 Chatbot assistant
- [x] Interface de chat integree (widget flottant)
- [x] Reponses automatiques : services, promotions, reservation
- [x] Historique des conversations
- [x] Suggestions rapides (boutons)

#### 3.3.8 Notifications
- [x] Liste des notifications
- [x] Marquer comme lu (individuel / tout)

#### 3.3.9 Profil client
- [x] Modification des informations (nom, email, telephone, allergies)
- [x] Photo de profil
- [x] Desactivation du compte

#### 3.3.10 Programme de fidelite
- [x] Accumulation de points (1 point / 1 000 FCFA)
- [x] Niveaux de fidelite
- [x] Affichage sur le tableau de bord

---

### 3.4 Fonctionnalites transversales

#### 3.4.1 Authentification
- [x] 3 guards independants (web, clients, employees)
- [x] Connexion / deconnexion pour chaque type
- [x] Inscription client
- [x] Reinitialisation de mot de passe (admin, client, employe)
- [x] Protection anti-brute-force (throttle: 5 tentatives/minute)
- [x] Middleware `client.active` (clients desactives bloques)

#### 3.4.2 Design et interface
- [x] Design personnalise "Beauty Theme" avec variables CSS
- [x] 8 themes de couleurs (Rose Gold, Ocean Blue, Emerald, Royal Purple, Sunset, Teal Coral, Cherry, Slate)
- [x] Mode sombre (Dark Theme)
- [x] Responsive (mobile, tablette, desktop)
- [x] Navbar avec menu mobile (hamburger)
- [x] Composants : beauty-card, beauty-stat, beauty-page-header, beauty-empty
- [x] Animations et transitions CSS

#### 3.4.3 Pages publiques
- [x] Page d'accueil / bienvenue (`/`)
- [x] Liste des services publique (`/services`)

---

## 4. Routes de l'application (165 routes)

### 4.1 Routes publiques
| Methode | URI                    | Description                  |
|---------|------------------------|------------------------------|
| GET     | `/`                    | Page d'accueil               |
| GET     | `/services`            | Services publics             |
| GET     | `/login`               | Connexion admin              |
| GET     | `/client/login`        | Connexion client             |
| GET     | `/client/register`     | Inscription client           |
| GET     | `/employee/login`      | Connexion employe            |

### 4.2 Routes admin (52 routes)
Dashboard, CRUD employes/clients/services/RDV/stocks, paiements, conges, messages, rapports, planning.

### 4.3 Routes client (48 routes)
Dashboard, profil, RDV (CRUD + calendrier + historique), paiements (CRUD + factures + mobile), services, chatbot, notifications.

### 4.4 Routes employe (42 routes)
Dashboard, profil, RDV (liste + calendrier + historique + statuts + notes), paiements/encaissement, conges, planning, messages, notifications.

### 4.5 Webhooks (3 routes)
| Methode | URI                    | Description                  |
|---------|------------------------|------------------------------|
| POST    | `/stripe/webhook`      | Callback Stripe              |
| POST    | `/orange-money/callback`| Callback Orange Money       |
| POST    | `/wave/callback`       | Callback Wave                |

---

## 5. Securite

| Mesure                          | Implementation                          |
|---------------------------------|-----------------------------------------|
| Authentification                | 3 guards Laravel separes                |
| Protection CSRF                 | Token CSRF sur tous les formulaires     |
| Limitation de tentatives        | Throttle 5 req/min sur login            |
| Validation des donnees          | Form Request + validation cote serveur  |
| Hashage mot de passe            | bcrypt via `Hash::make()`               |
| Controle d'acces                | Verification `employee_id` / `client_id`|
| Middleware actif                | `client.active` pour bloquer inactifs   |
| Protection XSS                  | Echappement Blade `{{ }}`               |
| Transactions DB                 | Paiements dans `DB::transaction()`      |

---

## 6. Identifiants de test

| Role           | Email               | Mot de passe | URL connexion       |
|----------------|---------------------|--------------|---------------------|
| Administrateur | admin@salon.com     | admin123     | `/login`            |
| Employe        | employe@salon.com   | employe123   | `/employee/login`   |
| Client         | client@salon.com    | client123    | `/client/login`     |

---

## 7. Arborescence du projet

```
salon2/
├── app/
│   ├── Enums/              → AppointmentStatus
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/      → 12 controleurs
│   │   │   ├── Auth/       → 11 controleurs
│   │   │   ├── Client/     → 6 controleurs
│   │   │   └── Employee/   → 11 controleurs
│   │   └── Middleware/
│   └── Models/             → 14 modeles
├── database/
│   ├── migrations/         → 35 migrations
│   └── seeders/
├── resources/views/
│   ├── admin/              → 15+ vues
│   ├── auth/               → Pages login/register
│   ├── clients/            → 20+ vues
│   ├── employee/           → 18+ vues
│   ├── layouts/            → 5 layouts master
│   ├── emails/             → Templates email
│   └── partials/           → Composants reutilisables
├── routes/
│   ├── web.php             → Routes principales
│   ├── admin.php           → Routes admin
│   ├── client.php          → Routes client
│   └── employee.php        → Routes employe
├── docs/                   → Documentation
└── public/                 → Assets publics
```

---

## 8. Contraintes et exigences

### 8.1 Exigences fonctionnelles
- L'application doit supporter 3 types d'utilisateurs avec des espaces separes
- La prise de rendez-vous doit verifier la disponibilite en temps reel
- Les paiements doivent supporter plusieurs methodes (especes, carte, mobile money)
- Le programme de fidelite doit etre automatique (1 point / 1 000 FCFA)
- L'interface doit etre en francais

### 8.2 Exigences non fonctionnelles
- Interface responsive (mobile, tablette, desktop)
- Temps de chargement optimise (preconnect CDN, fonts non-bloquantes)
- Design moderne et elegant adapte a un salon de beaute
- Mode sombre disponible
- Securite : protection CSRF, throttle, validation, hashage

### 8.3 Contexte geographique
- Devise : FCFA (Franc CFA)
- Indicatif telephonique : +223 (Mali)
- Langue : Francais
- Paiement mobile : Orange Money, Wave (operateurs Mali)

---

## 9. Rapport de verification

### 9.1 Audit des controleurs (40 controleurs)

| Espace       | Controleurs | Methodes | Statut |
|-------------|-------------|----------|--------|
| Admin       | 12          | 52+      | OK     |
| Client      | 6           | 30+      | OK     |
| Employee    | 11          | 35+      | OK     |
| Auth        | 11          | 22+      | OK     |
| **Total**   | **40**      | **139+** | **OK** |

### 9.2 Audit des vues Blade

| Espace       | Vues   | Statut |
|-------------|--------|--------|
| Admin       | 15+    | OK     |
| Client      | 20+    | OK     |
| Employee    | 18+    | OK     |
| Auth        | 5+     | OK     |
| Layouts     | 5      | OK     |

### 9.3 Audit des routes
- **165 routes** enregistrees et fonctionnelles
- Toutes les routes pointent vers des controleurs et methodes existants

### 9.4 Design et interface
- [x] Responsive mobile/tablette/desktop (3 breakpoints : 991px, 767px, 575px)
- [x] 8 themes de couleurs fonctionnels
- [x] Mode sombre complet
- [x] Listes deroulantes stylisees et coherentes
- [x] Chargement optimise (preconnect CDN, fonts non-bloquantes)
- [x] Cache Laravel (routes, config, vues)
