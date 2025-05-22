<?php 

session_start();
unset($_SESSION['reservation_temp']);

if (!isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wander7-Confirmation de déconnexion</title>
    <link rel="icon" href="assets/Logo_Wander7_Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="style/deconnexion.css" />
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
    <div class="confirmation-container">

        <h2>Voulez-vous vraiment vous déconnecter ?</h2>
        <form method="post" action="traitement_deconnexion.php">
            <button type="submit" name="confirm" class="btn-confirm">Oui, se déconnecter</button>
            <a href="index.php" class="btn-cancel">Non, revenir à l'accueil</a>
          </form>
          </div>
          </main>


          <footer>
  <p>&copy; 2025 Wander7. Tous droits réservés.</p>
</footer>



     </body>    
</html>
