<?php
session_start();

// beveiliging
if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] != "admin") {
    die("Geen toegang");
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracktive</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        html {
            overflow-y: scroll;
        }
    </style>
</head>

<body class="bg-dark text-white">

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark bg-black shadow-sm p-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-semibold" href="/index.php">Tracktive</a>

            <div class="dropdown">
                <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center"
                        type="button" id="profielDropdown" data-bs-toggle="dropdown"
                        style="width: 60px; height: 60px;">
                    <i class="bi bi-person fs-1 text-dark"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profielDropdown">
                    <li><a class="dropdown-item" href="logout.php">Loguit</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HEADER -->
    <header class="container text-center py-5">
        <h1 class="fw-bold">Admin Dashboard</h1>
        <p class="lead">Welkom <?php echo htmlspecialchars($_SESSION['naam']); ?> 👋</p>
    </header>

    <!-- CONTENT -->
    <section class="container py-4">
        <div class="row g-4 justify-content-center">

            <!-- USERS BEHEREN -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-secondary text-white h-100 shadow-sm">
                    <div class="card-body text-center d-flex flex-column">
                        <div class="display-4 mb-3"><i class="bi bi-people-fill"></i></div>
                        <h5 class="card-title">Gebruikers beheren</h5>
                        <p class="card-text flex-grow-1">Bekijk en beheer alle gebruikers.</p>
                        <a href="users.php" class="btn btn-light fw-semibold">
                            Openen <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center py-3 mt-4">
        <p class="m-0">© Tracktive</p>
    </footer>

</body>
</html>
