# TODO - Implémentation Module Employé

## Phase 1: Base de données & Modèles
- [x] 1. Mettre à jour la migration employees (ajouter tous les champs nécessaires)
- [x] 2. Créer migration leave_requests
- [x] 3. Créer migration employee_notifications
- [x] 4. Créer modèle LeaveRequest avec relations
- [x] 5. Créer modèle EmployeeNotification avec relations
- [x] 6. Mettre à jour modèle Employee (ajouter relations)

## Phase 2: Contrôleurs
- [x] 7. Mettre à jour EmployeeDashboardController (statistiques, RDV, notifications)
- [x] 8. Mettre à jour EmployeeAppointmentController (complet avec notes)
- [x] 9. Mettre à jour EmployeeServiceController (suivi des services)
- [x] 10. Compléter LeaveRequestController (CRUD complet)
- [x] 11. Créer EmployeeNotificationController (messages internes)

## Phase 3: Vues (Design Template)
- [x] 12. Créer employee/dashboard.blade.php (tableau de bord principal)
- [x] 13. Créer employee/appointments/index.blade.php (liste RDV)
- [x] 14. Mettre à jour employee/appointments/show.blade.php (détails RDV)
- [x] 15. Créer employee/services/index.blade.php (services du jour)
- [x] 16. Créer employee/leaves/index.blade.php (liste congés)
- [x] 17. Créer employee/leaves/create.blade.php (demande congé)
- [x] 18. Créer employee/notifications/index.blade.php (messages internes)
- [x] 19. Mettre à jour employee-menu.blade.php (navigation complète)

## Phase 4: Routes & Authentification
- [x] 20. Mettre à jour routes/web.php (routes manquantes)
- [x] 21. Vérifier middleware d'authentification

## Tests Finaux
- [ ] Tester connexion employé
- [ ] Tester toutes les fonctionnalités
- [ ] Vérifier les restrictions d'accès
- [ ] Vérifier cohérence du design
