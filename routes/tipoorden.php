<?php

use Http\controllers\TipoOrdenController;

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

/**RUTAS PARA GESTIONAR ORDENES DE EXAMENES MEDICOS */
$route->post("/tipo_orden/store",[TipoOrdenController::class,'store']);
$route->get("/tipo-ordenes/all",[TipoOrdenController::class,'showTipoOrdenes']);
$route->post("/tipo_orden/importar",[TipoOrdenController::class,'importar']);
$route->get("/tipo_ordenes/disponibles",[TipoOrdenController::class,'showTipoOrdenes_Disponibles']);
$route->post("/tipo-orden/{id}/update",[TipoOrdenController::class,'modificar']);
$route->post("/tipo-orden/{id}/eliminar",[TipoOrdenController::class,'eliminar']);
$route->post("/tipo-orden/{id}/activar",[TipoOrdenController::class,'activar']);
$route->post("/tipo-orden/{id}/borrar",[TipoOrdenController::class,'borrar']);