@extends($this->Layouts("dashboard"))

@section("title_dashboard","Crear-Poliza")

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
                    <h4> Crear nueva poliza</h4>
                    <a href="{{URL_BASE}}polizas/{{isset($cliente) ? $cliente[0]->id_paciente:""}}">Volver a la lista de polizas  </a>
                </div>
                <form action="{{URL_BASE}}poliza/store/{{isset($cliente) ? $cliente[0]->id_paciente:""}}" method="post">
                    <div class="row">
                        <div class="form-group">
                            <label for="num_poliza" class="form-label mt-2 "><b>Número poliza *</b></label>
                            <input type="text" class="form-control" id="num_poliza" name="num_poliza">
                        </div>
                        <div class="form-group">
                            <label for="asegurado" class="form-label mt-2 "><b>Asegurado *</b></label>
                            <input type="text" class="form-control" id="asegurado" name="asegurado" value="{{isset($cliente) ? strtoupper($cliente[0]->apellidos." ".$cliente[0]->nombres):""}}">
                        </div>

                        <div class="form-group">
                            <label for="sub_agente" class="form-label mt-2 "><b>Sub Agente </b></label>
                            <select name="sub_agente" id="sub_agente" class="form-select">
                                @if (isset($vendedores))
                                    @foreach ($vendedores as $vendedor)
                                        <option value="{{$vendedor->id_usuario}}">{{strtoupper($vendedor->name)}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="compania" class="form-label mt-2 "><b>Compañía *</b></label>
                            <select name="compania" id="compania" class="form-select">
                                @if (isset($companias))
                                @foreach ($companias as $compania)
                                    <option value="{{$compania->id_compania}}">{{strtoupper($compania->name_compania)}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ramo" class="form-label mt-2 "><b>Ramo*</b></label>
                            <select name="ramo" id="ramo" class="form-select">
                                @if (isset($Ramos))
                                @foreach ($Ramos as $ramo)
                                    <option value="{{$ramo->id_ramo}}">{{strtoupper($ramo->name_ramo)}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="producto" class="form-label mt-2 "><b>Producto*</b></label>
                            <select name="producto" id="producto" class="form-select"></select>
                        </div>

                        <div class="form-group">
                            <label for="comision_compania" class="form-label mt-2 "><b>Comisión compañia % </b></label>
                            <input type="text" class="form-control" id="comision_compania" name="comision_compania">
                        </div>

                        <div class="form-group">
                            <label for="comision_agente" class="form-label mt-2 "><b>Comisión sub agente % </b></label>
                            <input type="text" class="form-control" id="comision_agente" name="comision_agente">
                        </div>

                        <div class="form-group">
                            <label for="comision_agente" class="form-label mt-2 "><b>Tipo vegencia *</b></label>
                            <select name="tipo_vigencia" id="tipo_vigencia" class="form-select">
                                <option value="" selected disabled> --- Seleccione un tipo de vigencia --- </option>
                                <option value="anual">ANUAL</option>
                                <option value="declaracion mensual">DECLARACION MENSUAL</option>
                                <option value="periodica">PERIODICA</option>
                                <option value="no renovable">NO RENOVABLE</option>
                                <option value="eventual">EVENTUAL</option>
                                <option value="flotante">FLOTANTE</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="vigencia_inicio" class="form-label mt-2 "><b>Vigencia inicio*   </b></label>
                            <input type="date" class="form-control" id="vigencia_inicio" name="vigencia_inicio"
                            value="{{self::FechaActual("Y-m-d")}}">
                        </div>

                        <div class="form-group">
                            <label for="vigencia_fin" class="form-label mt-2 "><b>Vigencia fin *</b></label>
                            <input type="date" class="form-control" id="vigencia_fin" name="vigencia_fin"
                            value="{{self::addRestFecha("Y-m-d","+5 day")}}">
                        </div>

                        <div class="form-group">
                            <label for="fecha_emision" class="form-label mt-2 "><b>Fecha emisión *</b></label>
                            <input type="date" class="form-control" id="fecha_emision" name="fecha_emision"
                            value="{{self::FechaActual("Y-m-d")}}">
                        </div>

                        <div class="form-group">
                            <label for="moneda" class="form-label mt-2 "><b>Moneda *</b></label>
                            <input type="text" class="form-control" id="moneda" name="moneda">
                        </div>

                        <div class="form-group">
                            <label for="desc_aseguradora" class="form-label mt-2 "><b>Breve descripción de lo que asegura </b></label>
                            <textarea name="desc_aseguradora" id="desc_aseguradora" cols="30" rows="5" class="form-control" placeholder="Escriba aquí...."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ejecutivo" class="form-label mt-2 "><b>Ejecutivo de la cuenta</b></label>
                             <select name="ejecutivo" id="ejecutivo" class="form-select">
                                <option value="" selected disabled> --- Seleccionar ejecutivo ----</option>
                                @if (isset($ejecutivos))
                                @foreach ($ejecutivos as $ejecutivo)
                                    <option value="{{$ejecutivo->id_usuario}}">{{strtoupper($ejecutivo->name)}}</option>
                                @endforeach
                                @endif
                             </select>
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
    let Ramo = $('#ramo');
    let producto = $('#producto');

    Ramo.change(function(){
        let option = '<option selected disabled>--- Seleccione producto ----</option>';
       $.ajax({
        url:RUTA+"/ramo/"+$(this).val()+"/productos",
        method:"GET",
        dataType:"json",
        success:function(response){
           response.response.forEach(element => {
            option+=`<option value=`+element.id_producto+`>`+element.descripcion+`|`+element.comision_compania+`</option>`;
           }); 

           $('#producto').html(option);
        }
       })
    });

    producto.change(function(){
        let desc = $(this).find('option:selected').text();
        desc= desc.split("|");

        $('#comision_compania').val(desc[1]);
        $('#comision_agente').val(desc[1])
    });
  });
</script>
@endsection