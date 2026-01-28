<?php
declare(strict_types=1);

/**
 * Devuelve una conexión PDO reutilizable.
 * Las credenciales pueden sobreescribirse con variables de entorno:
 * EVAP_DB_HOST, EVAP_DB_NAME, EVAP_DB_USER, EVAP_DB_PASS.
 */
function getDBConnection(): PDO
{
    static $pdo = null; // cachea la conexión en memoria

    // Si ya existe, la reusa
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    // Lee credenciales o usa valores por defecto de XAMPP
    $host = getenv('EVAP_DB_HOST') ?: '127.0.0.1';
    $name = getenv('EVAP_DB_NAME') ?: 'evap_auth';
    $user = getenv('EVAP_DB_USER') ?: 'root';
    $pass = getenv('EVAP_DB_PASS') ?: '';

    // Data Source Name con charset utf8mb4
    $dsn = "mysql:host={$host};dbname={$name};charset=utf8mb4";

    // Crea PDO con opciones seguras
    $pdo = new PDO(
        $dsn,
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      // lanza excepciones
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // devuelve arrays asociativos
            PDO::ATTR_EMULATE_PREPARES => false,              // usa prepared nativos
        ]
    );

    return $pdo;
}
