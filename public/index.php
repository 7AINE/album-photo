<?php

declare(strict_types=1);

// Point d'entrée unique : toutes les pages passent par ce fichier.
require dirname(__DIR__) . '/app/bootstrap.php';

$action = $_GET['action'] ?? 'home';
$method = $_SERVER['REQUEST_METHOD'];

// Chaque route indique précisément la méthode HTTP autorisée.
$routes = [
    'home' => ['GET', AccueilController::class, 'index'],
    'photo' => ['GET', PhotoController::class, 'detail'],
    'image' => ['GET', PhotoController::class, 'image'],
    'photo-form' => ['GET', PhotoController::class, 'formulaire'],
    'photo-store' => ['POST', PhotoController::class, 'ajouter'],
    'photo-delete' => ['POST', PhotoController::class, 'supprimer'],
    'comment-store' => ['POST', CommentaireController::class, 'ajouter'],
    'comment-delete' => ['POST', CommentaireController::class, 'supprimer'],
    'login' => ['GET|POST', AuthController::class, 'connexion'],
    'register' => ['GET|POST', AuthController::class, 'inscription'],
    'logout' => ['POST', AuthController::class, 'deconnexion'],
    'admin' => ['GET', AdminController::class, 'index'],
    'page-store' => ['POST', AdminController::class, 'creerPage'],
    'page-delete' => ['POST', AdminController::class, 'supprimerPage'],
    'page-update' => ['POST', AdminController::class, 'modifierPage'],
];

if (!is_string($action) || !isset($routes[$action])) {
    erreur_http(404, 'Page introuvable.');
}

[$methodes, $classe, $fonction] = $routes[$action];

if (!in_array($method, explode('|', $methodes), true)) {
    erreur_http(405, 'Méthode HTTP non autorisée.');
}

try {
    (new $classe())->$fonction();
} catch (PDOException $exception) {
    error_log('Erreur SQL : ' . $exception->getMessage());
    erreur_http(500, 'Une erreur de base de données est survenue.');
}
