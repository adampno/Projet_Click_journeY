<?php
session_start();
require_once "database/database.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: seconnecter.php");
    exit;
}

// Fonction pour calculer l'âge
function calculerAge($dateNaissance) {
    try {
        $anniversaire = new DateTime($dateNaissance);
        $aujourdhui = new DateTime();
        return $anniversaire->diff($aujourdhui)->y;
    } catch (Exception $e) {
        return 0;
    }
}

// Vérifie la réservation en session
$reservation_id = $_SESSION['last_reservation_id'] ?? null;
if (!$reservation_id) {
    header("Location: index.php");
    exit;
}

// Récupère les infos de réservation
$stmt = $pdo->prepare("SELECT r.*, v.titre, v.pays FROM reservations r 
                       JOIN voyages v ON r.voyage_id = v.id_voyage 
                       WHERE r.id_reservation = :id");
$stmt->execute(['id' => $reservation_id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    echo "Réservation introuvable.";
    exit;
}

// Traitement du formulaire de passagers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $noms = $_POST['noms_passagers'] ?? [];
    $prenoms = $_POST['prenoms_passagers'] ?? [];
    $naissances = $_POST['naissances_passagers'] ?? [];
    $nationalites = $_POST['nationalites_passagers'] ?? [];
    $passeports = $_POST['passeports_passagers'] ?? [];
    $types = $_POST['type_passagers'] ?? [];

    try {
        $pdo->beginTransaction();

        for ($i = 0; $i < count($noms); $i++) {
            $stmt = $pdo->prepare("INSERT INTO passagers 
                (reservation_id, type_passager, nom, prenom, date_naissance, nationalite, passeport, age)
                VALUES (:res_id, :type, :nom, :prenom, :naissance, :nationalite, :passeport, :age)");

            $stmt->execute([
                'res_id' => $reservation_id,
                'type' => $types[$i],
                'nom' => $noms[$i],
                'prenom' => $prenoms[$i],
                'naissance' => $naissances[$i],
                'nationalite' => $nationalites[$i],
                'passeport' => $passeports[$i],
                'age' => calculerAge($naissances[$i])
            ]);
        }

        $pdo->commit();
        unset($_SESSION['reservation_prete']);
        header("Location: recap.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($reservation['titre']) ?> | Wander7</title>
    <link id="theme-style" rel="stylesheet">
    <script src="scripts/darkmode.js" defer></script>
</head>
<body>
<header>
    <img class="logo" src="assets/LogoWander7.png" alt="logo">
    <nav>
        <ul class="nav_links">
            <button id="theme-selector" style="position: fixed; top: 20px; right: 20px;">🌙</button>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="aproposdenous.php">À propos</a></li>
            <li><a href="explorer.php">Explorer</a></li>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="deconnexion.php">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<div class="hero-wrapper">
    <img src="assets/<?= strtolower(str_replace(' ', '_', $reservation['titre'])) ?>_hero.jpg" alt="<?= htmlspecialchars($reservation['titre']) ?>" class="hero-image">
    <div class="hero-overlay"></div>
    <div class="hero-text">
        <h1><?= htmlspecialchars($reservation['titre']) ?></h1>
        <p><?= htmlspecialchars($reservation['pays']) ?></p>
    </div>
</div>

<main class="page-content">
    <section id="reservationForm" class="reservation-container">
        <h1>Informations passagers</h1>
        <form method="POST">
            <?php for ($i = 1; $i <= $reservation['nb_adultes']; $i++): ?>
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

            <?php for ($j = 1; $j <= $reservation['nb_enfants']; $j++): ?>
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
                <button type="submit" class="confirmation-button">Enregistrer les informations</button>
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