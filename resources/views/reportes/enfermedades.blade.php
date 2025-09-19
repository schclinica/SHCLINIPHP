@extends($this->Layouts("dashboard"))

@section('title_dashboard', 'Enfermedades')
@section('clase_ocultar','d-none')
@section('expandir','layout-content-navbar layout-without-menu')
@section('contenido')
  <div class="card p-4">
    <div class="card-text"><p class="h4 float-start">Reporte de enfermedades</p><p class="float-end"> <a href="{{$this->route('dashboard')}}"><i class='bx bx-arrow-back'></i> Volver</a></p></div>
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-12 mb-xl-0 mb-lg-0 mb-1">
            <div class="card">
                <div class="card-text m-4"><b class="text-danger">AÑO</b> [ <b class="text-primary">{{$this->FechaActual("Y")}}</b> ]</div>
                <div class="card-body">
                    <div id="reporte_anual"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-12  mb-1">
            <div class="card">
                <div class="card-text m-4"><b class="text-danger">MES</b> [ <b class="text-primary">{{$this->getMonthName($this->FechaActual("m"))}}</b> ]</div>
                <div class="card-body">
                    <div id="reporte_mes"></div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-text m-4"><b class="text-danger">HOY</b> [ <b class="text-primary">{{$this->getDayDate($this->FechaActual("Y-m-d"))}}</b> ]</div>
                <div class="card-body">
                    <div id="reporte_diario"></div>
                </div>
            </div>
        </div>

    </div>
  </div>
@endsection
@section('js')
<script>
    var RUTA = "{{URL_BASE}}" // la url base del sistema
    var TOKEN = "{{$this->Csrf_Token()}}";
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>

showReporteEnfermedadPorAnio();
showReporteEnfermedadPorMes();
showReporteEnfermedadPorDiario();

$(window).resize(function() {
showReporteEnfermedadPorAnio();
showReporteEnfermedadPorMes();
showReporteEnfermedadPorDiario();
})
/// metodo para mostrar el reporte por año
function showReporteEnfermedadPorAnio(){
    let Enfermedades = [];
    $.ajax({
        url:RUTA+"enfermedades/reporte-statistico/anio",
        method:"GET",
        dataType:"json",
        success:function(response){
            
            if(response.enfermedades.length > 0){
                response.enfermedades.forEach(enf => {
                    Enfermedades.push([enf.enfermedad,parseInt(enf.cantidad)])
                });
            }

            GenerateGraficoEstadistico(Enfermedades,'reporte_anual','Enfermedades diagnosticados por año');
        }
    })
}

function showReporteEnfermedadPorMes(){
    let Enfermedades = [];
    $.ajax({
        url:RUTA+"enfermedades/reporte-statistico/mes",
        method:"GET",
        dataType:"json",
        success:function(response){
            
            if(response.enfermedades.length > 0){
                response.enfermedades.forEach(enf => {
                    Enfermedades.push([enf.enfermedad,parseInt(enf.cantidad)])
                });
            }else{
                Enfermedades.push(['',0])  
            }

             GenerateGraficoEstadistico(Enfermedades,'reporte_mes','Enfermedades diagnosticados por mes');
        }
    })
}

function showReporteEnfermedadPorDiario(){
    let Enfermedades = [
        ['Element', 'Enfermedades diagnosticados por día', {
        role: 'style',
        }]
    ];
    $.ajax({
        url:RUTA+"enfermedades/reporte-statistico/diario",
        method:"GET",
        dataType:"json",
        success:function(response){
            
            if(response.enfermedades.length > 0){
                response.enfermedades.forEach(enf => {
                    Enfermedades.push([enf.enfermedad,parseInt(enf.cantidad),GenerateRgb()])
                });
            }else{
                Enfermedades.push(['',0,GenerateRgb()])  
            }

             GraficoBarraChart(Enfermedades,'Enfermedad','Cantidad','reporte_diario')
        }
    })
}

function GenerateGraficoEstadistico(DataGrafico = [], div, Title, En3D = true, Resposiva = true) {

// Load the Visualization API and the corechart package.
google.charts.load('current', {
    'packages': ['corechart']
});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);
 
function drawChart() {

    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Topping');data.addColumn('number', 'Slices');
    data.addRows(DataGrafico);

    // Set chart options
    var options = {'title': Title,'responsive': Resposiva,is3D: En3D,};

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById(div));
    chart.draw(data, options);
}
}

/*Plnatilla reporte gráfico estadistico tipo barra*/
function GraficoBarraChart(Data1 = [], TitleOptions, TitleOptions_2, IdDiv) {
            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(drawMultSeries);

            function drawMultSeries() {
                var data = new google.visualization.arrayToDataTable(Data1);

                var options = {
                    title: TitleOptions,

                    vAxis: {
                        title: TitleOptions_2
                    },
                    bar: {
                        groupWidth: "40%"
                    },

                };

                var chart = new google.visualization.ColumnChart(
                    document.getElementById(IdDiv));

                chart.draw(data, options);
            }
}

/** Generamos código rgb aleatorios*/
function GenerateCodeAleatorio(numero) {
return (Math.random() * numero).toFixed(0);
}

/** Generamos el rgb**/
function GenerateRgb() {
let Code = "(" + GenerateCodeAleatorio(255) + "," + GenerateCodeAleatorio(255) + "," + GenerateCodeAleatorio(
255) + ")";
return 'rgb' + Code;
}
</script>
@endsection