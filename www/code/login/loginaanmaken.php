<?php
session_start();
$ingelogd = isset($_SESSION['user_id']);
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
    <title>Registratie – Tracktive</title>
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

<!-- REGISTRATIE CONTAINER -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">

            <div class="card bg-secondary text-white p-4 shadow-sm">
                <h3 class="text-center mb-4">Registratie</h3>

                <form action="loginaanmakenphp.php" method="post">

                    <p class="mb-1">E-mailadres</p>
                    <div class="mb-3">
                        <input type="email" name="email_adress" class="form-control"
                               placeholder="E-mailadres" required>
                    </div>

                    <p class="mb-1">Voornaam</p>
                    <div class="mb-3">
                        <input type="text" name="Voornaam" class="form-control"
                               placeholder="Voornaam" required>
                    </div>

                    <p class="mb-1">Familienaam</p>
                    <div class="mb-3">
                        <input type="text" name="Familienaam" class="form-control"
                               placeholder="Familienaam" required>
                    </div>

                    <p class="mb-1">Geboortedatum</p>
                    <div class="mb-3">
                        <input type="date" name="Geboortedatum" class="form-control" required>
                    </div>

                    <p class="mb-1">Geslacht</p>
                    <div class="mb-3">
                        <select name="Geslacht" class="form-select" required>
                            <option value="Man">Man</option>
                            <option value="Vrouw">Vrouw</option>
                            <option value="Anders">X</option>
                        </select>
                    </div>

                    <p class="mb-1">Paswoord</p>
                    <div class="mb-3">
                        <input type="password" name="Wachtwoord" class="form-control"
                               placeholder="Paswoord" required>
                    </div>

                    <button type="submit" class="btn btn-light fw-semibold w-100">
                        Registreren <i class="bi bi-arrow-right-circle ms-1"></i>
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>

<footer class="text-center py-3 mt-4">
    <p class="m-0">© Tracktive</p>
</footer>

</body>
</html>
