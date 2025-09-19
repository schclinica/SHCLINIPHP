<?php

use Http\controllers\TipoDocComprobanteController;

if(PHP_SESSION_ACTIVE != session_status())
{
    session_start();
}

$route->get("/gestionar/documentos",[TipoDocComprobanteController::class,'index']);
$route->post("/documento-emision/store",[TipoDocComprobanteController::class,'store']);
$route->get("/documentos-emision/all",[TipoDocComprobanteController::class,'showDocumentosEmision']);
$route->post("/documento-emision/{id}/eliminar",[TipoDocComprobanteController::class,'eliminar']);
$route->post("/documento-emision/{id}/update",[TipoDocComprobanteController::class,'update']);