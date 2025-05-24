






<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link id="theme-style" rel="stylesheet">
        <script src="scripts/darkmode.js" defer></script>
        <title><?php echo htmlspecialchars($voyage['titre']); ?> | Wander7</title>
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

<div class="hero-wrapper">
 <img src="assets/<?= strtolower(str_replace(' ', '_', $voyage['titre'])) ?>_hero.jpg" alt="<?= htmlspecialchars($voyage['titre']) ?>" class="hero-image">

 <div class="hero-text">
  <h1><?= htmlspecialchars($voyage['titre']) ?></h1>
  <p><?= htmlspecialchars($voyage['pays']) ?></p>
          </div>

 <div class="hero-overlay" id="heroOverlay"></div>
</div>

<main class="page-content">












<a href="paiement.php?montant=<?= urlencode($montant) ?>&description=<?= urlencode($description) ?>">
    <button>Payer</button>
</a>