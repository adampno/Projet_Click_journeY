<?php
session_start(); // Active la gestion des sessions


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
$stmt = $pdo->preprare("SELECT nom, prenom, email, region, telephone FROM utilisateurs WHERE id = ?");
$stmt>execute([$userId]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="style/profil.css">
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
          <?php else: ?>
            <li><a href="seconnecter.php">Se connecter</a></li>
          <?php endif; ?>
          <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
          <li><a href="admin.php">Admin</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>



    <!-- Titre -->
    <h1 class="profile-title">Mon Profil</h1>

    <!-- Conteneur du profil -->
    <div class="profile-container">
        
        <!-- Nom et prénom -->
        <div class="profile-item">
            <i class="fa-solid fa-user"></i>
            <label for="nom">Nom & Prénom :</label>
            <input type="text" id="nom" value="Dupont Jean">
            <i class="fa-solid fa-pen edit-icon"></i>
        </div>

        <!-- Numéro de téléphone -->
        <div class="profile-item">
            <i class="fa-solid fa-phone"></i>
            <label for="telephone">Téléphone :</label>
            <input type="tel" id="telephone" value="0612345678">
            <i class="fa-solid fa-pen edit-icon"></i>
        </div>

        <!-- Domicile -->
        <div class="profile-item">
            <i class="fa-solid fa-map-marker-alt"></i>
            <label for="domicile">Domicile :</label>
            <input type="text" id="domicile" value="Île-de-France">
            <i class="fa-solid fa-pen edit-icon"></i>
        </div>

        <!-- Email -->
        <div class="profile-item">
            <i class="fa-solid fa-envelope"></i>
            <label for="email">Email :</label>
            <input type="email" id="email" value="DupontJean@gmail.com">
            <i class="fa-solid fa-pen edit-icon"></i>
        </div>

    </div>

    <!-- Bouton d'enregistrement -->
    <div class="button-container">
        <button class="save-button">Enregistrer les modifications</button>
    </div>



    <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
      </footer>



</body>
</html>
