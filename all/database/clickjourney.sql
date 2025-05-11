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
-- Table paiements
-- --------------------------------------------------------
CREATE TABLE paiements (
  id_paiement INT AUTO_INCREMENT PRIMARY KEY,
  montant DECIMAL(10,2) NOT NULL,
  date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
  methode_paiement VARCHAR(20) NOT NULL,
  statut ENUM('en_attente', 'validé', 'annulé') DEFAULT 'en_attente',
  id_utilisateur INT NOT NULL,
  id_voyage INT NOT NULL,
  reference_paiement VARCHAR(50) DEFAULT NULL,
  FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE,
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




-- Insertion de données de test

INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, date_naissance, adresse, region) VALUES
('Kellai', 'Jean', 'jean@example.com', 'mdp123', 'utilisateur', '1990-05-14', '12 Rue de Paris, Paris', 'ile-de-france'),
('Doe', 'Jane', 'jane@example.com', 'mdp456', 'admin', '1985-08-24', '4 Rue de Lyon, Lyon', 'auvergne-rhone-alpes');

INSERT INTO voyages (titre, description, date_debut, date_fin, duree, specificites, prix_total, statut, id_utilisateur) VALUES
('Visite de Chichén Itzá', 'Découverte des pyramides et sites historiques', '2025-07-01', '2025-07-07', 6, 'Historique, culturel', 1500.00, 'en attente', 1);

INSERT INTO hebergements (nom, type_hebergement, niveau, prix_par_nuit, description, id_voyage) VALUES
('Hôtel Maya', 'hotel', '5 étoiles', 200.00, 'Vue sur les pyramides, piscine et spa.', 1),
('Tente Deluxe', 'tente', 'luxe', 100.00, 'Expérience unique sous les étoiles', 1),
('Villa Chichén', 'villa', '4 étoiles', 300.00, 'Luxueuse villa avec jardin privé.', 1);

INSERT INTO activites (nom, type_activite, prix_par_personne, description, id_voyage) VALUES
('Visite guidée des pyramides', 'culturelle', 30.00, 'Explorez les ruines avec un guide expert.', 1),
('Balade à cheval', 'aventure', 50.00, 'Découvrez les alentours à cheval.', 1),
('Dîner spectacle Maya', 'détente', 70.00, 'Un dîner typique avec danses traditionnelles.', 1);