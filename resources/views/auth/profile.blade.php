@extends($this->Layouts("dashboard"))

@section("title_dashboard","Mi perfíl")
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
    <div class="col-md-12">
      <div class="card mb-4">
        <h5 class="card-header">Mi perfíl</h5>
        <!-- Account -->
        <div class="card-body">
          <div class="row justify-content-center mt-3">
            <img src="{{getFoto($this->profile()->foto)}}" alt="" class="img-fluid img-thumbnail"
                style="border-radius: 50%;border: solid 2px blue" id="imagen_">
        </div>
          <div class="row justify-content-center">
             <div class="col-xl-4 col-lg-4 col-md-5 col-12 mt-2">
              <a  class="btn btn-outline-warning account-image-reset mb-4 form-control" href="{{$this->route('profile/editar')}}">
                <i class="bx bx-reset d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Editar mi perfíl <i class='bx bx-edit-alt'></i></span>
              </a>
             </div>
          </div>
        </div>
        <hr class="my-0" />
        <div class="card-body">
          <form id="formAccountSettings" method="POST" onsubmit="return false">
            <div class="row">
              <div class="mb-3 col-md-6">
                <label for="firstName" class="form-label"><b>Persona</b> <i class='bx bx-user-circle'></i></label>
                <label for="" class="form-control">
                  @if (isset($this->profile()->id_persona))
                  {{$this->profile()->apellidos}} , {{$this->profile()->nombres}}
                  @else 
                  <b class="text-danger">Complete sus datos...</b>
                  @endif
                </label>
              </div>
              <div class="mb-3 col-md-6">
                <label for="lastName" class="form-label"><b>Nombre de usuario</b> <i class='bx bx-user-circle'></i></label>
                <label for="" class="form-control">{{$this->profile()->name}}</label>
              </div>
              <div class="mb-3 col-md-6">
                <label for="email" class="form-label"><b>Email</b> <i class='bx bx-mail-send' ></i></label>
                <label for="" class="form-control">{{$this->profile()->email}}</label>
              </div>
              <div class="mb-3 col-md-6">
                <label for="organization" class="form-label"><b>ESTADO</b></label>
                <label for="" class="form-control"><span class="badge bg-success"><b>Activo</b></span></label>
              </div>
              <div class="mb-3 col-md-6">
                <label class="form-label" for="phoneNumber"><b>Dirección</b> <i class='bx bxs-city'></i></label>
                <div class="input-group input-group-merge">
                  <label for="" class="form-control">
                    @if (isset($this->profile()->id_persona))
                    @if ($this->profile()->direccion == null)
                    <span class="badge bg-danger">No especifica dirección....</span>
                    @else 
                    {{$this->profile()->direccion}}
                     @endif
                     @else
                     <b class="text-danger">Complete sus datos...</b>
                    @endif
                  </label>
                </div>
              </div>             
              
              <div class="mb-3 col-md-6">
                <label for="currency" class="form-label"><b># Documento</b> <i class='bx bxs-credit-card-front'></i></label>
                <label for="" class="form-control">
                  @if (isset($this->profile()->id_persona))
                  {{$this->profile()->documento}}
                  @else
                  <b class="text-danger">Complete sus datos...</b>
                  @endif
                </label>
              </div>
            </div>
            <div class="col-12">
              <label for="email" class="form-label"><b>Fecha nacimiento </b> <i class='bx bxs-calendar'></i></label>
               @if (isset($this->profile()->id_persona))
               @if ($this->profile()->fecha_nacimiento != null)
               @php
                 $fecha = $this->profile()->fecha_nacimiento;
              
                 $fecha = explode("-",$fecha);
              
                @endphp
                <label for="" class="form-control">{{$fecha[2]."/".$fecha[1]."/".$fecha[0]}}</label>
                @else 
                <label for="" class="form-control"><span class="badge bg-danger">sin fecha nacimiento....</span></label>
              @endif
              @else
              <b class="text-danger">Complete sus datos...</b>
               @endif
               
            </div>
            
          </form>
          @if (isset($this->profile()->rol))
          @if ($this->profile()->rol === 'Médico')
         
             <div class="col-12 mt-3">
              <span><b>Especialidad</b></span>
             </div>
             <div class="col-12 mt-2">
              @if (count($Cargo) > 0)
                  @foreach ($Cargo as $cargo)
                     <span class="badge bg-primary"><b> {{$cargo->nombre_esp}}</b></span>
                  @endforeach
              @endif
             </div>
          @endif
         @endif
        </div>

        @if (!isset($this->profile()->id_persona))
        <div class="card-footer">
          <a href="{{$this->route("paciente/completar_datos")}}" class="btn_blue"><b>Completar mis datos <i class='bx bxs-user-account'></i></b></a>
        </div>
        @endif
       
        <!-- /Account -->
      </div>
      <div class="card">
        <h5 class="card-header">Eliminar mi cuenta</h5>
        <div class="card-body">
          <div class="mb-3 col-12 mb-0">
            <div class="alert alert-warning">
              <h6 class="alert-heading fw-bold mb-1">¿Está seguro de que desea eliminar su cuenta??</h6>
              <p class="mb-0">Una vez que eliminas tu cuenta, no hay vuelta atrás. Por favor, esté seguro.</p>
            </div>
          </div>
          <form id="formAccountDeactivation" onsubmit="return false">
            <div class="form-check mb-3">
              <input
                class="form-check-input"
                type="checkbox"
                name="accountActivation"
                id="accountActivation"
              />
              <label class="form-check-label" for="accountActivation"
                >Confirmo la desactivación de mi cuenta</label
              >
            </div>
            <button type="submit" class="btn btn-danger deactivate-account" disabled><b><i class='bx bxs-user-x' ></i> Eliminar mi cuenta </b></button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
