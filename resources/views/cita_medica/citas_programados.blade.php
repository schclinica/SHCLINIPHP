@extends($this->Layouts("dashboard"))

@section("title_dashboard","citas programados")

@section('css')
<style>
    input[type=radio]
    {
        width:23px;
        height:23px;
    }
    label{
        color: #6A5ACD;
    }
    label>b{
        color:#FF4500;
        cursor: pointer;
    }
    td.hide_me {
        display: none;
    }
</style>
@endsection 

@section("contenido")
<div class="card" id="cardcitasprogramadas">
    <div class="card-text mx-3">
        <ul class="nav nav-tabs nav-fill" role="tablist" id="citaspanel">
            
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#cita_calendario"
                   id="citasprogramados" aria-controls="navs-justified-profile" aria-selected="false" style="color:#466e9b">
                   <img src="{{$this->asset('img/icons/unicons/citas_programados.ico')}}" class="menu-icon" alt="" style="height: 20px"> <b>Citas programadas</b>
                </button>
            </li>
 
            {{-- <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#cita_form"
                    aria-controls="navs-justified-home" id="citas_por_medico" aria-selected="true" style="color: #636d69">
                    <img src="{{$this->asset('img/icons/unicons/medico.ico')}}" class="menu-icon" alt="" style="height: 20px"> <b>Consultar citas por médico</b>
    
                </button>
            </li>  --}}
        </ul>
    </div>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="cita_calendario" role="tabpanel">
            <div class="card-text mx-3 mt-3">
                Filtrar :
         
                <div class="row mt-2">
                   <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                     <div class="form-group">
                         <label for="diario" class="form-label"><input type="radio" id="diario" name="opcion" checked><b>  Citas diarios (Hoy)</b></label>
                        </div>
                   </div>
                   <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                    <div class="form-group">
                     <label for="semanal" class="form-label"><input type="radio" id="semanal" name="opcion"><b>  Citas de esta semana</b></label>
                    </div>
                   </div>
         
                   <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                     <div class="form-group">
                      <label for="mensual" class="form-label"><input type="radio" id="mensual" name="opcion"><b>  Citas de este mes</b></label>
                     </div>
                    </div>
         
                    <div class="col-xl-4 col-lg-4 col-md-5 col-12">
                     <div class="form-group">
                      <label for="fecha_cita" class="form-label"><input type="radio" id="fecha_cita" name="opcion"><b> Citas por fecha personalizado</b></label>
                     </div>
                    </div>
                    <div class="col-xl-7 col-lg-6 col-md-6 col-12" style="display: none" id="fecha_select">
                     <div class="form-group">
                      <input type="date" id="fecha" class="form-control" value="{{$this->FechaActual("Y-m-d")}}">
                     </div>
                    </div>
                </div>
               
             </div>
         
            <div class="card-text mx-3">
             <div class="table-responsive">
                 <table class="table table-striped nowrap" id="Tabla_citas_programados" style="width: 100%"> 
                     <thead style="background:linear-gradient(to bottom, rgba(75, 118, 236, 1) 100%,#bce0eeff 100%);">
                         <tr>
                             <th class="py-3 text-white letra">#</th>
                             <th class="d-none">ID_CITA</th>
                             <th class="d-none">ID_HORARIO</th>
                             <th class="py-3 text-white letra">CEDULA</th>
                             <th class="py-3 text-white letra">PACIENTE</th>
                             <th class="py-3 text-white letra">ESPECIALIDAD</th>
                             <th class="py-3 text-white letra">MÉDICO</th>
                             <th class="py-3 text-white letra">SUCURSAL</th>
                             <th class="py-3 text-white letra">FECHA DE LA CITA</th>
                             <th class="py-3 text-white letra">HORA CITA</th>
                             <th class="py-3 text-white letra">MOTIVO</th>
                             <th class="py-3 text-white letra">ESTADO</th>
                             <th class="py-3 text-white letra">IMPORTE</th>
                             <th class="py-3 text-white letra">MONTO PARA EL MEDICO</th>
                             <th class="py-3 text-white letra">MONTO PARA LA CLINICA</th>
                             <th class="py-3 text-white letra">ACCIONES</th>
                         </tr>
                     </thead>
                 </table>
             </div>
            </div>
        </div>
    
        <div class="tab-pane fade" id="cita_form" role="tabpanel">
             <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                    <div class="form-group">
                        <label for="especialidad" class="form-label">Especialidad</label>
                        <select name="especialidad" id="especialidad" class="form-select"></select>
                    </div>
                </div>

                <div class="col-xl-7 col-lg-7 col-md-6 col-12">
                    <div class="form-group">
                        <label for="medico" class="form-label">Médico</label>
                        <select name="medico" id="medico" class="form-select"></select>
                    </div>
                </div>
             </div>

             <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-bordered table-striped nowrap responsive" id="citas_por_medico" style="width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PACIENTE</th>
                                <th>ESPECIALIDAD</th>
                                <th>FECHA DE LA CITA</th>
                                <th>HORA DE LA CITA</th>
                                <th>ESTADO</th>
                                <th>IMPORTE S/. </th>
                            </tr>
                        </thead>
                    </table>
                </div>
             </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
 
  var RUTA = "{{URL_BASE}}" // la url base del sistema
  var TOKEN = "{{$this->Csrf_Token()}}";
  var CitasPorMedico;
</script>
<script src="{{URL_BASE}}public/js/control.js"></script>
    <script>
        $(document).ready(function(){
            showCitasProgramados("diario/diario");

            $('#semanal').click(function(){
                $('#fecha_select').hide(800);
                showCitasProgramados("semana/semana");
            });

            $('#diario').click(function(){
                $('#fecha_select').hide(800);
                showCitasProgramados("diario/diario");
            });

            $('#mensual').click(function(){
                $('#fecha_select').hide(800);
                showCitasProgramados("mensual/mensual");
            });
            $('#fecha_cita').click(function(){
                $('#fecha_select').show(800);
                showCitasProgramados("fecha/"+$('#fecha').val());
            });

            $('#fecha').change(function(){

                showCitasProgramados("fecha/"+$(this).val());
            });

            $('#citaspanel').on('click','button',function(){
                if($(this)[0].id === 'citas_por_medico'){
                    showCitasPorMedicoReporte();
                }
               $(this).tab("show");
            });
            
            anularCitaMedica();
            confirmPagoCitaMedica();
        });

        /*MOSTRAR LAS CITAS POR CADA MEDICO*/
        function showCitasPorMedicoReporte(){
            //CitasPorMedico = $('#citas_por_medico').DataTable({});
        }
        function showCitasProgramados(opcion)
        {
            let TablaCitasProgramados = $('#Tabla_citas_programados').DataTable({
                responsive:true,
                autoWidth:true,
                processing:true,
                bDestroy:true,
                language:SpanishDataTable(),
               "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
                }],
                "order": [[1, 'asc']], /// enumera indice de las columnas de Datatable
                ajax:{
                    url:RUTA+"/citas-programados/all/"+opcion+"?token_="+TOKEN,
                    method:"GET",
                    dataSrc:"response",
                },
                columns:[
                    {"data":"documento"},
                    {"data":"id_cita_medica"},
                    {"data":"id_horario"},
                    {"data":"documento"},
                    {"data":"paciente",render:function(pacientedata){
                        return pacientedata.toUpperCase();
                    }},
                    {"data":"especialidad",render:function(especialidad){return '<span class="badge bg-info">'+especialidad+'</span>';}},
                    {"data":"medico",render:function(medicodata){
                        return medicodata.toUpperCase();
                    }},
                    {"data":"namesede",render:function(namesede){
                        return namesede.toUpperCase();
                    }},
                    {"data":"fecha_cita_"},
                    {"data":"hora_cita",render:function(hora){return '<span class="badge bg-primary">'+hora+'</span>';}},
                    {"data":"observacion",render:function(observacion){
                        return observacion != null ?observacion :'<span class="badge bg-danger">No especifica motivo...</span>';
                    }},
                    {"data":"estado",render:function(estado){
                        if(estado==='pendiente')
                        {
                            return '<span class="badge bg-danger">Pendiente <i class="bx bxs-adjust-alt"></i></span>';
                        }
                        else
                        {
                            if(estado === 'pagado')
                            {
                                return '<span class="badge bg-success">Asistencia confirmado</span>';
                            }
                            else
                            {
                                if(estado === 'examinado')
                                {
                                    return '<span class="badge bg-info">Examinado <i class="bx bx-handicap"></i></span>';
                                }
                                else
                                {
                                    if(estado === 'anulado')
                                    {
                                        return '<span class="badge bg-warning">Anulado <i class="bx bx-x"></i></span>';
                                    }
                                    else{
                                        return '<span class="badge bg-primary">Finalizado <i class="bx bx-check"></i></span>';
                                    }
                                }
                            }
                        }
                        
                    }},
                    {"data":"monto_pago",render:function(importe){return '<span class="badge bg-info"> S/. '+importe;}},
                    {"data":"monto_medico",render:function(importemedico){return importemedico != null ? '<span class="badge bg-success"> S/. '+importemedico: "0.00"}},
                        {"data":"monto_clinica",render:function(importeclinica){return importeclinica != null ? '<span class="badge bg-primary"> S/. '+importeclinica : "0.00";}},
                    {"data":null,render:function(dta){
                        let button = `<div class="row">`;
                        if(dta.estado === 'pagado')
                        {
                          button+=  ` 
                          <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2">
                            <button class="btn rounded btn-danger btn-sm" id='anular'><i class='bx bx-x'></i> Anular</button>
                          </div>
                          <div class="col-xl-3 col-lg-3  col-md-4 col-sm-5 col-12 m-2">
                            <a href="`+RUTA+`cita-medica/recibo-detalle/`+dta.id_cita_medica+`" target="_blank" class="btn btn-outline-primary btn-sm">Imprimir<i class='bx bx-printer'></i></a>    
                            </div>
                          `
                        }
                        else{
                            if(dta.estado === 'pendiente')
                            {
                            button+=`
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2">
                            <button class="btn rounded btn-outline-info btn-sm" id='confirm_pago'><b>Confirmar <i class='bx bx-check'></i></b></button>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2" id='anular'>
                            <button class="btn rounded btn-danger btn-sm"><i class='bx bx-x'></i> Anular</button>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2" id='wasap'>
                            <a href="https://api.whatsapp.com/send?phone=`+dta.whatsapp+`&text='Hola,estimad@ paciente `+dta.paciente+`', solo para hacerle acordar que hoy tiene una cita desde las `+dta.hora_cita+` con el médico `+dta.medico+`, por favor asistir puntual, muchas gracias!!!" target="_blank" class="btn rounded btn-success btn-sm"><i class='bx bxl-whatsapp'></i> enviar mensaje</a>
                            </div>
                            <div class="col-xl-3 col-lg-3  col-md-4 col-sm-5 col-12 m-2">
                            <a href="`+RUTA+`cita-medica/recibo-detalle/`+dta.id_cita_medica+`" target="_blank" class="btn btn-outline-primary btn-sm">Imprimir<i class='bx bx-printer'></i></a>    
                            </div>
                            `;
                            }else{
                               if(dta.estado === 'finalizado'){
                                button+=`
                                 <div class="col-xl-6 col-lg-6 col-md-5 col-sm-5 col-12 m-2" id='wasap'>
                                 <a href="https://api.whatsapp.com/send?phone=`+dta.whatsapp+`&text='Hola,estimad@ paciente `+dta.paciente+`', solo para hacerle acordar que hoy tiene una cita desde las `+dta.hora_cita+` con el médico `+dta.medico+`, por favor asistir puntual, muchas gracias!!!" target="_blank" class="btn rounded btn-success btn-sm"><i class='bx bxl-whatsapp'></i> enviar mensaje</a>
                                </div>
                              
                               <div class="col-xl-3 col-lg-3  col-md-4 col-sm-5 col-12 m-2">
                                    <a href="`+RUTA+`cita-medica/recibo-detalle/`+dta.id_cita_medica+`"  target="_blank" class="btn btn-outline-primary btn-sm">Imprimir<i class='bx bx-printer'></i></a>
                                </div> 
                                 `;
                               } 
                            }
                        }
                        return button;
                    }}
                ],
                columnDefs:[
                    { "sClass": "hide_me", targets: [1,2] }
                ]
            });

             /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
             TablaCitasProgramados.on( 'order.dt search.dt', function () {
             TablaCitasProgramados.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
            } );
            }).draw();
        }

        /// anular cita médica
        function anularCitaMedica()
        {
            $('#Tabla_citas_programados').on('click','#anular',function(){
               
                /// obtener la fila seleccionada
                let fila = $(this).closest('tr');

                if(fila.hasClass('child'))
                {
                    fila = fila.prev();
                }

                /// obtenemos los datos
                let Horario_Id_cita = fila.find('td').eq(2).text();/// id del horario de la cita médica
                let Id_Cita_Medica_ = fila.find('td').eq(1).text(); /// el id de la cita médica
                let paciente = fila.find('td').eq(4).text(); /// dato del paciente
                 
                ConfirmAnularCitaMedica(Horario_Id_cita,Id_Cita_Medica_,paciente)

                
            });
        }

        /// confirmar pago del paciente que realizó una cita médica sin realizar el pago
         /// confirmar pago del paciente que realizó una cita médica sin realizar el pago
         function confirmPagoCitaMedica()
        {
            $('#Tabla_citas_programados').on('click','#confirm_pago',function(){
               
               /// obtener la fila seleccionada
               let fila = $(this).closest('tr');

               if(fila.hasClass('child'))
               {
                   fila = fila.prev();
               }

               /// obtenemos los datos
               let Id_Cita_Medica_ = fila.find('td').eq(1).text(); /// el id de la cita médica
               let paciente = fila.find('td').eq(4).text(); /// dato del paciente
                
               ConfirmPagoCitaMedica(Id_Cita_Medica_,paciente);
               
           });
        }

        function ConfirmAnularCitaMedica(hora_cita,cita_medica,paciente)
        {
         Swal.fire({
            title: 'Estas seguro de anular la cita médica del paciente '+paciente,
            text: "Al anular la cita médica del paciente , el horario con lo cuál realizó su cita, volverá a estar disponible y su cita se anulará automaticamente!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#008000',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, anular!'
            }).then((result) => {
            if (result.isConfirmed) {
                loading('#cardcitasprogramadas','#4169E1','chasingDots') 
              $.ajax({
                url:RUTA+"anular_cita_medica",
                method:"POST",
                data:{token_:TOKEN,horario:hora_cita,cita:cita_medica},
                success:function(data_)
                {
                    data_ = JSON.parse(data_);

                    if(data_.response == 1)
                    {
                       setTimeout(() => {
                        $('#cardcitasprogramadas').loadingModal('hide');
                        $('#cardcitasprogramadas').loadingModal('destroy');
                        Swal.fire({
                            title:"Mensaje del sistema",
                            text:"La cita médica del paciente "+paciente+" se ha anulado correctamente",
                            icon:"success"
                        }).then(function(){
                            showCitasProgramados("diario/diario");
                        });
                       }, 1000);
                    }
                    else
                    {
                        $('#cardcitasprogramadas').loadingModal('hide');
                        $('#cardcitasprogramadas').loadingModal('destroy');
                        Swal.fire({
                            title:"¡ADVERTENCIA!",
                            text:"Error al intentar anular la cita médica del paciente "+paciente,
                            icon:"error"
                        })  
                    }
                }
              })
            }
            })
        }

        function ConfirmPagoCitaMedica(cita_medica,paciente)
        {
         Swal.fire({
            title: 'Estas seguro de confirmar la asistencia del paciente: '+paciente,
            text: "Al confirmar la asistencia del paciente, pasará directamente a triaje para ser examinado por una enfermera, y luego pasará directo con el médico que solicitó!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#00BFFF',
            cancelButtonColor: '#FF4500',
            confirmButtonText: 'Si, confirmar!'
            }).then((result) => {
            if (result.isConfirmed) {
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
                            showCitasProgramados("diario/diario");
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
              })
            }
            })
        }
    </script>
@endsection