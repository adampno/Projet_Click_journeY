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

INSERT INTO voyages(titre, duree) VALUES
('Christ Rédempteur', 7);

INSERT INTO voyages(titre, duree) VALUES
('Pétra', 5);

INSERT INTO voyages(titre, duree) VALUES
('Colisée', 5);

INSERT INTO voyages(titre, duree) VALUES
('Machu Picchu', 7);

INSERT INTO voyages(titre, duree) VALUES
('Taj Mahal', 6);

INSERT INTO voyages(titre, duree) VALUES
('Grande Muraille de Chine', 6);



-- --------------------------------------------------------
-- Récupération des identifiants de voyage 
-- --------------------------------------------------------
-- Chichén Itza
SELECT id_voyage INTO @id_chichen_itza FROM voyages WHERE titre ='Chichén Itza';

-- Christ-Rédempteur
SELECT id_voyage INTO @id_christ_redempteur FROM voyages WHERE titre ='Christ Rédempteur';

-- Pétra
SELECT id_voyage INTO @id_petra FROM voyages WHERE titre ='Pétra';

-- Colisée
SELECT id_voyage INTO @id_colisee FROM voyages WHERE titre ='Colisée';

-- Machu Picchu
SELECT id_voyage INTO @id_machu_picchu FROM voyages WHERE titre ='Machu Picchu';

-- Taj Mahal
SELECT id_voyage INTO @id_taj_mahal FROM voyages WHERE titre ='Taj Mahal';

-- Grande Muraille de Chine
SELECT id_voyage INTO @id_chine FROM voyages WHERE titre ='Grande Muraille de Chine';



-- --------------------------------------------------------
-- Insertion des vols associés aux voyages 
-- --------------------------------------------------------
-- Chichén Itza
INSERT INTO vols(aeroport_depart, aeroport_arrivee, heure_depart, heure_arrivee, duree, prix, type_vol, id_voyage) VALUES
('CHOIX_UTILISATEUR', 'Mérida(MID)', '08:15:00', '10:45:00', '10h30min', 229.00, 'aller', @id_chichen_itza), 
('Mérida(MID)', 'CHOIX_UTILISATEUR', '12:35:00', '07:05:00', '10h30min', 229.00, 'retour', @id_chichen_itza );

-- Christ-Rédempteur
INSERT INTO vols(aeroport_depart, aeroport_arrivee, heure_depart, heure_arrivee, duree, prix, type_vol, id_voyage) VALUES
('CHOIX_UTILISATEUR', 'Rio de Janeiro-Galeão(GIG)', '07:45:00', '14:20:00', '11h35min', 287.00 ,'aller', @id_christ_redempteur), 
('Rio de Janiero-Galeão(GIG)', 'CHOIX_UTILISATEUR', '11:05:00', '03:20:00', '11h35min',  287.00, 'retour', @id_christ_redempteur );

-- Pétra
INSERT INTO vols(aeroport_depart, aeroport_arrivee, heure_depart, heure_arrivee, duree, prix, type_vol, id_voyage) VALUES
('CHOIX_UTILISATEUR', 'Aqaba(AQJ)', '09:45:00', '16:45:00', '6h30min', 227, 'aller',  @id_petra), 
('Aqaba(AQJ)', 'CHOIX_UTILISATEUR', '13:15:00', '17:15:00', '6h30min', 227, 'retour', @id_petra);

-- Colisée
INSERT INTO vols(aeroport_depart, aeroport_arrivee, heure_depart, heure_arrivee, duree, prix, type_vol, id_voyage) VALUES
('CHOIX_UTILISATEUR', 'Rome Ciampino(CIA)', '09:55:00', '11:55:00', '2h05min', 110.00, 'aller', @id_colisee), 
('Rome Ciampino(CIA)', 'CHOIX_UTILISATEUR', '16:15:00', '18:15:00', '2h05min', 110.00,'retour', @id_colisee);

-- Machu Picchu
INSERT INTO vols(aeroport_depart, aeroport_arrivee, heure_depart, heure_arrivee, duree, prix, type_vol, id_voyage) VALUES
('CHOIX_UTILISATEUR', 'Cusco(CUZ)', '07:35:00', '13:55:00', '15h45min', 448.00, 'aller', @id_machu_picchu), 
('Cusco(CUZ)', 'CHOIX_UTILISATEUR', '11:05:00', '10:50:00', '15h45min', 448.00, 'retour', @id_machu_picchu);

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
(1, 'Hôtel Petra Premium', 3, 'Wadi Musa, Jordanie', 420.00, @id_petra),
(2, 'Hôtel Petra Moon', 4, 'Petra, Jordanie', 640.00, @id_petra),
(3, 'Hôtel Mövenpick Resort', 5, 'Petra, Jordanie', 725.00, @id_petra);

-- Colisée
INSERT INTO hebergements (id_hebergement, h_nom, etoiles, h_localisation, h_prix, id_voyage) VALUES
(1, 'Hôtel Grifo', 3, 'Monti, Italie', 435.00, @id_colisee),
(2, 'Hôtel Mercure Roma', 4, 'Rome, Italie', 750.00, @id_colisee),
(3, 'Hôtel Palazzo Manfredi', 5, 'Rome, Italie', 1865.00, @id_colisee);

-- Machu Picchu
INSERT INTO hebergements (id_hebergement, h_nom, etoiles, h_localisation, h_prix, id_voyage) VALUES
(1, 'Hôtel Saqray', 2, 'Cusco, Pérou', 177.00, @id_machu_picchu),
(2, 'Hôtel Antigua Casona', 3, 'San Blas, Pérou', 468.00, @id_machu_picchu),
(3, 'Hôtel Palacio del Inka', 5, 'Cusco, Pérou', 1415.00, @id_machu_picchu);

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
(@id_petra, 1, 'oui', 1, 'non', 'non', 'petit-dejeuner', 'oui', 'non', 'non', 'non', 'non', 'oui', 'oui' ),
(@id_petra, 2, 'oui', 2, 'oui', 'non', 'demi-pension', 'oui', 'oui', 'non', 'non', 'non', 'oui', 'oui' ),
(@id_petra, 3, 'oui', 4, 'oui', 'oui', 'all inclusive', 'oui', 'oui', 'oui', 'oui', 'oui', 'oui', 'oui' );

-- Colisée
INSERT INTO hebergement_caracteristiques(id_voyage, id_hebergement, transfert, nb_piscines, jacuzzi, spa, pension, wifi_gratuit, tv_chambres, climatisation, seche_cheveux, balcon_pv, laverie, pmr) VALUES
(@id_colisee, 1, 'oui', 1, 'non', 'non', 'petit-dejeuner', 'non', 'non', 'non', 'non', 'non', 'non', 'non' ),
(@id_colisee, 2, 'oui', 2, 'non', 'oui', 'demi-pension', 'oui', 'oui', 'non', 'non', 'non', 'oui', 'oui' ),
(@id_colisee, 3, 'oui', 4, 'oui', 'oui', 'all inclusive', 'oui', 'oui', 'oui', 'oui', 'oui', 'oui', 'oui' );

-- Machu Picchu
INSERT INTO hebergement_caracteristiques(id_voyage, id_hebergement, transfert, nb_piscines, jacuzzi, spa, pension, wifi_gratuit, tv_chambres, climatisation, seche_cheveux, balcon_pv, laverie, pmr) VALUES
(@id_machu_picchu, 1, 'oui', 0, 'non', 'non', 'petit-dejeuner', 'non', 'non', 'non', 'non', 'non', 'non', 'non' ),
(@id_machu_picchu, 2, 'oui', 2, 'non', 'oui', 'demi-pension', 'oui', 'oui', 'oui', 'non', 'non', 'non', 'oui' ),
(@id_machu_picchu, 3, 'oui', 3, 'oui', 'oui', 'all inclusive', 'oui', 'oui', 'oui', 'oui', 'oui', 'oui', 'oui' );

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


-- Christ Rédempteur
INSERT INTO activites(id_voyage, a_nom, a_description, a_duree, mode_transport, a_heure_depart, a_prix) VALUES
(@id_christ_redempteur, "Christ Rédempteur", "Admirez l'une des 7 merveilles du monde moderne, perchée au sommet du mont Corcovado, offrant une vue spectaculaire sur Rio.", '4h', 'À pied', '08:00:00', 17.00),
(@id_christ_redempteur, "Pão de Açùcar", "Montez en téléphérique jusqu'au sommet pour une vue imprenable sur la baie de Guanabara et les plages emblématiques de Rio", '2h30', 'Car', '14:00:00', 30.00),
(@id_christ_redempteur, "Jardin botanique de Rio", 'Explorez un havre de paix abritant une incroyable diversité de plantes tropicales et de palmiers majestueux.', '2h30', 'Car', '10:00:00', 12.00),
(@id_christ_redempteur, "Musée do Amanhã", "Plongez dans un musée futuriste dédié aux sciences et à la durabilité, situé au bord de la baie de Guanabara.", '3h', 'Car', '14:00:00', 5.00),
(@id_christ_redempteur, "Quartier de Santa Teresa", "Flânez dans ce quartier bohème aux ruelles pavées, rempli d'artistes, de galeries et de charmantes maisons coloniales.", '2h', 'Car', '16:00:00', 0.00),
(@id_christ_redempteur, "Visite guidée d'une favela", "Découvrez la vie quotidienne et la culture vibrante des favelas de Rio lors d'une visite guidée respectueuse.", '3h30', 'Car', '09:30:00', 9.00),
(@id_christ_redempteur, "Excursion à Ilha Grande", "Évadez-vous sur cette île paradisiaque aux plages immaculées et eaux cristallines, idéale pour la randonnée et la plongée.", '12h', 'Car', '07:30:00', 75.00),
(@id_christ_redempteur, "Cours de cuisine brésilienne à Copacabana", "Plongez dans l'univers savoureux de la gastronomie brésilienne lors d'un atelier interactif.", '4h', 'Car', '13:00:00', 146.00);


-- Petra
INSERT INTO activites(id_voyage, a_nom, a_description, a_duree, mode_transport, a_heure_depart, a_prix) VALUES
(@id_petra, "Traversée du Siq", "Marchez à travers un canyon étroit aux parois de grès rose menant à l'emblématique Trésor, une façade monumentale taillée dans la roche.", '1h', 'À pied', '11:00:00', 44.00),
(@id_petra, "Randonnée vers le Monastère", "Gravissez environ 800 marches pour atteindre le Monastère de Ad-Deir, un édifice majestueux offrant une vue panoramique sur les montagnes environnantes.", '2h30', 'À pied', '10:00:00', 33.00),
(@id_petra, "Petra by night", "Expérience magique où le Siq et le Trésor sont illuminés par des centaines de bougies, accompagnée de musique traditionelle.", '2h', 'À pied', '11:00:00', 21.00),
(@id_petra, "Cours de cuisine jordanienne au Petra Kitchen", "Apprenez à préparer des plats traditionnels comme le mansaf ou le maqluba, suivi d'un dîner convivial.", '3h', 'À pied', '17:00:00', 64.00),
(@id_petra, "Randonnée au Haut Lieu du Sacrifice", "Ascension vers un ancien site cérémoniel offrant une vue imprenable sur Pétra et ses environs", '2h', 'À pied', '10:00:00', 25.00),
(@id_petra, "Visite du château de Shobak", "Explorez une forteresse croisée du XIIe siècle perchée sur une colline, offrant un aperçu de l'histoire médiévale de la région.", '5h', 'Car', '08:00:00', 191.00),
(@id_petra, "Excursion au Wadi Rum", "Découvrez le désert du Wadi Rum en 4x4, célèbre pour ses paysages lunaires et ses formations rocheuses spectaculaires.", '10h', 'Car', '09:00:00', 180.00);


-- Colisée
INSERT INTO activites(id_voyage, a_nom, a_description, a_duree, mode_transport, a_heure_depart, a_prix) VALUES
(@id_colisee, "Visite du Colisée", "Plongez dans l'histoire en explorant l'amphithéâtre emblématique de Rome, autrefois le théâtre de combats de gladiateurs et de spectacles publics.", '2h', 'À pied', '08:00:00', 16.00),
(@id_colisee, "Découverte du Vatican", "Explorez les trésors artistiques et spirituels du Vatican, y compris la Chapelle Sixtine et la majestueuse Basilique Saint-Pierre.", '3h30', 'Car', '13:30:00', 18.00),
(@id_colisee, "Balade au Panthéon", "Admirez l'architecture impressionnante du Panthéon, un temple antique dédié à toutes les divinités, célèbre pour sa coupole et son oculus.", '1h', 'À pied', '10:00:00', 0.00),
(@id_colisee, "Cours de cuisine italienne", "Plongez dans l'art culinaire italien en apprenant à préparer des pâtes fraiches et un tiramisu traditionnel aux côtés d'un chef local.", '3h', 'À pied', '3h', '14:00:00', 66.00),
(@id_colisee, "Détente à la plage de Santa Severa", "Évadez-vous du tumulte urbain pour une journée de détente sur la plage de Santa Severa, réputée pour son sable fin, ses eaux claires et son château médiéval en toile de fond.", '11h', 'Car', '08:30:00', 10.00),
(@id_colisee, "Promenade à la Villa Borghèse", "Profitez d'une promenade dans ce vaste parc paysager, abritant des musées, des jardins et des vues panoramiques sur Rome", '2h30', 'Car', '09:30:00', 15.00);


-- Machu Picchu
INSERT INTO activites(id_voyage, a_nom, a_description, a_duree, mode_transport, a_heure_depart, a_prix) VALUES
(@id_machu_picchu, "Ascension du Huayna Picchu", "Grimpez au sommet emblématique qui surplombe la cité inca pour une vue panoramique spectaculaire.", '2h', 'À pied', '09:00:00', 49.00),
(@id_machu_picchu, "Randonnée jusqu'à la porte du Soleil", "Suivez les traces des Incas jusqu'à cette anciene porte d'entrée offrant une vue imprenable sur le sanctuaire.", '2h30', 'À pied', 0.00),
(@id_machu_picchu, "Détente aux bains d'Aguas Calientes", "Profitez d'une relaxation bien méritée dans ces sources chaudes naturelles après une journée d'exploration.", '1h30', 'À pied','18:00:00', 5.00),
(@id_machu_picchu, "Visite du Temple du Soleil", "Admirez l'architecture impressionnante de ce temple dédié au dieu Soleil, témoin de l'ingéniosité inca.", '30min', 'À pied', '10:00:00', 7.00),
(@id_machu_picchu, "Jardins de Mandor", "Explorez une végétation luxuriante et découvrez une cascade cachée dans ce jardin botanique paisible.", '3h', 'À pied', '14:00:00', 2.50),
(@id_machu_picchu, "Jardin Wasi Pillpi", "Observez plus de 500 espèces de papillons dans ce centre de conservation dédié à ces insectes colorés.", '1h', 'À pied', '15:00:00', 2.50),
(@id_machu_picchu, "Excursion en VTT dans la Vallée Sacrée", "Explorez les paysages spectaculaires de la Vallée Sacrée à vélo, en passant par des villages traditionnels et des sites archéologiques", '5h', 'Vélo', '13:30:00', 36.00),
(@id_machu_picchu, "Ascension du Putucusi", "Pour les aventuriers, cette montée raide offre une vue alternative sur le Machu Picchu, loin des foules", '4h', 'À pied', '08:00:00', 0.00);







-- Taj Mahal


-- Grande Muraille de Chine

