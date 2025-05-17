<?php
// Activation des erreurs PHP pour debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
require_once "config.php";

// Récupération de l'ID du voyage depuis l'URL
$id = $_GET['voyage'] ?? null;
if ($id === null) {
    echo "❌ ID de voyage non fourni.";
    exit;
}
$id = (int) $id;

// 🔍 Récupération des informations du voyage
$stmt_voyage = $pdo->prepare("SELECT * FROM voyages WHERE id_voyage = :id LIMIT 1");
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
        <link rel="stylesheet" href="style/voyage.css">
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
          <li><a href="profil.php">Mon profil</a></li>
          <li><a href="admin.php">Admin</a></li>
          <li><a href="seconnecter.php">Se connecter</a></li>
        </ul>
      </nav>
    </header>



<div class="sidebar-summary" id="sidebar-summary">
<h3>Récapitulatif</h3>
<p>Adultes : <span id="sum-adults">1</span></p>
<p>Enfants : <span id="sum-children">0</span></p>
<p>Chambres nécessaires : <span id="sum-rooms">1</span></p>
<p>Prix total : <span id="price-summary">0€</span></p>
</div>



    <section class="passenger-form">
    <h2>Participants au voyage</h2>
    <div class="form-grid">
    <form id="travelersForm">
        <label for="adults">Nombre d'adultes :</label>
        <input type="number" id="adults" name="adults" min="1" value="1" required>
        <label for="children">Nombre d'enfants :</label>
        <input type="number" id="children" name="children" min="0" value="0" required>
        <div id="childrenAges"></div>
        <label for="departure-date">Date de départ :</label>
        <input type="date" id="departure-date" name="departure_date" required>
    <p>Durée : 6 jours (fixe)</p>
</form>
  </div>
</section>


  <section class="flight-info">
  <div class="flight-wrapper">
    <h2>Vol aller</h2>
    <div class="flight-box">
      <div class="flight-row">
        <span class="airport">🛫 <?= htmlspecialchars($vol_aller['aeroport_depart'])?></span>
        <div class="flight-line">
          <hr><span class="plane">✈️</span><hr>
</div>
<span class="airport"> <?= htmlspecialchars($vol_aller['aeroport_arrivee'])?>🛬</span>
</div>
<div class="flight-details">
  <span>Départ : <?= htmlspecialchars($vol_aller['heure_depart'])?></span>
  <span>Durée : <?= htmlspecialchars($vol_aller['duree'])?></span>
  <span>Arrivée : <?= htmlspecialchars($vol_aller['heure_arrivee'])?></span>
</div>
</div>

<hr class="inner-separator">

<h2>Vol retour</h2>
<div class="flight-box">
  <div class="flight-row">
    <span class="airport">🛫 <?= htmlspecialchars($vol_retour['aeroport_depart'])?></span>
    <div class="flight-line">
      <hr><span class="plane">✈️</span><hr>
</div>
<span class="airport"><?= htmlspecialchars($vol_retour['aeroport_arrivee'])?> 🛬</span>
</div>
<div class="flight-details">
  <span>Départ : <?=htmlspecialchars($vol_retour['heure_depart'])?></span>
  <span>Durée : <?=htmlspecialchars($vol_retour['duree'])?></span>
  <span>Arrivée : <?=htmlspecialchars($vol_retour['heure_arrivee'])?></span>
</div>
</div>
<div class="flight-price">
  <span>Prix Total :</span> <span class="price-amount"><?= htmlspecialchars($vol_aller['prix'] + $vol_retour['prix'])?>€/pers.</span>
</div>
</div>
</section>
    



<section class="hotel-selection">
<h2>Sélectionnez votre hôtel </h2>

<?php foreach ($hebergements as $hebergement): ?>
<div class="hotel-option horizontal">
    <input type="radio" id="hotel-<?= strtolower($hebergement['h_nom']) ?>" name="hotel" value="<?= strtolower($hebergement['h_nom']) ?>">
    <label for="hotel-<?= strtolower($hebergement['h_nom']) ?>">
      <div class="hotel-content">

      <div class="hotel-image-container">
        <div class="hotel-heading">
          <h3><?= ucwords(htmlspecialchars($hebergement['h_nom'])) ?></h3>
          <div class="hotel-stars"> <?= str_repeat('★', $hebergement['etoiles'])?>  </div>
          <div class="hotel-location"> <?= htmlspecialchars($hebergement['h_localisation'])?> </div>
</div>
        <img src="assets/<?= str_replace(' ', '_', $hebergement['h_nom']) ?>.png" alt="<?= htmlspecialchars($hebergement['h_nom']) ?>" class="hotel-image-side">
</div>
<div class="hotel-details">
        <ul>
            <li>Transfert aéroport : <?= $hebergement['transfert']?></li>
            <li>Piscines : <?= $hebergement['nb_piscines']?> </li>
            <li>Jacuzzi : <?= $hebergement['jacuzzi']?></li>
            <li>Spa : <?= $hebergement['spa']?></li>
            <li>Services disponibles : chaises longues et parasols de plage</li>
            <li>Pension : P<?= $hebergement['pension']?></li>
            <li>Wifi gratuit : <?= $hebergement['wifi_gratuit']?></li>
            <li>TV chambres : <?= $hebergement['tv_chambres']?></li>
            <li>Climatisation : <?= $hebergement['climatisation']?></li>
            <li>Sèche-cheveux : <?= $hebergement['seche_cheveux']?></li>
            <li>Balcon privé : <?= $hebergement['balcon_pv']?></li>
            <li>Laverie : <?= $hebergement['laverie']?></li>
            <li>Accessibilité PMR : <?= $hebergement['pmr']?></li>
            <li>Prix par chambre double (1 ou 2 pers.) : <?= $hebergement['h_prix']?> €</li>
</ul>
</div>
</div>
</label>
</div>
<?php endforeach; ?>
</section>



<section class="activity-selection">
    <h2>Activités proposées</h2>

    <?php foreach ($activites as $index => $activite): ?>
    <div class="activity-option horizontal">
        <input type="checkbox" id="activity<?= $index?>" name="activity<?= $index?>" class="activity-checkbox" onchange="toggleDateInput(this, 'activity<?= $index?>-day')">
        
            <div class="activity-content">

                <div class="activity-image-container">
                    
                    <img src="assets/<?= str_replace(' ', '_', strtolower($activite['a_nom']))?>.jpeg" alt="<?= htmlspecialchars($activite['a_nom'])?>" class="activity-image-side">
                </div>
                <div class="activity-details">
                  <div class="activity-title">
                    <label for="activity<?= $index?>">
                      <h3><?= htmlspecialchars($activite['a_nom'])?></h3>
</label>
</div>
<p class="activity-description">
  <?=htmlspecialchars($activite['a_description'])?>
    </p>
                    <ul>
                        <li><?= htmlspecialchars($activite['a_duree'])?></li>
                        <li>Mode de transport : <?= htmlspecialchars($activite['mode_transport'])?></li>
                        <li>Départ : Réception de l'hôtel à <?= htmlspecialchars($activite['a_heure_depart'])?></li>
                        <li>Prix : <?= htmlspecialchars($activite['a_prix'])?>€ par personne </li>
                    </ul>

                    <div class="activity-date">
                        <label for="activity1-day">Sélectionnez un jour :</label>
                        <input type="date" id="activity1-day" name="activity1-day" disabled>
                    </div>
                </div>
            </div>
</div>
<?php endforeach; ?>
</section>




        <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
    </footer>

    </body>
</html>
