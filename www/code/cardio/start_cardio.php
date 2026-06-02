<?php
// Logincontrole
require '../../code/login/auth.php';
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
    <!-- Hoofdsectie -->
    <section class="container py-5">
        <h2 class="text-center fw-light mb-4">Kies uw activiteit</h2>

        <div class="row g-4 justify-content-center">

            <!-- Lopen -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-secondary text-white border-0 shadow-sm h-100">
                    <div class="card-body text-center d-flex flex-column">

                        <div class="display-4 mb-3">
                            <i class="bi bi-person-walking"></i>
                        </div>

                        <h5 class="fw-semibold">Start met lopen</h5>
                        <p class="flex-grow-1">Verbeter uw conditie met lopen.</p>

                        <a href="../cardio/lopen.php" class="btn btn-light fw-semibold">
                            Start <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>

                    </div>
                </div>
            </div>

            <!-- Fietsen -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-secondary text-white border-0 shadow-sm h-100">
                    <div class="card-body text-center d-flex flex-column">

                        <div class="display-4 mb-3">
                            <i class="bi bi-bicycle"></i>
                        </div>

                        <h5 class="fw-semibold">Start met fietsen</h5>
                        <p class="flex-grow-1">Verbeter uw conditie met fietsen.</p>

                        <a href="../cardio/fietsen.php" class="btn btn-light fw-semibold">
                            Start <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-3 mt-4 opacity-75">
        <p class="m-0">© Tracktive</p>
    </footer>
</body>
</html>
