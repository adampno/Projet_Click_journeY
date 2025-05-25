<?php
session_start();
require_once "database/database.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = $_GET['voyage'] ?? null;
if ($id === null) {
    echo "❌ ID de voyage non fourni.";
    exit;
}
$id = (int) $id;

if (!isset($_SESSION['user'])) {
    header("Location: seconnecter.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

$stmt = $pdo->prepare("SELECT * FROM voyages WHERE id_voyage = :id");
$stmt->execute(['id' => $id]);
$voyage = $stmt->fetch();

if (!$voyage) {
    echo "❌ Voyage introuvable.";
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
$total_passagers = $nb_adultes + $nb_enfants;

$stmt_duree = $pdo->prepare("SELECT duree FROM voyages WHERE id_voyage = :id");
$stmt_duree->execute(['id' => $id]);
$duree = (int)$stmt_duree->fetchColumn();
$date_retour = (new DateTime($date_depart))->modify("+".($duree - 1)." days")->format('Y-m-d');
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
    <div class="reservation-container">
        <h1>Informations passagers</h1>
        <form action="enregistrer_passagers.php" method="POST">
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
                <button type="submit" class="confirm-btn">Confirmer la réservation</button>
            </div>
        </form>
    </div>
</main>

<footer>
    <p>&copy; 2025 Wander7. Tous droits réservés.</p>
</footer>
</body>
</html>