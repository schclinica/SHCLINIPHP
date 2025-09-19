@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Page-No-Autorizado')

@section('contenido')
    <!-- Content -->

    <!-- Error -->
    <div class="container-xxl container-p-y text-center">
      <div class="misc-wrapper">
        <h2 class="mb-2 mx-2">404 -Page Not Found :(</h2>
        <p class="mb-4 mx-2">¡Ups! 😖 La URL solicitada no se encontró en este servidor.</p>
        <a href="{{$this->route("dashboard")}}" class="btn btn-primary">Ir a principal <i class='bx bx-arrow-back'></i></a>
        <div class="mt-3">
          <img
            src="{{$this->asset('img/illustrations/page-misc-error-light.png')}}"
            alt="page-misc-error-light"
            width="500"
            class="img-fluid"
            data-app-dark-img="illustrations/page-misc-error-dark.png"
            data-app-light-img="illustrations/page-misc-error-light.png"
          />
        </div>
      </div>
    </div>
  

   @endsection
