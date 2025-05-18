<?php
require "getapikey.php";

$montant = 30.00;
$transaction = uniqid();
$vendeur = "MI-2_E";
$api_key = getAPIKey($vendeur);

// URL de retour 
$retour = "retour_paiement.php";

// Calcul du contrôle
$control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
</head>
<body>
    <h2>Paiement de <?= $montant ?> €</h2>
    <form action="https://www.plateforme-smc.fr/cybank/init_paiement.php" method="POST">
        <input type="hidden" name="transaction" value="<?= $transaction ?>">
        <input type="hidden" name="montant" value="<?= $montant ?>">
        <input type="hidden" name="vendeur" value="<?= $vendeur ?>">
        <input type="hidden" name="retour" value="<?= $retour ?>">
        <input type="hidden" name="control" value="<?= $control ?>">
        <input type="submit" value="Payer">
    </form>
</body>
</html>

