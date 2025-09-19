<?php

use Http\controllers\ModuloController;
use Http\controllers\PrevilegioController;
use Http\controllers\RoleController;

if(session_status() != PHP_SESSION_ACTIVE){
    session_start();
}

$route->get("/gestionar-roles",[RoleController::class,'index']);
$route->post("/role/store",[RoleController::class,'store']);
$route->get("/roles/all",[RoleController::class,'showRoles']);
$route->post("/role/eliminar/{id}",[RoleController::class,'eliminar']);
$route->post("/role/activar/{id}",[RoleController::class,'activar']);
$route->post("/role/{id}/borrar",[RoleController::class,'forzarEliminado']);
$route->post("/role/{id}/update",[RoleController::class,'modificar']);
$route->get("/roles-no-asignados-al-usuario/{id}",[RoleController::class,'showRolesNotAsignedUser']);
$route->post("/asignar-roles/user/{id}",[RoleController::class,'GuardarRolesDelUsuario']);
$route->post("/asignar-rol-select-user/{id}",[RoleController::class,'AsignarRoleUser']);
$route->post("/quitar/role/de-lista/{id}",[RoleController::class,'quitarRoleList']);
$route->post("/usuario/guardar-roles/{id}",[RoleController::class,'GuardarRolesDelUsuario']);
$route->post("/user/cancel/roles-asigned",[RoleController::class,'clearRolesUser']);
$route->get("/roles-asignados/user/{id}",[RoleController::class,'showRolesAsignedUser']);
$route->post("/quitar-role/user/{id}",[RoleController::class,'eliminarRoleListUser']);
$route->get("/permisos-no-asignados/role/{id}",[RoleController::class,'showPrevilegiosNoAsignedRole']);

/// GESTION DE PERMISOS
$route->get("/gestionar-permisos",[PrevilegioController::class,'index']);
$route->post("/permiso/save",[PrevilegioController::class,'savePermiso']);
$route->get("/permisos/all",[PrevilegioController::class,'showPrevilegios']);
$route->post("/permiso/inhabilitar/{id}",[PrevilegioController::class,'eliminar']);
$route->post("/permiso/activate/{id}",[PrevilegioController::class,'activar']);
$route->post("/permiso/borrar/{id}",[PrevilegioController::class,'borrar']);
$route->post("/permiso/update/{id}",[PrevilegioController::class,'modificar']);

/// GESTION DE MODULOS
$route->post("/modulo/store",[ModuloController::class,'store']);
$route->get("/modulos/all",[ModuloController::class,'showModulos']);
$route->get("/modulos/disponibles",[ModuloController::class,'showModulesDisponibles']);
$route->post("/modulo/{id}/eliminar",[ModuloController::class,'eliminar']);
$route->post("/modulo/{id}/habilitar",[ModuloController::class,'habilitar']);

/// SELECCIONAR TODOS LOS PREVILEGIOS
$route->post("/previlegios/select/all",[PrevilegioController::class,'allPrevilegios']);
$route->post("/role/save/asigned_previlegios/{id}",[PrevilegioController::class,'savePermissionsAsignedRole']);
$route->post("/cancel/permisos/asigned/role",[PrevilegioController::class,'CancelarPermisosRole']);
$route->post("/add-quitar/permiso/role",[PrevilegioController::class,'addQuitarPrevilegio']);