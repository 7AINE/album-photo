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


<!-- ==========================================
     GESTION DES ALBUMS EXISTANTS
     ========================================== -->

<section class="panneau">

    <h2>Albums existants</h2>

    <?php if (empty($pages)): ?>

        <p>Aucun album disponible.</p>

    <?php else: ?>


        <?php foreach ($pages as $page): ?>

            <div class="album-admin">

                <!-- Nom de l'album -->
                <a href="<?= e(url('home', [
                                'page' => $page['id_page']
                            ])) ?>">
                    <?= e($page['titre']) ?>
                </a>

                <!-- Bouton modifier -->
                <button
                    type="button"
                    class="bouton petit"
                    onclick="afficherModification(
                <?= (int) $page['id_page'] ?>
            )">
                    Modifier
                </button>


                <!-- Bouton supprimer -->
                <form
                    action="<?= e(url('page-delete')) ?>"
                    method="post"
                    onsubmit="return confirm('Supprimer cet album ?');">
                    <?= champ_csrf() ?>

                    <input
                        type="hidden"
                        name="id_page"
                        value="<?= (int) $page['id_page'] ?>">

                    <button type="submit" class="bouton danger petit">
                        Supprimer
                    </button>
                </form>

            </div>

            <!-- Formulaire de modification masqué -->
            <div
                id="modification-<?= (int) $page['id_page'] ?>"
                class="formulaire-modification"
                hidden>

                <form
                    action="<?= e(url('page-update')) ?>"
                    method="post"
                    class="formulaire">
                    <?= champ_csrf() ?>

                    <input
                        type="hidden"
                        name="id_page"
                        value="<?= (int) $page['id_page'] ?>">

                    <label>Titre de l'album</label>

                    <input
                        type="text"
                        name="titre"
                        maxlength="150"
                        value="<?= e($page['titre']) ?>"
                        required>

                    <label>Description</label>

                    <textarea
                        name="description"
                        maxlength="2000"
                        rows="3"><?= e($page['description'] ?? '') ?></textarea>

                    <button type="submit" class="bouton">
                        Enregistrer
                    </button>

                </form>

            </div>

        <?php endforeach; ?>


    <?php endif; ?>

    <p class="texte-secondaire">
        Un album doit être vide pour pouvoir être supprimé.
        Les photos et commentaires doivent être supprimés
        au préalable.
    </p>

</section>

<script>
    // ==========================================
    // AFFICHER / MASQUER LE FORMULAIRE
    // ==========================================

    function afficherModification(idPage) {

        // Récupérer le formulaire de l'album
        const formulaire = document.getElementById(
            'modification-' + idPage
        );

        // Afficher ou masquer le formulaire
        formulaire.hidden = !formulaire.hidden;
    }
</script>