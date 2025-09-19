<?php

 /*========================================= inicializamos la sesion en caso no exista ==============================*/

use Http\controllers\EnfermedadController;

 if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
$route->get("/gestionar-enfermedades",[EnfermedadController::class,'index']);
$route->post("/enfermedad/store",[EnfermedadController::class,'store']);
$route->get("/enfermedades/all",[EnfermedadController::class,'mostrar']);
$route->post("/enfermedad/{id}/eliminar",[EnfermedadController::class,'eliminarEnfermendad']);
$route->post("/enfermedad/{id}/habilitar",[EnfermedadController::class,'Habilitar']);
$route->post("/enfermedad/{id}/update",[EnfermedadController::class,'update']);
$route->post("/enfermedad/{id}/borrar",[EnfermedadController::class,'borrar']);
$route->get("/enfermedades-habilitadas",[EnfermedadController::class,'mostrarHabilitados']);
$route->get("/enfermedades/reportes",[EnfermedadController::class,'reporteEnfermedades']);
$route->get("/enfermedades/reporte-statistico/{opc}",[EnfermedadController::class,'reportStadisticoEnfermedades']);
$route->post("/enfermedad/importar-datos",[EnfermedadController::class,'importar']);

$route->post("/asignar-diagnostico-al-paciente/{id}",[EnfermedadController::class,'AsignarEnfermedadDiagnostico']);
$route->get("/diagnosticos-asignados-al-paciente",[EnfermedadController::class,'showDiagnosticosDelPaciente']);
$route->post("/quitar-de-lista-diagnostico/paciente/{id}",[EnfermedadController::class,'EliminarEnfermedadDiagnostico']);

$route->post("/modificar/tipo-diagnostico/paciente/{id}",[EnfermedadController::class,'ModificarEnfermedadDiagnostico']);