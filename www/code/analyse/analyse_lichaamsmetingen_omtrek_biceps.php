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

// Basischeck
if (!$gebruikernummer || !$begindatum || !$einddatum) {
    die("Gebruikernummer, begin- en einddatum zijn verplicht.");
}

// Toegestane kolommen
$kolommen = [
    "gewicht"        => "Gewicht",
    "omtrek_borst"   => "Omtrek_borst",
    "omtrek_buik"    => "Omtrek_buik",
    "omtrek_biceps"  => "Omtrek_biceps",
    "omtrek_been"    => "Omtrek_been"
];

// Check of type geldig is
if (!isset($kolommen[$type2])) {
    die("Ongeldige selectie.");
}

$kolom = $kolommen[$type2];

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

// Query opbouwen (BELANGRIJK: juiste kolom gebruiken)
$query = "
SELECT YEAR(Datum) AS Jaar,
       MONTH(Datum) AS Maand,
       MAX($kolom) AS Waarde
FROM Lichaamsmetingen
WHERE Gebruikernr = ?
AND Datum BETWEEN ? AND ?
GROUP BY Jaar, Maand
ORDER BY Jaar ASC, Maand ASC
";

// Query uitvoeren
$stmt = $mysqli->prepare($query);
$stmt->bind_param("iss", $gebruikernummer, $begindatum, $einddatum);
$stmt->execute();
$result = $stmt->get_result();

// Resultaten opslaan
$records = [];
while ($row = $result->fetch_assoc()) {
    $key = $row['Jaar'].'-'.str_pad($row['Maand'], 2, '0', STR_PAD_LEFT);
    $records[$key] = (float)$row['Waarde'];
}

// Labels en data opbouwen
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

            <h2 class="text-center mb-4">Lichaamsmetingen per maand</h2>

            <div class="p-2">
                <canvas id="metingChart" style="height:350px;"></canvas>
            </div>

        </div>
    </div>
</div>

<script>
const labels     = <?php echo json_encode($labels); ?>;
const dataValues = <?php echo json_encode($data); ?>;

const ctx = document.getElementById('metingChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: '<?php echo ucfirst(str_replace("_", " ", $type2)); ?>',
            data: dataValues,
            borderColor: 'rgba(255,159,64,1)',
            backgroundColor: 'rgba(255,159,64,0.2)',
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
                title: { display: true, text: 'Waarde', color: 'white' }
            }
        }
    }
});
</script>

</body>
</html>
