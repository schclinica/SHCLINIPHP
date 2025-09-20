<?php
session_start();

try {
    $host = "shclinicaphp-server.mysql.database.azure.com";
    $port = 3306;
    $dbname = "shclinicaphp-database";
    $username = "tiwlnotwgl";
    $password = "rM$2WP0q2hk0WvW8";

    // DSN con SSL habilitado (Azure lo requiere por defecto)
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_SSL_CA       => __DIR__ . "/certs/DigiCertGlobalRootCA.crt.pem",
    ];

    $pdo = new PDO($dsn, $username, $password, $options);

    echo "✅ Conexión exitosa a Azure MySQL Flexible Server";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
