 <!-- ======= Hero Section ======= -->
 <section id="hero" class="d-flex align-items-center">
    <div class="container">
      <h1 class="logo me-auto">Bienvenido a 
        @if (isset($this->BusinesData()[0]->nombre_empresa))
         {{isset($this->BusinesData()[0]->nombre_empresa) ? $this->BusinesData()[0]->nombre_empresa:'Tu clínica online'}}  
       @else  
        Tu clínica online 
       @endif
      </h1>
      <h2>¡Tu Bienestar es Nuestro Compromiso y Prioridad</h2>
      <a href="#about" class="btn-get-started scrollto">Empezar <i class='bx bx-child' ></i></a>
    </div>
  </section><!-- End Hero -->