<?php
// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logincontrole
require '../../code/login/auth.php';

// Databaseconfig
include 'config.php';

// Check of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    die("Je bent niet ingelogd.");
}

$gebruikernummer = $_SESSION['user_id'];

// Check of schema is meegestuurd
if (!isset($_POST['Fitness_schemasnr'])) {
    die("Geen schema geselecteerd.");
}

$fitness_schema = (int) $_POST['Fitness_schemasnr'];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Tracktive - Oefeningen</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-white container mt-5">

<h2 class="mb-4"></h2>

<?php
// Oefeningen ophalen die bij het schema horen
$stmt = $conn->prepare("
    SELECT Fitness_schema_oefnr, Fitness_schemasnr, Stanoefnr, Gebruikernr 
    FROM Fitnessschema_oef 
    WHERE Fitness_schemasnr = ?
");
$stmt->bind_param("i", $fitness_schema);
$stmt->execute();
$result = $stmt->get_result();

// Als er oefeningen zijn, tijdelijke tabel verversen
if ($result->num_rows > 0) {

    // Oude tijdelijke oefeningen verwijderen
    $del = $conn->prepare("
        DELETE FROM Tijdelijk_f_oef 
        WHERE Fitness_schemasnr = ? 
        AND Gebruikernr = ?
    ");
    $del->bind_param("ii", $fitness_schema, $gebruikernummer);
    $del->execute();
    $del->close();

    // Nieuwe tijdelijke oefeningen toevoegen
    $insert = $conn->prepare("
        INSERT INTO Tijdelijk_f_oef 
        (Fitness_schema_oefnr, Fitness_schemasnr, Stanoefnr, Gebruikernr)
        VALUES (?, ?, ?, ?)
    ");

    while ($row = $result->fetch_assoc()) {

        $fitness_schema_oefnr = $row['Fitness_schema_oefnr'];
        $stanoefnr            = $row['Stanoefnr'];

        $insert->bind_param(
            "iiii",
            $fitness_schema_oefnr,
            $fitness_schema,
            $stanoefnr,
            $gebruikernummer
        );

        $insert->execute();
    }

    $insert->close();
}

// Opruimen
$stmt->close();
$conn->close();

// Automatische POST-redirect naar overzicht
echo '
<form id="postRedirect" action="toon_tijdelijke_tabel.php" method="POST">
    <input type="hidden" name="Fitness_schemasnr" value="' . htmlspecialchars($fitness_schema, ENT_QUOTES, "UTF-8") . '">
</form>

<script>
    document.getElementById("postRedirect").submit();
</script>
';

exit;
?>

</body>
</html>
