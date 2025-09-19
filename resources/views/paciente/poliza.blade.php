@extends($this->Layouts("dashboard"))

@section("title_dashboard","Polizas-Cliente")

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
                    <h4>Polizas</h4>
                </div>
                <div class="card-text">
                    @php
                        $url = isset($cliente)? URL_BASE.'poliza/create/'.$cliente[0]->id_paciente:'#';
                    @endphp
                    <a href="{{$url}}" class="btn btn-success">Agregar poliza <i class="fas fa-plus"></i></a>
                </div>
                <div class="card-text">
                    <b class="text-primary ">Datos del cliente</b>
                    <br>
                    <div class="row mt-2 border border-primary p-2" style="border-radius: 4%">
                        
                        <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                            <label for="nombrescli" class="mt-2"><b>Nombre completo</b></label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-6 col-12">
                            <input type="text" id="nombrescli" class="form-control" value="{{isset($cliente) ? strtoupper($cliente[0]->apellidos."  ".$cliente[0]->nombres):''}}">
                        </div>
                       
                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-1">
                            <label for="tipodoc" class="mt-2"><b>Tipo documento</b></label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-6 col-12 mt-1">
                            <input type="text" id="tipodoc" class="form-control" value="{{isset($cliente) ? $cliente[0]->name_tipo_doc:''}}">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-1">
                            <label for="numdoc" class="mt-2"><b># Documento</b></label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-6 col-12 mt-1">
                            <input type="text" id="numdoc" class="form-control" value="{{isset($cliente) ? $cliente[0]->documento:''}}">
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-1">
                            <label for="num_telef" class="mt-2"><b># Teléfono</b></label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-6 col-12 mt-1">
                            <input type="text" id="num_telef" class="form-control" value="{{isset($cliente) ? $cliente[0]->telefono:''}}">
                        </div>
                    </div>

                    <div class="row">
                        @if ($this->getSession("mensaje")== 'ok')
                        <div class="alert alert-success">
                         <b>Poliza registrado correctamente!</b>
                         </div>
                          
                        @endif
                        @php
                        $this->destroySession("mensaje")
                        @endphp
                        @if ($this->getSession("mensaje_error") == 'error')
                        <div class="alert alert-danger">
                         <b>Hubo un error al intentar registrar la poliza!</b>
                         </div>
                          
                        @endif
                        @php
                        $this->destroySession("mensaje_error")
                        @endphp
                        <div class="table-responsive">
                            <table id="tabla_polizas" class="table table-bordered table-striped responsive nowrap" id="tabla-tipodocumentos" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>CONTRATANTE</th>
                                        <th>COMPAÑIA</th>
                                        <th>RAMO</th>
                                        <th>PRODUCTO</th>
                                        <th>POLIZA</th>
                                        <th>MONEDA</th>
                                        <th>VIG.INICIO</th>
                                        <th>VIG.FIN</th>
                                        <th>SUB AGENTE</th>
                                        <th>m.ASEGURADA</th>
                                        <th>EJECUTIVO</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if (isset($TodasPolizasPorCliente) and count($TodasPolizasPorCliente) > 0)
                                      @php
                                          $item = 0;
                                      @endphp    
                                    @foreach ($TodasPolizasPorCliente as $poliza)
                                        @php
                                            $item++;
                                        @endphp    
                                        <tr>
                                            <td>{{$item}}</td>
                                        <td>{{strtoupper($poliza->asegurado)}}</td>
                                        <td>{{strtoupper($poliza->name_compania)}}</td>
                                        <td>{{strtoupper($poliza->name_ramo)}}</td>
                                        <td>{{strtoupper($poliza->descripcion)}}</td>
                                        <td>{{strtoupper($poliza->num_poliza)}}</td>
                                        <td>{{strtoupper($poliza->moneda)}}</td>
                                        <td>{{strtoupper($poliza->vigencia_inicio)}}</td>
                                        <td>{{strtoupper($poliza->vigencia_fin)}}</td>
                                        <td>{{strtoupper($poliza->subagente)}}</td>
                                        <td>
                                            @if ($poliza->desc_asegurada != null)
                                            {{strtoupper($poliza->desc_asegurada)}}
                                              @else 
                                              <span class= "text-danger">No especifica descripción...</span>
                                            @endif
                                        </td>
                                        <td>{{strtoupper($poliza->ejecutivo)}}</td>
                                        <td>
                                            <a href="{{URL_BASE}}poliza/{{$poliza->id_poliza}}/prima/plan-pagos" class="btn btn-primary btn-sm">Primas</a>
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
    </div>
</div>

 
@endsection

@section('js')
{{-- JS ADICIONALES ---}}
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>
 $(document).ready(function(){
    $('#tabla_polizas').DataTable();
});

</script>
@endsection