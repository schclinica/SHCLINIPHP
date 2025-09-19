<?php

use Http\controllers\MovimientoController;

if(PHP_SESSION_ACTIVE != session_status()){
    session_start();
}

$route->post("/movimiento/store/{tipo}",[MovimientoController::class,'store']);
$route->post("/movimiento-farmacia/store/{tipo}",[MovimientoController::class,'storeCajaFarmacia']);


/** VER LOS MOVIMIENTOS DE LA CLINICA CON RESPECTO A UNA CAJA APERTURADA */
$route->get("/movimientos/clinica/{tipo}",[MovimientoController::class,'showMovimientosClinica']);
$route->post("/movimiento/eliminar/{id}",[MovimientoController::class,'eliminarmovimiento']);