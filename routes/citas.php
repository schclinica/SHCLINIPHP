<?php 
/*========================================= inicializamos la sesion en caso no exista ==============================*/

use Http\controllers\ApiPeruController;
use Http\controllers\CajaController;
use Http\controllers\CitaMedicaController;
use Http\controllers\ElectrocardiogramaController;
use Http\controllers\EspecialidadController;
use Http\controllers\EvaluacionController;
use Http\controllers\HomeController;
use Http\controllers\InformeMedicoController;
use Http\controllers\MedicoController;
use Http\controllers\PacienteController;
use Http\controllers\RecetaController;
use Http\controllers\ReporteController;
use Http\controllers\ServicioController;
use Http\controllers\TriajeController;
use models\Caja;

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}


// rutas para el registro de de citas médicas
$route->get("/crear-nueva-cita-medica",[CitaMedicaController::class,'index']);
$route->get("/obtener_dia_segun_fecha",[CitaMedicaController::class,'obtenerDia']);
$route->get("/medicos_por_escpecialidad/{especialidad}",[CitaMedicaController::class,'mostrar_medicos_por_especialidad']);

$route->get("/procedimientos_por_medico/{id}/{espe_id}",[CitaMedicaController::class,'verProcedimientosMedico']);

$route->get("/consulta_paciente/{documento}",[CitaMedicaController::class,'consultarPaciente']);
$route->get("/horarios_programador_del_medico/{medico}/{dia}",[CitaMedicaController::class,'horarios_disponibles_medico']);

$route->get("/obtener_precio_cita_medica/{id}",[CitaMedicaController::class,'getPrecio']);

$route->get("/citas-programados",[CitaMedicaController::class,'ver_citas_programados']);
$route->get("/citas-programados/all/{opcion}/{fecha}",[CitaMedicaController::class,'citas_Programados']);
$route->post("/cita_medica/save",[CitaMedicaController::class,'saveCitaMedica']);
$route->post("/anular_cita_medica",[CitaMedicaController::class,'AnularCitaMedica']);
$route->post("/confirmar_pago_cita_medica",[CitaMedicaController::class,'ConfirmarPagoCitaMedica']);
$route->get("/citas-programados-calendar",[CitaMedicaController::class,'citasMedicasCalendar']);
$route->get("/triaje/pacientes",[TriajeController::class,'index']);
$route->get("/triaje/pacientes/all",[TriajeController::class,'mostrarPacientesTriaje']);

// pacientes no atendidos 
$route->get("/cita_medica/pacientes_no_atendidos",[CitaMedicaController::class,'pacientes_no_atendidos']);

/// triaje
$route->post("/triaje/paciente/save",[TriajeController::class,'save']);
$route->get("/triaje/pacientes/desktop",[HomeController::class,'PacientesEnTriaje']);
$route->get("/triaje/{cita}/editar",[TriajeController::class,'consulta_triaje']);
$route->post("/triaje/{triaje_id}/update",[TriajeController::class,'update']);
$route->post("/paciente/save/proceso_cita_medica",[CitaMedicaController::class,'savePaciente']);
$route->get("/pacientes/cola/atencion_medica",[HomeController::class,'show_pacientes_en_atencion_medica']);

/// reportes para la receta medica
$route->get("/receta_medica",[RecetaController::class,'informe_receta_medica']);

$route->get("/paciente/receta_medica",[RecetaController::class,'informe_receta_medica_personalizado']);

/// modificar la cita médica
$route->post("/citamedica/{cita}/{hora_id}/update",[CitaMedicaController::class,'actualizarCitaMedica']);


/// registrar el informe médico del paciente
$route->post("/informe_medico/{atencion_medica}/save",[InformeMedicoController::class,'save']);
/// actualizar el informe médico
$route->post("/informe_medico/{id}/update",[InformeMedicoController::class,'update']);
/// verificamos existencia del registro del informe del paciente atendido
$route->get("/informe_medico/{id}/verificar_existencia",[InformeMedicoController::class,'verificarInforme']);

$route->get("/informe",[InformeMedicoController::class,'prueba']);

$route->get("/seleccionar-especialidad",[EspecialidadController::class,'allEspecialidadesCitas']);

$route->get("/seleccionar-medico",[MedicoController::class,'medicoPorEspecialidad']);

$route->get("/agendar_cita",[CitaMedicaController::class,'agendarCitaPaciente']);

$route->get("Buscar-paciente",[CitaMedicaController::class,'consultarPacienteCitaMedica']);

$route->get("citas-realizados",[PacienteController::class,'CitasRegistrados']);

$route->get("/citas-anulados",[CitaMedicaController::class,'citasAnulados']);
$route->post("/delete/{id}/citas_anulados",[CitaMedicaController::class,'DeleteCitasAnulados']);

$route->get("/citas_registrados_data",[PacienteController::class,'DataCitasRegistrados']);

/// citas por mes
$route->get("/citas_medicas_por_mes",[CitaMedicaController::class,'CitasMedicasPorMes']);

$route->get("/devuelve_fecha_texto/{fecha}",[TriajeController::class,'PrintFechaText']);
$route->get("/pacientes-triaje-personalizado/{fecha}",[TriajeController::class,'PacientesTriajePersonalizado']);

$route->get("prue",[TriajeController::class,'pruebas']);


/**HISTORIAL CLINICO */
$route->get("/ver-historial-del-paciente/{pacientedoc}",[MedicoController::class,'showHistorialClinicoPaciente']);
$route->get("/ver-pacientes_del_medico",[MedicoController::class,'showPacientes']);
$route->get("/paciente/historial",[MedicoController::class,'reporteHistorialPaciente']);

/**
 * Rutas para ver los servicios de los médicos
 */
$route->get("/medico/mis_servicios",[MedicoController::class,'MisServicios']);
$route->get("/medico/mis_servicios_data_/{id}",[MedicoController::class,'dataServiciosMedico']);

// añadir los servicios del médico
$route->post("/medico/new_service",[MedicoController::class,'addServicio']);

/** AÑADIR EL SERVICIO DEL MÉDICO , MEDIANTE EXCEL */
$route->post("/medico/import/service",[MedicoController::class,'importDatService']);

/** Modificar los servicios del médico */
$route->post("/medico/editar_servicio/{id}",[MedicoController::class,'updateServicio']);

/** Eliminado lógico de los servcios del médico */
$route->post("/medico/servicio/eliminar/{id}",[MedicoController::class,'DeleteSoftServicio']);

/** SERVICIOS DEL MEDICO ELIMINADOS */
$route->get("/medico/servicios_eliminados/{id}",[MedicoController::class,'dataServiciosMedicoEliminados']);

/** Volvemos activar el servicio del médico */
$route->post("/activar_servicio_medico/{id}",[MedicoController::class,'ActiveSoftServicio']);

/// reporte estadisticos
$route->get("reporte_estadistico/{tipo}",[MedicoController::class,'CitasPorAnio_Gr_Estadistico']);

$route->get("/grafica_pacientes_atendidos",[MedicoController::class,'cantidad_pacientes_atendidos']);

/// ruta para guardar las evaluaciones de un paciente atendido
$route->post("/paciente/atenciones_medica/evalacion_pre_operatoria/save",[EvaluacionController::class,'store']);
/// mostrar las evaluaciones pre operativas
$route->get("/mostrar/evaluaciones/pre_operativas",[EvaluacionController::class,'mostrarEvaluacionPreOperatoria']);


/// visualizamos la vista de evaluación e informes
$route->get("/paciente/evaluacion_informes",[EvaluacionController::class,'create']);

/// mostramos el pdf de las evaluaciones pre operatoria de cada paciente
$route->get("/paciente/evaluacion_pre_operatoria",[EvaluacionController::class,'GeneratePdfEvaluationOperatoria']);

/// editamos los datos de la evaluación pre operativa registrada
$route->get("/paciente/evaluation_pre_operativa/editar/{id}",[EvaluacionController::class,'editarEvaluationPreOperativa']);

/// modificar los datos de una evaluación pre operatoria
$route->post("/paciente/evaluation_pre_operatoria/update/{id}",[EvaluacionController::class,'update']);

/// editar la orden de laboratorio
$route->get("/orden/laboratorio/editar/{id}",[MedicoController::class,'editarOrdenLaboratorio']);

/// modifica la órden de laboratorio de un paciente
$route->post("/orden/laboratorio/update/{id}",[MedicoController::class,'UpdateOrdenLaboratorio']);

/// Guardar el informe de electrocardiograma
$route->post("/informe/electrocardiograma/store",[ElectrocardiogramaController::class,'store']);

/// mostrar todos los informes de electrocardiogramas generados
$route->get("/informe/electrocardiograma/all",[ElectrocardiogramaController::class,'showInformesElectrocardiogramas']);

/// mostramos el pdf del informe de electrocardiograma
$route->get("/paciente/informe/electrocardiograma/{id}",[ElectrocardiogramaController::class,'informeElectroCardiograma']);

/// editar los datos del electrocardiograma registrado
$route->get("/paciente/informe/editar/electrocard/{id}",[ElectrocardiogramaController::class,'editar']);

/// modificar los datos del electrocardiograma registrado
$route->post("/paciente/informe/update/electrocard/{id}",[ElectrocardiogramaController::class,'update']);

/// ruta para reportes
$route->get("/reportes",[ReporteController::class,'index']);
/// ver reporte de lso ingresos recaudados por mes de la clínica
$route->get("/reporte/ingresos/por_mes",[ReporteController::class,'verIngresosPorMes']);

$route->get("/reporte/ingresos/por_anio",[ReporteController::class,'verIngresosPorAnio']);

$route->get("/clinica/ingresos/servicio/{fecha1}/{fecha2}",[ReporteController::class,'RepoIngresosClinica']);

$route->get("/clinica/reporte/resultados",[ReporteController::class,'resultadosClinica']);

$route->post("/clinica_farmacia/apertura_caja",[CajaController::class,'store']);
$route->post("/clinica_farmacia/apertura_caja/update/{id}",[CajaController::class,'update']);

$route->get("/clinica_farmacia/mostrar/aperturas/caja",[CajaController::class,'mostrarAperturasCaja']);

/// cerrar la caja aperturda
$route->post("/cerrar/caja/aperturada/{id}",[CajaController::class,'CerrarCajaAperturada']);


/// ver el informe de cierre de caja
$route->get("/informe/cierre_de_caja/{id}",[CajaController::class,'informeCierreCaja']);

/// eliminar caja aperturada
$route->post("/eliminar/caja_aperturada/{id}",[CajaController::class,'delete']);

/**
 * Reporte de la historia de ingresos de la clínica por mes de cada año y tambien po año
 */
$route->get("/clinica/reporte/ingresos/por_mes/año",[ReporteController::class,'repoPdfHistoriaIngresosClinicaPorMes']);

$route->post("/clinica/apertura_caja/farmacia/{id}",[CajaController::class,'updateExists']);

$route->get("/resumen/caja/validate-cierre-caja",[CajaController::class,'confirmarCierreCaja']);

$route->get("/cita-medica/generate/recibo/{folio}",[ReporteController::class,'reciboCitaMedica']);

$route->get("/cita-medica/recibo-detalle/{id}",[ReporteController::class,'reciboCitaMedicaProgram']);

$route->get("/consulta-price-servicio/{id}",[CitaMedicaController::class,'getPriceServicio']);

$route->get("/medico-default/cita-medica/{especialidad}",[CitaMedicaController::class,'showMedicoDefault']);


/**servicios */
$route->post("/service/{id}/eliminar",[ServicioController::class,'eliminar']);

/** CONSULTAR DATOS DE LA PERSONA POR API PERU */
$route->post("/persona/seach/api-peru",[ApiPeruController::class,'BuscarPersonaDni']);

$route->get("/services/{id}/por-especialidad",[ServicioController::class,'showServicesPorEspecialidad']);

$route->get("/obtener-service/{id}",[ServicioController::class,'ObtenerService']);


$route->get("/generar-num-expediente/{tipo}",[CitaMedicaController::class,'GenerateNumExpediente']);
 