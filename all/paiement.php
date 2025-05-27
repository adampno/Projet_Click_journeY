<?php
session_start();
require_once "database/database.php";
require "getapikey.php";

if (!isset($_SESSION['user']) || !isset($_SESSION['last_reservation_id'])) {
    echo "Erreur : utilisateur ou réservation non définis.";
    exit;
}

$id_user = $_SESSION['user']['id'];
$reservation_id = $_SESSION['last_reservation_id'];

// Récupérer la réservation
$stmt = $pdo->prepare("
    SELECT r.montant_total, r.voyage_id, v.titre 
    FROM reservations r 
    JOIN voyages v ON r.voyage_id = v.id_voyage 
    WHERE r.id_reservation = :id
");
$stmt->execute(['id' => $reservation_id]);
$donnees = $stmt->fetch();

if (!$donnees) {
    echo "Erreur : réservation introuvable.";
    exit;
}

$montant = (float)$donnees['montant_total'];
$id_voyage = $donnees['voyage_id'];
$titre = $donnees['titre'];

$transaction_id = strtoupper(bin2hex(random_bytes(5)));
$vendeur = "MI-2_E";
$api_key = getAPIKey($vendeur);
$montant_formate = number_format($montant, 2, '.', '');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$retour = rtrim($base_url, '/') . '/retour_paiement.php';

$control_hash = md5($api_key . "#" . $transaction_id . "#" . $montant_formate . "#" . $vendeur . "#" . $retour . "#");

// Enregistrer dans la base de données
try {
    $sql = "INSERT INTO paiements (id_user, id_voyage, transaction_id, vendeur, control_hash, montant, statut, numero_carte, nom_titulaire, date_validite, cryptogramme)
            VALUES (:id_user, :id_voyage, :transaction_id, :vendeur, :control_hash, :montant, 'en_attente', '', '', '1970-01-01', '')";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_user' => $id_user,
        ':id_voyage' => $id_voyage,
        ':transaction_id' => $transaction_id,
        ':vendeur' => $vendeur,
        ':control_hash' => $control_hash,
        ':montant' => $montant
    ]);
} catch (PDOException $e) {
    die("Erreur BDD : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
    <link id="theme-style" rel="stylesheet">

        <script src="scripts/darkmode.js" defer></script>
   
</head>
<body>
<button id="theme-selector" style="position: fixed; top: 20px; right: 20px; z-index: 1000; font-size: 20px; background: none; border: none; cursor: pointer;">🌙</button>
    <div class="page-wrapper">
        <div class="box">
            <h2>Récapitulatif</h2>
            <p><strong>Destination :</strong> <?= htmlspecialchars($titre) ?></p>
            <p><strong>Total à payer :</strong> <?= number_format($montant, 2, ',', ' ') ?> €</p>

            <h2>Paiement</h2>
            <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
                <input type="hidden" name="transaction" value="<?= htmlspecialchars($transaction_id) ?>">
                <input type="hidden" name="montant" value="<?= $montant_formate ?>">
                <input type="hidden" name="vendeur" value="<?= htmlspecialchars($vendeur) ?>">
                <input type="hidden" name="retour" value="<?= htmlspecialchars($retour) ?>">
                <input type="hidden" name="control" value="<?= htmlspecialchars($control_hash) ?>">
                <button type="submit">Payer <?= $montant_formate ?> €</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
    </footer>
</body>
</html>