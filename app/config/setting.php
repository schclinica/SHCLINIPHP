<?php
// Inicia sesión
session_start();

// Configuración de conexión a MySQL Azure
$host = "shclinicaphp-server.mysql.database.azure.com";
$port = 3306;
$dbname = "shclinicaphp-database";
$username = "tiwlnotwgl@shclinicaphp-server";
$password = "rM$2WP0q2hk0WvW8";

// Ruta correcta al certificado SSL
$ssl_ca_path = __DIR__ . "/app/config/DigiCertGlobalRootG2.crt.pem";

try {
       // Opciones PDO
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_SSL_CA => $ssl_ca_path,
    ];

    // Conexión PDO
    $pdo = new PDO($dsn, $username, $password, $options);

    echo "✅ Conexión exitosa a Azure MySQL Flexible Server";

} catch (PDOException $e) {
    die("❌ Error de conexión a la base de datos: " . $e->getMessage());
}


