<?php

function app_config(): array
{
    static $config = null;

    if ($config === null) {
        $config = require dirname(__DIR__) . '/config/config.php';
    }

    return $config;
}

/** Convertit une donnée en texte HTML sûr (protection XSS). */
function e(?string $valeur): string
{
    return htmlspecialchars($valeur ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Construit une URL vers le point d'entrée public/index.php. */
function url(string $action = 'home', array $parametres = []): string
{
    $parametres = array_merge(['action' => $action], $parametres);
    return 'index.php?' . http_build_query($parametres);
}

function rediriger(string $action = 'home', array $parametres = []): never
{
    header('Location: ' . url($action, $parametres), true, 303);
    exit;
}

function afficher(string $vue, array $donnees = []): void
{
    extract($donnees, EXTR_SKIP);

    require dirname(__DIR__) . '/app/views/layouts/header.php';
    require dirname(__DIR__) . '/app/views/' . $vue . '.php';
    require dirname(__DIR__) . '/app/views/layouts/footer.php';
}

function erreur_http(int $code, string $message): never
{
    http_response_code($code);
    $titre = 'Erreur ' . $code;
    afficher('erreur', compact('titre', 'message'));
    exit;
}

function utilisateur_connecte(): ?array
{
    return $_SESSION['utilisateur'] ?? null;
}

function est_admin(): bool
{
    return (utilisateur_connecte()['role'] ?? '') === 'admin';
}

function exiger_connexion(): array
{
    $utilisateur = utilisateur_connecte();

    if ($utilisateur === null) {
        rediriger('login');
    }

    return $utilisateur;
}

function exiger_admin(): array
{
    $utilisateur = exiger_connexion();

    if ($utilisateur['role'] !== 'admin') {
        erreur_http(403, 'Accès réservé aux administrateurs.');
    }

    return $utilisateur;
}

function jeton_csrf(): string
{
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf'];
}

function champ_csrf(): string
{
    return '<input type="hidden" name="csrf" value="' . e(jeton_csrf()) . '">';
}

function verifier_csrf(): void
{
    $jeton = $_POST['csrf'] ?? '';

    if (!is_string($jeton) || !hash_equals(jeton_csrf(), $jeton)) {
        erreur_http(403, 'Formulaire expiré ou invalide. Réessayez.');
    }
}

function message_flash(string $texte, string $type = 'succes'): void
{
    $_SESSION['flash'] = ['texte' => $texte, 'type' => $type];
}

function lire_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function entier_positif(mixed $valeur): ?int
{
    $id = filter_var($valeur, FILTER_VALIDATE_INT);
    return ($id !== false && $id > 0) ? $id : null;
}

function uniquement_post(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        erreur_http(405, 'Cette action nécessite un formulaire POST.');
    }
}
