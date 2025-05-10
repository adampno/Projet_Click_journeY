--""-- --------------------------------------------------------
-- Base de données : clickjourney
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS clickjourney;
USE clickjourney;

-- --------------------------------------------------------
-- Table utilisateurs
-- --------------------------------------------------------
CREATE TABLE utilisateurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  role ENUM('utilisateur', 'admin') NOT NULL DEFAULT 'utilisateur',
  date_naissance DATE NOT NULL,
  adresse TEXT NOT NULL,
  region VARCHAR(100) NOT NULL,
  date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  derniere_connexion TIMESTAMP NULL
);

-- --------------------------------------------------------
-- Table aeroports
-- --------------------------------------------------------
CREATE TABLE aeroports (
  id_aeroport INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  ville VARCHAR(100) NOT NULL,
  region VARCHAR(100) NOT NULL
);

-- --------------------------------------------------------
-- Table voyages
-- --------------------------------------------------------
CREATE TABLE voyages (
  id_voyage INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(100) NOT NULL,
  date_debut DATE NOT NULL,
  date_fin DATE NOT NULL,
  duree INT DEFAULT NULL,
  specificites TEXT DEFAULT NULL,
  prix_total DECIMAL(10,2) DEFAULT NULL,
  statut ENUM('payé', 'en cours de modification', 'en attente') DEFAULT 'en attente',
  id_utilisateur INT DEFAULT NULL,
  FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Table vols
-- --------------------------------------------------------
CREATE TABLE vols (
  id_vol INT AUTO_INCREMENT PRIMARY KEY,
  aeroport_depart VARCHAR(100) NOT NULL,
  aeroport_arrivee VARCHAR(100) NOT NULL,
  date_depart DATETIME NOT NULL,
  date_arrivee DATETIME NOT NULL,
  prix DECIMAL(10,2) NOT NULL,
  id_voyage INT NOT NULL,
  FOREIGN KEY (id_voyage) REFERENCES voyages(id_voyage) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Table hebergements
-- --------------------------------------------------------
CREATE TABLE hebergements (
  id_hebergement INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  type_hebergement ENUM('hotel', 'tente', 'villa') NOT NULL,
  niveau ENUM('3 étoiles', '4 étoiles', '5 étoiles', 'luxe') NOT NULL,
  prix_par_nuit DECIMAL(10,2) NOT NULL,
  id_voyage INT NOT NULL,
  FOREIGN KEY (id_voyage) REFERENCES voyages(id_voyage) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Table activites
-- --------------------------------------------------------
CREATE TABLE activites (
  id_activite INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  type_activite ENUM('sportive', 'culturelle', 'aventure', 'détente') NOT NULL,
  prix_par_personne DECIMAL(10,2) NOT NULL,
  description TEXT,
  id_voyage INT NOT NULL,
  FOREIGN KEY (id_voyage) REFERENCES voyages(id_voyage) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Insertion des aéroports en France
-- --------------------------------------------------------
INSERT INTO aeroports (nom, ville, region) VALUES
('Aéroport de Bordeaux', 'Bordeaux', 'nouvelle-aquitaine'),
('Aéroport de Brest', 'Brest', 'bretagne'),
('Aéroport de Caen', 'Caen', 'normandie'),
('Aéroport de Clermont Ferrand', 'Clermont', 'auvergne-rhone-alpes'),
('Aéroport de Dijon', 'Dijon', 'bourgogne-franche-comte'),
('Aéroport de Grenoble', 'Grenoble', 'auvergne-rhone-alpes'),
('Aéroport de Lille', 'Lille', 'hauts-de-france'),
('Aéroport de Lyon', 'Lyon', 'auvergne-rhone-alpes'),
('Aéroport de Marseille', 'Marseille', 'provence-alpes-cote-d-azur'),
('Aéroport de Montpellier', 'Montpellier', 'occitanie'),
('Aéroport de Nantes', 'Nantes', 'pays-de-la-loire'),
('Aéroport de Nice', 'Nice', 'provence-alpes-cote-d-azur'),
('Aéroport de Paris', 'Paris', 'ile-de-france'),
('Aéroport de Rennes', 'Rennes', 'bretagne'),
('Aéroport de Strasbourg', 'Strasbourg', 'grand-est'),
('Aéroport de Toulouse', 'Toulouse', 'occitanie');

""

