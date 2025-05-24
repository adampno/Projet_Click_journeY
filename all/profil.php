<?php
session_start(); // Active la gestion des sessions
unset($_SESSION['reservation_temp']);


// Vérificationsi l'utilisateur est connecté
if (!isset($_SESSION['user'])){
    header("Location: seconnecter.php");
    exit;
}

$estConnecte = isset($_SESSION['user']);
$estAdmin = $estConnecte && ($_SESSION['user']['role'] === 'admin');

// Connexion à la base de données
require_once "database/database.php";

// Récupération de l'ID de l'utilisateur connecté
$userId = $_SESSION['user']['id'];

// Requête pour récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT nom, prenom, email, region, telephone, sexe, date_naissance, date_inscription, derniere_connexion FROM utilisateurs WHERE id = ?");
$stmt->execute([$userId]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$utilisateur){
    echo "Utilisateur non trouvé.";
    exit;
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link id="theme-style" rel="stylesheet">

    <script src="scripts/darkmode.js" defer></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
          <?php if (isset($_SESSION['user'])):?>
          <li><a href="profil.php">Mon profil</a></li>
          <li><a href="deconnexion.php">Se déconnecter</a></li>
          <?php else: ?>
            <li><a href="seconnecter.php">Se connecter</a></li>
          <?php endif; ?>
          <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
          <li><a href="admin.php">Admin</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>



    <?php if (isset($_GET['success']) && $_GET['success'] === 'modification_reussie'): ?>
    <p style="color: green;">✅ Les modifications ont été enregistrées avec succès.</p>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'modification_echouee'): ?>
    <p style="color: red;">❌ Échec de l'enregistrement des modifications.</p>
<?php endif; ?>


    <h1 class="profile-title">Mon Profil</h1>

<form id="profileForm" action="controllers/update_profil.php" method="post">
    <div class="profile-container">
        <!-- Champs modifiables -->
        <!-- Nom -->
        <div class="profile-item">
            <label>Nom :</label>
            <input type="text" name="nom" value="<?= $utilisateur['nom']?>" readonly>
            <i class="fas fa-pen edit-icon" onclick="enableEdit(this)"></i>
        </div>
        <!-- Prénom -->
        <div class="profile-item">
            <label>Prénom :</label>
            <input type="text" name="prenom" value="<?= $utilisateur['prenom']?>" readonly>
            <i class="fas fa-pen edit-icon" onclick="enableEdit(this)"></i>
        </div>
        <!-- Téléphone -->
        <div class="profile-item">
            <label>Télephone :</label>
            <input type="tel" name="telephone" value="<?= $utilisateur['telephone']?>" readonly>
            <i class="fas fa-pen edit-icon" onclick="enableEdit(this)"></i>
        </div>
         <!-- Région -->
        <div class="profile-item">
            <label>Région :</label>
            <select name="region">
    <option value="">-- Sélectionnez une région --</option>
    <option value="auvergne-rhone-alpes" <?= $utilisateur['region'] === 'auvergne-rhone-alpes' ? 'selected' : '' ?>>Auvergne-Rhône-Alpes</option>
    <option value="bourgogne-franche-comte" <?= $utilisateur['region'] === 'bourgogne-franche-comte' ? 'selected' : '' ?>>Bourgogne-Franche-Comté</option>
    <option value="bretagne" <?= $utilisateur['region'] === 'bretagne' ? 'selected' : '' ?>>Bretagne</option>
    <option value="centre-val-de-loire" <?= $utilisateur['region'] === 'centre-val-de-loire' ? 'selected' : '' ?>>Centre-Val de Loire</option>
    <option value="corse" <?= $utilisateur['region'] === 'corse' ? 'selected' : '' ?>>Corse</option>
    <option value="grand-est" <?= $utilisateur['region'] === 'grand-est' ? 'selected' : '' ?>>Grand Est</option>
    <option value="hauts-de-france" <?= $utilisateur['region'] === 'hauts-de-france' ? 'selected' : '' ?>>Hauts-de-France</option>
    <option value="ile-de-france" <?= $utilisateur['region'] === 'ile-de-france' ? 'selected' : '' ?>>Île-de-France</option>
    <option value="normandie" <?= $utilisateur['region'] === 'normandie' ? 'selected' : '' ?>>Normandie</option>
    <option value="nouvelle-aquitaine" <?= $utilisateur['region'] === 'nouvelle-aquitaine' ? 'selected' : '' ?>>Nouvelle-Aquitaine</option>
    <option value="occitanie" <?= $utilisateur['region'] === 'occitanie' ? 'selected' : '' ?>>Occitanie</option>
    <option value="pays-de-la-loire" <?= $utilisateur['region'] === 'pays-de-la-loire' ? 'selected' : '' ?>>Pays de la Loire</option>
    <option value="provence-alpes-cote-d-azur" <?= $utilisateur['region'] === 'provence-alpes-cote-d-azur' ? 'selected' : '' ?>>Provence-Alpes-Côte d'Azur</option>
    <option value="outre-mer" <?= $utilisateur['region'] === 'outre-mer' ? 'selected' : '' ?>>Outre-Mer</option>
</select>
        </div>
         <!-- Sexe -->
        <div class="profile-item">
            <label>Sexe :</label>
            <select name="sexe">
                    <option value="">-- Sélectionnez --</option>
                    <option value="homme" <?= $utilisateur['sexe'] === 'homme' ? 'selected' : '' ?>>Homme</option>
                    <option value="femme" <?= $utilisateur['sexe'] === 'femme' ? 'selected' : '' ?>>Femme</option>
                    <option value="non_precise" <?= $utilisateur['sexe'] === 'non_precise' ? 'selected' : '' ?>>Préfère ne pas préciser</option>
                </select>
</div>

         <!-- Date de naissance -->
        <div class="profile-item">
            <label>Date de Naissance :</label>
            <input type="date" name="date_naissance" value="<?= $utilisateur['date_naissance']?>">
        </div>

        <!-- Email -->
        <div class="profile-item">
            <label>Email :</label>
            <input type="email" name="email" value="<?= $utilisateur['email']?>" required>
            <i class="fas fa-pen edit-icon" onclick="enableEdit(this)"></i>
        </div>

        
        <!-- Champs non modifiables -->
         <!-- Date d'inscription-->
        <div class="profile-item">
            <label>Date d'inscription  :</label>
            <input type="text" value="<?= $utilisateur['date_inscription']?>" readonly>
          </div>
        <!-- Dernière connexion-->
        <div class="profile-item">
            <label>Dernière connexion :</label>
            <input type="text" value="<?= $utilisateur['derniere_connexion']?>" readonly>
    </div>
          </div>

    <!-- Bouton d'enregistrement -->
    <div class="button-container">
        <button class="save-button" type="submit">Enregistrer les modifications</button>
    </div>
          </form>

        <script>
            function enableEdit(icon){
                const input = icon.previousElementSibling;
                input.removeAttribute('readonly');
                input.focus();
            }
            </script>



    <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
      </footer>



</body>
</html>
