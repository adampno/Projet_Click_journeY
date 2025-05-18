<?php
$montant = isset($_GET['montant']) ? $_GET['montant'] : '0.00';
$description = isset($_GET['description']) ? $_GET['description'] : 'Aucun produit';

$montant = htmlspecialchars($montant);
$description = htmlspecialchars($description);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Paiement - Wander7</title>
    <link rel="stylesheet" href="paiement.css">
</head>
<body>
    <main>
        <div class="recap">
            <div class="recap-title">Détails de la commande :</div>
            <div class="recap-item">
                <span>Produit :</span>
                <span><?= $description ?></span>
            </div>
            <div class="recap-item">
                <span>Montant à payer :</span>
                <span><?= number_format((float)$montant, 2, ',', ' ') ?> €</span>
            </div>
        </div>

        <h3>Coordonnées bancaires</h3>
        <form action="https://www.plateforme-smc.fr/cybank" method="POST" novalidate>
            <input type="hidden" name="montant" value="<?= $montant ?>">
            <input type="hidden" name="description" value="<?= $description ?>">

            <label for="numero_carte">Numéro de carte :</label>
            <input type="text" id="numero_carte" name="numero_carte" maxlength="19" required pattern="\d{13,19}" placeholder="1234 5678 9012 3456" autocomplete="cc-number" inputmode="numeric">

            <label for="date_expiration">Date d'expiration (MM/AA) :</label>
            <input type="text" id="date_expiration" name="date_expiration" maxlength="5" required pattern="(0[1-9]|1[0-2])\/\d{2}" placeholder="MM/AA" autocomplete="cc-exp">

            <label for="cvv">CVV :</label>
            <input type="text" id="cvv" name="cvv" maxlength="4" required pattern="\d{3,4}" placeholder="123" autocomplete="cc-csc" inputmode="numeric">

            <input type="submit" value="Valider le paiement">
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
    </footer>
</body>
</html>

