<?php
session_start(); // Active la gestion des sessions
$estConnecte = isset($_SESSION['user']);
$estAdmin = $estConnecte && ($_SESSION['user']['role'] === 'admin');
unset($_SESSION['reservation_temp']);

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
    <link rel="stylesheet" id="theme-style" >

    <script src="scripts/darkmode.js" defer></script>
    <script src="scripts/cart.js" defer></script>
    <script src="scripts/carousel.js" defer></script>


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





          <?php
$panierItems = $_SESSION['panier'] ?? [];
?>
<!-- Icône Panier -->
<li class="cart-icon">
  <a href="#" id="open-cart">
    <img src="assets/panier.png" alt="Panier" style="height: 24px;">
    <?php if (count($panierItems) > 0): ?>
      <span class="cart-count"><?php echo count($panierItems); ?></span>
    <?php endif; ?>
  </a>
</li>

<?php
if (isset($_SESSION['user'])):
    $panierItems = $_SESSION['panier'] ?? [];
?>
<div class="mini-panier">
  <h3>
    <img src="assets/cart.png" alt="Panier" class="cart-icon-img">
    Mon panier
  </h3>

  <?php if (empty($panierItems)): ?>
    <p>Votre panier est vide.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($panierItems as $id_voyage => $infos): 
        $stmt = $pdo->prepare("SELECT pays FROM voyages WHERE id_voyage = ?");
        $stmt->execute([$id_voyage]);
        $voyage = $stmt->fetch();
        if ($voyage):
          $nb_passagers = $infos['passagers'] ?? 1;
          $date_depart = $infos['date'] ?? 'Date inconnue';
      ?>
        <li>
          <strong><?php echo htmlspecialchars($voyage['pays']); ?></strong><br>
          👤 <?php echo $nb_passagers; ?> passager(s)<br>
          📅 Départ : <?php echo htmlspecialchars($date_depart); ?><br>
          <a href="recap.php?trip=<?php echo $id_voyage; ?>" class="mini-button">Voir détail</a>
        </li>
      <?php endif; endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
<?php endif; ?>


  
        </ul>
      </nav>

    </header>



    <main>


      <h1>Bienvenue sur Wander7</h1>
      <p>Découvrez les 7 merveilles du monde à travers nos circuits exclusifs.</p><br>

      <div class="carousel-wrapper">
      <span class="arrow left-arrow" onclick="prevSlide()">❮</span>
      <div class="carousel">
      <img id="carousel-image" src="assets/greatWall_index.jpg" alt="Image">
      <div class="carousel-text" id="carousel-text">Une gigantesque fortification longue de plus de 20 000 km, construite pour protéger la Chine des invasions. C’est l’un des symboles les plus emblématiques du pays.</div>
      </div>
    <span class="arrow right-arrow" onclick="nextSlide()">❯</span>
      </div>

      <div class="top-destinations">
  <h2 class="section-title">Top 3 des destinations Wander7</h2>
  
  <div class="destinations-grid">

  <?php foreach ($topDestinations as $destination): ?>
      <a href="<?= $estConnecte ? 'voyage.php?id=' . $destination['id_voyage'] :'seconnecter.php?error=unauthorized_access' ?>" class="destination-card">

    <img src="assets/<?= strtolower(str_replace(' ', '_', $destination['titre'])) ?>_index.jpg" alt="<?= $destination['titre'] ?>">
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