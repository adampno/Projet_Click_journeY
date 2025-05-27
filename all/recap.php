<?php
session_start();
require_once "database/database.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: seconnecter.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$reservation_id = $_SESSION['last_reservation_id'] ?? null;

if (!$reservation_id) {
    echo "Erreur : Réservation introuvable.";
    exit;
}


// Récupère les infos de la réservation
$stmt = $pdo->prepare("
    SELECT r.*, v.titre AS voyage_titre, v.duree, v.pays, h.h_nom 
    FROM reservations r
    JOIN voyages v ON r.voyage_id = v.id_voyage
    JOIN hebergements h ON r.hebergement_id = h.id_hebergement
    WHERE r.id_reservation = :id
");
$stmt->execute(['id' => $reservation_id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    echo "Erreur : Détails de la réservation introuvables.";
    exit;
}


// Récupère le voyage de la réservation
$voyage_id = $reservation['voyage_id'];
$stmt = $pdo->prepare("SELECT * FROM voyages WHERE id_voyage = :id");
$stmt->execute(['id' => $voyage_id]);
$voyage = $stmt->fetch();


// Récupère les activités
$stmt = $pdo->prepare("
    SELECT a.a_nom, ra.date_activite, ra.nb_participants, ra.prix_total_activite
    FROM reservation_activites ra
    JOIN activites a ON a.id_activite = ra.id_activite
    WHERE ra.id_reservation = :id
");
$stmt->execute(['id' => $reservation_id]);
$activites = $stmt->fetchAll();

// Récupère les passagers
$stmt = $pdo->prepare("SELECT * FROM passagers WHERE reservation_id = :id");
$stmt->execute(['id' => $reservation_id]);
$passagers = $stmt->fetchAll();
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


</header>
    <div class="recap-container">
        <h1>Récapitulatif de votre commande</h1>

        <section class="recap-section">
            <h2>Voyage vers <?= htmlspecialchars($reservation['voyage_titre']) ?> (<?= htmlspecialchars($reservation['pays']) ?>)</h2>
            <p><strong>Durée :</strong> <?= $reservation['duree'] ?> jours</p>
            <p><strong>Date de départ :</strong> <?= $reservation['date_debut'] ?></p>
            <p><strong>Date de retour :</strong> <?= $reservation['date_fin'] ?></p>
            <p><strong>Hébergement sélectionné :</strong> <?= htmlspecialchars($reservation['h_nom']) ?></p>
        </section>

        <?php if ($activites): ?>
        <section class="recap-section">
            <h2>Activités sélectionnées</h2>
            <ul>
                <?php foreach ($activites as $act): ?>
                    <li>
                        <?= htmlspecialchars($act['a_nom']) ?> (<?= $act['nb_participants'] ?> participant(s), le <?= $act['date_activite'] ?>) - <?= number_format($act['prix_total_activite'], 2, ',', ' ') ?> €
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <?php endif; ?>

        <section class="recap-section">
            <h2>Passagers</h2>
            <ul>
                <?php foreach ($passagers as $p): ?>
                    <li>
                        <?= ucfirst($p['type_passager']) ?> : <?= htmlspecialchars($p['prenom']) . ' ' . htmlspecialchars($p['nom']) ?>,
                        né(e) le <?= $p['date_naissance'] ?>,
                        Nationalité : <?= htmlspecialchars($p['nationalite']) ?>,
                        Passeport : <?= $p['passeport'] ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="recap-section">
            <h2>Montant total</h2>
            <p><strong><?= number_format($reservation['montant_total'], 2, ',', ' ') ?> €</strong></p>
        </section>

      
    </div>
</main>


<div class="btn-wrapper">
<a href="paiement.php?montant=<?= urlencode($reservation['montant_total']) ?>&description=<?= urlencode('Réservation vers ' . $reservation['voyage_titre']) ?>" class="btn-payer"> 
    Payer
</a>
                </div>

<footer>
    <p>&copy; 2025 Wander7. Tous droits réservés.</p>
</footer>
</body>
</html>

