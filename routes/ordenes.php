<?php

use Http\controllers\CategoriaOrdenController;
use Http\controllers\OrdenController;

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

/**RUTAS PARA GESTIONAR ORDENES DE EXAMENES MEDICOS */
$route->get("/gestionar-ordenes",[OrdenController::class,'index']);
$route->post("/orden/store",[OrdenController::class,'store']);
$route->post("/orden/importar",[OrdenController::class,'importar']);
$route->get("/ordenes/all",[OrdenController::class,'showExamenes']);
$route->post("/orden/{id}/eliminar",[OrdenController::class,'Eliminar']);

$route->post("/orden/{id}/activar",[OrdenController::class,'Activar']);
$route->post("/orden/{id}/borrar",[OrdenController::class,'Borrar']);
$route->post("/orden/{id}/udpate",[OrdenController::class,'modificar']);
$route->get("/ordenes/disponibles/{id}",[OrdenController::class,'showExamenesDisponbles']);
$route->post("/asignar-orden-medico/paciente/{id}",[OrdenController::class,'AsignarOrdenPaciente']);
$route->get("/orders-del-paciente",[OrdenController::class,'showOrdersDelPaciente']);
$route->post("/order/paciente/quitar/{id}",[OrdenController::class,'quitarLaOrden']);
$route->post("/modificar-precio-de-la-orden-asignada-paciente",[OrdenController::class,'ModifyPriceOrdenMedica']);


/// rutas para categoria orden
$route->get("/categorias/orden",[CategoriaOrdenController::class,'showCategoriasOrden']);
$route->post("/categoria/order/save",[CategoriaOrdenController::class,'save']);
$route->post("/categoria/order/update/{id}",[CategoriaOrdenController::class,'update']);
$route->post("/categoria/order/eliminar/{id}",[CategoriaOrdenController::class,'eliminar']);
$route->post("/categoria/order/activar/{id}",[CategoriaOrdenController::class,'activar']);
$route->post("/categoria/order/borrar/{id}",[CategoriaOrdenController::class,'borrar']);
$route->post("/categoria/order/importar-datos",[CategoriaOrdenController::class,'importarDatosCategory']);
$route->get("/categorias/por-tipo/disponibles/{id}",[CategoriaOrdenController::class,'showCategoriaPorTipo']);
$route->get("/historial-de-ordenes",[OrdenController::class,'historialOrdenes']);
$route->get("/historial-de-ordenes/all",[OrdenController::class,'showOrdenesHistorial']);