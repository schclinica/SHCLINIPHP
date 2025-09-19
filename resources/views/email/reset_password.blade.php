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
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Forgot Password -->
          <div class="card">
            <div class="card-body">
            
              <!-- /Logo -->
              <h4 class="mb-2 text-center">Recuperar contraseña  🔒</h4>
              
              @if ($this->ExistSession("error_reset"))
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($this->getSession("error_reset") as $error)
                          <li>{{$error}}</li>
                      @endforeach
                    </ul>
                  </div>
                  @php $this->destroySession("error_reset") @endphp
              @endif
 
              <form id="form_cambiar_password" class="mb-3" action="{{$this->route('guardar_cambio_password_reset/'.$this->get('id'))}}" method="POST">
                <input type="hidden" value="{{$this->get("token")}}" name="token">
                <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                <div class="mb-3">
                  <label for="email" class="form-label"><b>Password(*)</b></label>
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    placeholder="Escriba un nuevo password..."
                    value="{{$this->old("password")}}"
                    autofocus
                  />
                </div>

                <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                <div class="mb-3">
                  <label for="email" class="form-label"><b>Vuelva escribir su password(*)</b></label>
                  <input
                    type="password"
                    class="form-control"
                    id="password_repeat"
                    name="password_repeat"
                    placeholder="Vuelva escribir su password..."
            
                    autofocus
                  />
                </div>


                <button class="btn_blue_ w-100" id="reset">Guardar cambios <i class='bx bxs-save'></i></button>
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
    <script src="{{URL_BASE}}public/js/control.js"></script>
    <script>
      $(document).ready(function(){
        let Password = $('#password');
        let Password_Repeat = $('#password_repeat');

        /// validamoa al presionar enter
        enter('password','password_repeat');
        //enter('password_repeat','password_repeat');

        $('#reset').click(function(evento){
          evento.preventDefault();

          if(Password.val().trim().length == 0)
          {
            Password.focus();
          }
          else
          {
            if(Password_Repeat.val().trim().length == 0)
            {
              Password_Repeat.focus();
            }
            else
            {
              $('#form_cambiar_password').submit();
            }
          }
        });
      });
    </script>
  </body>
</html>
