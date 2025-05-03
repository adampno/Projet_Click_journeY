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
    <link rel="stylesheet" href="style/index.css">
  </head>

  <body>
    <header>
         <img class="logo" src="assets/LogoWander7.png" alt="logo">
         <nav>
             <ul class="nav_links">
             <li><a href="index.php">Accueil</a></li>
              <li><a href="aproposdenous.php">À propos de nous</a></li>
              <li><a href="explorer.php">Explorer</a></li>
              <li><a href="monprofil.php">Mon profil</a></li>
              <li><a href="admin.php">Admin</a></li>
              <li><a href="seconnecter.php">Se connecter</a></li>
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


      <section class="merveilles-container">
      <h2>Nos voyages</h2>
      
      <div class="merveilles-grid">
       
        <div class="merveille-card">
          <a href="<?php echo $estConnecte ? 'grandemuraille.php' : 'seconnecter.php'; ?>">
            <img src="assets/greatWall_index.jpg" alt="La Grande Muraille de Chine">
            <div class="merveille-info">
              <h3>Grande Muraille de Chine</h3>
              <p>À partir de 1499€</p>
              <p>8 jours | 5 étapes</p>
            </div>
          </a>
        </div>

        <div class="merveille-card">
          <a href="<?php echo $estConnecte ? 'christredempteur.php' : 'seconnecter.php'; ?>">
            <img src="assets/christRedeemer_index.jpg" alt="Christ Rédempteur">
            <div class="merveille-info">
              <h3>Christ Rédempteur</h3>
              <p>À partir de 1299€</p>
              <p>7 jours | 4 étapes</p>
            </div>
          </a>
        </div>

        <div class="merveille-card">
          <a href="<?php echo $estConnecte ? 'machupicchu.php' : 'seconnecter.php'; ?>">
            <img src="assets/machuPicchu_index.jpg" alt="Machu Picchu">
            <div class="merveille-info">
              <h3>Machu Picchu</h3>
              <p>À partir de 1599€</p>
              <p>10 jours | 6 étapes</p>
            </div>
          </a>
        </div>

        <div class="merveille-card">
          <a href="<?php echo $estConnecte ? 'chichenitza.php' : 'seconnecter.php'; ?>">
            <img src="assets/chichenItza_index.jpg" alt="Chichén Itzá">
            <div class="merveille-info">
              <h3>Chichén Itzá</h3>
              <p>À partir de 1399€</p>
              <p>9 jours | 5 étapes</p>
            </div>
          </a>
        </div>

        <div class="merveille-card">
          <a href="<?php echo $estConnecte ? 'colisee.php' : 'seconnecter.php'; ?>">
            <img src="assets/colosseum_index.jpg" alt="Colisée de Rome">
            <div class="merveille-info">
              <h3>Colisée de Rome</h3>
              <p>À partir de 1199€</p>
              <p>6 jours | 4 étapes</p>
            </div>
          </a>
        </div>

        <div class="merveille-card">
          <a href="<?php echo $estConnecte ? 'tajmahal.php' : 'seconnecter.php'; ?>">
            <img src="assets/tajmahal_index.jpg" alt="Taj Mahal">
            <div class="merveille-info">
              <h3>Taj Mahal</h3>
              <p>À partir de 1399€</p>
              <p>8 jours | 5 étapes</p>
            </div>
          </a>
        </div>

     
        <div class="merveille-card center-card">
          <a href="<?php echo $estConnecte ? 'petra.php' : 'seconnecter.php'; ?>">
            <img src="assets/petra_index.jpg" alt="Pétra">
            <div class="merveille-info">
              <h3>Pétra</h3>
              <p>À partir de 1699€</p>
              <p>11 jours | 7 étapes</p>
            </div>
          </a>
        </div>
      </div>
    </section>



</main>

<footer>
  <p>&copy; 2025 Wander7. Tous droits réservés.</p>
</footer>

</body>
</html>