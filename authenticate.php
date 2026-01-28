<?php
declare(strict_types=1);
session_start(); // permite compartir datos (errores/sesión) entre peticiones

// Acepta solo POST para evitar accesos directos por GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Sanitiza y toma entradas del formulario
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$errors = [];

// Validaciones básicas de presencia
if ($username === '' || $password === '') {
    $errors[] = 'Usuario y contraseña son obligatorios.';
}

// Patrón de usuario permitido
if ($username !== '' && !preg_match('/^[A-Za-z0-9_]{4,30}$/', $username)) {
    $errors[] = 'El usuario solo puede contener letras, números o guión bajo (4-30).';
}

// Longitud mínima de contraseña
if ($password !== '' && strlen($password) < 8) {
    $errors[] = 'La contraseña debe tener al menos 8 caracteres.';
}

// Si falló validación, regresa al formulario con mensajes
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = ['username' => $username];
    header('Location: index.php');
    exit;
}

require __DIR__ . '/config/db.php'; // incluye conexión PDO

try {
    $pdo = getDBConnection(); // abre conexión

    // Prepared statement para evitar SQL Injection
    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1');
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();
} catch (Throwable $e) {
    // Error de BD: regresa con mensaje genérico
    $_SESSION['errors'] = ['No se pudo conectar o consultar la base de datos.'];
    $_SESSION['old'] = ['username' => $username];
    header('Location: index.php');
    exit;
}

// Verifica existencia de usuario y hash de contraseña
if (!$user || !password_verify($password, $user['password_hash'])) {
    $_SESSION['errors'] = ['Usuario o contraseña incorrectos.'];
    $_SESSION['old'] = ['username' => $username];
    header('Location: index.php');
    exit;
}

// Autenticado: protege sesión y guarda datos clave
session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['logged_in_at'] = time();

header('Location: dashboard.php'); // redirige a zona protegida
exit;
