<?php

use Http\controllers\InventarioController;

if(PHP_SESSION_ACTIVE != session_status()){
    session_start();
}

$route->get("/gestionar-inventario",[InventarioController::class,'index']);
$route->get("/productos/all/farmacia",[InventarioController::class,'showProductosSucursal']);
$route->post("/producto/agregar/lote",[InventarioController::class,'saveLote']);
$route->get("/total-en-lote/producto/{producto}",[InventarioController::class,'TotalLoteProducto']);
$route->get("/producto/lotes",[InventarioController::class,'showLotesProducto']);
$route->post("/lote/{codigo}/{producto}/delete",[InventarioController::class,'eliminarLoteProducto']);