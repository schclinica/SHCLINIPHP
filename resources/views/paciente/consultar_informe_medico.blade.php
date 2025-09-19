@extends($this->Layouts("dashboard"))

@section("title_dashboard","Informe-Médico")

@section('css')
<style>
#Tabla_Informe_Medico>thead>tr>th
{
    background-color: #4169E1;
    color:aliceblue;
}
</style>
@endsection
@section('contenido')
<div class="row">
    <div class="col-12">
       <div class="card">
        <div class="card-body">
            <div class="card-text">
                <p class="h4">consultar informe médico</p>
            </div>
            <div class="table table-responsive">
                <table class="table table-bordered responsive table-striped nowrap" id="Tabla_Informe_Medico" 
                style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>FECHA</th>
                            <th>MEDICO</th>
                            <th>VER</th>
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
{{-- JS ADICIONALES ---}}
<script>
    var RUTA = "{{URL_BASE}}" // la url base del sistema
    var TOKEN = "{{$this->Csrf_Token()}}";
    var PERSONA_ID = "{{$this->profile()->id_persona}}";
  </script>
  <script src="{{URL_BASE}}public/js/control.js"></script>
<script>
var TablaInformeMedico;
$(document).ready(function(){

    showInformeMedico();

    verInformePaciente(TablaInformeMedico,'#Tabla_Informe_Medico tbody');
});

/// Mostrar datos del informe médico en DataTable
function showInformeMedico()
{
    TablaInformeMedico = $('#Tabla_Informe_Medico').DataTable({
        retrieve:true,
        responsive:true,
        language:SpanishDataTable(),
        "columnDefs": [{
        "searchable": false,
        "orderable": false,
        "targets": 0
        }],
        ajax:{
            url:RUTA+"paciente/show/informe_medico?token_="+TOKEN,
            method:"GET",
            dataSrc:"response"
        },
        columns:[
            {"data":"id_informe"},
            {"data":"fecha",render:function(fecha){return '<span class="badge bg-info">'+fecha+'</span>';}},
            {"data":"medico"},
            {"data":null,render:function(){
                return '<button class="btn btn-rounded btn-danger btn-sm" id="informe"><b>informe <i class="bx bxs-file-doc"></i></b></button>';
            }},
        ]
    });

      /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
   TablaInformeMedico.on( 'order.dt search.dt', function () {
    TablaInformeMedico.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();
}

/// ver informe médico 
function verInformePaciente(Tabla,Tbody)
{
    $(Tbody).on('click','#informe',function(){
        let FilaSeleccionado = $(this).parents('tr');

        if(FilaSeleccionado.hasClass('child'))
        {
            FilaSeleccionado = FilaSeleccionado.prev();
        }

        let Dato = Tabla.row(FilaSeleccionado).data();

        let id_Informe = Dato.id_informe;

         window.open(RUTA+"informe_medico?id="+id_Informe,"_blank")
    });
}
</script>
@endsection