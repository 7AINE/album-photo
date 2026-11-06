# Documentation technique — Album Photo M2L

**Formation** : BTS SIO — Option SLAM, 2e année  
**Étudiant(s)** : [À compléter]  
**Année scolaire** : 2026-2027  
**Établissement** : [À compléter]

## Présentation

Développement d'un album photo en ligne pour des associations sportives de la M2L. Les visiteurs consultent les photos et commentaires ; les membres connectés publient du contenu ; les auteurs peuvent demander la suppression, différée de 15 jours. Les administrateurs modèrent immédiatement.

## Choix techniques

PHP 8, HTML5, CSS3, MySQL/MariaDB, PDO, sessions PHP et architecture MVC sans framework.

### Architecture MVC

- **Modèles** dans `app/models/` : requêtes préparées PDO et accès aux tables.
- **Vues** dans `app/views/` : HTML, formulaires, échappement anti-XSS.
- **Contrôleurs** dans `app/controllers/` : routage des actions, validations, droits et appels aux modèles.
- **Routeur** : `public/index.php`, point d'entrée unique.

## Base de données

Quatre tables : `utilisateur`, `page`, `photo`, `commentaire`. Les relations et cardinalités sont expliquées dans `MCD_MLD.md`. Le script complet est dans `sql/album_photo_m2l.sql`.

## Cybersécurité

Les requêtes préparées PDO réduisent les injections SQL. L'affichage avec `htmlspecialchars` protège des XSS HTML. Les formulaires sensibles ont un jeton CSRF. Les mots de passe sont hachés. Les autorisations sont contrôlées dans les contrôleurs. Les images sont vérifiées et sauvegardées hors du répertoire public.

## Suppression différée

Un membre marque le contenu `en_attente`. Il n'est plus affiché au public, mais l'administrateur le voit avec un badge. Un script PHP à planifier retire définitivement les éléments après 15 jours. L'administrateur supprime immédiatement.

## Répartition réelle du travail

[Décrire honnêtement ce qui a été réalisé par chaque personne.]

## Fonctionnalités supplémentaires

Création d'albums par un administrateur, galerie responsive, affichage privé des photos en cours de suppression.

## Tests effectués

[Reporter ici les résultats concrets de `PLAN_DE_TESTS.md` avec captures.]

## Captures d'écran et conclusion

[Ajouter les captures de la galerie, d'une photo, de la connexion, de l'administration et conclure après vérification sur XAMPP.]
