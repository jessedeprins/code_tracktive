<?php

include 'config.php';

// CHECK
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Ongeldige aanvraag");
}

if (empty($_POST['token']) || empty($_POST['Wachtwoord'])) {
    die("Ontbrekende gegevens");
}

$token = trim($_POST['token']);
$wachtwoord = trim($_POST['Wachtwoord']);

// MINIMUM CHECK
if (strlen($wachtwoord) < 8) {
    die("Minstens 8 karakters vereist");
}

// HASH
$passwordHash = password_hash($wachtwoord, PASSWORD_DEFAULT);

// UPDATE
$stmt = $conn->prepare("
UPDATE Profiel
SET Wachtwoord = ?,
    reset_token = NULL,
    reset_expires = NULL
WHERE reset_token = ?
");

$stmt->bind_param("ss", $passwordHash, $token);

if ($stmt->execute()) {

    echo "Wachtwoord gewijzigd! Je kan opnieuw inloggen.";

} else {

    echo "Fout bij updaten.";
}
?>