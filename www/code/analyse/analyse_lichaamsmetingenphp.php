<?php
// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logincontrole
require '../../code/login/auth.php';

// Formulierdata ophalen
$gebruikernummer = $_POST['gebruikernummer'] ?? '';
$type1 = $_POST['type1'] ?? '';
$type2 = $_POST['type2'] ?? '';
$type3 = $_POST['type3'] ?? '';
$begindatum = $_POST['begindatum'] ?? '';
$einddatum = $_POST['einddatum'] ?? '';

// Doelpagina bepalen
$pagina = '';

if ($type2 === "gewicht") {
    $pagina = "analyse_lichaamsmetingen_gewicht.php";
} elseif ($type2 === "omtrek_borst") {
    $pagina = "analyse_lichaamsmetingen_omtrek_borst.php";
} elseif ($type2 === "omtrek_buik") {
    $pagina = "analyse_lichaamsmetingen_omtrek_buik.php";
} elseif ($type2 === "omtrek_biceps") {
    $pagina = "analyse_lichaamsmetingen_omtrek_biceps.php";
} elseif ($type2 === "omtrek_been") {
    $pagina = "analyse_lichaamsmetingen_omtrek_been.php";
} else {
    die("Geen analyse gevonden voor deze selectie.");
}

// Verborgen formulier maken voor automatische redirect
echo '<form id="redirectForm" method="post" action="'.$pagina.'">';

echo '<input type="hidden" name="gebruikernummer" value="'.$gebruikernummer.'">';
echo '<input type="hidden" name="type1" value="'.$type1.'">';
echo '<input type="hidden" name="type2" value="'.$type2.'">';
echo '<input type="hidden" name="type3" value="'.$type3.'">';
echo '<input type="hidden" name="begindatum" value="'.$begindatum.'">';
echo '<input type="hidden" name="einddatum" value="'.$einddatum.'">';

echo '</form>';

// Automatisch versturen
echo '<script>document.getElementById("redirectForm").submit();</script>';

exit;
?>
