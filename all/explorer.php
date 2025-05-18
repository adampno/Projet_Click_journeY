<?php
session_start(); // Active la gestion des sessions
require_once "config.php";

$estConnecte = isset($_SESSION['user']);
$estAdmin = $estConnecte && ($_SESSION['user']['role'] === 'admin');

$titre = $_GET['titre'] ?? null;

try {
    $query = "SELECT 
                v.id_voyage,
                v.titre,
                v.date_debut,
                v.date_fin,
                v.prix_total,
                v.specificites,
                h.nom AS hebergement,
                h.type_hebergement,
                a.nom AS activite,
                a.type_activite
              FROM voyages v
              LEFT JOIN hebergements h ON v.id_voyage = h.id_voyage
              LEFT JOIN activites a ON v.id_voyage = a.id_voyage
              WHERE (
                   v.titre LIKE :titre OR 
                   h.nom LIKE :titre OR 
                   h.type_hebergement LIKE :titre OR 
                   a.nom LIKE :titre OR 
                   a.type_activite LIKE :titre OR
                   v.specificites LIKE :titre
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
    <link rel="stylesheet" href="style/explorer.css" />
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
          
          <!-- Barre de recherche par mots-clés -->
          <form method="GET" action="">
            <input type="text" name="titre" placeholder="Rechercher par mots-clés (ex: Cheval, Maya, Aventure)">
            <button type="submit">Rechercher</button>
          </form>

          <!-- Résultats de recherche -->
          <h2>Résultats :</h2>
          <ul>
          <?php if (empty($voyages)): ?>
              <li>Aucun résultat trouvé pour cette recherche.</li>
          <?php else: ?>
              <?php foreach ($voyages as $voyage): ?>
                  <li>
                      <h3><?= $voyage['titre'] ?></h3>
                      <p>Date de début : <?= $voyage['date_debut'] ?></p>
                      <p>Date de fin : <?= $voyage['date_fin'] ?></p>
                      <p>Prix : <?= $voyage['prix_total'] ?> €</p>
                      <p>Hébergement : <?= $voyage['hebergement'] ?> (<?= $voyage['type_hebergement'] ?>)</p>
                      <p>Activité : <?= $voyage['activite'] ?> (<?= $voyage['type_activite'] ?>)</p>
                      <p>Spécificités : <?= $voyage['specificites'] ?></p>
                  </li>
              <?php endforeach; ?>
          <?php endif; ?>
          </ul>
        </section>
      </div>
        <!-- Filtres avancés (peuvent être cachés/dépliables) -->
        <div id="filtresAvances">
            <div class="filtreGroupe">
                <div class="champFiltre">
                    <label for="destinationSelect">Destination</label>
                    <select id="destinationSelect">
                        <option value="">Toutes destinations</option>
                        <option value="chichenItza">Chichen Itza (Mexique)</option>
                        <option value="christRedeemer">Christ Rédempteur (Brésil)</option>
                        <option value="greatWall">Grande Muraille (Chine)</option>
                        <option value="machuPicchu">Machu Picchu (Pérou)</option>
                        <option value="petra">Petra (Jordanie)</option>
                        <option value="colosseum">Colisée (Italie)</option>
                        <option value="tajMahal">Taj Mahal (Inde)</option>
                    </select>
                </div>

                <div class="champFiltre">
                    <label for="nombreEtapes">Nombre d'étapes</label>
                    <select id="nombreEtapes">
                        <option value="">Peu importe</option>
                        <option value="1">1 étape</option>
                        <option value="2">2 étapes</option>
                        <option value="3">3 étapes</option>
                        <option value="4">4 étapes</option>
                        <option value="5">5 étapes</option>
                      
                    </select>
                </div>

                <div class="champFiltre">
                    <label for="dateDepart">Date de départ</label>
                    <input type="date" id="dateDepart">
                </div>

                <div class="champFiltre">
                    <label for="budgetMax">Budget max (€)</label>
                    <input type="number" id="budgetMax" min="0" placeholder="Illimité">
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Zone des résultats -->
<div id="resultatsRecherche">
    <!-- Les résultats seront injectés ici via JavaScript/PHP -->
    <div class="aucunResultat">
        <p>Effectuez une recherche pour afficher les voyages disponibles</p>
    </div>
</div>





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
          <a href="voyages.php?id=chichen itza">
          
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
              <p>5 jours | 5 étapes</p>
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
        
      
