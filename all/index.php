<?php
session_start(); // Active la gestion des sessions
$estConnecte = isset($_SESSION['user']);
$estAdmin = $estConnecte && ($_SESSION['user']['role'] === 'admin');


// Informations de connexion
$host = 'localhost';
$db = 'clickjourney';
$user = 'root'; // Remplacez par votre utilisateur MySQL
$pass = 'root';    // Remplacez par votre mot de passe MySQL

// Options de PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Connexion à MySQL (sans sélectionner de base au départ)
try {
    $pdo = new PDO("mysql:host=$host", $user, $pass, $options);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Nom du fichier SQL
$sqlFile = __DIR__ . '/database/clickjourney.sql'; 



try {
    // Suppression de l'ancienne base de données si elle existe
    $pdo->exec("DROP DATABASE IF EXISTS $db");

    // Création de la nouvelle base de données
    $pdo->exec("CREATE DATABASE $db");

    // Sélection de la base de données
    $pdo->exec("USE $db");

    // Lecture du fichier SQL
    if (file_exists($sqlFile)){
        $sqlContent = file_get_contents($sqlFile);

        // Séparation des commandes SQL par le délimiteur ';'
        $commands = explode(';', $sqlContent);

        // Éxecution des commandes SQL
        foreach ($commands as $command){
            if (trim($command)){
                $pdo->exec($command . ';');
            }
        }
    }
    else {
        throw new Exception("Le fichier SQL n'a pas été trouvé : $sqlFile");
    }
}

catch (PDOException $e){
    die("Erreur lors de l'initialisation de la base de données : " . $e->getMessage());
}
catch (Exception $e){
    die("Erreur : ". $e->getMessage());
}
?>


<!DOCTYPE html>
  <html lang="fr">
  <head> 
    <title>Wander7-Accueil </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- Adapte le site aux écrans -->
    <link rel="icon" href="assets/Logo_Wander7_Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="style/index.css">
  </head>

  <body>
    <header>
         <img class="logo" src="assets/LogoWander7.png" alt="logo">
         <nav>
             <ul class="nav_links">
             <li><a href="index.php">Accueil</a></li>
              <li><a href="aproposdenous.php">À propos de nous</a></li>
              <li><a href="explorer.php">Explorer</a></li>
              <li><a href="monprofil.php">Mon profil</a></li>
              <li><a href="admin.php">Admin</a></li>
              <li><a href="seconnecter.php">Se connecter</a></li>
             </ul>
         </nav>
    </header>

    <main>


      <h1>Bienvenue sur Wander7</h1>
      <p>Découvrez les 7 merveilles du monde à travers nos circuits exclusifs.</p>

      <div class="top-destinations">
  <h2 class="section-title">Top 3 des destinations Wander7</h2>
  
  <div class="destinations-grid">
    <!-- Destination 1 -->
    <div class="destination-card">
      <img src="assets/colisee.jpg" alt="Colisée">
      <div class="destination-info">
        <h3>Colisée</h3>
        <div class="rating">★★★★★ <span>(4.8)</span></div>
      </div>
    </div>
    
    <!-- Destination 2 -->
    <div class="destination-card">
      <img src="assets/petra.jpg" alt="Petra">
      <div class="destination-info">
        <h3>Petra</h3>
        <div class="rating">★★★★☆ <span>(4.6)</span></div>
      </div>
    </div>
    
    <!-- Destination 3 -->
    <div class="destination-card">
      <img src="assets/tajmahal.jpg" alt="Taj Mahal">
      <div class="destination-info">
        <h3>Taj Mahal</h3>
        <div class="rating">★★★★☆ <span>(4.7)</span></div>
      </div>
    </div>
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