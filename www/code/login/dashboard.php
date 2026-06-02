<?php
require 'auth.php';

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Welkom, <?php echo $_SESSION['naam']; ?></h2>
    <a href="code/login/logout.php" class="btn btn-danger mt-3">Uitloggen</a>
</div>

</body>
</html>