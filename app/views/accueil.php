<!-- Bandeau principal, repris du premier projet -->
<section class="hero">
    <p class="surtitre">LA VIE DES ASSOCIATIONS</p>
    <h1>Nos meilleurs moments, en images.</h1>
    <p>Compétitions, entraînements et événements sportifs de la M2L.</p>
</section>

<?php if (!$pages): ?>
    <div class="panneau vide">
        Aucune photo sur cette page pour le moment.
    </div>
<?php else: ?>
    <!-- Une page (album) affichée à la fois -->
    <?php if (count($pages) > 1): ?>
        <nav class="onglets" aria-label="Choix de l’album">
            <?php foreach ($pages as $page): ?>
                <?php $selectionne = (int) $page['id_page'] === (int) $pageActive['id_page']; ?>

                <a
                    class="onglet <?= $selectionne ? 'actif' : '' ?>"
                    href="<?= e(url('home', ['page' => $page['id_page']])) ?>"
                >
                    <?= e($page['titre']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    <?php endif; ?>

    <?php if (!$photos): ?>
        <div class="panneau vide">
            Aucune photo sur cette page pour le moment.
        </div>
    <?php else: ?>
        <div class="grille-photos">
            <?php foreach ($photos as $photo): ?>
                <article class="carte-photo">
                    <a href="<?= e(url('photo', ['id' => $photo['id_photo']])) ?>">
                        <img
                            src="<?= e(url('image', ['id' => $photo['id_photo']])) ?>"
                            alt="<?= e($photo['legende']) ?>"
                            loading="lazy"
                        >
                    </a>

                    <div class="carte-contenu">
                        <h2><?= e($photo['legende']) ?></h2>
                        <p>Publiée par <?= e($photo['pseudo']) ?></p>

                        <?php if ($photo['statut_suppression'] === 'en_attente'): ?>
                            <span class="etiquette">En cours de suppression</span>
                        <?php endif; ?>

                        <a class="lien-detail" href="<?= e(url('photo', ['id' => $photo['id_photo']])) ?>">
                            Voir la photo et les commentaires →
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
