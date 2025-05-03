<?php
session_start(); // Active la gestion des sessions
$estConnecte = isset($_SESSION['user']);
$estAdmin = $estConnecte && ($_SESSION['user']['role'] === 'admin');
?>
<!DOCTYPE html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style/aproposdenous.css" />
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



    <main class="about-container">
        <h1>🚀 Wander7, l’innovation au service du voyage</h1>
        <p class="intro-text">
            Conçu par des passionnés, pour des aventuriers. 🌎
        </p>

        
    <section id="about-agency" class="about-agency">
    <h2>À propos de Wander7</h2>
    <p>
        Wander7 est une agence de voyage innovante située au cœur de Paris, dédiée à la découverte des merveilles du monde. 
        Nous vous proposons des circuits sur mesure vers les sept merveilles les plus emblématiques de la planète, alliant 
        culture, aventure et exploration. Nous mettons notre expertise et notre passion du voyage au service de nos clients, 
        avec des offres personnalisées pour chaque type de voyageur.
    </p>
    <p>
        Située au 32 Rue de la Paix, 75002 Paris, Wander7 est l'agence idéale pour tous ceux qui souhaitent partir à la découverte 
        des plus beaux trésors du monde. Venez nous rendre visite pour discuter de vos projets de voyage et commencer 
        l'aventure de votre vie !
    </p>
   </section>




    
        <section class="about-services">
            <h2>Nos Services</h2>
            <ul>
                <li>📍 Itinéraires sur mesure</li>
                <li>🎟️ Réservation simplifiée</li>
                <li>📊 Conseils et statistiques personnalisés</li>
            </ul>
        </section>

    
        <section class="about-team">
            <h2>Notre Équipe</h2>
            <div class="team-members">
                <a href="https://github.com/adampno" target="_blank">Adam Pineau</a> 
                <a href="https://github.com/hibamesbahi" target="_blank">Hiba Mesbahi</a> 
                <a href="https://github.com/Aliciakl" target="_blank">Alicia Kellai</a> 
            </div>
        </section>
    </main>
    
    <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
    </footer>
    
    </body>
    </html>
