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
    <title>Wander7-Explorer</title>
    <link rel="icon" href="assets/Logo_Wander7_Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="explorer.css" />

  </head>

  <body>
    <header>
      <img class="logo" src="assets/LogoWander7.png" alt="logo">
      <nav>
        <ul class="nav_links">
          <li><a href="index.php">Accueil</a></li>
          <li><a href="aproposdenous.php">À propos de nous</a></li>
          <li><a href="explorer.php">Explorer</a></li>
          <li><a href="profil.php">Mon profil</a></li>
          <li><a href="admin.php">Admin</a></li>
          <li><a href="seconnecter.php">Se connecter</a></li>
        </ul>
      </nav>
    </header>

    <main>
       
        
            <div id="conteneurEntete">
              <section id="zoneRecherche">
                <h3>Découvrez les 7 Merveilles du Monde</h3>
                <div id="conteneurChamps">
                  <!-- Sélection de la destination parmi les 7 merveilles -->
                  <div class="champAvecLabel">
                    <label for="destinationSelect" class="labels">Destination</label>
                    <select name="destination" id="destinationSelect">
                      <option value="">Choisir une destination</option>
                      <option value="chichenItza">Chichen Itza (Mexique)</option>
                      <option value="christRedeemer">Christ Rédempteur (Brésil)</option>
                      <option value="greatWall">Grande Muraille (Chine)</option>
                      <option value="machuPicchu">Machu Picchu (Pérou)</option>
                      <option value="petra">Petra (Jordanie)</option>
                      <option value="colosseum">Colisée (Italie)</option>
                      <option value="tajMahal">Taj Mahal (Inde)</option>
                    </select>
                  </div>
                  <!-- Date d'arrivée -->
                  <div class="champAvecLabel">
                    <label for="dateArrivee" class="labels">Arrivée</label>
                    <input type="date" name="dateArrivee" id="dateArrivee">
                  </div>
                  <!-- Nombre de visiteurs avec flèches d'incrémentation -->
                  <div class="champAvecLabel">
                    <label for="invites" class="labels">Visiteurs</label>
                    <input type="number" name="invites" id="invites" min="0" value="0" step="1">
                  </div>
                  <!-- Filtre supplémentaire : Type de voyage -->
                  <div class="champAvecLabel">
                    <label for="typeVoyage" class="labels">Type de voyage</label>
                    <select name="typeVoyage" id="typeVoyage">
                      <option value="">Choisir un type</option>
                      <option value="culturel">Culturel</option>
                      <option value="aventure">Aventure</option>
                      <option value="luxe">Luxe</option>
                      <option value="nature">Nature</option>
                    </select>
                  </div>
                  <!-- Bouton de recherche -->
                  <div class="conteneurBouton">
                    <button id="boutonRecherche">
                      <span id="iconeRecherche" class="material-symbols-outlined">search</span>
                    </button>
                  </div>
                </div>
              </section>
            </div>
          


            <section class="top-destinations">
                <h2>Top 3 des destinations Wander7</h2>
                <div class="destination">
                    <img src="assets/colisee.jpg" alt="Colisée">
                    <h3>Colisée</h3>
                    <p>Note : ★★★★★ (4.8)</p>
                </div>
                <div class="destination">
                    <img src="assets/petra.jpg" alt="Petra">
                    <h3>Petra</h3>
                    <p>Note : ★★★★☆ (4.6)</p>
                </div>
                <div class="destination">
                    <img src="assets/tajmahal.jpg" alt="Taj Mahal">
                    <h3>Taj Mahal</h3>
                    <p>Note : ★★★★☆ (4.7)</p>
                </div>
            </section>
        
            <!-- Explorer par type de voyage -->
            <section class="type-voyage">
                <h2>Explorer par type de voyage</h2>
                <div class="categorie">
                    <h3>Culturel</h3>
                    <p>Plongez dans les traditions.</p>
                </div>
                <div class="categorie">
                    <h3>Aventure</h3>
                    <p>Pour les amateurs de sensations fortes.</p>
                </div>
                <div class="categorie">
                    <h3>Luxe</h3>
                    <p>Voyagez avec style et confort.</p>
                </div>
                <div class="categorie">
                    <h3>Nature</h3>
                    <p>Reconnectez-vous avec la nature.</p>
                </div>
            </section>
      

   

       <!--map-->

        <h1>Où se cachent les 7 Merveilles du Monde ? 🌍</h1>
        <div id="map"></div>


        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    </head>
    <body>
    
     
    
        <div id="info">
            <h2 id="wonder-title"> Un clic, une merveille : regardez un aperçu en vidéo !</h2>
            <video id="wonder-video" controls style="display: none;">
                <source id="video-source" src="" type="video/mp4">
                Votre navigateur ne supporte pas la lecture vidéo.
            </video>
        </div>
        <script src="explorer.js"></script

    </script>


    </main>
    
    <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
      </footer>
      
      </body>
      </html>
        
      
