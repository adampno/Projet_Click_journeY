<?php
session_start();
require_once "database/database.php";
ini_set('display_errors', 1);

error_reporting(E_ALL);

if (!isset($_SESSION['reservation_temp'])) {
    echo "Erreur : aucune information de réservation en session.";
    exit;
}


if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo"Méthode non autorisée.";
    exit;
}


$user_id = $_SESSION['user']['id'] ?? null;
$reservation = $_SESSION['reservation_temp'] ?? null;
$action = $_POST['action'] ?? null;
$hotel_id = $_POST['hotel_id'] ?? null;
$voyage_id = $reservation['voyage_id'] ?? null;





// Sécurité et conversion
$nb_adultes = (int)$reservation['nb_adultes'];
$nb_enfants = (int)$reservation['nb_enfants'];
$total_passagers = $nb_adultes + $nb_enfants;
$date_depart = $reservation['date_depart'];
$hotel_id = (int)$hotel_id;
$voyage_id = (int)$voyage_id;

// Récupération durée du voyage
$stmt = $pdo->prepare("SELECT duree FROM voyages WHERE id_voyage = :id");
$stmt->execute(['id' => $voyage_id]);
$duree = (int)$stmt->fetchColumn();
$date_fin = date('Y-m-d', strtotime($date_depart . " + " . ($duree - 1) . " days"));

// Prix vols
$stmt = $pdo->prepare("SELECT SUM(prix) FROM vols WHERE id_voyage = :id");
$stmt->execute(['id' => $voyage_id]);
$prix_vols = (float)$stmt->fetchColumn();
$prix_total_vols = $prix_vols * $total_passagers;

// Prix hôtel
$stmt = $pdo->prepare("SELECT h_prix FROM hebergements WHERE id_hebergement = :hid AND id_voyage = :vid");
$stmt->execute(['hid' => $hotel_id, 'vid' => $voyage_id]);
$prix_chambre = (float)$stmt->fetchColumn();
$nb_chambres = ceil($total_passagers / 2);
$prix_total_hebergement = $prix_chambre * $nb_chambres;

// Activités
$prix_total_activites = 0;
$activites = $_POST['activities'] ?? [];
$dates = $_POST['activities_date'] ?? [];
$participants = $_POST['activities_participants'] ?? [];

try {
    $pdo->beginTransaction();

    // Créer la réservation
    $stmt = $pdo->prepare("INSERT INTO reservations 
        (utilisateur_id, voyage_id, date_debut, date_fin, nb_adultes, nb_enfants, hebergement_id, montant_total, statut_reservation)
        VALUES (:uid, :vid, :start, :end, :adults, :children, :hotel, :total, 'en attente')");
    $stmt->execute([
        'uid' => $user_id,
        'vid' => $voyage_id,
        'start' => $date_depart,
        'end' => $date_fin,
        'adults' => $nb_adultes,
        'children' => $nb_enfants,
        'hotel' => $hotel_id,
        'total' => 0
    ]);
    $reservation_id = $pdo->lastInsertId();
    $_SESSION['last_reservation_id'] = $reservation_id;

    // Ajouter les activités
    foreach ($activites as $id_activite) {
        $nb_part = (int)($participants[$id_activite] ?? 1);
        $date_act = $dates[$id_activite] ?? $date_depart;

        $stmt = $pdo->prepare("SELECT a_prix FROM activites WHERE id_activite = :id");
        $stmt->execute(['id' => $id_activite]);
        $prix_unitaire = (float)$stmt->fetchColumn();

        $prix_total_act = $prix_unitaire * $nb_part;
        $prix_total_activites += $prix_total_act;

        $stmt = $pdo->prepare("INSERT INTO reservation_activites 
            (id_reservation, id_activite, nb_participants, date_activite, prix_total_activite)
            VALUES (:res, :act, :nb, :date, :total)");
        $stmt->execute([
            'res' => $reservation_id,
            'act' => $id_activite,
            'nb' => $nb_part,
            'date' => $date_act,
            'total' => $prix_total_act
        ]);
    }

    // Mise à jour du total
    $montant_final = $prix_total_vols + $prix_total_hebergement + $prix_total_activites;
    $stmt = $pdo->prepare("UPDATE reservations SET montant_total = :total WHERE id_reservation = :id");
    $stmt->execute([
        'total' => $montant_final,
        'id' => $reservation_id
    ]);

    $pdo->commit();
   

    // Redirection
    if ($action === 'retour_accueil') {
        header('Location: index.php');
    } else {
        $_SESSION['reservation_prete'] = true;
        header('Location: reservation.php?voyage=' . $voyage_id);
    }
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erreur : " . $e->getMessage();
    exit;
}