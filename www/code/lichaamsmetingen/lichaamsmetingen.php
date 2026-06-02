<?php
// Logincontrole
require '../../code/login/auth.php';

// Gebruikersnummer uit sessie
$Gebruikernr = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<?php
$ingelogd = isset($_SESSION['user_id']);
include "../../navbar.php";
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
<!-- TITEL -->
<header class="container text-center py-5">
    <h1 class="fw-bold">Lichaamsmeting registreren</h1>
    <p class="lead">Vul hieronder je metingen in.</p>
</header>

<!-- FORMULIER -->
<div class="container pb-5">

    <div class="mx-auto" style="max-width: 900px;">

        <div class="card bg-secondary text-light shadow-lg p-4">

            <h2 class="text-center mb-4 fw-bold">Nieuwe meting</h2>

            <form action="lichaamsmetingenphp.php" method="post">

                <!-- Gebruikersnummer meesturen -->
                <input type="hidden" name="Gebruikernr" value="<?= htmlspecialchars($Gebruikernr) ?>">

                <div class="row g-4">

                    <!-- Gewicht -->
                    <div class="col-md-6">
                        <label class="form-label">Gewicht (kg)</label>
                        <input type="number" name="Gewicht" class="form-control form-control-lg bg-dark text-light border-light" required>
                    </div>

                    <!-- Omtrek borst -->
                    <div class="col-md-6">
                        <label class="form-label">Omtrek borst (cm)</label>
                        <input type="number" name="Omtrek_borst" class="form-control form-control-lg bg-dark text-light border-light" required>
                    </div>

                    <!-- Omtrek buik -->
                    <div class="col-md-6">
                        <label class="form-label">Omtrek buik (cm)</label>
                        <input type="number" name="Omtrek_buik" class="form-control form-control-lg bg-dark text-light border-light" required>
                    </div>

                    <!-- Omtrek biceps -->
                    <div class="col-md-6">
                        <label class="form-label">Omtrek biceps (cm)</label>
                        <input type="number" name="Omtrek_biceps" class="form-control form-control-lg bg-dark text-light border-light" required>
                    </div>

                    <!-- Omtrek been -->
                    <div class="col-md-6">
                        <label class="form-label">Omtrek been (cm)</label>
                        <input type="number" name="Omtrek_been" class="form-control form-control-lg bg-dark text-light border-light" required>
                    </div>

                </div>

                <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                        Opslaan
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

<!-- FOOTER -->
<footer class="text-center py-3 mt-4">
    <p class="m-0">© Tracktive</p>
</footer>

</body>
</html>
