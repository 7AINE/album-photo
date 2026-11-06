<div class="panneau formulaire largeur-moyenne">
    <h1>Connexion</h1>

    <?php if ($erreur): ?>
        <div class="alerte erreur" role="alert"><?= e($erreur) ?></div>
    <?php endif; ?>

    <form action="<?= e(url('login')) ?>" method="post">
        <?= champ_csrf() ?>

        <label for="identifiant">Pseudo ou e-mail</label>
        <input id="identifiant" name="identifiant" type="text" autocomplete="username" required>

        <label for="mot_de_passe">Mot de passe</label>
        <input id="mot_de_passe" name="mot_de_passe" type="password"
               autocomplete="current-password" required>

        <button class="bouton" type="submit">Se connecter</button>
    </form>

    <p>Pas de compte ? <a href="<?= e(url('register')) ?>">Inscrivez-vous</a>.</p>
</div>
