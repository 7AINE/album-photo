<?php

// Configuration locale : adapter ces valeurs à XAMPP.
return [
    'database' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'name' => 'album_photo_m2l',
        'user' => 'root',
        'password' => '',
    ],
    // Mettre 2 pour les tests, puis 21600 (15 jours) pour la remise.
    'delai_suppression_minutes' => 2,
    'taille_max_photo' => 5 * 1024 * 1024,
];
