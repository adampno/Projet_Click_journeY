<?php
session_start();

// Activation des erreurs PHP pour debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
require_once "database/database.php";

// Récupération de l'ID du voyage depuis l'URL
$id = $_GET['voyage'] ?? null;
if ($id === null) {
    echo "❌ ID de voyage non fourni.";
    exit;
}
$id = (int) $id;

// 🔍 Récupération des informations du voyage
$stmt_voyage = $pdo->prepare("SELECT v.*, h.h_localisation AS pays FROM voyages v LEFT JOIN hebergements h ON v.id_voyage = h.id_voyage WHERE v.id_voyage =:id LIMIT 1");
$stmt_voyage->bindParam(':id', $id, PDO::PARAM_INT);

if (!$stmt_voyage->execute()) {
    print_r($stmt_voyage->errorInfo());
    exit;
}
$voyage = $stmt_voyage->fetch();

if (!$voyage) {
    echo "❌ Voyage non trouvé.";
    exit;
}


// 🔍 Récupération des vols (aller et retour séparemment)
$stmt_vols_aller = $pdo->prepare("SELECT * FROM vols WHERE id_voyage = :id AND type_vol = 'aller'");
$stmt_vols_aller->bindParam(':id', $id, PDO::PARAM_INT);
if (!$stmt_vols_aller->execute()){
  print_r($stmt_vols_aller->errorInfo());
  exit;
}
$vol_aller = $stmt_vols_aller->fetch();

$stmt_vols_retour = $pdo->prepare("SELECT * FROM vols WHERE id_voyage = :id AND type_vol = 'retour'");
$stmt_vols_retour->bindParam(':id', $id, PDO::PARAM_INT);
if (!$stmt_vols_retour->execute()){
  print_r($stmt_vols_retour->errorInfo());
  exit;
}
$vol_retour = $stmt_vols_retour->fetch();



// Récupération de l'utilisateur connecté
$user_id = $_SESSION['user']['id'] ?? null;


// Récupération de la région de l'utilisateur
$stmt_region = $pdo->prepare("SELECT region FROM utilisateurs WHERE id = :utilisateurs_id");
$stmt_region->bindParam(':utilisateurs_id', $user_id, PDO::PARAM_INT);
if (!$stmt_region->execute()){
  print_r($stmt_region->errorInfo());
  exit;
}
$user_region = $stmt_region->fetchColumn();


// Récupération de l'aéroport associé à la région de l'utilisateur
$stmt_aeroport = $pdo->prepare("SELECT nom FROM aeroports WHERE region = :region LIMIT 1");
$stmt_aeroport->bindParam(':region', $user_region);
if (!$stmt_aeroport->execute()){
  print_r($stmt_aeroport->errorInfo());
  exit;
}
$aeroport_region = $stmt_aeroport->fetchColumn();



// Remplacement des données dans les vols
if($aeroport_region){
  $vol_aller['aeroport_depart'] = $aeroport_region;
  $vol_retour['aeroport_arrivee'] = $aeroport_region;
}



// 🔍 Récupération des hébergements + caractéristiques associées
$stmt_hotels = $pdo->prepare("SELECT h.*, c.* FROM hebergements h LEFT JOIN hebergement_caracteristiques c ON h.id_hebergement = c.id_hebergement AND h.id_voyage = c.id_voyage WHERE h.id_voyage = :id");
$stmt_hotels->bindParam(':id', $id, PDO::PARAM_INT);

if (!$stmt_hotels->execute()){
  print_r($stmt_hotels->errorInfo());
  exit;
}
$hebergements = $stmt_hotels->fetchAll();



// Récupération des activités du voyage
$stmt_activites = $pdo->prepare("SELECT * FROM activites WHERE id_voyage = :id");
$stmt_activites->bindParam(':id', $id, PDO::PARAM_INT);

if (!$stmt_activites->execute()){
  print_r($stmt_activites->errorInfo());
  exit;
}
$activites = $stmt_activites->fetchAll();



?>



<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style/reservation.css">
        <title><?php echo htmlspecialchars($voyage['titre']); ?> | Wander7</title>
    </head>
    <body>

<header>
      <img class="logo" src="assets/LogoWander7.png" alt="logo">
      <nav>
        <ul class="nav_links">
          <li><a href="index.php">Accueil</a></li>
          <li><a href="aproposdenous.php">À propos de nous</a></li>
          <li><a href="explorer.php">Explorer</a></li>
          <?php if (isset($_SESSION['user'])):?>
          <li><a href="profil.php">Mon profil</a></li>
          <li><a href="deconnexion.php">Se déconnecter</a></li>
          <?php else: ?>
            <li><a href="seconnecter.php">Se connecter</a></li>
          <?php endif; ?>
          <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
          <li><a href="admin.php">Admin</a></li>
          <?php endif; ?>
        </ul>
      </nav>
          </header>

<div class="hero-wrapper">
 <img src="assets/<?= strtolower(str_replace(' ', '_', $voyage['titre'])) ?>_hero.jpg" alt="<?= htmlspecialchars($voyage['titre']) ?>" class="hero-image">

 <div class="hero-text">
  <h1><?= htmlspecialchars($voyage['titre']) ?></h1>
  <p><?= htmlspecialchars($voyage['pays']) ?></p>
          </div>

 <div class="hero-overlay" id="heroOverlay"></div>
</div>

<main class="page-content">



<div class="reservation-container">
  <h1>Nombre de passagers</h1>
  <form method="POST" action="traitement_reservation.php" id="form-reservation">
    <input type="hidden" name="voyage_id" value="<?= htmlspecialchars($id) ?>">

    <div class="form-group">
      <label for="passengers">Nombre d'adultes :</label>
      <input type="number" id="passenger" name="passengers" min="1" max="10" value="1" required>
    </div>

    <div class="form-group">
      <label for="children">Nombre d'enfants :</label>
      <input type="number" id="children" name="children" min="0" max="10" value="0" required>
    </div>

    <div class="children-ages" id="childrenAgesContainer">
      <!-- Champs d'âge ajoutés dynamiquement ici -->
    </div>

<div class="passengers-info" id="passengerInfoContainer">
    <!-- Formulaires ajoutés dynamiquement ici -->
          </div>

  </form>
</div>



<div class="reservation-button-container" id="reservationButton">
  <a href="recap.php?voyage=<?= $id ?>" class="reservation-button">
    Confirmer la réservation
          </a>
    </div>

</main>


<script>
  const childrenInput = document.getElementById('children');
  const adultsInput = document.getElementById('passenger');
  const childContainer = document.getElementById('childrenAgesContainer');
  const passengerContainer = document.getElementById('passengerInfoContainer');

  // === Fonction pour générer les adultes ===
  function generatePassengerForms(count) {
    passengerContainer.innerHTML = ''; // reset

    for (let i = 1; i <= count; i++) {
      const div = document.createElement('div');
      div.classList.add('passenger-info');
      div.innerHTML = `
        <h4>Passager ${i}</h4>
        <div class="row">
          <div class="form-group">
            <label for="nom_passager_${i}">Nom :</label>
            <input type="text" name="noms_passagers[]" id="nom_passager_${i}" required>
          </div>
          <div class="form-group">
            <label for="prenom_passager_${i}">Prénom :</label>
            <input type="text" name="prenoms_passagers[]" id="prenom_passager_${i}" required>
          </div>
            <div class="form-group">
            <label for="dob_passager_${i}">Date de naissance :</label>
            <input type="date" name="naissances_passagers[]" id="dob_passager_${i}" required>
            </div>
            <div class="form-group">
            <label for="nationalite_passager_${i}">Nationalité :</label>

         <select name="nationalites_passagers[]" required>
  <option value="">-- Sélectionner la nationalité --</option>
  <option value="Algérienne">Algérienne</option>
  <option value="Allemande">Allemande</option>
  <option value="Américaine">Américaine</option>
  <option value="Australienne">Australienne</option>
  <option value="Belge">Belge</option>
  <option value="Brésilienne">Brésilienne</option>
  <option value="Britannique">Britannique</option>
  <option value="Canadienne">Canadienne</option>
  <option value="Chinoise">Chinoise</option>
  <option value="Espagnole">Espagnole</option>
  <option value="Française">Française</option>
  <option value="Indienne">Indienne</option>
  <option value="Italienne">Italienne</option>
  <option value="Japonaise">Japonaise</option>
  <option value="Marocaine">Marocaine</option>
  <option value="Mexicaine">Mexicaine</option>
  <option value="Néerlandaise">Néerlandaise</option>
  <option value="Portugaise">Portugaise</option>
  <option value="Suisse">Suisse</option>
  <option value="Tunisienne">Tunisienne</option>
  <option value="Autre">Autre</option>
</select>

            </div>
            <div class="form-group">
            <label for="passeport_passager_${i}">Numéro de passeport :</label>
            <input type="text" name="passeports_passagers[]" id="passeport_passager_${i}" pattern="[A-Z]{2}[0-9]{6}" placeholder="Ex : AB123456"required>
            </div>
        </div>
      `;
      passengerContainer.prepend(div);
    }
  }

  // === Fonction pour générer les enfants ===
  function generateChildForms(count) {
    childContainer.innerHTML = ''; // reset

    for (let i = 1; i <= count; i++) {
      const div = document.createElement('div');
      div.classList.add('child-info');
      div.innerHTML = `
        <h4>Enfant ${i}</h4>
        <div class="row">
          <div class="form-group">
            <label for="nom_enfant_${i}">Nom :</label>
            <input type="text" name="noms_enfants[]" id="nom_enfant_${i}" required>
          </div>
          <div class="form-group">
            <label for="prenom_enfant_${i}">Prénom :</label>
            <input type="text" name="prenoms_enfants[]" id="prenom_enfant_${i}" required>
          </div>
          <div class="form-group">
            <label for="age_enfant_${i}">Âge :</label>
            <input type="number" name="ages_enfants[]" id="age_enfant_${i}" min="0" max="17" required>
          </div>
          <div class="form-group">
            <label for="dob_enfants_${i}">Date de naissance :</label>
            <input type="date" name="naissances_enfants[]" id="dob_enfants_${i}" required>
            </div>
            <div class="form-group">
            <label for="nationalite_enfant_${i}">Nationalité :</label>
<select name="nationalites_enfants[]" required>
  <option value="">-- Sélectionner la nationalité --</option>
  <option value="Algérienne">Algérienne</option>
  <option value="Allemande">Allemande</option>
  <option value="Américaine">Américaine</option>
  <option value="Australienne">Australienne</option>
  <option value="Belge">Belge</option>
  <option value="Brésilienne">Brésilienne</option>
  <option value="Britannique">Britannique</option>
  <option value="Canadienne">Canadienne</option>
  <option value="Chinoise">Chinoise</option>
  <option value="Espagnole">Espagnole</option>
  <option value="Française">Française</option>
  <option value="Indienne">Indienne</option>
  <option value="Italienne">Italienne</option>
  <option value="Japonaise">Japonaise</option>
  <option value="Marocaine">Marocaine</option>
  <option value="Mexicaine">Mexicaine</option>
  <option value="Néerlandaise">Néerlandaise</option>
  <option value="Portugaise">Portugaise</option>
  <option value="Suisse">Suisse</option>
  <option value="Tunisienne">Tunisienne</option>
  <option value="Autre">Autre</option>
</select>
            </div>
            <div class="form-group">
            <label for="passeport_enfant_${i}">Numéro de passeport :</label>
            <input type="text" name="passeports_enfants[]" id="passeport_enfant_${i}" pattern="[A-Z]{2}[0-9]{6}" placeholder="Ex : AB123456" required>
            </div>
        </div>
        <p class="age-info">Âge au moment du voyage</p>
        </div>
      `;
      childContainer.prepend(div);
    }
  }

  // === Listener enfants ===
  childrenInput.addEventListener('input', function () {
    const count = parseInt(this.value) || 0;
    generateChildForms(count); // Appelle la fonction enfants
  });

  // === Listener adultes ===
  adultsInput.addEventListener('input', function () {
    const count = parseInt(this.value) || 0;
    generatePassengerForms(count); // Appelle la fonction adultes
  });

  // === Générer automatiquement passager 1 au chargement ===
  document.addEventListener('DOMContentLoaded', function(){
    const count = parseInt(adultsInput.value) || 1;
    generatePassengerForms(count);
  });



  // === Affichage du bouton lorsqu'on atteint le bas de la page ===
  const reservationBtn = document.getElementById('reservationButton');

  window.addEventListener('scroll', function () {
    const scrollTop = window.scrollY || window.pageYOffset;
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;

    if (scrollTop + windowHeight >= documentHeight - 100) {
      reservationBtn.classList.add('visible');
    } else {
      reservationBtn.classList.remove('visible');
    }
  });


</script>



</body>
</html>