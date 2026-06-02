<?php
session_start();
$ingelogd = isset($_SESSION['user_id']);
include "navbar.php";
if ($ingelogd) {
    include "code/login/config.php";
    

    $sql = "SELECT xp, `rank`, Voornaam FROM Profiel WHERE Gebruikernr = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $xp = $result['xp'] ?? 0;
    $rank = $result['rank'] ?? 1;
    $voornaam = $result['Voornaam'] ?? null;

    $rankThresholds = [
        1 => 0,
        2 => 100,
        3 => 300,
        4 => 500,
        5 => 1000,
        6 => 2000,
        7 => 3500,
        8 => 5000
    ];

    $currentThreshold = $rankThresholds[$rank];
    $nextThreshold = $rankThresholds[$rank + 1] ?? $currentThreshold;
    $xpInRank = $xp - $currentThreshold;
    $xpNeeded = $nextThreshold - $currentThreshold;
    $percentage = $xpNeeded > 0 ? ($xpInRank / $xpNeeded) * 100 : 100;

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="nl">

<style>
    html {
        overflow-y: scroll;
    }
</style>

<head>
    <link rel="icon" type="image/png" href="/favicon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracktive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-dark text-white">
    <header class="container text-center py-5">
        <h1 class="fw-bold">
            <?php echo $ingelogd ? "Welkom " . htmlspecialchars($voornaam ?? '') : "Welkom bij Tracktive"; ?>
        </h1>
        <p class="lead">Kies een onderdeel om te starten.</p>
    </header>

    <?php if ($ingelogd): ?>
    <div class="container mb-4">
        <div class="card bg-secondary text-white p-3 shadow-sm">
            <h4 class="mb-2">Jouw voortgang</h4>
            <p class="mb-1">Rank: <strong><?php echo $rank; ?></strong></p>

            <div class="d-flex justify-content-between mb-1">
                <span><?php echo $currentThreshold; ?> XP</span>
                <span><?php echo $nextThreshold; ?> XP</span>
            </div>

            <div class="progress" style="height: 30px;">
                <div class="progress-bar bg-success fw-bold"
                    role="progressbar"
                    style="width: <?php echo $percentage; ?>%;"
                    aria-valuenow="<?php echo $percentage; ?>"
                    aria-valuemin="0"
                    aria-valuemax="100">
                    <?php echo $xp; ?> XP
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <section class="container py-4">
        <div class="row g-4">

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-secondary text-white h-100">
                    <div class="card-body text-center d-flex flex-column">
                        <div class="display-4 mb-3"><i class="bi bi-person-walking"></i></div>
                        <h5 class="card-title">Start fitnesstraining</h5>
                        <p class="card-text flex-grow-1">Begin met kracht- en spiertraining.</p>
                        <a href="/code/fitness/start_fitness.php" class="btn btn-light fw-semibold">Start
                            <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-secondary text-white h-100">
                    <div class="card-body text-center d-flex flex-column">
                        <div class="display-4 mb-3"><i class="bi bi-heart-pulse-fill"></i></div>
                        <h5 class="card-title">Start cardiotraining</h5>
                        <p class="card-text flex-grow-1">Start met lopen of fietsen.</p>
                        <a href="/code/cardio/start_cardio.php" class="btn btn-light fw-semibold">Start
                            <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-secondary text-white h-100">
                    <div class="card-body text-center d-flex flex-column">
                        <div class="display-4 mb-3"><i class="bi bi-clipboard2-check-fill"></i></div>
                        <h5 class="card-title">Trainingsschema</h5>
                        <p class="card-text flex-grow-1">Pas je persoonlijke schema aan.</p>
                        <a href="/code/wijzig_schema/overzicht_schemas.php" class="btn btn-light fw-semibold">Start
                            <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-secondary text-white h-100">
                    <div class="card-body text-center d-flex flex-column">
                        <div class="display-4 mb-3"><i class="bi bi-list-ol"></i></div>
                        <h5 class="card-title">Lijst oefeningen</h5>
                        <p class="card-text flex-grow-1">Bekijk alle beschikbare oefeningen.</p>
                        <a href="/code/lijst_oefeningen/overzicht_oefeningen.php" class="btn btn-light fw-semibold">Start
                            <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-secondary text-white h-100">
                    <div class="card-body text-center d-flex flex-column">
                        <div class="display-4 mb-3"><i class="bi bi-clipboard2-pulse"></i></div>
                        <h5 class="card-title">Lichaamsmeting</h5>
                        <p class="card-text flex-grow-1">Meet gewicht, lichaamsmetingen en meer.</p>
                        <a href="/code/lichaamsmetingen/lichaamsmetingen.php" class="btn btn-light fw-semibold">Start
                            <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-secondary text-white h-100">
                    <div class="card-body text-center d-flex flex-column">
                        <div class="display-4 mb-3"><i class="bi bi-bar-chart-line-fill"></i></div>
                        <h5 class="card-title">Analyse</h5>
                        <p class="card-text flex-grow-1">Bekijk je vooruitgang via grafieken.</p>
                        <a href="/code/analyse/keuze_grafiek.php" class="btn btn-light fw-semibold">Start
                            <i class="bi bi-arrow-right-circle ms-1"></i>
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
