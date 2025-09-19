@extends($this->Layouts("dashboard"))
@section('title_dashboard','Gestión de usuarios')
@section('css')
    <style>
      #tabla-usuarios>thead>tr>th
    {
      background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 50%,#539fe1 79%,#87bcea 100%);
      color:aliceblue;
       
    }
    #tabla-usuarios-cuenta>thead>tr>th{
      padding: 23px;
      background:linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 50%,#539fe1 79%,#87bcea 100%);;
      color: aliceblue;
    }
    </style>
    <link rel="stylesheet" href="{{$this->asset("css/estilos.css")}}">
@endsection
@section('contenido')
<div class="">
  <div class="col-12">
    <div class="nav-align-top mb-4">
      <ul class="nav nav-tabs nav-fill" role="tablist" id="tab_user_">
        <li class="nav-item">
          <button
            type="button"
            class="nav-link active"
            role="tab"
            data-bs-toggle="tab"
            data-bs-target="#navs-justified-home"
            aria-controls="navs-justified-home"
            aria-selected="true"
            style="color: #4169E1"
            id="gestion_user"
          >
            <i class="tf-icons bx bx-home"></i> Gestionar usuarios
      
          </button>
        </li>
       
        <li class="nav-item">
          <button
            type="button"
            class="nav-link"
            role="tab"
            data-bs-toggle="tab"
            data-bs-target="#navs-justified-messages"
            aria-controls="navs-justified-messages"
            aria-selected="false"
            style="color:#48D1CC"
          >
            <i class="tf-icons bx bx-message-square"></i>cuenta de usuario pacientes
          </button>
        </li>
        
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
            
                <button class="btn_blue col-xl-3 col-lg-4 col-md-5 col-sm-5 col-12"
                  id="modal-create-user"><b class="letra"> Agregar uno nuevo <i class='bx bx-plus'></i></b></button>
                <div class="card-text">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm table-striped nowrap responsive" id="tabla-usuarios" style="width: 100%" >
                      <thead>
                        <tr>
                          <th class="py-4 letra">#</th>
                          <th class="py-4 letra">GESTIONAR</th>
                          <th class="py-4 letra">PERSONA</th>
                          <th class="py-4 letra">NOMBRE DE USUARIO</th>
                          <th class="py-4 letra">GÉNERO</th>
                          <th class="py-4 letra">EMAIL</th>
                          <th class="py-4 letra">ROL</th>
                          <th class="py-4 letra">FOTO</th>
                          <th class="py-4 letra">ESTADO</th>
                          <th class="py-4 letra">SUCURSAL</th>
                        </tr>
                      </thead>
                    </table>
                  </div>      
            </div>
        </div>
        <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
         
            <div class="card-text">
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped nowrap" id="tabla-usuarios-inactive" style="width: 100%">
                  <thead style="background-color: #00FA9A">
                    <tr>
                      <th>#</th>
                      <th>USUARIO</th>
                      <th>EMAIL</th>
                      <th>ROL</th>
                      <th>ESTADO</th>
                      <th>ACCIÓN</th>
                    </tr>
                  </thead>
                </table>
              </div>      
        </div>
        
        </div>
        <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
          <div class="card-text">
            <div class="table-responsive">
              <table class="table table-bordered table-hover table-sm table-striped nowrap" id="tabla-usuarios-cuenta" style="width: 100%">
                <thead style="background-color: #7994e2">
                  <tr>
                    <th class="text-white">#</th>
                    <th class="text-white"># DOCUMENTO</th>
                    <th class="text-white">PERSONA</th>
                    <th class="text-white">ACCIÓN</th>
                  </tr>
                </thead>
              </table>
            </div>      
      </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  {{--- ,PDAL PARA CREAR NUEVO USUARIO----}}
  <div class="modal fade" id="modal_user" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 51%,#539fe1 79%,#87bcea 100%);">
          <h4 class="text-white"><span id="text_modal_user">Crear usuarios</span> <b><i class='bx bx-user-plus' style="display:none"></i> <i class='bx bx-edit-alt' style="display: none"></i> </b></h4>
          <button type="button" class="btn-close close_user" data-bs-dismiss="modal"  aria-label="Close"></button>
        </div>
        <div class="modal-body">
            
         <div id="alerta" class="alert alert-danger mt-2" style="display: none">
            <ul class="ul"></ul>
          </div>
          <form action="" method="post" id="form_users_create">
            <div class="row">
              <div class="col-12">
               @if ($this->profile()->rol === 'admin_general')
                  <div class="form-group">
                      <label for="sedeselect"><b>Sucursal*</b></label>
                      <select name="sedeselect" id="sedeselect" class="form-select">
                        <option selected disabled>--- Seleccione ----</option>
                        @foreach ($sedes as $sede)
                        <option value="{{$sede->id_sede}}">{{strtoupper($sede->namesede)}}</option>
                        @endforeach
                      </select>
                    </div>
               @endif
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="rol" class="form-label"><b>Rol (*)</b></label>
                  <select name="rol" id="rol" class="form-select" style="display: none" autofocus >
                    @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general')
                    <option value="Director">Director</option>
                    <option value="Admisión">Admisión</option>
                    <option value="Enfermera-Triaje">Enfermera-Triaje</option>
                    {{-- <option value="ejecutivo">Ejecutivo</option>
                    <option value="vendedor">Vendedor</option> --}}
                    @endif
                    @if ($this->profile()->rol === 'admin_farmacia' || $this->profile()->rol === 'admin_general')
                    <option value="Farmacia">Farmacia</option>
                    <option value="admin_farmacia">Administrador {{$this->profile()->rol === 'admin_general'?'- Farmacia':''}}</option>
                    @endif

                    @if ($this->profile()->rol === 'admin_general')
                    <option value="admin_general">Super administrador</option>
                   
                    @endif
                  </select>
                  <input type="text" class="form-control" id="role_medic_paciente" readonly style="display: none">
                </div>
              </div>
              <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="form-group"><b>Tipo Documento (*)</b></div>
               <select name="tipo_doc" id="tipo_doc" class="form-select">
                 @if (count($TipoDocumentos) > 0)
                     @foreach ($TipoDocumentos as $doc)
                        <option value="{{$doc->id_tipo_doc}}"> {{$doc->name_tipo_doc}}</option>
                     @endforeach
                 @endif
               </select>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-8 col-sm-6 col-12">
                <div class="form-group"><b># Documento (*)</b></div>
              <input type="text" name="documento" id="documento" class="form-control" placeholder="xxx xxx xxx xx">
               </select>
              </div>
  
              <div class="col-xl-5 col-lg-5 col-md-6 col-sm-6 col-12">
                <div class="form-group"><b>Apellidos completos (*)</b></div>
              <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Apellidos completos...">
               
              </div>
  
              <div class="col-xl-7 col-lg-7 col-md-6 col-sm-6 col-12">
                <div class="form-group"><b>Nombres completos (*)</b></div>
              <input type="text" name="nombres" id="nombres" class="form-control" placeholder="Nombres completos...">
               
              </div>
  
              <div class="col-xl-5 col-lg-5  col-12">
                <div class="form-group"><b>Género (*)</b></div>
                <select name="genero" id="genero" class="form-select">
                 
                  <option value="1">Masculino</option>
                  <option value="2">Femenino</option>
                </select>
              </div>
  
              <div class="col-12">
                <div class="form-group"><b>Dirección (Opcional)</b></div>
              <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Escriba su dirección...">
               
              </div>
  
              <div class="col-xl-5 col-lg-5 col-md-6 col-sm-6 col-12">
                <div class="form-group"><b>Fecha Nacimiento (*)</b></div>
              <input type="date" name="fecha_nac" id="fecha_nac" class="form-control" value="{{$this->addRestFecha("Y-m-d")}}">
               
              </div>
  
              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                  <label for="departamento" class="form-label float-start">Departamento (*)</label>
                  <select name="departamento" id="departamento" class="form-select">
                  </select>
                </div>
              </div>
              <div class="col-xl-4 col-lg-3   col-12">
                <div class="form-group">
                  <label for="provincia" class="form-label float-start">Provincia (*)</label>
                  <select name="provincia" id="provincia" class="form-select">
                  </select>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="distrito" class="form-label float-start">Distrito (*)</label>
                  <select name="distrito" id="distrito" class="form-select">
                  </select>
                </div>
              </div>
  
            </div>
            <div class="row">
              <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                  <label for="username" class="form-label float-start">Nombre de usuario (*)</label>
                  <input type="text" name="username" id="username" class="form-control" placeholder="*** *** ***">
                </div>
              </div>
  
              <div class="col-xl-8 col-lg-8 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                  <label for="email" class="form-label float-start">Email(*)</label>
                  <input type="email" name="email" id="email" class="form-control" placeholder="Email...">
                </div>
              </div>

              <div class="col-12" id="password_div" style="display: none">
                <div class="form-group">
                  <label for="password" class="form-label float-start">Password (*)</label>
                  <input type="password" name="password" id="password" class="form-control" placeholder="Password....">
                </div>
              </div>
  
              
            </div>
            <div class="row justify-content-center" id="estado_div" style="display:none">
              <div class="col-xl-4 col-lg-5 col-md-12 col-12 mb-2">
                <div class="form-group">
                  <label for="password" class="form-label float-start">Estado(*)</label>
                  <select name="estado" id="estado" class="form-select">
                    <option value="1">Activo</option>
                    <option value="2">Inactivo</option>
                  </select>
                </div>
              </div>
            </div>
          </form>
        </div>
        
        <div class="modal-footer border-2">

          <button class="btn_blue" id="btn_save" style="display: none"><b>Guardar</b> <i class='bx bx-save'></i></button>
          <button class="btn_blue" id="btn_update" style="display: none"><b>Guardar cambios</b> <i class='bx bx-save'></i></button>
        </div>
      </div>
    </div>
  </div>
  {{-- MODAL PARA AGREGARLE UNA CUENTA A LOS PACIENTES---}}
  <div class="modal fade" id="account_pacientes_add">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
          <p class="h5 text-white letra">Crear cuenta de usuario <i class='bx bxs-user-account'></i></p>
          <button type="button" class="btn-close close_user_paciente" data-bs-dismiss="modal"  aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="name_account"><b>Nombre de usuario <span class="text-danger">(*)</span></b></label>
            <input type="text" name="name_account" id="name_account" class="form-control" placeholder="Nombre de usuario....">
          </div>

          <div class="form-group">
            <label for="email_account"><b>Correo electrónico <span class="text-danger">(*)</span></b></label>
            <input type="email" name="email_account" id="email_account" class="form-control" placeholder="Ingrese un correo electrónico....">
          </div>

          <div class="form-group">
            <label for="password"><b>Password <span class="text-danger">(*)</span></b></label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Escriba una password" aria-describedby="password">
            
          </div>
        </div>
        <div class="modal-footer border-2">
          <button class="btn_3d" id="save_account_paciente"><b>Crear <i class='bx bxs-user-plus'></i></b></button>
        </div>
      </div>
    </div>
  </div>
  {{-- MODAL PARA ASIGNAR LOS ROLES AL USUARIO---}}
 <div class="modal fade" id="modal_asignar_roles" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header" style="background: linear-gradient(to bottom, #6db3f2 17%,#54a3ee 41%,#54a3ee 54%,#3690f0 75%,#1e69de 100%);">
            <h4 class="letra text-white">Asignar roles</h4>
        </div>
        <div class="modal-body">
              <div class="form-group mb-3">
                <label for="usuariotext"><b>Usuario</b></label>
                <input type="text" class="form-control" id="usuariotext" readonly>
              </div>
              <table class="table table-bordered table-hover table-striped table-sm nowrap responsive" id="roles_no_asignados"
                style="width: 100%">
                <thead style="background: linear-gradient(to bottom, #6db3f2 17%,#54a3ee 41%,#54a3ee 54%,#3690f0 75%,#1e69de 100%);">
                  <tr>
                    <th class="py-3 text-white letra">NOMBRE DEL ROL</th>
                  </tr>
                </thead>
              </table>
            </div>
        <div class="modal-footer border-2">
            <button class="btn_blue" id="save_roles_user"><b>Guardar <i class='bx bxs-save'></i></b></button>
            <button class="btn btn-outline-danger" id="salir_roles_user"><i class='bx bx-x'></i>Salir</button>
        </div>
        </div>
    </div>
 </div>

 {{--- VER LOS ROLES DEL USUARIO---}}
  <div class="modal fade" id="modal_show_roles">
    <div class="modal-dialog modal-lg  modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header" style="background:linear-gradient(to bottom, #6db3f2 17%,#54a3ee 41%,#54a3ee 54%,#3690f0 75%,#1e69de 100%);">
            <h4 class="letra text-white">Roles Asignados</h4>
        </div>
        <div class="modal-body">
             <div class="form-group mb-3">
               <label for=""><b>USUARIO</b></label>
               <input type="text" readonly id="usuariodatorolesasignados" class="form-control">
             </div>
             <table class="table table-bordered table-striped table-hover table-sm nowrap responsive" 
             id="tabla_roles_user"
             style="width: 100%">
               <thead style="background:linear-gradient(to bottom, #6db3f2 17%,#54a3ee 41%,#54a3ee 54%,#3690f0 75%,#1e69de 100%);">
                <tr>
                   <th>ID</th>
                   <th class="py-3 text-white letra">NOMBRE DEL ROL</th>
                   <th class="py-3 text-white letra">QUITAR</th>
                </tr>
               </thead>
             </table>
        </div>
         
        </div>
    </div>
 </div>
@endsection

@section('js')
<script>
var URLBASE = "{{URL_BASE}}";
var TOKEN = "{{$this->Csrf_Token()}}";
var USER_ID;
var PERSON_ID;
var PACIENTE;
var TablaUsers;
var TablaPacientesSinCuenta;
var TablaRolesNoAsignedUser;
var TablaRolesDelUsuario;
  </script>
  <script src="{{URL_BASE}}public/js/control.js"></script>
  <script src="{{URL_BASE}}public/js/gestionuser.js"></script>
  <script src="{{URL_BASE}}public/js/usuario.js"></script>
  <script>
    $(document).ready(function(){

      /******************** ENTRADA DE DATOS ******************************/ 
      let Documento = $('#documento'); let Rol = $('#rol'); let TipoDocumento = $('#tipo_doc'); let Apellidos = $('#apellidos');
      let Nombres = $('#nombres'); let Genero = $('#genero'); let Direccion = $('#direccion'); let FechaNacimiento = $('#fecha_nac');
      let Departamento = $('#departamento'); let Provincia = $('#provincia'); let Distrito = $('#distrito');
      let Username = $('#username'); let Email = $('#email'); let Password = $('#password');
      let PacienteMedico = $('#role_medic_paciente'); let Estado = $('#estado');
      let NameAccount = $('#name_account'); let EmailAccount = $('#email_account');
      let PasswordAccount = $('#password');
      showUsers();
      editarUser(TablaUsers,'#tabla-usuarios tbody');
      ConfirmaAntesDeEliminar(TablaUsers,'#tabla-usuarios tbody');
      AsignarRoles(TablaUsers,'#tabla-usuarios tbody');
      VerRolesUser(TablaUsers,'#tabla-usuarios tbody');
      
      //showUserCuentaInactive();
      showUserNotCuentaSystem();
      addAccountPaciente(TablaPacientesSinCuenta,'#tabla-usuarios-cuenta tbody');
      let Accion = 'save';

      
      /// MODAL PARA CREAR USUARIOS
      $('#modal-create-user').click(function(){
      Accion = 'save';
      $('#password').val("");
      $('#password_div').show();
      $('#btn_save').show();
      $('#btn_update').hide();
      $('#estado_div').hide();
      $('#text_modal_user').text("Crear usuarios");
      $('#text_modal_user').css("color","white");
      $('#sedeselect').prop("selectedIndex",0)
      $('.bx-user-plus').show();
      $('.bx-edit-alt').hide();
      $('#modal_user .modal-header').css("background", "linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 51%,#539fe1 79%,#87bcea 100%)");
      // ShowDepartamentos('departamento');
      // showProvincias($('#departamento').val(),'provincia'); 

      $('#form_users_create')[0].reset();
      //showDistritos_Provincia($('#provincia').val(),'distrito');
      
      $('#modal_user').modal('show');
      $('#role_medic_paciente').hide();
      $('#rol').show();

      /// reset input
      $('#form_users_create')[0].reset();
      $('#sedeselect').attr("disabled",false);
      });

      $('#save_roles_user').click(function(){
       saveRolesUser();
      });

      $('#salir_roles_user').click(function(){
        CancelAsignedRolesUser();
        $('#modal_asignar_roles').modal("hide");
      });

      $('#ul_roles_asigned').on('click','button',function(){
        
        $(this).tab("show");
      });

      /// a la escucha al selecionar un rol para el usuario
      $('#roles_no_asignados').on('click','input[type="checkbox"]',function(){
        let IdRoleSeleccionado = $(this).val();
        let Check = $(this).is(":checked");
        
        if(Check){
          /// se agrega el rol a la lista del usuario
          addListRoleUser(IdRoleSeleccionado);
        }else{
          /// Quitamos al rol de la lista del usuario
          QuitarRoleListUser(IdRoleSeleccionado);
        }

      });

      /// guardar nuevo usuario
      $('#btn_save').click(function(){
         //alert($('#sedeselect').val());return;
        let gestion = new gestionUser(Documento,Apellidos,Nombres,Genero, Direccion,FechaNacimiento,TipoDocumento,Distrito,Username,Email,Password,Rol,URLBASE,$("#sedeselect"));
      
        gestion.save();
      })
      $('#rol').change(function(){
         if($(this).val().trim() === 'admin_general'){
          $('#sedeselect').prop("selectedIndex",0);
          $('#sedeselect').attr("disabled",true);
         }else{
          $('#sedeselect').attr("disabled",false);
         }
      })

      /// crear cuenta de usuario del paciente
      $('#save_account_paciente').click(function(){

        if(NameAccount.val().trim().length == 0)
        {
          NameAccount.focus();
        }
        else
        {
          if(EmailAccount.val().trim().length == 0)
          {
            EmailAccount.focus();
          }
          else{
            if(PasswordAccount.val().trim().length == 0)
            {
              Password.focus();
            }
            else
            {
              /// crear cuenta de usuario a paciente
              saveAccountPacientes(NameAccount,EmailAccount,PasswordAccount);
            }
          }
        }
      });

      $('.close_user_paciente').click(function(){
        EmailAccount.val("");
      });
      
      $('.close_user').click(function(evento){
        evento.preventDefault();
        $('#provincia').empty();
        $('#distrito').empty();
        $('#alerta').hide();
      });

      Departamento.change(function(){
        showDistritos_Provincia(null,'distrito');
        showProvincias($(this).val(),'provincia');
      });
      Provincia.change(function(){
        showDistritos_Provincia($(this).val(),'distrito');
      });

      /// actualizar datos del usuario
      $('#btn_update').click(function(){

        
       if(Documento.val().trim().length == 0)
       {
        Documento.focus();
       }
       else{
        if(Apellidos.val().trim().length == 0)
        {
          Apellidos.focus();
        }
        else
        {
          if(Nombres.val().trim().length == 0)
          {
            Nombres.focus();
          }
          else
          {
            if(FechaNacimiento.val().trim() === '')
            {
              Swal.fire({
                title:'¡ADVERTENCIA!',
                text:'Ingrese la fecha de nacimiento del usuario',
                icon:'warning',
                target:document.getElementById('modal_user')
              })
            }else
            {
            if(Username.val().trim().length == 0)
            {
            Username.focus();
            }
            else
            {
            if(Email.val().trim().length == 0)
            {
            Email.focus();
            }else
            {
              if(Rol.val() == null)
              {
              updateUser(
                Documento,Apellidos,Nombres,Genero,
                Direccion,FechaNacimiento,TipoDocumento,Distrito,
                Username,Email,Estado,PacienteMedico,$('#sedeselect')
                );
              }else
              {
                updateUser(
                Documento,Apellidos,Nombres,Genero,
                Direccion,FechaNacimiento,TipoDocumento,Distrito,
                Username,Email,Estado,Rol,$('#sedeselect')
                )
              }
            }
            
            }
            }
          }
        }
       }
      });

      /// validación al presionar enter
      enter('documento','apellidos');
      enter('apellidos','nombres')
      enter('nombres','direccion')
      enter('direccion','username')
      enter('username','email')
      enter('email','password')
      enter('password','password')
 QuitarRoleUser('#tabla_roles_user');
  });

/// método para crear la cuenta de usuario del paciente
function saveAccountPacientes(name__,email__,pass__)
{
  $.ajax({
    url:URLBASE+"pacientes/create_account/"+PERSON_ID,
    method:"POST",
    data:{token_:TOKEN,name:name__.val(),email:email__.val(),pass:pass__.val()},
    success:function(response)
    {
      response = JSON.parse(response);

      if(response.response === 'ok')
      {
        Swal.fire({
          title:"Mensaje del sistema!",
          text:"Cuenta creada correctamente para el paciente "+PACIENTE,
          icon:"success",
          target:document.getElementById('account_pacientes_add')
        }).then(function()
        {
           
           showUserNotCuentaSystem();
        });
      }
      else
      {
        if(response.response === 'existe')
        {
        $('#email_account').select();
        Swal.fire({
          title:"Mensaje del sistema!",
          text:"El email que especificaste ya existe",
          icon:"info",
          target:document.getElementById('account_pacientes_add')
        });
        }
        else
        {
        Swal.fire({
          title:"Mensaje del sistema!",
          text:"Acaba de ocurrir un error al crear la cuenta de usuario del paciente "+PACIENTE,
          icon:"error",
          target:document.getElementById('account_pacientes_add')
        });
        }
      }
    }
  })
}  
/// modificar datos del usuario
function updateUser(
  documento_,apellidos_,nombres_,genero_,
  direccion_,fecha_nac_,tipo_doc_,distrito_,
  name_,email_,estado_,rol_,sededata
)
{
  $.ajax({
    url:URLBASE+"user/"+PERSON_ID+"/"+USER_ID+"/update",
    method:"POST",
    data:{token_:TOKEN,doc:documento_.val(),email:email_.val(),apellidos:apellidos_.val(),
         nombres:nombres_.val(),genero:genero_.val(),direccion:direccion_.val(),fecha_nac:fecha_nac_.val(),
         tipo_doc:tipo_doc_.val(),distrito:distrito_.val(),name:name_.val(),rol:rol_.val(),estado:estado_.val(),
        sede:sededata.val()},
    success:function(response)
    {
      response = JSON.parse(response);

      if(response.response === 'ok')
      {
        Swal.fire({
          title:"Mensaje del sistema!",
          text:"Datos actualizados correctamente",
          icon:"success",
        }).then(function()
        {
          showUsers();
        });
        $('#modal_user').modal('hide');
      }
      else
      {

      }
    }
  });
}  
  
function showUsers()
          {
           TablaUsers = $('#tabla-usuarios').DataTable({
          retrieve:true,
          responsive:true,
          autoWidth:false,
          language:SpanishDataTable(),
          "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
          }],
          ajax:{
          url:URLBASE+"user_gestion_mostrar?token_="+TOKEN,
          method:"GET",
          dataSrc:"usuarios"
          },
          "order": [[1, 'asc']], /// enumera indice de las columnas de Datatable
          columns:[
          {"data":"nombres"},
          {"data":"id_persona",render:function(persona_id)
          {
            if(persona_id != null)
            {
              return `
              <button class="btn rounded btn-outline-warning btn-sm" id='editar'><i class='bx bxs-edit-alt'></i></button>
              <button class="btn rounded btn-outline-danger btn-sm" id='eliminar'><i class='bx bxs-message-square-x'></i></button>
              <button class="btn rounded btn-outline-success btn-sm" id='asignar_role'><i class='bx bxs-user-detail'></i></button>
              <button class="btn rounded btn-outline-info btn-sm" id='ver_roles'><i class='bx bxs-user-account'></i></button>
              `
            }

            return `
          <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2">
              <button class="btn rounded btn-outline-danger btn-sm" id='eliminar'><i class='bx bxs-message-square-x'></i></button>
            </div>
          </div>
          `
          },className:"text-center"},
          {"data":null,render:function(dta){
            if(dta.nombres == null)
            {
              return    '<span class="badge bg-warning">Cuenta no completado</span>';
            }
            return (dta.apellidos+" "+dta.nombres).toUpperCase();
          }},
          {"data":"name",render:function(name){return '<span class="badge bg-success">'+name+'</span>';}},
          {"data":"genero",render:function(genero){
          if(genero === '1')
          {
          return '<span class="badge bg-primary">Masculino</span>';
          }else{
            if(genero == null)
            {
              return '<span class="badge bg-info">No especifica aún</span>';
            }
            return '<span class="badge bg-danger">Femenino</span>';
          }
          
          }},
          {"data":"email"},
          {"data":"rol",render:function(roldata){
            return (roldata === 'admin_general' ? 'SUPER ADMINISTRADOR':(roldata === 'admin_farmacia' ? 'ADMIN FARMACIA':roldata.toUpperCase()));
          }},
          {"data":"foto",render:function(foto){
          let Directorio = URLBASE+"public/asset/";
          if(foto !== null)
          {
          Directorio+='foto/'+foto;
          }
          else{
          Directorio+='img/avatars/anonimo_2.png';
          }
          return `<img src='`+Directorio+`' style="width: 36px;height: 33px;border-radius:50%">`;
          }},
          {"data":"estado",render:function(estado){
          if(estado === '1')
          {
          return '<span class="badge bg-primary">Activo</span>';
          }
          return '<span class="badge bg-danger">Inactivo</span>';
          }}, 
          {"data":"namesede",render:function(sededato){
            if(sededato != null){
               return sededato.toUpperCase();
            }
            return "<span class='badge bg-danger'>TODAS LAS SUCURSALES</span>";
          }}         
          ]
          }).ajax.reload(null,false);
          
          /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
          TablaUsers.on( 'order.dt search.dt', function () {
          TablaUsers.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
          } );
          }).draw();
          
          }
      
       /// eliminamos al usuario
       function ConfirmaAntesDeEliminar(Tabla,Tbody)
       {
         $(Tbody).on('click','#eliminar',function(){
          let FilaSeleccionada = $(this).parents('tr');

          if(FilaSeleccionada.hasClass('child'))
          {
            FilaSeleccionada = FilaSeleccionada.prev();
          }

          let Datos = Tabla.row(FilaSeleccionada).data();
          let Rol = Datos.rol;

          USER_ID = Datos.id_usuario;
      Swal.fire({
          title: 'Estas seguro de eliminar al usuario '+Datos.name+'?',
          text: "al presionar que si, se eliminarán todo sus datos de este usuario",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si,eliminar!',
          }).then((result) => {
          if (result.isConfirmed) {
          EliminaUser(USER_ID,Datos.name,Rol)
          }
          })
         });
       }   

       /// eliminar la cuenta del usuario
       function EliminaUser(id,user,rol)
       {
        $.ajax({
          url:URLBASE+"usuario/"+id+"/delete",
          method:"POST",
          data:{token_:TOKEN,rol:rol},
          success:function(response)
          {
            response = JSON.parse(response);
            if(response.response === 'ok')
            {
              Swal.fire(
                {
                  title:'Mensaje del sistema!',
                  text:'La cuenta se a eliminado correctamente!',
                  icon:'success'
                }
              ).then(function(){
                showUsers();
              });
            }
            else
            {
              if(response.response === 'existe')
              {
                Swal.fire(
                {
                  title:'Mensaje del sistema!',
                  text:'Lo sentimos , pero no podemos eliminar esta cuenta',
                  icon:'error'
                }
              )
              }else
              {
                if(response.response === 'no'){
                  Swal.fire(
                {
                  title:'Mensaje del sistema!',
                  text:'Lo sentimos , pero no podemos eliminar tu propia cuenta!!',
                  icon:'error'
                }
              )
                }else{
                  Swal.fire(
                {
                  title:'Mensaje del sistema!',
                  text:'Acaba de ocurrir un error al eliminar la cuenat del usuario '+user,
                  icon:'error'
                }
              )
                }
              }
            }
          }
        })
       }
          
          /// mostrar los usuario que tienen la cuenta inactiva
          function showUserCuentaInactive()
          {
          let TablaUserCuentaInactive = $('#tabla-usuarios-inactive').DataTable({
          responsive:true,
          language:SpanishDataTable(),
          })
          }
          /// mostrar los usuario que no tienen una cuenta en el sistema
          function showUserNotCuentaSystem()
          {
          TablaPacientesSinCuenta = $('#tabla-usuarios-cuenta').DataTable({
          retrieve:true,
          responsive:true,
          language:SpanishDataTable(),
          "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
          }],
          ajax:{
            url:URLBASE+"pacientes_sin_cuenta_de_usuario?token_="+TOKEN,
            method:"GET",
            dataSrc:"pacientes",
          },
          columns:[
           {"data":"documento"}, 
           {"data":"documento"}, 
           {"data":null,render:function(person){return person.apellidos+' '+person.nombres}},
           {"data":null,render:function(){
            return '<button class="btn btn-rounded btn-outline-primary btn-sm" id="account_paciente"><i class="bx bxs-user-account"></i></button>';
           }} 
          ]
          }).ajax.reload(null,false);;

          TablaPacientesSinCuenta.on( 'order.dt search.dt', function () {
          TablaPacientesSinCuenta.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
          })
          }).draw();
          }

          /// editar paciente para añadirle una cuenta de usuario al sistema
          function addAccountPaciente(Tabla,Tbody)
          {
            $(Tbody).on('click','#account_paciente',function(){

              $('#account_pacientes_add').modal('show');

              let fila = $(this).parents('tr');

              if(fila.hasClass('child'))
              {
                fila = fila.prev();
              }

              let Data = Tabla.row(fila).data();

              $('#name_account').val(Data.nombres);
              $('#password').val(Data.documento);

              PERSON_ID = Data.id_persona;
              PACIENTE = Data.apellidos+" "+Data.nombres;
            });
          }
          
          /// editar a los usuarios
          function editarUser(Tabla,Tbody)
          {
            ShowDepartamentos('departamento');
          $(Tbody).on('click','#editar',function(){
          
          
          Accion = 'update';
          $('#password_div').hide();
          $('#password').val("")
          $('#btn_save').hide();
          $('#btn_update').show();
          $('#estado_div').show()
          $('#text_modal_user').text("Editar usuarios");
          $('#text_modal_user').css("color","#483D8B");
          $('.bx-user-plus').hide();
          $('.bx-edit-alt').show();
          $('#modal_user .modal-header').css("background", "linear-gradient(to bottom, #40E0D0 100%,#f8b500 49%,#fccd4d 50%,#fbdf93 99%)")
          $('#modal_user').modal("show")
          $('#sedeselect').attr("disabled",false);

          let fila = $(this).parents('tr');
          
          if(fila.hasClass('child'))
          {
          fila = fila.prev();
          }
          
          let Datos = Tabla.row(fila).data();
          if(Datos.rol === 'admin_general'){
            $('#sedeselect').prop("selectedIndex",0);
            $('#sedeselect').attr("disabled",true);
          }else{
            $('#sedeselect').attr("disabled",false);
           $('#sedeselect').val(Datos.sede_id)
          }
          
          //alert(Datos.id_departamento+"  "+Datos.id_provincia+" "+Datos.id_distrito)
          if(Datos.rol === 'Médico' || Datos.rol === 'Paciente')
          {
            $('#role_medic_paciente').show();
            $('#rol').hide();
          }
          else{
            $('#role_medic_paciente').hide();
            $('#rol').show();
          }

          /*************Agregar este codigo 1******************/ 
          showProvincias(Datos.id_departamento,'provincia');
          showDistritos_Provincia(Datos.id_provincia,'distrito');
          /************* solo hasta aqui ***************************/ 
          
          $('#documento').val(Datos.documento); $('#tipo_doc').val(Datos.id_tipo_doc); $('#rol').val(Datos.rol);
          $('#apellidos').val(Datos.apellidos); $('#nombres').val(Datos.nombres); $('#genero').val(Datos.genero);
          $('#direccion').val(Datos.direccion); $('#fecha_nac').val(Datos.fecha_nacimiento); $('#departamento').val(Datos.id_departamento);
          $('#username').val(Datos.name); $('#email').val(Datos.email);$('#estado').val(Datos.estado);
          $('#role_medic_paciente').val(Datos.rol);  
          
           
          /************************ Agregar este codigo 2 *******/ 
          $('#distrito').val(Datos.id_distrito);
          $('#provincia').val(Datos.id_provincia);
           /************************ solo hasta aqui *******/ 


           
          USER_ID = Datos.id_usuario; PERSON_ID = Datos.id_persona;
          
          });
          } /// método que muestrar los departamentos en format json
function ShowDepartamentos(idselect)
{
  option='<option selected disabled>--- Seleccione ---</option>';
    $.ajax(
        {
          url:URLBASE+"departamento/mostrar?token_="+TOKEN,

          method:"GET",

          success:function(response)
          {
            response = JSON.parse(response)
            
            if(response.response.length > 0)
            {
                response.response.forEach(documento => {
                    
                    option+=
                     `
                      <option value=`+documento.id_departamento+`>`+documento.name_departamento.toUpperCase()+`</option>
                    `;
                });
            }
            $('#'+idselect).html(option)
          } 
        }
    )
    //showProvincias("Ancash",'provincia');
}
/// mostrar las provincias
function showProvincias(id_dep,select_id)
{
  let option ="<option selected disabled>--- Seleccione ----</option>";
  
  let resultado = show(URLBASE+"provincia/mostrar?token_="+TOKEN,{id_departamento:id_dep});
  
  if(resultado.length > 0)
  {
   resultado.forEach(provincia => {
    
    option+= "<option value="+provincia.id_provincia+">"+provincia.name_provincia.toUpperCase()+"</option>";

   });
   
  }
  $('#'+select_id).html(option);
  //showDistritos_Provincia("CARHUAZ",'distrito');
}

/// mostramos los distros por provincia

function showDistritos_Provincia(id_prov,select_id)
{
  let option ="<option selected disabled>--- Seleccione ----</option>";
  
  let resultado = show(URLBASE+"distritos/mostrar-para-la-provincia/"+id_prov+"?token_="+TOKEN);

  if(resultado.length > 0)
  {
   resultado.forEach(distrito => {
    
    option+= "<option value="+distrito.id_distrito+">"+distrito.name_distrito.toUpperCase()+"</option>";

   });
   
  }
  $('#'+select_id).html(option);
}

function hello()
{
  alert(22)
}
</script>
@endsection