<?php

use Http\controllers\ProductoAlmacenController;

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

$route->get("/almacenes/disponibles/no-asignados/producto/{id}",[ProductoAlmacenController::class,'showSedesDisponibles']);
$route->post("/productos/asignar-almacen/{id}",[ProductoAlmacenController::class,'asignar']);
$route->post("/producto/quitar/almacenes",[ProductoAlmacenController::class,'QuitarTodoAlmacenes']);
$route->post("/producto/almacenes/save",[ProductoAlmacenController::class,'saveAlmacenesProducto']);
$route->post("/producto/quitar/almacen/{id}",[ProductoAlmacenController::class,'QuitarAlmacenLista']);
$route->post("/producto/add/almacen/{id}",[ProductoAlmacenController::class,'AgregarAlmacenLista']);
$route->post("/producto/modificar-stock/almacen/{id}",[ProductoAlmacenController::class,'ModifyStockProductoAlmacen']);
$route->get("/presentaciones/disponibles",[ProductoAlmacenController::class,'showPresentacionesDisponibles']);
 
$route->get("/productos/por/almacen",[ProductoAlmacenController::class,'showProductosPorAlmacen']);
$route->get("/productos/precios",[ProductoAlmacenController::class,'showProductosPrecios']);
$route->post("/agregar-precio/producto/{id}",[ProductoAlmacenController::class,'addPriceProducto']);
$route->post("/producto/modificar/precio-stock/{id}",[ProductoAlmacenController::class,'ModificarPrecioStockProducto']);
$route->post("/producto/update/precios/presentacion/{id}",[ProductoAlmacenController::class,'ModificarPreciosProductoPresentacion']);
$route->post("/producto/{id}/quitar-precio-de-lista/delete",[ProductoAlmacenController::class,'eliminarPrecioProductoPresentacion']);
$route->post("/producto/{id}/eliminar-su-almacen-asignado/delete",[ProductoAlmacenController::class,'eliminarProductoAlmacen']);
 