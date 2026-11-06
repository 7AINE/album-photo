<?php

// En développement local, garder les erreurs détaillées dans les journaux PHP.
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// Cookies de session plus difficiles à voler depuis du JavaScript.
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? '1' : '0');

session_start();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');

require __DIR__ . '/helpers.php';
require __DIR__ . '/database.php';

require __DIR__ . '/models/Page.php';
require __DIR__ . '/models/Photo.php';
require __DIR__ . '/models/Commentaire.php';
require __DIR__ . '/models/Utilisateur.php';

require __DIR__ . '/controllers/AccueilController.php';
require __DIR__ . '/controllers/PhotoController.php';
require __DIR__ . '/controllers/CommentaireController.php';
require __DIR__ . '/controllers/AuthController.php';
require __DIR__ . '/controllers/AdminController.php';
