<?php
// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logincontrole
require '../../code/login/auth.php';

// Databaseconfig
include "config.php";
include "../../code/gamification/functie_xp.php";

// Check op verplichte velden
if (!isset($_POST['gebruikernummer']) || !isset($_POST['type1']) || !isset($_POST['type2']) || !isset($_POST['tijd']) || !isset($_POST['afstand'])) {
    die("Fout: Niet alle vereiste POST variabelen zijn ingevuld.");
}

// Formulierdata ophalen
$gebruikernummer = $_POST['gebruikernummer'];
$type1 = $_POST['type1'];
$type2 = $_POST['type2'];
$tijd = $_POST['tijd'];
$afstand = $_POST['afstand'];

// Query voorbereiden
$sql = "INSERT INTO Uitgevoerdeoef 
        (U_Oeftype1, U_Oeftype2, U_Tijd_min, U_Afstand, Gebruikernr)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

// Check op prepare-fout
if (!$stmt) {
    die("Fout bij prepare: " . $conn->error);
}

// Waarden koppelen
$stmt->bind_param("ssdds", $type1, $type2, $tijd, $afstand, $gebruikernummer);

// Uitvoeren
if ($stmt->execute()) {
    addXP($gebruikernummer, 10, $conn);
    header("Location: ../../index.php");
    exit;
} else {
    echo "Fout bij registreren.";
}

$stmt->close();
$conn->close();
?>
