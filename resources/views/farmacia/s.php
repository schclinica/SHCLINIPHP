@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Farmacia')
@section('clase_ocultar', 'd-none')
@section('expandir', 'layout-content-navbar layout-without-menu')

@section('css')
    <style>
        .card {
            background-image: radial-gradient(circle at 40.37% 43.26%, #ffffff 0, #d5e5f0 50%, #acc7da 100%);
        }

        #lista_tipo_productos>thead>tr>th {
            background-image: radial-gradient(circle at 60.89% 34.45%, #00dbf7 0, #29bfdc 25%, #38a2bd 50%, #3e869d 75%, #3e6d80 100%);
            color: white;
        }
        #lista_presentacion>thead>tr>th {
            background-image: radial-gradient(circle at 60.89% 34.45%, #00dbf7 0, #29bfdc 25%, #38a2bd 50%, #3e869d 75%, #3e6d80 100%);
            color: white;
        }
        #lista_laboratorios>thead>tr>th {
            background-image: radial-gradient(circle at 60.89% 34.45%, #00dbf7 0, #29bfdc 25%, #38a2bd 50%, #3e869d 75%, #3e6d80 100%);
            color: white;
        }
        #lista_grupo_terapeuticos>thead>tr>th {
            background-image: radial-gradient(circle at 60.89% 34.45%, #00dbf7 0, #29bfdc 25%, #38a2bd 50%, #3e869d 75%, #3e6d80 100%);
            color: white;
        }
        #lista_embalajes>thead>tr>th {
            background-image: radial-gradient(circle at 60.89% 34.45%, #00dbf7 0, #29bfdc 25%, #38a2bd 50%, #3e869d 75%, #3e6d80 100%);
            color: white;
        }
        #lista_proveedores>thead>tr>th {
            background-image: radial-gradient(circle at 60.89% 34.45%, #00dbf7 0, #29bfdc 25%, #38a2bd 50%, #3e869d 75%, #3e6d80 100%);
            color: white;
        }
        #fecha_vencimiento{
            font-family: Arial, Helvetica, sans-serif;
            background-color: aqua;
        }
        #lista_productos>thead>tr>th {
            background-image: radial-gradient(circle at 60.89% 34.45%, #00dbf7 0, #29bfdc 25%, #38a2bd 50%, #3e869d 75%, #3e6d80 100%);
            color: white;
        }
    </style>
@endsection
@section('contenido')
    <div class="card" id="farmacia_card">
        <div class="card-header bg-primary">
            <h5 class="text-white float-start">Gestión de la farmacia</h5>
            <a href="{{ $this->route('dashboard') }}" class="btn btn-warning rounded btn-sm float-end">Volver <i
                    class='bx bx-arrow-back'></i></a>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs card-header-tabs" id="tab_farmacia">
                @if ($this->profile()->rol === 'Director')
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="true" href="#tipo_producto" id="tipo_producto_">Tipo
                            producto
                            <img src="{{ $this->asset('img/icons/unicons/categoria_farmacia.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#presentacion" id="presentacion_">Presentación
                            <img src="{{ $this->asset('img/icons/unicons/presentacion_farmacia.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#laboratorio" tabindex="-1" aria-disabled="true"
                            id="laboratorios_">Laboratorios
                            <img src="{{ $this->asset('img/icons/unicons/laboratorio_farmacia.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#grupos" tabindex="-1" aria-disabled="true"
                            id="grupos_">Grupos terapeúticos
                            <img src="{{ $this->asset('img/icons/unicons/grupo.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#embalaje" tabindex="-1" aria-disabled="true"
                            id="embalaje_">Embalajes o empaques
                            <img src="{{ $this->asset('img/icons/unicons/embalaje.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#proveedores" tabindex="-1" aria-disabled="true"
                            id="proveedores_">Proveedores
                            <img src="{{ $this->asset('img/icons/unicons/proveedores.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#productos" tabindex="-1" aria-disabled="true"
                            id="productos_">Productos
                            <img src="{{ $this->asset('img/icons/unicons/productos_farmacia.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#compras" tabindex="-1" aria-disabled="true"
                            id="compras_">Compras
                            <img src="{{ $this->asset('img/icons/unicons/compras.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>
                     
                @endif
                @if ($this->profile()->rol === 'Farmacia')
                    <li class="nav-item active">
                        <a class="nav-link" href="#ventas" tabindex="-1" aria-disabled="true" id="save_ventas_">Registrar
                            venta
                            <img src="{{ $this->asset('img/icons/unicons/venta_farmacia.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#clientes" tabindex="-1" aria-disabled="true" id="clientes_">Clientes
                            <img src="{{ $this->asset('img/icons/unicons/clientes_farmacia.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#historia_ventas" tabindex="-1" aria-disabled="true"
                            id="historia_ventas_">Historia de las
                            ventas <img src="{{ $this->asset('img/icons/unicons/historia_ventas.ico') }}" class="menu-icon"
                                alt=""></a>
                    </li>
                @endif
            </ul>

            {{-- Opciones del tab-- --}}
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade @if ($this->profile()->rol === 'Director') show active @endif" id="tipo_producto"
                    role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <form action="" method="post" id="form_tipo_producto">
                        <input type="hidden" name="_token" value="{{ $this->Csrf_Token() }}">
                        <div class="form-group">
                            <label for="name_tipo_producto"><b>Nombre tipo producto <span
                                        class="text-danger">*</span></b></label>
                            <div class="input-group">
                                <input type="text" name="name_tipo_producto" id="name_tipo_producto" class="form-control"
                                    placeholder="Escriba aquí..." autofocus>
                                <button class="btn btn-outline-success" id="save_tipo_producto"><i
                                        class='bx bx-save'></i></button>
                            </div>
                        </div>
                    </form>

                    <div class="card mt-2 card_tipo">
                        <div class="card-body">
                            <div class="card-text">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped responsive nowrap"
                                        id="lista_tipo_productos">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tipo producto</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="presentacion" role="tabpanel" aria-labelledby="profile-tab">
                    <br>
                    <form action="" method="post" id="form_presentacion">
                        <input type="hidden" name="_token" value="{{ $this->Csrf_Token() }}">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-7 col-12">
                                <div class="form-group">
                                    <label for="name_presentacion"><b>Nombre presentación<span
                                                class="text-danger">*</span></b></label>
                                        <input type="text" name="name_presentacion" id="name_presentacion" class="form-control"
                                         placeholder="Escriba aquí..." autofocus>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-12">
                                <div class="form-group">
                                    <label for="name_corto_presentacion"><b>Nombre corto presentación<span
                                                class="text-danger">*</span></b></label>
                                    <div class="input-group">
                                        <input type="text" name="name_corto_presentacion" id="name_corto_presentacion" class="form-control"
                                         placeholder="Escriba aquí..." autofocus>
                                         <button class="btn btn-outline-success rounded" id="save_presentacion">
                                            <i class='bx bx-save'></i>
                                         </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form> 
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped nowrap responsive" id="lista_presentacion" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre presentación</th>
                                            <th>Nombre corto presentación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="laboratorio" role="tabpanel" aria-labelledby="contact-tab">
                    <br>
                    <form action="" method="post" id="form_laboratorio">
                        <input type="hidden" name="_token" value="{{ $this->Csrf_Token() }}">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name_laboratorio"><b>Nombre laboratorio<span
                                                class="text-danger">*</span></b></label>
                                        <input type="text" name="name_laboratorio" id="name_laboratorio" class="form-control"
                                         placeholder="Escriba aquí..." autofocus>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="direccion_laboratorio"><b>Dirección </b></label>
                                     <textarea name="direccion_laboratorio" id="direccion_laboratorio" class="form-control" cols="30" rows="2" placeholder="Escriba aquí..."></textarea>
                                </div>
                            </div>
                            <div class="col-12 text-center mt-3 mb-2">
                                <button class="btn_info_tw" id="save_laboratorio">Guardar <i class='bx bx-save'></i></button>
                            </div>
                        </div>
                    </form> 
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped nowrap responsive" id="lista_laboratorios" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre laboratorio</th>
                                            <th>Dirección</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="grupos" role="tabpanel" aria-labelledby="contact-tab">
                    <br>
                    <form action="" method="post" id="form_grupos">
                        <input type="hidden" name="_token" value="{{ $this->Csrf_Token() }}">
                        <div class="form-group">
                            <label for="name_grupo"><b>Nombre grupo <span
                                        class="text-danger">*</span></b></label>
                            <div class="input-group">
                                <input type="text" name="name_grupo" id="name_grupo" class="form-control"
                                    placeholder="Escriba aquí..." autofocus>
                                <button class="btn btn-outline-success" id="save_grupo"><i
                                        class='bx bx-save'></i></button>
                            </div>
                        </div>
                    </form>

                    <div class="card mt-2 card_tipo">
                        <div class="card-body">
                            <div class="card-text">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped responsive nowrap"
                                        id="lista_grupo_terapeuticos" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Grupo terapeútico</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="tab-pane fade" id="embalaje" role="tabpanel" aria-labelledby="contact-tab">
                   <br>
                   <form action="" method="post" id="form_empaque">
                    <input type="hidden" name="_token" value="{{$this->Csrf_Token()}}">
                     <div class="form-group">
                        <label for="name_embalaje"><b>Nombre embalaje <span class="text-danger">*</span></b></label>
                        <div class="input-group">
                            <input type="text" name="name_embalaje" id="name_embalaje" class="form-control" placeholder="Escriba aquí....">
                            <button class="btn btn-outline-success" id="save_embalaje"><i
                                class='bx bx-save'></i></button></button>
                        </div>
                     </div>
                   </form>
                   <div class="mt-3 card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped nowrap responsive" id="lista_embalajes" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre empaque</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            </table>
                        </div>
                    </div>
                   </div>
                </div>
                <div class="tab-pane fade" id="proveedores" role="tabpanel" aria-labelledby="contact-tab">
                  <br>
                  <form action="" method="post" id="form_proveedores">
                    <input type="hidden" name="_token" value="{{$this->Csrf_Token()}}">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <label for="proveedor_name"><b>Nombre proveedor <span class="text-danger">*</span></b></label>
                                <input type="text" name="proveedor_name" id="proveedor_name" class="form-control" placeholder="Escriba aquí...">
                            </div>
                           </div>
                           <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <label for="contacto_name"><b>Nombre contacto <span class="text-danger">*</span></b></label>
                                <input type="text" name="contacto_name" id="contacto_name" class="form-control" placeholder="Escriba aquí...">
                            </div>
                           </div>
    
                           <div class="col-xl-3 col-lg-3 col-md-4 col-12">
                            <div class="form-group">
                                <label for="telefono"><b>Teléfono </b></label>
                                <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Escriba aquí...">
                            </div>
                           </div>
    
                           <div class="col-xl-9 col-lg-9 col-md-8 col-12">
                            <div class="form-group">
                                <label for="correo"><b>Correo electrónico  </b></label>
                                <input type="text" name="correo" id="correo" class="form-control" placeholder="Escriba aquí...">
                            </div>
                           </div>
    
                           <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                            <div class="form-group">
                                <label for="direccion"><b>Dirección  </b></label>
                                <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Escriba aquí...">
                            </div>
                           </div>
    
                           <div class="col-xl-7 col-lg-7 col-md-6 col-12">
                            <div class="form-group">
                                <label for="paginaweb"><b>Página web  </b></label>
                                <input type="text" name="paginaweb" id="paginaweb" class="form-control" placeholder="Escriba aquí...">
                            </div>
                           </div>
                           <div class="col-12 text-center mt-3 mb-2">
                            <button class="btn_info_tw" id="save_proveedor">Guardar <i class='bx bx-save'></i></button>
                        </div>
                    </div>
                </form>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped nowrap responsive" id="lista_proveedores" style="width: 100%">
                                 <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Proveedor</th>
                                        <th>Contacto</th>
                                        <th>Teléfono</th>
                                        <th>Correo</th>
                                        <th>Dirección</th>
                                        <th>Página web</th>
                                        <th>Acciones</th>
                                    </tr>
                                 </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="tab-pane fade" id="productos" role="tabpanel" aria-labelledby="contact-tab">
                   <br>
                   <form action="" method="post" id="form_productos">
                    <input type="hidden" name="_token" value="{{$this->Csrf_Token()}}">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                            <label for="nombre_producto"><b>Nombre producto <span class="text-danger">*</span></b></label>
                            <input type="text" name="nombre_producto" id="nombre_producto" class="form-control" placeholder="Escriba aquí....">
                        </div>
                         <div class="col-xl-2 col-lg-3 col-md-3 col-sm-6 col-12">
                            <label for="precio_venta"><b>Precio de venta <span class="text-danger">*</span></b></label>
                            <input type="text" name="precio_venta" id="precio_venta" class="form-control" placeholder="Escriba aquí....">
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-5 col-sm-6 col-12">
                            <label for="stock"><b>Stock <span class="text-danger">*</span></b></label>
                            <input type="number" name="stock" id="stock" class="form-control" placeholder="0" min="0" value="0" >
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-12 col-sm-6 col-12">
                            <label for="stock_minimo"><b>Stock Mínimo<span class="text-danger">*</span></b></label>
                            <input type="number" name="stock_minimo" id="stock_minimo" class="form-control" placeholder="0" min="0" value="0" >
                        </div>
                        <div class="col-xl-3 col-12 col-md-12 col-sm-6 col-12">
                            <label for="fecha_vencimiento"><b>Fecha de vencimiento</b></label>
                            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control"  min="{{$this->FechaActual("Y-m-d")}}"
                            value="{{$this->FechaActual("Y-m-d")}}">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <label for="tipo_select"><b>Tipo<span class="text-danger">*</span></b></label>
                             <select name="tipo_select" id="tipo_select" class="form-select"> 
                             </select>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6  col-12">
                            <label for="presentacion_select"><b>Presentación<span class="text-danger">*</span></b></label>
                             <select name="presentacion_select" id="presentacion_select" class="form-select"> 
                             </select>
                        </div>
                        <div class="col-xl-5 col-lg-5 col-12">
                            <label for="laboratorio_select"><b>Laboratorio<span class="text-danger">*</span></b></label>
                             <select name="laboratorio_select" id="laboratorio_select" class="form-select"> 
                             </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <label for="grupo_select"><b>Grupo terapeútico<span class="text-danger">*</span></b></label>
                             <select name="grupo_select" id="grupo_select" class="form-select"> 
                             </select>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <label for="embalaje_select"><b>Empaque<span class="text-danger">*</span></b></label>
                             <select name="embalaje_select" id="embalaje_select" class="form-select"> 
                             </select>
                        </div>
                        <div class="col-xl-5 col-lg-5 col-12">
                            <label for="proveedor_select"><b>Proveedor<span class="text-danger">*</span></b></label>
                             <select name="proveedor_select" id="proveedor_select" class="form-select"> 
                             </select>
                        </div>
                        <div class="mt-3 text-center">
                            <button class="btn_info_tw" id="save_producto"><b>Registrar <i class='bx bx-save'></i></b></button>
                        </div>

                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped nowrap responsive" id="lista_productos" style="width: 100%">
                                      <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Producto</th>
                                            <th>Precio {{count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda:'S/.'}}</th>
                                            <th>Stock</th>
                                            <th>Stock Mínimo</th>
                                            <th>Fecha de vencimiento</th>
                                            <th>Tipo</th>
                                            <th>Presentación</th>
                                            <th>Laboratorio</th>
                                            <th>Grupo terapeútico</th>
                                            <th>Empaque</th>
                                            <th>Proveedor</th>
                                            <th>Acciones</th>
                                        </tr>
                                      </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                   </form>
                </div>
                <div class="tab-pane fade" id="compras" role="tabpanel" aria-labelledby="contact-tab">
                    <h4>Compras</h4>
                </div>
                <div class="tab-pane fade" id="clientes" role="tabpanel" aria-labelledby="contact-tab">
                    <h4>Clientes</h4>
                </div>
                <div class="tab-pane fade  @if ($this->profile()->rol === 'Farmacia') show active @endif" id="ventas"
                    role="tabpanel" aria-labelledby="contact-tab">
                    <h4>Ventas</h4>
                </div>
                <div class="tab-pane fade" id="historia_ventas" role="tabpanel" aria-labelledby="contact-tab">
                    <h4>Hstoria de las ventas</h4>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL_BASE }}public/js/control.js"></script>
    <script src="{{ URL_BASE }}public/js/presentacion.js"></script>
    <script src="{{ URL_BASE }}public/js/laboratorio.js"></script>
    <script src="{{ URL_BASE }}public/js/grupos.js"></script>
    <script src="{{ URL_BASE }}public/js/empaques.js"></script>
    <script src="{{ URL_BASE }}public/js/proveedor.js"></script>
    <script src="{{ URL_BASE }}public/js/productofarmacia.js"></script>
    <script>
        var RUTA = "{{ URL_BASE }}" // la url base del sistema
        var TOKEN = "{{ $this->Csrf_Token() }}";
        var PROFILE_ = "{{ $this->profile()->rol }}";
        var TablaListaTipoProductos,TablaListaEmpaques;
        var TablaListaPresentacion,TablaListaLaboratorios,TablaListaGrupos,TablaListaProveedores,
        TablaListaProductos;
        var IDTIPOPRODUCTO,IDPRESENTACION,IDLABORATORIO,IDGRUPO,IDEMBALAJE,IDPROVEEDOR,IDPRODUCTO;
        var ControlBotonTipo = 'save',ControlBotonPresnetacion = 'save',ControlButtonLaboratorio= 'save',
        ControlBotonGrupo = 'save',ControlBotonEmbalaje = 'save',ControlBotonProveedor = 'save',
        ControlBotonProducto = 'save';
        var MONEDA ="{{count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda:'S/.'}}";
        $(document).ready(function() {
            let NameTipoProducto = $('#name_tipo_producto');
            let NamePresentacion = $('#name_presentacion');
            let NameCortoPresentacion = $('#name_corto_presentacion');
            let NameLaboratorio = $('#name_laboratorio');
            let DireccionLaboratorio = $('#direccion_laboratorio');
            let NameGrupoTerapeutico = $('#name_grupo');
            let NameEmbalaje = $('#name_embalaje');
            let ProveedorName = $('#proveedor_name');
            let ProveedorContacto = $('#contacto_name');
            let NameProducto = $('#nombre_producto');
            let PrecioVentaProducto = $('#precio_venta'); let Stock = $('#stock');
            let FechaVencimiento = $('#fecha_vencimiento');let TipoProducto = $('#tipo_select');
            let PresentacionProducto = $('#presentacion_select'); let LaboratorioProducto = $('#laboratorio_select');
            let GrupoProducto = $('#grupo_select');let EmbalajeProducto = $('#embalaje_select');
            let ProveedorProducto = $('#proveedor_select');let StockMinimo = $('#stock_minimo');


            /// mostramos la vista de la pestaña por defecto
            mostrarTipoProductos();
            /** Editar tipo de productos **/
            EditarTipoProducto(TablaListaTipoProductos, '#lista_tipo_productos tbody');
            /** Eliminar tipo de producto */ 
            EliminarTipoProducto(TablaListaTipoProductos,'#lista_tipo_productos tbody');
            /// activar nuevamente el tipo de producto
            ActivarTipoProducto(TablaListaTipoProductos,'#lista_tipo_productos tbody');

            /** Eliminar por completo el tipo de productos**/ 
            DeleteConfirmTipoProducto(TablaListaTipoProductos,'#lista_tipo_productos tbody');

            $('#tab_farmacia a').on('click', function(evento) {
                evento.preventDefault();
                const idTab = $(this)[0].id;
                if (idTab === 'tipo_producto_') {
                    ControlBotonTipo = 'save';
                    mostrarTipoProductos();
                }else{
                    if(idTab === 'presentacion_')
                    {
                        ControlBotonPresnetacion = 'save';
                        showPresentaciones();
                        /*Editar la presentación*/
                        editarPresentacion(TablaListaPresentacion,'#lista_presentacion tbody');
                        /*Eliminar la presentación*/
                        Eliminar(TablaListaPresentacion,'#lista_presentacion tbody');
                        /*Activar la presentación*/
                        Activar(TablaListaPresentacion,'#lista_presentacion tbody');
                        /** Eliminar por completo a la presentación **/ 
                        DeleteConfirmPresentacion(TablaListaPresentacion,'#lista_presentacion tbody');
                    }else{
                        if(idTab === 'laboratorios_')
                        {
                            ControlButtonLaboratorio = 'save';
                            showLaboratorios();
                            /** Editar laboratorio */ 
                            editarLaboratorio(TablaListaLaboratorios,'#lista_laboratorios tbody');
                            /** Eliminar laboratorios*/ 
                            EliminarLaboratorio(TablaListaLaboratorios,'#lista_laboratorios tbody');
                            /** Activar laboratorio*/ 
                            ActivarLaboratorio(TablaListaLaboratorios,'#lista_laboratorios tbody');
                            /*Eliminar por completo de la base de datos a laboratorios*/
                            DeleteConfirmLaboratorio(TablaListaLaboratorios,'#lista_laboratorios tbody') ;
                        }else{
                            if(idTab === 'grupos_')
                            {
                                ControlBotonGrupo = 'save';
                                mostrarGruposTerapeuticos();

                                /** Editar Grupo terapeutico**/ 
                                EditarGrupo(TablaListaGrupos,'#lista_grupo_terapeuticos tbody');
                                /*Eliminar grupo terapeutico*/
                                ConfirmEliminadoGrupo(TablaListaGrupos,'#lista_grupo_terapeuticos tbody');
                                /// activar grupo terapeútico
                                ActivateGrupo(TablaListaGrupos,'#lista_grupo_terapeuticos tbody');
                                /*Confirmar eliminado de los grupos terapeuticos*/
                                DeleteConfirmGrupos(TablaListaGrupos,'#lista_grupo_terapeuticos tbody');
                            }else{
                                if(idTab === 'embalaje_')
                                {
                                    ControlBotonEmbalaje = 'save';
                                    mostrarEmpaques();
                                    /** Editar embalaje **/
                                    EditarEmpaque(TablaListaEmpaques,'#lista_embalajes tbody');
                                    /** Confirma eliminado del empaque**/ 
                                    ConfirmEliminadoGrupo(TablaListaEmpaques,'#lista_embalajes tbody');
                                    /*Actiavar empaques*/
                                    ActivateEmpaque(TablaListaEmpaques,'#lista_embalajes tbody');
                                    /** Confirmar eliminado de empaques o embalajes*/ 
                                    DeleteConfirmEmbalajes(TablaListaEmpaques,'#lista_embalajes tbody');
                                }else{
                                    if(idTab === 'proveedores_')
                                    {
                                        $('#proveedor_name').val("");$('#contacto_name').val("");
                                        $('#telefono').val("");$('#correo').val("");$('#direccion').val("");
                                        $('#paginaweb').val("");
                                        ControlBotonProveedor = 'save';
                                        mostrarProveedores();
                                        /** Editar proveedores**/ 
                                        EditarProveedor(TablaListaProveedores,'#lista_proveedores tbody');
                                        /*Confirmar borrado de proveedores*/
                                        DeleteConfirmProveedor(TablaListaProveedores,'#lista_proveedores tbody');
                                        /** Confirmar eliminado del proveedor*/ 
                                        ConfirmEliminadoProveedor(TablaListaProveedores,'#lista_proveedores tbody');
                                        /*Activar proveedores*/
                                        ActivateProveedor(TablaListaProveedores,'#lista_proveedores tbody');
                                    }else{
                                        if(idTab === 'productos_')
                                        {
                                            ControlBotonProducto = 'save';
                                            showProducto();
                                            loading('#farmacia_card','#4169E1','chasingDots');
                                            setTimeout(() => {
                                                $('#farmacia_card').loadingModal('hide');
                                                $('#farmacia_card').loadingModal('destroy');
                                                /** mostrar en los combos
                                             * 1 mostrar tipo de productos*/
                                             mostrarTipoProductoCombo(); 
                                             //mostrar presentaciones
                                             mostrarPresentacionCombo();
                                             /// mostrar laboratorios
                                             mostrarLaboratoriosCombo();
                                             /// mostrar grupo terapeutico
                                             mostrarGruposTerapeuticosCombo();
                                             /// mostrar embalajes
                                             mostrarEmpaquesCombo();
                                             /** Mostrar proveedores*/ 
                                             mostrarProveedoresCombo();
                                            }, 200);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $(this).tab("show")
            });

            $('#save_tipo_producto').click(function(evento) {
                evento.preventDefault();
                if (NameTipoProducto.val().trim().length == 0) {
                    NameTipoProducto.focus();
                } else {

                    if (ControlBotonTipo === 'save') {
                        saveTipoProducto();
                    } else {
                        UpdateTipoProducto(IDTIPOPRODUCTO);
                    }
                }
            });

            $('#save_presentacion').click(function(evento){
                evento.preventDefault();
                if(NamePresentacion.val().trim().length == 0)
                {
                    NamePresentacion.focus();
                }else{
                    if(NameCortoPresentacion.val().trim().length == 0)
                    {
                        NameCortoPresentacion.focus();
                    }else{
                      if(ControlBotonPresnetacion === 'save')
                      {
                       save('form_presentacion',RUTA);
                      }else{
                       modificar(IDPRESENTACION);
                      }
                    }
                }

            });

            $('#save_laboratorio').click(function(evento){
                evento.preventDefault();
                if(NameLaboratorio.val().trim().length == 0)
                {
                    NameLaboratorio.focus();
                }else{
                    if(ControlButtonLaboratorio === 'save')
                    {
                        saveLaboratorio();
                    }else{
                        updateLaboratorio(IDLABORATORIO);
                    }
                }
            });

            $('#save_grupo').click(function(evento){
                evento.preventDefault();

                if(NameGrupoTerapeutico.val().trim().length == 0)
                {
                    NameGrupoTerapeutico.focus();
                }else{
                    if(ControlBotonGrupo === 'save')
                    {
                        saveGrupo();
                    }else{
                        updateGrupo(IDGRUPO);
                    }
                }
            });
            $('#save_embalaje').click(function(evento){
                evento.preventDefault();
                if(NameEmbalaje.val().trim().length == 0)
                {
                    NameEmbalaje.focus();
                }else{
                    if(ControlBotonEmbalaje === 'save')
                    {
                        saveEmbalaje();
                    }else{
                        updateEmbalaje(IDEMBALAJE);
                    }
                }
            });

            $('#save_proveedor').click(function(evento){
                evento.preventDefault();
                if(ProveedorName.val().trim().length == 0)
                {
                    ProveedorName.focus();
                }else{
                    if(ProveedorContacto.val().trim().length == 0)
                    {
                        ProveedorContacto.focus();
                    }else{
                        if(ControlBotonProveedor === 'save')
                        {
                            saveProveedor();
                        }else{
                            updateProveedor(IDPROVEEDOR);
                        }
                    }
                }
            });

            $('#save_producto').click(function(evento){
                evento.preventDefault();
                   
                    if(NameProducto.val().length == 0)
                    {
                     
                        NameProducto.focus();
                    }else{
                        if(PrecioVentaProducto.val().trim().length == 0)
                        {
                            PrecioVentaProducto.focus();
                        }else{
                            if(Stock.val().trim().length == 0)
                            {
                                Stock.focus();
                            }else{
                               if(StockMinimo.val().trim().length == 0)
                               {
                                StockMinimo.focus();se
                               }el
                            }
                        }
                    }
                 
            });
        });

        function mostrarTipoProductos() {
            TablaListaTipoProductos = $('#lista_tipo_productos').DataTable({
                language: SpanishDataTable(),
                retrieve: true,
                responsive: true,
                processing: true,
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                ajax: {
                    url: RUTA + "app/farmacia/mostrar_tipo_productos/si",
                    method: "GET",
                    dataSrc: "response",
                },
                columns: [{
                        "data": "name_tipo_producto"
                    },
                    {
                        "data": "name_tipo_producto",
                        render: function(tipo) {
                            return tipo.toUpperCase();
                        }
                    },
                    {
                        "data": "deleted_at",
                        render: function(eliminado_at) {
                            if (eliminado_at == null) {
                                return `<button class='btn btn-danger rounded btn-sm' id='eliminar_tipo'><i class='bx bx-x'></i></button>
                <button class='btn btn-warning rounded btn-sm' id='editar_tipo'><i class='bx bx-pencil' ></i></button>
                <button class='btn btn-info rounded btn-sm' id='delete_tipo'><i class='bx bx-trash'></i></button>`;
                            }

                            return ` <button class='btn btn-success rounded btn-sm' id='activar_tipo'><i class='bx bx-check'></i></button>
                            <button class='btn btn-info rounded btn-sm' id='delete_tipo'><i class='bx bx-trash'></i></button>`;
                        }
                    }
                ]
            }).ajax.reload();

            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
            TablaListaTipoProductos.on('order.dt search.dt', function() {
                TablaListaTipoProductos.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }

        /// guardar los tipos de productos de la farmacia
        function saveTipoProducto() {
            $.ajax({
                url: RUTA + "app/farmacia/save_tipo_producto",
                method: "POST",
                data: $('#form_tipo_producto').serialize(),
                dataType: "json",
                success: function(response) {

                    if (response.response == 1) {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Tipo producto registrado correctamente 😁😎😀!",
                            icon: "success"
                        }).then(function() {
                            $('#name_tipo_producto').focus();
                            $('#name_tipo_producto').val("");
                            mostrarTipoProductos();
                           
                        });
                    } else {
                        if (response.response === 'existe') {
                            Swal.fire({
                                title: "Mensaje del sistema!",
                                text: "No se permite duplicidad en tipo de productos !",
                                icon: "warning"
                            }).then(function() {
                                $('#name_tipo_producto').focus();
                                $('#name_tipo_producto').val("");
                            });
                        } else {
                            Swal.fire({
                                title: "Mensaje del sistema!",
                                text: "Error al registrar tipo de producto😔😢 !",
                                icon: "error"
                            });
                        }
                    }
                }
            })
        }

        /// modificar datos de tipo de productos
        function UpdateTipoProducto(id) {
            $.ajax({
                url: RUTA + "app/farmacia/update/" + id,
                method: "POST",
                data: $('#form_tipo_producto').serialize(),
                dataType: "json",
                success: function(response) {

                    if (response.response == 1) {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Tipo producto modificado correctamente 😁😎😀!",
                            icon: "success"
                        }).then(function() {
                            $('#name_tipo_producto').focus();
                            $('#name_tipo_producto').val("");
                            ControlBotonTipo = 'save';
                            mostrarTipoProductos();
                        });
                    } else {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Error al registrar tipo de producto😔😢 !",
                            icon: "error"
                        });
                    }
                }
            })
        }
        /// editar los tipos de productos
        function EditarTipoProducto(Tabla, Tbody) {
            $(Tbody).on('click', '#editar_tipo', function() {
                /// obtenemos la fila seleccionada
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                ControlBotonTipo = 'update';
                let Data = Tabla.row(fila).data();
                IDTIPOPRODUCTO = Data.id_tipo_producto;
                $('#name_tipo_producto').focus();
                $('#name_tipo_producto').val(Data.name_tipo_producto);
                $('#name_tipo_producto').select();
            });
        }

        /// inhabilitar tipos de productos
        function EliminarTipoProducto(Tabla, Tbody) {
            $(Tbody).on('click', '#eliminar_tipo', function() {
                /// obtenemos la fila seleccionada
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                ControlBotonTipo = 'update';
                let Data = Tabla.row(fila).data();
                let TipoProducto = Data.name_tipo_producto;
                IDTIPOPRODUCTO = Data.id_tipo_producto;
                Swal.fire({
                    title: "Estas seguro de eliminar al tipo producto "+TipoProducto+" ?",
                    text: "Al eliminar el tipo de producto se quitará automaticamente de la lista!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, eliminar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        ProcesoHabilitadoInhabilitadoTipoProducto(IDTIPOPRODUCTO,'i');   
                    }
                });
            });
        }
        /// habilitar nuevamente el tipo de producto
        function ActivarTipoProducto(Tabla, Tbody) {
            $(Tbody).on('click', '#activar_tipo', function() {
                /// obtenemos la fila seleccionada
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                ControlBotonTipo = 'update';
                let Data = Tabla.row(fila).data();
                let TipoProducto = Data.name_tipo_producto;
                IDTIPOPRODUCTO = Data.id_tipo_producto;
                 
                ProcesoHabilitadoInhabilitadoTipoProducto(IDTIPOPRODUCTO,'h');    
                 
            });
        }

        // Proceso para habilitar e inhabilitar los tipos de productos
        function ProcesoHabilitadoInhabilitadoTipoProducto(id,condition)
        {
            $.ajax({
                url:RUTA+"app/farmacia/habilitar_e_inhabilitar/tipo_producto/"+id+"/"+condition,
                method:"POST",
                data:{
                    _token:TOKEN
                },
                dataType:"json",
                success:function(response)
                {
                   if(response.response == 1)
                   {
                    Swal.fire({
                        title:"Mensaje del sistema!",
                        text:condition === 'i'?"Tipo producto quitado de la lista correctamente":"Tipo producto activado nuevamente",
                        icon:"success"
                    }).then(function(){
                       mostrarTipoProductos(); 
                    });
                   }else{
                    Swal.fire({
                        title:"Mensaje del sistema!",
                        text:"Error al realizar el proceso de eliminado o activación del tipo de producto",
                        icon:"error"
                    });
                   }
                }
            });
        }
        /** Eliminar tipo de producto*/ 
        function DeleteConfirmTipoProducto(Tabla,Tbody) 
        {
          $(Tbody).on('click','#delete_tipo',function(){
            let fila = $(this).parents('tr');

            if(fila.hasClass('child'))
            {
                fila = fila.prev();
            }

            let Data = Tabla.row(fila).data();

            IDTIPOPRODUCTO = Data.id_tipo_producto;
            Swal.fire({
            title: "Estas seguro de borrar al tipo producto "+Data.name_tipo_producto+"?",
            text: "Al eliminarlo, se borrará de la base de datos por completo!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!"
          }).then((result) => {
            if (result.isConfirmed) {
              DeleteTipoProducto(IDTIPOPRODUCTO);
            }
          });
          });
        }

        /*Eliminar el tipo producto de la base de datos*/
        function DeleteTipoProducto(id)
        {
            $.ajax({
                url:RUTA+"app/farmacia/tipo_producto/delete/"+id,
                method:"POST",
                data:{
                    _token:TOKEN
                },
                dataType:'json',
                success:function(response)
                {
                    if(response.response == 1)
                    {
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Tipo de producto eliminado por completo!",
                            icon:"success"
                        }).then(function(){
                            mostrarTipoProductos();
                        });
                    }else{
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Error al eliminar tipo de producto!",
                            icon:"error"
                        })  
                    }
                }
            });
        }
    </script>
@endsection
