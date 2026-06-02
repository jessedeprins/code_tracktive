<?php
// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Databaseconfig
include "config.php";

// Gegevens uit formulier
$email         = $_POST['email_adress'];
$voornaam      = $_POST['Voornaam'];
$familienaam   = $_POST['Familienaam'];
$geboortedatum = $_POST['Geboortedatum'];
$geslacht      = $_POST['Geslacht'];
$paswoord      = password_hash($_POST['Wachtwoord'], PASSWORD_DEFAULT);

// Check of e-mailadres al bestaat
$check_sql = "SELECT 1 FROM Profiel WHERE email_adress = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo "Dit e-mailadres is al geregistreerd!";
    $check_stmt->close();
    $conn->close();
    exit;
}

$check_stmt->close();

// Nieuwe gebruiker toevoegen
$sql = "INSERT INTO Profiel
        (email_adress, Voornaam, Familienaam, Geboortedatum, Geslacht, Wachtwoord)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssss",
    $email,
    $voornaam,
    $familienaam,
    $geboortedatum,
    $geslacht,
    $paswoord
);

// Uitvoeren
if ($stmt->execute()) {
    header("Location: /index.php");
    exit;
} else {
    echo "Fout bij registreren.";
}

$stmt->close();
$conn->close();
?>
