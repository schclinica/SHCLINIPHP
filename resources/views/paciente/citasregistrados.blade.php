@extends($this->Layouts("dashboard"))

@section("title_dashboard","Pacientes")

@section('css')
    <style>
        #tabla_citas_registrados>thead>tr>th
        {
            background-color: #4169E1;
            color: aliceblue
            

        }
    </style>
@endsection
@section('contenido')
 
<div class="col">
    <div class="card">
        <div class="card-body">
            <div class="card-test">
                <h4>Mis citas registrados</h4>
            </div>
            <div class="card-text">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped nowrap responsive" style="width: 100%"
                    id="tabla_citas_registrados">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ELIMINAR</th>
                                <th>FECHA</th>
                                <th>HORA</th>
                                <th>ESPECIALIDAD</th>
                                <th>MÉDICO</th>
                                <th>ESTADO</th>
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
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>
 var URL = "{{URL_BASE}}";
 var TOKEN = "{{$this->Csrf_Token()}}";
 var ID_CITA_,ID_HORARIO_CITA;
 var TablaCitasRegistrados;

  $(document).ready(function(){

    ShowCitasRegistrados();

    dropCitaRegistrado(TablaCitasRegistrados,'#tabla_citas_registrados tbody');
  });

 var ShowCitasRegistrados = ()=>{
    TablaCitasRegistrados = $('#tabla_citas_registrados').DataTable({
        language:SpanishDataTable(),
        processing:true,
        retrieve:true,
        "columnDefs": [{
        "searchable": false,
        "orderable": false,
        "targets": 0
        }],
       "order": [[1, 'asc']], /// enumera indice de las columnas de Datatable
        ajax:{
            url:URL+"citas_registrados_data?token_="+TOKEN,
            method:"GET",
            dataSrc:"response"
        },
        columns:[
            {"data":null},
            {"data":null,render:function(dta){
               if(dta.estado === 'pendiente')
               {
                return '<button class="btn btn-danger btn-rounded btn-sm" id="drop_cita"><i class="bx bx-x"></i></button>';
               }
               return '';
            }},
            {"data":"fecha",render:function(fecha){
                 return fecha;
            }},
            {"data":"hora_cita"},
            {"data":"nombre_esp"},
            {"data":"medico_data"},
            {"data":"estado",render:function(estado){
                if(estado === 'pendiente')
                {
                    return '<span class="badge bg-danger">Pendiente</span>';
                }
                else
                {
                    if(estado === 'anulado')
                    {
                        return '<span class="badge bg-warning">Anulado</span>'; 
                    }
                    else
                    {
                        return '<span class="badge bg-primary">Finalizado <i class="bx bx-check"></i></span>';
                    }
                    
                }
            }},
        ]
    }).ajax.reload();

    /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
   TablaCitasRegistrados.on( 'order.dt search.dt', function () {
    TablaCitasRegistrados.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();
 }

 // El paciente realizar el proceso de anular la cita que registró, siempre en cuándo estee en estado pendiente.
 function anularCita(HorarioId,CitaIdDrop)
 {
    $.ajax({
        url:URL+"anular_cita_medica",
        method:"POST",
        data:{
            token_:TOKEN,
            horario:HorarioId,
            cita:CitaIdDrop
        },
        success:function(response)
        {
            response = JSON.parse(response);

            if(response.response == 1)
            {
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"La cita que has registrado, se ha anulado sin problemas 😃",
                    icon:"success"
                }).then(function(){
                    ShowCitasRegistrados();
                });
            }
            else
            {
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error,al intentar anular la cita",
                    icon:"error"
                });
            }
        }
    });
 }

 /// confirmar el anulado de la cita
 function dropCitaRegistrado(Tabla,Tbody)
 {
  $(Tbody).on('click','#drop_cita',function(){
    let fila = $(this).parents("tr");

    if(fila.hasClass("child"))
    {
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();

    ID_CITA_ = Data.id_cita_medica; ID_HORARIO_CITA = Data.id_horario;
    Swal.fire({
  title: 'Estas seguro de anular la cita ?',
  text: "Al anular la cita, ya no podrás asistir a la cita que realizaste!",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Si, eliminar!'
  }).then((result) => {
  if (result.isConfirmed) {
    anularCita(ID_HORARIO_CITA,ID_CITA_);
  }
  });
  });
 }
</script>
@endsection