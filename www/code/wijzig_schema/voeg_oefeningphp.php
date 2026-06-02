<?php
// Logincontrole + user ophalen
require '../../code/login/auth.php';
include "config.php";

$gebruikernummer = $_SESSION['user_id'];

// IDs ophalen uit POST
$fitness_schemasnr = $_POST['Fitness_schemasnr'];
$stanoefnr = $_POST['Stanoefnr'];

// Nieuwe oefening koppelen aan schema
$sql = "INSERT INTO Fitnessschema_oef
        (Fitness_schemasnr, Stanoefnr, Gebruikernr)
        VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    // Fout bij voorbereiden query
    die("Fout bij prepare: " . $conn->error);
} 

$stmt->bind_param("iii", 
    $fitness_schemasnr, 
    $stanoefnr, 
    $gebruikernummer 
);

// Uitvoeren + foutmelding indien mislukt
if (!$stmt->execute()) {
    echo "Fout bij updaten: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<!-- Automatisch terug naar de oefening-lijst -->
<form id="autoForm" action="voeg_oefening.php" method="post">
    <input type="hidden" name="Fitness_schemasnr" value="<?php echo htmlspecialchars($fitness_schemasnr); ?>">
    <input type="submit" style="display:none;">
</form>

<script>
    // Form automatisch submitten
    document.getElementById('autoForm').submit();
</script>
