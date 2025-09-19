@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Configurar datos empresa')

@section('css')
    <style>
        #lista_notificaciones>thead>tr>th {
            background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);
            padding: 25px;
            color: azure;
            font-size: 12px;
        }

        .swal2-popup {
            max-height: 520px !important;
            max-width: 440px !important;
        }

        .swal2-icon {
            font-size: 13px !important;
        }

        .swal2-text {
            max-height: 70px;
        }

        .swal2-confirm {
            background: #38c5c5 !important;
        }
         
    </style>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%)">
                        <h5 class="letra text-white">Notificaciones de hoy</h5>
                </div>
                <div class="card-body">
                    <div class="card-text table-responsive">
                        <table class="table table-bordered table-striped nowrap responsive" id="lista_notificaciones">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Acciones</th>
                                    <th>Estado</th>
                                    <th>Tipo documento</th>
                                    <th>Num.Documento</th>
                                    <th>Persona</th>
                                    <th>Email</th>
                                    <th>Celular|Teléfono|WhatsApp</th>
                                    <th>Fecha</th>
                                    <th>Especialidad</th>
                                    <th>Médico</th>
                                    <th>Mensaje</th>
                                    <th>Sede</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL_BASE }}public/js/control.js"></script>
    <script>
        var TablaListaNotificaciones;
        var RUTA = "{{ URL_BASE }}"; // la url base del sistema
        var NOTIFICACIONID;
        var TOKEN = "{{ $this->Csrf_Token() }}";
        $(document).ready(function() {
            mostrarLasNotificacionesHoy();
            AtenderSolicitud(TablaListaNotificaciones, '#lista_notificaciones tbody');
            RechazarSolicitud(TablaListaNotificaciones, '#lista_notificaciones tbody');
        });

        function mostrarLasNotificacionesHoy() {
            TablaListaNotificaciones = $('#lista_notificaciones').DataTable({
                language: SpanishDataTable(),
                retrieve: true,
                responsive: true,
                processing: true,
                buttons: [
            'copyHtml5',
            'excelHtml5',
            'pdfHtml5'
        ],
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                ajax: {
                    url: RUTA + "notificaciones-por-rol",
                    method: "GET",
                    dataSrc: "response"
                },
                columns: [{
                        "data": "num_documento"
                    },
                    {
                        "data": null,
                        render: function(dato) {
                            if (dato.estado_not !== 'a' && dato.estado_not !== 'r') {
                                return `<button class='btn btn-outline-success btn-sm' id='atender'><i class='bx bx-check'></i></button>
            <button class='btn btn-outline-danger btn-sm' id='rechazar'><i class='bx bx-x'></i></button>`;
                            }
                            return '';
                        }
                    },
                    {
                        "data": "estado_not",
                        render: function(estado) {
                            if (estado === 'sa') {
                                return `<span class='badge bg-warning'>Solicitud sin atender </span>`;
                            } else {
                                if (estado === 'a') {
                                    return `<span class='badge bg-success'>Solicitud atendido <i class='bx bx-check'></i></span>`;
                                }
                            }

                            return `<span class='badge bg-danger'>Solicitud rechazado <i class='bx bx-x'></i></span>`;

                        }
                    },
                    {
                        "data": "name_tipo_doc",
                        render: function(tipodoc) {
                            return tipodoc.toUpperCase();
                        }
                    },
                    {
                        "data": "num_documento"
                    },
                    {
                        "data": "nombre_remitente",
                        render: function(remitente) {
                            return remitente.toUpperCase();
                        }
                    },
                    {
                        "data": "email",
                        render: function(email) {
                            if (email == null) {
                                return `<span class='badge bg-danger'>No especifica el correo electrónico </span>`;
                            }
                            return email.toUpperCase();
                        }
                    },
                    {
                        "data": "celular"
                    },
                    {
                        "data": "fecha_cita"
                    },
                    {
                        "data": "nombre_esp",
                        render: function(esp) {
                            return esp.toUpperCase();
                        }
                    },
                    {
                        "data": null,
                        render: function(persona) {
                            return persona.apellidos.toUpperCase() + " " + persona.nombres.toUpperCase();
                        }
                    },
                    {
                        "data": "mensaje",
                        render: function(msg) {
                            if (msg == null) {
                                return `<span class='badge bg-danger'>No especifica el mensaje </span>`;
                            }
                            return msg;
                        }
                    },
                    {
                        "data":"namesede",render:function(namesede){
                            return namesede.toUpperCase();
                        }
                    }

                ]

            }).ajax.reload();
            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
            TablaListaNotificaciones.on('order.dt search.dt', function() {
                TablaListaNotificaciones.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }

        /// atender la solicitud de la persona que desea sacar una cita 
        function AtenderSolicitud(Tabla, Tbody) {
            $(Tbody).on('click', '#atender', function() {
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();

                NOTIFICACIONID = Data.id_notificacion;
                Swal.fire({
                    title: "Estas seguro de atender la solicitud seleccionada?",
                    text: "Al aceptar no hay vuelta atras, la solicitud se mostrará como atendido!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    cancelButtonText: "Cancelar",
                    confirmButtonText: "Si, Antender!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: RUTA + "notificacion/cambiar-estado/" + NOTIFICACIONID + "/a",
                            method: "POST",
                            data: {
                                _token: TOKEN
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.response === 'ok-atendido') {
                                    Swal.fire({
                                        title: "Mensaje del sistema!",
                                        text: "La solicitud del paciente visitante " +
                                            Data
                                            .nombre_remitente + " a sido atendido!",
                                        icon: "success"
                                    }).then(function() {
                                       location.href = RUTA+"clinica/notificaciones";
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Mensaje del sistema!",
                                        text: "Error al procesar la solicitud del paciente visitante " +
                                            Data.nombre_remitente + " !",
                                        icon: "error"
                                    });
                                }
                            },
                            error: function(err) {
                                Swal.fire({
                                    title: "Mensaje del sistema!",
                                    text: "Error al procesar la solicitud del paciente visitante " +
                                        Data.nombre_remitente + " !",
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });
        }

        /// rechazar la solicitud de la persona que desea sacar una cita 
        function RechazarSolicitud(Tabla, Tbody) {
            $(Tbody).on('click', '#rechazar', function() {
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();

                NOTIFICACIONID = Data.id_notificacion;


                Swal.fire({
                    title: "Estas seguro de rechazar la solicitud seleccionada?",
                    text: "Al aceptar no hay vuelta atras!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    cancelButtonText: "Cancelar",
                    confirmButtonText: "Si eliminar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: RUTA + "notificacion/cambiar-estado/" + NOTIFICACIONID + "/r",
                            method: "POST",
                            data: {
                                _token: TOKEN
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.response === 'ok-rechazado') {
                                    Swal.fire({
                                        title: "Mensaje del sistema!",
                                        text: "La solicitud del paciente visitante " +
                                            Data
                                            .nombre_remitente + " a sido rechazado!",
                                        icon: "success"
                                    }).then(function() {
                                        mostrarLasNotificacionesHoy();
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Mensaje del sistema!",
                                        text: "Error al tratar de rechazar la solicitud del paciente visitante " +
                                            Data.nombre_remitente + " !",
                                        icon: "error"
                                    });
                                }
                            },
                            error: function(err) {
                                Swal.fire({
                                    title: "Mensaje del sistema!",
                                    text: "Error al tratar de rechazar la solicitud del paciente visitante " +
                                        Data.nombre_remitente + " !",
                                    icon: "error"
                                });
                            }
                        })
                    }
                });

            });
        }
    </script>
@endsection
