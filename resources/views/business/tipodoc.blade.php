@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Gestionar Documentos')

@section('css')
  <style>
    
    td.hide_me
    {
      display: none;
    }
    #lista_documentos>thead>tr>th{
      background:  linear-gradient(135deg,#85c1e9 100%);
    }
  </style>
@endsection

@section('contenido')
 <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background:linear-gradient(135deg, #5a7ca8 0%, #7fb6e0 100%);" ><h5 class="text-white letra">Gestión de Documentos</h5></div>
        <div class="card-body">
             <div class="row mt-2">
                <div class="col-xl-3 col-lg-3 col-md-4 col-12">
                    <button class="btn btn-outline-primary form-control" id="create"><b>Agregar uno nuevo <i class="fas fa-plus"></i></b></button>
                </div>
             </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped nowrap responsive" id="lista_documentos"
                    style="width: 100%">
                    <thead>
                        <tr>
                            <th class="py-3 letra">Acciones</th>
                            <th class="py-3 letra">Nombre Documento</th>
                            <th class="py-3 letra">Serie</th>
                            <th class="py-3 letra">Correlativo Inicial</th>
                            <th class="py-3 letra">Correlativo Final</th>
                            <th class="py-3 letra">Estado</th>
                            <th class="py-3 letra">Sucursal</th>   
                        </tr>
                    </thead>
        
                </table>
            </div>
        </div>
        </div>
    </div>
 </div>
 {{--- CREAR NUEVO DOCUMENTO ---}}
 <div class="modal fade" id="create_documento">
     <div class="modal-dialog modal-xl">
        <div class="modal-content">
         <div class="modal-header"
                style="background: linear-gradient(135deg,#85c1e9 100%);">
                <h5 class="letra text-white">Crear Documento</h5>
         </div>
         <div class="modal-body">
            <form action="" method="post" id="form_save_doc">
                <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                @if ($this->profile()->rol === "admin_general")
                   <div class="row">
                    <div class="col-12">
                        <div class="form-floating mb-2">
                            <select name="sede" id="sede" class="form-control">
                                @foreach ($sedes as $sede)
                                <option value="{{$sede->id_sede}}">{{strtoupper($sede->namesede)}}</option>
                                @endforeach
                            </select>
                            <label for="sede"><b>Seleccione la Sucursal</b></label>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                     <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <div class="form-floating mb-2">
                            <select name="tipo" id="tipo" class="form-control">
                                <option value="hc">HISTORIAL CLINICO</option>
                                <option value="cm">CITA MEDICA</option>
                                <option value="t">TRIAJE</option>
                                <option value="r">GENERAR RECIBO CLINICA</option>
                                <option value="c">COMPRAS - FARMACIA</option>
                                <option value="v">VENTAS -FARMACIA</option>
                            </select>
                            <label for="tipo">TIPO *</label>
                        </div>
                     </div>
                     <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                       <div class="form-floating mb-2">
                         <input type="text" name="nombredoc" id="nombredoc" class="form-control" placeholder="Nombre del documento...">
                         <label for="nombredoc">Nombre del documento *</label>
                        </div>
                     </div>
                     <div class="col-xl-4 col-lg-3 col-md-6 col-12">
                       <div class="form-floating mb-2">
                        <input type="text" name="seriedoc" id="seriedoc" class="form-control" placeholder="HCE0001">
                        <label for="seriedoc">Serie del documento *</label>
                      </div>
                     </div>
                     <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                        <div class="form-floating mb-2">
                            <input type="text" name="correlativodocinicial" id="correlativodocinicial" class="form-control"
                                placeholder="Correlativo inicial(0)..." value="0">
                            <label for="correlativodocinicial">Correlativo Inicial *</label>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-12">
                        <div class="form-floating mb-2">
                            <input type="text" name="correlativodocfinal" id="correlativodocfinal" class="form-control"
                                placeholder="Correlativo Final(0)..." value="0">
                            <label for="correlativodocfinal">Correlativo Final *</label>
                        </div>
                    </div>
                </div>
            </form>
         </div>
         <div class="modal-footer border-2">
            <button class="btn_3d" id="save_doc"><b>Guardar <i class="fas fa-save"></i></b></button>
         </div>
        </div>
     </div>
 </div>
 {{--EDITAR--}}
 <div class="modal fade" id="editar_documento">
     <div class="modal-dialog modal-xl">
        <div class="modal-content">
         <div class="modal-header"
                style="background: linear-gradient(to bottom, rgba(254,252,234,1) 0%,rgba(241,218,54,1) 100%);">
                <h5 class="letra text-dark">Editar Documento</h5>
         </div>
         <div class="modal-body">
            <form action="" method="post" id="form_update_doc">
                <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                @if ($this->profile()->rol === "admin_general")
                   <div class="row">
                    <div class="col-12">
                        <div class="form-floating mb-2">
                            <select name="sedeeditar" id="sedeeditar" class="form-control">
                                @foreach ($sedes as $sede)
                                <option value="{{$sede->id_sede}}">{{strtoupper($sede->namesede)}}</option>
                                @endforeach
                            </select>
                            <label for="sedeeditar"><b>Seleccione la Sucursal</b></label>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                     <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <div class="form-floating mb-2">
                            <select name="tipoeditar" id="tipoeditar" class="form-control">
                                <option value="hc">HISTORIAL CLINICO</option>
                                <option value="cm">CITA MEDICA</option>
                                <option value="t">TRIAJE</option>
                                <option value="r">GENERAR RECIBO CLINICA</option>
                                <option value="c">COMPRAS - FARMACIA</option>
                                <option value="v">VENTAS -FARMACIA</option>
                            </select>
                            <label for="tipoeditar">TIPO *</label>
                        </div>
                     </div>
                     <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                       <div class="form-floating mb-2">
                         <input type="text" name="nombredoceditar" id="nombredoceditar" class="form-control" placeholder="Nombre del documento...">
                         <label for="nombredoceditar">Nombre del documento *</label>
                        </div>
                     </div>
                     <div class="col-xl-4 col-lg-3 col-md-6 col-12">
                       <div class="form-floating mb-2">
                        <input type="text" name="seriedoceditar" id="seriedoceditar" class="form-control" placeholder="HCE0001">
                        <label for="seriedoceditar">Serie del documento *</label>
                      </div>
                     </div>
                     <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="form-floating mb-2">
                            <input type="text" name="correlativodocinicialeditar" id="correlativodocinicialeditar" class="form-control"
                                placeholder="Correlativo inicial(0)..." value="0">
                            <label for="correlativodocinicialeditar">Correlativo Inicial *</label>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-12">
                        <div class="form-floating mb-2">
                            <input type="text" name="correlativodocfinaleditar" id="correlativodocfinaleditar" class="form-control"
                                placeholder="Correlativo Final(0)..." value="0">
                            <label for="correlativodocfinaleditar">Correlativo Final *</label>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-12">
                        <div class="form-floating mb-2">
                             <select name="estado" id="estado" class="form-select">
                                <option value="a">ACTIVO</option>
                                <option value="i">INACTIVO</option>
                             </select>
                            <label for="estado">Estado *</label>
                        </div>
                    </div>
                </div>
            </form>
         </div>
         <div class="modal-footer border-2">
            <button class="btn_3d" id="update_doc"><b>Guardar cambios  <i class="fas fa-save"></i></b></button>
         </div>
        </div>
     </div>
 </div>
@endsection

@section('js')
<script src="{{ URL_BASE }}public/js/control.js"></script>
<script src="{{ URL_BASE }}public/js/documentos.js"></script>
 <script>
  var TablaDocumentos,DOCID;
  var TOKEN = "{{$this->Csrf_Token()}}";
  var RUTA = "{{URL_BASE}}";
  var TablaListaDocumentos;
  $(document).ready(function(){
    showTypeDocumentEmition();
    ConfirmEliminadoDocumento(TablaDocumentos,'#lista_documentos tbody');
    EditarDocumento(TablaDocumentos,'#lista_documentos tbody');
    $('#create').click(function(){
        $('#create_documento').modal("show");
    });
    $('#save_doc').click(function(){
        if($('#nombredoc').val().trim().length == 0){
             $('#nombredoc').focus();
        }else{
            if($('#seriedoc').val().trim().length == 0){
                $('#seriedoc').focus();
            }else{
                GuardarDocumentoEmision();
            }
        }
    });
    $('#nombredoc').keyup(function(){
        $(this).val($(this).val().toUpperCase());
    });
    $('#seriedoc').keyup(function(){
        $(this).val($(this).val().toUpperCase());
    });
    $('#update_doc').click(function(){
        modificar(DOCID);
    });

    enter("nombredoc","seriedoc");
    enter("correlativodocinicial","correlativodocfinal");
  });

  function GuardarDocumentoEmision(){
    axios({
        url:RUTA+"documento-emision/store",
        method:"POST",
        data:$('#form_save_doc').serialize()
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE ERROR DEL SISTEMA!!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById('create_documento')
            });
        }else{
            Swal.fire({
                title:"CORRECTO!!!",
                text:response.data.success,
                icon:"success",
                target:document.getElementById('create_documento')
            }).then(function(){
                $('#form_save_doc')[0].reset();
                showTypeDocumentEmition();
            });
        }
    });
  }
  /// MOSTRAR DOCUMENTOS
 </script> 
@endsection