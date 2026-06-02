<?php
// Debug aan
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Login check
require '../../code/login/auth.php';

// POST ophalen + trimmen
$gebruikernummer = isset($_POST['gebruikernummer']) ? trim($_POST['gebruikernummer']) : '';
$type1           = isset($_POST['type1']) ? trim(strtolower($_POST['type1'])) : '';
$type2           = isset($_POST['type2']) ? trim(strtolower($_POST['type2'])) : '';
$type3           = isset($_POST['type3']) ? trim(strtolower($_POST['type3'])) : '';
$begindatum      = isset($_POST['begindatum']) ? trim($_POST['begindatum']) : '';
$einddatum       = isset($_POST['einddatum']) ? trim($_POST['einddatum']) : '';

// XSS‑veilig maken
$gebruikernummer = htmlspecialchars($gebruikernummer, ENT_QUOTES, 'UTF-8');
$type1           = htmlspecialchars($type1, ENT_QUOTES, 'UTF-8');
$type2           = htmlspecialchars($type2, ENT_QUOTES, 'UTF-8');
$type3           = htmlspecialchars($type3, ENT_QUOTES, 'UTF-8');
$begindatum      = htmlspecialchars($begindatum, ENT_QUOTES, 'UTF-8');
$einddatum       = htmlspecialchars($einddatum, ENT_QUOTES, 'UTF-8');

// Doelpagina bepalen
$pagina = '';

// BENEN
if ($type2 === "benen" && $type3 === "aantal") {
    $pagina = "analyse_fitness_benen_aantal.php";

} elseif ($type2 === "benen" && $type3 === "gewicht") {
    $pagina = "analyse_fitness_benen_gewicht.php";


// BOVENLICHAAM
} elseif ($type2 === "bovenlichaam" && $type3 === "aantal") {
    $pagina = "analyse_fitness_bovenlichaam_aantal.php";

} elseif ($type2 === "bovenlichaam" && $type3 === "gewicht") {
    $pagina = "analyse_fitness_bovenlichaam_gewicht.php";


// RUG
} elseif ($type2 === "rug" && $type3 === "aantal") {
    $pagina = "analyse_fitness_rug_aantal.php";

} elseif ($type2 === "rug" && $type3 === "gewicht") {
    $pagina = "analyse_fitness_rug_gewicht.php";


// Geen match → stoppen
} else {
    die("Geen analyse gevonden voor deze selectie.");
}

// Verborgen formulier genereren
echo '<form id="redirectForm" method="post" action="' . $pagina . '">';

echo '<input type="hidden" name="gebruikernummer" value="' . $gebruikernummer . '">';
echo '<input type="hidden" name="type1" value="' . $type1 . '">';
echo '<input type="hidden" name="type2" value="' . $type2 . '">';
echo '<input type="hidden" name="type3" value="' . $type3 . '">';
echo '<input type="hidden" name="begindatum" value="' . $begindatum . '">';
echo '<input type="hidden" name="einddatum" value="' . $einddatum . '">';

echo '</form>';

// Auto-submit
echo '<script>document.getElementById("redirectForm").submit();</script>';

exit;
?>
