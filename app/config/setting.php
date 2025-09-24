<?php
// Inicia sesión
session_start();

// Configuración de conexión a MySQL Azure
$host = "shclinicaphp-server.mysql.database.azure.com";
$port = 3306;
$dbname = "shclinicaphp-database";
$username = "tiwlnotwgl@shclinicaphp-server";
$password = "rM$2WP0q2hk0WvW8";

// Ruta al certificado SSL de Azure (descargar BaltimoreCyberTrustRoot.crt.pem)
$ssl_ca_path = __DIR__ . "/certs/BaltimoreCyberTrustRoot.crt.pem";

try {
    // DSN (Data Source Name)
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

    // Opciones PDO con SSL
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,             // Muestra errores de PDO
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Fetch asociativo
        PDO::MYSQL_ATTR_SSL_CA => $ssl_ca_path,                  // Certificado SSL
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,          // Verifica certificado
    ];

    // Conexión PDO
    $pdo = new PDO($dsn, $username, $password, $options);

    echo "✅ Conexión exitosa a Azure MySQL Flexible Server";

} catch (PDOException $e) {
    // Muestra error de conexión
    die("❌ Error de conexión a la base de datos: " . $e->getMessage());
}

