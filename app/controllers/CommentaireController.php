<?php

class CommentaireController
{
    public function ajouter(): void
    {
        uniquement_post();
        $utilisateur = exiger_connexion();
        verifier_csrf();

        $idPhoto = entier_positif($_POST['id_photo'] ?? null);
        $contenu = trim((string) ($_POST['contenu'] ?? ''));
        $photo = $idPhoto ? (new Photo())->trouver($idPhoto) : null;

        if (!$photo || $photo['statut_suppression'] !== 'actif') {
            erreur_http(404, 'Impossible de commenter cette photo.');
        }

        if ($contenu === '' || mb_strlen($contenu) > 2000) {
            erreur_http(422, 'Le commentaire doit contenir de 1 à 2000 caractères.');
        }

        (new Commentaire())->creer($contenu, $utilisateur['id_utilisateur'], $idPhoto);
        message_flash('Commentaire ajouté.');
        rediriger('photo', ['id' => $idPhoto]);
    }

    public function supprimer(): void
    {
        uniquement_post();
        $utilisateur = exiger_connexion();
        verifier_csrf();

        $id = entier_positif($_POST['id'] ?? null);
        $commentaire = $id ? (new Commentaire())->trouver($id) : null;
        if (!$commentaire) {
            erreur_http(404, 'Commentaire introuvable.');
        }

        if (!est_admin() && (int) $commentaire['id_utilisateur'] !== $utilisateur['id_utilisateur']) {
            erreur_http(403, 'Vous ne pouvez supprimer que vos commentaires.');
        }

        $modele = new Commentaire();
        if (est_admin()) {
            $modele->supprimer($id);
            message_flash('Commentaire supprimé définitivement.');
        } else {
            if ($commentaire['statut_suppression'] !== 'actif') {
                erreur_http(409, 'Ce commentaire est déjà en attente de suppression.');
            }
            $modele->demanderSuppression($id);
            message_flash('Commentaire masqué et archivé pendant 15 jours.');
        }

        rediriger('photo', ['id' => (int) $commentaire['id_photo']]);
    }
}
