<?php

class Utilisateur
{
    public function trouverParIdentifiant(string $identifiant): ?array
    {
        $sql = 'SELECT * FROM utilisateur
                WHERE pseudo = :pseudo OR email = :email
                LIMIT 1';

        $requete = database()->prepare($sql);
        $requete->execute([
            'pseudo' => $identifiant,
            'email' => $identifiant,
        ]);

        return $requete->fetch() ?: null;
    }

    public function existe(string $pseudo, string $email): bool
    {
        $sql = 'SELECT COUNT(*) FROM utilisateur
                WHERE pseudo = :pseudo OR email = :email';

        $requete = database()->prepare($sql);
        $requete->execute(['pseudo' => $pseudo, 'email' => $email]);

        return (int) $requete->fetchColumn() > 0;
    }

    public function creer(string $nom, string $prenom, string $pseudo,
                          string $email, string $motDePasse): void
    {
        $sql = 'INSERT INTO utilisateur
                    (nom, prenom, pseudo, email, mot_de_passe)
                VALUES
                    (:nom, :prenom, :pseudo, :email, :mot_de_passe)';

        $requete = database()->prepare($sql);
        $requete->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'pseudo' => $pseudo,
            'email' => $email,
            'mot_de_passe' => password_hash($motDePasse, PASSWORD_DEFAULT),
        ]);
    }
}
