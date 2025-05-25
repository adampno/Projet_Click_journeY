<?php
session_start();
require_once "database/database.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérification de la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Méthode non autorisée.";
    exit;
}

// Vérifie qu'on a une réservation temporaire
if (!isset($_SESSION['reservation_temp'])) {
    echo "Aucune réservation en cours.";
    exit;
}

// Fonction pour calculer l'âge
function calculerAge($dateNaissance) {
    if (!$dateNaissance) return null;
    try {
        $anniversaire = new DateTime($dateNaissance);
        $aujourdHui = new DateTime();
        return $anniversaire->diff($aujourdHui)->y;
    } catch (Exception $e) {
        return null;
    }
}

// Récupération des données du formulaire
$voyage_id = $_POST['voyage_id'] ?? null;
$date_depart = $_POST['date_depart'] ?? null;
$nb_adultes = (int)($_POST['nb_adultes'] ?? 0);
$nb_enfants = (int)($_POST['nb_enfants'] ?? 0);

$noms = $_POST['noms_passagers'] ?? [];
$prenoms = $_POST['prenoms_passagers'] ?? [];
$naissances = $_POST['naissances_passagers'] ?? [];
$nationalites = $_POST['nationalites_passagers'] ?? [];
$passeports = $_POST['passeports_passagers'] ?? [];
$types = $_POST['type_passagers'] ?? [];

$user_id = $_SESSION['user']['id'] ?? null;

// Vérifie les données essentielles
if (!$voyage_id || !$user_id || empty($noms) || count($noms) !== count($types)) {
    echo "Données manquantes ou incohérentes.";
    exit;
}

try {
    // Récupère l'identifiant de la dernière réservation faite par l'utilisateur
    $stmt = $pdo->prepare("SELECT id_reservation FROM reservations WHERE utilisateur_id = :uid ORDER BY id_reservation DESC LIMIT 1");
    $stmt->execute(['uid' => $user_id]);
    $reservation_id = $stmt->fetchColumn();

    if (!$reservation_id) {
        throw new Exception("Réservation introuvable.");
    }

    $pdo->beginTransaction();

    // Boucle d'insertion de chaque passager
    for ($i = 0; $i < count($noms); $i++) {
        $age = ($types[$i] === 'adulte') ? calculerAge($naissances[$i]) : null;

        $stmt = $pdo->prepare("
            INSERT INTO passagers (
                reservation_id, type_passager, nom, prenom, date_naissance, nationalite, passeport, age
            ) VALUES (
                :reservation_id, :type, :nom, :prenom, :naissance, :nationalite, :passeport, :age
            )
        ");

        $stmt->execute([
            'reservation_id' => $reservation_id,
            'type' => $types[$i],
            'nom' => $noms[$i],
            'prenom' => $prenoms[$i],
            'naissance' => $naissances[$i],
            'nationalite' => $nationalites[$i],
            'passeport' => $passeports[$i],
            'age' => $age
        ]);
    }

    $pdo->commit();

    // Enregistre l'ID de réservation dans la session pour le recap
    $_SESSION['last_reservation_id'] = $reservation_id;
    unset($_SESSION['reservation_temp']);

    header("Location: recap.php");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erreur : " . $e->getMessage();
}