<?php

use Http\controllers\SerieController;

if(PHP_SESSION_ACTIVE != session_status())
{
    session_start();
}

$route->post("/cita-medica/generate/identificador",[SerieController::class,'GenerateCorrelativoSerieCitaMedica']);
$route->post("/recibo/generate/identificador",[SerieController::class,'GenerateCorrelativoSerieRecibo']);
$route->post("/triaje/generate-folio",[SerieController::class,'GenerateFolioTriaje']);
$route->post("/historial-clinico/generate-folio",[SerieController::class,'GenerateFolioHistorialClinico']);
$route->post("/orden-medico/generate/serie",[SerieController::class,'GenerateCorrelativoSerieOrdenMedico']);
$route->get("/receta-electronico/generate/serie",[SerieController::class,'GenerateCorrelativoSerieRecetaElectronico']);
$route->get("/compra/generate-serie-correlativo",[SerieController::class,'GenerateCorrelativoSerieCompras']);
$route->get("/venta-farmacia/generate-serie-correlativo",[SerieController::class,'GenerateCorrelativoSerieVentas']);