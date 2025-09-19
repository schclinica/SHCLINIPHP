<?php

use Http\controllers\OrdenController;
use Http\controllers\ReporteController;
use Http\controllers\ReportesController;

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}


/// orden del laboraorio para el paciente
$route->get("/paciente/orden_medica/{id}",[ReportesController::class,'GenerateOrdenLaboratorioPaciente']);
$route->get("/orden_medica-paciente/{id}",[ReportesController::class,'GenerateOrdenLaboratorioPacienteordeid']);

$route->post("/orden_medica-paciente/delete/{id}",[OrdenController::class,'eliminarOrden']);