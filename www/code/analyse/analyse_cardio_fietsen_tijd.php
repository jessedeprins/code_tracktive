<?php
// Zet foutmeldingen aan (handig tijdens ontwikkelen)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Controleert of gebruiker ingelogd is
require '../../code/login/auth.php';
$ingelogd = isset($_SESSION['user_id']);
include "../../navbar.php";

// Haal gebruikernummer uit POST (formulier)
$gebruikernummer = $_SESSION['user_id'];

// Haal type op (lopen/fietsen)
$type2          = $_POST['type2'] ?? '';

// Haal begin- en einddatum op
$begindatum     = $_POST['begindatum'] ?? '';
$einddatum      = $_POST['einddatum'] ?? '';

// Controle of verplichte velden ingevuld zijn
if (!$gebruikernummer || !$begindatum || !$einddatum) {
    die("Gebruikernummer, begin- en einddatum zijn verplicht.");
}

// Database gegevens
$host   = 'ID479738_Tracktive.db.webhosting.be';
$dbname = 'ID479738_Tracktive';
$user   = 'ID479738_Tracktive';
$pass   = 'Spike2021?';

// Maak verbinding met database
$mysqli = new mysqli($host, $user, $pass, $dbname);

// Controle of verbinding gelukt is
if ($mysqli->connect_error) {
    die("Database verbinding mislukt: " . $mysqli->connect_error);
}

// Zet encoding op UTF-8
$mysqli->set_charset("utf8");

// SQL query: berekent totale tijd per maand (in minuten)
$query = "
SELECT YEAR(U_Datum) AS Jaar,
       MONTH(U_Datum) AS Maand,
       SUM(U_Tijd_min) AS TotTijd
FROM Uitgevoerdeoef
WHERE Gebruikernr = ?
AND U_Datum BETWEEN ? AND ?
";

$params = [$gebruikernummer, $begindatum, $einddatum];
$types = "iss";

// Als type gekozen is (lopen/fietsen), voeg filter toe
if (!empty($type2)) {
    $query .= " AND U_Oeftype2 = ?";
    $params[] = $type2;
    $types .= "s";
}

$query .= "
GROUP BY Jaar, Maand
ORDER BY Jaar ASC, Maand ASC
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$records = [];
while ($row = $result->fetch_assoc()) {
    $key = $row['Jaar'] . '-' . str_pad($row['Maand'], 2, '0', STR_PAD_LEFT);
    $records[$key] = (float)$row['TotTijd'];
}

$labels = [];
$data   = [];

$start = new DateTime($begindatum);
$end   = new DateTime($einddatum);
$end->modify('first day of next month');

$interval = new DateInterval('P1M');
$period   = new DatePeriod($start, $interval, $end);

foreach ($period as $dt) {
    $key = $dt->format('Y-m');
    $labels[] = $dt->format('M Y');
    $data[] = $records[$key] ?? 0;
}

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
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <h2 class="text-center mb-4">Sporttijd per maand (minuten)</h2>
            <div class="p-2">
                <canvas id="tijdChart" style="height:350px;"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
const labels = <?php echo json_encode($labels); ?>;
const dataValues = <?php echo json_encode($data); ?>;

const ctx = document.getElementById('tijdChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Totale tijd (minuten)',
            data: dataValues,
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
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
                title: { display: true, text: 'Tijd (minuten)', color: 'white' }
            }
        }
    }
});
</script>

</body>
</html>
