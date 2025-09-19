<?php 

use Http\controllers\ConfiguracionController;
use Http\controllers\PolizaController;
 

if(session_status() !== PHP_SESSION_ACTIVE)
{
    # le preguntamos, que si la sessión no está activada, lo activamos
    session_start(); 
}


$route->get("/polizas/{id}",[PolizaController::class,'index']);

/// agregar nuevas polizas al cliente
$route->get("/poliza/create/{id}",[PolizaController::class,'create']);

/// ver los productos por ramo
$route->get("/ramo/{id}/productos",[PolizaController::class,'showProductosPorRamo']);
$route->post("/poliza/store/{id}",[PolizaController::class,'store']);

$route->get("/poliza/{id}/prima/plan-pagos",[PolizaController::class,'plan_pagos_get']);
$route->get("/poliza/{id}/prima/plan_pagos/new",[PolizaController::class,'createpagosPrimaPoliza']);
$route->post("/poliza/pagos/prima/store/{id}",[PolizaController::class,'storePagosPoliza']);

/// ver cuotas
$route->get("/poliza/pagos/cuotas/{id}",[PolizaController::class,'cuotasPagosPoliza']);
$route->get("/poliza/plan-pagos/cuotas/{id}",[PolizaController::class,'cuotasPoliza']);

$route->get("/poliza/plan-pagos/{id}/editar-cuotas/{id_prima_pago}",[PolizaController::class,'editarCuota']);

$route->post("/poliza/plan-pagos/{id}/update/{id_prima_pago}",[PolizaController::class,'update']);

$route->get("/poliza/plan-pagos/documenstos/{id}",[PolizaController::class,'documentos']);

$route->post("/poliza/plan-pagos/documentos/{id}",[PolizaController::class,'updateplapagosadddocumentos']);