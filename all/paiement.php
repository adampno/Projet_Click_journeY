<?php
require_once "database/database.php";
require "getapikey.php";

// Exemple : variables fixes pour test, à remplacer par tes vraies données (il faut faire le JS)
$id_user = 1;
$id_voyage = 1;
$montant = 30.00;
$titre = "PETRA - Visite guidée"; // Exemple de destination



$transaction_id =  strtoupper(bin2hex(random_bytes(5)));
$vendeur = "MI-2_E";
$api_key = getAPIKey($vendeur);

$montant_formate = number_format($montant, 2, '.', '');

$retour = "http://localhost:8888/all/retour_paiement.php";

$control_hash = md5($api_key . "#" . $transaction_id . "#" . $montant_formate . "#" . $vendeur . "#" . $retour . "#");

try {
    $pdo = new PDO("mysql:host=localhost;dbname=clickjourney", "root", "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
    <style>
        body { font-family: Arial, sans-serif; background: #111; color: #fff; padding: 2em; }
        .box { background: #222; padding: 1.5em; border-radius: 10px; max-width: 500px; margin: auto; }
        h2 { color: #00ffff; }
        button { padding: 10px 20px; background: #00ffff; border: none; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Récapitulatif</h2>
        <p><strong>Destination :</strong> <?= htmlspecialchars($titre) ?></p>
        <p><strong>Total à payer :</strong> <?= number_format($montant, 2, ',', ' ') ?> €</p>

        <h2>Paiement</h2>
        <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
            <input type="hidden" name="transaction" value="<?= htmlspecialchars($transaction_id) ?>">
            <input type="hidden" name="montant" value="<?= $montant_formate;?>">
            <input type="hidden" name="vendeur" value="<?= htmlspecialchars($vendeur) ?>">
            <input type="hidden" name="retour" value="<?= htmlspecialchars($retour) ?>">
            <input type="hidden" name="control" value="<?= htmlspecialchars($control_hash) ?>">
            <button type="submit">Payer <?= $montant_formate; ?> €</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
    </footer>
</body>
</html>
