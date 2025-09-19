@extends($this->Layouts("dashboard"))

@section("title_dashboard","Agregar-pago-prima-poliza")

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
<div class="mx-3">
    <div class="row">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-text">
                    <h4> Agregar pago-primas Poliza</h4>
                    <a href="{{URL_BASE}}poliza/{{isset($polizaSelect) ? $polizaSelect[0]->id_poliza."/prima/plan-pagos":""}}">Volver a la lista de plan de pagos  </a>
                </div>
                <form action="{{URL_BASE}}poliza/pagos/prima/store/{{isset($polizaSelect) ? $polizaSelect[0]->id_poliza:""}}" method="post">
                    <div class="row">
                        <div class="form-group">
                            <label for="num_poliza" class="form-label mt-2 "><b>Tipo Doc *</b></label>
                             <select name="tipo_doc" id="tipo_doc" class="form-select">
                                <option value="" selected disabled>--- Seleccione ----</option>
                                <option value="emision">EMISION</option>
                                <option value="renovacion">RENOVACION</option>
                                <option value="endoso">ENDOSO</option>
                                <option value="devolucion">DEVOLUCION</option>
                             </select>
                        </div>
                        <div class="form-group">
                            <label for="asegurado" class="form-label mt-2 "><b>CONTRATANTE *</b></label>
                            <input type="text" class="form-control" id="asegurado" name="asegurado" value="{{isset($polizaSelect) ? strtoupper($polizaSelect[0]->asegurado):""}}">
                        </div>

                        <div class="form-group">
                            <label for="vigencia_inicio" class="form-label mt-2 "><b>VIGENCIA INICIO *</b></label>
                             <input type="text" class="form-control" name="vigencia_incio" value="{{isset($polizaSelect) ? strtoupper($polizaSelect[0]->vigencia_inicio):""}}">
                        </div>
                        <div class="form-group">
                            <label for="vigencia_fin" class="form-label mt-2 "><b>VIGENCIA FIN*</b></label>
                             <input type="date" class="form-control" name="vigencia_fin" value="{{isset($polizaSelect) ? strtoupper($polizaSelect[0]->vigencia_fin):""}}">
                        </div>
                        <div class="form-group">
                            <label for="fecha_emision" class="form-label mt-2 "><b>FECHA EMISION *</b></label>
                             <input type="date" class="form-control" name="fecha_emision" value="{{isset($polizaSelect) ? strtoupper($polizaSelect[0]->fecha_emision):""}}">
                        </div>
                        <div class="form-group">
                            <label for="fecha_solicitud" class="form-label mt-2 "><b>FECHA SOLICITUD SEGURO</b></label>
                             <input type="date" class="form-control" name="fecha_solicitud" value="{{self::FechaActual("Y-m-d")}}" >
                        </div>

                        <div class="form-group">
                            <label for="fecha_entrega" class="form-label mt-2 "><b>FECHA ENTREGA POLIZA CONTRATANTE</b></label>
                             <input type="date" class="form-control" name="fecha_entrega" value="{{self::addRestFecha("Y-m-d","+5 day")}}">
                        </div>

                        <div class="form-group">
                            <label for="suma_asegurada" class="form-label mt-2 "><b>SUMA ASEGURADA COBERTURA PRINCIPAL </b></label>
                             <input type="text" class="form-control" name="suma_asegurada" >
                        </div>

                        <div class="form-group">
                            <label for="tipo_pago" class="form-label mt-2 "><b>TIPO DE PAGO </b></label>
                            <select name="tipo_pago" id="tipo_pago" class="form-select">
                               <option value="" selected disabled> ---- seleccione ----</option>
                               <option value="contado">CONTADO</option>
                               <option value="financiado">FINANCIADO</option>
                               <option value="cargo en cuenta">CARGO EN CUENTA</option>
                               <option value="sin prima">SIN PRIMA</option>
                               <option value="pago efectivo">PAGO EFECTIVO</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="producto" class="form-label mt-2 "><b>MONEDA*</b></label>
                             <input type="text" class="form-control" readonly value="{{isset($polizaSelect) ? strtoupper($polizaSelect[0]->moneda):""}}">
                        </div>

                        <div class="form-group">
                            <label for="prima_comercial" class="form-label mt-2 "><b>PRIMA COMERCIAL </b></label>
                            <input type="text" class="form-control" id="prima_comercial" name="prima_comercial">
                        </div>

                        <div class="form-group">
                            <label for="prima_neta" class="form-label mt-2 "><b>PRIMA NETA *</b></label>
                            <input type="text" class="form-control" id="prima_neta" name="prima_neta">
                        </div>
                        <div class="form-group">
                            <label for="prima_totalbruta" class="form-label mt-2 "><b>PRIMA TOTAL BRUTA*</b></label>
                            <input type="text" class="form-control" id="prima_totalbruta" name="prima_totalbruta">
                        </div>
                        <div class="form-group">
                            <label for="motivo" class="form-label mt-2 "><b>MOTIVO*</b></label>
                            <input type="text" class="form-control" id="motivo" name="motivo">
                        </div>

                        <div class="form-group">
                            <label for="num_operacionseguros" class="form-label mt-2 "><b>NUM.OPERACIOMN CIA DE SEGUROS</b></label>
                            <input type="text" class="form-control" id="num_operacionseguros" name="num_operacionseguros">
                        </div>
                        <div class="form-group">
                            <label for="comisioncompania" class="form-label mt-2 "><b>COMISION COMPAÑIA %</b></label>
                            <input type="text" class="form-control" id="comisioncompania" name="comisioncompania" value="{{isset($polizaSelect) ? strtoupper($polizaSelect[0]->comision_compania):""}}">
                        </div>
                        <div class="form-group">
                            <label for="importecomisioncompania" class="form-label mt-2 "><b>IMPORTE COMISION COMPAÑIA </b></label>
                            <input type="text" class="form-control" id="importecomisioncompania" name="importecomisioncompania"  >
                        </div>

                        <div class="form-group">
                            <label for="subagente" class="form-label mt-2 "><b>SUB AGENTE *   </b></label>
                            <select name="subagente" id="subagente" class="form-select">
 
                                @if (isset($vendedores))
                                @foreach ($vendedores as $vendedor)
                                    <option value="{{$vendedor->id_usuario}}" @if($vendedor->name===$polizaSelect[0]->subagente) selected  @endif>{{strtoupper($vendedor->name)}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="comisionsubagente" class="form-label mt-2 "><b>COMISION SUB AGENTE % *</b></label>
                            <input type="text" class="form-control" id="comisionsubagente" name="comisionsubagente" value="{{isset($polizaSelect) ? strtoupper($polizaSelect[0]->comi_sub_agente):""}}">
                        </div>
                        <div class="form-group">
                            <label for="importecomisionsubagente" class="form-label mt-2 "><b>IMPORTE COMISION SUB AGENTE * </b></label>
                            <input type="text" class="form-control" id="importecomisionsubagente" name="importecomisionsubagente"  >
                        </div>
                        <div class="form-group">
                            <label for="num_cuotas" class="form-label mt-2 "><b># DE CUOTAS*   </b></label>
                            <input type="text" class="form-control" id="num_cuotas" name="num_cuotas">
                        </div>

                        <div class="form-group">
                            <label for="num_primera_cuota" class="form-label mt-2 "><b>NUM.PRIMERA CUOTA *</b></label>
                            <input type="text" class="form-control" id="num_primera_cuota" name="num_primera_cuota">
                        </div>

                        <div class="form-group">
                            <label for="importe_primera_cuota" class="form-label mt-2 "><b>IMPORTE PRIMERA CUOTA*</b></label>
                            <input type="text" class="form-control" id="importe_primera_cuota" name="importe_primera_cuota">
                        </div>
                        <div class="form-group">
                            <label for="fecha_primera_cuota" class="form-label mt-2 "><b>FECHA PRIMERA CUOTA *</b></label>
                            <input type="date" class="form-control" id="fecha_primera_cuota" name="fecha_primera_cuota"
                            value="{{self::addRestFecha("Y-m-d","+10 day")}}">
                        </div>

                        
 
                    </div>
                    <br>
                    <button class="btn btn-outline-success rounded">Guardar</button>
                </form>
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
     let comisionCompania = $('#comisioncompania');
     let comisionsubagente = $('#comisionsubagente');
     let importeSubAgente = $('#importecomisionsubagente');
     let importeCompania = $('#importecomisioncompania');
     let PrimaNeta = $('#prima_neta');

     importeCompania.click(function(){
        if(PrimaNeta.val().trim().length == 0)
        {
           importeCompania.val("0.00")
        }else{
            importeCompania.val(PrimaNeta.val()*(comisionCompania.val()/100));
        }
     });

     comisionCompania.click(function(){
        if(PrimaNeta.val().trim().length == 0)
        {
           importeCompania.val("0.00")
        }else{
            importeCompania.val(PrimaNeta.val()*(comisionCompania.val()/100));
        }
     });

     comisionsubagente.click(function(){
        if(PrimaNeta.val().trim().length == 0)
        {
           importeSubAgente.val("0.00")
        }else{
            importeSubAgente.val(PrimaNeta.val()*(comisionsubagente.val()/100));
        }
     });

     importeSubAgente.click(function(){
        if(PrimaNeta.val().trim().length == 0)
        {
           importeSubAgente.val("0.00")
        }else{
            importeSubAgente.val(PrimaNeta.val()*(comisionsubagente.val()/100));
        }
     });



     
  });
</script>
@endsection