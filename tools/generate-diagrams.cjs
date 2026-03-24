const pako = require('pako');

function encode(mermaidCode) {
    const data = new TextEncoder().encode(mermaidCode);
    const compressed = pako.deflate(data, { level: 9 });
    const b64 = Buffer.from(compressed).toString('base64url');
    return `https://mermaid.ink/img/pako:${b64}`;
}

// 1. Use Case
const usecase = `flowchart LR
    subgraph Acteurs
        Admin((Admin))
        Client((Client))
        Employe((Employe))
    end
    subgraph UC_Auth[Authentification]
        UC1[Se connecter]
        UC2[Se deconnecter]
        UC3[Reinitialiser mot de passe]
        UC4[S inscrire]
    end
    subgraph UC_Admin[Cas utilisation Admin]
        A1[Gerer employes CRUD]
        A2[Gerer clients CRUD]
        A3[Gerer services CRUD]
        A4[Gerer rendez-vous CRUD]
        A5[Gerer stocks CRUD]
        A6[Consulter paiements]
        A7[Approuver/Rejeter conges]
        A8[Repondre messages employes]
        A9[Generer rapports / Export CSV]
        A10[Gerer planning employes]
        A11[Bloquer creneaux horaires]
        A12[Gerer theme application]
        A13[Gerer profil admin]
        A14[Tableau de bord statistiques]
    end
    subgraph UC_Client[Cas utilisation Client]
        C1[Consulter services et promotions]
        C2[Prendre un rendez-vous]
        C3[Annuler un rendez-vous]
        C4[Consulter historique RDV]
        C5[Effectuer un paiement]
        C6[Telecharger facture PDF]
        C7[Utiliser le chatbot]
        C8[Gerer profil et photo]
        C9[Consulter notifications]
        C10[Voir calendrier RDV]
        C11[Desactiver son compte]
        C12[Consulter points fidelite]
    end
    subgraph UC_Employe[Cas utilisation Employe]
        E1[Voir rendez-vous assignes]
        E2[Mettre a jour statut RDV]
        E3[Ajouter notes au RDV]
        E4[Consulter calendrier]
        E5[Demander un conge]
        E6[Envoyer message a admin]
        E7[Consulter planning et horaires]
        E8[Gerer profil et photo]
        E9[Consulter notifications]
        E10[Consulter services]
        E11[Gerer paiements]
        E12[Voir historique RDV]
    end
    Admin --> UC1
    Admin --> UC2
    Admin --> UC3
    Admin --> A1
    Admin --> A2
    Admin --> A3
    Admin --> A4
    Admin --> A5
    Admin --> A6
    Admin --> A7
    Admin --> A8
    Admin --> A9
    Admin --> A10
    Admin --> A11
    Admin --> A12
    Admin --> A13
    Admin --> A14
    Client --> UC1
    Client --> UC2
    Client --> UC3
    Client --> UC4
    Client --> C1
    Client --> C2
    Client --> C3
    Client --> C4
    Client --> C5
    Client --> C6
    Client --> C7
    Client --> C8
    Client --> C9
    Client --> C10
    Client --> C11
    Client --> C12
    Employe --> UC1
    Employe --> UC2
    Employe --> UC3
    Employe --> E1
    Employe --> E2
    Employe --> E3
    Employe --> E4
    Employe --> E5
    Employe --> E6
    Employe --> E7
    Employe --> E8
    Employe --> E9
    Employe --> E10
    Employe --> E11
    Employe --> E12`;

// 2. Class diagram
const classdiag = `classDiagram
    class Utilisateur {
        +int id
        +chaine nom
        +chaine email
        +chaine mot_de_passe
        +chaine role
        +chaine photo
        +aRole(chaine) booleen
        +aUnDesRoles(tableau) booleen
    }
    class Client {
        +int id
        +chaine nom
        +chaine email
        +chaine telephone
        +chaine adresse
        +chaine allergies
        +int points_fidelite
        +booleen actif
        +chaine photo
        +ajouterPointsFidelite(int) void
        +utiliserPointsFidelite(int) booleen
        +getNiveauFidelite() chaine
        +getReductionFidelite() int
    }
    class Employe {
        +int id
        +chaine nom
        +chaine email
        +chaine telephone
        +chaine role
        +booleen est_actif
        +chaine specialites
        +tableau jours_travail
        +chaine photo
        +estDisponibleA(dateHeure) booleen
        +getCreneauxDisponibles() tableau
    }
    class Service {
        +int id
        +chaine nom
        +chaine description
        +decimal prix
        +decimal prix_promotion
        +int duree
        +chaine categorie
        +chaine genre
        +booleen actif
        +aPromotionActive() booleen
        +getPrixActuel() decimal
    }
    class RendezVous {
        +int id
        +int client_id
        +int service_id
        +int employe_id
        +dateHeure date_prevue
        +StatutRDV statut
        +chaine notes
        +booleen rappel_envoye
    }
    class Paiement {
        +int id
        +int client_id
        +int rdv_id
        +decimal montant
        +chaine methode
        +chaine statut
        +chaine id_transaction
    }
    class Stock {
        +int id
        +chaine nom
        +chaine categorie
        +int quantite
        +int seuil_alerte
        +estStockBas() booleen
    }
    class DemandeConge {
        +int id
        +int employe_id
        +date date_debut
        +date date_fin
        +chaine motif
        +chaine statut
        +chaine reponse_admin
    }
    class HoraireEmploye {
        +int id
        +int employe_id
        +int jour_semaine
        +chaine heure_debut
        +chaine heure_fin
        +booleen est_travaille
    }
    class CreneauBloque {
        +int id
        +int employe_id
        +dateHeure debut
        +dateHeure fin
        +chaine motif
    }
    class MessageChat {
        +int id
        +int client_id
        +chaine message
        +chaine reponse
        +chaine intention
    }
    class MessageEmploye {
        +int id
        +int employe_id
        +chaine sujet
        +chaine message
        +chaine statut
        +chaine reponse_admin
    }
    class NotificationClient {
        +int id
        +int client_id
        +chaine type
        +chaine titre
        +chaine message
        +booleen lu
    }
    class NotificationEmploye {
        +int id
        +int employe_id
        +chaine titre
        +chaine message
        +chaine type
        +booleen est_lu
    }
    class Parametre {
        +int id
        +chaine theme_couleur
        +booleen mode_sombre
        +getInstance() Parametre
    }
    class StatutRDV {
        <<enumeration>>
        EnAttente
        Confirme
        Termine
        Annule
        Absent
    }
    Client "1" --> "*" RendezVous : prend
    Employe "1" --> "*" RendezVous : est assigne a
    Service "1" --> "*" RendezVous : concerne
    RendezVous "1" --> "0..1" Paiement : genere
    Client "1" --> "*" Paiement : effectue
    Employe "*" <--> "*" Service : realise
    Employe "1" --> "*" DemandeConge : soumet
    Employe "1" --> "*" HoraireEmploye : possede
    Employe "1" --> "*" CreneauBloque : a
    Client "1" --> "*" MessageChat : envoie
    Employe "1" --> "*" MessageEmploye : envoie
    Client "1" --> "*" NotificationClient : recoit
    Employe "1" --> "*" NotificationEmploye : recoit
    CreneauBloque "*" --> "0..1" Utilisateur : cree par
    RendezVous --> StatutRDV : utilise`;

// 3. Sequence - Prise de RDV
const seq1 = `sequenceDiagram
    actor C as Client
    participant UI as Interface Web
    participant CR as Controleur RDV
    participant MS as Modele Service
    participant ME as Modele Employe
    participant MR as Modele RendezVous
    participant NC as Notification Client
    participant NE as Notification Employe
    C->>UI: Selectionne un service
    UI->>CR: Recuperer employes du service
    CR->>MS: Rechercher service
    CR->>ME: Employes disponibles
    ME-->>CR: Liste employes qualifies
    CR-->>UI: Affiche employes disponibles
    C->>UI: Selectionne employe et date
    UI->>CR: Recuperer creneaux disponibles
    CR->>ME: Calculer creneaux pour la date
    ME->>ME: Verifie horaires conges et blocages
    ME-->>CR: Creneaux disponibles
    CR-->>UI: Affiche creneaux horaires
    C->>UI: Choisit creneau et confirme
    UI->>CR: Enregistrer le rendez-vous
    CR->>MR: Creer rendez-vous
    MR-->>CR: RDV cree statut En attente
    CR->>NC: Creer notification client
    CR->>NE: Creer notification employe
    CR-->>UI: Redirection avec succes
    UI-->>C: Confirmation du rendez-vous`;

// 4. Sequence - Paiement
const seq2 = `sequenceDiagram
    actor C as Client
    participant UI as Interface Web
    participant CP as Controleur Paiement
    participant MP as Modele Paiement
    participant MR as Modele RendezVous
    participant MC as Modele Client
    participant Ext as Passerelle Externe
    C->>UI: Selectionne RDV a payer
    UI->>CP: Afficher formulaire paiement
    CP->>MR: RDV non payes du client
    CP-->>UI: Formulaire de paiement
    C->>UI: Choisit methode de paiement
    UI->>CP: Enregistrer le paiement
    CP->>MP: Creer paiement en attente
    alt Paiement par carte Stripe
        CP->>Ext: Initier paiement Stripe
        Ext-->>CP: URL de redirection
        CP-->>UI: Redirection vers Stripe
        Ext->>CP: Webhook de confirmation
        CP->>MP: Statut passe a termine
    else Paiement mobile Orange Money ou Wave
        CP->>Ext: Initier paiement mobile
        Ext-->>CP: Identifiant transaction
        CP-->>UI: Page attente mobile
        Ext->>CP: Callback de confirmation
        CP->>MP: Statut passe a termine
    else Paiement en especes au salon
        CP->>MP: Creer paiement en attente
        CP-->>UI: Paiement enregistre
    end
    CP->>MC: Ajouter points fidelite
    CP->>MR: Mettre a jour statut RDV
    CP-->>UI: Confirmation paiement
    UI-->>C: Facture disponible`;

// 5. Sequence - Conges
const seq3 = `sequenceDiagram
    actor E as Employe
    participant UI as Interface Employe
    participant CC as Controleur Conges Employe
    participant DC as Modele Demande Conge
    participant CA as Controleur Conges Admin
    participant NE as Notification Employe
    actor A as Admin
    E->>UI: Remplit formulaire de conge
    UI->>CC: Soumettre demande de conge
    CC->>DC: Creer demande statut En attente
    CC-->>UI: Demande soumise avec succes
    UI-->>E: Confirmation soumission
    Note over A: Admin consulte les demandes
    A->>CA: Consulter les demandes de conge
    CA->>DC: Recuperer demandes en attente
    CA-->>A: Liste des demandes en attente
    alt Approuver la demande
        A->>CA: Approuver la demande
        CA->>DC: Statut passe a Approuvee
        CA->>NE: Notification conge approuve
        NE-->>E: Notification recue
    else Rejeter la demande
        A->>CA: Rejeter la demande avec motif
        CA->>DC: Statut passe a Rejetee avec reponse
        CA->>NE: Notification conge rejete
        NE-->>E: Notification recue
    end`;

// 6. Sequence - Messagerie
const seq4 = `sequenceDiagram
    actor E as Employe
    participant UI as Interface Employe
    participant CM as Controleur Messages
    participant MM as Modele Message Employe
    participant CA as Controleur Admin Messages
    actor A as Admin
    E->>UI: Redige un message avec sujet et contenu
    UI->>CM: Envoyer le message
    CM->>MM: Creer message statut En attente
    CM-->>UI: Message envoye avec succes
    UI-->>E: Confirmation envoi
    Note over A: Admin consulte les messages
    A->>CA: Consulter les messages employes
    CA->>MM: Recuperer messages en attente
    CA-->>A: Liste des messages en attente
    A->>CA: Voir le detail du message
    CA-->>A: Detail du message
    A->>CA: Repondre au message
    CA->>MM: Enregistrer reponse statut Repondu
    CA-->>A: Reponse enregistree`;

// 7. Activite - Prise de RDV
const act1 = `flowchart TD
    A([Le client se connecte]) --> B[Consulter la liste des services]
    B --> C{Service actif avec promotion?}
    C -->|Oui| D[Afficher le prix promotionnel]
    C -->|Non| E[Afficher le prix normal]
    D --> F[Selectionner un service]
    E --> F
    F --> G[Recuperer les employes qualifies]
    G --> H{Employes disponibles?}
    H -->|Non| I[Message: aucun employe disponible]
    I --> F
    H -->|Oui| J[Selectionner un employe]
    J --> K[Choisir une date]
    K --> L[Verifier la disponibilite]
    L --> M{Horaires de travail?}
    M -->|Jour de repos| N[Aucun creneau - choisir autre date]
    N --> K
    M -->|Jour travaille| O{Conges approuves?}
    O -->|En conge| N
    O -->|Disponible| P{Creneaux bloques?}
    P -->|Bloque| N
    P -->|Libre| Q[Afficher les creneaux disponibles]
    Q --> R[Selectionner un creneau horaire]
    R --> S[Confirmer le rendez-vous]
    S --> T[Creer le RDV statut En attente]
    T --> U[Envoyer notification au client]
    T --> V[Envoyer notification a employe]
    U --> W([Rendez-vous confirme])
    V --> W`;

// 8. State - Cycle de vie RDV
const state1 = `stateDiagram-v2
    [*] --> EnAttente: Client cree le RDV
    EnAttente --> Confirme: Employe ou Admin confirme
    EnAttente --> Annule: Client annule
    Confirme --> Termine: Service effectue
    Confirme --> Annule: Client ou Admin annule
    Confirme --> Absent: Client absent
    Termine --> [*]: Paiement et points fidelite
    Annule --> [*]: RDV termine
    Absent --> [*]: RDV termine`;

// 9. Activite - Planning Admin
const act3 = `flowchart TD
    A([Admin se connecte]) --> B[Acceder au tableau de bord]
    B --> C{Quelle action?}
    C -->|Gerer le planning| D[Consulter le planning des employes]
    D --> E[Selectionner un employe]
    E --> F{Action sur le planning}
    F -->|Modifier les horaires| G[Mettre a jour les horaires]
    G --> H[Definir heures de travail et pauses par jour]
    H --> I([Planning mis a jour])
    F -->|Bloquer un creneau| J[Creer un creneau bloque]
    J --> K[Definir date debut et fin avec motif]
    K --> L([Creneau bloque])
    F -->|Debloquer| M[Supprimer le creneau bloque]
    M --> N([Creneau libere])
    C -->|Gerer les conges| O[Consulter les demandes en attente]
    O --> P[Examiner la demande]
    P --> Q{Decision?}
    Q -->|Approuver| R[Statut passe a Approuvee]
    R --> S[Notifier employe]
    Q -->|Rejeter| T[Statut passe a Rejetee avec reponse]
    T --> S
    S --> U([Demande traitee])
    C -->|Rapports| V[Generer les statistiques]
    V --> W{Format de sortie?}
    W -->|Page web| X[Afficher tableau de bord rapports]
    W -->|Fichier| Y[Telecharger en CSV]`;

// 10. Activite - Paiement
const act4 = `flowchart TD
    A([Le client consulte ses RDV]) --> B[Selectionner un RDV non paye]
    B --> C[Afficher le formulaire de paiement]
    C --> D{Choix de la methode de paiement}
    D -->|Carte bancaire Stripe| E[Redirection vers Stripe]
    E --> F{Paiement reussi?}
    F -->|Oui| G[Confirmation: statut Termine]
    F -->|Non| H[Echec: statut Echoue]
    H --> C
    D -->|Orange Money| I[Initier le paiement mobile]
    I --> J[Afficher la page attente]
    J --> K{Confirmation recue?}
    K -->|Succes| G
    K -->|Echec| H
    D -->|Wave| L[Initier le paiement Wave]
    L --> J
    D -->|Especes au salon| M[Enregistrer paiement en attente]
    M --> N([Paiement a regler au salon])
    G --> O[Ajouter les points fidelite]
    O --> P[Mettre a jour le statut du RDV]
    P --> Q[Generer la facture]
    Q --> R([Facture disponible en PDF])`;

const diagrams = {
    'USECASE': usecase,
    'CLASSDIAG': classdiag,
    'SEQ1': seq1,
    'SEQ2': seq2,
    'SEQ3': seq3,
    'SEQ4': seq4,
    'ACT1': act1,
    'STATE1': state1,
    'ACT3': act3,
    'ACT4': act4
};

for (const [name, code] of Object.entries(diagrams)) {
    const url = encode(code);
    console.log(`${name}:`);
    console.log(url);
    console.log(`LENGTH: ${url.length}`);
    console.log('');
}
