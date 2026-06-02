<?php
// zet foutmeldingen aan zodat je de errors kan zien als je iets fout hebt -->
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../../code/login/auth.php'; // checkt of ie is ingelogd
$ingelogd = isset($_SESSION['user_id']);

// haalt de data op uit de post
$gebruikernummer = $_SESSION['user_id'];
$type2          = $_POST['type2'] ?? '';
$begindatum     = $_POST['begindatum'] ?? '';
$einddatum      = $_POST['einddatum'] ?? '';
include "../../navbar.php";

// checkt of alle verplichte velden ingevuld zijn
if (!$gebruikernummer || !$begindatum || !$einddatum) {
    die("Gebruikernummer, begin- en einddatum zijn verplicht.");
}

$host   = 'ID479738_Tracktive.db.webhosting.be';
$dbname = 'ID479738_Tracktive';
$user   = 'ID479738_Tracktive';
$pass   = 'Spike2021?';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
    die("Database verbinding mislukt: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");

// aantal oefeningen per maand tellen
$query = "
SELECT YEAR(U_Datum) AS Jaar,
       MONTH(U_Datum) AS Maand,
       COUNT(U_Oeftype2) AS TotAantal
FROM Uitgevoerdeoef
WHERE Gebruikernr = ?
AND U_Datum BETWEEN ? AND ?
";

$params = [$gebruikernummer, $begindatum, $einddatum];
$types = "iss"; // i=integer en s=string

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
    $records[$key] = (int)$row['TotAantal'];
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="bg-dark text-white">
<!-- SYMMETRISCHE LAYOUT -->
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <h2 class="text-center mb-4">Aantal oefeningen per maand</h2>

            <div class="p-2">
                <canvas id="aantalChart" style="height:350px;"></canvas>
            </div>

        </div>
    </div>
</div>

<script>
const labels = <?php echo json_encode($labels); ?>;
const dataValues = <?php echo json_encode($data); ?>;

const ctx = document.getElementById('aantalChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Totaal aantal oefeningen',
            data: dataValues,
            borderColor: 'rgba(153, 102, 255, 1)',
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
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
                title: { display: true, text: 'Aantal', color: 'white' }
            }
        }
    }
});
</script>

</body>
</html>
