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

    <title>login</title>

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
      
      .logo_div{
          padding:0px;
      }
      #imagen_logo{
        max-width: 500px;
        height: auto;
        object-fit: cover;
        opacity: 0.90;
      }
 @media screen and (max-width: 600px) {
      #imagen_logo {
      max-width: 93%;
      }
      }

  .divider:after,
      .divider:before {
      content: "";
      flex: 1;
      height: 1px;
      background: #eee;
      }
      .h-custom {
      height: calc(100% - 73px);
      }
      @media (max-width: 450px) {
      .h-custom {
      height: 100%;
      }
      }
    
    </style>
  </head>
  <body style="background-color: white">
    <section class="vh-100">
      <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center  h-100">
          <div class="col-md-9 col-lg-6 col-xl-5">
            @if (isset($this->BusinesData()[0]->logo))
                         @if (!file_exists("public/asset/empresa/".$this->BusinesData()[0]->logo))
                         <img src="{{$this->asset("img/lgo_clinica_default.jpg")}}" id="imagen_logo"  class="img-fluid" >
                         @else 
                         <img src="{{$this->asset(isset($this->BusinesData()[0]->logo) ?"empresa/".$this->BusinesData()[0]->logo:"img/lgo_clinica_default.jpg")}}" id="imagen_logo" class="img-fluid" />
                         @endif
                         @endif
          </div>
          <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
            <div>
              <h1>Iniciar sesión</h1>
              <a href="{{$this->route("./")}}">
               <i class='bx bx-home'></i>
               <b>Página de inicio</b>
              </a>
              @if ($this->ExistSession("success_reset"))
              <div class="text-center alert alert-success">
              <p>{{$this->getSession("success_reset")}}</p>
              </div>
              @php $this->destroySession("success_reset") @endphp
              @endif
              @if ($this->ExistSession("response"))
              <div class="text-center alert alert-success">
              <p>{{$this->getSession("response")}}</p>
              </div>
              @php $this->destroySession("response") @endphp
              @endif
             </div>
            <form  id="form_login" class="mt-3"  action="{{$this->route('login/sigIn')}}" method="POST">
              <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
              <!-- Email input -->
              <div class="form-floating">
                <input type="text" id="email_username" name="email_username" class="form-control form-control-lg mb-3"
                value="{{$this->old("email_username")}}"  placeholder="Username | email...." />
                  <label for="email_username">Email | Username</label>
              </div>
              @if ($this->ExistSession("error_user"))
              <b class="text-danger mx-1"> {{$this->getSession("error_user")}} <i class='bx bxs-sad'></i></b>
              @php $this->destroySession("error_user") @endphp
              @endif
              <!-- Password input -->
              <div class="form-floating">
                <input type="password" id="password" name="password" class="form-control form-control-lg"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                 <label for="password">Password</label>
              </div>
            @if ($this->ExistSession("error_pas"))
              <b class="text-danger mx-1"> {{$this->getSession("error_pas")}} <i class='bx bxs-sad'></i></b>
              @php $this->destroySession("error_pas") @endphp
              @endif
    
              <div class="d-flex justify-content-between align-items-center mt-2">
                <!-- Checkbox -->
                <div class="form-check mb-0">
                  <input class="form-check-input me-2" name="remember_me" type="checkbox" value="" id="form2Example3" />
                  <label class="form-check-label" for="remember_me">
                    Recordar mi sesión
                  </label>
                </div>
                <div class="d-flex justify-content-between">
                  <a href="{{$this->route('reset-password')}}">
                    <small><b>Olvidaste tu contraseña?</b></small>
                  </a>
                </div>
              </div>
    
              <div class="text-center text-lg-start mt-4 pt-2">
                <button class="btn_3d  d-grid w-100 p-2" type="submit" id="login"><b>Iniciar sesión <i class='bx bx-log-in'></i></b> </button>
                <p class="small fw-bold mt-2 pt-1 mb-0">Eres un nuevo paciente? <a href="{{$this->route('create_account_paciente')}}"
                    class="link-danger">Regístrate aquí</a></p>
              </div>
    
            </form>
          </div>
        </div>
      </div>
      <div
        class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5" style="background-color: #9400D3">
        <!-- Copyright -->
        <div class="mb-3 mb-md-0" style="color: white">
          Copyright © {{$this->FechaActual("Y-m-d")}}. Todos los derechos reservados.
        </div>
        <!-- Copyright -->
    
        <!-- Right -->
        <div>
          <a href="#!" class="text-white me-4">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="#!" class="text-white me-4">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="#!" class="text-white me-4">
            <i class="fab fa-google"></i>
          </a>
          <a href="#!" class="text-white">
            <i class="fab fa-linkedin-in"></i>
          </a>
        </div>
        <!-- Right -->
      </div>
    </section>
    
 
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
        let Username = $('#email_username');
        let Pasword = $('#password');
        enter('email_username','password');
        Username.focus();
        $('#login').click(function(evento){
          evento.preventDefault();

          if($('#email_username').val().trim().length == 0)
          {
            $('#email_username').focus();
          }
          else
          {
            if($('#password').val().trim().length == 0)
            {
              $('#password').focus();
            }
            else
            {
              $('#form_login').submit();
            }
          }


          
        });
      });
    </script>
  </body>
</html>