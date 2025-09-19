<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>@yield('title_home')</title>
  <meta content="{{isset($this->BusinesData()[0]->nombre_empresa) ? $this->BusinesData()[0]->nombre_empresa:'clinica-online'}}" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{isset($this->BusinesData()[0]->logo) ? $this->asset("empresa/".$this->BusinesData()[0]->icono):''}}" rel="icon">
  <link href="public/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Vendor CSS Files -->
  <link href="{{URL_BASE}}public/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{URL_BASE}}public/assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ $this->asset('css/estilos.css') }}">

   {{-- - CSS SWEET ALERT 2- --}}


   <link rel="stylesheet" href="{{ $this->NodeModule('sweetalert2/dist/sweetalert2.css') }}">

   <link rel="stylesheet" href="{{ $this->NodeModule('sweetalert2/dist/sweetalert2.min.css') }}">
   <link rel="stylesheet" href="{{ $this->asset('css/jquery.loadingModal.css') }}">

  <!-- =======================================================
  * Template Name: Medilab
  * Updated: Mar 10 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  @php
      $ImagenBanner = $this->BusinesData()[0]->imagen_banner != null ? 
                       $this->asset("empresa/".$this->BusinesData()[0]->imagen_banner):
                       'public/assets/img/gallery/gallery-1.jpg';

      $PortadaVideo = $this->BusinesData()[0]->foto_portada_video != null ? 
                       $this->asset("empresa/".$this->BusinesData()[0]->foto_portada_video):
                       'public/assets/img/departments-2.jpg';
  @endphp
  <style>
    .whatsapp {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            z-index: 100;
        }

        .whatsapp-icon {
            margin-top: 13px;
        }
        #imagen_logo{
        filter: brightness(1.1);
        mix-blend-mode: multiply;
         
      }
      #hero{
        width: 100%;
        height: 100vh;
        background: url("{{$ImagenBanner}}") ; 
        background-size: cover;
      }
     .about .video-box {
     background: url("{{$PortadaVideo}}") center center no-repeat;
     background-size: cover;
     min-height: 500px;
     }
  </style>
</head>

<body>
 <!-- ======= Top Bar ======= -->
 <div id="topbar" class="d-flex align-items-center fixed-top">
  <div class="container d-flex justify-content-between">
    <div class="contact-info d-flex align-items-center">
      <i class="bi bi-envelope"></i> <a href="mailto:contact@example.com">
        {{isset($this->BusinesData()[0]->contacto) ? $this->BusinesData()[0]->contacto:'soporteclinicademo@tecnologysoft.com'}}  
      </a>
      <i class="bi bi-phone"></i>  {{isset($this->BusinesData()[0]->wasap) ? $this->BusinesData()[0]->wasap:'+51 XXX XXX XXX'}}  
    </div>
    <div class="d-none d-lg-flex social-links align-items-center">
      
      @if (isset($redesSocialesClinica) && count($redesSocialesClinica) > 0)
          @foreach ($redesSocialesClinica as $red)
          <a href="{{$red->link_red_social}}" class="twitter" target="_blank"><i class="{{$red->icono}}"></i></a>
          @endforeach
      @endif
    </div>
  </div>
</div>
  
 @yield('contenido')   
  
  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- Vendor JS Files -->
  <script src="{{URL_BASE}}public/assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="{{URL_BASE}}public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{URL_BASE}}public/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="{{URL_BASE}}public/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="{{URL_BASE}}public/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
   <script src="{{URL_BASE}}public/assets/js/main.js"></script>

  
    {{-- - JS PARA SWEET ALERT 2--- --}}
    <script src="{{ $this->NodeModule('sweetalert2/dist/sweetalert2.all.js') }}"></script>
    <script src="{{ $this->NodeModule('sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ $this->NodeModule('sweetalert2/dist/sweetalert2.js') }}"></script>
    <script src="{{ $this->NodeModule('sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="{{ $this->asset('js/jquery.loadingModal.js') }}"></script>
   @yield('js')

</body>

</html>