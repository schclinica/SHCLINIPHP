@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Gestionar caja')

@section('css')
  <style>
    
    td.hide_me
    {
      display: none;
    }
    #lista_apertura_caja>thead>tr>th{
      background:  linear-gradient(to bottom, rgba(228,245,252,1) 0%,rgba(191,232,249,1) 50%,rgba(159,216,239,1) 51%,rgba(42,176,237,1) 100%);
    }
  </style>
@endsection

@section('contenido')
<div class="row">
    <div class="col-12">
        <div class="card" id="cajacard">
          <div class="card-header" style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
             <h5 class="text-white letra float-start">Aperturar Caja</h5>
          </div>
            <div class="card-body">
                 <div class="row">
                   <div class="col-xl-auto col-lg-auto col-md-auto col-12 mt-2" >
                   <div class="dropdown">
                      <button class="btn btn-success form-control dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Opciones <i class='bx bx-menu'></i>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="#" id="add_apertura_caja">Abri Caja <i class='bx bx-lock-open'></i></a></li>
                        <li><a class="dropdown-item" href="#" id="cerrar_caja" style="display: none">Cerrar Caja <i class='bx bxs-box'></i></a></li>
                         <li><a class="dropdown-item" href="#" id="eliminar_caja" style="display: none">Eliminar Caja <i class='bx bx-x'></i></i></a></li>
                      </ul>
                    </div>
                   </div>
               <div class="col-xl-auto col-lg-auto col-md-auto col-12 mt-2" id="menu_mov" style="display: none">
                    <div class="dropdown">
                      <button class="btn btn-primary form-control dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Registrar movimientos
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="#" id="gasto">Gastos <i class='bx bx-line-chart-down'></i></a></li>
                        <li><a class="dropdown-item" href="#" id="prestamo">Préstamos <i class='bx bxs-bank'></i></a></li>
                        <li><a class="dropdown-item" href="#" id="deposito">Depósitos <i class='bx bx-money'></i></a></li>
                      </ul>
                    </div>
                  </div> 
                  <div class="col-xl-auto col-lg-auto col-md-auto col-12 mt-2" id="imprimir" style="display: none">
                     <a href="" class="btn btn-outline-danger form-control"><b>Imprimir <i class="fas fa-print"></i></b></a>
                  </div>
                 </div>                 
                 <div class="row mt-2">
                    <div class="col-12">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"><b class="text-primary">Monto Inicial:</b></td>
                                    <td colspan="2" class="text-primary"><b > {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/. ' }} <span id="monto_inicial"></span></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><b class="text-success">Ingresos:</b></td>
                                    <td colspan="2" class="text-success"><b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/. ' }} <span id="ingresos"></span></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><b class="text-danger">Préstamos:</b></td>
                                    <td colspan="2" class="text-danger"><b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/. ' }} <span id="prestamos"></span></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><b class="text-info">Gastos:</b></td>
                                    <td colspan="2" class="text-info"><b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/. ' }} <span id="gastos"></span></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><b class="text-dark">Depósitos:</b></td>
                                    <td colspan="2" class="text-dark"><b>{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/. ' }} <span id="depositos"></span></b></td>
                                </tr>
                                 <tr>
                                    <td colspan="4"><h5 class="text-success">INGRESOS TOTALES:</h5></td>
                                    <td colspan="2"><h5 class="text-success">{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/. ' }} <span id="ingresos_totales"></span></h5></td>
                                </tr>
                                 <tr>
                                    <td colspan="4"><h5 class="text-danger">EGRESOS TOTALES:</h5></td>
                                    <td colspan="2"><h5 class="text-danger">{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/. ' }} <span id="egresos_totales"></span></h5></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><h5 class="text-primary">SALDO:</h5></td>
                                    <td colspan="2"><h5 class="text-primary">{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/. ' }} <span id="saldo"></span></h5></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><h5 class="text-info">MONTO INICIAL + SALDO:</h5></td>
                                    <td colspan="2"><h5 class="text-info">{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/. ' }} <span id="saldo_final"></span></h5></td>
                                </tr>
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
          <div class="form-floating">
            <input type="text" class="form-control" id="monto_apertura" name="monto_apertura" placeholder="0.00">
             <label for="monto_apertura"><b>Monto inicial {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} <span class="text-danger"> * </span></b></label>
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
  <div class="modal-dialog">
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
      </div>
      <div class="modal-footer border-2">
        <button class="btn btn-success rounded" id="save_gasto"><b>Guardar <i class='bx bxs-save' ></i></b></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_movimiento_prestamo">
  <div class="modal-dialog">
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
      </div>
      <div class="modal-footer border-2">
        <button class="btn btn-success rounded" id="save_prestamo"><b>Guardar <i class='bx bxs-save' ></i></b></button>
      </div>
    </div>
  </div>
</div>

{{--MODAL PARA REGISTRAR UN DEPOSITO A LA CAJA ---}}
<div class="modal fade" id="modal_movimiento_deposito">
  <div class="modal-dialog">
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
  var CajaId;
  $(document).ready(function(){
    
    showCajaAperturado();
    VerificarCajaAbierta();
    $('#gasto').click(function(evento){
        evento.preventDefault();
        $('#modal_movimiento_gasto').modal("show");
    });
     $('#prestamo').click(function(evento){
        evento.preventDefault();
          $('#modal_movimiento_prestamo').modal("show");
    });
     $('#deposito').click(function(evento){
        evento.preventDefault();
        $('#modal_movimiento_deposito').modal("show");
    });

    $('#add_apertura_caja').click(function(evento){
      evento.preventDefault();
        $('#modal_apertura_caja').modal("show");
    });
    $('#save_apertura_caja').click(function(evento){
        evento.preventDefault();
        if($('#monto_apertura').val().trim().length == 0){
            $('#monto_apertura').focus();
        }else{
            OpenCajaChica($('#monto_apertura'));
        }
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
    });

    $('#eliminar_caja').click(function(evento){
      evento.preventDefault();
       ConfirmarEliminadoCaja();
    });
    $('#cerrar_caja').click(function(evento){
      evento.preventDefault();
       ConfirmarCerradoCaja();
    });
    $('#imprimir').click(function(evento){
      evento.preventDefault();
      window.open(RUTA+"informe-cierre-caja/farmacia/"+CajaId,'blank_');
    });
    enter('descripcion_gasto','monto_gasto');
    enter('descripcion_prestamo','monto_prestamo')
    enter('descripcion_deposito','monto_deposito');
    enter('monto_apertura','monto_apertura')
  });

  /** VERIFICAMOS LA CAJA SI ESTA ABIERTA**/
  function VerificarCajaAbierta(){
    axios({
        url:RUTA+"verificar/caja/farmacia",
        method:"GET",
    }).then(function(response){
        if(response.data.caja != undefined){
            if(response.data.caja.length <=0){
                $('#menu_mov').hide(200);
                $('#imprimir').hide();
                $('#cerrar_caja').hide();
                $('#eliminar_caja').hide();
                $('#add_apertura_caja').show();
            }else{
                $('#menu_mov').show(200);
                $('#imprimir').show(200);
                $('#cerrar_caja').show();
                $('#add_apertura_caja').hide();
                $('#eliminar_caja').show();
            }
        }
    })
  }

  /// ABRIR LA CAJA
  function OpenCajaChica(SaldoInicial){
    let FormOpenCajaChica = new FormData();
    FormOpenCajaChica.append("token_",TOKEN);
    FormOpenCajaChica.append("saldo_inicial",SaldoInicial.val());
    axios({
        url:RUTA+"apertura/caja/store",
        method:"POST",
        data:FormOpenCajaChica
    }).then(function(response){
        if(response.data.error != undefined){
           Swal.fire({
             title:"MENSAJE DEL SISTEMA!!",
             text:response.data.error,
             icon:"error",
             target:document.getElementById('modal_apertura_caja')
           }); 
        }else{
            loading('#cajacard','#4169E1','chasingDots');
            setTimeout(() => {
              $('#cajacard').loadingModal('hide');
              $('#cajacard').loadingModal('destroy');
             $('#modal_apertura_caja').modal("hide");
              Swal.fire({
              title:"MENSAJE DEL SISTEMA!!",
              text:response.data.success,
              icon:"success",
              }).then(function(){
              showCajaAperturado();
              VerificarCajaAbierta();
              SaldoInicial.val("");
              });
            }, 1000); 
        }
    });
  }

  /// VER LA CAJA APERTURADO
  function showCajaAperturado(){
     axios({
        url:RUTA+"ver-caja-aperturado",
        method:"GET",
     }).then(function(response){
       if(response.data.error != undefined){
         Swal.fire({
           title:"MENSAJE DEL SISTEMA!!!",
           text:response.data.error,
           icon:"error"
         });
       }else{
        let caja = response.data.caja;
         let SaldoInicial = caja.saldo_inicial != null ? caja.saldo_inicial : "0.00";
         let IngresoVentas = caja.ingreso_ventas != null ? caja.ingreso_ventas:"0.00";
         let TotalPrestamos = caja.total_prestamos != null ? caja.total_prestamos:"0.00";
         let TotalGastos = caja.total_gastos != null ? caja.total_gastos:"0.00";
         let TotalDepositos = caja.total_depositos != null ? caja.total_depositos:"0.00";

         let IngresosTotal = parseFloat(IngresoVentas) + parseFloat(TotalDepositos);
         let EgresosTotal = parseFloat(TotalGastos) + parseFloat(TotalPrestamos);
         let Saldo = parseFloat(IngresosTotal) - parseFloat(EgresosTotal);

            CajaId = caja.id_caja;
            $('#monto_inicial').text(SaldoInicial) 
            $('#ingresos').text(IngresoVentas);
            $('#prestamos').text(TotalPrestamos);
            $('#gastos').text(TotalGastos);
            $('#depositos').text(TotalDepositos); 
            $('#ingresos_totales').text(parseFloat(IngresosTotal).toFixed(2));
            $('#egresos_totales').text(parseFloat(EgresosTotal).toFixed(2));
            $('#saldo').text(Saldo.toFixed(2));
            $('#saldo_final').text((parseFloat(SaldoInicial) +parseFloat(Saldo)).toFixed(2));
       }
     })
  }
  /** REGISTRAR EL GASTO**/
  function SaveGasto(DescripcionGasto,MontoGasto,FechaGsto){
  let FormMovimiento = new FormData();
  FormMovimiento.append("token_",TOKEN);
  FormMovimiento.append("desc_mov",DescripcionGasto.val());
  FormMovimiento.append("monto",MontoGasto.val());
  FormMovimiento.append("fecha",FechaGsto.val());
  axios({
    url:RUTA+"movimiento-farmacia/store/g",
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
         showCajaAperturado();
      })
    }
  })
}

/*REGISTRAR UN DEPOSITO*/
function SaveDeposito(DescripcionDeposito,MontoDeposito,FechaDeposito){
  let FormMovimientoDeposito = new FormData();
  FormMovimientoDeposito.append("token_",TOKEN);
  FormMovimientoDeposito.append("desc_mov",DescripcionDeposito.val());
  FormMovimientoDeposito.append("monto",MontoDeposito.val());
  FormMovimientoDeposito.append("fecha",FechaDeposito.val());
  axios({
    url:RUTA+"movimiento-farmacia/store/d",
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
         showCajaAperturado();
      })
    }
  })
}
/*REGISTRAR UN PRESTAMO*/
function SavePrestamo(DescripcionPrestamo,MontoPrestamo,FechaPrestamo){
  let FormMovimientoPrestamo = new FormData();
  FormMovimientoPrestamo.append("token_",TOKEN);
  FormMovimientoPrestamo.append("desc_mov",DescripcionPrestamo.val());
  FormMovimientoPrestamo.append("monto",MontoPrestamo.val());
  FormMovimientoPrestamo.append("fecha",FechaPrestamo.val());
  axios({
    url:RUTA+"movimiento-farmacia/store/p",
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
         showCajaAperturado();
      })
    }
  })
}
/// confirmar antes de cerrar la caja
function ConfirmarCerradoCaja(){
  Swal.fire({
  title: "ESTAS SEGURO?",
  text: "DESEAS CERRAR LA CAJA QUE ESTA APERTURADO?",
  icon: "question",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si, cerrar!"
  }).then((result) => {
  if (result.isConfirmed) {
   CerrarCaja();
  }
  });  
}
/// confirmar antes de eliminar la caja
function ConfirmarEliminadoCaja(){
 Swal.fire({
  title: "ESTAS SEGURO?",
  text: "DESEAS ELIMINAR LA CAJA QUE ESTA APERTURADO?",
  icon: "question",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si, eliminar!"
  }).then((result) => {
  if (result.isConfirmed) {
   EliminarCaja();
  }
  });
}

/// proceso de eliminar la caja
function EliminarCaja(){
  axios({
    url:RUTA+"eliminar-caja/farmacia",
    method:"POST",
  }).then(function(response){
      if(response.data.error != undefined){
         Swal.fire({
           title:"MENSAJE DEL SISTEMA!!!",
           text:response.data.error,
           icon:"error"
         });
      }else{
        Swal.fire({
          title:"MENSAJE DEL SISTEMA!!",
          text:response.data.success,
          icon:"success"
        }).then(function(){
          showCajaAperturado();
          VerificarCajaAbierta();
        });
      }
  })
}
///PROCESO DE CERRAR LA CAJA
function CerrarCaja(){
  axios({
    url:RUTA+"cerrar-caja/farmacia",
    method:"POST",
  }).then(function(response){
      if(response.data.error != undefined){
         Swal.fire({
           title:"MENSAJE DEL SISTEMA!!!",
           text:response.data.error,
           icon:"error"
         });
      }else{
        Swal.fire({
          title:"MENSAJE DEL SISTEMA!!",
          text:response.data.success,
          icon:"success"
        }).then(function(){
          showCajaAperturado();
          VerificarCajaAbierta();
        });
      }
  })
}
</script> 

@endsection