<?php
declare(strict_types=1);
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$username = htmlspecialchars((string)($_SESSION['username'] ?? 'usuario'), ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel | EVAP1</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<main class="page">
    <section class="card">
        <div class="card__header">
            <p class="eyebrow">Sesión activa</p>
            <h1>¡Hola, <?= $username; ?>!</h1>
            <p class="lede">Tu sesión está protegida por PHP nativo usando cookies de sesión.</p>
        </div>
        <p>Desde aquí puedes continuar a tu aplicación protegida. La sesión se creó con <code>session_regenerate_id()</code> al iniciar sesión para reducir riesgos de fijación de sesión.</p>
        <div class="actions">
            <form action="logout.php" method="post">
                <button type="submit">Cerrar sesión</button>
            </form>
            <p class="hint">Inicio de sesión: <?= date('d/m/Y H:i:s', (int)($_SESSION['logged_in_at'] ?? time())); ?></p>
        </div>
    </section>
</main>
</body>
</html>
