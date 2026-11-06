<div class="panneau formulaire largeur-moyenne">
    <h1>Créer un compte</h1>

    <?php if ($erreur): ?>
        <div class="alerte erreur" role="alert"><?= e($erreur) ?></div>
    <?php endif; ?>

    <form action="<?= e(url('register')) ?>" method="post">
        <?= champ_csrf() ?>

        <label for="nom">Nom</label>
        <input id="nom" name="nom" maxlength="100" required>

        <label for="prenom">Prénom</label>
        <input id="prenom" name="prenom" maxlength="100" required>

        <label for="pseudo">Pseudo (3 caractères minimum)</label>
        <input id="pseudo" name="pseudo" minlength="3" maxlength="50" required>

        <label for="email">E-mail</label>
        <input id="email" name="email" type="email" maxlength="255" required>

        <label for="mot_de_passe">Mot de passe (10 caractères minimum)</label>
        <input id="mot_de_passe" name="mot_de_passe" type="password"
               minlength="10" maxlength="72" autocomplete="new-password" required>

        <button class="bouton" type="submit">Créer mon compte</button>
    </form>
</div>
