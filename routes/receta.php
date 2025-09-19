<?php

use Http\controllers\RecetaController;

if(session_status() != PHP_SESSION_ACTIVE)
{
    session_start();
}

$route->get("/consultar/pacientes",[RecetaController::class,'buscarPaciente']);

$route->get("/consultar/productos/clinica",[RecetaController::class,'buscarProductos']);

$route->post("/guardar-receta",[RecetaController::class,'anadirReceta']);

$route->get("/receta-detalle/all",[RecetaController::class,'showRecetaData']);

$route->post("/receta/delete-detalle-seleccionado",[RecetaController::class,'eliminarDetalleSeleccionado']);

$route->post("/receta/save",[RecetaController::class,'SaveReceta']);

$route->post("/receta/borrar-detalle",[RecetaController::class,'eliminarDeLaCestaReceta']);

$route->get("/recetas/view/generados",[RecetaController::class,'showRecetasGenerados']);

$route->post("/receta/delete/{id}",[RecetaController::class,'deleteRecetaElectronica']);


$route->post("/receta/guardar/{id}",[RecetaController::class,'AgregarALaListaReceta']);
$route->get("/receta/detalle/all",[RecetaController::class,'showDetalleReceta']);

$route->post("/quitar-medicamento/{id}/receta",[RecetaController::class,'QuitarMedicamentoReceta']);