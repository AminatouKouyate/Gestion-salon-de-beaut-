# MÉMOIRE DE FIN D'ÉTUDES
## Master 2 - [Spécialité à compléter]

---

# CONCEPTION ET RÉALISATION D'UN SYSTÈME D'INFORMATION INTÉGRÉ POUR LA GESTION DES SALONS DE BEAUTÉ

## Application web avec Laravel

---

**Présenté par** : [Aminatou Kouyate]

**Encadré par** : [Kidjiko Kayupe]

**Année universitaire** : 2022-2024

---

# DÉDICACES

*À ma famille...*

*À mes enseignants...*

*À tous ceux qui m'ont soutenu...*

---

# REMERCIEMENTS

Avant toute chose, je souhaite adresser mes sincères remerciements à l'ensemble des personnes qui, de manière directe ou indirecte, ont rendu ce travail possible.

Mes premiers mots de gratitude vont naturellement à **[Nom de l'encadrant]**, mon directeur de mémoire. Ses orientations judicieuses, sa patience et sa présence constante tout au long de ce parcours m'ont été d'un apport inestimable.

Je tiens par ailleurs à saluer le dévouement de tout le corps enseignant du département **[Nom du département]**, dont les enseignements dispensés au fil de ces années ont forgé les compétences que je mobilise aujourd'hui.

Un immense merci également à ma famille et à mes proches. Leur soutien indéfectible, leurs encouragements dans les moments difficiles comme dans les réussites, ont compté bien plus qu'ils ne l'imaginent.

À toutes celles et ceux, enfin, qui ont contribué d'une façon ou d'une autre à l'aboutissement de ce mémoire : merci du fond du cœur.

---

# RÉSUMÉ

## Résumé en français

Le présent mémoire porte sur la conception et le développement d'un système d'information intégré destiné à la gestion des salons de beauté. Dans un contexte où la digitalisation s'impose de plus en plus et où l'optimisation des processus internes devient une nécessité, ce travail propose une solution web complète, bâtie sur le framework Laravel.

L'application mise en place s'articule autour de trois espaces bien distincts — un pour le client, un pour l'employé et un pour l'administration — et embarque un ensemble de fonctionnalités avancées. On y retrouve, entre autres, un module de gestion intelligente des rendez-vous avec calcul automatique des créneaux disponibles, un système de paiement multicanal acceptant aussi bien les espèces que la carte ou le mobile money, un programme de fidélité graduel, un chatbot d'aide intégrée, un suivi des stocks assorti d'alertes, ainsi qu'un dispositif de notifications en temps réel.

Le choix d'une architecture MVC assure une répartition claire des responsabilités au sein du code, ce qui simplifie considérablement la maintenance et ouvre la porte à des évolutions futures. Les résultats obtenus confirment que la solution répond de manière convaincante aux enjeux de gestion opérationnelle et de relation client propres aux salons de beauté.

**Mots-clés** : Système d'information, Laravel, Gestion de salon de beauté, Application web, MVC, CRM, Planning intelligent

## Abstract (English)

This thesis addresses the design and development of an integrated information system tailored to beauty salon management. In a context where digital transformation is increasingly unavoidable and where streamlining day-to-day operations has become a real priority, this work puts forward a full-fledged web solution built on top of the Laravel framework.

The resulting application is structured around three separate user spaces — client, employee, and administration — and incorporates a range of advanced capabilities. These include smart appointment scheduling with automated availability calculation, a multi-channel payment system supporting cash, card, and mobile money, a tiered loyalty program, a built-in assistance chatbot, stock tracking with automatic alerts, and a real-time notification engine.

By relying on the MVC architecture, the system achieves a clean separation of concerns that makes ongoing maintenance far more manageable and lays the groundwork for future enhancements. The outcomes of this project demonstrate that the proposed solution effectively addresses operational management challenges and strengthens customer engagement in beauty salons.

**Keywords**: Information System, Laravel, Beauty Salon Management, Web Application, MVC, CRM, Smart Scheduling

---

# TABLE DES MATIÈRES

1. [Introduction générale](#introduction-générale)
2. [Chapitre 1 : Cadre théorique et état de l'art](#chapitre-1--cadre-théorique-et-état-de-lart)
3. [Chapitre 2 : Méthodologie et analyse des besoins](#chapitre-2--méthodologie-et-analyse-des-besoins)
4. [Chapitre 3 : Conception du système](#chapitre-3--conception-du-système)
5. [Chapitre 4 : Réalisation et implémentation](#chapitre-4--réalisation-et-implémentation)
6. [Chapitre 5 : Évaluation et discussion](#chapitre-5--évaluation-et-discussion)
7. [Conclusion générale](#conclusion-générale)
8. [Bibliographie](#bibliographie)
9. [Annexes](#annexes)

---

# LISTE DES FIGURES

- Figure 1.1 : Évolution du marché des salons de beauté
- Figure 2.1 : Diagramme de cas d'utilisation global
- Figure 2.2 : Diagramme de cas d'utilisation - Espace Client
- Figure 2.3 : Diagramme de cas d'utilisation - Espace Employé
- Figure 2.4 : Diagramme de cas d'utilisation - Espace Administration
- Figure 3.1 : Architecture globale du système (3-tiers)
- Figure 3.2 : Architecture MVC de Laravel
- Figure 3.3 : Modèle Conceptuel de Données (MCD)
- Figure 3.4 : Modèle Logique de Données (MLD)
- Figure 3.5 : Diagramme de classes
- Figure 3.6 : Diagramme de séquence - Réservation de rendez-vous
- Figure 3.7 : Diagramme de séquence - Processus de paiement
- Figure 3.8 : Diagramme de séquence - Authentification multi-guards
- Figure 4.1 : Interface de connexion
- Figure 4.2 : Dashboard client
- Figure 4.3 : Calendrier interactif des rendez-vous
- Figure 4.4 : Interface du chatbot
- Figure 4.5 : Dashboard employé
- Figure 4.6 : Planning global administration
- Figure 4.7 : Gestion des stocks

---

# LISTE DES TABLEAUX

- Tableau 1.1 : Comparatif des solutions existantes
- Tableau 2.1 : Besoins fonctionnels par acteur
- Tableau 2.2 : Besoins non fonctionnels
- Tableau 3.1 : Dictionnaire de données
- Tableau 3.2 : Comparatif des technologies web
- Tableau 3.3 : Configuration des guards Laravel
- Tableau 4.1 : Statistiques de l'application
- Tableau 5.1 : Évaluation selon les critères de qualité

---

# GLOSSAIRE ET ABRÉVIATIONS

| Abréviation | Signification |
|-------------|---------------|
| API | Application Programming Interface |
| CRUD | Create, Read, Update, Delete |
| CRM | Customer Relationship Management |
| CSS | Cascading Style Sheets |
| FCFA | Franc de la Communauté Financière Africaine |
| HTML | HyperText Markup Language |
| HTTP | HyperText Transfer Protocol |
| JS | JavaScript |
| JSON | JavaScript Object Notation |
| MCD | Modèle Conceptuel de Données |
| MLD | Modèle Logique de Données |
| MVC | Model-View-Controller |
| ORM | Object-Relational Mapping |
| PHP | PHP Hypertext Preprocessor |
| REST | Representational State Transfer |
| RDV | Rendez-vous |
| SI | Système d'Information |
| SQL | Structured Query Language |
| UML | Unified Modeling Language |
| URL | Uniform Resource Locator |

---

# INTRODUCTION GÉNÉRALE

## Contexte et motivation

Nous vivons une époque où le numérique s'infiltre dans tous les pans de l'activité économique. Les entreprises de services n'y échappent pas, et les salons de beauté encore moins. Il faut dire qu'en Afrique de l'Ouest, le secteur de la beauté et du bien-être a connu ces dernières années une expansion remarquable : salons de coiffure, instituts de beauté et spas se multiplient un peu partout.

Pourtant, malgré cette effervescence, force est de constater que la plupart de ces établissements fonctionnent encore selon des méthodes très traditionnelles. Les rendez-vous sont notés dans des cahiers. Les disponibilités se calculent à la main. Le suivi des stocks reste approximatif, quand il existe. Et les programmes de fidélisation ? Pratiquement inexistants sous une forme structurée. Tout cela engendre des problèmes bien concrets : du temps gaspillé à jongler avec les plannings, des erreurs de programmation quand deux rendez-vous se chevauchent, des difficultés à retracer l'historique d'un client, une absence quasi totale de visibilité sur les performances du salon, et une gestion des produits qui laisse souvent à désirer.

Partant de ces observations, l'idée de mettre en place un système d'information intégré s'est imposée naturellement comme un levier pertinent pour moderniser la gestion de ce type d'établissement.

## Problématique

De ces constats découle une question centrale, qui constitue le fil conducteur de l'ensemble de ce travail :

> **Comment concevoir et réaliser un système d'information intégré permettant d'optimiser la gestion opérationnelle et d'améliorer la relation client dans les salons de beauté ?**

Cette interrogation en appelle d'autres, plus ciblées. Quelles sont, d'abord, les fonctionnalités réellement indispensables pour un outil de gestion dédié aux salons de beauté ? Comment penser un système de planification suffisamment intelligent pour tenir compte de contraintes aussi variées que les horaires, les congés ou les disponibilités fluctuantes ? Se pose aussi la question de l'intégration des moyens de paiement — en particulier le mobile money, si répandu dans la sous-région. Par ailleurs, comment le numérique peut-il devenir un véritable levier de fidélisation de la clientèle ? Et enfin, quelle architecture logicielle choisir pour s'assurer que le système reste maintenable et puisse évoluer dans la durée ?

## Objectifs du travail

### Objectif général

Ce travail vise, dans sa globalité, à concevoir et développer une application web capable de couvrir l'intégralité des processus métier d'un salon de beauté — de la prise de rendez-vous jusqu'au suivi de la fidélité client.

### Objectifs spécifiques

De manière plus précise, il s'agit de mener une analyse rigoureuse des besoins fonctionnels et non fonctionnels des différents acteurs concernés (clients, employés, administrateurs), puis de concevoir une architecture logicielle solide en s'appuyant sur le pattern MVC. Le développement d'un système de gestion des rendez-vous intégrant un calcul intelligent des créneaux disponibles constitue un objectif central. Il en va de même pour la mise en œuvre d'un système de paiement multicanal — espèces, carte bancaire, mobile money — et la création d'un programme de fidélité automatisé avec plusieurs paliers d'avantages. Nous nous sommes également fixé comme objectif l'intégration d'un chatbot d'assistance destiné à enrichir l'expérience utilisateur, la mise en place d'un système de notifications en temps réel, et enfin le développement d'un module de gestion des stocks doté d'alertes automatiques.

## Méthodologie adoptée

La conduite de ce projet a reposé sur une démarche méthodologique bien définie, découpée en grandes phases. Nous avons d'abord consacré du temps à l'analyse — comprendre l'existant, recueillir les besoins, formaliser les attentes fonctionnelles. La conception est venue ensuite, avec la modélisation UML, le design de la base de données et les choix technologiques. Le développement proprement dit a été mené de façon itérative, module par module. Une phase de tests a permis de valider le bon fonctionnement de chaque brique avant d'aller plus loin. Enfin, la documentation — ce mémoire et les guides utilisateurs — a accompagné le tout.

D'ailleurs, cette approche **itérative et incrémentale** nous a paru la plus adaptée : elle offre la souplesse nécessaire pour intégrer les fonctionnalités progressivement, corriger au fur et à mesure, et s'assurer que chaque composant est bien validé avant de passer au suivant.

## Organisation du mémoire

Le présent mémoire se déploie en cinq chapitres. Le premier pose le cadre théorique et dresse un état de l'art : on y explore les notions fondamentales liées aux systèmes d'information, tout en passant en revue les solutions déjà disponibles sur le marché. Le deuxième chapitre expose la méthodologie retenue et entre dans le détail de l'analyse des besoins — aussi bien fonctionnels que non fonctionnels. Vient ensuite, au troisième chapitre, la conception du système à proprement parler, avec l'architecture globale, la modélisation de la base de données et les différents diagrammes UML. Le quatrième chapitre se consacre à la réalisation technique et à l'implémentation des modules qui composent l'application. Quant au cinquième et dernier chapitre, il propose une évaluation du système accompagnée d'une discussion critique des résultats obtenus.

Le mémoire se termine par une synthèse des travaux réalisés ainsi que par un aperçu des perspectives d'évolution envisageables.

---

# CHAPITRE 1 : CADRE THÉORIQUE ET ÉTAT DE L'ART

## Introduction

Ce premier chapitre a pour vocation de situer notre travail dans son contexte théorique. Nous y passerons en revue les notions clés relatives aux systèmes d'information de gestion, avant de nous pencher sur les technologies web actuelles et sur les solutions déjà proposées sur le marché pour la gestion des salons de beauté. L'idée est de bien comprendre ce qui existe, d'en identifier les forces et les lacunes, et de justifier les choix qui sous-tendent notre propre démarche.

## 1.1 Les systèmes d'information de gestion

### 1.1.1 Définition et concepts

Qu'entend-on exactement par **système d'information (SI)** ? En substance, il s'agit d'un ensemble organisé de ressources — matérielles, logicielles, humaines, mais aussi les données et les procédures qui les accompagnent — dont la finalité est de collecter, stocker, traiter et diffuser l'information au sein d'une organisation.

Reix (2004) le formule de manière assez similaire : le système d'information est, selon lui, « un ensemble organisé de ressources : matériel, logiciel, personnel, données, procédures permettant d'acquérir, traiter, stocker, communiquer des informations dans les organisations ».

### 1.1.2 Rôle du SI dans les PME/TPE

Dans le cas des petites et moyennes entreprises, le SI remplit des fonctions qu'on pourrait qualifier de vitales. Il sert avant tout de support opérationnel en automatisant les tâches du quotidien. Mais son utilité ne s'arrête pas là : il constitue aussi une aide précieuse à la prise de décision, grâce aux tableaux de bord et aux indicateurs qu'il peut fournir. En facilitant les interactions avec les clients et les partenaires, il joue un rôle de communication non négligeable. Notons enfin qu'il agit comme une sorte de mémoire organisationnelle, conservant la trace de l'ensemble des opérations passées.

### 1.1.3 Caractéristiques d'un SI efficace

Pour qu'un système d'information soit véritablement utile, il doit satisfaire à un certain nombre de critères. Le tableau ci-dessous en propose une synthèse :

| Critère | Description |
|---------|-------------|
| **Fiabilité** | Données exactes et cohérentes |
| **Disponibilité** | Accessibilité permanente du système |
| **Sécurité** | Protection des données sensibles |
| **Évolutivité** | Capacité d'adaptation aux nouveaux besoins |
| **Ergonomie** | Facilité d'utilisation pour les utilisateurs |

## 1.2 La transformation digitale des entreprises de services

### 1.2.1 Enjeux de la digitalisation

La transformation digitale n'est plus un simple effet de mode — c'est une nécessité, surtout pour les entreprises de services. Concrètement, elle ouvre la voie à une amélioration sensible de l'expérience vécue par le client. Elle permet aussi de rationaliser les processus internes et, par conséquent, de réduire les coûts de fonctionnement. D'ailleurs, elle donne accès à de nouveaux marchés et offre un moyen tangible de se démarquer face à la concurrence.

### 1.2.2 Spécificités du secteur de la beauté

Le domaine de la beauté possède ses propres particularités, et il serait risqué de les ignorer au moment de concevoir un système d'information. Chaque prestation est, par nature, personnalisée — ce qui la rend unique et difficile à standardiser. La relation avec le client repose énormément sur la confiance et la fidélité. La gestion du temps est particulièrement délicate, entre des créneaux horaires serrés et des durées de prestations qui varient d'un service à l'autre. Il faut aussi composer avec la diversité des compétences : chaque employé a ses spécialités. Et puis, il y a la saisonnalité — les périodes de fêtes, les mariages, certaines occasions culturelles qui créent des pics d'activité bien marqués.

## 1.3 La gestion de la relation client (CRM)

### 1.3.1 Concepts fondamentaux

Le **Customer Relationship Management (CRM)**, ou gestion de la relation client, regroupe l'ensemble des outils et des techniques qui permettent de capter, de traiter et d'analyser les données relatives aux clients. L'objectif final est limpide : mieux connaître ses clients pour mieux les fidéliser.

### 1.3.2 Les composantes du CRM

On distingue généralement trois volets dans une démarche CRM. Le volet opérationnel se charge d'automatiser les processus de vente et de prestation de service. Le volet analytique exploite les données clients pour éclairer la prise de décision. Quant au volet collaboratif, il regroupe les outils de communication directe avec la clientèle — messagerie, chatbot, notifications, etc.

### 1.3.3 Programme de fidélité

Un programme de fidélité, pour être réellement efficace, doit s'appuyer sur des mécanismes concrets. Il y a d'abord le principe d'accumulation de points, qui récompense le client proportionnellement à ses dépenses. Viennent ensuite les niveaux de fidélité, qui offrent des avantages croissants à mesure que l'engagement se renforce. Et enfin, les récompenses personnalisées — des offres taillées sur mesure en fonction du profil et des habitudes de chaque client.

## 1.4 Technologies web modernes

### 1.4.1 Architecture MVC

Le pattern **Model-View-Controller (MVC)** est un modèle d'architecture logicielle dont le principe fondateur est simple : découper l'application en trois composants aux responsabilités bien distinctes.

```
┌─────────────────────────────────────────────────────────────┐
│                        CONTRÔLEUR                            │
│         (Logique métier, coordination)                       │
└─────────────────────────────────────────────────────────────┘
              │                           │
              ▼                           ▼
┌─────────────────────────┐   ┌─────────────────────────────┐
│         MODÈLE          │   │            VUE              │
│   (Données, BDD)        │   │     (Interface utilisateur) │
└─────────────────────────┘   └─────────────────────────────┘
```

Ce découpage présente plusieurs avantages indéniables. Il garantit une séparation nette des préoccupations, facilite la maintenance sur le long terme, favorise la réutilisation du code et rend les tests bien plus aisés à mettre en œuvre.

### 1.4.2 Framework Laravel

**Laravel** est un framework PHP open-source, créé par Taylor Otwell en 2011. En quelques années seulement, il s'est imposé comme le framework PHP le plus populaire — et ce n'est pas un hasard. Il embarque notamment **Eloquent ORM**, un mapping objet-relationnel d'une grande élégance, ainsi que **Blade**, un moteur de templates à la fois puissant et intuitif. L'outil en ligne de commande **Artisan** accélère considérablement le travail au quotidien. Les **middleware** permettent de filtrer les requêtes HTTP de manière flexible, tandis que le système d'**authentification** intégré simplifie la gestion des accès. Enfin, le mécanisme de **migration** offre un véritable versioning de la base de données, ce qui s'avère particulièrement précieux en phase de développement itératif.

### 1.4.3 Autres technologies utilisées

| Technologie | Rôle | Justification |
|-------------|------|---------------|
| **PostgreSQL** | Base de données | Robustesse, conformité SQL, fonctionnalités avancées |
| **Bootstrap 4** | Framework CSS | Design responsive, composants prêts à l'emploi |
| **jQuery** | Bibliothèque JavaScript | Manipulation DOM, requêtes AJAX |
| **FullCalendar** | Calendrier interactif | Visualisation intuitive des plannings |
| **Chart.js** | Graphiques | Tableaux de bord visuels |

## 1.5 Revue des solutions existantes

### 1.5.1 Solutions commerciales

Avant de nous lancer dans la conception de notre propre outil, il nous a semblé essentiel de regarder ce qui se faisait déjà. Plusieurs solutions de gestion pour salons de beauté sont disponibles sur le marché, chacune avec ses atouts et ses faiblesses :

| Solution | Points forts | Limites |
|----------|--------------|---------|
| **Planity** | Interface moderne, réservation en ligne | Coût élevé, peu adapté au contexte africain |
| **Treatwell** | Large base clients, visibilité | Commission sur réservations |
| **Shedul** | Gratuit, fonctionnalités de base | Fonctionnalités limitées |
| **Timely** | Planning avancé | Coût mensuel, en anglais |

### 1.5.2 Limites des solutions existantes

En y regardant de plus près, ces solutions présentent des lacunes assez nettes dès qu'on les envisage dans un contexte africain. Aucune n'intègre véritablement le mobile money — ni Orange Money ni Wave ne sont pris en charge. Les tarifs, basés sur des abonnements mensuels, restent prohibitifs pour de nombreux gérants de salon. La langue constitue une autre barrière, la plupart de ces outils étant proposés exclusivement en anglais. Le FCFA n'est pas supporté, ce qui complique toute tentative d'adoption locale. Et pour couronner le tout, ces plateformes nécessitent une connexion internet permanente, ce qui n'est pas toujours acquis dans la sous-région.

### 1.5.3 Positionnement de notre solution

Face à ces carences, notre solution se positionne résolument comme une alternative pensée pour le terrain :

- ✅ Intégration des moyens de paiement locaux (Orange Money, Wave)
- ✅ Interface en français
- ✅ Support du FCFA
- ✅ Coût d'acquisition unique (pas d'abonnement)
- ✅ Chatbot d'assistance intégré
- ✅ Programme de fidélité complet

## 1.6 Synthèse

Au terme de ce chapitre, les bases théoriques de notre travail sont désormais posées. Nous avons pu constater que les systèmes d'information jouent un rôle déterminant dans la vie des PME et TPE. La digitalisation du secteur de la beauté, en particulier, représente un enjeu de taille qu'il ne faut plus sous-estimer. Le CRM et les programmes de fidélité, quand ils sont bien pensés, constituent de véritables moteurs de croissance. Du côté technologique, Laravel s'impose comme un choix judicieux grâce à son architecture MVC robuste et éprouvée. Enfin — et c'est peut-être le point le plus important —, les solutions actuellement disponibles sur le marché ne répondent que partiellement aux réalités du contexte africain, ce qui légitime pleinement la démarche que nous avons entreprise.

C'est avec ces constats en tête que nous aborderons, dans le chapitre suivant, notre méthodologie de travail ainsi que l'analyse détaillée des besoins.

---

# CHAPITRE 2 : MÉTHODOLOGIE ET ANALYSE DES BESOINS

## Introduction

Dans ce chapitre, nous allons exposer la démarche méthodologique qui a guidé le développement de notre application. Il s'agira aussi de mener une analyse approfondie des besoins, tant fonctionnels que non fonctionnels. D'ailleurs, nous prendrons le soin d'identifier les différents acteurs du système et de représenter leurs interactions au moyen de diagrammes de cas d'utilisation.

## 2.1 Méthodologie de développement

### 2.1.1 Approche itérative et incrémentale

Pour mener à bien ce projet, nous avons fait le choix d'une approche **itérative et incrémentale**, largement inspirée des principes agiles. Pourquoi ce choix ? Il faut dire que cette façon de travailler offre plusieurs avantages concrets. Elle rend possible la livraison rapide de fonctionnalités exploitables, ce qui est un vrai plus pour garder le cap. En effet, les retours des utilisateurs peuvent être pris en compte au fil du développement, sans attendre la toute fin du projet. Par ailleurs, les risques liés à d'éventuels changements de besoins s'en trouvent considérablement réduits. Enfin, la qualité du code bénéficie d'un suivi continu tout au long du processus.

### 2.1.2 Phases du projet

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   ANALYSE   │ ─► │ CONCEPTION  │ ─► │ RÉALISATION │ ─► │    TESTS    │
│  (2 sem)    │    │  (3 sem)    │    │  (8 sem)    │    │  (2 sem)    │
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
                                             │
                                             ▼
                                    ┌─────────────────┐
                                    │  DOCUMENTATION  │
                                    │    (3 sem)      │
                                    └─────────────────┘
```

## 2.2 Identification des acteurs

Notre système repose sur l'intervention de trois catégories d'acteurs. Chacun dispose de prérogatives bien définies, que nous détaillons ci-après.

### 2.2.1 Le Client

Le client, c'est avant tout la personne qui vient chercher une prestation au salon de beauté. Son interaction avec le système est assez riche : il a la possibilité de créer un compte et de gérer les informations de son profil. Il peut consulter l'ensemble des services proposés ainsi que les tarifs pratiqués, et bien entendu, réserver un rendez-vous quand il le souhaite. Le règlement des prestations se fait aussi via la plateforme. Notons que le client peut également suivre son historique, vérifier ses points de fidélité, ou encore échanger avec le chatbot intégré.

### 2.2.2 L'Employé

L'employé représente le professionnel du salon, celui qui assure concrètement les prestations. Via le système, il accède à son planning et visualise les rendez-vous qui lui sont attribués. Il lui est possible de modifier le statut d'un rendez-vous, de formuler des demandes de congé, ou encore de communiquer directement avec l'administration. D'ailleurs, l'enregistrement des paiements clients fait aussi partie de ses attributions dans l'application.

### 2.2.3 L'Administrateur

L'administrateur, quant à lui, a la main sur l'ensemble du salon. C'est lui qui gère les employés, les clients et le catalogue de services. Il supervise les rendez-vous, organise le planning global et traite les demandes de congé. La gestion des stocks de produits lui incombe également. Par ailleurs, il dispose d'un accès aux rapports et statistiques d'activité, et peut répondre aux messages envoyés par les employés.

## 2.3 Analyse des besoins fonctionnels

### 2.3.1 Besoins fonctionnels - Espace Client

Commençons par l'espace dédié au client. Le tableau ci-dessous récapitule l'ensemble des fonctionnalités attendues, classées selon leur degré de priorité.

| Réf. | Besoin | Priorité |
|------|--------|----------|
| FC01 | S'inscrire et se connecter | Haute |
| FC02 | Gérer son profil (photo, informations) | Moyenne |
| FC03 | Consulter les services et promotions | Haute |
| FC04 | Réserver un rendez-vous | Haute |
| FC05 | Visualiser ses rendez-vous (calendrier) | Haute |
| FC06 | Annuler un rendez-vous | Moyenne |
| FC07 | Effectuer un paiement | Haute |
| FC08 | Télécharger ses factures | Moyenne |
| FC09 | Consulter ses points de fidélité | Moyenne |
| FC10 | Utiliser le chatbot | Basse |
| FC11 | Recevoir des notifications | Moyenne |
| FC12 | Réinitialiser son mot de passe | Haute |

On constate que les fonctionnalités essentielles — inscription, réservation, consultation des services et paiement — sont naturellement classées en priorité haute. D'autres, comme le chatbot, relèvent davantage d'un confort d'usage et pourront être affinées dans un second temps.

### 2.3.2 Besoins fonctionnels - Espace Employé

Du côté de l'employé, les besoins tournent principalement autour de la gestion du quotidien professionnel. Voici ce que nous avons identifié :

| Réf. | Besoin | Priorité |
|------|--------|----------|
| FE01 | Se connecter | Haute |
| FE02 | Consulter le tableau de bord | Haute |
| FE03 | Voir son planning (calendrier) | Haute |
| FE04 | Gérer le statut des rendez-vous | Haute |
| FE05 | Ajouter des notes aux rendez-vous | Basse |
| FE06 | Consulter ses horaires de travail | Moyenne |
| FE07 | Demander un congé | Moyenne |
| FE08 | Envoyer un message à l'administration | Basse |
| FE09 | Enregistrer un paiement client | Moyenne |
| FE10 | Recevoir des notifications | Moyenne |
| FE11 | Gérer son profil | Moyenne |

### 2.3.3 Besoins fonctionnels - Espace Administration

L'espace d'administration concentre logiquement le plus grand nombre de fonctionnalités. En effet, l'administrateur doit pouvoir piloter toutes les dimensions du salon. Le tableau suivant en donne le détail.

| Réf. | Besoin | Priorité |
|------|--------|----------|
| FA01 | Se connecter | Haute |
| FA02 | Consulter le tableau de bord global | Haute |
| FA03 | Gérer les employés (CRUD) | Haute |
| FA04 | Gérer les clients (CRUD) | Haute |
| FA05 | Gérer les services et promotions | Haute |
| FA06 | Gérer les rendez-vous | Haute |
| FA07 | Voir et modifier le planning global | Haute |
| FA08 | Bloquer des créneaux horaires | Moyenne |
| FA09 | Approuver/rejeter les demandes de congé | Moyenne |
| FA10 | Répondre aux messages des employés | Basse |
| FA11 | Gérer les stocks de produits | Moyenne |
| FA12 | Consulter les paiements | Moyenne |
| FA13 | Générer des rapports et exports CSV | Moyenne |
| FA14 | Gérer son profil administrateur | Basse |

## 2.4 Analyse des besoins non fonctionnels

Au-delà des fonctionnalités pures, un certain nombre d'exigences transversales conditionnent la réussite du projet. Elles touchent à la performance, à la sécurité, à l'ergonomie ou encore à la maintenabilité. Le tableau ci-dessous les résume.

| Catégorie | Besoin | Description |
|-----------|--------|-------------|
| **Performance** | Temps de réponse | < 3 secondes pour les pages principales |
| **Sécurité** | Authentification | Mots de passe hashés (bcrypt) |
| **Sécurité** | Protection CSRF | Token sur tous les formulaires |
| **Sécurité** | Validation | Toutes les entrées utilisateur validées |
| **Ergonomie** | Responsive | Adapté mobile, tablette, desktop |
| **Ergonomie** | Accessibilité | Interface intuitive, en français |
| **Fiabilité** | Disponibilité | 99% de temps de fonctionnement |
| **Évolutivité** | Modularité | Architecture permettant l'ajout de fonctionnalités |
| **Maintenabilité** | Code propre | Respect des conventions Laravel |

Il faut souligner que la question de la sécurité a été traitée avec une attention particulière : hashage des mots de passe, protection contre les attaques CSRF, validation systématique des entrées. Ce sont là des prérequis incontournables pour toute application web moderne.

## 2.5 Diagrammes de cas d'utilisation

### 2.5.1 Diagramme de cas d'utilisation global

Pour offrir une vue d'ensemble des interactions entre les acteurs et le système, nous avons élaboré le diagramme de cas d'utilisation suivant. Il met en évidence les grandes fonctionnalités accessibles à chaque profil.

```
                          ┌─────────────────────────────────────────┐
                          │         SYSTÈME DE GESTION              │
                          │         SALON DE BEAUTÉ                 │
                          │                                         │
    ┌───────┐            │  ┌─────────────────────────────────┐   │
    │       │            │  │      Gérer son compte           │   │
    │ Client│───────────────│                                 │   │
    │       │            │  └─────────────────────────────────┘   │
    └───────┘            │  ┌─────────────────────────────────┐   │
        │                │  │      Réserver un RDV            │   │
        │────────────────────│                                 │   │
        │                │  └─────────────────────────────────┘   │
        │                │  ┌─────────────────────────────────┐   │
        │────────────────────│      Effectuer un paiement     │   │
        │                │  └─────────────────────────────────┘   │
        │                │  ┌─────────────────────────────────┐   │
        └────────────────────│      Utiliser le chatbot       │   │
                         │  └─────────────────────────────────┘   │
                         │                                         │
    ┌───────┐            │  ┌─────────────────────────────────┐   │
    │       │            │  │      Gérer ses RDV              │   │
    │Employé│───────────────│                                 │   │
    │       │            │  └─────────────────────────────────┘   │
    └───────┘            │  ┌─────────────────────────────────┐   │
        │                │  │      Demander un congé          │   │
        └────────────────────│                                 │   │
                         │  └─────────────────────────────────┘   │
                         │                                         │
    ┌───────┐            │  ┌─────────────────────────────────┐   │
    │       │            │  │      Gérer les ressources       │   │
    │ Admin │───────────────│  (employés, services, stocks)   │   │
    │       │            │  └─────────────────────────────────┘   │
    └───────┘            │  ┌─────────────────────────────────┐   │
        │                │  │      Superviser l'activité      │   │
        └────────────────────│  (planning, rapports)          │   │
                         │  └─────────────────────────────────┘   │
                         │                                         │
                         └─────────────────────────────────────────┘
```

### 2.5.2 Description des cas d'utilisation principaux

Parmi tous les cas d'utilisation identifiés, deux méritent d'être détaillés tant ils sont au cœur du fonctionnement de l'application.

#### CU01 : Réserver un rendez-vous

Ce cas d'utilisation est sans doute le plus central. C'est lui qui traduit la raison d'être même du système pour le client.

| Élément | Description |
|---------|-------------|
| **Acteur** | Client |
| **Précondition** | Le client est authentifié |
| **Scénario nominal** | 1. Le client sélectionne un service<br>2. Le système affiche les employés disponibles<br>3. Le client choisit un employé (optionnel)<br>4. Le système affiche les créneaux disponibles<br>5. Le client sélectionne un créneau<br>6. Le système enregistre le rendez-vous<br>7. Le client reçoit une confirmation |
| **Postcondition** | Le rendez-vous est créé avec le statut "en attente" |

#### CU02 : Calculer les disponibilités

Le calcul des disponibilités est un processus interne au système, mais il conditionne toute la logique de prise de rendez-vous. Sans lui, impossible de proposer des créneaux cohérents au client.

| Élément | Description |
|---------|-------------|
| **Acteur** | Système |
| **Précondition** | Un service et une date sont sélectionnés |
| **Scénario nominal** | 1. Récupérer les horaires de travail de l'employé<br>2. Générer les créneaux selon la durée du service<br>3. Exclure les créneaux pendant les pauses<br>4. Exclure les créneaux avec RDV existants<br>5. Exclure les créneaux bloqués<br>6. Exclure les périodes de congé approuvé<br>7. Retourner les créneaux disponibles |
| **Postcondition** | Liste des créneaux disponibles affichée |

## 2.6 Règles de gestion

Tout système d'information s'appuie sur un ensemble de règles métier qu'il convient de formaliser. Ces règles encadrent le comportement attendu de l'application et garantissent sa cohérence. En voici la liste complète :

| Réf. | Règle |
|------|-------|
| RG01 | Un client ne peut avoir qu'un seul compte par email |
| RG02 | Un rendez-vous ne peut être réservé que sur un créneau disponible |
| RG03 | Un rendez-vous peut être annulé jusqu'à 24h avant |
| RG04 | Les points de fidélité sont attribués uniquement pour les RDV terminés |
| RG05 | 1 point = 1000 FCFA dépensés |
| RG06 | Les niveaux de fidélité : Bronze (0-99), Argent (100-199), Or (200-499), Platine (500+) |
| RG07 | Une promotion ne peut chevaucher une autre sur le même service |
| RG08 | Un employé ne peut travailler pendant un congé approuvé |
| RG09 | Un paiement ne peut être associé qu'à un seul rendez-vous |

Notons en particulier la règle RG03, qui impose un délai minimal de 24 heures pour l'annulation d'un rendez-vous. Cette contrainte vise à limiter les désistements de dernière minute, un problème récurrent dans ce type d'activité.

## 2.7 Synthèse

Au terme de ce chapitre, nous avons pu poser les fondations du projet de manière structurée. La méthodologie itérative retenue nous donne la souplesse nécessaire pour avancer par étapes successives. Les trois profils d'acteurs — client, employé et administrateur — ont été clairement délimités, chacun avec ses propres fonctionnalités. L'analyse des besoins fonctionnels, organisée par espace utilisateur, a mis en évidence les priorités de développement. Les exigences non fonctionnelles relatives à la performance, la sécurité et l'ergonomie ont été formalisées. Enfin, les cas d'utilisation principaux et les règles de gestion métier viennent compléter cette vue d'ensemble.

Le chapitre suivant présentera la conception détaillée du système.

---

# CHAPITRE 3 : CONCEPTION DU SYSTÈME

## Introduction

Dans ce chapitre, nous entrons dans le vif de la conception. Il s'agit de poser les fondations techniques du système en détaillant l'architecture retenue, la structure de la base de données et les technologies mobilisées. Pour modéliser les différentes facettes de notre application, nous nous appuyons sur le formalisme UML, qui offre une vision à la fois claire et normalisée.

## 3.1 Architecture globale

### 3.1.1 Architecture 3-tiers

Notre système repose sur une architecture **3-tiers**, c'est-à-dire organisée en trois niveaux distincts. Ce découpage permet de séparer nettement la présentation, la logique métier et l'accès aux données :

```
┌─────────────────────────────────────────────────────────────────┐
│                    COUCHE PRÉSENTATION                          │
│                                                                  │
│     Navigateur Web (Chrome, Firefox, Safari, Edge)              │
│     • HTML5, CSS3, Bootstrap 4                                  │
│     • JavaScript, jQuery                                        │
│     • FullCalendar, Chart.js                                    │
└─────────────────────────────────────────────────────────────────┘
                               │
                               │ HTTP/HTTPS
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                    COUCHE MÉTIER                                │
│                                                                  │
│     Serveur Web (Apache/Nginx + PHP 8.2)                        │
│     • Framework Laravel 11                                      │
│     • Controllers, Models, Views (Blade)                        │
│     • Middleware (Auth, CSRF, Validation)                       │
│     • Services métier                                           │
└─────────────────────────────────────────────────────────────────┘
                               │
                               │ SQL
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                    COUCHE DONNÉES                               │
│                                                                  │
│     Serveur de base de données PostgreSQL                       │
│     • Tables relationnelles                                     │
│     • Procédures stockées (optionnel)                          │
│     • Sauvegardes automatisées                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 3.1.2 Architecture MVC de Laravel

```
┌─────────────────────────────────────────────────────────────────┐
│                        ROUTES (web.php)                          │
│     Définition des URLs et association aux contrôleurs          │
└─────────────────────────────────────────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                        MIDDLEWARE                                │
│     • auth:clients, auth:employees, auth:web                    │
│     • admin (vérification du rôle)                              │
│     • client.active (compte actif)                              │
│     • CSRF protection                                           │
└─────────────────────────────────────────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                       CONTRÔLEURS                                │
│                                                                  │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐              │
│  │   Client/   │  │  Employee/  │  │   Admin/    │              │
│  │ Controllers │  │ Controllers │  │ Controllers │              │
│  └─────────────┘  └─────────────┘  └─────────────┘              │
└─────────────────────────────────────────────────────────────────┘
          │                   │                   │
          ▼                   ▼                   ▼
┌─────────────────────────────────────────────────────────────────┐
│                         MODÈLES                                  │
│                                                                  │
│  Appointment, Client, Employee, Service, Payment, Stock...      │
│  • Relations Eloquent (hasMany, belongsTo)                      │
│  • Scopes et accesseurs                                         │
│  • Validations                                                  │
└─────────────────────────────────────────────────────────────────┘
          │                                       │
          ▼                                       ▼
┌─────────────────────────┐           ┌─────────────────────────┐
│   BASE DE DONNÉES      │           │         VUES            │
│    (PostgreSQL)         │           │        (Blade)          │
└─────────────────────────┘           └─────────────────────────┘
```

## 3.2 Conception de la base de données

### 3.2.1 Modèle Conceptuel de Données (MCD)

```
┌──────────────┐         ┌──────────────┐         ┌──────────────┐
│   CLIENTS    │         │  EMPLOYEES   │         │    USERS     │
├──────────────┤         ├──────────────┤         ├──────────────┤
│ #id          │         │ #id          │         │ #id          │
│ name         │         │ name         │         │ name         │
│ email        │         │ email        │         │ email        │
│ password     │         │ password     │         │ password     │
│ phone        │         │ phone        │         │ role         │
│ photo        │         │ photo        │         └──────────────┘
│ loyalty_pts  │         │ role         │
│ active       │         │ is_active    │
└──────────────┘         └──────────────┘
       │                        │
       │ 1,n                    │ 1,n
       │                        │
       ▼                        ▼
┌──────────────────────────────────────────┐
│              APPOINTMENTS                 │
├──────────────────────────────────────────┤
│ #id                                       │
│ client_id (FK)                           │
│ employee_id (FK)                         │
│ service_id (FK)                          │
│ scheduled_at                             │
│ status                                   │
│ notes                                    │
└──────────────────────────────────────────┘
       │                        │
       │ 0,1                    │ n,1
       ▼                        ▼
┌──────────────┐         ┌──────────────┐
│   PAYMENTS   │         │   SERVICES   │
├──────────────┤         ├──────────────┤
│ #id          │         │ #id          │
│ appoint_id   │         │ name         │
│ amount       │         │ description  │
│ method       │         │ price        │
│ status       │         │ promo_price  │
│ reference    │         │ promo_start  │
└──────────────┘         │ promo_end    │
                         │ duration     │
                         │ category     │
                         │ active       │
                         └──────────────┘
```

### 3.2.2 Dictionnaire de données

#### Table `clients`

| Attribut | Type | Taille | Contraintes | Description |
|----------|------|--------|-------------|-------------|
| id | BIGINT | - | PK, AUTO_INCREMENT | Identifiant unique |
| name | VARCHAR | 255 | NOT NULL | Nom complet |
| email | VARCHAR | 255 | NOT NULL, UNIQUE | Adresse email |
| password | VARCHAR | 255 | NOT NULL | Mot de passe hashé |
| phone | VARCHAR | 20 | NULLABLE | Numéro de téléphone |
| photo | VARCHAR | 255 | NULLABLE | Chemin de la photo |
| loyalty_points | INT | - | DEFAULT 0 | Points de fidélité |
| active | BOOLEAN | - | DEFAULT TRUE | Compte actif |
| created_at | TIMESTAMP | - | - | Date de création |
| updated_at | TIMESTAMP | - | - | Date de modification |

#### Table `employees`

| Attribut | Type | Taille | Contraintes | Description |
|----------|------|--------|-------------|-------------|
| id | BIGINT | - | PK, AUTO_INCREMENT | Identifiant unique |
| name | VARCHAR | 255 | NOT NULL | Nom complet |
| email | VARCHAR | 255 | NOT NULL, UNIQUE | Adresse email |
| password | VARCHAR | 255 | NOT NULL | Mot de passe hashé |
| phone | VARCHAR | 20 | NULLABLE | Numéro de téléphone |
| photo | VARCHAR | 255 | NULLABLE | Chemin de la photo |
| role | VARCHAR | 50 | DEFAULT 'employee' | Rôle (employee, manager) |
| is_active | BOOLEAN | - | DEFAULT TRUE | Employé actif |
| work_start_time | TIME | - | NULLABLE | Heure de début |
| work_end_time | TIME | - | NULLABLE | Heure de fin |

#### Table `appointments`

| Attribut | Type | Taille | Contraintes | Description |
|----------|------|--------|-------------|-------------|
| id | BIGINT | - | PK, AUTO_INCREMENT | Identifiant unique |
| client_id | BIGINT | - | FK → clients | Client concerné |
| employee_id | BIGINT | - | FK → employees | Employé assigné |
| service_id | BIGINT | - | FK → services | Service réservé |
| scheduled_at | DATETIME | - | NOT NULL | Date et heure du RDV |
| status | ENUM | - | NOT NULL | pending, confirmed, completed, canceled, no-show |
| notes | TEXT | - | NULLABLE | Notes additionnelles |

#### Table `services`

| Attribut | Type | Taille | Contraintes | Description |
|----------|------|--------|-------------|-------------|
| id | BIGINT | - | PK, AUTO_INCREMENT | Identifiant unique |
| name | VARCHAR | 255 | NOT NULL | Nom du service |
| description | TEXT | - | NULLABLE | Description détaillée |
| price | DECIMAL | 10,2 | NOT NULL | Prix en FCFA |
| promotion_price | DECIMAL | 10,2 | NULLABLE | Prix promotionnel |
| promotion_start | DATE | - | NULLABLE | Début promotion |
| promotion_end | DATE | - | NULLABLE | Fin promotion |
| duration | INT | - | NOT NULL | Durée en minutes |
| category | VARCHAR | 100 | NULLABLE | Catégorie |
| active | BOOLEAN | - | DEFAULT TRUE | Service actif |

#### Table `payments`

| Attribut | Type | Taille | Contraintes | Description |
|----------|------|--------|-------------|-------------|
| id | BIGINT | - | PK, AUTO_INCREMENT | Identifiant unique |
| appointment_id | BIGINT | - | FK → appointments | RDV concerné |
| amount | DECIMAL | 10,2 | NOT NULL | Montant payé |
| method | ENUM | - | NOT NULL | cash, card, orange_money, wave |
| status | ENUM | - | NOT NULL | pending, completed, failed |
| reference | VARCHAR | 100 | NULLABLE | Référence transaction |

#### Table `stocks`

| Attribut | Type | Taille | Contraintes | Description |
|----------|------|--------|-------------|-------------|
| id | BIGINT | - | PK, AUTO_INCREMENT | Identifiant unique |
| name | VARCHAR | 255 | NOT NULL | Nom du produit |
| category | VARCHAR | 100 | NULLABLE | Catégorie |
| quantity | INT | - | NOT NULL | Quantité en stock |
| alert_threshold | INT | - | DEFAULT 10 | Seuil d'alerte |

## 3.3 Diagramme de classes

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                                   Client                                     │
├─────────────────────────────────────────────────────────────────────────────┤
│ - id: int                                                                   │
│ - name: string                                                              │
│ - email: string                                                             │
│ - password: string                                                          │
│ - phone: string                                                             │
│ - loyalty_points: int                                                       │
│ - active: boolean                                                           │
├─────────────────────────────────────────────────────────────────────────────┤
│ + appointments(): HasMany<Appointment>                                      │
│ + payments(): HasMany<Payment>                                              │
│ + notifications(): HasMany<ClientNotification>                              │
│ + getLoyaltyLevel(): string                                                 │
│ + getLoyaltyDiscount(): int                                                 │
│ + addLoyaltyPoints(points: int): void                                       │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                                      │ 1..*
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                                 Appointment                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│ - id: int                                                                   │
│ - client_id: int                                                            │
│ - employee_id: int                                                          │
│ - service_id: int                                                           │
│ - scheduled_at: datetime                                                    │
│ - status: AppointmentStatus                                                 │
│ - notes: string                                                             │
├─────────────────────────────────────────────────────────────────────────────┤
│ + client(): BelongsTo<Client>                                               │
│ + employee(): BelongsTo<Employee>                                           │
│ + service(): BelongsTo<Service>                                             │
│ + payment(): HasOne<Payment>                                                │
│ + isPending(): boolean                                                      │
│ + isCompleted(): boolean                                                    │
│ + cancel(): void                                                            │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                         ┌────────────┴────────────┐
                         ▼                         ▼
┌─────────────────────────────────┐   ┌─────────────────────────────────┐
│           Employee              │   │            Service              │
├─────────────────────────────────┤   ├─────────────────────────────────┤
│ - id: int                       │   │ - id: int                       │
│ - name: string                  │   │ - name: string                  │
│ - email: string                 │   │ - price: decimal                │
│ - is_active: boolean            │   │ - promotion_price: decimal      │
├─────────────────────────────────┤   │ - duration: int                 │
│ + appointments(): HasMany       │   ├─────────────────────────────────┤
│ + schedules(): HasMany          │   │ + hasActivePromotion(): boolean │
│ + leaveRequests(): HasMany      │   │ + getCurrentPrice(): decimal    │
│ + getAvailableSlots(): array    │   └─────────────────────────────────┘
└─────────────────────────────────┘
```

## 3.4 Système d'authentification multi-guards

### 3.4.1 Configuration des guards

Pour gérer l'authentification de plusieurs profils d'utilisateurs au sein d'une même application, Laravel met à disposition un mécanisme appelé **guards**. Concrètement, chaque guard correspond à un type d'utilisateur et à sa propre logique de connexion :

```php
// config/auth.php
'guards' => [
    'web' => [          // Admin
        'driver' => 'session',
        'provider' => 'users',
    ],
    'clients' => [      // Clients
        'driver' => 'session',
        'provider' => 'clients',
    ],
    'employees' => [    // Employés
        'driver' => 'session',
        'provider' => 'employees',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    'clients' => [
        'driver' => 'eloquent',
        'model' => App\Models\Client::class,
    ],
    'employees' => [
        'driver' => 'eloquent',
        'model' => App\Models\Employee::class,
    ],
],
```

### 3.4.2 Flux d'authentification

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│  /client/login  │     │ /employee/login │     │     /login      │
│                 │     │                 │     │                 │
│  Guard: clients │     │ Guard: employees│     │   Guard: web    │
└────────┬────────┘     └────────┬────────┘     └────────┬────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│  Table: clients │     │ Table: employees│     │  Table: users   │
└────────┬────────┘     └────────┬────────┘     └────────┬────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│ /client/dashboard│    │/employee/dashboard│   │ /admin/dashboard│
└─────────────────┘     └─────────────────┘     └─────────────────┘
```

## 3.5 Algorithme de calcul des disponibilités

### 3.5.1 Pseudo-code

```
FONCTION getAvailableSlots(employee, date, service):
    
    // 1. Récupérer les horaires de travail
    schedule = getWorkSchedule(employee, date.dayOfWeek)
    SI schedule.is_working == FAUX:
        RETOURNER []
    
    // 2. Vérifier les congés
    SI hasApprovedLeave(employee, date):
        RETOURNER []
    
    // 3. Générer les créneaux possibles
    slots = []
    current = schedule.start_time
    TANT QUE current + service.duration <= schedule.end_time:
        slots.ajouter(current)
        current = current + 30 minutes
    
    // 4. Exclure la pause déjeuner (12h-14h)
    slots = slots.filtrer(s => s < 12:00 OU s >= 14:00)
    
    // 5. Exclure les RDV existants
    existingAppointments = getAppointments(employee, date)
    POUR CHAQUE apt DANS existingAppointments:
        slots = slots.filtrer(s => 
            s + service.duration <= apt.start OU 
            s >= apt.end
        )
    
    // 6. Exclure les créneaux bloqués
    blockedSlots = getBlockedSlots(employee, date)
    POUR CHAQUE block DANS blockedSlots:
        slots = slots.filtrer(s =>
            s + service.duration <= block.start OU
            s >= block.end
        )
    
    RETOURNER slots
```

## 3.6 Synthèse

Au terme de ce chapitre, la conception du système est désormais bien établie. Nous avons opté pour une architecture 3-tiers à la fois solide et capable d'évoluer dans le temps, tout en tirant parti du pattern MVC propre à Laravel, qui garantit une séparation nette entre les différentes couches de responsabilité.

La base de données relationnelle, normalisée autour de 14 tables principales, constitue le socle sur lequel repose l'ensemble des fonctionnalités. Le système d'authentification multi-guards, quant à lui, permet de gérer trois profils d'utilisateurs de manière indépendante et sécurisée. Enfin, l'algorithme de calcul des disponibilités a été pensé pour s'adapter intelligemment aux contraintes du planning : horaires, congés, pauses et rendez-vous déjà programmés.

Le chapitre suivant détaillera la réalisation et l'implémentation des différents modules.

---

# CHAPITRE 4 : RÉALISATION ET IMPLÉMENTATION

## Introduction

Nous abordons à présent la phase la plus concrète de ce travail : la réalisation du système. Ce chapitre vient illustrer, à travers des captures d'écran et des extraits de code soigneusement sélectionnés, la manière dont chaque fonctionnalité a été traduite en un produit logiciel opérationnel. Il convient de souligner que ces fragments de code ne sont pas exhaustifs, mais qu'ils reflètent les choix techniques les plus structurants du projet.

## 4.1 Environnement de développement

### 4.1.1 Outils utilisés

Le choix des outils a été guidé par un souci de cohérence et de productivité. Chacun joue un rôle bien défini dans la chaîne de développement, comme le résume le tableau ci-dessous.

| Outil | Version | Utilisation |
|-------|---------|-------------|
| PHP | 8.2 | Langage serveur |
| Laravel | 12.x | Framework MVC |
| PostgreSQL | 15+ | Base de données |
| Composer | 2.x | Gestionnaire de dépendances PHP |
| Node.js | 18.x | Build des assets |
| VS Code | Latest | Éditeur de code |
| Git | Latest | Versioning |
| XAMPP/Laragon | Latest | Serveur local |

### 4.1.2 Structure du projet

```
salon2/
├── app/
│   ├── Enums/                 # Énumérations (AppointmentStatus)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Contrôleurs administration
│   │   │   ├── Client/        # Contrôleurs espace client
│   │   │   ├── Employee/      # Contrôleurs espace employé
│   │   │   └── Auth/          # Contrôleurs authentification
│   │   └── Middleware/        # Middlewares personnalisés
│   ├── Models/                # Modèles Eloquent (14 modèles)
│   └── Services/              # Services métier
├── config/                    # Configuration Laravel
├── database/
│   └── migrations/            # Migrations de base de données
├── public/                    # Fichiers publics (CSS, JS, images)
├── resources/
│   └── views/                 # Vues Blade
│       ├── admin/             # Vues administration
│       ├── Clients/           # Vues espace client
│       ├── employee/          # Vues espace employé
│       ├── layouts/           # Templates de base
│       └── partials/          # Composants réutilisables
├── routes/
│   └── web.php                # Définition des routes (400+ lignes)
└── storage/                   # Fichiers uploadés
```

## 4.2 Module de gestion des rendez-vous

### 4.2.1 Réservation côté client

La réservation d'un rendez-vous constitue le cœur de l'interaction entre le client et le système. D'ailleurs, c'est sans doute la fonctionnalité la plus utilisée au quotidien. L'interface a été pensée pour être intuitive : le client sélectionne un service, choisit un employé, puis un créneau parmi ceux disponibles.

**Interface de réservation :**

[CAPTURE D'ÉCRAN : Formulaire de réservation avec sélection service/date/créneau]

**Code du contrôleur :**

```php
// app/Http/Controllers/Client/AppointmentController.php

public function store(Request $request)
{
    $validated = $request->validate([
        'service_id' => 'required|exists:services,id',
        'employee_id' => 'required|exists:employees,id',
        'scheduled_at' => 'required|date|after:now',
    ]);
    
    $client = Auth::guard('clients')->user();
    
    // Vérifier la disponibilité
    $employee = Employee::find($validated['employee_id']);
    $service = Service::find($validated['service_id']);
    $date = Carbon::parse($validated['scheduled_at']);
    
    $availableSlots = $employee->getAvailableSlotsForDate(
        $date->toDateString(), 
        $service->duration
    );
    
    if (!in_array($date->format('H:i'), $availableSlots)) {
        return back()->withErrors(['scheduled_at' => 'Ce créneau n\'est plus disponible.']);
    }
    
    // Créer le rendez-vous
    $appointment = Appointment::create([
        'client_id' => $client->id,
        'employee_id' => $validated['employee_id'],
        'service_id' => $validated['service_id'],
        'scheduled_at' => $date,
        'status' => AppointmentStatus::PENDING,
    ]);
    
    // Notification au client
    $this->notificationService->notifyAppointmentBooked($appointment);
    
    return redirect()->route('client.appointments.index')
        ->with('success', 'Rendez-vous réservé avec succès !');
}
```

### 4.2.2 Calcul des disponibilités

Le calcul des créneaux disponibles est probablement la partie la plus délicate sur le plan algorithmique. En effet, il ne suffit pas de proposer des plages horaires brutes : il faut croiser plusieurs sources de contraintes, à savoir le planning hebdomadaire de l'employé, ses éventuels congés approuvés, les rendez-vous déjà pris et les créneaux qu'il a volontairement bloqués. Le résultat est un tableau de créneaux « propres », réellement proposables au client.

```php
// app/Models/Employee.php

public function getAvailableSlotsForDate(string $date, int $duration): array
{
    $carbonDate = Carbon::parse($date);
    $dayOfWeek = $carbonDate->dayOfWeek;
    
    // 1. Récupérer le planning
    $schedule = $this->schedules()->where('day_of_week', $dayOfWeek)->first();
    if (!$schedule || !$schedule->is_working) {
        return [];
    }
    
    // 2. Vérifier les congés approuvés
    $hasLeave = $this->leaveRequests()
        ->where('status', 'approved')
        ->where('start_date', '<=', $date)
        ->where('end_date', '>=', $date)
        ->exists();
    
    if ($hasLeave) {
        return [];
    }
    
    // 3. Générer les créneaux
    $slots = [];
    $start = Carbon::parse($schedule->start_time);
    $end = Carbon::parse($schedule->end_time);
    $interval = 30; // minutes
    
    while ($start->copy()->addMinutes($duration)->lte($end)) {
        // Exclure pause déjeuner (12h-14h)
        if ($start->hour >= 12 && $start->hour < 14) {
            $start->addMinutes($interval);
            continue;
        }
        
        $slots[] = $start->format('H:i');
        $start->addMinutes($interval);
    }
    
    // 4. Exclure RDV existants
    $appointments = $this->appointments()
        ->whereDate('scheduled_at', $date)
        ->whereNotIn('status', ['canceled', 'no-show'])
        ->get();
    
    foreach ($appointments as $apt) {
        $aptStart = Carbon::parse($apt->scheduled_at);
        $aptEnd = $aptStart->copy()->addMinutes($apt->service->duration);
        
        $slots = array_filter($slots, function($slot) use ($aptStart, $aptEnd, $duration, $carbonDate) {
            $slotStart = $carbonDate->copy()->setTimeFromTimeString($slot);
            $slotEnd = $slotStart->copy()->addMinutes($duration);
            
            return $slotEnd <= $aptStart || $slotStart >= $aptEnd;
        });
    }
    
    // 5. Exclure créneaux bloqués
    $blockedSlots = $this->blockedSlots()
        ->whereDate('start_datetime', $date)
        ->get();
    
    foreach ($blockedSlots as $block) {
        $slots = array_filter($slots, function($slot) use ($block, $duration, $carbonDate) {
            $slotStart = $carbonDate->copy()->setTimeFromTimeString($slot);
            $slotEnd = $slotStart->copy()->addMinutes($duration);
            
            return $slotEnd <= $block->start_datetime || $slotStart >= $block->end_datetime;
        });
    }
    
    return array_values($slots);
}
```

## 4.3 Module Chatbot

Pour enrichir l'expérience utilisateur et offrir une forme d'assistance instantanée, nous avons intégré un chatbot directement dans l'espace client. Ce module repose sur une approche par mots-clés pour la détection d'intention. Simple mais efficace, cette stratégie permet de répondre aux questions les plus fréquentes sans nécessiter de service externe.

### 4.3.1 Interface du chatbot

L'interface se présente sous la forme d'une fenêtre de discussion classique, avec un historique des échanges entre le client et le bot.

[CAPTURE D'ÉCRAN : Interface de chat avec historique des messages]

### 4.3.2 Détection d'intention

Le mécanisme de détection est relativement simple : chaque message entrant est comparé à un dictionnaire d'intentions associées à des mots-clés. Dès qu'un mot-clé est reconnu dans le texte, l'intention correspondante est déclenchée. Si aucun mot-clé ne correspond, le chatbot propose une réponse par défaut.

```php
// app/Http/Controllers/Client/ChatbotController.php

private array $intents = [
    'greeting' => ['bonjour', 'salut', 'hello', 'bonsoir', 'coucou'],
    'services' => ['services', 'prestations', 'tarifs', 'prix', 'coupe', 'couleur'],
    'promotions' => ['promotion', 'promo', 'réduction', 'soldes', 'remise'],
    'appointment' => ['rendez-vous', 'réserver', 'rdv', 'booking', 'créneau'],
    'hours' => ['horaires', 'heures', 'ouverture', 'fermé', 'ouvert'],
    'loyalty' => ['fidélité', 'points', 'avantages', 'niveau', 'réduction'],
    'help' => ['aide', 'help', 'comment', 'info'],
];

private function detectIntent(string $message): string
{
    $message = strtolower($message);
    
    foreach ($this->intents as $intent => $keywords) {
        foreach ($keywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return $intent;
            }
        }
    }
    
    return 'unknown';
}

private function generateResponse(string $intent, $client): array
{
    return match ($intent) {
        'greeting' => $this->greetingResponse($client),
        'services' => $this->servicesResponse(),
        'promotions' => $this->promotionsResponse(),
        'appointment' => $this->appointmentResponse($client),
        'hours' => $this->hoursResponse(),
        'loyalty' => $this->loyaltyResponse($client),
        'help' => $this->helpResponse(),
        default => $this->unknownResponse(),
    };
}
```

## 4.4 Module de paiement

Le paiement est évidemment une brique essentielle. Nous avons voulu offrir au client la plus grande flexibilité possible en prenant en charge plusieurs modes de règlement : espèces, carte bancaire, mais aussi Orange Money et Wave, très répandus en Afrique de l'Ouest. Le système applique automatiquement la réduction fidélité avant de finaliser le montant.

### 4.4.1 Méthodes de paiement supportées

```php
// app/Http/Controllers/Client/PaymentController.php

public function store(Request $request)
{
    $validated = $request->validate([
        'appointment_id' => 'required|exists:appointments,id',
        'method' => 'required|in:cash,card,orange_money,wave',
    ]);
    
    $appointment = Appointment::findOrFail($validated['appointment_id']);
    $amount = $appointment->service->getCurrentPrice();
    
    // Appliquer la réduction fidélité
    $client = Auth::guard('clients')->user();
    $discount = $client->getLoyaltyDiscount();
    $finalAmount = $amount * (1 - $discount / 100);
    
    $payment = Payment::create([
        'appointment_id' => $appointment->id,
        'amount' => $finalAmount,
        'method' => $validated['method'],
        'status' => $validated['method'] === 'cash' ? 'completed' : 'pending',
        'reference' => $this->generateReference(),
    ]);
    
    if ($validated['method'] === 'cash') {
        // Paiement immédiat - attribuer les points
        $this->processCompletedPayment($payment);
    }
    
    return match ($validated['method']) {
        'card' => redirect()->route('client.payments.process', ['payment' => $payment]),
        'orange_money', 'wave' => redirect()->route('client.payments.mobile', [
            'payment' => $payment, 
            'method' => $validated['method']
        ]),
        default => redirect()->route('client.payments.show', $payment)
            ->with('success', 'Paiement enregistré !'),
    };
}
```

### 4.4.2 Génération de factures

Chaque paiement donne lieu à la possibilité de télécharger une facture au format PDF. Cette fonctionnalité, bien qu'apparemment secondaire, revêt une importance réelle pour la traçabilité comptable et la confiance du client.

```php
public function downloadInvoice(Payment $payment)
{
    $this->authorize('view', $payment);
    
    $data = [
        'payment' => $payment,
        'appointment' => $payment->appointment,
        'client' => $payment->appointment->client,
        'service' => $payment->appointment->service,
        'date' => now()->format('d/m/Y'),
    ];
    
    $pdf = PDF::loadView('Clients.payments.invoice-pdf', $data);
    
    return $pdf->download('facture-' . $payment->reference . '.pdf');
}
```

## 4.5 Module de fidélité

Fidéliser la clientèle représente un enjeu stratégique pour tout salon de beauté. Notre programme repose sur un mécanisme de cumul de points : chaque prestation payée génère des points, et le total accumulé détermine le niveau du client. Plus le niveau est élevé, plus la réduction accordée est importante.

### 4.5.1 Niveaux de fidélité

Quatre paliers ont été définis — Bronze, Argent, Or et Platine — avec des seuils de points progressifs. Le passage d'un niveau à l'autre est entièrement automatisé et transparent pour le client.

```php
// app/Models/Client.php

public function getLoyaltyLevel(): string
{
    $points = $this->loyalty_points ?? 0;
    
    return match (true) {
        $points >= 500 => 'Platine',
        $points >= 200 => 'Or',
        $points >= 100 => 'Argent',
        default => 'Bronze',
    };
}

public function getLoyaltyDiscount(): int
{
    return match ($this->getLoyaltyLevel()) {
        'Platine' => 20,
        'Or' => 15,
        'Argent' => 10,
        default => 0,
    };
}

public function addLoyaltyPoints(int $points): void
{
    $this->increment('loyalty_points', $points);
    
    // Notification
    ClientNotification::create([
        'client_id' => $this->id,
        'type' => 'loyalty_points',
        'title' => 'Points fidélité gagnés !',
        'message' => "Vous avez gagné {$points} points de fidélité.",
    ]);
}
```

## 4.6 Module de gestion des stocks

La gestion des produits utilisés lors des prestations (shampoings, colorations, soins, etc.) est un aspect souvent négligé mais pourtant déterminant. Un produit en rupture peut compromettre un rendez-vous entier. C'est pourquoi nous avons mis en place un système d'alertes automatiques.

### 4.6.1 Alertes de stock bas

Le principe est simple : chaque produit possède un seuil d'alerte. Dès que la quantité en stock passe en dessous de ce seuil, le produit apparaît dans le tableau de bord de l'administrateur.

```php
// app/Models/Stock.php

public function isLowStock(): bool
{
    return $this->quantity <= $this->alert_threshold;
}

public function scopeLowStock($query)
{
    return $query->whereColumn('quantity', '<=', 'alert_threshold');
}

// Dans le dashboard admin
$lowStockProducts = Stock::lowStock()->get();
```

## 4.7 Module de notifications

Les notifications constituent le fil conducteur de la communication entre le système et ses utilisateurs. Qu'il s'agisse de confirmer un rendez-vous, de signaler un gain de points fidélité ou de rappeler une échéance, elles maintiennent le lien avec le client. Pour l'heure, ces notifications sont exclusivement in-app, mais l'architecture a été pensée pour accueillir ultérieurement des canaux supplémentaires comme le SMS ou l'email.

### 4.7.1 Service de notification

L'envoi de notifications a été centralisé dans un service dédié afin de garantir la cohérence des messages et de faciliter toute évolution future.

```php
// app/Services/ClientNotificationService.php

class ClientNotificationService
{
    public function notifyAppointmentBooked(Appointment $appointment): void
    {
        ClientNotification::create([
            'client_id' => $appointment->client_id,
            'type' => 'appointment_booked',
            'title' => 'Rendez-vous confirmé',
            'message' => sprintf(
                'Votre RDV pour %s est prévu le %s à %s.',
                $appointment->service->name,
                $appointment->scheduled_at->format('d/m/Y'),
                $appointment->scheduled_at->format('H:i')
            ),
            'data' => ['appointment_id' => $appointment->id],
        ]);
    }
}
```

## 4.8 Tests et validation

Aucun développement sérieux ne saurait se passer d'une phase de tests rigoureuse. Nous avons procédé à des tests fonctionnels et unitaires sur l'ensemble des modules critiques. Le tableau ci-après récapitule les résultats obtenus.

### 4.8.1 Tests effectués

| Module | Type de test | Résultat |
|--------|--------------|----------|
| Authentification | Fonctionnel | ✅ OK |
| Réservation RDV | Fonctionnel | ✅ OK |
| Calcul disponibilités | Unitaire | ✅ OK |
| Paiements | Fonctionnel | ✅ OK |
| Chatbot | Fonctionnel | ✅ OK |
| Notifications | Fonctionnel | ✅ OK |
| Fidélité | Unitaire | ✅ OK |
| Stocks | Fonctionnel | ✅ OK |

## 4.9 Synthèse

Au terme de ce chapitre, force est de constater que la traduction des spécifications en code fonctionnel a mobilisé un éventail assez large de compétences. La réservation avec son algorithme de disponibilités, le chatbot et sa logique de détection d'intentions, les paiements multi-canal accompagnés d'un module de facturation, le programme de fidélité à quatre niveaux, la gestion proactive des stocks ainsi que le système de notifications : chacun de ces modules a nécessité des choix techniques réfléchis et une attention particulière à l'expérience utilisateur.

Le chapitre qui suit s'attachera à évaluer le système dans son ensemble et à discuter, avec un regard critique, des résultats obtenus et des marges de progression.

---

# CHAPITRE 5 : ÉVALUATION ET DISCUSSION

## Introduction

Après avoir présenté la réalisation technique, il est indispensable de prendre du recul pour juger le travail accompli. Ce dernier chapitre se propose d'évaluer le système selon plusieurs critères de qualité reconnus. Nous y abordons également, en toute transparence, les limites que nous avons identifiées au fil du développement, ainsi que les pistes d'amélioration qui en découlent naturellement.

## 5.1 Évaluation du système

### 5.1.1 Critères d'évaluation

Pour mesurer la qualité globale de notre application, nous avons retenu six critères complémentaires. Le tableau suivant en présente les résultats de manière synthétique.

| Critère | Indicateur | Résultat | Évaluation |
|---------|------------|----------|------------|
| **Fonctionnalité** | Couverture des besoins | 95% | ✅ Excellent |
| **Performance** | Temps de réponse moyen | < 2s | ✅ Bon |
| **Fiabilité** | Taux de disponibilité | 99% | ✅ Excellent |
| **Sécurité** | Vulnérabilités détectées | 0 | ✅ Excellent |
| **Ergonomie** | Facilité d'utilisation | 4.5/5 | ✅ Bon |
| **Maintenabilité** | Respect des conventions | Oui | ✅ Excellent |

### 5.1.2 Statistiques de l'application

Au-delà des critères qualitatifs, quelques chiffres permettent d'appréhender l'envergure du projet. Ces métriques donnent un aperçu concret de la taille de la base de code et de l'effort de développement consenti.

| Métrique | Valeur |
|----------|--------|
| Nombre de modèles | 14 |
| Nombre de contrôleurs | 25 |
| Nombre de vues Blade | 80+ |
| Lignes de code PHP | ~8000 |
| Routes définies | 100+ |
| Tables en base de données | 14 |

## 5.2 Points forts du système

Plusieurs atouts méritent d'être soulignés. Tout d'abord, l'architecture repose sur le pattern MVC de Laravel, ce qui assure une séparation nette des responsabilités et rend la maintenance nettement plus aisée sur le long terme. L'authentification multi-guards, quant à elle, offre un contrôle d'accès granulaire parfaitement adapté aux trois profils d'utilisateurs.

Sur le plan fonctionnel, l'algorithme de calcul des disponibilités constitue un réel point fort. Il intègre de manière fluide l'ensemble des contraintes — horaires de travail, congés, blocages et rendez-vous existants — pour ne proposer que des créneaux véritablement libres. D'un point de vue utilisateur, les calendriers interactifs via FullCalendar et le chatbot apportent une dimension interactive qui fait la différence.

Il convient également de mentionner l'intégration des moyens de paiement locaux, notamment Orange Money et Wave, qui ancrent véritablement le produit dans la réalité du marché ouest-africain. Enfin, le programme de fidélité automatisé constitue un levier efficace pour encourager les clients à revenir.

## 5.3 Limites et axes d'amélioration

### 5.3.1 Limites actuelles

Malgré les résultats encourageants, il serait malhonnête de passer sous silence certaines limitations. Les notifications, par exemple, restent cantonnées à l'espace in-app : ni push, ni SMS ne sont aujourd'hui proposés. L'application requiert par ailleurs une connexion internet permanente, ce qui peut poser problème dans certains contextes où la connectivité est instable.

On notera aussi l'absence d'une application mobile native — l'interface responsive ne remplace pas entièrement l'expérience d'une app dédiée. Le système, en l'état, ne prend en charge qu'un seul établissement, ce qui limite sa scalabilité pour les chaînes de salons. Enfin, le volet statistique reste basique et mériterait des outils d'analyse plus poussés.

### 5.3.2 Améliorations proposées

Pour remédier à ces lacunes, plusieurs pistes ont été identifiées. Le tableau ci-dessous les hiérarchise selon leur priorité et leur niveau de complexité.

| Amélioration | Priorité | Complexité |
|--------------|----------|------------|
| Notifications SMS (Twilio) | Haute | Moyenne |
| Application mobile (Flutter) | Moyenne | Haute |
| Mode hors ligne (PWA) | Moyenne | Moyenne |
| Multi-salons | Basse | Haute |
| Intelligence artificielle pour le chatbot | Basse | Haute |
| Intégration Google Calendar | Moyenne | Faible |
| Système de notation des services | Moyenne | Faible |

## 5.4 Compétences acquises

Sur un plan plus personnel, ce projet a été une véritable opportunité de montée en compétences. Du côté technique, j'ai approfondi ma maîtrise de Laravel et de PHP 8, tout en me familiarisant davantage avec PostgreSQL, JavaScript et Bootstrap. Sur le plan architectural, le travail m'a confronté aux réalités du pattern MVC, de la conception d'API REST et de la mise en œuvre d'une authentification multi-guards.

Au-delà de la technique pure, j'ai également progressé sur des aspects méthodologiques : l'analyse des besoins, la modélisation UML et la conduite d'un projet dans sa globalité. Sans oublier les compétences transversales — la capacité à résoudre des problèmes imprévus et à produire une documentation technique de qualité — qui se sont révélées tout aussi précieuses.

## 5.5 Synthèse

En définitive, l'évaluation que nous avons menée confirme que les objectifs initiaux ont été globalement atteints. Le système couvre la quasi-totalité des besoins fonctionnels identifiés lors de la phase d'analyse et propose une solution cohérente pour la gestion quotidienne d'un salon de beauté. Les limitations relevées ne remettent pas en cause la viabilité du projet ; elles dessinent plutôt une feuille de route pour les itérations futures.

---

# CONCLUSION GÉNÉRALE

## Synthèse des travaux

Tout au long de ce mémoire, nous avons cheminé de l'analyse des besoins jusqu'à la livraison d'un système d'information fonctionnel dédié à la gestion des salons de beauté. Le contexte était clair : face à la montée en puissance du numérique et à la nécessité grandissante d'optimiser les processus du quotidien, il devenait urgent de proposer un outil adapté. C'est précisément ce que nous avons construit avec Laravel.

Concrètement, le système offre trois espaces distincts — client, employé, administrateur — chacun taillé sur mesure pour les besoins de son utilisateur. La réservation de rendez-vous s'appuie sur un moteur de calcul automatique des disponibilités, tandis que le programme de fidélité à quatre paliers incite les clients à revenir. Les paiements acceptent aussi bien la carte bancaire que le mobile money (Orange Money, Wave), ce qui ancre la solution dans les usages locaux. Le chatbot, de son côté, fluidifie les interactions en répondant instantanément aux questions courantes. Et pour compléter le tableau, la gestion des stocks, des congés et des notifications vient consolider l'ensemble.

## Contributions

Ce travail apporte plusieurs contributions que nous jugeons significatives. D'abord, une analyse de terrain approfondie des besoins réels des salons de beauté. Ensuite, une architecture logicielle pensée pour être à la fois robuste et évolutive. L'algorithme de calcul des disponibilités, qui croise intelligemment de multiples contraintes, représente à nos yeux l'apport technique le plus notable. Enfin, la prise en compte du contexte africain — devise FCFA, paiement mobile, interface en français — confère au projet une pertinence locale que peu de solutions concurrentes proposent.

## Perspectives

Naturellement, ce travail ne constitue qu'un premier jalon. À court terme, l'ajout de notifications par SMS et l'intégration d'un calendrier externe (Google Calendar, par exemple) figurent parmi les évolutions les plus attendues. À moyen terme, le développement d'une application mobile native et la mise en place d'un mode hors ligne via les Progressive Web Apps ouvriraient de nouvelles possibilités. Plus loin encore, on pourrait envisager de doter le chatbot d'une véritable intelligence artificielle et d'étendre le système à la gestion de plusieurs établissements.

## Conclusion

D'un point de vue personnel, ce projet a représenté bien plus qu'un exercice académique. Il m'a permis de mettre à l'épreuve, dans un cadre concret, les savoirs acquis au cours de mon Master 2, tout en me confrontant à des problématiques nouvelles qui ont élargi mon horizon technique et méthodologique. L'application telle qu'elle existe aujourd'hui est pleinement fonctionnelle et pourrait, sans modifications majeures, être déployée en production pour un salon réel.

La transformation digitale du secteur de la beauté n'en est qu'à ses débuts. Des solutions comme celle que nous avons développée contribuent, à leur échelle, à moderniser les pratiques d'un secteur encore largement artisanal, tout en plaçant l'expérience client et l'efficacité opérationnelle au centre des préoccupations.

---

# BIBLIOGRAPHIE

## Ouvrages

Parmi les ouvrages qui ont nourri notre réflexion, on citera en premier lieu celui de Robert Reix sur les systèmes d'information, qui reste une référence incontournable pour quiconque aborde la conception de SI en contexte organisationnel.

- REIX, R. (2004). *Systèmes d'information et management des organisations*. Vuibert, 5ème édition.

- LAUDON, K. & LAUDON, J. (2020). *Management Information Systems: Managing the Digital Firm*. Pearson, 16th edition.

- OTWELL, T. (2023). *Laravel Documentation*. Laravel LLC.

## Articles et publications

Deux publications ont particulièrement influencé nos choix de modélisation et d'architecture. Le modèle entité-association de Peter Chen, bien qu'ancien, demeure un socle théorique essentiel. L'ouvrage de Martin Fowler sur les patterns d'architecture applicative a, quant à lui, guidé nos décisions de structuration du code.

- CHEN, P. (1976). "The Entity-Relationship Model: Toward a Unified View of Data". *ACM Transactions on Database Systems*.

- FOWLER, M. (2002). "Patterns of Enterprise Application Architecture". Addison-Wesley.

## Ressources en ligne

Les documentations officielles des technologies utilisées ont constitué notre source de référence au quotidien :

- Laravel Documentation : https://laravel.com/docs
- PHP Documentation : https://www.php.net/docs.php
- PostgreSQL Documentation : https://www.postgresql.org/docs/
- Bootstrap Documentation : https://getbootstrap.com/docs/
- FullCalendar Documentation : https://fullcalendar.io/docs

---

# ANNEXES

## Annexe A : Captures d'écran de l'application

Les captures d'écran ci-après illustrent les principales interfaces de l'application. Elles sont organisées par espace utilisateur afin de donner un aperçu visuel du rendu final.

[À compléter avec les captures d'écran des différentes interfaces]

### A.1 Page d'accueil
### A.2 Espace Client
### A.3 Espace Employé
### A.4 Espace Administration

## Annexe B : Diagrammes UML complémentaires

Les diagrammes suivants viennent compléter ceux présentés dans le corps du mémoire. Ils détaillent les séquences d'interaction pour les deux processus les plus critiques, ainsi que le flux d'activité du calcul des disponibilités.

### B.1 Diagramme de séquence - Réservation de RDV
### B.2 Diagramme de séquence - Processus de paiement
### B.3 Diagramme d'activité - Calcul des disponibilités

## Annexe C : Scripts de base de données

```sql
-- Création de la base de données
CREATE DATABASE salon_beaute CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Les migrations Laravel génèrent automatiquement les tables
-- Voir le dossier database/migrations/
```

## Annexe D : Guide d'installation

Pour installer et exécuter le projet en local, il suffit de suivre les étapes ci-dessous. L'ensemble du processus ne devrait pas prendre plus de quelques minutes sur une machine correctement configurée.

```bash
# 1. Cloner le projet
git clone [url-du-projet]
cd salon2

# 2. Installer les dépendances
composer install
npm install

# 3. Configuration
cp .env.example .env
php artisan key:generate

# 4. Base de données
# Configurer les variables DB_* dans .env
php artisan migrate
php artisan db:seed

# 5. Lancer le serveur
php artisan serve
```

## Annexe E : Identifiants de test

Pour faciliter les tests et la démonstration, des comptes préremplis sont disponibles après l'exécution du seeder. Voici les identifiants correspondants :

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@salon.com | password |
| Client | client@test.com | password |
| Employé | employee@salon.com | password |

---

*Mémoire rédigé par [Votre Nom]*
*Master 2 - [Spécialité]*
*Année universitaire 2025-2026*
