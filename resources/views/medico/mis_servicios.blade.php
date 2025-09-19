@extends($this->Layouts("dashboard"))

@section("title_dashboard","Mis servicios")

@section('css')
    <style>
        #tabla_servicios>thead>tr>th{
            background-color: rgb(76, 105, 158);
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
        label{
          cursor: pointer;
        }
    </style>
@endsection
@section('contenido')
 <div class="col">
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="tab_service">
                <li class="nav-item">
                  <a class="nav-link active text-primary" aria-current="true" href="#servicios" id="servicios_">Servicios <i class='bx bxs-slideshow'></i></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-danger" href="#servicios_delete" id="servicios_delete_">Servicios eliminados <i class='bx bx-hide'></i></a>
                </li>
                 
              </ul>
        </div>
        <div class="tab-content card-body">
            <br>
            
                <div class="tab-pane fade show active" id="servicios" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="card-text">
                        <b>Especialidad (*)</b>
                        <select name="especialidad" id="especialidad" class="form-select mt-1">
                            <option disabled selected> --- Seleccione --- </option>
                            @if (isset($Data) and count($Data) > 0)
                                @foreach ($Data as $esp)
                                    <option value="{{$esp->id_especialidad}}">{{strtoupper($esp->nombre_esp)}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
        
                    <div class="card-text mt-3">
                        <div class="table-responsive">
                           <caption> 
                            <div class="row">
                                <div class="col-auto">
                                    <h5>Mis Servicios</h5>
                                </div>
                            </div>
        
                            <div class="row">
                                <div class="col-xl-auto col-lg-auto col-md-auto col-12 mb-3">
                                    <input type="text" class="form-control" placeholder="Buscar...." id="buscar">
                                </div>
                                <div class="col-xl-auto col-lg-auto col-md-auto col-12">
                                    <label for="" class="col-form-label">Mostrar</label>
                                </div>
                                <div class="col-xl-auto col-lg-auto col-md-auto col-12">
                                    <span>
                                        <select name="registros" id="registros" class="form-select">
                                            <option value="3">3</option>
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="30">30</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                         </select>    
                                    </span> 
                                </div>
                                <div class="col-xl-auto col-lg-auto col-md-auto col-12">
                                    <label for="" class="col-form-label">registros</label>
                                </div>
                                
                            </div>
                           </caption>
                            <table class="table table-bordered mt-xl-0 mt-lg-0 mt-md-0 mt-2" id="tabla_servicios">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Servicio</th>
                                        <th>Precio {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                        <th class="d-none">ID</th>

                                    </tr>
                                </thead>
                                <tbody id="lista_serv"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="servicios_delete" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="card-text">
                        <b>Especialidad (*)</b>
                        <select name="especialidad_" id="especialidad_" class="form-select mt-1">
                            
                            @if (isset($Data) and count($Data) > 0)
                                @foreach ($Data as $esp)
                                    <option value="{{$esp->id_especialidad}}">{{strtoupper($esp->nombre_esp)}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped nowrap responsive" id="tabla_servicios_eliminados" style="width: 100%">
                     <thead>
                        <tr>
                            <th>#</th>
                            <th>SERVICIO</th>
                            <th>PRECIO</th>
                            <th>HABILITAR</th>
                        </tr>
                     </thead>
                    </table>
                   </div>

                   <div class="alert alert-success" style="display: none" id="mensaje_serv_active">
                     <b>El servicio a sido activado nuevamente!</b>
                   </div>
                </div>
         
        </div>
    </div>
 </div>
 {{--- MODAL PARA AGREGAR NUEVOS SERVICIOS DEL MÉDICO CON RESPECTO A UNA ESPECIALIDAD---}}
 <div class="modal fade" id="modal-add-servicio" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="float-start d-xl-block d-lg-block d-md-block d-none editar_texto">Agregar servicio</h4>
     
                <button class="float-end btn btn-outline-danger btn-rouded col-xl-3 col-lg-3 col-md-3 col-12" id="exit_servicio_add">Salir <i class='bx bx-x' ></i></button>
               
            </div>

            <div class="modal-body">
                <form action="{{$this->route("medico/import/service")}}" method="post" enctype="multipart/form-data" id="form_excel">
                   
                    <div id="form_add_servicio" class="mb-2">
                    <label for="name_servicio"><b>Nombre (*)</b></label>
                    <input type="text" name="name_servicio" id="name_servicio" class="form-control"
                    placeholder="Escriba aquí ....">

                    <label for="precio_servicio"><b>Precio</b></label>
                    <input type="text" name="precio_servicio" id="precio_servicio" class="form-control"
                    placeholder="Escriba aquí ....">
                   </div>
  
                    <div id="form_add_servicio_excel" class="mb-2" style="display: none">
                        <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                        <label for="excel_file">
                            Seleccionar un archivo excel 
                        </label>
                        <input type="file" name="excel_file" id="excel_file" class="form-control">
                     </div>
                 
                    <tr >
                        <td>
                           <div class="input-group mt-1">
                            <label for="check_data_excel" class="text-primary" id="label_text__">Por excel  </label>
                            <input type="checkbox" style="width: 20px;height:20px;"
                            id="check_data_excel">
                           </div>
                        </td>
                    </tr>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-rouded" id="save" >Guardar <i class='bx bx-save' ></i></button>
                <button class="btn btn-success btn-rouded" id="importar" style="display: none">Importar <i class='bx bx-import'></i></button>
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
  var ID_SERV;
  var LIMIT = 10;
  var Control = 'save';
  var SERVICIO_EDITAR;
  var tabla_serviciosEliminados;
$(document).ready(function(){
 /*** VARIABLES */
 let Especialidad = $('#especialidad');
 let NameServicioText = $('#name_servicio');
 let PrecioServicioText = $('#precio_servicio');
 mostrarServiciosMedico("null","",LIMIT);

 $('#tab_service a').on('click',function(evento){
    evento.preventDefault();
   if($(this)[0].id === 'servicios_delete_')
   {
     showServiciosEliminados();
     ActiveServiceMedico('#tabla_servicios_eliminados tbody',tabla_serviciosEliminados)
   }
   else{
    mostrarServiciosMedico(ID_SERV,"",LIMIT);
   }

   $(this).tab("show")
 });
 Especialidad.change(function(){
    ID_SERV = $(this).val();
   mostrarServiciosMedico($(this).val(),"",LIMIT)
 });

 $('#buscar').keyup(function(){
  mostrarServiciosMedico(ID_SERV,$(this).val(),LIMIT);
 });

 $('#registros').change(function(){
    LIMIT = $(this).val();
    mostrarServiciosMedico(ID_SERV,"",$(this).val());
 });

 $('#open_modal_new_servicio').click(function(){
  if(Especialidad.val() != null)
  {
    $('.editar_texto').text("Agregar servicio");
    NameServicioText.val("");PrecioServicioText.val("");
    Control = 'save';
    $('#modal-add-servicio').modal("show");
  }
  else{
   Swal.fire({
    title:"Mensaje del sistema!",
    text:"Antes de continuar el proceso de agregar nuevos servicios del médico, seleccione la especialidad al cuál desea agregar.",
    icon:"warning"
   })
  }
 });

 $('#exit_servicio_add').click(function(){
  $('#modal-add-servicio').modal("hide");
  $('#form_add_servicio').show();
  $('#form_add_servicio_excel').hide();
  $('#label_text__').text("Por excel");
  $('#importar').hide(80);
  $('#save').show(80);
  $('#check_data_excel').prop("checked",false)
  $('#excel_file').val("")
 });

 $('#check_data_excel').click(function(){
 if($(this).is(":checked"))
 {
    $('#form_add_servicio').hide();
    $('#form_add_servicio_excel').show();
    $('#label_text__').text("Ver formulario");
    $('#importar').show(80);
    $('#save').hide(80);
 }
 else
 {
  $('#form_add_servicio').show();
  $('#form_add_servicio_excel').hide();
  $('#label_text__').text("Por excel");
  $('#importar').hide(80);
  $('#save').show(80);
 }
 });

 $('#save').click(function(evento){
  evento.preventDefault();
    if(NameServicioText.val().trim().length == 0)
    {
        NameServicioText.focus();
    }
    else{
        if(PrecioServicioText.val().trim().length == 0)
        {
            PrecioServicioText.focus();
        }
        else{
            if(Control === 'save')
            {
                addServicioMedico(NameServicioText.val(),PrecioServicioText.val(),Especialidad.val());
            }
            else{
                updateServicio(SERVICIO_EDITAR,NameServicioText.val(),PrecioServicioText.val())
            }
        }
  }
  
 });

 /**importar servicios por excel**/ 
//  $('#importar').click(function(){
//  importAddServicio(Especialidad);
//  });

 /// editar los servicio
 EditarService('#lista_serv');

 /// eliminado lógico
 ConfirmSoftDeleteService('#lista_serv')
}); 

/*MOSTRAR LOS SERVICIOS DEL MÉDICO*/
function mostrarServiciosMedico(id,buscador,limit)
{
  let tr = '';let contador = 0;
  $.ajax({
    url:RUTA+"medico/mis_servicios_data_/"+id+"?token_="+TOKEN,
    method:"GET",
    data:{buscador:buscador,limit:limit},
    success:function(response)
    {
        response = JSON.parse(response);
        
        if(response.response.length> 0)
        {
            response.response.forEach(element => {
            contador++;
            tr+= `<tr>
                <td>`+contador+`</td>
                <td>`+element.name_servicio.toUpperCase()+`</td> <td>`+(element.precio_servicio != null ? element.precio_servicio:'0.00')+`</td>
                <td class='d-none'>`+element.id_servicio+`</td>
                 
                </tr>`;
        });

        }else
        {
            tr+='<tr><td colspan="4"><span class="text-danger">No hay servicios para mostrar.....</span></td></tr>';
        }
        $('#lista_serv').html(tr);
    }
  });
}

/**Agregar nuevos servicios**/
function addServicioMedico(nameservicio,precioServicio,medicoEsp)
{
    $.ajax({
        url:RUTA+"medico/new_service",
        method:"POST",
        data:{token_:TOKEN,name_servicio:nameservicio,precio_servicio:precioServicio,medico_esp:medicoEsp},
        success:function(response)
        {
            response = JSON.parse(response);

            if(response.response === 'ok')
            {
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"El servicio se a registrado correctamente",
                    icon:"success",
                    target:document.getElementById('modal-add-servicio')
                }).then(function(){
                    mostrarServiciosMedico(ID_SERV,"",LIMIT)  
                });
            }
            else
            {
               if(response.response === 'existe')
               {
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"El servicio que desea agregar ya existe",
                    icon:"warning",
                    target:document.getElementById('modal-add-servicio')
                });
               }
               else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error al agregar el servicio.",
                    icon:"error",
                    target:document.getElementById('modal-add-servicio')
                });
               }
            }
        }
    })
}

/**Importar, mediante excel los servicios del mèdico**/
function importAddServicio(espe)
{
    let formulario = new FormData(document.getElementById('form_excel'));
    formulario.append("medico_esp",espe.val())
    $.ajax({
        url:RUTA+"medico/import/service",
        method:"POST",
        data:formulario,
        processData:false,
        contentType:false,
        success:function(response)
        {
            response = JSON.parse(response);
            

           if(response.response === 'vacio')
           {
            Swal.fire(
                {
                    title:"Mensaje del sistema!",
                    text:"Seleccione un archivo excel para importar los datos",
                    icon:"warning",
                    target:document.getElementById('modal-add-servicio')
                }
            )
           }else
           {
            if(response.response === 'archivo no acceptable')
            {
                Swal.fire(
                {
                    title:"Mensaje del sistema!",
                    text:"El archivo seleccionado es incorrecto",
                    icon:"warning",
                    target:document.getElementById('modal-add-servicio')
                }
                );    
            }
            else{
                if(response.response === 'ok' || response.response === 'existe')
                {
               loading('#body_','#4169E1','chasingDots');

               setTimeout(() => {
                $('#body_').loadingModal('hide');
                $('#body_').loadingModal('destroy');
                Swal.fire(
                {
                    title:"Mensaje del sistema!",
                    text:"Los datos de su servicio han sido importados correctamente",
                    icon:"success",
                    target:document.getElementById('modal-add-servicio')
                }
                ).then(function(){
                    mostrarServiciosMedico(ID_SERV,"",LIMIT);
                    //$('#modal-add-servicio').modal("hide");
                    $('#registros').val("10");
                });
               }, 1000);
                }else{
                Swal.fire(
                {
                    title:"Mensaje del sistema!",
                    text:"Error al importar los datos del servicio",
                    icon:"error",
                    target:document.getElementById('modal-add-servicio')
                }
                );       
             }
            }
           }
        }
    })
}

/** EDITAR LOS SERVICIO **/
function EditarService(Tbody)
{
 $(Tbody).on('click','#editar',function(){
  let fila = $(this).parents('tr');
  Control = 'editar';
  let Nombre_De_Servicio = fila.find('td').eq(1).text();
  let Precio_De_Servicio = fila.find("td").eq(2).text();
  SERVICIO_EDITAR = fila.find("td").eq(3).text();
   
  $('#name_servicio').val(Nombre_De_Servicio) ;
  $('#precio_servicio').val(Precio_De_Servicio);
  $('.editar_texto').text("Editar servicio")
  $('#modal-add-servicio').modal("show");
 });
}

/** CONFIRMAR ELIMINADO LOGICO **/
function ConfirmSoftDeleteService(Tbody)
{
 $(Tbody).on('click','#delete',function(){
  let fila = $(this).parents('tr');
  
  let Nombre_De_Servicio = fila.find('td').eq(1).text();
  let Precio_De_Servicio = fila.find("td").eq(2).text();
  SERVICIO_EDITAR = fila.find("td").eq(3).text();
   
  Swal.fire({
  title: "Estas seguro de eliminar al servicio "+Nombre_De_Servicio+" ?",
  text: "Al aceptar el eliminado, automaticamente se quitará de la lista de los servicios del médico!",
  icon: "question",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si, eliminar!"
}).then((result) => {
  if (result.isConfirmed) {
     SoftDeleteServicio(SERVICIO_EDITAR);
  }
});
 });
}

/** Mosificar los servcios del médico**/
function updateServicio(id,servicioName,servicioPrecio)
{
 let formularioUpdate = new FormData();
 formularioUpdate.append("token_",TOKEN);
 formularioUpdate.append("name_servicio",servicioName);
 formularioUpdate.append("precio_servicio",servicioPrecio);
 
 $.ajax({
    url:RUTA+"medico/editar_servicio/"+id,
    method:"POST",
    data:formularioUpdate,
    contentType:false,
    processData:false,
    success:function(response)
    {
        response = JSON.parse(response);
      
        if(response.response === 'ok')
        {
            Swal.fire(
                {
                    title:"Mensaje del sistema!",
                    text:"Servicio modificado correctamente",
                    icon:"success",
                    target:document.getElementById('modal-add-servicio')
                }
            ).then(function(){
                mostrarServiciosMedico(ID_SERV,"",LIMIT)
            });
        }
        else
        {
            Swal.fire(
                {
                    title:"Mensaje del sistema!",
                    text:"Error al modificar los datos del servicio solicitado",
                    icon:"error",
                    target:document.getElementById('modal-add-servicio')
                }
            );  
        }
    }
 });
}

/** Mosificar los servcios del médico**/
function SoftDeleteServicio(id)
{
 
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
                mostrarServiciosMedico(ID_SERV,"",LIMIT)
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

/** Ver los servicios eliminados **/
function showServiciosEliminados()
{
 tabla_serviciosEliminados = $('#tabla_servicios_eliminados').DataTable({
    retrieve:true,
    responsive:true,
    "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
    ajax:{
        url:RUTA+"medico/servicios_eliminados/"+$('#especialidad_').val()+"?token_="+TOKEN,
        method:"GET",
        dataSrc:"response",
    },
    columns:[
        {"data":"name_servicio"},
        {"data":"name_servicio"},
        {"data":"precio_servicio"},
        {"data":null,render:function(){
            return '<button class="btn btn-success btn-rounded btn-sm" id="active_serv"><i class="bx bx-check"></i></button>';
        }}
    ]
    
 }).ajax.reload();

  /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
  tabla_serviciosEliminados.on( 'order.dt search.dt', function () {
    tabla_serviciosEliminados.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();
  /*======================================================================================*/
}

/*Confirmar el activado el servicio del médico*/
function ActiveServiceMedico(Tbody,Tabla)
{
    $(Tbody).on('click','#active_serv',function(){
       /// obtenemos la fila seleccionado
       let filaSel = $(this).parents('tr');
       
       /// verificamos para casos response
       if(filaSel.hasClass("child"))
       {
        filaSel = filaSel.prev();
       }

       let Data_ = Tabla.row(filaSel).data();

       let ID_SELECT = Data_.id_servicio; 
    
       ActivarServicio(ID_SELECT);
    });
}

/*Activar servicio del médico*/
function ActivarServicio(id)
{
    $.ajax(
        {
            url:RUTA+"activar_servicio_medico/"+id,
            method:"POST",
            data:{token_:TOKEN},
            success:function(response){
                response = JSON.parse(response);
                $('#mensaje_serv_active').show(400);

                setTimeout(() => {
                    $('#mensaje_serv_active').hide();
                    showServiciosEliminados();
                }, 2500);
            }
        }
    );
}
</script>    
@endsection