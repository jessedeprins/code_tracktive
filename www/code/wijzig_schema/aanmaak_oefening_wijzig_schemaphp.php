<?php
// Logincontrole
require '../../code/login/auth.php';

// Databaseconfig
include "config.php";

// Debug: POST tonen (handig tijdens ontwikkeling)
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Alleen POST toestaan
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Fout: formulier niet correct verzonden.");
}

// Check op verplichte velden
if (!isset($_POST['Gebruikernr'], $_POST['Type1'], $_POST['Type2'], $_POST['Oefnaam'], $_POST['Kg'], $_POST['Aantal'])) {
    die("Fout: Niet alle vereiste POST variabelen zijn ingevuld.");
}

// Formulierdata ophalen en opschonen
$gebruikernummer = intval($_POST['Gebruikernr']);
$type1           = trim($_POST['Type1']);
$type2           = trim($_POST['Type2']);
$oefnaam         = trim($_POST['Oefnaam']);
$kg              = floatval($_POST['Kg']);
$aantal          = intval($_POST['Aantal']);

// Query voorbereiden
$sql = "INSERT INTO Standaardoef (Type1, Type2, Oefnaam, Kg, Aantal, Gebruikernr)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

// Check op prepare-fout
if (!$stmt) {
    die("Fout bij prepare: " . $conn->error);
}

// Waarden koppelen
$stmt->bind_param("sssdii", $type1, $type2, $oefnaam, $kg, $aantal, $gebruikernummer);

// Uitvoeren
if ($stmt->execute()) {
    header("Location: wijzig_schema.php");
    exit;
} else {
    echo "Fout bij registreren: " . $stmt->error;
}

// Opruimen
$stmt->close();
$conn->close();
?>
