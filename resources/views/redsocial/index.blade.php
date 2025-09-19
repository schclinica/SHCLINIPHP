@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Redes sociales')

@section('css')
    <style>
      #tabla_red_social>thead>tr>th
        {
        background:linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 50%,#539fe1 79%,#87bcea 100%);;
        padding: 24px;
        color:aliceblue;
        }
    </style>
@endsection

@section('contenido')
  <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
                    <h4 class="letra text-white">Gestión de redes sociales</h4 class="text-sm" >
            </div>
            <div class="card-body">
                <div class="card-text mt-2">
                    <button class="btn_blue  col-xl-3 col-lg-4 col-md-5  col-12" id="add_red_social">Agregar uno nuevo</button>
                </div>
                <div class="card-text mt-3">
                    <table class="table table-bordered nowrap" id="tabla_red_social" style="width: 100%">
                        <thead>
                            <th>Red social</th>
                            <th>Icono</th>
                            <th>Acciones</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
  </div>

  {{--AGREGAR NUEVAS REDES SOCIALES----}}
  <div class="modal fade" id="venta_add_red_social">
     <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
                <h4 class="text-white">Agregar nueva red social</h4>
            </div>

            <div class="modal-body">
                <input type="text" class="form-control mb-2" id="name_red" placeholder="Nombre red social...">
                <input type="text" class="form-control" id="icono_red" placeholder="Indicar icono...">

                <div class="alert alert-danger mt-2" id="alerta_errores" style="display: none">
                    <ul id="lista_errors">

                    </ul>
                </div>

                <div class="alert alert-danger mt-2" id="alerta_error" style="display: none">
                    <span>Error, el token es invalid!</span>
                </div>

                <div class="alert alert-warning mt-2" id="alerta_existe" style="display: none">
                     <span>Ya existe esa red social!</span>
                </div>

                <div class="alert alert-success mt-2" id="alerta_success" style="display: none">
                    <span>Red social registrado correctamente!</span>
               </div>
            </div>

            <div class="modal-footer border-2">
                <button class="btn btn-success brn-rounded" id="save_red_social"><b>Guardar  <i class='bx bx-save' ></i></b></button>
            </div>
        </div>
     </div>
  </div>

  {{--VENTANA PARA EDITAR LAS REDES SOCIALES---}}
  <div class="modal fade" id="ventana_editar_red_social">
    <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header" style="background: linear-gradient(to bottom, #fefcea 0%,#f1da36 100%);">
               <h4 class="text-dark">Editar red social</h4>
           </div>

           <div class="modal-body">
               <input type="text" class="form-control mb-2" id="name_red_editar" placeholder="Nombre red social...">
               <input type="text" class="form-control" id="icono_red_editar" placeholder="Indicar icono...">

               <div class="alert alert-danger mt-2" id="alerta_errores_editar" style="display: none">
                   <ul id="lista_errors_editar">

                   </ul>
               </div>

               <div class="alert alert-danger mt-2" id="alerta_error_editar" style="display: none">
                   <span>Error, el token es invalid!</span>
               </div>

               <div class="alert alert-warning mt-2" id="alerta_existe_editar" style="display: none">
                    <span>Ya existe esa red social!</span>
               </div>

               <div class="alert alert-success mt-2" id="alerta_success_editar" style="display: none">
                   <span>Red social modificado correctamente!</span>
              </div>
           </div>

           <div class="modal-footer border-2">
               <button class="btn btn-success brn-rounded" id="update_red_social"><b>Guardar  <i class='bx bx-save' ></i></b></button>
           </div>
       </div>
    </div>
 </div>
@endsection

@section('js') 
    <script src="{{URL_BASE}}public/js/control.js"></script>
    <script>
        var TablaRedeSocial;
        var IdRedeSocial;
        var TOKEN = "{{$this->Csrf_Token()}}"
        $(document).ready(function(){

            let NameRedSocial = $('#name_red');
            let IconoRedSocial = $('#icono_red');
            let NameRedSocialEditar = $('#name_red_editar');
            let IconoRedSocialEditar = $('#icono_red_editar');

            showRedesSociales();

            $('#add_red_social').click(function(){
                NameRedSocial.focus();
                $('#alerta_errores').hide();
                $('#alerta_error').hide();
                $('#alerta_success').hide();
                $('#alerta_existe').hide();
                NameRedSocial.val("");
                IconoRedSocial.val("");
                $('#venta_add_red_social').modal("show");
            });

            /// Guardar la red social
            $('#save_red_social').click(function(){
                saveRedSocial(NameRedSocial,IconoRedSocial);
            });

            $('#update_red_social').click(function(){
                ModificarRedSocial(IdRedeSocial,NameRedSocialEditar,IconoRedSocialEditar);
            })
        });


        /// mostrar las redes sociales
        function showRedesSociales()
        {
            TablaRedeSocial = $('#tabla_red_social').DataTable({
                language:SpanishDataTable(),
                retrieve:true,
                ajax:{
                    url:"{{URL_BASE}}redes-sociales/all",
                    method:"GET",
                    dataSrc:"redes_sociales"
                },
                columns:[
                    {"data":null,render:function(dato){
                        if(dato.deleted_at == null){
                            return `<b>`+dato.nombre_red_social.toUpperCase()+`</b>`;
                        }
                        return `<b class="text-danger">`+dato.nombre_red_social.toUpperCase()+`</b>`;
                    }},
                    {"data":"icono",render:function(icono){
                        return `<i class='`+icono+`'></i>`;
                    }},
                    {"data":null,render:function(dato){
                        
                        if(dato.deleted_at == null){
                          return `
                            <button class="btn btn-warning btn-sm rounded" id="editar"><i class='bx bx-edit-alt'></i></button>
                            <button class="btn btn-danger btn-sm rounded" id="eliminar"><i class='bx bx-x'></i></button>
                            `;
                        }


                        return `
                         <button class="btn btn-warning btn-sm rounded" ><i class='bx bx-edit-alt' ></i></button>
                          <button class="btn btn-success btn-sm rounded" id="activar"><i class='bx bx-check' ></i></button>
                         <button class="btn btn-info btn-sm rounded" id="borrar"><i class='bx bx-trash' ></i></button>
                        `;
                        
                    }}
                ]
               
            
            }).ajax.reload();

            ConfirmEliminar(TablaRedeSocial,'#tabla_red_social tbody');
            ActivarRedSocial(TablaRedeSocial,'#tabla_red_social tbody');
            ConfirmBorrado(TablaRedeSocial,'#tabla_red_social tbody');
            Editar(TablaRedeSocial,'#tabla_red_social tbody');
        }

        /// Confirmar antes de eliminar
        function ConfirmEliminar(Tabla,Tbody){
           $(Tbody).on('click','#eliminar',function(){
            let fila = $(this).parents('tr');

            if(fila.hasClass("child")){
                fila = fila.prev();
            }

            let Data = Tabla.row(fila).data();

            Swal.fire({
            title: "Estas seguro de eliminar a la red social "+Data.nombre_red_social+"?",
            text: "Al eliminar, se quitará de la lista!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!"
            }).then((result) => {
            if (result.isConfirmed) {
             eliminar(Data.id_red_social)
            }
            });
           });
        }

        /// método es para editar
        const Editar = function(Tabla,Tbody){
            $(Tbody).on('click','#editar',function(){
                 let fila = $(this).parents('tr');

                 if(fila.hasClass("child")){
                    fila = fila.prev();
                 }

                 let Data = Tabla.row(fila).data();

                 IdRedeSocial = Data.id_red_social;
                 $('#name_red_editar').val(Data.nombre_red_social);
                 $('#icono_red_editar').val(Data.icono);
                 $('#alerta_errores_editar').hide();
                $('#alerta_error_editar').hide();
                $('#alerta_success_editar').hide();
                $('#alerta_existe_editar').hide();
                 $('#ventana_editar_red_social').modal("show");
            });
        }

        /// método para guardar lso cambios
        function ModificarRedSocial(id,NameRedS,IconoRedS)
        {
            let FormUpdate = new FormData();
            FormUpdate.append("token_",TOKEN);
            FormUpdate.append("name_red_social",NameRedS.val());
            FormUpdate.append("icono_red_social",IconoRedS.val());
            axios({
                 url:"{{URL_BASE}}red-social/update/"+id,
                 data:FormUpdate,
                 method:"POST"
                }).then(function(response){
                    response = response.data;
                    if(response.errors != undefined){
                        NameRedS.focus();
                        $('#alerta_existe_editar').hide();
                        $('#alerta_error_editar').hide();
                        $('#alerta_success_editar').hide();
                        $('#alerta_errores_editar').show(300);
                        let li = '';
                        response.errors.forEach(error => {
                            li+=`<li>`+error+`</li>`;
                        });

                        $('#lista_errors_editar').html(li);
                    }else{
                        if(response.existe != undefined){
                            $('#alerta_errores_editar').hide();
                            $('#alerta_error_editar').hide();
                            $('#alerta_success_editar').hide();
                            $('#lista_errors_editar').empty(); 
                            $('#alerta_existe_editar').show(300);
                        }else{
                            if(response.error != undefined){
                                $('#alerta_errores_editar').hide();
                                $('#lista_errors_editar').empty(); 
                                $('#alerta_existe_editar').hide();
                                $('#alerta_success_editar').hide();
                                $('#alerta_error_editar').show(300);
                                
                            }else{
                                if(response.response === 'ok'){
                                    $('#alerta_errores_editar').hide();
                                    $('#lista_errors_editar').empty();
                                    $('#alerta_existe_editar').hide();
                                    $('#alerta_error_editar').hide();
                                    $('#alerta_success_editar').show(300);
                                    showRedesSociales();
                                }else{
                                    Swal.fire(
                                        {
                                            title:"MENSAJE DEL SISTEMA!",
                                            text:"Error al modificar la red social!",
                                            icon:"error"
                                        }
                                    )
                                }
                            }
                        } 
                    }
                })
        }

        /// Confirmar antes de eliminar
        function ConfirmBorrado(Tabla,Tbody){
           $(Tbody).on('click','#borrar',function(){
            let fila = $(this).parents('tr');

            if(fila.hasClass("child")){
                fila = fila.prev();
            }

            let Data = Tabla.row(fila).data();

            Swal.fire({
            title: "Estas seguro de borrar a la red social "+Data.nombre_red_social+"?",
            text: "Al borrar, ya no podrás recuperar ese registro!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!"
            }).then((result) => {
            if (result.isConfirmed) {
             borrar(Data.id_red_social);
            }
            });
           });
        }

         /// Activar red social
         function ActivarRedSocial(Tabla,Tbody){
           $(Tbody).on('click','#activar',function(){
            let fila = $(this).parents('tr');

            if(fila.hasClass("child")){
                fila = fila.prev();
            }

            let Data = Tabla.row(fila).data();
           
             activar(Data.id_red_social)
             
           });
        }

        function eliminar(id){
            let FormEliminar = new FormData();
            FormEliminar.append("token_",TOKEN);
            axios({
                url:"{{URL_BASE}}red-social/eliminar/"+id,
                method:"POST",
                data:FormEliminar
                
            }).then(function(response){
                if(response.data.response === 'ok'){
                    Swal.fire(
                        {
                            title:"MENSAJE DEL SISTEMA!",
                            text:"Red social eliminado!",
                            icon:"success"
                        }
                    ).then(function(){
                        showRedesSociales();
                    })
                }else{
                 Swal.fire({
                    title:"MENSAJE DEL SISTEMA!",
                    text:"Error al eliminar red social!",
                    icon:"error"
                 })   
                }
            });
        }


        function borrar(id){
            let FormBorrar = new FormData();
            FormBorrar.append("token_",TOKEN);
            axios({
                url:"{{URL_BASE}}red-social/borrar/"+id,
                method:"POST",
                data:FormBorrar
                
            }).then(function(response){
                if(response.data.response === 'ok'){
                    Swal.fire(
                        {
                            title:"MENSAJE DEL SISTEMA!",
                            text:"Red social borrado definitivamente!",
                            icon:"success"
                        }
                    ).then(function(){
                        showRedesSociales();
                    })
                }else{
                 Swal.fire({
                    title:"MENSAJE DEL SISTEMA!",
                    text:"Error al borrar red social!",
                    icon:"error"
                 })   
                }
            });
        }

        function activar(id){
            let FormActivar = new FormData();
            FormActivar.append("token_",TOKEN);
            axios({
                url:"{{URL_BASE}}red-social/activar/"+id,
                method:"POST",
                data:FormActivar
                
            }).then(function(response){
                if(response.data.response === 'ok'){
                    Swal.fire(
                        {
                            title:"MENSAJE DEL SISTEMA!",
                            text:"Red social habilitado nuevamente!",
                            icon:"success"
                        }
                    ).then(function(){
                        showRedesSociales();
                    })
                }else{
                 Swal.fire({
                    title:"MENSAJE DEL SISTEMA!",
                    text:"Error al habilitar red social!",
                    icon:"error"
                 })   
                }
            });
        }

        /// Registrar una red social
        function saveRedSocial(namered,iconored)
        {
            $.ajax({
                url:"{{URL_BASE}}red-social/new",
                method:"POST",
                data:{
                    token_:TOKEN,
                    name_red_social:namered.val(),
                    icono_red_social:iconored.val()
                },
                dataType:"json",
                success:function(response){
                    if(response.errors != undefined){
                        namered.focus();
                        $('#alerta_existe').hide();
                        $('#alerta_error').hide();
                        $('#alerta_success').hide();
                        $('#alerta_errores').show(300);
                        let li = '';
                        response.errors.forEach(error => {
                            li+=`<li>`+error+`</li>`;
                        });

                        $('#lista_errors').html(li);
                    }else{
                        if(response.existe != undefined){
                            $('#alerta_errores').hide();
                            $('#alerta_error').hide();
                            $('#alerta_success').hide();
                            $('#lista_errors').empty(); 
                            $('#alerta_existe').show(300);
                        }else{
                            if(response.error != undefined){
                                $('#alerta_errores').hide();
                                $('#lista_errors').empty(); 
                                $('#alerta_existe').hide();
                                $('#alerta_success').hide();
                                $('#alerta_error').show(300);
                                
                            }else{
                                if(response.response === 'ok'){
                                    $('#alerta_errores').hide();
                                    $('#lista_errors').empty();
                                    $('#alerta_existe').hide();
                                    $('#alerta_error').hide();
                                    $('#alerta_success').show(300);
                                    showRedesSociales();
                                }else{
                                    Swal.fire(
                                        {
                                            title:"MENSAJE DEL SISTEMA!",
                                            text:"Error al registrar la red social!",
                                            icon:"error"
                                        }
                                    )
                                }
                            }
                        } 
                    }
                }
            })
        }
    </script>
@endsection