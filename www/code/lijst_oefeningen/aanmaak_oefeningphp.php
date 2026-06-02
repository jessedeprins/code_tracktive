<?php
// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logincontrole
require '../../code/login/auth.php';

// Databaseconfig
include "config.php";

// Check op verplichte velden
if (
    !isset($_POST['Gebruikernr']) ||
    !isset($_POST['Type1']) ||
    !isset($_POST['Type2']) ||
    !isset($_POST['Oefnaam']) ||
    !isset($_POST['Kg']) ||
    !isset($_POST['Aantal'])
) {
    die("Fout: Niet alle vereiste POST variabelen zijn ingevuld.");
}

// Formulierdata ophalen
$gebruikernummer = $_POST['Gebruikernr'];
$type1           = $_POST['Type1'];
$type2           = $_POST['Type2'];
$oefnaam         = $_POST['Oefnaam'];
$kg              = $_POST['Kg'];
$aantal          = $_POST['Aantal'];

// Query voorbereiden
$sql = "INSERT INTO Standaardoef 
        (Type1, Type2, Oefnaam, Kg, Aantal, Gebruikernr)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

// Check op prepare-fout
if (!$stmt) {
    die("Fout bij prepare: " . $conn->error);
}

// Waarden koppelen
$stmt->bind_param("sssdii", 
    $type1, 
    $type2, 
    $oefnaam, 
    $kg, 
    $aantal, 
    $gebruikernummer
);

// Uitvoeren
if ($stmt->execute()) {
    header("Location: overzicht_oefeningen.php");
    exit;
} else {
    echo "Fout bij registreren.";
}

$stmt->close();
$conn->close();
?>
