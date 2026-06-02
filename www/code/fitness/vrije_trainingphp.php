<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logincontrole
require '../../code/login/auth.php';
include "config.php";
include "../../code/gamification/functie_xp.php";

$gebruikernummer = $_SESSION['user_id'];

// POST-waarden ophalen
$U_Oeftype1 = $_POST['U_Oeftype1'] ?? null;
$U_Oeftype2 = $_POST['U_Oeftype2'] ?? null;
$U_Oefnaam  = $_POST['U_Oefnaam']  ?? null;
$U_Kg       = $_POST['U_Kg']       ?? null;
$U_Aantal   = $_POST['U_Aantal']   ?? null;

// Fallback voor naam
if (empty($U_Oefnaam)) {
    $U_Oefnaam = "Geen";
}

// Nieuwe oefening opslaan
$sql = "INSERT INTO Uitgevoerdeoef
        (U_Oeftype1, U_Oeftype2, U_Oefnaam, U_Kg, U_Aantal, Gebruikernr)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Fout bij prepare: " . $conn->error);
}

// ENUMS = strings → dus sssssi
$stmt->bind_param(
    "ssssdi",
    $U_Oeftype1,
    $U_Oeftype2,
    $U_Oefnaam,
    $U_Kg,
    $U_Aantal,
    $gebruikernummer
);

if (!$stmt->execute()) {
    die("Fout bij opslaan: " . $stmt->error);
}

$stmt->close();

// XP geven NA succesvolle insert
addXP($gebruikernummer, 10, $conn);

$conn->close();

// Terug naar vrije training pagina
header("Location: vrije_training.php");
exit;
?>
