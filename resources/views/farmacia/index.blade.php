@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Farmacia')
@section('clase_ocultar', 'd-none')
@section('expandir', 'layout-content-navbar layout-without-menu')

@section('css')
    <link rel="stylesheet" href="{{$this->asset("css/cssfarmacia.css")}}">
    <style>
        td.hide_me
    {
      display: none;
    }
    #tabla_buscar_proveedores>thead>tr>th{
        background:background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);
    }
    </style>
@endsection
@section('contenido')
    <div class="card" id="farmacia_card">
        <div class="card-header header_card_farmacia" style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
            <h5 class="text-white float-start">Gestión de la farmacia</h5>
            <a href="{{ $this->route('dashboard') }}" class="btn btn-primary rounded btn-sm float-end">Ir a Dashboard<i
                    class='bx bx-arrow-back'></i></a>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs card-header-tabs" id="tab_farmacia">
                @if ($this->profile()->rol === 'admin_general' || $this->profile()->rol === 'admin_farmacia' )
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="true" href="#tipo_producto" id="tipo_producto_"><b>Tipo
                            producto</b>
                            <img src="{{ $this->asset('img/icons/unicons/categoria_farmacia.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#presentacion" id="presentacion_"><b>Presentación</b>
                            <img src="{{ $this->asset('img/icons/unicons/presentacion_farmacia.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#laboratorio" tabindex="-1" aria-disabled="true"
                            id="laboratorios_"><b>Laboratorios</b>
                            <img src="{{ $this->asset('img/icons/unicons/laboratorio_farmacia.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#grupos" tabindex="-1" aria-disabled="true" id="grupos_"><b>Grupos
                            terapeúticos</b>
                            <img src="{{ $this->asset('img/icons/unicons/grupo.ico') }}" class="menu-icon" alt="">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#embalaje" tabindex="-1" aria-disabled="true" id="embalaje_"><b>Marcas</b>
                            <img src="{{ $this->asset('img/icons/unicons/embalaje.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#proveedores" tabindex="-1" aria-disabled="true"
                            id="proveedores_"><b>Proveedores</b>
                            <img src="{{ $this->asset('img/icons/unicons/proveedores.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>
                    @if ($this->profile()->rol === "admin_general")
                       <li class="nav-item">
                            <a class="nav-link" href="#productos" tabindex="-1" aria-disabled="true" id="productos_"><b>Productos</b>
                                <img src="{{ $this->asset('img/icons/unicons/productos_farmacia.ico') }}" class="menu-icon" alt="">
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="#compras" tabindex="-1" aria-disabled="true" id="compras_"><b>Compras</b>
                            <img src="{{ $this->asset('img/icons/unicons/compras.ico') }}" class="menu-icon" alt="">
                        </a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" onclick="location.href='{{URL_BASE}}gestionar-inventario'" style="cursor:pointer" tabindex="-1" aria-disabled="true"><b>Inventarios</b>
                            <img src="{{ $this->asset('img/icons/unicons/inventario.ico') }}" class="menu-icon" alt="">
                        </a>
                    </li>
                @endif
                <!-- modificado --->
                @if ($this->profile()->rol === 'Farmacia' || $this->profile()->rol === 'Director')
                    <li class="nav-item active">
                        <a class="nav-link" href="#ventas" tabindex="-1" aria-disabled="true" id="save_ventas_"><b>Registrar venta</b>
                            <img src="{{ $this->asset('img/icons/unicons/venta_farmacia.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#clientes" tabindex="-1" aria-disabled="true" id="clientes_"><b>Clientes</b>
                            <img src="{{ $this->asset('img/icons/unicons/clientes_farmacia.ico') }}" class="menu-icon"
                                alt="">
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#historia_ventas" tabindex="-1" aria-disabled="true"
                            id="historia_ventas_"><b>Historia de las
                                ventas</b><img src="{{ $this->asset('img/icons/unicons/historia_ventas.ico') }}"
                                class="menu-icon" alt=""></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#reporte_ventas" tabindex="-1" aria-disabled="true"
                            id="reporte_ventas_"><b>Reporte de ventas</b><img src="{{ $this->asset('img/icons/unicons/reporte_venta.ico') }}"
                                class="menu-icon" alt=""></a>
                    </li>
                @endif
                
            </ul>

            {{-- Opciones del tab-- --}}
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade @if ($this->profile()->rol === 'admin_general' || $this->profile()->rol === 'admin_farmacia') show active @endif" id="tipo_producto"
                    role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <form action="" method="post" id="form_tipo_producto">
                        <input type="hidden" name="_token" value="{{ $this->Csrf_Token() }}">
                        <div class="form-group">
                            <label for="name_tipo_producto"><b>Nombre tipo producto <span
                                        class="text-danger">*</span></b></label>
                            <div class="input-group">
                                <input type="text" name="name_tipo_producto" id="name_tipo_producto"
                                    class="form-control" placeholder="Escriba aquí..." autofocus>
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
                                    <input type="text" name="name_presentacion" id="name_presentacion"
                                        class="form-control" placeholder="Escriba aquí..." autofocus>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-12">
                                <div class="form-group">
                                    <label for="name_corto_presentacion"><b>Nombre corto presentación<span
                                                class="text-danger">*</span></b></label>
                                    <div class="input-group">
                                        <input type="text" name="name_corto_presentacion" id="name_corto_presentacion"
                                            class="form-control" placeholder="Escriba aquí..." autofocus>
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
                                <table class="table table-bordered table-striped nowrap responsive"
                                    id="lista_presentacion" style="width: 100%">
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
                                    <input type="text" name="name_laboratorio" id="name_laboratorio"
                                        class="form-control" placeholder="Escriba aquí..." autofocus>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="direccion_laboratorio"><b>Dirección </b></label>
                                    <textarea name="direccion_laboratorio" id="direccion_laboratorio" class="form-control" cols="30"
                                        rows="2" placeholder="Escriba aquí..."></textarea>
                                </div>
                            </div>
                            <div class="col-12 text-center mt-3 mb-2">
                                <button class="btn_info_tw" id="save_laboratorio">Guardar <i
                                        class='bx bx-save'></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped nowrap responsive"
                                    id="lista_laboratorios" style="width: 100%">
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
                            <label for="name_grupo"><b>Nombre grupo <span class="text-danger">*</span></b></label>
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
                        <input type="hidden" name="_token" value="{{ $this->Csrf_Token() }}">
                        <div class="form-group">
                            <label for="name_embalaje"><b>Nombre marca <span class="text-danger">*</span></b></label>
                            <div class="input-group">
                                <input type="text" name="name_embalaje" id="name_embalaje" class="form-control"
                                    placeholder="Escriba aquí....">
                                <button class="btn btn-outline-success" id="save_embalaje"><i
                                        class='bx bx-save'></i></button></button>
                            </div>
                        </div>
                    </form>
                    <div class="mt-3 card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped nowrap responsive" id="lista_embalajes"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre marca</th>
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
                        <input type="hidden" name="_token" value="{{ $this->Csrf_Token() }}">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="proveedor_name"><b>Nombre proveedor <span
                                                class="text-danger">*</span></b></label>
                                    <input type="text" name="proveedor_name" id="proveedor_name" class="form-control"
                                        placeholder="Escriba aquí...">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="contacto_name"><b>Nombre contacto <span
                                                class="text-danger">*</span></b></label>
                                    <input type="text" name="contacto_name" id="contacto_name" class="form-control"
                                        placeholder="Escriba aquí...">
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-3 col-md-4 col-12">
                                <div class="form-group">
                                    <label for="telefono"><b>Teléfono </b></label>
                                    <input type="text" name="telefono" id="telefono" class="form-control"
                                        placeholder="Escriba aquí...">
                                </div>
                            </div>

                            <div class="col-xl-9 col-lg-9 col-md-8 col-12">
                                <div class="form-group">
                                    <label for="correo"><b>Correo electrónico </b></label>
                                    <input type="text" name="correo" id="correo" class="form-control"
                                        placeholder="Escriba aquí...">
                                </div>
                            </div>

                            <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="direccion"><b>Dirección </b></label>
                                    <input type="text" name="direccion" id="direccion" class="form-control"
                                        placeholder="Escriba aquí...">
                                </div>
                            </div>

                            <div class="col-xl-7 col-lg-7 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="paginaweb"><b>Página web </b></label>
                                    <input type="text" name="paginaweb" id="paginaweb" class="form-control"
                                        placeholder="Escriba aquí...">
                                </div>
                            </div>
                            <div class="col-12 text-center mt-3 mb-2">
                                <button class="btn_info_tw" id="save_proveedor">Guardar <i
                                        class='bx bx-save'></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped nowrap responsive" id="lista_proveedores"
                                    style="width: 100%">
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
                        <input type="hidden" name="_token" value="{{ $this->Csrf_Token() }}">
                        <div class="row">
                            <div class="col-xl-3 col-lg-2 col-md-3 col-12">
                                <div class="form-floating mb-2">
                                    <input type="text" name="code_barra" id="code_barra" class="form-control" placeholder="Codigo barra...">
                                    <label for="code_barra"><b>Codigo de barra <span class="text-danger">*</span></b></label>
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-6 col-md-6 col-12">
                                <div class="form-floating mb-2">
                                    <input type="text" name="nombre_producto" id="nombre_producto" class="form-control"
                                    placeholder="Escriba aquí....">
                                    <label for="nombre_producto"><b>Nombre producto<span
                                            class="text-danger">*</span></b></label>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-3  col-12">
                               <div class="form-floating mb-2">
                                <input type="text" name="precio_venta" id="precio_venta" class="form-control"
                                    placeholder="Escriba aquí....">
                                  <label for="precio_venta"><b>Precio de venta <span
                                            class="text-danger">*</span></b></label>
                               </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-12 col-sm-6 col-12">
                               <div class="form-floating mb-2">
                                <input type="number" name="stock_minimo" id="stock_minimo" class="form-control"
                                    placeholder="0" min="0" value="0">
                                  <label for="stock_minimo"><b>Stock Mínimo<span class="text-danger">*</span></b></labe>
                               </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                               <div class="form-floating mb-2">
                                <select name="tipo_select" id="tipo_select" class="form-select">
                                </select>
                                <label for="tipo_select"><b>Tipo<span class="text-danger">*</span></b></label>
                            </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6  col-12">
                                <div class="form-floating mb-2">
                                    <select name="presentacion_select" id="presentacion_select" class="form-select"></select>
                                    <label for="presentacion_select"><b>Presentación<span class="text-danger">*</span></b></label>
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-5 col-12">
                                <div class="form-floating mb-2">
                                     <select name="laboratorio_select" id="laboratorio_select" class="form-select">
                                    </select>
                                    <label for="laboratorio_select"><b>Laboratorio<span
                                    class="text-danger">*</span></b></label>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="form-floating mb-2">
                                    <select name="grupo_select" id="grupo_select" class="form-select">
                                    </select>
                                    <label for="grupo_select"><b>Grupo terapeútico<span class="text-danger">*</span></b></label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="form-floating mb-2">
                                 <select name="embalaje_select" id="embalaje_select" class="form-select">
                                </select>
                                <label for="embalaje_select"><b>Marca<span class="text-danger">*</span></b></label>
                                </div>
                            </div>
                            <div class="col-xl-5  col-12">
                                <div class="form-floating mb-2">
                                    <select name="proveedor_select" id="proveedor_select" class="form-select">
                                    </select>
                                    <label for="proveedor_select"><b>Proveedor<span class="text-danger">*</span></b></label>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="lote" id="lote">
                                    <label class="form-check-label" for="lote"><b class="text-primary" style="cursor: pointer">El producto que deseas registrar es con lote?</b></label>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <div class="mt-3 text-center mb-2 border-2">
                                <button class="btn_3d" id="save_producto"><b>Guardar producto <i class='bx bx-save'></i></b></button>
                            </div>
                        </form>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-7 col-lg-7 col-md-6 col-12">
                                    <form action="{{$this->route("productos/import/excel")}}" method="post" enctype="multipart/form-data" id="form_import_productos">
                                        <input type="hidden" name="_token" value="{{$this->Csrf_Token()}}">
                                        <input type="file" name="excel_productos" id="excel_productos" class="form-control" >
                                    </form>
                                </div>

                                <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                                      <button class="btn btn-primary" id="importar_productos">Importar datos <i class="fas fa-file-excel"></i></button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm nowrap responsive" id="lista_productos"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th class="py-3 letra text-white">#</th>
                                            <th class="py-3 letra text-white">Acciones</th>
                                            <th class="py-3 letra text-white">Cod.Barra</th>
                                            <th class="py-3 letra text-white">Producto</th>
                                            <th class="py-3 letra text-white">Estado</th>
                                            <th class="py-3 letra text-white">Precio
                                                {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}
                                            </th>
                                            <th class="py-3 letra text-white">Stock</th>
                                            <th class="py-3 letra text-white">Stock Mínimo</th>
                                            <th class="py-3 letra text-white">Tipo</th>
                                            <th class="py-3 letra text-white">Presentación</th>
                                            <th class="py-3 letra text-white">Laboratorio</th>
                                            <th class="py-3 letra text-white">Grupo terapeútico</th>
                                            <th class="py-3 letra text-white">Marca</th>
                                            <th class="py-3 letra text-white">Proveedor</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane fade" id="compras" role="tabpanel" aria-labelledby="contact-tab">
                <br>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-end">
                                   @if ($this->profile()->rol === "admin_general")
                                        <div class="col-12">
                                        <div class="form-floating mb-2">
                                            <select name="almacen" id="almacen" class="form-select">
                                                @foreach ($almacenes as $almacen)
                                                    <option value="{{$almacen->id_sede}}">{{strtoupper($almacen->namesede)}}</option>
                                                @endforeach
                                            </select>
                                            <label for="">Seleccione almacén</label>
                                        </div>
                                    </div>
                                   @endif
                                    <div class="col-xl-5 col-lg-4 col-12">
                                        <label for="proveedor_compra"><b>Seleccione proveedor <span class="text-danger">*</span></b></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="proveedor_compra" placeholder="PROVEEDOR.." readonly>
                                            <button class="btn btn-primary" id="open_proveedor_compra"><i class='bx bxs-user-detail'></i></button>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="fecha_emision_compra"><b>Fecha de la compra <span class="text-danger">*</span></b></label>
                                            <input type="date" class="form-control" id="fecha_emision_compra" value="{{$this->FechaActual("Y-m-d")}}">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="serie_emision"><b># Compra<span class="text-danger">*</span></b></label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <b>REC-</b>
                                                </span>
                                                <input type="text" class="form-control" id="serie_compra" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <span class="float-start"><b>Detalle de la compra</b></span>
                                        <button class="btn_success_person float-end mb-2 col-xl-3 col-lg-5 col-md-5  col-12" id="agregar_producto_compra"><b>Agregar productos <i class='bx bx-plus'></i></b></button>
                                        <div class="table-responsive" >
                                           <table class="table table-bordered" id="lista_detalle_compra">
                                               <thead>
                                                   <tr> 
                                                       <th class="text-center text-white">Quitar</th>
                                                       <th class="text-center text-white">Cantidad</th>
                                                       <th class="text-center text-white">Descripción</th>
                                                       <th class="text-center text-white">Empaque</th>
                                                       <th class="text-center text-white">Cod.Lote</th>
                                                       <th class="text-center text-white">F.Producción</th>
                                                       <th class="text-center text-white">F.Vencimiento</th>
                                                       <th class="text-center text-white">Precio  {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                                       <th class="text-center text-white">Importe  {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                                   </tr>
                                               </thead>
                                               <tbody id="lista_detalle_compra_body"></tbody>
                                           </table>
                                  </div>
 
                            </div>
                        </div>
                    </div>
                    




                    <div class="col-12 mt-2 text-center">
                        <button class="btn_twiter m-1" id="save_compra">Guardar la compra <i class='bx bxs-basket'></i></button>
                        <button class="btn btn-danger rounded m-1 col-xl-2 col-lg-3 col-md-4 col-sm-6 col-9 cancelar_compra"><b>Cancelar compra</b> <b>X</b><i class='bx bxs-basket'></i></button>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="clientes" role="tabpanel" aria-labelledby="contact-tab">
            <br>
             <form action="" method="post" id="form_clientes">
                <input type="hidden" name="_token" id="_token" value="{{$this->Csrf_Token()}}">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <label for="tipo_doc" class="form-label"><b>Tipo documento <span class="text-danger">*</span></b></label>
                        <select name="tipo_doc" id="tipo_doc" class="form-select">
                            @if (isset($TipoDocumentos))
                                @foreach ($TipoDocumentos as $tipodoc)
                                    <option value="{{$tipodoc->id_tipo_doc}}">{{$tipodoc->name_tipo_doc}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-12">
                        <label for="num_doc" class="form-label"><b>Número documento <span class="text-danger">*</span></b></label>
                        <input type="text" name="num_doc" id="num_doc" class="form-control" placeholder="Escriba aquí..." maxlength="20">
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <label for="name_cliente" class="form-label"><b>Nombres completos <span class="text-danger">*</span></b></label>
                        <input type="text" name="name_cliente" id="name_cliente" class="form-control" placeholder="Escriba aquí...">
                    </div>

                    <div class="col-xl-4 col-lg-4 col-12">
                        <label for="apellidos_cliente" class="form-label"><b>Apellidos completos <span class="text-danger">*</span></b></label>
                        <input type="text" name="apellidos_cliente" id="apellidos_cliente" class="form-control" placeholder="Escriba aquí...">
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <label for="direccion_cliente" class="form-label"><b>Dirección</b></label>
                        <input type="text" name="direccion_cliente" id="direccion_cliente" class="form-control" placeholder="Escriba aquí...">
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <label for="telefono_cliente" class="form-label"><b>Teléfono</b></label>
                        <input type="text" name="telefono_cliente" id="telefono_cliente" class="form-control" placeholder="Escriba aquí..." maxlength="20">
                    </div>

                    <div class="text-center mt-3 mb-2">
                        <button id="save_cliente" class="btn_success_person"><b>Guardar cliente <i class='bx bx-save'></i></b></button>
                    </div>
                </div>
             </form>

             <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover nowrap responsive" id="lista_clientes" style="width: 100%">
                          <thead>
                            <tr>
                                <th>#</th>
                                <th>Acciones</th>
                                <th>Tipo documento</th>
                                <th>Num.documento</th>
                                <th>Cliente</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                            </tr>
                          </thead>
                        </table>
                    </div>
                </div>
             </div>
            </div>
            <!--- MODIFICADO--->
            <div class="tab-pane fade  @if ($this->profile()->rol === 'Farmacia' || $this->profile()->rol === 'Director') show active @endif" id="ventas"
                role="tabpanel" aria-labelledby="contact-tab">
               <br>
                <input type="hidden" name="_token" value="{{$this->Csrf_Token()}}">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-end">
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="fecha_emision"><b>Fecha emisión <span class="text-danger">*</span></b></label>
                                            <input type="date" class="form-control" id="fecha_emision_venta" value="{{$this->FechaActual("Y-m-d")}}">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-3">
                                        <div class="form-group">
                                            <label for="fecha_emision"><b>Número de venta <span class="text-danger">*</span></b></label>
                                            <div class="input-group">
                                                
                                                <input type="text" class="form-control" id="serie" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span><b>Datos del cliente</b></span>
                                <div class="row mt-2">
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class='bx bx-id-card'></i></span>
                                            <input type="text"   class="form-control" placeholder="# documento" id="documento_venta">
                                          </div>
                                          <span class="text-danger error_buscar_cliente" style="display: none">Mínimo 8 caracteres 😔😢</span>
                                    </div>
                                    <div class="col-xl-9 col-lg-8 col-md-6 col-sm-6 col-12">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class='bx bxs-user-circle'></i></span>
                                            <input type="text" class="form-control" id="cliente_venta" placeholder="Cliente...." readonly
                                            value="Público en general">
                                            <button class="btn btn-primary" id="search_cliente"><i class='bx bx-select-multiple'></i></button>
                                        </div>
                                        <span class="text-danger error_buscar_cliente_two" style="display: none">No se pudo encontrar al cliente.. 😔😢</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-xl-9 col-lg-8  col-12">
                        <div class="card">
                            <div class="card-body">
                                <span class="float-start"><b>Detalle de la venta</b></span>
                                        <button class="btn_success_person float-end mb-2 col-xl-3 col-lg-5 col-md-5  col-12" id="agreagr_producto_venta"><b>Agregar productos <i class='bx bx-plus'></i></b></button>
                                        <div class="table-responsive" >
                                           <table class="table table-bordered" id="lista_detalle_venta">
                                               <thead>
                                                   <tr> 
                                                       <th class="text-center text-white">Quitar</th>
                                                       <th class="text-center text-white">Cantidad</th>
                                                       <th class="text-center text-white">Descripción</th>
                                                       <th class="text-center text-white">Marca</th>
                                                       <th class="text-center text-white">Precio  {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                                       <th class="text-center text-white">Importe  {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                                       <th class="tex-center d-none">IDPROD</th>
                                                       <th class="text-center d-none">PRICEIDENT</th>
                                                    </tr>
                                               </thead>
                                               <tbody id="lista_detalle_venta_body"></tbody>
                                           </table>
                                  </div>

                                  <div class="row" id="monto_recibido_and_vuelto"  >
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                                        <label for=""><b>Monto recibido por el cliente {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</b></label>
                                        <input type="text" class="form-control" id="monto_recibido" placeholder="{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} 0.00" >
                                        <span class="text-danger" style="display: none" id="msg_error_monto">El monto debe ser > al monto total!</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                                        <label for=""><b>Vuelto {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</b></label>
                                        <input type="text" class="form-control" id="vuelto" placeholder="{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} 0.00" readonly
                                        value="0.00">
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4  col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for=""><b>Sub Total <span class="text-primary">{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</span></b></label>
                                    <input type="text" class="form-control" id="sub_total_venta" readonly value="0.00">
                                </div>
                                <div class="form-group">
                                    <label for=""><b>Igv  <span class="text-primary">{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</span></b></label>
                                    <input type="text" class="form-control" id="igv_venta" readonly value="0.00">
                                </div>
                                <div class="form-group">
                                    <label for=""><b>Total a pagar  <span class="text-primary">{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</span></b></label>
                                    <input type="text" class="form-control" id="total_venta" readonly value="0.00">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2 text-center">
                        <button class="btn_twiter m-1" id="save_venta">Guardar la venta <i class='bx bxs-basket'></i></button>
                        <button class="btn btn-danger rounded m-1 col-xl-2 col-lg-3 col-md-4 col-sm-6 col-9 cancelar_venta"><b>Cancelar venta</b> <b>X</b><i class='bx bxs-basket'></i></button>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="historia_ventas" role="tabpanel" aria-labelledby="contact-tab">
              <br>
              <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-7 col-sm-8 col-12">
                    <div class="form-group">
                        <label for="fecha_venta_historial"><b>Seleccionar fecha</b></label>
                        <input type="date" class="form-control" id="fecha_venta_historial" value="{{$this->FechaActual("Y-m-d")}}">
                    </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-striped nowrap responsive" id="lista_historial_ventas" style="width: 100%">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>Acciones</th>
                        <th>Num.venta</th>
                        <th>Fecha emisión</th>
                        <th>Cliente</th>
                        <th>Farmaceútico vendedor</th>
                        <th>Total venta</th>
                        <th>Monto recibido</th>
                        <th>Vuelto</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            <div class="tab-pane fade" id="reporte_ventas" role="tabpanel" aria-labelledby="contact-tab">
              <br>
              <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                    <div class="form-group">
                        <label for="fi"><b>Fecha inicio</b></label>
                        <input type="date" name="fi" id="fi" class="form-control" value="{{$this->FechaActual("Y-m-d")}}">
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                    <div class="form-group">
                        <label for="ff"><b>Fecha fin</b></label>
                        <input type="date" name="ff" id="ff" class="form-control" value="{{$this->addRestFecha("Y-m-d","10 day")}}">
                    </div>
                </div>
                
              </div>
              
              <div class="table-responsive">
                <table class="table table-bordered table-striped nowrap responsive" id="reporte_productos_ganancias" style="width: 100%">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Precio de compra</th>
                        <th>Precio de venta</th>
                        <th>Ganancia</th>
                    </tr>
                  </thead>

                  <tfoot align="right" style="background-color: blue">
                    <tr><th class="text-white"></th><th class="text-white"></th><th class="text-white pc"></th><th class="text-white"></th><th class="text-white"></th></tr>
                    </tfoot>
                </table>
              </div>

              <div class="row justify-content-center">
               <div class="col-xl-3 col-lg-3 col-md-5 col-sm-4 col-12">
                <button class="btn btn-danger" id="resultados">Ver resultados <i class='bx bxs-file-pdf'></i></button>
               </div>
              </div>
            </div>
            <div class="tab-pane fade" id="egresos" role="tabpanel" aria-labelledby="contact-tab">
                <h4>egresos</h4>
            </div>
        </div>
    </div>
    </div>

{{--- MODAL PARA MOSTRAR LISTADO DE CLIENTES ---}}
<div class="modal fade" id="modal_listado_clientes" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style=" background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                <h4 class="text-white letra">Buscar cliente</h4>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped responsive nowrap" id="lista_search_cliente" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="text-white py-3 letra">#</th>
                                <th class="text-white py-3 letra">Num.Documento</th>
                                <th class="text-white py-3 letra">Cliente</th>
                                <th class="text-white py-3 letra">Enviar</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-2">
                <button class="btn btn-danger rounded salir_search_cliente"><b>Salir X</b></button>
            </div>
        </div>
    </div>
</div>

{{--- MODAL PARA CONSULTAR PRODUCTOS Y AGREGARLOS A LA CESTA ---}}
<div class="modal fade" id="modal_listado_producto_venta" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                <h4 class="text-white float-start">Agregar Productos <i class='bx bx-plus'></i></h4>
                <h4 class="float-end letra d-xl-block d-lg-block d-md-block d-none" style="color:#ADFF2F">{{$this->profile()->namesede}}</h4>
            </div>

            <div class="modal-body">
                    <table class="table table-bordered table-striped table-shover table-sm responsive nowrap" id="lista_search_productos" style="width: 100%">
                        <thead style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                            <tr>
                                <th class="py-3 text-white letra">#</th>
                                <th class="d-none">ID</th>
                                <th class="py-3 text-white letra">Agregar</th>
                                <th class="py-3 text-white letra">Producto</th>
                                <th class="py-3 text-white letra">Stock</th>
                                <th class="py-3 text-white letra">Sucursal Stock</th>
                                <th class="py-3 text-white letra">Estado</th>
                                <th class="py-3 text-white letra">Precio  {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                <th class="py-3 text-white letra">Marca</th>
                                <th class="py-3 text-white letra">Tipo</th>
                                <th class="py-3 text-white letra">Presentación</th>
                                <th class="py-3 text-white letra">Grupo Terapeútico</th>
                                <th class="py-3 text-white letra">Price Indent</th>

                            </tr>
                        </thead>
                    </table>
            </div>
            <div class="modal-footer border-2">
                <button class="btn btn-danger rounded salir_search_producto"><b>Salir X</b></button>
            </div>
        </div>
    </div>
</div>
{{--- MODAL PARA MOSTRAR LOS PRODUCTOS PARA AÑADIR A LA CESTA DE LA COMPRA ---}}
<div class="modal fade" id="modal_listado_producto_compra" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                <h4 class="text-white">Productos existentes <i class='bx bx-plus'></i></h4>
            </div>

            <div class="modal-body">
                    <table class="table table-bordered table-striped table-sm table-hover responsive nowrap" id="lista_search_productos_compra" style="width: 100%">
                        <thead >
                            <tr>
                                <th class="py-4 text-primary letra">#</th>
                                <th class="d-none">ID</th>
                                <th class="py-4 letra text-white">Agregar</th>
                                <th class="py-4 letra text-white">Producto</th>
                                <th class="py-4 letra text-white">Proveedor</th>
                                <th class="py-4 letra text-white">Marca</th>
                                <th class="py-4 letra text-white">Tipo</th>
                                <th class="py-4 letra text-white">Precio de venta actual {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} </th>
                                <th class="py-4 letra text-white">Presentación</th>
                                <th class="py-4 letra text-white">Grupo Terapeútico</th>
                                <th class="py-4 letra text-white">LOTE</th>
                            </tr>
                        </thead>
                    </table>
                <div class="row"  >
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                        <div class="form-group">
                            <label for="producto_compra"><b>Producto a comprar </b></label>
                            <input type="text" name="producto_compra" id="producto_compra" class="form-control" placeholder="" readonly>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                        <div class="form-group">
                            <label for="empaque_compra"><b>Marca</b></label>
                            <input type="text" name="empaque_compra" id="empaque_compra" class="form-control" placeholder="" readonly>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                        <div class="form-group">
                            <label for="precio_compra"><b>Precio de compra <span class="text-primary">{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</span> <span class="text-danger">*</span></b></label>
                            <input type="text" name="precio_compra" id="precio_compra" class="form-control" placeholder="0.00">
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                        <div class="form-group">
                            <label for="cantidad_compra"><b>Cantidad <span class="text-danger">*</span></b></label>
                            <div class="input-group">
                            <input type="text" name="cantidad_compra" id="cantidad_compra" class="form-control" placeholder="0">
                            <button class="btn btn-primary" id="add_lote" style="display:none">Agregar Lote<i class='bx bx-plus'></i></button>    
                        </div>
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="modal-footer border-2">
                <button class="btn btn-danger rounded salir_search_producto_compra"><b>Salir X</b></button>
            </div>
        </div>
    </div>
</div>
{{-- modal para reporte de productos ----}}
<div class="modal fade" id="modal_reporte_productos" data-bs-backdrop="static">
    <div class="modal-dialog">
       <div class="modal-content">
        <div class="modal-header" style="background-color: aquamarine">
            <h5>Reporte de productos por vencer</h5>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="diasvencer"><b>Días por vencer <span class="text-danger">*</span></b></label>
                <select name="diasvencer" id="diasvencer" class="form-select">
                    <option value="10">10 días</option>
                    <option value="20">20 días</option>
                    <option value="30">30 días</option>
                    <option value="40">40 días</option>
                    <option value="50">50 días</option>
                    <option value="60">60 días</option>
                    <option value="70">70 días</option>
                    <option value="80">80 días</option>
                    <option value="90">90 días</option>
                    <option value="100">100 días</option>
                    <option value="mas de 100 días">Más de 100 días</option>
                </select>
            </div>
            <div class="form-group">
                <label for="proveedor_reporte"><b>Seleccione a un proveedor <span class="text-danger">*</span></b></label>
                <select name="proveedor_reporte" id="proveedor_reporte" class="form-control"></select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-danger rounded" id="cerrar_reporte_productos">Cerrar <b>X</b></button>
            <button class="btn btn-success rounded" id="ver_reporte_productos">Ver reporte</button>
        </div>
       </div>
    </div>
</div>

{{---MODAL PARA PROVEEDORES---}}
<div class="modal fade" id="modal_open_proveedores" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
       <div class="modal-content">
        <div class="modal-header" style="background-color: rgb(89, 106, 180)">
            <h5 class="text-white">Buscar proveedor</h5>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped nowrap responsive" style="width: 100%" id="tabla_buscar_proveedores">
              <thead>
                <tr>
                    <th>#</th>
                    <th class="d-none">ID</th>
                    <th class="col-2">Seleccionar</th>
                    <th>Proveedor</th>
                    <th>Contacto</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-danger rounded" id="cerrar_modal_buscar_proveedor">Cerrar <b>X</b></button>
        </div>
       </div>
    </div>
</div>
{{---VENTANA MODAL PARA ASIGNAR LAS SEDES A PRODUCTOS---}}
<div class="modal fade" id="modal_open_asignar_almacenes" data-bs-backdrop="static">
    <div class="modal-dialog  modal-fullscreen">
       <div class="modal-content">
        <div class="modal-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
            <h5 class="text-white letra" id="text_almacen">Asignar Almacén</h5>
        </div>
        <div class="modal-body" id="carga_view_almacenes">
            <div class="row mb-2" id="fila_producto_almacen">
                <div class="col-xl-8 col-lg-8 col-md-7 col-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="producto_seleccionado_almacen" readonly>
                        <label for="producto_seleccionado_alamacen">Producto Seleccionado</label>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-5 col-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="producto_stock_seleccionado_almacen" readonly>
                        <label for="producto_stock_seleccionado_alamacen">Stock Total del Producto</label>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" style="width: 100%" id="lista_almacenes">
              <thead style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                <tr>
                     <th class="py-3 text-white letra"><div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="all_sedes" checked>
                            <label class="form-check-label" for="all_sedes">Todos los almacenes</label>
                        </div>
                    </th>
                    <th class="py-3 text-white letra">Stock</th>
                </tr>
              </thead>
              <tbody id="tabla_lista_almacenes"></tbody>
            </table>
            <a href="" id="inventario"><b>Ver Inventario de productos <i class='bx bx-right-arrow-alt'></i></b></a>
            <a href="" id="inicio" style="display:none"><b>Volver al Inicio <i class='bx bx-left-arrow-alt'></i></b></a>
            <div id="div_prod_almacen" style="display:none">
                <table class="table table-bordered table-striped table-hover table-sm nowrap responsive" style="width: 100%" id="lista_productos_almacen">
              <thead style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%)">
                <tr>
                    <th class="py-3 letra text-white">Almacén</th>
                    <th class="py-3 letra text-white">Producto</th>
                    <th class="py-3 letra text-white">Presentación</th>
                    <th class="py-3 letra text-white">Precio {{count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.'}}</th>
                    <th class="py-3 letra text-white">Stock</th>
                     <th class="py-3 letra text-white">Acciones</th>
                </tr>
              </thead>
            </table>
            </div>
        </div>
        <div class="modal-footer border-2">
            <button class="btn btn-success" id="save_prod_almacenes">Guardar <i class="fas fa-save"></i></button>
            <button class="btn btn-danger rounded" id="cerrar_modal_asignar_almacenes">Cerrar <b>X</b></button>
        </div>
       </div>
    </div>
</div>
{{---PARA AGREGAR MAS PRECIOS A PRODUCTOS---}}
<div class="modal fade" id="modal_open_add_prices" data-bs-backdrop="static">
    <div class="modal-dialog  modal-lg">
       <div class="modal-content">
        <div class="modal-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
            <h5 class="text-white letra" id="text_precios">Agregar Precios</h5>
        </div>
        <div class="modal-body" id="carga_view_almacenes">
           <div class="alert alert-success" role="alert">
             <b>  Los campos con * son obligatorios</b>
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-xl-0 mb-lg-0 mb-md-0 mb-3">
                   <div class="form-floating">
                    <select name="presentacion_select_prices" id="presentacion_select_prices" class="form-select">
                        
                    </select>
                   </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                    <div class="form-floating mb-2">
                        <input type="number" class="form-control" id="cantidad_add" placeholder="0" min="0">
                        <label for="cantidad_add" ><b>cantidad * </b></label>
                    </div>
                </div>
                 <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="precio_add" placeholder="0.00">
                        <label for="precio_add" ><b>Precio {{count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.'}} * </b></label>
                    </div>
                </div>
              
              <div class="col-12" id="tabla_de_precios" >
                {{-- ContieneNuevosPrecio() --}}
                    <table class="table table-bordered table-striped table-hover table-sm nowrap responsive" style="width: 100%"
                        id="lista_precios">
                        <thead
                            style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%)">
                            <tr>
                                <th class="py-3 letra text-white">Presentación</th>
                                <th class="py-3 letra text-white">Cantidad</th>
                                <th class="py-3 letra text-white">Precio {{count($this->BusinesData()) == 1 ?
                                    $this->BusinesData()[0]->simbolo_moneda : 'S/.'}}</th>
                                <th class="py-3 letra text-white">Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer border-2">
            <button class="btn btn-success" id="save_prices_producto">Guardar <i class="fas fa-save"></i></button>
            <button class="btn btn-danger rounded" id="cerrar_modal_prices_producto">Cerrar <b>X</b></button>
        </div>
       </div>
    </div>
</div>
{{--MODAL PARA AGREGAR LOTES AL PRODUCTO---}}
<div class="modal fade" id="modal_open_add_lote" data-bs-backdrop="static">
    <div class="modal-dialog  modal-lg">
       <div class="modal-content">
        <div class="modal-header" style="background:linear-gradient(to bottom, rgba(179,220,237,1) 0%,rgba(41,184,229,1) 50%,rgba(188,224,238,1) 100%);">
            <h5 class="text-white letra" id="text_precios"><b>Agregar Lote</b></h5>
        </div>
        <div class="modal-body" id="carga_view_almacenes">
           <div class="form-floating mb-2">
              <input type="text" class="form-control" id="productolote" readonly>
              <label for="productolote">PRODUCTO</label>
           </div>
           <div class="alert alert-success" role="alert">
             <b>  Los campos con * son obligatorios</b>
            </div>
          <form id="form_add_lote" method="post">
            <div class="row">
                    <div class="col-xl-6 col-lg-6 col-12 mb-xl-0 mb-lg-0 mb-md-0 mb-3">
                   <div class="form-floating mb-2">
                     <input type="text" class="form-control" id="codelote" placeholder="Codigo lote....">
                     <label for="codelote">Código Lote</label>
                     <span class="text-danger" id="errorcodigo"></span>
                   </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-12">
                    <div class="form-floating mb-2">
                        <input type="date" id="fechaprodlote" class="form-control" value="{{$this->FechaActual("Y-m-d")}}">
                        <label for="fechaprodlote"><b>FECHA DE PRODUCCIÓN....</b></label>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-12">
                    <div class="form-floating mb-2">
                        <input type="date" class="form-control" id="fecha_vencimiento_lote" value="{{$this->addRestFecha("Y-m-d","+380 day")}}">
                        <label for="fecha_vencimiento_lote" ><b>Fecha Vencimiento * </b></label>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-12">
                    <div class="form-floating mb-2">
                        <input type="number" class="form-control" id="cantidad_lote" placeholder="0" min="0" readonly>
                        <label for="cantidad_lote" ><b>cantidad * </b></label>
                        <span class="text-danger" id="errorcantidad"></span>
                    </div>
                </div>
            </div>
            </form>
        </div>
        <div class="modal-footer border-2">
            <button class="btn btn-success" id="save_lote">Agregar <i class="fas fa-plus"></i></button>
            <button class="btn btn-danger rounded" id="cerrar_modal_lote">Cerrar <b>X</b></button>
        </div>
       </div>
    </div>
</div>
{{---MODIFICAR EL STOCK Y PRECIO DEL PRODUCTO DE CADA ALMACEN---}}
<div class="modal fade" id="modal_open_editar_price_stock_producto">
    <div class="modal-dialog">
       <div class="modal-content">
        <div class="modal-header" style="background:linear-gradient(to bottom, rgba(252,234,187,1) 0%,rgba(252,205,77,1) 50%,rgba(248,181,0,1) 51%,rgba(251,223,147,1) 100%);">
            <h5 class="text-white letra" id="text_precios">Editar</h5>
        </div>
        <div class="modal-body" id="carga_view_almacenes">
           <div class="alert alert-success" role="alert">
             <b>  Los campos con * son obligatorios</b>
            </div>
            <div class="row">
                 <div class="col-12">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="almacen_editar_texto" placeholder="0.00" readonly>
                        <label for="almacen_editar_texto" ><b>Almacén</b></label>
                    </div>
                 </div>
                 <div class="col-12">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="producto_editar_texto" placeholder="0.00" readonly>
                        <label for="producto_editar_texto" ><b>Producto</b></label>
                    </div>
                 </div>
                <div class="col-12">
                    <div class="form-floating mb-2">
                        <input type="number" class="form-control"  id="stock_prod_almacen" placeholder="0" min="0"
                        onkeydown="return event.keyCode !== 189 && event.keyCode !== 109" onkeypress="return event.charCode >= 48">
                        <label for="stock_prod_almacen" ><b>Stock * </b></label>
                    </div>
                </div>
                 <div class="col-12">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" disabled id="precio_prod_almacen" placeholder="0.00">
                        <label for="precio_prod_almacen" ><b>Precio {{count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.'}} * </b></label>
                    </div>
                </div>
          
        </div>
        <div class="modal-footer border-2">
            <button class="btn btn-success" id="update_price_stock_producto">Guardar <i class="fas fa-save"></i></button>
        </div>
       </div>
    </div>
</div>
</div>
{{---ALERTA MODAL PARA APERTURA UNA CAJA ---}}
<div class="modal fade" id="modal_alerta_para_caja" data-bs-backdrop="static">
    <div class="modal-dialog">
       <div class="modal-content">
        <div class="modal-header" style="background:linear-gradient(to bottom, rgba(255,103,15,1) 32%,rgba(255,103,15,1) 32%,rgba(255,103,15,1) 62%);">
            <h5 class="text-white letra" id="text_precios"><b>Mensaje del sistema!!!!</b></h5>
        </div>
        <div class="modal-body" >
          <b>Antes de Continuar debes de aperturar una caja!!!</b>  
       </div>
       <div class="modal-footer justifi-content-center border-2">
          <button class="btn_3d" id="ir_caja"><b>Aperturar Caja</b>  <i class="fa-solid fa-money-check-dollar"></i></button>
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
    <script src="{{ URL_BASE }}public/js/clientes.js"></script>
    <script src="{{ URL_BASE }}public/js/ventas.js"></script>
    <script src="{{ URL_BASE }}public/js/historia_ventas.js"></script>
    <script src="{{ URL_BASE }}public/js/compra.js"></script>
    <script src="{{ URL_BASE }}public/js/reportes.js"></script>
    <script src="{{URL_BASE}}public/js/producto_almacenes.js"></script>
    <script>
        var RUTA = "{{ URL_BASE }}" // la url base del sistema
        var TOKEN = "{{ $this->Csrf_Token() }}";
        var PROFILE_ = "{{ $this->profile()->rol }}";
        var TablaListaTipoProductos, TablaListaEmpaques;
        var TablaListaPresentacion, TablaListaLaboratorios, TablaListaGrupos, TablaListaProveedores,TablaListaClientes,
            TablaListaProductos,TablaSearchClienteVenta,TablaSearchProductoVenta,lista_historial_ventas,TablaProductosCompra,TablaRepoProductos,
            TablaBuscarProveedores;
        var IDTIPOPRODUCTO, IDPRESENTACION, IDLABORATORIO, IDGRUPO, IDEMBALAJE, IDPROVEEDOR, IDPRODUCTO,IDCLIENTE,IDPRODUCTOCOMPRA,
        CLIENTE_VENTA_ID,PROVEEDOR_ID_COMPRA;
        var ControlBotonTipo = 'save',
            ControlBotonPresnetacion = 'save',
            ControlButtonLaboratorio = 'save',
            ControlBotonGrupo = 'save',
            ControlBotonEmbalaje = 'save',
            ControlBotonProveedor = 'save',
            ControlBotonClientes = 'save';
            ControlBotonProducto = 'save',
            AccionButtonProducto_Presentacion = 'save';
            var TotalPricecompra = 0.00;
        var ViewDivMontos;
        var MONEDA = "{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}";
        var IvaData = "{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->iva_valor : 18 }}";
        var FechaActualVenta = "{{$this->FechaActual('Y-m-d')}}";
        var CantidadInputEnter;
        var ProductosAlmacen,TablaListaPrecios;
        var PRODUCTO_ALMACEN_ID;
        var PRODUCTO_PRESETACION_ID;
        var StockTotal,AuxStockEdit,LOTEPRODUCTO;
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
            let PrecioVentaProducto = $('#precio_venta');
            let FechaVencimiento = $('#fecha_vencimiento');
            let TipoProducto = $('#tipo_select');
            let PresentacionProducto = $('#presentacion_select');
            let LaboratorioProducto = $('#laboratorio_select');
            let GrupoProducto = $('#grupo_select');
            let EmbalajeProducto = $('#embalaje_select');
            let ProveedorProducto = $('#proveedor_select');
            let StockMinimo = $('#stock_minimo');
            let NumDocCliente = $('#num_doc');
            let NameCliente = $('#name_cliente');
            let ApellidosCliente = $('#apellidos_cliente'); 
            QuitarAlmacenesAlProducto();
            loading('#farmacia_card','#4169E1','chasingDots') 
            setTimeout(() => {
            
            $('#farmacia_card').loadingModal('hide');
            $('#farmacia_card').loadingModal('destroy');
            
            if(PROFILE_ === 'Farmacia'){
                VerifyCajaAbierta()
            }
                /// mostramos la vista de la pestaña por defecto
            mostrarTipoProductos();
            /** Editar tipo de productos **/
            EditarTipoProducto(TablaListaTipoProductos, '#lista_tipo_productos tbody');
            /** Eliminar tipo de producto */
            EliminarTipoProducto(TablaListaTipoProductos, '#lista_tipo_productos tbody');
            /// activar nuevamente el tipo de producto
            ActivarTipoProducto(TablaListaTipoProductos, '#lista_tipo_productos tbody');

            /** Eliminar por completo el tipo de productos**/
            DeleteConfirmTipoProducto(TablaListaTipoProductos, '#lista_tipo_productos tbody');

            if(PROFILE_ === 'Farmacia' || PROFILE_ === 'Director')
            {
              
            showProductosDeLaCesta();

            /** Listar productos para agregar a la venta **/ 
            ListarProductosParaLaVenta();
            /** Agregar productos a la cesta **/ 
             addCestaProducto(TablaSearchProductoVenta,'#lista_search_productos tbody');

             /// quitar producto de la cesta
             QuitarProductoCesta();
             modificarCantidadProductoCesta();

             getSerieVenta();
            }
            }, 1000);

            $('#ir_caja').click(function(){
                location.href= RUTA+"apertura/caja-farmacia";
            });

           $('#monto_recibido').keyup(function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            });

            /// ABRIR VENTA DE AGREGAR LOTE
            $('#add_lote').click(function(){
               if($('#cantidad_compra').val().trim().length == 0){
                 $('#cantidad_compra').focus();
               }else{
                    $('#cantidad_lote').val($('#cantidad_compra').val());
                    $('#modal_open_add_lote').modal("show");
               }
            });

            $('#cerrar_modal_lote').click(function(){
                $('#modal_open_add_lote').modal("hide");
            });
             
            $('#cerrar_modal_asignar_almacenes').click(function(){
                $('#modal_open_asignar_almacenes').modal("hide");
                $('#all_sedes').prop("checked",true);
                $('#lista_almacenes').show(400);
                $('#div_prod_almacen').hide();
                $('#inicio').hide();
                $('#inventario').show(400);
                $('#ila_producto_almacen').show(400);
                $('#text_almacen').text("Asignar Almacén");
                 $('#save_prod_almacenes').show();
                QuitarAlmacenesAlProducto();
            });
            $('#cerrar_modal_prices_producto').click(function(){
                $('#modal_open_add_prices').modal("hide");
                AccionButtonProducto_Presentacion = 'save';
                $('#presentacion_select_prices').prop("selectedIndex",0);
                $('#cantidad_add').val("");
                $('#precio_add').val("");
            })
            $('#inventario').click(function(evento){
                evento.preventDefault();
                $('#lista_almacenes').hide();
                $('#div_prod_almacen').show(400);
                $('#inicio').show(400);
                $('#inventario').hide();
                $('#ila_producto_almacen').hide();
                $('#text_almacen').text("Inventario de productos");
                $('#save_prod_almacenes').hide();
               MostrarProductosPorAlmacen();
               ProductosAlmacen.ajax.reload();
                EditarPrecioStockProductoAlmacen(ProductosAlmacen,'#lista_productos_almacen tbody');
                ConfirmarEliminadoAlmacenDelProducto(ProductosAlmacen,'#lista_productos_almacen tbody');
            });
             $('#inicio').click(function(evento){
                evento.preventDefault();
                $('#lista_almacenes').show(400);
                $('#div_prod_almacen').hide();
                $('#inicio').hide();
                $('#inventario').show(400);
                $('#ila_producto_almacen').show(400);
                $('#text_almacen').text("Asignar Almacén");
                $('#save_prod_almacenes').show(400);
            })

            /// GUARDAR LOTE
            $('#save_lote').click(function(evento){
                evento.preventDefault();
                //saveLoteProducto();
                if($('#codelote').val().trim().length == 0){
                    $('#codelote').focus();
                }else{
                    addCestaProductos(IDPRODUCTOCOMPRA);
                }
            });
            $('#save_prod_almacenes').click(function(){
                 SaveAlmacenProdcuto();
            });
            $('#update_price_stock_producto').click(function(){
                ModificarPrecioStockProductoAlmacen(PRODUCTO_ALMACEN_ID,$('#precio_prod_almacen'),$('#stock_prod_almacen'));
            });
            $('#save_prices_producto').click(function(){
                if($('#presentacion_select_prices').val() == null){
                    $('#presentacion_select_prices').focus();
                }else{
                    if($('#cantidad_add').val().trim().length == 0){
                        $('#cantidad_add').focus();
                    }else{
                    if($('#precio_add').val().trim().length == 0){
                      $('#precio_add').focus();  
                    }else{
                        if(AccionButtonProducto_Presentacion == 'save'){
                        saveAddPrecioProducto(IDPRODUCTO,$('#presentacion_select_prices'),$('#cantidad_add'),$('#precio_add'));
                        }else{
                        ModificarPreciosProductoPresentacion(PRODUCTO_PRESETACION_ID,$('#presentacion_select_prices'),$('#precio_add'),$('#cantidad_add'))
                        }
                    }
                 }
                }
                 
            })
            $('#tab_farmacia a').on('click', function(evento) {
                evento.preventDefault();
                const idTab = $(this)[0].id;
                if (idTab === 'tipo_producto_') {
                    ControlBotonTipo = 'save';
                    mostrarTipoProductos();
                } else {
                    if (idTab === 'presentacion_') {
                        ControlBotonPresnetacion = 'save';
                        showPresentaciones();
                        /*Editar la presentación*/
                        editarPresentacion(TablaListaPresentacion, '#lista_presentacion tbody');
                        /*Eliminar la presentación*/
                        Eliminar(TablaListaPresentacion, '#lista_presentacion tbody');
                        /*Activar la presentación*/
                        Activar(TablaListaPresentacion, '#lista_presentacion tbody');
                        /** Eliminar por completo a la presentación **/
                        DeleteConfirmPresentacion(TablaListaPresentacion, '#lista_presentacion tbody');
                    } else {
                        if (idTab === 'laboratorios_') {
                            ControlButtonLaboratorio = 'save';
                            showLaboratorios();
                            /** Editar laboratorio */
                            editarLaboratorio(TablaListaLaboratorios, '#lista_laboratorios tbody');
                            /** Eliminar laboratorios*/
                            EliminarLaboratorio(TablaListaLaboratorios, '#lista_laboratorios tbody');
                            /** Activar laboratorio*/
                            ActivarLaboratorio(TablaListaLaboratorios, '#lista_laboratorios tbody');
                            /*Eliminar por completo de la base de datos a laboratorios*/
                            DeleteConfirmLaboratorio(TablaListaLaboratorios, '#lista_laboratorios tbody');
                        } else {
                            if (idTab === 'grupos_') {
                                ControlBotonGrupo = 'save';
                                mostrarGruposTerapeuticos();

                                /** Editar Grupo terapeutico**/
                                EditarGrupo(TablaListaGrupos, '#lista_grupo_terapeuticos tbody');
                                /*Eliminar grupo terapeutico*/
                                ConfirmEliminadoGrupo(TablaListaGrupos, '#lista_grupo_terapeuticos tbody');
                                /// activar grupo terapeútico
                                ActivateGrupo(TablaListaGrupos, '#lista_grupo_terapeuticos tbody');
                                /*Confirmar eliminado de los grupos terapeuticos*/
                                DeleteConfirmGrupos(TablaListaGrupos, '#lista_grupo_terapeuticos tbody');
                            } else {
                                if (idTab === 'embalaje_') {
                                    ControlBotonEmbalaje = 'save';
                                    mostrarEmpaques();
                                    /** Editar embalaje **/
                                    EditarEmpaque(TablaListaEmpaques, '#lista_embalajes tbody');
                                    /** Confirma eliminado del empaque**/
                                    ConfirmEliminadoEmpaque(TablaListaEmpaques, '#lista_embalajes tbody');
                                    /*Actiavar empaques*/
                                    ActivateEmpaque(TablaListaEmpaques, '#lista_embalajes tbody');
                                    /** Confirmar eliminado de empaques o embalajes*/
                                    DeleteConfirmEmbalajes(TablaListaEmpaques, '#lista_embalajes tbody');
                                } else {
                                    if (idTab === 'proveedores_') {
                                        $('#proveedor_name').val("");
                                        $('#contacto_name').val("");
                                        $('#telefono').val("");
                                        $('#correo').val("");
                                        $('#direccion').val("");
                                        $('#paginaweb').val("");
                                        ControlBotonProveedor = 'save';
                                        mostrarProveedores();
                                        /** Editar proveedores**/
                                        EditarProveedor(TablaListaProveedores, '#lista_proveedores tbody');
                                        /*Confirmar borrado de proveedores*/
                                        DeleteConfirmProveedor(TablaListaProveedores,
                                            '#lista_proveedores tbody');
                                        /** Confirmar eliminado del proveedor*/
                                        ConfirmEliminadoProveedor(TablaListaProveedores,
                                            '#lista_proveedores tbody');
                                        /*Activar proveedores*/
                                        ActivateProveedor(TablaListaProveedores,
                                        '#lista_proveedores tbody');
                                    } else {
                                        if (idTab === 'productos_') {
                                            $('#form_productos')[0].reset();
                                            ControlBotonProducto = 'save';
                                            showProducto();
                                            loading('#farmacia_card', '#4169E1', 'chasingDots');
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
                                                mostrarProveedoresComboReporte();

                                                /** acciones de la tabla productos*/
                                                //editar productos
                                                EditarProductos(TablaListaProductos,
                                                    '#lista_productos tbody');
                                                /*Confirmar borrado de productos*/
                                                ConfirmarBorradoProductos(TablaListaProductos,
                                                    '#lista_productos tbody');

                                                /** Confirmar eliminado de la listad de productos*/ 
                                                EliminadoConfirmProductos(TablaListaProductos,'#lista_productos tbody');
                                                /*Actiavr productos*/
                                                ActivarProductos(TablaListaProductos,'#lista_productos tbody');
                                                AsignarSedes(TablaListaProductos,'#lista_productos tbody');
                                                addPrecioProductoAlmacen(TablaListaProductos,'#lista_productos tbody');
                                            }, 200);
                                        }else{
                                            if(idTab === 'clientes_')
                                            {
                                                $('#form_clientes')[0].reset();
                                                ControlBotonClientes = 'save';
                                                mostrarClientes();
                                                $('#num_doc').focus();
                                                /** Editar clientes*/ 
                                                EditarClientes(TablaListaClientes,'#lista_clientes tbody');
                                                /** Confirmar borrado del clientes*/ 
                                                ConfirmarBorradoClientes(TablaListaClientes,'#lista_clientes tbody');
                                                /** Confirmar eliminado de clientes*/ 
                                                EliminadoConfirmClientes(TablaListaClientes,'#lista_clientes tbody');
                                                // Activar clientes 
                                                ActivarClientes(TablaListaClientes,'#lista_clientes tbody');
                                            }else{
                                                if(idTab === 'save_ventas_')
                                                {
                                                    getSerieVenta();
                                                }else{
                                                  if(idTab === 'historia_ventas_')
                                                  {
                                                    mostrarHistoriaVentas("{{$this->FechaActual('Y-m-d')}}");
                                                    printTicketDeVenta();
                                                  }else{
                                                    if(idTab === 'compras_')
                                                    {
                                                        getSerieCompra();
                                                        showProductosParaLaCompra($('#almacen').val());
                                                        addCestaProductoCompra();
                                                        showProductosDeLaCestaCompra();
                                                        QuitarProductoCestaCompra();
                                                        modificarCantidadProductoCestaCompra();
                                                        modificarPriceProductoCestaCompra();
                                                    }else{
                                                        if(idTab === 'reporte_ventas_'){
                                                            showRepoProductos($('#fi').val(),$('#ff').val());
                                                        }
                                                    }
                                                  }
                                                }
                                            } 
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

            /// abrir el explorador de archivos
            $('#importar_productos').click(function(evento){
                evento.preventDefault();
                if($('#excel_productos').val().trim().length >0 )
                {
                 loading('#farmacia_card','#4169E1','chasingDots');

                setTimeout(() => {
                 $('#farmacia_card').loadingModal('hide');
                 $('#farmacia_card').loadingModal('destroy');
                 importDataProductosToExcel();
                }, 1000);
                }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Seleccione un archivo excel!",
                    icon:"error"
                });
                }
            });

            $('#reporte_productos').click(function(event){
                event.preventDefault();
                $('#modal_reporte_productos').modal("show");
            });
            
            $('#ver_reporte_productos').click(function(event){
                event.preventDefault();
                window.open(RUTA+"app/farmacia/productos/por/vencer?dias="+$('#diasvencer').val()+"&&v="+$('#proveedor_reporte').val(),"_blank")
            });
            $('#cerrar_reporte_productos').click(function(event){
                event.preventDefault();
                $('#modal_reporte_productos').modal("hide");
            });

            $('#save_presentacion').click(function(evento) {
                evento.preventDefault();
                if (NamePresentacion.val().trim().length == 0) {
                    NamePresentacion.focus();
                } else {
                    if (NameCortoPresentacion.val().trim().length == 0) {
                        NameCortoPresentacion.focus();
                    } else {
                        if (ControlBotonPresnetacion === 'save') {
                            save('form_presentacion', RUTA);
                        } else {
                            modificar(IDPRESENTACION);
                        }
                    }
                }

            });

            $('#save_laboratorio').click(function(evento) {
                evento.preventDefault();
                if (NameLaboratorio.val().trim().length == 0) {
                    NameLaboratorio.focus();
                } else {
                    if (ControlButtonLaboratorio === 'save') {
                        saveLaboratorio();
                    } else {
                        updateLaboratorio(IDLABORATORIO);
                    }
                }
            });

            $('#save_grupo').click(function(evento) {
                evento.preventDefault();

                if (NameGrupoTerapeutico.val().trim().length == 0) {
                    NameGrupoTerapeutico.focus();
                } else {
                    if (ControlBotonGrupo === 'save') {
                        saveGrupo();
                    } else {
                        updateGrupo(IDGRUPO);
                    }
                }
            });
            $('#save_embalaje').click(function(evento) {
                evento.preventDefault();
                if (NameEmbalaje.val().trim().length == 0) {
                    NameEmbalaje.focus();
                } else {
                    if (ControlBotonEmbalaje === 'save') {
                        saveEmbalaje();
                    } else {
                        updateEmbalaje(IDEMBALAJE);
                    }
                }
            });

            $('#save_proveedor').click(function(evento) {
                evento.preventDefault();
                if (ProveedorName.val().trim().length == 0) {
                    ProveedorName.focus();
                } else {
                    if (ProveedorContacto.val().trim().length == 0) {
                        ProveedorContacto.focus();
                    } else {
                        if (ControlBotonProveedor === 'save') {
                            saveProveedor();
                        } else {
                            updateProveedor(IDPROVEEDOR);
                        }
                    }
                }
            });

            $('#save_producto').click(function(evento) {
               evento.preventDefault();
                if($('#code_barra').val().trim().length == 0){
                $('#code_barra').focus();
                }else{
                if (NameProducto.val().length == 0) {
                
                NameProducto.focus();
                } else {
                if (PrecioVentaProducto.val().trim().length == 0) {
                PrecioVentaProducto.focus();
                } else {
                if (StockMinimo.val().trim().length == 0) {
                StockMinimo.focus();
                } else {
                if (ControlBotonProducto === 'save') {
                registrarProducto();
                } else {
                ModificarProducto(IDPRODUCTO);
                }
                }
                }
                }
                
                }
            });
            
            $('#save_cliente').click(function(evento){
                evento.preventDefault();

                if(NumDocCliente.val().trim().length == 0)
                {
                    NumDocCliente.focus();
                }else{
                    if(NameCliente.val().trim().length == 0)
                    {
                        NameCliente.focus();
                    }else{
                        if(ApellidosCliente.val().trim().length == 0)
                        {
                            ApellidosCliente.focus();
                        }else{
                           if(ControlBotonClientes == 'save')
                           {
                            saveCliente();
                           }else{
                             /*Modificar datos del cliente*/
                             updateCliente(IDCLIENTE);
                           }
                        }
                    }
                }
            });

            $('#search_cliente').click(function(){
             /// abrimos modal para buscar cliente para la venta
             $('#modal_listado_clientes').modal("show");
              /** Listar clientes para la venta **/ 
              ListarClientesParaLaVenta();

              /** Seleccionar cliente*/ 
              SeleccionarCliente(TablaSearchClienteVenta,'#lista_search_cliente tbody');
             });

             $('.salir_search_cliente').click(function(){
                $('#modal_listado_clientes').modal("hide");
             });
             $('.salir_search_producto').click(function(){
                $('#modal_listado_producto_venta').modal("hide");
             });

             $('#resultados').click(function(){
 
               window.open(RUTA+"resultados?fi="+$('#fi').val()+"&&ff="+$('#ff').val(),"_target");
             });

             /// Cancelar la venta
             $('.cancelar_venta').click(function(){
                CLIENTE_VENTA_ID = null;
                $('#documento_venta').val("");
                $('#cliente_venta').val("Público en general");
                $('#fecha_emision_venta').val("{{$this->FechaActual('Y-m-d')}}");
                $('#igv_venta').val("0.00");$('#total_venta').val("0.00");$('#sub_total_venta').val("0.00");
                $('#monto_recibido').val("");$('#vuelto').val("0.00");$('#proveedor_compra').val("");
                CancelVentaDetalle();
                
             });
              /// Cancelar la venta
              $('.cancelar_compra').click(function(){
               
                $('#fecha_emision_compra').val("{{$this->FechaActual('Y-m-d')}}");
                $('#igv_compra').val("0.00");$('#total_compra').val("0.00");$('#sub_total_compra').val("0.00");
                $('#proveedor_compra').val("");
                CancelCompraDetalle();
                
             });

             $('#agreagr_producto_venta').click(function(){
                
                $('#modal_listado_producto_venta').modal("show");
                ListarProductosParaLaVenta();
             });

             /** Guardar la venta **/ 
             $('#save_venta').click(function(){
                
               if($('#lista_detalle_venta_body tr').length >= 1)
               {
                 if($('#monto_recibido').val().trim().length == 0)
                 {
                    $('#monto_recibido').focus();
                 }else{
                    if($('#vuelto').val().trim().length == 0)
                    {
                       $('#vuelto').focus();
                    }else{
                       saveVentaFarmacia();
                    }
                 }
               }else{
                 Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Para completar la venta, debe de añadir como mínimo 1 producto a la cesta!",
                    icon:"error"
                 });
               }
             });
             
             /** Guardar la venta **/ 
             $('#save_compra').click(function(){
                
                if($('#lista_detalle_compra_body tr').length >= 1)
                {
                    if($('#proveedor_compra').val().trim().length == 0){
                         Swal.fire({
                            title:"MENSAJE DEL SISTEMA!!",
                            text:"PARA GUARDAR LA COMPRA, DEBES DE INDICAR EL PROVEEDOR!!",
                            icon:"info"
                         });
                    }else{
                        saveCompraFarmacia();
                    }
                }else{
                  Swal.fire({
                     title:"Mensaje del sistema!",
                     text:"Para completar la compra, debe de añadir como mínimo 1 producto a la cesta!",
                     icon:"error"
                  });
                }
              });

              $('#ff').change(function(){
                showRepoProductos($('#fi').val(),$(this).val());
              });

              $('#fi').change(function(){
                showRepoProductos($(this).val(),$('#ff').val());
              });
 

             /// evento change para mostrar historial de ventas por fecha
             $('#fecha_venta_historial').change(function(){
                
                mostrarHistoriaVentas($(this).val());
             });

             /// abrir modal para ver los productos existentes para la compra
             $('#agregar_producto_compra').click(function(){
                 showProductosParaLaCompra($('#almacen').val());
                $('#modal_listado_producto_compra').modal("show")
             });

             $('.salir_search_producto_compra').click(function(){
                $('#modal_listado_producto_compra').modal("hide");
                
                $('#precio_compra').val("");$('#cantidad_compra').val("");
                $('#producto_compra').val("");$('#empaque_compra').val("");
                $('#add_lote').hide();

             });


             $('#open_proveedor_compra').click(function(){
                $('#modal_open_proveedores').modal("show");
                BuscarProveedores();
                SeleccionarProveedor();
             });

             $('#cerrar_modal_buscar_proveedor').click(function(){
                $('#modal_open_proveedores').modal("hide");
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
                    title: "Estas seguro de eliminar al tipo producto " + TipoProducto + " ?",
                    text: "Al eliminar el tipo de producto se quitará automaticamente de la lista!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, eliminar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        ProcesoHabilitadoInhabilitadoTipoProducto(IDTIPOPRODUCTO, 'i');
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

                ProcesoHabilitadoInhabilitadoTipoProducto(IDTIPOPRODUCTO, 'h');

            });
        }

        // Proceso para habilitar e inhabilitar los tipos de productos
        function ProcesoHabilitadoInhabilitadoTipoProducto(id, condition) {
            $.ajax({
                url: RUTA + "app/farmacia/habilitar_e_inhabilitar/tipo_producto/" + id + "/" + condition,
                method: "POST",
                data: {
                    _token: TOKEN
                },
                dataType: "json",
                success: function(response) {
                    if (response.response == 1) {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: condition === 'i' ?
                                "Tipo producto quitado de la lista correctamente" :
                                "Tipo producto activado nuevamente",
                            icon: "success"
                        }).then(function() {
                            mostrarTipoProductos();
                        });
                    } else {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Error al realizar el proceso de eliminado o activación del tipo de producto",
                            icon: "error"
                        });
                    }
                }
            });
        }
        /** Eliminar tipo de producto*/
        function DeleteConfirmTipoProducto(Tabla, Tbody) {
            $(Tbody).on('click', '#delete_tipo', function() {
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();

                IDTIPOPRODUCTO = Data.id_tipo_producto;
                Swal.fire({
                    title: "Estas seguro de borrar al tipo producto " + Data.name_tipo_producto + "?",
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
        function DeleteTipoProducto(id) {
            $.ajax({
                url: RUTA + "app/farmacia/tipo_producto/delete/" + id,
                method: "POST",
                data: {
                    _token: TOKEN
                },
                dataType: 'json',
                success: function(response) {
                    if (response.response == 1) {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Tipo de producto eliminado por completo!",
                            icon: "success"
                        }).then(function() {
                            mostrarTipoProductos();
                        });
                    } else {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Error al eliminar tipo de producto!",
                            icon: "error"
                        })
                    }
                },
                error: function(err) {
                    Swal.fire({
                        title: "Mensaje del sistema!",
                        text: "Error al eliminar el tipo de producto seleccionado!",
                        icon: "error"
                    });
                }
            });
        }

        /*VERIFICAR SI HAY UNA CAJA APERTURADA PARA REALIZAR VENTA DE MEDICAMENTOS*/
        function VerifyCajaAbierta(){
            axios({
                url:RUTA+"verificar/caja/farmacia",
                method:"GET",
            }).then(function(response){
                
                if(response.data.caja != undefined){
                    if(response.data.caja.length <= 0){
                       $('#modal_alerta_para_caja').modal("show")
                    }
                }
            })
        }
    </script>
@endsection
