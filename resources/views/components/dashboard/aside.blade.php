 
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme @yield('clase_ocultar')">
  <div class="app-brand demo">
    {{-- <a href="index.html" class="app-brand-link">
      <span class="app-brand-logo demo">
        <span class="text-primary">
          <svg
            width="25"
            viewBox="0 0 22 42"
            version="1.1"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>
              <path
                d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                id="path-1"></path>
              <path
                d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                id="path-3"></path>
              <path
                d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                id="path-4"></path>
              <path
                d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                id="path-5"></path>
            </defs>
            <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
              <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                <g id="Icon" transform="translate(27.000000, 15.000000)">
                  <g id="Mask" transform="translate(0.000000, 8.000000)">
                    <mask id="mask-2" fill="white">
                      <use xlink:href="#path-1"></use>
                    </mask>
                    <use fill="currentColor" xlink:href="#path-1"></use>
                    <g id="Path-3" mask="url(#mask-2)">
                      <use fill="currentColor" xlink:href="#path-3"></use>
                      <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                    </g>
                    <g id="Path-4" mask="url(#mask-2)">
                      <use fill="currentColor" xlink:href="#path-4"></use>
                      <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                    </g>
                  </g>
                  <g
                    id="Triangle"
                    transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                    <use fill="currentColor" xlink:href="#path-5"></use>
                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                  </g>
                </g>
              </g>
            </g>
          </svg>
        </span>
      </span>
      <span class="app-brand-text text-primary menu-text fw-bold ms-2 h4 letra mt-4">{{strtoupper("Buena Salud")}}</span>
    </a> --}}
    @if (!file_exists("public/asset/empresa/".$this->BusinesData()[0]->logo))
    <img src="{{$this->asset("img/lgo_clinica_default.jpg")}}" id="imagen_logo" style="width:180px;height:105px" >
    @else 
    <img src="{{$this->asset(isset($this->BusinesData()[0]->logo) ?"empresa/".$this->BusinesData()[0]->logo:"img/lgo_clinica_default.jpg")}}" id="imagen_logo" style="width:240px;height:91px" >
    @endif
   
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>
    
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
    {{--- Dashboard del sistema(inicial del sistema)---}}
      <li class="menu-item active">
        <a href="{{$this->route('dashboard')}}" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Analytics">Dashboard 
          </div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{$this->route('dashboard')}}" class="menu-link">
              <div data-i18n="Without navbar" class="letra" >Inicio</div>
            </a>
          </li>
        
        </ul>
      </li>
      
      <!-- Configuración del sistema -->
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <img src="{{$this->asset('img/icons/unicons/config.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Layouts" class="letra" style="color: #0f0606"><b>Configuración </b></div>
        </a>
      
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{$this->route('profile/editar')}}" class="menu-link">
              <div data-i18n="Without navbar" class="letra" >Actualizar perfíl</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{$this->route('config/sistema')}}" class="menu-link">
              <div data-i18n="Without navbar" class="letra" >Sistema</div>
            </a>
          </li>
          @if ($this->authenticado())
              @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_farmacia' || $this->profile()->rol === 'admin_general')
              <li class="menu-item">
                <a href="{{$this->route($this->profile()->rol === 'admin_farmacia' ? 'Configurar-datos-farmacia':'Configurar-datos-clinica')}}" class="menu-link">
                  
                  <div data-i18n="Container" class="letra">
                    @if ($this->profile()->rol === 'admin_farmacia')
                        Datos de la farmacia
                        @else 
                        Datos de la clínica
                    @endif
                  </div>
                </a>
              </li>
              @endif
          @endif
        </ul>
      </li>

      {{-- VER MI INFORME MÉDICO ---}}

      @if ($this->authenticado() and $this->profile()->rol === 'Paciente')
      <li class="menu-item">
        <a href="{{$this->route("paciente/consultar_informe_medico")}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/informe_medico.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra text-dark"><b style="color: #000000">Informe médico</b></div>
        </a>
      </li>
      @endif
     
      <!-- Tipo de documentos -->
      @if ($this->authenticado() and ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general' || $this->profile()->rol === 'admin_farmacia'))
       <li class="menu-item">
        <a href="{{$this->route("reportes")}}" class="menu-link menu-toggle">
          <img src="{{$this->asset('img/icons/unicons/previlegiosuser.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra text-dark"><b>Gestiónar usuarios</b></div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{$this->route("user_gestion")}}" class="menu-link">

              {{-- SOLO ADMINISTARDORES--}}
              <div data-i18n="Without menu" class="letra" style="color: ##696969">Usuarios</div>
            </a>
          </li>
        @if ($this->profile()->rol === 'Director' or $this->profile()->rol === 'admin_general')
          <li class="menu-item">
            <a href="{{$this->route("gestionar-roles")}}" class="menu-link">

              {{-- SOLO ADMINISTARDORES--}}
              <div data-i18n="Without menu" class="letra" style="color: ##696969">Roles</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{$this->route("gestionar-permisos")}}" class="menu-link">

              {{-- SOLO ADMINISTARDORES--}}
              <div data-i18n="Without menu" class="letra" style="color: ##696969">Previlegios</div>
            </a>
          </li>
          @endif
        </ul>
      </li>
      @endif

      @if ($this->authenticado() and ($this->profile()->rol === 'Director'  || $this->profile()->rol === 'admin_general' || $this->profile()->rol === 'admin_farmacia'))
      <li class="menu-item">
        <a href="{{$this->route("tipo-documentos-existentes")}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/documento.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra text-dark"><b>Cédula de identidad</b></div>
        </a>
      </li>
      @endif

    
      @if ($this->authenticado() and ($this->profile()->rol === 'Director' || $this->profile()->rol === "Admisión" || $this->profile()->rol === 'admin_general' || $this->profile()->rol === 'admin_farmacia'  || $this->profile()->rol === 'Farmacia' || $this->profile()->rol === "Médico") )
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <img src="{{$this->asset('img/icons/unicons/gestion.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Layouts" class="letra text-dark" style="color: #0f0606"><b>Gestionar</b></div>
        </a>
      
        <ul class="menu-sub">
          @if ($this->authenticado() and ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general' || $this->profile()->rol === 'admin_farmacia'))
          <li class="menu-item">
            <a href="{{$this->route("departamentos")}}" class="menu-link">
              <div data-i18n="Analytics" class="letra text-dark"><b style="color: #010101">Ciudades</b></div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{$this->route("especialidades")}}" class="menu-link">
              <div data-i18n="Analytics" class="letra text-dark"><b style="color: #010101">Especialidades</b></div>
            </a>
          </li>
            @if ($this->profile()->rol === "admin_general")
            <li class="menu-item">
              <a href="{{$this->route("sucursales")}}" class="menu-link">
                <div data-i18n="Analytics" class="letra text-dark"><b style="color: #010101">Sucursales</b></div>
              </a>
            </li>
            @endif
          <li class="menu-item">
            <a href="{{$this->route("redes-sociales")}}" class="menu-link">
              <div data-i18n="Analytics" class="letra text-dark"><b style="color: #010101">Redes sociales</b></div>
            </a>
          </li>
           @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general')
           <li class="menu-item">
            <a href="{{$this->route("gestionar-enfermedades")}}" class="menu-link">

              {{-- SOLO ADMINISTARDORES--}}
              <div data-i18n="Without menu" class="letra text-dark"><b style="color: #010101">Enfermedades</b></div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{$this->route("gestionar-ordenes")}}" class="menu-link">
              <div data-i18n="Without navbar" class="letra text-dark"><b style="color: #010101">Gestionar órdenes</b></div>
            </a>
          </li>
           @endif
            
          @endif
          @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_farmacia' || $this->profile()->rol === 'Farmacia' || $this->profile()->rol === "admin_general")
          <li class="menu-item">
            <a href="{{($this->profile()->rol === 'Farmacia' || $this->profile()->rol === 'admin_farmacia') ? $this->route("apertura/caja-farmacia"):$this->route("apertura/caja")}}" class="menu-link">
             
              <div data-i18n="Analytics" class="letra text-dark"><b style="color: #010101">Caja {{$this->profile()->rol === 'admin_general' ? ' Resumen Clinica':''}}</b></div>
            </a>
          </li>
          @endif
          @if ($this->profile()->rol === 'Farmacia' || $this->profile()->rol === 'admin_farmacia' || $this->profile()->rol === "admin_general")
          <li class="menu-item">
            <a href="{{$this->route("app/farmacia/resumen-caja")}}" class="menu-link">
              <div data-i18n="Analytics" class="letra text-dark"><b style="color: #010101">Resumen de caja @php echo $this->profile()->rol ==="admin_general" ? 'Farmacia':'' @endphp</b></div>
            </a>
          </li>
          @endif
           @if ($this->profile()->rol === 'Director' || $this->profile()->rol === "Admisión" || $this->profile()->rol === 'admin_farmacia'  || $this->profile()->rol === 'Farmacia' ||  $this->profile()->rol === "admin_general")
          <li class="menu-item">
            <a href="{{$this->route("categorias-egresos")}}" class="menu-link">
             
              <div data-i18n="Analytics" class="letra text-dark"><b style="color: #010101">Categoría Gastos</b></div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{$this->route("gestionar-gastos")}}" class="menu-link">
             
              <div data-i18n="Analytics" class="letra text-dark"><b style="color: #010101">Gastos</b></div>
            </a>
          </li>
          @endif
          
        </ul>
      </li>
    
      @endif

      
      @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general' || $this->profile()->rol === 'admin_farmacia' || $this->profile()->rol === 'Farmacia')
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <img src="{{$this->asset('img/icons/unicons/aplicaciones.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Layouts" class="letra text-dark" style="color: #0f0606"><b>Aplicaciones </b></div>
        </a>
      
        <ul class="menu-sub">
          <!-- modificado ---->
          @if ($this->profile()->rol === 'Farmacia' || $this->profile()->rol === 'admin_general' || $this->profile()->rol === 'admin_farmacia')
          <li class="menu-item">
            <a href="{{$this->route("app/farmacia")}}" class="menu-link">
              <div data-i18n="Analytics" class="letra"> Farmacia </div>
            </a>
          </li>
          @endif 
        </ul>
      </li>
      @endif    

      @if ($this->authenticado() and ($this->profile()->rol === 'Director' or $this->profile()->rol === 'admin_general' or $this->profile()->rol === 'Admisión' || $this->profile()->rol === 'Médico'))
      <li class="menu-item">
        <a href="{{$this->route("paciente")}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/paciente.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra text-dark"><b>Pacientes</b></div>
        </a>
      </li>
      @endif

      @if ($this->authenticado() and ($this->profile()->rol === 'admin_general' or $this->profile()->rol === 'Director' || $this->profile()->rol === 'Médico'))
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <img src="{{$this->asset('img/icons/unicons/medico.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Layouts" class="letra text-dark"><b>Médico</b></div>
        </a>
      
        <ul class="menu-sub">
          @if ($this->authenticado() and ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general'))
          <li class="menu-item">
            <a href="{{$this->route("medicos")}}" class="menu-link">

              {{-- SOLO ADMINISTARDORES--}}
              <div data-i18n="Without menu" class="letra" style="color: ##696969">Gestionar médico</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{$this->route("medico/servicios")}}" class="menu-link">

              {{-- SOLO ADMINISTARDORES--}}
              <div data-i18n="Without menu" class="letra" style="color: ##696969">Servicios</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{$this->route("medico/citas-realizados")}}" class="menu-link">

              {{-- SOLO ADMINISTARDORES--}}
              <div data-i18n="Without menu" class="letra" style="color: ##696969">citas realizados</div>
            </a>
          </li>
          @endif
          {{---SOLO PARA LOS MÉDICOS --}}
          @if ($this->profile()->rol === 'Médico')
          <li class="menu-item">
            <a href="{{str_replace(" ","_",$this->route($this->profile()->name).'/horarios')}}" class="menu-link">
              <div data-i18n="Without navbar" class="letra">mis horarios</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{$this->route("gestionar-enfermedades")}}" class="menu-link">
              <div data-i18n="Without navbar" class="letra">Enfermedades</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{$this->route("medico/import-dias-de-atencion")}}" class="menu-link">
              <div data-i18n="Without navbar" class="letra">Dias de atención</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{$this->route("gestionar-ordenes")}}" class="menu-link">
              <div data-i18n="Without navbar" class="letra">Gestionar órdenes</div>
            </a>
          </li>
          @endif
          @if ($this->authenticado() and $this->profile()->rol === 'Médico')
          <li class="menu-item">
            <a href="{{$this->route("medico/mis_servicios")}}" class="menu-link">

              {{-- SOLO ADMINISTARDORES--}}
              <div data-i18n="Without menu" class="letra" style="color: ##696969">Mis servicios</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{$this->route("medico/especialidades")}}" class="menu-link">

              {{-- SOLO ADMINISTARDORES--}}
              <div data-i18n="Without menu" class="letra" style="color: ##696969">Especialidades</div>
            </a>
          </li>
          @endif
       
        </ul>
      </li>
      @endif
      @if ($this->profile()->rol === 'Paciente' and isset($this->profile()->id_persona))
      <li class="menu-item">
       <a href="{{$this->route('seleccionar-especialidad')}}" class="menu-link">
        <img src="{{$this->asset('img/icons/unicons/ctma.ico')}}" class="menu-icon" alt="">
         <div data-i18n="Without menu" class="letra text-dark"><b  style="color: #0b0606">Sacar cita</b></div>
       </a>
     </li>
      @endif
      @if ($this->profile()->rol === 'Paciente' and isset($this->profile()->id_persona))
      <li class="menu-item">
       <a href="{{$this->route('citas-realizados')}}" class="menu-link">
        <img src="{{$this->asset('img/icons/unicons/citas_save.ico')}}" class="menu-icon" alt="">
         <div data-i18n="Without menu" class="letra"><b style="color: #0b0606">Citas realizados</b></div>
       </a>
     </li>
     <li class="menu-item">
      <a href="{{$this->route('mis-testimonios-publicados')}}" class="menu-link">
       <img src="{{$this->asset('img/icons/unicons/comentarios.ico')}}" class="menu-icon" alt="">
        <div data-i18n="Without menu" class="letra text-dark"><b>Mis testimonios</b></div>
      </a>
    </li>
      @endif
      @if ($this->authenticado())
          @if ($this->profile()->rol === 'Admisión' || $this->profile()->rol === 'Director' || $this->profile()->rol === 'Médico' || $this->profile()->rol === 'admin_general' )
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <img src="{{$this->asset('img/icons/unicons/gestionmedica.ico')}}" class="menu-icon" alt="">
              <div data-i18n="Layouts" class="letra text-dark"><b>Gestión médica</b></div>
            </a>
          
            <ul class="menu-sub">
    
              @if ($this->authenticado())
    
               @if ($this->profile()->rol === 'Admisión' or $this->profile()->rol === 'Médico' || $this->profile()->rol === 'Director')
               <li class="menu-item">
                <a href="{{$this->route('crear-nueva-cita-medica')}}" class="menu-link">
    
                  <div data-i18n="Without menu" class="letra"><b>nueva cita médica</b></div>
                </a>
              </li>
               @endif
              @endif
    
              @if ($this->authenticado())
    
               @if ($this->profile()->rol === 'Admisión' || $this->profile()->rol === 'Médico' || $this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general')
               <li class="menu-item">
                <a href="{{$this->route("citas-programados")}}" class="menu-link">
                  <div data-i18n="Without navbar" class="letra"><b>citas programados</b></div>
                </a>
              </li>
               @endif

             @if ( $this->profile()->rol === 'Médico')
              <li class="menu-item">
                <a href="{{$this->route("medico/generate/recibo/paciente")}}" class="menu-link">
              
                  <div data-i18n="Without menu" class="letra"><b>recibos</b></div>
                </a>
              </li>
              <li class="menu-item">
                <a href="{{$this->route("gestionar-recetas")}}" class="menu-link">
              
                  <div data-i18n="Without menu" class="letra"><b>recetas</b></div>
                </a>
              </li>
               <li class="menu-item">
                <a href="{{$this->route("historial-de-ordenes")}}" class="menu-link">
              
                  <div data-i18n="Without menu" class="letra"><b>órden médica</b></div>
                </a>
              </li>
              @endif
                  
              @endif
           
            </ul>
          </li>
          @endif
      @endif
      
      @if ($this->profile()->rol === 'Enfermera-Triaje' or $this->profile()->rol === 'Médico')
      <li class="menu-item">
        <a href="{{$this->route("triaje/pacientes")}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/triaje.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra text-dark"><b>Pacientes (Triaje)</b></div>
        </a>
      </li>
      @endif
      
      @if ($this->profile()->rol === 'Médico')
      <li class="menu-item">
        <a href="{{$this->route("nueva_atencion_medica")}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/atencion_medica.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra text-dark"><b>Atención médica</b></div>
        </a>
      </li>
      <li class="menu-item">
        <a href="{{$this->route("paciente/evaluacion_informes")}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/informes_.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra text-dark"><b>Evaluación e informes</b></div>
        </a>
      </li>
      @endif 

      @if ($this->profile()->rol === 'Director' or $this->profile()->rol === 'admin_general' || $this->profile()->rol === 'admin_farmacia' || $this->profile()->rol === 'Farmacia' )
      <li class="menu-item">
        <a href="{{$this->route("reportes")}}" class="menu-link menu-toggle">
          <img src="{{$this->asset('img/icons/unicons/reporte.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra text-dark"><b>Reportes</b></div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{$this->route("reportes")}}" class="menu-link">

              {{-- SOLO ADMINISTARDORES--}}
              <div data-i18n="Without menu" class="letra" style="color: ##696969">Reporte general</div>
            </a>
          </li>
        @if ($this->profile()->rol === 'Director' or $this->profile()->rol === 'admin_general')
          <li class="menu-item">
            <a href="{{$this->route("medico/ingresos-por-mes-detallado")}}" class="menu-link">

              {{-- SOLO ADMINISTARDORES--}}
              <div data-i18n="Without menu" class="letra" style="color: ##696969">Ingresos mensual</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{$this->route("enfermedades/reportes")}}" class="menu-link">

              {{-- SOLO ADMINISTARDORES--}}
              <div data-i18n="Without menu" class="letra" style="color: ##696969">Enfermedades</div>
            </a>
          </li>
          @endif
        </ul>
      </li>
      @endif
      @if ($this->profile()->rol === 'Admisión' || $this->profile()->rol === 'Médico')
      <li class="menu-item">
        <a href="{{$this->route('clinica/notificaciones')}}" class="menu-link">
         <img src="{{$this->asset('img/icons/unicons/alarma.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Without menu" class="letra text-dark"><b>Notificaciones <span class="badge bg-danger">
          {{isset($this->CantidadNotificaciones()->cantidad_notificaciones) ? $this->CantidadNotificaciones()->cantidad_notificaciones:0}}  
          </span></b></div>
        </a>
      </li>
      @endif

      @if ($this->profile()->rol === 'Admisión' || $this->profile()->rol === 'Enfermera-Triaje')
      <li class="menu-item">
        <a href="{{$this->route('ver-historial-clinico')}}" class="menu-link">
         <img src="{{$this->asset('img/icons/unicons/documento.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Without menu" class="letra text-dark"><b>Historias clínicas</b>
           </div>
        </a>
      </li>
      @endif


       @if ($this->profile()->rol === 'Admisión')
      <li class="menu-item">
        <a href="{{$this->route('medico/generate/recibo/paciente')}}" class="menu-link">
         <img src="{{$this->asset('img/icons/unicons/dinero.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Without menu" class="letra text-dark"><b>Cobrar Servicios</b>
           </div>
        </a>
      </li>
       <li class="menu-item">
        <a href="{{$this->route('apertura/caja')}}" class="menu-link">
         <img src="{{$this->asset('img/icons/unicons/cajachica.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Without menu" class="letra text-dark"><b>Aperturar Caja</b>
           </div>
        </a>
      </li>
      
      @endif
    </ul>
  </aside>