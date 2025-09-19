@extends($this->Layouts("dashboard"))

@section("title_dashboard","Mis servicios")

@section('css')
    <style>
        #listapagosmedico>thead>tr>th{
            background-color: rgb(76, 105, 158);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 15px;
            color:azure;
             
        }

        #listapagosmedico>tfoot>tr>th{
            background-color: rgb(149, 235, 167);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 15px;
            color:azure;
             
        }
        
        td.hide_me
        {
        display: none;
        }
     input[type=radio]
        {
        width:23px;
        height:23px;
        }
        label{
        color: #4169E1;
        }
        label>b{
        color:#FF4500;
        cursor: pointer;
        }  
    </style>
@endsection
@section('contenido')
 <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color: aquamarine">
                <h4>Citas por médico</h4>
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-xl-4 col-lg-4 col-md-5 col-12">
                        <div class="form-group">
                            <label for="especialidad" class="form-label"><b>Especialidad</b></label>
                            <select name="especialidad" id="especialidad" class="form-select">
                                @foreach ($especialidades as $especialidad)
                                  <option value="{{$especialidad->id_especialidad}}">{{strtoupper($especialidad->nombre_esp)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-5 col-12">
                        <div class="form-group">
                            <label for="medico" class="form-label"><b>Médico</b></label>
                            <select name="medico" id="medico" class="form-select">
                               
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                      <div class="form-group">
                          <label for="diario" class="form-label"><input type="radio" id="diario" name="opcion" checked><b> Hoy ({{$this->getDayDate($this->FechaActual("Y-m-d"))}})</b></label>
                         </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                     <div class="form-group">
                      <label for="mensual" class="form-label"><input type="radio" id="mensual" name="opcion"><b>Mes de {{$this->getMonthName($this->FechaActual("m"))}}</b></label>
                     </div>
                    </div>
          
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                      <div class="form-group">
                       <label for="anio" class="form-label"><input type="radio" id="anio" name="opcion"><b>Año {{$this->FechaActual("Y")}}</b></label>
                      </div>
                     </div>
          
                     <div class="col-xl-4 col-lg-4 col-md-5 col-12">
                      <div class="form-group">
                       <label for="rango_fechas" class="form-label"><input type="radio" id="rango_fechas" name="opcion"><b>Rango de fechas</b></label>
                      </div>
                     </div>
                     <div class="col-xl-7 col-lg-6 col-md-6 col-12" style="display: none" id="fecha_select">
                      <div class="form-group">
                       <label for="fecha_inicio"><b>Fecha inicio <span class="text-danger">*</span></b></label>
                       <input type="date" id="fecha_inicio" class="form-control" value="{{$this->FechaActual("Y-m-d")}}">
                      </div>
                      <div class="form-group">
                        <label for="fecha_fin"><b>Fecha fin <span class="text-danger">*</span></b></label>
                        <input type="date" id="fecha_fin" class="form-control" value="{{$this->addRestFecha("Y-m-d","+30 day")}}">
                       </div>
                     </div>
                 </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped nowrap responsive" id="listapagosmedico" style="width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Monto {{count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda:'S/.'}}</th>
                                <th>Paciente</th>
                                <th>Especialidad</th>
                                <th>Servicio</th>
                                <th>Fecha y Hora</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
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
        var TablaListaPagosMedico;
        var MEDICOID;
        var tipoReporte = 'diario';
        $(document).ready(function(){
          let Especialidad = $('#especialidad');

          let Medico = $('#medico');

          mostrarMedicosPorEspecialidad(Especialidad.val());
          
          Especialidad.change(function(){
            mostrarMedicosPorEspecialidad($(this).val());
          });

          Medico.change(function(){
            MEDICOID = $(this).val();
             
            if(tipoReporte === 'diario'){
                mostrarCitasDelMedico($(this).val(),'2024-10-13','2024-10-13');
            }else{
                if(tipoReporte === 'mensual'){
                    mostrarCitasDelMedico(MEDICOID,'2024-10-13','2024-10-13','mensual');
                }else{
                    mostrarCitasDelMedico(MEDICOID,'2024-10-13','2024-10-13','anio');  
                }
            }
            
          });

          $('#diario').click(function(){
            tipoReporte = 'diario';
            if(Medico.val() != null){
                mostrarCitasDelMedico(MEDICOID,'2024-10-13','2024-10-13');
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Seleccione a un médico!",
                    icon:"warning"
                })
            }
            $('#fecha_select').hide();
          })
          $('#mensual').click(function(){
            tipoReporte = 'mensual';
             if(Medico.val()!=null){
                mostrarCitasDelMedico(MEDICOID,'2024-10-13','2024-10-13','mensual');
             }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Seleccione a un médico!",
                    icon:"warning"
                })
             }
             $('#fecha_select').hide();
          });

          $('#anio').click(function(){
            tipoReporte = 'anio';
            if(Medico.val()!=null){
                mostrarCitasDelMedico(MEDICOID,'2024-10-13','2024-10-13','anio');
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Seleccione a un médico!",
                    icon:"warning"
                })
            }
            $('#fecha_select').hide();
          });
          $('#rango_fechas').click(function(){
            $('#fecha_select').show(500);
          })

          $('#fecha_inicio').change(function(){
            if(Medico.val()!= null){
                mostrarCitasDelMedico(MEDICOID,$(this).val(),$('#fecha_fin').val(),'rango');
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Seleccione a un médico!",
                    icon:"warning"
                })  
            }
          });

          $('#fecha_fin').change(function(){
            if(Medico.val()!= null){
                mostrarCitasDelMedico(MEDICOID,$('#fecha_inicio').val(),$(this).val(),'rango');
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Seleccione a un médico!",
                    icon:"warning"
                }); 
            }
          });
        });

         /// mostrar los médicos por especialidad
         function mostrarMedicosPorEspecialidad(id){
            let option = '<option selected disabled>-- Seleccione ---</option>';
            $.ajax({
                url:RUTA+"medicos-por-especialidad/"+id,
                method:"GET",
                dataType:"json",
                success:function(medicoresponse){
                    if(medicoresponse.medicos.length > 0){
                        medicoresponse.medicos.forEach(doctor => {
                            option+=`<option value=`+doctor.id_medico+`>`+(doctor.apellidos+" "+doctor.nombres).toUpperCase()+`</option>`;
                        });
                    }

                    $('#medico').html(option);
                }
            })
        }

        /// mostrar todas las citas por un determinado médico
        function mostrarCitasDelMedico(id,fecha_inicio,fecha_fin,opcion='diario'){
            TablaListaPagosMedico = $('#listapagosmedico').DataTable({
                bDestroy:true,
                "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
                }],
                "drawCallback":function(){
                    var api = this.api();
                    $(api.column(1).footer()).html(
                        '<span class="text-primary">Pagar al médico :</span> <span class="badge bg-primary"><b>'+api.column(1,{page:"current"}).data().sum()+"</b></span>"
                    )
                },
                ajax:{
                    url:RUTA+"medico/citas-realizados/pago/"+opcion+"/"+id+"/"+fecha_inicio+"/"+fecha_fin,
                    method:"GET",
                    dataSrc:"citas"
                },
                columns:[
                    {"data":"pacientedata"},
                    {"data":"monto"},
                    {"data":"pacientedata",render:function(pacientdata){
                        return pacientdata.toUpperCase();
                    }},
                    {"data":"nombre_esp",render:function(nombre_esp){
                        return nombre_esp.toUpperCase();
                    }},
                    {"data":"name_servicio",render:function(name_servicio){
                        return name_servicio.toUpperCase();
                    }},
                    {"data":null,render:function(cita){
                        return cita.fecha_cita+" ["+cita.hora_cita+"]";
                    }}
                ]
            });

            TablaListaPagosMedico.on( 'order.dt search.dt', function () {
            TablaListaPagosMedico.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
            } );
         }).draw();
         
        }
    </script>
@endsection