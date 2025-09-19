@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Resumen de caja')

@section('css')
  <style>
    .card{
        background-color: #F0FFFF;
    }
    td.hide_me
    {
      display: none;
    }
    #lista_resumen_caja>thead>tr>th{
      background: linear-gradient(to bottom, rgba(228,245,252,1) 0%,rgba(191,232,249,1) 50%,rgba(159,216,239,1) 51%,rgba(42,176,237,1) 100%);
    }
  </style>
@endsection

@section('contenido')
<div class="row">
    <div class="col-12">
        <div class="card">
       <div class="card-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 76%);">
         <h5 class="text-white letra">Resumen de caja</h5>
       </div>
            <div class="card-body">
                <div class="card-text">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-hover table-striped nowrap responsive" id="lista_resumen_caja" >
                         <thead>
                            <tr>
                                <th class="text-dark py-3 letra">#</th>
                                <th class="text-dark py-3 letra">Acciones  {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                <th class="text-dark py-3 letra">Fecha Apertura</th>
                                <th class="text-dark py-3 letra">Fecha Cierre</th>
                                <th class="text-dark py-3 letra">Ingreso Total clínica  {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                <th class="text-dark py-3 letra">Ingreso Total Farmacia {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                 <th class="text-dark py-3 letra">Gasto Compras de Productos Farmacia {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                <th class="text-dark py-3 letra">Gasto Interno  {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                <th class="text-dark py-3 letra">Saldo Total {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                               
                            </tr>
                         </thead>
                         
                        </table>
                     </div>
                </div>

              

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ URL_BASE }}public/js/control.js"></script>
<script src="{{ URL_BASE }}public/js/egresos.js"></script>
<script>
var TablaResumenCaja ;
var RUTA = "{{ URL_BASE }}" // la url base del sistema
var TOKEN = "{{ $this->Csrf_Token() }}";
var PROFILE_ = "{{ $this->profile()->rol }}";
$(document).ready(function(){
 MostrarResumenCaja();
 ConfirmCierreCaja(TablaResumenCaja,'#lista_resumen_caja tbody');
 ConfirmarEliminadoDeCaja(TablaResumenCaja,'#lista_resumen_caja tbody');
});

function MostrarResumenCaja()
{
    TablaResumenCaja = $('#lista_resumen_caja').DataTable({
        language: SpanishDataTable(),
 retrieve:true,
 processing:true,
 responsive:true,
 "columnDefs": [{
        "searchable": false,
        "orderable": false,
        "targets": 0
    }],
 ajax:{
  url:RUTA+"clinica_farmacia/mostrar/aperturas/caja",
  method:"GET",
  dataSrc:"response"
 },
 columns:[
  {"data":null},
  {"data":null,render:function(dataestado){
     if(dataestado.estado_caja === 'sc')
     {
      return `<button class='btn btn-danger rounded btn-sm' id='eliminar_caja'> <i class='bx bxs-trash' ></i> </button>
      <button class='btn btn-success rounded btn-sm' id='confirm_caja'> <i class='bx bx-check'></i> </button>`
     }

     return `<button class='btn btn-danger rounded btn-sm' id='eliminar_caja'> <i class='bx bxs-trash' ></i> </button>
      <button class='btn btn-success rounded btn-sm' id='confirm_caja'> <i class='bx bx-check'></i> </button>`
  }},
  {"data":"fecha_apertura"},
  {"data":"fecha_cierre",render:function(fechacierre){
    if(fechacierre == null)
    {
      return `<span class='badge bg-danger'><b>---------------</b></span>`;
    }
    return fechacierre;
  }},
  {"data":"saldo_final_clinica",render:function(saldo_final_clinica){
    if(saldo_final_clinica == null)
   {
     return `<span class='badge bg-danger'><b>0.00</b></span>`
   }

   return  `<span class='badge bg-success'><b>`+saldo_final_clinica+`</b></span>`
  },className:"text-center"},
  {"data":"saldo_final_farmacia",render:function(saldo_final_farmacia){
    if(saldo_final_farmacia == null)
   {
     return `<span class='badge bg-danger'><b>0.00</b></span>`
   }

   return  `<span class='badge bg-success'><b>`+saldo_final_farmacia +`</b></span>`
  },className:"text-center"},
  {"data":"total_compras",render:function(total_compras){
    if(total_compras == null)
   {
     return `<span class='badge bg-danger'><b>0.00</b></span>`
   }

   return  `<span class='badge bg-success'><b>`+total_compras+`</b></span>`},className:"text-center"}
    ,
  {"data":"total_egreso",render:function(total_egreso){
    if(total_egreso == null)
   {
     return `<span class='badge bg-danger'><b>0.00</b></span>`
   }

   return  `<span class='badge bg-success'><b>`+total_egreso +`</b></span>`
  }},
  {"data":null,render:function(totaldata){
   

   let SaldoClinica = totaldata.saldo_final_clinica == null ? "0.00":totaldata.saldo_final_clinica;
   let SaldoFarmacia = totaldata.saldo_final_farmacia == null ? "0.00":totaldata.saldo_final_farmacia;
   let EgresoTotal = totaldata.total_egreso == null ? "0.00":totaldata.total_egreso;
   let TotalCompras = totaldata.total_compras == null ? "0.00":totaldata.total_compras;
   return  `<span class='badge bg-success'><b>`+((parseFloat(SaldoClinica) +parseFloat(SaldoFarmacia))-(parseFloat(EgresoTotal)+parseFloat(TotalCompras))).toFixed(2)+`</b></span>`
  }}
]
}).ajax.reload();
TablaResumenCaja.on('order.dt search.dt', function() {
    TablaResumenCaja.column(0, {
        search: 'applied',
        order: 'applied'
    }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
    });
}).draw();
}

/// eliminar la caja aperturada
function ConfirmarEliminadoDeCaja(Tabla,Tbody)
{
  $(Tbody).on('click','#eliminar_caja',function(){
    let fila = $(this).parents('tr');
    if(fila.hasClass("child")){
      fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();
    let fechaApertura = Data.fecha_apertura;

   Swal.fire({
    title: "Estas seguro de eliminar la caja en la fecha aperturada [ "+fechaApertura+" ]",
    text: "Al aceptar ya no podrás recuperar la caja aperturada, se borrar definitivamente!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminar!"
    }).then((result) => {
    if (result.isConfirmed) {
      EliminarCaja(Data.id_apertura_caja);
    }
    });
  });
}

/// eliminar caja
function EliminarCaja(iddata){
  $.ajax({
      url:RUTA+"caja/"+iddata+"/delete",
      method:"POST",
      dataType:"json",
      success:function(response){
        if(response.response === 'ok'){
          Swal.fire({
            title:"Correcto!",
            text:"Apertura de caja eliminado correctamente!",
            icon:"success"
          }).then(function(){
           MostrarResumenCaja();
          });
        }
      }
    });
}

/// confirmar el cierre de caja total
function ConfirmCierreCaja(Tabla,Tbody)
{
  $(Tbody).on('click','#confirm_caja',function(){
    let fila = $(this).parents('tr');

    if(fila.hasClass("child"))
    {
      fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();

    $.ajax({
      url:RUTA+"confirma/cierre/caja/por/completo/"+Data.id_apertura_caja,
      method:"POST",
      data:{
        _token:TOKEN
      },
      dataType:"json",
      success:function(response)
      {
        if(response.response == 1)
        {
          Swal.fire({
            title:"Última confirmación de cierre de caja",
            text:"La caja del día se a cerrado por completo!",
            icon:"success"
          }).then(function(){
            MostrarResumenCaja();
          });
        }
      }
    })

  });
}
</script>    
@show