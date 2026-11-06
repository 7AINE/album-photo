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
}
