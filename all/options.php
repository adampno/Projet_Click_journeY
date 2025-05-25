<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enregistrer_infos'])) {
  $_SESSION['reservation_temp'] = [
      'nb_adultes' => (int)$_POST['nb_adultes'],
      'nb_enfants' => (int)$_POST['nb_enfants'],
      'date_depart' => $_POST['date_depart'],
      'voyage_id' => (int)$_GET['voyage']
  ];
  header("Location: options.php?voyage=" . $_GET['voyage']); 
  exit;
}


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

// Récupération temporaire du nombre de passagers
$total_passagers = ($_SESSION['reservation_temp']['nb_adultes'] ?? 1) + ($_SESSION['reservation_temp']['nb_enfants'] ?? 0);

$nb_passagers = ($_SESSION['reservation_temp']['nb_adultes'] ?? 0 ) + ($_SESSION['reservation_temp']['nb_enfants'] ?? 0);
$nb_chambres = ceil($nb_passagers / 2);

$prix_vols = (float)($vol_aller['prix'] + $vol_retour['prix']);
$prix_total_vols = $prix_vols * $total_passagers;


?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="style/options.css">
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

<form action="options.php?voyage=<?= $voyage['id_voyage']?>" method="POST">
    <section class="passenger-selection">
  <h2>Informations voyage</h2>
  <div class="passenger-fields">
    <div class="passenger-field">
      <label for="adults">Nombre d'adultes :</label>
      <input type="number" id="adults" name="nb_adultes" min="1" value="<?= $_SESSION['reservation_temp']['nb_adultes'] ?? 1?>" required>
    </div>
    <div class="passenger-field">
      <label for="children">Nombre d'enfants :</label>
      <input type="number" id="children" name="nb_enfants" min="0" value="<?= $_SESSION['reservation_temp']['nb_enfants'] ?? 0 ?>">
    </div>
    <div class="passenger-field">
        <label for="date_depart">Date de départ :</label>
        <input type="date" id="date_depart" name="date_depart" required value="<?= $_SESSION['reservation_temp']['date_depart'] ?? ''?>">
          </div>
  </div>
<button type="submit" name="enregistrer_infos" class="btn-tempo">Enregistrer les informations</button>
</section>
          </form>


<section class="flight-info">
  <div class="flight-wrapper">
    <h2>Vol aller</h2>
    <div class="flight-box">
      <div class="flight-row">
        <span class="airport">🛫 Aéroport de <?= htmlspecialchars($vol_aller['aeroport_depart'])?></span>
        <div class="flight-line">
          <hr><span class="plane">✈️</span><hr>
</div>
<span class="airport">Aéroport de <?= htmlspecialchars($vol_aller['aeroport_arrivee'])?>🛬</span>
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
    <span class="airport">🛫 Aéroport de <?= htmlspecialchars($vol_retour['aeroport_depart'])?></span>
    <div class="flight-line">
      <hr><span class="plane">✈️</span><hr>
</div>
<span class="airport">Aéroport de <?= htmlspecialchars($vol_retour['aeroport_arrivee'])?> 🛬</span>
</div>
<div class="flight-details">
  <span>Départ : <?=htmlspecialchars($vol_retour['heure_depart'])?></span>
  <span>Durée : <?=htmlspecialchars($vol_retour['duree'])?></span>
  <span>Arrivée : <?=htmlspecialchars($vol_retour['heure_arrivee'])?></span>
</div>
</div>
<div class="flight-price-box">
  <span>Prix par personne :</span>
  <span><?= htmlspecialchars($vol_aller['prix'] + $vol_retour['prix'])?> €</span>
</div>
<div class="flight-price-emphasized">
<span>Prix total <span class="passenger-info">(pour <?= $total_passagers ?> passager<?= $total_passagers > 1 ? 's' : '' ?>)</span> :
          </span>
  <strong><?= number_format($prix_total_vols, 2, ',', ' ') ?> €</strong>
          </div>
</div>
</section>
    




<section class="hotel-selection">
<h2>Hôtels proposés</h2>

<?php foreach ($hebergements as $hebergement): ?>
<div class="hotel-option horizontal">
    <label class="hotel-radio-label">
        <input type="radio" name="hotel_id" value="<?= $hebergement['id_hebergement'] ?>" class="hotel-radio" required>
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
            <li>Pension : <?= $hebergement['pension']?></li>
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

<?php 
$prix_chambre = (float) $hebergement['h_prix'];
$prix_total_chambres = $prix_chambre * $nb_chambres;
?>

<div class="hotel-price-summary">
  Prix total <span>(pour <?= $nb_chambres ?> chambre<?= $nb_chambres > 1 ? 's' : '' ?> double<?= $nb_chambres > 1 ? 's' : '' ?>)</span> : 
  <strong><?= number_format($prix_total_chambres, 2, ',', ' ') ?> €</strong>
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
            <div class="activity-content">

                <div class="activity-image-container">
                    
                    <img src="assets/<?= str_replace(' ', '_', strtolower($activite['a_nom']))?>.jpeg" alt="<?= htmlspecialchars($activite['a_nom'])?>" class="activity-image-side">
                </div>
                <div class="activity-details">
                  <div class="activity-title">
                    <label class="activity-checkbox-label">
                        <input type="checkbox" name="activities[]" value="<?= $activite['id_activite'] ?>" class="activity-checkbox" data-activity-id="<?=$activite['id_activite']?>">
                        <h3><?= htmlspecialchars($activite['a_nom'])?></h3>
</label>


</div>
<p class="activity-description">
  <?=htmlspecialchars($activite['a_description'])?>
    </p>
                    <ul>
                        <li>Durée : <?= htmlspecialchars($activite['a_duree'])?></li>
                        <li>Mode de transport : <?= htmlspecialchars($activite['mode_transport'])?></li>
                        <li>Départ : Réception de l'hôtel à <?= htmlspecialchars($activite['a_heure_depart'])?></li>
                        <li>Prix : <?= htmlspecialchars($activite['a_prix'])?>€ par personne </li>
                    </ul>

<div class="activity-participants">
  <label for="activity-participants-<?= $activite['id_activite'] ?>">Nombre de participants :</label>
  <input type="number" id="activity-participants-<?= $activite['id_activite']?>" name="activities_participants[<?= $activite['id_activite'] ?>]" min="1" max="<?= $total_passagers ?>" value="1" disabled class="activity-participants-input" data-prix="<?= $activite['a_prix']?>"></div>



                    <div class="activity-date">
    <label for="activity-date-<?= $activite['id_activite'] ?>">Sélectionnez un jour :</label>
    <input type="date" name="activities_date[<?= $activite['id_activite'] ?>]" id="activity-date-<?= $activite['id_activite']?>" disabled class="activity-date-input">
    </div>

<div class="activity-total-price" id="activity-total-price-<?= $activite['id_activite']?>">
  Prix total : 0€
    </div>

                </div>
            </div>
</div>
<?php endforeach; ?>
</section>



  <form action="traitement_reservation.php" method="POST" id="formReservation">
    <input type="hidden" name="action" id="actionInput" value="">

<section class="final-buttons">
    <button type="submit" class="btn-confirmer-reserver" onclick="setActionAndSubmit('payer', event)">
      Confirmer et réserver</button>
<button type="submit" class="btn-retour" onclick="setActionAndSubmit('retour_accueil', event)">
  Confirmer et revenir à l’accueil</button>
</section>
</form>

    </main>

<footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
    </footer>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>

document.addEventListener("DOMContentLoaded", function () {
  const activityCheckboxes = document.querySelectorAll('.activity-checkbox');

  activityCheckboxes.forEach(checkbox => {
    const id = checkbox.dataset.activityId;
    const participantsInput = document.getElementById(`activity-participants-${id}`);
    const totalPriceDisplay = document.getElementById(`activity-total-price-${id}`);
    const dateInput = document.getElementById(`activity-date-${id}`);

    function updateTotalPrice() {
      const unitPrice = parseFloat(participantsInput.dataset.prix || 0);
      const nbParticipants = parseInt(participantsInput.value || 0);
      const total = unitPrice * nbParticipants;

      totalPriceDisplay.textContent = `Prix total : ${total.toFixed(2)} €`;
    }

    checkbox.addEventListener('change', function () {
      const isChecked = this.checked;

      if (participantsInput) {
        participantsInput.disabled = !isChecked;
        participantsInput.value = isChecked ? 1 : '';
        participantsInput.max = totalPassagers;
        updateTotalPrice();
      }

      if (dateInput) {
        dateInput.disabled = !isChecked;
        dateInput.value = isChecked ? dateInput.value : '';
      }

      if (!isChecked) {
        totalPriceDisplay.textContent = "Prix total : 0 €";
      }
    });

    if (participantsInput) {
      participantsInput.addEventListener('input', updateTotalPrice);
    }
  });
});


document.addEventListener("DOMContentLoaded", function () {
  const activityCheckboxes = document.querySelectorAll('.activity-checkbox');

  activityCheckboxes.forEach(checkbox => {
    const id = checkbox.dataset.activityId;
    const participantsInput = document.getElementById(`activity-participants-${id}`);
    const dateInput = document.getElementById(`activity-date-${id}`);

    checkbox.addEventListener('change', function () {
      const isChecked = this.checked;

      if (participantsInput) {
        participantsInput.disabled = !isChecked;
        participantsInput.value = isChecked ? 1 : '';
        participantsInput.max = totalPassagers;
      }

      if (dateInput) {
        dateInput.disabled = !isChecked;
        dateInput.value = isChecked ? dateInput.value : '';
      }
    });
  });
});


  const totalPassagers =<?= $total_passagers ?>;
  const heroText = document.querySelector(".hero-text");
  const flightInfo = document.querySelector(".passenger-selection"); 

  const observer = new IntersectionObserver(
    ([entry]) => {
      if (entry.isIntersecting) {
        heroText.classList.add("fade-out");
      } else {
        heroText.classList.remove("fade-out");
      }
    },
    {
      root: null,
      threshold: 0.1
    }
  );

  if (flightInfo) {
    observer.observe(flightInfo);
  }



document.addEventListener("DOMContentLoaded", function () {
  const dureeVoyage = <?= (int)$voyage['duree'] ?>;
  const dateDepartInput = document.getElementById('date_depart');
  const activityCheckboxes = document.querySelectorAll('.activity-checkbox');

  function updateActivityPickers() {
    const departStr = dateDepartInput.value;
    if (!departStr) return;

    const departDate = new Date(departStr);
    if(isNaN(departDate.getTime())) return;

    const retourDate = new Date(departDate);
    retourDate.setDate(retourDate.getDate() + dureeVoyage - 1);


    activityCheckboxes.forEach(checkbox => {
      const id = checkbox.dataset.activityId;
      const dateInput = document.getElementById(`activity-date-${id}`);

      if (dateInput._flatpickr) {
        dateInput._flatpickr.destroy();
      }

flatpickr(dateInput, {
  minDate: departDate,
  maxDate: retourDate,
  dateFormat: "Y-m-d", 
  disableMobile: true,
  locale: "fr", 
  allowInput: false,
});
dateInput.disabled = !checkbox.checked;

    });
  }

    dateDepartInput.addEventListener('change', updateActivityPickers);

    activityCheckboxes.forEach((checkbox) => {
      checkbox.addEventListener('change', function() {
      updateActivityPickers();
  });
});
  updateActivityPickers();
});

function setActionAndSubmit(actionValue, event) {
    event.preventDefault();
    const form = document.getElementById("formReservation");
    document.getElementById("actionInput").value = actionValue;

    // Nettoyage des anciens inputs dynamiques
    form.querySelectorAll(".dynamic-hidden").forEach(e => e.remove());

    // Vérifie que l'hôtel est sélectionné
    const selectedHotel = document.querySelector("input.hotel-radio:checked");
    if (!selectedHotel) {
        alert("Veuillez sélectionner un hôtel.");
        return;
    }

    // Ajoute l'hôtel sélectionné
    const hiddenHotel = document.createElement("input");
    hiddenHotel.type = "hidden";
    hiddenHotel.name = "hotel_id";
    hiddenHotel.value = selectedHotel.value;
    hiddenHotel.classList.add("dynamic-hidden");
    form.appendChild(hiddenHotel);

    // Activités cochées
    document.querySelectorAll(".activity-checkbox:checked").forEach(cb => {
        const id = cb.value;

        // ID activité
        const actInput = document.createElement("input");
        actInput.type = "hidden";
        actInput.name = "activities[]";
        actInput.value = id;
        actInput.classList.add("dynamic-hidden");
        form.appendChild(actInput);

        // Date activité
        const dateInput = document.getElementById(`activity-date-${id}`);
        if (dateInput && dateInput.value) {
            const dateField = document.createElement("input");
            dateField.type = "hidden";
            dateField.name = `activities_date[${id}]`;
            dateField.value = dateInput.value;
            dateField.classList.add("dynamic-hidden");
            form.appendChild(dateField);
        }

        // Participants
        const partInput = document.getElementById(`activity-participants-${id}`);
        if (partInput && partInput.value) {
            const partField = document.createElement("input");
            partField.type = "hidden";
            partField.name = `activities_participants[${id}]`;
            partField.value = partInput.value;
            partField.classList.add("dynamic-hidden");
            form.appendChild(partField);
        }
    });

    form.submit();
}



</script>




    </body>
    </html>