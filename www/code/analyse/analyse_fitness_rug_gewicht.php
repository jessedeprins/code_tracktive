<?php
// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logincontrole
require '../../code/login/auth.php';
$ingelogd = isset($_SESSION['user_id']);
include "../../navbar.php";

// Formulierdata ophalen
$gebruikernummer = $_SESSION['user_id'];
$type2       = $_POST['type2'] ?? '';
$begindatum  = $_POST['begindatum'] ?? '';
$einddatum   = $_POST['einddatum'] ?? '';

// Basisvalidatie
if (!$gebruikernummer || !$begindatum || !$einddatum) {
    die("Gebruikernummer, begin- en einddatum zijn verplicht.");
}

// Databasegegevens
$host   = 'ID479738_Tracktive.db.webhosting.be';
$dbname = 'ID479738_Tracktive';
$user   = 'ID479738_Tracktive';
$pass   = 'Spike2021?';

// Verbinding maken
$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
    die("Database verbinding mislukt: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

// Query opbouwen
$query = "
    SELECT YEAR(U_Datum) AS Jaar,
           MONTH(U_Datum) AS Maand,
           MAX(U_Kg) AS TotGewicht
    FROM Uitgevoerdeoef
    WHERE Gebruikernr = ?
    AND U_Datum BETWEEN ? AND ?
";

$params = [$gebruikernummer, $begindatum, $einddatum];
$types  = "iss";

// Extra filter indien type2 is gekozen
if (!empty($type2)) {
    $query .= " AND U_Oeftype2 = ?";
    $params[] = $type2;
    $types   .= "s";
}

// Groeperen per maand
$query .= "
    GROUP BY Jaar, Maand
    ORDER BY Jaar ASC, Maand ASC
";

// Query uitvoeren
$stmt = $mysqli->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Resultaten opslaan per maand
$records = [];
while ($row = $result->fetch_assoc()) {
    $key = $row['Jaar'] . '-' . str_pad($row['Maand'], 2, '0', STR_PAD_LEFT);
    $records[$key] = (float)$row['TotGewicht'];
}

// Labels en data voorbereiden
$labels = [];
$data   = [];

$start = new DateTime($begindatum);
$start->modify('first day of this month');
$end = new DateTime($einddatum);
$end->modify('first day of next month');

$interval = new DateInterval('P1M');
$period   = new DatePeriod($start, $interval, $end);

// Elke maand invullen
foreach ($period as $dt) {
    $key      = $dt->format('Y-m');
    $labels[] = $dt->format('M Y');
    $data[]   = $records[$key] ?? 0;
}

// Opruimen
$stmt->close();
$mysqli->close();
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
<!-- SYMMETRISCHE LAYOUT -->
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <h2 class="text-center mb-4">Gewicht getild per maand</h2>

            <div class="p-2">
                <canvas id="gewichtChart" style="height:350px;"></canvas>
            </div>

        </div>
    </div>
</div>

<script>
const labels     = <?php echo json_encode($labels); ?>;
const dataValues = <?php echo json_encode($data); ?>;

const ctx = document.getElementById('gewichtChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Gewicht (kg)',
            data: dataValues,
            borderColor: 'rgba(255, 159, 64, 1)',
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            borderWidth: 2,
            tension: 0.2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { color: 'white' } }
        },
        scales: {
            x: {
                ticks: { color: 'white' },
                title: { display: true, text: 'Maand / Jaar', color: 'white' }
            },
            y: {
                beginAtZero: true,
                ticks: { color: 'white' },
                title: { display: true, text: 'Gewicht (kg)', color: 'white' }
            }
        }
    }
});
</script>

</body>
</html>
