<?php
// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si l'utilisateur est déjà connecté, le rediriger vers la page principale
if (isset($_SESSION["user"])) {
    header("Location: ../index.php");
    exit;
}

// Inclure la configuration de la base de données
require_once "../database/database.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

 
    try {
        // Rechercher l'utilisateur dans la base de données
        $stmt = $pdo->prepare("SELECT id, email, mot_de_passe, role FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if (!$user) {
            header("Location: ../seconnecter.php?error=login_failed");
            exit;
        }     


        if ($user && password_verify($password, $user["mot_de_passe"])) {
            // Authentification réussie : enregistrer les infos dans la session
            $_SESSION["user"] = [
                "id" => $user["id"],
                "email" => $user["email"],
                "role" => $user["role"]
            ];

            // Optionnel : mise à jour de la dernière connexion
            $updateStmt = $pdo->prepare("UPDATE utilisateurs SET derniere_connexion = NOW() WHERE id = ?");
            $updateStmt->execute([$user["id"]]);

        
            // Rediriger vers la page d'accueil 
            header("Location: ../index.php");
            exit;
        } else {
            // Mauvais identifiants
            header("Location: ../seconnecter.php?error=login_failed");
            exit;
        }
    } catch (PDOException $e) {
        // Erreur lors de la connexion à la base
        header("Location: ../seconnecter.php?error=server_error");
        exit;
    }
} else {
    // Si l'utilisateur accède à ce fichier sans passer par un POST, on redirige
    header("Location: ../seconnecter.php");
    exit;
}
?>
