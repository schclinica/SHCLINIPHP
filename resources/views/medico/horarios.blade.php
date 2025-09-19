@extends($this->Layouts("dashboard"))

@section("title_dashboard","Mis Horarios")

@section('css')
   <style>
        #tabla_horas_medico>thead>tr>th {
            background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);
            color:#fff;
            padding: 19px;
        }
        .list-group
        {
            cursor: pointer;
        }
     td.hide_me
        {
        display: none;
        }
        .swal2-popup {
            max-height: 520px !important;
            max-width: 480px !important;
            
        }

        .swal2-icon {
            font-size: 16px !important;
        }

        .swal2-text {
            max-height: 70px;
        }

        .swal2-confirm {
            background: #38c5c5 !important;
        }
    </style>
@endsection

@section('contenido')

 <div class="card">
    <div class="card-header" style="background:linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%)"><p class="h4 letra text-white float-start">Mis Horarios de trabajo</p>
      <button class="btn_blue col-xl-3 col-lg-4 col-md-5 col-12 float-end" id="new_horario" style="display:none;">Agregar uno personalizado <i class='bx bx-plus'></i></button>
    </div>
    <div class="card-body">
         <div class="row">
        <div class="col-12 alerta mt-2" style="display: none">
            <div class="alert alert-danger">Hola, estimado(a) doctor(a), <span class='badge bg-primary'>{{$this->profile()->apellidos}} {{$this->profile()->nombres}}</span>, usted, aún no tiene un horario de trabajo asignado.
            Ir a completar los días de atención <a href="{{$this->route("medico/import-dias-de-atencion")}}" class="btn btn-rounded btn-primary btn-sm">Aquí <i class='bx bx-log-in-circle' ></i></a></div>
        </div>
        <div class="col-xl-4 col-lg-4 col-12 mt-2">
            
            <div class="card-text" id="text_horas" style="display: none"><b>Tus Horarios</b></div>
            <div class="list-group" id="lista">
                @php
                    $contador = 0
                @endphp
                @if (isset($Horario_Medico))
                    @foreach ($Horario_Medico as $item)
                    @php
                        $contador++
                    @endphp

                     @if ($contador == 1)
                     <li class="list-group-item active" aria-current="true"><span class='badge bg-info'>{{$item->dia }} <h5 id="id_atencion" class="d-none">{{$item->id_atencion}}</h5></span> ({{$item->hora_inicio_atencion}} -{{$item->hora_final_atencion}})
                     <h4 id="hitext" class="d-none">{{$item->hora_inicio_atencion}}</h4> <h4 id="hftext" class="d-none">{{$item->hora_final_atencion}}</h4></li>
                     @else 
                     <li class="list-group-item"  ><span class='badge bg-info'>{{$item->dia }} <h5 id="id_atencion" class="d-none">{{$item->id_atencion}}</h5></span>  ({{$item->hora_inicio_atencion}} -{{$item->hora_final_atencion}})
                     <h4 id="hitext" class="d-none">{{$item->hora_inicio_atencion}}</h4> <h4 id="hftext" class="d-none">{{$item->hora_final_atencion}}</h4></li></li>
                     @endif
                    @endforeach
                @endif
   
                </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-12" id="tabla_horas" style="display: none">
            <div class="table-responsive">
                <table class="table table-bordered table-striped responsive nowrap" id="tabla_horas_medico" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="d-none">ID</th>
                            <th>HORARIO</th>
                            <th>ESTADO</th>
                            <th>DESHABILITAR</th>
                            <th>MOTIVO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    </div>
 </div>

 <!--Horario nuevo modal--->
 <div class="modal fade" id="modal_new_horario" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%)">
               <h5 id="text_modal_new_horario float-end"> <span id="text_horario" class="text-white letra">Nuevo horario</span></h5>  
            </div>
            <div class="modal-body">
                <div id="form_manual_hoario_medico">
                    <div class="form-group">
                        <label for="hora_inicio"><b>Hora inicio (*)</b></label>
                        <input type="time" class="form-control" id="hi">
                    </div>
                    <div class="form-group mb-2">
                        <label for="hora_inicio"><b>Hora que finaliza (*)</b></label>
                        <input type="time" class="form-control" id="hf">
                    </div>
                </div>

                <a href="#" class="mt-4" style="display: none" id="import-data"><b>Importar datos por <span class="text-primary">EXCEL Presiona aquí
                    <img src="{{$this->asset('img/icons/unicons/excel.ico')}}" class="menu-icon"
                    style="width: 24px;height: 24px;" alt=""></span></b></a>

                    <a href="#" class="mt-4" style="display: none" id="form_default_hour"><b>Volver a mi  <span class="text-primary">Formulario
                        <img src="{{$this->asset('img/icons/unicons/receta.ico')}}" class="menu-icon"
                        style="width: 24px;height: 24px;" alt=""></span></b></a>

                <div class="form-group" id="grupo_estado_horarios" style="display: none">
                    <label for="estado_horariodata">Seleccione un estado</label>
                    <select name="estado_horariodata" id="estado_horariodata" class="form-control">
                        <option value="disponible">Disponible</option>
                        <option value="reservado">Reservado</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>

                <div class="form-group" id="grupo_motivo" style="display: none">
                    <label for="estado_horariodata">Indique el motivo <span class="text-danger">*</span></label>
                     <textarea name="motivohorario_inactive" id="motivohorario_inactive" cols="30" rows="4" class="form-control" placeholder="Escriba el motivo aquí...."></textarea>
                </div>

                <div id="form-file_import" style="display: none" class="mt-2">
                 <form action="" method="post" enctype="multipart/form-data" id="form_import">
                    <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                    <input type="hidden" id="atencion_data" name="atencion_data">
                    <label for="file"><b>Seleccione un archivo excel</b></label>
                    <div class="input-group mt-1" >
                        <input type="file" id="file" name="file" class="form-control">
                        <button class="btn btn-rounded btn-success" id="import_horario">Importar <i class='bx bxs-file-import' ></i></button>
                    </div>
                </form>
                </div>
            </div>
            <div class="modal-footer border-2">
                <div class="d-flex flex-row">
                    <button class="btn btn-success btn-rounded" id="save_hour">Guardar <i class='bx bxs-save'></i></button>
                     
                    <button class="btn btn-rounded btn-danger btn-rounded mx-1" id="exit_"><i class='bx bxs-exit'></i> Salir</button>
                   
                    {{-- <button class="btn btn-rounded btn-primary m-1" style="display: none" id="import-data">Importar horario <img src="{{$this->asset('img/icons/unicons/excel.ico')}}" class="menu-icon"
                    style="width: 24px;height: 24px;" alt=""></button> --}}
                </div>
            </div>
        </div>
    </div>
 </div>
@endsection

@section('js')
<script>
    var ATENCION_ID=null ;
    var RUTA = "{{URL_BASE}}" // la url base del sistema
    var TOKEN = "{{$this->Csrf_Token()}}";
    var ID_HORARIO;
    var TablaHorasMedico;
    var Proceso;
    var HORAIN,HORAFIN;
</script>
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>
$(document).ready(function(){
$('#lista li').on('click', function (e) {
    e.preventDefault()
    
    $(this).tab('show');
});

$('#estado_horariodata').change(function(){
   if($(this).val().trim() === 'inactivo')
   {
    $('#grupo_motivo').show(500);
   }else{
    $('#grupo_motivo').hide(300);
    $('#motivohorario_inactive').val("");
   }
});

$('#new_horario').click(function(){
    Proceso = 'save';
    $('#grupo_estado_horarios').hide();
    $('#text_horario').text("Nuevo horario");
    $('#import-data').show();
    $('#text_modal_new_horario').text("Nuevo horario");
    $('#modal_new_horario').modal("show");
});

$('#import_horario').click((evento)=>{
    evento.preventDefault();
    ImportarHorario();    
});

$('#exit_').click(function(){
 
    $('#hi').val("");$('#hf').val("");
    $('#modal_new_horario').modal("hide");
    $('#form-file_import').hide();
    $('#motivohorario_inactive').val("");
    $('#file').val("")
    $('#form-file_import').hide();
    $('#form_manual_hoario_medico').show();
    $('#form_default_hour').hide();
    $('#import-data').hide();
    $('#save_hour').show();
});
$('#import-data').click(function(){
    //Proceso = 'import';
    $('#atencion_data').val(ATENCION_ID);
    $('#form-file_import').show(690);
    $('#form_manual_hoario_medico').hide();
    $('#form_default_hour').show();
    $('#import-data').hide();
    $('#save_hour').hide();
});

$('#form_default_hour').click(function(){
    //Proceso = 'import';
    $('#form-file_import').hide();
    $('#form_manual_hoario_medico').show();
    $('#form_default_hour').hide();
    $('#import-data').show();
    $('#save_hour').show();
});
$('#save_hour').click(function(){
  if(Proceso === 'save')
  {
    if($('#hi').val().trim().length == 0)
    {
        $('#hi').focus();
    }
    else
    {
        if($('#hf').val().trim().length == 0)
        {
            $('#hf').focus();
        }
        else
        {
            $.ajax({
        url:RUTA+"medico/add_horario_personalizado/"+ATENCION_ID,
        method:"POST",
        data:{
            token_:TOKEN,
            hi:$('#hi').val(),
            hf:$('#hf').val()
        },
        success:function(response)
        {
            response = JSON.parse(response);

            if(response.response === 'ok')
            {
                Swal.fire(
                    {
                        title:"Mensaje del sistema!",
                        text:"El horario se a agregado correctamente",
                        icon:"success",
                        target:document.getElementById('modal_new_horario')
                    }
                ).then(function(){
                    showHorasProgramadosMedico(ATENCION_ID);
                });
            }
            else
            {
                Swal.fire(
                    {
                        title:"Mensaje del sistema!",
                        text:"Error, no se pudo agregar el nuevo horario",
                        icon:"error"
                    }
                )
            }
        }
    });
        }
    }
  }else
  {
    updateHorario(ID_HORARIO);
  }
});
ATENCION_ID = $('.list-group .active #id_atencion').text();
 
if(ATENCION_ID!= '')
{
    $('#text_horas').show();
    $('#tabla_horas').show();
    $('.alerta').hide();
    $('#new_horario').show();
    showHorasProgramadosMedico(ATENCION_ID);
    deleteHorario();
    editarHorario();
}
else{
   
    $('#text_horas').hide();
    $('#tabla_horas').hide();
    $('.alerta').show();
    $('#new_horario').hide()
}
$('.list-group').on('click','li',function(){
   

    ATENCION_ID =  $(this).find('#id_atencion').text();

    showHorasProgramadosMedico(ATENCION_ID);     
});

EstadoHorarioCambio();

});

function showHorasProgramadosMedico(dia)
{
     TablaHorasMedico = $('#tabla_horas_medico').DataTable({
        pageLength:50,
        responsive:true,
        bDestroy:true,
        language:SpanishDataTable(),
       "columnDefs": [{
        "searchable": false,
        "orderable": false,
        "targets": 0
        }],
        
        ajax:{
            url:RUTA+"medico/horarios_programados_por_dia/"+dia+"?token_="+TOKEN,
            method:"GET",
            dataSrc:"response"
        },
        columns:[
            {"data":"estado"},
            {"data":"id_horario"},
            {"data":null,render:function(hora){return hora.hora_inicio+" - "+hora.hora_final}},
            {"data":"estado",render:function(estado){
                let estatu = '';
                if(estado === 'disponible')
                {
                    estatu = '<span class="badge bg-success">Disponible</span>';
                }
                else
                {
                    if(estado === 'reservado')
                    {
                        estatu = '<span class="badge bg-info">Reservado</span>';
                    }
                    else
                    {
                        estatu = '<span class="badge bg-danger">Deshabilitado</span>';
                    }
                }

                return estatu;
            }},
            {"data":null,render:function(estado){
                let boton = '';
                if(estado.estado === 'disponible')
                {
                    boton = `
                   <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="update_estado_horario`+estado.id_horario+`" checked>
                        <label class="form-check-label" for="update_estado_horario`+estado.id_horario+`" style="cursor: pointer;" >Deshabilitar</label>
                    </div>`;
                }
                else
                {
                   if(estado.estado === 'inactivo')
                   {
                    boton = `
                   <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="update_estado_horario`+estado.id_horario+`" >
                        <label class="form-check-label" for="update_estado_horario`+estado.id_horario+`" style="cursor: pointer;">Habilitar</label>
                    </div>`;
                   }
                   else
                   {
                    boton = '';
                   }
                }
                return boton;
            }},
            {"data":null,render:function(motivo){
                if(motivo.estado === "inactivo"){
                    return motivo.desc_motivo;
                }
                return '<span class="badge bg-danger">-----------</span>';
            }},
            {"data":null,render:function(){

                return `
                <div class="row">
                 <div class="col-xl-4 col-lg-3 col-md-4 col-sm-2 col-2 m-1">
                 <button class='btn btn-rounded btn-outline-danger btn-sm' id='delete'><i class='bx bx-x' ></i></button>
                 </div>

                 <div class="col-xl-4 col-lg-3 col-md-4 col-sm-2 col-2 m-1">
                 <button class="btn rounded btn-outline-warning btn-sm" id='editar' ><i class='bx bxs-eyedropper'></i></button>
                 </div>
                  
                 </div> 
                `;
            }}

        ],
     columnDefs:[
        { "sClass": "hide_me", target: 1 }
        ]
    });
     /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
   TablaHorasMedico.on( 'order.dt search.dt', function () {
    TablaHorasMedico.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();
}

///activar y deshabilitar los horarios de un méico
function EstadoHorarioCambio()
{
    $('#tabla_horas_medico').on('click','input[type=checkbox]',function(){

        let fila = $(this).parents('tr');

        if(fila.hasClass('child')){
            fila = fila.prev();
        }

        ID_HORARIO = fila.find('td').eq(1).text();

        if($(this).is(":checked"))
        {
            cambiarEstadoHorario(ID_HORARIO,"disponible");
        }
        else
        {
            cambiarEstadoHorario(ID_HORARIO,"inactivo");
        }
        showHorasProgramadosMedico(ATENCION_ID);
    });
}

function cambiarEstadoHorario(id,estado)
{
    $.ajax({
        url:RUTA+"medico/"+id+"/"+estado+"/cambiar_estado",
        method:"POST",
        data:{token_:TOKEN},
        success:function(response)
        {
            response = JSON.parse(response);

            if(response.response !== 'ok')
            {
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error al desactivar el horario seleccionado",
                    icon:"error"
                })  
            }
             
        }
    })
}
/// eliminar horario
function deleteHorario()
{
$('#tabla_horas_medico').on('click','#delete',function(){    
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

   ID_HORARIO = fila.find('td').eq(1).text();

    Swal.fire({
  title: 'Estas seguro de eliminar el horario ( '+fila.find('td').eq(2).text()+' ) ',
  text: "Al eliminar, automaticamente se quitará de su lista de horarios de atención",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Si eliminar!'
  }).then((result) => {
  if (result.isConfirmed) {
     $.ajax({
        url:RUTA+"medico/"+ID_HORARIO+"/eliminar_horario",
        method:"POST",
        data:{
            token_:TOKEN
        },
        success:function(response)
        {
            response = JSON.parse(response);

            if(response.response === 'ok')
            {
                Swal.fire(
                    {
                        title:"Mensaje del sistema!",
                        text:"El horario se a eliminado correctamente",
                        icon:"success"
                    }
                ).then(function(){
                    showHorasProgramadosMedico(ATENCION_ID);
                });
            }
            else
            {
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error, no se pudo eliminar el horario seleccionado",
                    icon:"error"
                })
            }
        }
     })
  }
})
  }); 
}

/// editar el horario del médico
function editarHorario()
{  
    $('#tabla_horas_medico tbody').on('click','#editar',function(){
    Proceso = 'editar';
    $('#text_horario').text("Editar horario");
    $('#grupo_estado_horarios').show();
    $('#import-data').hide();
    $('#text_modal_new_horario').text("Editar horario");
    $('#modal_new_horario').modal("show");
    $('#form-file_import').hide()
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

    ID_HORARIO = fila.find('td').eq(1).text();//07:00:00
    let Horario = fila.find('td').eq(2).text().split("-");
    let estado = fila.find('td').eq(3).text();
    let motivoText = fila.find('td').eq(5).text();
    estado = estado ==='Reservado'?'reservado':(estado==='Disponible'?'disponible':'inactivo');
    $('#estado_horariodata').val(estado);
    
    
   if(estado === 'inactivo')
    {
    $('#grupo_motivo').show(200);
    $('#motivohorario_inactive').val(motivoText);
    }else{
    $('#grupo_motivo').hide(300);
    $('#motivohorario_inactive').val("");
    }
    $('#hi').val(Horario[0].substr(0,5)); $('#hf').val(Horario[1].substr(1,5));
    }); 
}
// modificar el horario
function updateHorario(id)
{
 $.ajax({
    url:RUTA+"medico/"+id+"/modificar_horario",
    method:"POST",
    data:{
        token_:TOKEN,
        hi:$('#hi').val(),
        hf:$('#hf').val(),
        estado_horariodata:$('#estado_horariodata').val(),
        motivo:$('#motivohorario_inactive').val()
    },
    success:function(response)
    {
        response = JSON.parse(response);

        if(response.response === 'ok')
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Horario modificado correctamente",
                icon:"success",
                target:document.getElementById('modal_new_horario')
            }).then(function(){
                showHorasProgramadosMedico(ATENCION_ID);
            });
        }
        else
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al modificar el horario",
                icon:"error",
                target:document.getElementById('modal_new_horario')
            })
        }
    }
 });
}

/// importamos los datos
function ImportarHorario()
{
    var FormularioData = new FormData(document.getElementById('form_import'));
  
    $.ajax({
        url:RUTA+"medico/importar_horario",
        method:"POST",
        data:FormularioData,
        cache:false,
        contentType: false,
        processData: false,
        success:function(response){
            response = JSON.parse(response);

           if(response.response === 'vacio')
           {
            Swal.fire({
                title:"Advertencia!",
                text:"Seleccione un archivo donde tenga sus horarios para guardarlos en el sistema",
                icon:"warning",
                target:document.getElementById('modal_new_horario')
            });
           }else
           {
            if(response.response === 'archivo no aceptable')
            {
                Swal.fire({
                title:"Advertencia!",
                text:"El archivo seleccionado es incorrecto,solo se permite un tipo de archivo excel",
                icon:"error",
                target:document.getElementById('modal_new_horario')
            });
            }
            else{
                Swal.fire({
                title:"Advertencia!",
                text:"Tus horarios han sido importados correctamente",
                icon:"success",
                target:document.getElementById('modal_new_horario')
            });
            }
           }
        }
    }).then(function(){
        showHorasProgramadosMedico(ATENCION_ID);
    });
    
}
</script>
@endsection