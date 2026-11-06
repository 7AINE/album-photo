<section class="intro">
    <p class="surtitre">Espace réservé</p>
    <h1>Administration</h1>
    <p>Un administrateur peut créer des albums et supprimer les photos et commentaires immédiatement.</p>
</section>

<section class="panneau formulaire largeur-moyenne">
    <h2>Nouvel album</h2>
    <form action="<?= e(url('page-store')) ?>" method="post">
        <?= champ_csrf() ?>

        <label for="titre">Titre</label>
        <input id="titre" name="titre" maxlength="150" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" maxlength="2000" rows="3"></textarea>

        <button class="bouton" type="submit">Créer l'album</button>
    </form>
</section>

<section class="panneau">
    <h2>Albums existants</h2>
    <?php foreach ($pages as $page): ?>
        <p><a href="<?= e(url('home', ['page' => $page['id_page']])) ?>">
            <?= e($page['titre']) ?>
        </a></p>
    <?php endforeach; ?>

    <p class="texte-secondaire">
        Pour retirer définitivement une photo ou un commentaire, ouvrez son album.
        Les éléments en cours de suppression restent visibles pour les administrateurs.
    </p>
</section>
