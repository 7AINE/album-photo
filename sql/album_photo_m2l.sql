    -- Base de données : Album Photo M2L (MySQL / MariaDB)
    -- À importer avec phpMyAdmin, onglet SQL / Importer.

    CREATE DATABASE IF NOT EXISTS album_photo_m2l
        CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

    USE album_photo_m2l;

    CREATE TABLE IF NOT EXISTS utilisateur (
        id_utilisateur INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        prenom VARCHAR(100) NOT NULL,
        pseudo VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,
        mot_de_passe VARCHAR(255) NOT NULL,
        role ENUM('membre', 'admin') NOT NULL DEFAULT 'membre',
        date_inscription DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS page (
        id_page INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        titre VARCHAR(150) NOT NULL,
        description TEXT NULL,
        date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS photo (
        id_photo INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nom_fichier VARCHAR(255) NOT NULL,
        type_mime VARCHAR(30) NOT NULL,
        legende VARCHAR(255) NOT NULL,
        date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        statut_suppression ENUM('actif', 'en_attente') NOT NULL DEFAULT 'actif',
        date_demande_suppression DATETIME NULL,
        id_utilisateur INT UNSIGNED NOT NULL,
        id_page INT UNSIGNED NOT NULL,
        INDEX idx_photo_page (id_page),
        INDEX idx_photo_purge (statut_suppression, date_demande_suppression),
        CONSTRAINT fk_photo_utilisateur FOREIGN KEY (id_utilisateur)
            REFERENCES utilisateur (id_utilisateur) ON DELETE RESTRICT,
        CONSTRAINT fk_photo_page FOREIGN KEY (id_page)
            REFERENCES page (id_page) ON DELETE RESTRICT
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS commentaire (
        id_commentaire INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        contenu TEXT NOT NULL,
        date_commentaire DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        statut_suppression ENUM('actif', 'en_attente') NOT NULL DEFAULT 'actif',
        date_demande_suppression DATETIME NULL,
        id_utilisateur INT UNSIGNED NOT NULL,
        id_photo INT UNSIGNED NOT NULL,
        INDEX idx_commentaire_photo (id_photo),
        INDEX idx_commentaire_purge (statut_suppression, date_demande_suppression),
        CONSTRAINT fk_commentaire_utilisateur FOREIGN KEY (id_utilisateur)
            REFERENCES utilisateur (id_utilisateur) ON DELETE RESTRICT,
        CONSTRAINT fk_commentaire_photo FOREIGN KEY (id_photo)
            REFERENCES photo (id_photo) ON DELETE CASCADE
    ) ENGINE=InnoDB;

    -- Albums de démonstration : insérés seulement si la table est vide.
    INSERT INTO page (titre, description)
    SELECT 'Football', 'Rencontres et compétitions de football'
    WHERE NOT EXISTS (SELECT 1 FROM page);

    INSERT INTO page (titre, description)
    SELECT 'Basketball', 'Tournois et événements de basketball'
    WHERE NOT EXISTS (SELECT 1 FROM page WHERE titre = 'Basketball');

    -- Pour créer votre PREMIER administrateur :
    -- 1. Inscrivez un compte normalement sur le site.
    -- 2. Remplacez le pseudo ci-dessous par le vôtre dans phpMyAdmin :
    -- UPDATE utilisateur SET role = 'admin' WHERE pseudo = 'votre_pseudo';
