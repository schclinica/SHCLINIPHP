<?php 

/**
 * Verificamos si existe sesión, caso contrario
 * lo creamos
 */

 use Http\controllers\CajaController;
use Http\controllers\NotificacionesController;

 if (session_status() != PHP_SESSION_ACTIVE) {
     session_start();
 }

 $route->get("/clinica/notificaciones",[NotificacionesController::class,'index']);
 $route->get("/medicos-por-especialidad-home/{id}",[NotificacionesController::class,'mostrarMedicosPorEspecialidad']);
 
 $route->post("/notificaciones/save",[NotificacionesController::class,'saveSolicitud']);

 $route->get("/notificaciones-por-rol",[NotificacionesController::class,'mostrarLasNotificaciones']);

 $route->post("/notificacion/cambiar-estado/{id}/{estado}",[NotificacionesController::class,'AtenderRechazarSolicitud']);