<?php
session_start();

// Connexion à la base de données
require_once "database/database.php";

// Activation des erreurs PHP pour debug
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Récupération de l'ID du voyage depuis l'URL
$id = $_GET['voyage'] ?? null;
if ($id === null) {
    echo "❌ ID de voyage non fourni.";
    exit;
}
$id = (int) $id;


// Vérifier que les données de session existent
if(!isset($_SESSION['reservation_temp']) || !isset($_SESSION['user'])){
  header("Location: options.php?voyage=" . $id);
  exit;
}


// Récupération de l'utilisateur connecté
$user_id = $_SESSION['user']['id'] ?? null;


$reservation = $_SESSION['reservation_temp'];
$nb_adultes = (int)$reservation['nb_adultes'];
$nb_enfants = (int)$reservation['nb_enfants'];
$date_depart = $reservation['date_depart'];
$total_passagers = $nb_adultes + $nb_enfants;


// Calcul de la date de retour
$stmt_duree = $pdo->prepare("SELECT duree FROM voyages WHERE id_voyage = :id");
$stmt_duree->execute(['id' => $id]);
$duree = (int)$stmt_duree->fetchColumn();
$date_retour = (new DateTime($date_depart))->modify("+".($duree - 1)." days")->format('Y-m-d');

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

  <h1>Informations passagers</h1>
  <form action="recap.php" method="POST">
    <input type="hidden" name="voyage_id" value="<?= htmlspecialchars($id) ?>">
    <input type="hidden" name="date_depart" value="<?= htmlspecialchars($date_depart) ?>">
    <input type="hidden" name="date_retour" value="<?= htmlspecialchars($date_retour) ?>">
    <input type="hidden" name="nb_adultes" value="<?= $nb_adultes ?>">
    <input type="hidden" name="nb_enfants" value="<?= $nb_enfants ?>">

    <?php for ($i = 1; $i <= $nb_adultes; $i++): ?>
      <fieldset>
        <legend>Adulte <?= $i ?></legend>
        <label>Nom : <input type="text" name="noms_passagers[]" required></label>
        <label>Prénom : <input type="text" name="prenoms_passagers[]" required></label>
        <label>Date de naissance : <input type="date" name="naissances_passagers[]" required></label>
        <label>Nationalité :
          <select name="nationalites_passagers[]" required>
  <option value="">-- Sélectionner --</option>
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
    </label>
    <label>Passeport : <input type="text" name="passeports_passagers[]" required pattern="[A-Z]{2}[0-9]{6}" placeholder="AB123456"></label>
    <input type="hidden" name="type_passagers[]" value="adulte">
    </fieldset>
    <?php endfor; ?>


    <?php for ($j = 1; $j <= $nb_enfants; $j++): ?>
      <fieldset>
        <legend>Enfant <?= $j ?></legend>
        <label>Nom : <input type="text" name="noms_passagers[]" required></label>
        <label>Prénom : <input type="text" name="prénoms_passagers[]" required></label>
        <label>Date de naissance : <input type="date" name="naissances_passagers[]" required></label>
        <label>Nationalité :
          <select name="nationalites_passagers[]" required>
  <option value="">-- Sélectionner --</option>
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
    </label>
    <label>Passeport : <input type="text" name="passeports_passagers[]" required pattern="[A-Z]{2}[0-9]{6}" placeholder="AB123456"></label>
    <input type="hidden" name="type_passagers[]" value="enfant">
    </fieldset>
    <?php endfor; ?>

<button type="submit">Confirmer la réservation</button>
    </form>

    </main>

<footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
    </footer>

    </body>
</html>