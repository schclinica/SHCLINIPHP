@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Gestionar caja')

@section('css')
  <style>
    
    td.hide_me
    {
      display: none;
    }
    #lista_apertura_caja>thead>tr>th{
      background:  linear-gradient(to bottom, rgba(228,245,252,1) 0%,rgba(191,232,249,1) 50%,rgba(159,216,239,1) 100%,rgba(42,176,237,1) 100%);
    }
  </style>
@endsection

@section('contenido')
<div class="row">
    <div class="col-12">
        <div class="card">
          <div class="card-header" style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 100%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
             <h5 class="text-white letra float-start">Apertura inicial de caja</h5>
            @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'Director' || $this->profile()->rol === "Admisión")
             <div class="dropdown float-end">
              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                aria-expanded="false">
                Registrar movimientos
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="#" id="gasto">Gastos <i class='bx bx-line-chart-down'></i></a></li>
                <li><a class="dropdown-item" href="#" id="prestamo">Préstamos <i class='bx bxs-bank'></i></a></li>
                <li><a class="dropdown-item" href="#" id="deposito">Depósitos <i class='bx bx-money'></i></a></li>
              </ul>
            </div>
            @endif
          </div>
            <div class="card-body">
               <div class="card-text mt-3">
                <b class="float-start">Total Saldo {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} :    </b> <span id="totalsaldo_" class="badge bg-primary mx-2"></span>
                @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_farmacia' || $this->profile()->rol === 'Farmacia' || $this->profile()->rol === "Admisión")
                <button class="btn_twiter float-end mb-3 col-xl-4 col-lg-5 col-md-6 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-2" id="add_apertura_caja"><b>Abir nueva caja <i class='bx bx-plus'></i></b></button>                    
                @endif
                 <div class="table-responsive">
                    <table class="table table-bordered table-striped nowrap responsive" id="lista_apertura_caja" style="width: 100%">
                     <thead>
                        <tr>
                            <th class="py-3 letra">#</th>
                            <th class="py-3 letra">Acciones</th>
                            <th class="py-3 letra">Quién aperturó la caja?</th>
                            <th class="py-3 letra">Fecha apertura</th>
                            <th class="py-3 letra">Saldo inicial {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                            <th class="py-3 letra">Fecha de cierre</th>
                            <th class="py-3 letra">Total Gastos {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                             <th class="py-3 letra">Total Préstamo {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                              <th class="py-3 letra">Total Depósito {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                            <th class="py-3 letra">Ingreso clínica {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
 
                            <th class="py-3 letra">Saldo Final{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                             
                        </tr>
                     </thead>
                     
                    </table>
                 </div>
               </div>
               
            </div>
        </div>
    </div>
</div>
{{--- MODAL PARA APERTURAR NUEVA CAJA ----}}
<div class="modal fade" id="modal_apertura_caja">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
        <h5 class="text-white">Nueva apertura de caja</h5>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="form_apertura_caja">
          <input type="hidden" name="_token" value="{{$this->Csrf_Token()}}">
          <div class="form-group">
            <label for="monto_apertura"><b>Monto inicial {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} <span class="text-danger"> * </span></b></label>
            <input type="text" class="form-control" id="monto_apertura" name="monto_apertura" placeholder="0.00">
          </div>
        </form>
      </div>
      <div class="modal-footer border-2">
        <button class="btn btn-success rounded" id="save_apertura_caja"><b>Guardar <i class='bx bxs-save' ></i></b></button>
      </div>
    </div>
  </div>
</div>
{{--- REGISTRAR EL GASTO---}}
<div class="modal fade" id="modal_movimiento_gasto">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
        <h5 class="text-white">Registrar Gasto {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</h5>
      </div>
      <div class="modal-body">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="descripcion_gasto" name="descripcion_gasto" placeholder="Descripción del gasto">
            <label for="descripcion_gasto"><b>Indicar el motivo del gasto <span class="text-danger"> * </span></b></label>
          </div>
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="monto_gasto" name="monto_gasto" placeholder="0.00">
            <label for="monto_gasto"><b>Monto gasto {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} <span class="text-danger"> * </span></b></label>
          </div>
          <div class="form-floating">
            <input type="text" class="form-control" id="fecha_gasto" name="fecha_gasto" placeholder="0.00"
            value="{{$this->FechaActual("Y-m-d")}}">
            <label for="fecha_gasto"><b>Fecha <span class="text-danger"> * </span></b></label>
          </div>
        <br>
       <table class="table table-bordered table-striped table-hover table-sm nowrap responsive mt-2" id="lista_gastos" style="width: 100%">
         <thead style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
            <tr>
               <th class="py-3 letra text-white">Descripción Gasto</th>
               <th class="py-3 letra text-white">Monto {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
               <th class="py-3 letra text-white">Acciones</th>
            </tr>
         </thead>
       </table>
      </div>
      <div class="modal-footer border-2">
        <button class="btn btn-success rounded" id="save_gasto"><b>Guardar <i class='bx bxs-save' ></i></b></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_movimiento_prestamo">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
        <h5 class="text-white">Registrar Préstamo {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</h5>
      </div>
      <div class="modal-body">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="descripcion_prestamo" name="descripcion_prestamo" placeholder="Descripción del gasto">
            <label for="descripcion_prestamo"><b>Indicar el motivo del préstamo <span class="text-danger"> * </span></b></label>
          </div>
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="monto_prestamo" name="monto_prestamo" placeholder="0.00">
            <label for="monto_prestamo"><b>Monto préstamo {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} <span class="text-danger"> * </span></b></label>
          </div>
          <div class="form-floating">
            <input type="text" class="form-control" id="fecha_prestamo" name="fecha_prestamo" placeholder="0.00"
            value="{{$this->FechaActual("Y-m-d")}}">
            <label for="fecha_prestamo"><b>Fecha <span class="text-danger"> * </span></b></label>
          </div>
          <br>
        <table class="table table-bordered table-striped table-hover table-sm nowrap responsive" id="lista_prestamos" style="width: 100%">
         <thead style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
            <tr>
               <th class="py-3 letra text-white">Descripción Préstamo</th>
               <th class="py-3 letra text-white">Monto {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
               <th class="py-3 letra text-white">Acciones</th>
            </tr>
         </thead>
       </table>
      </div>
      <div class="modal-footer border-2">
        <button class="btn btn-success rounded" id="save_prestamo"><b>Guardar <i class='bx bxs-save' ></i></b></button>
      </div>
    </div>
  </div>
</div>

{{--MODAL PARA REGISTRAR UN DEPOSITO A LA CAJA ---}}
<div class="modal fade" id="modal_movimiento_deposito">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
        <h5 class="text-white">Registrar Depósito {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</h5>
      </div>
      <div class="modal-body">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="descripcion_deposito" name="descripcion_deposito" placeholder="Descripción del gasto">
            <label for="descripcion_deposito"><b>Indicar el motivo del Depósito <span class="text-danger"> * </span></b></label>
          </div>
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="monto_deposito" name="monto_deposito" placeholder="0.00">
            <label for="monto_deposito"><b>Monto Depósito {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} <span class="text-danger"> * </span></b></label>
          </div>
          <div class="form-floating">
            <input type="text" class="form-control" id="fecha_deposito" name="fecha_deposito" placeholder="0.00"
            value="{{$this->FechaActual("Y-m-d")}}">
            <label for="fecha_deposito"><b>Fecha <span class="text-danger"> * </span></b></label>
          </div>

       <br>
          <table class="table table-bordered table-striped table-hover table-sm nowrap responsive mt-2" id="lista_depositos"
            style="width: 100%">
            <thead
              style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
              <tr>
                <th class="py-3 letra text-white">Descripción Depósito</th>
                <th class="py-3 letra text-white">Monto {{ count($this->BusinesData()) == 1 ?
                  $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                <th class="py-3 letra text-white">Acciones</th>
              </tr>
            </thead>
          </table>
      </div>
      <div class="modal-footer border-2">
        <button class="btn btn-success rounded" id="save_deposito"><b>Guardar <i class='bx bxs-save' ></i></b></button>
      </div>
    </div>
  </div>
</div>

{{---MODAL PARA EDITAR LA CAJA ---}}
<div class="modal fade" id="modal_editar_apertura_caja">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #4169E1">
        <h5 class="text-white">Editar la apertura de caja</h5>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="form_apertura_caja_editar">
          <input type="hidden" name="_token" value="{{$this->Csrf_Token()}}">
          <div class="form-group">
            <label for="monto_apertura_editar"><b>Monto inicial {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} <span class="text-danger"> * </span></b></label>
            <input type="text" class="form-control" id="monto_apertura_editar" name="monto_apertura_editar" placeholder="0.00">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success rounded" id="update_apertura_caja"><b>Guardar <i class='bx bxs-save' ></i></b></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_add_apertura_caja_farmacia">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #4169E1">
        <h5 class="text-white">Nueva apertura de caja</h5>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="form_apertura_caja_far">
          <input type="hidden" name="_token" value="{{$this->Csrf_Token()}}">
          <div class="form-group">
            <label for="monto_apertura_far"><b>Monto inicial {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} <span class="text-danger"> * </span></b></label>
            <input type="text" class="form-control" id="monto_apertura_far" name="monto_apertura_far" placeholder="0.00">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success rounded" id="far_apertura_caja"><b>Guardar <i class='bx bxs-save' ></i></b></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal_alerta_para_caja">
    <div class="modal-dialog">
       <div class="modal-content">
        <div class="modal-header" style="background:linear-gradient(to bottom, rgba(255,103,15,1) 32%,rgba(255,103,15,1) 32%,rgba(255,103,15,1) 62%);">
            <h5 class="text-white letra" id="text_precios"><b>Mensaje del sistema!!!!</b></h5>
        </div>
        <div class="modal-body" >
          <b>Antes de Continuar debes de aperturar una caja!!!</b>  
       </div>
       
    </div>
</div>
</div>
@endsection
@section('js')
<script src="{{ URL_BASE }}public/js/control.js"></script>
<script src="{{ URL_BASE }}public/js/egresos.js"></script>
<script>
  var RUTA = "{{ URL_BASE }}" // la url base del sistema
  var TOKEN = "{{ $this->Csrf_Token() }}";
  var PROFILE_ = "{{ $this->profile()->rol }}";
  var ID_CAJA;
  var TablaAperturaCaja ;
  var TablaListaGastos,TablaListaPrestamos,TablaListaDepositos;
  var MOVIMIENTOID;
$(document).ready(function(){
  let MontoApertura = $('#monto_apertura'); 
  let MontoAperturaEditar = $('#monto_apertura_editar'); 
  let MontoAperturaFar = $('#monto_apertura_far');
  mostrarAperturaCajasInicial();
  ConfirmarCerrarCajaAperturada(TablaAperturaCaja,'#lista_apertura_caja tbody');
  VerInformeCajaCierre(TablaAperturaCaja,'#lista_apertura_caja tbody');
  EditarCajaCierre(TablaAperturaCaja,'#lista_apertura_caja tbody');
  ConfirmarEliminadoCajaAperturada(TablaAperturaCaja,'#lista_apertura_caja tbody');

  enter("descripcion_gasto","monto_gasto");
  enter("descripcion_prestamo","monto_prestamo");
  enter("descripcion_deposito","monto_deposito");

  if(PROFILE_ === "Admisión"){
  showGastos();
  ConfirmaEliminadoGasto(TablaListaGastos,'#lista_gastos tbody');
  
  
  showPrestamos();
  ConfirmaEliminadoPrestamo(TablaListaPrestamos,'#lista_prestamos tbody');
  
  showDepositos();
  ConfirmaEliminadoDeposito(TablaListaDepositos,'#lista_depositos tbody');
  }
$('#add_apertura_caja').click(function(){
 $('#modal_apertura_caja').modal("show")
});
$('#deposito').click(function(){
   VerifyCajaAbierta("d");
});
/// abrir modal de registrar un prestamo
$('#prestamo').click(function(){
  VerifyCajaAbierta("p");
})

// abrir la ventana del gasto
$('#gasto').click(function(){
  VerifyCajaAbierta("g");
});
$('#save_gasto').click(function(){
  if($('#descripcion_gasto').val().trim().length == 0){
    $('#descripcion_gasto').focus();
  }else{
    if($('#monto_gasto').val().trim().length == 0){
       $('#monto_gasto').focus();
    }else{
      SaveGasto($('#descripcion_gasto'),$('#monto_gasto'),$('#fecha_gasto'));
    }
  }
});

$('#save_deposito').click(function(){
  if($('#descripcion_deposito').val().trim().length == 0){
    $('#descripcion_deposito').focus();
  }else{
    if($('#monto_deposito').val().trim().length == 0){
       $('#monto_deposito').focus();
    }else{
      SaveDeposito($('#descripcion_deposito'),$('#monto_deposito'),$('#fecha_deposito'));
    }
  }
});

$('#save_prestamo').click(function(){
  if($('#descripcion_prestamo').val().trim().length == 0){
    $('#descripcion_prestamo').focus();
  }else{
    if($('#monto_prestamo').val().trim().length == 0){
       $('#monto_prestamo').focus();
    }else{
      SavePrestamo($('#descripcion_prestamo'),$('#monto_prestamo'),$('#fecha_prestamo'));
    }
  }
})
MontoApertura.keyup(function (){
        this.value = (this.value + '').replace(/[^0-9]/g, '');
 });

MontoApertura.keypress(function(evento){
 if(evento.which == 13){
  evento.preventDefault();
  if($(this).val().trim().length == 0){
    $(this).focus();
  }else{
    saveAperturaCaja();
  }
 }
});
MontoAperturaFar.keypress(function(evento){
 if(evento.which == 13){
  evento.preventDefault();
  if($(this).val().trim().length == 0){
    $(this).focus();
  }else{
    updateAperturaCajaExists()
  }
 }
});
MontoAperturaEditar.keypress(function(evento){
 if(evento.which == 13){
  evento.preventDefault();
  if($(this).val().trim().length == 0){
    $(this).focus();
  }else{
    updateAperturaCaja();
  }
 }
});
$('#save_apertura_caja').click(function(){
  if(MontoApertura.val().trim().length == 0)
  {
    MontoApertura.focus();
  }else{
    saveAperturaCaja();
  }
});

$('#far_apertura_caja').click(function(){
  if(MontoAperturaFar.val().trim().length == 0)
  {
    MontoAperturaFar.focus();
  }else{
    updateAperturaCajaExists()
  }
});
$('#update_apertura_caja').click(function(){
  if(MontoAperturaEditar.val().trim().length == 0)
  {
    MontoAperturaEditar.focus();
  }else{
    updateAperturaCaja();
  }
});

});

/// mostrar todas las aperturas de caja
function mostrarAperturaCajasInicial()
{
TablaAperturaCaja = $('#lista_apertura_caja').DataTable({
  "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
            
            // converting to interger to find total
            var intVal = function ( i ) {
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1 :
            typeof i === 'number' ?
            i : 0;
            };
            
            // computing column Total the complete result
            var TotalSaldo= api
            .column(10)
            .data()
            .reduce( function (a, b) {
            return intVal(a) + intVal(b);
            }, 0 );
            // Update footer by showing the total with the reference of the column index
            $('#totalsaldo_').html("<b>"+TotalSaldo.toFixed(2)+"</b>");
            
   },  
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
  {"data":null,render:function(dataresp){
   if(dataresp.estado_clinica === 'c'){
    return `<button class='btn btn-danger rounded btn-sm' id='eliminar_caja'> <i class='bx bxs-trash'></i></button>
    <button class='btn btn-info rounded btn-sm' id='print_caja'> <i class='bx bxs-file-pdf'></i> </button>`;
    }
     return `<button class='btn btn-warning rounded btn-sm' id='editar_caja'><i class='bx bxs-pencil'></i></button>
    <button class='btn btn-danger rounded btn-sm' id='eliminar_caja'> <i class='bx bxs-trash' ></i> </button>
    <button class='btn btn-primary rounded btn-sm' id='cerrar_caja'> <i class='bx bx-key' ></i> </button>`;
  }},
  {"data":null,render:function(dato){
    return '<span class="badge bg-primary">'+dato.name.toUpperCase()+"["+dato.rol.toUpperCase()+"]"+'</span>';
  }},
  {"data":null,render:function(cajadata){
    if(cajadata.fecha_apertura_clinica == null){
        return `<span class='badge bg-danger'>---------------</span>`;
      }
      return cajadata.fecha_apertura_clinica; 
  }},
  {"data":null,render:function(caja){
     if(caja.saldo_inicial_clinica == null){
        return `<span class='badge bg-danger'><b>0.00</b></span>`
     }
       return `<span class='badge bg-success'><b>`+caja.saldo_inicial_clinica+`</b></span>`
  }},
  {"data":null,render:function(caja){
   if(caja.fecha_cierre_clinica == null)
    {
     return `<span class='badge bg-danger'><b>--------------------</b></span>`
    }

   return caja.fecha_cierre_clinica;
  }},
  {"data":"total_egreso_clinica",render:function(total_egreso_clinica){
   if(total_egreso_clinica == null)
   {
     return `<span class='badge bg-danger'><b>0.00</b></span>`
   }

   return `<span class='badge bg-danger'><b>`+total_egreso_clinica+`</b></span>`;

  }},
  {"data":"total_prestamo",render:function(total_prestamo){
   if(total_prestamo == null)
   {
     return `<span class='badge bg-danger'><b>0.00</b></span>`
   }

   return `<span class='badge bg-danger'><b>`+total_prestamo+`</b></span>`;

  }},
    {"data":"total_deposito",render:function(total_deposito){
   if(total_deposito == null)
   {
     return `<span class='badge bg-danger'><b>0.00</b></span>`
   }

   return `<span class='badge bg-danger'><b>`+total_deposito+`</b></span>`;

  }},
  {"data":"ingreso_clinica",render:function(ingreso_clinica){
   if(ingreso_clinica == null)
   {
     return `<span class='badge bg-danger'><b>0.00</b></span>`
   }

   return ingreso_clinica;

  }},
  {"data":"saldo_final_clinica",render:function(saldo_final_clinica){
   if(saldo_final_clinica == null)
   {
     return `<span class='badge bg-danger'><b>0.00</b></span>`
   }

   return '<span class="badge bg-primary">'+saldo_final_clinica+'</span>';

  }},
  
 ],
 
  
}).ajax.reload();

 /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
 TablaAperturaCaja.on('order.dt search.dt', function() {
    TablaAperturaCaja.column(0, {
        search: 'applied',
        order: 'applied'
    }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
    });
}).draw();
}

/// apertura de caja
function saveAperturaCaja()
{
  let respuesta = crud(RUTA+"clinica_farmacia/apertura_caja",'form_apertura_caja');
 
  if(respuesta === "error-caja"){
    Swal.fire({
      title:"MENSAJE DEL SISTEMA!!!",
      text:"ERROR, NO PUEDES APERTURAR UNA NUEVA CAJA, YA QUE TIENES UNA CAJA ABIERTA!!!",
      icon:"error",
      target:document.getElementById('modal_apertura_caja')
    });
    return;
  }
  if(respuesta  == 1)
  {
    Swal.fire({
      title:"Mensaje del sistema!",
      text:"La apertura se a iniciado sin problemas!",
      icon:"success",
      target:document.getElementById('modal_apertura_caja')
    }).then(function(){
      $('#form_apertura_caja')[0].reset();
      mostrarAperturaCajasInicial();
    });
  }else{
    Swal.fire({
      title:"Mensaje del sistema!",
      text:"Error al registrar nueva apertura de caja, para aperturar una nueva, se recomienda cerrar la caja actual aperturada!",
      icon:"error",
      target:document.getElementById('modal_apertura_caja')
    }).then(function(){
      mostrarAperturaCajasInicial();
    })
  }
}
/// modificar la apertura de caja
function updateAperturaCaja()
{
  
  let respuesta = crud(RUTA+"clinica_farmacia/apertura_caja/update/"+ID_CAJA,'form_apertura_caja_editar');
 
  if(respuesta  == 1)
  {
    Swal.fire({
      title:"Mensaje del sistema!",
      text:"La apertura a sido modificado sin problemas!",
      icon:"success",
      target:document.getElementById('modal_editar_apertura_caja')
    }).then(function(){
      $('#form_apertura_caja_editar')[0].reset();
      mostrarAperturaCajasInicial();
    });
  }else{
    Swal.fire({
      title:"Mensaje del sistema!",
      text:"Error al modificar la apertura de caja!",
      icon:"error",
      target:document.getElementById('modal_editar_apertura_caja')
    })
  }
}

function updateAperturaCajaExists()
{
 
  let respuesta = crud(RUTA+"clinica/apertura_caja/farmacia/"+ID_CAJA,'form_apertura_caja_far');
 
  if(respuesta  == 1)
  {
    Swal.fire({
      title:"Mensaje del sistema!",
      text:"La apertura de caja a sido registrada sin problemas!",
      icon:"success",
      target:document.getElementById('modal_add_apertura_caja_farmacia')
    }).then(function(){
      $('#form_apertura_caja_far')[0].reset();
      mostrarAperturaCajasInicial();
    });
  }else{
    Swal.fire({
      title:"Mensaje del sistema!",
      text:"Error al aperturar la caja!",
      icon:"error",
      target:document.getElementById('modal_add_apertura_caja_farmacia')
    })
  }
}

/// proceso para eliminar la caja aperturada

function eliminarAperturaCaja()
{
   $.ajax({
    url:RUTA+"eliminar/caja_aperturada/"+ID_CAJA,
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
      title:"Mensaje del sistema!",
      text:"La apertura a sido eliminado sin problemas!",
      icon:"success",
      
    }).then(function(){
      
      mostrarAperturaCajasInicial();
    }); 
    }else{
      Swal.fire({
      title:"Mensaje del sistema!",
      text:"Error al eliminar la caja aperturada!",
      icon:"error",
    
    })
    }
    },err:function(error){
      Swal.fire({
      title:"Mensaje del sistema!",
      text:"Error al eliminar la caja aperturada!",
      icon:"error",
    
    });
    }
   })
}


/// cerrar la caja
function ConfirmarCerrarCajaAperturada(Tabla,Tbody)
{
 $(Tbody).on('click','#cerrar_caja',function(){
  let fila = $(this).parents('tr');

  if(fila.hasClass('child'))
  {
    fila = fila.prev();
  }

  let Data = Tabla.row(fila).data();
  let Saldo = (PROFILE_ === 'Director' || PROFILE_ === 'Admisión') ? Data.saldo_inicial_clinica : Data.saldo_inicial_farmacia;
  let fecha = (PROFILE_ === 'Director' || PROFILE_ === 'Admisión') ? Data.fecha_apertura_clinica :Data.fecha_apertura_farmacia;
  ID_CAJA = Data.id_apertura_caja;

  Swal.fire({
  title: "Estas seguro de cerrar la caja con saldo inicial de : "+Saldo+" con fecha : "+fecha+" ?",
  text: "Al cerrar la caja, ya podrás ver todos los datos restantes de ingresos y egresos completados en la caja aperturada, a la vez podrás crear una nueva apertura de caja!",
  icon: "question",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si, cerrar!",
  
}).then((result) => {
  if (result.isConfirmed) {
     CerrarCaja(ID_CAJA);
  }
});
   
 });
}

/// confirma eliminado de caja
function ConfirmarEliminadoCajaAperturada(Tabla,Tbody)
{
 $(Tbody).on('click','#eliminar_caja',function(){
  let fila = $(this).parents('tr');

  if(fila.hasClass('child'))
  {
    fila = fila.prev();
  }

  let Data = Tabla.row(fila).data();
  let Saldo = Data.saldo_inicial_clinica;
  let fecha = Data.fecha_apertura_clinica ;
  ID_CAJA = Data.id_apertura_caja;

  Swal.fire({
  title: "Estas seguro de eliminar la caja con saldo inicial de : "+Saldo+" con fecha : "+fecha+" ?",
  text: "Al eliminar la caja aperturada, ya no podrás recuperarlo y se restará su saldo!",
  icon: "question",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si, eliminar!",
  
}).then((result) => {
  if (result.isConfirmed) {
     eliminarAperturaCaja();
  }
});
   
 });
}

/// ver informe de cierre de caja
function VerInformeCajaCierre(Tabla,Tbody)
{
 $(Tbody).on('click','#print_caja',function(){
  let fila = $(this).parents('tr');

  if(fila.hasClass('child'))
  {
    fila = fila.prev();
  }

  let Data = Tabla.row(fila).data();
 
  ID_CAJA = Data.id_apertura_caja;
   
  window.open(RUTA+"informe/cierre_de_caja/"+ID_CAJA,"_blank");
   
 });
}

// editar la caja
function EditarCajaCierre(Tabla,Tbody)
{
 $(Tbody).on('click','#editar_caja',function(){
  let fila = $(this).parents('tr');

  if(fila.hasClass('child'))
  {
    fila = fila.prev();
  }

  let Data = Tabla.row(fila).data();
 
  ID_CAJA = Data.id_apertura_caja;
   
  if((PROFILE_ === 'Director' || PROFILE_ ==="Admisión") && Data.saldo_inicial_clinica != null){
  $('#monto_apertura_editar').val(Data.saldo_inicial_clinica);
  $('#modal_editar_apertura_caja').modal("show");
  }else{
    if((PROFILE_ === 'Director' || PROFILE_ ==="Admisión") && Data.saldo_inicial_clinica == null)
    {
      $('#monto_apertura_far').val(Data.saldo_inicial_clinica);
      $('#modal_add_apertura_caja_farmacia').modal("show");
    }else{
      if((PROFILE_ === 'admin_farmacia' || PROFILE_ === 'Farmacia') && Data.saldo_inicial_farmacia != null){
       $('#monto_apertura_editar').val(Data.saldo_inicial_farmacia);
       $('#modal_editar_apertura_caja').modal("show");
  }else{
    if((PROFILE_ === 'admin_farmacia' || PROFILE_ === 'Farmacia') && Data.saldo_inicial_farmacia == null)
    {
      $('#monto_apertura_far').val(Data.saldo_inicial_farmacia);
      $('#modal_add_apertura_caja_farmacia').modal("show");
    }
  }
    }
  }
   
 });
}

/// proceso para cerrar la caja aperturada 
function CerrarCaja(id)
{
   $.ajax({
    url:RUTA+"cerrar/caja/aperturada/"+id,
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
        title:"Mensaje del sistema!",
        text:"LA CAJA HA SIDO CERRADO CORRECTAMENTE!!",
        icon:"success"
      }).then(function(){
        mostrarAperturaCajasInicial();
      });
     }
    },err:function(error)
    {
      Swal.fire({
        title:"Mensaje del sistema!",
        text:"ERROR AL CERRAR LA CAJA!!!",
        icon:"error"
      });
    }
   })
}

function SaveGasto(DescripcionGasto,MontoGasto,FechaGsto){
  let FormMovimiento = new FormData();
  FormMovimiento.append("token_",TOKEN);
  FormMovimiento.append("desc_mov",DescripcionGasto.val());
  FormMovimiento.append("monto",MontoGasto.val());
  FormMovimiento.append("fecha",FechaGsto.val());
  axios({
    url:RUTA+"movimiento/store/g",
    method:"POST",
    data:FormMovimiento
  }).then(function(response){
    if(response.data.error != undefined){
       Swal.fire({
         title:"MENSAJE DEL SISTEMA!!",
         text:response.data.error,
         icon:"error",
         target:document.getElementById('modal_movimiento_gasto')
       });
    }else{
      DescripcionGasto.focus();
      Swal.fire({
        title:"MENSAJE DEL SISTEMA!!",
        text:response.data.success,
        icon:"success",
        target:document.getElementById('modal_movimiento_gasto')
      }).then(function(){
         DescripcionGasto.val("");MontoGasto.val("");
         FechaGsto.val("{{$this->FechaActual('Y-m-d')}}");
         showGastos();
      })
    }
  })
}
function SavePrestamo(DescripcionPrestamo,MontoPrestamo,FechaPrestamo){
  let FormMovimientoPrestamo = new FormData();
  FormMovimientoPrestamo.append("token_",TOKEN);
  FormMovimientoPrestamo.append("desc_mov",DescripcionPrestamo.val());
  FormMovimientoPrestamo.append("monto",MontoPrestamo.val());
  FormMovimientoPrestamo.append("fecha",FechaPrestamo.val());
  axios({
    url:RUTA+"movimiento/store/p",
    method:"POST",
    data:FormMovimientoPrestamo
  }).then(function(response){
    if(response.data.error != undefined){
       Swal.fire({
         title:"MENSAJE DEL SISTEMA!!",
         text:response.data.error,
         icon:"error",
         target:document.getElementById('modal_movimiento_prestamo')
       });
    }else{
      DescripcionPrestamo.focus();
      Swal.fire({
        title:"MENSAJE DEL SISTEMA!!",
        text:response.data.success,
        icon:"success",
        target:document.getElementById('modal_movimiento_prestamo')
      }).then(function(){
         DescripcionPrestamo.val("");MontoPrestamo.val("");
         FechaPrestamo.val("{{$this->FechaActual('Y-m-d')}}");
         showPrestamos();
      })
    }
  })
}
/// registrar deposito
function SaveDeposito(DescripcionDeposito,MontoDeposito,FechaDeposito){
  let FormMovimientoDeposito = new FormData();
  FormMovimientoDeposito.append("token_",TOKEN);
  FormMovimientoDeposito.append("desc_mov",DescripcionDeposito.val());
  FormMovimientoDeposito.append("monto",MontoDeposito.val());
  FormMovimientoDeposito.append("fecha",FechaDeposito.val());
  axios({
    url:RUTA+"movimiento/store/d",
    method:"POST",
    data:FormMovimientoDeposito
  }).then(function(response){
    if(response.data.error != undefined){
       Swal.fire({
         title:"MENSAJE DEL SISTEMA!!",
         text:response.data.error,
         icon:"error",
         target:document.getElementById('modal_movimiento_deposito')
       });
    }else{
      DescripcionDeposito.focus();
      Swal.fire({
        title:"MENSAJE DEL SISTEMA!!",
        text:response.data.success,
        icon:"success",
        target:document.getElementById('modal_movimiento_deposito')
      }).then(function(){
         DescripcionDeposito.val("");MontoDeposito.val("");
         FechaDeposito.val("{{$this->FechaActual('Y-m-d')}}");
         showDepositos();
      })
    }
  })
}
 function VerifyCajaAbierta(tipo){
            axios({
                url:RUTA+"verificar/caja/clinica",
                method:"GET",
            }).then(function(response){
                
                if(response.data.caja != undefined){
                    if(response.data.caja.length <= 0){
                       $('#modal_alerta_para_caja').modal("show")
                    }else{
                     if(tipo==='g'){
                      $('#modal_movimiento_gasto').modal("show");
                     }else{
                       if(tipo === 'p'){
                         $('#modal_movimiento_prestamo').modal("show");
                       }else{
                         $('#modal_movimiento_deposito').modal("show");
                       }
                     }
                  }
                } 
          })
   }

   /// VER EL LISTADO DE GASTOS
   function showGastos(){
     TablaListaGastos = $('#lista_gastos').DataTable({
       language:SpanishDataTable(),
       retrieve:true,
       ajax:{
        url:RUTA+"movimientos/clinica/g",
        method:"GET",
        dataSrc:"movimientos"
       },
       columns:[
         {"data":"descripcion_movimiento",render:function(desc){
           return desc.toUpperCase();
         }},
         {"data":"monto_movimiento"},
         {"data":null,render:function(){
           return `<button class="btn btn-outline-danger btn-sm" id="eliminar"><i class='bx bx-x'></i></button>
                   <button class="btn btn-outline-warning btn-sm" id="editar"><i class='bx bxs-edit-alt'></i></button>
            `;
         },className:"text-center"}
       ]
     }).ajax.reload();
   }

   /// VER EL LISTADO DE GASTOS
   function showPrestamos(){
     TablaListaPrestamos = $('#lista_prestamos').DataTable({
       language:SpanishDataTable(),
       retrieve:true,
       ajax:{
        url:RUTA+"movimientos/clinica/p",
        method:"GET",
        dataSrc:"movimientos"
       },
       columns:[
         {"data":"descripcion_movimiento",render:function(desc){
           return desc.toUpperCase();
         }},
         {"data":"monto_movimiento"},
         {"data":null,render:function(){
           return `<button class="btn btn-outline-danger btn-sm" id="eliminar"><i class='bx bx-x'></i></button>
                   <button class="btn btn-outline-warning btn-sm" id="editar"><i class='bx bxs-edit-alt'></i></button>
            `;
         },className:"text-center"}
       ]
     }).ajax.reload();
   }

   /// VER EL LISTADO DE DEPOSITOS
   function showDepositos(){
     TablaListaDepositos = $('#lista_depositos').DataTable({
       language:SpanishDataTable(),
       retrieve:true,
       ajax:{
        url:RUTA+"movimientos/clinica/d",
        method:"GET",
        dataSrc:"movimientos"
       },
       columns:[
         {"data":"descripcion_movimiento",render:function(desc){
           return desc.toUpperCase();
         }},
         {"data":"monto_movimiento"},
         {"data":null,render:function(){
           return `<button class="btn btn-outline-danger btn-sm" id="eliminar"><i class='bx bx-x'></i></button>
                   <button class="btn btn-outline-warning btn-sm" id="editar"><i class='bx bxs-edit-alt'></i></button>
            `;
         },className:"text-center"}
       ]
     }).ajax.reload();
   }

   /// ELIMINAR EL GASTO DE LA LISTA CON RESPECTO A UNA CAJA ABIERTA
   function ConfirmaEliminadoGasto(Tabla,Tbody){
      $(Tbody).on('click','#eliminar',function(){
         let fila = $(this).parents("tr");

         if(fila.hasClass("child")){
           fila = fila.prev();
         }

         let Data = Tabla.row(fila).data();
         MOVIMIENTOID = Data.id_movimiento;
     Swal.fire({
        title: "DESEAS ELIMINAR EL GASTO SELECCIONADO?",
        text: "Al Aceptar se quitará de lista, y ya no se sumará como un gasto de la caja!!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        target:document.getElementById('modal_movimiento_gasto')
        }).then((result) => {
        if (result.isConfirmed) {
         EliminarMovimiento(MOVIMIENTOID,'modal_movimiento_gasto','g');
        }
        });
      });
   }
    /// ELIMINAR EL PRESTAMO DE LA LISTA CON RESPECTO A UNA CAJA ABIERTA
   function ConfirmaEliminadoPrestamo(Tabla,Tbody){
      $(Tbody).on('click','#eliminar',function(){
         let fila = $(this).parents("tr");

         if(fila.hasClass("child")){
           fila = fila.prev();
         }

         let Data = Tabla.row(fila).data();
         MOVIMIENTOID = Data.id_movimiento;
     Swal.fire({
        title: "DESEAS ELIMINAR EL PRESTAMO SELECCIONADO?",
        text: "Al Aceptar se quitará de lista, y ya no se sumará como un préstamo de la caja!!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        target:document.getElementById('modal_movimiento_prestamo')
        }).then((result) => {
        if (result.isConfirmed) {
         EliminarMovimiento(MOVIMIENTOID,'modal_movimiento_prestamo','p');
        }
        });
      });
   }

    /// ELIMINAR EL DEPOSITO DE LA LISTA CON RESPECTO A UNA CAJA ABIERTA
   function ConfirmaEliminadoDeposito(Tabla,Tbody){
      $(Tbody).on('click','#eliminar',function(){
         let fila = $(this).parents("tr");

         if(fila.hasClass("child")){
           fila = fila.prev();
         }

         let Data = Tabla.row(fila).data();
         MOVIMIENTOID = Data.id_movimiento;
     Swal.fire({
        title: "DESEAS ELIMINAR EL DEPOSITO SELECCIONADO?",
        text: "Al Aceptar se quitará de lista, y ya no se sumará como un depósito a la caja!!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        target:document.getElementById('modal_movimiento_deposito')
        }).then((result) => {
        if (result.isConfirmed) {
         EliminarMovimiento(MOVIMIENTOID,'modal_movimiento_deposito','d');
        }
        });
      });
   }

   /// eliminar movimiento
   function EliminarMovimiento(id,modal,tipo){
     let FormDeleteMovimiento = new FormData();
     FormDeleteMovimiento.append("token_",TOKEN);
     axios({
       url:RUTA+"movimiento/eliminar/"+id,
       method:"POST",
       data:FormDeleteMovimiento
     }).then(function(response){
        if(response.data.error != undefined){
           Swal.fire({
             title:"MENSAJE DEL SISTEMA!!",
             text:response.Data.error,
             icon:"error",
             target:document.getElementById(modal)
           });
        }else{
          Swal.fire({
             title:"MENSAJE DEL SISTEMA!!",
             text:response.data.success,
             icon:"success",
             target:document.getElementById(modal)
          }).then(function(){
            if(tipo=== 'g'){
               showGastos();
            }else{
              if(tipo === "p"){
                showPrestamos();
              }else{
                showDepositos();
              }
            }
          });
        }
     })
   }
</script> 

@endsection