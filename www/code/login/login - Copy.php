<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4 shadow-sm">
                <h3 class="text-center mb-4">Inloggen</h3>

                <form action="login_verwerken.php" method="post">
                    <div class="mb-3">
                        <input type="email" name="email_adress" class="form-control" placeholder="E-mailadres" required>
                    </div>

                    <div class="mb-3">
                        <input type="password" name="Wachtwoord" class="form-control" placeholder="Paswoord" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>