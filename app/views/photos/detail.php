<a class="retour" href="<?= e(url('home', ['page' => $photo['id_page']])) ?>">
    ← Retour à la galerie
</a>

<article class="detail-photo">
    <div class="detail-image">
        <img
            class="photo-large"
            src="<?= e(url('image', ['id' => $photo['id_photo']])) ?>"
            alt="<?= e($photo['legende']) ?>"
        >
    </div>

    <div class="detail-infos">
        <h1><?= e($photo['legende']) ?></h1>
        <p class="texte-secondaire">
            Album : <?= e($photo['titre_page']) ?><br>
            Publiée par <?= e($photo['pseudo']) ?><br>
            <?= e($photo['date_ajout']) ?>
        </p>

        <?php if ($photo['statut_suppression'] === 'en_attente'): ?>
            <span class="etiquette">En cours de suppression</span>
        <?php endif; ?>

        <?php
        $peutSupprimer = utilisateur_connecte()
            && (est_admin() || utilisateur_connecte()['id_utilisateur'] === (int) $photo['id_utilisateur']);
        ?>

        <?php if ($peutSupprimer && ($photo['statut_suppression'] === 'actif' || est_admin())): ?>
            <form
                action="<?= e(url('photo-delete')) ?>"
                method="post"
                onsubmit="return confirm('Confirmer la suppression de la photo ?');"
            >
                <?= champ_csrf() ?>
                <input type="hidden" name="id" value="<?= (int) $photo['id_photo'] ?>">
                <button class="bouton danger" type="submit">
                    <?= est_admin() ? 'Supprimer définitivement' : 'Demander la suppression' ?>
                </button>
            </form>
        <?php endif; ?>
    </div>
</article>

<section class="commentaires" id="commentaires">
    <h2>Commentaires (<?= count($commentaires) ?>)</h2>

    <?php if (!$commentaires): ?>
        <p>Aucun commentaire pour le moment.</p>
    <?php endif; ?>

    <?php foreach ($commentaires as $commentaire): ?>
        <article class="panneau commentaire">
            <div class="commentaire-entete">
                <strong><?= e($commentaire['pseudo']) ?></strong>
                <small><?= e($commentaire['date_commentaire']) ?></small>
            </div>

            <?php if ($commentaire['statut_suppression'] === 'en_attente'): ?>
                <span class="etiquette">En cours de suppression</span>
            <?php endif; ?>

            <p class="texte-conserve-sauts"><?= e($commentaire['contenu']) ?></p>

            <?php
            $peutSupprimerCommentaire = utilisateur_connecte()
                && (est_admin() || utilisateur_connecte()['id_utilisateur'] === (int) $commentaire['id_utilisateur']);
            ?>

            <?php if ($peutSupprimerCommentaire
                && ($commentaire['statut_suppression'] === 'actif' || est_admin())): ?>
                <form action="<?= e(url('comment-delete')) ?>" method="post">
                    <?= champ_csrf() ?>
                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int) $commentaire['id_commentaire'] ?>"
                    >
                    <button class="bouton secondaire petit" type="submit">Supprimer</button>
                </form>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>

    <?php if (utilisateur_connecte() && $photo['statut_suppression'] === 'actif'): ?>
        <form class="panneau formulaire" action="<?= e(url('comment-store')) ?>" method="post">
            <h3>Ajouter un commentaire</h3>
            <?= champ_csrf() ?>
            <input type="hidden" name="id_photo" value="<?= (int) $photo['id_photo'] ?>">

            <label for="contenu">Votre commentaire</label>
            <textarea id="contenu" name="contenu" rows="4" maxlength="2000" required></textarea>
            <button class="bouton" type="submit">Publier le commentaire</button>
        </form>
    <?php elseif (!utilisateur_connecte() && $photo['statut_suppression'] === 'actif'): ?>
        <p><a href="<?= e(url('login')) ?>">Connectez-vous</a> pour commenter cette photo.</p>
    <?php endif; ?>
</section>
