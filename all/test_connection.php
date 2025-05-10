<?php
// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=clickjourney', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion réussie à la base de données !<br>";
} catch (PDOException $e) {
    die("❌ Erreur de connexion : " . $e->getMessage());
}

// Requête pour récupérer les utilisateurs
try {
    $stmt = $pdo->query("SELECT * FROM utilisateurs");
    echo "<h2>Liste des utilisateurs :</h2>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "👤 Nom : " . $row['nom'] . " | Prénom : " . $row['prenom'] . " | Email : " . $row['email'] . "<br>";
    }
} catch (PDOException $e) {
    echo "❌ Erreur lors de la requête : " . $e->getMessage();
}
?>
