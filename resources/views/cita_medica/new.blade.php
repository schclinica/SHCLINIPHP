@extends($this->Layouts("dashboard"))

@section("title_dashboard","cita médica")

@section('css')
<style>
    #tabla_medico_citas>thead>tr>th {

        background: #4169E1;
        color: aliceblue;
    }

  #tabla_citas_pendientes>thead>tr>th {
    
    background: linear-gradient(to bottom, #f85032 0%,#f16f5c 50%,#f6290c 51%,#f02f17 71%,#e73827 100%);
    color: aliceblue;
    }
    #tabla_search_paciente>thead>tr>th {
    
    background: linear-gradient(to bottom, #b3dced 0%,#29b8e5 50%,#bce0ee 100%);;
    color: aliceblue;
    }

    td.hide_me {
        display: none;
    }
    #dia{
       cursor: pointer; 
    }
    #calendar_citas{
        color:#13d6bc;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        font-size: 17px;
        border-radius: 20%;
    }

    .tabla_hp
    {
     overflow:scroll;
     height:290px;
     width:100%;
     border: 0.1rem solid blue;
    }

    .tabla_hp table{
      width:100%;
      
    }
    #tablahp>thead>tr>th{
        background-color: #4169E1;
        color:aliceblue
    }
    button.fc-dayGridMonth-button.fc-button.fc-button-primary {
    background-color: rgb(37, 35, 35) !important;
    border-color: rgb(8, 5, 7);
    }
    button.fc-timeGridWeek-button.fc-button.fc-button-primary {
    background-color: rgb(12, 181, 170) !important;
    border-color: greenyellow;
    color: #4169E1
    }
    button.fc-timeGridDay-button.fc-button.fc-button-primary {
    background-color: #4169E1 !important;
    border-color: greenyellow;
    color: white;
    padding-top: 5px; /* an override! */
  padding-bottom: 5px; /* an override! */
    }

   
    .fc .fc-col-header-cell-cushion {
  display: inline-block;
  padding: 12px 12px;
}
td.hide_me {
        display: none;
}

#calendar_citas .fc-event {
  color: #e2eaf2; /*los estilos predeterminados de bootstrap lo hacen negro. deshacer  */
  background:linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);
}
 
 #tabla_citas_anuladas>thead>tr>th{
    background: linear-gradient(to bottom, #f85032 0%,#f16f5c 50%,#f6290c 51%,#f02f17 71%,#e73827 100%);;
    color: #e2eaf2;
 }
 input[type="checkbox"]{
    width: 18px;
    height: 18px;
 }
 label{
    cursor: pointer;
 }
 
</style>
 
<script src="{{$this->asset("js/index.global.min.js")}}"></script>
@endsection
@section('contenido')
<div class="nav-align-top mb-4" id="cita_content">
    <ul class="nav nav-tabs nav-fill" role="tablist" id="ul_citas">
        @if ($this->profile()->rol === 'Admisión' or $this->profile()->rol === 'Médico' || $this->profile()->rol === 'Director')
        <li class="nav-item">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#cita_calendario"
               id="citasprogramados" aria-controls="navs-justified-profile" aria-selected="false" style="color:#466e9b">
               <img src="{{$this->asset('img/icons/unicons/citas_programados.ico')}}" class="menu-icon" alt="" style="height: 20px"> <b>Citas programadas</b>
            </button>
        </li>
        @endif
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#cita_form"
                aria-controls="navs-justified-home" aria-selected="true" style="color: #636d69">
                <img src="{{$this->asset('img/icons/unicons/add_cita.ico')}}" class="menu-icon" alt="" style="height: 20px"> <b>Registrar cita médica</b>

            </button>
        </li>

        @if ($this->profile()->rol === 'Admisión')
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#cita_anuladas"
                aria-controls="navs-justified-c_anuladas" aria-selected="true" style="color: #ee2222" id="pendientes_">
                <i class='bx bx-x'></i> <b>Citas pendientes no atendidos</b>

            </button>
        </li>
        @endif

        @if ($this->profile()->rol === 'Admisión')
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#cita_anuladas_view"
               id="citasna"  aria-controls="navs-justified-c_anuladas" aria-selected="true" style="color: #FF7F50">
                <i class='bx bx-phone-off'></i><b>Citas anulados</b>

            </button>
        </li>
        @endif
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade @if($this->profile()->rol === 'Admisión' or $this->profile()->rol === 'Médico' or $this->profile()->rol === 'Director') show active @endif"  id="cita_calendario" role="tabpanel">
            <div id="calendar_citas"></div>
        </div>
        <div class="tab-pane fade  @if($this->profile()->rol === 'Paciente') show active @endif" id="cita_form" role="tabpanel">
            <div class="card-text mb-3">
                <b class="h4" style="color:#4169E1">Registrar una cita médica</b>
            </div>

            <form action="" method="post" id="form_cita_medica">
               <div class="row">
                    <div class="col-12">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" id="serie_cita" readonly name="serie_cita" placeholder="Serie cita....">
                            <label for="serie_cita"><b>Serie de la cita</b></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12">
                        <div class="form-group">
                            <label for="identificacion"><b> # de Identificación <span class="text-danger">(*)</span></b></label>
                            <input type="text" class="form-control" name="identificacion" id="identificacion" autofocus
                                placeholder="xxx xxx xxx xxx" style="border: 0.6px solid rgb(74, 151, 239)"
                                @if($this->profile()->rol === 'Paciente') readonly @endif >
                        </div>
                    </div>
    
                    <div class="col-xl-9 col-lg-9 col-md-8 col-sm-7 col-12">
                        <div class="form-group">
                            <label for="paciente"><b> Paciente <span class="text-danger">(*)</span></b></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="paciente" id="paciente" readonly style="background-color: #E0FFFF">
                                @if ($this->profile()->rol === "Admisión" || $this->profile()->rol === "Médico" || $this->profile()->rol === "Director")
                                <button class="button-new" id="search_paciente"><i class='bx bx-search-alt-2'></i> Buscar</button >
                                @endif
                            </div>
                        </div>
                        <span class="text-danger message_error_paciente" style="display:none">El paciente no
                            existe!☹️</span>
                    </div>
    
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-5 col-12">
                        <div class="form-group">
                            <label for="especialidad"><b>Consultorio (Especialidad) <span class="text-danger">(*)</span></b></label>
                            <select name="especialidad" id="especialidad" class="form-select" @if($this->profile()->rol === 'Paciente') disabled @endif>
                               @if ($this->profile()->rol === 'Admisión' || $this->profile()->rol === 'Director')
                               <option disabled selected>-- Seleccione ---</option>
                               @endif
                                @if (isset($Especialidades))
                                @foreach ($Especialidades as $esp)
                                <option value="{{$esp->id_especialidad}}">
                                    {{strtoupper($esp->nombre_esp)}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
    
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-7 col-12">
                        <div class="form-group">
                            <label for="medico"><b> Médico <span class="text-danger">(*)</span></b></label>
                            <input type="text" class="form-control" name="medico" id="medico" style="background-color: #466e9b;color:white"
                             value="{{$this->profile()->rol === 'Médico' ? "Med. ".$this->profile()->apellidos." ".$this->profile()->nombres:''}}" readonly>
                        </div>
                    </div>
                   {{--     
                    <div class="col-12">
                        <div class="form-group">
                            <label for="servicio"><b> Atención de servicio (Opcional)</b></label>
                            <select name="servicio" id="servicio" class="form-select">
                               <option disabled selected>--- Seleccione ----</option>
                            </select>
                        </div>
                    </div> --}}
                    <div class="col-12">
                        <div class="form-group">
                            <label for="asunto"><b> Describir el motivo de la consulta </b></label>
                            <textarea name="asunto" id="asunto" cols="30" rows="5" class="form-control"
                                placeholder="escriba aquí...."></textarea>
                        </div>
                    </div>
    
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="fecha"><b> Fecha de la cita <span class="text-danger">(*)</span></b></label>
                            <input type="date" class="form-control" name="fecha" id="fecha" value="{{$this->FechaActual("Y-m-d")}}"
                            min="{{$this->FechaActual("Y-m-d")}}" max="{{$this->addRestFecha("Y-m-d",'+ 7 day')}}">
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="dia"><b> Día de la cita <span class="text-danger">(*)</span></b></label>
                            <input type="text" class="form-control text-end"
                                style="background-color: #E0FFFF;color:rgb(56, 62, 67);font-family: Arial;font-size: 16px" name="dia" id="dia"
                                readonly>
                        </div>
                    </div>
    
                    <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                        <div class="form-group">
                            <label for="estado"><b> Estado</b></label>
                            <select name="estado" id="estado" class="form-select" @if($this->profile()->rol === 'Paciente') disabled  @endif>
                                <option value="pendiente">Pendiente</option>
                                <option value="pagado">Cita confirmado</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
                <div class="row mt-2">
                  <div class="col-12">
                    <div class="card-text"><p class="h5">Horarios disponibles</p></div>
                    <select name="cita_horario" id="cita_horario" class="form-select border-primary">
                     
                    </select>
                  </div>
                </div>
                <div class="d-flex flex-row mt-3 justify-content-end">
                     <div class="col-xl-3 col-lg-4 col-md-5 col-sm-5 col-12">
                        <label for=""><b>Monto a pagar <span class="text-primary">{{count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda:'S/.'}}</span> </b></label>
                     <input type="text" class="form-control" id="importe" disabled value="0.00" @if($this->profile()->rol === 'Paciente') readonly @endif>
                     </div>
                     <div class="col-xl-3 col-lg-4 col-md-5 col-sm-5 col-12 d-none">
                        <label for=""><b>Monto Medico <span class="text-primary">{{count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda:'S/.'}}</span> </b></label>
                     <input type="text" class="form-control" id="importe_medicototal" style="display: none">
                     </div>
                     <div class="col-xl-3 col-lg-4 col-md-5 col-sm-5 col-12 d-none">
                        <label for=""><b>Monto Clinica <span class="text-primary">{{count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda:'S/.'}}</span> </b></label>
                     <input type="text" class="form-control" id="importe_clinicatotal"  @if($this->profile()->rol !== 'Paciente') style="display: none" @endif >
                     </div>
                </div>
                
                <div class="row mt-4 justify-content-center">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5  m-xl-0 m-lg-0 m-md-0 m-1">
                        <button class="btn_info_tw save_cita" style="width: 100%"  style="display: none"> <b>Agendar cita</b> <i class='bx bx-calendar-plus'></i></button>
                    </div>
                     
                </div>
            </form>
        </div>

        <div class="tab-pane fade" id="cita_anuladas" role="tabpanel">
            <div class="card-text"><p class="h5 float-start">Anular las citas pendientes</p>
            <label for="todo" class="float-end"><input type="checkbox" id="todo"> Seleccionar todo</label>
            </div>
           <div class="table-responsive">
            <table class="table table-bordered nowrap" id="tabla_citas_pendientes" style="width: 100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="d-none">ID</th>
                        <th class="d-none">HORA_id</th>
                        <th class="letra py-3">PACIENTE</th>
                        <th class="letra py-3">CITA PARA ? </th>
                        <th class="letra py-3">ESTADO</th>
                        <th class="letra py-3">ACCIÓN</th>
                    </tr>
                </thead>
            </table>
           </div>
        </div>

        <div class="tab-pane fade" id="cita_anuladas_view" role="tabpanel">
           <div class="row">
            <div class="card-text"><p class="h5 float-start">Limpiar citas anuladas</p>
                <label for="todo_all"  class="float-end"><input type="checkbox" id="todo_all"> <span id="all_label">Seleccionar todo</span></label>
                </div>
           </div>
           <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-5 col-sm-6 col-12">
                <button class="btn btn-rounded btn-info form-control" id="drop_citas_anuladas"><b>Eliminar seleccionados <i class='bx bxs-x-circle'></i></b></button></div>   
           </div>
           <div class="table-responsive">
            <table class="table table-bordered responsive nowrap" id="tabla_citas_anuladas" style="width: 100%">
                <thead>
                    <tr>
                        <th class="py-3 letra">#</th>
                        <th class="py-3 letra">SELECCIONAR</th>
                        <th class="py-3 letra">ELIMINAR</th>
                        <th class="py-3 letra">FECHA</th>
                        <th class="py-3 letra">MÉDICO</th>
                        <th class="py-3 letra">ESPECIALIDAD</th>
                        <th class="py-3 letra">PACIENTE</th>
                        <th class="py-3 letra">ESTADO</th>
                    </tr>
                </thead>
            </table>
           </div>
        </div>
    </div>
</div>
</div>
{{--- MEDICOS PORESPECIALIDAD----}}
<div class="modal fade" id="medicos" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
                <p class="h4 text-white">Médicos disponibles</p>
                <button class="btn btn-rounded btn-danger btn-sm" id="cerrar_modal__medic_citas">X</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered nowrap" style="width: 100%" id="tabla_medico_citas">
                        <thead style="background:linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
                           <tr>
                               <th>#</th>
                               <th class="d-none">ID_MEDICO ESP</th>
                               <th class="d-none">ID_ESP</th>
                               <th class="d-none">ID_ MEDICO</th>
                               <th class="letra py-3">MEDICO</th>
                               <th class="letra py-3">CITAR</th>
                           </tr>
                        </thead>
                       </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PARA CREAR PACIENTE ---}}
<div class="modal fade" id="modal_new_paciente" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(to bottom, #3b679e 0%,#2b88d9 50%,#207cca 51%,#7db9e8 100%);">
                <p class="h4 text-white float-start" >Nuevo paciente <i class='bx bxs-user-plus'></i></p>
                <button class="btn btn-rounded btn-danger btn-sm float-end" id="cerrar_modal_new_paciente">X</button>
            </div>
            <div class="modal-body" id="modal_para_crear_pacientes">
               <div class="row">
                @if ($this->profile()->rol === 'Director')
                    <div class="col-12 mb-2">
                        <label for="sede_dir" class="form-label"><b>Sede * </b></label>
                        <select name="sede_dir" id="sede_dir" class="form-select"></select>
                    </div>
                @endif
                <div class="col-xl-7 col-lg-8 col-md-6 col-sm-6 col-12">
                    <label for="tipo_doc"><b>Tipo documento de identidad </b></label>
                    <select name="tipo_doc" id="tipo_doc" class="form-select">
                        @if (isset($TipoDocumentos))
                            @foreach ($TipoDocumentos as $doc)
                             <option value="{{$doc->id_tipo_doc}}">{{$doc->name_tipo_doc}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-xl-5 col-lg-4 col-md-6 col-sm-6 col-12">
                    <label for="documento"><b># Documento <span class="text-danger">(*)</span></b></label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="documento" id="documento" placeholder="# documento..." >
                        <button class="btn btn-outline-primary" id="search_paciente_dni"><i class="fas fa-search"></i></button>
                    </div>
                </div>

                <div class="col-xl-7 col-lg-7 col-md-6 col-sm-6 col-12">
                    <label for="apellidos"><b>Apellidos completos <span class="text-danger">(*)</span></b></label>
                    <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="# Apellidos completos...">
                </div>

                <div class="col-xl-5 col-lg-5 col-md-6 col-sm-6 col-12">
                    <label for="nombres"><b>Nombres completos <span class="text-danger">(*)</span></b></label>
                    <input type="text" class="form-control" name="nombres" id="nombres" placeholder="# Nombres completos...">
                </div>

                <div class="col-xl-3 col-lg-3 col-md-4  col-12">
                    <label for="genero"><b>Género <span class="text-danger">(*)</span></b></label>
                    <select name="genero" id="genero" class="form-select">
                        <option value="1">Masculino</option>
                        <option value="2">Femenino</option>
                    </select>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-8 col-12">
                    <label for="direccion"><b>Departamento</span></b></label>
                    <select name="departamento_modal" id="departamento_modal" class="form-select"></select>
                </div>

                <div class="col-xl-5 col-lg-5  col-12">
                    <label for="direccion"><b>Provincia</span></b></label>
                    <select name="provincia_modal" id="provincia_modal" class="form-select"></select>
                </div>

                <div class="col-xl-5 col-lg-5  col-12">
                    <label for="direccion"><b>Distrito</span></b></label>
                    <select name="distrito_modal" id="distrito_modal" class="form-select"></select>
                </div>

                <div class="col-xl-7 col-lg-7  col-12">
                    <label for="direccion"><b>Dirección (opcional)</span></b></label>
                    <input type="text" class="form-control" name="direccion" id="direccion" placeholder="# Escriba la dirección del paciente...">
                </div>

                <div class="col-12">
                    <label for="fecha_de_nacimiento_modal_paciente" class="form-label"><b>Fecha de Nacimiento</b></label>
                    <input type="date" name="fecha_de_nacimiento_modal_paciente" id="fecha_de_nacimiento_modal_paciente" class="form-control"
                    value="{{$this->FechaActual("Y-m-d")}}" max="{{$this->FechaActual("Y-m-d")}}">
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <label for="telefono"><b># Teléfono <span class="text-danger">(*)</span></b></label>
                    <input type="text" class="form-control" name="telefono" id="telefono" placeholder="# telefono...">
                </div>

                <div class="col-xl-5 col-lg-5 col-md-6 col-sm-6 col-12">
                    <label for="wasap"><b>WhatsApp <span class="text-danger">(*)</span> <i class='bx bxl-whatsapp' ></i></b></label>
                    <input type="text" class="form-control" name="wasap" id="wasap" placeholder="Indique su whatsApp (opcional)...">
                </div>

                <div class="col-xl-4 col-lg-4 col-12">
                    <label for="estado_civil"><b>Estado civil <span class="text-danger">(*)</span></b></label>
                   <select name="estado_civil" id="estado_civil" class="form-select">
                    <option value="se">Sin especificar</option>
                    <option value="s">Soltero(a)</option>
                    <option value="c">Casado(a)</option>
                    <option value="v">viudo(a)</option>
                   </select>
                </div>

               </div>
               <div class="my-2" id="alerta_paciente" style="display: none">
                <div class="alert alert-danger">
                     <ul id="alerta_errores">
                        
                     </ul>
                </div>
               </div>
            </div>
            <div class="modal-footer border-2">
                <button class="btn_blue" id="save_paciente"><b>Guardar <i class='bx bxs-save' ></i></b></button>
            </div>
        </div>
    </div>
</div>
{{--- MODAL PARA CREAR UNA NUEVA CITA MÉDICA---}}
<div class="modal fade" id="modal_nueva_cita" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(to bottom, #00FFFF 0%,#e3f5ab 33%,#b7df2d 100%);">
                <p class="h3 letra text-white mt-1"><span id="texto_cita_">Registrar cita médica</span>  <img src="{{$this->asset('img/icons/unicons/clinica.ico')}}" class="text-white" class="menu-icon" alt=""></p>
                <button type="button" class="btn-close" id="close_modal_nueva_cita" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bodi_nueva_cita">

                <form action="" method="post" id="form_cita_medica_modal">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12">
                            <div class="form-group">
                                <label for="identificacion_modal"><b> # de Identificación <span class="text-danger">(*)</span></b></label>
                                <input type="text" class="form-control" name="identificacion_modal" id="identificacion_modal" autofocus
                                placeholder="xxx xxx xxx xxx">
                            </div>
                        </div>
        
                        <div class="col-xl-9 col-lg-9 col-md-8 col-sm-7 col-12">
                            <label for="paciente_modal"><b> Paciente <span class="text-danger">(*)</span></b></label>
                            <div class="input-group">
                           
                            <input type="text" class="form-control" name="paciente_modal" id="paciente_modal" readonly>
                  
                                <button class="button-new" id="search_paciente_modal"><i class='bx bx-search-alt-2'></i> Buscar</button >
                            </div>
                    
                            <span class="text-danger message_error_paciente_modal" style="display:none">El paciente no existe!☹️</span>
                        </div>
        
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-5 col-12">
                            <div class="form-group">
                                <label for="especialidad_modal"><b>Consultorio (Especialidad) <span class="text-danger">(*)</span></b></label>
                                <select name="especialidad_modal" id="especialidad_modal" class="form-select">
                                    <option disabled selected>-- Seleccione ---</option>
                                    @if (isset($Especialidades))
                                    @foreach ($Especialidades as $esp)
                                    <option value="{{$esp->id_especialidad}}">
                                        {{strtoupper($esp->nombre_esp)}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
        
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-7 col-12">
                            <div class="form-group">
                                <label for="medico_modal"><b> Médico <span class="text-danger">(*)</span></b></label>
                                <input type="text" class="form-control" name="medico_modal" id="medico_modal" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="asunto_modal"><b> Describir el motivo de la consulta </b></label>
                                <textarea name="asunto_modal" id="asunto_modal" cols="30" rows="5" class="form-control"
                                placeholder="escriba aquí...."></textarea>
                            </div>
                        </div>
                        <div class="col-12 mt-1" id="mensaje_modal_error" style="display: none">
                            <div class="alert alert-danger">No hay horarios disponibles en estos momentos, consulte mañana o seleccione otra fecha que sea > a la fecha actual, gracias!</div>
                        </div>
        
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="fecha_cita_modal"><b> Fecha de la cita <span class="text-danger">(*)</span></b></label>
                                <input type="date" class="form-control" name="fecha_cita_modal" id="fecha_cita_modal" value="{{$this->FechaActual("Y-m-d")}}"
                                min="{{$this->FechaActual("Y-m-d")}}" max="{{$this->addRestFecha("Y-m-d",'+ 7 day')}}">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="dia"><b> Día de la cita <span class="text-danger">(*)</span></b></label>
                                <input type="text" class="form-control text-end"
                                    name="dia_cita_modal" id="dia_cita_modal"
                                    readonly>
                            </div>
                        </div>
        
                        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                            <div class="form-group">
                                <label for="estado_modal"><b> Estado</b></label>
                                <select name="estado_modal" id="estado_modal" class="form-select" @if($this->profile()->rol === 'Paciente') readonly  @endif>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="pagado">Cita Confirmado</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row mt-2">
                        <div class="col-12" style="display: none" id="div_hora_cita_">
                            <label> <b>Hora de la cita</b></label>
                            <input type="text" class="form-control" id="hora_cita_modal_" readonly>
                          </div>
                      <div class="col-12">
                        <div class="card-text"><p class="h5">Horarios disponibles</p></div>
                        <select name="cita_horario_modal" id="cita_horario_modal" class="form-select border-primary">
                        </select>
                      </div>
                    </div>
                    <div class="d-flex flex-row mt-3 justify-content-end">
                         <div class="col-xl-3 col-lg-4 col-md-5 col-sm-5 col-12">
                            <label for=""><b>Monto a pagar S./ </b></label>
                           <input type="text" class="form-control" id="importe_modal" value="0.00">
                         </div>
                         <div class="col-xl-3 col-lg-4 col-md-5 col-sm-5 col-12 d-none">
                            <label for=""><b>Monto Clinica S./ </b></label>
                           <input type="text" class="form-control" id="importe_modal_clinica" value="0.00">
                         </div>
                         <div class="col-xl-3 col-lg-4 col-md-5 col-sm-5 col-12 d-none">
                            <label for=""><b>Monto Médico S./ </b></label>
                           <input type="text" class="form-control" id="importe_modal_medico" value="0.00">
                         </div>
                         
                    </div>
                     
                </form>  
            </div>
            <div class="modal-footer border-2">
                 <button class="btn_info_tw save_modal_cita" >Guardar<i class='bx bxs-save'></i></button>
                 <button class="btn btn-rounded btn-danger anular_cita" >Anular<i class='bx bxs-save'></i></button>
            </div>
        </div>
    </div>
</div>
{{--- MODAL PARA BUSCAR PACIENTES EXISTENTES----}}
<div class="modal" id="modal_search_paciente" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
            <h5 class="text-white mt-1">Consultar pacientes </h5>
            <img src="{{$this->asset('img/icons/unicons/paciente.ico')}}" class="menu-icon float-end text-white" alt="">
        </div>
        <div class="modal-body">
            <div class="row mb-2">
                <div class="col-xl-3 col-lg-4 col-md-7 col-12">
                    <label for="" class="form-label"><b>Buscar paciente</b></label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="search_por_doc" placeholder="Num.Documento">
                        <button class="btn btn-primary" id="buscar_otros_pacientes"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
 
                <table class="table table-bordered table-striped table-hover table-sm responsive nowrap" style="width: 100%" id="tabla_search_paciente">
                 <thead style="background: linear-gradient(to bottom, #b3dced 0%,#29b8e5 50%,#bce0ee 100%);">
                    <tr>
                        <th class="letra py-3">#</th>
                        <th class="letra py-3"># DOCUMENTO</th>
                        <th class="letra py-3">PACIENTE</th>
                        <th class="letra py-3">SEDE</th>
                        <th class="letra py-3">ENVIAR</th>
                    </tr>
                 </thead>
                </table>
        </div>
        <div class="modal-footer border-2">
            <button class="btn btn-rounded btn-danger float-end" id="close_searh_paciente"><i class='bx bx-x' ></i> Cerrar</button>
        </div>
         
      </div>
    </div>
</div>
 
@endsection

@section('js')
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>

  var RUTA = "{{URL_BASE}}" // la url base del sistema
  var TOKEN = "{{$this->Csrf_Token()}}";
  var ID_MEDICO_;
  var MONEDA = "{{count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda:'S/.'}}";
  var ID_MEDICO_ESP_;
  var ID_ESP;
  var PACIENTE_ID;
  var DIA;
  var MONTO_PAGO_CITA;
  var FechaCalendar = null;
  var BUTTON_CITA ='save';
  var HORARIO_ID;
  var PRECIOCITAMEDICA;
  var IMOPORTEMEDICO= 0;
  var IMPORTECLINICA=0;
  var HID;
  var CITA_ID;
  var PROFILE_ = "{{$this->profile()->rol}}";
  var DOC_USER = "{{$this->profile()->documento}}";
  var ServicioSeleccionado = null;
  var PACIENTE_TEXT;
  var CITA_ID_;
  var CORREO = null;
  var PRECIOSERVICIO = 0.00;
  var IMPORTEEDICIONCITATOTAL;
  var PORCENTAJEMEDICOEDICION;
  var PORCENTAJECLINICAEDICION;
  var EspecialidadCondition;
 
  loading('#cita_content','#4169E1','chasingDots');
  initCalendarCitaMedica();
  setTimeout(() => {
    $('#cita_content').loadingModal('hide');
    $('#cita_content').loadingModal('destroy');
  }, 1000);
  

  /// iniciar calendario de citas médicas
    
    function initCalendarCitaMedica()
    {
      
     let Fecha = new Date();
     let Anio = Fecha.getFullYear();
     let Mes = Fecha.getMonth()+1;
     let Dia = Fecha.getDate();
    document.addEventListener('DOMContentLoaded', function() {
     
       
    var calendarEl = document.getElementById('calendar_citas');
    var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    themeSystem: 'bootstrap',
    headerToolbar:{
        left:"prev,next today",
        center:"title",
        right:"dayGridMonth,timeGridWeek,timeGridDay"
    },
    locales:"es",
    events:RUTA+"citas-programados-calendar?token_="+TOKEN,
    eventColor: '#000',
    editable  : true,
    droppable : true, // this allows things to be dropped onto the calendar !!!
    eventTextColor: 'black',

    eventClick:function(info){

     // loading
     loading('#cita_content','#4169E1','chasingDots') 

     setTimeout(function(){
        
        $('#cita_content').loadingModal('hide');
        $('#cita_content').loadingModal('destroy');
        BUTTON_CITA ='editar';
        FechaCalendar = info.event.extendedProps.fecha_cita;
        $('#anular_button').show();
        $('#div_hora_cita_').show();
        
        $('#estado_modal').attr("disabled","true");
        $('#identificacion_modal').attr('disabled',true);
        
        $('#dia_cita_modal').val(getDay(FechaCalendar));
        DIA = getDay(FechaCalendar);
        
        showProcedimientosEdicion(info.event.extendedProps.id_especialidad);
        $('#servicio_modal').val(info.event.extendedProps.id_servicio == null?'null':info.event.extendedProps.id_servicio);
        $('#fecha_cita_modal').val(FechaCalendar);
        $('#paciente_modal').val(info.event.extendedProps.paciente);
        $('#identificacion_modal').val(info.event.extendedProps.documento);
        $('#especialidad_modal').val(info.event.extendedProps.id_especialidad);
        $('#medico_modal').val(info.event.extendedProps.medico);
        $('#asunto_modal').val(info.event.extendedProps.observacion);
        $('#importe_modal').val(info.event.extendedProps.monto_pago);
        $('#importe_modal_clinica').val(info.event.extendedProps.monto_clinica);
        $('#importe_modal_medico').val(info.event.extendedProps.monto_medico);
        PORCENTAJECLINICAEDICION = info.event.extendedProps.precio_clinica
        PORCENTAJEMEDICOEDICION = info.event.extendedProps.precio_medico;
         
        $('#hora_cita_modal_').val(info.event.extendedProps.hora_cita);
        $('#estado_modal').val(info.event.extendedProps.estado);
        ID_MEDICO_ = info.event.extendedProps.id_medico; ID_ESP = info.event.extendedProps.id_especialidad;
        PACIENTE_ID = info.event.extendedProps.id_paciente;
        DIA = getDay(FechaCalendar);HORARIO_ID = info.event.extendedProps.id_horario;CITA_ID = info.event.id;
        PACIENTE_TEXT = info.event.extendedProps.paciente;
        HID = info.event.extendedProps.id_horario;
        getHourMedico(ID_MEDICO_,DIA,FechaCalendar);
        // abrimos nuevamente el modal de crear citas
        $('#texto_cita_').text("Editar cita");
        $('body').loadingModal('hide');
        $('body').loadingModal('destroy');
        
        $('#modal_nueva_cita').modal("show");
        }, 300);
        
    },
    dateClick:function(info){
        BUTTON_CITA ='save';
        $('#anular_button').hide();
        $('#div_hora_cita_').hide();
        $('#identificacion_modal').attr('disabled',false);
       if(info.allDay){
        $('#texto_cita_').text("Nueva cita");
         /// abrimos al modal de crear las citas médicas
         let fechaSeleccionado = info.dateStr;
         FechaCalendar = fechaSeleccionado;
         let FechaPartes = fechaSeleccionado.split("-");
         
         if(FechaPartes[0]>=Anio && FechaPartes[1]>=Mes && FechaPartes[2]>=Dia)
         {
            loading('#cita_content','#4169E1','chasingDots') 

            setTimeout(() => {

            $('#fecha_cita_modal').val(fechaSeleccionado);
            //$('#modal_nueva_cita').modal("show");
          
            $('#dia_cita_modal').val(getDay(fechaSeleccionado));
            DIA = getDay(fechaSeleccionado);
            $('#cita_content').loadingModal('hide');
            $('#cita_content').loadingModal('destroy');
            }, 500);
           
         }
         else
         {
           Swal.fire(
            {
                title:'Mensaje del sistema!',
                text:'Error, para crear una nueva cita, debe de seleccionar una fecha mayor o igual a la fecha actual',
                icon:'error'
            }
           )
         }
        // $('#modal_nueva_cita').modal("show");
       }
    },
    eventResize: function(info) {
    alert(info.event.title + " end is now " + info.event.end.toISOString());

    if (!confirm("is this okay?")) {
      info.revert();
    }
  }
    });
    calendar.render();
    });
    
    }

    
</script>
 
<script>
    var TablaCitasPendientes;
    var TablaSearchPaciente;
    var TablaCitasAnulados;
    var Text_Check = true;
  $(document).ready(function(){
    /***************** Datos de entrada ************************/ 
    let Fecha = $('#fecha'); let Dia = $('#dia'); let Especialidad = $('#especialidad');
    let Identificacion = $('#identificacion'); let Documento = $('#documento');
    let Apellidos = $('#apellidos'); let Nombres = $('#nombres'); let Genero = $('#genero');
    let Direccion = $('#direccion'); let Telefono = $('#telefono');let Wasap = $('#wasap');
    let Horario = $('#cita_horario');let Asunto = $('#asunto'); let Estado = $('#estado');
    let EstadoCivil = $('#estado_civil'); let TipoDocumento = $('#tipo_doc');
    let IdentificacionModal = $('#identificacion_modal'); let EspecialidadModal = $('#especialidad_modal');
    let FechaCitaModal = $('#fecha_cita_modal');let DiaModal = $('#dia_cita_modal');
    let HorarioCitaModal = $('#cita_horario_modal');let AsuntoModal = $('#asunto_modal');
    let EstadoModal = $('#estado_modal');let ServicioModal = $('#servicio_modal');
    let Hora_text_cita = $('#hora_cita_modal_'); let ImporteCitaModal = $('#importe_modal');

    let ImporteTotalParaMedico = $('#importe_medicototal');
    let ImporteTotalClinica = $('#importe_clinicatotal');

 
    showProcedimientos(Especialidad.val());

    /// GENERAR IDENTIFICADOR
    GenerarIdentificador();
    
    if(PROFILE_ !== 'Paciente' && PROFILE_ !== 'Médico'){
        showMedicoPorDefecto(Especialidad.val());
    }
    
     

    if(PROFILE_ === 'Médico'){
        medico_id_ = "{{isset($this->MedicoData()->id_medico)?$this->MedicoData()->id_medico:null}}";
        ID_MEDICO_ = PROFILE_ === 'Médico' ? medico_id_:0;
    }
    /// Buscar paciente
    $('#search_paciente').click(function(evento){
     evento.preventDefault();
     
      showSearchPaciente();
    $('#modal_search_paciente').modal("show");
      selectPacienteSearch(TablaSearchPaciente,'#tabla_search_paciente tbody');
    });

    $('#buscar_otros_pacientes').click(function(){
        //showSearchPaciente();
         TablaSearchPaciente.ajax.reload();
    })

    $('#especialidad').change(function(){
        if(PROFILE_ === 'Admisión'){
            $('#medico').val("");
        }
        ID_MEDICO_ESP_= $(this).val();
        
        showProcedimientos(ID_MEDICO_ESP_)
        
    });

    $('#importe').keyup(function(){
        let ImporteTotalCita = $(this).val();

        let total_med = parseFloat(ImporteTotalCita *(IMOPORTEMEDICO/100)).toFixed(2);
        let total_clinic = parseFloat(ImporteTotalCita *(IMPORTECLINICA/100)).toFixed(2);
        
        $('#importe_medicototal').val(total_med);
        $('#importe_clinicatotal').val(total_clinic);
    });

    
    $('#importe_modal').keyup(function(){
        let ImporteTotalCitaModal = $(this).val();
      
        let total_medModal = parseFloat(ImporteTotalCitaModal *(PORCENTAJEMEDICOEDICION/100)).toFixed(2);
        let total_clinicModal = parseFloat(ImporteTotalCitaModal *(PORCENTAJECLINICAEDICION/100)).toFixed(2);
        
        $('#importe_modal_medico').val(total_medModal);
        $('#importe_modal_clinica').val(total_clinicModal);
    });

    $('#search_paciente_modal').click(function(evento){
      evento.preventDefault();
       
      showSearchPaciente();
   $('#modal_search_paciente').modal("show");
      selectPacienteSearch(TablaSearchPaciente,'#tabla_search_paciente tbody');
    });

    $('#close_searh_paciente').click(function(){
        $('#search_por_doc').val("");
        showSearchPaciente();
        $('#modal_search_paciente').modal("hide");
    });

    $('#search_por_doc').keyup(function(){
        if($(this).val().trim().length == 0){
            TablaSearchPaciente.ajax.reload();
        }
    });

    $('#ul_citas button').on('click',function(){
        const item = $(this)[0].id;
        if(item === 'citasna')
        {
            CitasAnulados_();
            ClearCitaAnulada(TablaCitasAnulados,'#tabla_citas_anuladas tbody');
        }
        else
        {
            if(item === 'pendientes_')
            {
                CitasPendientesNoAtendidos();
            }
        }
    });

    $('#drop_citas_anuladas').click(function(){
        if(verificarSeleccionados() > 0){
            Swal.fire({
        title: 'Estas seguro de eliminar todas las citas anuladas?',
        text: "Al dar que si, se limpiarán todas las citas anuladas de la base de datos",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#00FF7F',
        cancelButtonColor: '#FF4500',
        confirmButtonText: 'Si, eliminar!'
      }).then((result) => {
     if (result.isConfirmed) {
       /// Limpiar todas las citas anulados
       dropCitasAnulados(TablaCitasAnulados,'#tabla_citas_anuladas tbody tr');
     }
     });
        }
        else
        {
            Swal.fire({
                title:"Aviso del sistema!",
                text:"No hay seleccionados, proceda a seleccionar las citas anuladas que desea eliminar",
                icon:"info"
            });
        }
    });
    // $('#servicio').change(function(){
    //     ServicioSeleccionado = $(this).val();
    //     $.ajax({
    //         url:RUTA+"consulta-price-servicio/"+ServicioSeleccionado,
    //         method:"GET",
    //         dataType:"json",
    //         success:function(response)
    //         {
    //             let importeTotal_ = response.servicio.precio_servicio;
    //              $('#importe').val(importeTotal_);
    //              IMOPORTEMEDICO = response.servicio.precio_medico;
    //              IMPORTECLINICA = response.servicio.precio_clinica;

    //              $('#importe_medicototal').val(parseFloat(importeTotal_* (IMOPORTEMEDICO/100)).toFixed(2));
    //              $('#importe_clinicatotal').val(parseFloat(importeTotal_* (IMPORTECLINICA/100)).toFixed(2));
    //         }
    //     });
    // });

    ServicioModal.change(function(){
          
        getService($(this).val());
    });

    $('#todo_all').click(function(){
       if(Text_Check)
       {
        MarcarTodo("true");
        $('#all_label').text("Desmarcar todo");
        Text_Check = !Text_Check;
       }
       else
       {
        MarcarTodo(false);
        $('#all_label').text("Seleccionar todo");
        Text_Check = !Text_Check;
       }
    })

    /*
    *Verificamos si es paciente el documento es automático
     */

     if(PROFILE_ === 'Paciente')
     {
        loading('#cita_content','#4169E1','chasingDots');
        setTimeout(() => {
        $('#cita_content').loadingModal('hide');
        $('#cita_content').loadingModal('destroy');
        var PACIENTEAUTH = "{{$this->profile()->apellidos}} {{$this->profile()->nombres}}";
    
        Especialidad.val("{{$this->getSession('espec_id')}}");
        $('#medico').val("{{$this->getSession('name_medico')}}");
        ID_MEDICO_ESP_ = "{{$this->getSession('espec_id')}}";
        CORREO = "{{$this->profile()->email}}";
        showProcedimientos(ID_MEDICO_ESP_,1);

        ID_MEDICO_ = "{{$this->getSession('medic_id')}}";
 
        DIA = getDay(FechaCitaModal.val());
         getHourMedico(ID_MEDICO_,DIA,FechaCitaModal.val());
        let Paciente_Dato = consultaPaciente(DOC_USER); 
        var PACIENTE_DATA_ID = Paciente_Dato[0].id_paciente;
        Identificacion.val(DOC_USER); $('#paciente').val(PACIENTEAUTH.toUpperCase());
        PACIENTE_ID = PACIENTE_DATA_ID;  Especialidad.focus();
        }, 1000);
     }
    CitasPendientesNoAtendidos();
    anularCitaMedica();
    ShowDepartamentos('departamento_modal');
    //showProvincias('ANCASH','provincia_modal');
    //showDistritos_Provincia('CARHUAZ','distrito_modal');

    /// al seleccionar un departamento, deberá de mostrar las provincias
    $('#departamento_modal').change(function(){
        showProvincias($(this).val(),'provincia_modal');  
        showDistritos_Provincia($('#provincia_modal').val(),'distrito_modal');
    });
    /// al seleccionar una provincia, deberá de mostrar los distritos de la provincia
    $('#provincia_modal').change(function(){
        showDistritos_Provincia($(this).val(),'distrito_modal');
    });
    if(Dia.val().trim().length == 0)
    {
        Dia.val(getDay(Fecha.val())+" (Hoy)");
        DIA = getDay(Fecha.val());
    }

    Fecha.change(function(){
        if(ID_MEDICO_ ==null)
        {
            $('#identificacion').focus();
            Fecha.val("{{$this->FechaActual('Y-m-d')}}")
            Swal.fire({
               title:'¡ADVERTENCIA!',
               text:'Ingrese los datos del paciente y seleccione al médico con lo cuál el paciente desea sacar la cita',
               icon:'error' 
            }) 
        }
        else
        {
            Dia.val(getDay($(this).val()));/// obtenemos el día de acuerdo a la fecha seleccionado

            DIA = getDay($(this).val());
            getHourMedico(ID_MEDICO_,DIA,$(this).val());
        }
        
    });

    FechaCitaModal.change(function(){

        Hora_text_cita.val("");
        if(ID_MEDICO_ ==null)
        {
            $('#identificacion').focus();
            FechaCitaModal.val("{{$this->FechaActual('Y-m-d')}}")
            Swal.fire({
               title:'¡ADVERTENCIA!',
               text:'Ingrese los datos del paciente y seleccione al médico con lo cuál el paciente desea sacar la cita',
               icon:'error' 
            }) 
        }
        else
        {
            DiaModal.val(getDay($(this).val()));/// obtenemos el día de acuerdo a la fecha seleccionado

            DIA = getDay($(this).val());
            getHourMedico(ID_MEDICO_,DIA,$(this).val());
        } 
    });

    Especialidad.change(function(){
        if(PROFILE_ === 'Admisión' || PROFILE_ === 'Director')
        {
            $('#medicos').modal("show");
        MedicosDisponiblesEspecialidad($(this).val());
        importCitaMedica($(this).val());
        }
    });

    Especialidad.keypress(function(){
        if(PROFILE_ === 'Admisión' || PROFILE_ === 'Director')
        {
        $('#medicos').modal("show");
        MedicosDisponiblesEspecialidad($(this).val());
        importCitaMedica($(this).val());
        } 
    })

    EspecialidadModal.change(function(){
        $('#modal_nueva_cita').modal("hide");
        $('#medicos').modal("show");
        showMedicoPorDefecto($(this).val());
        MedicosDisponiblesEspecialidad($(this).val());
        EspecialidadCondition = 'new_medic';
        //importCitaMedica($(this).val());
    });

    HorarioCitaModal.change(function(){

        HORARIO_ID = null;

        let Hora_cita_text = $('#cita_horario_modal option:selected').text();

        Hora_text_cita.val(Hora_cita_text);
    });

    $('#cerrar_modal__medic_citas').click(function(){
       $('#medicos').modal("hide");
       if(FechaCalendar != null){
        $('#modal_nueva_cita').modal("show");
       }
    });

    $('#close_modal_nueva_cita').click(function(){
     $('#form_cita_medica_modal')[0].reset();
     FechaCalendar = null;$('#cita_horario_modal').empty();
     EstadoModal.attr('disabled',false);
     BUTTON_CITA = 'save';$('#anular_button').hide();
     $('#div_hora_cita_').hide();
    });
    

    /// guardar la cita médica
    $('.save_cita').click(function(evento){
        evento.preventDefault();
        if(Identificacion.val().trim().length == 0)
        {
            Identificacion.focus();
        }
        else
        {
            if(Especialidad.val() === null)
            {
                Especialidad.focus();
            }
            else
            {
                if(Horario.val() === null)
                {
                 Swal.fire({
                 title:"¡ADVERTENCIA!",
                 text:"Debe de seleccionar un horario",
                 icon:"error",
                 target:document.getElementById('layout-menu')
                 })
                }
                else
                {
                    if($('#importe').val() < 0 && PROFILE_ !== 'Paciente'){
                            $('#importe').focus();
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Ingrese el monto a pagar !",
                            icon:"warning"
                        });
                    }else{
                    let Horario_Id = $('#cita_horario').val();
                    let Hora_cita = $('#cita_horario option:selected').text();
                    saveCitaMedica(Fecha.val(),Asunto.val(),Estado.val(),Horario_Id,PACIENTE_ID,ServicioSeleccionado,Hora_cita,$('#importe').val(),ID_MEDICO_,Especialidad.val(),
                    CORREO,$('#paciente').val(),$('#medico').val(),$('#especialidad option:selected').text(),$('#servicio option:selected').text(),ImporteTotalParaMedico.val(),ImporteTotalClinica.val(),$('#serie_cita').val());
                    }
                }
            }
        }
        
    });

    /// guardar la cita médica a traves del modal , full calendar
    $('.save_modal_cita').click(function(evento){
        evento.preventDefault();
      if(BUTTON_CITA === 'save')
      {
        if(IdentificacionModal.val().trim().length == 0)
        {
            IdentificacionModal.focus();
        }
        else
        {
            if(EspecialidadModal.val() === null)
            {
                EspecialidadModal.focus();
            }
            else
            {
                if(HorarioCitaModal.val() === null)
                {
                 Swal.fire({
                 title:"¡ADVERTENCIA!",
                 text:"Debe de seleccionar un horario",
                 icon:"error",
                 target:document.getElementById('modal_nueva_cita')
                 })
                }
                else
                {
                    let Horario_Id = $('#cita_horario_modal').val();
                    let Hora_cita = $('#cita_horario_modal option:selected').text();
                    saveCitaMedica(FechaCitaModal.val(),AsuntoModal.val(),EstadoModal.val(),Horario_Id,PACIENTE_ID,$('#servicio_modal').val(),Hora_cita,$('#importe_modal').val(),ID_MEDICO_,EspecialidadModal.val(),
                    '','','','','');
                }
            }
        }
      }else
      {
       if(Hora_text_cita.val().trim().length == 0)
       {
        Swal.fire(
            {
                title:'Mensaje del sistema!',
                text:'Seleccione un horario para guardar su cita médica',
                icon:'error',
                target:document.getElementById('modal_nueva_cita')
            }
        )
       }else
       {
      
        if(HORARIO_ID != null)
       {
    
        updateCitaMedica(CITA_ID,FechaCitaModal,AsuntoModal,HORARIO_ID,PACIENTE_ID,ServicioModal,Hora_text_cita,ImporteCitaModal,ID_MEDICO_,ID_ESP,$('#importe_modal_medico'),$('#importe_modal_clinica'));
       }
       else
       {
    
        updateCitaMedica(CITA_ID,FechaCitaModal,AsuntoModal,HorarioCitaModal.val(),PACIENTE_ID,ServicioModal,Hora_text_cita,ImporteCitaModal,ID_MEDICO_,ID_ESP,$('#importe_modal_medico'),$('#importe_modal_clinica'));
       }
       }
      }
    });

    /// registrar a un nuevo paciente
    $('#save_paciente').click(function(){

       if(PROFILE_ === 'Director'){
        if($('#sede_dir').val() == null){
           Swal.fire({
                title:"MENSAJE DLE SISTEMA!!",
                text:"SELECCIONE LA SEDE PARA ESTE PACIENTE!!",
                icon:"error",
                target:document.getElementById('modal_new_paciente')
                });
         }else{
            if($('#distrito_modal').val() == null){
            $('#departamento_modal').focus();
            Swal.fire({
            title:"MENSAJE DLE SISTEMA!!",
            text:"DEBES DE SELECCIONAR UNA DIRECCIÓN COMPLETA PARA EL PACIENTE (DEPARTAMENTO/PROVINCIA/DISTRITO).",
            icon:"error",
            target:document.getElementById('modal_new_paciente')
            });
            }else{
            RegistrarAlPaciente();
            }
          }
            return;
        }
       if($('#distrito_modal').val() == null){
          $('#departamento_modal').focus();
          Swal.fire({
            title:"MENSAJE DLE SISTEMA!!",
            text:"DEBES DE SELECCIONAR UNA DIRECCIÓN COMPLETA PARA EL PACIENTE (DEPARTAMENTO/PROVINCIA/DISTRITO).",
            icon:"error",
            target:document.getElementById('modal_new_paciente')
          });
       }else{
         RegistrarAlPaciente();
       }
    });

    /// Boton de full calendar para anular la cita mèdica
    $('.anular_cita').click(function(evento){
       evento.preventDefault();
        Swal.fire({
        title: 'Estas seguro de anular la cita médica del paciente <b class="text-primary">'+PACIENTE_TEXT+'</b> ?',
        text: "Al presionar que si, usted esta indicando que se va anular por completo la cita del paciente, lo cuál dicha cita ya no estará disponible para la atención médica !",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, anular la cita!',
        target:document.getElementById('modal_nueva_cita')
        }).then((result) => {
        if (result.isConfirmed) {
        
         ConfirmarAnulado(CITA_ID,PACIENTE_TEXT,HORARIO_ID);
        }
        })
    });

    //// BUSCAR A LA PERSONA POR DNI USANDO LA API PERU
    $('#search_paciente_dni').click(function(){
        
      if($('#tipo_doc option:selected').text().toUpperCase() === 'DNI'){
        if($('#documento').val().trim().length < 8 || $('#documento').val().trim().length > 8){
          Swal.fire({
            title:"MENSAJE DEL SISTEMA!!",
            text:"Debes de ingresar 8 dígitos para realizar la consulta!!",
            icon:"error",
            target:document.getElementById('modal_new_paciente')
          });
          $('#documento').focus();
        }else{
          BuscarPersonaPorDni($('#documento').val());
        }
      }
    });

    /// buscar persona por dni usando la api PERU al presionar enter
    $('#documento').keypress(function(evento){
      if(evento.which == 13){
        if($(this).val().trim().length == 0){
             $(this).focus();
        }else{
           if($(this).val().trim().length < 8 || $(this).val().trim().length> 8){
                Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:"Debes de ingresar 8 dígitos para realizar la consulta!!",
                icon:"error",
                target:document.getElementById('modal_new_paciente')
                });
                $('#documento').focus();
            }else{
                BuscarPersonaPorDni($(this).val());  
            }
        }
      }
    })
    /// enter formulario de crear pacientes
    enter('apellidos','nombres');
    enter('nombres','direccion');
    enter('direccion','telefono');
    enter('telefono','wasap');
    enter('identificacion_modal','identificacion_modal');

    $('#cerrar_modal_new_paciente').click(function(){
        $('#alerta_errores').empty();
        $('#alerta_paciente').hide();
        $('#modal_new_paciente').modal("hide");
        $('#provincia_modal').empty();
        $('#distrito_modal').empty();
        if(FechaCalendar != null)
        {
            $('#modal_nueva_cita').modal("show");
        }
        Apellidos.val("");Nombres.val("");Direccion.val("");
        Telefono.val("");Wasap.val("");Genero.val("1");
        EstadoCivil.val("se");
        Identificacion.focus();
    });

    Identificacion.keyup(function(){
      
       if($(this).val().trim().length>= 8)
        {
        let data_ = consultaPaciente($(this).val());
        if(data_ !== 'no existe')
        {
        $('.message_error_paciente').hide();
        PACIENTE_ID = data_[0].id_paciente;
        CORREO = data_[0].email;
        $('#paciente').val(data_[0].paciente.toUpperCase());
        Especialidad.focus();
        }
        else
        {
        PACIENTE_ID = null;
        ShowDepartamentos('departamento_modal');
        $('.message_error_paciente').text("El paciente no existe!☹️");
        $('.message_error_paciente').show();
        $('#paciente').val("");
        Documento.val($(this).val());
        $('#modal_new_paciente').modal("show");
        Apellidos.focus();
         sedesDisponibles("#sede_dir");
        }
        }
        else
        {
         if($(this).val().trim().length > 0)
         {
        $('#paciente').val("");
        $('.message_error_paciente').text("Complete como mínimo 8 caracteres...");
        $('.message_error_paciente').show();
        PACIENTE_ID = null;
         }
         else
         {
        $('#paciente').val("");
        $('.message_error_paciente').text("");
        $('.message_error_paciente').hide();
        PACIENTE_ID = null;
         }
        }
    });

    IdentificacionModal.keyup(function(){
      
      if($(this).val().trim().length>= 8)
       {
       let data_ = consultaPaciente($(this).val());
       if(data_ !== 'no existe')
       {
       $('.message_error_paciente_modal').hide();
       PACIENTE_ID = data_[0].id_paciente;
       $('#paciente_modal').val(data_[0].paciente);
       EspecialidadModal.focus();
       }
       else
       {
       PACIENTE_ID = null;
       $('.message_error_paciente_modal').text("El paciente no existe!☹️");
       $('.message_error_paciente_modal').show();
       $('#paciente').val("");
       Documento.val($(this).val());
       $('#modal_nueva_cita').modal("hide");
       $('#modal_new_paciente').modal("show");
       Apellidos.focus();
       }
       }
       else
       {
        if($(this).val().trim().length > 0)
        {
       $('#paciente_modal').val("");
       $('.message_error_paciente_modal').text("Complete como mínimo 8 caracteres...");
       $('.message_error_paciente_modal').show();
       PACIENTE_ID = null;
        }
        else
        {
       $('#paciente_modal').val("");
       $('.message_error_paciente_modal').text("");
       $('.message_error_paciente_modal').hide();
       PACIENTE_ID = null;
        }
       }
   });

    Dia.click(function(){
       if($('#paciente').val().trim().length == 0)
       {
         Identificacion.focus();
       }
       else
       {
        if($('#medico').val().trim().length ==0)
        {
            Especialidad.focus();
        }
        else
        {
           getHourMedico(ID_MEDICO_,DIA,$('#fecha').val())
        }
       }
    });
    citarMedico('#tabla_medico_citas');
     
    enter('identificacion','identificacion');

function RegistrarAlPaciente(){
    $.ajax({
    url:RUTA+"paciente/save/proceso_cita_medica",
    method:"POST",
    data:{token_:TOKEN,doc:Documento.val(),apell:Apellidos.val(),nomb:Nombres.val(),genero:Genero.val(),direccion:Direccion.val(),
    tipo_doc:TipoDocumento.val(),telefono:Telefono.val(),wasap:Wasap.val(),estado_civil:EstadoCivil.val(),distrito:$('#distrito_modal').val(),
    fecha_de_nacimiento_modal_paciente:$('#fecha_de_nacimiento_modal_paciente').val(),sede:$('#sede_dir').val()},
    success:function(response)
    {
    response = JSON.parse(response);
    
    if(response.response instanceof Object)
    {
    $('#alerta_paciente').show(600);
    let li = '';
    
    response.response.forEach(error=> {
    li+=`<li>`+error+`</li>`;
    });
    $('#alerta_errores').html(li);
    }
    else{
    Swal.fire(
    {
    title:"Mensaje del sistema!",
    text:"Paciente registrado correctamente",
    icon:"success",
    target:document.getElementById('modal_new_paciente')
    }).then(function(){
    
    let data_ = consultaPaciente(Documento.val());
    if(data_ !== 'no existe')
    {
    $('.message_error_paciente').hide();
    PACIENTE_ID = data_[0].id_paciente;
    $('#paciente').val(data_[0].paciente);
    }
    $('#alerta_errores').empty();
    $('#alerta_paciente').hide();
    //$('#modal_new_paciente').modal("hide");
    // RESETEMAMOS FORMULARIO
    Apellidos.val("");Nombres.val("");Direccion.val("");
    Telefono.val("");Wasap.val("");Genero.val("1");
    EstadoCivil.val("se");$('#especialidad').focus();
    $('#sede_dir').prop("selectedIndex",0);
    $('#departamento_modal').prop("selectedIndex",0);
    $('#provincia_modal').empty();
    $('#distrito_modal').empty();
    });
    }
    }
    });
    }
  });

  /// modifica la cita médica
  function updateCitaMedica(id_cita_,fecha_cita_,obs_,horario_,paciente_,servicio_,horacita_,monto_,medico_,especialidad_,MontoModalMedico,MontoModalClinica)
  {
    $.ajax({
        url:RUTA+"citamedica/"+id_cita_+"/"+HID+"/update",
        method:"POST",
        data:{token_:TOKEN,fecha_cita:fecha_cita_.val(),obs:obs_.val(),horario:horario_,paciente:paciente_,serv:servicio_.val(),horacita:horacita_.val(),monto:monto_.val(),medico:medico_,esp:especialidad_,
            importe_modal_medico:MontoModalMedico.val(),importe_modal_clinica:MontoModalClinica.val()
        },
        success:function(response)
        {
            response = JSON.parse(response);

            if(response.response === 'ok')
            {
              Swal.fire(
                {
                    title:'Mensaje del sistema!',
                    text:'La cita médica del paciente '+PACIENTE_TEXT+" a sido modificado correctamente!",
                    icon:'success',
                    target:document.getElementById('modal_nueva_cita')
                }
              ).then(function(){
                location.href = RUTA+"crear-nueva-cita-medica";
              });
            }
        }
    })
  }

  /// obtener el día de la cita
  function getDay(fecha_)
  {
    let respuesta = show(RUTA+"obtener_dia_segun_fecha?token_="+TOKEN,{fecha:fecha_});
    /// obtenemos el día por defecto
   return respuesta;
  }

  /// mostra médicos disponibles por especialidad
  function MedicosDisponiblesEspecialidad(especialidad_medico)
  {
    let TablaMedicEspecialidad = $('#tabla_medico_citas').DataTable({
        processing: true,
        responsive:true,
        bDestroy:true,
        autoWidth:true,
        language:SpanishDataTable(),
       "columnDefs": [{
        "searchable": false,
        "orderable": false,
        "targets": 0
        }],
        "order": [[0, 'asc']], /// enumera indice de las columnas de Datatable
        "ajax":{
            url:RUTA+"medicos_por_escpecialidad/"+especialidad_medico+"?token_="+TOKEN,
            method:"GET",
            dataSrc:"medicos"
        },
        "columns":[
            {"data":"medico"},
            {"data":"id_medico_esp"},
            {"data":"id_especialidad"},
            {"data":"id_medico"},
            {"data":"medico",render:function(medicodata){
                return medicodata.toUpperCase();
            }},
            {"defaultContent":`
            <button class="btn btn-rounded btn-primary btn-sm" id="selec_cita_medico"><i class="bx bx-subdirectory-right"></i></button>
            `}
        ],
        columnDefs:[
            { "sClass": "hide_me", targets: [1,2,3] }
        ]
         
    });

     /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
     TablaMedicEspecialidad.on( 'order.dt search.dt', function () {
      TablaMedicEspecialidad.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();

  //TablaMedicEspecialidad.columns( [1,2,3] ).visible( false );
 
  }

  function showMedicoPorDefecto(especialidad_data)
  {
    $.ajax({
        url:RUTA+"medico-default/cita-medica/"+especialidad_data,
        method:"GET",
        dataType:"json",
        success:function(response){
           
            ID_MEDICO_ESP_= response.medico.id_medico_esp;
            ID_MEDICO_ = response.medico.id_medico; 
            $('#medico').val(response.medico.medicodata);
            
        }
    })
  }
  function citarMedico(Tbody)
  {
    $(Tbody).on('click','#selec_cita_medico',function(){
  
        let fila = $(this).closest('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        //ID_MEDICO_ESP_= fila.find('td').eq(1).text();
        ID_MEDICO_ = fila.find('td').eq(3).text();
        let id_esp = fila.find('td').eq(2).text();
        
        let Medico = fila.find('td').eq(4).text();
        
        $('#medico').val(Medico);
        if(FechaCalendar != null)
        {
            ID_ESP = id_esp;
            $('#medico_modal').val(Medico);
            $('#dia_cita_modal').val(getDay(FechaCalendar));/// obtenemos el día de acuerdo a la fecha seleccionado
            
            DIA = getDay(FechaCalendar);
            getHourMedico(ID_MEDICO_,DIA,FechaCalendar);
            $('#medicos').modal("hide");
            $('#modal_nueva_cita').modal("show");
            showProcedimientosEdicion($('#especialidad_modal').val());
        }else{
        showProcedimientos(ID_MEDICO_ESP_,1);
        getHourMedico(ID_MEDICO_,DIA,$('#fecha').val());
         
        $('#medicos').modal('hide');
        $('#asunto').focus();
        }
    });
  }

  /// MOSTRAR LAS CITAS PENDIENTES NO ATENDIDOS
  function CitasPendientesNoAtendidos()
  {
    TablaCitasPendientes = $('#tabla_citas_pendientes').DataTable({
    responsive:true,
    bDestroy:true,
    language:SpanishDataTable(),
    "columnDefs": [{
        "searchable": false,
        "orderable": false,
        "targets": 0
        }],
        "order": [[0, 'asc']], /// enumera indice de las columnas de Datatable
    ajax:{
        url:RUTA+"cita_medica/pacientes_no_atendidos?token_="+TOKEN,
        method:"GET",
        dataSrc:"pacientes",
    },
    columns:[
        {"data":"cliente"},
        {"data":"id_cita_medica"},
        {"data":"id_horario"},
        {"data":"cliente"},
        {"data":"hora_cita",render:function(hora){return '<span class="badge bg-info">'+hora+'</span>'}},
        {"data":"estado",render:function(estado){return '<b class="text-danger">pendiente</b>';}},
        {"data":null,render:function(data_){return '<button class="btn btn-rounded btn-danger btn-sm" id="anular_cita_pendiente">anular <i class="bx bx-x"></i></button>';}}
    ],
    columnDefs:[
                    { "sClass": "hide_me", targets: [1,2] }
     ]
    });

     /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
       TablaCitasPendientes.on( 'order.dt search.dt', function () {
        TablaCitasPendientes.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
   }).draw();
  }

  /// VER LOS PROCEDIMIENTOS DEL MÉDICO CON RESPECTO A UNA ESPECIALIDAD
  function showProcedimientos(id)
  {
    let respuesta = show(RUTA+"services/"+id+"/por-especialidad");
   
    /// obtenemos el día por defecto
    let option = PROFILE_ === 'Admisión' || PROFILE_ === 'Director' || PROFILE_ === 'Paciente' ?  '<option disabled selected>--- Seleccione ---</option>':'';
    if(respuesta.length > 0){
        respuesta.forEach(proc => {
        option+=`
        <option value=`+proc.id_servicio+`>`+proc.name_servicio+" - Precio( "+MONEDA+" "+proc.precio_servicio+` )</option>
        `;
    });
   // PRECIOSERVICIO = respuesta[0].precio_servicio;
    //IMOPORTEMEDICO = respuesta[0].precio_medico;
    //ServicioSeleccionado = respuesta[0].id_servicio;
    //IMPORTECLINICA = respuesta[0].precio_clinica;
    //showProcedimientos($(this).val());

    //$('#importe').val(PRECIOSERVICIO);
    //$('#importe_medicototal').val(parseFloat(PRECIOSERVICIO* (IMOPORTEMEDICO/100)).toFixed(2));
    //$('#importe_clinicatotal').val(parseFloat(PRECIOSERVICIO* (IMPORTECLINICA/100)).toFixed(2));
    //$('#servicio').html(option);
    $('#importe').val("0.00");
    }else{
    
        PRECIOSERVICIO = 0.00;
        $('#servicio').html(option);
        IMOPORTEMEDICO = 0.00;
        IMPORTECLINICA=0.00;
        $('#importe').val("0.00");
        $('#importe_medicototal').val(0);
        $('#importe_clinicatotal').val(0);
        ServicioSeleccionado = null;

    }

    if($('#medico').val().trim().length > 0){
    $('#importe').attr("disabled",false);
    $('.save_cita').show();
    }else{
    $('#importe').attr("disabled",true);
    $('.save_cita').hide()
    }
  }

  /// procedimiento para editar
   /// VER LOS PROCEDIMIENTOS DEL MÉDICO CON RESPECTO A UNA ESPECIALIDAD
   function showProcedimientosEdicion(id)
  {
    let respuesta = show(RUTA+"services/"+id+"/por-especialidad");
     
    let option = '';
  
    /// obtenemos el día por defecto
    if(respuesta.length > 0){
        respuesta.forEach(element => {
            option+=`<option value=`+element.id_servicio+`>`+element.name_servicio.toUpperCase()+" => {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} "+element.precio_servicio+`</option>`;
        });

        $('#servicio_modal').html(option);
    }
  }

  /// obtener servicio
  function getService(id){
    let respuesta = show(RUTA+"obtener-service/"+id);
    IMPORTEEDICIONCITATOTAL = respuesta[0].precio_servicio;
    PORCENTAJECLINICAEDICION = respuesta[0].precio_clinica;
    PORCENTAJEMEDICOEDICION = respuesta[0].precio_medico;
    

    let ImporteTotalClinicaEnEdicion = (IMPORTEEDICIONCITATOTAL * (PORCENTAJECLINICAEDICION/100)).toFixed(2);
    let ImporteTotalMedicoEnEdicion = (IMPORTEEDICIONCITATOTAL * (PORCENTAJEMEDICOEDICION/100)).toFixed(2);
    $('#importe_modal').val(respuesta[0].precio_servicio); 
    $('#importe_modal_clinica').val(ImporteTotalClinicaEnEdicion); 
    $('#importe_modal_medico').val(ImporteTotalMedicoEnEdicion); 
  }

  /// consultar paciente
  function consultaPaciente(document_paciente)
  {
    let respuesta = show(RUTA+"consulta_paciente/"+document_paciente+"?token_="+TOKEN);

    return respuesta;
     
  }

  /// consultar horarios del médico
  function getHourMedico(medico_id_,dia_,fecha_)
  {
    let respuesta = show(RUTA+"horarios_programador_del_medico/"+medico_id_+"/"+dia_+"?token_="+TOKEN,{fecha:fecha_});

    let Option = '<option disabled selected> --- Seleccione un horario --- </option>';
 

     if(respuesta.length > 0)
     {
       respuesta.forEach(hr => {
        
        Option+=`<option value=`+hr.id_horario+`>`+hr.hora_inicio+` - `+hr.hora_final+`</option>`;
        });
        
        if(FechaCalendar != null)
        {
            $('#mensaje_modal_error').hide();
        }
     }
     else
     {
        if(FechaCalendar!= null)
        {
            $('#mensaje_modal_error').show();
        }else
        {
            $('#mensaje_modal_error').hide();
            Swal.fire({
            title:'Mensaje del sistema !',
            text:'No hay horarios disponibles en estos momentos, consulte mañana o seleccione otra fecha que sea > a la fecha actual, gracias!',
            icon:"error",
            target:document.getElementById('cita_content')
        }) 
        }
     }     
     if(FechaCalendar != null)
     {
      $('#cita_horario_modal').html(Option);
     }
     else
     {
        $('#cita_horario').html(Option);
     }
     
  }

  /// obtener el importe de la cita
  function importCitaMedica(id)
  {
    let respuesta = show(RUTA+"/obtener_precio_cita_medica/"+id+"?token_="+TOKEN);
    $('#importe').val(respuesta.precio_especialidad);
    if(FechaCalendar != null)
    {
        $('#importe_modal').val(respuesta.precio_especialidad);
    }
  }

  /// registrar una cita médica
  function saveCitaMedica(fecha_,observacion_,estado_,horario_id,paciente_,servicio_,hora_cita_,importe_,medico__,especialidad__,
  correo_,name_data,doctora_,esp_data,serv_data,medicoimporte,clinicaimporte,SerieCita)
  {
    $.ajax({
        url:RUTA+"cita_medica/save",
        method:"POST",
        data:
        {token_:TOKEN,
        fecha:fecha_,
        observacion:observacion_,
        estado:estado_,
        id_horario:horario_id,
        paciente:paciente_,
        servicio:servicio_,
        hora_cita:hora_cita_,
        monto:importe_,
        medico:medico__,
        especialidad:especialidad__,
        correo:correo_,
        name_:name_data,
        doctora:doctora_,
        esp:esp_data,
        serv:serv_data,
        monto_medico:medicoimporte,
        monto_clinica:clinicaimporte,
        serie:SerieCita
        },
        beforeSend:function(){
            loading('#cita_content','#4169E1','chasingDots');
        },
        success:function(response)
        {
            response = JSON.parse(response);

            if(response.response == 'ok')
            {
              
                if(PROFILE_ === 'Admisión'  || PROFILE_ === 'Director'|| PROFILE_ === 'Médico')
              {
                $('#cita_content').loadingModal('hide');
                 $('#cita_content').loadingModal('destroy');
                Swal.fire({
                title:'Mensaje del sistema',
                text:'La cita médica para el paciente '+$('#paciente').val()+' a sido registrado correctamente 😎',
                icon:'success',
                }).then(function(){
                  
                     /// imprimimos reporte de la cita realizada
                  window.open(RUTA+"cita-medica/generate/recibo/"+$('#serie_cita').val());
                    setTimeout(() => {
                        location.href=RUTA+"crear-nueva-cita-medica";
                    }, 500);
                 
                });
                }
                else
                {
                    $('#cita_content').loadingModal('hide');
                    $('#cita_content').loadingModal('destroy');
                Swal.fire({
                title:'Mensaje del sistema',
                text:'La cita médica para el paciente '+$('#paciente').val()+' a sido registrado correctamente, por favor revise su correo, le hemos enviado un mensaje con los datos de la reserva de su cita que acaba de realizar! 😎',
                icon:'success'
                }).then(function(){
                    
                    location.href=RUTA+"seleccionar-especialidad";
                    setTimeout(() => {
                        location.href=RUTA+"crear-nueva-cita-medica";
                    }, 500);
                });
                }
                 
            }
             
            else
            {
                Swal.fire({
                    title:'Mensaje del sistema',
                    text:'Acaba de ocurrir un error al intentar registrar la cita médica para el paciente '+$('#paciente').val()+' ☹️',
                    icon:'error'
                });  
            }
        }
    })
  }

  /// anular cita médica
  /// anular cita médica
  function anularCitaMedica()
        {
            $('#tabla_citas_pendientes').on('click','#anular_cita_pendiente',function(){
               
                /// obtener la fila seleccionada
                let fila = $(this).closest('tr');

                if(fila.hasClass('child'))
                {
                    fila = fila.prev();
                }

                /// obtenemos los datos
                let Id_Cita_Medica_ = fila.find('td').eq(1).text(); /// el id de la cita médica
                let paciente = fila.find('td').eq(3).text(); /// dato del pacient
                let Horario_Id_cita = fila.find('td').eq(2).text();/// id del horario de la cita médica
                ConfirmarAnulado(Id_Cita_Medica_,paciente,Horario_Id_cita);
                
            });
        }

        function ConfirmarAnulado(cita_,paciente,hi)
        {
            loading('#bodi_nueva_cita','#4169E1','chasingDots') 
            $.ajax({
                url:RUTA+"anular_cita_medica",
                method:"POST",
                data:{token_:TOKEN,horario:hi,cita:cita_},
                success:function(data_)
                {
                    data_ = JSON.parse(data_);

                    if(data_.response == 1)
                    {
                       if(FechaCalendar != null)
                       {

                        setTimeout(() => {
                            $('#bodi_nueva_cita').loadingModal('hide');
                            $('#bodi_nueva_cita').loadingModal('destroy');
                            Swal.fire({
                            title:"Mensaje del sistema",
                            text:"La cita médica del paciente "+paciente+" se ha anulado correctamente",
                            icon:"success",
                            target:document.getElementById('modal_nueva_cita')
                        }).then(function(){
                            
                            if(FechaCalendar != null)
                            {
                                location.href= RUTA+"crear-nueva-cita-medica";
                            }
                            else
                            {
                                CitasPendientesNoAtendidos();
                            }
                        });
                        }, 800);
                       }
                       else
                       {
                        $('#bodi_nueva_cita').loadingModal('hide');
                        $('#bodi_nueva_cita').loadingModal('destroy');
                        Swal.fire({
                            title:"Mensaje del sistema",
                            text:"La cita médica del paciente "+paciente+" se ha anulado correctamente",
                            icon:"success",
                        }).then(function(){
                            
                            if(FechaCalendar != null)
                            {
                                location.href= RUTA+"crear-nueva-cita-medica";
                            }
                            else
                            {
                                CitasPendientesNoAtendidos();
                            }
                        });
                       }
                    }
                    else
                    {
                        Swal.fire({
                            title:"¡ADVERTENCIA!",
                            text:"Error al intentar anular la cita médica del paciente "+paciente,
                            icon:"error",
                            target:document.getElementById('modal_nueva_cita')
                        })  
                    }
                }
              })
        }

/// método que muestrar los departamentos en format json
function ShowDepartamentos(idselect)
{
    /// inicializamos la option del select
    let option ="<option>-- Seleccione ---</option>";
    
    $.ajax(
        {
          url:RUTA+"departamento/mostrar?token_="+TOKEN,

          method:"GET",

          success:function(response)
          {
            response = JSON.parse(response)
            
            if(response.response.length > 0)
            {
                response.response.forEach(documento => {
                    
                    option+=
                     `
                      <option value=`+documento.id_departamento+`>`+documento.name_departamento.toUpperCase()+`</option>
                    `;
                });
            }
            $('#'+idselect).html(option);
          } 
        }
    )
}


/// mostrar las provincias existentes por departamento

function showProvincias(id_dep,select_id)
{
  let option ="";
  
  let resultado = show(RUTA+"provincia/mostrar?token_="+TOKEN,{id_departamento:id_dep});
  
  if(resultado.length > 0)
  {
   resultado.forEach(provincia => {
    
    option+= "<option value="+provincia.id_provincia+">"+provincia.name_provincia.toUpperCase()+"</option>";

   });
   
  }
  $('#'+select_id).html(option);
}

/// mostramos los distros por provincia

function showDistritos_Provincia(id_prov,select_id)
{
  let option ="";
  
  let resultado = show(RUTA+"distritos/mostrar-para-la-provincia/"+id_prov+"?token_="+TOKEN);

  if(resultado.length > 0)
  {
   resultado.forEach(distrito => {
    
    option+= "<option value="+distrito.id_distrito+">"+distrito.name_distrito.toUpperCase()+"</option>";

   });
   
  }
  $('#'+select_id).html(option);
}

function showSearchPaciente()
{
    TablaSearchPaciente = $('#tabla_search_paciente').DataTable(
        {
            retrieve:true,
            processing:true,
            responsive:true,
           
            language:SpanishDataTable(),
             "columnDefs": [{
             "searchable": false,
             "orderable": false,
             "targets": 0
           }],
            ajax:{
                url:RUTA+"Buscar-paciente?token_="+TOKEN,
                method:"GET",
                data:function(persona){
                    persona.doc=$('#search_por_doc').val()
                },
                dataSrc:"pacientes"
            },
            columns:[
                {"data":"documento"},
                {"data":"documento"},
                {"data":"paciente",render:function(pacient){
                    return pacient.toUpperCase()
                }},
                {"data":"namesede",render:function(namesede){
                  return namesede.toUpperCase();
                }},
                {"data":null,render:function(){
                  return `
                    <button class="btn rounded btn-outline-primary btn-sm" id='select_paciente'><i class='bx bx-paper-plane'></i></button>
                    `
                },className:"text-center"}
            ]
        }
    ).ajax.reload();

    TablaSearchPaciente.on( 'order.dt search.dt', function () {
    TablaSearchPaciente.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();
}

/// click al boton seleccionar paciente
function selectPacienteSearch(Tabla,Tbody)
{
  $(Tbody).on('click','#select_paciente',function(){
    let fila = $(this).parents('tr');

    if(fila.hasClass("child"))
    {
        fila = fila.prev();
    }

    let dataPaciente = Tabla.row(fila).data();
 
    if(FechaCalendar!=null)
    {
        $('#identificacion_modal').val(dataPaciente.documento); $('#paciente_modal').val(dataPaciente.paciente.toUpperCase());
    }
    else
    {
        FechaCalendar = null;
        $('#identificacion').val(dataPaciente.documento); $('#paciente').val(dataPaciente.paciente.toUpperCase());
    }
    
    $('#modal_search_paciente').modal('hide');PACIENTE_ID = dataPaciente.id_paciente;
    CORREO = dataPaciente.email; 
  });
}
/// citas anulados
var CitasAnulados_ = ()=>{
    TablaCitasAnulados = $('#tabla_citas_anuladas').DataTable({
        processing:true,
        autoWidth:true,
        retrieve:true,
        autoWidth:true,
        language:SpanishDataTable(),
      
        "columnDefs": [{
             "searchable": false,
             "orderable": false,
             
             "targets": 0
           }],
        ajax:{
            url:RUTA+"citas-anulados?mitoken_="+TOKEN,
            method:"GET",
            dataSrc:"citas_anulados",
        },
        columns:[
            {"data":"fecha"},
            {"data":null,render:function(){
                return '<input type="checkbox" id="sel">';
            }},
            {"data":null,render:function(){
                return '<button class="btn btn-rounded btn-danger btn-sm" id="delete_cita_anulado"><i class="bx bxs-x-circle"></i></button>';
            }},
            {"data":null,render:function(dta){return dta.fecha+' [ '+dta.hora_cita+' ] '}},
            {"data":"medico_data"},
            {"data":"nombre_esp"},
            {"data":"paciente_data"},
            {"data":"estado",render:function(estado){return '<span class="badge bg-warning">ANULADO</span>';}}
        ]
    }).ajax.reload();

    TablaCitasAnulados.on( 'order.dt search.dt', function () {
    TablaCitasAnulados.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();
}

// verificar si tengo seleccionados
function verificarSeleccionados()
{
    let contadorSeleccionados = 0;
    $('#tabla_citas_anuladas tbody input[type=checkbox]').each(function(){
         
        if($(this).is(":checked"))
        {
            contadorSeleccionados++;
        }
    })

    return contadorSeleccionados;
}

/// seleccionar todo
function MarcarTodo(estado)
{
    $('#tabla_citas_anuladas tbody input[type=checkbox]').each(function(){
        $(this).prop("checked",estado);
     })
}

/// eliminar todas las citas anuladas
function dropCitasAnulados(Tabla,Tbody)
{
  loading('#cita_content','#4169E1','chasingDots');
  setTimeout(() => {
    $('#cita_content').loadingModal('hide');
    $('#cita_content').loadingModal('destroy');
    let respuesta = null;
  $(Tbody).each(function(){
     let fila = $(this);

     if(fila.find('#sel').is(":checked")){
   

     if(fila.hasClass("child"))
     {
        fila = fila.prev();
     }

     let DatosIdCita = Tabla.row(fila).data();
     

     respuesta = DeleteCitasAnulados_(DatosIdCita.id_cita_medica);
    }
  });

  if(respuesta === 'ok')
  {
    Swal.fire({
        title:"Mensaje del sistema !",
        text:"Citas anulados eliminados correctamente",
        icon:"success"
    }).then(function(){
        CitasAnulados_();
    });
  }
  }, 500);
 
}

/// eliminar las citas anulados
 function DeleteCitasAnulados_(id){
    let MessageResp = null;
    $.ajax({
        url:RUTA+"delete/"+id+"/citas_anulados",
        method:"POST",
        data:{token_:TOKEN},
        async:false,
        success:function(response){
            response = JSON.parse(response);
            MessageResp = response.response;
        }
    });

    return MessageResp;
}

function ClearCitaAnulada(Tabla,Tbody)
{
    $(Tbody).on('click','#delete_cita_anulado',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }
        let Data = Tabla.row(fila).data();
        Swal.fire({
        title: 'Estas seguro de eliminar la cita anulada de la fecha '+Data.fecha+' ['+Data.hora_cita+']'+'?',
        text: "Al dar que si, se limpiarán todas las citas anuladas de la base de datos",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#00FF7F',
        cancelButtonColor: '#FF4500',
        confirmButtonText: 'Si, eliminar!'
      }).then((result) => {
     if (result.isConfirmed) {
       /// Limpiar todas las citas anulados
       
        CITA_ID_ = Data.id_cita_medica;
        let resp =  DeleteCitasAnulados_(CITA_ID_);
        if(resp === 'ok')
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Cita anulada eliminado correctamente",
                icon:"success"
            }).then(function(){
                CitasAnulados_();
            });
        }
     }
     });
    });
}

/*CONSULTAR PERSONA POR DNI DESDE LA API PERU*/
function BuscarPersonaPorDni(docnum){
    $.ajax({
        url:RUTA+"persona/seach/api-peru",
        method:"POST",
        data:{token_:TOKEN,dni:docnum},
        
        success:function(response){
          response = JSON.parse(response);
           
          if(response.data != undefined){
            loading('#modal_para_crear_pacientes','#4169E1','chasingDots');
            setTimeout(() => {
              $('#modal_para_crear_pacientes').loadingModal('hide');
              $('#modal_para_crear_pacientes').loadingModal('destroy');
              $('#apellidos').val(response.data.apellido_paterno+" "+response.data.apellido_materno);
              $('#nombres').val(response.data.nombres);
              $('#direccion').val(response.data.direccion);
              $('#direccion').focus();
            }, 1000);
          }else{
            loading('#modal_para_crear_pacientes','#4169E1','chasingDots');
            setTimeout(() => {
              $('#modal_para_crear_pacientes').loadingModal('hide');
              $('#modal_para_crear_pacientes').loadingModal('destroy');
              Swal.fire({
              title:"MENSAJE DEL SISTEMA!!",
              text:"NO SE ENCONTRÓ A LA PERSONA CON ESE # DNI",
              icon:"error",
              target:document.getElementById('modal_new_paciente')
            }).then(function(){
              $('#apellidos').val("");
              $('#nombres').val("");
              $('#direccion').val("");
            });
            $('#documento').val("");
            $('#documento').focus();
            }, 1000);
          }
        }
       }) 
  }
    /*MOSTRAR LAS SEDES DISPONIBLES*/
  function sedesDisponibles(sedeid){
    let option = '<option disabled selected>--- Seleccione ----</option>';
    axios({
      url:RUTA+"sedes/all/disponibles",
      method:"GET",
    }).then(function(response){
        if(response.data.sedes.length > 0){
           response.data.sedes.forEach(sede => {
              option+=`<option value=${sede.id_sede}>${sede.namesede.toUpperCase()}</option>`;
           });
        }
        $(sedeid).html(option);
    });
  }

  /*GENERAR */
  function GenerarIdentificador(){
    axios({
        url:RUTA+"cita-medica/generate/identificador",
        method:"POST",
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.error,
                icon:"error"
            });
        }else{
            $('#serie_cita').val(response.data.serie)
        }
    });
  }

</script>
@endsection