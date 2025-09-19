@extends($this->Layouts("dashboard"))

@section("title_dashboard","Historial de ingresos por médico")

@section('css')
<style>
    #historial_ingresos>thead>tr>th {

        padding: 20px;
        background:linear-gradient(to bottom, rgba(59,103,158,1) 0%,rgba(43,136,217,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%);
        color:white;
    }
 
</style>
@endsection
@section('contenido')
 <div class="row">
    <div class="col-12">
        <div class="card">
        <div class="card-header" style="background: linear-gradient(to bottom, rgba(59,103,158,1) 0%,rgba(43,136,217,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%);">
          <h4 class="text-white letra">Historial de ingresos</h4>
        </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-8 col-lg-8 col-md-6 col-12 mt-2">
                        <div class="form-group">
                            <label for="medico"><b>Seleccionar médico</b></label>
                            <select name="medico" id="medico" class="form-select"></select>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 mt-2">
                        <div class="form-group">
                            <label for="anio"><b>Seleccionar año</b></label>
                            <select name="anio" id="anio" class="form-select">
                                @if (isset($anios) && count($anios) > 0)
                                    @foreach ($anios as $an)
                                        <option value="{{$an->anio}}">{{$an->anio}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-12 table-responsive">
                        <table class="table table-bordered table-striped nowrap" id="historial_ingresos" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>MES</th>
                                    <th>MONTO {{count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda:'S/.'}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
        
                                <div class="card-text">
                                    <div id="reporte_historial"></div>
                                </div>
        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
@endsection

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="{{ URL_BASE }}public/js/control.js"></script>
    <script>
        var TablaHistorial;
        var RUTA = "{{URL_BASE}}" // la url base del sistema
        var TOKEN = "{{$this->Csrf_Token()}}";
        var MEDICOID;
        $(document).ready(function(){
           
            showMedicos();

            $('#medico').change(function(){
                MEDICOID = $(this).val();
              
                mostrarHistorialIngresos(MEDICOID,$('#anio').val());
                showReporteHistirialGraficaEstadictico(MEDICOID,$('#anio').val());
            });

            $('#anio').change(function(){
   
               if($('#medico').val() != null){
                mostrarHistorialIngresos($('#medico').val(),$(this).val());
                showReporteHistirialGraficaEstadictico($('#medico').val(),$(this).val());
               }else{
                 Swal.fire(
                    {
                        title:"MENSAJE DEL SISTEMA!",
                        text:"Seleccione a un médico!",
                        icon:"warning"
                    }
                 ).then(function(){
                    $('#medico').focus();
                 })
               }
            });

            
        });

        function mostrarHistorialIngresos(id_medico,anio){
            TablaHistorial = $('#historial_ingresos').DataTable({
                bDestroy:true,
              "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
                }],
                ajax:{
                    url:RUTA+"medico/reporte/historial/ingresos-detallado/"+id_medico+"/"+anio,
                    method:"GET",
                    dataSrc:"response"
                },
                columns:[
                    {"data":"mes"},
                    {"data":"mes",render:function(mes){
                        return mes.toUpperCase()+"<b class='text-primary'>[ "+$('#anio').val()+" ]</b>"
                    }},
                    {"data":"monto",render:function(monto){
                        return `<span class='badge bg-success'><b class='text-primary'>`+monto+`</b></span>`;
                    }}
                ]
            });
/*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
            TablaHistorial.on( 'order.dt search.dt', function () {
            TablaHistorial.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
            } );
            }).draw();
        }

        /// mostrar todos los médicos
        function showMedicos(){
            let option = '<option disabled selected>--- Seleccionar ----</option>';
            $.ajax({
                url:RUTA+"show/medicos",
                method:"GET",
                dataType:"json",
                success:function(response){
                   if(response.medicos.length > 0){
                     response.medicos.forEach(medico => {
                        option+=`<option value=`+medico.id_medico+`>`+(medico.apellidos+" "+medico.nombres).toUpperCase()+`</option>`;
                     });
                   }

                   $('#medico').html(option);
                }
            })
        }

        function showReporteHistirialGraficaEstadictico(idmedico,anio) {
            let Data1 = [
                ['Element', 'Mes', {
                    role: 'style',
                }]
            ];
            $.ajax({
                url: RUTA + "medico/reporte/historial/ingresos-detallado/" + idmedico+"/"+anio,
                method: "GET",
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.response.length > 0) {
                        response.response.forEach(historia => {
                            Data1.push([historia.mes, parseInt(historia.monto), GenerateRgb()]);
                        });
                    } else {
                        Data1.push(["", 0, GenerateRgb()]);
                    }
                    GraficoBarraChart(Data1, 'Historial de ingresos del médico '+"[ "+$('#anio').val()+" ]", 'Monto',
                        'reporte_historial')
                }
            })

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

          /** Generamos el rgb**/
          function GenerateRgb() {
            let Code = "(" + GenerateCodeAleatorio(255) + "," + GenerateCodeAleatorio(255) + "," + GenerateCodeAleatorio(
                255) + ")";
            return 'rgb' + Code;
        }
        /** Generamos código rgb aleatorios*/
        function GenerateCodeAleatorio(numero) {
            return (Math.random() * numero).toFixed(0);
        }
        
    </script>
@endsection