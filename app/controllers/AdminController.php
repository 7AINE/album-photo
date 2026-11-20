<?php

class AdminController
{
    // ==========================================
    // AFFICHER LA PAGE D'ADMINISTRATION
    // ==========================================

    public function index(): void
    {
        // Vérifier les droits administrateur
        exiger_admin();

        // Récupérer tous les albums
        $pages = (new Page())->toutes();

        $titre = 'Administration';

        afficher('admin/index', compact('titre', 'pages'));
    }

    // ==========================================
    // CRÉER UN NOUVEL ALBUM
    // ==========================================

    public function creerPage(): void
    {
        uniquement_post();
        exiger_admin();
        verifier_csrf();

        // Récupérer les données du formulaire
        $titre = trim((string) ($_POST['titre'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));

        // Vérifier le titre et la description
        if (
            $titre === '' ||
            mb_strlen($titre) > 150 ||
            mb_strlen($description) > 2000
        ) {
            erreur_http(422, 'Titre ou description invalide.');
        }

        // Créer l'album dans la base de données
        $pageModel = new Page();

        $pageModel->creer($titre, $description);

        message_flash('Nouvel album créé.');

        rediriger('admin');
    }

    // ==========================================
    // SUPPRIMER UN ALBUM VIDE
    // ==========================================

    public function supprimerPage(): void
    {
        // Autoriser uniquement les requêtes POST
        uniquement_post();

        // Vérifier que l'utilisateur est administrateur
        exiger_admin();

        // Vérifier le jeton CSRF
        verifier_csrf();

        // Récupérer et vérifier l'identifiant
        $idPage = filter_input(
            INPUT_POST,
            'id_page',
            FILTER_VALIDATE_INT
        );

        if (
            $idPage === false ||
            $idPage === null ||
            $idPage <= 0
        ) {
            erreur_http(422, 'Identifiant de l’album invalide.');
        }

        // Demander au modèle de supprimer l'album
        $pageModel = new Page();

        $supprime = $pageModel->supprimer($idPage);

        // Vérifier si la suppression a réussi
        if (!$supprime) {
            message_flash(
                'Suppression impossible : album inexistant ou contenant des photos.'
            );

            rediriger('admin');
            return;
        }

        // Confirmer la suppression
        message_flash('Album supprimé avec succès.');

        rediriger('admin');
    }


    // ==========================================
    // MODIFIER UN ALBUM
    // ==========================================

    public function modifierPage(): void
    {
        // Autoriser uniquement les requêtes POST
        uniquement_post();

        // Vérifier les droits administrateur
        exiger_admin();

        // Vérifier le jeton CSRF
        verifier_csrf();

        // Récupérer l'identifiant de l'album
        $idPage = filter_input(
            INPUT_POST,
            'id_page',
            FILTER_VALIDATE_INT
        );

        // Récupérer le nouveau titre et la description
        $titre = trim((string) ($_POST['titre'] ?? ''));

        $description = trim(
            (string) ($_POST['description'] ?? '')
        );

        // Vérifier les données
        if (
            !$idPage ||
            $titre === '' ||
            mb_strlen($titre) > 150 ||
            mb_strlen($description) > 2000
        ) {
            erreur_http(422, 'Données de l’album invalides.');
        }

        // Vérifier que l'album existe
        $pageModel = new Page();

        $page = $pageModel->trouver($idPage);

        if (!$page) {
            erreur_http(404, 'Album introuvable.');
        }

        // Enregistrer les modifications
        $pageModel->modifier(
            $idPage,
            $titre,
            $description
        );

        // Afficher un message de confirmation
        message_flash('Album modifié avec succès.');

        // Retourner vers l'administration
        rediriger('admin');
    }

    public function formulaireModification(): void
    {
        exiger_admin();

        // Récupérer l'identifiant depuis l'URL
        $idPage = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!$idPage || $idPage <= 0) {
            erreur_http(422, 'Identifiant invalide.');
        }

        // Rechercher l'album
        $page = (new Page())->trouver($idPage);

        if (!$page) {
            erreur_http(404, 'Album introuvable.');
        }

        $titre = 'Modifier un album';

        // Afficher le formulaire
        afficher(
            'admin/modifier',
            compact('titre', 'page')
        );
    }
}
