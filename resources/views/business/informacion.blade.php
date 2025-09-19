@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Configurar datos empresa')

@section('css')
    <style>
        #hora_inicio_atencion,
        #hora_cierre_atencion {
            color: crimson;
            border: 1px solid #3ad5ec;
        }

        #tabla_hora_atencion_>thead>tr>th {
            background-color: #4169E1;
            color: antiquewhite;
        }

        td.hide_me {
            display: none;
        }
        .tabla-red>thead{
            background-color: #0e1f52;
           ;
        }

        #tabla_redes_sociales>thead{
            background-color: #0e1f52;
           ;
        }
 
        
    </style>
@endsection

@section('contenido')
    <div class="col-12" id="car">
        <div class="nav-align-top mb-4">
            <ul class="nav nav-tabs nav-fill" role="tablist" id="tab_data_clinica">

                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                         id="copia_restore_clinica_"
                        data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="true"
                        style="color: #4169E1">
                        <i class='bx bxs-data'></i> <b>Copia y restauración del sistema</b>

                    </button>
                </li>

                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#cita_save"
                         id="data_clinica_"
                        aria-controls="navs-justified-messages" aria-selected="false" style="color:#151516">
                        <b> <img src="{{ $this->asset('img/icons/unicons/cita_.ico') }}" class="menu-icon" alt=""
                                style="width: 25px;height:24px ;">{{$this->profile()->rol === 'admin_farmacia' ? 'Registrar mi farmacia':'Registar mi Clínica'}}</b>
                    </button>
                </li>

                @if (isset($this->profile()->rol) and ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general' || $this->profile()->rol === 'Médico'))
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            id="save_dias_laborables_clinica_"
                            data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile"
                            aria-selected="false" style="color:#FF4500">
                            <b><img src="{{ $this->asset('img/icons/unicons/reloj.ico') }}" class="menu-icon" alt=""
                                    style="width: 25px;height:24px ;"> Registrar diás laborables</b>
                        </button>
                    </li>
                @endif
                @if (isset($this->profile()->rol) and ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general' || $this->profile()->rol === 'Médico') )
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            id="dias_laborables_clinica_"
                            data-bs-target="#navs-justified-messages" aria-controls="navs-justified-messages"
                            aria-selected="false" style="color:#48D1CC">
                            <b> <img src="{{ $this->asset('img/icons/unicons/citas_save.ico') }}" class="menu-icon"
                                    alt="" style="width: 25px;height:24px ;"> </i> Días Laborables</b>
                        </button>
                    </li>
                @endif
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                    <div class="form-group">
                        @if ($this->ExistSession('mensaje_error'))
                            <div class="alert alert-danger">
                                {{ $this->getSession('mensaje_error') }}
                            </div>
                            {{ $this->destroySession('mensaje_error') }}
                        @endif


                        <label for="form-group"><b>Que desea realizar ?</b></label>
                        <select name="opcion_sistema" id="opcion_sistema" class="form-select">

                            @if (isset($this->profile()->rol))
                                <option value="1">Copia de seguridad</option>
                            @endif
                            <option value="2">Restaurar sistema</option>
                        </select>
                        <br>
                        <img src="{{ $this->asset('img/gif/loading2.gif') }}" alt="Hola" id="carga_restore"
                            style="width: 100px;height:100px;display:none;">
                        @if (isset($this->profile()->rol))
                            <div class="col" id="copia">
                                <form action="{{ $this->route('configuracion/copia') }}" method="post">
                                    <input type="hidden" name="token_" value="{{ $this->Csrf_Token() }}">
                                    <div class="form-group">
                                        <label for="copia_" class="form-label"><b>Nombre de la copia - BD <span
                                                    class="text-danger">(*)</span></b></label>
                                        <input type="text" name="copia_" id="copia_" class="form-control"
                                            placeholder="Escriba el nombre de la copia de seguridad ...." autofocus>
                                    </div>
                                    <br>
                                    <button class="btn-save float-end"><b>generar copia </b> <i
                                            class='bx bxs-data'></i></button>
                                </form>
                            </div>
                        @endif

                        <div class="col" id="restaurar" @if (isset($this->profile()->rol)) style="display:none" @endif>
                            <form action="{{ $this->route('configuracion/restaurar') }}" method="post"
                                enctype="multipart/form-data" id="form-restore">
                                <input type="hidden" name="token_" value="{{ $this->Csrf_Token() }}">
                                <div class="form-group">
                                    <label for="archivo_bd" class="form-label"><b>Seleccione Archivo .sql</b></label>
                                    <input type="file" name="archivo_bd" id="archivo_bd" class="form-control"
                                        accept=".sql">
                                </div>
                                <br>
                                <button class="btn_blue float-end" id="restore_button"><b>Restaurar sistema </b> <i
                                        class='bx bx-refresh'></i></button>
                                @if (!isset($this->profile()->rol))
                                    <a href="{{ $this->route('login') }}"
                                        class="btn btn-rounded btn-outline-info float-end mx-2"><b>Iniciar sesión <i
                                                class='bx bx-log-in'></i></b></a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">

                    <form action="" method="post" id="formulario_horario">
                        <input type="hidden" name="token_" value="{{ $this->Csrf_Token() }}">
                        <div class="card-text">
                            <h4>Configurar horarios de atención</h3>
                        </div>
                        <div class="row mb-3">

                            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-6 col-12">
                                <label for="dias_atencion" class="form-label">Días de atención(*)</label>
                                <select name="dias_atencion" id="dias_atencion" class="form-select" autofocus>
                                    <option value="Lunes">Lunes</option>
                                    <option value="Martes">Martes</option>
                                    <option value="Miercoles">Miercoles</option>
                                    <option value="Jueves">Jueves</option>
                                    <option value="Viernes">Viernes</option>
                                    <option value="Sábado">Sábado</option>
                                    <option value="Domingo">Domingo</option>
                                </select>
                            </div>

                            <div class="col-xl-4 col-lg-7 col-md-6 col-sm-6 col-12">
                                <label for="hora_inicio_atencion" class="form-label">Hora inicio(*)</label>
                                <input type="time" name="hora_inicio_atencion" id="hora_inicio_atencion"
                                    class="form-control" value="08:00">
                            </div>

                            <div class="col-xl-4 col-12">
                                <label for="hora_cierre_atencion" class="form-label">Hora de Cierre(*)</label>
                                <input type="time" name="hora_cierre_atencion" id="hora_cierre_atencion"
                                    class="form-control" value="16:00">
                            </div>
                        </div>

                        <div class="row lista" style="display: none">
                            <div class="card-text">

                                <b class="text-info"> Lista de Horarios de atención</b>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th><b>Día de atención</b></th>
                                                <th><b>Hora Inicio</b></th>
                                                <th><b>Hora Final</b></th>
                                                <th><b>Quitar</b></th>
                                            </tr>
                                        </thead>
                                        <tbody id="lista-horarios"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 m-1">
                                <button class="button-new" style="width: 100%" id="listar"> Agregar horario <i
                                        class='bx bx-check'></i></button>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-1">
                                <button class="button-store" style="width: 100%;display: none" id="store_atencion">
                                    Guardar horario <i class='bx bx-save'></i></button>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
                    <div class="card-text">
                        <p class="h4">Configurar días laborables</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover nowrap" id="tabla_hora_atencion_"
                            style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="d-none">ID</th>
                                    <th>DÍA</th>
                                    <th>HORARIO</th>
                                    <th>LABORABLE?</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade busines" id="cita_save" role="tabpanel">
                    <form action="{{ $this->route('empresa/store') }}" method="post" enctype="multipart/form-data"
                        id="form_clinica_save">
                        <input type="hidden" name="token_" value="{{ $this->Csrf_Token() }}">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="name_clinica"><b>{{$this->profile()->rol === 'admin_farmacia' ? 'Nombre de la farmacia':'Nombre de la clínica'}}</b> <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" class="form-control" name="name_clinica" id="name_clinica" placeholder="Escriba aquí...">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="ruc_clinica"><b>Indique su # de ruc </b> </label>
                                    <input type="text" class="form-control" name="ruc_clinica" id="ruc_clinica" placeholder="Escriba aquí...">
                                </div>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="direccion"><b>Dirección</b> </label>
                                    <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Escriba aquí..">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="phone_clinica"><b># Teléfono</b><span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" class="form-control" name="phone_clinica" id="phone_clinica" placeholder="XXX XXX XXX">
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                <div class="form-group">
                                    <label for="simbolo_moneda"><b>Símbolo moneda</b><span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" class="form-control" name="simbolo_moneda" id="simbolo_moneda" placeholder="ejemplo (S/.)">
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                <div class="form-group">
                                    <label for="wasap"><b>WhatsApp</b></label>
                                    <input type="tel" class="form-control" name="wasap" id="wasap" placeholder="ejemplo +51 XXX XXX XXX">
                                </div>
                            </div>
 

                            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                <div class="form-group">
                                    <label for="valor_iva"><b>Valor IVA [%]</b><span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" class="form-control" name="valor_iva" id="valor_iva" placeholder="Inique el impuesto..."
                                    pattern="[0-9]+">
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="paginaweb_clinica"><b>Página web</b></label>
                                    <input type="url" class="form-control" name="paginaweb_clinica" placeholder="https://mipaginaweb.com"
                                        id="paginaweb_clinica">
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="email_clinica"><b>Contacto (email) </b></label>
                                    <input type="url" class="form-control" name="email_clinica" placeholder="contactoclinica@gmail|empresa.com"
                                        id="email_clinica">
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="logo_clinica"><b>Seleccione su logo</b></label>
                                    <input type="file" class="form-control" name="logo_clinica" id="logo_clinica">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Mapa *</label>
                                    <input type="text" name="mapa" id="mapa" class="form-control" placeholder="url mapa...">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="message_wasap"><b>Escriba mensaje para whatsApp</b></label>
                                    <textarea name="message_wasap" id="message_wasap" cols="30" rows="3" class="form-control" placeholder="Escriba el mensaje para whatsApp...."></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="historia"><b>Describe los valores </b></label>
                                     <textarea name="historia" id="historia" cols="30" rows="5" class="form-control" placeholder="Escriba aquí..."></textarea>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="mision"><b>Misión  </b></label>
                                     <textarea name="mision" id="mision" cols="30" rows="5" class="form-control" placeholder="Escriba aquí..."></textarea>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="vision"><b>Visión   </b></label>
                                     <textarea name="vision" id="vision" cols="30" rows="5" class="form-control" placeholder="Escriba aquí..."></textarea>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="text-center">
                            <img src="{{ $this->asset('img/default.png') }}" alt=""
                                style="border-radius: 50%;border:#4169E1 1px solid;width: 180px;height:180px; "
                                id="logo_">
                            <br>
                            <button class="btn rounded btn-outline-success my-5" id="save_clinica" style="display: none"><b>Registrar <i
                            class='bx bx-save'></i></b></button>
                            <button class="btn rounded btn-outline-success my-5" id="update_clinica" style="display: none"><b>Guardar cambios <i
                                class='bx bx-save'></i></b></button>
                            <button class="btn rounded btn-danger" id="cancelar_"><b>Cancelar <i class='bx bx-x'></i></b></button>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table
                            class="table table-borderless table-light table-hover table-striped table-sm nowrap responsive"
                            style="width: 100%" id="tabla_empresa">
                            <thead>
                                <tr>
                                    <th>Acciones</th>
                                    <th>Nombre empresa</th>
                                    <th>Ruc</th>
                                    <th>Diección</th>
                                    <th>Teléfono</th>
                                    <th>WhatsApp</th>
                                    <th>Mensaje para whatsApp</th>
                                    <th>Símbolo moneda</th>
                                    <th>Iva Valor[%]</th>
                                    <th>Página web</th>
                                    <th>Contacto</th>
                                    <th>Quienes son ?</th>
                                    <th>Misión</th>
                                    <th>Visión</th>
                                    <th>Logo</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal para editar la configuración de días laborables con su horarios-- --}}
    <div class="modal" tabindex="-1" id="modal_config">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #4169E1;">
                    <h5 class="modal-title text-white">Editar horario de atención</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="dia_editar" class="form-group"><b>Día</b></label>
                        <input type="text" class="form-control" id="dia_editar" readonly
                            style="border: 1px solid #4169E1">
                    </div>
                    <div class="form-group">
                        <label for="hora_inicial_editar" class="form-group"><b>Hora inicio</b></label>
                        <input type="time" class="form-control" id="hora_inicial_editar"
                            style="border:1px solid #4169E1">
                    </div>

                    <div class="form-group">
                        <label for="hora_cierre_editar" class="form-group"><b>Hora cierre</b></label>
                        <input type="time" class="form-control" id="hora_cierre_editar"
                            style="border:1px solid #4169E1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-save" id="update_config_dia"> Guardar <i
                            class='bx bx-save'></i></button>
                </div>
            </div>
        </div>
    </div>
    {{-- UPLOAD BANNER ---}}
    <div class="modal" tabindex="-1" id="modal_upload_banner">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #4169E1;">
                    <h5 class="modal-title text-white">Subir portada y Vídeo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                    <form action="" method="post" id="form_upload_banner" enctype="multipart/form-data">
                        <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                        <input type="file" id="file_banner" name="file_banner" style="display: none" class="form-control">
                      <div class="form-group text-center mt-2 fot">
                        <label for="imagen_banner"><b>Seleccione una imágen [800 X 600 px]</b></label>
                        <img src="{{$this->asset("img/icons/unicos/galeria.png")}}" id="imagen_banner" alt="" class="img-fluid" style="height: auto;width: 360px;border-radius: 30%;border:2px solid blue;cursor: pointer;">
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="video"><b>Vídeo <span class="text-danger">*</span></b></label>
                                <input type="text" name="video" id="video" class="form-control" placeholder="https://video">
                            </div>
                        </div>
    
                        <div class="col-12">
                            <div class="form-group">
                                <label for="portada_video"><b>Portada del vídeo [600 X 525 px]<span class="text-danger">*</span></b></label>
                                <input type="file" name="portada_video" id="portada_video" class="form-control">
                            </div>
                        </div>
                      </div>
                    </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-save" style="display: none"  id="upload_save_baner"> Guardar <i
                        class='bx bx-save'></i></button>
                    </div>
            </div>
        </div>
    </div>

    {{---AGREGAR UNA RED SOCIAL----}}
    <div class="modal" tabindex="-1" id="modal_asignar_redes_sociales">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #4169E1;">
                    <h5 class="modal-title text-white" id="titulo_redes">Asignar red social</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                     
                        <table class="table table-bordered table-striped tabla-red" id="tabla_full_red_data">
                            <thead >
                                <tr>
                                    <th class="text-white">
                                        <input type="checkbox" style="width: 30px;height: 30px;cursor: pointer;" id="todo">
                                    </th>
                                    <th class="text-white">Red Social</th>
                                    <th class="text-white">Link</th>
                                </tr>
                            </thead>
                            <tbody id="tabla_red_data">

                            </tbody>
                        </table>

                        <table class="table table-bordered table-striped tabla-red"  id="tabla_full_redes_sociales" style="display: none">
                            <thead >
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th class="text-white">Red Social</th>
                                    <th class="text-white">Link</th>
                                    <th class="text-white">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla_redes_sociales">

                            </tbody>
                        </table>
                      
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-save" id="asignar_redes"> Asignar redes sociales <i
                        class='bx bx-save'></i></button>
                    </div>
            </div>
        </div>
    </div>
 {{-- SUBIR UNA IMAGEN DE FONDO---}}
 <div class="modal modal-fade" id="modal_upload_imagen_fondo" data-bs-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="text-white letra">Subir una imágen para fondo</h4>
            </div>
            <div class="modal-body">
               <form action="" method="post" id="form_upload_img_fondo" enctype="multipart/form-data">
                <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <input type="file" id="file_img_fondo" name="file_img_fondo" style="display: none">
                        <img src="{{$this->asset("img/default.png")}}" id="img_preview_fondo"  style="border-radius: 50%;border:#4169E1 1px solid;width: 200px;height:200px; " alt="">
                    </div>
                    <div class="col-12 text-center mt-2">
                        <button class="btn btn-primary" id="upload_img_fondo">Subir imágen <i class="fas fa-upload"></i></button>
                    </div>
                </div>
               </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" id="save_img_fondo"><b>Guardar <i class="fas fa-save"></i></b></button>
                <button class="btn btn-danger" onclick="$('#modal_upload_imagen_fondo').modal('hide')">Salir X</button>
            </div>
        </div>
    </div>
 </div>
 {{--- SUBIR EL ICONO DE LA EMPRESA----}}
 <div class="modal fade" id="ventana_subir_icono">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #4169E1">
                <h4 class="letra text-white">Subir Icono</h4>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="form_upload_icono">
                     <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                     <label for="icono_select" class="form-label">
                        Seleccionar un archivo con extensión .ico 
                     </label>
                      <input type="file" class="form-control" id="icono_select"
                        name="icono_select">
                </form>
            </div>
            <div class="modal-footer  border-2">
                <button class="btn_blue" id="subir_icono_save">Subir Icono <i class='bx bx-upload' ></i></button>
            </div>
        </div>
    </div>
 </div>
@endsection

@section('js')
    <script>
        var RUTA = "{{ URL_BASE }}" // la url base del sistema
        var TOKEN = "{{ $this->Csrf_Token() }}";
        var Tabla_Horario_Dias_Atencion;
        var ID_DATA_CONFIG;
        var TablaEmpresa;
        var CLINICA_ID_DATA;
        var EMPRESAID;
        var PROFILE_ = "{{$this->profile()->rol}}";
    </script>
    <script src="{{ URL_BASE }}public/js/control.js"></script>
    <script src="{{ URL_BASE }}public/js/configuracion.js"></script>
    <script>
        var Horarios = [];
        var ID_CLINICA_;
        $(document).ready(function() {
            let Dias = $('#dias_atencion');

            let Hora_Inicio_Atencion = $('#hora_inicio_atencion');

            let Hora_Cierre_Atencion = $('#hora_cierre_atencion');

            let ButtonStore = $('#store_atencion');

            let TablaListaHorarios = $('#lista-horarios');

            let NameClinica = $('#name_clinica');

            let Ruc = $('#ruc_clinica');

            let PhoneClinica = $('#phone_clinica');

            let SimboloMoneda = $('#simbolo_moneda');
            let ValorIva = $('#valor_iva');

            var Foto;var FotoPortadaVideo;
          
            /// visualizamos al boton de save
            $('#save_clinica').show();
            MostrarDataEmpresa();
            ConfirmdeleteClinica(TablaEmpresa, '#tabla_empresa tbody');
            SubirImagenDeFondoReportes(TablaEmpresa, '#tabla_empresa tbody');
            editarClinica(TablaEmpresa,'#tabla_empresa tbody');
            UploadBanner(TablaEmpresa,'#tabla_empresa tbody');
            AsignarRedesSociales(TablaEmpresa, '#tabla_empresa tbody');
            uploadIcono(TablaEmpresa, '#tabla_empresa tbody');
            ActiveRedSocial();
             

            $('#save_img_fondo').click(function(evtno){
                evtno.preventDefault();
                let FormularioUploadImgFondo = new FormData(document.getElementById("form_upload_img_fondo"));

                axios({
                    url:RUTA+"empresa/upload-img-fondo/"+ID_CLINICA_,
                    method:"POST",
                    data:FormularioUploadImgFondo,
                }).then(function(response){
                    if(response.data.error != undefined){
                        Swal.fire({
                            title:"MENSAJE DEL SISTEMA!!",
                            text:response.data.error,
                            icon:"error",
                            target:document.getElementById("modal_upload_imagen_fondo")
                        });
                    }else{
                        if(response.data.response === 'ok'){
                            Swal.fire({
                            title:"MENSAJE DEL SISTEMA!!",
                            text:"LA IMAGEN DE FONDO A SIDO SUBIDO CORRECTAMENTE!!",
                            icon:"success",
                            target:document.getElementById("modal_upload_imagen_fondo")
                        }).then(function(){
                            MostrarDataEmpresa();
                        });
                        }else{
                            Swal.fire({
                            title:"MENSAJE DEL SISTEMA!!",
                            text:"ERROR AL SUBIR IMAGEN DE FONDO!!",
                            icon:"error",
                            target:document.getElementById("modal_upload_imagen_fondo")
                        });
                        }
                    }
                });
            });

            $('#upload_img_fondo').click(function(evtno){
                evtno.preventDefault();
                $('#file_img_fondo').click();
            });

            $('#file_img_fondo').change(function(){
                ReadImagenTwo(this, 'img_preview_fondo', event.target.files[0],'modal_upload_imagen_fondo');
            });
            /// dando click al boton listar
            $('#listar').click(function(evento) {

                evento.preventDefault();

                /// verificamos que el campo de dias este rellenado

                if (Dias.val().trim().length > 0) {

                    listarHorarios(Dias.val(), Hora_Inicio_Atencion.val(), Hora_Cierre_Atencion.val(),
                        Horarios);

                } else {
                    Dias.focus();
                }
            });

            $('#subir_icono_save').click(function(){
                let FormUploadIcono = new FormData(document.getElementById("form_upload_icono"));
                axios({
                    url:RUTA+"upload/icono/empresa/"+EMPRESAID,
                    method:"POST",
                    data:FormUploadIcono
                }).then(function(response){
                   if(response.data.error != undefined){
                    Swal.fire({
                        title:"MENSAJE DEL SISTEMA!!!",
                        text:response.data.error,
                        icon:"error",
                        target:document.getElementById('ventana_subir_icono')
                    });
                   }else{
                     Swal.fire({
                        title:"MENSAJE DEL SISTEMA!!!",
                        text:response.data.response,
                        icon:"success",
                        target:document.getElementById('ventana_subir_icono')
                     }).then(function(){
                        //$('#form_upload_icono')[0].reset();
                     })
                   }
                });
            });

            $('#tabla_red_data').on('click','a',function(evento){
                evento.preventDefault();
                showRedesSocialesClinica()
                $('#asignar_redes').hide();
                $('#titulo_redes').text("Mis redes sociales")
                $('#tabla_full_red_data').hide();
                $('#tabla_full_redes_sociales').show();
            });

            $('#tabla_redes_sociales').on('click','a',function(evento){
                evento.preventDefault();
                
                $('#titulo_redes').text("Asignar redes sociales")
                $('#asignar_redes').show();
                $('#tabla_full_red_data').show();
                $('#tabla_full_redes_sociales').hide();
            });

            $('#tab_data_clinica button').on('click',function(){
                let IdControlClinica = $(this)[0].id;
                if(IdControlClinica === 'data_clinica_')
                {
                    MostrarDataEmpresa();
                } 
            });

            $('#asignar_redes').click(function(){
               if(CantidadRedesSeleccionados() > 0){
                AsignarRedSocial_Clinica();
               }else{
                Swal.fire(
                    {
                        title:"MENSAJE DEL SISTEMA!",
                        text:"Seleccione por lo menos una red social, para asignar a la clínica!",
                        icon:"warning",
                        target:document.getElementById('modal_asignar_redes_sociales')
                    }
                )
               }
            });

            $('#update_config_dia').click(function() {
                updateAtencion(ID_DATA_CONFIG, $('#hora_inicial_editar'), $('#hora_cierre_editar'), $(
                    '#dia_editar'));
            });

            $('#logo_clinica').change(function() {
                ReadImagen(this, 'logo_', event.target.files[0]);
            });
            /// Guardamos los datos de la clínica
            $('#save_clinica').click(function(ev) {
                ev.preventDefault();
                if (NameClinica.val().trim().length == 0) {
                    NameClinica.focus();
                } else {
                   
                   if (PhoneClinica.val().trim().length == 0) {
                       PhoneClinica.focus();
                   } else {
                    if(SimboloMoneda.val().trim().length == 0)
                    {
                        SimboloMoneda.focus();
                    }
                    else{
                        if(ValorIva.val().trim().length == 0)
                        {
                            ValorIva.focus();
                        }else{
                            saveClinicaData();
                        }
                    }
                   }
                     
                }
            });

            /// modificar los datos de la clínica
            $('#update_clinica').click(function(evento){
                evento.preventDefault();
                if (NameClinica.val().trim().length == 0) {
                    NameClinica.focus();
                } else {
                  if(PhoneClinica.val().trim().length == 0)
                  {
                    PhoneClinica.focus();
                  }
                  else{
                    if(SimboloMoneda.val().trim().length == 0)
                    {
                        SimboloMoneda.focus();
                    }else{
                        if(ValorIva.val().trim().length == 0)
                        {
                            ValorIva.focus();
                        }else{
                            updateClinicaData(CLINICA_ID_DATA);
                        }
                    }
                  }
                }
            });

            /// cancelar registro de la clínica
            $('#cancelar_').click(function(evento){
                evento.preventDefault();
                $('#save_clinica').show();
                $('#update_clinica').hide();
                $('#name_clinica').val("");
                $('#ruc_clinica').val("");
                $('#direccion').val("");
                $('#phone_clinica').val("");
                $('#paginaweb_clinica').val("");
                $('#logo_').attr("src","{{$this->asset("")}}"+"img/default.png");

            });

            ButtonStore.click(function(evento) {

                evento.preventDefault();

                if (storeHorarioEsSalud() == 200) {
                    Hora_Cierre_Atencion.focus();
                    MessageRedirectAjax('success', 'Mensaje del sistema',
                        'Horarios registrados correctamente', 'layout-menu')


                    // limpiamos todo
                    Horarios = [];
                    $('#lista-horarios').empty();
                    Hora_Inicio_Atencion.val("08:00");
                    Hora_Cierre_Atencion.val("16:00");
                    $('.lista').hide(550);
                    ButtonStore.hide(550)
                }

            });

            $('#restore_button').click(function(evento) {
                evento.preventDefault();
                if ($('#archivo_bd').val().trim().length == 0) {
                    Swal.fire({
                        title: 'Mensaje del sistema!',
                        text: 'Para importar los datos del sistema, proceda a seleccionar un archivo [.sql]',
                        icon: 'error'
                    })
                } else {
                    let FormRestaurar = new FormData(document.getElementById('form-restore'));
                    $.ajax({
                        url: RUTA + "configuracion/restaurar",
                        method: "POST",
                        data: FormRestaurar,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $('#carga_restore').show();
                        },
                        success: function(response) {

                            response = JSON.parse(response);

                            if (response.mensaje === 'success') {
                                Swal.fire({
                                    title: "Mensaje del sistema!",
                                    text: "Los datos se han importado correctamente al sistema",
                                    icon: "success"
                                }).then(function() {
                                    $('#carga_restore').hide();
                                    $('#archivo_bd').val("");
                                });
                            } else {
                                if (response.mensaje === 'error') {
                                    alert("error")
                                } else {
                                    alert("archivo vacio");
                                }
                            }
                        }
                    })
                }
            });

            $('#archivo_bd').change(function() {

                let NombreArchivo = this.files[0].name;

                let Extension = NombreArchivo.split(".");

                if (Extension[1] !== 'sql') {
                    Swal.fire({
                        title: 'Mensaje del sistema!',
                        text: 'Solo están permitidos archivos con extensión .sql',
                        icon: 'error'
                    })
                }

                return;
            });

            $('#opcion_sistema').change(function() {


                if ($(this).val() === "1") {
                    $('#copia').show(700);
                    $('#copia_').focus();
                    $('#restaurar').hide();
                } else {
                    $('#copia').hide();
                    $('#restaurar').show(700);
                    $('#copia_').val("");
                }
            });

            /// selecciona una imagen para el banner
            $('#imagen_banner').click(function(){
                $('#file_banner').click();
            });

            $('#file_banner').change(function(){
                let tipoImagen = this.files[0].type;
                tipoImagen = tipoImagen.split("/")[1];
                Foto = this.files[0];
  
                
                if(tipoImagen !== 'jpeg'){
                    Swal.fire({
                        title:"Mensaje del sistema!",
                        text:"Error,debe de seleccionar una imágen tipo JPG",
                        icon:"error",
                        target:document.getElementById('modal_upload_banner')
                    });
                    $('#imagen_banner').attr("src","{{$this->asset('img/default.png')}}");
                    $('#upload_save_baner').hide();
                    return;
                } 
                   var imagen = new Image();

                   imagen.onload = function(){
                     let DimensionX = this.width; let DimensionY = this.height;
                     
                     if(DimensionX != 800 && DimensionY != 600){
                        Swal.fire({
                            title:"¡ADVERTENCIA!",
                            text:"La imágen debe tener una medida de [800 X 600 px]",
                            icon:"warning",
                            target:document.getElementById('modal_upload_banner')
                        });
                        $('#upload_save_baner').hide();
                        return;
                     } 
                     $('#upload_save_baner').show(400);
                   };

                   imagen.src = URL.createObjectURL(Foto)
                   
                   ReadImagen(this, 'imagen_banner', event.target.files[0]);
            });

            $('#todo').change(function(){
               if($(this).is(':checked')){
                $('#tabla_red_data input[type=checkbox]').prop("checked",true);
                $('#tabla_red_data input[type=text]').prop("disabled",false);
               }else{
                $('#tabla_red_data input[type=checkbox]').prop("checked",false);
                $('#tabla_red_data input[type=text]').prop("disabled",true);
               }
            });

            $('#upload_save_baner').click(function(){
                if( $('#video').val().trim().length == 0){
                    Swal.fire({
                        title:"¡ADVERTENCIA!",
                        text:"Complete todos los datos!",
                        icon:"warning",
                        target:document.getElementById('modal_upload_banner')
                    });
                }else{
                    let FormUploadBanner = new FormData(document.getElementById('form_upload_banner'));
                $.ajax({
                    url:RUTA+"busines/cargar-foto/"+ID_CLINICA_,
                    method:"POST",
                    data:FormUploadBanner,
                    contentType:false,
                    processData:false,
                    dataType:"json",
                    success:function(response){
                       if(response.response === 'ok'){
                         Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"La imágen se a subido correctamente!",
                            icon:"success",
                            target:document.getElementById('modal_upload_banner')
                         }).then(function(){
                            $('#video').val("");
                            $('#file_banner').val("");
                            $('#portada_video').val("");
                            MostrarDataEmpresa();
                         })
                       }else{
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Error al intentar subir la imágen!",
                            icon:"error",
                            target:document.getElementById('modal_upload_banner')
                         });
                       }
                    }
                    
                });
                }
            });

            $('#portada_video').change(function(){
                FotoPortadaVideo = this.files[0];
                let tipoImagen = FotoPortadaVideo.type; 
                tipoImagen = tipoImagen.split("/")[1];

                if(tipoImagen !== 'jpeg'){
                    Swal.fire({
                        title:"Mensaje del sistema!",
                        text:"Error,debe de seleccionar una imágen tipo JPG",
                        icon:"error",
                        target:document.getElementById('modal_upload_banner')
                    });
                   
                    $('#upload_save_baner').hide();
                    return;
                } 

                var imagenPortada = new Image();

                   imagenPortada.onload = function(){
                     let DimensionX = this.width; let DimensionY = this.height;
                     
                     if(DimensionX != 600 && DimensionY != 525){
                        Swal.fire({
                            title:"¡ADVERTENCIA!",
                            text:"La imágen de portada del vídeo debe tener una medida de [600 X 525 px]",
                            icon:"warning",
                            target:document.getElementById('modal_upload_banner')
                    });
                    $('#upload_save_baner').hide();
                        return;
                    } 
                     $('#upload_save_baner').show(400);
                   };

                   imagenPortada.src = URL.createObjectURL(FotoPortadaVideo)

            });

            $('#tabla_redes_sociales').on('click','#editar',function(){
              let fila = $(this).parents('tr');
              fila.find('#link').prop("disabled",false);
              fila.find('#link').focus();
            });

            $('#tabla_redes_sociales').on('keypress','#link',function(evento){
              let fila = $(this).parents('tr');
              let reds = fila.find('td').eq(0).text();
              let FormRedes = new FormData();
              FormRedes.append("token_",TOKEN);
              FormRedes.append("link",$(this).val());
             
               if(evento.which == 13){
                if($(this).val().trim().length > 0){
               axios({
                url:RUTA+"red-social/clinica/modificar/"+reds,
                method:"POST",
                data:FormRedes
               }).then(function(response){
                  if(response.data.error != undefined){
                    Swal.fire({
                        title:"MENSAJE DEL SISTEMA!",
                        text:"Error, token incorrecto!",
                        icon:"error"
                    })
                  }else{
                    if(response.data.response === 'ok'){
                        Swal.fire({
                            title:"MENSAJE DEL SISTEMA!",
                            text:"La red social de la clínica a sido modificado!",
                            icon:"success",
                            target:document.getElementById('modal_asignar_redes_sociales')
                        }).then(function(){
                            showRedesSocialesClinica();
                        })
                    }
                  }
               })
              }
               }
            });

            /// confirmar borrado de la red social asignado
            $('#tabla_redes_sociales').on('click','#borrar',function(evento){
              let fila = $(this).parents('tr');
              let reds = fila.find('td').eq(0).text();
              let redsName = fila.find('td').eq(1).text();
            Swal.fire({
            title: "Estas seguro de eliminar a la red social "+redsName+" que se le asignó?",
            text: "Al aceptar, se borrará de la lista!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!",
            target:document.getElementById('modal_asignar_redes_sociales')
            }).then((result) => {
            if (result.isConfirmed) {
              BorrarRedSocialClinica(reds);
            }
            });
              
            });

            /// pasar enter
            enter('dias_atencion', 'hora_inicio_atencion');
            enter('hora_inicio_atencion', 'hora_cierre_atencion');

            Hora_Cierre_Atencion.keypress(function(evento) {

                if (evento.which == 13) {
                    evento.preventDefault();

                    /// verificamos que el campo de dias este rellenado

                    if (Dias.val().trim().length > 0) {


                        listarHorarios(Dias.val(), Hora_Inicio_Atencion.val(), Hora_Cierre_Atencion.val(),
                            Horarios);


                    } else {
                        Dias.focus();
                    }
                }
            });

            if ("{{ isset($this->profile()->rol) }}") {
                ConfigShowDiasLaborables();
                ConfigDiaLaborable();
            }
            editarDiasLaborEsSalud(Tabla_Horario_Dias_Atencion, '#tabla_hora_atencion_ tbody');
        });



        function BorrarRedSocialClinica(id){
            let formDelete = new FormData();
            formDelete.append("token_",TOKEN);
            axios({
                url:RUTA+"red-social/clinica/delete/"+id,
                method:"POST",
                data:formDelete

            }).then(function(response){
                if(response.data.error != undefined){
                    Swal.fire({
                        title:"MENSAJE DEL SISTEMA!",
                        text:"Error, el token de seguridad es incorrecto!",
                        icon:"error",
                        target:document.getElementById('modal_asignar_redes_sociales')
                    });
                }else{
                    if(response.data.response === 'ok'){
                        Swal.fire({
                            title:"MENSAJE DEL SISTEMA!",
                            text:"Red social quitado correctamente!",
                            icon:"success",
                            target:document.getElementById('modal_asignar_redes_sociales')
                        }).then(function(){
                            showRedesSocialesClinica();
                            showRedesSocialesHabilitadas();
                        })
                    }
                }
            });
        }

        function ConfigShowDiasLaborables() {
            Tabla_Horario_Dias_Atencion = $('#tabla_hora_atencion_').DataTable({
                responsive: true,
                bDestroy: true,
                language: SpanishDataTable(),
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                "order": [
                    [1, 'asc']
                ], /// enumera indice de las columnas de Datatable
                ajax: {
                    url: RUTA + "configurar_dias_laborables?token_=" + TOKEN,
                    method: "GET",
                    dataSrc: "response"
                },
                columns: [{
                        "data": "dias_atencion"
                    },
                    {
                        "data": "id_data_empresa"
                    },
                    {
                        "data": "dias_atencion",
                        render: function(dia) {
                            return '<span class="badge bg-danger" id="dia">' + dia + '</span>';
                        }
                    },
                    {
                        "data": null,
                        render: function(horario) {
                            return '<span class="badge bg-info"><b id="ha_">' + horario
                                .horario_atencion_inicial + ' - ' + horario.horario_atencion_cierre +
                                '</b></span>';
                        }
                    },
                    {
                        "data": null,
                        render: function(loborable) {

                            let labor_confirm = '';

                            if (loborable.laborable === 'si') {
                                labor_confirm = `
                     <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="config_dia_atencion` + loborable
                                    .id_data_empresa + `" checked>
                        <label class="form-check-label" for="config_dia_atencion` + loborable.id_data_empresa + `" style="cursor: pointer;" ><b>Laborable</b></label>
                        <button class="btn btn-rounded btn-outline-warning btn-sm" id="editar_dia_labor"> <i class='bx bxs-edit-alt'></i></button>
                    </div> 
                     `;
                            } else {
                                labor_confirm = `
                     <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="config_dia_atencion` + loborable
                                    .id_data_empresa + `">
                        <label class="form-check-label" for="config_dia_atencion` + loborable.id_data_empresa + `" style="cursor: pointer;" ><b class="text-danger">No Laborable</b></label>
                        <button class="btn btn-rounded btn-outline-warning btn-sm" id="editar_dia_labor"> <i class='bx bxs-edit-alt'></i></button>
                    </div> 
                     `;
                            }
                            return labor_confirm;
                        }
                    }
                ],
                columnDefs: [{
                    "sClass": "hide_me",
                    target: 1
                }]
            });

            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
            Tabla_Horario_Dias_Atencion.on('order.dt search.dt', function() {
                Tabla_Horario_Dias_Atencion.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }

        /// editar dias laborables
        function editarDiasLaborEsSalud(Tabla, Tbody) {
            $(Tbody).on('click', '#editar_dia_labor', function() {

                $('#modal_config').modal("show");
                let FilaSeleccionado = $(this).parents('tr');

                if (FilaSeleccionado.hasClass('child')) {
                    FilaSeleccionado = FilaSeleccionado.prev();
                }

                ID_DATA_CONFIG = FilaSeleccionado.find('td').eq(1).text();
                let Dia_Config = FilaSeleccionado.find('td').eq(2).text();

                let HoraAtencionEsSalud = FilaSeleccionado.find("#ha_").text().split(" - ");

                let Hi_ = HoraAtencionEsSalud[0];
                let Hf_ = HoraAtencionEsSalud[1];

                $('#dia_editar').val(Dia_Config);
                $('#hora_inicial_editar').val(Hi_);
                $('#hora_cierre_editar').val(Hf_);


            });
        }

        /// Habilitar y deshabilitar dias laborables
        function ConfigDiaLaborable() {
            $('#tabla_hora_atencion_').on('click', 'input[type=checkbox]', function() {

                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                let id_laborable = fila.find('td').eq(1).text();
                let dia = fila.find("#dia").text();

                if ($(this).is(":checked")) {
                    cambiarDiaAtencion(id_laborable, "si", dia);
                } else {
                    cambiarDiaAtencion(id_laborable, "no", dia);
                }
                ConfigShowDiasLaborables();

            });
        }

        function cambiarDiaAtencion(id, estado, dia) {
            $.ajax({
                url: RUTA + "cambiar_dias_atencion_laborable_no_laborable/" + id + "/" + estado,
                method: "POST",
                data: {
                    token_: TOKEN
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.response !== 'ok') {
                        Swal.fire({
                            title: 'Mensaje del sistema!',
                            text: 'Error al intentar configurar los días de atención de EsSalud',
                            icon: 'error'
                        })
                    }
                }
            })
        }

        /// modificar los datos del horario de atención EsSalud
        function updateAtencion(id, hora_i, hora_f, dia) {
            $.ajax({
                url: RUTA + "configuracion/" + id + "/update",
                method: "POST",
                data: {
                    token_: TOKEN,
                    hi: hora_i.val(),
                    hc: hora_f.val()
                },
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.response === 'ok') {
                        Swal.fire({
                            title: 'Mensaje del sistema!',
                            text: 'El Horario de atención para el día ' + dia +
                                ' se a modificado correctamente',
                            icon: 'success',
                            target: document.getElementById('modal_config')
                        }).then(function() {
                            ConfigShowDiasLaborables();
                        });
                    } else {
                        Swal.fire({
                            title: 'Mensaje del sistema!',
                            text: 'Error al modificar la hora de atención del día seleccionado',
                            icon: 'error',
                            target: document.getElementById('modal_config')
                        })
                    }
                }
            })
        }

        /**Registramos los datos de la clínica**/
        function saveClinicaData() {
            let FormSaveClinica = new FormData(document.getElementById('form_clinica_save'));
            $.ajax({
                url: RUTA + "empresa/store",
                method: "POST",
                data: FormSaveClinica,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.response === 'ok') {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Los datos de tu clínica se han registrado sin problemas 😎😁",
                            icon: "success"
                        }).then(function() {
                            $('#name_clinica').val("");
                            $('#ruc_clinica').val("");
                            $('#direccion').val("");
                            $('#phone_clinica').val("");
                            $('#paginaweb_clinica').val("");
                            $('#logo_clinica').val("");
                            $('#email_clinica').val("");
                            $('#historia').val("");
                            $('#mision').val("");
                            $('#vision').val("");
                            $('#simbolo_moneda').val("");
                            $('#valor_iva').val("");
                            $('#wasap').val("");
                            $('#mapa').val("");
                            $('#logo_').attr("src", "{{ $this->asset('img/default.png') }}");
                            MostrarDataEmpresa();
                        });
                    }else{
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Error al registrar nuevamente la "+((PROFILE_ === 'admin_general' || PROFILE_ === 'Director' || PROFILE_ === 'Médico')?'Clínica':'Farmacia')+", posiblemente ya tenga una clínica registrada, solo se permite el registro de una clínica!",
                            icon:"error"
                        });
                    }
                },error:function(err){
                    Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Error al registrar nuevamente la "+((PROFILE_ === 'admin_general' || PROFILE_ === 'Director' || PROFILE_ === 'Médico')?'Clínica':'Farmacia')+", posiblemente ya tenga una clínica registrada, solo se permite el registro de una clínica!",
                            icon:"error"
                        });
                }
            });
        }

        function MostrarDataEmpresa() {
            TablaEmpresa = $('#tabla_empresa').DataTable({
                language: SpanishDataTable(),
                retrieve: true,
                search: false,
                processing: true,
                responsive: true,

                ajax: {
                    url: RUTA + "empresa/info?token_=" + TOKEN,
                    method: "GET",
                    dataSrc: "response",
                },
                columns: [{
                        "data": null,
                        render: function() {
                            return `
                    <div class='row'>
                        <div class='col-xl-5 col-lg-3 col-md-5 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-1 mx-xl-1 mx-lg-1 mx-md-1 mx-0'>
                         <button class='btn rounded btn-outline-danger btn-sm' id='delete_clinica'><i class='bx bx-x'></i></button>    
                        </div>
                        <div class='col-xl-4 col-lg-3 col-md-4 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-1'>
                         <button class='btn rounded btn-outline-warning btn-sm' id='editar_clinica'><i class='bx bxs-edit-alt'></i></button>    
                        </div>
                         <div class='col-xl-4 col-lg-3 col-md-4 col-12 mt-1 mx-xl-1 mx-lg-1 mx-md-1 mx-0'>
                         <button class='btn rounded btn-outline-primary btn-sm' id='upload_baner'><i class='bx bx-upload'></i></button>    
                        </div>
                         <div class='col-xl-4 col-lg-3 col-md-4 col-12 mt-1 mx-xl-1 mx-lg-1 mx-md-1 mx-0'>
                         <button class='btn rounded btn-outline-success btn-sm' id='red_social'><i class='bx bxl-instagram'></i></i></button>    
                        </div>
                        <div class='col-xl-4 col-lg-3 col-md-4 col-12 mt-1 mx-xl-1 mx-lg-1 mx-md-1 mx-0'>
                         <button class='btn rounded btn-outline-info btn-sm' id='subir_imagen_fondo'><i class='bx bxs-image-add'></i></button>    
                        </div>

                        <div class='col-xl-4 col-lg-3 col-md-4 col-12 mt-1 mx-xl-1 mx-lg-1 mx-md-1 mx-0'>
                         <button class='btn rounded btn-outline-dark btn-sm' id='subir_icono'>
                         <i class='bx bxs-image-alt'></i>   
                         </button>    
                        </div>
                    </div>
                    `;
                        }
                    },
                    {
                        "data": "nombre_empresa"
                    },
                    {
                        "data": "ruc"
                    },
                    {
                        "data": "direccion"
                    },
                    {
                        "data": "telefono"
                    },
                    {"data":"wasap",render:function(wasap){
                        if(wasap == null)
                        {
                            return `<span class='badge bg-danger'>XXX XXX XXX</span>`;
                        }
                        return wasap;
                    }},
                    {"data":"message_wasap",render:function(messagewasap){
                        if(messagewasap == null)
                        {
                            return `<span class='badge bg-danger'>No especifica ningún mensaje....</span>`;
                        }
                        return messagewasap;
                    }},
                    {
                        "data":"simbolo_moneda",render:function(simbolo_moneda)
                        {
                            return  '<span class="badge bg-primary"><b>'+simbolo_moneda+'</b></span>';
                        }
                    },
                    {
                        "data":"iva_valor"
                    },
                    {
                        "data": "pagina_web",
                        render: function(web) {
                            return '<a href="'+web+'" target="_blank"><span class="badge bg-primary">' + web + '</span></a>';
                        }
                    },
                    {
                        "data":"contacto",render:function(contacto){
                          if(contacto == null)
                          {
                            return `<span class='badge bg-danger'>Sin especificar..</span>`;
                          }
                          return contacto;
                          }
                    },
                    {
                        "data":"quienes_son",render:function(quienes_son){
                            if(quienes_son == null)
                            {
                                return `<span class='badge bg-danger'>Sin especificar..</span>`;  
                            }
                            return quienes_son;
                        }
                    },
                    {
                        "data":"mision",render:function(mision){
                            if(mision == null)
                            {
                                return `<span class='badge bg-danger'>Sin especificar..</span>`;  
                            }
                            return mision;
                        }
                    },
                    {
                        "data":"vision",render:function(vision){
                            if(vision == null)
                            {
                                return `<span class='badge bg-danger'>Sin especificar..</span>`;  
                            }
                            return vision;
                        }
                    },
                    {
                        "data": "logo",
                        render: function(logo) {
                            if (logo == null) {
                                rutaImg = "img/default.png";
                            } else {
                                rutaImg = "empresa/" + logo;
                            }
                            return '<img src="{{ $this->asset("'+rutaImg+'") }}" style="width:60px;height:60px;border-radius:50%">';
                        }
                    }
                ]
            }).ajax.reload();
           
        }

        /// SUBIR ICONO DE LA EMPRESA
        function uploadIcono(Tabla,Tbody){
            $(Tbody).on('click','#subir_icono',function(){
                let fila = $(this).parents("tr");

                if(fila.hasClass("child")){
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();

                EMPRESAID = Data.id_empresa_data;
               
                $('#ventana_subir_icono').modal("show")
            });
        }

        /// subir una imagen de fondo
         
         function SubirImagenDeFondoReportes(Tabla, Tbody) {
            $(Tbody).on('click', '#subir_imagen_fondo', function() {
                /// obtenemos la fila seleccionada
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();

                ID_CLINICA_ = Data.id_empresa_data;

                $('#modal_upload_imagen_fondo').modal("show");

                let imgFondo = Data.img_fondo !== null ? "{{$this->asset('empresa/')}}"+Data.img_fondo:"{{$this->asset('img/default.png')}}";
                $('#img_preview_fondo').attr("src",imgFondo)
                 
            });
        }
        /// eliminar los datos de la clínica
        function ConfirmdeleteClinica(Tabla, Tbody) {
            $(Tbody).on('click', '#delete_clinica', function() {
                /// obtenemos la fila seleccionada
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();

                ID_CLINICA_ = Data.id_empresa_data;

                Swal.fire({
                    title: "Estas seguro de eliminar a la "+((PROFILE_ === 'admin_general' || PROFILE_ === 'Director' || PROFILE_ === 'Médico')?'Clínica':'Farmacia')+" registrada?",
                    text: "Al aceptar, se borrará definitivamente los datos de tu "+((PROFILE_ === 'admin_general' || PROFILE_ === 'Director' || PROFILE_ === 'Médico')?'Clínica':'Farmacia')+"!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, eliminar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                     eliminarClinica(ID_CLINICA_);   
                    }
                });
            });
        }

        /// upload Banner (subir)
        function UploadBanner(Tabla, Tbody) {
            $(Tbody).on('click', '#upload_baner', function() {
                /// obtenemos la fila seleccionada
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();

                ID_CLINICA_ = Data.id_empresa_data;
                if(Data.imagen_banner != null){
                    $('#imagen_banner').attr("src","public/asset/empresa/"+Data.imagen_banner);
                    $('#upload_save_baner').show();
                } else{
                    $('#imagen_banner').attr("src","{{$this->asset('img/icons/unicons/galeria.png')}}")
                }
                $('#video').val(Data.video_url);
                $('#modal_upload_banner').modal("show");
                
            });
        }

        /// ASIGNAR REDES SOCIALES
        function AsignarRedesSociales(Tabla, Tbody) {
            $(Tbody).on('click', '#red_social', function() {
                /// obtenemos la fila seleccionada
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();

                $('#tabla_full_red_data').show();
                $('#tabla_full_redes_sociales').hide();
                $('#modal_asignar_redes_sociales').modal("show");
                $('#asignar_redes').show();
                $('#todo').prop("checked",false);
                showRedesSocialesHabilitadas()
                
            });
        }
        /// proceso para eliminar la clinica
        function eliminarClinica(id)
        {
            $.ajax({
                url:RUTA+"clinica/delete/"+id,
                method:"POST",
                data:{
                    token_:TOKEN,
                },
                success:function(response){
                    response = JSON.parse(response);

                    if(response.response === 'ok')
                    {
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Datos de la "+((PROFILE_ === 'admin_general' || PROFILE_ === 'Director' || PROFILE_ === 'Médico')?'Clínica':'Farmacia')+", eliminados correctamente.",
                            icon:"success"
                        }).then(function(){
                            MostrarDataEmpresa();
                        });
                    }else{
                        Swal.fire({
                        title:"Mensaje del sistema!",
                        text:"Error al intentar eliminar la "+((PROFILE_ === 'admin_general' || PROFILE_ === 'Director' || PROFILE_ === 'Médico')?'Clínica':'Farmacia')+".",
                        icon:"error"
                    });
                    }
                },error:function(err)
                {
                    Swal.fire({
                        title:"Mensaje del sistema!",
                        text:"Error al intentar eliminar la "+((PROFILE_ === 'admin_general' || PROFILE_ === 'Director' || PROFILE_ === 'Médico')?'Clínica':'Farmacia')+".",
                        icon:"error"
                    });
                }
            });
        }
        /// editar los datos de la clìnica
        function editarClinica(Tabla,Tbody)
        {
         $(Tbody).on('click','#editar_clinica',function(){
             /// obtenemos la fila seleccionada
             let fila = $(this).parents('tr');

             /// verificamos para tablas responsivas
             if(fila.hasClass("child"))
             {
                fila = fila.prev();
             }

             let Data = Tabla.row(fila).data();
             
             /// enviamos los datos en los inputs para editar
             CLINICA_ID_DATA = Data.id_empresa_data;
             //subidaScroll('.busines,html', 400);
             $('#save_clinica').hide();
             $('#update_clinica').show();
             $('#name_clinica').val(Data.nombre_empresa);
             $('#ruc_clinica').val(Data.ruc);
             $("#direccion").val(Data.direccion);
             $('#phone_clinica').val(Data.telefono);
             $('#wasap').val(Data.wasap);
             $('#message_wasap').val(Data.message_wasap);
             $('#paginaweb_clinica').val(Data.pagina_web);
             $('#simbolo_moneda').val(Data.simbolo_moneda);
             $('#valor_iva').val(Data.iva_valor);
             $('#email_clinica').val(Data.contacto);
             $('#historia').val(Data.quienes_son);
             $('#mision').val(Data.mision);
             $('#vision').val(Data.vision);
             $('#name_clinica').focus();
             $('#mapa').val(Data.mapa_url);

             let logo_ = Data.logo == null ? "img/default.png": "empresa/"+Data.logo;
              $('#logo_').attr("src","{{$this->asset("")}}"+logo_);
              
         });
        }

        /// actualizar datos de la clínica
        function updateClinicaData(id) {
            let FormUpdateClinica = new FormData(document.getElementById('form_clinica_save'));
            $.ajax({
                url: RUTA + "clinica/update/"+id,
                method: "POST",
                data: FormUpdateClinica,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.response === 'ok') {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Los datos de tu "+((PROFILE_ === 'admin_general' || PROFILE_ === 'Director' || PROFILE_ === 'Médico')?'Clínica':'Farmacia')+" se han modificado sin problemas 😎😁",
                            icon: "success"
                        }).then(function() {
                            $('#name_clinica').val("");
                            $('#ruc_clinica').val("");
                            $('#direccion').val("");
                            $('#phone_clinica').val("");
                            $('#paginaweb_clinica').val("");
                            $('#email_clinica').val("");
                            $('#wasap').val("");
                            $('#logo_clinica').val("");
                            $('#historia').val("");
                            $('#mision').val("");
                            $('#vision').val("");
                            $('#simbolo_moneda').val("");
                            $('#valor_iva').val("");
                            $('#mapa').val("");
                            $('#logo_').attr("src", "{{ $this->asset('img/default.png') }}");
                            $('#update_clinica').hide();
                            $('#save_clinica').show();
                            MostrarDataEmpresa();
                        });
                    }
                }
            });
        }

        /// mostrar las redes sociales habilitadas
        function showRedesSocialesHabilitadas()
        {
            let tr = '';let item = 0;
            $.ajax({
                url:RUTA+"redes-sociales-habilitadas",
                method:"GET",
                dataType:"json",
                success:function(response){
                    
                    if(response.redes_sociales_habilitadas.length > 0){
                        response.redes_sociales_habilitadas.forEach(red => {
                            
                            tr+=`<tr>
                              <td><input type='checkbox' id='selec_red' style='width:30px;height:30px;cursor:pointer' value='`+red.id_red_social+`'></td>
                              <td><b>`+red.nombre_red_social.toUpperCase()+`<i class='`+red.icono+`'></i></b></td>
                              <td><input type='text' id='link' class='form-control' disabled placeholder='Link de `+red.nombre_red_social.toUpperCase()+`....'></td> 
                             </tr>`;
                        }); 

                        tr+=`<tr>
                            <td colspan=3>
                             <a href='#'>Ver mis redes sociales</a>    
                            </td>
                            </tr>`;
                        
                    }else{
                        tr+=`<tr><td colspan='3'> <span class='text-danger'>No hay redes sociales....</span></td></tr>
                          <tr>
                            <td colspan=3>
                             <a href='#'>Ver mis redes sociales</a>    
                            </td>
                            </tr>;`;
                    }
                     

                    $('#tabla_red_data').html(tr);
                }
            })
        }

        /// mostrar las redes sociales asignadas de la clinica
        function showRedesSocialesClinica()
        {
            let tr = '';let item = 0;
            $.ajax({
                url:RUTA+"redes-sociales-clinica",
                method:"GET",
                dataType:"json",
                success:function(response){
                    
                    if(response.redes_sociales.length > 0){
                        response.redes_sociales.forEach(red => {
                            
                            tr+=`<tr>
                              <td class="d-none">`+red.id_red_social_clim+`</td>
                              <td><b>`+red.nombre_red_social.toUpperCase()+`<i class='`+red.icono+`'></i></b></td>
                              <td><input type='text' id='link' class='form-control' disabled placeholder='Link de `+red.nombre_red_social.toUpperCase()+`....' value='`+(red.link_red_social != null ? red.link_red_social:'' )+`'></td> 
                              <td>
                               <button class="btn btn-warning btn-rounded btn-sm" id='editar'><i class='bx bx-edit-alt'></i></button>
                               <button class="btn btn-danger btn-rounded btn-sm" id='borrar'><i class='bx bx-trash'></i></button>  
                               <button onClick='redirigir("`+red.link_red_social+`")' class="btn btn-primary btn-rounded btn-sm"><i class='bx bx-subdirectory-right'></i></button>
                               </td>
                              </tr>`;
                       
                        }); 

                        tr+=`<tr>
                            <td colspan=3>
                             <a href='#'>Asignar nuevas redes sociales</a>    
                            </td>
                            </tr>`;
                        
                    }else{
                       tr+=`
                        <tr>
                            <td colspan='3'> <span class='text-danger'>No hay redes sociales....</span></td>
                        </tr>
                        <tr>
                            <td colspan=3>
                                <a href='#'>Asignar nuevas redes sociales</a>
                            </td>
                        </tr>
                        `;
                    }
                     

                    $('#tabla_redes_sociales').html(tr);
                }
            })
        }

        function redirigir(links){
            window.open(links,"_blank")
        }

        /// activar red social asignacion
        function ActiveRedSocial(){
            $('#tabla_red_data').on('click','#selec_red',function(){
                let fila = $(this).parents('tr');
                let check = $(this).is(":checked");
                if(check){
                    fila.find('#link').attr("disabled",false);
                    fila.find('#link').focus();
                }else{
                    fila.find('#link').attr("disabled",true);
                }
            });
        }

        /// método para asignar las redes sociales
        function AsignarRedSocial_Clinica(){
            let Respuesta;
            $('#tabla_red_data input[type=checkbox]').each(function(){
                let fila = $(this).parents('tr');
                if($(this).is(":checked")){
                    let red_social = fila.find('td').eq(1).text();
                    let IdRedSocial_ = $(this).val();
                    let Link = fila.find('#link').val();
                    Respuesta = AsignarRedSocialClinica(IdRedSocial_,Link);
                }
            });

            if(Respuesta.error != undefined){
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error, token-invalid!",
                    icon:"error",
                    target:document.getElementById('modal_asignar_redes_sociales')
                }) 
            }else{
                if(Respuesta.response === 'ok'){
                    Swal.fire({
                        title:"MENSAJE DEL SISTEMA!",
                        text:"redes sociales asignados correctamente!",
                        icon:"success",
                        target:document.getElementById('modal_asignar_redes_sociales')
                    }).then(function(){
                    showRedesSocialesHabilitadas();
                })
                }
            }
        }

        /// Método para asignar las redes sociales a la clínica
        function AsignarRedSocialClinica(idRed,link){

            let Mensaje = '';
            $.ajax({
                url:RUTA+"asignarRedSocialClinica",
                method:"POST",
                data:{
                    token_:TOKEN,
                    red:idRed,
                    link:link

                },
                dataType:"json",
                async:false,
                success:function(response){
                     Mensaje = response;
                }
            });

            return Mensaje;
        }

        function CantidadRedesSeleccionados(){
            let contador = 0;
            $('#tabla_red_data input[type=checkbox]').each(function(){
                if($(this).is(":checked")){
                    contador++;
                }
            });
           return contador;
        }
    </script>
@endsection
