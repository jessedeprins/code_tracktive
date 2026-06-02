<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$ingelogd = isset($_SESSION['user_id']);
include "config.php";

?>
<!DOCTYPE html>
<html lang="nl">
<style>
    html {
        overflow-y: scroll;
    }
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inloggen – Tracktive</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-dark text-white">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-black shadow-sm p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand fw-semibold" href="../../index.php">Tracktive</a>

        <div class="dropdown">
            <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center"
                    type="button" id="profielDropdown" data-bs-toggle="dropdown"
                    style="width: 60px; height: 60px;">
                <i class="bi bi-person fs-1 text-dark"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profielDropdown">
                <?php if ($ingelogd): ?>
                    <li><a class="dropdown-item" href="../../code/login/logout.php">Loguit</a></li>
                <?php else: ?>
                    <li><a class="dropdown-item" href="../../code/login/login.php">Login</a></li>
                    <li><a class="dropdown-item" href="../../code/login/loginaanmaken.php">Registreer</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- LOGIN CONTAINER -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">

            <div class="card bg-secondary text-white p-4 shadow-sm">
                <h3 class="text-center mb-4">Inloggen</h3>

                <!-- FOUTMELDING -->
                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['login_error']; ?>
                    </div>
                    <?php unset($_SESSION['login_error']); ?>
                <?php endif; ?>

                <form action="login_verwerken.php" method="post">

                    <p class="mb-1">E-mailadres</p>
                    <div class="mb-3">
                        <input type="email" name="email_adress" class="form-control"
                               placeholder="E-mailadres" required>
                    </div>

                    <p class="mb-1">Paswoord</p>
                    <div class="mb-3">
                        <input type="password" name="Wachtwoord" class="form-control"
                               placeholder="Paswoord" required>
                    </div>

                    <button type="submit" class="btn btn-light fw-semibold w-100">
                        Login <i class="bi bi-arrow-right-circle ms-1"></i>
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="wachtwoord_vergeten.php" class="text-white">Wachtwoord vergeten?</a>
                    <a href="loginaanmaken.php" class="btn btn-link text-white">Maak een nieuw account aan</a>
                </div>

            </div>

        </div>
    </div>
</div>

<footer class="text-center py-3 mt-4">
    <p class="m-0">© Tracktive</p>
</footer>

</body>
</html>
