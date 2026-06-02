<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../../code/login/auth.php';
include "config.php";
include "../../code/gamification/functie_xp.php";

// Check verplichte velden
if (!isset($_POST['gebruikernummer'], $_POST['type1'], $_POST['type2'], $_POST['tijd'], $_POST['afstand'])) {
    die("Fout: Niet alle vereiste POST variabelen zijn ingevuld.");
}

// Formulierdata ophalen
$gebruikernummer = intval($_POST['gebruikernummer']);
$type1 = $_POST['type1'];
$type2 = $_POST['type2'];
$tijd = floatval($_POST['tijd']);
$afstand = floatval($_POST['afstand']);

$sql = "INSERT INTO Uitgevoerdeoef 
        (U_Oeftype1, U_Oeftype2, U_Tijd_min, U_Afstand, Gebruikernr)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Fout bij prepare: " . $conn->error);
}

// ssddi = string, string, double, double, int
$stmt->bind_param("ssddi", $type1, $type2, $tijd, $afstand, $gebruikersnummer);

if ($stmt->execute()) {
    addXP($gebruikernummer, 10, $conn);
    header("Location: ../../index.php");
    exit;
} else {
    echo "Fout bij registreren: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
