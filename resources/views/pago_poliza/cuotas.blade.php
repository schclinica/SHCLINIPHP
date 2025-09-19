@extends($this->Layouts("dashboard"))

@section("title_dashboard","Agregar-pago-prima-poliza")

@section('css')
    <style>
        #tabla_cuotas>thead>tr>th{
            background-color: #E6E6FA;
            color: #000;
            padding: 20px;
        }
    </style>
@endsection
@section('contenido')
<div class="mx-3">
    <div class="row">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-text">
                    <h1>Importe Total </h1>
                    <h1>   <span class="text-primary">{{(isset($cuotasPoliza)?$cuotasPoliza[0]->moneda:'')." ".number_format($sumaimporte,2,","," ")}}</span></h1>
                </div>
              
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                @if ($this->ExistSession("mensaje"))
                <div class="m-2">
      
                   @if ($this->getSession("mensaje")== 'ok')
                   <div class="alert alert-success">
                    <b>Cuota modificado correctamente!</b>
                    </div>
                     
                   @endif

                @endif
                    {{--- DESTRUIMOS LA SESSION----}}
                    @php
                    $this->destroySession("mensaje")
                    @endphp
                <div class="card-text">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-1">
                            <label for="tipodoc" class="mt-2"><b>Num.primera cuota</b></label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-6 col-12 mt-1">
                            <input type="text" id="tipodoc" class="form-control" value="{{isset($cuotasPoliza) ? $cuotasPoliza[0]->num_operacion_primera_cuota:''}}">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-1">
                            <label for="numdoc" class="mt-2"><b>Vigencia inicio</b></label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-6 col-12 mt-1">
                            <input type="text" id="numdoc" class="form-control"  value="{{isset($cuotasPoliza) ? $cuotasPoliza[0]->vigencia_inicio:''}}">
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-1">
                            <label for="num_telef" class="mt-2"><b>Vigencia fín</b></label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-6 col-12 mt-1">
                            <input type="text" id="num_telef" class="form-control"  value="{{isset($cuotasPoliza) ? $cuotasPoliza[0]->vigencia_fin:''}}"">
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-1">
                            <label for="num_telef" class="mt-2"><b>Tipo documento</b></label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-6 col-12 mt-1">
                            <input type="text" id="num_telef" class="form-control"  value="{{isset($cuotasPoliza) ? strtoupper($cuotasPoliza[0]->tipo_doc):''}}"">
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-1">
                            <label for="num_telef" class="mt-2"><b>Concepto</b></label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-6 col-12 mt-1">
                            <input type="text" id="num_telef" class="form-control"  value="{{isset($cuotasPoliza) ? $cuotasPoliza[0]->motivo:''}}"">
                        </div>
                    </div>
                </div>
              
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="card-text table-responsive">
                    <table id="tabla_cuotas" class="table table-bordered table-striped responsive nowrap mt-3"  style="width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>CUPON</th>
                                <th>FECHA VENCIMIENTO</th>
                                <th>MONEDA</th>
                                <th>IMPORTE</th>
                                <th>FECHA DE PAGO</th>
                                <th>FACTURA</th>
                                <th>OBSERVACION</th>
                                <th>ACCIONES</th>
                                
                            </tr>
                        </thead>
        
                        <tbody>
                            @if (isset($cuotasPoliza))
                                @foreach ($cuotasPoliza as $key=>$cuota)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$cuota->num_cupon}}</td>
                                        <td>{{$cuota->fecha_vencimiento}}</td>
                                        <td>{{$cuota->moneda}}</td>
                                        <td>{{$cuota->importe}}</td>
                                        <td>{{$cuota->fecha_pago!=null ?$cuota->fecha_pago:'----'}}</td>
                                        <td>{{$cuota->factura!= null ? $cuota->factura :'-----'}}</td>
                                        <td>{{$cuota->observacion!=null?$cuota->observacion:'----------'}}</td>
                                        <td>
                                            <a href="{{URL_BASE}}poliza/plan-pagos/{{$cuota->id_cuota}}/editar-cuotas/{{isset($cuotasPoliza) ? $cuotasPoliza[0]->id_prima_pago:''}}" class="btn btn-warning btn-sm rounded"><i class="fas fa-edit"></i></a>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
              
            </div>
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
     $('#tabla_cuotas').DataTable({})
  });
</script>
@endsection