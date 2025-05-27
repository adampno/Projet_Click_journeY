<?php
session_start(); // Active la gestion des sessions
require_once "database/database.php";

$estConnecte = isset($_SESSION['user']);
$estAdmin = $estConnecte && ($_SESSION['user']['role'] === 'admin');
unset($_SESSION['reservation_temp']);

$titre = $_GET['titre'] ?? null;

try{
  // Requête SQL pour récupérer les voyages
  $query = "SELECT
  v.id_voyage,
  v.titre,
  v.prix,
  v.duree
  FROM voyages v";

  $stmt = $pdo->prepare($query);
  $stmt->execute();
  $voyages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

catch(PDOException $e){
  die("Erreur lors de la récupération des voyages : " . $e->getMessage());
}



try {
    $query = "SELECT 
                v.id_voyage,
                v.titre,
                v.prix AS prix_total,
                v.duree,
                v.statut,
                GROUP_CONCAT(DISTINCT h.h_nom SEPARATOR ', ') AS hebergement,
                GROUP_CONCAT(DISTINCT h.h_localisation SEPARATOR ', ') AS localisations,
                GROUP_CONCAT(DISTINCT h.etoiles SEPARATOR ', ') AS etoiles,
                GROUP_CONCAT(DISTINCT h.h_prix SEPARATOR ', ') AS prix_hebergements,
                GROUP_CONCAT(DISTINCT a.a_nom SEPARATOR ', ') AS activites,
                GROUP_CONCAT(DISTINCT a.a_description SEPARATOR ', ') AS descriptions,
                GROUP_CONCAT(DISTINCT a.a_duree SEPARATOR ', ') AS durees,
                GROUP_CONCAT(DISTINCT a.mode_transport SEPARATOR ', ') AS transports,
                GROUP_CONCAT(DISTINCT a.a_heure_depart SEPARATOR ', ') AS heures_depart,
                GROUP_CONCAT(DISTINCT a.a_prix SEPARATOR ', ') AS prix_activites
              FROM voyages v
              LEFT JOIN hebergements h ON v.id_voyage = h.id_voyage
              LEFT JOIN activites a ON v.id_voyage = a.id_voyage
              WHERE (
                   v.titre LIKE :titre OR 
                   h.h_nom LIKE :titre OR 
                   h.h_localisation LIKE :titre OR 
                   a.a_nom LIKE :titre OR 
                   a.a_description LIKE :titre 
              )
              GROUP BY v.id_voyage";

    $stmt = $pdo->prepare($query);

    $stmt->execute([
        ':titre' => $titre ? "%$titre%" : "%"
    ]);

    $voyages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de recherche : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wander7 - Explorer</title>
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
      <div id="conteneurRecherche">
        <section id="zoneRecherche">
          
        <div id="resultatsRecherche">
    <div class="aucunResultat">
        <p>Effectuez une recherche pour afficher les voyages disponibles</p>
    </div>
</div>


          <!-- Barre de recherche par mots-clés -->
          <form method="GET" action="">
            <input type="text" name="titre" placeholder="Rechercher par mots-clés (ex: Cheval, Maya, Aventure)">
            <button type="submit">Rechercher</button>
          </form>
          

        </section>
      </div>


<h2 class="titre-centre">Résultats :</h2>

          <?php if (empty($voyages)): ?>
              <p class="message-vide">Aucun résultat trouvé pour cette recherche.</p>
          <?php endif; ?>
         
<div class="separateur"></div>

            <section class="merveilles-container">
      <h2>Nos voyages</h2>
      
      <div class="merveilles-grid">
       <?php foreach ($voyages as $voyage): ?>
        <div class="merveille-card">

          <a href="<?= $estConnecte ? 'voyage.php?id=' . $voyage['id_voyage'] : 'seconnecter.php?error=unauthorized_access'; ?>">
            <img src="assets/<?= strtolower(str_replace(' ', '_', $voyage['titre']))?>_index.jpg" alt="<?= $voyage['titre']?>">
            <div class="merveille-info">
              <h3><?= $voyage['titre'] ?></h3>
              <?php if (!empty($voyage['prix_total'])): ?>
              <p>À partir de <?= $voyage['prix_total']?>€</p>
              <?php else: ?> 
                <p>Prix non disponible</p>
                <?php endif; ?>
              
              <p><?= $voyage['duree'] ?> jours </p>

                <?php if (!empty($voyage['activites'])): ?>
                  <p><?= substr_count($voyage['activites'], ',') +1 ?> activités disponibles</p>
                  <?php else: ?>
                    <p>Aucune activité</p>
                    <?php endif; ?>

            </div>
          </a>
        </div>
        <?php endforeach; ?>
              </div>
    </section>


        
    </main>
    
    <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
      </footer>
      
      </body>
      </html>
        
      
