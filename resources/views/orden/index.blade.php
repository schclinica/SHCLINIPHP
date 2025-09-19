@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Gestionar órdenes')
 
@section('css')
   <style>
     #tabla_ordenes>thead>tr>th{
        padding: 20px;
        background:linear-gradient(#3498db);
      }
   </style>
@endsection
@section('contenido')
<div class="row" id="app_examenes">
    <div class="col-12">
     <div class="card">
       <div class="card-header" style="background: linear-gradient(135deg,#2a5298)"><h5 class="text-white letra">Gestionar órdenes</h5></div>   
       <div class="card-body">
         <div class="card-text table-responsive">
            <div class="row">
               <div class="col-12">
                  <button class="btn_blue col-xl-3 col-lg-3 col-md-5 col-12 mb-3" id="create_orden">Agregar uno nuevo <i class='bx bx-plus'></i></button>
               </div>
            </div>
             <table class="table table-bordered table-striped table-sm responsive nowrap" id="tabla_ordenes" style="width: 100%">
                <thead>
                    <tr>
                    <th class="text-white">Acciones</th> 
                    <th class="text-white">Código</th>
                    <th class="text-white">Nombre Exámen</th>
                    <th class="text-white">Precio {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                    <th class="text-white">Categoría</th>
                     <th class="text-white">Tipo</th>
                    <th class="text-white">Estado</th>
                    </tr>
                </thead>
             </table>
         </div> 
       </div>
    </div>    
    </div>
</div>

{{--- CREAR EXAMENES---}}
<div class="modal fade" id="modal_create_orden">
   <div class="modal-dialog modal-dialog-md">
     <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%)">
         <h4  class="letra text-white">Crear nueva Orden</h4>
      </div>
      <div class="modal-body" id="loading_create_orden">
         <form action="" method="post" id="form_create_orden">
            <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
            
            <div class="form-group">
               <label for="tipo_orden" class="form-label"><b>Tipo órden <span class="text-danger">*</span></b></label>
                
               <select name="tipo_orden" id="tipo_orden" class="form-select"></select>
            </div>

            <div class="form-group">
               <label for="categoria_orden" class="form-label"><b>Categoría<span class="text-danger">*</span></b></label>
               <div class="input-group">
                  <select name="categoria_orden" id="categoria_orden" class="form-select"></select>
                  <button class="btn btn-outline-primary" id="create_categoria">Agregar <i class="fas fa-plus"></i></button>
               </div>
            </div>

            <div class="form-group">
               <label for="codigo_orden" class="form-label"><b>Código Órden<span class="text-danger">*</span></b></label>
               <input type="text" class="form-control" name="codigo_orden" id="codigo_orden" placeholder="Código órden....">
            </div>
            <div class="form-group">
               <label for="nombre_orden" class="form-label"><b>Nombre Órden<span class="text-danger">*</span></b></label>
               <input type="text" class="form-control" name="nombre_orden" id="nombre_orden" placeholder="Nombre órden....">
            </div>

            <div class="form-group">
               <label for="precio" class="form-label"><b>Precio {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}<span class="text-danger">*</span></b></label>
               <input type="text" class="form-control" name="precio" id="precio" placeholder="Precio órden....">
            </div>
         </form>
         <div class="form-goup mt-2" id="div_import_excel">
            <label for="import_excel" class="text-primary">
               Importar por excel <i class="fas fa-file-excel"></i>. <a href="#" id="view_form_excel">Dar click aquí</a>
            </label>
         </div>

         <div class="form-goup" id="div_save_form" style="display: none">
            <label for="form_register" class="text-primary">
               <i class="fas fa-file"></i>. <a href="#" id="view_form_register">Volver a mi formulario</a>
            </label>
         </div>
         <form action="" method="post" id="import_excel_orden" style="display: none">
            <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
            <div class="form-group">
               <label for="archivo_excel" class="form-label"><b>Seleccionar un archivo excel <span class="text-danger">*</span></b></label>
               <input type="file" name="archivo_excel" id="archivo_excel" class="form-control">
            </div>
         </form>
      </div>

      <div class="modal-footer border-2">
         <button class="btn-save" id="save_orden"><span id="text_save_orden">Guardar</span> <i class="fas fa-save"></i></button>
      </div>
     </div>
   </div>
</div>

{{--EDITAR AL EXAMEN ---}}
<div class="modal fade" id="modal_editar_orden">
   <div class="modal-dialog modal-dialog-md">
     <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(to bottom, #6495ED 90%,rgba(252,205,77,1) 50%,rgba(248,181,0,1) 51%,rgba(251,223,147,1) 100%);">
         <h4  class="letra text-dark">Editar Exámen</h4>
      </div>
      <div class="modal-body">
         <form action="" method="post" id="form_orden_editar">
            <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
            <div class="form-group">
               <label for="tipo_orden_editar" class="form-label"><b>Tipo órden <span class="text-danger">*</span></b></label>
               <div class="input-group">
                  <select name="tipo_orden_editar" id="tipo_orden_editar" class="form-select"></select>
               </div>
            </div>

            <div class="form-group">
               <label for="categoria_orden_editar" class="form-label"><b>Categoría<span class="text-danger">*</span></b></label>
               <div class="input-group">
                  <select name="categoria_orden_editar" id="categoria_orden_editar" class="form-select"></select>
               </div>
            </div>
            <div class="form-group">
               <label for="codigo_orden_editar" class="form-label"><b>Código Órden<span class="text-danger">*</span></b></label>
               <input type="text" class="form-control" name="codigo_orden_editar" id="codigo_orden_editar" placeholder="Código órden....">
            </div>
            <div class="form-group">
               <label for="nombre_orden_editar" class="form-label"><b>Nombre Órden<span class="text-danger">*</span></b></label>
               <input type="text" class="form-control" name="nombre_orden_editar" id="nombre_orden_editar" placeholder="Nombre órden....">
            </div>

            <div class="form-group">
               <label for="precio_editar" class="form-label"><b>Precio {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}<span class="text-danger">*</span></b></label>
               <input type="text" class="form-control" name="precio_editar" id="precio_editar" placeholder="Precio órden....">
            </div>
         </form>
         
      </div>

      <div class="modal-footer border-2">
         <button class="btn-save" id="update_orden">Guardar cambios<i class="fas fa-save"></i></button>
      </div>
     </div>
   </div>
</div>
{{-- ---}}




{{--CREAR TIPO ORDEN ---}}
<div class="modal fade" id="modal_create_tipo_orden" data-bs-backdrop="static">
   <div class="modal-dialog   modal-dialog-scrollable modal-lg" >
     <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
         <h4  class="letra text-white">Crear Tipo órden</h4>
      </div>
      <div class="modal-body" id="loading_create_tipo_orden">
          <form action="" method="post" id="form_tipo_orden">
            <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
            <div class="form-group">
               <label for="codigo_tipo_orden" class="form-label"><b>Código <span class="text-danger">*</span></b></label>
               <input type="text" class="form-control" name="codigo_tipo_orden" id="codigo_tipo_orden"  placeholder="Nombre tipo....">
               
            </div>

               <div class="form-group">
                <label for="nombre_tipo_orden" class="form-label"><b>Nombre Tipo <span class="text-danger">*</span></b></label>
                <input type="text" class="form-control" name="nombre_tipo_orden" id="nombre_tipo_orden" placeholder="Nombre tipo....">
               </div>
          </form>

          <div class="form-goup mt-2" id="div_import_excel_tipo_orden">
            <label for="import_excel_tipo_orden" class="text-primary">
               Importar por excel <i class="fas fa-file-excel"></i>. <a href="#" id="view_form_excel_tipo_orden">Dar click aquí</a>
            </label>
         </div>

         <div class="form-goup" id="div_save_form_tipo_orden" style="display: none">
            <label for="form_register_tipo_orden" class="text-primary">
               <i class="fas fa-file"></i>. <a href="#" id="view_form_register_tipo_orden">Volver a mi formulario</a>
            </label>
         </div>

         <form action="" method="post" id="import_excel_tipo_orden" style="display: none">
            <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
            <div class="form-group">
               <label for="archivo_excel_tipo_orden" class="form-label"><b>Seleccionar un archivo excel <span class="text-danger">*</span></b></label>
               <input type="file" name="archivo_excel_tipo_orden" id="archivo_excel_tipo_orden" class="form-control">
            </div>
         </form>

         <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover   nowrap responsive" id="tabla_tipo_examenes" style="width: 100%">
               <thead style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                  <tr>
                     <th class="py-3 text-white letra">Acciones</th>
                     <th class="py-3 text-white letra">Código</th>
                     <th class="py-3 text-white letra">Tipo exámen</th>
                     <th class="py-3 text-white letra">Estado</th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>

      <div class="modal-footer border-2">
         <button class="btn_blue" id="save_tipo_orden"><span id="text_save_tipo_orden">Guardar</span> <i class="fas fa-save"></i></button>
         <button class="btn btn-danger" id="cerrar_modal_tipo_orden"><b>Cerrar <i class="fas fa-close"></i></b></button>
      </div>
     </div>
   </div>
</div>

{{---CREAR VENTANA MODAL PARA CREAR LAS CATEGORIAS----}}

<div class="modal fade" id="modal_create_categoria" data-bs-backdrop="static">
   <div class="modal-dialog   modal-dialog-scrollable modal-xl" >
     <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
         <h4  class="letra text-white" id="text_modal_create_categoria">Crear categoría</h4>
      </div>
      <div class="modal-body" id="loading_create_categoria">
          <form action="" method="post" id="form_save_categoria">
            <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
            <div class="form-group">
               <label for="tipo_categoria" class="form-label"><b>TIPO <span class="text-danger">*</span></b></label>
               <div class="input-group">
                  <select name="tipo_categoria" id="tipo_categoria" class="form-select"></select>
                  <button class="btn btn-outline-primary" id="create_tipo_orden">Agregar <i class="fas fa-plus"></i></button>
               </div>
            </div>
            <div class="form-group">
               <label for="codigo_categoria" class="form-label"><b>Código <span class="text-danger">*</span></b></label>
               <input type="text" class="form-control" name="codigo_categoria" id="codigo_categoria"  placeholder="Código tipo....">
               
            </div>

               <div class="form-group">
                <label for="nombre_categoria" class="form-label"><b>Nombre Categoría <span class="text-danger">*</span></b></label>
                <input type="text" class="form-control" name="nombre_categoria" id="nombre_categoria" placeholder="Nombre categoría....">
               </div>
          </form>

          <div class="form-goup mt-2" id="div_import_excel_categoria">
            <label for="import_excel_categoria" class="text-primary">
               Importar por excel <i class="fas fa-file-excel"></i>. <a href="#" id="view_form_excel_categoria">Dar click aquí</a>
            </label>
         </div>

         <div class="form-goup" id="div_save_form_categoria" style="display: none">
            <label for="form_register_categoria" class="text-primary">
               <i class="fas fa-file"></i>. <a href="#" id="view_form_register_categoria">Volver a mi formulario</a>
            </label>
         </div>

         <form action="" method="post" id="import_excel_categoria" style="display: none">
            <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
            <div class="form-group">
               <label for="archivo_excel_categoria" class="form-label"><b>Seleccionar un archivo excel <span class="text-danger">*</span></b></label>
               <input type="file" name="archivo_excel_categoria" id="archivo_excel_categoria" class="form-control">
            </div>
         </form>

         <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover   nowrap responsive" id="tabla_categorias" style="width: 100%">
               <thead style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                  <tr>
                     <th class="py-3 text-white letra">Acciones</th>
                     <th class="py-3 text-white letra">Código</th>
                     <th class="py-3 text-white letra">Grupo</th>
                     <th class="py-3 text-white letra">Categoría</th>
                     <th class="py-3 text-white letra">Estado</th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>

      <div class="modal-footer border-2">
         <button class="btn_blue" id="save_categoria"><span id="text_save_categoria">Guardar</span> <i class="fas fa-save"></i></button>
         <button class="btn btn-danger" id="cerrar_modal_categoria"><b>Cerrar <i class="fas fa-close"></i></b></button>
      </div>
     </div>
   </div>
</div>
@endsection 

@section('js')
<script src="{{ URL_BASE }}public/js/control.js"></script>
<script src="{{ URL_BASE }}public/js/categoriaorden.js"></script>
<script src="{{ URL_BASE }}public/js/ordenes.js"></script>
<script>
   var TablaOrdenes;
   var TablaTipoExamenes;
   var accion = 'save_form';
   var accionTipo = 'save_form';
   var RUTA = "{{ URL_BASE }}" // la url base del sistema
   var TOKEN = "{{ $this->Csrf_Token() }}";
   var ExamenId;
   var TipoExamenId;
   var TablacategoriasOrden;
   var accionCategoria = 'save';
   var CATEGORIAIDORDEN;
 $(document).ready(function(){

   /// mostrar las ordenes
   mostrarOrdenes();
   ClickActivarOrden(TablaOrdenes,' #tabla_ordenes tbody');

   /// mostrar tipo examenes
   mostrarTipoOrdenes();
   ClickActivarTipoOrden(TablaTipoExamenes,'#tabla_tipo_examenes tbody'); 

   mostrarTipoOrdenesEdicionCombo();

   mostrarTipoCategoriaDisponibles();
   /*ENTER PARA formulario tipo orden*/
   enter('codigo_tipo_orden','nombre_tipo_orden');
   enter('nombre_tipo_orden','nombre_tipo_orden');

   enter('codigo_orden','nombre_orden');
    enter('nombre_orden','nombre_orden');

    /*ENTER PARA formulario  orden*/
    enter('nombre_orden','precio');
    enter('precio','precio');

    enter('codigo_categoria','nombre_categoria');
    enter('nombre_categoria','nombre_categoria');

    $('#tipo_categoria').change(function(){
      $('#codigo_categoria').focus();
    });

   /// creamos una orden
   $('#create_orden').click(function(){
      accion = 'save_form';
      $('#text_save_orden').text("Guardar")
      $('#form_create_orden').show();
      $('#import_excel_orden').hide();
      $('#div_save_form').hide();
      $('#div_import_excel').show();
      $('#modal_create_orden').modal("show");
      mostrarTipoOrdenesDisponibles();
      ShowCategoryDisponiblesTipo(0);
   });

   $('#view_form_excel').click(function(evento){
      evento.preventDefault();
      accion = 'import_excel';
      $('#text_save_orden').text("Impordar datos");
      $('#import_excel_orden').show(400);
      $('#div_save_form').show(400);
      $('#form_create_orden').hide();
      $('#div_import_excel').hide();
   });

   $('#view_form_register').click(function(evento){
      evento.preventDefault();
      accion = 'save_form';
      $('#text_save_orden').text("Guardar")
      $('#form_create_orden').show(400);
      $('#import_excel_orden').hide();
      $('#div_save_form').hide();
      $('#div_import_excel').show();
   });


   $('#view_form_excel_tipo_orden').click(function(evento){
      evento.preventDefault();
      accionTipo = 'import_excel';
      $('#text_save_tipo_orden').text("Impordar datos");
      $('#import_excel_tipo_orden').show(400);
      $('#div_save_form_tipo_orden').show(400);
      $('#form_tipo_orden').hide();
      $('#div_import_excel_tipo_orden').hide();
   });

   $('#view_form_register_tipo_orden').click(function(evento){
      evento.preventDefault();
      accionTipo = 'save_form';
      $('#text_save_tipo_orden').text("Guardar")
      $('#form_tipo_orden').show(400);
      $('#import_excel_tipo_orden').hide();
      $('#div_save_form_tipo_orden').hide();
      $('#div_import_excel_tipo_orden').show();
   });

   $('#create_tipo_orden').click(function(evento){
      evento.preventDefault();
      $('#modal_create_tipo_orden').modal("show");
      $('#modal_create_categoria').modal("hide");
   });

   $('#cerrar_modal_tipo_orden').click(function(){
      $('#modal_create_tipo_orden').modal("hide");
      $('#modal_create_categoria').modal("show");

      accionTipo = 'save_form';
      $('#text_save_tipo_orden').text("Guardar")
      $('#form_tipo_orden').show();
      $('#import_excel_tipo_orden').hide();
      $('#div_save_form_tipo_orden').hide();
      $('#div_import_excel_tipo_orden').show();
      mostrarTipoOrdenesDisponibles();
      $('#codigo_tipo_orden').val("");
      $('#nombre_tipo_orden').val("");
      mostrarTipoCategoriaDisponibles();
   });
   
   $('#save_orden').click(function(){
     
      if(accion === 'save_form'){
         if($('#categoria_orden').val() == null){
            $('#categoria_orden').focus();
                Swal.fire({
                  title:"MENSAJE DEL SISTEMA!!!",
                  text:"Seleccione una categoría!!!",
                  icon:"error",
                  target:document.getElementById('modal_create_orden')
                });
                return;
         }

         if($('#nombre_orden').val().trim().length == 0){
            $('#nombre_orden').focus();
         }else{
            if($('#precio').val().trim().length == 0){
               $('#precio').focus();
            }else{
               let FormSaveOrden = new FormData(document.getElementById("form_create_orden"));
               saveExamen(FormSaveOrden);
            }
         }
      }else{
         let FormSaveOrdenExcel = new FormData(document.getElementById("import_excel_orden"));
         importExamen(FormSaveOrdenExcel);
      }
   })

   $('#tipo_orden').change(function(){
      ShowCategoryDisponiblesTipo($(this).val());
   });

   $('#save_tipo_orden').click(function(){
      if(accionTipo === 'save_form'){
         if($('#codigo_tipo_orden').val().trim().length == 0){
            $('#codigo_tipo_orden').focus();
         }else{
            if($('#nombre_tipo_orden').val().trim().length == 0){
               $('#nombre_tipo_orden').focus();
            }else{
               let FormSaveTioOrde = new FormData(document.getElementById("form_tipo_orden"));
               saveTipoExamen(FormSaveTioOrde);
            }
         }
      }else{
        if(accionTipo === 'update'){

         if($('#codigo_tipo_orden').val().trim().length == 0){
            $('#codigo_tipo_orden').focus();
         }else{
            if($('#nombre_tipo_orden').val().trim().length == 0){
               $('#nombre_tipo_orden').focus();
            }else{
               let FormUpdateTipoOrde = new FormData(document.getElementById("form_tipo_orden"));
               ActualizarTipoExamen(TipoExamenId,FormUpdateTipoOrde);  
            }
         }
        }else{
         let FormSaveTipoOrdenImportExcel = new FormData(document.getElementById("import_excel_tipo_orden"));
         importTipoExamen(FormSaveTipoOrdenImportExcel);
        }
      }
   })

   $('#update_orden').click(function(){

      if($('#nombre_orden_editar').val().trim().length == 0){
            $('#nombre_orden_editar').focus();
         }else{
            if($('#precio_editar').val().trim().length == 0){
               $('#precio_editar').focus();
            }else{
               let FormUpdateOrden = new FormData(document.getElementById('form_orden_editar'));
               ModificarExamen(ExamenId,FormUpdateOrden);
            }
         }
   });

   $('#save_categoria').click(function(){

      if(accionCategoria === 'importar'){
         importCategoriaOrder();
         return;
      }
      if($('#codigo_categoria').val().trim().length == 0){
         $('#codigo_categoria').focus();
      }else{
         if($('#nombre_categoria').val().trim().length == 0){
            $('#nombre_categoria').focus();
         }else{
      if(accionCategoria === 'save'){
         saveCategoriaOrden();
      }else{
        if(accionCategoria === 'editar'){
            modificarCategoriaOrden(CATEGORIAIDORDEN);
         } 
       }
         }
      }
   })
 });

 /*MOSTRAR LAS ORDENES */

</script>
@endsection