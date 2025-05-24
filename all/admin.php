<?php
session_start(); // Active la gestion des sessions
$estConnecte = isset($_SESSION['user']);
$estAdmin = $estConnecte && ($_SESSION['user']['role'] === 'admin');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wander7-Admin</title>
    <link rel="icon" href="assets/Logo_Wander7_Favicon.png" type="image/x-icon">
    <link id="theme-style" rel="stylesheet">

    <script src="scripts/darkmode.js" defer></script>
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

    <main>
        <h3>Gestion des utilisateurs</h3>
        <table>

            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>


            <tr>
                <td>Jean-Michel Basique</td>
                <td>simplebasique@gmail.com</td>
                <td>Standard</td>
                <td>
                    <button class="ban">Ban</button>
                    <button class="vip">VIP</button>
                </td>
            </tr>
            <tr>
                <td>Jean-Michel PasCool</td>
                <td>jm.pascool@gmail.com</td>
                <td>Ban</td>
                <td>
                    <button class="standard">Standard</button>
                    <button class="vip">VIP</button>
                </td>
            </tr>
            <tr>
                <td>Jean-Michel Célèbre</td>
                <td>jean1@gmail.com</td>
                <td>VIP</td>
                <td>
                    <button class="ban">Ban</button>
                    <button class="standard">Standard</button>
                </td>
            </tr>

        </table>
        <button class="reset">Réinitialiser</button>
        <button class="check">Valider</button>
    </main>

    <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
    </footer>
</body>

</html>
