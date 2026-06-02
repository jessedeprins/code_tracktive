<?php
// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logincontrole
require '../../code/login/auth.php';

// Databaseconfig
include "config.php";

// Check of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    die("Niet ingelogd.");
}

// Check op verplichte velden
if (!isset($_POST['Schema_naam'])) {
    die("Fout: Niet alle vereiste POST variabelen zijn ingevuld.");
}

// Formulierdata ophalen
$gebruikernummer = $_SESSION['user_id'];
$schema_naam     = trim($_POST['Schema_naam']);

// Query voorbereiden
$sql = "INSERT INTO Fitnessschema 
        (Schema_naam, Gebruikernr)
        VALUES (?, ?)";

$stmt = $conn->prepare($sql);

// Check op prepare-fout
if (!$stmt) {
    die("Fout bij prepare: " . $conn->error);
}

// Waarden koppelen
$stmt->bind_param("si", $schema_naam, $gebruikernummer);

// Uitvoeren
if ($stmt->execute()) {
    header("Location: overzicht_schemas.php");
    exit;
} else {
    echo "Fout bij registreren: " . $stmt->error;
}

// Opruimen
$stmt->close();
$conn->close();
?>
