<?php 
 
use Http\controllers\AuthController;
use Http\controllers\GestionUserController;
use Http\controllers\PacienteController;
use Http\controllers\ProfileController;

 /*========================================= inicializamos la sesion en caso no exista ==============================*/
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
 
$route->get("/login",[AuthController::class,'loginView']);
$route->post("/login/sigIn",[AuthController::class,'signIn']);

/// cerrar session

$route->post("/logout",[AuthController::class,'logout']);

/// resetep password
$route->get("/reset-password",[AuthController::class,'RestaurarPassword']);

$route->post("/reset-password",[AuthController::class,'sendEmailResetPassword']);

/// reseteo
$route->get("/reset_password",[AuthController::class,'resetForm']);

$route->post("/guardar_cambio_password_reset/{id}",[AuthController::class,'UpdatePasswordReset']);

/// rutas para gestión de usuarios
$route->get("/user_gestion",[GestionUserController::class,'index']);

$route->get("/user_gestion_mostrar",[GestionUserController::class,'showUsers']);
/// gestión de usuarios
$route->post("/user_gestion/save",[GestionUserController::class,'save']);

/// ruta de perfil
$route->get("{usuario}/profile",[ProfileController::class,'index']);

/// modificar datos del usuario
$route->post("/user/{id_persona}/{id_user}/update",[GestionUserController::class,'updateUser']);

$route->get("/pacientes_sin_cuenta_de_usuario",[GestionUserController::class,'showPacientesSinCuenta']);

$route->post("/pacientes/create_account/{persona}",[GestionUserController::class,'createAccountPaciente']);

$route->get("/prueba_auth",[GestionUserController::class,'ContentInfoCredenciales']);

$route->post("/usuario/{id}/delete",[GestionUserController::class,'deleteUser']);

$route->get("/create_account_paciente",[AuthController::class,'createAccountPaciente']);

// ruta que redirige a la vista para el completado de datos del paciente
$route->get("/paciente/completar_datos",[PacienteController::class,'completaDatos']);
 



 
