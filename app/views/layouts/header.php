<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titre ?? 'Album Photo M2L') ?> | M2L</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <header class="site-header">
        <div class="container navigation">
            <a class="logo" href="<?= e(url()) ?>">M2L <span>Album</span></a>

            <nav aria-label="Navigation principale">
                <a href="<?= e(url()) ?>">Galerie</a>

                <?php if (utilisateur_connecte()): ?>
                    <a href="<?= e(url('photo-form')) ?>">Ajouter une photo</a>

                    <?php if (est_admin()): ?>
                        <a href="<?= e(url('admin')) ?>">Administration</a>
                    <?php endif; ?>

                    <span class="pseudo">
                        <?= e(utilisateur_connecte()['pseudo']) ?>
                    </span>

                    <form action="<?= e(url('logout')) ?>" method="post" class="inline-form">
                        <?= champ_csrf() ?>
                        <button type="submit" class="lien-bouton">Déconnexion</button>
                    </form>
                <?php else: ?>
                    <a href="<?= e(url('login')) ?>">Connexion</a>
                    <a href="<?= e(url('register')) ?>">Inscription</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container contenu">
        <?php $flash = lire_flash(); ?>

        <?php if ($flash): ?>
            <div class="alerte <?= e($flash['type']) ?>" role="status">
                <?= e($flash['texte']) ?>
            </div>
        <?php endif; ?>