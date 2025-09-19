<?php 
use Http\controllers\{AuthController, ConfiguracionController,DepartamentoController,DistritoController, EgresoController, EmpresaController, EspecialidadController,
    HomeController, InformeMedicoController, MedicoController,PacienteController, PagoMedicoController, ProductoFarmaciaController, ProvinciaController, RecetaController, RedSocialController, ServicioController, TestimonioController, TipoDocumentoController,UsuarioController, VentaFarmaciaController};
use lib\View;

/*========================================= inicializamos la sesion en caso no exista ==============================*/
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

 
/// ruta para la página principal

$route->get("/",[HomeController::class,'PageInicio']);
$route->get("/usuario/create",[UsuarioController::class,'create']);

 
/// ruta para el Dashboard

$route->get("/reportes-citas-medicas",[HomeController::class,'Dashboard']);
$route->get("/dashboard",[HomeController::class,'Welcome']);
$route->get("/escritorio",[HomeController::class,'Desktop']);


//// rutas para tipo de documentos

$route->get("/new-tipo-documento",[TipoDocumentoController::class,'create']);

$route->post("/save-tipo-documento",[TipoDocumentoController::class,'save']);

$route->get("/tipo-documentos-existentes",[TipoDocumentoController::class,'index']);

$route->get("/documentos-existentes",[TipoDocumentoController::class,'showTipoDocumentos']);

$route->post("/update-tipo-documento/{id}",[TipoDocumentoController::class,'update']);

$route->post("/destroy-tipo-documento/{id}",[TipoDocumentoController::class,'delete_']);
$route->post("/activar/tipo-documento/{id}",[TipoDocumentoController::class,'ActivarTipoDocumento']);
$route->post("/tipo-doc/borrar/{id}",[TipoDocumentoController::class,'BorrarTipoDocumento']);

$route->post("/saludar",[TipoDocumentoController::class,'saludar']);

$route->post("/tipodoc/save",[TipoDocumentoController::class,'save_tipo_doc']);

/// rutas para los pacientes

$route->get("/paciente",[PacienteController::class,'create']);
$route->get("/paciente-existentes",[PacienteController::class,'PacientesExistentes']);

$route->post("/paciente/save",[PacienteController::class,'save']);
$route->post("/paciente/completar_datos_",[PacienteController::class,'completeDatos']);

/// ruta para la vista del informe médico del paciente
$route->get("/paciente/consultar_informe_medico",[InformeMedicoController::class,'viewInformeMedico']);
$route->get("/paciente/show/informe_medico",[InformeMedicoController::class,'showInformeMedicoPaciente']);
/// modificar datos del paciente
$route->post("/paciente/{persona}/{paciente}/update",[PacienteController::class,'modificarPaciente']);
$route->post("/paciente/{id}/{medicoid}/delete",[PacienteController::class,'eliminar']);
/// creamos la cuenta de usuario del paciente , desde el lógin
$route->post("/paciente/create_account",[AuthController::class,'saveAccountPaciente']);
$route->get("/paciente/confirm_account",[AuthController::class,'ConfirmAccountPaciente']);
$route->post("/paciente/verify_account_paciente",[AuthController::class,'VerifyCodUserPaciente']);
$route->post("/paciente/send/codigo/veryfy_account",[AuthController::class,'sendCodigoEmailUser']);
/// ver pdf del informe médico del paciente
$route->get("/informe_medico",[InformeMedicoController::class,'GenerateInformeMedicoPdf']);


/// rutas pata departamentos

$route->get("/departamentos",[DepartamentoController::class,'viewDepartementos']);
$route->post("/departemento/save",[DepartamentoController::class,'saveDepartamento']);
$route->get("/departamento/mostrar",[DepartamentoController::class,'showDepartamentos']);
$route->get("/departamento/eliminados",[DepartamentoController::class,'showDepartamentosEliminados']);
$route->post("/departamento/{id}/update",[DepartamentoController::class,'update']);
$route->post("/departamento/{id}/cambiar_estado/{estado}",[DepartamentoController::class,'cambiarEstado']);

/// rutas para provincias
$route->post("/provincia/save",[ProvinciaController::class,'saveProvincia']);
$route->get("/provincia/mostrar",[ProvinciaController::class,'showProvincias']);
$route->get("/provincia/mostrartodo",[ProvinciaController::class,'allProvincias']);
$route->get("/provincia/mostrartodo/eliminados",[ProvinciaController::class,'allProvinciasEliminados']);
$route->post("/provincia/{id}/update",[ProvinciaController::class,'update']);
$route->post("/provincia/{id}/modifystatus/{estatus}",[ProvinciaController::class,'CambiarEstatusProvincia']);
$route->get("/provincia-por-departamento/show/{id}",[ProvinciaController::class,'showProvinciasPorId']);

 // ruta para distritos
$route->post("/distrito/save",[DistritoController::class,'save']);

$route->get("/distritos/mostrar-para-la-provincia/{id_provincia}",[DistritoController::class,'showDistritos_provincia']);
$route->get("/distritos/all",[DistritoController::class,'showDistritosAll']);
$route->get("/distritos-por-provincia/show/{id}",[DistritoController::class,'showDistritos_provinciaPorId']);
/// modficiar distritos ----mod
$route->post("/distrito/update/{id}",[DistritoController::class,'update']);
/// eliminar comuna
$route->post("/distrito/delete/{id}",[DistritoController::class,'delete']);
/// mostrar los distritos eliminados
$route->get("distristos/eliminados",[DistritoController::class,'showDistritosEliminados']);
/// activar distrito
$route->post("/distrito/activate/{id}",[DistritoController::class,'Activate']);

/// rutas para médicos
$route->get("/medicos",[MedicoController::class,'index']);

/// ruta para los médicos
$route->get("/especialidades/all-por-medico-seleccionado/{id}",[EspecialidadController::class,'showEspecialidadesMedicoPublic']);
$route->post("/medico/save",[MedicoController::class,'save']);
$route->get("/medico/all",[MedicoController::class,'mostrarMedicos']);
$route->get("/medico/{id_medico}/{buscador}/especialidades-no-asignados",[MedicoController::class,'mostrarEspecialidadesNoAsignados']);
$route->post("/medico/asignar-especialidad",[MedicoController::class,'AsignarEspecialidad']);
$route->get("/medicos_y_sus_especialidades",[MedicoController::class,'MostrarMedicoEspecialidades']);
$route->get("/mostrar-horario-por-dia",[MedicoController::class,'getHoarioEsSaludPorDia']);
$route->post("/medico/asignar-horario_atencio-medica",[MedicoController::class,'AsignarHorariosAtencion']);
$route->get("/medico/generar_horarios",[MedicoController::class,'generateHorario']);
$route->post("/medico/programacion_horarios",[MedicoController::class,'guardarProgramacionDeHorarios']);
$route->get('/especialidades_del_medico/{medico}',[MedicoController::class,'showEspecialidadesMedico']);
$route->get("/verificar_existencia_de_procedimiento_medico/{medico}/{procedimiento}",[MedicoController::class,'verifyprocedimEspecialidad']);
$route->post("/medico/save/procedimientos_por_especialidad",[MedicoController::class,'saveProcedimientoMedico']);
$route->post("/medico/update/procedimientos_por_especialidad/{id}",[MedicoController::class,'modificarProcedimiento']);
$route->get("/{medico}/horarios",[MedicoController::class,'showHorariosMedico']);
$route->get("/medico/horarios_programados_por_dia/{dia}",[MedicoController::class,'showHorariosProgramdosMedico']);
$route->post("/medico/{id}/{estado}/cambiar_estado",[MedicoController::class,'active_desactive_horario_medico']);
$route->get("/medico_perfil/{id?}",[MedicoController::class,'profileMedic']);
$route->post("/medico/add_horario_personalizado/{atencion}",[MedicoController::class,'addPersonalizadoHourMedico']);
$route->post("/medico/{id}/eliminar_horario",[MedicoController::class,'deleteHorario']);
$route->post("/medico/{id}/modificar_horario",[MedicoController::class,'updateHorario']);
$route->post("/medico/importar_horario",[MedicoController::class,'ImportarHorario']);
$route->get("/medico/import-dias-de-atencion",[MedicoController::class,'ViewImportDiasDeAtencion']);
$route->post("/medico/importar_dias_atencion",[MedicoController::class,'ImportDiasAtencion']);
$route->post("/medico/{id}/{medicoid}/delete",[MedicoController::class,'eliminar']);
$route->get("/nueva_atencion_medica",[MedicoController::class,'atencion_medico_paciente']);
$route->get("/ver-historial-clinico",[MedicoController::class,'atencion_medico_paciente']);

$route->post("/save_atencion_medica_paciente",[MedicoController::class,'saveAtencionMedica']);
$route->post("/save_receta_paciente",[MedicoController::class,'saveRecetaPaciente']);
$route->get("/atencion_medica/pacientes_atendidos/{opcion}/{fecha}",[MedicoController::class,'showPacientesAtendidos']);



/// rutas de especialidades
$route->post("/especialidad/save",[EspecialidadController::class,'store']);
$route->post("/especialidad/{id_especialidad}/update",[EspecialidadController::class,'Update']);
$route->post("/especialidad/{id_especialidad}/delete",[EspecialidadController::class,'CambiarEstadoEspecialidad']);
$route->get("/especialidad/all",[EspecialidadController::class,'showEspecialidades']);
$route->get('/show_procedimientos_medico/{id}',[MedicoController::class,'showProcedimientoMedico']);

/// ruta configuración datos de la empresa
$route->get("/Configurar-datos-clinica",[ConfiguracionController::class,'index']);
$route->get("/Configurar-datos-farmacia",[ConfiguracionController::class,'index']);
$route->get("/configurar_dias_laborables",[ConfiguracionController::class,'mostrar_dias_atencion']);
$route->post("/store-horario-essalud",[ConfiguracionController::class,'storeHorarioBusiness']);
$route->post("/cambiar_dias_atencion_laborable_no_laborable/{id}/{estado}",[ConfiguracionController::class,'cambiar_estado_dia_atencion']);
$route->post("/store-horario-essalud/existe",[ConfiguracionController::class,'storeHorarioBusiness']);
$route->get("/verificar-horario-before-list",[ConfiguracionController::class,'existeBeforeList']);
$route->get("/mostrar_dias/{medico}",[ConfiguracionController::class,'showDias']);


/// rutas para casos de procedimientos de las especialidades de los médicos
$route->post("/procedimiento/{id}/delete",[MedicoController::class,'deleteProcedimiento']);
$route->get("/horarios_no_programados/{medico}",[ConfiguracionController::class,'DiasAtencion_No_Programados']);
$route->get("/plantilla",function(){
 
    View::View_("email.reset_password");
});

/// registrar datos de la empresa
$route->post("/empresa/store",[EmpresaController::class,'store']);

/// mostrar datos de la empresa
$route->get("/empresa/info",[EmpresaController::class,'all']);

/// eliminamos los datos de la clínica
$route->post("/clinica/delete/{id}",[EmpresaController::class,'eliminar']);

/// modificamos los datos de la clínica
$route->post("/clinica/update/{id}",[EmpresaController::class,'update']);

/// especialidades del médico
$route->get("/medico/especialidades",[EspecialidadController::class,'EspecialidadesMedico']);
$route->get("/medico/show/especialidades",[EspecialidadController::class,'showEspecialidadesMedico']);
$route->post("/medico/especialidad/delete/{id}",[EspecialidadController::class,'eliminarEspecialidadMedico']);
$route->post("/medico/add/especialidad",[EspecialidadController::class,'addEspecialidadMedico']);
$route->get("/medico/especialidades/no_asignadas",[EspecialidadController::class,'especialidadesNoAsignadasMedico']);
$route->post("/medico/{persona}/{medico}/update",[MedicoController::class,'updateMedico']);
/**
 * Rutas para gestionar egresos de la clínica
 */
$route->get("/categorias-egresos",[EgresoController::class,'index']);
$route->get("/gestionar-gastos",[EgresoController::class,'gastosIndex']);
$route->get("/gasto/create",[EgresoController::class,'createNuevoGasto']);
$route->get("/categoria-egreso/create",[EgresoController::class,'create']);

$route->post("/categoria-egreso/save",[EgresoController::class,'saveCategoria']);

$route->post("/egreso/gasto/save",[EgresoController::class,'saveSubCategoria']);
$route->get("/show/gastos/all",[EgresoController::class,'showGastos']);

$route->get("/egreso/categorias/all",[EgresoController::class,'mostrarCategoriasEgreso']);

$route->post("/egreso/categoria/delete/{id}",[EgresoController::class,'deleteCategoriaAndSubCategoria']);

$route->get("/egreso/subcategorias/{id}",[EgresoController::class,'mostrarSubCategoriasEgreso']);
$route->post("/egreso/subcategoria/delete/{id}",[EgresoController::class,'deleteSubCategoria']);
$route->post("/egreso/subcategoria/update/{id}",[EgresoController::class,'updateSubCategoria']);

$route->post("/egreso/categoria/update/{id}",[EgresoController::class,'updateCategoria']);
$route->get("/resultados",[VentaFarmaciaController::class,'resultados']);

/// importar datos de excel a la tabla prodcutos
$route->post("/productos/import/excel",[ProductoFarmaciaController::class,'importarDatos']);


/// seguimiento de pacientes
$route->get("/pacientes/seguimiento/cia_medica",[HomeController::class,'mostrarPacientesSeguimiento']);

/// testimonios
$route->get("/mis-testimonios-publicados",[TestimonioController::class,'index']);
$route->post("/testimonio/save",[TestimonioController::class,'save']);
$route->post("/testimonio/update/{id}",[TestimonioController::class,'modificar']);
$route->get("/testimonios-publicados-por-paciente",[TestimonioController::class,'showTestimonios']);
$route->post("/testimonio/eliminar/{id}",[TestimonioController::class,'Eliminar']);

$route->post("/contact/clinica",[HomeController::class,'contacto']);

$route->post("/delete/{id}/especialidad",[EspecialidadController::class,'forzarEliminado']);

$route->get("/gestionar-recetas",[RecetaController::class,'generarRecetaView']);

/**
 * Ruta para subida de documentos del paciente (subir hosting)
 */
$route->post("/paciente/upload/documentos",[PacienteController::class,'uploadDocumentos']);
$route->get("/paciente/documentos/subidos/{id}",[PacienteController::class,'showDocumentUpload']);
$route->post("/paciente/update/descripcion/upload-document/{id}",[PacienteController::class,"updateUploadDocumentoDescripcion"]);

$route->post("/paciente/documento/delete/{id}",[PacienteController::class,'deleteDocumentoUpload']);

/// servicios del medico
$route->get("/medico/servicios",[ServicioController::class,'index']);
$route->get("/medicos-por-especialidad/{id}",[ServicioController::class,'showMedicosPorEspecialidad']);
$route->get("/servicios-medico-por-especialidad/{id}",[ServicioController::class,'showServicesMedico']);
$route->post("/service/modificar/{id}",[ServicioController::class,'update']);
$route->post("/service/savedata",[ServicioController::class,'store']);
$route->post("/service/importdata/excel",[ServicioController::class,'importarServicios']);
$route->get("/medico/citas-realizados",[PagoMedicoController::class,'ViewCitasRealizadosPago']);
$route->get("/medico/citas-realizados/pago/{opcion}/{id}/{fecha_inicio}/{fecha_final}",[PagoMedicoController::class,'citasRealizadosPago']);

$route->get("/medico/dias-de-trabajo-clinica",[MedicoController::class,'showDiasTrabajo']);
$route->post("/medico/delete/{id}/dia-programado",[MedicoController::class,'deleteDiaAtencion']);
$route->post("/medico/update/{id}/dia-trabajo",[MedicoController::class,'updateDiaTrabajo']);
$route->get("/medico/ingresos-por-mes-detallado",[MedicoController::class,'reporteIngresosDetalladoMedicoPorMes']);
$route->get("/show/medicos",[MedicoController::class,'showMedicosData']);
$route->get("medico/reporte/historial/ingresos-detallado/{medico}/{anio}",[MedicoController::class,'showReporteIngresosMensualMedico']);

/// agregar
$route->post("/busines/cargar-foto/{id}",[EmpresaController::class,'subirImagenBanner']);
$route->post("/upload/icono/empresa/{id}",[EmpresaController::class,'subirIconoBusiness']);

$route->get("/redes-sociales",[RedSocialController::class,'index']);
$route->post("/red-social/new",[RedSocialController::class,'save']);
$route->get("/redes-sociales/all",[RedSocialController::class,'showRedesSociales']);
$route->post("/red-social/eliminar/{id}",[RedSocialController::class,'eliminar']);
$route->post("/red-social/activar/{id}",[RedSocialController::class,'activar']);
$route->post("/red-social/borrar/{id}",[RedSocialController::class,'borrar']);
$route->post("/red-social/update/{id}",[RedSocialController::class,'updateRedSocial']);
$route->get("/redes-sociales-habilitadas",[RedSocialController::class,'showRedesSocialesHabilitadas']);
$route->get("/redes-sociales-clinica",[RedSocialController::class,'showRedesSocialesClinica']);
$route->post("/asignarRedSocialClinica",[RedSocialController::class,'asignarRedSocialClinica']);
$route->post("/red-social/clinica/modificar/{id}",[RedSocialController::class,'updateRedSocialUser']);
$route->post("/red-social/clinica/delete/{id}",[RedSocialController::class,'borrarRedSocialClinica']);

$route->get("/especialidades",[EspecialidadController::class,'ViewGestionEspecialidades']);

$route->post("/medico/subir-firma/{id}",[MedicoController::class,'subirFirma']);
$route->post("/medico/firma/eliminar/{id}",[MedicoController::class,'EliminarFirma']);