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

    <title>Recuperar-password</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

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

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{$this->asset('vendor/css/pages/page-auth.css')}}" />
    <!-- Helpers -->
    <script src="{{$this->asset('vendor/js/helpers.js')}}"></script>
       <link rel="icon" type="image/x-icon"
        href="{{ count($this->BusinesData()) > 0 ? $this->asset('empresa/' . $this->BusinesData()[0]->icono) : $this->asset('img/essalud.ico') }}" />  
     

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{$this->asset('js/config.js')}}"></script>
    <link rel="stylesheet" href="{{$this->asset('css/estilos.css')}}">
    <style>
      #imagen_logo{
        filter: brightness(1.1);
        mix-blend-mode: multiply;
         
      }
      .logo_div{
          padding:0px;
      }
    
    </style>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Forgot Password -->
          <div class="card">
            <div class="card-body">
              <div class='text-center logo_div'>
               @if (isset($this->BusinesData()[0]->logo))
               @if (!file_exists("public/asset/empresa/".$this->BusinesData()[0]->logo))
               <img src="{{$this->asset("img/lgo_clinica_default.jpg")}}" id="imagen_logo" style="width:180px;height:105px" >
               @else 
               <img src="{{$this->asset(isset($this->BusinesData()[0]->logo) ?"empresa/".$this->BusinesData()[0]->logo:"img/lgo_clinica_default.jpg")}}" id="imagen_logo" style="width:180px;height:105px" >
               @endif
               @endif
            </div>
              <!-- /Logo -->
              <h4 class="mb-2">Has olvidado la contraseña ? 🔒</h4>
              <p class="mb-2">Ingrese su correo electrónico y le enviaremos instrucciones para restablecer su contraseña</p>
              @if ($this->ExistSession("error"))
                  <span class="text-danger">{{$this->getSession("error")}}</span>
                  @php $this->destroySession("error") @endphp
              @endif

              @if ($this->ExistSession("success"))
                  <span class="text-success">{{$this->getSession("success")}}</span>
                  @php $this->destroySession("success") @endphp
              @endif
              <form id="formAuthentication" class="mb-3" action="{{$this->route('reset-password')}}" method="POST">
                <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                <div class="mb-3">
                  <label for="email" class="form-label"><b>Escriba su email (*)</b></label>
                  <input
                    type="text"
                    class="form-control"
                    id="email"
                    name="email"
                    placeholder="Escriba su correo electrónico"
                    autofocus
                  />
                </div>
                <button class="btn_3d w-100">Enviar enlace de reinicio <i class='bx bxs-envelope'></i></button>
              </form>
              <div class="text-center">
                <a href="{{$this->route('login')}}" class="d-flex align-items-center justify-content-center">
                  <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                  Iniciar Sesión
                </a>
              </div>
            </div>
          </div>
          <!-- /Forgot Password -->
        </div>
      </div>
    </div>

   

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{$this->asset('vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{$this->asset('vendor/libs/popper/popper.js')}}"></script>
    <script src="{{$this->asset('vendor/js/bootstrap.js')}}"></script>
    <script src="{{$this->asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{$this->asset('vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{$this->asset('js/main.js')}}"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
