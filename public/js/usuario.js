/**
 * ASIGNAR ROLES AL USUARIO
 */
function AsignarRoles(Tabla,Tbody){
 $(Tbody).on('click','#asignar_role',function(){
     let fila = $(this).parents("tr");

     if(fila.hasClass("child")){
        fila = fila.prev();
     }

     let Data = Tabla.row(fila).data();
     $('#usuariotext').val((Data.apellidos+"  "+Data.nombres).toUpperCase());
    
     RolesNoAsignadosUser(Data.id_usuario);
     USER_ID = Data.id_usuario;
     $('#modal_asignar_roles').modal("show");
 });
}

/// VER LOS ROLES DEL USUARIO
function VerRolesUser(Tabla,Tbody){
 $(Tbody).on('click','#ver_roles',function(){
     let fila = $(this).parents("tr");

     if(fila.hasClass("child")){
        fila = fila.prev();
     }

     let Data = Tabla.row(fila).data();
    // $('#usuariotext').val((Data.apellidos+"  "+Data.nombres).toUpperCase());
    
     USER_ID = Data.id_usuario;
     $('#usuariodatorolesasignados').val(Data.name.toUpperCase());
     showRolesUser(USER_ID);
     
     $('#modal_show_roles').modal("show");
 });
}

/// MOSTRAR LOS ROLES ASIGNADOS DEL USUARIO
function showRolesUser(id){
  TablaRolesDelUsuario = $('#tabla_roles_user').DataTable({
     bDestroy:true,
      responsive:true,
      language:SpanishDataTable(),
      ajax:{
        url:URLBASE+"roles-asignados/user/"+id,
        method:"GET",
        dataSrc:"roles"
      },
      columns:[
        {"data":"id_user_rol",className:"d-none"},
        {"data":"nombre_rol",render:function(roledata){
          return roledata.toUpperCase();
        }},
        {"data":null,render:function(dato){
          return `<button class="btn btn-outline-danger rounded btn-sm" id="quitar_role"><i class='bx bx-x'></i></button>`;
        },className:"text-center"}
      ]
  })
}

/// Qiitar el rol seleccionado del usuario
function QuitarRoleUser(Tbody){
 $(Tbody).on('click','#quitar_role',function(){
   let fila = $(this).closest("tr");
   if(fila.hasClass("child")){
     fila = fila.prev();
   }

   let IdRoleUser = fila.find("td").eq(0).text();
   Swal.fire({
     title: "ESTAS SEGURO?",
     text: "Al aceptar, el rol que seleccionaste se quitará de la lista de roles del usuario!!!",
     icon: "question",
     showCancelButton: true,
     confirmButtonColor: "#3085d6",
     cancelButtonColor: "#d33",
     cancelButtonText:"Cancelar",
     confirmButtonText: "Si, quitar!",
     target:document.getElementById('modal_show_roles')
   }).then((result) => {
     if (result.isConfirmed) {
        ProcessEliminadoRoleUser(IdRoleUser);
     }
   });
 })
}

/// PROCESO DE ELIMINAR AL ROL DEL USUARIO
function ProcessEliminadoRoleUser(id){
  let FormQuitarRoleUser = new FormData();
  FormQuitarRoleUser.append("token_",TOKEN);
  axios({
    url:URLBASE+"quitar-role/user/"+id,
    method:"POST",
    data:FormQuitarRoleUser
  }).then(function(response){
     if(response.data.error != undefined){
       Swal.fire({
         title:"ERROR DEL SISTEMA!!!",
         text:response.data.error,
         icon:"error",
         target:document.getElementById('modal_show_roles')
       })
     }else{
      Swal.fire({
         title:"MENSAJE DEL SISTEMA!!!",
         text:response.data.response,
         icon:"success",
         target:document.getElementById('modal_show_roles')
       }).then(function(){
        showRolesUser(USER_ID);
       });
     }
  })
}

/// VER LOS ROLES QUE TODAVIA NO HAN SIDO ASIGNADOS AL USUARIO
function RolesNoAsignadosUser(id){
    TablaRolesNoAsignedUser = $('#roles_no_asignados').DataTable({
      bDestroy:true,
      responsive:true,
      language:SpanishDataTable(),
      ajax:{
        url:URLBASE+"roles-no-asignados-al-usuario/"+id,
        method:"GET",
        dataSrc:"roles"
      },
      columns:[
        {"data":null,render:function(role){
          return `
          <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="role`+role.id_rol+`" value=`+role.id_rol+`>
          <label class="form-check-label" for="role`+role.id_rol+`">`+role.nombre_rol.toUpperCase()+`</label>
          </div>
          `;
        }}
      ]
    });    
}

/// agregarle rol a la lista al usuario
function addListRoleUser(id){
    let FormAsignar = new FormData();
    FormAsignar.append("token_", TOKEN);
    axios({
      url: URLBASE + "asignar-rol-select-user/" + id,
      method: "POST",
      data: FormAsignar,
    }).then(function (response) {
      console.log(response.data);
    });
}

/// Quitar de la lista al rol del usuario
function QuitarRoleListUser(id){
      let FormQuitar = new FormData();
      FormQuitar.append("token_", TOKEN);
      axios({
        url: URLBASE + "quitar/role/de-lista/" + id,
        method: "POST",
        data: FormQuitar,
      }).then(function (response) {
        console.log(response.data);
      });  
}
/** GUARDAR LOS ROLES ASIGNADOS DEL USUARIO */
function saveRolesUser(){
  let FormSaveRolesUser = new FormData();
  FormSaveRolesUser.append("token_",TOKEN);
  axios({
    url:URLBASE+"usuario/guardar-roles/"+USER_ID,
    method:"POST",
    data:FormSaveRolesUser,
  }).then(function(response){
     if(response.data.error != undefined){
       Swal.fire({
         title:"ERROR DEL SISTEMA!!!",
         text:response.data.error,
         icon:"error",
         target:document.getElementById('modal_asignar_roles')
       })
     }else{
       Swal.fire({
        title:"CORRECTO!!",
        text:response.data.response,
        icon:"success",
        target:document.getElementById('modal_asignar_roles')
       }).then(function(){
         RolesNoAsignadosUser(USER_ID);
       });
     }
  });
}

/// CANCELAR EL ASIGANDOS DE ROLES AL USUARIO
function CancelAsignedRolesUser(){
  let DataFormCancel = new FormData();
  DataFormCancel.append("token_",TOKEN);
  axios({
    url:URLBASE+"user/cancel/roles-asigned",
    method:"POST",
    data:DataFormCancel
  }).then(function(response){})
}