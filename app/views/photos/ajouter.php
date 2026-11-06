<div class="panneau formulaire largeur-moyenne">
    <h1>Ajouter une photo</h1>
    <p class="texte-secondaire">Formats acceptés : JPG, PNG, WEBP. 5 Mo maximum.</p>

    <?php if (!$pages): ?>
        <p>Aucun album. Contactez un administrateur pour créer une page.</p>
    <?php else: ?>
        <form action="<?= e(url('photo-store')) ?>" method="post" enctype="multipart/form-data">
            <?= champ_csrf() ?>

            <label for="id_page">Album</label>
            <select id="id_page" name="id_page" required>
                <?php foreach ($pages as $page): ?>
                    <option value="<?= (int) $page['id_page'] ?>">
                        <?= e($page['titre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="legende">Légende</label>
            <input type="text" id="legende" name="legende" maxlength="255" required>

            <label for="photo">Photo</label>
            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" required>

            <button class="bouton" type="submit">Publier la photo</button>
        </form>
    <?php endif; ?>
</div>
