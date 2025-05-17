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
$stmt_vols_aller->execute();
$vol_aller = $stmt_vols_aller->fetch();

$stmt_vols_retour = $pdo->prepare("SELECT * FROM vols WHERE id_voyage = :id AND type_vol = 'retour'");
$stmt_vols_retour->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_vols_retour->execute();
$vol_retour = $stmt_vols_retour->fetch();



// 🔍 Récupération des hébergements + caractéristiques associées
$stmt_hotels = $pdo->prepare("SELECT h.*, c.* FROM hebergements h LEFT JOIN hebergement_caracteristiques c ON h.id_hebergement = c.id_hebergement AND h.id_voyage = c.id_voyage WHERE h.id_voyage = :id");
$stmt_hotels->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_hotels->execute();
$hebergements = $stmt_hotels->fetchAll();




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
          <h3><?= strtolower($hebergement['h_nom']) ?></h3>
          <div class="hotel-stars"> <?= str_repeat('★', $hebergement['etoiles'])?>  </div>
          <div class="hotel-location"> <?= htmlspecialchars($hebergement['h_localisation'])?> </div>
</div>
        <img src="assets/<?= strtolower($hebergement['h_nom']) ?>.png" alt="<?= strtolower($hebergement['h_nom']) ?>" class="hotel-image-side">
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

    <div class="activity-option horizontal">
        <input type="checkbox" id="activity1" name="activity1" class="activity-checkbox" onchange="toggleDateInput(this, 'activity1-day')">
        
            <div class="activity-content">

                <div class="activity-image-container">
                    
                    <img src="all/assets/chichen_itza/eglise.jpg" alt="Église Chichen Itza" class="activity-image-side">
                    <p class="photo-credit">
                        Crédit photo : <a href="https://commons.wikimedia.org/wiki/File:Church_at_Piste,_Yucat%C3%A1n,_Mexico.jpg" target="_blank">Wikimedia Commons</a>
                    </p>
                </div>

                <div class="activity-details">
                  <div class="activity-title">
                    <label for="activity1">
                      <h3>Visite guidée de l'Église de Pisté</h3>
</label>
</div>
                    <ul>
                        <li>Durée : 1h30</li>
                        <li>Mode de transport : À pied</li>
                        <li>Départ : Réception de l'hôtel à 9h30</li>
                        <li>Prix : 10€ par personne (gratuit pour les -3 ans)</li>
                    </ul>

                    <div class="activity-date">
                        <label for="activity1-day">Sélectionnez un jour :</label>
                        <input type="date" id="activity1-day" name="activity1-day" disabled>
                    </div>
                </div>
            </div>
</div>

<div class="activity-option horizontal">
        <input type="checkbox" id="activity2" name="activity2" class="activity-checkbox" onchange="toggleDateInput(this, 'activity2-day')">
        
            <div class="activity-content">

                <div class="activity-image-container">
                    
                    <img src="all/assets/chichen_itza/visite.jpeg" alt="Visite Chichen Itza" class="activity-image-side">
                </div>

                <div class="activity-details">
                  <div class="activity-title">
                    <label for="activity2">
                      <h3>Visite guidée de Chichén Itza</h3>
</label>
</div>
                    <ul>
                        <li>Durée : 3h</li>
                        <li>Mode de transport : À pied</li>
                        <li>Départ : Réception de l'hôtel à 8h30</li>
                        <li>Prix : 45€ par personne</li>
                    </ul>

                    <div class="activity-date">
                        <label for="activity2-day">Sélectionnez un jour :</label>
                        <input type="date" id="activity2-day" name="activity2-day" disabled>
                    </div>
                </div>
            </div>
</div>


<div class="activity-option horizontal">
        <input type="checkbox" id="activity3" name="activity3" class="activity-checkbox" onchange="toggleDateInput(this, 'activity1-day')">
        
            <div class="activity-content">

                <div class="activity-image-container">
                    
                    <img src="all/assets/chichen_itza/ikkil.jpeg" alt="Chichen Itza Ik Kil" class="activity-image-side">
                </div>

                <div class="activity-details">
                  <div class="activity-title">
                    <label for="activity3">
                      <h3>Visite guidée du Cenote Ik Kil</h3>
</label>
</div>
                    <ul>
                        <li>Durée : 2h</li>
                        <li>Mode de transport : Bus</li>
                        <li>Départ : Réception de l'hôtel à 9h30</li>
                        <li>Prix : 15€ par personne</li>
                    </ul>

                    <div class="activity-date">
                        <label for="activity3-day">Sélectionnez un jour :</label>
                        <input type="date" id="activity3-day" name="activity3-day" disabled>
                    </div>
                </div>
            </div>
</div>


<div class="activity-option horizontal">
        <input type="checkbox" id="activity4" name="activity4" class="activity-checkbox" onchange="toggleDateInput(this, 'activity1-day')">
        
            <div class="activity-content">

                <div class="activity-image-container">
                    
                    <img src="all/assets/chichen_itza/ekbalam.jpeg" alt="Chichen Itza Ek Balam" class="activity-image-side">
                </div>

                <div class="activity-details">
                  <div class="activity-title">
                    <label for="activity4">
                      <h3>Visite guidée du site archéologique d'Ek Balam</h3>
</label>
</div>
                    <ul>
                        <li>Durée : 3h</li>
                        <li>Mode de transport : Bus</li>
                        <li>Départ : Réception de l'hôtel à 8h30</li>
                        <li>Prix : 45€ par personne </li>
                    </ul>

                    <div class="activity-date">
                        <label for="activity4-day">Sélectionnez un jour :</label>
                        <input type="date" id="activity4-day" name="activity4-day" disabled>
                    </div>
                </div>
            </div>
</div>

<div class="activity-option horizontal">
        <input type="checkbox" id="activity5" name="activity5" class="activity-checkbox" onchange="toggleDateInput(this, 'activity5-day')">
        
            <div class="activity-content">

                <div class="activity-image-container">
                    
                    <img src="all/assets/chichen_itza/xcanche.jpeg" alt="Chichen Itza X'Canché" class="activity-image-side">
                </div>

                <div class="activity-details">
                  <div class="activity-title">
                    <label for="activity5">
                      <h3>Visite guidée du Cenote X'Canché</h3>
</label>
</div>
                    <ul>
                        <li>Durée : 2h30</li>
                        <li>Mode de transport : Bus</li>
                        <li>Départ : Réception de l'hôtel à 9h</li>
                        <li>Prix : 25€ par personne</li>
                    </ul>

                    <div class="activity-date">
                        <label for="activity5-day">Sélectionnez un jour :</label>
                        <input type="date" id="activity5-day" name="activity5-day" disabled>
                    </div>
                </div>
            </div>
</div>


<div class="activity-option horizontal">
        <input type="checkbox" id="activity6" name="activity6" class="activity-checkbox" onchange="toggleDateInput(this, 'activity1-day')">
        
            <div class="activity-content">

                <div class="activity-image-container">
                    
                    <img src="all/assets/chichen_itza/dzitnup.jpeg" alt="Chichen Itza Dzitnup" class="activity-image-side">
                </div>

                <div class="activity-details">
                  <div class="activity-title">
                    <label for="activity6">
                      <h3>Visite guidée des Cénotes Dzitnup</h3>
</label>
</div>
                    <ul>
                        <li>Durée : 3h</li>
                        <li>Mode de transport : Bus</li>
                        <li>Départ : Réception de l'hôtel à 13h30</li>
                        <li>Prix : 25€ par personne</li>
                    </ul>

                    <div class="activity-date">
                        <label for="activity6-day">Sélectionnez un jour :</label>
                        <input type="date" id="activity6-day" name="activity6-day" disabled>
                    </div>
                </div>
            </div>
</div>



</section>




        <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
    </footer>

    </body>
</html>
