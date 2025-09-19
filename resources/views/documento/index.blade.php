@extends($this->Layouts("dashboard"))

@section("title_dashboard","crear tipo documento")

@section('css')
    <style>
        #tabla-tipodocumentos>thead>tr>th{
            background:linear-gradient(to bottom, #6db3f2 17%,#54a3ee 41%,#54a3ee 54%,#3690f0 75%,#1e69de 100%);;
            color: #f3efef;
            padding: 20px;
        }
    </style>
@endsection
@section('contenido')
    <div class="row">
        <div class="card">
                <div class="card-header" style="background: linear-gradient(to bottom, #6db3f2 17%,#54a3ee 41%,#54a3ee 54%,#3690f0 75%,#1e69de 100%);">
                    <h5 class="letra text-white">Gestión Tipo Documentos</h5>
                </div>
                <div class="card-body">
                     @if ($this->ExistSession("mensaje"))
                <div class="m-2">

                    @if ($this->getSession("mensaje") == 1)
                    <div class="alert alert-success">
                        <b> Tipo de documento creado correctamente</b>
                    </div>
                    @endif
                    {{--- DESTRUIMOS LA SESSION----}}
                    @php
                    $this->destroySession("mensaje")
                    @endphp

                </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <button class="btn_blue mb-2 mt-2 col-xl-3 col-lg-4 col-md-5 col-12"
                        onclick="location.href='{{$this->route('new-tipo-documento')}}'"><i class='bx bx-plus'></i>
                        Agregar uno nuevo</button>
                    </div>
                </div>
              <div class="table-responsive-sm">
                <table class="table table-bordered table-hover table-striped table-sm responsive nowrap" id="tabla-tipodocumentos" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>TIPO DOCUMENTO</th>
                            <th class="text-center">GESTIONAR</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                        $Contador = 0;
                        @endphp
                        @foreach ($TipoDocumentos as $tipoDocumento)
                        @php $Contador++ @endphp
                        <tr>
                            <td>{{$Contador}}</td>
                            <td>{{strtoupper($tipoDocumento->name_tipo_doc)}}</td>
                            <td class="text-center">
                                <div class="row justify-content-center">
                                    @if ($tipoDocumento->estado === '1')
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
                                        <button class="btn rounded-pill btn-warning btn-sm" id="editar"
                                          onclick="editar(`{{$tipoDocumento->id_tipo_doc}}`,`{{$tipoDocumento->name_tipo_doc}}`)"><i
                                          class='bx bxs-edit-alt'></i></button>
                                    </div>
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
                                        <form action="" method="post" id="form-delete">
                                            <button class="btn rounded-pill btn-primary btn-sm"
                                                onclick="event.preventDefault(); ConfirmDelete(`{{$tipoDocumento->id_tipo_doc}}`,`{{$tipoDocumento->name_tipo_doc}}`,event)"><i
                                                    class='bx bx-message-square-x'></i></button>
                                        </form>
                                    </div>
                                    @endif

                                    @if ($tipoDocumento->estado === '0')
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
                                        <button class="btn rounded-pill btn-success btn-sm" id="activar" onclick="ActivarTipoDocumento(`{{$tipoDocumento->id_tipo_doc}}`)"><i class='bx bx-check'></i></button>
                                    </div>
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
                                        <button class="btn rounded-pill btn-danger btn-sm" id="activar" onclick="ConfirmBorrado(`{{$tipoDocumento->id_tipo_doc}}`,`{{$tipoDocumento->name_tipo_doc}}`)"><i class='bx bx-trash'></i></button>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
              </div>
                </div>
        </div>
    </div>
{{--- MODAL PARA EDITAR LOS TIPOS DE DOCUMENTOS -----}}
<div class="modal fade" id="modal-editar-tipo-documento" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(to bottom, #ffb76b 0%,#ffa73d 55%,#ff7c00 64%,#ff7f04 100%);">
                <strong><h5 class="letra text-white">Editar tipo documento</h5></strong>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" method="post" id="form-modificar-tipo-documento">

                <div class="modal-body">
                    <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                    <div class="form-group">
                        <label for="documento" class="form-label">Tipo documento (*)</label>
                        <input type="text" name="documento" id="documento" class="form-control"
                            placeholder="Tipo documento...">
                        <div class="row justify-content-center">
                            <div class="col-xl-5 col-lg-5 col-md-6 col-sm-5 col-12 mt-2">
                                <button type="submit" class="btn rounded-pill btn-success form-control" id="save"
                                    name="save">Guardar <i class='bx bx-save'></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
{{-- JS ADICIONALES ---}}
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>
    var IdTipoDoc;
    var URL_BASE_ = "{{URL_BASE}}";

 $(document).ready(function(){

    let BotonSave = $('#save');

    let InputTipoDocumento = $('');
  
    var TableTipoDocumentos =  $('#tabla-tipodocumentos').DataTable({
        language:SpanishDataTable(),
    });

    /// boton editar

    BotonSave.click(function(evento){
     evento.preventDefault()
     GuardarCambios();

    });
    

 });  

 /// llama al modal para editar tipo documentos
 function editar(id,TipoDoc)
 {

    IdTipoDoc = id; /// capturamos el id

    $('#documento').val(TipoDoc)

    FocusInputModal('modal-editar-tipo-documento','documento');

    $('#modal-editar-tipo-documento').modal('show')
 }

 /// guardar cambios del tipo de documento

 function GuardarCambios()
 {
   DataForm = $('#form-modificar-tipo-documento').serialize();

   $.ajax({
    url:"{{$this->route('update-tipo-documento/')}}"+IdTipoDoc,
    method:"POST",
    data:DataForm,
    success:function(response)
    {
       if(response === 'existe')
       {
        Message("warning","¡ADVERTENCIA!","No se actualzaron los datos",'modal-editar-tipo-documento')
       }
       else
       {
        if(response == 1)
        {
            Message("success","¡SUCCESSFULL!","Datos modificados correctamente",'modal-editar-tipo-documento')  
        }
        else
        {
            Message("error","¡ERROR!","Error al actualizar los datos de tipo documento",'modal-editar-tipo-documento')
        }
       }
    }
   });

 
 }

  /// Eliminar tipo documento

  function ActivarTipoDocumento(id)
 {
    
    /// capturamos el id que deseamoa eliminar

    IdTipoDoc = id;
 
    $.ajax({
        url:"{{$this->route('activar/tipo-documento/')}}"+id,
        method:"POST",
        data:{
            token_:"{{$this->Csrf_Token()}}"
        },
        dataType:"json",
        success:function(response){
            if(response.response!=undefined){
                if(response.response === 'ok'){
                    Swal.fire(
                        {
                            title:"Mensaje del sistema!",
                            text:"Tipo documento activado nuevamente!",
                            icon:"success"
                        }
                    ).then(function(){
                        location.href='{{URL_BASE}}tipo-documentos-existentes';
                    });
                }else{
                    Swal.fire(
                        {
                            title:"Mensaje del sistema!",
                            text:"Error al eliminar el tipo de documento!",
                            icon:"error"
                        }
                    ) 
                }
            }else{
                Swal.fire(
                        {
                            title:"Mensaje del sistema!",
                            text:"Error, token invalid!",
                            icon:"error"
                        }
                    ) 
            }
        }
    })
  
 }

  /// Eliminar tipo documento

  function ConfirmDelete(idEliminar,documento,evento)
 {

    evento.preventDefault()
    
    /// capturamos el id que deseamoa eliminar

    IdTipoDoc = idEliminar;

    /// token
 
   Swal.fire({
     title: 'Estas seguro de eliminar al tipo documento '+documento+'?',
     text: "al presionar que si, se inactivará este documento, y ya no será mostrado en el sistema para su uso correspondiente!",
     icon: 'question',
     showCancelButton: true,
     confirmButtonColor: '#3085d6',
     cancelButtonColor: '#d33',
     confirmButtonText: 'Si,eliminar!',
   }).then((result) => {
  if (result.isConfirmed) {
     DeleteDocumento(IdTipoDoc);
   }
 })
  
 }

 function ConfirmBorrado(idEliminar,documento)
 {
    
    /// capturamos el id que deseamoa eliminar

    IdTipoDoc = idEliminar;

    /// token
 
   Swal.fire({
     title: 'Estas seguro de borrar al tipo documento '+documento+'?',
     text: "al presionar que si, se borrar definitivamente y no podras recuperarlo!",
     icon: 'question',
     showCancelButton: true,
     confirmButtonColor: '#3085d6',
     cancelButtonColor: '#d33',
     confirmButtonText: 'Si,eliminar!',
     target:document.getElementById('layout-menu')
   }).then((result) => {
  if (result.isConfirmed) {
     BorradoDocumento(IdTipoDoc);
   }
 })
  
 }

 function DeleteDocumento(id)
 {
    DataFormDel = $('#form-delete').serialize();
  $.ajax({
    url:"{{$this->route('destroy-tipo-documento/')}}"+id,
    method:"POST",
    data:{token_:"{{$this->Csrf_Token()}}"},
    success:function(response)
    {
    Message('success','Mensaje del sistema','Tipo documento eliminado',null);
    }
  })
 }

 function BorradoDocumento(id)
 {
     
  $.ajax({
    url:"{{$this->route('tipo-doc/borrar/')}}"+id,
    method:"POST",
    data:{token_:"{{$this->Csrf_Token()}}"},
    dataType:"json",
    success:function(response)
    {
     if(response.response != undefined){
        if(response.response === 'ok'){
            Message('success','Mensaje del sistema','Tipo documento borrado por completo!',null);
        }else{
            if(response.response === 'existe'){
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error, el tipo documento no se puede eliminar, porque se esta haciendo uso ese registro!",
                    icon:"error"
                })
            }
            else{
                Swal.fire(
                {
                    title:"Mensaje del sistema!",
                    text:"Error al eliminar tipo documento!",
                    icon:"error"
                }
            )
            }
        }
     }else{
        Swal.fire(
                {
                    title:"Mensaje del sistema!",
                    text:"Error, token invalid!",
                    icon:"error"
                }
            ) 
     }
    }
  })
 }
</script>
@endsection