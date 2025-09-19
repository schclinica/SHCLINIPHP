<?php

/** Verificamos si no existe session, lo creamos */

use Http\controllers\InformeMedicoController;
use Http\controllers\MedicoController;
use Http\controllers\ReciboController;
 

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

/// creamos las rutas para generar el recibo
$route->get("/medico/generate/recibo/paciente",[MedicoController::class,'recibo']);

/// mostramos la ruta de pacientes atendidos que no tienen recibo
$route->get("/pacientes_sin_recibo",[ReciboController::class,'Pacientes_Sin_Recibo']);

/// agregamos el servicio al detalle del recibo
$route->post("/add_cesta_service/{id}",[ReciboController::class,'addDetalleService']);

/// mostrar datos de la cesta añadido
$route->get("services/agregado_en_carrito",[ReciboController::class,'mostrarCestaServiceDetalle']);

/// quitar servicio del detalle
$route->post("/quitar_service_detalle",[ReciboController::class,'QuitarServiceCart']);

/// guardar los datos del recibo del paciente
$route->post("/recibo/save",[ReciboController::class,'saveRecibo']);

/// cancelar el recibo del paciente
$route->post("/recibo/cancel/update/{id}",[ReciboController::class,'CancelRecibo']);

/// para casos de que el médico u asistente den cancelar al proceso de la cita médica
$route->post("/recibo/cancel/proceso",[ReciboController::class,'cancelDataRecibo']);

/// generamos el recibo en pdf
$route->get("/paciente/recibo",[InformeMedicoController::class,'prueba']);

/// mostrar los recibos ya generados
$route->get("/recibos/generados",[ReciboController::class,'mostrar_recibos_generados']);

/// ELIMINAR EL RECIBO
$route->post("/recibo/{idrecibo}/{citaid}/eliminarbd",[ReciboController::class,'eliminarReciboBD']);

$route->post("/recibo/dar-descuento",[ReciboController::class,'ModifyPriceServiceRecibo']);

$route->post("/recibo/detalle/editar/{id}",[ReciboController::class,'getDetalleRecibo']);
$route->post("/quitar-service/detalle_service/editar/{id}",[ReciboController::class,'QuitarServiceDetalleEdition']);
$route->get("/recibo/detalle/show/edition",[ReciboController::class,'showDetalleServiceEdition']);
$route->post("/recibo/add-service/edition-detalle/{id}",[ReciboController::class,'addServiceCestaEdition']);
$route->post("/recibo/{id}/update",[ReciboController::class,'UpdateRecibo']);
