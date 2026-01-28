<?php
declare(strict_types=1);
session_start(); // habilita sesiones para pasar datos entre peticiones

// Recupera errores y valores previos almacenados tras un intento fallido
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];

// Limpia los mensajes para que no persistan en futuros reloads
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | EVAP1</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<main class="page">
    <section class="card">
        <div class="card__header">
            <p class="eyebrow">Demo cliente-servidor</p>
            <h1>Acceso seguro</h1>
            <p class="lede">Valida tus credenciales. El lado servidor usa prepared statements y sesiones.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <!-- Bloque de errores enviados por el servidor -->
            <div class="alert" role="alert">
                <strong>Revisa:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulario de login: validación cliente + servidor -->
        <form id="login-form" class="form" action="authenticate.php" method="POST" novalidate>
            <div class="field">
                <label for="username">Usuario</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="ej. admin"
                    value="<?= htmlspecialchars($old['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    required
                    minlength="4"
                    maxlength="30"
                    pattern="[A-Za-z0-9_]{4,30}">
                <small>4-30 caracteres alfanuméricos o guión bajo.</small>
            </div>

            <div class="field">
                <label for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Contraseña"
                    required
                    minlength="8"
                    autocomplete="current-password">
                <small>Mínimo 8 caracteres.</small>
            </div>

            <div class="actions">
                <button type="submit">Ingresar</button>
                <p class="hint">Usuario demo: <code>admin</code> / <code>Admin123!</code></p>
            </div>

            <!-- Errores mostrados por validación en cliente -->
            <div id="client-errors" class="alert alert--ghost" role="alert" hidden tabindex="-1"></div>
        </form>
    </section>
</main>

<!-- JS de validación en cliente -->
<script src="assets/login.js" defer></script>
</body>
</html>
