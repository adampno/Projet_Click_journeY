<?php

// Informations de connexion
$host = 'localhost';
$db = 'clickjourney';
$user = 'root'; // Remplacez par votre utilisateur MySQL

// Teste deux cas de mot de passe windows avec xamp et mac avec mamp
$passwords = ['root', ''];
$pdo = null;
$connected = false;


// Options de PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];


foreach ($passwords as $pass){
    try {
        $pdo = new PDO("mysql:host=$host", $user, $pass, $options);
        $connected = true;
        break;
    } catch (PDOException $e){
        continue;
    }
}

if (!$connected){
    die("Erreur de connexion à MySQL avec tous les mots de passe testés.");
}


// Connexion à MySQL (sans sélectionner de base au départ)
try {
    $pdo = new PDO("mysql:host=$host", $user, $pass, $options);
 
    // Création de la base de données si elle n'existe pas 
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $db");

    $pdo->exec("USE $db");
} 

catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}


// Supprime les tables sauf "utilisateurs"
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    if ($table !== 'utilisateurs') {
        $pdo->exec("DROP TABLE IF EXISTS $table");
    }
}

// On récupère toutes les tables sauf `utilisateurs`
$tables = $pdo->query("
    SELECT table_name 
    FROM information_schema.tables 
    WHERE table_schema = '$db' 
    AND table_name != 'utilisateurs'
")->fetchAll(PDO::FETCH_COLUMN);

if (!empty($tables)) {
    // On désactive les contraintes de clés étrangères
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // On supprime toutes les tables sauf `utilisateurs`
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS $table");
    }

    // On réactive les contraintes
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
}

// Nom du fichier SQL
$sqlFile = __DIR__ . '/../database/clickjourney.sql'; 

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

?>