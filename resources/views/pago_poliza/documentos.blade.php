@extends($this->Layouts("dashboard"))

@section("title_dashboard","Agregar-documento")

@section('css')
    <style>
        #tabla_documentos>thead>tr>th{
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
                @if ($this->getSession("mensaje")== 'ok')
                <div class="alert alert-success">
                 <b>Documento subido correctamente!</b>
                 </div>
                  
                @endif
                @php
                $this->destroySession("mensaje")
                @endphp
                @if ($this->getSession("mensaje_error") == 'error')
                <div class="alert alert-danger">
                 <b>Hubo un error al intentar subir el documento!</b>
                 </div>
                  
                @endif
                @php
                $this->destroySession("mensaje_error")
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
                <button  id="adddoc" class="btn btn-primary">Agregar documento <i class="fas fa-plus"></i></button>
                <button  id="hideform" class="btn btn-danger" style="display: none">Ocultar formulario<i class="fas fa-plus"></i></button>
                <form action="{{URL_BASE}}poliza/plan-pagos/documentos/{{isset($cuotasPoliza)?$cuotasPoliza[0]->id_prima_pago:''}}" method="post" class="mt-2" style="display: none" id="form_doc"
                     enctype="multipart/form-data">
                    <label for="doc">Seleccione pdf *</label>
                    <input type="file" class="form-control" name="documento">
                    <button class="btn btn-success mt-2" id="docsub">Subir pdf</button>
                </form>
                <div class="card-text table-responsive">
                    <table id="tabla_documentos" class="table table-bordered table-striped responsive nowrap mt-3"  style="width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>DOCUMENTO PDF</th>
                                
                                <th>ACCIONES</th>
                                
                            </tr>
                        </thead>
        
                        <tbody>
                            @if (isset($documentosData))
                                @foreach ($documentosData as $key=> $doc)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$doc->documento}}</td>
                                        <td>
                                            <a href="{{URL_BASE}}public/asset/documentos_pdf/{{$doc->documento}}" target="_blank" class="btn btn-outline-primary"><i class="fa-solid fa-cloud-arrow-down"></i></a>
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
     $('#tabla_documentos').DataTable({})

     $('#adddoc').click(function(){
        $('#form_doc').show(300);$('#adddoc').hide(300);
        $('#hideform').show();
     });

     $('#hideform').click(function(){
        $('#form_doc').hide(300);$('#adddoc').show();
        $('#hideform').hide();
     });
  });
</script>
@endsection