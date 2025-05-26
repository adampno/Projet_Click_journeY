<?php
require_once "database/database.php";
session_start();

$estConnecte = isset($_SESSION['user']);
$estAdmin = $estConnecte && ($_SESSION['user']['role'] === 'admin');

// Rediriger si l'utilisateur n'est pas admin
if (!$estAdmin) {
    header("Location: index.php");
    exit();
}

// Pagination
$voyagesParPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $voyagesParPage;

// Total voyages
$requeteTotal = $connexion->query("SELECT COUNT(*) AS total FROM voyage");
$totalVoyages = $requeteTotal->fetch()['total'];
$totalPages = ceil($totalVoyages / $voyagesParPage);

// Récupération voyages
$requete = $connexion->prepare("SELECT * FROM voyage LIMIT :limit OFFSET :offset");
$requete->bindValue(':limit', $voyagesParPage, PDO::PARAM_INT);
$requete->bindValue(':offset', $offset, PDO::PARAM_INT);
$requete->execute();
$voyages = $requete->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wander7 - Admin</title>
    <link rel="icon" href="assets/Logo_Wander7_Favicon.png" type="image/x-icon">
    <link id="theme-style" rel="stylesheet" href="styles.css"> <!-- Lien vers ton CSS -->
    <script src="scripts/darkmode.js" defer></script>
</head>
<body>
    <!-- HEADER -->
    <header>
        <img class="logo" src="assets/LogoWander7.png" alt="logo">
        <nav>
            <ul class="nav_links">
                <button id="theme-selector" style="position: fixed; top: 20px; right: 20px; z-index: 1000; font-size: 20px; background: none; border: none; cursor: pointer;">🌙</button>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="aproposdenous.php">À propos de nous</a></li>
                <li><a href="explorer.php">Explorer</a></li>
                <?php if ($estConnecte): ?>
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

    <!-- CONTENU ADMIN -->
    <main style="padding: 20px;">
        <h2>Liste des voyages</h2>
        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Durée</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Pays</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($voyages as $voyage): ?>
                    <tr>
                        <td><?= htmlspecialchars($voyage['id_voyage']) ?></td>
                        <td><?= htmlspecialchars($voyage['titre']) ?></td>
                        <td><?= htmlspecialchars($voyage['duree']) ?> jours</td>
                        <td><?= htmlspecialchars($voyage['prix']) ?> €</td>
                        <td><?= htmlspecialchars($voyage['statut']) ?></td>
                        <td><?= htmlspecialchars($voyage['pays']) ?></td>
                        <td><?= htmlspecialchars($voyage['description']) ?></td>
                        <td>
                            <a href="modifier_voyage.php?id=<?= $voyage['id_voyage'] ?>">Modifier</a> |
                            <a href="supprimer_voyage.php?id=<?= $voyage['id_voyage'] ?>" onclick="return confirm('Supprimer ce voyage ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- PAGINATION -->
        <div style="margin-top: 20px;">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">&laquo; Précédent</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $page): ?>
                    <strong><?= $i ?></strong>
                <?php else: ?>
                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>">Suivant &raquo;</a>
            <?php endif; ?>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
    </footer>
</body>
</html>
