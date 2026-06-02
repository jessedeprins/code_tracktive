<?php
// Fouten tonen tijdens ontwikkeling
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Logincontrole
require '../../code/login/auth.php';

// Databaseconfig
include 'config.php';

// Check of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

$gebruikernummer = $_SESSION['user_id'];

// Oefening-ID ophalen
$id = isset($_POST['Stanoefnr']) ? (int) $_POST['Stanoefnr'] : 0;

if ($id > 0) {

    // Verwijder gekoppelde tijdelijke oefeningen
    $stmt = $conn->prepare("
        DELETE FROM Tijdelijk_f_oef 
        WHERE Fitness_schema_oefnr IN (
            SELECT Fitness_schema_oefnr 
            FROM Fitnessschema_oef 
            WHERE Stanoefnr = ?
        )
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Verwijder koppelingen in Fitnessschema_oef
    $stmt = $conn->prepare("DELETE FROM Fitnessschema_oef WHERE Stanoefnr = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Verwijder oefening zelf
    $stmt = $conn->prepare("
        DELETE FROM Standaardoef 
        WHERE Stanoefnr = ? 
        AND Gebruikernr = ?
    ");
    $stmt->bind_param("ii", $id, $gebruikernummer);

    if ($stmt->execute()) {
        // Alles OK → terug naar overzicht
        $stmt->close();
        $conn->close();
        header("Location: overzicht_oefeningen.php");
        exit;
    } else {
        echo "Fout bij verwijderen: " . $stmt->error;
    }

} else {
    echo "Ongeldig ID.";
}

// Verbinding sluiten
$conn->close();
?>
