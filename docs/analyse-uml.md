# Analyse UML — Gestion Salon de Beauté

> **Projet** : Application de gestion d'un salon de beauté  
> **Framework** : Laravel (PHP)  
> **Date** : Mars 2026  
> **Acteurs** : Admin, Client, Employé

---

## Table des matières

1. [Diagramme de Cas d'Utilisation](#1-diagramme-de-cas-dutilisation)
2. [Diagramme de Classes](#2-diagramme-de-classes)
3. [Diagrammes de Séquence](#3-diagrammes-de-séquence)
4. [Diagrammes d'Activité](#4-diagrammes-dactivité)

---

## 1. Diagramme de Cas d'Utilisation

### 1.1 Acteurs du Système

| Acteur | Description | Garde d'authentification |
|--------|-------------|--------------------------|
| **Admin** | Administrateur du salon, gère l'ensemble du back-office | `web` (Modèle Utilisateur) |
| **Client** | Client du salon, prend des RDV et effectue des paiements | `clients` (Modèle Client) |
| **Employé** | Employé du salon, gère ses RDV et son planning | `employees` (Modèle Employé) |

### 1.2 Diagramme des Cas d'Utilisation Global

![Diagramme des Cas d Utilisation Global](https://mermaid.ink/img/pako:fVRNb9swDP0rRg6DewgQ57PZLVWF7bKh6LJetB4UW06E2ZInycWGYf99pGy3kpPUB1rQEx-pR1J_J7kuxOTj5Gh4c0r2dz9UAp9tD93GLneiNbbbxW9X1FKlqf_d3Lztk0oK5dK0-4cIrZtK_xFp2i8GTKhiFIxw63kZLLpAz0GgjH0SRphEdDQ2xOY9lvvoEbToISvMi8xjt2WPPd4_hdurwcXp_GfksGZEK9tWDtCGS1GPo23YrmmMbl8wGa2Ocbxb9igarQojklpYy0fwFuIqHxnkaLSJqbNZn1ZTcaWkOkZgxu4q_avFsAZIePs7ggeB3AlyjpBBH8i6lFUELdmeHyrgSgqRHLQpnt-pXFd4X7puGVCRLNDtQiHInD1A1qhLXAqyYDul2uqsRmTJPkuoj5Fw5zG2YrQsRe7aoEohvmZ7UYn8xM0RTpQcTppQFLJh352spEU1T9wddOR-e00xsmVftZOlzLmTcN9IgRlIU-Ed5flloHr3wkIesuubunFRPlC-By2h1ZJSFqKSr-jFSvRz5kvRrwMymrEnLX0KCbdWHlVUCjpn3xx3rRvlSBd4N2HH28vgWuH-Cm5Uc1UMcxBia_al6_6Ej4acbsIBO-9zelV7el17itq_1390G96WSzNNQf2LvfYqvn-qkul0CjOTfIDXCM0CzRLNCs0azQbNLZotmmzmrffJvFPmvbJlx9vNkScmeIjgGYJHCBITJCZITJCYIDFBYuKJiSeG3unI-k7wbBQRimwU2SiyUWSjyEaRjSIbRTbq2ahnAy0m__4D)

### 1.3 Cas d'Utilisation — Admin

| # | Cas d'utilisation | Description |
|---|-------------------|-------------|
| A1 | Gérer les employés (CRUD) | Créer, lire, modifier, supprimer, activer/désactiver des employés |
| A2 | Gérer les clients (CRUD) | Créer, lire, modifier, supprimer, activer/désactiver des clients |
| A3 | Gérer les services (CRUD) | Créer, lire, modifier, supprimer des services avec promotions |
| A4 | Gérer les rendez-vous (CRUD) | Créer, consulter, modifier, supprimer des rendez-vous |
| A5 | Gérer les stocks (CRUD) | Gérer l'inventaire des produits avec alertes de stock bas |
| A6 | Consulter les paiements | Voir les paiements et mettre à jour leur statut |
| A7 | Approuver/Rejeter les congés | Traiter les demandes de congé des employés |
| A8 | Répondre aux messages employés | Gérer la messagerie interne avec les employés |
| A9 | Générer des rapports | Statistiques et export CSV |
| A10 | Gérer le planning employés | Définir les horaires hebdomadaires des employés |
| A11 | Bloquer des créneaux horaires | Bloquer des créneaux pour un employé ou globalement |
| A12 | Gérer le thème de l'application | Personnaliser couleur et mode sombre |
| A13 | Gérer son profil | Modifier ses informations et photo de profil |
| A14 | Consulter le tableau de bord | Statistiques générales du salon |

### 1.4 Cas d'Utilisation — Client

| # | Cas d'utilisation | Description |
|---|-------------------|-------------|
| C1 | Consulter les services | Voir les services, prix et promotions actives |
| C2 | Prendre un rendez-vous | Choisir service, employé, date et créneau |
| C3 | Annuler un rendez-vous | Annuler un RDV existant |
| C4 | Consulter l'historique des RDV | Voir les RDV passés et à venir |
| C5 | Effectuer un paiement | Payer via Stripe, Orange Money, Wave ou en espèces |
| C6 | Télécharger une facture PDF | Générer et télécharger la facture |
| C7 | Utiliser le chatbot | Poser des questions via le chatbot assistant |
| C8 | Gérer son profil | Modifier informations, photo, allergies |
| C9 | Consulter ses notifications | Voir et marquer comme lues les notifications |
| C10 | Voir le calendrier des RDV | Calendrier interactif FullCalendar |
| C11 | Désactiver son compte | Auto-désactivation du compte |
| C12 | Consulter ses points de fidélité | Voir niveau (Bronze/Argent/Or/Platine) et réductions |

### 1.5 Cas d'Utilisation — Employé

| # | Cas d'utilisation | Description |
|---|-------------------|-------------|
| E1 | Voir les rendez-vous assignés | Liste et détails des RDV du jour et à venir |
| E2 | Mettre à jour le statut d'un RDV | Confirmer, terminer, marquer absent |
| E3 | Ajouter des notes au RDV | Annoter un rendez-vous avec des remarques |
| E4 | Consulter le calendrier | Vue calendrier FullCalendar des RDV |
| E5 | Demander un congé | Soumettre une demande de congé avec motif |
| E6 | Envoyer un message à l'admin | Communication interne avec l'administration |
| E7 | Consulter son planning | Voir horaires, jours de travail et congés |
| E8 | Gérer son profil | Modifier informations et photo |
| E9 | Consulter ses notifications | Voir notifications (RDV, congés, messages) |
| E10 | Consulter les services | Voir la liste des services du salon |
| E11 | Gérer les paiements | Enregistrer des paiements côté salon |
| E12 | Voir l'historique des RDV | Consulter les RDV passés |

---

## 2. Diagramme de Classes

### 2.1 Diagramme de Classes Complet

![Diagramme de Classes](https://mermaid.ink/img/pako:zVdLb9swDP4rRk7pHsV2zWFA127YoRuCdtupgMHajKNVFj1JTpEV_e-j5Cr1Q47TdRiWQ2CLpER-JD_Rd7OMcpwtZpkEY84EFBrKK5XwLxcaMytIJV_fNyteJ_lmhRQGLNY6PclLoZK7Rux-L4WyichbC8ZqoYpEQYnDVSxByOFyxefcko5so0lGtqnWZKm1vAZzwYrzRnyUXBPJrvhEbb0GaA3btsJ9O9RTKZDj-YfxcSQqsg_kuUZjIgIpURcCTc9FSVuQdptWxG99oSULMoXKC0vsKjgoEuDEbw4Amv06b05a-oPm_HeUbKiDUG0wotTLSYH2QekcNyjnR0lzZFTlTJiMamVZi_eKJO5DWTEC-B9krlewHl1h0jGATYWZ4BC7GfVVmvC5N2kOWzOdF2FONuw_XEs8sfOcm9WKEmOg7_QuJVnzkfQZK3uLV4nPkz87AvEl6o3IngNxjibTonIU0xKuJIFNKs17R1apJKef9uXu5LzW0Nvs4aSMwylIb4eSAlWOerL8HWP4tWXwYB4D87TWmttp6ZxjBe90BLoLd-iv71Sb_ei5hcxTUDpYNw38QwE2pd-ThBJITLbGvJaYp8E1L0dVl9xxYOtIdSmyOKAIjUz9jF1qMNqCSxBYTrLnvhBbBNUVNsUApaOAobcl2jVFinAsOqtBGfA33e6YbqVbym6eUeeR6nMb_KxBWWH7y8CEblO7Zr5fk-xgYs7p1jszH72xzpi3OCunpAqcRn68Vhxa7IZ77Eu4ePvrgesQTKz9xpAHNz2kHGhFymAkmk-kgaeQg9h8PB7PDbBNaZXeIt60JE1H-EjdY1_iIu2tB_52XLy7nbozAzMAQv1e0s_6WSloORdeYxohHT35WEp8w_GqZQa4jvH6Z54zoMDTNfxh6-7a0O8Tc6md77aEd8RORwe0eYLQaWfDmMvPLJNQrPX1D557nxDQH5f3F75JViLzl9Yho-YE4nZbRbxjiomNzMNoHlgd8glP_w7MB7sVDy5Uxoi_S-DPGLT6sPEkI0maKReHrZ4Dz1wlfyJ1DnlI1tXs7dUsef36HT-94KfWvb7gWYXfGvWA2F59dlsUYYIMw9Vei4xUhjqYtERtqzfHx-5ldxsv3MiDGvcG0tLG1Yq7ocZ-JC86BsHfhSsg_jrE_YF3bqlFYqjmZO036V0FjC8Zg_nEQV0yZpAb9eEn7JQhU-Z-yNq0yagp_gaa8K3HWl2jkVMijOEgz0hMwBdr4K7lGCbHx2GPR7tiV0DTVjs3n2L0WE9PsvKD2uE2TWMEpvANtRJFrXF2_xs)

### 2.2 Relations entre les classes

| Relation | Type | Description |
|----------|------|-------------|
| Client → RendezVous | 1..* | Un client peut avoir plusieurs rendez-vous |
| Employe → RendezVous | 1..* | Un employé peut être assigné à plusieurs RDV |
| Service → RendezVous | 1..* | Un service est utilisé dans plusieurs RDV |
| RendezVous → Paiement | 1..0..1 | Un RDV peut avoir un paiement |
| Client → Paiement | 1..* | Un client effectue plusieurs paiements |
| Employe ↔ Service | \*..* | Relation plusieurs-à-plusieurs (table pivot `employee_service`) |
| Employe → DemandeConge | 1..* | Un employé soumet plusieurs demandes de congé |
| Employe → HoraireEmploye | 1..* | Un employé a un horaire par jour de semaine (7 max) |
| Employe → CreneauBloque | 1..* | Créneaux bloqués spécifiques à un employé |
| CreneauBloque → Utilisateur_Admin | *..1 | Créé par un administrateur |
| Client → MessageChat | 1..* | Messages du chatbot |
| Employe → MessageEmploye | 1..* | Messages vers l'administration |
| Client → NotificationClient | 1..* | Notifications client |
| Employe → NotificationEmploye | 1..* | Notifications employé |
| Utilisateur_Admin → Employe | 1..* | L'admin gère les employés (CRUD) |
| Utilisateur_Admin → Client | 1..* | L'admin gère les clients (CRUD) |
| Utilisateur_Admin → Service | 1..* | L'admin gère les services (CRUD) |
| Utilisateur_Admin → Stock | 1..* | L'admin gère les stocks (CRUD) |
| Utilisateur_Admin → Parametre | 1..1 | L'admin configure les paramètres du salon |

---

## 3. Diagrammes de Séquence

### 3.1 Séquence — Prise de Rendez-vous par un Client

![Séquence Prise de RDV](https://mermaid.ink/img/pako:fZNRS8MwFIX_ymVPExy-DxzYdgVhqKxjT4Jk6e12oU1ikpap-N9N7FLXbrUvpTknN9-5vfmacJnjZD4x-F6j4JgQ22tWvQpwD-NWaoiBGYhLQmHbZcW0JU6KCQupF1Opq7pkpBHWyfbSlHlThrrBWsOKadZgeemKEm-LmEHIERIpBKJpbfFssUjnEB8kGdJQCzCuGnFs5dTJ2RzWPoPFoBnIySgpaFeGOpkzRskcsuVqGW_-jC4oFSdPlMzacisy1qOYzheKtDQPRUH8gHogX7JipUr5cZ31pBlQ0vWGYz_YgLczTxU10oZvfDttuhlNEHaOJOjLgwQ585wWuEaBrL4a46Qdx1u-RU0FubMOUvtJMbfApdj7t5sZwKNjdVMw_AlxKFzSTo_yh-N7_FtWUu7EEsHJOX7OGlmbHv7Lc7aBO_4723dMKUnCVthRBPbHp2y53sCZAaaO1tbmXrnKJPbDzvtIDgqv1hHSTRtxZkkOhuM_Iz-7gaEBsRQF6ao1dFcv9Wp83p4z2-T7Bw)

### 3.2 Séquence — Processus de Paiement

![Séquence Paiement](https://mermaid.ink/img/pako:pZRPb9pAEMW_yohTc0C-IzVSMAhFSoOFqbj0MlkPeBV7191ZE0VVv3tnWVxwHCpUfLAs79v35v3859dI2YJGkxHTz5aMopnGncP6hwE5UHnrIAVkSCtNxsfbDTqvlW7QeMjCYoY7kpOm-lNNHjQ5uT21Dp7Q4Z6qoWqxiVbM5Kiq_mU4nQXpFJmgIJhZY4g4ytLx_X02gbS0mrWDmnwpBYOs6dlhJXN5pxuCVJxJ3IxC7SguhyMTq3wC2TJfQ6IO_ZMG34MHJ3zYexLnIl5sJNgROWBi1tb8TShJvdrWn-SLzTi6f189DYeLhrHJigqZSvlgtyfHR8-TMD0m53horM3WuhqDnkGFar3Us0qxQvJGL6W1r_0q05mMls0e1nM4VoYv7NG3_FVGLe6imip5AkuHRh7_N2vo_Up49rBlXPe3dAgfjfZaIHZMLiTcgPBzww5kas1Wu1omkBdVQfhAYLnOLmE8L5MorKoXVP9PcyOZV1J860kv0us73kCtbzSkNbTqgwrz3g5ozg0pYsAWGCtrroSlkMth5uNzPl-th5lkCm12Xawp4sWHXcZ6vdXq8KmBOvs_dgSPaKKgTycLknQCD1txKAWekG5Hv_8A)

### 3.3 Séquence — Gestion des Congés (Employé → Admin)

![Séquence Congés](https://mermaid.ink/img/pako:rZNBi9swEIX_ypBT9xByN3TBWStlIWxDnL0VyiCPUxVbckeyoZT-90qW7HU2m5aFBnyI9N7oe9LMr5U0Fa2ylaUfPWlJhcIzY_tFg_-hdIZBAFoQbdeYnxTXO2SnpOpQO9iN24_aEdco6bawDLqSeKCeYY-MAzXXqm0RZFu0BBVBYbQmskuaPOznVat0XBXr-_udyOBI_mDFUBtu-wYVjwWk0efEsgvKMoPD5_IEG4qYtGnIg6w5xLcunVR65bbI4PGpFMcTjJKvSQIfrEPX248d6Urp8120bIt1LF9QizqczEQ0lUuMD0bXilt0ymggPRg1o3mFeDFb07fKJnseC38SHhtD8L8yl2IvHl4x239B75V14b6s_0YCuyDPM8jrWslvxPO2pwd0jvyzp8dpHORdx6YfiOPSgv3wfIN9o6oNBttAL6YpyvOhyE_ixvUnV3V37UvPpo1THjvd9rItl9GmK8cEnyTU-A480ndy743D3iTdO9NE0_9LwyP5lEVXl7Pi-9D2jU92UdVezsnYb_OYvCF81XEXCv9PQ9NPfTQ32tMyxWI45xGZO40pIKK7mhCpbDBjFNLq9x8)

### 3.4 Séquence — Messagerie Employé vers Admin

![Séquence Messagerie](https://mermaid.ink/img/pako:hZJPa4NAEMW_yuCpOYTchQY0bkogbUNMeyqUQUe7oLt2Z00ppd-9679QY0I87rx9_ubt-_ESnZLne0yfNamEIom5wfJNgfswsdqAAGQQZVXob-rOKzRWJrJCZWHdjjfKkskwoevCuNHFZI5UG9iiwSMVU1UYNbIQmSAliLRSRPyfJmjmQVpK1Z2K-XK5Fj7sKZU5GagVlMSMeY-wbgSxD7vn-AAL6uho0Wt669hpwsiHzVMs9gcYVO-9Cu7Yoq35viKVSpXPulthNO-8H3tZYogGwx5rGJE6nlJxSG7qhiutMmlKtFL32wSd4YNwrNgseSKeXyaOxVaspsR8C3kr2TYRM5z7unngQ5BlMvlweZ4MSQFaS-6hR6i7l-uoC5kuDHGlVTqmftlFwUFczxkVf5GhdHbxeZS20tG1sQ0eY_o9uX_ykDqNm-JS57pwfQWerD-0pX2AG2W5Fv3lcjC4widgOjI-q8kk7rOmvGppoMDhOrSBe79_)

---

## 4. Diagrammes d'Activité

### 4.1 Activité — Processus Complet de Prise de Rendez-vous

![Activité Prise de RDV](https://mermaid.ink/img/pako:bZHLbsIwEEV_ZZRVWfADXbQqCeH9EFTdpCyMM8BIyRjZTvqI-Pc6JqFRi5d3judeX1eBVCkGj8EhUx_yJLSF1-idwZ2XhyTCfWF3Pej3n2CQhBkhWzAIUjGjtLi7kgMPhEmo2BSZRQ0ZGsfpkiSaBgo9FFXbqwzypMjQ8-U6jdwUloqvmzraqiCvDZMtZs6TamsNBQPm50x9tRmGnoqT0K-tAYRU3CLGfjxyY6GP7r7UyCiKT0jJnBXTPrsFHXl0XIV3kDbuuBs37mht3Mm_uI1jYzLx1LSu7EA695WBI1L87peqaLNMPTZLhqzxSMZqB26iN0CGvTDt42aemjuqdIVoYGXpQFLU7n9qmnt0cR-V_oMbcuHJZbUWNZcLYoss2LYVLLvPXSUbTElTXW2J2sBZEOa_y1aeWj8kMfGu11nQdrgOLj8)

### 4.2 Activité — Cycle de Vie d'un Rendez-vous (Diagramme d'État)

![Cycle de Vie RDV](https://mermaid.ink/img/pako:jZCxCsIwEIZf5cgodHHsIJS2LyDiYh1ie0qguZTkWhDx3U2aWBU6mC1_vv-7Iw_Rmg5FLhxLxkrJm5U6m7YNgT-nzRmybAc1FcxIjJBDaVGyMgTdCPvqGMEPEPDS0FVZHehaD725I7QpWsMLorGf1b3yIcj5HslFFcADWq3oW8sp8XWHdlLtWu1vf3Fx4X0BKVhhsOjmXQfpYum9R-j4L4phmvKbRWHKxPMF)

### 4.3 Activité — Gestion du Planning par l'Admin

![Activité Planning Admin](https://mermaid.ink/img/pako:VZJJb8MgFIT_ypNPySHqvYdWXrI0u5TeaA7YfrapMLgsqqoo_70EcJT4BnwzzBt8SSpZY_KaNFz-Vh1VBj6LLwHuSyekwNKa8xRmszfISFr3TACtKqwRqIWBUyGYaM-BzzyWkxNyrAyTQqACKwD7gcs_jFTuqeKSegS0tB1lBvH9Gs4Ldw4FNkwwBZ1UlCnUXjMneSeZdtvf0ipwGTgFjT1lYjSfe3BBTtRzHVqFDnRDvDjDCC08tCTjJQO1GkEOITLno9nScysyFwpbpo3Ce6BIrDzxMSELZz59yJ9x-WMdXikU6Jq6Yet7_JoaBDQhXrRae2YzJu-lYQ3UFkouK9qO1MZT20sWdqHlsqR8rG57u_pgmYd2JB0GznwMCkZaDdwVGR9Dnx8ke_cON8n-SRJJ1_D9OaNo5-nDUzHjpKWfPIL7AIbFIZT10FIuhbbcOPX4J3nkSNKmYVV3c6UcRa0Yquh4DCbJ9R8)

### 4.4 Activité — Processus de Paiement

![Activité Paiement](https://mermaid.ink/img/pako:dZLBbuIwEIZfZcSpPfQF9tAKkkBpC0mhUg8uB2MmxEuws47dFUJ9951MEpqq3SgX29_88__2nEfK7nD0a5SX9q8qpPPwEr8ZoG98JWLcBr-5hpubW5iIqNRoPKjC6lp7yOQJ3aZlJ4xE5wX6gvSA_kpqPBJ_99EiESEQUQOEiTRKaodcFIsV7mihvLYG1t7pCiEqUB0s9W5rYyaTc9ZpgsNQ17qXThrpNGimpuIVt4W1h16LpEOnM2ViJsimp_YSftvgoPbSB98Y3m0Ggkuy0-D3YpznWhXoAB017jPft5kH6VInzR5hYQ2e-HBO2f4EpMhfjsbZvNOYM_ZwuVprcu2O5OwdFaQvWYc9MPZ4jmRZbqU6cKY-_WPTeh2UwrqNN9hOyLZqUwx8vpI8bz5d_PHWp68nPl5881XTdTVohy0YW_7sa_mzr-X_fSV1hT2disQ43OuaHspdhgkqNDtt9l3_lNFMJMeqtCf89Ek-sGoGqgOzoYMZL56pwTsVOTDWa3pgyQOoOHFX9szkSszQYGMjl8oHGpwsnnbEion1lZhqs7keffwD)

---

## 5. Architecture Technique

### 5.1 Authentification Multi-Garde

| Garde | Modèle | Intergiciel | Préfixe de route |
|-------|--------|-------------|------------------|
| `web` | Utilisateur | `auth:web`, `admin` | `/admin` |
| `clients` | Client | `auth:clients`, `client.active` | `/client` |
| `employees` | Employé | `auth:employees` | `/employee` |

### 5.2 Passerelles de Paiement

| Méthode | Type | Point de rappel |
|---------|------|-----------------|
| Stripe | Carte bancaire | `POST /stripe/webhook` |
| Orange Money | Paiement mobile | `POST /orange-money/callback` |
| Wave | Paiement mobile | `POST /wave/callback` |
| Espèces/Salon | Espèces | Enregistrement direct |

### 5.3 Système de Fidélité

| Niveau | Points | Réduction |
|--------|--------|-----------|
| Bronze | 0 – 99 | 0% |
| Argent | 100 – 199 | 10% |
| Or | 200 – 499 | 15% |
| Platine | 500+ | 20% |
