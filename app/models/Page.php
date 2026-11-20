<?php

class Page
{
    public function toutes(): array
    {
        $sql = 'SELECT id_page, titre, description FROM page ORDER BY id_page';
        return database()->query($sql)->fetchAll();
    }

    public function trouver(int $id): ?array
    {
        $sql = 'SELECT * FROM page WHERE id_page = :id';
        $requete = database()->prepare($sql);
        $requete->execute(['id' => $id]);

        return $requete->fetch() ?: null;
    }

    public function creer(string $titre, string $description): void
    {
        $sql = 'INSERT INTO page (titre, description) VALUES (:titre, :description)';
        $requete = database()->prepare($sql);
        $requete->execute([
            'titre' => $titre,
            'description' => $description,
        ]);
    }

    public function modifier(int $id, string $titre, string $description): void
    {
        $sql = 'UPDATE page SET titre = :titre, description = :description WHERE id_page = :id';
        $requete = database()->prepare($sql);
        $requete->execute([
            'id' => $id,
            'titre' => $titre,
            'description' => $description,
        ]);
    }

    public function supprimer(int $idPage): bool
    {
        // Vérifier si l'album contient des photos
        $sql = "SELECT COUNT(*)
                FROM photo
                WHERE id_page = :id_page";

        $requete = database()->prepare($sql);

        $requete->execute([
            'id_page' => $idPage
        ]);

        // Récupérer le nombre de photos
        $nombrePhotos = (int) $requete->fetchColumn();

        // Refuser la suppression si l'album contient des photos
        if ($nombrePhotos > 0) {
            return false;
        }

        // Supprimer l'album vide
        $sql = "DELETE FROM page
                WHERE id_page = :id_page";

        $requete = database()->prepare($sql);

        $requete->execute([
            'id_page' => $idPage
        ]);

        // Vérifier si une ligne a bien été supprimée
        return $requete->rowCount() > 0;
    }
}
