<?php

// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logincontrole
require '../../code/login/auth.php';
$ingelogd = isset($_SESSION['user_id']);

// Databaseconfig
include 'config.php';
include "../../navbar.php";
// Check of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    die("Je bent niet ingelogd.");
}

$gebruikernummer = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Tracktive - Overzicht schema's</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        html { overflow-y: scroll; }
    </style>
</head>

<body class="bg-dark text-white">



<div class="container py-4">

    <!-- TITEL + GROENE KNOP RECHTS -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">Overzicht schema's</h2>

        <a href="/code/fitness/vrije_training.php" class="btn btn-success fw-semibold">
            Vrije training <i class="bi bi-lightning-charge-fill ms-1"></i>
        </a>
    </div>

    <?php
    // Schema's ophalen
    $stmt = $conn->prepare("
        SELECT Fitness_schemasnr, Schema_naam 
        FROM Fitnessschema 
        WHERE Gebruikernr = ?
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
                        <th>Schema naam</th>
                        <th class='text-center'>Openen</th>
                        <th class='text-center'>Wijzigen</th>
                    </tr>
                </thead>
                <tbody>
        ";

        while ($row = $result->fetch_assoc()) {
            echo "
            <tr>
                <td>" . htmlspecialchars($row["Schema_naam"]) . "</td>

                <td class='text-center'>
                    <form method='post' action='vul_tijdelijke_tabel.php' class='d-inline'>
                        <input type='hidden' name='Fitness_schemasnr' value='" . htmlspecialchars($row['Fitness_schemasnr']) . "'>
                        <button type='submit' class='btn btn-outline-success btn-sm'>
                            Openen
                        </button>
                    </form>
                </td>

                <td class='text-center'>
                    <form method='post' action='../wijzig_schema/wijzig_schema.php' class='d-inline'>
                        <input type='hidden' name='Fitness_schemasnr' value='" . htmlspecialchars($row['Fitness_schemasnr']) . "'>
                        <button type='submit' class='btn btn-outline-primary btn-sm'>
                            Wijzigen
                        </button>
                    </form>
                </td>
            </tr>
            ";
        }

        echo "</tbody></table></div>";

    } else {
        echo "<div class='alert alert-warning'>Er zijn nog geen schema's aangemaakt.</div>";
    }

    $stmt->close();
    $conn->close();
    ?>
    <form action='../../code/wijzig_schema/overzicht_schemas.php' method='post'>
        <button type='submit' class='btn btn-primary mt-3'>
            Schema toevoegen
        </button>
    </form>
</div>

</body>
</html>
