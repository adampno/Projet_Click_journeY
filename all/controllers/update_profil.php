<?php
session_start();


// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user'])){
    header('Location: ../seconnecter/php');
    exit;
}

require_once "../database/database.php"; // Connexion à la base de données

// Récupération de l'ID de l'utilisateur connecté
$userId = $_SESSION['user']['id'];

// Récupération des nouvelles informations depuis le formulaire
$nom = isset($_POST['nom']) ? trim($_POST['nom']) : null;
$prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : null;
$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : null;
$region = isset($_POST['region']) ? trim($_POST['region']) : null;
$sexe = isset($_POST['sexe']) ? trim($_POST['sexe']) : null;
$date_naissance = isset($_POST['date_naissance']) ? trim($_POST['date_naissance']) : null;

// Vérification des champs obligatoires
if (!$nom || !$prenom || !$email){
    header("Location: ../profil.php?error=champs_vides");
    exit;
}



// Préparation de la requête SQL
$stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, telephone = ?, region = ?, sexe = ?, date_naissance = ? WHERE id = ?");

// Éxécution de la requête
$success = $stmt->execute([$nom, $prenom, $email, $telephone, $region, $sexe, $date_naissance, $userId]);

if ($success){
    // Mise à jour des informations dans la session
    $_SESSION['user']['email'] = $email;
    header("Location: ../profil.php?success=modification_reussie");
    exit;
} else {
    header('Location: ../profil.php?error=modification_echouee');
    exit;
}
?>






