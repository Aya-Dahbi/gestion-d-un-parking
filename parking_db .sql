-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 08 nov. 2026 à 13:02
-- Version du serveur : 10.11.16-MariaDB
-- Version de PHP : 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `parking_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `place`
--

CREATE TABLE `place` (
  `id_place` int(11) NOT NULL,
  `numero_place` varchar(50) NOT NULL,
  `etat` enum('libre','occupee') DEFAULT 'libre'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `place`
--

INSERT INTO `place` (`id_place`, `numero_place`, `etat`) VALUES
(1, 'A-01', 'libre'),
(2, 'A-02', 'libre'),
(3, 'A-03', 'libre'),
(4, 'A-04', 'libre'),
(5, 'A-05', 'libre'),
(6, 'A-06', 'libre'),
(7, 'A-07', 'libre'),
(8, 'A-08', 'libre'),
(9, 'B-01', 'libre'),
(10, 'B-02', 'libre'),
(11, 'B-03', 'libre'),
(12, 'B-04', 'libre'),
(13, 'B-05', 'libre'),
(14, 'B-06', 'libre'),
(15, 'B-07', 'libre'),
(16, 'B-08', 'libre');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id_reservation` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_place` int(11) NOT NULL,
  `heure_debut` datetime NOT NULL,
  `heure_fin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id_reservation`, `id_utilisateur`, `id_place`, `heure_debut`, `heure_fin`) VALUES
(1, 1, 8, '2026-11-06 13:48:00', '2026-11-06 15:48:00'),
(2, 1, 7, '2026-11-06 13:58:00', '2026-11-06 15:00:00'),
(3, 1, 8, '2026-11-06 16:17:00', '2026-11-06 16:29:06'),
(4, 1, 7, '2026-11-06 16:17:00', '2026-11-06 16:29:16'),
(5, 1, 14, '2026-11-06 16:27:00', '2026-11-06 16:29:19'),
(6, 1, 12, '2026-11-06 16:33:14', '2026-11-06 17:33:00'),
(7, 1, 8, '2026-11-06 16:33:30', '2026-11-06 18:18:10'),
(12, 6, 7, '2026-11-07 12:30:36', '2026-11-07 12:31:09'),
(13, 6, 8, '2026-11-07 12:35:51', '2026-11-07 12:36:58'),
(14, 6, 7, '2026-11-07 12:36:40', '2026-11-07 12:37:01'),
(15, 6, 2, '2026-11-07 12:38:51', '2026-11-07 12:39:31'),
(16, 6, 8, '2026-11-07 13:21:21', '2026-11-07 13:21:34');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `email`, `mot_de_passe`, `token`) VALUES
(1, 'aya dahbi', 'aya@gmail.com', '$2y$10$nZRcCj.MP7m8rzEQ9wGW4uX90sF0u5WlEtY3Puc3J.2iWzppNA3iG', NULL),
(2, 'Ahmed', 'ahmedgng@gmail.com', '$2y$10$HSunPRQfvebzsXlNLrJH9eVIAs.8NK2C1c9rbvZOmu79p8gXo0L8u', NULL),
(3, 'nihad nihal', 'nihad@gmail.com', '$2y$10$/y3VPdHeurxdfSbkqhlkier8DzoLYsX5YnjicoeUMpkr7/e91oHvW', NULL),
(4, 'chaimae mahir', 'chaimae@gmail.com', '$2y$10$Sa5hQq120goO6sQATpzojuKIE6FrMItr4vDMGUk6b4mSyQJXpoZIC', NULL),
(5, 'siham jabir', 'siham@gmail.com', '$2y$10$HCofDQoCny5H7gIyEruSeOHKrEOTPbUdX685equWD.871xK6CeQz6', NULL),
(6, 'ranya bikhar', 'ranya@gmail.com', '$2y$10$l6Q6fHB8l7JOiBcaCsrBiOeiCy17zXFUtBL7w9vLNRZr.xgV3Aso2', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`id_place`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_place` (`id_place`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `place`
--
ALTER TABLE `place`
  MODIFY `id_place` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`id_place`) REFERENCES `place` (`id_place`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
