<?php
session_start(); // Active la gestion des sessions
$estConnecte = isset($_SESSION['user']);
$estAdmin = $estConnecte && ($_SESSION['user']['role'] === 'admin');

//Connexion à la base de données
require_once "database/database.php";


// Récupération dynamique du Top 3 (Colisée, Pétra et Taj Mahal)
$stmt = $pdo->prepare("SELECT id_voyage, titre FROM voyages WHERE titre IN ('Colisée', 'Pétra', 'Taj Mahal')");
$stmt->execute();
$topDestinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

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



    <main>


      <h1>Bienvenue sur Wander7</h1>
      <p>Découvrez les 7 merveilles du monde à travers nos circuits exclusifs.</p>

      <div class="top-destinations">
  <h2 class="section-title">Top 3 des destinations Wander7</h2>
  
  <div class="destinations-grid">

  <?php foreach ($topDestinations as $destination): ?>
      <a href="<?= $estConnecte ? "voyage.php?id=".$destination['id_voyage'] :"seconnecter.php" ?>" class="destination-card">

    <img src="assets/<?= strtolower(str_replace(' ', '', $destination['titre'])) ?>_index.jpg" alt="<?= $destination['titre'] ?>">
    <div class="destination-info">
    <h3><?= $destination['titre'] ?></h3>
    <div class="rating">★★★★★</div>
  </div>
  </a>
  <?php endforeach; ?>

  </div>
  </div>

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