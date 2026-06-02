<?php
// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logincontrole
require '../../code/login/auth.php';
include "../../navbar.php";

// Databaseconfig
include '../../code/login/config.php';

// Check of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) { 
    die("Je bent niet ingelogd.");
}

$gebruikernummer = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<?php
$ingelogd = isset($_SESSION['user_id']);
?>
<style>
    html {
        overflow-y: scroll;
    }
</style>


<head>   
    <!-- voegt speciale tekens toe aan de woordenschat -->
    <meta charset="UTF-8">
    <!-- zorgt dat alles deftig werkt op gsm -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracktive</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-dark text-white">
<div class="container py-4">

    <h2 class="fw-bold mb-4">Overzicht oefeningen</h2>

    <?php
    // Oefeningen ophalen
    $stmt = $conn->prepare("
        SELECT U_Oeftype1, U_Oeftype2, U_Oefnaam, U_Kg, U_Aantal, U_Tijd_min, U_Afstand, U_Datum
        FROM Uitgevoerdeoef
        WHERE Gebruikernr = ?
        ORDER BY U_Datum DESC
    ");

    $stmt->bind_param("i", $gebruikernummer);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        echo "
        <div class='table-responsive'>
            <table class='table table-dark table-striped table-hover align-middle'>
                <thead class='table-light text-dark'>
                    <tr>
                        <th>Type1</th>
                        <th>Type2</th>
                        <th>Naam</th>
                        <th>Kg</th>
                        <th>Aantal</th>
                        <th>Tijd (min)</th>
                        <th>Afstand</th>
                        <th>Datum</th>
                    </tr>
                </thead>
                <tbody>";
        
        // Rijen tonen
    while ($row = $result->fetch_assoc()) {

        echo "
        <tr>
            <td>".htmlspecialchars($row["U_Oeftype1"] ?? "/")."</td>
            <td>".htmlspecialchars($row["U_Oeftype2"] ?? "/")."</td>
            <td>".htmlspecialchars($row["U_Oefnaam"] ?? "/")."</td>
            <td>".htmlspecialchars($row["U_Kg"] ?? "/")."</td>
            <td>".htmlspecialchars($row["U_Aantal"] ?? "/")."</td>
            <td>".htmlspecialchars($row["U_Tijd_min"] ?? "/")."</td>
            <td>".htmlspecialchars($row["U_Afstand"] ?? "/")."</td>
            <td>".htmlspecialchars($row["U_Datum"] ?? "/")."</td>
        </tr>";
    }


        echo "</tbody></table></div>";

    } else {
        echo "<div class='alert alert-warning'>Geen oefeningen gevonden.</div>";
    }

    $stmt->close();
    $conn->close();
    ?>

</div>

</body>
</html>
