<?php
// checkt of ie is ingelogd
require '../../code/login/auth.php';
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
    <link rel="icon" type="image/png" href="/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracktive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-dark text-white">
<!-- TITEL -->
<header class="container text-center py-5">
    <h1 class="fw-bold">Cardiotraining registreren</h1>
    <p class="lead">Vul hieronder je gegevens in.</p>
</header>

<!-- FORMULIER -->
<div class="container pb-5">

    <div class="mx-auto" style="max-width: 900px;">

        <div class="card bg-secondary text-light shadow-lg p-4">

            <h2 class="text-center mb-4 fw-bold">Oefening registreren</h2>

            <form action="lopenphp.php" method="post">

                <!-- Gebruikersnummer meesturen -->
                <input type="hidden" name="gebruikernummer" value="<?= htmlspecialchars($Gebruikernr) ?>">

                <div class="row g-4">

                    <!-- Type training -->
                    <div class="col-md-6">
                        <label class="form-label">Soort training</label>
                        <select name="type1" class="form-select form-select-lg bg-dark text-light border-light">
                            <option value="cardio" selected>Cardio</option>
                        </select>
                    </div>

                    <!-- Type oefening -->
                    <div class="col-md-6">
                        <label class="form-label">Soort oefening</label>
                        <select name="type2" class="form-select form-select-lg bg-dark text-light border-light">
                            <option value="lopen" selected>Lopen</option>
                        </select>
                    </div>

                    <!-- Tijd -->
                    <div class="col-md-6">
                        <label class="form-label">Tijd (minuten)</label>
                        <input type="number" name="tijd" class="form-control form-control-lg bg-dark text-light border-light" required>
                    </div>

                    <!-- Afstand -->
                    <div class="col-md-6">
                        <label class="form-label">Afstand (m)</label>
                        <input type="number" name="afstand" class="form-control form-control-lg bg-dark text-light border-light" required>
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
