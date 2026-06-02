<?php

// ============================
// FORGOT PASSWORD
// ============================

include 'config.php';

// FORM VERSTUURD
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // EMAIL OPHALEN
    $email = trim($_POST['email']);

    // CONTROLEREN OF EMAIL BESTAAT
    $stmt = $conn->prepare("
        SELECT Gebruikernr
        FROM Profiel
        WHERE email_adress = ?
    ");

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    // EMAIL GEVONDEN
    if ($result->num_rows > 0) {

        // TOKEN GENEREREN
        $token = bin2hex(random_bytes(32));

        // TOKEN GELDIG TOT 1 UUR
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // TOKEN OPSLAAN
        $stmt2 = $conn->prepare("
            UPDATE Profiel
            SET reset_token = ?, reset_expires = ?
            WHERE email_adress = ?
        ");

        $stmt2->bind_param("sss", $token, $expires, $email);
        $stmt2->execute();

        // RESET LINK
        $resetLink = "https://tracktive.be/code/login/reset_wachtwoord.php?token=" . $token;

        // MAIL
        $subject = "Wachtwoord reset";

        $message = "
Hallo,

Klik op onderstaande link om je wachtwoord opnieuw in te stellen:

$resetLink

Deze link is 1 uur geldig.

Tracktive
";

        // HEADERS
        $headers = "From: Tracktive <no-reply@tracktive.be>\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // MAIL VERSTUREN
        if (mail($email, $subject, $message, $headers)) {
            $success = "Reset link verzonden. Controleer je inbox.";
        } else {
            $error = "Mail verzenden mislukt.";
        }

    } else {
        $error = "E-mailadres niet gevonden.";
    }
}

?>
<!DOCTYPE html>
<html lang="nl">
<style>
    html {
        overflow-y: scroll;
    }
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord vergeten – Tracktive</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-dark text-white">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-black shadow-sm p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand fw-semibold" href="../../index.php">Tracktive</a>

        <div class="dropdown">
            <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center"
                    type="button" id="profielDropdown" data-bs-toggle="dropdown"
                    style="width: 60px; height: 60px;">
                <i class="bi bi-person fs-1 text-dark"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profielDropdown">
                <li><a class="dropdown-item" href="../../code/login/login.php">Login</a></li>
                <li><a class="dropdown-item" href="../../code/login/loginaanmaken.php">Registreer</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- CONTAINER -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">

            <div class="card bg-secondary text-white p-4 shadow-sm">
                <h3 class="text-center mb-4">Wachtwoord vergeten</h3>

                <?php if(isset($success)): ?>
                    <div class="alert alert-success">
                        <?= $success ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <p class="mb-1">E-mailadres</p>
                    <div class="mb-3">
                        <input type="email"
                               name="email"
                               class="form-control"
                               placeholder="Voer je e-mailadres in"
                               autocomplete="email"
                               required>
                    </div>

                    <button type="submit" class="btn btn-light fw-semibold w-100">
                        Verstuur reset link <i class="bi bi-arrow-right-circle ms-1"></i>
                    </button>

                </form>

                <div class="text-center mt-3">
                    <a href="login.php" class="text-white">Terug naar login</a>
                </div>

            </div>

        </div>
    </div>
</div>

<footer class="text-center py-3 mt-4">
    <p class="m-0">© Tracktive</p>
</footer>

</body>
</html>
