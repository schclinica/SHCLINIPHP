 <!-- ======= Header ======= -->
 <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">

      <h1 class="logo me-auto"><a href="index.html">
        
        @if (isset($this->BusinesData()[0]->nombre_empresa))
         {{isset($this->BusinesData()[0]->nombre_empresa) ? $this->BusinesData()[0]->nombre_empresa:'Tu clínica online'}}  
       @else  
        Tu clínica online 
       @endif</a>  
      </a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero"><b>Inicio</b></a></li>
          <li><a class="nav-link scrollto" href="#about"><b>Especialidades</b></a></li>
          <li><a class="nav-link scrollto" href="#doctors"><b>Médicos</b></a></li>
          <li><a class="nav-link scrollto" href="#contact"><b>Contactos</b></a></li>
 
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

      <a href="{{$this->route("login")}}" class="appointment-btn scrollto"><span class="d-none d-md-inline"><b>Iniciar</span> Sesión </b><i class='bx bx-user'></i></a>

    </div>
  </header><!-- End Header -->