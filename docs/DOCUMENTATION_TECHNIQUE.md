# 📚 Documentation Technique - Système de Gestion de Salon de Beauté

## Table des matières

1. [Introduction](#1-introduction)
2. [Architecture du projet](#2-architecture-du-projet)
3. [Structure de la base de données](#3-structure-de-la-base-de-données)
4. [Système d'authentification](#4-système-dauthentification)
5. [Gestion des rendez-vous](#5-gestion-des-rendez-vous)
6. [Système de planning](#6-système-de-planning)
7. [Gestion des paiements](#7-gestion-des-paiements)
8. [Programme de fidélité](#8-programme-de-fidélité)
9. [Système de promotions](#9-système-de-promotions)
10. [Gestion des congés](#10-gestion-des-congés)
11. [Notifications](#11-notifications)
12. [API et routes](#12-api-et-routes)

---

## 1. Introduction

### 1.1 Présentation du projet

Ce projet est un **système de gestion complet pour salon de beauté** développé avec le framework **Laravel 11**. Il permet de gérer :
- Les rendez-vous clients
- Le planning des employés
- Les paiements (espèces, carte, mobile money)
- Les stocks de produits
- Le programme de fidélité
- Les promotions sur les services

### 1.2 Technologies utilisées

| Technologie | Version | Utilisation |
|-------------|---------|-------------|
| PHP | 8.2+ | Langage serveur |
| Laravel | 11.x | Framework MVC |
| PostgreSQL | 15+ | Base de données |
| Bootstrap | 4.6 | Framework CSS |
| jQuery | 3.3+ | JavaScript |
| FullCalendar | 5.x/6.x | Calendriers interactifs |
| Chart.js | - | Graphiques statistiques |

### 1.3 Prérequis

```bash
- PHP >= 8.2
- Composer
- Node.js & NPM
- PostgreSQL
- Extension PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
```

---

## 2. Architecture du projet

### 2.1 Pattern MVC

Le projet suit le pattern **Model-View-Controller** (MVC) de Laravel :

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Contrôleurs administration
│   │   ├── Client/         # Contrôleurs espace client
│   │   ├── Employee/       # Contrôleurs espace employé
│   │   └── Auth/           # Contrôleurs authentification
│   └── Middleware/         # Middlewares personnalisés
├── Models/                 # Modèles Eloquent
├── Enums/                  # Énumérations PHP 8.1+
└── Services/               # Services métier
```

### 2.2 Organisation des vues

```
resources/views/
├── admin/                  # Vues administration
│   ├── appointments/       # Gestion RDV
│   ├── employees/          # Gestion employés
│   ├── services/           # Gestion services
│   ├── schedules/          # Planning
│   └── ...
├── Clients/                # Vues espace client
│   ├── appointments/       # RDV client
│   ├── payments/           # Paiements
│   └── ...
├── employee/               # Vues espace employé
├── layouts/                # Templates de base
└── partials/               # Composants réutilisables
```

### 2.3 Diagramme d'architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        NAVIGATEUR WEB                            │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                         ROUTES (web.php)                         │
│  • Routes Client (/client/*)                                     │
│  • Routes Employé (/employee/*)                                  │
│  • Routes Admin (/admin/*)                                       │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                         MIDDLEWARE                               │
│  • auth:clients / auth:employees / auth:web                      │
│  • admin (vérifie rôle admin)                                    │
│  • client.active (vérifie compte actif)                          │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                        CONTRÔLEURS                               │
│  • Logique métier                                                │
│  • Validation des données                                        │
│  • Appel aux modèles                                             │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                          MODÈLES                                 │
│  • Eloquent ORM                                                  │
│  • Relations entre tables                                        │
│  • Scopes et accesseurs                                          │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                      BASE DE DONNÉES                             │
│  • PostgreSQL                                                    │
└─────────────────────────────────────────────────────────────────┘
```

---

## 3. Structure de la base de données

### 3.1 Diagramme Entité-Relation

```
┌──────────────┐       ┌──────────────┐       ┌──────────────┐
│    users     │       │   clients    │       │  employees   │
├──────────────┤       ├──────────────┤       ├──────────────┤
│ id           │       │ id           │       │ id           │
│ name         │       │ name         │       │ name         │
│ email        │       │ email        │       │ email        │
│ password     │       │ password     │       │ password     │
│ role         │       │ phone        │       │ phone        │
│ photo        │       │ loyalty_pts  │       │ role         │
└──────────────┘       │ photo        │       │ is_active    │
      │                │ active       │       │ photo        │
      │                └──────────────┘       └──────────────┘
      │                       │                      │
      │                       │                      │
      │                       ▼                      ▼
      │               ┌──────────────┐       ┌──────────────┐
      │               │ appointments │◄──────│employee_sched│
      │               ├──────────────┤       ├──────────────┤
      │               │ id           │       │ employee_id  │
      │               │ client_id    │       │ day_of_week  │
      │               │ employee_id  │       │ start_time   │
      │               │ service_id   │       │ end_time     │
      │               │ scheduled_at │       │ is_working   │
      │               │ status       │       └──────────────┘
      │               │ notes        │
      │               └──────────────┘       ┌──────────────┐
      │                      │               │ blocked_slots│
      │                      │               ├──────────────┤
      │                      ▼               │ employee_id  │
      │               ┌──────────────┐       │ start_datetime│
      │               │   payments   │       │ end_datetime │
      │               ├──────────────┤       │ reason       │
      │               │ id           │       └──────────────┘
      │               │ appointment_id│
      │               │ amount       │       ┌──────────────┐
      │               │ method       │       │leave_requests│
      │               │ status       │       ├──────────────┤
      │               └──────────────┘       │ employee_id  │
      │                                      │ start_date   │
      │               ┌──────────────┐       │ end_date     │
      └──────────────►│   services   │       │ status       │
                      ├──────────────┤       └──────────────┘
                      │ id           │
                      │ name         │
                      │ price        │
                      │ promo_price  │
                      │ duration     │
                      │ active       │
                      └──────────────┘
```

### 3.2 Tables principales

#### Table `clients`
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Clé primaire auto-incrémentée |
| name | VARCHAR(255) | Nom complet du client |
| email | VARCHAR(255) | Email unique pour connexion |
| password | VARCHAR(255) | Mot de passe hashé (bcrypt) |
| phone | VARCHAR(20) | Numéro de téléphone |
| loyalty_points | INT | Points de fidélité accumulés |
| photo | VARCHAR(255) | Chemin vers la photo de profil |
| active | BOOLEAN | Compte actif ou désactivé |

#### Table `employees`
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Clé primaire |
| name | VARCHAR(255) | Nom de l'employé |
| email | VARCHAR(255) | Email de connexion |
| password | VARCHAR(255) | Mot de passe hashé |
| role | VARCHAR(50) | Rôle (employee, manager) |
| is_active | BOOLEAN | Employé actif |
| work_start_time | TIME | Heure début travail |
| work_end_time | TIME | Heure fin travail |

#### Table `appointments`
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Clé primaire |
| client_id | BIGINT | FK vers clients |
| employee_id | BIGINT | FK vers employees |
| service_id | BIGINT | FK vers services |
| scheduled_at | DATETIME | Date et heure du RDV |
| status | ENUM | pending, confirmed, completed, canceled, no-show |
| notes | TEXT | Notes additionnelles |

#### Table `services`
| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT | Clé primaire |
| name | VARCHAR(255) | Nom du service |
| description | TEXT | Description détaillée |
| price | DECIMAL(10,2) | Prix normal en FCFA |
| promotion_price | DECIMAL(10,2) | Prix promotionnel |
| promotion_start | DATE | Début de la promotion |
| promotion_end | DATE | Fin de la promotion |
| duration | INT | Durée en minutes |
| category | VARCHAR(100) | Catégorie (coiffure, soin...) |

---

## 4. Système d'authentification

### 4.1 Multi-Guard Authentication

Le système utilise **3 guards distincts** pour séparer les espaces :

```php
// config/auth.php
'guards' => [
    'web' => [           // Admin
        'driver' => 'session',
        'provider' => 'users',
    ],
    'clients' => [       // Clients
        'driver' => 'session',
        'provider' => 'clients',
    ],
    'employees' => [     // Employés
        'driver' => 'session',
        'provider' => 'employees',
    ],
],
```

### 4.2 Flux d'authentification

```
Client → /client/login
    │
    ├── Validation email/password
    ├── Auth::guard('clients')->attempt()
    ├── Vérification compte actif
    └── Redirection → /client/dashboard

Employé → /employee/login
    │
    ├── Auth::guard('employees')->attempt()
    └── Redirection → /employee/dashboard

Admin → /login
    │
    ├── Auth::guard('web')->attempt()
    ├── Middleware 'admin' vérifie role
    └── Redirection → /admin/dashboard
```

### 4.3 Middleware personnalisé

```php
// Middleware Admin
class AdminMiddleware
{
    public function handle($request, $next)
    {
        if (!auth('web')->check()) {
            return redirect('/login');
        }
        
        if (auth('web')->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }
        
        return $next($request);
    }
}
```

---

## 5. Gestion des rendez-vous

### 5.1 Cycle de vie d'un rendez-vous

```
┌─────────┐    Création     ┌───────────┐    Confirmation    ┌───────────┐
│ Nouveau │ ──────────────► │  Pending  │ ────────────────► │ Confirmed │
└─────────┘                 └───────────┘                    └───────────┘
                                  │                                │
                                  │ Annulation                     │ RDV effectué
                                  ▼                                ▼
                            ┌───────────┐                    ┌───────────┐
                            │ Canceled  │                    │ Completed │
                            └───────────┘                    └───────────┘
                                                                   │
                                                                   │ Points fidélité
                                                                   ▼
                                                            ┌───────────┐
                                                            │  Payment  │
                                                            └───────────┘
```

### 5.2 Logique de réservation (AppointmentController)

```php
/**
 * ÉTAPES DE CRÉATION D'UN RENDEZ-VOUS
 * 
 * 1. Validation des données entrées
 * 2. Construction de la date/heure
 * 3. Sélection ou attribution de l'employé
 * 4. Vérification des conflits
 * 5. Création du rendez-vous
 * 6. Notification au client
 */
public function store(Request $request)
{
    // 1. VALIDATION
    $request->validate([
        'service_id' => 'required|exists:services,id',
        'employee_id' => 'nullable|exists:employees,id',
        'date' => 'required|date|after_or_equal:today',
        'time' => 'required|date_format:H:i',
    ]);

    // 2. CONSTRUCTION DATE/HEURE
    $scheduledAt = $request->date . ' ' . $request->time;

    // 3. SÉLECTION EMPLOYÉ
    if (!$request->employee_id) {
        // Attribution automatique : trouve un employé disponible
        $employee = Employee::whereHas('services', function($q) use ($request) {
            $q->where('services.id', $request->service_id);
        })
        ->where('is_active', true)
        ->get()
        ->first(function($e) use ($scheduledAt) {
            // Vérifie qu'il n'a pas déjà un RDV à cette heure
            return !Appointment::where('employee_id', $e->id)
                ->where('scheduled_at', $scheduledAt)
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();
        });
    }

    // 4. VÉRIFICATION CONFLITS
    // Le système vérifie :
    // - Horaires de travail de l'employé
    // - Pauses
    // - Congés approuvés
    // - Créneaux bloqués
    // - Autres RDV existants

    // 5. CRÉATION
    $appointment = Appointment::create([...]);

    // 6. NOTIFICATION
    $this->notificationService->notifyAppointmentBooked($appointment);
}
```

### 5.3 Calcul des créneaux disponibles

```php
/**
 * ALGORITHME DE CALCUL DES CRÉNEAUX DISPONIBLES
 * 
 * Pour une date et un service donnés :
 */
public function getAvailableSlotsForDate($date, int $serviceDuration)
{
    // 1. Récupérer l'horaire du jour (ex: 09:00 - 18:00)
    $schedule = $this->schedules()->forDay($date->dayOfWeek)->first();
    
    if (!$schedule || !$schedule->is_working) {
        return []; // Jour de repos
    }

    // 2. Récupérer les contraintes
    $existingAppointments = $this->appointments()->whereDate(...)->get();
    $blockedSlots = BlockedSlot::forDate($date)->get();
    $onLeave = $this->leaveRequests()->approved()->...->exists();

    // 3. Générer les créneaux par intervalle de 30 min
    $slots = [];
    $currentSlot = $startOfDay;

    while ($currentSlot + $serviceDuration <= $endOfDay) {
        $isAvailable = true;

        // Vérifier la pause déjeuner
        if ($currentSlot chevauche $breakPeriod) {
            $isAvailable = false;
        }

        // Vérifier les RDV existants
        foreach ($existingAppointments as $appointment) {
            if ($currentSlot chevauche $appointment) {
                $isAvailable = false;
            }
        }

        // Vérifier les blocages
        // ...

        if ($isAvailable) {
            $slots[] = $currentSlot;
        }

        $currentSlot += 30 minutes;
    }

    return $slots;
}
```

---

## 6. Système de planning

### 6.1 Composants du planning

| Composant | Table | Description |
|-----------|-------|-------------|
| Horaires hebdo | employee_schedules | Horaires récurrents par jour |
| Blocages | blocked_slots | Indisponibilités ponctuelles |
| Congés | leave_requests | Demandes de congé |
| RDV | appointments | Rendez-vous planifiés |

### 6.2 Modèle EmployeeSchedule

```php
/**
 * Représente les horaires de travail hebdomadaires d'un employé.
 * 
 * Chaque employé a jusqu'à 7 enregistrements (un par jour de la semaine).
 */
class EmployeeSchedule extends Model
{
    protected $fillable = [
        'employee_id',   // FK vers l'employé
        'day_of_week',   // 0=Dimanche, 1=Lundi, ..., 6=Samedi
        'start_time',    // Heure de début (ex: "09:00:00")
        'end_time',      // Heure de fin (ex: "18:00:00")
        'break_start',   // Début pause (ex: "12:00:00")
        'break_end',     // Fin pause (ex: "13:00:00")
        'is_working',    // true = jour travaillé
    ];

    /**
     * Vérifie si une heure donnée est dans les horaires de travail.
     */
    public function isWithinWorkingHours(string $time): bool
    {
        if (!$this->is_working) return false;
        return $time >= $this->start_time && $time < $this->end_time;
    }

    /**
     * Vérifie si une heure est pendant la pause.
     */
    public function isDuringBreak(string $time): bool
    {
        if (!$this->break_start || !$this->break_end) return false;
        return $time >= $this->break_start && $time < $this->break_end;
    }
}
```

### 6.3 Gestion des blocages (Admin)

```php
/**
 * Crée un créneau bloqué pour un employé ou tout le salon.
 * 
 * Un blocage peut être :
 * - Spécifique à un employé (employee_id renseigné)
 * - Global pour tout le salon (employee_id = null)
 */
public function storeBlockedSlot(Request $request)
{
    // Validation
    $request->validate([
        'employee_id' => 'nullable|exists:employees,id',
        'start_datetime' => 'required|date|after_or_equal:now',
        'end_datetime' => 'required|date|after:start_datetime',
        'reason' => 'nullable|string|max:500',
    ]);

    // Vérifier qu'il n'y a pas de RDV sur ce créneau
    $conflicts = Appointment::where('scheduled_at', '>=', $start)
        ->where('scheduled_at', '<', $end)
        ->whereNotIn('status', ['canceled'])
        ->exists();

    if ($conflicts) {
        return back()->withErrors(['Des RDV existent sur ce créneau']);
    }

    // Création du blocage
    BlockedSlot::create([
        'employee_id' => $request->employee_id,
        'start_datetime' => $start,
        'end_datetime' => $end,
        'reason' => $request->reason,
        'created_by' => Auth::id(),
    ]);
}
```

---

## 7. Gestion des paiements

### 7.1 Méthodes de paiement supportées

| Méthode | Code | Description |
|---------|------|-------------|
| Espèces | cash | Paiement au salon |
| Carte | card | Via Stripe |
| Orange Money | orange_money | Mobile Money |
| Wave | wave | Mobile Money |

### 7.2 Flux de paiement

```
┌──────────────┐
│   RDV terminé │
│  (Completed)  │
└──────────────┘
        │
        ▼
┌──────────────┐     Choix méthode
│ Créer payment│ ◄─────────────────
│   (pending)  │
└──────────────┘
        │
        ├─── Espèces ──────► Paiement immédiat ──► completed
        │
        ├─── Carte ────────► Stripe Checkout ───► Webhook ──► completed
        │
        └─── Mobile Money ─► API Orange/Wave ───► Callback ─► completed
```

### 7.3 Logique de paiement mobile

```php
/**
 * Initie un paiement mobile (Orange Money ou Wave).
 * 
 * Le processus :
 * 1. Affiche les instructions avec le numéro à contacter
 * 2. L'utilisateur effectue le paiement sur son téléphone
 * 3. Un callback API met à jour le statut
 */
public function mobile(Payment $payment, string $method)
{
    // Vérifier que le paiement appartient au client
    if ($payment->appointment->client_id !== auth('clients')->id()) {
        abort(403);
    }

    // Mettre à jour la méthode
    $payment->update([
        'method' => $method,
        'status' => 'processing',
    ]);

    // Afficher la page avec les instructions
    return view('Clients.payments.mobile', [
        'payment' => $payment,
        'method' => $method,
        'phone' => $method === 'orange_money' ? '077 XX XX XX' : '01 XX XX XX',
    ]);
}
```

---

## 8. Programme de fidélité

### 8.1 Règles d'accumulation

```
┌─────────────────────────────────────────────────────────┐
│           CALCUL DES POINTS DE FIDÉLITÉ                 │
├─────────────────────────────────────────────────────────┤
│                                                         │
│   Points gagnés = Prix du service ÷ 1000 (arrondi)     │
│                                                         │
│   Exemple :                                             │
│   - Service à 5000 FCFA → 5 points                      │
│   - Service à 15000 FCFA → 15 points                    │
│   - Service à 800 FCFA → 0 points                       │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

### 8.2 Niveaux et réductions

| Niveau | Points requis | Réduction | Couleur badge |
|--------|---------------|-----------|---------------|
| Bronze | 0-99 | 0% | Gris |
| Argent | 100-199 | 10% | Bleu |
| Or | 200-499 | 15% | Jaune |
| Platine | 500+ | 20% | Violet |

### 8.3 Implémentation dans le modèle Client

```php
class Client extends Authenticatable
{
    /**
     * Ajoute des points de fidélité.
     * Appelée automatiquement quand un RDV passe à "completed".
     */
    public function addLoyaltyPoints(int $points): void
    {
        $this->increment('loyalty_points', $points);
    }

    /**
     * Détermine le niveau de fidélité selon les points.
     */
    public function getLoyaltyLevel(): string
    {
        $points = $this->loyalty_points ?? 0;

        return match(true) {
            $points >= 500 => 'Platine',
            $points >= 200 => 'Or',
            $points >= 100 => 'Argent',
            default => 'Bronze',
        };
    }

    /**
     * Retourne le pourcentage de réduction du niveau actuel.
     */
    public function getLoyaltyDiscount(): int
    {
        return match($this->getLoyaltyLevel()) {
            'Platine' => 20,
            'Or' => 15,
            'Argent' => 10,
            default => 0,
        };
    }
}
```

### 8.4 Attribution automatique des points

```php
// Dans AppointmentController (Admin ou Employee)
public function updateStatus(Request $request, Appointment $appointment)
{
    $oldStatus = $appointment->status->value;
    $newStatus = $request->status;

    $appointment->update(['status' => $newStatus]);

    // Attribution des points si passage à "completed"
    if ($newStatus === 'completed' && $oldStatus !== 'completed') {
        if ($appointment->client) {
            // Calcul : 1 point par 1000 FCFA
            $price = $appointment->service->getCurrentPrice();
            $points = (int) floor($price / 1000);
            
            if ($points > 0) {
                $appointment->client->addLoyaltyPoints($points);
            }
        }
    }
}
```

---

## 9. Système de promotions

### 9.1 Structure des promotions

```php
// Dans le modèle Service
protected $fillable = [
    'price',             // Prix normal
    'promotion_price',   // Prix promotionnel
    'promotion_start',   // Date début promo
    'promotion_end',     // Date fin promo
    'promotion_label',   // Libellé (ex: "Soldes d'été")
];
```

### 9.2 Vérification de promotion active

```php
/**
 * Une promotion est active si :
 * 1. Un prix promotionnel est défini
 * 2. La date actuelle est >= date début (ou pas de date début)
 * 3. La date actuelle est <= date fin (ou pas de date fin)
 */
public function hasActivePromotion(): bool
{
    if (!$this->promotion_price) {
        return false;
    }

    $today = Carbon::today();

    // Avant le début ?
    if ($this->promotion_start && $today->lt($this->promotion_start)) {
        return false;
    }

    // Après la fin ?
    if ($this->promotion_end && $today->gt($this->promotion_end)) {
        return false;
    }

    return true;
}

/**
 * Retourne le prix actuel (promo ou normal).
 */
public function getCurrentPrice(): float
{
    return $this->hasActivePromotion() 
        ? $this->promotion_price 
        : $this->price;
}

/**
 * Calcule le pourcentage de réduction.
 */
public function getDiscountPercentage(): ?int
{
    if (!$this->hasActivePromotion()) return null;
    
    return round(
        (($this->price - $this->promotion_price) / $this->price) * 100
    );
}
```

### 9.3 Affichage côté client

```blade
{{-- Dans la liste des services --}}
@if($service->hasActivePromotion())
    {{-- Badge promo sur l'image --}}
    <span class="badge badge-danger">
        -{{ $service->getDiscountPercentage() }}%
    </span>
    
    {{-- Prix barré + nouveau prix --}}
    <span class="text-decoration-line-through">
        {{ $service->price }} FCFA
    </span>
    <span class="text-danger">
        {{ $service->promotion_price }} FCFA
    </span>
@else
    <span>{{ $service->price }} FCFA</span>
@endif
```

---

## 10. Gestion des congés

### 10.1 Workflow des demandes

```
┌─────────────┐         ┌─────────────┐         ┌─────────────┐
│  Employé    │         │   Admin     │         │  Employé    │
│  demande    │ ──────► │   examine   │ ──────► │  notifié    │
│  congé      │         │   demande   │         │             │
└─────────────┘         └─────────────┘         └─────────────┘
                              │
                              ├── Approuver ──► status = 'approved'
                              │                  → Congé visible dans planning
                              │
                              └── Refuser ────► status = 'rejected'
                                                → Motif envoyé à l'employé
```

### 10.2 Modèle LeaveRequest

```php
class LeaveRequest extends Model
{
    protected $fillable = [
        'employee_id',    // Employé demandeur
        'start_date',     // Date début congé
        'end_date',       // Date fin congé
        'reason',         // Motif de la demande
        'status',         // pending, approved, rejected
        'admin_response', // Réponse de l'admin
        'responded_at',   // Date de réponse
    ];

    /**
     * Calcule le nombre de jours de congé.
     */
    public function getDaysCountAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Retourne le libellé du statut en français.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'En attente',
            'approved' => 'Approuvée',
            'rejected' => 'Rejetée',
            default    => ucfirst($this->status),
        };
    }
}
```

### 10.3 Impact sur les disponibilités

Quand un congé est approuvé, le système :
1. Affiche le congé dans le planning (couleur distincte)
2. Bloque automatiquement les créneaux de l'employé
3. N'affiche pas l'employé dans les créneaux disponibles
4. Empêche la prise de RDV sur cette période

---

## 11. Notifications

### 11.1 Types de notifications

| Type | Destinataire | Déclencheur |
|------|--------------|-------------|
| appointment_booked | Client | Nouveau RDV créé |
| appointment_confirmed | Client | RDV confirmé |
| appointment_canceled | Client | RDV annulé |
| appointment_reminder | Client | J-1 avant RDV |
| loyalty_points | Client | Points gagnés |
| leave_approved | Employé | Congé approuvé |
| leave_rejected | Employé | Congé refusé |

### 11.2 Service de notification

```php
class ClientNotificationService
{
    /**
     * Notifie le client qu'un RDV a été réservé.
     */
    public function notifyAppointmentBooked(Appointment $appointment): void
    {
        ClientNotification::create([
            'client_id' => $appointment->client_id,
            'type' => 'appointment_booked',
            'title' => 'Rendez-vous réservé',
            'message' => sprintf(
                'Votre rendez-vous pour %s est confirmé le %s à %s.',
                $appointment->service->name,
                $appointment->scheduled_at->format('d/m/Y'),
                $appointment->scheduled_at->format('H:i')
            ),
            'data' => [
                'appointment_id' => $appointment->id,
                'service_name' => $appointment->service->name,
            ],
        ]);
    }
}
```

---

## 12. API et routes

### 12.1 Routes Client

| Méthode | URI | Action | Description |
|---------|-----|--------|-------------|
| GET | /client/dashboard | dashboard | Tableau de bord |
| GET | /client/appointments | index | Liste RDV |
| POST | /client/appointments | store | Créer RDV |
| GET | /client/appointments/slots | getAvailableSlots | Créneaux dispo |
| PATCH | /client/appointments/{id}/cancel | cancel | Annuler RDV |
| GET | /client/payments | index | Liste paiements |
| POST | /client/payments/simulate/{apt} | simulate | Simuler paiement |

### 12.2 Routes Employé

| Méthode | URI | Action | Description |
|---------|-----|--------|-------------|
| GET | /employee/dashboard | index | Tableau de bord |
| GET | /employee/appointments | index | Mes RDV |
| PATCH | /employee/appointments/{id}/status | updateStatus | Changer statut |
| GET | /employee/schedules | index | Mon planning |
| POST | /employee/leaves | store | Demander congé |

### 12.3 Routes Admin

| Méthode | URI | Action | Description |
|---------|-----|--------|-------------|
| GET | /admin/dashboard | index | Tableau de bord |
| RESOURCE | /admin/employees | CRUD | Gestion employés |
| RESOURCE | /admin/services | CRUD | Gestion services |
| RESOURCE | /admin/appointments | CRUD | Gestion RDV |
| GET | /admin/schedules | index | Planning global |
| PATCH | /admin/leaves/{id}/approve | approve | Approuver congé |
| POST | /admin/schedules/block | storeBlockedSlot | Bloquer créneau |

---

## Annexes

### A. Commandes Artisan utiles

```bash
# Lancer les migrations
php artisan migrate

# Créer le lien symbolique pour storage
php artisan storage:link

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Lister les routes
php artisan route:list

# Créer un contrôleur
php artisan make:controller NomController

# Créer un modèle avec migration
php artisan make:model NomModele -m
```

### B. Variables d'environnement importantes

```env
# Base de données
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=salon_db
DB_USERNAME=root
DB_PASSWORD=

# Stripe (paiements)
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
```

### C. Structure des dossiers

```
salon2/
├── app/
│   ├── Enums/              # Énumérations (AppointmentStatus)
│   ├── Http/
│   │   ├── Controllers/    # Contrôleurs par rôle
│   │   └── Middleware/     # Middlewares
│   ├── Models/             # Modèles Eloquent
│   └── Services/           # Services métier
├── config/                 # Configuration Laravel
├── database/
│   └── migrations/         # Migrations BDD
├── public/                 # Fichiers publics
├── resources/
│   └── views/              # Vues Blade
├── routes/
│   └── web.php             # Définition des routes
└── storage/                # Fichiers uploadés
```

---

## Conclusion

Ce système de gestion de salon de beauté offre une solution complète et modulaire pour :

1. **Les clients** : réservation en ligne, suivi des RDV, programme de fidélité
2. **Les employés** : gestion du planning personnel, demandes de congé
3. **L'administration** : vue d'ensemble, gestion des ressources, rapports

L'architecture Laravel utilisée garantit :
- Une **séparation claire** des responsabilités (MVC)
- Une **sécurité robuste** avec l'authentification multi-guard
- Une **extensibilité** facile pour ajouter de nouvelles fonctionnalités
- Une **maintenabilité** grâce au code commenté et documenté

---

*Documentation générée le {{ date('d/m/Y') }} pour le projet de mémoire.*
*Développé par MINATOU - 2026*
