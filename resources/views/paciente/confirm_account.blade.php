<!DOCTYPE html>
 
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
    <link rel="icon" type="image/x-icon" href="{{$this->asset("img/avatars/anonimo_4.jpg")}}" />
    <!-- Helpers -->
    <script src="{{$this->asset('vendor/js/helpers.js')}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{$this->asset('js/config.js')}}"></script>
    <link rel="stylesheet" href="{{ $this->asset('css/jquery.loadingModal.css') }}">
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

  <body  style="background-color: #E6E6FA">
    <!-- Content -->

    <div class="container-fluid" id="bod_all">
      
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-xl-4 col-lg-5 col-md-5 col-md-7">
                <div class="card">
                    <div class="card-header" style="background:linear-gradient(to bottom, rgba(179,220,237,1) 100%,rgba(41,184,229,1) 50%,rgba(188,224,238,1) 100%);">
                        <div class="card-text text-center">
                            <p class="h3 text-white text-center letra my-2"><b>Confirmar la cuenta</b></p>
                            @if (!file_exists("public/asset/empresa/".$this->BusinesData()[0]->logo))
                         <img src="{{$this->asset("img/lgo_clinica_default.jpg")}}" id="imagen_logo" style="width:180px;height:105px" >
                         @else 
                         <img src="{{$this->asset(isset($this->BusinesData()[0]->logo) ?"empresa/".$this->BusinesData()[0]->logo:"img/lgo_clinica_default.jpg")}}" id="imagen_logo" style="width:180px;height:105px" >
                         @endif
                        </div>
                    </div>
                   <form action="{{$this->route("paciente/verify_account_paciente")}}" method="post">
                    <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                    <div class="card-body">
                     <div class="text-center"> <a href="{{$this->route("login")}}"><b>Iniciar sesión <i class='bx bx-right-arrow-alt'></i></b></a></div>
                       @if ($this->ExistSession('success_'))
                       <div class="alert alert-success">
                          {{$this->getSession('success_')}}
                       </div>
                       @endif
                      <div class="input-group">
                       <input type="text" class="form-control" id="usercodigo" name="usercodigo" placeholder="CÓDIGO..."
                       autofocus style="font-size: 20px">
                       <button class="btn btn-outline-info" id="reenviar"><i class='bx bx-refresh'></i> reenviar</button>
                      </div>

                      <div class="mt-2">
                        @if ( $this->ExistSession("response"))
                            @if ($this->getSession("response") === 'error')
                                <span class="text-danger" id="error_code_no_existe">
                                   Error, el código que escribiste no existe!.😒
                                </span>
                            @endif
                            @if ($this->getSession("response") === 'vacio')
                             <span class="text-danger"> Complete el campo código! 😢</span>
                            @endif
                        @php $this->destroySession("response") @endphp
                        @endif
                        <span class="text-success mx-2" style="display: none" id="message_send_new">
                          <b >Nuevo código reenviado a su correo!.😁😎</b>
                       </span>

                       <span class="text-danger mx-2" style="display: none" id="message_send_error">
                        Erro, el código debe ser de 6 caracteres!
                       </span>
                      </div>
                      
                   </div>
 
                      <div class="mb-4 text-center card-text">
                        <button class="btn_blue  col-6" type="submit" id="active_cuenta"><b>Activar mi cuenta<i class='bx bxs-user-account'></i></b> </button>
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
    <script src="{{ $this->asset('js/ui-popover.js') }}"></script>
    <script src="{{ $this->asset('js/ui-toasts.js') }}"></script>
    <script src="{{ $this->asset('js/jquery.loadingModal.js') }}"></script>
    <script src="{{URL_BASE}}public/js/control.js"></script>
    <script>
    var RUTA = "{{URL_BASE}}";
     $(document).ready(function(){

      $('#usercodigo').keypress(function(ev){
        if(ev.which == 13){
          ev.preventDefault();
          if($(this).val().trim().length == 0){
            $('#message_send_error').show(200);
            $('#message_send_new').hide()
            $('#error_code_no_existe').hide();
          } else{
            $('#message_send_error').hide(200);
          }
        }
      });
      $('#usercodigo').keyup(function(ev){
         if($(this).val().trim().length < 6 || $(this).val().trim().length > 6){
          $('#message_send_error').show(200);
          $('#message_send_new').hide();
          $('#error_code_no_existe').hide();
         }else{
          $('#message_send_error').hide(200);
         }
      });
      $('#reenviar').click(function(evento){
        evento.preventDefault();
        loading('#bod_all','#4169E1','chasingDots') ;

           $.ajax(
              {
              url: RUTA+"paciente/send/codigo/veryfy_account?id={{$_GET['id']}}",
              method:"POST",
              dataType:"json",
              success:function(res){
              if(res.response === 'codigo-reenviado'){
              setTimeout(() => {
              $('#bod_all').loadingModal('hide');
              $('#bod_all').loadingModal('destroy');
              $('#message_send_new').show(200);
              $('#message_send_error').hide();
              },1000);
              }else{
              $('#message_send_new').hide();
              }
              }
              });
          
      });
     });
    </script>
  </body>
</html>