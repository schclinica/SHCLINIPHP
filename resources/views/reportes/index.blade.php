@extends($this->Layouts("dashboard"))

@section("title_dashboard","Reportes")

@section('css')
  <style>
     #reporte_ingresos_mensual>thead>tr>th, #reporte_ingresos_anio>thead>tr>th, #reporte_resultados>thead>tr>th{
       background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%);;
     }
  </style>
@endsection
@section('contenido')
<div class="row">
     @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general')
     <div class="col-xl-4 col-lg-4 col-md-6  col-12 mt-xl-0 mt-lg-0 mt-1">
        <div class="card text-white" style="background:linear-gradient(to bottom, rgba(230,240,163,1) 0%,rgba(210,230,56,1) 50%,rgba(195,216,37,1) 51%,rgba(219,240,67,1) 100%);">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ $this->asset('img/icons/unicons/money.ico') }}" alt="chart success"
                            class="rounded" />
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded text-primary"> (Hoy {{$this->getDayDate($this->FechaActual("Y-m-d"))}})</i>
                        </button>
        
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1 text-center text-primary">Total recaudado</span>
                <h3 class="card-title mb-2 text-white text-center" >
                    {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}  {{isset($ReporteIngresosDia[0]->total_ingreso) ?$ReporteIngresosDia[0]->total_ingreso : "0.00"}}
                </h3>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6  col-12 mt-xl-0 mt-lg-0 mt-1">
        <div class="card text-white" style="background:linear-gradient(to bottom, rgba(183,222,237,1) 0%,rgba(113,206,239,1) 50%,rgba(33,180,226,1) 51%,rgba(183,222,237,1) 100%);">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ $this->asset('img/icons/unicons/money.ico') }}" alt="chart success"
                            class="rounded" />
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded text-white"> (Mes {{$this->getMonthName($this->FechaActual("m"))}} {{$this->FechaActual("Y")}})</i>
                        </button>
        
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1 text-center">Total recaudado</span>
                <h3 class="card-title mb-2 text-white text-center">
                    {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}  {{isset($ReporteIngresosMes[0]->total_ingreso) ?$ReporteIngresosMes[0]->total_ingreso : "0.00"}}
                </h3>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12 col-12 mt-xl-0 mt-lg-0 mt-1">
        <div class="card text-white" style="background:linear-gradient(to bottom, rgba(255,183,107,1) 0%,rgba(255,167,61,1) 50%,rgba(255,124,0,1) 51%,rgba(255,127,4,1) 100%);">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ $this->asset('img/icons/unicons/money.ico') }}" alt="chart success"
                            class="rounded" />
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded text-white"> (Año {{$this->FechaActual("Y")}})</i>
                        </button>
        
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1 text-center">Total recaudado</span>
                <h3 class="card-title mb-2 text-white text-center">
                    {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}  {{isset($ReporteIngresosAnio[0]->total_ingreso) ?$ReporteIngresosAnio[0]->total_ingreso : "0.00"}}
                </h3>
            </div>
        </div>
    </div>

     @endif
     @if ($this->profile()->rol === 'admin_farmacia' || $this->profile()->rol === 'Farmacia' || $this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general')
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-1">
        <div class="card text-white" style="background:linear-gradient(to bottom, rgba(206,220,231,1) 0%,rgba(89,106,114,1) 100%);">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ $this->asset('img/icons/unicons/money.ico') }}" alt="chart success"
                            class="rounded" />
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
                        </button>
        
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1 text-center">Total en caja</span>
                <h3 class="card-title mb-2 text-white text-center">
                    {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}  {{isset($ReporteCajaDia[0]->cajatotal) ?$ReporteCajaDia[0]->cajatotal : "0.00"}}
                </h3>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mt-1">
        <div class="card text-white" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ $this->asset('img/icons/unicons/money.ico') }}" alt="chart success"
                            class="rounded" />
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded text-white"> (Mes actual)</i>
                        </button>
        
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1 text-center">Total en caja</span>
                <h3 class="card-title mb-2 text-white text-center">
                    {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} {{isset($ReporteCajaMes[0]->cajatotal) ?$ReporteCajaMes[0]->cajatotal : "0.00"}}
                </h3>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4  col-12  mt-1">
        <div class="card text-white" style="background:linear-gradient(to bottom, rgba(180,227,145,1) 0%,rgba(97,196,25,1) 50%,rgba(180,227,145,1) 80%);">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ $this->asset('img/icons/unicons/money.ico') }}" alt="chart success"
                            class="rounded" />
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded text-white"> (Año actual)</i>
                        </button>
        
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1 text-center">Total en caja</span>
                <h3 class="card-title mb-2 text-white text-center">
                    {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} {{isset($ReporteCajaAnio[0]->cajatotal) ?$ReporteCajaAnio[0]->cajatotal : "0.00"}}
                </h3>
            </div>
        </div>
    </div>
    @endif
    @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general' || $this->profile()->rol === 'admin_farmacia')
    @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general')
    <div class="col-12 mt-2">
        <div class="card">
            <div class="card-body">
                <div class="card-text">  <h5>Estado de resultados</h5></div>
                <div class="row">
                    <div class="col-xl-5 col-lg-5 co-md-6 col-12">
                        <div class="form-group">
                            <label for="fi"><b>Fecha inicial</b></label>
                            <input type="date" class="form-control" id="fi" 
                            value="{{$this->FechaActual("Y-m-d")}}">
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5 co-md-6 col-12">
                        <div class="form-group">
                            <label for="ff"><b>Fecha final</b></label>
                            <input type="date" class="form-control" id="ff" 
                            value="{{$this->addRestFecha("Y-m-d","10 day")}}">
                        </div>
                    </div>
                </div>
              <div class="card-text mt-2">
                <button class="btn_twiter col-xl-3 col-lg-4 col-md-5 col-sm-6 col-12" id="reporte_estados"><b>Ver reporte <i class='bx bxs-file-pdf'></i></b></button>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped nowrap responsive" id="reporte_resultados"
                    style="width: 100%">
                        <thead>
                            <th class="py-3 text-white letra">#</th>
                            <th class="py-3 text-white letra">Fecha</th>
                            <th class="py-3 text-white letra">Paciente</th>
                            <th class="py-3 text-white letra">Monto {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                        </thead>
                        <tfoot align="right" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%);">
                            <tr><th class="text-white" colspan="3">Total ingreso de la clínica  {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>  <th class="text-white" colspan="1"></th></tr>
                        </tfoot>
                    </table>
                </div>
              </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-12 mt-2">
        <div class="card">
            <div class="card-body">
                <div class="card-text">  <h5>Reporte de caja por fechas</h5></div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 co-md-6 col-12">
                        <div class="form-group">
                            <label for="fi_caja"><b>Fecha inicial</b></label>
                            <input type="date" class="form-control" id="fi_caja" 
                            value="{{$this->FechaActual("Y-m-d")}}">
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 co-md-6 col-12">
                        <div class="form-group">
                            <label for="ff_caja"><b>Fecha final</b></label>
                            <input type="date" class="form-control" id="ff_caja" 
                            value="{{$this->addRestFecha("Y-m-d","10 day")}}">
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4  col-12">
                        <div class="form-group">
                            <label for="ff_caja"><b>Selección personalizado</b></label>
                             <select name="select_tiempo_caja" id="select_tiempo_caja" class="form-select">
                                <option value="null" selected disabled> -- Seleccione -- </option>
                                <option value="1">Enero {{$this->FechaActual("Y")}}</option>
                                <option value="2">Febrero {{$this->FechaActual("Y")}}</option>
                                <option value="3">Marzo {{$this->FechaActual("Y")}}</option>
                                <option value="4">Abril {{$this->FechaActual("Y")}}</option>
                                <option value="5">Mayo {{$this->FechaActual("Y")}}</option>
                                <option value="6">Junio {{$this->FechaActual("Y")}}</option>
                                <option value="7">Julio {{$this->FechaActual("Y")}}</option>
                                <option value="8">Agosto {{$this->FechaActual("Y")}}</option>
                                <option value="9">Septiembre {{$this->FechaActual("Y")}}</option>
                                <option value="10">Octubre {{$this->FechaActual("Y")}}</option>
                                <option value="11">Noviembre {{$this->FechaActual("Y")}}</option>
                                <option value="12">Diciembre {{$this->FechaActual("Y")}}</option>

                             </select>
                        </div>
                    </div>
                </div>
              <div class="card-text mt-2">
                <button class="btn_info_tw col-xl-3 col-lg-4 col-md-5 col-sm-6 col-12 m-1" id="reporte_cajas"><b>Ver reporte <i class='bx bxs-file-pdf'></i></b></button>
                <button class="btn rounded btn-danger col-xl-3 col-lg-4 col-md-5 col-sm-6 col-12 m-1" id="cancel_cajas"><b>Cancelar reporte X</b></button>
              
              </div>
            </div>
        </div>
    </div>
    @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general')
    <div class="col-xl-6 col-lg-6 col-12 mt-2">
        <div class="card">
            <div class="card-body">
              <div class="card-text">  <h5>Ingresos recaudados por mes ({{$this->FechaActual("Y")}})</h5></div>
              <div class="card-text">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped nowrap responsive" id="reporte_ingresos_mensual"
                    style="width: 100%">
                        <thead>
                            <th class="py-3 text-white letra">#</th>
                            <th class="py-3 text-white letra">MES</th>
                            <th class="py-3 text-white letra">GANANCIA</th>
                        </thead>
                    </table>
                </div>
              </div>
              <div class="card-text mt-2 text-center">
                <button class="btn btn-outline-danger rounded col-xl-8 col-lg-4 col-md-5 col-sm-6 col-12" id="reporte_ingresos_mensual_buton"><b>Imprimir <i class='bx bxs-printer'></i></b></button>
              </div>
            </div>
             
        </div>
    </div>
 
    {{-- reporte de ingresos por año de la clínica---}}
    <div class="col-xl-6 col-lg-6  col-12 mt-2" id="reporr">
        <div class="card">
            <div class="card-body">
              <div class="card-text">  <h5>Ingresos recaudados por cada año</h5></div>
              <div class="card-text">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped nowrap responsive" id="reporte_ingresos_anio"
                    style="width: 100%">
                        <thead>
                            <th class="py-3 text-white letra">#</th>
                            <th class="py-3 text-white letra">AÑO</th>
                            <th class="py-3 text-white letra">GANANCIA</th>
                        </thead>
                    </table>
                </div>
              </div>
              <div class="card-text mt-2 text-center">
                <button class="btn btn-outline-danger rounded col-xl-8 col-lg-4 col-md-5 col-sm-6 col-12" id="reporte_ingresos_anio_buton"><b>Imprimir <i class='bx bxs-printer'></i></b></button>
              </div>
            </div>
        </div>
    </div>

    @endif
@endif
    
</div>
@endsection

@section('js')
<script src="{{ URL_BASE }}public/js/control.js"></script>
<script>
var URL = "{{ URL_BASE }}";
var TOKEN = "{{ $this->Csrf_Token() }}";
var MONEDA = "{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}";
var TablaReporteIngresosMes,TablaReporteIngresosAnio,TablaReporteResultados;
$(document).ready(function(){
MostrarReporteIngresosMes();
MostrarReporteIngresosAnio();
 
reporteResultados($('#fi').val(),$('#ff').val());

 $('#ff').change(function(){
    reporteResultados($('#fi').val(),$(this).val());
 });
 $('#fi').change(function(){
    reporteResultados($(this).val(),$('#ff').val());
 });

 $('#reporte_estados').click(function(){
    window.open(URL+"clinica/reporte/resultados?fi="+$('#fi').val()+"&&ff="+$('#ff').val(),"_blank")
 })

 $('#reporte_ingresos_mensual_buton').click(function(){
    window.open(URL+"clinica/reporte/ingresos/por_mes/año","_blank");
 });
 $('#reporte_ingresos_anio_buton').click(function(){
   
 });
 $('#reporte_cajas').click(function(){
  let tiempo_personalizado = $('#select_tiempo_caja').val();
  let fiCaja = $('#fi_caja').val();
  let ffCaja = $('#ff_caja').val();
  if(tiempo_personalizado == null)
  {
    window.open(URL+"historial/caja?fi="+fiCaja+"&&ff="+ffCaja,"_blank")
  }else{
    window.open(URL+"historial/caja?select_tiempo="+tiempo_personalizado,"_blank")
  }
 });

 $('#cancel_cajas').click(function(){
    $('#select_tiempo_caja').val("null")
 });
});  
function MostrarReporteIngresosMes()
{
   TablaReporteIngresosMes = $('#reporte_ingresos_mensual').DataTable({
    language: SpanishDataTable(),
    responsive:true,
    processing:true,
    "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
    ajax:{
        url:URL+"reporte/ingresos/por_mes",
        method:"GET",
        dataSrc:"response",
    },
    columns:[
        {"data":"mes"},
        {"data":"mes",render:function(mes){
            return `<b class='badge bg-success'>`+mes+`</b>`;
        }},
        {"data":"total",render:function(total){
            return `<span class='badge bg-primary'>`+MONEDA+` `+total+`</span>`;
        }}
    ]
   });
   /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
   TablaReporteIngresosMes.on( 'order.dt search.dt', function () {
    TablaReporteIngresosMes.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();
}
 

function MostrarReporteIngresosAnio()
{
   TablaReporteIngresosAnio = $('#reporte_ingresos_anio').DataTable({
    language: SpanishDataTable(),
    responsive:true,
    processing:true,
    "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
    ajax:{
        url:URL+"reporte/ingresos/por_anio",
        method:"GET",
        dataSrc:"response",
    },
    columns:[
        {"data":"anio"},
        {"data":"anio",render:function(mes){
            return `<b class='badge bg-success'>`+mes+`</b>`;
        }},
        {"data":"total",render:function(total){
            return `<span class='badge bg-primary'>`+MONEDA+` `+total+`</span>`;
        }}
    ]
   });
   /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
   TablaReporteIngresosAnio.on( 'order.dt search.dt', function () {
    TablaReporteIngresosAnio.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();
}

/// mostrar los datos para el reporte de resultados
function reporteResultados(fecha1,fecha2)
{
  TablaReporteResultados = $('#reporte_resultados').DataTable({
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
            
            var monTotalIngresosClinica = api
            .column( 3 )
            .data()
            .reduce( function (a, b) {
            return intVal(a) + intVal(b);
            }, 0 );
 
            // Update footer by showing the total with the reference of the column index
         
            $( api.column( 3 ).footer() ).html("<b>"+monTotalIngresosClinica.toFixed(2)+"</b>");
            
            },  
    language: SpanishDataTable(),
    bDestroy: true,
    responsive: true,
    processing: true,
    "columnDefs": [{
        "searchable": false,
        "orderable": false,
        "targets": 0
    }],
    ajax:{
      url:URL+"clinica/ingresos/servicio/"+fecha1+"/"+fecha2,
      method:"GET",
      dataSrc:"response"
    },
    columns:[
        {"data":"fechadata"},
        {"data":"fechadata",render:function(fecha){
            return `<span class='badge bg-info'>`+fecha+`</span>`;
        }},
        {"data":"pacientedata",render:function(paciente){
          return paciente.toUpperCase();
        }},
        {"data":"monto_clinica",render:function(montoclinica){return montoclinica != null ? montoclinica : "0.00"}}
    ]
  
  });

   /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
   TablaReporteResultados.on('order.dt search.dt', function() {
    TablaReporteResultados.column(0, {
        search: 'applied',
        order: 'applied'
    }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
    });
}).draw();
}
</script>    
@endsection