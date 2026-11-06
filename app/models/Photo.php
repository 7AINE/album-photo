<?php

class Photo
{
    public function pourPage(int $idPage, bool $admin): array
    {
        $sql = 'SELECT photo.*, utilisateur.pseudo
                FROM photo
                INNER JOIN utilisateur
                    ON utilisateur.id_utilisateur = photo.id_utilisateur
                WHERE photo.id_page = :id_page';

        if (!$admin) {
            $sql .= " AND photo.statut_suppression = 'actif'";
        }

        $sql .= ' ORDER BY photo.date_ajout DESC, photo.id_photo DESC';
        $requete = database()->prepare($sql);
        $requete->execute(['id_page' => $idPage]);

        return $requete->fetchAll();
    }

    public function trouver(int $id): ?array
    {
        $sql = 'SELECT photo.*, utilisateur.pseudo, page.titre AS titre_page
                FROM photo
                INNER JOIN utilisateur
                    ON utilisateur.id_utilisateur = photo.id_utilisateur
                INNER JOIN page
                    ON page.id_page = photo.id_page
                WHERE photo.id_photo = :id';

        $requete = database()->prepare($sql);
        $requete->execute(['id' => $id]);

        return $requete->fetch() ?: null;
    }

    public function creer(string $fichier, string $mime, string $legende,
                          int $idUtilisateur, int $idPage): void
    {
        $sql = 'INSERT INTO photo
                    (nom_fichier, type_mime, legende, id_utilisateur, id_page)
                VALUES
                    (:fichier, :mime, :legende, :utilisateur, :page)';

        $requete = database()->prepare($sql);
        $requete->execute([
            'fichier' => $fichier,
            'mime' => $mime,
            'legende' => $legende,
            'utilisateur' => $idUtilisateur,
            'page' => $idPage,
        ]);
    }

    public function demanderSuppression(int $id): void
    {
        $sql = "UPDATE photo
                SET statut_suppression = 'en_attente',
                    date_demande_suppression = NOW()
                WHERE id_photo = :id AND statut_suppression = 'actif'";

        $requete = database()->prepare($sql);
        $requete->execute(['id' => $id]);
    }

    public function supprimer(int $id): void
    {
        $requete = database()->prepare('DELETE FROM photo WHERE id_photo = :id');
        $requete->execute(['id' => $id]);
    }

    public function expirees(int $minutes): array
    {
        $sql = "SELECT id_photo, nom_fichier FROM photo
                WHERE statut_suppression = 'en_attente'
                AND date_demande_suppression <= DATE_SUB(NOW(), INTERVAL :minutes MINUTE)";

        $requete = database()->prepare($sql);
        $requete->bindValue(':minutes', $minutes, PDO::PARAM_INT);
        $requete->execute();

        return $requete->fetchAll();
    }
}
