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
require_once "../database/database.php"; // adapte le chemin si nécessaire

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Vérifier que tous les champs sont remplis
    if (empty($email) || empty($password)) {
        $_SESSION['sign_in_up_error'] = "Tous les champs sont obligatoires.";
        header("Location: seconnecter.php");
        exit;
    }

    try {
        // Rechercher l'utilisateur dans la base de données
        $stmt = $pdo->prepare("SELECT id, email, mot_de_passe, role FROM utilisateurs WHERE email = ?");

        echo "Email récupéré : " . $email;

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if (!$user) {
            echo "⚠️ Aucun utilisateur trouvé avec cet email.";
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

            echo "Connexion réussie !";
            // Rediriger vers la page d'accueil ou une page spécifique
            header("Location: ../index.php");
            exit;
        } else {
            // Mauvais identifiants
            $_SESSION['sign_in_up_error'] = "Email ou mot de passe incorrect.";
            echo "Connexion échouée.";
            header("Location: ../seconnecter.php");
            exit;
        }
    } catch (PDOException $e) {
        // Erreur lors de la connexion à la base
        $_SESSION['sign_in_up_error'] = "Erreur serveur. Veuillez réessayer plus tard.";
        header("Location: ../seconnecter.php");
        exit;
    }
} else {
    // Si l'utilisateur accède à ce fichier sans passer par un POST, on redirige
    header("Location: ../seconnecter.php");
    exit;
}
?>
