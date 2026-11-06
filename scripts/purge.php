<?php

declare(strict_types=1);

// Script à exécuter en ligne de commande uniquement.
// Exemple Windows : C:\xampp\php\php.exe C:\xampp\htdocs\album_photo_m2l_mvc\scripts\purge.php
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Accès refusé.');
}

require dirname(__DIR__) . '/app/helpers.php';
require dirname(__DIR__) . '/app/database.php';
require dirname(__DIR__) . '/app/models/Photo.php';
require dirname(__DIR__) . '/app/models/Commentaire.php';

$minutes = (int) app_config()['delai_suppression_minutes'];

try {
    $photos = new Photo();
    $commentaires = new Commentaire();

    // Enregistrer les fichiers avant la suppression des lignes.
    $photosExpirees = $photos->expirees($minutes);

    // Les commentaires expirés sont supprimés indépendamment.
    $commentaires->purger($minutes);

    foreach ($photosExpirees as $photo) {
        $photos->supprimer((int) $photo['id_photo']);

        $chemin = dirname(__DIR__) . '/storage/photos/' . $photo['nom_fichier'];
        if (is_file($chemin)) {
            @unlink($chemin);
        }
    }

    echo 'Purge terminée : ' . count($photosExpirees) . " photo(s) effacée(s).
";
} catch (Throwable $exception) {
    error_log('Echec purge : ' . $exception->getMessage());
    exit(1);
}
