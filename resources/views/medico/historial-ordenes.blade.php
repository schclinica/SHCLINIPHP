@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Historial-Orden-médico')

@section('css')
    <style>
       
    </style>
@endsection
@section('contenido')
<div class="card">
<div class="card-header" style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
       <h3 class="letra text-white">Historial - órden médica</h3>
  </div>
    <div class="card-body">
        <div class="card-text mt-3">
            <table class="table table-bordered table-striped table-hover nowrap responsive" style="width: 100%"
            id="lista_historial_orden_medica">
                <thead style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                    <tr>
                        <th class="py-3 text-white letra">Num.órden médica</th>
                        <th class="py-3 text-white letra">Fecha</th>
                        <th class="py-3 text-white letra">Paciente</th>
                        <th class="py-3 text-white letra">Ver</th>
                    </tr>

                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
 <script src="{{ URL_BASE }}public/js/control.js"></script>
 <script>
  var TablaHistorialOrdenesMedica;
  var RUTA = "{{ URL_BASE }}" // la url base del sistema
  var TOKEN = "{{ $this->Csrf_Token() }}";
   $(document).ready(function(){
     showHistorialOrdenesMedicas();
   });

  /*VISUALIZAR EL HISTORIAL DE LAS ÓRDENES MÉDICAS*/
  function showHistorialOrdenesMedicas(){
    TablaHistorialOrdenesMedica = $('#lista_historial_orden_medica').DataTable({
        retrieve:true,
        responsive:true,
        language:SpanishDataTable(),
        ajax:{
            url: RUTA+"historial-de-ordenes/all",
            method:"GET",
            dataSrc:"historial_orden",
        },
        columns:[
            {"data":"serieorden"},
            {"data":"fecha_registro_orden",render:function(fecha){
                return '<span class="badge bg-success">'+fecha+'</span>';
            }},
            {"data":null,render:function(persona){
                return (persona.apellidos+" "+persona.nombres).toUpperCase()
            }},
            {
                "data":null,render:function(orden){
                    return `
                              <a href='`+RUTA+`orden_medica-paciente/`+orden.id_orden_medico+`' target="_blank" class="btn btn-outline-primary btn-sm"><i class="fas fa-print"></i></a>    
                              <button class="btn btn-danger btn-sm" id='eliminar'><b>X</b></button>`;
                }
            }
        ]
    }).ajax.reload();

    ConfirmarEliminadoOrden('#lista_historial_orden_medica tbody',TablaHistorialOrdenesMedica);
  }

  function ConfirmarEliminadoOrden(Tbody,Tabla){
   $(Tbody).on('click','#eliminar',function(){
     let fila = $(this).parents("tr");

     if(fila.hasClass("child")){
        fila = fila.prev();
     }

     let Data = Tabla.row(fila).data();

  Swal.fire({
    title: "ESTAS SEGURO DE ELIMINAR LA ORDEN MÉDICA # "+Data.serieorden+" ? ",
    text:"Al aceptar, se quitará de la lista!!!" ,
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminar!"
    }).then((result) => {
    if (result.isConfirmed) {
       EliminarLaOrden(Data.id_orden_medico);
    }
    });
   });
  }

  function EliminarLaOrden(id){
    $.ajax({
        url:RUTA+"orden_medica-paciente/delete/"+id,
        method:"POST",
        data:{
            token_:TOKEN
        },
        dataType:"json",
        success:function(response){
             
            if(response.error != undefined){
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:response.error,
                    icon:"error"
                });
            }else{
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!!",
                    text:response.response,
                    icon:"success"
                }).then(function(){
                    showHistorialOrdenesMedicas();
                })
            }
        }
    })
  }
 </script>
@endsection