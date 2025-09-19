<nav
class="layout-navbar container-xxl navbar  navbar-expand-xl navbar-detached align-items-center  bg-navbar-theme"
id="layout-navbar"  >

<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none "  >
  <a class="nav-item nav-link px-0 me-xl-4 " href="javascript:void(0)">
    <i class="bx bx-menu bx-sm"></i>
  </a>
</div>

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
  <!-- Search -->
  @if ($this->profile()->rol === 'admin_general' || $this->profile()->rol === 'Director' )
  <li class="menu-item mx-2">
    <a href="{{$this->route("apertura/caja")}}" class="menu-link">
      <img src="{{ $this->asset('img/icons/unicons/caja.ico') }}" class="menu-icon" alt="">
      <div data-i18n="Analytics" class=" text-dark"><b style="color: #010101"></b></div>
    </a>
  </li> 
  <li class="menu-item mx-0">
    <a href="{{$this->route("resumen/caja/validate-cierre-caja")}}" class="menu-link">
      <img src="{{ $this->asset('img/icons/unicons/resumen_caja.ico') }}" class="menu-icon" alt="">
      <div data-i18n="Analytics" class="text-dark"><b style="color: #010101"></b></div>
    </a>
  </li>  
  @else 
   <b>
    @if (isset($this->BusinesData()[0]->nombre_empresa))
    <span class="text-primary letra"> {{isset($this->BusinesData()[0]->nombre_empresa) ? $this->BusinesData()[0]->nombre_empresa:'Tu clínica online'}}  </span>
   @else  
    Tu clínica online 
   @endif</a>
   </b>
  @endif
  @if ($this->authenticado() and ($this->profile()->rol === 'admin_general' ||  $this->profile()->rol === 'Director') )
  <li class="menu-item">
    <a href="{{$this->route("categorias-egresos")}}" class="menu-link">
      <img src="{{ $this->asset('img/icons/unicons/egresos.ico') }}" class="menu-icon" alt="">
      <div data-i18n="Analytics" class="letra text-dark"><b style="color: #010101"> </b></div>
    </a>
  </li>
  @else 
    
  @endif
  @if ($this->profile()->rol === "admin_general" || $this->profile()->rol === "Director")
     <li class="menu-item">
        <a href="{{$this->route("gestionar/documentos")}}" class="menu-link">
          <img src="{{ $this->asset('img/icons/unicons/comprobantes.ico') }}" class="menu-icon" alt="Tipo Documentos">
          <div data-i18n="Analytics" class="letra text-dark"><b style="color: #010101"> </b></div>
        </a>
      </li>
  @endif
  <!-- /Search -->

  <ul class="navbar-nav flex-row align-items-center ms-auto">
    

    <!-- User -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
          <img src="{{getFoto($this->profile()->foto)}}" alt class="w-px-41 rounded-circle" 
          style="height: 50px;width: 50px"/>
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item" href="#">
            <div class="d-flex">
              <div class="flex-shrink-0 me-3">
                <div class="avatar avatar-online">
                  <img src="{{getFoto($this->profile()->foto)}}" alt class="w-px-43 rounded-circle" />
                </div>
              </div>
              <div class="flex-grow-1">
                <span class="fw-semibold d-block"><span class="text-primary">Hola, </span> {{$this->profile()->name}}</span>
                <small class="text-muted" id="roltext">{{$this->profile()->rol !== 'Farmacia'?($this->profile()->rol === 'admin_farmacia'?'Administrador':($this->profile()->rol === 'admin_general' ? 'Super Administrador':($this->profile()->rol=== 'Admisión' ? 'Secretaria|Cajero(a)':$this->profile()->rol))) : ($this->profile()->genero == 1?'Farmaceutico':'Farmaceutica')}}</small>
              </div>
            </div>
          </a>
        </li>
        <li>
          <div class="dropdown-divider"></div>
        </li>
        <li>
          <a class="dropdown-item" href="{{$this->route(str_replace(" ","_",$this->profile()->name)."/profile")}}">
            <i class="bx bx-user me-2"></i>
            <span class="align-middle">Mi perfil</span>
          </a>
        </li>
         <li>
          <div class="dropdown-divider"></div>
        </li>
         <li>
          <a class="dropdown-item" href="{{$this->route("reportes-citas-medicas")}}">
            <i class='bx bxs-pie-chart-alt-2'></i>
            <span class="align-middle">{{($this->profile()->rol === 'Farmacia' || $this->profile()->rol === 'admin-farmacia') ? 'Reporte de ventas' :'Reporte de citas' }}</span>
          </a>
        </li>
         <li>
          <div class="dropdown-divider"></div>
        </li>
         <li>
          <a class="dropdown-item" href="{{$this->route("dashboard")}}">
            <i class='bx bxs-home-smile'></i>
            <span class="align-middle">Inicio</span>
          </a>
        </li>
        @if ($this->authenticado() and ($this->profile()->rol === 'Director' or $this->profile()->rol === 'admin_general' or $this->profile()->rol=== 'Admisión' or
        $this->profile()->rol=== 'Enfremera-Triaje' ))
        <li>
          <div class="dropdown-divider"></div>
        </li>

        <li>
          <a href="{{$this->route('escritorio')}}" class="dropdown-item">
            <i class='bx bx-desktop'></i>
            <span class="align-middle">Escritorio</span>
          </a>
        </li>
        @endif
       
        <li>
          <div class="dropdown-divider"></div>
        </li>
        <li>
          <a class="dropdown-item" href="logout" id="logout_"  >
            <i class="bx bx-power-off me-2"></i>
            <span class="align-middle">Salir del sistema</span>
            <form action="{{$this->route('logout')}}" method="post" id="form_logout">
              <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
            </form>
          </a>
        </li>
      </ul>
    </li>
    <!--/ User -->
  </ul>
</div>
</nav>
