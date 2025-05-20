<?php
session_start(); // Active la gestion des sessions

$estConnecte = isset($_SESSION['user']);
$estAdmin = $estConnecte && ($_SESSION['user']['role'] === 'admin');
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wander7-Se Connecter</title>
    <link rel="icon" href="assets/Logo_Wander7_Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="style/seconnecter.css" />
  </head>

  <body>
  <header>
      <img class="logo" src="assets/LogoWander7.png" alt="logo">
      <nav>
        <ul class="nav_links">
          <li><a href="index.php">Accueil</a></li>
          <li><a href="aproposdenous.php">À propos de nous</a></li>
          <li><a href="explorer.php">Explorer</a></li>
          <?php if ($estConnecte):?>
          <li><a href="profil.php">Mon profil</a></li>
          <li><a href="deconnexion.php">Se déconnecter</a></li>
          <?php else: ?>
            <li><a href="seconnecter.php">Se connecter</a></li>
          <?php endif; ?>
          <?php if ($estAdmin): ?>
          <li><a href="admin.php">Admin</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>


    <?php if (isset($_GET['error']) && $_GET['error'] === 'unauthorized_access'): ?>
        <div class="error-message-wrapper">
          <div class="error-message">⚠️ Vous devez être connecté pour accéder aux détails de ce voyage.</div>
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['error']) && $_GET['error'] === 'login_failed'): ?>
        <div class="error-message-wrapper">
          <div class="error-message">⚠️ Email ou mot de passe incorrect.</div>
        </div>
      <?php elseif (isset($_GET['error']) && $_GET['error'] === 'server_error'): ?>
        <div class="error-message-wrapper">
          <div class="error-message">⚠️ Erreur serveur. Veuillez réessayer plus tard.</div>
        </div>
      <?php endif; ?>

    <div class="login-main-container">

      <div class="login-inner-container">

        <div class="login-col1">
          <h1 class="login-h1">Vous avez déjà un compte</h1>
          <p class="login-p">
            Déjà membre ? Identifiez-vous et accédez aux informations de votre espace.
          </p>
        </div>  

        <div class="login-col2">
          <form action="controllers/control_seconnecter.php" method="post" id="login-form" name="login">
            <label for="email" class="login-email">Email</label><br />
            <input type="text" id="email" name="email" required/><br />

          <label for="password"> Mot de passe </label><br />
          <input type="password" id="password" name="password" required/><br />

            <button class="login-button" type="submit">Je me connecte</button>
          </form>

          <p class="login-a">Vous n'avez pas de compte ?
            <a href="sinscrire.php" class="underline-link">Inscrivez vous</a>
          </p>
        </div>
      </div>
    </div>

  <footer>
    <p>&copy; 2025 Wander7. Tous droits réservés.</p>
  </footer>

  </body>
</html>