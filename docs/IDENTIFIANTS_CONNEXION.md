# Identifiants de Connexion

## Comptes créés par le UserSeeder

Tous les comptes sont prêts à être utilisés pour la connexion.

### 👤 Client
- **Email:** `client@salon.com`
- **Mot de passe:** `client123`
- **URL de connexion:** `/client/login`
- **Dashboard:** `/client/dashboard`

### 👨‍💼 Employé
- **Email:** `employe@salon.com`
- **Mot de passe:** `employe123`
- **URL de connexion:** `/employee/login`
- **Dashboard:** `/employee/dashboard`

### 👑 Administrateur
- **Email:** `admin@salon.com`
- **Mot de passe:** `admin123`
- **URL de connexion:** `/login`
- **Dashboard:** `/admin/dashboard`

## Vérifications effectuées

✅ Tous les utilisateurs sont créés dans la base de données
✅ Les mots de passe sont correctement hashés
✅ Les clients sont actifs (`active = true`)
✅ Les employés sont actifs (`is_active = true`)
✅ Les rôles sont correctement définis

## Notes importantes

- Le modèle `Client` a été modifié pour retirer le cast `'password' => 'hashed'` car le UserSeeder utilise déjà `Hash::make()`, ce qui évite un double hashage
- Les mots de passe sont vérifiés et fonctionnent correctement
- Tous les utilisateurs peuvent se connecter avec leurs identifiants respectifs

## Pour réinitialiser les utilisateurs

Si vous devez recréer les utilisateurs, exécutez :

```bash
php artisan db:seed --class=UserSeeder --force
```

