@extends($this->Layouts("dashboard"))

@section("title_dashboard","Pacientes-triaje")

@section('css')
    <style>
        #tabla_pacientes_triaje>thead>tr>th
        {
            background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);
            color:aliceblue;
            padding: 18px;

        }

        #tab_triaje>li>a{
            color:rgb(49, 70, 194)
        }
        #tabla_pacientes_personalizado>thead>tr>th{
            background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);
            color:aliceblue;
            padding: 19px;
        }
    
        td.hide_me
       {
        display: none;
       }
    </style>
@endsection
@section('contenido')
<div class="card" id="cardtriaje">
    <ul class="nav nav-tabs" id="tab_triaje">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#clasificacion_paciente" id="clasificacion_paciente_">Clasificación de pacientes
            <img src="{{$this->asset('img/icons/unicons/paciente.ico')}}" class="menu-icon" alt="">
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#paciente_personalizado" id="paciente_personalizado_">Confirmar asistencia del paciente
            <img src="{{$this->asset('img/icons/unicons/confirm_asistencia.ico')}}" class="menu-icon" alt="">
          </a>
        </li>
      </ul>


      <div class="tab-content" >
        <div class="tab-pane fade show active" id="clasificacion_paciente" role="tabpanel" aria-labelledby="clasificacion_paciente">
            <div class="card-text">
                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-5 col-12">
                    <button class="btn btn-rounded btn-outline-info form-control" id="refresh_info_triaje"><b>Refrescar <i class='bx bx-refresh'></i></b></button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped nowrap" id="tabla_pacientes_triaje" style="width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ACCIÓN</th>
                                <th>PACIENTE</th>
                                <th>HORA DE LA CITA</th>
                                <th>CONSULTORIO</th>
                                <th>MÉDICO</th>
                                <th>ESTADO</th>
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
            <div class="card-text p-3" style="display: none" id="form_paciente_triaje">
                <div class="row">
                 <div class="col-12">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" id="folio" readonly>
                            <label for="folio"><b>N° FOLIO</b></label>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                        <div class="form-group">
                            <label for=""><b># DOCUMENTO</b></label>
                            <input type="text" class="form-control" id="documento" readonly>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                        <div class="form-group">
                            <label for=""><b>PACIENTE</b></label>
                            <input type="text" class="form-control" id="paciente" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-12">
                        <div class="form-group">
                            <label for=""><b>EDAD DEL PACIENTE</b></label>
                            <input type="text" class="form-control" id="edad" readonly>
                        </div>
                    </div>
                   <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                    <div class="form-group">
                            <label for=""><b>GÉNERO DEL PACIENTE</b></label>
                            <input type="text" class="form-control" id="genero" readonly>
                        </div>
                   </div>
                   <div class="col-xl-9 col-lg-9 col-md-6 col-12">
                    <div class="form-group">
                            <label for=""><b>CONSULTORIO</b></label>
                            <select name="consultorio" id="consultorio" class="form-select">
                                @if (isset($especialidades))
                                    @foreach ($especialidades as $consultorio)
                                        <option value="{{$consultorio->id_especialidad}}">{{strtoupper($consultorio->nombre_esp)}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                   </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for=""><b>MOTIVO DE LA CONSULTA</b></label>
                            <textarea name="motivo" id="motivo" cols="30" rows="3" class="form-control" placeholder="Escriba el motivo de la consulta del paciente....."></textarea>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                        <div class="form-group">
                            <label for=""><b>Presión arterial- mm Hg </b></label>
                            <input type="text" class="form-control" id="presion" >
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                        <div class="form-group">
                            <label for=""><b>Temperatura [°C]</b></label>
                            <input type="text" class="form-control" id="temperatura" >
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                        <div class="form-group">
                            <label for=""><b>Frecuencia cardiaca[T/minuto] </b></label>
                            <input type="text" class="form-control" id="frecuencia_car" >
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                        <div class="form-group">
                            <label for=""><b>Frecuencia respiratoria[T/minuto] </b></label>
                            <input type="text" class="form-control" id="frecuencia_resp" >
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                        <div class="form-group">
                            <label for=""><b>Saturación de oxigeno[%]</b></label>
                            <input type="text" class="form-control" id="oxigeno" >
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                        <div class="form-group">
                            <label for=""><b>Talla [Cm]  <span class="text-danger">(*)</span></b></label>
                            <input type="text" class="form-control" id="talla" >
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                        <div class="form-group">
                            <label for=""><b>Peso[Kg]  <span class="text-danger">(*)</span></b></label>
                            <input type="text" class="form-control" id="peso" >
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                        <div class="form-group">
                            <label for=""><b>IMC <span class="text-danger">(*)</span></b></label>
                            <input type="text" class="form-control" id="imc" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                        <div class="form-group">
                            <label for=""><b>EVALUACIÓN IMC <span class="text-danger">(*)</span></b></label>
                            <input type="text" class="form-control" id="imc_eval" readonly>
                        </div>
                    </div>
                
                    <div class="col p-2">
                        <button class="btn btn-rouded btn-success" id="save_paciente_triaje">Guardar <i class='bx bx-save'></i></button>
                        <button class="btn btn-rounded btn-danger" id="cancel">Cancelar <i class='bx bx-x' ></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="paciente_personalizado" role="tabpanel" aria-labelledby="paciente_personalizado">
            @php
                $FechaActual = $this->FechaActual("Y-m-d");
                $FechaActual = explode("-",$FechaActual);
                $FechaActual = $this->getDayDate($this->FechaActual("Y-m-d"))." , ".$FechaActual[2]."/".$FechaActual[1]."/".$FechaActual[0];
            @endphp
            <h4>Pacientes <span class="text-primary" id="title_fecha">{{$this->getFechaText($FechaActual)}} - Hoy</span></h4>

            <div class="form-group">
                <label for="fecha"><b>Fecha (*)</b></label>
                <input type="date" class="form-control" name="fecha" id="fecha_pers"
                value="{{$this->FechaActual("Y-m-d")}}" min="{{$this->FechaActual("Y-m-d")}}" max="{{$this->addRestFecha("Y-m-d","+7 day")}}">
            </div>
            <div id="listado_pacientes_triaje_" class="col-12 mt-2 table-responsive">
                <table class="table table-bordered table-striped nowrap resposive" id="tabla_pacientes_personalizado" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="d-none">ID</th>
                            <th>ASISTIO?</th>
                            <th>ESTADO</th>
                            <th>PACIENTE</th>
                            <th>HORA CITA</th>
                            <th>MOTIVO CONSULTA</th>
                            <th class="d-none">HORARIO</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
      </div>
</div>
@endsection
@section('js')
{{-- JS ADICIONALES ---}}
<script>
    var RUTA = "{{URL_BASE}}" // la url base del sistema
    var TOKEN = "{{$this->Csrf_Token()}}";
    var CITA_ID;
    var PACIENTE;
    var TRIAJE_ID;
    var CONTROL = 'create';
    var Tabla_Pacientes_Triaje;
    var FechaActual = "{{date('Y-m-d')}}";
    var TablaPacientesPersonalizado;
     
</script>
<script src="{{URL_BASE}}public/js/control.js"></script>
    <script>
        var HORAID;
        var CITAID_; 
    $(document).ready(function(){

        let Talla = $('#talla');let Peso = $('#peso');
        let PresionArterial = $('#presion'); let Temperatura = $('#temperatura');
        let FrecuenciaCardiaca = $('#frecuencia_car'); let FrecuenciaRespiratoria = $('#frecuencia_resp');
        let SaturacionOxigeno = $('#oxigeno'); let Imc = $('#imc');let EstadoImc = $('#imc_eval');
        GenerarFolioTriaje();
        mostrarPacienteTriaje();
        atencionTriaje(Tabla_Pacientes_Triaje,'#tabla_pacientes_triaje tbody');
        EditarTriaje(Tabla_Pacientes_Triaje,'#tabla_pacientes_triaje tbody');
        $('#cancel').click(function(){
            

            $('#form_paciente_triaje').hide(700)
        });
        /// al presionar en el tab
        $('#tab_triaje a').on('click',function(evento){
            evento.preventDefault();
           // obtenemos el id seleccionado
           if($(this)[0].id === 'clasificacion_paciente_')
           {
            mostrarPacienteTriaje();
            atencionTriaje(Tabla_Pacientes_Triaje,'#tabla_pacientes_triaje tbody');
            EditarTriaje(Tabla_Pacientes_Triaje,'#tabla_pacientes_triaje tbody');

            GenerarFolioTriaje();
           }
           else
           {
            MostrarPacientesEnTriajePersonalizado(FechaActual);
            ConfirmaAsistenciaCitaMedica(TablaPacientesPersonalizado);
            AnularAsistenciaCitaMedicaPaciente(TablaPacientesPersonalizado);
           }

           $(this).tab("show");
        });

        $('#fecha_pers').change(function(){
         
        loading('#paciente_personalizado','#4169E1','chasingDots');
        setTimeout(() => {
            $('#paciente_personalizado').loadingModal('hide');
            $('#paciente_personalizado').loadingModal('destroy');
            MostrarPacientesEnTriajePersonalizado($(this).val());
            
        }, 1000);
         $.ajax({
            url:RUTA+"devuelve_fecha_texto/"+$(this).val(),
            method:"GET",
            data:{token_:TOKEN},
            success:function(response)
            {
                response = JSON.parse(response);
            
                $('#title_fecha').text(response.response)
            }
         });
        });

        $('#imc').click(function(){

            if(Talla.val().trim().length == 0)
            {
                Talla.focus();
            }
            else
            {
                if(Peso.val().trim().length == 0)
                {
                    Peso.focus();
                }
                else{
                    /// calculamos el IMC
                    let Talla_  = parseFloat(Talla.val());
                    let Peso_ = parseFloat(Peso.val());
                    
                    let imc = Peso_/(Math.pow(Talla_,2));
                    let Estado_IMC;

                    $('#imc').val(imc.toFixed(1));

                    if(imc<18.5)
                    {
                        Estado_IMC = 'BAJO';
                    }
                    else{
                        if(imc>=18.5 && imc<=24.9)
                        {
                            Estado_IMC = 'PESO SALUDABLE';
                        }
                        else
                        {
                            if(imc>=25 && imc<=29.9)
                            {
                                Estado_IMC = 'SOBREPESO'
                            }
                            else
                            {
                                if(imc>30 && imc<=35)
                                {
                                    Estado_IMC = 'OBESIDAD I';
                                }
                                else
                                {
                                    if(imc>35 && imc<=40)
                                {
                                    Estado_IMC = 'OBESIDAD II';
                                }
                                else{
                                    if(imc>40 )
                                {
                                    Estado_IMC = 'OBESIDAD III';
                                }
                                }
                                }
                            }
                        }
                        
                    }
                    $('#imc_eval').val(Estado_IMC);
                }
            }
        });

        $('#save_paciente_triaje').click(function(){
            if($('#talla').val().trim().length == 0){
                 $('#talla').focus();
            }else{
                if($('#peso').val().trim().length == 0){
             $('#peso').focus();
           }else{
             if($('#imc').val().trim().length == 0){
                Swal.fire({
                     title:"MENSAJE DEL SISTEMA!!",
                     text:"Para continuar con el registro de triaje debes de calcular el valor del IMC.Para calcular el IMC dale clic en el campo IMC!",
                     icon:"error"
                   })
             }else{
                if($('#imc_eval').val().trim().length == 0){
                   Swal.fire({
                     title:"MENSAJE DEL SISTEMA!!",
                     text:"Para continuar con el registro de triaje debes de calcular el valor del IMC.Para calcular el IMC dale clic en el campo IMC!",
                     icon:"error"
                   })
                }else{
             if(CONTROL === 'create')
                    {
                    
                    savePacienteEnTriaje(PresionArterial,Temperatura,FrecuenciaCardiaca,FrecuenciaRespiratoria,SaturacionOxigeno,Talla,Peso,Imc,EstadoImc,CITA_ID,PACIENTE,$('#motivo'),$('#folio'))
                    }
                    else
                    {
                    UpdatePacienteEnTriaje(PresionArterial,Temperatura,FrecuenciaCardiaca,FrecuenciaRespiratoria,SaturacionOxigeno,Talla,Peso,Imc,EstadoImc,PACIENTE,CITA_ID,$('#motivo'),$('#folio'))
                    }
                }
             }
           }
            }
        });

        /// refrescar la información de triaje
        $('#refresh_info_triaje').click(function(){
            mostrarPacienteTriaje(); 
            EditarTriaje(Tabla_Pacientes_Triaje,'#tabla_pacientes_triaje tbody')
        });

        /// pasar enter
        enter('presion','temperatura');
        enter('temperatura','frecuencia_car');
        enter('frecuencia_car','frecuencia_resp');
        enter('frecuencia_resp','oxigeno');
        enter('oxigeno','talla');
        enter('talla','peso');
    });

    function mostrarPacienteTriaje()
    {
         Tabla_Pacientes_Triaje = $('#tabla_pacientes_triaje').DataTable({
          retrieve:true,
          responsive:true,
          language:SpanishDataTable(),
       "columnDefs": [{
        "searchable": false,
        "orderable": false,
        "targets": 0
        }],
        "order": [[2, 'asc']], /// enumera indice de las columnas de Datatable
          ajax:{
            url:RUTA+"triaje/pacientes/all?token_="+TOKEN,
            method:"GET",
            dataSrc:"response",
          },
          columns:[
            {"data":null},
            {"data":null,render:function(dta_){
                let boton = '';
                if(dta_.estado === 'pagado')
                {
                    boton = '<button class="btn btn-rounded btn-info btn-sm" id="triaje"><i class="bx bx-street-view"></i></button>';
                }
                else
                {
                    if(dta_.estado === 'examinado')
                    {
                        boton = '<button class="btn btn-rounded btn-warning btn-sm" id="editar_triaje"><i class="bx bx-edit-alt" ></i></button>';
                    }
                }
                return boton;
                
            }},
            {"data":"paciente",render:function(paciente){
                return paciente.toUpperCase();
            }},
            {"data":"hora_cita",render:function(hora_cita){return '<span class="badge bg-danger">'+hora_cita+'</span>';}},
            {"data":"nombre_esp",render:function(esp){
                return esp.toUpperCase();
            }},
            {"data":"medico",render:function(medico_){
                return medico_.toUpperCase();
            }},
            {"data":"estado",render:function(estado){
                if(estado === 'pagado')
                {
                    return '<span class="badge bg-success">CONFIRMADO</span>';
                }
                else
                {
                    return '<span class="badge bg-primary">'+estado+'</span>';
                }
            }}
          ]
        }).ajax.reload(null,false);
      /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
      Tabla_Pacientes_Triaje.on( 'order.dt search.dt', function () {
      Tabla_Pacientes_Triaje.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
      cell.innerHTML = i+1;
      } );
      }).draw();
     /*======================================================================================*/
     
    }

    function atencionTriaje(Tabla,Tbody)
    {
        $(Tbody).on('click','#triaje',function(){
            reset_();CONTROL = 'create';
            $('#form_paciente_triaje').show(700);
            BajadaScroll('.modal-body,html', 400);
            $('#presion').focus();
            let fila = $(this).parents('tr');

            if(fila.hasClass('child'))
            {
                fila = fila.prev();
            }

            let data_ = Tabla.row(fila).data();
            CITA_ID = data_.id_cita_medica;
            PACIENTE = data_.paciente;
            $('#paciente').val(data_.paciente);
            $('#documento').val(data_.documento);
            $('#motivo').val(data_.observacion);
            $('#genero').val(data_.genero === '1' ? 'MASCULINO' : (data_.genero === '0' ? 'FEMENINO':'OTRO'));
            $('#consultorio').val(data_.id_especialidad);
            if(data_.fecha_nacimiento != null)
            {
            let AnioNac = parseInt(data_.fecha_nacimiento.substr(0,4));

            let date_ = new Date();

            let AnioActual = date_.getFullYear();

            let EdadPaciente = AnioActual-AnioNac;
            $('#edad').val(EdadPaciente+" Años");
            
            }else
            {
                $('#edad').val("No especificó su fecha nacimiento");
            }
             
            
             
        });
    }

    function EditarTriaje(Tabla,Tbody)
    {
        $(Tbody).on('click','#editar_triaje',function(){
            CONTROL = 'update';
            $('#form_paciente_triaje').show(700);
            BajadaScroll('.modal-body,html', 400);
            $('#presion').focus();
            let fila = $(this).parents('tr');

            if(fila.hasClass('child'))
            {
                fila = fila.prev();
            }

            let data_ = Tabla.row(fila).data();
            CITA_ID = data_.id_cita_medica;
            PACIENTE = data_.paciente;
            $('#paciente').val(data_.paciente);
            $('#documento').val(data_.documento);
            $('#motivo').val(data_.observacion);
            $('#genero').val(data_.genero === '1' ? 'MASCULINO' : (data_.genero === '0' ? 'FEMENINO':'OTRO'));
            $('#consultorio').val(data_.id_especialidad);

            if(data_.fecha_nacimiento != null)
            {
            let AnioNac = parseInt(data_.fecha_nacimiento.substr(0,4));

            let date_ = new Date();

            let AnioActual = date_.getFullYear();

            let EdadPaciente = AnioActual-AnioNac;
            $('#edad').val(EdadPaciente+" Años");
            
            }else
            {
                $('#edad').val("No especificó su fecha nacimiento");
            }

            editarTriaje(data_.id_cita_medica);
             
            
             
        });
    }

    /// registrar paciente a triaje
    function savePacienteEnTriaje(presion_arterial_,temperatura_,frecuencia_cardiaca_,frecuencia_respiratoria_,saturacion_oxigeno_,talla_,peso_,imc_,estado_imc_,cita,paciente_,
        motivoData,FolioData
    )
    {
        $.ajax({
            url:RUTA+"triaje/paciente/save",
            method:"POST",
            data:{token_:TOKEN,
                presion_arterial:presion_arterial_.val(),
                temperatura:temperatura_.val(),
                frecuencia_cardiaca:frecuencia_cardiaca_.val(),
                frecuencia_respiratoria:frecuencia_respiratoria_.val(),
                saturacion_oxigeno:saturacion_oxigeno_.val(),
                talla:talla_.val(),peso:peso_.val(),imc:imc_.val(),estado_imc:estado_imc_.val(),
                cita_id:cita,motivo:motivoData.val(),folio:FolioData.val(),especialidad:$('#consultorio').val()},
          success:function(response)
          {
            response = JSON.parse(response);
            
            if(response.response === 'ok')
            {
                Swal.fire({
                    title:'Mensaje del sistema!',
                    text:'El triaje para el paciente '+paciente_+' se ha registrado correctamente',
                    icon:'success'
                }).then(function(){

                    mostrarPacienteTriaje();

                    /// reseteamos el formulario
                    presion_arterial_.val("");
                    temperatura_.val("");
                    frecuencia_cardiaca_.val("");
                    frecuencia_respiratoria_.val("");
                    saturacion_oxigeno_.val("");talla_.val("");
                    peso_.val("");imc_.val("");estado_imc_.val("");
                    $('#form_paciente_triaje').hide();
                    GenerarFolioTriaje();
                });
            }else
            {
                Swal.fire({
                    title:'Mensaje del sistema!',
                    text:'Error al intentar registrar el triaje para el paciente '+paciente_,
                    icon:'error',
                    target:document.getElementById('layout-menu')
                })
            }
          }
        });
    }

    /// actualizar triaje
    function UpdatePacienteEnTriaje(presion_arterial_,temperatura_,frecuencia_cardiaca_,frecuencia_respiratoria_,saturacion_oxigeno_,talla_,peso_,imc_,estado_imc_,paciente_,
        cita,observacionCita
    )
    {
        $.ajax({
            url:RUTA+"triaje/"+TRIAJE_ID+"/update",
            method:"POST",
            data:{token_:TOKEN,
                presion_arterial:presion_arterial_.val(),
                temperatura:temperatura_.val(),
                frecuencia_cardiaca:frecuencia_cardiaca_.val(),
                frecuencia_respiratoria:frecuencia_respiratoria_.val(),
                saturacion_oxigeno:saturacion_oxigeno_.val(),
                talla:talla_.val(),peso:peso_.val(),imc:imc_.val(),estado_imc:estado_imc_.val(),
                cita_id:cita,motivo:observacionCita.val(),especialidad:$('#consultorio').val()},
          success:function(response)
          {
            response = JSON.parse(response);
            
            if(response.response === 'ok')
            {
                Swal.fire({
                    title:'Mensaje del sistema!',
                    text:'Los datos del triaje para el paciente '+paciente_+' han sido modificados correctamente',
                    icon:'success',
                    target:document.getElementById('layout-menu')
                }).then(function(){

                    mostrarPacienteTriaje();

                    /// reseteamos el formulario
                    presion_arterial_.val("");
                    temperatura_.val("");
                    frecuencia_cardiaca_.val("");
                    frecuencia_respiratoria_.val("");
                    saturacion_oxigeno_.val("");talla_.val("");
                    peso_.val("");imc_.val("");estado_imc_.val("");

                    $('#form_paciente_triaje').hide();
                });
            }else
            {
                Swal.fire({
                    title:'Mensaje del sistema!',
                    text:'Error al intentar actualziar el triaje para el paciente '+paciente_,
                    icon:'error'
                })
            }
          }
        });
    }

    /// consultar triaje para la edición
    function editarTriaje(id_cita)
    {
        let response = show(RUTA+"triaje/"+id_cita+"/editar?token_="+TOKEN);
         TRIAJE_ID = response.id_triaje;

         $('#talla').val(response.talla); $('#peso').val(response.peso);
         $('#presion').val(response.presion_arterial); $('#temperatura').val(response.temperatura);
         $('#frecuencia_car').val(response.frecuencia_cardiaca); $('#frecuencia_resp').val(response.frecuencia_respiratoria);
         $('#oxigeno').val(response.saturacion_oxigeno); $('#imc').val(response.imc); $('#imc_eval').val(response.estado_imc);
         $('#folio').val(response.id_triaje);
    }

    function reset_()
    {
         $('#talla').val(""); $('#peso').val("");
         $('#presion').val(""); $('#temperatura').val("");
         $('#frecuencia_car').val(""); $('#frecuencia_resp').val("");
         $('#oxigeno').val(""); $('#imc').val(""); $('#imc_eval').val("");
         TRIAJE_ID = null;
    }

    function MostrarPacientesEnTriajePersonalizado(fecha_)
    {
        TablaPacientesPersonalizado = $('#tabla_pacientes_personalizado').DataTable({
            responsive:true,
            retrieve:true,
            langauge:SpanishDataTable(),
            "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
            }],
            ajax:{
                url:RUTA+"/pacientes-triaje-personalizado/"+fecha_+"?token_="+TOKEN,
                method:"GET",
                dataSrc:"pacientes"
            },
            columns:[
                {"data":"paciente"},
                {"data":"id_cita_medica"},
                {"data":null,render:function(estado){
                    if(estado.estado === 'pendiente' && estado.fecha_cita === FechaActual)
                    {
                        return `<button class="btn btn-success btn-sm" id="confirm"><b>confirmar <i class="bx bx-check"></i></b></button>
                               <button class="btn btn-danger btn-sm" id="anular_cita"><b>anular X</b></button>`;
                    }
                    return '';
                }},
                {"data":"estado",render:function(estado){
                    if(estado === 'pendiente')
                    {
                        return '<span class="text-danger">Sin confirmar la asistencia</span>';
                    }
                    return '<span class="text-primary">Asistencia confirmado</span>';
                }},
                {"data":"paciente"},
                {"data":"hora_cita",render:function(hora){return "<span class='badge bg-primary'>"+hora+"</span>"}},
                {"data":"observacion",render:function(motivo){
                    if(motivo == null)
                    {
                        return '<span class="badge bg-danger el motivo..">No especifica</span>';
                    }
                    return motivo;
                }},
                {"data":"id_horario"}
            ],
            columnDefs:[
                       { "sClass": "hide_me", target: [1,7] }
                       ]
        }).ajax.reload();
         TablaPacientesPersonalizado.on( 'order.dt search.dt', function () {
         TablaPacientesPersonalizado.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
         cell.innerHTML = i+1;
         });
         }).draw();
    }

function ConfirmaAsistenciaCitaMedica(Tabla)
    {
        $('#tabla_pacientes_personalizado tbody').on('click','#confirm',function(){
            let fila = $(this).parents('tr');

            if(fila.hasClass('child'))
            {
                fila = fila.prev();
            }

            CITA_ID =  fila.find('td').eq(1).text(); let PacienteText = fila.find("td").eq(4).text();
            Swal.fire({
  title: "<h4><b>Estas seguro de confirmar la asistencia a su cita médica para el paciente <span class='badge bg-primary'>"+PacienteText+"</span> ?</b></h4>",
  text: "Al confirmar la asistencia del paciente,pasa directo a triaje para la ser examinado!",
  icon: "question",
  showCancelButton: true,
  confirmButtonColor: "#00FF00",
  cancelButtonColor: "#DC143C",
  confirmButtonText: "Si, confirmar!",
  cancelButtonText:"Cancelar"
}).then((result) => {
  if (result.isConfirmed) {
    ConfirmarAsistenciaCita(CITA_ID,PacienteText)  
  }
});
 });
}

function AnularAsistenciaCitaMedicaPaciente(Tabla)
    {
        $('#tabla_pacientes_personalizado tbody').on('click','#anular_cita',function(){
            let fila = $(this).parents('tr');

            if(fila.hasClass('child'))
            {
                fila = fila.prev();
            }

            CITAID_ =  fila.find('td').eq(1).text(); let PacienteText = fila.find("td").eq(4).text();
            HORAID = fila.find('td').eq(7).text();
            
            Swal.fire({
              title: "<h4><b>Estas seguro de anular la cita médica para el paciente <span class='badge bg-primary'>"+PacienteText+"</span> ?</b></h4>",
              text: "Al anular la cita médica, se quitará de la lista de asistencias del paciente!",
              icon: "question",
              showCancelButton: true,
              confirmButtonColor: "#00FF00",
              cancelButtonColor: "#DC143C",
              confirmButtonText: "Si, confirmar!",
              cancelButtonText:"Cancelar"
            }).then((result) => {
              if (result.isConfirmed) {
                AnularCitaDataPaciente(HORAID,CITAID_,PacienteText);
              }
          });
     });
}
  

  /** Anular la cita programada **/ 
function AnularCitaDataPaciente(horacita,citamedica,paciente)
{
    loading('#cardtriaje','#4169E1','chasingDots') 
    $.ajax({
    url:RUTA+"anular_cita_medica",
    method:"POST",
    data:{token_:TOKEN,horario:horacita,cita:citamedica},
    success:function(data_)
    {
    data_ = JSON.parse(data_);
    
    if(data_.response == 1)
    {
     setTimeout(() => {
        $('#cardtriaje').loadingModal('hide');
        $('#cardtriaje').loadingModal('destroy');
     Swal.fire({
    title:"Mensaje del sistema",
    text:"La cita médica del paciente "+paciente+" se ha anulado correctamente",
    icon:"success"
    }).then(function(){
     MostrarPacientesEnTriajePersonalizado();
    });
     }, 800);
    }
    else
    {
    $('#cardtriaje').loadingModal('hide');
    $('#cardtriaje').loadingModal('destroy');
    Swal.fire({
    title:"¡ADVERTENCIA!",
    text:"Error al intentar anular la cita médica del paciente "+paciente,
    icon:"error"
    })
    }
    }
    });
}     
function ConfirmarAsistenciaCita(cita_medica,paciente)
{
           $.ajax({
                url:RUTA+"confirmar_pago_cita_medica",
                method:"POST",
                data:{token_:TOKEN,cita:cita_medica},
                success:function(data_)
                {
                    data_ = JSON.parse(data_);

                    if(data_.response == 1)
                    {
                        Swal.fire({
                            title:"Mensaje del sistema",
                            text:"La asistencia del paciente "+paciente+" a sido verificado correctamente",
                            icon:"success"
                        }).then(function(){
                          MostrarPacientesEnTriajePersonalizado(FechaActual);
                        });
                    }
                    else
                    {
                        Swal.fire({
                            title:"¡ADVERTENCIA!",
                            text:"Error al intentar validar la asistencia del paciente "+paciente,
                            icon:"error"
                        })  
                    }
                }
   });
}
 /*GENERAR */
  function GenerarFolioTriaje(){
    axios({
        url:RUTA+"triaje/generate-folio",
        method:"POST",
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.error,
                icon:"error"
            });
        }else{
            $('#folio').val(response.data.serie)
        }
    });
  }
 
    </script>
@endsection

