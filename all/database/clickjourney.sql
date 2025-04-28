-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 28 avr. 2025 à 09:11
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `clickjourney`
--

-- --------------------------------------------------------

--
-- Structure de la table `etapes`
--

CREATE TABLE `etapes` (
  `id_etape` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `date_arrivee` datetime NOT NULL,
  `date_depart` datetime NOT NULL,
  `duree` int(11) DEFAULT NULL,
  `position_gps` varchar(255) DEFAULT NULL,
  `nom_lieu` varchar(100) DEFAULT NULL,
  `id_voyage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `etapes_personnes`
--

CREATE TABLE `etapes_personnes` (
  `id_etape` int(11) NOT NULL,
  `nom_personne` varchar(100) NOT NULL,
  `type_personne` varchar(30) DEFAULT 'ami'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `options`
--

CREATE TABLE `options` (
  `id_option` int(11) NOT NULL,
  `nom_option` varchar(100) NOT NULL,
  `type_option` enum('activité sportive','activité culturelle','hébergement','restauration','transport','garde enfants','gestion linge') NOT NULL,
  `valeur_par_defaut` tinyint(1) DEFAULT 0,
  `prix_par_personne` decimal(10,2) DEFAULT NULL,
  `vitesse_moyenne` decimal(5,2) DEFAULT NULL,
  `age_minimum` int(11) DEFAULT NULL,
  `id_etape` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

CREATE TABLE `paiements` (
  `id_paiement` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_transaction` datetime DEFAULT current_timestamp(),
  `methode_paiement` varchar(20) NOT NULL,
  `statut` varchar(20) DEFAULT 'en_attente',
  `id_utilisateur` int(11) NOT NULL,
  `id_voyage` int(11) NOT NULL,
  `reference_paiement` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `Id` int(11) NOT NULL COMMENT 'Identifiant unique',
  `Nom` varchar(100) NOT NULL,
  `Prénom` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Mot_de_passe` varchar(255) NOT NULL,
  `Role` enum('utilisateur','admin') NOT NULL,
  `date_naissance` date NOT NULL,
  `adresse` text NOT NULL,
  `Date d'inscription` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Date de création',
  `Dernière connexion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Dernier login'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`Id`, `Nom`, `Prénom`, `Email`, `Mot_de_passe`, `Role`, `date_naissance`, `adresse`, `Date d'inscription`, `Dernière connexion`) VALUES
(1, 'Dupont', 'Jean', 'jean@example.com', 'mdp123', 'utilisateur', '1990-05-14', 'Paris', '2025-04-22 13:47:43', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs_voyages`
--

CREATE TABLE `utilisateurs_voyages` (
  `id_utilisateur` int(11) NOT NULL,
  `id_voyage` int(11) NOT NULL,
  `type_relation` varchar(20) NOT NULL DEFAULT 'consulté',
  `date_action` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `voyages`
--

CREATE TABLE `voyages` (
  `id_voyage` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `duree` int(11) DEFAULT NULL,
  `specificites` text DEFAULT NULL,
  `prix_total` decimal(10,2) DEFAULT NULL,
  `statut` enum('payé','en cours de modification','en attente') DEFAULT 'en attente',
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `etapes`
--
ALTER TABLE `etapes`
  ADD PRIMARY KEY (`id_etape`),
  ADD KEY `id_voyage` (`id_voyage`);

--
-- Index pour la table `etapes_personnes`
--
ALTER TABLE `etapes_personnes`
  ADD PRIMARY KEY (`id_etape`,`nom_personne`);

--
-- Index pour la table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id_option`),
  ADD KEY `id_etape` (`id_etape`);

--
-- Index pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_voyage` (`id_voyage`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Index pour la table `utilisateurs_voyages`
--
ALTER TABLE `utilisateurs_voyages`
  ADD PRIMARY KEY (`id_utilisateur`,`id_voyage`,`type_relation`),
  ADD KEY `fk_voyage` (`id_voyage`);

--
-- Index pour la table `voyages`
--
ALTER TABLE `voyages`
  ADD PRIMARY KEY (`id_voyage`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `etapes`
--
ALTER TABLE `etapes`
  MODIFY `id_etape` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `options`
--
ALTER TABLE `options`
  MODIFY `id_option` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id_paiement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `voyages`
--
ALTER TABLE `voyages`
  MODIFY `id_voyage` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `etapes`
--
ALTER TABLE `etapes`
  ADD CONSTRAINT `etapes_ibfk_1` FOREIGN KEY (`id_voyage`) REFERENCES `voyages` (`id_voyage`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_etape_voyage` FOREIGN KEY (`id_voyage`) REFERENCES `voyages` (`id_voyage`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `etapes_personnes`
--
ALTER TABLE `etapes_personnes`
  ADD CONSTRAINT `fk_etape_ep` FOREIGN KEY (`id_etape`) REFERENCES `etapes` (`id_etape`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `fk_option_etape` FOREIGN KEY (`id_etape`) REFERENCES `etapes` (`id_etape`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`id_etape`) REFERENCES `etapes` (`id_etape`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `fk_paiement_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_paiement_voyage` FOREIGN KEY (`id_voyage`) REFERENCES `voyages` (`id_voyage`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateurs_voyages`
--
ALTER TABLE `utilisateurs_voyages`
  ADD CONSTRAINT `fk_uv_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_uv_voyage` FOREIGN KEY (`id_voyage`) REFERENCES `voyages` (`id_voyage`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `voyages`
--
ALTER TABLE `voyages`
  ADD CONSTRAINT `fk_voyage_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
