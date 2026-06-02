<?php
// Checkt loginstatus en haalt sessie op
require '../../code/login/auth.php';
include 'config.php';
 
// Als gebruiker niet is ingelogd → terug naar login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

$gebruikernummer = $_SESSION['user_id'];

// ID's ophalen uit POST (veilig gecast naar int)
$id = isset($_POST['Fitness_schema_oefnr']) ? (int)$_POST['Fitness_schema_oefnr'] : 0;
$schema_id = isset($_POST['Fitness_schemasnr']) ? (int)$_POST['Fitness_schemasnr'] : 0;

// Alleen uitvoeren als oefening-ID geldig is
if ($id > 0) {

    // Verwijdert oefening uit schema
    $stmt = $conn->prepare("DELETE FROM Fitnessschema_oef 
                            WHERE Fitness_schema_oefnr = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        // Terugsturen naar het schema dat werd bewerkt
        $stmt->close();
        $conn->close();
        ?>

        <form id="backForm" method="post" action="wijzig_schema.php">
            <input type="hidden" name="Fitness_schemasnr" value="<?php echo $schema_id; ?>">
        </form>

        <script>
            // Automatisch terug naar het schema
            document.getElementById("backForm").submit();
        </script>

        <?php
        exit;

    } else {
        // DB-foutmelding
        echo "Fout bij verwijderen.";
    }

} else {
    // Geen geldig ID ontvangen
    echo "Ongeldig ID.";
}

// DB sluiten
$conn->close();
?>
