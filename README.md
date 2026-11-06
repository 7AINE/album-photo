# Album Photo M2L — BTS SIO SLAM 2e année

Site PHP 8 + MySQL en **MVC simple** (sans framework). Les fichiers sont indentés et commentés.

## 1. Installation sur XAMPP (Windows)

1. Décompresser le dossier `album_photo_m2l_mvc` dans `C:\xampp\htdocs\`.
2. Démarrer **Apache** et **MySQL** depuis XAMPP.
3. Ouvrir `http://localhost/phpmyadmin` et importer `sql/album_photo_m2l.sql`.
4. Modifier si nécessaire `config/config.php` (identifiants MySQL de votre ordinateur).
5. Ouvrir **http://localhost/album_photo_m2l_mvc/public/index.php**.
6. Créer un compte avec **Inscription**, puis se connecter.
7. Pour obtenir un compte administrateur, exécuter dans phpMyAdmin :
   `UPDATE utilisateur SET role = 'admin' WHERE pseudo = 'VOTRE_PSEUDO';`
   **Se déconnecter et se reconnecter** pour actualiser le rôle.
8. Ajouter une photo JPG/PNG/WEBP de moins de 5 Mo.

**Prérequis PHP** : extensions PDO MySQL, mbstring, fileinfo (généralement incluses avec XAMPP).

## 2. MVC : où modifier quoi ?

- `public/index.php` : **routeur** / point d'entrée du site (`?action=...`).
- `app/controllers/` : **contrôleurs**, traitements des formulaires, validations et droits.
- `app/models/` : **modèles**, seules classes qui écrivent les requêtes SQL.
- `app/views/` : **vues** HTML et affichage des données, `e()` contre les XSS.
- `app/helpers.php` : fonctions communes (sessions, CSRF, redirection, échappement).
- `config/config.php` : configuration MySQL et durée de l'archivage.
- `public/assets/style.css` : apparence du site.
- `storage/photos` : fichiers images, **hors du dossier `public`** ; des fichiers `.htaccess` bloquent aussi l’accès web direct au dossier `storage` et au code applicatif lorsque le projet est placé dans `htdocs`.
- `scripts/purge.php` : suppression différée des photos/commentaires.

Pour changer une couleur, modifier les variables `:root` du fichier CSS. Pour changer une page HTML, modifier le fichier dans `app/views/`.

## 3. Suppression après 15 jours

- Suppression par un membre : statut `en_attente`, contenu caché des visiteurs et des membres immédiatement.
- Pendant 15 jours, seul un administrateur peut encore voir ce contenu avec la mention « En cours de suppression ».
- Suppression par un administrateur : **immédiate** dans MySQL. Les commentaires d'une photo sont supprimés par la clé étrangère `ON DELETE CASCADE`.
- Suppression définitive après 15 jours : exécuter régulièrement `scripts/purge.php` avec le Planificateur de tâches Windows. **Une simple importation SQL ne programme pas la purge.**

Exemple d'action du Planificateur Windows :

- Programme : `C:\xampp\php\php.exe`
- Arguments : `C:\xampp\htdocs\album_photo_m2l_mvc\scripts\purge.php`
- Déclencheur : toutes les minutes, pendant la période de test. En production, planifier une cadence adaptée.

Pour les tests, remplacer dans `config/config.php` la valeur `21600` par `2` puis revenir à `21600` : 15 jours × 24 heures × 60 minutes.

## 4. Sécurité mise en œuvre

- Requêtes préparées PDO avec paramètres nommés : protection contre les injections SQL.
- `htmlspecialchars()` via `e()` : protection XSS lors de l'affichage.
- Token CSRF sur les formulaires qui modifient les données (y compris la déconnexion).
- `password_hash` et `password_verify` pour les mots de passe.
- Sessions avec cookies HttpOnly / SameSite, renouvellement d'identifiant après connexion.
- Contrôle des droits **côté serveur** : seul l'auteur ou un administrateur peut supprimer ; seul un administrateur peut créer un album.
- Validation des données ; upload d'images vérifié par MIME réel, dimensions et taille ; nom aléatoire ; images stockées **hors du répertoire public** et servies par un contrôleur.
- Pas d'affichage des erreurs SQL détaillées aux visiteurs.

**À savoir** : ce projet pédagogique n'est pas audité pour une mise en production Internet. Utiliser HTTPS, configurer les sauvegardes et protéger le serveur avant tout déploiement public.

## 5. Checklist de recette

Voir `docs/PLAN_DE_TESTS.md` et `docs/MCD_MLD.md`.

## 6. Documentation demandée par le professeur

Compléter `docs/documentation_technique.md` avec vos noms, vos captures d'écran, les tests réellement effectués et la répartition réelle du travail. Ne pas déclarer des tests non effectués.


## Interface graphique

Cette édition conserve **l’architecture MVC de la version BTS SIO 2A**, mais reprend l’interface du premier album : navigation blanche, titre « M2L Album », bandeau vert, galerie sur fond gris clair et pied de page vert foncé.

Le code du design est dans `public/assets/style.css`, découpé en sections numérotées. Les vues personnalisées sont dans `app/views/layouts/`, `app/views/accueil.php` et `app/views/photos/detail.php`. Les modèles, contrôleurs, protections XSS/SQL/CSRF et le script SQL sont ceux du second projet.

**Pour modifier les couleurs :** commence par les variables `:root` en haut du CSS. Pour changer le texte du bandeau : modifie `app/views/accueil.php`. Pour les liens du menu : modifie `app/views/layouts/header.php`.
