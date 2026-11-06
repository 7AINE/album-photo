<?php

class AdminController
{
    public function index(): void
    {
        exiger_admin();
        $pages = (new Page())->toutes();
        $titre = 'Administration';
        afficher('admin/index', compact('titre', 'pages'));
    }

    public function creerPage(): void
    {
        uniquement_post();
        exiger_admin();
        verifier_csrf();

        $titre = trim((string) ($_POST['titre'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));

        if ($titre === '' || mb_strlen($titre) > 150 || mb_strlen($description) > 2000) {
            erreur_http(422, 'Titre ou description invalide.');
        }

        (new Page())->creer($titre, $description);
        message_flash('Nouvelle page créée.');
        rediriger('admin');
    }
}
