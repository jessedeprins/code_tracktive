<?php
session_start();
include "config.php";

// alleen admin
if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] != "admin") {
    die("Geen toegang");
}

$sql = "SELECT * FROM Profiel";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracktive | Users</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        html { overflow-y: scroll; }
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
                    <li><a class="dropdown-item" href="admin.php">Admin Dashboard</a></li>
                    <li><a class="dropdown-item" href="logout.php">Loguit</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HEADER -->
    <header class="container text-center py-5">
        <h1 class="fw-bold">Gebruikersbeheer</h1>
        <p class="lead">Overzicht van alle geregistreerde gebruikers.</p>
    </header>

    <!-- USERS LIST -->
    <div class="container pb-5">
        <div class="row g-4">

            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card bg-secondary text-white shadow-sm h-100">
                        <div class="card-body d-flex flex-column">

                            <h5 class="card-title mb-3">
                                <i class="bi bi-person-fill me-2"></i>
                                <?php echo htmlspecialchars($row['Voornaam']); ?>
                            </h5>

                            <p class="mb-1"><strong>ID:</strong> <?php echo $row['Gebruikernr']; ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($row['email_adress']); ?></p>
                            <p class="mb-3"><strong>Rol:</strong> <?php echo htmlspecialchars($row['Rol']); ?></p>

                            <form action="delete_user.php" method="POST"
                                  onsubmit="return confirm('Zeker verwijderen?')"
                                  class="mt-auto">
                                <input type="hidden" name="id" value="<?php echo $row['Gebruikernr']; ?>">
                                <button type="submit" class="btn btn-danger w-100 fw-semibold">
                                    <i class="bi bi-trash-fill me-1"></i> Verwijder
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

        </div>
    </div>

    <footer class="text-center py-3 mt-4">
        <p class="m-0">© Tracktive</p>
    </footer>

</body>
</html>

<?php $conn->close(); ?>
