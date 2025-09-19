@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Pacientes')

@section('css')
    <style>
        #lista_testimonios_paciente>thead>tr>th {
            padding: 20px;
            background-color: #4169E1;
            color: aliceblue;

        }
    </style>
@endsection
@section('contenido')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">
                        <h5 class="float-end">Mis testimonios publicados</h5>
                        <button class="btn_twiter" id="create_testimonio">Publicar uno nuevo <i
                                class="fas fa-plus"></i></button>
                    </div>
                    <div class="card-text table-responsive">
                        <table class="table table-bordered table-striped nowrap responsive" id="lista_testimonios_paciente"
                            style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Acciones</th>
                                    <th>Testimonio</th>
                                    <th>Fecha de publicación</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal para crear nuevos testimonios- --}}
    <div class="modal fade" id="modal_testimonio">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><span id="title_accion_testimonio">Crear nuevo testimonio</span></h5>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="form_testimonio">
                        <input type="hidden" name="_token" value="{{ $this->Csrf_Token() }}">
                        <div class="form-group">
                            <label for="desc_testimonio"><b>Escriba su testimonio <span
                                        class="text-danger">*</span></b></label>
                            <textarea name="desc_testimonio" id="desc_testimonio" cols="30" rows="5" class="form-control"
                                placeholder="Escriba aquí...."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn_success_person" id="guardar"><b>Guardar <i class="fas fa-save"></i></b></button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL_BASE }}public/js/control.js"></script>
    <script>
        var TablaTestimonios;
        var RUTA = "{{ URL_BASE }}";// la url base del sistema
        var TOKEN = "{{ $this->Csrf_Token() }}";
        var IDTESTIMONIO;var Control;
        $(document).ready(function() {
            mostrarTestimonios();

            ConfirmDeleteTestimonio(TablaTestimonios, '#lista_testimonios_paciente tbody');
            EditarTestimonio(TablaTestimonios, '#lista_testimonios_paciente tbody');

            $('#create_testimonio').click(function() {
                Control = 'save';$('#desc_testimonio').val("");
                $('.modal-header>h5>span').text("Crear nuevo testimonio");
                $('.modal-header').css("background-color", "#4169E1");
                $('.modal-header>h5>span').css("color", "white");
                $('#modal_testimonio').modal("show");
            });

            /// Guardar el testimonio
            $('#guardar').click(function() {
               if($('#desc_testimonio').val().trim().length == 0)
               {
                $('#desc_testimonio').focus();
               }else{
                if(Control === 'save')
                {
                    saveTestimonio();
                }else{
                    /// actualizar
                    updateTestimonio();
                }
               }
            });
        });

        function mostrarTestimonios() {
            TablaTestimonios = $('#lista_testimonios_paciente').DataTable({
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
                    url: RUTA + "testimonios-publicados-por-paciente",
                    method: "GET",
                    dataSrc: "testimonios"
                },
                columns: [{
                        "data": "descripcion_testimonio"
                    },
                    {
                        "data": null,
                        render: function() {
                            return `<button class='btn btn-outline-warning btn-sm' id='editar_tes'><i class='fas fa-pencil'></i></button>
            <button class='btn btn-outline-danger btn-sm' id='delete_tes'><i class='fas fa-trash-alt'></i></button>`;
                        }
                    },
                    {
                        "data": "descripcion_testimonio"
                    },
                    {
                        "data": "fechahora"
                    }
                ]
            }).ajax.reload();

            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
            TablaTestimonios.on('order.dt search.dt', function() {
                TablaTestimonios.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }

        /// Confirmar si deseamos eliminar el testimonio
        function ConfirmDeleteTestimonio(Tabla, Tbody) {
            $(Tbody).on('click', '#delete_tes', function() {
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();

                IDTESTIMONIO = Data.id_testimonio;
                Swal.fire({
                    title: "Estas seguro de eliminar el testimonio seleccionado ?",
                    text: "Al aceptar, ya no hay vuelta atras, se eliminará por completo y no se podrá recuperar!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#4169E1",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si Eliminar <i class='fas fa-check'></i>!",
                    cancelButtonText:"Salir <b>X</b>"
                }).then((result) => {
                    if (result.isConfirmed) {
                        eliminarTestimonio(IDTESTIMONIO)
                    }
                });
            });
        }

        /// Editar los testimonios
        function EditarTestimonio(Tabla, Tbody) {
            $(Tbody).on('click', '#editar_tes', function() {
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                Control = 'editar';
                let Data = Tabla.row(fila).data();

                IDTESTIMONIO = Data.id_testimonio;

                $('#desc_testimonio').val(Data.descripcion_testimonio);
                $('.modal-header>h5>span').text("Editar testimonio");
                $('.modal-header>h5>span').css("color","white");
                $('.modal-header').css("background-color","#FFA500")
                $('#modal_testimonio').modal("show");
                
            });
        }

        /*Guardar los testimonio creados por el paciente*/
        function saveTestimonio() {
            let respuesta = crud(RUTA + "testimonio/save", 'form_testimonio');

            if (respuesta == 1) {
                Swal.fire({
                    title: "Mensaje del sistema!",
                    text: "Tu testimonio a sido publicado correctamente!",
                    icon: "success",
                    target: document.getElementById('modal_testimonio')
                }).then(function() {
                    mostrarTestimonios();
                    $('#form_testimonio')[0].reset();
                });
            } else {
                if (respuesta === 'existe') {
                    Swal.fire({
                        title: "Mensaje del sistema!",
                        text: "No es permitible publicar dos o más testimonios el mismo día!",
                        icon: "error",
                        target: document.getElementById('modal_testimonio')
                    }).then(function() {
                        $('#form_testimonio')[0].reset();
                    });
                } else {
                    Swal.fire({
                        title: "Mensaje del sistema!",
                        text: "Error al publicar tu testimonio!",
                        icon: "error",
                        target: document.getElementById('modal_testimonio')
                    }).then(function() {
                        $('#form_testimonio')[0].reset();
                    });
                }
            }
        }

        /// modificar testimonio
        /*Guardar los testimonio creados por el paciente*/
        function updateTestimonio() {
            let respuesta = crud(RUTA + "testimonio/update/"+IDTESTIMONIO, 'form_testimonio');

            if (respuesta == 1) {
                Swal.fire({
                    title: "Mensaje del sistema!",
                    text: "Tu testimonio a sido actualizado correctamente!",
                    icon: "success",
                    target: document.getElementById('modal_testimonio')
                }).then(function() {
                    mostrarTestimonios();
                    $('#form_testimonio')[0].reset();
                });
            } else {
                Swal.fire({
                        title: "Mensaje del sistema!",
                        text: "Error al publicar tu testimonio!",
                        icon: "error",
                        target: document.getElementById('modal_testimonio')
                    }).then(function() {
                        $('#form_testimonio')[0].reset();
                    });
            }
        }

        /// proceso para eliminar al testimonio
        function eliminarTestimonio(id)
        {
            $.ajax({
                url:RUTA+"testimonio/eliminar/"+id,
                method:"POST",
                data:{_token:TOKEN},
                dataType:"json",
                success:function(response)
                {
                    if(response.response == 1)
                    {
                    Swal.fire({
                    title: "Mensaje del sistema!",
                    text: "Testimonio eliminado!",
                    icon: "success",
                    }).then(function(){
                        mostrarTestimonios();
                    });
                    }else{
                    Swal.fire({
                    title: "Mensaje del sistema!",
                    text: "Error al eliminar testimonio seleccionado!",
                    icon: "error",
                    });
                    }
                }
            });
        }
    </script>
@endsection
