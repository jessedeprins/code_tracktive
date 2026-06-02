<?php
// Logincontrole
require '../../code/login/auth.php';

// Gebruikersnummer ophalen
$gebruikernummer = $_SESSION['user_id'];
?> 

<!DOCTYPE html>
<html lang="nl">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracktive - Nieuwe oefening</title>
    <link rel="icon" type="image/png" href="/favicon.png">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-5">

    <h2>Nieuwe oefening aanmaken</h2>

    <!-- Formulier voor nieuwe oefening (variant voor schema-wijziging) -->
    <form action="aanmaak_oefening_wijzig_schemaphp.php" method="post">

        <!-- Gebruikersnummer meesturen -->
        <input type="hidden" name="Gebruikernr" value="<?= htmlspecialchars($gebruikernummer) ?>">

        <!-- Type 1 -->
        <div class="mb-3">
            <label class="form-label">Type 1</label>
            <select name="Type1" class="form-select">
                <option value="Fitness" selected>Fitness</option>
            </select>
        </div>

        <!-- Type 2 -->
        <div class="mb-3">
            <label class="form-label">Type 2</label>
            <select name="Type2" class="form-select">
                <option value="Bovenlichaam">Bovenlichaam</option>
                <option value="Benen">Benen</option>
                <option value="Rug">Rug</option>
            </select>
        </div>

        <!-- Naam oefening -->
        <div class="mb-3">
            <label class="form-label">Naam oefening</label>
            <input type="text" name="Oefnaam" class="form-control" required>
        </div>

        <!-- Gewicht -->
        <div class="mb-3">
            <label class="form-label">Gewicht (kg)</label>
            <input type="number" step="0.1" name="Kg" class="form-control" required>
        </div>

        <!-- Aantal reps -->
        <div class="mb-3">
            <label class="form-label">Aantal reps</label>
            <input type="number" name="Aantal" class="form-control" required>
        </div>

        <!-- Opslaan -->
        <button type="submit" class="btn btn-primary">Opslaan</button>

    </form>
</div>
</body>
</html>
