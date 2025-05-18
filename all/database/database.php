<?php

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
$sqlFile = __DIR__ . '/clickjourney.sql'; 



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