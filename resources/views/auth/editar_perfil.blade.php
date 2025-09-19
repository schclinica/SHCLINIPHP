@extends($this->Layouts("dashboard"))

@section("title_dashboard","Editar-Perfil")

@section('css')
<style>
    #imagen_ {
        max-width: 70%;
        width: 220px;
        height: 210px;
    }
</style>
@endsection
@section('contenido')
<div class="row">
    <div class="col-xl-8 col-lg-8 col-12">
        <div class="card">
            <form action="{{$this->route('usuario/update_profile')}}" method="post" enctype="multipart/form-data">
               <div class="card-body">
                   <div class="card-text">
                       <p class="h4">Datos personales</p>
                   </div>
                   <div class="row">
                    <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                       <div class="col-xl-5 col-lg-5 col-md-6 col-12"><b>Tipo documento <span
                                   class="text-danger">(*)</span></b>
                           <select name="tipo_doc" id="tipo_doc" class="form-select">
                            @if (isset($TipoDocs))
                                @foreach ($TipoDocs as $tipo)
                                    <option value="{{$tipo->id_tipo_doc}}" @if($tipo->id_tipo_doc === $this->profile()->id_tipo_doc) selected @endif>{{$tipo->name_tipo_doc}}</option>
                                @endforeach
                            @endif
                           </select>
                       </div>
                       <div class="col-xl-7 col-lg-7 col-md-6 col-12"><b># Documento <span
                                   class="text-danger">(*)</span></b>
                           <input type="text" name="documento" id="documento" class="form-control" placeholder="# Documento"
                           value="{{$this->profile()->documento}}" autofocus>
                       </div>
           
                       <div class="col-12"><b>Apellidos <span
                                   class="text-danger">(*)</span></b>
                           <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Apellidos..."
                           value="{{$this->profile()->apellidos}}" >
                       </div>
                   </div>
           
                   <div class="row">
                       <div class="col-xl-7 col-lg-7 col-md-6 col-12"><b>Nombres <span class="text-danger">(*)</span></b>
                           <input type="text" name="nombres" id="nombres" class="form-control" placeholder="Nombres..."
                           value="{{$this->profile()->nombres}}" >
                       </div>
                       <div class="col-xl-5 col-lg-5 col-md-6 col-12"><b>Género <span class="text-danger">(*)</span></b>
                           <select name="genero" id="genero" class="form-select">
                            <option value="1" @if($this->profile()->genero === '1') selected @endif>Másculino</option>
                            <option value="2"  @if($this->profile()->genero === '2') selected @endif>Femenino</option>
                           </select>
                       </div>
           
                       <div class="col-12"><b>Dirección<span class="text-danger">(*)</span></b>
                           <input type="text" name="direccion" id="direccion" placeholder="Dirección...." class="form-control"
                           value="{{$this->profile()->direccion}}">
                       </div>

                       <div class="col-12"><b>Fecha de nacimiento<span class="text-danger">(*)</span></b>
                        <input type="date" name="fecha_nac" id="fecha_nac"  class="form-control"
                        value="{{$this->profile()->fecha_nacimiento == null ? $this->addRestFecha("Y-m-d","- 30 year"):$this->profile()->fecha_nacimiento}}">
                    </div>
                   </div>
                   <div class="card-text mt-4">
                       <p class="h4">Datos de usuario</p>
                   </div>
                   <div class="row">
                       <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"><b>Nombre de pila <span
                                   class="text-danger">(*)</span></b>
                           <input type="text" name="username" id="username" class="form-control" placeholder="Nombre de pila..."
                           value="{{$this->profile()->name}}">
                       </div>
                       <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"><b>Email<span class="text-danger">(*)</span></b>
                           <input type="text" name="email" id="email" class="form-control" placeholder="Correo electrónico..."
                           value="{{$this->profile()->email}}">
                       </div>
                   </div>
                   <div class="row justify-content-center mt-3">
                       
                       <img src="{{getFoto($this->profile()->foto)}}" alt="" class="img-fluid img-thumbnail"
                           style="border-radius: 50%;border: solid 2px blue" id="imagen_">
                   </div>
           
                   <div class="row justify-content-center mt-2 mb-4">
                       <div class="col-xl-5 col-lg-5 col-md-5 col-sm-6 col-12">
                           <button class="btn btn-rouded btn-outline-primary form-control" id="nueva_foto">Subir nueva foto <i class='bx bx-upload'></i></button>
                           <input type="file" name="foto" id="foto" style="display: none" accept=".jpg,.png">
                       </div>
                   </div>

                 @if ($this->ExistSession("success"))
                 <div class="alert alert-success mb-2">{{$this->getSession("success")}}</div>
                 {{$this->destroySession("success")}}
                 @endif
               </div>
               @if ($this->profile()->rol === "Paciente")
                <div class="card-footer text-end">
                    @if (isset($this->profile()->id_persona))
                    <button class="btn_3d"><b> Guardar cambios <i class='bx bxs-save'></i></b></button>
                    @else
                    <div class="alert alert-danger">
                        Complete sus datos por favor! <a href="{{$this->route(" paciente/completar_datos")}}"
                            class="btn btn-rounded btn-info btn-sm">Ir <i class='bx bx-right-arrow-alt'></i></a>
                    </div>
                    @endif
                </div>
                @else 
                  <div class="card-footer text-end">
                  <button class="btn_3d"><b> Guardar cambios <i class='bx bxs-save'></i></b></button>
                  </div>
               @endif
           </form>
           </div>
    </div>

    <div class="col-xl-4 col-lg-4 col-12 mt-xl-0 mt-lg-0 mt-2">
        <div class="card">
            <div class="card-body">
                <div class="card-text"><p class="h4">Cambiar contraseña </p></div>
                
                    <div class="form-group">
                        <label for="pasword_actual"><b>Password Actual <span class="text-danger">(*)</span></b></label>
                        <input type="password" name="pa" id="pa" class="form-control is-invalid">
                    </div>
                    <div class="form-group">
                        <label for="pass_nuevo"><b>Nuevo Password <span class="text-danger">(*)</span></b></label>
                        <input type="password" name="pass_nuevo" id="pass_nuevo" class="form-control is-invalid">
                    </div>

                    <div class="form-group">
                        <label for="cp"><b>Confirmar Password <span class="text-danger">(*)</span></b></label>
                        <input type="password" name="cp" id="cp" class="form-control is-invalid">
                    </div>
             <div class="alert alert-danger mt-1" style="display: none" id="alert_no_pas">El Password actual no coincide con su password</div>
             <div class="alert alert-danger mt-1" style="display: none" id="alert_err_pas">Verifique los errores!</div>
             <div class="alert alert-success mt-1 text-primary" style="display: none" id="alert_success_pass">Su password a sido modificado correctamente 😁 </div>

             <div class="col-12 text-center mt-3">
                <button class="btn-save" id="save_pass" style="display: none"><b> Guardar <i class='bx bx-save'></i></b></button>
             </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>
  var RUTA = "{{URL_BASE}}" // la url base del sistema
  var TOKEN = "{{$this->Csrf_Token()}}";
 $(document).ready(function(){
    
   /******************** VARIABLES *******************/ 
   let PasswordActual = $('#pa'); let PasswordNuevo = $('#pass_nuevo');
    let PasswordConfirm = $('#cp');

  $('#nueva_foto').click(function(evento){
    evento.preventDefault();
    $('#foto').click();
  });

  $('#foto').change(function(){
    ReadImagen(this,'imagen_',event.target.files[0]);
  });

  PasswordActual.keyup(function(){
    if($(this).val().trim().length > 0)
    {
    $(this).addClass("is-valid");
    $(this).removeClass("is-invalid");
    }
    else
    {
    $(this).addClass("is-invalid");
    $(this).removeClass("is-valid");  
    }
 
  });

  PasswordNuevo.keyup(function(){

    if($(this).val().trim().length > 0)
    {
      if(PasswordConfirm.val().trim().length > 0)
      {
        if(PasswordConfirm.val().trim() !== $(this).val().trim())
        {
            PasswordConfirm.addClass("is-invalid");
            PasswordConfirm.removeClass("is-valid"); 
            $('#save_pass').hide(700);
        }
        else{
            PasswordConfirm.addClass("is-valid");
            PasswordConfirm.removeClass("is-invalid"); 
            $('#save_pass').show(700);
        }
      }
      else
      {
        $(this).addClass("is-valid");
        $(this).removeClass("is-invalid");
      }
    }
    else
    {
    $(this).addClass("is-invalid");
    $(this).removeClass("is-valid");  
    }
  });

  PasswordConfirm.keyup(function(){

    if($(this).val().trim().length > 0)
    {
        if($(this).val().trim() === PasswordNuevo.val().trim())
        {
            $(this).addClass("is-valid");
            $(this).removeClass("is-invalid"); 
            $('#save_pass').show(700);
        }
        else
        {
            $(this).addClass("is-invalid");
            $(this).removeClass("is-valid"); 
            $('#save_pass').hide(700);
        }
    }
  });

  $('#save_pass').click(function(){

    if(PasswordActual.val().trim().length == 0 && PasswordNuevo.val().trim().length == 0 && PasswordConfirm.val().trim().length == 0)
    {
        PasswordActual.addClass("is-invalid"); PasswordNuevo.addClass("is-invalid"); PasswordConfirm.addClass("is-invalid");
        PasswordActual.focus();
    }
    else
    {
       if(PasswordActual.hasClass("is-invalid") || PasswordNuevo.hasClass("is-invalid") || PasswordConfirm.hasClass("is-invalid"))
       {
        $('#alert_err_pas').show();
        $('#alert_no_pas').hide();
        $('#alert_success_pass').hide();
       }
       else
       {
        ModificarPassword(PasswordActual.val(),PasswordNuevo.val());
       }
    }
  });

    /// actualizamos el password del usuario
    function ModificarPassword(pass_Actual,pass_Nuevo)
    {
        $.ajax({
            url:RUTA+"usuario/modificar_password",
            method:"POST",
            data:{token_:TOKEN,pa:pass_Actual,password:pass_Nuevo},
            success:function(response)
            {
                response = JSON.parse(response);

                if(response.response === 'ok')
                {
                     $('#alert_no_pas').hide();
                     $('#alert_err_pas').hide();
                     $('#alert_success_pass').show(400);
                     PasswordActual.val("");
                     PasswordConfirm.val("");
                     PasswordNuevo.val("");
                     PasswordActual.focus();
                     PasswordActual.removeClass("is-valid");
                     PasswordNuevo.removeClass("is-valid");
                     PasswordConfirm.removeClass("is-valid");
                     PasswordActual.addClass("is-invalid");
                     PasswordNuevo.addClass("is-invalid");
                     PasswordConfirm.addClass("is-invalid");
                }
                else
                {
                    if(response.response === 'no')
                    {
                        $('#alert_no_pas').show(400);
                        $('#alert_err_pas').hide();
                        $('#alert_success_pass').hide();
                    }
                }
            }
        })
    }
 
    // enter
    enter('documento','apellidos');
    enter('apellidos','nombres');
    enter('nombres','direccion');
    enter('direccion','username');
    enter('username','email');
    enter('email','email');
    enter('pa','pass_nuevo');
    enter('pass_nuevo','cp');
 });  
</script>
@endsection