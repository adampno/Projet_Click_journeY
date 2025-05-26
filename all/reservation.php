<?php
session_start();
require_once "database/database.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: seconnecter.php");
    exit;
}

// Fonction pour calculer l'âge
function calculerAge($dateNaissance) {
    try {
        $anniversaire = new DateTime($dateNaissance);
        $aujourdHui = new DateTime();
        return $anniversaire->diff($aujourdHui)->y;
    } catch (Exception $e) {
        return null;
    }
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['id'];
    $voyage_id = $_POST['voyage_id'];
    $date_depart = $_POST['date_depart'];
    $date_retour = $_POST['date_retour'];
    $nb_adultes = (int)$_POST['nb_adultes'];
    $nb_enfants = (int)$_POST['nb_enfants'];

    $noms = $_POST['noms_passagers'];
    $prenoms = $_POST['prenoms_passagers'];
    $naissances = $_POST['naissances_passagers'];
    $nationalites = $_POST['nationalites_passagers'];
    $passeports = $_POST['passeports_passagers'];
    $types = $_POST['type_passagers'];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO reservations (utilisateur_id, id_voyage, date_depart, date_retour, nb_adultes, nb_enfants, date_reservation)
            VALUES (:user_id, :voyage_id, :date_depart, :date_retour, :nb_adultes, :nb_enfants, NOW())
        ");
        $stmt->execute([
            'user_id' => $user_id,
            'voyage_id' => $voyage_id,
            'date_depart' => $date_depart,
            'date_retour' => $date_retour,
            'nb_adultes' => $nb_adultes,
            'nb_enfants' => $nb_enfants
        ]);
        $reservation_id = $pdo->lastInsertId();

        for ($i = 0; $i < count($noms); $i++) {
            $age = ($types[$i] === 'adulte') ? calculerAge($naissances[$i]) : null;

            $stmt = $pdo->prepare("
                INSERT INTO passagers (
                    reservation_id, type_passager, nom, prenom, date_naissance, nationalite, passeport, age
                ) VALUES (
                    :reservation_id, :type, :nom, :prenom, :naissance, :nationalite, :passeport, :age
                )
            ");
            $stmt->execute([
                'reservation_id' => $reservation_id,
                'type' => $types[$i],
                'nom' => $noms[$i],
                'prenom' => $prenoms[$i],
                'naissance' => $naissances[$i],
                'nationalite' => $nationalites[$i],
                'passeport' => $passeports[$i],
                'age' => $age
            ]);
        }

        $pdo->commit();
        unset($_SESSION['reservation_temp']);
        $_SESSION['last_reservation_id'] = $reservation_id;
        header("Location: recap.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur : " . $e->getMessage();
    }
}

// --- Préparation du formulaire ---
$id = $_GET['voyage'] ?? null;
if (!$id) {
    echo "ID de voyage non fourni.";
    exit;
}
$id = (int)$id;

$stmt = $pdo->prepare("SELECT * FROM voyages WHERE id_voyage = :id");
$stmt->execute(['id' => $id]);
$voyage = $stmt->fetch();

if (!$voyage) {
    echo "Voyage introuvable.";
    exit;
}

$reservation = $_SESSION['reservation_temp'] ?? null;
if (!$reservation) {
    header("Location: options.php?voyage=" . $id);
    exit;
}

$nb_adultes = (int)$reservation['nb_adultes'];
$nb_enfants = (int)$reservation['nb_enfants'];
$date_depart = $reservation['date_depart'];
$duree = (int)$voyage['duree'];
$date_retour = (new DateTime($date_depart))->modify("+".($duree - 1)." days")->format('Y-m-d');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <link id="theme-style" rel="stylesheet">
    <script src="scripts/darkmode.js" defer></script>
    <title><?= htmlspecialchars($voyage['titre']) ?> | Wander7</title>
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
            <?php if (isset($_SESSION['user'])): ?>
                <li><a href="profil.php">Mon profil</a></li>
                <li><a href="deconnexion.php">Se déconnecter</a></li>
            <?php else: ?>
                <li><a href="seconnecter.php">Se connecter</a></li>
            <?php endif; ?>
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <li><a href="admin.php">Admin</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<div class="hero-wrapper">
    <img src="assets/<?= strtolower(str_replace(' ', '_', $voyage['titre'])) ?>_hero.jpg" alt="<?= htmlspecialchars($voyage['titre']) ?>" class="hero-image">
    <div class="hero-overlay"></div>
    <div class="hero-text">
        <h1><?= htmlspecialchars($voyage['titre']) ?></h1>
        <p><?= htmlspecialchars($voyage['pays']) ?></p>
    </div>
</div>

<main class="page-content">
    <section id="reservationForm" class="reservation-container">
        <h1>Informations passagers</h1>
        <form method="POST" action="reservation.php?voyage=<?= $id ?>">
            <input type="hidden" name="voyage_id" value="<?= $id ?>">
            <input type="hidden" name="date_depart" value="<?= $date_depart ?>">
            <input type="hidden" name="date_retour" value="<?= $date_retour ?>">
            <input type="hidden" name="nb_adultes" value="<?= $nb_adultes ?>">
            <input type="hidden" name="nb_enfants" value="<?= $nb_enfants ?>">

            <?php for ($i = 1; $i <= $nb_adultes; $i++): ?>
            <div class="passenger-info">
                <h4>Adulte <?= $i ?></h4>
                <div class="form-group"><label>Nom : <input type="text" name="noms_passagers[]" required></label></div>
                <div class="form-group"><label>Prénom : <input type="text" name="prenoms_passagers[]" required></label></div>
                <div class="form-group"><label>Date de naissance : <input type="date" name="naissances_passagers[]" required></label></div>
                <div class="form-group">
                    <label>Nationalité :
                        <select name="nationalites_passagers[]" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="Française">Française</option>
                            <option value="Marocaine">Marocaine</option>
                            <option value="Algérienne">Algérienne</option>
                            <option value="Tunisienne">Tunisienne</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </label>
                </div>
                <div class="form-group"><label>Passeport : <input type="text" name="passeports_passagers[]" required pattern="[A-Z]{2}[0-9]{6}" placeholder="AB123456"></label></div>
                <input type="hidden" name="type_passagers[]" value="adulte">
            </div>
            <?php endfor; ?>

            <?php for ($j = 1; $j <= $nb_enfants; $j++): ?>
            <div class="passenger-info">
                <h4>Enfant <?= $j ?></h4>
                <div class="form-group"><label>Nom : <input type="text" name="noms_passagers[]" required></label></div>
                <div class="form-group"><label>Prénom : <input type="text" name="prenoms_passagers[]" required></label></div>
                <div class="form-group"><label>Date de naissance : <input type="date" name="naissances_passagers[]" required></label></div>
                <div class="form-group">
                    <label>Nationalité :
                        <select name="nationalites_passagers[]" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="Française">Française</option>
                            <option value="Marocaine">Marocaine</option>
                            <option value="Algérienne">Algérienne</option>
                            <option value="Tunisienne">Tunisienne</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </label>
                </div>
                <div class="form-group"><label>Passeport : <input type="text" name="passeports_passagers[]" required pattern="[A-Z]{2}[0-9]{6}" placeholder="AB123456"></label></div>
                <input type="hidden" name="type_passagers[]" value="enfant">
            </div>
            <?php endfor; ?>

            <div class="reservation-button-container">
                <button type="submit" class="confirmation-button">Confirmer la réservation</button>
            </div>
        </form>
    </section>
</main>

<footer>
    <p>&copy; 2025 Wander7. Tous droits réservés.</p>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const target = document.getElementById("reservationForm");
  if (target) {
    setTimeout(() => {
      smoothScrollTo(target, 1500); 
    }, 400);
  }

  function smoothScrollTo(element, duration) {
    const start = window.pageYOffset;
    const end = element.getBoundingClientRect().top + window.pageYOffset;
    const distance = end - start;
    const startTime = performance.now();

    function scrollStep(currentTime) {
      const timeElapsed = currentTime - startTime;
      const progress = Math.min(timeElapsed / duration, 1);
      const ease = progress < 0.5
        ? 2 * progress * progress
        : -1 + (4 - 2 * progress) * progress;
      window.scrollTo(0, start + distance * ease);
      if (timeElapsed < duration) {
        requestAnimationFrame(scrollStep);
      }
    }

    requestAnimationFrame(scrollStep);
  }
});

</script>

</body>
</html>