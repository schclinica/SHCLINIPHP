@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Gestión enfermedades')

@section('css')
    <style>
        #tabla_enfermedades>thead>tr>th {
            background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);
            color:white;
        }
        
    </style>
@endsection
@section('contenido')
 <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
                <h4 class="letra text-white">Gestión De Enfermedades</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <button class="btn_3d col-xl-3 col-lg-3 col-md-5 col-12 mt-2" id="create">Agregar uno nuevo <i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm responsive nowrap" id="tabla_enfermedades" style="width:100%">
                    <thead>
                        <tr>
                            <th class="py-4 letra">Código</th>
                            <th class="py-4 letra">Enfermedad</th>
                            <th class="py-4 letra">Descripción</th>
                            <th class="py-4 letra">Estado</th>
                            <th class="py-4 letra text-center">Acciones</th>
                        </tr>
                    </thead>
                </table>
                </div>
            </div>
        </div>
    </div>
 </div>

 {{---VENTANA MODAL PARA AGREGAR NUEVA ENFERMEDAD---}}
 <div class="modal fade" id="create_enfermedad">
     <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
                 <h4 class="text-white">Crear enfermedad</h4>
            </div>
            <div class="modal-body" id="loading_create_enfermedad">
                <form action="" method="POST" id="form_enfermedad">
                     <input type="text" name="codigo" id="codigo" class="form-control mb-1" placeholder="Código (obligatorio)....">
                     <span class="text-danger mb-2" id="text_error_codigo"></span>
                     <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                     <input type="text" name="enfermedad" id="enfermedad" class="form-control mb-1" placeholder="NOMBRE DE LA ENFERMEDAD (obligatorio)....">
                     <span class="text-danger mb-2" id="text_error_enfermedad"></span>
                     
                     <textarea name="descripcion" id="descripcion" class="form-control mt-1" cols="30" rows="4" placeholder="Describa aquí...."></textarea>
                </form>

                <div class="form-group mt-2" id="click_importar_datos">
                    <label for="importar_excel" class="form-label text-primary">
                        Importar datos por excel. <a href="#" id="importar_excel"><b>Dar click aquí.</b></a>
                    </label>
                </div>
                <div class="form-group mt-2" id="click_form_save" style="display: none">
                    <label for="importar_excel" class="form-label text-primary">
                        Volver a mi formulario. <a href="#" id="form_save_view"><b>Dar click aquí.</b></a>
                    </label>
                </div>
                <form action="" method="post"  id="form_import_excel" style="display: none">
                  <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                  <input type="file" name="archivo_excel_enfermedades" class="form-control">
                </form>
            </div>
            <div class="modal-footer border-2">
                <button class="btn_3d" id="save_enfermedad"><span id="text_button"> Guardar</span> <i class="fas fa-save"></i></button>
            </div>
        </div>
     </div>
 </div>

 {{--- EDITAR LA ENFERMEDAD---}}
 <div class="modal fade" id="editar_enfermedad">
    <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header" style="background: linear-gradient(to bottom, #fefcea 0%,#f1da36 100%);">
                <h4 class="text-dark">Editar enfermedad</h4>
           </div>
           <div class="modal-body">
               <form action="" method="POST" id="form_enfermedad_editar">
                    <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                    <input type="text" name="codigo_editar" id="codigo_editar" class="form-control mb-1" placeholder="Código (obligatorio)....">
                     <span class="text-danger mb-2" id="text_error_codigo_editar"></span>
                    <input type="text" name="enfermedad_editar" id="enfermedad_editar" class="form-control mb-1" placeholder="NOMBRE DE LA ENFERMEDAD....">
                    <span class="text-danger" id="text_error_enfermedad_editar"></span>
                    
                    <textarea name="descripcion_editar" id="descripcion_editar" class="form-control mt-1" cols="30" rows="4" placeholder="Describa aquí...."></textarea>
               </form>
           </div>
           <div class="modal-footer border-2">
               <button class="btn_3d" id="update_enfermedad">Guardar cambios <i class="fas fa-save"></i></button>
           </div>
       </div>
    </div>
</div>

@endsection


@section('js')
<script src="{{URL_BASE}}public/js/control.js"></script>
    <script>
          var RUTA = "{{URL_BASE}}" // la url base del sistema
          var TOKEN = "{{$this->Csrf_Token()}}";
          var TablaEnfermedades;
          var EnfermedadId;
          var Accion = 'save';
          var TextButton;

         $(document).ready(function(){
            /// evento enter
            enter('codigo','enfermedad');
            enter('enfermedad','tipo');
            enter('enfermedad_editar','descripcion_editar')
            MostrarEnfermedades();
            EventHabilitaEnfermedad(TablaEnfermedades,'#tabla_enfermedades tbody')

            // create enfermedad
            $('#create').click(function(){
                $('#text_error_codigo').text("");
                $('#text_error_enfermedad').text("");
                $('#form_enfermedad')[0].reset();
                Accion = 'save';
                $('#text_button').text("Guardar");
                $('#form_enfermedad').show();
                $('#click_importar_datos').show();
                $('#click_form_save').hide();
                $('#form_import_excel').hide(200);
                $('#create_enfermedad').modal("show");
            });

            /// REGISTRAR LA ENFERMEDAD
            $('#save_enfermedad').click(function(){
               if(Accion === 'save'){
                saveEnfermedad();
               }else{
                let FormImportDatosEnfermedades = new FormData(document.getElementById('form_import_excel'));
                importEnfermedades(FormImportDatosEnfermedades);
               }
            });
            // ACTUALIZAR LA ENFERMEDAD
            $('#update_enfermedad').click(function(){
                ActualizarEnfermedad();
            });

            $('#importar_excel').click(function(evento){
                evento.preventDefault();
                Accion = 'importar';
                $('#text_button').text("Importar enfermedades");
                $('#form_enfermedad').hide();
                $('#click_importar_datos').hide();
                $('#click_form_save').show();
                $('#form_import_excel').show(400);
            });

            $('#form_save_view').click(function(evento){
                evento.preventDefault();
                Accion = 'save';
                $('#text_button').text("Guardar");
                $('#form_enfermedad').show();
                $('#click_importar_datos').show();
                $('#click_form_save').hide();
                $('#form_import_excel').hide();
            });
         });

         function MostrarEnfermedades(){
             TablaEnfermedades = $('#tabla_enfermedades').DataTable({
              language:SpanishDataTable(),
              retrieve:true,
              responsive:true,
               ajax:{
                url:RUTA+"enfermedades/all",
                method:"GET",
                dataSrc:"enfermedades"
               },
               columns:[
                {"data":"codigo_enfermedad",render:function(codigo){
                    return codigo.toUpperCase();
                }},
                {"data":"enfermedad",render:function(enfermeda){
                    return enfermeda.toUpperCase();
                }},
                {"data":"descripcion_enfermedad",render:function(desc){
                    return desc != null ? desc.toUpperCase() : '<span class="badge bg-danger">no especifica...</span>';
                }},
                {"data":null,render:function(dato){
                    return dato.deleted_at != null ? ` <span class='badge bg-danger'>Deshabilitado <i class="fas fa-close mx-1"></i></span>` : 
                    `<span class='badge bg-success'>Habilitado <i class="fas fa-check mx-1"></i></span>`; 
                }},
                {"data":null,render:function(dato){
                    if(dato.deleted_at == null){
                        return `
                         <button class='btn btn-danger rounded btn-sm' id='eliminar'><i class="fas fa-trash-alt"></i></button>
                         <button class='btn btn-warning rounded btn-sm' id='editar'><i class="fas fa-edit"></i></button>
                        
                    `;
                    }

                    return `
                         <button class='btn btn-success rounded btn-sm' id="activar"><i class="fas fa-check"></i></button>
                         <button class='btn btn-danger rounded btn-sm' id='borrar'><i class="fas fa-close"></i></button>
                    `;
                },className:"text-center"},
               ]
            }).ajax.reload();

            /// eliminar de lista
            ConfirmEliminadoEnfermedad(TablaEnfermedades,'#tabla_enfermedades tbody')
            ConfirmBorradoEnfermedad(TablaEnfermedades,'#tabla_enfermedades tbody')
           // EventHabilitaEnfermedad(TablaEnfermedades,'#tabla_enfermedades tbody')
            EditarEnfermedad(TablaEnfermedades,'#tabla_enfermedades tbody')
         }

         /// CONFIRMAR ANTES DE ELIMINAR DE LISTA
         function ConfirmEliminadoEnfermedad(Tabla,Tbody){
            $(Tbody).on('click','#eliminar',function(){
                let fila = $(this).parents('tr');

                if(fila.hasClass('child')){
                    fila = fila.prev();
                }
                 
                let Data = Tabla.row(fila).data();

                EnfermedadId = Data.id_enfermedad;

           Swal.fire({
                title: "Estas seguro?",
                text: "Al eliminar a la enfermedad "+Data.enfermedad+" se quitará de la lista!!",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar!"
                }).then((result) => {
                if (result.isConfirmed) {
                   EliminarEnfermedad(EnfermedadId);
                }
                });
            });
         }

         /// CONFIRMAR ANTES DE BORRAR A LA ENFERMEDAD DE LA BASE DE DATOS
         function ConfirmBorradoEnfermedad(Tabla,Tbody){
            $(Tbody).on('click','#borrar',function(){
                let fila = $(this).parents('tr');

                if(fila.hasClass('child')){
                    fila = fila.prev();
                }
                 
                let Data = Tabla.row(fila).data();

                EnfermedadId = Data.id_enfermedad;

           Swal.fire({
                title: "Estas seguro?",
                text: "Al borrar a la enfermedad "+Data.enfermedad+" ya no podrás recuperarlo !",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, borrar!"
                }).then((result) => {
                if (result.isConfirmed) {
                    BorrrarEnfermedad(EnfermedadId);
                }
                });
            });
         }

         /// EVENTO CLICK AL BOTON HABILITAR ENFERMEDAD
         function EventHabilitaEnfermedad(Tabla,Tbody){
            $(Tbody).on('click','#activar',function(){
                let fila = $(this).parents('tr');

                if(fila.hasClass('child')){
                    fila = fila.prev();
                }
                 
                let Data = Tabla.row(fila).data();

                HabilitarEnfermedad(Data.id_enfermedad);

           
            });
         }

         /// EDITAR A LA ENFERMEDAD
         function EditarEnfermedad(Tabla,Tbody){
            $(Tbody).on('click','#editar',function(){
                let fila = $(this).parents('tr');

                if(fila.hasClass('child')){
                    fila = fila.prev();
                }
                 
                let Data = Tabla.row(fila).data();
                $('#codigo_editar').val(Data.codigo_enfermedad);
                $('#enfermedad_editar').val(Data.enfermedad);
                $('#tipo_editar').val(Data.tipo_enfermedad)
                $('#descripcion_editar').val(Data.descripcion_enfermedad);
                EnfermedadId = Data.id_enfermedad;

                $('#editar_enfermedad').modal("show")
           
            });
         }

         /// MODIFICAR DATOS DE LA ENFERMEDAD
         function ActualizarEnfermedad(){
            let FormUpdateEnfermedad = new FormData(document.getElementById('form_enfermedad_editar'));
            $.ajax({
                url:RUTA+"enfermedad/"+EnfermedadId+"/update",
                method:"POST",
                data:FormUpdateEnfermedad,
                dataType:"json",
                contentType:false,
                processData:false,
                success:function(response){

                    if(response.errors != undefined){
                    if(response.errors.codigo_editar != undefined){
                    $('#codigo').focus();
                    $('#text_error_codigo_editar').text(response.errors.codigo_editar);
                    }else{
                    $('#text_error_codigo_editar').text("");
                    }
                    
                    if(response.errors.enfermedad_editar != undefined){
                    $('#text_error_enfermedad_editar').text(response.errors.enfermedad_editar);
                    }else{
                    $('#text_error_enfermedad_editar').text("");
                    }
                    return;
                    }
                    if(response.error_token != undefined){
                      Swal.fire({
                         title:"MENSAJE DEL SISTEMA!!",
                         text:"Error, el token de seguridad es incorrecto!!",
                         icon:"error",
                         target:document.getElementById('editar_enfermedad')
                      });
                    }else{
                        if(response.response === 'ok'){
                         Swal.fire({
                            title:"MENSAJE DEL SISTEMA!!",
                            text:"Enfermedad modificado!!",
                            icon:"success",
                             target:document.getElementById('editar_enfermedad')
                            }).then(function(){
                                MostrarEnfermedades();
                            });
                        }else{
                          if(response.response === 'existe'){
                            Swal.fire({
                            title:"MENSAJE DEL SISTEMA!!",
                            text:"No realizaste ningún cambio!!",
                            icon:"info",
                            target:document.getElementById('editar_enfermedad')
                            });
                          }else{
                            Swal.fire({
                            title:"MENSAJE DEL SISTEMA!!",
                            text:"Error, al modificar la enfermedad!!",
                            icon:"error",
                            target:document.getElementById('editar_enfermedad')
                            });
                          }
                        }
                    }
                }
            })
         }

         /// eliminar a la enfermedad
         function EliminarEnfermedad(id){
            let FormEliminado = new FormData();
            FormEliminado.append("token_",TOKEN);
            axios({
                url:RUTA+"enfermedad/"+id+"/eliminar",
                method:"POST",
                data:FormEliminado
            }).then(function(response){
                if(response.data.error_token){
                    Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:"Error, el token de seguridad es incorrecto!!",
                    icon:"error",
                    
                 });
                }else{
                    if(response.data.response === 'ok'){
                    Swal.fire({
                        title:"MENSAJE DEL SISTENA!!!",
                        text:"Enfermedad eliminado!!!",
                        icon:"success"
                    }).then(function(){
                        MostrarEnfermedades();
                    })
                 }
                }
            })
         }

         /// Borrar a la enfermedad
         function BorrrarEnfermedad(id){
            let FormBorrar = new FormData();
            FormBorrar.append("token_",TOKEN);
            axios({
                url:RUTA+"enfermedad/"+id+"/borrar",
                method:"POST",
                data:FormBorrar
            }).then(function(response){
                if(response.data.error_token){
                    Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:"Error, el token de seguridad es incorrecto!!",
                    icon:"error",
                    
                 });
                }else{
                    if(response.data.response === 'ok'){
                    Swal.fire({
                        title:"MENSAJE DEL SISTENA!!!",
                        text:"Enfermedad eliminado!!!",
                        icon:"success"
                    }).then(function(){
                        MostrarEnfermedades();
                    })
                 }else{
                    if(response.data.response === 'not-delete'){
                        Swal.fire({
                        title:"MENSAJE DEL SISTENA!!!",
                        text:"No se puede borrar esta enfermedad, porque ya se está usando en el proceso de la atención médica!!!",
                        icon:"error"
                    })
                    }
                 }
                }
            })
         }

         /// HABILITAR A LA ENFERMEDAD
         function HabilitarEnfermedad(id){
            let FormActive = new FormData();
            FormActive.append("token_",TOKEN);
            axios({
                url:RUTA+"enfermedad/"+id+"/habilitar",
                method:"POST",
                data:FormActive
            }).then(function(response){
                if(response.data.error_token){
                    Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:"Error, el token de seguridad es incorrecto!!",
                    icon:"error",
                    
                 });
                }else{
                    if(response.data.response === 'ok'){
                    Swal.fire({
                        title:"MENSAJE DEL SISTENA!!!",
                        text:"Enfermedad habilitado!!!",
                        icon:"success"
                    }).then(function(){
                        MostrarEnfermedades();
                    })
                 }
                }
            })
         }
         /// Método para registrar nueva enfermedad
         function saveEnfermedad(){
            let FormEnfermedades = new FormData(document.getElementById('form_enfermedad'))
            axios({
                url:RUTA+"enfermedad/store",
                method:"POST",
                data:FormEnfermedades
            }).then(function(response){
             
                 if(response.data.errors != undefined){
                    if(response.data.errors.codigo != undefined){
                        $('#codigo').focus();
                        $('#text_error_codigo').text(response.data.errors.codigo);
                    }else{
                        $('#text_error_codigo').text("");
                    }

                    if(response.data.errors.enfermedad != undefined){
                        $('#text_error_enfermedad').text(response.data.errors.enfermedad);
                    }else{
                        $('#text_error_enfermedad').text("");
                    }
                    return;
                 }
                 $('#text_error_enfermedad').text("");
                if(response.data.error_token != undefined){
                 Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:"Error, el token de seguridad es incorrecto!!",
                    icon:"error",
                    target:document.getElementById('create_enfermedad')
                 });
               }else{
                 if(response.data.response === 'ok'){
                    Swal.fire({
                        title:"Mensaje del sistema!!",
                        text:"La enfermedad a sido registrado correctamente!!!",
                        icon:"success",
                        target:document.getElementById('create_enfermedad')
                    }).then(function(){
                        MostrarEnfermedades();
                        $('#form_enfermedad')[0].reset();
                        $('#text_error_codigo').text("");
                        $('#text_error_enfermedad').text("");
                    });
                 }else{
                    if(response.data.response === 'existe'){
                        Swal.fire({
                        title:"Mensaje del sistema!!",
                        text:"La enfermedad que deseas ya existe por código oh Nombre de la enfermedad!!!",
                        icon:"warning",
                        target:document.getElementById('create_enfermedad')
                      });
                    }else{
                      Swal.fire({
                        title:"MENSAJE DEL SISTEMA!!",
                        text:"Error, el registrar la enfermedad!!",
                        icon:"error",
                        target:document.getElementById('create_enfermedad')
                        }); 
                    }
                 }
               }
            });
            
         }

         /// Imoportar datos desde excel a la tabla enfermedades
  function importEnfermedades(datosFormImportExcel){
    axios(
        {
            url:RUTA+"enfermedad/importar-datos",
            method:"POST",
            data:datosFormImportExcel,
        }
    ).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:"Error, token invalid!!",
                icon:"error",
                target:document.getElementById('create_enfermedad')
            });
        }else{
            if(response.data.response === 'ok' || response.data.response === 'existe'){
                loading('#loading_create_enfermedad','#4169E1','chasingDots');
                setTimeout(function(){
                    $('#loading_create_enfermedad').loadingModal('hide');
                    $('#loading_create_enfermedad').loadingModal('destroy');

                    Swal.fire({
                        title:"Mensaje del sistema!!",
                        text:"Datos de las enfermedades importados correctamente!!",
                        icon:"success",
                        target:document.getElementById('create_enfermedad')
                    }).then(function(){
                        $('#form_import_excel')[0].reset();
                        MostrarEnfermedades();
                    });  
                },1000);  
                   
            }else{
                if(response.data.response === 'vacio'){
                    Swal.fire({
                        title:"Mensaje del sistema!!",
                        text:"Error, para continuar debe de seleccionar un archivo excel!!",
                        icon:"error",
                        target:document.getElementById('create_enfermedad')
                    });
                }else{
                    if(response.data.response === "archivo no aceptable"){
                        Swal.fire({
                            title:"Mensaje del sistema!!",
                            text:"Error, el archivo seleccionado es incorrecto, debe de seleccionar un archivo excel!!",
                            icon:"error",
                            target:document.getElementById('create_enfermedad')
                        });
                    }else{
                        Swal.fire({
                            title:"Mensaje del sistema!!",
                            text:"Error al importar datos a la tabla enfermedades!!",
                            icon:"error",
                            target:document.getElementById('create_enfermedad')
                        });
                    }
                }
            }
        }
    });
}
    </script>
@endsection