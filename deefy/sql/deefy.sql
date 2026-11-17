-- ==============================================================
-- Script de création et de remplissage de la base de données Deefy
-- BUT Informatique S3 - R3.01 Développement Web
-- ==============================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `playlist2track`;
DROP TABLE IF EXISTS `track`;
DROP TABLE IF EXISTS `playlist`;
DROP TABLE IF EXISTS `User`;
SET FOREIGN_KEY_CHECKS = 1;

-- -------------------------------------------------------------
-- Table des Utilisateurs (au cas où l'authentification est activée)
-- Rôles : 1 = STANDARD, 100 = ADMIN
-- -------------------------------------------------------------
CREATE TABLE `User` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `passwd` VARCHAR(255) NOT NULL,
    `role` INT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------
-- Table des Playlists
-- -------------------------------------------------------------
CREATE TABLE `playlist` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------
-- Table des Pistes (Tracks)
-- -------------------------------------------------------------
CREATE TABLE `track` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `titre` VARCHAR(150) NOT NULL,
    `genre` VARCHAR(50) DEFAULT NULL,
    `duree` INT NOT NULL DEFAULT 0, -- durée en secondes
    `filename` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------
-- Table d'association Playlist <-> Track
-- -------------------------------------------------------------
CREATE TABLE `playlist2track` (
    `playlist_id` INT NOT NULL,
    `track_id` INT NOT NULL,
    `no_piste_dans_liste` INT NOT NULL DEFAULT 1,
    PRIMARY KEY (`playlist_id`, `track_id`),
    CONSTRAINT `fk_pl_id` FOREIGN KEY (`playlist_id`) REFERENCES `playlist`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_tr_id` FOREIGN KEY (`track_id`) REFERENCES `track`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==============================================================
-- Données de test
-- ==============================================================

-- Utilisateur test : test@univ-lorraine.fr / mot de passe 'secret123' (haché avec BCRYPT)
INSERT INTO `User` (`id`, `email`, `passwd`, `role`) VALUES
(1, 'test@univ-lorraine.fr', '$2y$10$eO1vVp0U6tBv17z1uBkWnOD7M4ZfQeF8rL1j9vKqH1tJm9w1Z7xK6', 1);

-- Playlists initiales
INSERT INTO `playlist` (`id`, `nom`) VALUES
(1, 'Best of Rock'),
(2, 'Chill & Lo-Fi'),
(3, 'Electro Vibes');

-- Pistes initiales
INSERT INTO `track` (`id`, `titre`, `genre`, `duree`, `filename`) VALUES
(1, 'Stairway to Heaven', 'Rock', 482, 'stairway.mp3'),
(2, 'Comfortably Numb', 'Rock', 382, 'comfortably.mp3'),
(3, 'Midnight City', 'Electro', 243, 'midnight.mp3'),
(4, 'Coffee Break', 'Lo-Fi', 160, 'coffee.mp3'),
(5, 'Get Lucky', 'Funk', 248, 'get_lucky.mp3');

-- Association des pistes aux playlists
INSERT INTO `playlist2track` (`playlist_id`, `track_id`, `no_piste_dans_liste`) VALUES
(1, 1, 1),
(1, 2, 2),
(2, 4, 1),
(3, 3, 1),
(3, 5, 2);
