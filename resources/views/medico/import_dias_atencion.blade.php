@extends($this->Layouts("dashboard"))

@section("title_dashboard","import-dias-atención")

@section('css')
 <style>
     #hora_atencion>thead>tr>th{
        padding: 20px;
        background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);
        color:aqua;
      }
 </style>
@endsection
 
@section("contenido")
 
<div class="col" id="dia_trabajo_content">
    <div class="card">
        <div class="card-header" style="background:linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%) ">
            <h5 class="text-white letra">Importar dias de atención médica</h5>
        </div>
        <div class="card-body">
            <div class="card-text mt-3">
                <label for=""><b class="letra">Seleccione el archivo excel</b><b class="text-danger">(*)</b></label>
                <form action="" method="post" enctype="multipart/form-data" id="form_import_dias_atencion">
                    <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                    <div class="input-group">
                        <input type="file" class="form-control" name="excel" id="excel">
                        <button class="button-store" id="save_import">Importar <i class='bx bx-import' ></i></button>
                    </div>
                </form>
              
                <div class="table-responsive">
                    <table class="table table-bordered table-sm nowrap responsive" id="hora_atencion" style="width: 100%">
                        <thead>
                            <th>Día</th>
                            <th>Hora inicio</th>
                            <th>Hora de cierre</th>
                            <th>Acciones</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{--- MODAL PARA EDITAR EL DIA DE ATENCION DEL MÉDICO--}}
<div class="modal fade" id="modal_edit_dia_atencion">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(to bottom, #ADD8E6 100%,#F0F8FF 50%);">
                <h4>Editar día de trabajo</h4>
            </div>

            <div class="modal-body">
                <form action="" method="post" id="form_editar_dia_atencion">
                    <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                    <div class="form-group">
                        <label for="dia" class="form-label"><b>Día <span class="text-danger"></span></b></label>
                        <input type="text" name="dia" id="dia" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="hora_inicio" class="form-label"><b>Hora inicio <span class="text-danger"></span></b></label>
                        <input type="time" name="hora_inicio" id="hora_inicio" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="hora_final" class="form-label"><b>Hora cierre <span class="text-danger"></span></b></label>
                        <input type="time" name="hora_final" id="hora_final" class="form-control">
                    </div>
                </form>
            </div>

            <div class="modal-footer border-2">
                <button class="btn btn-success rounded" id="update_dia_atencion">Guardar <i class='bx bx-save'></i></button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    var RUTA = "{{URL_BASE}}"; // la url base del sistema
    var TOKEN = "{{$this->Csrf_Token()}}";
    var TablaHoraAtencion;
    var IDATENCION;
</script>
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>
$(document).ready(function(){

    $('#save_import').click(function(event){
    event.preventDefault();
    ImportarDiasAtencion();
    });

    showDaysWork();

    $('#update_dia_atencion').click(function(){
        $.ajax({
            url:RUTA+"medico/update/"+IDATENCION+"/dia-trabajo",
            method:"POST",
            data:$('#form_editar_dia_atencion').serialize(),
            dataType:"json",
            success:function(response){
                if(response.response === 'ok'){
                    Swal.fire({
                        title:"Mensaje del sistema!",
                        text:"Día de trabajo modificado correctamente!",
                        icon:"success",
                        target:document.getElementById('modal_edit_dia_atencion')
                    }).then(function(){
                        showDaysWork();
                    });
                }else{
                    if(response.response === 'error-token' || response.response === 'error'){
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Error al actualizat día de trabajo!",
                            icon:"error",
                            target:document.getElementById('modal_edit_dia_atencion')
                        });
                    }
                }
            }
        })
    });
   
});

/**MOSTRAR LOS DIAS DE TRABAJO DEL MÉEDICO*/
function showDaysWork(){
    TablaHoraAtencion = $('#hora_atencion').DataTable({
        retrieve:true,
        language:SpanishDataTable(),
        ajax:{
            url:RUTA+"medico/dias-de-trabajo-clinica",
            method:"GET",
            dataSrc:"dias",
        },
        columns:[
            {"data":"dia",render:function(dia){
                return dia.toUpperCase();
            }},
            {"data":"hora_inicio_atencion"},
            {"data":"hora_final_atencion"},
            {"data":null,render:function(){
                return `
                  <button class="btn btn-warning btn-sm rounded" id='editar_dia_atencion'><i class='bx bx-edit-alt'></i></button>
                   <button class="btn btn-danger btn-sm rounded" id='delete_dia_atencion'><i class='bx bx-trash'></i></button>
                `;
            }}
        ]
    }).ajax.reload();
    ConfirmEliminadoDiaAtencion(TablaHoraAtencion,'#hora_atencion tbody');
    EditarDiaAtencion(TablaHoraAtencion,'#hora_atencion tbody')
}

/// Confirmar antes de eliminar
function ConfirmEliminadoDiaAtencion(Tabla,Tbody){
 $(Tbody).on('click','#delete_dia_atencion',function(){
    let fila = $(this).parents("tr");

    if(fila.hasClass("child")){
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();

    let IdDiaAtencion = Data.id_atencion;
    let dia = Data.dia;

 Swal.fire({
    title: "Estas seguro de eliminar el día de atencion "+dia+" ?",
    text: "Al eliminar el dia de atención seleccionado, se borrará de la lista y no podrás recuperarlo!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminar!"
    }).then((result) => {
    if (result.isConfirmed) {
     DeleteDayAtencion(IdDiaAtencion);
    }
    });
 });
}

/// editar dia de atencion
function EditarDiaAtencion(Tabla,Tbody){
 $(Tbody).on('click','#editar_dia_atencion',function(){
    let fila = $(this).parents("tr");

    if(fila.hasClass("child")){
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();

    IDATENCION = Data.id_atencion;
    let dia = Data.dia;
    let HoraInicio = Data.hora_inicio_atencion;
    let HoraFinal = Data.hora_final_atencion;

    $('#dia').val(dia);$('#hora_inicio').val(HoraInicio);
    $('#hora_final').val(HoraFinal);

    $('#modal_edit_dia_atencion').modal("show");

  
 });
}
/// eliminar dia de atencion
function DeleteDayAtencion(id){
    $.ajax({
        url:RUTA+"medico/delete/"+id+"/dia-programado",
        method:"POST",
        data:{
            token_:TOKEN
        },
        dataType:"json",
        success:function(response){
            if(response.response === 'error-token'){
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error en el token Csrf!",
                    icon:"error"
                });
            }else{
                if(response.response === 'existe'){
                    Swal.fire({
                        title:"¡ADVERTENCIA!",
                        text:"No se puede eliminar al día de atención seleccionada!",
                        icon:"warning"
                    });
                }else{
                    if(response.response === 'ok'){
                        Swal.fire({
                        title:"¡Mensaje del sistema!",
                        text:"Dia de atención eliminado correctamente!",
                        icon:"success"
                     }).then(function(){
                        showDaysWork();
                     });   
                    }else{
                        Swal.fire({
                        title:"¡ADVERTENCIA!",
                        text:"Error al intentar eliminar al día seleccionado!",
                        icon:"error"
                       });
                    }
                }
            }
        }
    })
}
/**
 *Importar los días de atención del médico a traves de excel
 */
 function ImportarDiasAtencion()
{
    var FormularioData = new FormData(document.getElementById('form_import_dias_atencion'));
    loading('#dia_trabajo_content','#4169E1','chasingDots') 
    $.ajax({
        url:RUTA+"medico/importar_dias_atencion",
        method:"POST",
        data:FormularioData,
        cache:false,
        contentType: false,
        processData: false,
        success:function(response){
            response = JSON.parse(response);

           if(response.response === 'vacio')
           {
            $('#dia_trabajo_content').loadingModal('hide');
            $('#dia_trabajo_content').loadingModal('destroy');
            Swal.fire({
                title:"Advertencia!",
                text:"Seleccione un archivo donde tenga los días de atención, para guardarlos en el sistema",
                icon:"warning",
      
            });
           }else
           {
            if(response.response === 'error-tipo-archivo')
            {
                $('#dia_trabajo_content').loadingModal('hide');
                $('#dia_trabajo_content').loadingModal('destroy');
                Swal.fire({
                title:"Advertencia!",
                text:"El archivo seleccionado es incorrecto,solo se permite un tipo de archivo excel",
                icon:"error",
             
            });
            }
            else{
                setTimeout(() => {
                    $('#dia_trabajo_content').loadingModal('hide');
                    $('#dia_trabajo_content').loadingModal('destroy');
               Swal.fire({
                    title:"Advertencia!",
                    text:"Los días de atención han sido importados correctamente",
                    icon:"success",
                    
                    }).then(function(){
                    showDaysWork();
                    });
                }, 1000);
            }
           }
        }
    }).then(function(){
        showHorasProgramadosMedico(ATENCION_ID);
    });
    
}


</script>
@endsection