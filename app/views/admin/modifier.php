<!-- ==========================================
     FORMULAIRE DE MODIFICATION D'UN ALBUM
     ========================================== -->

<section class="intro">

    <p class="surtitre">Administration</p>

    <h1>Modifier un album</h1>

    <p>
        Modifiez le titre et la description de l'album.
    </p>

</section>

<section class="panneau formulaire largeur-moyenne">

    <form
        action="<?= e(url('page-update')) ?>"
        method="post">

        <!-- Protection CSRF -->
        <?= champ_csrf() ?>

        <!-- Identifiant de l'album -->
        <input
            type="hidden"
            name="id_page"
            value="<?= (int) $page['id_page'] ?>">

        <!-- Titre -->
        <label for="titre">
            Titre de l'album
        </label>

        <input
            type="text"
            id="titre"
            name="titre"
            maxlength="150"
            value="<?= e($page['titre']) ?>"
            required>

        <!-- Description -->
        <label for="description">
            Description
        </label>

        <textarea
            id="description"
            name="description"
            maxlength="2000"
            rows="4"><?= e($page['description'] ?? '') ?></textarea>

        <!-- Enregistrer -->
        <button
            type="submit"
            class="bouton">
            Enregistrer les modifications
        </button>

        <!-- Annuler -->
        <a
            href="<?= e(url('admin')) ?>"
            class="bouton secondaire">
            Annuler
        </a>

    </form>

</section>