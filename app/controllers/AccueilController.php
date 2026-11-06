<?php

class AccueilController
{
    public function index(): void
    {
        $modelePage = new Page();
        $pages = $modelePage->toutes();

        $idPage = entier_positif($_GET['page'] ?? null);
        if ($idPage === null && count($pages) > 0) {
            $idPage = (int) $pages[0]['id_page'];
        }

        $pageActive = $idPage === null ? null : $modelePage->trouver($idPage);
        if ($idPage !== null && $pageActive === null) {
            erreur_http(404, 'Cette page n’existe pas.');
        }

        $photos = $pageActive
            ? (new Photo())->pourPage($idPage, est_admin())
            : [];

        $titre = 'Galerie photo';
        afficher('accueil', compact('titre', 'pages', 'pageActive', 'photos'));
    }
}
