@extends($this->Layouts("dashboard"))

@section("title_dashboard","Prima-Plan de pagos")

@section('css')
    <style>
        #tabla-tipodocumentos>thead>tr>th{
            background-color: #E6E6FA;
            color: #000;
            padding: 20px;
        }
    </style>
@endsection
@section('contenido')
<div class="card-text">
    <b class="text-primary ">Datos del cliente</b>
    <br>
    <div class="row mt-2 border border-primary p-2" style="border-radius: 4%">
        
        <div class="col-xl-3 col-lg-3 col-md-6 col-12">
            <label for="nombrescli" class="mt-2"><b># Poliza</b></label>
        </div>
        <div class="col-xl-9 col-lg-9 col-md-6 col-12">
            <input type="text" id="nombrescli" class="form-control" value="{{isset($polizaSelect) ? strtoupper($polizaSelect[0]->num_poliza):''}}">
        </div>
       
        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-1">
            <label for="tipodoc" class="mt-2"><b>Asegurado</b></label>
        </div>
        <div class="col-xl-9 col-lg-9 col-md-6 col-12">
            <input type="text" id="asegurado" class="form-control" value="{{isset($polizaSelect) ? $polizaSelect[0]->asegurado:''}}">
        </div>
        
    </div>

    <div class="row">
        @if ($this->getSession("mensaje")== 'ok')
        <div class="alert alert-success">
         <b>Plan de pago registrado correctamente!</b>
         </div>
          
        @endif
        @php
        $this->destroySession("mensaje")
        @endphp
        @if ($this->getSession("mensaje_error") == 'error')
        <div class="alert alert-danger">
         <b>Hubo un error al intentar registrar el plan de pago de la poliza!</b>
         </div>
          
        @endif
        @php
        $this->destroySession("mensaje_error")
        @endphp
        <div class="table-responsive">
            <a href="{{URL_BASE}}poliza/{{isset($polizaSelect)?$polizaSelect[0]->id_poliza:''}}/prima/plan_pagos/new" class="btn btn-success rounded">Agregar nuevo Pago <i class="fas fa-plus"></i></a>
            <table id="tabla_prima_pagos" class="table table-bordered table-striped responsive nowrap mt-3" id="tabla-tipodocumentos" style="width: 100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th># PRIMERA CUOTA</th>
                        <th>POLIZA</th>
                        <th>CONTRATANTE</th>
                        <th>COMPAÑIA</th>
                        <th>RAMO</th>
                        <th>TP</th>
                        <th>MONEDA</th>
                        <th>COMERCIAL</th>
                        <th>NETO</th>
                        <th>TOTAL</th>
                        <th>VIG.INICIO</th>
                        <th>VIG.FIN</th>
                        <th>NUM.OPERACION</th>
                        <th>MOTIVO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>

                <tbody>
                    @if (isset($pagosPolizas))
                        @foreach ($pagosPolizas as $key=>$pago)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$pago->num_primera_cuota}}</td>
                                <td>{{$pago->num_poliza}}</td>
                                <td>{{$pago->asegurado}}</td>
                                <td>{{$pago->name_compania}}</td>
                                <td>{{$pago->name_ramo}}</td>
                                <td>{{strtoupper($pago->tipo_doc)}}</td>
                                <td>{{$pago->moneda}}</td>
                                <td>{{$pago->prima_comercial}}</td>
                                <td>{{$pago->prima_neta}}</td>
                                <td>{{$pago->prima_total_bruta}}</td>
                                <td>{{$pago->vigencia_inicio}}</td>
                                <td>{{$pago->vigencia_fin}}</td>
                                <td>{{$pago->num_operacion}}</td>
                                <td>{{$pago->motivo}}</td>
                                <td>
                                    <a href="{{URL_BASE}}poliza/plan-pagos/cuotas/{{$pago->id_prima_pago}}" class="btn btn-outline-success">Cuotas</a>
                                    <a href="{{URL_BASE}}poliza/plan-pagos/documenstos/{{$pago->id_prima_pago}}" class="btn btn-outline-danger"> <i class="fa-solid fa-file-pdf"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
{{-- JS ADICIONALES ---}}
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>
  var RUTA = "{{URL_BASE}}" // la url base del sistema
  var COMISIONCOMPANIA;
$(document).ready(function(){
    $('#tabla_prima_pagos').DataTable();
});
  
</script>
@endsection