<?php
use Bramus\Router\Router;
use Dotenv\Dotenv;

// Autoload y logs
require_once 'storage/logs/php_errors.php';
require_once 'vendor/autoload.php';

// Cargar .env
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Configuración general y librerías
require_once 'app/config/setting.php';
require_once 'app/lib/Controls.php';
require_once 'public/fpdf/fpdf.php';

// Trait de conexión
require_once 'app/config/Conection.php';

// Instancia de conexión
class Database { use config\Conection; }
$pdo = Database::getConection();

// Router
$route = new Router;

// Rutas
require 'routes/web.php';
require_once 'routes/Auth_route.php';
require_once 'routes/citas.php';
require_once 'routes/Config.php';
require_once 'routes/recibo.php';
require_once 'routes/Farmacia.php';
require_once 'routes/caja.php';
require_once 'routes/notificaciones.php';
require_once 'routes/poliza.php';
require_once 'routes/receta.php';
require_once 'routes/enfermedades.php';
require_once 'routes/ordenes.php';
require_once 'routes/tipoorden.php';
require_once 'routes/reportes.php';
require_once 'routes/atencion.php';
require_once 'routes/sedes.php';
require_once 'routes/auth.php';
require_once 'routes/productos_almacen.php';
require_once 'routes/movimiento.php';
require_once 'routes/documento.php';
require_once 'routes/serie.php';
require_once 'routes/inventario.php';

// Ejecutar router
$route->run();

// Cerrar conexión al final (opcional)
Database::closeDataBase();


 
 


 
 
