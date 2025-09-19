<?php

use Http\controllers\SedesController;

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
/**
 * GESTION DE RUTAS PARA LAS SEDES DE LA CLINICA
 */
$route->get("/sucursales",[SedesController::class,'ViewGestionSedes']);
$route->post("/sede/store",[SedesController::class,'save']);
$route->get("/sedes/all",[SedesController::class,'showSedes']);
$route->get("/sedes/all/disponibles",[SedesController::class,'showSedesDisponibles']);
$route->post("/sede/{id}/update",[SedesController::class,'updateSede']);
$route->post("/sede/{id}/papelera",[SedesController::class,'EliminarSede']);
$route->post("/sede/{id}/habilitar",[SedesController::class,'HabilitarSede']);
$route->post("/sede/{id}/borrar",[SedesController::class,'BorrarSede']);