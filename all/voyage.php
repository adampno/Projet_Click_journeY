<?php
// Activation des erreurs PHP pour debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
require_once "config.php";

// Récupération de l'ID du voyage depuis l'URL
$id = $_GET['voyage'] ?? null;
if ($id === null) {
    echo "❌ ID de voyage non fourni.";
    exit;
}
$id = (int) $id;

// 🔍 Récupération des informations du voyage
$stmt_voyage = $pdo->prepare("SELECT * FROM voyages WHERE id_voyage = :id LIMIT 1");
$stmt_voyage->bindParam(':id', $id, PDO::PARAM_INT);

if (!$stmt_voyage->execute()) {
    print_r($stmt_voyage->errorInfo());
    exit;
}

$voyage = $stmt_voyage->fetch();

if (!$voyage) {
    echo "❌ Voyage non trouvé.";
    exit;
}


// 🔍 Récupération des vols
$stmt_vols = $pdo->prepare("SELECT * FROM vols WHERE id_voyage = :id");
$stmt_vols->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_vols->execute();
$vols = $stmt_vols->fetchAll();


// 🔍 Récupération des hébergements du voyage
$stmt_hebergements = $pdo->prepare("SELECT * FROM hebergements WHERE id_voyage = :id");
$stmt_hebergements->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_hebergements->execute();
$hebergements = $stmt_hebergements->fetchAll();


// 🔍 Récupération des activités du voyage
$stmt_activites = $pdo->prepare("SELECT * FROM activites WHERE id_voyage = :id");
$stmt_activites->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_activites->execute();
$activites = $stmt_activites->fetchAll();


?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style/voyage.css">
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
          <li><a href="profil.php">Mon profil</a></li>
          <li><a href="admin.php">Admin</a></li>
          <li><a href="seconnecter.php">Se connecter</a></li>
        </ul>
      </nav>
    </header>


        <div class="voyage-container">
            <h1>🌍 <?php echo htmlspecialchars($voyage['titre']); ?></h1>
            <p>📅 Départ : <?php echo $voyage['date_debut']; ?> - Retour : <?php echo $voyage['date_fin']; ?></p>
            <p>💰 Prix Total : <?php echo $voyage['prix_total']; ?> €</p>
            <p>📌 Spécificités : <?php echo nl2br(htmlspecialchars($voyage['specificites'])); ?></p>
        </div>


        <h2>🛫 Vols</h2>
        <div class="vols">
            <?php foreach ($vols as $vol): ?>
                <div class="vol">
                    <h3><?php echo $vol['aeroport_depart']; ?> -> <?php echo $vol['aeroport_arrivee']; ?></h3>
                    <p>Départ : <?php echo $vol['date_depart']; ?></p>
                    <p>Arrivée : <?php echo $vol['date_arrivee']; ?></p>
                    <p>Prix : <?php echo $vol['prix']; ?> €</p>
            </div>
            <?php endforeach; ?>
            </div>


            <h2>🏨 Hébergements proposés</h2>
        <div class="hebergements">
            <?php foreach ($hebergements as $hebergement): ?>
                <div class="hebergement">
                    <h3><?php echo $hebergement['nom']; ?> (<?php echo $hebergement['niveau']; ?>)</h3>
                    <p>Prix par nuit : <?php echo $hebergement['prix_par_nuit']; ?> €</p>
                </div>
            <?php endforeach; ?>
        </div>

       

        <h2>🎯 Activités disponibles</h2>
        <div class="activites">
            <?php foreach ($activites as $activite): ?>
                <div class="activite">
                    <h3><?php echo $activite['nom']; ?> (<?php echo $activite['type_activite']; ?>)</h3>
                    <p>Prix par personne : <?php echo $activite['prix_par_personne']; ?> €</p>
                    <p>Description : <?php echo $activite['description']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
    </footer>

    </body>
</html>
