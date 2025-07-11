<?php
session_start();
unset($_SESSION['reservation_temp']);

// Activation des erreurs PHP pour debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
require_once "database/database.php";

// Récupération de l'ID du voyage depuis l'URL
$id = $_GET['id'] ?? null;
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
        <link id="theme-style" rel="stylesheet">
        <script src="scripts/darkmode.js" defer></script>
        <title><?php echo htmlspecialchars($voyage['titre']); ?> | Wander7</title>
    </head>
    <body>

<header>
      <img class="logo" src="assets/LogoWander7.png" alt="logo">
      <nav>
        <ul class="nav_links">
          <button id="theme-selector" style="position: fixed; top: 20px; right: 20px; z-index: 1000; font-size: 20px; background: none; border: none; cursor: pointer;">🌙</button>
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


<div class="voyage-description-box">
  <p><?= htmlspecialchars($voyage['v_description'])?></p>
          </div>





<section class="hotel-selection">
<h2>Hôtels proposés</h2>

<?php foreach ($hebergements as $hebergement): ?>
<div class="hotel-option horizontal">
  <div class="hotel-content">
    <div class="hotel-text">
    <div class="hotel-heading">
          <h3><?= ucwords(htmlspecialchars($hebergement['h_nom'])) ?></h3>
          <div class="hotel-stars"> <?= str_repeat('★', $hebergement['etoiles'])?>  </div>
          <div class="hotel-location"> <?= htmlspecialchars($hebergement['h_localisation'])?> </div>
</div>
</div>
      <div class="hotel-image-container">
        <img src="assets/<?= str_replace(' ', '_', $hebergement['h_nom']) ?>.png" alt="<?= htmlspecialchars($hebergement['h_nom']) ?>" class="hotel-image-side">
</div>
</div>
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
                    <label for="activity<?= $index?>">
                      <h3><?= htmlspecialchars($activite['a_nom'])?></h3>
</label>
</div>
<p class="activity-description">
  <?=htmlspecialchars($activite['a_description'])?>
    </p>
                    
                </div>
            </div>
</div>
<?php endforeach; ?>
</section>


<div class="option-button-container">
  <a href="options.php?voyage=<?= $voyage['id_voyage'] ?>" class="option-button">Choisir les options</a>
    </div>




    </main>

        <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
    </footer>
   

    <script>
document.addEventListener("DOMContentLoaded", function () {
  const heroText = document.querySelector(".hero-text");
  const descriptionBox = document.querySelector(".voyage-description-box");

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

  if (descriptionBox) {
    observer.observe(descriptionBox);
  }
});
</script>
 


    </body>
</html>
