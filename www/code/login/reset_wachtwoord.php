<?php

// ============================
// RESET WACHTWOORD PAGINA
// ============================

include 'config.php';

// FOUTMELDINGEN TONEN (tijdelijk voor testen)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// CONTROLEREN OF TOKEN BESTAAT
if (!isset($_GET['token']) || empty(trim($_GET['token']))) {
    die("Geen geldige token ontvangen.");
}

// TOKEN OPHALEN
$token = trim($_GET['token']);

// TOKEN CONTROLEREN
$stmt = $conn->prepare("
    SELECT Gebruikernr
    FROM Profiel
    WHERE reset_token = ?
");

$stmt->bind_param("s", $token);

$stmt->execute();

$result = $stmt->get_result();

// TOKEN NIET GEVONDEN
if ($result->num_rows === 0) {
    die("Ongeldige reset link.");
}

// GEBRUIKER OPHALEN
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="nl">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Nieuw wachtwoord</title>

<style>

body{
    margin:0;
    padding:0;
    background:#f4f4f4;
    font-family:Arial, sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:white;
    padding:30px;
    border-radius:12px;
    width:350px;
    box-shadow:0 0 15px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
    margin-bottom:20px;
}

input{
    width:100%;
    padding:12px;
    margin-top:10px;
    border:1px solid #ccc;
    border-radius:6px;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:12px;
    margin-top:20px;
    background:#007bff;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-size:16px;
}

button:hover{
    background:#0056b3;
}

.info{
    font-size:14px;
    color:#666;
    margin-top:10px;
    text-align:center;
}

</style>

</head>

<body>

<div class="box">

<h2>Nieuw wachtwoord</h2>

<form action="save_wachtwoord.php" method="POST">

<!-- TOKEN VERSTUREN -->
<input
type="hidden"
name="token"
value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">

<!-- NIEUW WACHTWOORD -->
<input
type="password"
name="Wachtwoord"
placeholder="Nieuw wachtwoord"
autocomplete="new-password"
minlength="8"
required>

<p class="info">
Gebruik minstens 8 karakters.
</p>

<button type="submit">
Opslaan
</button>

</form>

</div>

</body>
</html>