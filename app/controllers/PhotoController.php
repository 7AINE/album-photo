<?php

class PhotoController
{
    public function detail(): void
    {
        $id = entier_positif($_GET['id'] ?? null);
        $photo = $id ? (new Photo())->trouver($id) : null;

        if (!$photo || ($photo['statut_suppression'] !== 'actif' && !est_admin())) {
            erreur_http(404, 'Photo introuvable.');
        }

        $commentaires = (new Commentaire())->pourPhoto($id, est_admin());
        $titre = $photo['legende'];
        afficher('photos/detail', compact('titre', 'photo', 'commentaires'));
    }

    public function formulaire(): void
    {
        exiger_connexion();
        $pages = (new Page())->toutes();
        $titre = 'Ajouter une photo';
        afficher('photos/ajouter', compact('titre', 'pages'));
    }

    public function ajouter(): void
    {
        uniquement_post();
        $utilisateur = exiger_connexion();
        verifier_csrf();

        $idPage = entier_positif($_POST['id_page'] ?? null);
        $legende = trim((string) ($_POST['legende'] ?? ''));
        $fichier = $_FILES['photo'] ?? null;

        if (!$idPage || !(new Page())->trouver($idPage)) {
            erreur_http(422, 'La page sélectionnée est invalide.');
        }

        if ($legende === '' || mb_strlen($legende) > 255) {
            erreur_http(422, 'La légende doit contenir de 1 à 255 caractères.');
        }

        if (!$fichier || !is_array($fichier)
            || ($fichier['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            erreur_http(422, 'Sélectionnez une image valide.');
        }

        if ($fichier['size'] > app_config()['taille_max_photo']) {
            erreur_http(422, 'Image trop grande : maximum 5 Mo.');
        }

        $cheminTemporaire = $fichier['tmp_name'];
        if (!is_uploaded_file($cheminTemporaire)) {
            erreur_http(422, 'Fichier non reconnu comme téléversement.');
        }

        // Vérifier le contenu réel, jamais seulement l'extension déclarée.
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($cheminTemporaire);
        $types = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        $dimensions = @getimagesize($cheminTemporaire);
        if (!isset($types[$mime]) || $dimensions === false
            || $dimensions[0] > 5000 || $dimensions[1] > 5000) {
            erreur_http(422, 'Formats acceptés : JPG, PNG, WEBP (5000 px maximum).');
        }

        $nomFichier = bin2hex(random_bytes(16)) . '.' . $types[$mime];
        $destination = dirname(__DIR__, 2) . '/storage/photos/' . $nomFichier;

        if (!move_uploaded_file($cheminTemporaire, $destination)) {
            erreur_http(500, 'Impossible de stocker la photo.');
        }

        try {
            (new Photo())->creer(
                $nomFichier,
                $mime,
                $legende,
                $utilisateur['id_utilisateur'],
                $idPage
            );
        } catch (Throwable $exception) {
            @unlink($destination);
            throw $exception;
        }

        message_flash('Photo publiée.');
        rediriger('home', ['page' => $idPage]);
    }

    /** Les images sont privées : contrôle des droits avant leur envoi. */
    public function image(): void
    {
        $id = entier_positif($_GET['id'] ?? null);
        $photo = $id ? (new Photo())->trouver($id) : null;

        if (!$photo || ($photo['statut_suppression'] !== 'actif' && !est_admin())) {
            http_response_code(404);
            exit;
        }

        $chemin = dirname(__DIR__, 2) . '/storage/photos/' . $photo['nom_fichier'];
        if (!is_file($chemin)) {
            http_response_code(404);
            exit;
        }

        header('Content-Type: ' . $photo['type_mime']);
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, no-store');
        header('Content-Length: ' . filesize($chemin));
        readfile($chemin);
        exit;
    }

    public function supprimer(): void
    {
        uniquement_post();
        $utilisateur = exiger_connexion();
        verifier_csrf();

        $id = entier_positif($_POST['id'] ?? null);
        $photo = $id ? (new Photo())->trouver($id) : null;
        if (!$photo) {
            erreur_http(404, 'Photo introuvable.');
        }

        if (!est_admin() && (int) $photo['id_utilisateur'] !== $utilisateur['id_utilisateur']) {
            erreur_http(403, 'Vous ne pouvez supprimer que vos photos.');
        }

        $modele = new Photo();
        if (est_admin()) {
            $modele->supprimer($id);
            $chemin = dirname(__DIR__, 2) . '/storage/photos/' . $photo['nom_fichier'];
            if (is_file($chemin)) {
                @unlink($chemin);
            }
            message_flash('Photo supprimée définitivement.');
        } else {
            if ($photo['statut_suppression'] !== 'actif') {
                erreur_http(409, 'Cette suppression est déjà en attente.');
            }
            $modele->demanderSuppression($id);
            message_flash('Photo masquée et archivée pendant 15 jours.');
        }

        rediriger('home', ['page' => (int) $photo['id_page']]);
    }
}
