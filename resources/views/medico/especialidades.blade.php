@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Mis especialidades')

@section('css')
    <style>
        #especialidades_medico>thead>tr>th {
            background:linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);
            color: aliceblue;
            padding: 18px;
        }
    </style>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">
                        <h4 class="float-start">Mis especialidades</h4>
                        <button class="btn btn-primary rounded float-end mb-2" id="add_modal_esp">Agregar especialidad <i class='bx bx-plus'></i></button>
                    </div>

                    <div class="card-text">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover nowrap responsive"
                                style="width: 100%" id="especialidades_medico">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Especialidad</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--- modal para agregar nuevas especialidades al médico ----}}
    <div class="modal fade" id="modal_add_esp">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%)">
                    <h5 class="text-white">Agregar especialidades</h5>
                </div>
                <div class="modal-body">
                    <span class="text-danger view_error text-center" style="display: none">Seleccione por lo menos una especialidad para el médico 😢😔!</span>
                    <div class="table-responsive">
                       <form action="{{$this->route("medico/add/especialidad")}}" method="post" id="form_espec_add">
                         <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                        <table class="table table-bordered table-striped table-hover nowrap responsive" id="especialidades_" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Especialidad</th>
                                    <th class="text-center">Seleccionar</th>
                                </tr>
                            </thead>
                             
                        </table>
                       </form>
                    </div>
                </div>
                <div class="modal-footer border-2 text-center">
                  <button class="btn_info_tw" id="save_add_esp_medico">Guardar <i class='bx bxs-receipt'></i></button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ URL_BASE }}public/js/control.js"></script>
    <script>
        var TablaMisEspecialidades;
        var TablaEspecialidades;
        var RUTA = "{{ URL_BASE }}" // la url base del sistema
        var TOKEN = "{{ $this->Csrf_Token() }}";
        var ID_ESP_MEDICO;
        $(document).ready(function() {
            mostrarMisEspecialidades();
            quitarEspecialidadMedico(TablaMisEspecialidades, '#especialidades_medico tbody');

            $('#add_modal_esp').click(function(){
                $('.view_error').hide();
                showEspecialidades();
                $('#modal_add_esp').modal("show");
            });
            $('#save_add_esp_medico').click(function(evento){
                evento.preventDefault();
                addEspecialidadMedico();
            });
        });

        function mostrarMisEspecialidades() {
            TablaMisEspecialidades = $('#especialidades_medico').DataTable({
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
                    url: RUTA + "medico/show/especialidades",
                    method: "GET",
                    dataSrc: "response",
                },
                columns: [{
                        "data": "nombre_esp"
                    },
                    {
                        "data": "nombre_esp",
                        render: function(name_esp) {
                            return `<span class='badge bg-primary'>` + name_esp + `</span>`;
                        }
                    },
                    {
                        "data": null,
                        render: function() {
                            return `
            <button id='quitar' class='btn rounded btn-danger btn-sm'><b>X</b></button>
            `;
                        }
                    }
                ]
            }).ajax.reload();

            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
            TablaMisEspecialidades.on('order.dt search.dt', function() {
                TablaMisEspecialidades.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }

        /// eliminar las especialidades asignadas al médico
        function quitarEspecialidadMedico(Tabla, Tbody) {
            $(Tbody).on('click', '#quitar', function() {
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();
                ID_ESP_MEDICO = Data.id_medico_esp;
                Swal.fire({
                    title: "Estas seguro de eliminar esta especialidad del médico?",
                    text: "Al eliminarlo, se quitará automaticamente de la lista de especialidades asignadas del médico!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, eliminar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                       deleteEspecialidadMedico(ID_ESP_MEDICO);
                    }
                });
            });
        }

        /// eliminar la especialidad asignada del médico
        function  deleteEspecialidadMedico(id)
        {
            $.ajax({
                url:RUTA+"medico/especialidad/delete/"+id,
                method:"POST",
                data:{
                    token_:TOKEN
                },
                dataType:"json",
                success:function(response)
                {
                    if(response.response === 'ok')
                    {
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Especialidad eliminado de la lista correctamente!",
                            icon:"success"
                        }).then(function(){
                            mostrarMisEspecialidades();
                            
                        });
                    }else{
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Error al eliminar la especialidad seleccionada!",
                            icon:"error"
                        })
                    }
                }
            });
        }

        /// mostrar las especialidades existentes
        function showEspecialidades()
        {
            TablaEspecialidades = $('#especialidades_').DataTable({
                retrieve:true,
                responsive:true,
                processing:true,
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                ajax:{
                    url:RUTA+"medico/especialidades/no_asignadas",
                    method:"GET",
                    dataSrc:"response",
                },
                columns:[
                    {"data":"nombre_esp"},
                    {"data":"nombre_esp"},
                    {"data":"id_especialidad",render:function(espe_id){
                        return `
                         <div class='text-center'>
                            <input type="checkbox" name="select_esp[]" id="select_espe"
                         style="width: 20px;height:20px;" value="`+espe_id+`"></div>
                        `;
                    }},
                ] 
            }).ajax.reload();
            TablaEspecialidades.on('order.dt search.dt', function() {
                TablaEspecialidades.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }

        /// agregar nuevas especialidades al médico
        function addEspecialidadMedico()
        {
            $.ajax({
                url:RUTA+"medico/add/especialidad",
                method:"POST",
                data:$('#form_espec_add').serialize(),
                dataType:"json",
                success:function(response)
                {
                   if(response.response === 'vacio')
                   {
                     $('.view_error').show(400);
                   }else{
                    if(response.response === 'ok')
                    {
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Especialidades asignados correctamente al médico 😁😀😎🎉",
                            icon:"success",
                            target:document.getElementById('modal_add_esp')
                        }).then(function(){
                          showEspecialidades();
                          mostrarMisEspecialidades();
                        });
                    }
                   }
                }
            });
        }
    </script>
@endsection
