<?php
session_start();
require_once 'includes/auth_check.php'; // Vérifie si l'utilisateur est connecté

// Récupère les données de l'utilisateur depuis la session ou la base de données
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="profil.css">
    <script src="https://kit.fontawesome.com/yourkitcode.js" crossorigin="anonymous"></script>
</head>

<body>
     <header>
        <img class="logo" src="assets/LogoWander7.png" alt="logo">
          <nav>
            <ul class="nav_links">
              <li><a href="index.html">Accueil</a></li>
              <li><a href="aproposdenous.html">À propos de nous</a></li>
              <li><a href="explorer.html">Explorer</a></li>
              <li><a href="monprofil.html">Mon profil</a></li>
              <li><a href="admin.html">Admin</a></li>
              <li><a href="seconnecter.html">Se connecter</a></li>
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
