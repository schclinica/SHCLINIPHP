<?php

use Http\controllers\AtencionController;

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}


$route->get("/editar-atencion-medica",[AtencionController::class,'EditarAtencionMedica']);
$route->post("/atencion-medica/{id}/update",[AtencionController::class,'ModificarAtencionMedica']);
$route->get("/diagnostios/data/edition",[AtencionController::class,'showDiagnosticosEdition']);
$route->post("/modificar-tipo_diagnostico-del-paciente/{id}/update",[AtencionController::class,'modificarTipoDiagnosticoPaciente']);
$route->post("/quitar-de-la-lista-de-diagnosticos-del-paciente/{id}",[AtencionController::class,'QuitarDiagnosticoDeLaLista']);
$route->post("/agregar-nuevos-diagnosticos-al-paciente",[AtencionController::class,'AgregarNuevosDiagnosticosALista']);
$route->get("/mostrar-lista-tipo-diagnosticos",[AtencionController::class,'showTipoDiagnosticos']);