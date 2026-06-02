<?php
// Fouten tonen tijdens maken website
error_reporting(E_ALL);
ini_set('display_errors', 1);

// checkt of je bent ingelogd
require '../../code/login/auth.php';

// Databaseconfig
include "config.php";

// Checkt dat verplichte velden zijn ingevuld
if (
    !isset($_POST['Gebruikernr']) ||
    !isset($_POST['Gewicht']) ||
    !isset($_POST['Omtrek_borst']) ||
    !isset($_POST['Omtrek_buik']) ||
    !isset($_POST['Omtrek_biceps']) ||
    !isset($_POST['Omtrek_been'])
) {
    die("Fout: Niet alle vereiste POST variabelen zijn ingevuld.");
}

// Formulierdata ophalen
$gebruikernr      = $_POST['Gebruikernr'];
$gewicht          = $_POST['Gewicht'];
$omtrek_borst     = $_POST['Omtrek_borst'];
$omtrek_buik      = $_POST['Omtrek_buik'];
$omtrek_biceps    = $_POST['Omtrek_biceps'];
$omtrek_been      = $_POST['Omtrek_been'];

// info toevoegen in lichaamsmetingen tabel
$sql = "INSERT INTO Lichaamsmetingen
        (Gebruikernr, Gewicht, Omtrek_borst, Omtrek_buik, Omtrek_biceps, Omtrek_been)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

// Check op prepare-fout
if (!$stmt) {
    die("Fout bij prepare: " . $conn->error);
}

// Waarden koppelen
$stmt->bind_param(
    "iddddd",
    $gebruikernr,
    $gewicht,
    $omtrek_borst,
    $omtrek_buik,
    $omtrek_biceps,
    $omtrek_been
);

// Uitvoeren
if ($stmt->execute()) {
    header("Location: ../../index.php");
    exit();
} else {
    echo "Fout bij registreren van lichaamsmeting.";
}

$stmt->close();
$conn->close();
?>
