<?php
// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logincontrole
require '../../code/login/auth.php';

// Databaseconfig
include 'config.php';
include "../../code/gamification/functie_xp.php";

// Check of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    die("Je bent niet ingelogd.");
}

$gebruikernummer = $_SESSION['user_id'];

// Check of schema is meegestuurd
if (!isset($_POST['Fitness_schemasnr'])) {
    die("Geen schema geselecteerd.");
}

// Gegevens uit POST
$tijdelijk_f_oef_nr = (int) $_POST['Tijdelijk_f_oef_nr'];
$fitness_schema     = (int) $_POST['Fitness_schemasnr'];
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
// Oefening ophalen uit tijdelijke tabel
$stmt = $conn->prepare("
    SELECT tfo.Stanoefnr, so.Type1, so.Type2, so.Oefnaam, so.Kg, so.Aantal
    FROM Tijdelijk_f_oef tfo
    JOIN Standaardoef so ON tfo.Stanoefnr = so.Stanoefnr
    WHERE tfo.Tijdelijk_f_oef_nr = ?
");
$stmt->bind_param("i", $tijdelijk_f_oef_nr);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    // Gegevens van oefening
    $row = $result->fetch_assoc();
    $stanoefnr = $row['Stanoefnr'];
    $Oeftype1  = $row['Type1'];
    $Oeftype2  = $row['Type2'];
    $Oefnaam   = $row['Oefnaam'];
    $Kg        = $row['Kg'];
    $Aantal    = $row['Aantal'];

    // Oefening toevoegen aan Uitgevoerdeoef
    $insert = $conn->prepare("
        INSERT INTO Uitgevoerdeoef 
        (U_Oeftype1, U_Oeftype2, U_Oefnaam, U_Kg, U_Aantal, Gebruikernr)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $insert->bind_param(
        "sssiii",
        $Oeftype1,
        $Oeftype2,
        $Oefnaam,
        $Kg,
        $Aantal,
        $gebruikernummer
    );
    $insert->execute();
    $insert->close();

    // Oefening verwijderen uit tijdelijke tabel
    $del = $conn->prepare("
        DELETE FROM Tijdelijk_f_oef 
        WHERE Tijdelijk_f_oef_nr = ?
    ");
    $del->bind_param("i", $tijdelijk_f_oef_nr);
    $del->execute();
    $del->close();
}

// Opruimen
$stmt->close();
addXP($gebruikernummer, 10, $conn);
$conn->close();

// Automatische POST-redirect
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
