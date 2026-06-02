<?php
// Logincontrole + user ophalen
require '../../code/login/auth.php';
$gebruikernummer = $_SESSION['user_id'];

// DB-config laden
include 'config.php';
include "../../navbar.php";
?>
<!DOCTYPE html>
<html lang="en">

<?php
$ingelogd = isset($_SESSION['user_id']);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracktive</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        html { overflow-y: scroll; }
    </style>
</head>

<body class="bg-dark text-white">


<div class="container py-4">

    <h2 class="fw-bold mb-4">Oefeningen toevoegen aan schema</h2>

    <?php
    // Haalt alle standaard-oefeningen van deze gebruiker op
    $sql = "SELECT Stanoefnr, Type1, Type2, Oefnaam, Kg, Aantal 
            FROM Standaardoef 
            WHERE Gebruikernr = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $gebruikernummer);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0):
    ?>

        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover align-middle">
                <thead class="table-light text-dark">
                    <tr>
                        <th>Type1</th>
                        <th>Type2</th>
                        <th>Naam</th>
                        <th>Kg</th>
                        <th>#</th>
                        <th class="text-center">+</th>
                    </tr>
                </thead>
                <tbody>

                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Type1']) ?></td>
                        <td><?= htmlspecialchars($row['Type2']) ?></td>
                        <td><?= htmlspecialchars($row['Oefnaam']) ?></td>
                        <td><?= htmlspecialchars($row['Kg']) ?></td>
                        <td><?= htmlspecialchars($row['Aantal']) ?></td>

                        <td class="text-center">

                            <!-- Elke rij heeft zijn eigen form -->
                            <form method="post" action="vrije_trainingphp.php">

                                <input type="hidden" name="Stanoefnr" value="<?= $row['Stanoefnr'] ?>">
                                <input type="hidden" name="U_Oefnaam" value="<?= htmlspecialchars($row['Oefnaam']) ?>">
                                <input type="hidden" name="U_Oeftype1" value="<?= htmlspecialchars($row['Type1']) ?>">
                                <input type="hidden" name="U_Oeftype2" value="<?= htmlspecialchars($row['Type2']) ?>">
                                <input type="hidden" name="U_Kg" value="<?= htmlspecialchars($row['Kg']) ?>">
                                <input type="hidden" name="U_Aantal" value="<?= htmlspecialchars($row['Aantal']) ?>">

                                <button type="submit" class="btn btn-outline-success btn-sm">+</button>
                            </form>

                        </td>
                    </tr>
                <?php endwhile; ?>

                </tbody>
            </table>
        </div>

    <?php
    else:
        echo "<div class='alert alert-warning'>Geen oefeningen gevonden.</div>";
    endif;

    $conn->close();
    ?>
    <form action='../../code/lijst_oefeningen/overzicht_oefeningen.php' method='post'>
        <input type='hidden' name='Fitness_schemasnr' value='<?= htmlspecialchars($nr) ?>'>
        <button type='submit' class='btn btn-primary mt-3'>
            Oefening toevoegen
        </button>
    </form>
</div>

</body>
</html>
