@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Generar-receta-médica')

@section('css')
 <style>
  
 </style>
@endsection

@section("contenido")
  <div class="card">
    <ul class="nav nav-tabs nav-fill" role="tablist" id="ul_recetas">
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#recetas_generado" id="recetas_generado_"
                aria-controls="navs-justified-home" aria-selected="true" style="color: #636d69">
                <img src="{{$this->asset('img/icons/unicons/receta.ico')}}" class="menu-icon" alt="" style="height: 20px"> <b>Recetas generadas</b>

            </button>
        </li>
    </ul>

    <div class="tab-content">

        <div class="tab-pane fade show active" id="recetas_generado" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-bordred table-striped nowrap" id="tabla_recetas" style="width: 100%">
                    <thead style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                        <tr>
                            <th class="text-white py-3 letra">#</th>
                            <th class="text-white py-3 letra">Num.Receta</th>
                            <th class="text-white py-3 letra">Fecha</th>
                            <th class="text-white py-3 letra">Paciente</th>
                            <th class="text-white py-3 letra">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
   
  </div>
  {{--- MODAL PARA BUSCAR AL PACIENTE ----}}
  <div class="modal fade" id="modal_search_paciente">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="card-header px-4 bg-success">
                <h4 class="text-white">Buscar paciente</h4>
            </div>
            <div class="modal-body">
                <div class="card-text px-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm table-hover nowrap responsive" id="tabla_pacientes_search"
                        style="width: 100%">
                           <thead>
                            <tr>
                                <th>#</th>
                                <th># Documento</th>
                                <th>Paciente</th>
                                <th>Enviar</th>
                            </tr>
                           </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

  {{--- MODAL PARA MOSTRAR LOS MEDICAMENTOS EXISTENTES----}}
  <div class="modal fade" id="modal_search_producto_receta">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="card-header px-4 bg-success">
                <h4 class="text-white">Buscar producto</h4>
            </div>
            <div class="modal-body">
                <div class="card-text px-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped nowrap responsive" id="tabla_productos_search"
                        style="width: 100%">
                           <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo</th>
                                <th>Producto</th>
                                <th>Recetar</th>
                            </tr>
                           </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection

@section('js')
<script src="{{URL_BASE}}public/js/control.js"></script>
    <script>

        /***VARIABLES **/
        var TablaPacientesBuscar;
        var TablaConsultarProductos;
        var RUTA = "{{URL_BASE}}";
        var TOKEN = "{{$this->Csrf_Token()}}";
        var PACIENTE_ID;
        var RecetasGenerado;
        var RECETAID;
     
        $(document).ready(function(){
            let InputPaciente = $('#input_paciente');
            let InputMedicamento = $('#medicamento') ;
            let Frecuencia = $('#frecuencia');
            let Dosis = $('#dosis');
            let Cantidad = $('#cantidad');

            InputPaciente.focus();

            showReceta();
            VerRecetasGenerados();
            printRecetaElectronica(RecetasGenerado,'#tabla_recetas tbody')
            DeleteConfirmRecetaElectronica(RecetasGenerado,'#tabla_recetas tbody')

            $('#lista_receta').on('click','#delete_receta_detalle',function(){
                let fila = $(this).parents('tr');

                let productoSeleccionado = fila.find('td').eq(2).text();
                DeleteRecetaSeleccionado(productoSeleccionado);
            });

            $('#ul_recetas button').on('click',function(){
                Id = $(this)[0].id;
                
                if(Id === "generar_receta_"){

                }else{
                     VerRecetasGenerados();
                    printRecetaElectronica(RecetasGenerado,'#tabla_recetas tbody')
                    DeleteConfirmRecetaElectronica(RecetasGenerado,'#tabla_recetas tbody')
                }
                $(this).tab("show");
            });
            /// enter
            InputPaciente.keypress(function(evento){
                if(evento.which == 13){
                    if($(this).val().trim().length == 0){
                        $(this).focus();
                    }else{
                        InputMedicamento.focus();
                    }
                }
            });

            InputMedicamento.keypress(function(evento){
                if(evento.which == 13){
                    if($(this).val().trim().length == 0){
                        $(this).focus();
                    }else{
                        Frecuencia.focus();
                    }
                }
            });

            Frecuencia.keypress(function(evento){
                if(evento.which == 13){
                    if($(this).val().trim().length == 0){
                        $(this).focus();
                    }else{
                        Dosis.focus();
                    }
                }
            });
            Dosis.keypress(function(evento){
                if(evento.which == 13){
                    if($(this).val().trim().length == 0){
                        $(this).focus();
                    }else{
                        $('#cantidad').focus();
                    }
                }
            });

            $('#cancelar_receta').click(function(){
                 eliminarDetalleReceta();
                 InputPaciente.val("");
                 InputMedicamento.val("");
                 Frecuencia.val("");
                 Dosis.val("");
                 Cantidad.val("");
                 InputPaciente.focus();
                 showReceta();
            });

            Cantidad.keypress(function(evento){
                if(evento.which == 13){
                    if($(this).val().trim().length == 0){
                        $(this).focus();
                    }else{
                       if(InputPaciente.val().trim().length == 0 || InputMedicamento.val().trim().length == 0 ||
                         Frecuencia.val().trim().length == 0 || Dosis.val().trim().length == 0 || Cantidad.val().trim().length == 0){
                            Swal.fire({
                                title:"¡ADVERTENCIA DEL SISTEMA!",
                                text:"Complete los datos requeridos para el detalle de la receta!",
                                icon:"warning"
                            });
                         }else{
                            AgregarALaCestaRecetaElectronica(
                                        InputMedicamento,
                                        Frecuencia,
                                        Dosis,
                                        Cantidad);
                             
                         }
                    }
               }
            });
            
            /// buscar paciente(mostrar modal de pacientes)
            $('#buscar_paciente').click(function(){
                
                $('#modal_search_paciente').modal("show");
                mostrarPacientes();
                seleccionarPaciente(TablaPacientesBuscar,'#tabla_pacientes_search tbody');
            });

            /// buscar productos para la recetar al paciente
            $('#open_modal_productos').click(function(){
                $('#modal_search_producto_receta').modal("show");
                mostrarProductos();
                seleccionarProducto(TablaConsultarProductos,'#tabla_productos_search tbody');
            });

            $('#save_receta').click(function(){ 
                 if(InputPaciente.val().trim().length == 0){
                    $('#error_paciente').show(400);
                    InputPaciente.focus();
                 }else{
                     if($('#lista_receta tr').length >0){
                        if($('#tiempo_tratamiento').val().trim().length == 0 || $('#tiempo_tratamiento').val() <= 0){
                             $('#tiempo_tratamiento').focus();
                            Swal.fire({
                                title:"¡Hay un problema!",
                                text:"Ingrese el tiempo de tratamiento > 0 días!",
                                icon:"error"
                            });
                        }else{
                        saveReceta(PACIENTE_ID,InputPaciente.val(),$('#tiempo_tratamiento').val());
                        }
                     }else{
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Complete el detalle de la receta!",
                            icon:"error"
                        });
                     }
                 }
            });
        });

        /**MOSTRAR PACIENTES (CONSULTAR)**/
        function mostrarPacientes()
        {
            TablaPacientesBuscar = $('#tabla_pacientes_search').DataTable({
                language:SpanishDataTable(),
                retrieve:true,
                "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
                }],
                ajax:{
                    url:RUTA+"consultar/pacientes",
                    method:"GET",
                    dataSrc:"pacientes",
                },
                columns:[
                    {"data":"apellidos"},
                    {"data":"documento"},
                    {"data":null,render:function(pac){
                        return (pac.apellidos+" "+pac.nombres).toUpperCase()
                    }},
                    {"data":null,render:function(){
                        return `<button class='btn btn-primary btn-rounded btn-sm' id='seleccionar_paciente'><i class='fas fa-check'></i></button>`;
                    }}
                ]
            }).ajax.reload();
            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
           TablaPacientesBuscar.on( 'order.dt search.dt', function () {
            TablaPacientesBuscar.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
          }).draw();
        }


        /// seleccionar paciente
        function seleccionarPaciente(Tabla,Tbody)
        {
            $(Tbody).on('click','#seleccionar_paciente',function(){
                /// fila seleccionada
                let fila = $(this).parents("tr");

                if(fila.hasClass("child")){
                    fila = fila.prev();
                }
                
                let Datos = Tabla.row(fila).data();

                PACIENTE_ID = Datos.id_paciente;
                $('#input_paciente').val((Datos.apellidos+" "+Datos.nombres).toUpperCase())
                $('#modal_search_paciente').modal("hide");
                $('#medicamento').focus();
            });
        }

        /// consultar productos
        function mostrarProductos()
        {
            TablaConsultarProductos = $('#tabla_productos_search').DataTable({
                language:SpanishDataTable(),
                retrieve:true,
                "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
                }],
                ajax:{
                    url:RUTA+"consultar/productos/clinica",
                    method:"GET",
                    dataSrc:"productos",
                },
                columns:[
                    {"data":"nombre_producto"},
                    {"data":"name_tipo_producto",render:function(datap){
                        return `<span class='badge bg-danger'>`+datap.toUpperCase()+`</span>`;
                    }},
                    {"data":null,render:function(prod){
                        return prod.nombre_producto.toUpperCase()
                    }},
                    {"data":null,render:function(){
                        return `<button class='btn btn-primary btn-rounded btn-sm' id='seleccionar_producto'><i class='fas fa-cart-shopping'></i></button>`;
                    }}
                ]
            }).ajax.reload();
            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
           TablaConsultarProductos.on( 'order.dt search.dt', function () {
            TablaConsultarProductos.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
          }).draw();
        }

        /// seleccionar producto
        function seleccionarProducto(Tabla,Tbody)
        {
            $(Tbody).on('click','#seleccionar_producto',function(){
                /// fila seleccionada
                let fila = $(this).parents("tr");

                if(fila.hasClass("child")){
                    fila = fila.prev();
                }
                
                let Datos = Tabla.row(fila).data();

                $('#medicamento').val((Datos.nombre_producto).toUpperCase())
                $('#modal_search_producto_receta').modal("hide");
                $('#frecuencia').focus();
            });
        }

        /// agregar a la cesta la receta médica del paciente
        function AgregarALaCestaRecetaElectronica(Med,Frec,Dos,Can)
        {
            $.ajax({
                url:RUTA+"/guardar-receta",
                method:"POST",
                data:{
                    token_:TOKEN,
                    producto:Med.val(),
                    frecuencia:Frec.val(),
                    dosis:Dos.val(),
                    cantidad:Can.val()
                },
                dataType:"json",
                success:function(response){
                    if(response.erroracceso != undefined){
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Error, uste no tiene permiso para realizar esta acción!",
                            icon:"error"
                        });
                    }else{
                        if(response.response != undefined && response.response === "agregado"){
                            Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Agregado para la receta!",
                            icon:"success"
                        }).then(function(){
                            showReceta();
                            $('#medicamento').focus();
                            $('#medicamento').val("");
                            $('#frecuencia').val("");
                            $('#dosis').val("");
                            $('#cantidad').val("");
                        }); 
                        }else{
                            Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"El medicamento que deseas agregar ya esta en el detalle de la receta!",
                            icon:"warning"
                        }).then(function(){
                            $('#medicamento').val("");
                        }); 
                        }
                    }

                     
                }

            })
        }

        /// Validacion de formulario
        function ValidateForm(Paciente,Med,Frec,Dos,Cant)
        {
            let Errors = "";
            if(Paciente.val().trim().length == 0){
            $('#error_paciente').show(600)
            Errors = "si";
            }else{
               $('#error_paciente').hide(400); 
               Errors = "no";              
            }
            if(Med.val().trim().length == 0){
                $('#error_producto').show(600)
                Errors = "si";
            }else{
               $('#error_producto').hide(400); 
               Errors = "no";
            }

            if(Frec.val().trim().length == 0){
                $('#error_frecuencia').show(600);
                Errors = "si";
            }else{
               $('#error_frecuencia').hide(400); 
               Errors = "no";
            }
            if(Dos.val().trim().length == 0){
                    $('#error_dosis').show(600)
                    Errors = "si";
            }else{
               $('#error_dosis').hide(400); 
               Errors = "no";
            }
            if(Cant.val().trim().length == 0){
                $('#error_cantidad').show(600);
                Errors = "si";
            }else{
               $('#error_cantidad').hide(400); 
               Errors = "no";
            }

            return Errors;
        }

        /// mostrar los productos añadidos a la receta
        function showReceta()
        {
            let tr= ``,contador = 0;
            $.ajax({
                url:RUTA+"receta-detalle/all",
                method:"GET",
                dataType:"json",
                success:function(response){
                  
                    response = Object.values(response.receta);
                    if(response.length > 0){
                        response.forEach(prod => {
                            contador++;
                            tr+=`<tr>
                              <td>`+contador+`</td>
                              <td>
                               <button class='btn btn-danger rounded btn-sm' id='delete_receta_detalle'><b>X</b></button>  
                              </td>
                              <td>`+prod.producto+`</td>
                              <td>`+prod.frecuencia+`</td>
                              <td>`+prod.dosis+`</td>
                              <td>`+prod.cantidad+`</td>
                            </tr>`;
                        });
                    }else{
                        tr+=`<td colspan='5'>
                            <span class='text-danger'>No hay productos para mostrar....</span>
                            </td>`;
                    }

                    $('#lista_receta').html(tr);
                }
            })
        }

        /// Eliminar el detalle seleccionado de la receta
        function DeleteRecetaSeleccionado(producto){
            $.ajax({
                url:RUTA+"receta/delete-detalle-seleccionado",
                method:"POST",
                data:{
                    token_:TOKEN,
                    producto:producto,
                },
                dataType:"json",
                success:function(response){
                    if(response.response === 'eliminado'){
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"Producto eliminado de la lista de la receta.",
                            icon:"success"
                        }).then(function(){
                            showReceta();
                        });
                    }
                }
            });
        }

        /// Guardar receta medica
        function saveReceta(pacienteIdData,pacienteRecetaData,tiempotratamiento){
            $.ajax({
                url:RUTA+"receta/save",
                method:"POST",
                data:{
                    token_:TOKEN,
                    paciente_id:pacienteIdData,
                    paciente_receta:pacienteRecetaData,
                    tiempo_tratamiento:tiempotratamiento
                },
                dataType:"json",
                success:function(response)
                {
                    let datos = response.response.split("-");

                    if(datos[0] === 'ok'){
                         
                        Swal.fire({
                            title:"Mensaje del sistema!",
                            text:"La receta se a registrado correctamente!",
                            icon:"success"
                        }).then(function(){
                            window.open(RUTA+"paciente/receta_medica?v="+datos[1]);
                               $('#input_paciente').val("");
                               $('#medicamento').val("") ;
                               $('#frecuencia').val("");
                               $('#dosis').val("");
                               $('#cantidad').val("");
                               $('#tiempo_tratamiento').val("");
                               PACIENTE_ID = null;
                               eliminarDetalleReceta();
                               showReceta();
                        });
                    }
                }
            });
        }

        /// eliminar de la cesta receta
        function eliminarDetalleReceta(){
            $.ajax({
                url:RUTA+"receta/borrar-detalle",
                method:"POST",
                data:{
                    token_:TOKEN,
                },
                dataType:"json",
                success:function(response){

                }
            });
        }

        function VerRecetasGenerados(){
            RecetasGenerado = $('#tabla_recetas').DataTable({
              "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
                }],
                language:SpanishDataTable(),
                retrieve:true,
                ajax:{
                    url:RUTA+"recetas/view/generados",
                    method:"GET",
                    dataSrc:"recetas"
                },
                columns:[
                    {"data":"serie_receta"},
                    {"data":"serie_receta",render:function(serie){
                        return `<span class='badge bg-success'>`+serie+`</span>`;
                    }},
                    {"data":"fecha_receta",render:function(fecha){
                        return `<span class='badge bg-primary'>`+fecha+`</span>`;
                    }},
                    {"data":null,render:function(datapersona){
                        return (datapersona.apellidos+"  "+datapersona.nombres).toUpperCase();
                    }},
                    {"data":null,render:function(){
                        return `<button class='btn btn-info rounded btn-sm' id='print'><i class='fas fa-print'></i></button>
                        <button class='btn btn-danger rounded btn-sm' id='delete'><i class='fas fa-trash-alt'></i></button>`;
                    }}
                ]
            }).ajax.reload();
             /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
  RecetasGenerado.on( 'order.dt search.dt', function () {
RecetasGenerado.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
cell.innerHTML = i+1;
} );
}).draw();
        }

 /*IMPRIMIR LA RECETA DEL PACIENTE*/
 function printRecetaElectronica(Tabla,Tbody)
 {
    $(Tbody).on('click','#print',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child')){
            fila = fila.prev();
        }
        
        let Data = Tabla.row(fila).data();

        window.open(RUTA+"receta_medica?v="+Data.atencion_id,"_blank");
    });
 }

 /// confirmar antes de eliminar la receta electronica
 function DeleteConfirmRecetaElectronica(Tabla,Tbody)
 {
    $(Tbody).on('click','#delete',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child')){
            fila = fila.prev();
        }
        
        let Data = Tabla.row(fila).data();

        RECETAID = Data.id_receta_electro;
  Swal.fire({
        title: "Estas seguro de eliminar la receta electrónica seleccionado?",
        text: "Al aceptar, ya no podrás recuperarlo!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
        }).then((result) => {
        if (result.isConfirmed) {
          EliminarRecetaElectronica(RECETAID);
        }
        });
    });
 }

 /// eliminar receta
 function EliminarRecetaElectronica(id)
 {
    $.ajax({
        url:RUTA+"receta/delete/"+id,
        method:"POST",
        data:{
            token_:TOKEN
        },
        dataType:"json",
        success:function(response){
            if(response.response === 'ok'){
                Swal.fire({
                    title:"Correcto!",
                    text:"La receta se acaba de eliminar de manera satisfactoria!",
                    icon:"success"
                }).then(function(){
                    VerRecetasGenerados();
                });
            }else{
                if(response.response === 'token-invalid'){
                    Swal.fire({
                    title:"Hay un problema!",
                    text:"Token incorrecto, no puedes procesar esta tarea!",
                    icon:"error"
                }) 
                }else{
                    Swal.fire({
                    title:"Hay un problema!",
                    text:"No tiene permisos para realizar esta tarea!",
                    icon:"error"
                })
                }
            }
        }
    })
 }
 </script>
@endsection