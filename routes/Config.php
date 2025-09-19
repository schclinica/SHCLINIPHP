<?php

use Http\controllers\ConfiguracionController;
use Http\controllers\EmpresaController;
use Http\controllers\ProfileController;

if(session_status() !== PHP_SESSION_ACTIVE)
{
    # le preguntamos, que si la sessión no está activada, lo activamos
    session_start(); 
}

$route->post("/configuracion/copia",[ConfiguracionController::class,'copia_bd']);
$route->post("/configuracion/restaurar",[ConfiguracionController::class,'restore_sistema']);
$route->post("/configuracion/{id}/update",[ConfiguracionController::class,'updateHorario']);

/// perfil
$route->get("/profile/editar",[ProfileController::class,'editar']);
$route->post("/usuario/modificar_password",[ProfileController::class,'ActualizarPasswordActual']);
$route->post("/usuario/update_profile",[ProfileController::class,'updatePerfil']);

$route->get("/config/sistema",[ConfiguracionController::class,'configSistema']);
$route->post("/config/sistema/color/menu",[ConfiguracionController::class,'cambiarColorMenu']);
$route->post("/config/cancel/sistema/color/menu",[ConfiguracionController::class,'cambiarColorMenu']);

$route->post("/empresa/upload-img-fondo/{id}",[EmpresaController::class,'SubirImgFondoEmpresa']);