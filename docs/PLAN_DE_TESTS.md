# Plan de tests — À cocher APRÈS vérification

- [ ] 01. Importation SQL sans erreur dans phpMyAdmin.
- [ ] 02. Page d'accueil affichée avec les albums Football et Basketball.
- [ ] 03. Changement d'album : une seule page à la fois.
- [ ] 04. Inscription d'un membre avec un mot de passe de 10 caractères minimum.
- [ ] 05. Connexion / déconnexion.
- [ ] 06. Mauvais mot de passe : refus.
- [ ] 07. Publication d'une image JPG avec sa légende.
- [ ] 08. Image PNG/WEBP correctement affichée.
- [ ] 09. Fichier texte renommé en .jpg : refus.
- [ ] 10. Image de plus de 5 Mo : refus.
- [ ] 11. Visiteur : peut voir les photos et commentaires, mais pas ajouter.
- [ ] 12. Membre : peut commenter une photo active.
- [ ] 13. Membre : ne peut supprimer la photo d'un autre (403).
- [ ] 14. Membre : ne peut supprimer le commentaire d'un autre (403).
- [ ] 15. Suppression par membre : cachée du public, conservée en base.
- [ ] 16. Administrateur : voit la mention « En cours de suppression ».
- [ ] 17. Administrateur : suppression définitive immédiate.
- [ ] 18. Suppression d'une photo : ses commentaires disparaissent via cascade.
- [ ] 19. Purge automatique : régler à 2 minutes, planifier le script, puis vérifier.
- [ ] 20. Injection SQL : entrer `' OR '1'='1` dans l'identifiant de connexion ; aucun contournement.
- [ ] 21. XSS : saisir `<script>alert(1)</script>` en légende / commentaire ; le script ne s'exécute pas.
- [ ] 22. CSRF : modifier ou supprimer le token d'un formulaire ; requête refusée.
- [ ] 23. Accès direct `?action=image&id=...` à une photo archivée : 404 pour visiteur.
- [ ] 24. Seul un administrateur peut accéder à l'administration et créer un album.

Conserver des captures d'écran des tests et leur résultat (réussi / échec / correction).
