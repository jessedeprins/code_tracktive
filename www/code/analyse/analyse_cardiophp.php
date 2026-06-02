<?php
// Zet foutmeldingen aan (voor debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Controleert of gebruiker ingelogd is
require '../../code/login/auth.php';

// Haal alle POST-gegevens op (en trim om spaties te verwijderen)
$gebruikernummer = isset($_POST['gebruikernummer']) ? trim($_POST['gebruikernummer']) : '';
$type1          = isset($_POST['type1']) ? trim(strtolower($_POST['type1'])) : '';
$type2          = isset($_POST['type2']) ? trim(strtolower($_POST['type2'])) : '';
$type3          = isset($_POST['type3']) ? trim(strtolower($_POST['type3'])) : '';
$begindatum     = isset($_POST['begindatum']) ? trim($_POST['begindatum']) : '';
$einddatum      = isset($_POST['einddatum']) ? trim($_POST['einddatum']) : '';

// Veilig maken van input (tegen XSS / schadelijke code)
$gebruikernummer = htmlspecialchars($gebruikernummer, ENT_QUOTES, 'UTF-8');
$type1          = htmlspecialchars($type1, ENT_QUOTES, 'UTF-8');
$type2          = htmlspecialchars($type2, ENT_QUOTES, 'UTF-8');
$type3          = htmlspecialchars($type3, ENT_QUOTES, 'UTF-8');
$begindatum     = htmlspecialchars($begindatum, ENT_QUOTES, 'UTF-8');
$einddatum      = htmlspecialchars($einddatum, ENT_QUOTES, 'UTF-8');

// Variabele waarin de juiste pagina wordt opgeslagen
$pagina = '';

// Bepaal welke analysepagina moet worden geopend
// Afhankelijk van: type (lopen/fietsen) + type3 (aantal/afstand/tijd)

// LOPEN
if ($type2 === "lopen" && $type3 === "aantal") {
    $pagina = "analyse_cardio_lopen_aantal.php";

} elseif ($type2 === "lopen" && $type3 === "afstand") {
    $pagina = "analyse_cardio_lopen_afstand.php";

} elseif ($type2 === "lopen" && $type3 === "tijd") {
    $pagina = "analyse_cardio_lopen_tijd.php";


// FIETSEN
} elseif ($type2 === "fietsen" && $type3 === "aantal") {
    $pagina = "analyse_cardio_fietsen_aantal.php";

} elseif ($type2 === "fietsen" && $type3 === "afstand") {
    $pagina = "analyse_cardio_fietsen_afstand.php";

} elseif ($type2 === "fietsen" && $type3 === "tijd") {
    $pagina = "analyse_cardio_fietsen_tijd.php";


// Als geen combinatie overeenkomt → stop script
} else {
    die("Geen analyse gevonden voor deze selectie.");
}

// Maak een verborgen formulier dat automatisch wordt doorgestuurd
echo '<form id="redirectForm" method="post" action="' . $pagina . '">';

// Stuur alle gegevens door naar de juiste pagina
echo '<input type="hidden" name="gebruikernummer" value="' . $gebruikernummer . '">';
echo '<input type="hidden" name="type1" value="' . $type1 . '">';
echo '<input type="hidden" name="type2" value="' . $type2 . '">';
echo '<input type="hidden" name="type3" value="' . $type3 . '">';
echo '<input type="hidden" name="begindatum" value="' . $begindatum . '">';
echo '<input type="hidden" name="einddatum" value="' . $einddatum . '">';

echo '</form>';

// Laat het formulier automatisch submitten via JavaScript
echo '<script>document.getElementById("redirectForm").submit();</script>';

// Stop verdere uitvoering van de pagina
exit;
?>