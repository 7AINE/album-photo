<?php

class Commentaire
{
    public function pourPhoto(int $idPhoto, bool $admin): array
    {
        $sql = 'SELECT commentaire.*, utilisateur.pseudo
                FROM commentaire
                INNER JOIN utilisateur
                    ON utilisateur.id_utilisateur = commentaire.id_utilisateur
                WHERE commentaire.id_photo = :photo';

        if (!$admin) {
            $sql .= " AND commentaire.statut_suppression = 'actif'";
        }

        $sql .= ' ORDER BY commentaire.date_commentaire DESC, commentaire.id_commentaire DESC';
        $requete = database()->prepare($sql);
        $requete->execute(['photo' => $idPhoto]);

        return $requete->fetchAll();
    }

    public function trouver(int $id): ?array
    {
        $sql = 'SELECT * FROM commentaire WHERE id_commentaire = :id';
        $requete = database()->prepare($sql);
        $requete->execute(['id' => $id]);
        return $requete->fetch() ?: null;
    }

    public function creer(string $contenu, int $idUtilisateur, int $idPhoto): void
    {
        $sql = 'INSERT INTO commentaire (contenu, id_utilisateur, id_photo)
                VALUES (:contenu, :utilisateur, :photo)';
        $requete = database()->prepare($sql);
        $requete->execute([
            'contenu' => $contenu,
            'utilisateur' => $idUtilisateur,
            'photo' => $idPhoto,
        ]);
    }

    public function demanderSuppression(int $id): void
    {
        $sql = "UPDATE commentaire
                SET statut_suppression = 'en_attente',
                    date_demande_suppression = NOW()
                WHERE id_commentaire = :id AND statut_suppression = 'actif'";
        $requete = database()->prepare($sql);
        $requete->execute(['id' => $id]);
    }

    public function supprimer(int $id): void
    {
        $requete = database()->prepare('DELETE FROM commentaire WHERE id_commentaire = :id');
        $requete->execute(['id' => $id]);
    }

    public function purger(int $minutes): void
    {
        $sql = "DELETE FROM commentaire
                WHERE statut_suppression = 'en_attente'
                AND date_demande_suppression <= DATE_SUB(NOW(), INTERVAL :minutes MINUTE)";
        $requete = database()->prepare($sql);
        $requete->bindValue(':minutes', $minutes, PDO::PARAM_INT);
        $requete->execute();
    }
}
