<?php

session_start();
require_once "database/database.php";
require "getapikey.php";

// Récupération des données envoyées en GET
$transaction_id = $_GET['transaction_id'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$status = $_GET['status'] ?? '';
$control_recu = $_GET['control'] ?? '';


$montant_formate = number_format((float)$montant, 2, '.', '');

// Recalcule du hash
$api_key = getAPIKey($vendeur);
$control_calcule = md5($api_key . "#" . $transaction_id . "#" . $montant_formate . "#" . $vendeur . "#" . $status . "#");


$verification = ($control_recu === $control_calcule);

?>


<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Paiement</title>
        <link id="theme-style" rel="stylesheet">
        <script src="scripts/darkmode.js" defer></script>
   
    </head>

    <body>
        <button id="theme-selector" style="position: fixed; top: 20px; right: 20px; z-index: 1000; font-size: 20px; background: none; border: none; cursor: pointer;">🌙</button>
        <div class="container">
        <?php if ($verification): ?>
            <h1>Paiement validé ✅</h1>
            <p>Merci pour votre réservation. Le paiement a été vérifié avec succès.</p>
            <p><strong>Transaction :</strong> <?= htmlspecialchars($transaction_id) ?></p>
            <p><strong>Montant :</strong> <?= htmlspecialchars($montant_formate) ?> €</p>
        <?php else: ?>
            <h1>Échec de vérification ❌</h1>
            <p>Le paiement n'a pas pu être vérifié. Veuillez contacter le support.</p>
        <?php endif; ?>
    </div>




         <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
      </footer>
      
     </body>
      </html>