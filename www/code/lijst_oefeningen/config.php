<?php

$host = "ID479738_Tracktive.db.webhosting.be";
$dbname = "ID479738_Tracktive";
$username = "ID479738_Tracktive";
$password = "Spike2021?";
$mysqli = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Databaseverbinding mislukt");
}
?>
