@extends($this->Layouts("dashboard"))

@section("title_dashboard","Mis servicios")

@section('css')
    <style>
        #listaservicios>thead>tr>th{
            background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 15px;
            color:azure;
             
        }
        #tabla_servicios_eliminados>thead>tr>th{
            background-color: rgb(76, 105, 158);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 15px;
            color:azure; 
        }
        td.hide_me
        {
        display: none;
        }
         
    </style>
@endsection
@section('contenido')
 <div class="row" id="app_servicios">
    <div class="col">
        <div class="card">
            <div class="card-header" style="background:linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 50%,#539fe1 79%,#87bcea 100%);">
                <h4 class="text-white letra">Servicios por especialidad</h4>
            </div>

            <div class="card-body">
                <div class="row mt-3">
                    <div class="col-12">
                        <label for="especialidad"><b class="letra">Seleccionar especialidad</b></label>
                        <select name="especialidad" id="especialidad" class="form-select">
                            <option disabled selected>--- Seleccionar ---</option>
                            @foreach ($especialidades as $esp)
                                <option value="{{$esp->id_especialidad}}">{{strtoupper($esp->nombre_esp)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 table-responsive">
                        <div class="card">
                            <div class="card-text">
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn_blue mb-2 col-xl-3 col-lg-4 col-md-5 col-12" id="addservicemedico">Agregar servicio <i class='bx bx-plus'></i></button>
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped table-hover nowrap responsive" id="listaservicios" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Servicio</th>
                                            <th>Imp.paciente </th>
                                            <th>% Médico</th>
                                            <th>% Clínica</th>
                                            <th>Acciones</th>
                                            <th class="d-none">ID</th>
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
 {{--modal para editar los servicios de un médico con respecto a una especialidad---}}
 <div class="modal fade" id="editar_servicio" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(to bottom, #fefcea 0%,#f1da36 100%);">
                <h4>Editar servicio</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="name_servicio" class="form-label"><b>Nombre servicio <span class="text-danger">*</span></b></label>
                    <input type="text" id="name_servicio" class="form-control">
                </div>

                <div class="form-group">
                    <label for="precio_servicio" class="form-label"><b>Precio servicio <span class="text-danger">*</span></b></label>
                    <input type="text" id="precio_servicio" class="form-control">
                </div>

                <div class="form-group">
                    <label for="precio_medico_servicio" class="form-label"><b>Precio servicio (Médico) <span class="text-danger">*</span></b></label>
                    <input type="number" id="precio_medico_servicio" class="form-control">
                </div>

                <div class="form-group">
                    <label for="precio_clinica_servicio" class="form-label"><b>Precio servicio (Clínica) <span class="text-danger">*</span></b></label>
                    <input type="number" id="precio_clinica_servicio" class="form-control">
                </div>
            </div>

            <div class="modal-footer border-2">
                <button class="btn btn-success rounded" id="update_service">Guardar <i class='bx bxs-save' ></i></button>
                <button class="btn btn-danger rounded" id="cerrar_modal_service">Cancelar <i class='bx bx-x' ></i></button>
            </div>
        </div>
    </div>
 </div>

 {{---MODAL PARA AGREGAR NUEVOS SERVICIOS PARA EL MEDICO---}}
 <div class="modal fade" id="add_servicio_modal" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
                <h4 class="text-white">Registrar nuevo servicio</h4>
            </div>

            <div class="modal-body" id="body_servicios">
                <div id="formulario_add_servicio">
                    <div class="form-group">
                        <label for="name_servicio_add" class="form-label"><b>Nombre servicio <span class="text-danger">*</span></b></label>
                        <input type="text" id="name_servicio_add" class="form-control">
                    </div>
    
                    <div class="form-group">
                        <label for="precio_servicio_add" class="form-label"><b>Precio servicio <span class="text-danger">*</span></b></label>
                        <input type="text" id="precio_servicio_add" class="form-control">
                    </div>
    
                    <div class="form-group">
                        <label for="precio_medico_servicio_add" class="form-label"><b>% para el (Médico) <span class="text-danger">*</span></b></label>
                        <input type="number" id="precio_medico_servicio_add" class="form-control">
                    </div>
    
                    <div class="form-group">
                        <label for="precio_clinica_servicio_add" class="form-label"><b>% para la (Clínica) <span class="text-danger">*</span></b></label>
                        <input type="number" id="precio_clinica_servicio_add" class="form-control">
                    </div>
                </div>

                <a href="" id="import_excel">Importar datos por excel <i class='bx bxs-file-export'></i></a>
                <div id="formulario_add_servicio_excel" style="display: none">
                    <a href="" id="formulario_service">Ir a formulario<i class='bx bxs-file-export'></i></a>
                    <form action="" method="post" id="form_import_excel_service" enctype="multipart/form-data">
                        <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                        <div class="form-group">
                            <label for="file_excel" class="form-label"><b>Seleccionar un archivo excel <span class="text-danger">*</span></b></label>
                            <input type="file" name="file_excel" id="file_excel" class="form-control">
                        </div>
                    </form>
                    <div class="alert alert-danger mt-2" id="alert_import_excel_service" style="display: none">
                        Seleccione un archivo excel!
                    </div>
                </div>
            </div>

            <div class="modal-footer border-2">
                <button class="btn btn-success rounded" id="save_service">Guardar <i class='bx bxs-save' ></i></button>
                <button class="btn btn-danger rounded" id="cerrar_modal_add_service">Cancelar <i class='bx bx-x' ></i></button>
            </div>
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
        var TablaServicios;
        var SERVICEID;
        var MEDICOESP;
        var accion = 'formulario';
        $(document).ready(function(){
          let Especialidad = $('#especialidad');
          let Medico = $('#medico');
         mostrarServiciosMedico(null);  

          Especialidad.change(function(){
            MEDICOESP = $(this).val();
             mostrarServiciosMedico(MEDICOESP);
             ActivarServicio('#listaservicios tbody')
          });

          $('#name_servicio_add').keypress(function(evento){
             if(evento.which == 13){
                if($(this).val().trim().length > 0){
                    $('#precio_servicio_add').focus();
                }else{
                    $(this).focus();
                }
             }
          });
          $('#precio_servicio_add').keypress(function(evento){
             if(evento.which == 13){
                if($(this).val().trim().length > 0){
                    $('#precio_medico_servicio_add').focus();
                }else{
                    $(this).focus();
                }
             }
          });
          $('#precio_medico_servicio_add').keypress(function(evento){
             if(evento.which == 13){
                if($(this).val().trim().length > 0){
                    $('#precio_clinica_servicio_add').focus();
                }else{
                    $(this).focus();
                }
             }
          });

          $('#cerrar_modal_service').click(function(){
            $('#editar_servicio').modal("hide");
          });

          $('#update_service').click(function(){

            let sumaPorcentaje = parseInt($('#precio_medico_servicio').val()) + parseInt($('#precio_clinica_servicio').val());
            if(sumaPorcentaje > 100){
                                Swal.fire({
                                    title:"Mensaje del sistema!!",
                                    text:"El porcentaje entre lo que le corresponde al médico y clínica no debe ser > a 100",
                                    icon:"error",
                                    target:document.getElementById('editar_servicio')
                                });
                            }else{
            modificarService(SERVICEID,
                             $('#name_servicio'),
                             $('#precio_servicio'),
                             $('#precio_medico_servicio'),
                             $('#precio_clinica_servicio'))
                        }
          });

          $('#addservicemedico').click(function(){
             
            if($('#especialidad').val() != null){
                $('#add_servicio_modal').modal("show");
                $('#name_servicio_add').focus();
            }else{
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:"Debes de seleccionar una especialidad, para asignarle los servicios!!!",
                    icon:"info"
                });
            }
          });
          $('#cerrar_modal_add_service').click(function(){
             $('#formulario_add_servicio_excel').hide();
             $('#formulario_add_servicio').show(400);
             $('#import_excel').show(400);
             $('#formulario_service').hide();
             $('#file_excel').val("");
             $('#alert_import_excel_service').hide();
             $()
            $('#add_servicio_modal').modal("hide");
          });

          $('#save_service').click(function(){
             if(accion === 'formulario')
              {
                if($('#name_servicio_add').val().trim().length == 0){
                $('#name_servicio_add').focus();
             }else{
                if($('#precio_servicio_add').val().trim().length == 0){
                    $('#precio_servicio_add').focus();
                }else{
                    if($('#precio_medico_servicio_add').val().trim().length == 0){
                        $('#precio_medico_servicio_add').focus();
                    }else{
                        if($('#precio_clinica_servicio_add').val().trim().length == 0){
                            $('#precio_clinica_servicio_add').focus();
                        }else{

                            let sumaPorcentaje = parseInt($('#precio_medico_servicio_add').val()) + parseInt($('#precio_clinica_servicio_add').val());
                            
                            if(sumaPorcentaje > 100){
                                Swal.fire({
                                    title:"Mensaje del sistema!!",
                                    text:"El porcentaje entre lo que le corresponde al médico y clínica no debe ser > a 100",
                                    icon:"error",
                                    target:document.getElementById('add_servicio_modal')
                                });
                            }else{
                                saveService(MEDICOESP,
                             $('#name_servicio_add'),
                             $('#precio_servicio_add'),
                             $('#precio_medico_servicio_add'),
                             $('#precio_clinica_servicio_add'))
                            }
                        }
                    }
                }
             }
            }else{
                importService()
            }
          });

          $('#import_excel').click(function(evento){
             evento.preventDefault();
             accion = 'excel';
             $('#formulario_add_servicio_excel').show(400);
             $('#formulario_add_servicio').hide();
             $(this).hide();
             $('#formulario_service').show(400);
          });
          $('#formulario_service').click(function(evento){
             evento.preventDefault();
             accion = 'formulario';
             $('#formulario_add_servicio_excel').hide();
             $('#formulario_add_servicio').show(400);
             $('#import_excel').show(400);
             $(this).hide();
          })
        });

     function mostrarServiciosMedico(id){
            TablaServicios = $('#listaservicios').DataTable({
                bDestroy:true,
         "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
                }],
                language:SpanishDataTable(),
                ajax:{
                    url:RUTA+"servicios-medico-por-especialidad/"+id,
                    method:"GET",
                    dataSrc:"servicios"
                },
                columns:[
                    {"data":"name_servicio"},
                    {"data":null,render:function(dato){
                        let Estado = dato.deleted_at != null ? '<span class="badge bg-danger">Deshabilitado X</span>' : '<span class="badge bg-success">Habilitado<i class="fas fa-check"></i></span>';
                        return dato.name_servicio.toUpperCase()+"  "+Estado;
                    }},
                    {"data":"precio_servicio",render:function(imp){
                        return "<b class='text-primary'>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} </b>"+"<span id='imp'>"+imp+"</span>"
                    },className:'text-center'},
                    {"data":"precio_medico",render:function(porcen_medico){
                        return "<span id='por_medico'>"+porcen_medico+"</span>"+"<b class='text-center'> %</b>";
                    },className:'text-center'},
                    {"data":"precio_clinica",render:function(porcen_clin){
                        return "<span id='por_clin'>"+porcen_clin+"</span>"+"<b class='text-center'> %</b>";
                    },className:'text-center'},
                    {"data":null,render:function(dato){
                        if(dato.deleted_at == null){
                        return `
                      <div class='row'>
                        <div class='col-auto'>
                         <button class='btn btn-danger rounded btn-sm' id='eliminar'><i class="fas fa-trash-alt"></i></button>
                        </div>  
                        <div class='col-auto'>
                         <button class='btn btn-warning rounded btn-sm' id='editarservicio'><i class="fas fa-edit"></i></button>
                        </div>
                      </div>
                    `;
                    }

                    return `
                      <div class='row'>
                        <div class='col-auto'>
                         <button class='btn btn-success rounded btn-sm' id="activar"><i class="fas fa-check"></i></button>
                        </div>  
                        <div class='col-auto'>
                         <button class='btn btn-danger rounded btn-sm' id='borrar'><i class="fas fa-close"></i></button>
                        </div>  
 
                      </div>
                    `;
                    }},
                    {"data":"id_servicio"}
                ],
       columnDefs:[
                { "sClass": "hide_me", target: 6 }
                ]
            });

         TablaServicios.on( 'order.dt search.dt', function () {
            TablaServicios.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
            } );
         }).draw();
         EditarServicio('#listaservicios tbody');
         ConfirmarEliminadoServicio('#listaservicios tbody');
        }

        /// Editar el servicio
         function EditarServicio(Tbody){
            $(Tbody).on('click','#editarservicio',function(){
                let fila = $(this).parents("tr");
                $('#editar_servicio').modal("show")

                if(fila.hasClass("child")){
                    fila = fila.prev();
                }
                SERVICEID = fila.find('td').eq(6).text();
                let NameService = fila.find('td').eq(1).text();
                let PrecioService = fila.find('#imp').text();
                let PrecioMedicoService = fila.find('#por_medico').text();
                let PrecioClinicaService = fila.find('#por_clin').text();
                $('#name_servicio').val(NameService);
                $('#precio_servicio').val(PrecioService);
                $('#precio_medico_servicio').val(PrecioMedicoService);
                $('#precio_clinica_servicio').val(PrecioClinicaService);
            })
         }

         /*CONFIRMAR ELIMINADO DEL SERVICIO*/
         function ConfirmarEliminadoServicio(Tbody){
            $(Tbody).on('click','#eliminar',function(){
                let fila = $(this).parents("tr");
                
                if(fila.hasClass("child")){
                    fila = fila.prev();
                }
                SERVICEID = fila.find('td').eq(6).text();
                let NameService = fila.find('td').eq(1).text();
                let PrecioService = fila.find('#imp').text();
                let PrecioMedicoService = fila.find('#por_medico').text();
                let PrecioClinicaService = fila.find('#por_clin').text();
                Swal.fire({
                title: "Estas seguro de eliminar al servicio "+NameService+"?",
                text: "Al Aceptar se quitará de la lista y ya no estará disponible!!",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar!",
                target:document.getElementById('app_servicios')
                }).then((result) => {
                if (result.isConfirmed) {
                  EliminarServicio(SERVICEID);
                }
                });
            })
         }

         /*ACTIVAR SERVICIO*/
         function ActivarServicio(Tbody){
            $(Tbody).on('click','#activar',function(){
                let fila = $(this).parents("tr");
                
                if(fila.hasClass("child")){
                    fila = fila.prev();
                }
                SERVICEID = fila.find('td').eq(6).text();
          $.ajax(
                {
                url:RUTA+"activar_servicio_medico/"+SERVICEID,
                method:"POST",
                data:{token_:TOKEN},
                success:function(response){
                response = JSON.parse(response);
                  if(response.response === 'ok'){
                    Swal.fire({
                        title:"Mensaje del sistema!!",
                        text:"Servicio habilitado nuevamente!",
                        icon:"success"
                    }).then(function(){
                        mostrarServiciosMedico(MEDICOESP);
                    });
            }else{
                Swal.fire(
                {
                title:"Mensaje del sistema!",
                text:"Error al activar servicio seleccionado",
                icon:"error",
                
                }
                );
                }
                }
            });
            })
         }

         /*ELIMINAR SERVICIO*/
        function EliminarServicio(id){
        $.ajax({
        url:RUTA+"medico/servicio/eliminar/"+id,
        method:"POST",
        data:{token_:TOKEN},
        success:function(response)
        {
        response = JSON.parse(response);
        
        if(response.response === 'ok')
        {
        Swal.fire(
        {
        title:"Mensaje del sistema!",
        text:"Servicio eliminado correctamente",
        icon:"success",
        
        }
        ).then(function(){
          mostrarServiciosMedico(MEDICOESP);
        });
        }
        else
        {
        Swal.fire(
        {
        title:"Mensaje del sistema!",
        text:"Error al eliminar el servicio seleccionado",
        icon:"error",
        
        }
        );
        }
        }
        });
         }

         /// modificar el servicio
         function modificarService(id,nameservice,precioservice,preciomedservice,precclinicaservice){
            $.ajax({
                url:RUTA+"service/modificar/"+id,
                method:"POST",
                data:{
                    token_:TOKEN,
                    name_servicio:nameservice.val(),
                    precio_servicio:precioservice.val(),
                    precio_medico:preciomedservice.val(),
                    precio_clinica:precclinicaservice.val()
                },
                dataType:"json",
                success:function(response){
                    
                    if(response.response === 'ok')
                     {
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Servicio modificado correctamente!",
                            icon:"success",
                            target:document.getElementById('editar_servicio')
                        }).then(function(){
                            mostrarServiciosMedico(MEDICOESP);
                        });
                     }
                }
            })
         }

         function saveService(idmedicoesp,nameservice,precioservice,preciomedservice,precclinicaservice){
            $.ajax({
                url:RUTA+"service/savedata",
                method:"POST",
                data:{
                    token_:TOKEN,
                    name_servicio:nameservice.val(),
                    precio_servicio:precioservice.val(),
                    medico_esp:idmedicoesp,
                    precio_medico:preciomedservice.val(),
                    precio_clinica:precclinicaservice.val()
                },
                dataType:"json",
                success:function(response){
                    
                    if(response.response === 'ok')
                     {
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Servicio registrado correctamente!",
                            icon:"success",
                            target:document.getElementById('add_servicio_modal')
                        }).then(function(){
                            mostrarServiciosMedico(MEDICOESP);
                            nameservice.val("");
                            precioservice.val("");
                            preciomedservice.val("");
                            precclinicaservice.val("");
                        });
                     }else{
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Error al registrar nuevo servicio!",
                            icon:"success",
                            target:document.getElementById('add_servicio_modal')
                        })
                     }
                }
            })
         }
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
                            option+=`<option value=`+doctor.id_medico_esp+`>`+(doctor.apellidos+" "+doctor.nombres).toUpperCase()+`</option>`;
                        });
                    }

                    $('#medico').html(option);
                }
            })
        }

        /// importar los servicios por excel
        function importService()
        {
            let FormImportExcelService = new FormData(document.getElementById('form_import_excel_service'));
             FormImportExcelService.append("medico_esp",MEDICOESP);
            $.ajax({
                url:RUTA+"service/importdata/excel",
                method:"POST",
                data:FormImportExcelService,
                cache:false,
                contentType:false,
                processData:false,
                dataType:"json",
                success:function(response){
                    if(response.response === 'vacio' || response.response === 'no-accept'){
                        $('#alert_import_excel_service').show(400);
                    }else{
                        if(response.response === 'ok' || response.response === 'existe'){
                            $('#alert_import_excel_service').hide();
                            loading('#body_servicios','#4169E1','chasingDots') 

                            setTimeout(() => {
                            
                            $('#body_servicios').loadingModal('hide');
                            $('#body_servicios').loadingModal('destroy');

                            Swal.fire({
                                title:"Mensaje del sistema!",
                                text:"Servicios importados correctamente!",
                                icon:"success",
                                target:document.getElementById('add_servicio_modal')
                            }).then(function(){
                                mostrarServiciosMedico(MEDICOESP);
                            });
                            }, 1000);
                        }
                    }
                }
            });
        }
    </script>
@endsection