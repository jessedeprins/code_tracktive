<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Login check
require '../../code/login/auth.php';
$ingelogd = isset($_SESSION['user_id']);

// POST waarden
$gebruikernummer = $_SESSION['user_id'];
$type2          = $_POST['type2'] ?? '';
$begindatum     = $_POST['begindatum'] ?? '';
$einddatum      = $_POST['einddatum'] ?? '';

if (!$gebruikernummer || !$begindatum || !$einddatum) {
    die("Gebruikernummer, begin- en einddatum zijn verplicht.");
}

// Database connectie
$host   = 'ID479738_Tracktive.db.webhosting.be';
$dbname = 'ID479738_Tracktive';
$user   = 'ID479738_Tracktive';
$pass   = 'Spike2021?';

$mysqli = new mysqli($host, $user, $pass, $dbname);

if ($mysqli->connect_error) {
    die("Database verbinding mislukt: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8");

// SQL query
$query = "
SELECT YEAR(U_Datum) AS Jaar,
       MONTH(U_Datum) AS Maand,
       SUM(U_Afstand) AS TotAfstand
FROM Uitgevoerdeoef
WHERE Gebruikernr = ?
AND U_Datum BETWEEN ? AND ?
";

$params = [$gebruikernummer, $begindatum, $einddatum];
$types = "iss";

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
    $records[$key] = (float)$row['TotAfstand'];
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
<!-- SYMMETRISCHE LAYOUT -->
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <h2 class="text-center mb-4">Sportafstand per maand (km)</h2>

            <div class="p-2">
                <canvas id="afstandChart" style="height:350px;"></canvas>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const labels = <?php echo json_encode($labels); ?>;
const dataValues = <?php echo json_encode($data); ?>;

const ctx = document.getElementById('afstandChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Totale afstand (km)',
            data: dataValues,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 2,
            tension: 0.2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: false,
        scales: {
            x: {
                title: { display: true, text: 'Maand / Jaar', color: '#ccc' },
                ticks: { color: '#ccc' }
            },
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Afstand (km)', color: '#ccc' },
                ticks: { color: '#ccc' }
            }
        },
        plugins: {
            legend: { labels: { color: '#ddd' } }
        }
    }
});
</script>

</body>
</html>
