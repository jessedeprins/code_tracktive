<?php
// Logincontrole + user ophalen
require '../../code/login/auth.php'; 
include "config.php";

// Formulier moet via POST komen
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Fout: formulier niet correct verzonden.");
}

// Checkt of er überhaupt oefeningen zijn meegestuurd
if (!isset($_POST['Type2'])) {
    die("Fout: Geen oefeningen ontvangen.");
}

$gebruikernummer = $_SESSION['user_id'];

// Loopt door alle oefeningen die aangepast werden
foreach ($_POST['Type2'] as $id => $type) {

    $id = (int)$id; // Veilig casten
    $oefnaam = $_POST['Oefnaam'][$id];
    $kg = $_POST['Kg'][$id];
    $aantal = $_POST['Aantal'][$id];

    // Update oefening van deze gebruiker
    $sql = "UPDATE Standaardoef 
            SET Type2 = ?, Oefnaam = ?, Kg = ?, Aantal = ?
            WHERE Stanoefnr = ? AND Gebruikernr = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Fout bij prepare: " . $conn->error);
    }

    $stmt->bind_param("ssdiii", 
        $type, 
        $oefnaam, 
        $kg, 
        $aantal, 
        $id, 
        $gebruikernummer
    );

    // Uitvoeren + foutmelding indien mislukt
    if (!$stmt->execute()) {
        echo "Fout bij updaten: " . $stmt->error;
    }

    $stmt->close();
}

// Terug naar overzicht
header("Location: overzicht_schemas.php");
exit;

$conn->close();
