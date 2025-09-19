@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Gestión de inventarios')
@section('clase_ocultar', 'd-none')
@section('expandir', 'layout-content-navbar layout-without-menu')

@section('css')
<link rel="stylesheet" href="{{$this->asset("css/cssfarmacia.css")}}">
  <style>
    #lista_productos>thead{
        background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);;
    }
  </style>
@endsection

@section('contenido')
   <div class="card" id="farmacia_card">
        <div class="card-header header_card_farmacia"
            style="background: linear-gradient(to bottom, rgba(179,220,237,1) 0%,rgba(41,184,229,1) 50%,rgba(188,224,238,1) 100%);">
            <h5 class="text-white float-start">Gestión de inventario</h5>
            <a href="{{ $this->route('app/farmacia') }}" class="btn btn-primary rounded btn-sm float-end">Ir a Farmacia<i
                    class='bx bx-arrow-back'></i></a>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs card-header-tabs" id="tab_inventarios">
                @if ($this->profile()->rol === 'admin_general' || $this->profile()->rol === 'admin_farmacia' )
                <li class="nav-item">
                    <a class="nav-link active" aria-current="true" href="#existencia" id="existencia_"><b>Existencia
                            de productos</b>
                        <img src="{{ $this->asset('img/icons/unicons/categoria_farmacia.ico') }}" class="menu-icon" alt="">
                    </a>
                </li>
               
                <li class="nav-item">
                    <a class="nav-link" href="#laboratorio" tabindex="-1" aria-disabled="true"
                        id="laboratorios_"><b>Kardex</b>
                        <img src="{{ $this->asset('img/icons/unicons/laboratorio_farmacia.ico') }}" class="menu-icon" alt="">
                    </a>
                </li>
    
                @endif
            </ul>
    
            {{-- Opciones del tab-- --}}
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade @if ($this->profile()->rol === 'admin_general' || $this->profile()->rol === 'admin_farmacia') show active @endif"
                    id="tipo_producto" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    @if ($this->profile()->rol === "admin_general")
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-12">
                            <div class="form-floating mb-2">
                                <select name="sede" id="sede" class="form-select">
                                    <option selected disabled>---- Seleccione Sucursal ----</option>
                                    @foreach ($sedes as $sucursal)
                                    <option value="{{$sucursal->id_sede}}">{{strtoupper($sucursal->namesede)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                    <hr>
                    <table class="table table-bordered table-striped table-sm table-hover responsive nowrap"
                        id="lista_productos">
                        <thead>
                            <tr>
                                <th class="py-3 text-white letra">Acciones</th>
                                <th class="py-3 text-white letra">Codigo Producto</th>
                                <th class="py-3 text-white letra">Producto</th>
                                <th class="py-3 text-white letra">Stock</th>
                                <th class="py-3 text-white letra">Estado</th>
                                <th class="py-3 text-white letra">Precio {{count($this->BusinesData()) == 1 ?
                                    $this->BusinesData()[0]->simbolo_moneda : 'S/.'}}</th>
                                <th class="py-3 text-white letra">Tipo</th>
                                <th class="py-3 text-white letra">Presentación</th>
                                <th class="py-3 text-white letra">Laboratorio</th>
                                <th class="py-3 text-white letra">Marca</th>
                                <th class="py-3 text-white letra">Proveedor</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
{{--VENTANA MODAL PARA VER LOS LOTES DE UN PRODUCTO.---}}
        <div class="modal fade" id="modal_show_lotes_producto" data-bs-backdrop="static">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header"
                        style="background: linear-gradient(to bottom, rgba(184,225,252,1) 0%,rgba(169,210,243,1) 10%,rgba(144,186,228,1) 25%,rgba(144,188,234,1) 37%,rgba(144,191,240,1) 50%,rgba(107,168,229,1) 51%,rgba(162,218,245,1) 83%,rgba(189,243,253,1) 100%);">
                         <h4><b id="prod_lotes" class="text-white letra"></b></h4>
                        <button class="btn btn-danger btn-sm float-end" id="salir_showlotes">X</button>
                    </div>
        
                    <div class="modal-body">
                        <div class="alert alert-success" id="texto_alerta" style="display: none"></div>
                        <div class="alert alert-danger" id="texto_alerta_error" style="display: none"></div>
                        <table class="table table-bordered table-striped table-hover table-sm nowrap responsive"
                            id="tabla_lotes_producto" style="width: 100%">
                            <thead
                                style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                                <tr>
                                    <th class="py-3 text-white letra">Cod.Lote</th>
                                    <th class="py-3 text-white letra">Fecha Producción</th>
                                    <th class="py-3 text-white letra">Fecha Vencimiento</th>
                                    <th class="py-3 text-white letra">Está vencido?</th>
                                    <th class="py-3 text-white letra">Faltan para vencer</th>
                                    <th class="py-3 text-white letra">Existencia</th>
                                    <th class="py-3 text-white letra">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    
    {{--VENTANA MODAL PARA AGREGAR LOTES --}}
    <div class="modal fade" id="modal_add_lote" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(to bottom, rgba(184,225,252,1) 0%,rgba(169,210,243,1) 10%,rgba(144,186,228,1) 25%,rgba(144,188,234,1) 37%,rgba(144,191,240,1) 50%,rgba(107,168,229,1) 51%,rgba(162,218,245,1) 83%,rgba(189,243,253,1) 100%);">
                    <h4 class="text-white letra float-end">Agregar Lote <i class="fas fa-plus"></i></h4>
                    <button class="btn btn-danger btn-sm float-start" id="salir">X</button>
                </div>
    
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-5 col-lg-5 col-12">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control" id="productotexto" readonly>
                                <label for="productotexto"><b>PRODUCTO</b></label>
                            </div>
                        </div>
    
                        <div class="col-xl-4 col-lg-4 col-12">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control" id="cantidad_total" readonly>
                                <label for="cantidad_total"><b>CANTIDAD TOTAL</b></label>
                            </div>
                        </div>
    
                        <div class="col-xl-3 col-lg-3 col-12">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control" id="cantidad_total_restante" readonly>
                                <label for="cantidad_total"><b>CANTIDAD RESTANTE</b></label>
                            </div>
                        </div>
                    </div>
                    <form method="POST" id="formlote">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-12">
                                <div class="form-floating mb-2">
                                    <input type="text" id="codigolote" class="form-control" placeholder="Código Lote...">
                                    <label for="codigolote"><b>CÓDIGO LOTE....</b></label>
                                    <span id="errorcodigo" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-12">
                                <div class="form-floating mb-2">
                                    <input type="date" id="fechaprodlote" class="form-control" value="{{$this->FechaActual("Y-m-d")}}">
                                    <label for="fechaprodlote"><b>FECHA DE PRODUCCIÓN....</b></label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-12">
                                <div class="form-floating mb-2">
                                    <input type="date" id="fechavenlote" class="form-control" value="{{$this->addRestFecha("Y-m-d","+380 day")}}">
                                    <label for="fechavenlote"><b>FECHA DE VENCIMIENTO....</b></label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-12">
                                <div class="form-floating mb-2">
                                    <input type="number" id="existencialote" class="form-control" min="0" placeholder="0">
                                    <label for="existencialote"><b>EXISTENCIA....</b></label>
                                     <span id="errorcantidad" class="text-danger"></span>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
    
            <div class="modal-footer border-2">
                <button class="btn_blue" id="save_lote"><b>Guardar <i class="fas fa-save"></i></b></button>
            </div>
        </div>
    </div>

@endsection
@section('js')
<script src="{{ URL_BASE }}public/js/control.js"></script>
<script src="{{ URL_BASE }}public/js/inventario.js"></script>
<script>
    var RUTA = "{{URL_BASE}}";
    var TOKEN = "{{ $this->Csrf_Token() }}";
    var PROFILE_ = "{{ $this->profile()->rol }}";
    var TablaProductos,TablaLotesProducto;
    var PRODUCTOID,SEDEID,STOCKPRODUCTO;

    $(document).ready(function(){

        if(PROFILE_ === "admin_farmacia"){
            showProductos();
            AgregarLote(TablaProductos,'#lista_productos tbody');
            ShowLotesProducto(TablaProductos,'#lista_productos tbody');
        }
        $('#sede').change(function(){
            showProductos();
            TablaProductos.ajax.reload();
            AgregarLote(TablaProductos,'#lista_productos tbody');
            ShowLotesProducto(TablaProductos,'#lista_productos tbody');
        });

        $('#salir').click(function(){
            $('#modal_add_lote').modal("hide");
            $('#formlote')[0].reset();

            $('#errorcodigo').text("");
            $('#errorcantidad').text("");
            
        });
        $('#salir_showlotes').click(function(){
            $('#modal_show_lotes_producto').modal("hide");
            $('#texto_alerta_error').hide();
            $('#texto_alerta').hide();
            $('#texto_alerta_error').text("");
            $('#texto_alerta').text("");
        });

        $('#save_lote').click(function(evento){
           evento.preventDefault();
            let TotalRestante = parseInt($('#cantidad_total_restante').val());
            if(TotalRestante == 0){
            Swal.fire({
            title:"MENSAJE DEL SISTEMA!!",
            text:"ERROR, LA CANTIDAD TOTAL DE LOS LOTES DEL PRODUCTO ALCANZARON LA CANTIDAD TOTAL DEL PRODUCTO!!!",
            icon:"warning",
            target:document.getElementById('modal_add_lote')
            })
            }else{
            if(parseInt($('#existencialote').val().trim()) > TotalRestante){
            $('#existencialote').select();
            Swal.fire({
            title:"MENSAJE DEL SISTEMA!!",
            text:"LA CANTIDAD SUMADO DE LOS LOTES INGRESADOS PARA ESTE PRODUCTO , INCLUIDO CON ESTE LOTE QUE DESEAS REGISTRAR,SUPERA A LA CANTIDAD DEL PRODUCTO!!!",
            icon:"warning",
            target:document.getElementById('modal_add_lote')
            });
            }else{
            saveLoteProducto()
            }
            }
        });
    })
</script>
@endsection