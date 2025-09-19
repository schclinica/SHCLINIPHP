@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Generar-recibo')

@section('css')
    <style>
        #detalle_servicios>thead>tr>th {
            background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);
            color: #b6e7a9;
        }

        #tabla_paciente_search>thead>tr>th {
            background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);;
            color: #E6E6FA;
        }

        #tabla_servicios>thead>tr>th {
            background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);;
            color: #E6E6FA;
        }

        #tabla_recibos>thead>tr>th {
            background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);;
            color: #E6E6FA;
        }

        #serie,#documento {
            background-color: #ccdfc0;
            color: #0070d4;
            font-style: italic;
            font-stretch: condensed;
            border: 2px solid #5491ee;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
        }
        #paciente {
            background-color: #cfcbfd;
            color: #0070d4;
            font-style: italic;
            font-stretch: condensed;
            border: 2px solid #5491ee;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
        }
    </style>
@endsection
@section('contenido')
    <div class="card-header  text-white" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);" >
        <ul class="nav nav-tabs card-header-tabs border-primary" id="tab-recibo">
            <li class="nav-item bg">
                <a class="nav-link active text-primary" aria-current="true" href="#generar_recibo" id="generar_recibo__"> <i
                        class='bx bxs-file-plus'></i></i>
                    Generar recibo
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" style="color:orangered" href="#show_recibos" tabindex="-1" aria-disabled="true"
                    id="show_recibos__"><i class='bx bxs-file-doc'></i>
                    Recibos generados
                </a>
            </li>

        </ul>
    </div>
    <div class="row">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="generar_recibo" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="col-12 mb-3">
                    <div class="card" style="background-color: #F8F8FF">
                        <div class="card-header">
                            <div class="row">
                               <div class="col-xl-8 col-lg-8 col-12">
                                 <h5><b class="text-primary float-start letra">Facturación</b></h5>
                             </div>
                             <div class="col-xl-4 col-lg-4 col-12">
                               <div class="form-floating">
                                <input type="text" class="form-control" id="serie" readonly>
                                    <label for="serie">Serie Recibo</label>
                               </div>
                             </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-text">
                                <h5><b>Datos del paciente|cliente</b></h5>
                            </div>
                            <div class="row">
                              <div class="col-xl-4 col-lg-4 col-md-5 col-sm-6 col-12 mx-xl-0 mb-lg-0 mb-md-0 mb-1">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="# documento..." id="documento" autofocus>
                                        <button class="btn btn-outline-primary" id="search_paciente"><i class="fas fa-search"></i>
                                            Buscar</button>
                                    </div>
                                </div>
                            <div class="col-xl-8 col-lg-8 col-md-7 col-sm-6 col-12">
                                    <input type="text" class="form-control" id="paciente" placeholder="PACIENTE....." disabled>
                                </div>
                            </div>
                            <div class="card-text my-2">
                                <h5><b>Servicios adquiridos.</b></h5>
                            </div>
                            <div class="card-text table-responsive">
                                <div class="col-auto float-end">
                                    <button class="btn btn-rounded btn-primary mb-2" id="consultar_servicios">Consultar
                                        servicios <i class="fas fa-search"></i></button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="detalle_servicios">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="p-4">Quitar</th>
                                                <th scope="col" class="p-4">Cantidad</th>
                                                <th scope="col" class="p-4">Descripción</th>
                                                <th scope="col" class="p-4">Precio
                                                    <b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</b>
                                                </th>
                                                <th scope="col"  class="p-4">Importe
                                                    <b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</b>
                                                </th>

                                                <th class="scope d-none">Importe Medico
                                                    <b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</b>
                                                </th>

                                                <th class="scope d-none">Importe Clinica
                                                    <b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</b>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div
                                    class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-sm-0 mt-1">
                                    <button class="btn btn-rounded btn-outline-primary form-control"
                                        id="save_recibo"><b>Generar
                                            recibo <i class="fas fa-file-pdf"></i></b></button>
                                </div>

                                <div
                                    class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-sm-0  mt-1">
                                    <button class="btn btn-rounded btn-outline-danger form-control"
                                        id="cancel_recibo"><b>Cancelar
                                            <i class="fas fa-cancel"></i></b></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="show_recibos" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="card" style="background-color: #F8F8FF">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-striped table-hover responsive nowrap"
                                id="tabla_recibos" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="py-3 letra">#</th>
                                        <th class="py-3 letra">VER</th>
                                        <th class="py-3 letra">NUM.RECIBO</th>
                                        <th class="py-3 letra">FECHA</th>
                                        <th class="py-3 letra">PACIENTE</th>
                                        <th>TOTAL IMPORTE   <b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</b></th>
                                        
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- modal buscar los pacientes del médico--- --}}
    <div class="modal fade" id="modal_buscar_paciente" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:linear-gradient(to bottom, rgba(179,220,237,1) 0%,rgba(41,184,229,1) 50%,rgba(188,224,238,1) 100%);">
                    <h4 class="float-start text-white letra">Mis pacientes</h4>
                    <button class="btn btn-rounded btn-outline-danger" id="exit_"><b>Salir X</b></button>
                </div>
                <div class="modal-body">
                        <table class="table table-bordered table-sm table-hover responsive nowrap" id="tabla_paciente_search"
                            style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="py-3"># DOCUMENTO</th>
                                    <th class="py-3">PACIENTE</th>
                                    <th class="py-3">DESEA UN RECIBO?</th>
                                </tr>
                            </thead>

                        </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modal buscar los servicios del médico--- --}}
    <div class="modal fade" id="modal_servicios" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                    <h4 class="h4 float-start text-white letra">Mis servicios</h4>
                    <button class="btn btn-rounded btn-outline-danger" id="exit_servicios"><b>Salir X</b></button>
                </div>
                <div class="modal-body">
                    <b>Especialidad (*)</b>
                    <select name="especialidad_" id="especialidad_" class="form-select mt-1">
                        @if (isset($Data) and count($Data) > 0)
                            @foreach ($Data as $esp)
                                <option value="{{ $esp->id_especialidad }}">{{ $esp->nombre_esp }}</option>
                            @endforeach
                        @endif

                    </select>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-hover responsive nowrap" id="tabla_servicios" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>ESPECIALIDAD</th>
                                    <th class="py-3 letra">SERVICIO</th>
                                    <th class="py-3 letra">PRECIO  <b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</b></th>
                                    <th class="py-3 letra">SELECCIONAR</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
 {{---EDITAR RECIBO ----}}
 <div class="modal fade" id="modal_editar_recibo" >
     <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-4" style="background: #63efed">
                <h5 class="text-white letra">Editar Pago <img src="{{$this->asset('img/icons/unicons/dinero.ico')}}" class="menu-icon" alt=""></h5>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <label for="num_recibo_editar"><b class="letra"># RECIBO </b></label>
                        <input type="text" id="num_recibo_editar" class="form-control" readonly>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6 col-12">
                        <label for="fecha_recibo_editar"><b class="letra">FECHA EMITIDO</b></label>
                        <input type="text" id="fecha_recibo_editar" class="form-control" readonly>
                    </div>

                    <div class="col-xl-7 col-lg-5 col-12">
                        <label for="paciente_recibo_editar"><b class="letra">PACIENTE </b></label>
                        <input type="text" id="paciente_recibo_editar" class="form-control" readonly>
                    </div>

                    <div class="col-12 table-responsive-sm mt-3">
                        <button class="btn_blue mb-1" id="add_servicio_detalle_editar">Agregar servicio <i class='bx bx-list-plus'></i></button>
                        <table class="table table-bordered table-striped table-hover table-sm table-hover">
                             <thead style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 55%,rgba(30,105,222,1) 88%);">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th class="px-2 py-4 text-white text-center">Quitar</th>
                                    <th class="px-2 py-4 text-white text-center">Cantidad</th>
                                    <th class="px-2 py-4 text-white">Descripción</th>
                                    <th class="px-2 py-4 text-white text-center">Precio   <b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</b></th>
                                    <th class="px-2 py-4 text-white text-center">Importe   <b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</b></th>
                                </tr>
                             </thead>
                             <tbody id="lista_detalle_recibo_editar"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-4">
                <button class="btn btn-success rounded" id="update_recibo"><b>Guardar <i class='bx bx-save'></i></b></button>
                <button class="btn btn-danger rounded" id="cancel_recibo_editar"><b>Cancelar <i class='bx bx-x'></i></b></button>
            </div>
        </div>
     </div>
 </div>
 

 <div class="modal fade" id="modal_servicios_edicion" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 55%,rgba(30,105,222,1) 88%);">
                    <h4 class="h4 float-start text-white">Mis servicios</h4>
                    <button class="btn btn-rounded btn-outline-danger" id="exit_serviciosedit"><b>Salir X</b></button>
                </div>
                <div class="modal-body">
                    <b>Especialidad (*)</b>
                    <select name="especialidad_" id="especialidadedit_" class="form-select mt-1">
                        @if (isset($Data) and count($Data) > 0)
                            @foreach ($Data as $esp)
                                <option value="{{ $esp->id_especialidad }}">{{ $esp->nombre_esp }}</option>
                            @endforeach
                        @endif

                    </select>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm responsive nowrap" id="tabla_serviciosedit" style="width: 100%">
                            <thead style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 55%,rgba(30,105,222,1) 88%);">
                                <tr>
                                    <th class="py-3 letra text-white">ESPECIALIDAD</th>
                                    <th scope="col" class="py-3 letra text-white">SERVICIO</th>
                                    <th scope="col" class="py-3 letra text-white">PRECIO  <b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</b></th>
                                    <th scope="col" class="py-3 letra text-white">SELECCIONAR</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{---ALERTA MODAL PARA APERTURA UNA CAJA ---}}
<div class="modal fade" id="modal_alerta_para_caja" data-bs-backdrop="static">
    <div class="modal-dialog">
       <div class="modal-content">
        <div class="modal-header" style="background:linear-gradient(to bottom, rgba(255,103,15,1) 32%,rgba(255,103,15,1) 32%,rgba(255,103,15,1) 62%);">
            <h5 class="text-white letra" id="text_precios"><b>Mensaje del sistema!!!!</b></h5>
        </div>
        <div class="modal-body" >
          <b>Antes de Continuar debes de aperturar una caja!!!</b>  
       </div>
       <div class="modal-footer justifi-content-center border-2">
          <button class="btn_3d" id="ir_caja"><b>Aperturar Caja</b>  <i class="fa-solid fa-money-check-dollar"></i></button>
       </div>
    </div>
</div>
</div>
@endsection

@section('js')
    <script src="{{ URL_BASE }}public/js/control.js"></script>
    <script>
        var RUTA = "{{ URL_BASE }}" // la url base del sistema
        var TOKEN = "{{ $this->Csrf_Token() }}";
        var TablaPacientesSearch;
        var TablaServiciosMedico;
        var TablaServicioMedicoEditar;
        var ID_PACIENTE, CITA_MEDICA_ID;
        var Tabla_Recibos;
        var MONTOMEDICO;
        var MONTOCLINICA;
        var RECIBOID;
        var CITAID;
     VerifyCajaAbierta();
        $(document).ready(function() {
            GenerarIdentificador();
            showCestaDetalleServiceRecibo();
            ConfirmquitarServiceDetalle();
            ConsultaServiciosMedico($('#especialidad_').val());
            AddCestaServiceDetalle(TablaServiciosMedico, '#tabla_servicios tbody');
            
            ConsultaServiciosMedicoEdition($('#especialidadedit_').val());
            AddServiceDetalleEdition(TablaServicioMedicoEditar,'#tabla_serviciosedit tbody');

            /// abrir modal para buscar al paciente
            $('#search_paciente').click(function() {
                $('#modal_buscar_paciente').modal("show");
                BuscarPacienteParaRecibo();

                SeleccionarPacienteParRecibo(TablaPacientesSearch, '#tabla_paciente_search tbody')

                ConfirmCancelReciboPaciente(TablaPacientesSearch, '#tabla_paciente_search tbody');
            });
$('#ir_caja').click(function(){
location.href= RUTA+"apertura/caja";
});
            /// abrir modal para consultar los servicios del médico para añadir a detalle del recibo
            $('#consultar_servicios').click(function() {
                $('#modal_servicios').modal("show");
                TablaServiciosMedico.column(0).search($('#especialidad_ option:selected').text()).draw();
            });

            $('#especialidad_').change(function(){
             
                 TablaServiciosMedico.column(0).search($('#especialidad_ option:selected').text()).draw();
                
            })
            /// salir de la ventana de buscar pacientes
            $('#exit_').click(function() {
                 $('#especialidad').prop("selectedIndex",0);
                $('#modal_buscar_paciente').modal("hide");
            });
            /// salir de la ventana de servicios del médico
            $('#exit_servicios').click(function() {
                $('#modal_servicios').modal("hide");
                $('#especialidad_').prop("selectedIndex",0);
            });

            $('#exit_serviciosedit').click(function() {
                $('#modal_servicios_edicion').modal("hide");
                $('#especialidadedit_').prop("selectedIndex",0);
            });

            $('#detalle_servicios tbody').on('keypress','#precio_por_service',function(evento){
                if(evento.which == 13){
                    evento.preventDefault();

                    if($(this).val().length==0){
                        $(this).focus();
                    }else{
                        if($(this).val().trim() <=0){
                            $(this).val("");
                            $(this).focus();
                            Swal.fire({
                                title:"MENSAJE DEL SISTEMA!!!",
                                text:"INGRESE UN PRECIO MAYOR QUE 0!!",
                                icon:"error"
                            });
                        }else{
                             let fila = $(this).closest("tr");
                             let descripcionService = fila.find("td").eq(2).text();
                             AplicarDescuento(descripcionService,$(this).val());
                        }
                    }
                }
            });

            $('#save_recibo').click(function() {
                if ($("#paciente").val().trim().length > 0) {

                    if (document.getElementById('detalle_servicios').rows.length > 2) {
                        /// aquí llamamos al método

                        saveRecibo(
                            $('#serie').val(),
                            ID_PACIENTE,
                            $('#total').text());
                    } else {
                        Swal.fire({
                            title: "Mensaje dle sistema!",
                            text: "Ingrese el servicio(s) que adquirió el paciente..",
                            icon: "error"
                        });
                    }
                } else {
                    Swal.fire({
                        title: "Mensaje dle sistema!",
                        text: "Seleccione a un paciente...",
                        icon: "error"
                    })
                }
            });

            $('#cancel_recibo').click(function() {

                Swal.fire({
                    title: "Deseas cancelar el proceso del recibo?",
                    text: "Al realizar esta acción, se reanudarán los datos!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, Cancelar proceso!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#paciente').val("");
                        $('#documento').val("");
                        CITA_MEDICA_ID = null;
                        ID_PACIENTE = null;
                        $.ajax({
                            url: RUTA + "recibo/cancel/proceso",
                            method: "POST",
                            data: {
                                token_: TOKEN,
                            },
                            success: function(response) {
                                response = JSON.parse(response);

                                if (response.response === 'ok') {
                                    showCestaDetalleServiceRecibo();
                                }
                            }
                        });
                        $('#documento').focus();
                    }
                });
            });

            $('#cancel_recibo_editar').click(function(){
                $('#modal_editar_recibo').modal("hide");
            });

            $('#add_servicio_detalle_editar').click(function(){
                 $('#modal_servicios_edicion').modal("show");
                 TablaServicioMedicoEditar.column(0).search($('#especialidadedit_ option:selected').text()).draw();
            });

            $('#especialidadedit_').change(function(){
              TablaServicioMedicoEditar.column(0).search($('#especialidadedit_ option:selected').text()).draw();
            });
            $('#tab-recibo a').on('click', function(e) {
                e.preventDefault();
                idControls = $(this)[0].id;
                if (idControls === 'show_recibos__') {
                    MostrarRecibosGenerados();
                    print_recibo(Tabla_Recibos, '#tabla_recibos tbody');
                    ConfirmarAntesDeEliminarRecibo(Tabla_Recibos, '#tabla_recibos tbody');
                    EditarPagoPaciente(Tabla_Recibos, '#tabla_recibos tbody');
                }

                $(this).tab("show")
            })

            ///
            $('#lista_detalle_recibo_editar').on('click','#quitar_service_detalle_editar',function(){
                let fila = $(this).closest("tr");

                /// obtenemos el id detalle
                let DetalleIdService = fila.find("td").eq(0).text();

                ConfirmQuitarServiceDetalleEdition(DetalleIdService);
            });

            $('#update_recibo').click(function(){
                ModifyRecibo();
            });
        });

        var BuscarPacienteParaRecibo = function() {
            TablaPacientesSearch = $('#tabla_paciente_search').DataTable({
                retrieve: true,
                language: SpanishDataTable(),
                processing: true,
                responsive: true,
                ajax: {
                    url: RUTA + "pacientes_sin_recibo?token_=" + TOKEN,
                    method: "GET",
                    dataSrc: "response",
                },
                columns: [{
                        "data": "documento"
                    },
                    {
                        "data": "paciente_"
                    },
                    {
                        "data": null,
                        render: function() {
                            return `
        <button class="btn rounded btn-outline-success btn-sm" id='generar_recibo'><i class='bx bx-check'></i><b>Si</b></button>
        <button class="btn rounded btn-outline-danger btn-sm" id='no_generar_recibo'><i class='bx bx-x' ></i><b>No</b></button>
        `;
                        }
                    }
                ]
            }).ajax.reload();
        }

        /**MOstrar los servicios del médico*/
        var ConsultaServiciosMedico = (id) => {
            TablaServiciosMedico = $('#tabla_servicios').DataTable({
                retrieve: true,
                language: SpanishDataTable(),
                processing: true,
                responsive: true,
                ajax: {
                    url: RUTA + "medico/mis_servicios_data_/" + id + "?token_=" + TOKEN + "&&limit=150",
                    method: "GET",
                    dataSrc: "response",
                },
                columns: [
                    {
                        "data":"nombre_esp"
                    },
                    {
                        "data": "name_servicio"
                    },
                    {
                        "data": "precio_servicio"
                    },
                    {
                        "data": null,
                        render: function() {
                            return `
         <button class='btn rounded btn-outline-primary btn-sm' id='add_cesta_service'><i class='bx bxs-select-multiple'></i></button>
        `;
                        }
                    }
                ]
            }).ajax.reload(null,false)
        };


        /// CONSULTAR LOS SERVICIOS DEL MÉDICO AL REALIZAR LA EDICION DEL DETALLE SERVICE
         /**MOstrar los servicios del médico*/
        var ConsultaServiciosMedicoEdition = (id) => {
            TablaServicioMedicoEditar = $('#tabla_serviciosedit').DataTable({
                retrieve: true,
                language: SpanishDataTable(),
                processing: true,
                responsive: true,
                ajax: {
                    url: RUTA + "medico/mis_servicios_data_/" + id + "?token_=" + TOKEN + "&&limit=150",
                    method: "GET",
                    dataSrc: "response",
                },
                columns: [
                    {
                        "data":"nombre_esp"
                    },
                    {
                        "data": "name_servicio"
                    },
                    {
                        "data": "precio_servicio"
                    },
                    {
                        "data": null,
                        render: function() {
                            return `
         <button class='btn rounded btn-outline-primary btn-sm' id='add_cesta_service_edition'><i class='bx bxs-select-multiple'></i></button>
        `;
                        }
                    }
                ]
            }).ajax.reload(null,false)
        };

        /// AGREGAR SERVICIOS AL DETALLE EDITION PAGO
        function AddServiceDetalleEdition(Tabla,Tbody){
            $(Tbody).on('click','#add_cesta_service_edition',function(){
                let fila = $(this).parents("tr");

                if(fila.hasClass("child")){
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();

                let FormAddServiceDetalleEdition = new FormData();
                FormAddServiceDetalleEdition.append("token_",TOKEN);
                axios({
                    url:RUTA+"recibo/add-service/edition-detalle/"+Data.id_servicio,
                    method:"POST",
                    data:FormAddServiceDetalleEdition
                }).then(function(response){
                    if(response.data.error != undefined){
                        Swal.fire({
                            title:"MENSAJE DEL SISTEMA!!!",
                            text:response.data.error,
                            icon:"error",
                            target:document.getElementById('modal_servicios_edicion')
                        });
                    }else{
                        Swal.fire({
                            title:"MENSAJE DEL SISTEMA!!",
                            text:response.data.response,
                            icon:"success",
                            target:document.getElementById('modal_servicios_edicion')
                        }).then(function(){
                            showReciboDetalleEdition();
                        })
                    }
                });
            });
        }

        /// realizamos la acción de generar recibo del paciente
        function SeleccionarPacienteParRecibo(Tabla, Tbody) {
            $(Tbody).on('click', '#generar_recibo', function() {
                /// obtenemos la fila seleccionada
                let filaSelect = $(this).parents('tr');

                /// verificamos para dispositivos móviles
                if (filaSelect.hasClass("child")) {
                    filaSelect = filaSelect.prev();
                }

                let Data = Tabla.row(filaSelect).data();

                $('#paciente').val(Data.paciente_);
                $('#documento').val(Data.documento);
                ID_PACIENTE = Data.id_paciente;
                CITA_MEDICA_ID = Data.id_cita_medica;
                $('#modal_buscar_paciente').modal("hide")

            });
        }
        /// realizamos la acción de añadir los servicios a la cesta
        function AddCestaServiceDetalle(Tabla, Tbody) {
            $(Tbody).on('click', '#add_cesta_service', function() {
                /// obtenemos la fila seleccionada
                let filaSelect = $(this).parents('tr');

                /// verificamos para dispositivos móviles
                if (filaSelect.hasClass("child")) {
                    filaSelect = filaSelect.prev();
                }

                let Data = Tabla.row(filaSelect).data();
 
                addCarritoReciboDetalle(Data.name_servicio, Data.precio_servicio, Data.id_servicio,Data.precio_medico,Data.precio_clinica,Data.id_servicio);

            });
        }


        /// proceso para añadir a la cesta del detalle del recibo
        function addCarritoReciboDetalle(servicio_data, precio_data, service_id_data,pricemedico,priceclinica,id) {
            $.ajax({
                url: RUTA + "add_cesta_service/"+id,
                method: "POST",
                data: {
                    token_: TOKEN,
                    service: servicio_data,
                    precio: precio_data,
                    id_serv: service_id_data,
                    preciomedico:pricemedico,
                    precioclinica:priceclinica
                },
                success: function(response) {
                    response = JSON.parse(response);
                     
                    if(response.error != undefined){
                        Swal.fire({
                            title:"MENSAJE DEL SISTEMA!!!",
                            text:response.error,
                            icon:"error",
                            target:document.getElementById('modal_servicios')
                        })
                    }else{
                        Swal.fire({
                            title:"MENSAJE DEL SISTEMA!!!",
                            text:response.response,
                            icon:"success",
                            target:document.getElementById('modal_servicios')
                        }).then(function(){
                            showCestaDetalleServiceRecibo();
                        })
                    }
                }
            });


        }

        /// mostrar los servicios a la tabla detalle
        function showCestaDetalleServiceRecibo() {
            let tr = '';
            let importe = 0.00;
            let TotalImporteMedico= 0.00;
            let TotalImporteClinica= 0.00;
            let Total = 0.00,
                Igv = 0.00,
                SubTotal = 0.00;
            let ValorIVA = "{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->iva_valor : 18 }}";
            $.ajax({
                url: RUTA + "services/agregado_en_carrito?token_=" + TOKEN,
                method: "GET",
                success: function(response) {
                    response = JSON.parse(response);
                    
                    let valor = Object.values(response.response);

                    if (response.response !== 'vacio' && valor.length > 0) {

                        valor.forEach(element => {
                            importe = element.precio * element.cantidad;
                            TotalImporteMedico = importe * (element.preciomedico/100);
                            TotalImporteClinica = importe * (element.precioclinica/100);
                            Total += importe;
                            SubTotal = Total / (1 + (ValorIVA / 100)); /// igv incluido
                            Igv = Total - SubTotal;
                            tr += `
                <tr>
                <td><button class='btn rounded btn-outline-danger btn-sm' id='quitar'>X</button></td>    
                <td class='text-center'><b>` + element.cantidad + `</b></td>
                <td><b class='text-secondary'>` + element.servicio + `</b></td>
                <td><input type="number" class="form-control" id="precio_por_service" value=`+element.precio+`
                    placeholder="{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda."0.00" : 'S/.0.00' }}"></td>
                <td>` + importe.toFixed(2) + `</td>
                <td class="d-none">` + TotalImporteMedico.toFixed(2) + `</td>
                <td class="d-none">` + TotalImporteClinica.toFixed(2) + `</td>
                </tr>
                `;
                        });

                        tr += `
               <tr>
               <td colspan=4><b>Importe a pagar {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</b></td> 
               <td colspan=1 id='total'>` + Total.toFixed(2) + `</td> 
               </tr>
               <tr>
               <td colspan=4><b>Sub Total {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</b></td> 
               <td colspan=1>` + SubTotal.toFixed(2) + `</td> 
               </tr>
               <tr>
               <td colspan=4><b>Igv {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} </b><b class='badge bg-danger'>[{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->iva_valor . '%' : '18%' }}]</b></td> 
               <td colspan=1>` + Igv.toFixed(2) + `</td> 
               </tr>
               `;
                    } else {
                        tr = `
                <tr>
                 <td colspan='5'><span class='text-danger'>No hay servicios agregados....</span></td>    
                </tr>
                `;
                    }

                    $('#detalle_servicios tbody').html(tr);
                }


            })
        }

        /// quitar de la lista el servicio añadido
        function ConfirmquitarServiceDetalle() {
            $('#detalle_servicios tbody').on('click', '#quitar', function() {
                /// obtenemos la fila
                let fila = $(this).parents("tr");
                /// obtenemos el producto seleccionado
                let producto = fila.find('td').eq(2).text();

                Swal.fire({
                    title: "Estas seguro?",
                    text: "Al aceptarm se quitará automaticamente el servicio añadido a la lista!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, eliminar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        QuitarServiceDetalle(producto);
                    }
                });
            });
        }

        /// proceso para quitar de la lista al servicio
        function QuitarServiceDetalle(servicio) {
            $.ajax({
                url: RUTA + "quitar_service_detalle",
                method: "POST",
                data: {
                    token_: TOKEN,
                    service: servicio
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.response === 'eliminado') {
                        showCestaDetalleServiceRecibo();
                    } else {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Error al quitar el servicio de la lista",
                            icon: "error"
                        });
                    }
                }
            });
        }

        /// método que realiza el registro de los datos del recibo
        function saveRecibo(number_recibo, paciente_id_data, monto_data,montomedico,montoclinica) {
            $.ajax({
                url: RUTA + "recibo/save",
                method: "POST",
                data: {
                    token_: TOKEN,
                    recibo_numero: number_recibo,
                    monto: monto_data,
                    monto_medico:montomedico,
                    monto_clinica:montoclinica,
                    citaid: CITA_MEDICA_ID,

                },
                success: function(response) {
                    response = JSON.parse(response);

                    /// validamos, según las respuestas obtenidas
                    if (response.response === 'ok') {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "El recibo se ha generado sin problemas 😁",
                            icon: "success"
                        }).then(function() {
                            CITA_MEDICA_ID = null;
                            ID_PACIENTE = null;
                            window.open(RUTA + "paciente/recibo?v=" + $('#serie').val(),'blank_');

                            location.href=RUTA+"medico/generate/recibo/paciente";
                        });
                    }
                }
            });
        }

        /// cancelar el recibo del paciente
        function ConfirmCancelReciboPaciente(Tabla, Tbody) {
            $(Tbody).on('click', '#no_generar_recibo', function() {
                /// obtenemos la fila seleccionada
                let fila = $(this).parents('tr');

                if (fila.hasClass("child")) {
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();

                let CITA_MEDICA_ID = Data.id_cita_medica;
                Swal.fire({
                    title: "Deseas cancelar su recibo para el paciente " + Data.paciente_,
                    text: "Al aceptar, automaticamente se borrará de la lista de pacientes!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, Acepto!",
                    target: document.getElementById('modal_buscar_paciente')
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: RUTA + "recibo/cancel/update/" + CITA_MEDICA_ID,
                            method: "POST",
                            data: {
                                token_: TOKEN
                            },
                            success: function(response) {
                                response = JSON.parse(response);

                                if (response.response === 'ok') {
                                    Swal.fire({
                                        title: "Mensaje del sistema!",
                                        text: "Recibo cancelado para el paciente " +
                                            Data.paciente_,
                                        icon: "success",
                                        target: document.getElementById(
                                            'modal_buscar_paciente')
                                    }).then(function() {
                                        BuscarPacienteParaRecibo();
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Mensaje del sistema!",
                                        text: "Error al cancelar el recibo para el paciente " +
                                            Data.paciente_,
                                        icon: "error",
                                        target: document.getElementById(
                                            'modal_buscar_paciente')
                                    });
                                }
                            }
                        })
                    }
                });
            });
        }

        /** Mostrar los recibos generados**/
        function MostrarRecibosGenerados() {
            Tabla_Recibos = $('#tabla_recibos').DataTable({
                retrieve: true,
                language: SpanishDataTable(),
                processing: true,
                responsive: true,
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                ajax: {
                    url: RUTA + "recibos/generados?token_=" + TOKEN,
                    method: "GET",
                    dataSrc: "response",
                },
                columns: [{
                        "data": "numero_recibo"
                    },
                    {
                        "data": null,
                        render: function() {
                            return `<button class="btn rounded btn-outline-primary btn-sm" id="print_recibo"><i class="bx bxs-printer"></i></button>
                                   <button class="btn rounded btn-outline-danger btn-sm" id="delete_recibo"><i class='bx bx-trash'></i></button>
                                   <button class='btn btn-outline-warning rounded btn-sm' id='editar_recibo'><i class='bx bx-edit-alt'></i></button>
                                   `;
                        }
                    },
                    {
                        "data": "numero_recibo",
                        render: function(recibo_serie) {
                            return '<span class="badge bg-warning"><b>' + recibo_serie + '</b></span>';
                        }
                    },
                    {
                        "data": "fecha_recibo",
                        render: function(fecha) {
                            fecha = fecha.split(" ");

                            let Hora = fecha[1];
                            let nuevaFecha = fecha[0].split("-");

                            let fechaFormat = nuevaFecha[2] + "/" + nuevaFecha[1] + "/" + nuevaFecha[0];

                            return fechaFormat + " " + Hora;
                        }
                    },
                    {
                        "data": "paciente_"
                    },
                    {
                        "data": "monto_pagar",
                        render: function(monto) {
                            return '<span class="badge bg-success"><b> {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} ' +
                                monto + '</b></span>';
                        }
                    },

                ]
            }).ajax.reload();

            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
            Tabla_Recibos.on('order.dt search.dt', function() {
                Tabla_Recibos.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }

        /// imprimir recibo generado
        function print_recibo(Tabla, Tbody) {
            $(Tbody).on('click', '#print_recibo', function() {
                let fila = $(this).parents('tr');

                if (fila.hasClass("child")) {
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();
                window.open(RUTA + "paciente/recibo?v=" + Data.id_recibo, "_blank")

            });
        }

        /// EDITAR EL PAGO DEL PACIENTE
        function EditarPagoPaciente(Tabla,Tbody){
            $(Tbody).on('click','#editar_recibo',function(){
                $('#modal_editar_recibo').modal("show");

                let fila = $(this).parents("tr");

                if(fila.hasClass("child")){
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();

                $('#num_recibo_editar').val(Data.numero_recibo);
                $('#fecha_recibo_editar').val(Data.fecha_recibo);
                $('#paciente_recibo_editar').val(Data.paciente_);
                RECIBOID = Data.id_recibo;
                CITAID = Data.cita_id;
                /// Obtener el detalle del recibo
                obtenerDetalleReciboEdicion(RECIBOID);
            });
        }

        /// OBTENER EL DETALLE DEL RECIBO
        function obtenerDetalleReciboEdicion(id){
            let FormDetalleReciboEditar = new FormData();
            FormDetalleReciboEditar.append("token_",TOKEN);
            axios({
                url:RUTA+"recibo/detalle/editar/"+id,
                method:"POST",
                data:FormDetalleReciboEditar
            }).then(function(response){
                showReciboDetalleEdition();
            })
        }

        function showReciboDetalleEdition(){
         let tr = '';
          axios({
            url:RUTA+"recibo/detalle/show/edition",
            method:"GET"
          }).then(function(response){
            let TotalPagar = 0.00;
                let SubTotalPagar = 0.00;
                let IgvTotal = 0.00;
                let TotalImporteClinica = 0.00;
                let TotalImporteMedico = 0.00;
                 let ValorIVA = "{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->iva_valor : 18 }}";
                datos = Object.values(response.data.detallerecibo);
                if(datos.length > 0){
                    
                    datos.forEach(detalle => {
                        let Importe = 0.00;
                        Importe = detalle.precio * detalle.cantidad;
                        TotalImporteMedico = Importe * (detalle.preciomedico/100);
                            TotalImporteClinica = Importe * (detalle.precioclinica/100);
                            TotalPagar += Importe;
                            SubTotalPagar = TotalPagar / (1 + (ValorIVA / 100)); /// igv incluido
                            IgvTotal = TotalPagar - SubTotalPagar;
                        tr+=`<tr>
                            <td class="d-none">`+detalle.serviceid+`</td>
                            <td class="text-center" id='quitar_service_detalle_editar'><button class="btn btn-outline-danger btn-sm rounded">X</button></td>
                            <td class="text-center">`+detalle.cantidad+`</td>
                            <td>`+detalle.servicio.toUpperCase()+`</td>
                            <td class="text-center">`+parseFloat(detalle.precio).toFixed(2)+`</td>
                            <td class="text-center">`+Importe.toFixed(2)+`</td>
                           </tr>`;
                    });
                    tr+=`<tr>
                         <td colspan="4" class="text-end">Importe a pagar <b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} </td>
                         <td colspan="1" class="text-center text-primary">`+TotalPagar.toFixed(2)+`</td>
                        </tr>
                        
                        <tr>
                         <td colspan="4" class="text-end">Sub Total <b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} </td>
                         <td colspan="1" class="text-center text-primary">`+SubTotalPagar.toFixed(2)+`</td>
                        </tr>

                        <tr>
                         <td colspan="4" class="text-end">Igv <b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} </td>
                         <td colspan="1" class="text-center text-primary">`+IgvTotal.toFixed(2)+`</td>
                        </tr>
                        `;
                }else{
                    tr = '<td colspan="5" class="px-2 py-3"><span class="text-danger">No hay servicios para mostrar en detalle.....</span></td>';
                }
                
                $('#lista_detalle_recibo_editar').html(tr);
          });
        }

        /// ELIMINAR RECIBO
        function ConfirmarAntesDeEliminarRecibo(Tabla,Tbody){
           $(Tbody).on("click","#delete_recibo",function(){
             /// OBTENEMOS LA FILA SELECCIONADA
             let fila = $(this).parents("tr");
            /// para casos de dispositivos moviles
            if(fila.hasClass("child")){
                fila = fila.prev();
            }

            let Data = Tabla.row(fila).data();

            let ReciboId = Data.id_recibo;
            let CitaIdBorrar = Data.cita_id;

     Swal.fire({
            title: "ESTAS SEGURO?",
            text: "Deseas eliminar por completo este recibo?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!"
            }).then((result) => {
            if (result.isConfirmed) {
             BorrarReciboGenerado(ReciboId,CitaIdBorrar);
            }
            });
           })
        } 

        /// PROCESO DE ELIMINAR RECIBO
        function BorrarReciboGenerado(id_recibo,id_cita){
            let FormReciboEliminar = new FormData();
            FormReciboEliminar.append("token_",TOKEN);
            axios({
                url:RUTA+"recibo/"+id_recibo+"/"+id_cita+"/eliminarbd",
                method:"POST",
                data:FormReciboEliminar
            }).then(function(response){
                if(response.data.error != null){
                    Swal.fire({
                        title:"MENSAJE DEL SISTEMA!!!",
                        text:response.data.error,
                        icon:"error"
                    });
                }else{
                    Swal.fire({
                        title:"MENSAJE DEL SISTEMA!!!",
                        text:response.data.success,
                        icon:"success"
                    }).then(function(response){
                        location.href = RUTA+"medico/generate/recibo/paciente";
                    });
                }
            });
        }

        function AplicarDescuento(serviceData,nuevo_precioData){
            let FormReciboAplicarDescuento = new FormData();
            FormReciboAplicarDescuento.append("token_",TOKEN);
            FormReciboAplicarDescuento.append("service",serviceData);
            FormReciboAplicarDescuento.append("nuevo_precio",nuevo_precioData);
            axios({
                url:RUTA+"recibo/dar-descuento",
                method:"POST",
                data:FormReciboAplicarDescuento
            }).then(function(response){
                if(response.data.error != null){
                    Swal.fire({
                        title:"MENSAJE DEL SISTEMA!!!",
                        text:response.data.error,
                        icon:"error"
                    });
                }else{
                    Swal.fire({
                        title:"MENSAJE DEL SISTEMA!!!",
                        text:response.data.success,
                        icon:"success"
                    }).then(function(response){
                         showCestaDetalleServiceRecibo();
                    });
                }
            });
        }

    function ConfirmQuitarServiceDetalleEdition(id){
   Swal.fire({
            title: "ESTAS SEGURO?",
            text: "Al presionar que si, el servicio se quitará de la lista detalle!!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, quitar de lista!",
            target:document.getElementById('modal_editar_recibo')
            }).then((result) => {
            if (result.isConfirmed) {
              QuitarServiceDetalleEdition(id);
            }
            });
        }

        /// QUITAR EL SERVICIO DEL DETALLE AL REALIZAR LA EDICION
        function QuitarServiceDetalleEdition(id){
            let FormQuitarService = new FormData();
            FormQuitarService.append("token_",TOKEN);
            axios({
                url:RUTA+"quitar-service/detalle_service/editar/"+id,
                method:"POST",
                data:FormQuitarService
            }).then(function(response){
                if(response.data.error != undefined){
                    Swal.fire({
                      title:"MENSAJE DEL SISTEMA!!",
                      text:response.data.error,
                      icon:"error",
                      target:document.getElementById('modal_editar_recibo')
                    });
                }else{
                    Swal.fire({
                      title:"MENSAJE DEL SISTEMA!!",
                      text:response.data.response,
                      icon:"success",
                      target:document.getElementById('modal_editar_recibo')
                    }).then(function(){
                        showReciboDetalleEdition();
                    });
                }
            });
        }

        /// MODIFICAR EK RECIBO GENERADO
        function ModifyRecibo(){
            let FormUpdateRecibo = new FormData();
            FormUpdateRecibo.append("token_",TOKEN);
            FormUpdateRecibo.append("citaid",CITAID);
            axios({
                url:RUTA+"recibo/"+RECIBOID+"/update",
                method:"POST",
                data:FormUpdateRecibo
            }).then(function(response){
                if(response.data.error != undefined){
                    Swal.fire({
                        title:"MENSAJE DEL SISTEMA!!",
                        text:response.data.error,
                        icon:"error",
                        target:document.getElementById("modal_editar_recibo")
                    });
                }else{
                    Swal.fire({
                        title:"MENSAJE DEL SISTEMA!!",
                        text:response.data.response,
                        icon:"success",
                        target:document.getElementById("modal_editar_recibo")
                    }).then(function(){
                        MostrarRecibosGenerados();
                    })
                }
            })
        }
/*VERIFICAR SI HAY UNA CAJA APERTURADA PARA LA CLINICA PARA REALIZAR REALIZAR CITAS MEDICAS*/
        function VerifyCajaAbierta(){
        axios({
        url:RUTA+"verificar/caja/clinica",
        method:"GET",
        }).then(function(response){
        
        if(response.data.caja != undefined){
        if(response.data.caja.length <= 0){ $('#modal_alerta_para_caja').modal("show") } } }) }

 /*GENERAR IDENTIFICADOR PARA EL RECIBO*/
  function GenerarIdentificador(){
    axios({
        url:RUTA+"recibo/generate/identificador",
        method:"POST",
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.error,
                icon:"error"
            });
        }else{
            $('#serie').val(response.data.serie)
        }
    });
  }
    </script>
@endsection
