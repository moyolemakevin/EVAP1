<?php
declare(strict_types=1);

/**
 * Devuelve una conexión PDO reutilizable.
 * Las credenciales pueden sobreescribirse con variables de entorno:
 * EVAP_DB_HOST, EVAP_DB_NAME, EVAP_DB_USER, EVAP_DB_PASS.
 */
function getDBConnection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('EVAP_DB_HOST') ?: '127.0.0.1';
    $name = getenv('EVAP_DB_NAME') ?: 'evap_auth';
    $user = getenv('EVAP_DB_USER') ?: 'root';
    $pass = getenv('EVAP_DB_PASS') ?: '';

    $dsn = "mysql:host={$host};dbname={$name};charset=utf8mb4";

    $pdo = new PDO(
        $dsn,
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    return $pdo;
}
