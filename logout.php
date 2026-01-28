<?php
declare(strict_types=1);
session_start(); // accede a la sesión actual

// Solo acepta logout por POST para evitar cierres accidentales
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$_SESSION = []; // limpia variables de sesión

// Si se usan cookies de sesión, invalida la cookie en el navegador
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy(); // destruye la sesión en el servidor
header('Location: index.php'); // vuelve al login
exit;
