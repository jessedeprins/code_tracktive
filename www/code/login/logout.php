<?php
// Sessie starten om deze te kunnen beëindigen
session_start();

// Alle sessiegegevens verwijderen
session_unset();

// Sessie volledig vernietigen
session_destroy();

// Terug naar de homepage
header("Location: /index.php");
exit();
?>
