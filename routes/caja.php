<?php

/**
 * Verificamos si existe sesión, caso contrario
 * lo creamos
 */

use Http\controllers\CajaController;
use Http\controllers\CajaFarmaciaController;
use models\CajaFarmacia;

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

$route->get("/apertura/caja",[CajaController::class,'index']);

/// confirmar el cierre de caja por completo
$route->post("/confirma/cierre/caja/por/completo/{id}",[CajaController::class,'cerrarConfirmCaja']);

$route->get("/historial/caja",[CajaController::class,'ReporteCajaPorFechas']);

$route->post("/caja/{id}/delete",[CajaController::class,'EliminarCajaApertura']);
$route->get("/verificar/caja/farmacia",[CajaFarmaciaController::class,'VerificarCajaAperturadaFarmacia']);
$route->get("/verificar/caja/clinica",[CajaController::class,'VerificarCajaAprturadaClinica']);

$route->get("/apertura/caja-farmacia",[CajaFarmaciaController::class,'index']);
$route->post("/apertura/caja/store",[CajaFarmaciaController::class,'store']);
$route->get("/ver-caja-aperturado",[CajaFarmaciaController::class,'showCajaAbierta']);
$route->post("/eliminar-caja/farmacia",[CajaFarmaciaController::class,'eliminarCaja']);
$route->post("/cerrar-caja/farmacia",[CajaFarmaciaController::class,'cerrarLaCaja']);
$route->get("/informe-cierre-caja/farmacia/{id}",[CajaFarmaciaController::class,'informeCierreCajaFarmacia']);

$route->get("/app/farmacia/resumen-caja",[CajaFarmaciaController::class,'ResumenCajaView']);

 