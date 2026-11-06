<?php

class AuthController
{
    public function connexion(): void
    {
        if (utilisateur_connecte() !== null) {
            rediriger();
        }

        $erreur = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifier_csrf();

            $identifiant = trim((string) ($_POST['identifiant'] ?? ''));
            $motDePasse = (string) ($_POST['mot_de_passe'] ?? '');
            $utilisateur = (new Utilisateur())->trouverParIdentifiant($identifiant);

            if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
                session_regenerate_id(true);
                $_SESSION['utilisateur'] = [
                    'id_utilisateur' => (int) $utilisateur['id_utilisateur'],
                    'pseudo' => $utilisateur['pseudo'],
                    'role' => $utilisateur['role'],
                ];
                message_flash('Connexion réussie.');
                rediriger();
            }

            $erreur = 'Identifiant ou mot de passe incorrect.';
        }

        $titre = 'Connexion';
        afficher('auth/connexion', compact('titre', 'erreur'));
    }

    public function inscription(): void
    {
        if (utilisateur_connecte() !== null) {
            rediriger();
        }

        $erreur = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifier_csrf();

            $nom = trim((string) ($_POST['nom'] ?? ''));
            $prenom = trim((string) ($_POST['prenom'] ?? ''));
            $pseudo = trim((string) ($_POST['pseudo'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $motDePasse = (string) ($_POST['mot_de_passe'] ?? '');

            if ($nom === '' || $prenom === '' || $pseudo === '' || $email === '') {
                $erreur = 'Tous les champs sont obligatoires.';
            } elseif (mb_strlen($nom) > 100 || mb_strlen($prenom) > 100
                || mb_strlen($pseudo) < 3 || mb_strlen($pseudo) > 50) {
                $erreur = 'Vérifiez les longueurs du nom, prénom et pseudo (3 à 50 caractères).';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
                $erreur = 'Adresse e-mail invalide.';
            } elseif (strlen($motDePasse) < 10 || strlen($motDePasse) > 72) {
                $erreur = 'Le mot de passe doit contenir entre 10 et 72 caractères.';
            } elseif ((new Utilisateur())->existe($pseudo, $email)) {
                $erreur = 'Ce pseudo ou cet e-mail est déjà utilisé.';
            } else {
                try {
                    (new Utilisateur())->creer($nom, $prenom, $pseudo, $email, $motDePasse);
                    message_flash('Compte créé. Vous pouvez vous connecter.');
                    rediriger('login');
                } catch (PDOException $exception) {
                    // Unicité protégée aussi en base en cas de demandes simultanées.
                    if ($exception->getCode() === '23000') {
                        $erreur = 'Ce pseudo ou cet e-mail est déjà utilisé.';
                    } else {
                        throw $exception;
                    }
                }
            }
        }

        $titre = 'Inscription';
        afficher('auth/inscription', compact('titre', 'erreur'));
    }

    public function deconnexion(): void
    {
        uniquement_post();
        verifier_csrf();

        $_SESSION = [];
        session_regenerate_id(true);
        message_flash('Vous êtes déconnecté.');
        rediriger();
    }
}
