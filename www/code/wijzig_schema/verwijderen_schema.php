<?php
// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logincontrole + DB-config
require '../../code/login/auth.php';
include 'config.php'; 

// Gebruiker moet ingelogd zijn
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

$gebruikernummer = $_SESSION['user_id'];

// Schema-ID ophalen (veilig gecast)
$id = isset($_POST['Fitness_schemasnr']) ? (int) $_POST['Fitness_schemasnr'] : 0;

// Alleen doorgaan als ID geldig is
if ($id > 0) {

    // Tijdelijke tabel leegmaken (reset)
    $conn->query("DELETE FROM Tijdelijk_f_oef");

    // Verwijdert alle oefeningen die bij dit schema horen
    $stmt1 = $conn->prepare("DELETE FROM Fitnessschema_oef WHERE Fitness_schemasnr = ?");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();
    $stmt1->close();

    // Verwijdert het schema zelf (alleen van deze gebruiker)
    $stmt2 = $conn->prepare("
        DELETE FROM Fitnessschema 
        WHERE Fitness_schemasnr = ? 
        AND Gebruikernr = ?
    ");
    $stmt2->bind_param("ii", $id, $gebruikernummer);

    // Bij succes terug naar overzicht
    if ($stmt2->execute()) {
        header("Location: overzicht_schemas.php");
        exit;
    } else {
        echo "Fout bij verwijderen.";
    }

} else {
    // Geen geldig schema-ID ontvangen
    echo "Ongeldig ID.";
}

// DB sluiten
$conn->close();
?>
