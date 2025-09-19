@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Gestión de egresos')

@section('css')
    <style>
        #detalle_egresos>thead>tr>th,
        .modal-header {
            background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);
        }
    </style>
@endsection
@section('contenido')
    <div class="row">
        <div class="col-12">
            <div class="card">
          <div class="card-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
             <h4 class="text-white letra">Registrar Gasto</h4>
          </div>
                <div class="card-body">
                    @if ($this->ExistSession("error"))
                        <div class="alert alert-danger mt-1">
                            {{$this->getSession("error")}}
                        </div>
                        {{$this->destroySession("error")}}
                    @endif
                     @if ($this->ExistSession("errors"))
                        <div class="alert alert-danger mt-1">
                           <ul>
                             @foreach ($this->getSession("errors") as $error)
                                <li>{{$error}}</li> 
                             @endforeach
                           </ul>
                        </div>
                        {{$this->destroySession("errors")}}
                    @endif
                 <form action="{{$this->route("egreso/gasto/save")}}" method="POST" id="save_gastos">
                      <input type="hidden" name="_token" value="{{$this->Csrf_Token()}}">
                    <div class="card-text mt-3">
                     <div class="form-floating mb-2">
                            <select name="categoria" id="categoria" class="form-select">
                                @foreach ($categorias as $cat)
                                <option value="{{$cat->id_categoria_egreso}}">{{strtoupper($cat->name_categoria_egreso)}}</option>
                                @endforeach
                            </select>
                            <label for="sede"><b>Tipo De Gasto <span class="text-danger">*</span></b></label>
                        </div>
                       @if ($this->profile()->rol === "admin_general")
                       <div class="form-floating mb-2">
                            <select name="tipo" id="tipo" class="form-select">
                                 <option value="c">CLINICA</option>
                                 <option value="f">FARMACIA</option>
                            </select>
                            <label for="tipo"><b>Donde desea registrar el gasto? <span class="text-danger">*</span></b></label>
                        </div>
                        <div class="form-floating mb-2">
                            <select name="sede" id="sede" class="form-select">
                                @foreach ($sedes as $sededata)
                                    <option value="{{$sededata->id_sede}}">{{strtoupper($sededata->namesede)}}</option>
                                @endforeach
                            </select>
                             <label for="sede"><b>Sede <span class="text-danger">*</span></b></label>
                        </div>
                        @endif
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" id="gasto_name" name="gasto_name"
                                placeholder="Escriba la categóría..." value="{{$this->old("gasto_name")}}">
                            <label for="categoria_name"><b>Nombre Gasto<span class="text-danger">*</span></b></label>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" id="monto_gasto" name="monto_gasto"
                                placeholder="Ingrese el monto del gasto..." value="{{$this->old("monto_gasto")}}">
                            <label for="monto_gasto"><b>Monto Del Gasto<span class="text-danger">*</span></b></label>
                        </div>
                        <div class="form-floating">
                            <input type="date" class="form-control" id="fecha" name="fecha" value="{{$this->FechaActual("Y-m-d")}}" >
                            <label for="fecha"><b>Fecha</b></label>
                        </div>
                    </div>
 
                    <div class="card-text text-center mb-3 mt-3">
                        <button class="btn_3d" id="save_egreso"><b>Guardar <i
                        class='bx bx-save'></i></b></button>
                    </div>
                 </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
 <script src="{{ URL_BASE }}public/js/egresos.js"></script>
    <script>
        var RUTA = "{{ URL_BASE }}" // la url base del sistema
        var TOKEN = "{{ $this->Csrf_Token() }}";
        var PROFILE_ = "{{ $this->profile()->rol }}";
     
        $(document).ready(function() {

            let NameSubCategoria = $('#name_subcategoria');
            let GastoSubCategoria = $('#gasto_subcategoria');
            let FechaSubCategoria = $('#fecha_subcategoria');
            let NameCategoria = $('#categoria_name');
 

            quitarSubCategoria();

            $('#add_subcat').click(function() {
                $('#modal_add_subcategoria_egresos').modal("show");
            });

            NameSubCategoria.keypress(function(evento){
                if(evento.which == 13){
                    evento.preventDefault();

                    if($(this).val().trim().length == 0)
                    {
                        $(this).focus();
                    }else{
                        GastoSubCategoria.focus();
                    }
                }

            });

            GastoSubCategoria.keypress(function(evento){
                if(evento.which == 13){
                    evento.preventDefault();

                    if($(this).val().trim().length == 0)
                    {
                        $(this).focus();
                    }else{
                        addDetalleSubCategoria(NameSubCategoria, GastoSubCategoria, 'detalle_egresos_body');    
                        NameSubCategoria.focus();
                        NameSubCategoria.val("");
                        GastoSubCategoria.val("");
                        FechaSubCategoria.val("{{ $this->FechaActual('Y-m-d') }}");
                    }
                }

            })
 
            $('#listar_egresos').click(function() {
               if(NameSubCategoria.val().trim().length == 0)
               {
                NameSubCategoria.focus();
               }else{
                if(GastoSubCategoria.val().trim().length == 0)
                {
                    GastoSubCategoria.focus();
                }else{
                    if (ExisteEgreso(NameSubCategoria)) {
                    Swal.fire({
                        title: "Mensaje del sistema!",
                        text: "Ya existe, agregue otro!",
                        icon: "info",
                        target: document.getElementById('modal_add_subcategoria_egresos')
                    }).then(function() {
                        NameSubCategoria.focus();
                        NameSubCategoria.val("");
                    });
                } else {
                     
                    addDetalleSubCategoria(NameSubCategoria, GastoSubCategoria, 'detalle_egresos_body');
                    NameSubCategoria.focus();
                    NameSubCategoria.val("");
                    GastoSubCategoria.val("");
                    FechaSubCategoria.val("{{ $this->FechaActual('Y-m-d') }}");

                }
                }
               }
            });

            $('#save_egreso').click(function() {
                if (NameCategoria.val().trim().length == 0) {
                    NameCategoria.focus();
                } else {
                    
                    saveCategoriaEgreso()
                }
            });
        });

        /// verificar existencia

        function ExisteEgreso(datatext) {
            let bandera = false;
            let Tabla = document.getElementById('detalle_egresos_body');

            let rowsData = Tabla.rows.length;

            for (let i = 0; i < rowsData; i++) {
                if (Tabla.rows[i].cells[0].innerHTML === datatext.val()) {
                    bandera = true;
                }

            }

            return bandera;
        }
    </script>
@endsection
