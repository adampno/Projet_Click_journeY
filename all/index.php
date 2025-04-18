<?php
session_start(); // Active la gestion des sessions
$estConnecte = isset($_SESSION['user']);
$estAdmin = $estConnecte && ($_SESSION['user']['role'] === 'admin');
?>
<!DOCTYPE html>
  <html lang="fr">
  <head> 
    <title>Wander7-Accueil </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- Adapte le site aux écrans -->
    <link rel="icon" href="assets/Logo_Wander7_Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="index.css">
  </head>

  <body>
    <header>
         <img class="logo" src="assets/LogoWander7.png" alt="logo">
         <nav>
             <ul class="nav_links">
                   <li><a href="index.html">Accueil</a></li> 
                   <li><a href="aproposdenous.html">À propos de nous </a></li> 
                   <li><a href="explorer.html">Explorer</a></li>
                   <li><a href="profil.html">Mon profil</a></li>
                   <li><a href="admin.html">Admin</a></li>  
                   <li><a href="seconnecter.html">Se connecter</a></li>
             </ul>
         </nav>
    </header>

    <main>
      <h1>Bienvenue sur Wander7</h1>
      <p>Découvrez les 7 merveilles du monde à travers nos circuits exclusifs.</p>

      <section class="section__container feature__container" id="service">
        <div class="feature__card">
          <img src="assets/feature-1.png" alt="feature" />
          <div>
            <h4>Destinations incontournables</h4>
            <p>Explorez les merveilles du monde avec Wander7.</p>
          </div>
        </div>

        <div class="feature__card">
          <img src="assets/feature-2.png" alt="feature" />
          <div>
            <h4>Meilleurs prix garantis</h4>
            <p>Voyagez aux tarifs les plus avantageux avec Wander7.</p>
          </div>
        </div>

        <div class="feature__card">
          <img src="assets/feature-3.png" alt="feature" />
          <div>
           <h4>Réservation instantanée</h4>
           <p>Réservez votre escapade de rêve en un seul clic.</p>           
          </div>
        </div>
      </section>
</main>

<footer>
  <p>&copy; 2025 Wander7. Tous droits réservés.</p>
</footer>

</body>
</html>