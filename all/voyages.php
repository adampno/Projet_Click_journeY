<?php
session_start(); // Active la gestion des sessions
$_SESSION['user'] = ['role' => 'user']; //pour le test//
$estConnecte = isset($_SESSION['user']);
$estAdmin = $estConnecte && ($_SESSION['user']['role'] === 'admin');

$id = $_GET['id'] ?? null;

if (!$id){
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Voyage - <?php echo htmlspecialchars($id); ?></title>
    <link rel="stylesheet" href="all/style/voyage.css">
</head>
<body>


<header>
    <img class="logo" src="assets/LogoWander7.png" alt="logo">
    <nav>
        <ul class="nav_links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="aproposdenous.php">À propos</a></li>
            <li><a href="explorer.php">Explorer</a></li>
            <li><a href="monprofil.php">Mon profil</a></li>
            <li><a href="seconnecter.php">Se connecter</a></li>
        </ul>
    </nav>
</header>



<main>
<?php if ($estConnecte): ?>
<?php
switch ($id){
    case 'chichen_itza':
        include 'travels/chichen_itza.php';
        break;
    case 'petra':
        include 'travels/petra.php';
        break;
    case 'christ_redempteur':
        include 'travels/chris_redempteur.php';
        break;
    case 'machu_picchu':
        include 'travels/machu_picchu.php';
        break;
    case 'rome':
        include 'travels/rome.php';
        break;
    case 'taj_mahal':
        include 'travels/taj_mahal.php';
        break;
    case 'chine':
        include 'travels/chine.php';
        break;
    default:
        header('Location: index.php');
        exit();
}
?>
<?php else: ?>
    <p style="color: red; font-weight: bold;">
        Vous devez être connecté pour personnaliser ce voyage.
</p>
<?php endif; ?>
</main>

<footer>
    <p>&copy; 2025 Wander7. Tous droits réservés.</p>
</footer>

</body>
</html>