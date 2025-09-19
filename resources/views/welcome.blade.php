@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Dashboard')

@section('css')
    <style>
        .card:hover{
            cursor: pointer;
        }
         
    </style>
@endsection

@section('contenido')
 <div class="row justify-content-center">
       <div class="col-xl-6 col-12">
        <figure>
            <blockquote class="blockquote mb-4">
                <h3>Te damos la bienvenida nuevamente</h3>
            </blockquote>
            <figcaption class="blockquote-footer">
                <b class="text-primary letra h5">[ {{$this->profile()->name}} ] </b>
            </figcaption>
        </figure>
       </div>
       <div class="col-xl-6 col-12">
        <figure>
            <blockquote class="blockquote mb-4">
                <h3>Sucursal</h3>
            </blockquote>
            <figcaption class="blockquote-footer">
                <b class="text-success letra h5">[ {{($this->profile()->rol!="admin_general") ? $this->profile()->namesede." (:":"TODAS LAS SUCURSALES"}} ] </b>
            </figcaption>
        </figure>
       </div>
        <div class="col-12">
            @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general' || $this->profile()->rol === 'Admisión')
                <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 my-1">
                    <div class="card cardpac" style="background: -webkit-linear-gradient(135deg, #1565c0 0%, #1e88e5 100%); color: #06041aff;">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ $this->asset('img/icons/unicons/paciente.ico') }}" alt="chart success"
                                        class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                    </button>
    
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-center text-white">PACIENTES</span>
                            <h3 class="card-title mb-2 text-white text-center">{{$CantidadPacientes[0]->numpaciente}}
                            </h3>
                        </div>
                    </div>
                </div>

                 <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 my-1">
                    <div class="card cardmed" style="background: -webkit-linear-gradient(90deg, #0dc9bd,#02cf4a);background: linear-gradient(90deg, #0dc9bd,#02cf4a);">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ $this->asset('img/icons/unicons/medico.ico') }}" alt="chart success"
                                        class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                    </button>
    
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-center text-white">MÉDICOS</span>
                            <h3 class="card-title mb-2 text-white text-center">{{$CantidadMedicos[0]->nummedico}}
                            </h3>
                        </div>
                    </div>
                </div>

                 <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 my-1">
                    <div class="card cardusu" style="background: -webkit-linear-gradient(90deg, #21a1de,#043e94);background: linear-gradient(90deg, #21a1de,#043e94);">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ $this->asset('img/icons/unicons/users.ico') }}" alt="chart success"
                                        class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                    </button>
    
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-center text-white">USUARIOS</span>
                            <h3 class="card-title mb-2 text-white text-center">{{$CantidadUsuarios[0]->numuser}}
                            </h3>
                        </div>
                    </div>
                </div>
                
                 <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 my-1">
                    <div class="card cardhistorias" style="background: -webkit-linear-gradient(90deg, #c7f2ff,#5cd3ff);background: linear-gradient(90deg, #c7f2ff,#5cd3ff);">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ $this->asset('img/icons/unicons/historias.ico') }}" alt="chart success"
                                        class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                    </button>
    
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-center text-primary">HISTORIAL CLINICAS</span>
                            <h3 class="card-title mb-2 text-white text-center">{{$CantidadHistorialClinicoMedico[0]->cantidadhistorias}}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if ($this->profile()->rol === 'Médico')
                <div class="row">
                  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                    <div class="card" style="background: -webkit-linear-gradient(90deg, #0dc9bd,#02cf4a);background: linear-gradient(90deg, #0dc9bd,#02cf4a);">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                     <img src="{{ $this->asset('img/icons/unicons/paciente.ico') }}" alt="chart success"
                                        class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                    </button>
    
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-center text-white">PACIENTES ATENDIDOS</span>
                            <h3 class="card-title mb-2 text-white text-center">{{$Pacientes_Atendidos_Medico->pacientes_atendidos}}
                            </h3>
                        </div>
                    </div>
                 </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                    <div class="card" style="background: -webkit-linear-gradient(90deg, #F0F8FF);background: linear-gradient(90deg, #F0F8FF);">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ $this->asset('img/icons/unicons/paciente.ico') }}" alt="chart success"
                                        class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                    </button>
    
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-center text-white">PACIENTES ANULADOS</span>
                            <h3 class="card-title mb-2 text-white text-center">{{$Pacientes_Anulados_Medico->pacientes_atendidos}}
                            </h3>
                        </div>
                    </div>
                 </div>

                 <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                    <div class="card cardhistorias" style="background: -webkit-linear-gradient(90deg, #c7f2ff,#5cd3ff);background: linear-gradient(90deg, #c7f2ff,#5cd3ff);">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ $this->asset('img/icons/unicons/historias.ico') }}" alt="chart success"
                                        class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                    </button>
    
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-center text-primary">HISTORIAL CLINICAS</span>
                            <h3 class="card-title mb-2 text-white text-center">{{$CantidadHistorialClinicoMedico[0]->cantidadhistorias}}
                            </h3>
                        </div>
                    </div>
                </div>
                </div>
            @endif

            @if ($this->profile()->rol === self::$profile[4])
               <div class="row">
                  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                    <div class="card cardhistorias" style="background: -webkit-linear-gradient(90deg, #c7f2ff,#5cd3ff);background: linear-gradient(90deg, #c7f2ff,#5cd3ff);">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                     <img src="{{ $this->asset('img/icons/unicons/historias.ico') }}" alt="chart success"
                                        class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                    </button>
    
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-center text-white">HISTORIAS CLINICAS</span>
                            <h3 class="card-title mb-2 text-white text-center">{{$CantidadHistorialClinicoMedico[0]->cantidadhistorias}}
                            </h3>
                        </div>
                    </div>
                  </div>

                  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                    <div class="card pacientestriaje" style="background:radial-gradient(at 50.05115834223528% 76.6898174446064%, hsla(168.9795918367347, 80.32786885245902%, 47.84313725490196%, 1) 0%, hsla(168.9795918367347, 80.32786885245902%, 47.84313725490196%, 0) 100%), radial-gradient(at 7.733902857886676% 95.09634762100372%, hsla(126.48648648648647, 49.333333333333336%, 44.11764705882353%, 1) 0%, hsla(126.48648648648647, 49.333333333333336%, 44.11764705882353%, 0) 100%), radial-gradient(at 20.232274860912348% 60.69226477277084%, hsla(249.37499999999994, 84.21052631578952%, 92.54901960784314%, 1) 0%, hsla(249.37499999999994, 84.21052631578952%, 92.54901960784314%, 0) 100%), radial-gradient(at 2.9038089407485645% 18.218484668317814%, hsla(187.25274725274727, 68.42105263157895%, 26.078431372549023%, 1) 0%, hsla(187.25274725274727, 68.42105263157895%, 26.078431372549023%, 0) 100%), radial-gradient(at 44.86594473761294% 40.78405545168927%, hsla(168.9795918367347, 80.32786885245902%, 47.84313725490196%, 1) 0%, hsla(168.9795918367347, 80.32786885245902%, 47.84313725490196%, 0) 100%), radial-gradient(at 99.34475659762529% 28.504409278414713%, hsla(126.48648648648647, 49.333333333333336%, 44.11764705882353%, 1) 0%, hsla(126.48648648648647, 49.333333333333336%, 44.11764705882353%, 0) 100%), radial-gradient(at 78.97600384622393% 7.658854163502415%, hsla(249.37499999999994, 84.21052631578952%, 92.54901960784314%, 1) 0%, hsla(249.37499999999994, 84.21052631578952%, 92.54901960784314%, 0) 100%), radial-gradient(at 15.359315363867253% 79.26055963684442%, hsla(187.25274725274727, 68.42105263157895%, 26.078431372549023%, 1) 0%, hsla(187.25274725274727, 68.42105263157895%, 26.078431372549023%, 0) 100%);">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                     <img src="{{ $this->asset('img/icons/unicons/triaje.ico') }}" alt="chart success"
                                        class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                    </button>
    
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-center text-white">PACIENTES EN TRIAJE</span>
                            <h3 class="card-title mb-2 text-white text-center">{{$PacientesEnTriajeTotal[0]->totalentriaje}}
                            </h3>
                        </div>
                    </div>
                  </div>

                  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                    <div class="card pacientesexaminados" style="background:radial-gradient(at 27.214886992669896% 56.76180364582948%, hsla(55.05882352941176, 100%, 50%, 1) 0%, hsla(55.05882352941176, 100%, 50%, 0) 100%), radial-gradient(at 40.34048971511797% 22.240402285152427%, hsla(40.94117647058823, 100%, 50%, 1) 0%, hsla(40.94117647058823, 100%, 50%, 0) 100%), radial-gradient(at 84.25280859009293% 13.300030857663469%, hsla(0, 0%, 100%, 1) 0%, hsla(0, 0%, 100%, 0) 100%), radial-gradient(at 4.394499838317212% 65.71186919817471%, hsla(55.05882352941176, 100%, 50%, 1) 0%, hsla(55.05882352941176, 100%, 50%, 0) 100%), radial-gradient(at 87.99811121677695% 90.27090434037912%, hsla(40.94117647058823, 100%, 50%, 1) 0%, hsla(40.94117647058823, 100%, 50%, 0) 100%), radial-gradient(at 57.530943659763764% 58.685604158646896%, hsla(0, 0%, 100%, 1) 0%, hsla(0, 0%, 100%, 0) 100%), radial-gradient(at 79.39523474163866% 69.90394415563318%, hsla(55.05882352941176, 100%, 50%, 1) 0%, hsla(55.05882352941176, 100%, 50%, 0) 100%), radial-gradient(at 89.70762284352125% 33.16383756056358%, hsla(40.94117647058823, 100%, 50%, 1) 0%, hsla(40.94117647058823, 100%, 50%, 0) 100%);">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                     <img src="{{ $this->asset('img/icons/unicons/triaje.ico') }}" alt="chart success"
                                        class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                    </button>
    
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-center text-primary">PACIENTES EXAMINADOS</span>
                            <h3 class="card-title mb-2 text-dark text-center">{{$PacientesExaminados[0]->totalexaminados}}
                            </h3>
                        </div>
                    </div>
                  </div>
               </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
 <script>
    var PROFILE = "{{$this->profile()->rol}}";
    var RUTA = "{{URL_BASE}}";
  $(document).ready(function(){
    $('.cardpac').click(function(){
       (PROFILE === 'Director' || PROFILE === 'Admisión' || PROFILE === 'admin_general') ?  location.href = RUTA+"paciente" : '';
    });
    $('.cardmed').click(function(){
       (PROFILE === 'Director' || PROFILE === 'admin_general') ? location.href = RUTA+"medicos" : '';
    });
    $('.cardusu').click(function(){
        (PROFILE === 'Director' || PROFILE === 'admin_general') ? location.href = RUTA+"user_gestion" : '';
    });

    $('.cardhistorias').click(function(){
         (PROFILE === 'Admisión' || PROFILE === 'Enfermera-Triaje' )? location.href = RUTA+"ver-historial-clinico" : '';
    })

    $('.pacientestriaje').click(function(){
         (PROFILE === 'Enfermera-Triaje' )? location.href = RUTA+"triaje/pacientes" : '';
    });

    $('.pacientesexaminados').click(function(){
      (PROFILE === 'Enfermera-Triaje' )? location.href = RUTA+"triaje/pacientes" : '';
    });
  })   
 </script>   
@endsection
