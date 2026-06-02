<?php
// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_POST['email_adress'], $_POST['Wachtwoord'])) {
    die("Geen data ontvangen");
}

include "config.php";
session_start();

// Gegevens uit formulier
$email = $_POST['email_adress'];
$password = $_POST['Wachtwoord'];

// Gebruiker ophalen op basis van e-mail
$sql = "SELECT * FROM Profiel WHERE email_adress = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// checkt of gebruiker bestaat
if ($result->num_rows === 1) {

    $user = $result->fetch_assoc();

    // Wachtwoord controleren
    if (password_verify($password, $user['Wachtwoord'])) {

        // Sessie vullen
        $_SESSION['user_id'] = $user['Gebruikernr'];
        $_SESSION['naam']    = $user['Voornaam'];
        $_SESSION['email']   = $user['email_adress'];
        $_SESSION['Rol']    = $user['Rol']; // 👈 BELANGRIJK

        // Doorsturen naar home
        header("Location: ../../index.php");
        exit;
    }
}

// Foutmelding bij mislukte login
header("Location: ../../code/login/login.php");

$stmt->close();
$conn->close();
?>