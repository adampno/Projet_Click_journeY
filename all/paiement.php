<?php
require "getapikey.php";

// Paramètres
$montant = number_format(30.00, 2, '.', ''); // Assure bien le format "30.00"
$transaction = uniqid();
$vendeur = "MI-2_E";
$api_key = getAPIKey($vendeur);

// Génération de l'URL de retour
$http = $_SERVER['HTTP_HOST'];
$path = dirname($_SERVER['SCRIPT_NAME']);
$path = rtrim($path, '/');
$retour = "http://localhost/retour.php";


// Génération de la valeur de contrôle
$control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");

?>

<h2>Paiement de <?= $montant ?> €</h2>

<form action="https://www.plateforme-smc.fr/cybank/init_paiement.php" method="POST">
    <input type="hidden" name="transaction" value="<?= $transaction ?>">
    <input type="hidden" name="montant" value="<?= $montant ?>">
    <input type="hidden" name="vendeur" value="<?= $vendeur ?>">
    <input type="hidden" name="retour" value="<?= $retour ?>">
    <input type="hidden" name="control" value="<?= $control ?>">
    <input type="submit" value="Payer <?= $montant ?> €">
</form>

<!-- Optionnel : pour debug -->
<p><strong>API Key :</strong> <?= $api_key ?></p>
<p><strong>Control :</strong> <?= $control ?></p>
<p><strong>Retour :</strong> <?= $retour ?></p>
