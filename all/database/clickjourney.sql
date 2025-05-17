-- --------------------------------------------------------
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
  date_debut DATE DEFAULT NULL,
  date_fin DATE DEFAULT NULL,
  duree INT NOT NULL,
  prix DECIMAL(10,2) DEFAULT NULL,
  statut ENUM('payé', 'en cours de modification', 'en attente') DEFAULT 'en attente',
  id_utilisateur INT DEFAULT NULL,
  FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE
);


-- --------------------------------------------------------
-- Table vols
-- --------------------------------------------------------
CREATE TABLE vols (
  id_vol INT AUTO_INCREMENT PRIMARY KEY,
  aeroport_depart VARCHAR(100) DEFAULT NULL,
  aeroport_arrivee VARCHAR(100) NOT NULL,
  heure_depart TIME NOT NULL,
  heure_arrivee TIME NOT NULL,
  duree VARCHAR(20) NOT NULL,
  prix DECIMAL(10,2) NOT NULL,
  type_vol ENUM('aller', 'retour') NOT NULL,
  id_voyage INT NOT NULL,
  FOREIGN KEY (id_voyage) REFERENCES voyages(id_voyage) ON DELETE CASCADE
);


-- --------------------------------------------------------
-- Table hebergements
-- --------------------------------------------------------
CREATE TABLE hebergements (
  id_hebergement INT NOT NULL,
  h_nom VARCHAR(100) NOT NULL,
  etoiles INT NOT NULL,
  h_localisation VARCHAR(100) NOT NULL,
  h_prix DECIMAL(10,2) NOT NULL,
  id_voyage INT NOT NULL,
  PRIMARY KEY (id_hebergement, id_voyage),
  FOREIGN KEY (id_voyage) REFERENCES voyages(id_voyage) ON DELETE CASCADE
);



-- --------------------------------------------------------
-- Table des caractéristiques des hebergements
-- --------------------------------------------------------
CREATE TABLE hebergement_caracteristiques (
  id_caracteristique INT AUTO_INCREMENT PRIMARY KEY,
  id_voyage INT NOT NULL,
  id_hebergement INT NOT NULL,
  transfert ENUM('oui', 'non') NOT NULL,
  nb_piscines INT NOT NULL,
  jacuzzi ENUM('oui', 'non') NOT NULL,
  spa ENUM('oui', 'non') NOT NULL,
  chaises_longues ENUM('oui', 'non') NOT NULL DEFAULT 'oui',
  parasols_plage ENUM('oui', 'non') NOT NULL DEFAULT 'oui',
  pension ENUM('petit-dejeuner', 'demi-pension', 'all inclusive') NOT NULL,
  wifi_gratuit ENUM('oui', 'non') NOT NULL,
  tv_chambres ENUM('oui', 'non') NOT NULL,
  climatisation ENUM('oui', 'non') NOT NULL,
  seche_cheveux ENUM('oui', 'non') NOT NULL,
  balcon_pv ENUM('oui', 'non') NOT NULL,
  laverie ENUM('oui', 'non') NOT NULL,
  pmr ENUM('oui', 'non') NOT NULL,
  FOREIGN KEY (id_hebergement, id_voyage) REFERENCES hebergements(id_hebergement, id_voyage) ON DELETE CASCADE
);




-- --------------------------------------------------------
-- Table activites
-- --------------------------------------------------------
CREATE TABLE activites (
  id_activite INT AUTO_INCREMENT PRIMARY KEY,
  id_voyage INT NOT NULL,
  a_nom VARCHAR(100) NOT NULL,
  a_description TEXT NOT NULL,
  a_duree VARCHAR(50) NOT NULL,
  mode_transport ENUM('À pied', 'Car', 'Vélo') NOT NULL,
  a_heure_depart TIME NOT NULL,
  a_prix DECIMAL(10, 2) NOT NULL,
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




-- --------------------------------------------------------
-- Insertion des 7 voyages 
-- --------------------------------------------------------
INSERT INTO voyages(titre, duree) VALUES
('Chichén Itza', 6);
SET @id_chichen_itza = LAST_INSERT_ID();

INSERT INTO voyages(titre, duree) VALUES
('Christ Rédempteur', 7);
SET @id_christ_redempteur = LAST_INSERT_ID();

INSERT INTO voyages(titre, duree) VALUES
('Pétra', 6);
SET @id_petra = LAST_INSERT_ID();

INSERT INTO voyages(titre, duree) VALUES
('Colisée', 6);
SET @id_colisee = LAST_INSERT_ID();

INSERT INTO voyages(titre, duree) VALUES
('Machu Picchu', 6);
SET @id_machu_picchu = LAST_INSERT_ID();

INSERT INTO voyages(titre, duree) VALUES
('Taj Mahal', 6);
SET @id_taj_mahal = LAST_INSERT_ID();

INSERT INTO voyages(titre, duree) VALUES
('Grande Muraille de Chine', 6);
SET @id_chine = LAST_INSERT_ID();




-- --------------------------------------------------------
-- Insertion des vols associés aux voyages 
-- --------------------------------------------------------
-- Chichén Itza
INSERT INTO vols(aeroport_depart, aeroport_arrivee, heure_depart, heure_arrivee, duree, prix, type_vol, id_voyage) VALUES
('CHOIX_UTILISATEUR', 'Mérida(MID)', '08:15:00', '10:45:00', '10h30min', 229.00, 'aller', @id_chichen_itza), 
('Mérida(MID)', 'CHOIX_UTILISATEUR', '12:35:00', '07:05:00', '10h30min', 229.00, 'retour', @id_chichen_itza );

-- Christ-Rédempteur
INSERT INTO vols(aeroport_depart, aeroport_arrivee, heure_depart, heure_arrivee, duree, prix, type_vol, id_voyage) VALUES
('CHOIX_UTILISATEUR', 'Rio de Janiero-Galeão(GIG)', '07:45:00', '14:20:00', '11h35min', 287..00 ,'aller', @id_christ_redempteur), 
('Rio de Janiero-Galeão(GIG)', 'CHOIX_UTILISATEUR', '11:05:00', '03:20:00', '11h35min',  287.00, 'retour', @id_christ_redempteur );

-- Pétra
INSERT INTO vols(aeroport_depart, aeroport_arrivee, heure_depart, heure_arrivee, duree, prix, type_vol, id_voyage) VALUES
('CHOIX_UTILISATEUR', ' ', ' ', ' ', ' h min',  , 'aller',  @id_petra), 
(' ', 'CHOIX_UTILISATEUR', ' ', ' ', ' h min',  , 'retour', @id_petra);

-- Colisée
INSERT INTO vols(aeroport_depart, aeroport_arrivee, heure_depart, heure_arrivee, duree, prix, type_vol, id_voyage) VALUES
('CHOIX_UTILISATEUR', ' ', ' ', ' ', ' h min',  , 'aller', @id_colisée), 
(' ', 'CHOIX_UTILISATEUR', ' ', ' ', ' h min',  ,'retour', @id_colisée);

-- Machu Picchu
INSERT INTO vols(aeroport_depart, aeroport_arrivee, heure_depart, heure_arrivee, duree, prix, type_vol, id_voyage) VALUES
('CHOIX_UTILISATEUR', ' ', ' ', ' ', ' h min',  , 'aller', @id_machu_picchu), 
(' ', 'CHOIX_UTILISATEUR', ' ', ' ', ' h min',  , 'retour', @id_machu_picchu);

-- Taj Mahal
INSERT INTO vols(aeroport_depart, aeroport_arrivee, heure_depart, heure_arrivee, duree, prix, type_vol, id_voyage) VALUES
('CHOIX_UTILISATEUR', ' ', ' ', ' ', ' h min',  , 'aller', @id_taj_mahal), 
(' ', 'CHOIX_UTILISATEUR', ' ', ' ', ' h min',  , 'retour', @id_taj_mahal);

-- Grande Muraille de Chine
INSERT INTO vols(aeroport_depart, aeroport_arrivee, heure_depart, heure_arrivee, duree, prix, type_vol, id_voyage) VALUES
('CHOIX_UTILISATEUR', ' ', ' ', ' ', ' h min',  , 'aller', @id_chine), 
(' ', 'CHOIX_UTILISATEUR', ' ', ' ', ' h min',  , 'retour', @id_chine);




-- --------------------------------------------------------
-- Insertion des hôtels associés aux voyages 
-- --------------------------------------------------------
-- Chichén Itza
INSERT INTO hebergements (id_hebergement, h_nom, etoiles, h_localisation, h_prix, id_voyage) VALUES
(1, 'Hôtel Alba', 2, 'Pisté, Mexique', 309.00, @id_chichen_itza),
(2, 'Hôtel Puerta', 3, 'Pisté, Mexique', 493.00, @id_chichen_itza),
(3, 'Hôtel Maya', 5, 'Pisté, Mexique', 594.00, @id_chichen_itza);

-- Christ Rédempteur
INSERT INTO hebergements (id_hebergement, h_nom, etoiles, h_localisation, h_prix, id_voyage) VALUES
(1, 'Hôtel Jo&Joe', 2, ' Cosme Velho, Brésil', 289.00, @id_christ_redempteur),
(2, 'Hôtel Os Jardins do Rio', 4, 'Cosme Velho, Brésil', 693.00, @id_christ_redempteur),
(3, 'Hôtel Santa Tereza', 5, 'Santa Tereza, Brésil', 1232.00, @id_christ_redempteur);

-- Pétra
INSERT INTO hebergements (id_hebergement, h_nom, etoiles, h_localisation, h_prix, id_voyage) VALUES
(1, 'Hôtel ',  , '  ', , @id_petra),
(2, 'Hôtel ',  , '  ', , @id_petra),
(3, 'Hôtel ',  , '  ', , @id_petra);

-- Colisée
INSERT INTO hebergements (id_hebergement, h_nom, etoiles, h_localisation, h_prix, id_voyage) VALUES
(1, 'Hôtel ',  , '  ', , @id_colisee),
(2, 'Hôtel ',  , '  ', , @id_colisee),
(3, 'Hôtel ',  , '  ', , @id_colisee);

-- Machu Picchu
INSERT INTO hebergements (id_hebergement, h_nom, etoiles, h_localisation, h_prix, id_voyage) VALUES
(1, 'Hôtel ',  , '  ', , @id_machu_picchu),
(2, 'Hôtel ',  , '  ', , @id_machu_picchu),
(3, 'Hôtel ',  , '  ', , @id_machu_picchu);

-- Taj Mahal
INSERT INTO hebergements (id_hebergement, h_nom, etoiles, h_localisation, h_prix, id_voyage) VALUES
(1, 'Hôtel ',  , '  ', , @id_taj_mahal),
(2, 'Hôtel ',  , '  ', , @id_taj_mahal),
(3, 'Hôtel ',  , '  ', , @id_taj_mahal);

-- Grande Muraille de Chine
INSERT INTO hebergements (id_hebergement, h_nom, etoiles, h_localisation, h_prix, id_voyage) VALUES
(1, 'Hôtel ',  , '  ', , @id_chine),
(2, 'Hôtel ',  , '  ', , @id_chine),
(3, 'Hôtel ',  , '  ', , @id_chine);




-- --------------------------------------------------------
-- Insertion des caractéristiques des hôtels 
-- --------------------------------------------------------
-- Chichén Itza
INSERT INTO hebergement_caracteristiques(id_voyage, id_hebergement, transfert, nb_piscines, jacuzzi, spa, pension, wifi_gratuit, tv_chambres, climatisation, seche_cheveux, balcon_pv, laverie, pmr) VALUES
(@id_chichen_itza, 1, 'oui', 2, 'non', 'non', 'petit-dejeuner', 'oui', 'non', 'non', 'non', 'non', 'non', 'non' ),
(@id_chichen_itza, 2, 'oui', 2, 'non', 'oui', 'demi-pension', 'oui', 'oui', 'oui', 'non', 'non', 'oui', 'oui'),
(@id_chichen_itza, 3, 'oui', 3, 'oui', 'oui', 'all inclusive', 'oui', 'oui', 'oui', 'oui', 'oui', 'oui', 'oui');

-- Christ Rédempteur
INSERT INTO hebergement_caracteristiques(id_voyage, id_hebergement, transfert, nb_piscines, jacuzzi, spa, pension, wifi_gratuit, tv_chambres, climatisation, seche_cheveux, balcon_pv, laverie, pmr) VALUES
(@id_christ_redempteur, 1, 'oui', 1, 'non', 'non', 'demi-pension', 'oui', 'non', 'non', 'non', 'non', 'non', 'non' ),
(@id_christ_redempteur, 2, 'oui', 2, 'non', 'oui', 'all inclusive', 'oui', 'oui', 'oui', 'non', 'non', 'non', 'oui' ),
(@id_christ_redempteur, 3, 'oui', 1, 'oui', 'oui', 'all inclusive', 'oui', 'oui', 'oui', 'oui', ' oui', 'oui', 'oui' );

-- Pétra
INSERT INTO hebergement_caracteristiques(id_voyage, id_hebergement, transfert, nb_piscines, jacuzzi, spa, pension, wifi_gratuit, tv_chambres, climatisation, seche_cheveux, balcon_pv, laverie, pmr) VALUES
(@id_petra, 1, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' ),
(@id_petra, 2, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' ),
(@id_petra, 3, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' );

-- Colisée
INSERT INTO hebergement_caracteristiques(id_voyage, id_hebergement, transfert, nb_piscines, jacuzzi, spa, pension, wifi_gratuit, tv_chambres, climatisation, seche_cheveux, balcon_pv, laverie, pmr) VALUES
(@id_colisee, 1, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' ),
(@id_colisee, 2, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' ),
(@id_colisee, 3, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' );

-- Machu Picchu
INSERT INTO hebergement_caracteristiques(id_voyage, id_hebergement, transfert, nb_piscines, jacuzzi, spa, pension, wifi_gratuit, tv_chambres, climatisation, seche_cheveux, balcon_pv, laverie, pmr) VALUES
(@id_machu_picchu, 1, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' ),
(@id_machu_picchu, 2, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' ),
(@id_machu_picchu, 3, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' );

-- Taj Mahal
INSERT INTO hebergement_caracteristiques(id_voyage, id_hebergement, transfert, nb_piscines, jacuzzi, spa, pension, wifi_gratuit, tv_chambres, climatisation, seche_cheveux, balcon_pv, laverie, pmr) VALUES
(@id_taj_mahal, 1, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' ),
(@id_taj_mahal, 2, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' ),
(@id_taj_mahal, 3, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' );

-- Grande Muraille de Chine
INSERT INTO hebergement_caracteristiques(id_voyage, id_hebergement, transfert, nb_piscines, jacuzzi, spa, pension, wifi_gratuit, tv_chambres, climatisation, seche_cheveux, balcon_pv, laverie, pmr) VALUES
(@id_chine, 1, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' ),
(@id_chine, 2, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' ),
(@id_chine, 3, ' ',  , ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' );





-- --------------------------------------------------------
-- Insertion des activités associées aux voyages 
-- --------------------------------------------------------
-- Chichén Itza
INSERT INTO activites(id_voyage, a_nom, a_description, a_duree, mode_transport, a_heure_depart, a_prix) VALUES
(@id_chichen_itza, "Église de Pisté", "Découvrez l'Église de Pisté, un joyau colonial au coeur du village, témoin de l'histoire de Yucatàn. Plongez dans son atmosphère paisible et ses récits anciens lors d'une visite guidée captivante.", '1h30', 'À pied', '09:30:00', 10.00),
(@id_chichen_itza, "Chichén Itza", "Explorez Chichén Itza, l'une des sept merveilles du monde, et plongez dans les mystères de la civilisation maya lors d'une visite guidée inoubliable. Découvrez la majestueuse pyramide de Kukulcàn, le temple des Guerriers et bien plus encore !", '3h', 'À pied', '08:30:00', 45.00),
(@id_chichen_itza, "Cenote Ik Kil", "Plongez dans les eaux cristallines du Cenote Ik Kil, un joyau naturel au coeur de la jungle maya. Découvrez son histoire sacrée lors d'une visite guidée et vivez une expérience unique entre nature et légende.", '2h', 'Car', '09:30:00', 15.00),
(@id_chichen_itza, "Site Archéologique d'Ek Balam", "Découvrez le site archéologique d'Ek Balam, un trésor maya méconnu entouré de jungle luxuriante. Grimpez au sommet de l'Acropole pour une vue imprenable et explorez ses mystères lors d'une visite guidée captivante.", '3h', 'Car', '08:30:00', 45.00),
(@id_chichen_itza, "Cenote X'Canché", "Évadez-vous au coeur de la jungle pour découvrir le Cenote X'Canché, un bassin naturel entouré de lianes et de végétation luxuriante. Profitez d'une visite guidée pour explorer ses eaux cristallines et en apprendre davantage sur les rituels sacrés des Mayas.",'2h30', 'Car', '09:00:00', 25.00),
(@id_chichen_itza, "Cenotes Dzitnup", "Plongez dans l'univers mystique des Cénotes Dzitnup, Xkeken et Samula, célèbres pour leurs formations spectaculaires et leurs eaux cristallines. Lors de la visite guidée, découvrez les légendes mayas qui entourent ces cavernes enchantées", '3h', 'Car', '13:30:00', 25.00);





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