@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Gestión de categorías - egresos')

@section('css')
    <style>
        #tabla_egresos>thead>tr>th {
             background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%); 
        }
        #detalle_egresos_index>thead>tr>th{
            background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);  
        }

        #detalle_egresos_index_edit>thead>tr>th{
            background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);
        }

        td.hide_me {
            display: none;
        }
    </style>
@endsection
@section('contenido')
    <div class="row">
        <div class="col-12">
            <div class="card">
               <div class="card-header" style="background: linear-gradient(to bottom, rgba(179,220,237,1) 0%,rgba(41,184,229,1) 50%,rgba(188,224,238,1) 100%);">
                  <h4 class="float-end text-white">Gestión de categorías(Gastos)</h4>
                     <a href="{{ $this->route('categoria-egreso/create') }}" class="btn btn-primary rounded">Agregar uno nuevo <i
                         class='bx bx-plus'></i></a>
                </div>
                <div class="card-body">
                     @if ($this->ExistSession("success"))
                        <div class="alert alert-success mt-1">
                            {{$this->getSession("success")}}
                        </div>
                        {{$this->destroySession("success")}}
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered responsive nowrap" id="tabla_egresos" style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="py-3 text-white letra">#</th>
                                    <th class="d-none">ID</th>
                                    <th class="py-3 text-white letra">Tipo Gasto</th>
                                    <th class="py-3 text-white letra">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
    {{--MOdal para editar las categorias ---}}
    <div class="modal fade" id="modal_editar_categoria">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(to bottom, rgba(255,183,107,1) 0%,rgba(255,167,61,1) 50%,rgba(255,124,0,1) 51%,rgba(255,127,4,1) 100%);">
                    <h5><b class="letra text-white">Editar categoría(Gasto)</b></h5>
                </div>

                <div class="modal-body">
                    <form action="" method="post">
                        <input type="hidden" value="{{$this->Csrf_Token()}}">
                        <div class="form-floating">
                            <input type="text" name="name_categoria_editar" id="name_categoria_editar" class="form-control" placeholder="Escriba aquí..">
                            <label for="name_categoria_editar"><b>Nombre categoría <span class="text-danger">*</span></b></label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer border-2">
                    <button class="btn_blue" id="update_categorias"><b>Guardar cambios<i class='bx bx-save' ></i></b></button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- /** Inclimos archivos externos**/ -->
    <script src="{{ URL_BASE }}public/js/control.js"></script>
    <script src="{{ URL_BASE }}public/js/egresos.js"></script>
    <script>
        var RUTA = "{{ URL_BASE }}" // la url base del sistema
        var TOKEN = "{{ $this->Csrf_Token() }}";
        var TablaEgresos;
        var IDCATEGORIAEGRESO;
        var IDSUBCATEGORIAEGRESO;
        var TablaSubCategoriasData;
        $(document).ready(function() {
            let GastoSubCategoria_Index = $('#gasto_subcategoria_index');
            let NameSubCategoria_Index = $('#name_subcategoria_index');
            
            let FechaSubCategoria_Index = $('#fecha_subcategoria_index');
        
            /// mostramos los egresos
            MostrarLosEgresos();
            DestroyCategoriaEgreso();
            addSubCategoria();
            quitarSubCategoriaIndex();
            editarSubCategoriaDeCategoriaDeEgresos();
            EditarCategoriaEgreso();
            enter("name_categoria_editar","name_categoria_editar");
            NameSubCategoria_Index.keypress(function(evento){
                if(evento.which == 13){
                    evento.preventDefault();

                    if($(this).val().trim().length == 0)
                    {
                        $(this).focus();
                    }else{
                        GastoSubCategoria_Index.focus();
                    }
                }

            });
            GastoSubCategoria_Index.keypress(function(evento){
                if(evento.which == 13){
                    evento.preventDefault();

                    if($(this).val().trim().length == 0)
                    {
                        $(this).focus();
                    }else{
                        if (ExisteEgreso(NameSubCategoria_Index)) {
                    Swal.fire({
                        title: "Mensaje del sistema!",
                        text: "Ya existe, agregue otro!",
                        icon: "info",
                        target: document.getElementById('modal_add_subcategoria_egresos_index')
                    }).then(function() {
                        NameSubCategoria_Index.focus();
                        NameSubCategoria_Index.val("");
                    });
                } else {
                        addDetalleSubCategoria(NameSubCategoria_Index, GastoSubCategoria_Index,'detalle_egresos_body_index');    
                        NameSubCategoria_Index.focus();
                         
                        GastoSubCategoria_Index.val("");
                        NameSubCategoria_Index.val("");
                }
                    }
                }

            });

            $('#save_egresos_index').click(function(){
                
               
                if($('#detalle_egresos_body_index tr').length >0)
                {
                    if(saveSubCategoriasEgreso('detalle_egresos_body_index',IDCATEGORIAEGRESO) == 1){
                   Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Egresos añadidos correctamente a la categoría!",
                    icon:"success",
                    target:document.getElementById('modal_add_subcategoria_egresos_index')
                   }).then(function(){
                     $('#detalle_egresos_body_index tr').remove();
                     MostrarLosEgresos();
                   });
                }else{
                    alert("error");
                }
                }else{
                    Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "Agregue las sub categorías perteneciente a la categoría que deseas agregar!",
                            icon: "error",
                            target: document.getElementById('modal_add_subcategoria_egresos_index')
                  });
                }
            });

            $('#update_categorias').click(function(){
                if($('#name_categoria_editar').val().trim().length == 0){
                    $('#name_categoria_editar').focus();
                }else{
                    UpdateCategoriaEgreso(IDCATEGORIAEGRESO)
                }
            });

            $('#salir').click(function(){
                $('#modal_add_subcategoria_egresos_index').modal("hide");
                NameSubCategoria_Index.val("");
                GastoSubCategoria_Index.val("");
                FechaSubCategoria_Index.val("{{$this->FechaActual('Y-m-d')}}");
                $('#detalle_egresos_body_index tr').remove();  
            });

            $('#salir_listado').click(function(){
                $('#modal_edit_delete_subcategoria_egresos_index').modal("hide");
                NameSubCategoria_Index.val("");
                GastoSubCategoria_Index.val("");
                FechaSubCategoria_Index.val("{{$this->FechaActual('Y-m-d')}}");
                 
            });

            $('#save_egresos_index_edit').click(function(){
                $.ajax({
                    url:RUTA+"egreso/subcategoria/update/"+IDSUBCATEGORIAEGRESO,
                    method:"POST",
                    data:{
                        _token:TOKEN,
                        subcategoria_egreso:$('#name_subcategoria_index_edit').val(),
                        gasto:$('#gasto_subcategoria_index_edit').val(),
                        fecha:$('#fecha_subcategoria_index_edit').val()
                    },
                    dataType:"json",
                    success:function(response)
                    {
                        if(response.response == 1)
                        {
                            Swal.fire({
                                title:"Mensaje del sistema!",
                                text:"Egreso modificado",
                                icon:"success",
                                target:document.getElementById('modal_edit_delete_subcategoria_egresos_index')
                            }).then(function(){
                                MostrarLosSubCategoriasEgresos(IDCATEGORIAEGRESO);
                                $('#name_subcategoria_index_edit').val("");
                                $('#gasto_subcategoria_index_edit').val("");
                                $('#fecha_subcategoria_index_edit').val("{{$this->FechaActual('Y-m-d')}}");
                            });
                        }else{
                            Swal.fire({
                                title:"Mensaje del sistema!",
                                text:"Error al modificar datos del egreso",
                                icon:"error",
                                target:document.getElementById('modal_edit_delete_subcategoria_egresos_index')
                            });
                        }
                    },error:function(err)
                    {
                        Swal.fire({
                                title:"Mensaje del sistema!",
                                text:"Error al modificar datos del egreso",
                                icon:"error",
                                target:document.getElementById('modal_edit_delete_subcategoria_egresos_index')
                       });
                    }
                });
            });

        });

         /// verificar existencia

         function ExisteEgreso(datatext) {
            let bandera = false;
            let Tabla = document.getElementById('detalle_egresos_body_index');

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
