<?php
session_start();
include "config.php";

// alleen admin
if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] != "admin") {
    die("Geen toegang");
}

// check POST
if (!isset($_POST['id'])) {
    die("Geen ID");
}

$id = $_POST['id'];

// verwijderen
$sql = "DELETE FROM Profiel WHERE Gebruikernr = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->close();
$conn->close();

// terug
header("Location: users.php");
exit;
?>