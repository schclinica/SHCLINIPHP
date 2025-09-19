@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Evaluación e informes')

@section('css')
    <style>
        #tabla_pacientes_evaluacion>thead>tr>th {
            background-color: #1E90FF;
            color: azure;
        }

        #tabla_informes_electrocardiograma>thead>tr>th {
            background-color: #1E90FF;
            color: azure;
        }

        td.hide_me {
            display: none;
        }
    </style>
@endsection
@section('contenido')
    <div class="row">
        <div class="col">
            <div class="card card_scrol" style="background-color: #F8F8FF">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-fill" role="tablist" id="tab_evaluacion_informe">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                data-bs-target="#evaluacion_pre_operatoria" aria-controls="navs-justified-home"
                                aria-selected="true" style="color: #190696ff" id="evaluacion_pre_operatoria_">
                                <i class='bx bxs-donate-heart'></i> <b>Evaluación Pre-Operatoria e informes</b>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#ver_evaluacion_pre_operatoria" aria-controls="navs-justified-home"
                                aria-selected="false" style="color: #00CED1" id="ver_evaluacion_pre_operatoria_">
                                <i class='bx bx-male'></i> <b>Evaluaciones realizados</b>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#ver_informes" aria-controls="navs-justified-home" aria-selected="false"
                                style="color: #4f4f50" id="ver_informes_">
                                <img src="{{ $this->asset('img/icons/unicons/electrocardiograma.ico') }}" class="menu-icon"
                                    alt="" style="height: 20px;width: 20px"> <b>Electrocardiogramas realizados</b>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="evaluacion_pre_operatoria" role="tabpanel"
                            aria-labelledby="home-tab">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover nowrap responsive"
                                    id="tabla_pacientes_evaluacion" style="width: 100%">
                                    <caption>Listado de Pacientes</caption>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th># Documento</th>
                                            <th>Paciente</th>
                                            <th>Seleccionar</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="form_evaluacion" style="display: none">
                                <div class="row">
                                    <h4>Evaluación Pre-Operatoria</h4>
                                    <div class="col-xl-7 col-lg-7 col-md-6 col-12">
                                        <div class="form-group">
                                            <b>Paciente</b>
                                            <input type="text" class="form-control" id="paciente_evaluacion"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                                        <div class="form-group">
                                            <b>Fecha</b>
                                            <input type="text" class="form-control" id="fecha_evaluacion"
                                                class="form-control" value="{{ $this->FechaActual('d/m/Y') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="indicaciones"><b>Indicaciones</b></label>
                                            <textarea name="indicaciones" id="indicaciones" cols="30" rows="3" class="form-control"
                                                placeholder="Escriba aquí...."></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="ant_importantes"><b>Antecedentes importantes</b></label>
                                            <textarea name="ant_importantes" id="ant_importantes" cols="30" rows="3" class="form-control"
                                                placeholder="Escriba aquí...."></textarea>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="molestias_importantes"><b>Molestias importantes actuales <span
                                                        class="text-danger">(*)</span></b></label>
                                            <textarea name="molestias_importantes" id="molestias_importantes" cols="30" rows="3" class="form-control"
                                                placeholder="Escriba aquí...."></textarea>
                                        </div>
                                    </div>
                                    <br>
                                    <h5 class="mt-3">Exámen físico</h5>
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-12">
                                        <div class="form-group mb-1">
                                            <label for="pa"><b>P/A</b></label>
                                            <input type="text" class="form-control" id="pa" name="pa"
                                                placeholder="P/A">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-12">
                                        <div class="form-group mb-1">
                                            <label for="fcc"><b>FcC</b></label>
                                            <input type="text" class="form-control" id="fcc" name="fcc"
                                                placeholder="FcC">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-12">
                                        <div class="form-group mb-1">
                                            <label for="fr"><b>FR</b></label>
                                            <input type="text" class="form-control" id="fr" name="fr"
                                                placeholder="FR">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-12">
                                        <div class="form-group mb-1">
                                            <label for="to"><b>TO</b></label>
                                            <input type="text" class="form-control" id="to" name="to"
                                                placeholder="TO">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-12">
                                        <div class="form-group mb-1">
                                            <label for="sato_dos"><b>SaTO2</b></label>
                                            <input type="text" class="form-control" id="sato_dos" name="sato_dos"
                                                placeholder="SaTO2">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-12">
                                        <div class="form-group mb-1">
                                            <label for="peso"><b>PESO</b></label>
                                            <input type="text" class="form-control" id="peso" name="peso"
                                                placeholder="PESO">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <textarea name="exa_fisico" id="exa_fisico" cols="30" rows="3" class="form-control"
                                                placeholder="Escriba detalladamente aquí...."></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="resultados_est"><b>Resultados de estudios realizados <span
                                                        class="text-danger">(*)</span></b></label>
                                            <textarea name="resultados_est" id="resultados_est" cols="30" rows="3" class="form-control"
                                                placeholder="Escriba los resultados aquí...."></textarea>
                                        </div>
                                    </div>
                                    <br>
                                    <h5 class="mt-3">Riesgo Quirúrgico <span class="text-danger">(*)</span></h5>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                        <div class="form-group mb-1">
                                            <label for="goldman"><b>GOLDMAN</b></label>
                                            <input type="text" class="form-control" id="goldman" name="goldman"
                                                placeholder=".....">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                        <div class="form-group mb-1">
                                            <label for="asa"><b>ASA</b></label>
                                            <input type="text" class="form-control" id="asa" name="asa"
                                                placeholder=".....">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group mb-1">
                                            <label for="sugerencias"><b>SUGERENCIAS</b></label>
                                            <textarea name="sugerencias" id="sugerencias" cols="30" rows="4" class="form-control"
                                                placeholder="Escriba algunas sugerencias para el paciente aquí.."></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 mb-3 mt-2">
                                        <button class="btn btn-outline-success rounded" id="save_evaluacion"><b>Guardar <i
                                                    class='bx bx-save'></i></b></button>
                                    </div>
                                    <hr>
                                    <h5 class="mt-2">Listado de las evaluaciones</h5>

                                    <div class="col-xl-7 col-lg-7 col-md-8 col-12">
                                        <b>Seleccione una fecha</b>
                                        <input type="date" id="fecha_evaluacion_view" class="form-control"
                                            value="{{ $this->FechaActual('Y-m-d') }}">
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover responsive nowrap"
                                            style="width: 100%" id="tabla_lista_evaluacion">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th class="d-none">ID</th>
                                                    <th>Fecha</th>
                                                    <th>Paciente</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-outline-danger rounded " id="cancelar"><b>Cancelar
                                                X</b></button>
                                    </div>
                                </div>
                            </div>

                            <div id="form_informe_electrocardiograma" style="display: none">
                                <div class="row">
                                    <h4>Informe de electrocardiograma</h4>
                                    <div class="col-xl-7 col-lg-7 col-md-6 col-12">
                                        <div class="form-group">
                                            <b>Paciente</b>
                                            <input type="text" class="form-control" id="paciente_informe"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                                        <div class="form-group">
                                            <b>Fecha</b>
                                            <input type="text" class="form-control" id="fecha_informe"
                                                class="form-control" value="{{ $this->FechaActual('d/m/Y') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <label for="pa_informe"><b>P/A <span class="text-danger">(*)</span></b></label>
                                        <input type="text" id="pa_informe" class="form-control" placeholder="P/A">
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <label for="obeso_informe"><b>Obeso <span
                                                    class="text-danger">(*)</span></b></label>
                                        <input type="text" id="obeso_informe" class="form-control"
                                            placeholder="....">
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-12">
                                        <label for="ekg_anterior_informe"><b>EKG anterior </span></b></label>
                                        <input type="text" id="ekg_anterior_informe" class="form-control"
                                            placeholder="....">
                                    </div>
                                    <div class="col-12">
                                        <label for="indicacion_informe"><b>Indicación </b></label>
                                        <input type="text" id="indicacion_informe" class="form-control"
                                            placeholder="....">
                                    </div>
                                    <div class="col-12">
                                        <label for="medicamentocv_informe"><b>Medicamento CV </b></label>
                                        <textarea id="medicamentocv_informe" cols="30" rows="4" class="form-control"
                                            placeholder="Medicamento CV...."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="solicitante_informe"><b>Solicitante </b></label>
                                        <input type="text" id="solicitante_informe" class="form-control"
                                            placeholder="solicitante....">
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <label for="frecuencia_informe"><b>Frecuencia <span class="text-danger">(*)</span>
                                            </b></label>
                                        <input type="text" id="frecuencia_informe" class="form-control"
                                            placeholder="Frecuencia....">
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <label for="ritmo_informe"><b>Ritmo <span class="text-danger">(*)</span>
                                            </b></label>
                                        <input type="text" id="ritmo_informe" class="form-control"
                                            placeholder="Ritmo....">
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <label for="p_informe"><b>P <span class="text-danger">(*)</span> </b></label>
                                        <input type="text" id="p_informe" class="form-control" placeholder="P....">
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                        <label for="pr_informe"><b>PR <span class="text-danger">(*)</span> </b></label>
                                        <input type="text" id="pr_informe" class="form-control" placeholder="PR....">
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                        <label for="qrs_informe"><b>QRS<span class="text-danger">(*)</span> </b></label>
                                        <input type="text" id="qrs_informe" class="form-control"
                                            placeholder="QRS....">
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                        <label for="aqrs_informe"><b>AQRS <span class="text-danger">(*)</span>
                                            </b></label>
                                        <input type="text" id="aqrs_informe" class="form-control"
                                            placeholder="AQRS....">
                                    </div>
                                    <div class="col-xl-3 col-lg-3  col-12">
                                        <label for="qt_informe"><b>QT <span class="text-danger">(*)</span> </b></label>
                                        <input type="text" id="qt_informe" class="form-control" placeholder="QT....">
                                    </div>
                                    <div class="col-12">
                                        <label for="hallazgos_informe"><b>Hallazgos </b></label>
                                        <textarea id="hallazgos_informe" cols="30" rows="4" class="form-control" placeholder="Escriba aquí...."></textarea>
                                    </div>

                                    <div class="col-12 mb-2 mt-3 text-center">
                                        <button class="btn btn-outline-primary rounded"
                                            id="save_informe_electro"><b>Guardar <i class='bx bxs-save'></i></b></button>
                                        <button class="btn rounded btn-outline-danger"
                                            id="cancel_informe_electro"><b>Cancelar X</b></button>
                                    </div>
                                    <hr>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="fecha_informe_"><b>Seleccionar una fecha </b></label>
                                            <input type="date" id="fecha_informe_" class="form-control"
                                                value="{{ $this->FechaActual('Y-m-d') }}">
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped nowrap responsive"
                                                id="tabla_informes_electrocardiograma_edit" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th class="d-none">ID_INFORME</th>
                                                        <th>FECHA</th>
                                                        <th>PACIENTE</th>
                                                        <th>ACCIÓN</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="ver_evaluacion_pre_operatoria" role="tabpanel">
                            <div class="col-xl-7 col-lg-7 col-md-8 col-12">
                                <b>Seleccione una fecha</b>
                                <input type="date" id="fecha_evaluacion_view_show" class="form-control"
                                    value="{{ $this->FechaActual('Y-m-d') }}">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover responsive nowrap"
                                    style="width: 100%" id="tabla_lista_evaluacion_show">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="d-none">ID</th>
                                            <th>Fecha</th>
                                            <th>Paciente</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="ver_informes" role="tabpanel">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="fecha_informes_data"><b>Seleccionar una fecha </b></label>
                                    <input type="date" id="fecha_informes_data" class="form-control"
                                        value="{{ $this->FechaActual('Y-m-d') }}">
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped nowrap responsive"
                                        id="tabla_informes_electrocardiograma" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="d-none">ID_INFORME</th>
                                                <th>FECHA</th>
                                                <th>PACIENTE</th>
                                                <th>ACCIÓN</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL_BASE }}public/js/control.js"></script>
    <script>
        var TablaEvaluacionPreOperatoria;
        var TablaPacientesEvaluacion;
        var TablaEvaluacionPreOperatoriaShow;
        var TablaInformeElectrocardiogramaEdit;
        var TablaInformeElectrocardiograma;
        var RUTA = "{{ URL_BASE }}" // la url base del sistema
        var TOKEN = "{{ $this->Csrf_Token() }}";
        var PACIENTEID;
        var EVALUATIONID;
        var ELECTROCARDIOGRAMAID;
        var ControlButton = 'save';
        var ControlButtonUpdateInforme = 'save';
        /*** DATOS PARA LA EVALUACION**/
        let Ev_Indicaciones = $('#indicaciones');
        let Ev_ant_importantes = $('#ant_importantes');
        let Ev_molestias_importantes = $('#molestias_importantes');
        let Ev_Pa = $('#pa');
        let Ev_fcc = $('#fcc');
        let Ev_Fr = $('#fr');
        let Ev_To = $('#to');
        let Ev_sato_dos = $('#sato_dos');
        let Ev_Peso = $('#peso');
        let Ev_exa_fisico = $('#exa_fisico');
        let Ev_resultados_est = $('#resultados_est');
        let Ev_goldman = $('#goldman');
        let Ev_Asa = $('#asa');
        let Ev_Sugerencias = $('#sugerencias');

        /// DATOS PARA EL INFORME DE ELECTROCARDIOGRAMA
        let IE_Pa = $('#pa_informe');
        let IE_Obeso = $('#obeso_informe');
        let IE_EkgAtenrior = $('#ekg_anterior_informe');
        let IE_Indicacion = $('#indicacion_informe');
        let IE_Medicamentocv = $('#medicamentocv_informe');
        let IE_Solicitante = $('#solicitante_informe');
        let IE_Freciencia = $('#frecuencia_informe');
        let IE_Ritmo = $('#ritmo_informe');
        let IE_p = $('#p_informe');
        let IE_pr = $('#pr_informe');
        let IE_Qrs = $('#qrs_informe');
        let IE_Aqrs = $('#aqrs_informe');
        let IE_Qt = $('#qt_informe');
        let IE_Hallazgos = $('#hallazgos_informe');


        $(document).ready(function() {

            mostrarPacientesEvaluacion();
            ShowEvaluaciones("{{ $this->FechaActual('Y-m-d') }}");
            FormEvaluacion(TablaPacientesEvaluacion, '#tabla_pacientes_evaluacion tbody');
            FormElectrocardiograma(TablaPacientesEvaluacion, '#tabla_pacientes_evaluacion tbody');
            EditarEvaluationPreOperativa('#tabla_lista_evaluacion tbody');
            MostrarInformesElectrocardiogramasEdit("{{ $this->FechaActual('Y-m-d') }}");
            EditarElectroCardiograma('#tabla_informes_electrocardiograma_edit tbody');
            $('#tab_evaluacion_informe button').on('click', function(evento) {
                evento.preventDefault();
                let IdControls = $(this)[0].id;

                if (IdControls === 'evaluacion_pre_operatoria_') {

                    /// mostramos los pacientes y las evaluaciones para la pre operatoria
                    mostrarPacientesEvaluacion();
                    FormEvaluacion(TablaPacientesEvaluacion, '#tabla_pacientes_evaluacion tbody');
                    FormElectrocardiograma(TablaPacientesEvaluacion, '#tabla_pacientes_evaluacion tbody');
                } else {

                    if (IdControls === 'ver_informes_') {
                        mostrarTodoInformeElectrocardiograma("{{ $this->FechaActual('Y-m-d') }}");
                        InformeElectroCardiogramaPdf('#tabla_informes_electrocardiograma tbody');
                    } else {
                        /// informe electrocardiograma
                        ShowEvaluacionesTwo("{{ $this->FechaActual('Y-m-d') }}");
                        PdfEvaluationPreOperatoria(TablaEvaluacionPreOperatoriaShow,
                            '#tabla_lista_evaluacion_show tbody');
                    }

                }

                $(this).tab("show");
            });

            $('#fecha_evaluacion_view').change(function() {
                ShowEvaluaciones($(this).val());
                EditarEvaluationPreOperativa('#tabla_lista_evaluacion tbody');
            });

            $('#fecha_evaluacion_view_show').change(function() {
                ShowEvaluacionesTwo($(this).val());
            });
            $('#fecha_informe_').change(function(r) {
                MostrarInformesElectrocardiogramasEdit($(this).val());
            });
            $('#fecha_informes_data').change(function() {
                mostrarTodoInformeElectrocardiograma($(this).val());
            });

            $('#cancelar').click(function() {
                ControlButton = 'save';
                $('#form_evaluacion').hide(600);
                LimpiarFormularioEvaluation();
            });
            $('#cancel_informe_electro').click(function() {
                ControlButtonUpdateInforme = 'save';
                $('#form_informe_electrocardiograma').hide(600);
                ClearFormElectrocardiograma();
            });

            $('#save_informe_electro').click(function() {
                if (ControlButtonUpdateInforme === 'save') {

                    saveElectrocardiograma(
                        IE_Pa, IE_Obeso, IE_Indicacion, IE_EkgAtenrior,
                        IE_Medicamentocv, IE_Solicitante, IE_Freciencia, IE_Ritmo,
                        IE_p, IE_pr, IE_Qrs, IE_Aqrs, IE_Qt, IE_Hallazgos,
                        PACIENTEID
                    );
                } else {
                     updateInformeElectrocardiograma(
                        ELECTROCARDIOGRAMAID, IE_Pa,
                        IE_Obeso,
                        IE_Indicacion,
                        IE_EkgAtenrior,
                        IE_Medicamentocv,
                        IE_Solicitante,
                        IE_Freciencia,
                        IE_Ritmo,
                        IE_p,
                        IE_pr,
                        IE_Qrs,
                        IE_Aqrs,
                        IE_Qt,
                        IE_Hallazgos,
                    );
                }
            });

            $('#save_evaluacion').click(function() {

                if (Ev_molestias_importantes.val().trim().length == 0) {
                    Ev_molestias_importantes.focus();
                } else {
                    if (Ev_resultados_est.val().trim().length == 0) {
                        Ev_resultados_est.focus();
                    } else {
                        if (Ev_goldman.val().trim().length == 0) {
                            Ev_goldman.focus();
                        } else {
                            if (Ev_Asa.val().trim().length == 0) {
                                Ev_Asa.focus();
                            } else {

                                if (ControlButton === 'save') {

                                    saveEvaluation(
                                        Ev_Indicaciones, Ev_ant_importantes, Ev_molestias_importantes,
                                        Ev_Pa, Ev_fcc,
                                        Ev_Fr, Ev_To, Ev_sato_dos, Ev_Peso, Ev_exa_fisico,
                                        Ev_resultados_est, Ev_goldman,
                                        Ev_Asa, Ev_Sugerencias, PACIENTEID
                                    );
                                } else {
                                    updateEvaluationPreOperatoria(EVALUATIONID, Ev_Indicaciones,
                                        Ev_ant_importantes, Ev_molestias_importantes, Ev_Pa, Ev_fcc,
                                        Ev_Fr, Ev_To, Ev_sato_dos, Ev_Peso, Ev_exa_fisico,
                                        Ev_resultados_est, Ev_goldman, Ev_Asa,
                                        Ev_Sugerencias
                                    )
                                }
                            }
                        }
                    }
                }
            });
        });

        function mostrarPacientesEvaluacion() {
            TablaPacientesEvaluacion = $('#tabla_pacientes_evaluacion').DataTable({
                responsive: true,
                retrieve: true,
                processing: true,
                language: SpanishDataTable(),
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                ajax: {
                    url: RUTA + "ver-pacientes_del_medico?token_=" + TOKEN,
                    method: "GET",
                    dataSrc: "personas",
                },
                columns: [{
                        "data": "documento"
                    },
                    {
                        "data": "documento"
                    },
                    {
                        "data": "paciente_"
                    },
                    {
                        "data": "paciente_",
                        render: function() {
                            return `<button class='btn btn-warning btn-sm' id='select_paciente'><i class='bx bxs-select-multiple' ></i></button>
                            <button class='btn btn-danger btn-sm' id='select_paciente_informe'><i class='bx bx-file'></i></button>`
                        }
                    }
                ]
            });
            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
            TablaPacientesEvaluacion.on('order.dt search.dt', function() {
                TablaPacientesEvaluacion.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }

        function FormEvaluacion(Tabla, Tbody) {
            $(Tbody).on('click', '#select_paciente', function() {
                let fila = $(this).parents("tr");

                if (fila.hasClass("child")) {
                    fila = fila.prev();
                }
                let Data = Tabla.row(fila).data();
                PACIENTEID = Data.id_paciente;
                
                LimpiarFormularioEvaluation();
                $('#form_evaluacion').show();
                $('#form_informe_electrocardiograma').hide();
                /// activamos el formulario
                $('#paciente_evaluacion').val(Data.paciente_);
                BajadaScroll('.card_scrol', 400);
                $('#form_evaluacion').show(300);
                $('#molestias_importantes').focus();
            });
        }

        /// llenar el formulario de electrocardiograma
        function FormElectrocardiograma(Tabla, Tbody) {
            $(Tbody).on('click', '#select_paciente_informe', function() {
                let fila = $(this).parents("tr");

                if (fila.hasClass("child")) {
                    fila = fila.prev();
                }
                let Data = Tabla.row(fila).data();

                ClearFormElectrocardiograma();
                ControlButtonUpdateInforme = 'save';
                PACIENTEID = Data.id_paciente;
                $('#form_evaluacion').hide();
                $('#paciente_informe').val(Data.paciente_);
                BajadaScroll('.card_scrol', 400);
                $('#form_informe_electrocardiograma').show();
                $('#pa_informe').focus();
            });
        }

        function LimpiarFormularioEvaluation() {
            ControlButton = 'save';
            $('#indicaciones').val("");
            $('#ant_importantes').val("");
            $('#molestias_importantes').val("");
            $('#pa').val("");
            $('#fcc').val("");
            $('#fr').val("");
            $('#to').val("");
            $('#sato_dos').val("");
            $('#peso').val("");
            $('#exa_fisico').val("");
            $('#resultados_est').val("");
            $('#goldman').val("");
            $('#asa').val("");
            $('#sugerencias').val("");
            $('#paciente_evaluacion').val("");

            //PACIENTEID = null;
        }

        /// Limpiar formulario de informe electrocardiograma
        function ClearFormElectrocardiograma() {
            $('#pa_informe').val("");
            $('#obeso_informe').val("");
            $('#ekg_anterior_informe').val("");
            $('#indicacion_informe').val("");
            $('#medicamentocv_informe').val("");
            $('#solicitante_informe').val("");
            $('#frecuencia_informe').val("");
            $('#ritmo_informe').val("");
            $('#p_informe').val("");
            $('#pr_informe').val("");
            $('#qrs_informe').val("");
            $('#aqrs_informe').val("");
            $('#qt_informe').val("");
            $('#hallazgos_informe').val("");
            $('#pa_informe').focus();

        }
        /// guardar la evaluación de los pacientes
        function saveEvaluation(
            indicaciones_, ant_importantes_, molestias_importantes_, pa_, fcc_, fr_, to_data,
            sato_dos_data, peso_data, det_ex_fisico_, resultados_, goldman_, asa_data, sugerencias_, atencion_id_data
        ) {
            $.ajax({
                url: RUTA + "paciente/atenciones_medica/evalacion_pre_operatoria/save",
                method: "POST",
                data: {
                    token_: TOKEN,
                    indicaciones: indicaciones_.val(),
                    ant_importantes: ant_importantes_.val(),
                    molestias_importantes: molestias_importantes_.val(),
                    pa: pa_.val(),
                    fcc: fcc_.val(),
                    fr: fr_.val(),
                    to_: to_data.val(),
                    sato_dos: sato_dos_data.val(),
                    peso: peso_data.val(),
                    det_ex_fisico: det_ex_fisico_.val(),
                    resultados: resultados_.val(),
                    goldman: goldman_.val(),
                    asa: asa_data.val(),
                    sugerencias: sugerencias_.val(),
                    atencion_id: atencion_id_data
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.response === 'ok') {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "La evaluación pre operatoria a sido registrada sin problemas 😀",
                            icon: "success",
                            target: document.getElementById('modal_evaluacion_pre_operatoria')
                        }).then(function() {
                            ShowEvaluaciones("{{ $this->FechaActual('Y-m-d') }}");
                            indicaciones_.val("");
                            ant_importantes_.val("");
                            molestias_importantes_.val("");
                            pa_.val("");
                            fcc_.val("");
                            fr_.val("");
                            to_data.val("");
                            sato_dos_data.val("");
                            peso_data.val("");
                            det_ex_fisico_.val("");
                            resultados_.val("");
                            goldman_.val("");
                            asa_data.val("");
                            sugerencias_.val("");
                            PACIENTEID = null;
                            $('#form_evaluacion').hide(600);
                        });
                    } else {
                        if (response.response === 'existe') {
                            Swal.fire({
                                title: "Mensaje del sistema!",
                                text: "Error, no se puede registrar dos veces la evaluación pre operatoria de un paciente en la misma fecha,espere al día siguiente para registrar uno nuevo",
                                icon: "warning"
                            });
                        } else {
                            Swal.fire({
                                title: "Mensaje del sistema!",
                                text: "Error al registrar la evaluación pre operatoria 😔😢",
                                icon: "error",
                                target: document.getElementById('modal_evaluacion_pre_operatoria')
                            });
                        }
                    }
                }
            })
        }

        function ShowEvaluaciones(fecha_) {
            TablaEvaluacionPreOperatoria = $('#tabla_lista_evaluacion').DataTable({
                language: SpanishDataTable(),
                bDestroy: true,
                processing: true,
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                ajax: {
                    url: RUTA + "mostrar/evaluaciones/pre_operativas?fecha=" + fecha_,
                    method: "GET",
                    dataSrc: "evaluacion",
                },
                columns: [{
                        "data": "fecha_"
                    },
                    {
                        "data": "id_evaluacion"
                    },
                    {
                        "data": "fecha_"
                    },
                    {
                        "data": "paciente_"
                    },

                    {
                        "data": null,
                        render: function() {
                            return `
                            <button class='btn rounded btn-outline-warning btn-sm' id='editar_ev'><i class='bx bx-edit-alt'></i></button>
                            `;
                        }
                    }
                ],
                columnDefs: [{
                    "sClass": "hide_me",
                    target: 1
                }]

            });
            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
            TablaEvaluacionPreOperatoria.on('order.dt search.dt', function() {
                TablaEvaluacionPreOperatoria.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

        }

        function ShowEvaluacionesTwo(fecha_) {
            TablaEvaluacionPreOperatoriaShow = $('#tabla_lista_evaluacion_show').DataTable({
                language: SpanishDataTable(),
                bDestroy: true,
                processing: true,
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                ajax: {
                    url: RUTA + "mostrar/evaluaciones/pre_operativas?fecha=" + fecha_,
                    method: "GET",
                    dataSrc: "evaluacion",
                },
                columns: [{
                        "data": "fecha_"
                    },
                    {
                        "data": "id_evaluacion"
                    },
                    {
                        "data": "fecha_"
                    },
                    {
                        "data": "paciente_"
                    },

                    {
                        "data": null,
                        render: function() {
                            return `
                            <button class='btn rounded btn-outline-danger btn-sm' id='pdf_ev_show'><i class='bx bxs-file-pdf'></i></button>
                            `;
                        }
                    }
                ],
                columnDefs: [{
                    "sClass": "hide_me",
                    target: 1
                }]

            });
            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
            TablaEvaluacionPreOperatoriaShow.on('order.dt search.dt', function() {
                TablaEvaluacionPreOperatoriaShow.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

        }
        /// mostrar el reporte de la evaluación pre operatoria
        function PdfEvaluationPreOperatoria(Tabla, Tbody) {
            $(Tbody).on('click', '#pdf_ev_show', function() {
                /// obtenemos la fila seleccionada
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }


                let IdEvaluation = fila.find('td').eq(1).text();
                window.open(RUTA + 'paciente/evaluacion_pre_operatoria?v=' + IdEvaluation, 'blank_');
            });
        }
        /// editar una evaluación pre operativa
        function EditarEvaluationPreOperativa(Tbody) {
            $(Tbody).on('click', '#editar_ev', function() {
                /// obtenemos la fila 
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                ControlButton = 'editar';
                EVALUATIONID = fila.find('td').eq(1).text();

                /// editamos los datos
                $.ajax({
                    url: RUTA + "paciente/evaluation_pre_operativa/editar/" + EVALUATIONID,
                    method: "GET",
                    success: function(response) {
                        response = JSON.parse(response);

                        $('#indicaciones').focus();
                        $('#paciente_evaluacion').val(response.response.apellidos + " " + response
                            .response.nombres);
                        $('#indicaciones').val(response.response.indicaciones);
                        $('#ant_importantes').val(response.response.antecedentes_importantes);
                        $('#molestias_importantes').val(response.response.molestias_importantes);
                        $('#pa').val(response.response.pa);
                        $('#fcc').val(response.response.fcc);
                        $('#fr').val(response.response.fr);
                        $('#to').val(response.response.to_);
                        $('#sato_dos').val(response.response.sato_dos);
                        $('#peso').val(response.response.peso);
                        $('#exa_fisico').val(response.response.detalle_ex_fisico);
                        $('#resultados_est').val(response.response.resultados_estudios);
                        $('#goldman').val(response.response.riesgo_quir_goldman);
                        $('#asa').val(response.response.riesgo_quir_asa);
                        $('#sugerencias').val(response.response.sugerencias);
                    }
                });

            });
        }

        /// actualizar los datos de la evaluación pre operatoria de un paciente
        function updateEvaluationPreOperatoria(id, indicaciones_update, ant_importantes_update, molestias_update, pa_update,
            fcc_update, fr_update, to_update,
            sato_dos_update, peso_update, examen_fisico_update, resultados_update, riesgo_goldman_update, riesgo_asa_update,
            sugerencias_update
        ) {
            $.ajax({
                url: RUTA + "paciente/evaluation_pre_operatoria/update/" + id,
                method: "POST",
                data: {
                    token_: TOKEN,
                    indicaciones: indicaciones_update.val(),
                    ant_importantes: ant_importantes_update.val(),
                    molestias: molestias_update.val(),
                    pa: pa_update.val(),
                    fcc: fcc_update.val(),
                    fr: fr_update.val(),
                    to: to_update.val(),
                    sato_dos: sato_dos_update.val(),
                    peso: peso_update.val(),
                    examen_fisico: examen_fisico_update.val(),
                    resultados: resultados_update.val(),
                    riesgo_goldman: riesgo_goldman_update.val(),
                    riesgo_asa: riesgo_asa_update.val(),
                    sugerencias: sugerencias_update.val(),
                },
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.response === 'ok') {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Los datos de la evaluación pre operatoria se han modificado correctamente.😁",
                            icon: "success"
                        }).then(function() {
                            ShowEvaluaciones("{{ $this->FechaActual('Y-m-d') }}");

                            LimpiarFormularioEvaluation();
                            $('#form_evaluacion').hide(700);
                            subidaScroll('.card_scrol', 200);
                        });
                    } else {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Error al modificar los datos de la evaluación pre operatoria",
                            icon: "error"
                        });
                    }
                }
            });
        }

        /// guardar el informe de electrocardiograma
        function saveElectrocardiograma(
            pa_informe, obeso_informe, indicacion_informe, ekg_anterior_informe,
            medicamento_cv_informe, solicitante_informe, frecuencia_informe, ritmo_informe,
            p_data_informe, pr_data_informe, qrs_data_informe, aqrs_data_informe, qt_data_informe,
            hallazgos_informe, paciente_id_informe
        ) {
            $.ajax({
                url: RUTA + "informe/electrocardiograma/store",
                method: "POST",
                data: {
                    token_: TOKEN,
                    pa: pa_informe.val(),
                    obeso: obeso_informe.val(),
                    indicacion: indicacion_informe.val(),
                    ekg_anterior: ekg_anterior_informe.val(),
                    medicamento_cv: medicamento_cv_informe.val(),
                    solicitante: solicitante_informe.val(),
                    frecuencia: frecuencia_informe.val(),
                    ritmo: ritmo_informe.val(),
                    p_data: p_data_informe.val(),
                    pr_data: pr_data_informe.val(),
                    qrs_data: qrs_data_informe.val(),
                    aqrs_data: aqrs_data_informe.val(),
                    qt_data: qt_data_informe.val(),
                    hallazgos: hallazgos_informe.val(),
                    paciente_id: paciente_id_informe
                },
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.response === 'ok') {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Los datos del informe de electrocardiograma se han guardado correctamente!😁",
                            icon: "success"
                        }).then(function() {
                            MostrarInformesElectrocardiogramasEdit(
                            "{{ $this->FechaActual('Y-m-d') }}");
                            ClearFormElectrocardiograma();
                        });
                    } else {
                        if (response.response === 'existe') {
                            Swal.fire({
                                title: "Mensaje del sistema!",
                                text: "No se permite registrar doble informe del mismo paciente en una misma fecha, espere al siguiente día para generar uno nuevo!.",
                                icon: "warning"
                            });
                        } else {
                            Swal.fire({
                                title: "Mensaje del sistema!",
                                text: "Error al registrar el informe de electrocardiograma del paciente!😢😔"
                            });
                        }
                    }
                }
            });
        }

        ///mostrar los informes de electrocardiograma
        function MostrarInformesElectrocardiogramasEdit(fecha_) {
            TablaInformeElectrocardiogramaEdit = $('#tabla_informes_electrocardiograma_edit').DataTable({
                language: SpanishDataTable(),
                bDestroy: true,
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                ajax: {
                    url: RUTA + "informe/electrocardiograma/all?fecha=" + fecha_,
                    method: "GET",
                    dataSrc: "response",
                },
                columns: [{
                        "data": "fecha_"
                    },
                    {
                        "data": "id_informe_electro"
                    },
                    {
                        "data": "fecha_"
                    },
                    {
                        "data": "paciente_"
                    },
                    {
                        "data": null,
                        render: function() {
                            return `
                    <button class='btn btn-outline-warning rouded btn-sm' id='editar_electrocardiograma'><i class='bx bx-edit-alt'></i></button>
                    `;
                        }
                    }
                ],
                columnDefs: [{
                    "sClass": "hide_me",
                    target: 1
                }]
            });
            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
            TablaInformeElectrocardiogramaEdit.on('order.dt search.dt', function() {
                TablaInformeElectrocardiogramaEdit.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }

        /// editar electrocardiogramas registrados
        function EditarElectroCardiograma(Tbody) {
            $(Tbody).on('click', '#editar_electrocardiograma', function() {
                /// obtenemos la fila seleccionada
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }
                ControlButtonUpdateInforme = 'update';
                ELECTROCARDIOGRAMAID = fila.find('td').eq(1).text();

                /// aplicado aja para editar de electrocardiograma
                $('#fecha_informe').val(fila.find('td').eq(2).text());
                $.ajax({
                    url: RUTA + "paciente/informe/editar/electrocard/" + ELECTROCARDIOGRAMAID,
                    method: "GET",
                    success: function(response) {
                        response = JSON.parse(response);
                        $('#paciente_informe').val(response.response.apellidos + " " + response.response
                            .nombres);
                        $('#pa_informe').val(response.response.pa);
                        $('#obeso_informe').val(response.response.obeso);
                        $('#ekg_anterior_informe').val(response.response.ekg_anterior);
                        $('#medicamentocv_informe').val(response.response.medicamento_cv);
                        $('#indicacion_informe').val(response.response.indicacion);
                        $('#solicitante_informe').val(response.response.solicitante);
                        $('#frecuencia_informe').val(response.response.frecuencia);
                        $('#ritmo_informe').val(response.response.ritmo);
                        $('#p_informe').val(response.response.p_data);
                        $('#pr_informe').val(response.response.pr_data);
                        $('#qrs_informe').val(response.response.qrs_data);
                        $('#aqrs_informe').val(response.response.aqrs_data);
                        $('#qt_informe').val(response.response.qt_data);
                        $('#hallazgos_informe').val(response.response.hallazgos);
                    }
                });
            });
        }

        /// mostrar los informes electrocardiogramas registrados
        function mostrarTodoInformeElectrocardiograma(fecha_) {
            TablaInformeElectrocardiograma = $('#tabla_informes_electrocardiograma').DataTable({
                language: SpanishDataTable(),
                bDestroy: true,
                processing: true,
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                ajax: {
                    url: RUTA + "informe/electrocardiograma/all?fecha=" + fecha_,
                    method: "GET",
                    dataSrc: "response",
                },
                columns: [{
                        "data": "fecha_"
                    },
                    {
                        "data": "id_informe_electro"
                    },
                    {
                        "data": "fecha_"
                    },
                    {
                        "data": "paciente_"
                    },
                    {
                        "data": null,
                        render: function() {
                            return `
                    <button class='btn btn-outline-danger rouded btn-sm' id='print_informe_electro'><i class='bx bxs-file-pdf'></i></button>
                    `;
                        }
                    }
                ],
                columnDefs: [{
                    "sClass": "hide_me",
                    target: 1
                }]
            });
            TablaInformeElectrocardiograma.on('order.dt search.dt', function() {
                TablaInformeElectrocardiograma.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }
        /// mostrar el reporte del informe electrocardiograma
        function InformeElectroCardiogramaPdf(Tbody) {
            $(Tbody).on('click', '#print_informe_electro', function() {
                /// obtenemos la fila seleccionada
                let fila = $(this).parents('tr');
                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                /// obtenemos el id del informe
                let IdInformePdf = fila.find('td').eq(1).text();
                window.open(RUTA + "paciente/informe/electrocardiograma/" + IdInformePdf, 'blank_');
            });
        }

        /// modificar los datos del electrocardiograma registrado
        function updateInformeElectrocardiograma(
            id, pa_informe_electro,
            obeso_informe_electro,
            indicacion_informe_electro,
            ekg_anterior_informe_electro,
            medicamento_cv_informe_electro,
            solicitante_informe_electro,
            frecuencia_informe_electro,
            ritmo_informe_electro,
            p_data_informe_electro,
            pr_data_informe_electro,
            qrs_data_informe_electro,
            aqrs_data_informe_electro,
            qt_data_informe_electro,
            hallazgos_informe_electro,
        ) {
            $.ajax({
                url: RUTA + "/paciente/informe/update/electrocard/" + id,
                method: "POST",
                data: {
                    token_: TOKEN,
                    pa: pa_informe_electro.val(),
                    obeso: obeso_informe_electro.val(),
                    indicacion: indicacion_informe_electro.val(),
                    ekg_anterior: ekg_anterior_informe_electro.val(),
                    medicamento_cv: medicamento_cv_informe_electro.val(),
                    solicitante: solicitante_informe_electro.val(),
                    frecuencia: frecuencia_informe_electro.val(),
                    ritmo: ritmo_informe_electro.val(),
                    p_data: p_data_informe_electro.val(),
                    pr_data: pr_data_informe_electro.val(),
                    qrs_data: qrs_data_informe_electro.val(),
                    aqrs_data: aqrs_data_informe_electro.val(),
                    qt_data: qt_data_informe_electro.val(),
                    hallazgos: hallazgos_informe_electro.val(),

                },
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.response === 'ok') {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Los datos del electrocardiograma se han modificado correctamente! 😁",
                            icon: "success"
                        }).then(function() {
                            MostrarInformesElectrocardiogramasEdit("{{ $this->FechaActual('Y-m-d') }}");
                            ClearFormElectrocardiograma();
                        });
                    } else {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Error al modificar los datos del electrocardiograma! 😔😢",
                            icon: "error"
                        });
                    }
                }
            });
        }
    </script>
@endsection
