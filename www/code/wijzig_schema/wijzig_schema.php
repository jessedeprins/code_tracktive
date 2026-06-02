<?php
// Logincontrole + user ophalen
require '../../code/login/auth.php';
$gebruikernummer = $_SESSION['user_id'];

// DB-config laden
include 'config.php';

// Schema-ID ophalen (veilig naar int)
$nr = isset($_POST['Fitness_schemasnr']) ? intval($_POST['Fitness_schemasnr']) : 0;
include "../../navbar.php";
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
    // Haalt alle oefeningen op die aan dit schema gekoppeld zijn
    $sql = "SELECT fso.Fitness_schema_oefnr, so.Type2, so.Oefnaam, so.Kg, so.Aantal
            FROM Fitnessschema_oef fso
            JOIN Standaardoef so ON fso.Stanoefnr = so.Stanoefnr
            WHERE fso.Fitness_schemasnr = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $nr);
    $stmt->execute();
    $result = $stmt->get_result();

    // Checkt of er oefeningen bestaan
    if ($result->num_rows > 0) {

        echo "<form method='post'>";
        echo "<input type='hidden' name='Fitness_schemasnr' value='".htmlspecialchars($nr)."'>";

        echo "
        <div class='table-responsive'>
            <table class='table table-dark table-striped table-hover align-middle'>
                <thead class='table-light text-dark'>
                    <tr>
                        <th>Type</th>
                        <th>Naam</th>
                        <th>Kg</th>
                        <th>#</th>
                        <th class='text-center'>Verwijderen</th>
                    </tr>
                </thead>
                <tbody>";

        // Toont elke oefening in het schema
        while($row = $result->fetch_assoc()) {

            echo "
            <tr>
                <td>".htmlspecialchars($row['Type2'])."</td>
                <td>".htmlspecialchars($row['Oefnaam'])."</td>
                <td>".htmlspecialchars($row['Kg'])."</td>
                <td>".htmlspecialchars($row['Aantal'])."</td>

                <!-- Verwijderknop voor deze oefening -->
                <td class='text-center'>
                    <button type='submit'
                            name='Fitness_schema_oefnr'
                            value='".$row['Fitness_schema_oefnr']."'
                            formaction='verwijderen_oefening_wijzig_schema.php'
                            class='btn btn-outline-danger btn-sm'>
                        Verwijderen
                    </button>
                </td>
            </tr>";
        }

        echo "</tbody></table></div>";
        echo "</form>";

    } else {
        // Geen oefeningen gekoppeld aan dit schema
        echo "<div class='alert alert-warning'>Geen oefeningen gevonden.</div>";
    }

    // DB sluiten
    $conn->close();
    ?>

    <!-- Knop om nieuwe oefening toe te voegen -->
    <form action='voeg_oefening.php' method='post'>
        <input type='hidden' name='Fitness_schemasnr' value='<?= htmlspecialchars($nr) ?>'>
        <button type='submit' class='btn btn-primary mt-3'>
            Oefening toevoegen
        </button>
    </form>

</div>

</body>
</html>
