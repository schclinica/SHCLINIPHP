<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Create-Account</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{$this->asset('vendor/fonts/boxicons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{$this->asset('vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{$this->asset('vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{$this->asset('css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{$this->asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

    <link rel="stylesheet" href="{{$this->asset('css/estilos.css')}}">
    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{$this->asset('vendor/css/pages/page-auth.css')}}" />
       <link rel="icon" type="image/x-icon"
        href="{{ count($this->BusinesData()) > 0 ? $this->asset('empresa/' . $this->BusinesData()[0]->icono) : $this->asset('img/essalud.ico') }}" />  
     
    <!-- Helpers -->
    <script src="{{$this->asset('vendor/js/helpers.js')}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{$this->asset('js/config.js')}}"></script>
    <style>
      #imagen_logo{
        filter: brightness(1.1);
        mix-blend-mode: multiply;
         
      }
      .card-header{
        padding: 3px;
      }
    </style>
  </head>

  <body  style="background-color: #F5FFFA">
    <!-- Content -->

    <div class="container-fluid">
      
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-xl-4 col-lg-5 col-md-5 col-md-7">
                <div class="card border-primary">
                    <div class="card-header" style="background-color:#E6E6FA">
                        <div class="card-text text-center">
                            @if (!file_exists("public/asset/empresa/".$this->BusinesData()[0]->logo))
                            <img src="{{$this->asset("img/lgo_clinica_default.jpg")}}" id="imagen_logo" style="width:180px;height:105px" >
                            @else 
                            <img src="{{$this->asset(isset($this->BusinesData()[0]->logo) ?"empresa/".$this->BusinesData()[0]->logo:"img/lgo_clinica_default.jpg")}}" id="imagen_logo" style="width:180px;height:105px" >
                            @endif

                            <p class="h3 text-primary text-center letra"><b>Create una cuenta</b></p>
                        </div>
                    </div>
                   <form action="{{$this->route("paciente/create_account")}}" method="post">
                    <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                    <div class="card-body">
                      <p>Ya tienes una cuenta ❓❓  <a href="{{$this->route("login")}}"><b>Iniciar sesión <i class='bx bx-right-arrow-alt'></i></b></a> </p>
                   
                      <div class="alert alert-danger p-2" id="mensaje_error" style="display: none">
                        Error , para poder continuar, complete todos los campos 😢
                     </div>
                      <div class="form-group mt-1">
                       <input type="text" class="form-control" id="usuario" name="usuario" placeholder="NOMBRE DE USUARIO..."
                       autofocus value="{{$this->old("usuario")}}">
                      </div>
                      <div class="form-group mt-2">
                       <input type="text" class="form-control" id="correo" name="correo" placeholder="CORREO ELECTRÓNICO..."
                       value="{{$this->old("correo")}}">
                      </div>
                      <div class="input-group input-group-merge mt-2">
                       <input type="password" class="form-control" id="pasword" name="pasword" placeholder="PASSWORD..."
                       style="font-size: 16px">
                       <span class="input-group-text cursor-pointer" id="pass_view"><i class="bx bx-hide" id="icon_pas"></i></span>
                      </div>

                      <div class="input-group input-group-merge mt-2">
                       <input type="password" class="form-control" id="confirm_pass" name="confirm_pass" placeholder="CONFIRMAR PASSWORD..."
                       style="font-size: 16px">
                       <span class="input-group-text cursor-pointer" id="pass_view_confirm"><i class="bx bx-hide" id="icon_pasconfirm"></i></span>
                      </div>
                   </div>

                   <div class="mx-4">
                     @if ( $this->ExistSession("response"))
                         @if ($this->getSession("response") === 'error')
                             <div class="alert alert-danger p-2">
                                Error, no podemos completar el proceso por ahora, intentelo 😢
                                más tarde!
                             </div>
                         @endif

                         @if ($this->getSession("response") === 'existe-paciente')
                             <div class="alert alert-warning p-2">
                                Lo sentimos, pero el correo que desea registrar ya existe!😢
                             </div>
                         @endif
                         @if ($this->getSession("response") === 'error_correo')
                             <div class="alert alert-danger p-2">
                                Lo sentimos, pero el correo que desea registrar es incorrecto!😢
                             </div>
                         @endif
                     @php $this->destroySession("response") @endphp
                     @endif 
                    
                   </div>
                   <div class="text-center mb-4 col-12">
                       <button class="btn_twiter col-xl-8 col-lg-8 col-md-8 col-sm-6 col-10" type="submit" id="register"><b>Registrarme <i class='bx bx-user-check'></i></b> </button>
                   </div>
                   </form>
                </div>
            </div>
        </div>
    </div>

 
    <script src="{{$this->asset('vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{$this->asset('vendor/libs/popper/popper.js')}}"></script>
    <script src="{{$this->asset('vendor/js/bootstrap.js')}}"></script>
    <script src="{{$this->asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{$this->asset('vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{$this->asset('js/main.js')}}"></script>
    <script src="{{URL_BASE}}public/js/control.js"></script>
    <script>
     $(document).ready(function(){
       let view_pass= 'on';
      /// validamos el enter
      enter("usuario","correo");
      enter("correo","pasword");
      enter("pasword","confirm_pass");

      /// variables
      let Usuario = $('#usuario'); let Correo = $('#correo'); let Pasword = $("#pasword");
      let Password_Confirm = $('#confirm_pass');

      $('#register').click(function(evento){
        if(Usuario.val().trim().length == 0  || Correo.val().trim().length == 0 || Pasword.val().trim().length == 0
        || Password_Confirm.val().trim().length == 0)
        {
          evento.preventDefault();
          $('#mensaje_error').text("Error , para poder continuar, complete todos los campos 😢");
          $('#mensaje_error').show(500);
        }
        else
        {
          if(Password_Confirm.val().trim() !== Pasword.val().trim())
          {
           evento.preventDefault();
           $('#mensaje_error').text("El campo de confirmar password no coincide con el password original 😢");
           $('#mensaje_error').show(500);
          }
        }
      });

      $('#pass_view').click(function(){
        if(view_pass === 'on')
        {
         Pasword.get(0).type = 'text';
         view_pass = 'off';
         $('#icon_pas').removeClass("bx bx-hide");
         $('#icon_pas').addClass("bx bx-show")
        }
        else
        {
          Pasword.get(0).type = 'password';
          view_pass = 'on';
          $('#icon_pas').removeClass("bx bx-show");
          $('#icon_pas').addClass("bx bx-hide")
        }
      });

      $('#pass_view_confirm').click(function(){
        if(view_pass === 'on')
        {
         Password_Confirm.get(0).type = 'text';
         view_pass = 'off';
         $('#icon_pasconfirm').removeClass("bx bx-hide");
         $('#icon_pasconfirm').addClass("bx bx-show")
        }
        else
        {
          Password_Confirm.get(0).type = 'password';
          view_pass = 'on';
          $('#icon_pasconfirm').removeClass("bx bx-show");
          $('#icon_pasconfirm').addClass("bx bx-hide")
        }
      });
     });
    </script>
  </body>
</html>