@extends($this->Layouts("dashboard"))
@section('title_dashboard','Gestión de Permisos')
@section('css')
    <style>
     
    </style>
@endsection
@section('contenido')
 <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background:linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 50%,#539fe1 79%,#87bcea 100%);">
                <h4 class="text-white letra">Gestión de permisos</h4>
            </div>
            <div class="card-body table-responsive-sm">
                <div class="row mt-1 mb-2">
                    <div class="col-12">
                         <button class="btn_blue col-xl-3 col-lg-5 col-md-5 col-12 mt-4 float-end" id="create_permiso">Agregar uno nuevo <i class='bx bx-plus'></i></button>
                        <div class="form-group col-xl-8 col-lg-6 col-md-6 col-12">
                            <label for=""><b>Filtrar por módulo</b></label>
                            <select name="modulo_filtro" id="modulo_filtro" class="form-select"></select>
                        </div> 
                    </div>
                    
                </div>
    
                <table class="table table-bordered table-striped table-hover table-sm nowrap responsive"
                id="tabla_permisos" style="width: 100%">
                 <thead style="background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 50%,#539fe1 79%,#87bcea 100%);">
                     <tr>
                        <th class="py-3 text-white letra">MODULO</th>
                        <th class="py-3 text-white letra">PERMISOS</th>
                        <th class="py-3 text-white letra">ALIAS</th>
                        <th class="py-3 text-white letra">ACCIONES</th>
                     </tr>
                 </thead>
               </table>
            </div>
        </div>
    </div>
 </div>

 {{-- MODAL PARA CREAR PERMISOS---}}
  <div class="modal fade" id="ventana_new_permiso">
   <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #b8e1fc 0%,#a9d2f3 10%,#90bae4 25%,#90bcea 37%,#90bff0 50%,#6ba8e5 51%,#a2daf5 83%,#bdf3fd 100%);">
             <h4 class="letra text-white"><b class="letra text-white">Crear Permiso</b></h4>
        </div>
        <div class="modal-body">
            <div class="form-group mb-2">
                <label for=""><b class="letra">Seleccionar módulo</b></label>
                <div class="input-group">
                    <select name="modulo" id="modulo" class="form-select"></select>
                    <button class="btn btn-outline-primary" id="create_modulo"><b>Agregar <i class='bx bx-plus'></i></b></button>
                </div>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="name_permiso" placeholder="Nombre Permiso">
                <label for="name_permiso">Nombre Permiso</label>
                <span class="text-danger" id="errors_name_permiso"></span>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="alias_permiso" placeholder="Alias Permiso">
                <label for="alias_permiso">Alias Permiso</label>
                <span class="text-danger" id="errors_alias_permiso"></span>
             </div>
        </div>
        <div class="modal-footer border-2">
            <button class="btn btn-success" id="save_permiso"><b>Guardar</b> <i class='bx bx-save'></i></button>
        </div>
     </div>
   </div>
 </div>
 {{-- MODAL PARA EDITAR PERMISO---}}
  <div class="modal fade" id="ventana_editar_permiso">
   <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header" style="background: #e9376d">
             <h4 class="letra text-white"><b class="letra text-white">Editar Permiso</b></h4>
        </div>
        <div class="modal-body">
            <div class="form-group mb-2">
                <label for=""><b class="letra">Seleccionar módulo</b></label>
                <select name="modulo_editar" id="modulo_editar" class="form-select"></select>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="name_permiso_editar" placeholder="Nombre Permiso">
                <label for="name_permiso_editar">Nombre Permiso</label>
                <span class="text-danger" id="errors_name_permiso_editar"></span>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="alias_permiso_editar" placeholder="Alias Permiso">
                <label for="alias_permiso_editar">Alias Permiso</label>
                <span class="text-danger" id="errors_alias_permiso_editar"></span>
            </div>

        </div>
        <div class="modal-footer border-2">
            <button class="btn btn-success" id="update_permiso"><b>Guardar</b> <i class='bx bx-save'></i></button>
        </div>
     </div>
   </div>
 </div>
 {{-- MODAL PARA CREAR NUEVO MODULOS---}}
  <div class="modal fade" id="ventana_create_modulo" data-bs-backdrop="static">
   <div class="modal-dialog modal-lg modal-dialog-scrollable">
     <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #e6f0a3 0%,#d2e638 50%,#c3d825 51%,#dbf043 100%);">
             <h4 class="letra text-white"><b class="letra text-white">Crear Módulo</b></h4>
        </div>
        <div class="modal-body">
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="name_modulo" placeholder="Nombre Módulo">
                <label for="name_modulo">Nombre Módulo</label>
                <span class="text-danger" id="errors_name_modulo"></span>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm nowrap responsive"
                id="tabla_modulos" style="width: 100%">
                    <thead style="background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 50%,#539fe1 79%,#87bcea 100%);">
                        <tr>
                            <th class="py-3 letra text-white">NOMBRE MODULO</th>
                            <th class="py-3 letra text-white">ACCIONES</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="modal-footer border-2">
            <button class="btn btn-success" id="save_modulo"><b>Guardar</b> <i class='bx bx-save'></i></button>
            <button class="btn btn-danger btn-rounded" id="salir_ventana_modulo"><b>Salir X</b></button>
        </div>
     </div>
   </div>
 </div>
@endsection
@section('js')
<script src="{{URL_BASE}}public/js/control.js"></script>
<script src="{{URL_BASE}}public/js/modulo.js"></script>
<script>
 var TablaPermisos;
 var RUTAAPP = "{{URL_BASE}}";
 var TOKEN = "{{$this->Csrf_Token()}}";
 var PERMISOID;
 var TablaModulos;
 $(document).ready(function(){
    let NombrePermiso = $('#name_permiso');
    let AliasPermiso = $('#alias_permiso');
  /// mostrar los permisos
  MostrarPermisos();
  //TablaPermisos.column(0).search($('#modulo_filtro option:selected').text(), true, false).draw();
  eliminar(TablaPermisos,'#tabla_permisos tbody');
  ButtonActivePermission(TablaPermisos,'#tabla_permisos tbody');
  ConfirmBorradoPermiso(TablaPermisos,'#tabla_permisos tbody');
  ButtonEditarPermission(TablaPermisos,'#tabla_permisos tbody');

  showModulos();
  ConfirmDeleteListModulo(TablaModulos,'#tabla_modulos tbody');
  ButtonClickHabilitarModulo(TablaModulos,'#tabla_modulos tbody');
  showModulosDisponibles('#modulo_editar');
  showModulosDisponibles('#modulo_filtro');
  /// crear nuevos permisos
  $('#create_permiso').click(function(){
    $('#errors_name_permiso').text("");
    $('#errors_alias_permiso').text("");
    NombrePermiso.val("");
    AliasPermiso.val("");
    $('#ventana_new_permiso').modal("show");
    showModulosDisponibles('#modulo');
  });

  /// GUARDAR EL PERMISO
  $('#save_permiso').click(function(){
    savePermiso(NombrePermiso,AliasPermiso);
  });
  /// modificar el permiso
  $('#update_permiso').click(function(){
     if($('#name_permiso_editar').val().trim().length == 0){
         $('#name_permiso_editar').focus();
     }else{
        if($('#alias_permiso_editar').val().trim().length == 0){
            $('#alias_permiso_editar').focus();
        }else{
             ModificarPermiso(PERMISOID,$('#name_permiso_editar'),$('#alias_permiso_editar'),$('#modulo_editar'));
        }
     }
  })
  /// CREAR MODULOS
  $('#create_modulo').click(function(){
    $('#ventana_create_modulo').modal("show");
    $('#ventana_new_permiso').modal("hide");
  })
  $('#salir_ventana_modulo').click(function(){
    $('#ventana_create_modulo').modal("hide");
    $('#ventana_new_permiso').modal("show");
    $('#name_modulo').removeClass("is-invalid");
    $('#errors_name_modulo').text("");
    $("#name_modulo").val("");
    showModulosDisponibles('#modulo');
    showModulosDisponibles('#modulo_filtro');
  });

  $('#save_modulo').click(function(){
    saveModulo($('#name_modulo'));
  });
  $('#name_modulo').keyup(function(){
    if($(this).val().length > 0){
      $(this).removeClass("is-invalid");  
      $(this).addClass("is-valid");  
      $('#errors_name_modulo').hide();
    }else{
        $(this).removeClass("is-valid"); 
        $(this).addClass("is-invalid");
        $('#errors_name_modulo').show();
       $("#errors_name_modulo").text("INGRESE EL NOMBRE DEL MODULO!!!")
    }
  })

  $('#modulo_filtro').change(function(){
    TablaPermisos.column(0).search($('#modulo_filtro option:selected').text(), true, false).draw();
  })
 });

 function MostrarPermisos(){
  TablaPermisos = $('#tabla_permisos').DataTable({
    retrieve:true,
    responsive:true,
    language:SpanishDataTable(),
    ajax:{
        url:RUTAAPP+"permisos/all",
        method:"GET",
        dataSrc:"permisos"
    },
    columns:[
        {"data":"moduloname",render:function(namemodulo){
            return namemodulo.toUpperCase();
        }},
        {"data":"nombre_previlegio",render:function(nameprev){
            return nameprev!= null ? nameprev.toUpperCase(): nameprev;
        }},
        {"data":"alias_previlegio",render:function(aliasprev){
            return aliasprev!= null ? aliasprev.toUpperCase() : aliasprev;
        }},
        {"data":null,render:function(dato){
            if(dato.daleted_at  == null){
                  return `<button class="btn btn-outline-primary btn-sm" id='papelera'><i class='bx bx-trash'></i></button>
                    <button class="btn btn-outline-warning btn-sm" id="editar"><i class='bx bxs-edit-alt'></i></button>
                    <span class="badge bg-success">ACTIVO <i class='bx bx-check'></i></span>`;
                    }
                    
                  return `<button class="btn btn-outline-success btn-sm" id="activar"><i class='bx bx-check'></i></button>
                    <button class="btn btn-outline-danger btn-sm" id="delete"><i class='bx bx-x'></i></button>
                    <span class="badge bg-danger">INACTIVO <i class='bx bx-x'></i></span>`;
        },className:"text-center"}
    ]
  }).ajax.reload();
 }

 /// EDITAR PERMISO
 function ButtonEditarPermission(Tabla,Tbody){
    $(Tbody).on('click','#editar',function(){
        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();
        PERMISOID = Data.id_previlegio;
   
        $('#modulo_editar').val(Data.modulo_id);
        $('#name_permiso_editar').val(Data.nombre_previlegio);
        $('#alias_permiso_editar').val(Data.alias_previlegio)
        $('#ventana_editar_permiso').modal("show");
    });
 } 
 function ButtonActivePermission(Tabla,Tbody){
    $(Tbody).on('click','#activar',function(){
        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();
        ActivarPermiso(Data.id_previlegio);
    });
 }
 /// CONFIRMAR ANTES DE INHABILITAR AL PREVILEGIO SELECCIONADO
 function eliminar(Tabla,Tbody){
    $(Tbody).on('click','#papelera',function(){
        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();
  Swal.fire({
        title: "ESTAS SEGURO?",
        text: "Al aceptar, el permiso seleccionado estará inhabilitado y no podrás usarlo!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#4169E1",
        cancelButtonColor: "#DC143C",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Si, eliminar!",
        }).then((result) => {
        if (result.isConfirmed) {
          EliminarPermiso(Data.id_previlegio);
        }
        });
    });
 }

 /// MODIFICAR DATOS DEL PERMISO
 
function ModificarPermiso(id,NamePrevilegioEdit,AliasPermisoEdit,modulo){
    let FormModifyPermission = new FormData();
    FormModifyPermission.append("token_",TOKEN);
    FormModifyPermission.append("modulo",modulo.val());
    FormModifyPermission.append("nombre_previlegio",NamePrevilegioEdit.val());
    FormModifyPermission.append("alias_previlegio",AliasPermisoEdit.val());
    axios({
        url:RUTAAPP+"permiso/update/"+id,
        method:"POST",
        data:FormModifyPermission
    }).then(function(response){
         
        if(response.data.error != undefined){
            Swal.fire({
                title:"ERROR DEL SISTEMA!!",
                text:response.data.error,
                icon:"error",
            });
        }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.response,
                icon:"success",
                target:document.getElementById('ventana_editar_permiso')
            }).then(function(){
                MostrarPermisos();
            });   
        }
    });
 }
 /// CONFIRMAR ANTES DE BORRAR AL PERMISO SELECCIONADO
  function ConfirmBorradoPermiso(Tabla,Tbody){
    $(Tbody).on('click','#delete',function(){
        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();
  Swal.fire({
        title: "ESTAS SEGURO?",
        text: "Al aceptar, el permiso seleccionado se borrará por completo!!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#4169E1",
        cancelButtonColor: "#DC143C",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Si, eliminar!",
        }).then((result) => {
        if (result.isConfirmed) {
           BorrarPermiso(Data.id_previlegio);
        }
        });
    });
 }
 /// PROCESO DE BORRAR AL PERMISO
  function BorrarPermiso(id){
    let FormBorradoPermission = new FormData();
    FormBorradoPermission.append("token_",TOKEN);
    axios({
        url:RUTAAPP+"permiso/borrar/"+id,
        method:"POST",
        data:FormBorradoPermission
    }).then(function(response){
         
        if(response.data.error != undefined){
            Swal.fire({
                title:"ERROR DEL SISTEMA!!",
                text:response.data.error,
                icon:"error",
            });
        }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.response,
                icon:"success",
            }).then(function(){
                MostrarPermisos();
            });   
        }
    });
 }
 /// PROCESO DE ELIMINAR AL PREVILEGIO E INACTIVARLO
  function EliminarPermiso(id){
    let FormEliminarPermission = new FormData();
    FormEliminarPermission.append("token_",TOKEN);
    axios({
        url:RUTAAPP+"permiso/inhabilitar/"+id,
        method:"POST",
        data:FormEliminarPermission
    }).then(function(response){
         
        if(response.data.error != undefined){
            Swal.fire({
                title:"ERROR DEL SISTEMA!!",
                text:response.data.error,
                icon:"error",
            });
        }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.response,
                icon:"success",
            }).then(function(){
                MostrarPermisos();
            });   
        }
    });
 }

 /// ACTIVAR PERMISOS
  function ActivarPermiso(id){
    let FormActivatePermission = new FormData();
    FormActivatePermission.append("token_",TOKEN);
    axios({
        url:RUTAAPP+"permiso/activate/"+id,
        method:"POST",
        data:FormActivatePermission
    }).then(function(response){
         
        if(response.data.error != undefined){
            Swal.fire({
                title:"ERROR DEL SISTEMA!!",
                text:response.data.error,
                icon:"error",
            });
        }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.response,
                icon:"success",
            }).then(function(){
                MostrarPermisos();
            });   
        }
    });
 }
 /// REGISTRAR NUEVOS PERMISOS DEL SISTEMA
 function savePermiso(NamePrevilegio,AliasPrevilegio){
    let FormSavePermission = new FormData();
    FormSavePermission.append("token_",TOKEN);
    FormSavePermission.append("name_previlegio",NamePrevilegio.val());
    FormSavePermission.append("alias_previlegio",AliasPrevilegio.val());
    FormSavePermission.append("modulo",$('#modulo').val())
    axios({
        url:RUTAAPP+"permiso/save",
        method:"POST",
        data:FormSavePermission
    }).then(function(response){
         if(response.data.errors != undefined){
             if(response.data.errors.name_previlegio != undefined){
                $('#name_permiso').focus();
                $('#errors_name_permiso').text(response.data.errors.name_previlegio);
             }else{
                $('#errors_name_permiso').text("");
             }

             if(response.data.errors.alias_previlegio != undefined){
               // $('#alias_permiso').focus();
                $('#errors_alias_permiso').text(response.data.errors.alias_previlegio);
             }else{
                $('#errors_alias_permiso').text("");
             }
            return;
         }

         if(response.data.existe!= undefined){
            Swal.fire({
                title:"AVISO DEL SISTEMA!!",
                text:response.data.existe,
                icon:"info",
                target:document.getElementById('ventana_new_permiso')
            });
            return;
         }
        if(response.data.error != undefined){
            Swal.fire({
                title:"ERROR DEL SISTEMA!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById('ventana_new_permiso')
            });
        }else{
            NamePrevilegio.focus();
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.response,
                icon:"success",
                target:document.getElementById('ventana_new_permiso')
            }).then(function(){
                NamePrevilegio.val("");
                AliasPrevilegio.val("");
                MostrarPermisos();
                TablaPermisos.column(0).search($('#modulo_filtro option:selected').text(), true, false).draw();
            });   
        }
    });
 }
</script>
@endsection