<?php
session_start();
require_once "../database/database.php"; // Connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
}

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    // Récupération des données du formulaire
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $sexe = trim($_POST['sexe']);
    $telephone = trim($_POST['telephone']);
    $domicile = trim($_POST['domicile']);
    $email = trim($_POST['email']);
    $mot_de_passe = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $date_naissance = trim($_POST['date_naissance']);

    // Vérification si l'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() > 0){
        header("Location: ../sinscrire.php?error=email_exists");
        exit;
    }

    // Affichage de debug
    echo "<pre>";
    print_r([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'mot_de_passe' => $mot_de_passe,
        'region' => $domicile,
        'date_naissance' => $date_naissance,
        'telephone' => $telephone,
        'sexe' => $sexe
    ]);
    echo "</pre>";

    // Insertion dans la base de données
    try {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, region, date_naissance, telephone, sexe) 
                               VALUES (:nom, :prenom, :email, :mot_de_passe, :region, :date_naissance, :telephone, :sexe)");

        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mot_de_passe' => $mot_de_passe,
            'region' => $domicile,
            'date_naissance' => $date_naissance,
            'telephone' => $telephone,
            'sexe' => $sexe
        ]);

        // Vérification de l'insertion
        if ($stmt->rowCount() > 0) {
            header("Location: ../seconnecter.php?success=inscription_reussie");
            exit;
        } else {
            exit;
        }

    } catch (PDOException $e) {
        echo "❌ Erreur lors de l'insertion : " . $e->getMessage();
        exit;
    }
}