@extends($this->Layouts("dashboard"))
@section('title_dashboard','Gestión de roles')
@section('css')
    <style>
     
    </style>
@endsection
@section('contenido')
 <div class="card">
    <div class="card-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
        <h4 class="letra text-white">Gestión de roles</h4>
    </div>
    <div class="card-body mt-3">
        <div class="row">
            <div class="col-12">
                 <button class="btn_blue col-xl-3 col-lg-3 col-md-5 col-12 mb-2" id="create_role">Agregar uno nuevo <i class='bx bx-plus-medical'></i></button>
            </div>
        </div>
        <div class="table-responsive-sm">
            <table class="table table-bordered table-striped table-hover nowrap responsive table-sm" id="roles" style="width: 100%">
                <thead style="background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 50%,#539fe1 79%,#87bcea 100%);">
                    <th class="py-4 text-white letra">ROL</th>
                    <th class="py-4 text-white letra">ACCIONES</th>
                </thead>
            </table>
        </div>
    </div>
 </div>
 
 {{-- MODAL PARA CREAR ROLES---}}
  <div class="modal fade" id="ventana_new_role">
   <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 50%,#539fe1 79%,#87bcea 100%);">
             <h4 class="letra text-white">Crear Rol</h4>
        </div>
        <div class="modal-body">
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="name_role" placeholder="Nombre rol">
                <label for="name_role">Nombre Rol</label>
            </div>
        </div>
        <div class="modal-footer border-2">
            <button class="btn btn-success" id="save_role"><b>Guardar</b> <i class='bx bx-save'></i></button>
        </div>
     </div>
   </div>
 </div>

 {{---MODAL PARA EDITAR  AL ROL---}}
 <div class="modal fade" id="ventana_editar_role">
   <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #ffb76b 0%,#ffa73d 50%,#ff7c00 51%,#ff7f04 100%);">
             <h4 class="letra text-white">Editar Rol</h4>
        </div>
        <div class="modal-body">
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="name_rol_editar" placeholder="Nombre rol">
                <label for="name_rol_editar">Nombre Rol</label>
            </div>
        </div>
        <div class="modal-footer border-2">
            <button class="btn btn-success" id="update_role">Guardar <i class='bx bx-save'></i></button>
        </div>
     </div>
   </div>
 </div>
 {{-- VENTANA MODAL PARA ASIGNAR PERMISOS A LOS ROLES--}}
 <div class="modal fade" id="ventana_asignar_previlegios" data-bs-backdrop="static">
   <div class="modal-dialog modal-dialog-scrollable modal-xl">
     <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #3b679e 0%,#2b88d9 50%,#207cca 51%,#7db9e8 100%);">
             <h4 class="letra text-white"><b>Asignar Previlegios</b></h4>
        </div>
        <div class="modal-body">
             <div class="form-floating">
                <input type="text" class="form-control" id="role_seleccionado" placeholder="Rol">
                <label for="role_seleccionado">Rol Seleccionado</label>
             </div>
             <div class="form-group">
                <label for="modulo_search" class="form-label"><b>Filtrar por módulo</b></label>
                <select name="modulo_search" id="modulo_search" class="form-select"></select>
             </div>
             <br>
             <table class="table table-bordered table-striped table-hover table-sm nowrap responsive"
             id="permisos_no_asignados_role" style="width: 100%">
              <thead style="background:linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 27%,#0a77d5 63%,#539fe1 81%,#87bcea 90%);">
                 <tr>
                    <th class="text-white letra py-3">
                        <b id="texttitle_permisostabla">NOMBRE DEL PERMISO</b>
                        <div class="form-check form-switch" id="checkpermisos" style="display: none">
                            <input class="form-check-input" type="checkbox" id="previlegio_all">
                            <label class="form-check-label" for="previlegio_all" style="cursor: pointer">TODOS LOS PREVILEGIOS</label>
                        </div>
                    </th>
                    <th class="text-white letra py-3 d-none">MODULO</th>
                 </tr>
              </thead>
             </table>
        </div>
        <div class="modal-footer border-2">
            <button class="btn btn-success" id="save_asignar_permisos">Guardar <i class='bx bx-save'></i></button>
            <button class="btn btn-danger" id="salir_permisos_asignar"><b>Salir X</b></button>
        </div>
     </div>
   </div>
 </div>
@endsection
@section('js')
 <script src="{{URL_BASE}}public/js/control.js"></script>
 <script>
  var TablaRoles;
  var TOKEN = "{{$this->Csrf_Token()}}";
  var RUTA = "{{URL_BASE}}";
  var RoleId;
  var TablaPermisosNoAsignedRole;
  $(document).ready(function(){
  
    /// MOSTRAR ROLES
    mostrarRoles();

    ConfirmEliminadoRole(TablaRoles,"#roles tbody");
    ButtonActiveRole(TablaRoles,"#roles tbody");
    ConfirmBorradoRole(TablaRoles,"#roles tbody");
    EditarRole(TablaRoles,"#roles tbody");
    AsignarPermisosRole(TablaRoles,"#roles tbody");
    showModulosDisponibles("#modulo_search")
    /// crear nuevo rol
    $('#create_role').click(function(){
        $("#ventana_new_role").modal("show");
    });

    /// REGISTRAR AL ROL
    $('#save_role').click(function(){
        if($("#name_role").val().trim().length == 0){
            $('#name_role').focus();
        }else{
            saveRole($('#name_role'));
        }
    });

    $('#update_role').click(function(){
       if($('#name_rol_editar').val().trim().length == 0){
         $('#name_rol_editar').focus();
       }else{
         ModificarRole(RoleId,$('#name_rol_editar'));
       }
    });
    $('#modulo_search').change(function(){
        MostrarPermisosNoAsignedRole(RoleId);
        $('#checkpermisos').show(200);
        $('#previlegio_all').prop("checked",true);
        CancelarAsignedPermisosRole();
        $('#texttitle_permisostabla').hide();
        TablaPermisosNoAsignedRole.column(1).search($('#modulo_search option:selected').text(),true,false).draw();
         AsignarAllPrevilegiosRole(TOKEN,"true",RoleId,$(this).val());
    });

    /// SELECCIONAR LOS PREVILEGIOS
    $('#previlegio_all').change(function(){
        let ModuloId = $('#modulo_search').val();
        let ValorSelect = $(this).is(":checked");

        if(ValorSelect){
            $('#permisos_no_asignados_role input[type=checkbox]').prop("checked",true);
        }else{
            $('#permisos_no_asignados_role input[type=checkbox]').prop("checked",false);
        }
        AsignarAllPrevilegiosRole(TOKEN,ValorSelect,RoleId,ModuloId);
    });

    /// salir de la venta asignar previlegios a roles
    $('#salir_permisos_asignar').click(function(){
        $('#ventana_asignar_previlegios').modal("hide");
        $('#previlegio_all').prop("checked",false);
        $('#permisos_no_asignados_role input[type=checkbox]').prop("checked",false);
        TablaPermisosNoAsignedRole.clear().draw();
        $('#modulo_search').prop("selectedIndex",0);
         $('#checkpermisos').hide();
        $('#texttitle_permisostabla').show();
        CancelarAsignedPermisosRole();
    });

    $('#save_asignar_permisos').click(function(){
        let DataFormAsignarPermisos = new FormData();
        DataFormAsignarPermisos.append("token_",TOKEN);
        axios({
            url:RUTA+"role/save/asigned_previlegios/"+RoleId,
            method:"POST",
            data:DataFormAsignarPermisos,
        }).then(function(response){
            if(response.data.error != undefined){
                Swal.fire({
                    title:"ERROR DEL SISTEMA!!",
                    text:response.data.error,
                    icon:"error",
                    target:document.getElementById('ventana_asignar_previlegios')
                })
          }else{
            Swal.fire({
            title:"MENSAJE DEL SISTEMA!!",
            text:response.data.response,
            icon:"success",
            target:document.getElementById('ventana_asignar_previlegios')
            }).then(function(){
                 $('#previlegio_all').prop("checked",false);
                 $('#permisos_no_asignados_role input[type=checkbox]').prop("checked",false);
                 TablaPermisosNoAsignedRole.clear().draw();
                 $('#modulo_search').prop("selectedIndex",0);
            })
            }
        })
    });

    $('#permisos_no_asignados_role').on('click','tbody input[type=checkbox]',function(){
       
        let Checked = $(this).is(":checked");
        let IdPrevilegio = $(this).val();
        let FormAddQuitarPermisoRole = new FormData();
        FormAddQuitarPermisoRole.append("token_",TOKEN);
        FormAddQuitarPermisoRole.append("check",Checked);
        FormAddQuitarPermisoRole.append("idprev",IdPrevilegio);
         axios({
            url:RUTA+"add-quitar/permiso/role",
            method:"POST",
            data:FormAddQuitarPermisoRole
         }).then(function(response){});
         
    });
  });

  /// MOSTRAR LOS ROLES EXISTENTES
  function mostrarRoles(){
    TablaRoles = $('#roles').DataTable({
         retrieve:true,
         responsive:true,
         language:SpanishDataTable(),
         ajax:{
            url:RUTA+"roles/all",
            method:"GET",
            dataSrc:"roles"
         },
         columns:[
            {"data":"nombre_rol",render:function(res){return res.toUpperCase();}},
            {"data":null,className:"text-center",render:function(dato){
                if(dato.deleted_at  == null){
                    return `<button class="btn btn-outline-primary btn-sm" id='papelera'><i class='bx bx-trash'></i></button>
                            <button class="btn btn-outline-warning btn-sm" id="editar"><i class='bx bxs-edit-alt'></i></button>
                            <button class="btn btn-outline-info btn-sm" id="asignar_permisos"><i class='bx bx-key'></i></button>
                            <span class="badge bg-success">ACTIVO  <i class='bx bx-check'></i></span>`;
                }

                return `<button class="btn btn-outline-success btn-sm" id="activar"><i class='bx bx-check'></i></button>
                    <button class="btn btn-outline-danger btn-sm" id="delete"><i class='bx bx-x'></i></button>
                    <span class="badge bg-danger">INACTIVO <i class='bx bx-x'></i></span>`;
            }},
         ]
    }).ajax.reload();
  }

  /// ASIGNAR PERMISOS A LOS ROLES
  function AsignarPermisosRole(Tabla,Tbody){
    $(Tbody).on('click','#asignar_permisos',function(){
        let fila = $(this).parents("tr");
        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();
        RoleId = Data.id_rol;
        $('#role_seleccionado').val(Data.nombre_rol.toUpperCase());
        $('#role_seleccionado').prop("disabled",true)
        $('#ventana_asignar_previlegios').modal("show");
    
    });
  }
  /// EDITAR AL ROL
  function EditarRole(Tabla,Tbody){
    $(Tbody).on('click','#editar',function(){
        let fila = $(this).parents("tr");
            
        if(fila.hasClass("child")){
        fila = fila.prev();
        }
            
        let Data = Tabla.row(fila).data();
        $('#name_rol_editar').val(Data.nombre_rol);
        RoleId = Data.id_rol;
        $("#ventana_editar_role").modal("show");
    });
  }

  /// MODIFICAR DATOS DEL ROL
   function ModificarRole(id,rol){
    let FormModifyRole = new FormData();
    FormModifyRole.append("token_",TOKEN);
    FormModifyRole.append("namerole",rol.val());
    axios({
        url:RUTA+"role/"+id+"/update",
        method:"POST",
        data:FormModifyRole
    }).then(function(response){
        if(response.data.error != undefined){
             Swal.fire({
                    title:"ERROR DEL SISTEMA!!",
                    text:response.error,
                    icon:"error",
                    target:document.getElementById('ventana_editar_role')
             });
        }else{
             if(response.data.existe != undefined){
            Swal.fire({
            title:"MENSAJE DEL SISTEMA!!",
            text:response.data.existe,
            icon:"info",
            target:document.getElementById('ventana_editar_role')
            }).then(function(){
            mostrarRoles();
            });
            }else{
            Swal.fire({
            title:"MENSAJE DEL SISTEMA!!",
            text:response.data.response,
            icon:"success",
            target:document.getElementById('ventana_editar_role')
            }).then(function(){
            mostrarRoles();
            });
            }
        }
    });
  }

  /// ACTIVAR AL ROL
  function ButtonActiveRole(Tabla,Tbody){
    $(Tbody).on('click','#activar',function(){
        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        ActivarRole(Data.id_rol);
    });
  }
  /// Confirmar antes de eliminar de lista al rol
  function ConfirmEliminadoRole(Tabla,Tbody){
    $(Tbody).on('click','#papelera',function(){
        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

  Swal.fire({
        title: "ESTAS SEGURO?",
        text: "Al aceptar se quitará de la lista de roles, y ya no podrá usarse!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
        }).then((result) => {
        if (result.isConfirmed) {
          eliminarRole(Data.id_rol);
        }
        });
    })
  }

  /// CONFIRMAR ANTES DE ELIMINAR AL ROL
  function ConfirmBorradoRole(Tabla,Tbody){
   $(Tbody).on('click','#delete',function(){
     let fila = $(this).parents("tr");

     if(fila.hasClass("child")){
        fila = fila.prev();
     }

     let Data = Tabla.row(fila).data();
 Swal.fire({
    title: "ESTAS SEGURO DE BORRAR AL ROL "+Data.nombre_rol+"?",
    text: "Al aceptar se borrará el registro por completo!!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminar!"
    }).then((result) => {
    if (result.isConfirmed) {
      BorrarRole(Data.id_rol);
    }
    });
   });
  }

  /// BORRAR AL ROL SELECCIONADO
  function BorrarRole(id){
    let FormEliminarRole = new FormData();
    FormEliminarRole.append("token_",TOKEN);
    axios({
        url:RUTA+"role/"+id+"/borrar",
        method:"POST",
        data:FormEliminarRole
    }).then(function(response){
        if(response.data.error != undefined){
             Swal.fire({
                    title:"ERROR DEL SISTEMA!!",
                    text:response.error,
                    icon:"error",
             });
        }else{
             Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:response.data.response,
                    icon:"success",
            }).then(function(){
                mostrarRoles();
            });
        }
    });
  }

  /// ELIMINAR ROL DE LA LISTA
  function eliminarRole(id){
    let FormEliminarRole = new FormData();
    FormEliminarRole.append("token_",TOKEN);
    axios({
        url:RUTA+"role/eliminar/"+id,
        method:"POST",
        data:FormEliminarRole
    }).then(function(response){
        if(response.data.error != undefined){
             Swal.fire({
                    title:"ERROR DEL SISTEMA!!",
                    text:response.error,
                    icon:"error",
             });
        }else{
             Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:response.data.response,
                    icon:"success",
            }).then(function(){
                mostrarRoles();
            });
        }
    });
  }

  /// ACTIVAR AL ROL
   function ActivarRole(id){
    let FormActivarRole = new FormData();
    FormActivarRole.append("token_",TOKEN);
    axios({
        url:RUTA+"role/activar/"+id,
        method:"POST",
        data:FormActivarRole
    }).then(function(response){
        if(response.data.error != undefined){
             Swal.fire({
                    title:"ERROR DEL SISTEMA!!",
                    text:response.error,
                    icon:"error",
             });
        }else{
             Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:response.data.response,
                    icon:"success",
            }).then(function(){
                mostrarRoles();
            });
        }
    });
  }

  /// crear nuevo rol
  function saveRole(NombreRol){
    $.ajax({
        url:RUTA+"role/store",
        method:"POST",
        data:{token_:TOKEN,rolename:NombreRol.val()},
        dataType:"json",
        success:function(response){
            if(response.error != undefined){
                Swal.fire({
                    title:"ERROR DEL SISTEMA!!",
                    text:response.error,
                    icon:"error",
                    target:document.getElementById("ventana_new_role")
                });
            }else{
              if(response.existe != undefined){
                NombreRol.focus();
                Swal.fire({
                title:"AVISO DEL SISTEMA!!",
                text:response.existe,
                icon:"info",
                target:document.getElementById("ventana_new_role")
                }).then(function(){
                    NombreRol.val("");
                });
                }else{
                  NombreRol.focus();
                    Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:response.response,
                    icon:"success",
                    target:document.getElementById("ventana_new_role")
                    }).then(function(){
                    NombreRol.val("");
                    mostrarRoles();
                    });
                }
            }
        }
    })
  }
  /// MOSTRAR LOS PERMISOS NO ASIGNADOS DEL ROL
  function MostrarPermisosNoAsignedRole(id){
    TablaPermisosNoAsignedRole = $('#permisos_no_asignados_role').DataTable({
        bDestroy:true,
        responsive:true,
        language:SpanishDataTable(),
        pageLength:25,
        ajax:{
            url:RUTA+"permisos-no-asignados/role/"+id,
            method:"GET",
            dataSrc:"permisosnoasignados"
        },
        columns:[
            {"data":null,render:function(permiso){
            return `<div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="permiso`+permiso.id_previlegio+`" value=`+permiso.id_previlegio+` checked>
                    <label class="form-check-label" for="permiso`+permiso.id_previlegio+`">`+permiso.nombre_previlegio.toUpperCase()+`</label>
                </div>`;
            }},
            {"data":"moduloname",render:function(modulodata){
                return modulodata.toUpperCase();
            },className:"d-none"}
        ]
    });
  }

  /**VER MODULOS DISPONIBLES */
function showModulosDisponibles(modulohtml){
    let option = '<option disabled selected>--- Seleccione ----</option>';

    axios({
        url:RUTA+"modulos/disponibles",
        method:"GET",
    }).then(function(response){
         if(response.data.modulos != undefined && response.data.modulos.length > 0){
              response.data.modulos.forEach(modulo => {
                 option+=`<option value=`+modulo.id_modulo+`>`+modulo.moduloname.toUpperCase()+`</option>`;
              });
         } 

         $(modulohtml).html(option);
    });
}

/// salir y cancelar asignados de permisos
function CancelarAsignedPermisosRole(){
    let FormCancel = new FormData();
    FormCancel.append("token_",TOKEN);
    axios({
        url:RUTA+"cancel/permisos/asigned/role",
        method:"POST",
        data:FormCancel,
    }).then(function(){})
}
/*ASIGNAR TODOS LOS PREVILEGIOS DEACUERDO AL MODULO*/
function AsignarAllPrevilegiosRole(Token,ValueSelect,RolId,ModuleId){
   let SendSelectPrevilegios=new FormData();
    SendSelectPrevilegios.append("token_",Token);
    SendSelectPrevilegios.append("todos",ValueSelect);
    SendSelectPrevilegios.append("rol",RolId);
    SendSelectPrevilegios.append("modulo",ModuleId);
    axios({
    url:RUTA+"previlegios/select/all",
    method:"POST",
    data:SendSelectPrevilegios
    }).then(function(response){ })
}
 </script>   
@endsection