<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$ingelogd = isset($_SESSION['user_id']);

if ($ingelogd && !isset($voornaam)) {
    include_once "code/login/config.php";
    $sql = "SELECT Voornaam FROM Profiel WHERE Gebruikernr = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $voornaam = $stmt->get_result()->fetch_assoc()['Voornaam'] ?? null;
}
?>
<nav class="navbar navbar-dark bg-black shadow-sm p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand fw-semibold" href="/index.php">Tracktive</a>
        <div class="dropdown">
            <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center"
                    type="button" id="profielDropdown" data-bs-toggle="dropdown"
                    style="width: 60px; height: 60px;">
                <?php if ($ingelogd): ?>
                    <span class="fs-4 fw-bold text-dark"><?php echo isset($voornaam) ? strtoupper(mb_substr($voornaam, 0, 1)) : '?'; ?></span>
                <?php else: ?>
                    <i class="bi bi-person fs-1 text-dark"></i>
                <?php endif; ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profielDropdown">
                <?php if ($ingelogd): ?>
                    <?php if (isset($voornaam)): ?>
                        <li><span class="dropdown-item-text fw-semibold"><?php echo htmlspecialchars($voornaam); ?></span></li>
                        <li><hr class="dropdown-divider"></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item text-danger" href="/code/login/logout.php">Uitloggen</a></li>
                <?php else: ?>
                    <li><a class="dropdown-item" href="/code/login/login.php">Login</a></li>
                    <li><a class="dropdown-item" href="/code/login/loginaanmaken.php">Registreer</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>