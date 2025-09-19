@extends($this->Layouts("dashboard"))
@section('title_dashboard','Gestión de especialidades')
@section('css')
    <style>
    
    </style>
    <link rel="stylesheet" href="{{$this->asset("css/estilos.css")}}">
@endsection
@section('contenido')
 <div class="card">
    <div class="card-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
        <h4 class="letra text-white">Gestión de especialidades</h4>
    </div>
    <div class="card-body mt-3">
        <div class="row">
            <div class="col-12">
                <button class="btn_blue col-xl-3 col-lg-3 col-md-5 col-12 mb-2" id="create">Agregar uno nuevo <i class='bx bx-plus-medical'></i></button>
            </div>
        </div>
        <div class="table-responsive-sm">
            <table class="table table-bordered table-striped table-hover nowrap responsive table-sm" id="especialidades" style="width: 100%">
                <thead style="background: linear-gradient(to bottom, #b3dced 0%,#29b8e5 50%,#bce0ee 100%);">
                    <th class="py-4 text-white letra">Especialidad</th>
                    <th class="py-4 text-white letra">Acciones</th>
                </thead>
            </table>
        </div>
    </div>
 </div>
 {{---MODAL PARA CREAR NUEVAS ESPECIALIDADES---}}
 <div class="modal fade" id="venta_new_especialidad">
   <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header" style="background: #168fc7">
             <h4 class="letra text-white">Crear especialidad</h4>
        </div>
        <div class="modal-body">
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="name_especialidad" placeholder="Nombre especialidad">
                <label for="name_especialidad">Nombre Especialidad</label>
            </div>
        </div>
        <div class="modal-footer border-2">
            <button class="btn btn-success" id="save_especialidad">Guardar <i class='bx bx-save'></i></button>
        </div>
     </div>
   </div>
 </div>

  {{---MODAL PARA EDITAR  ESPECIALIDADES---}}
 <div class="modal fade" id="ventana_editar_especialidad">
   <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #fefcea 0%,#f1da36 100%);">
             <h4 class="letra text-dark">Editar especialidad</h4>
        </div>
        <div class="modal-body">
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="name_especialidad_editar" placeholder="Nombre especialidad">
                <label for="name_especialidad_editar">Nombre Especialidad</label>
            </div>
        </div>
        <div class="modal-footer border-2">
            <button class="btn btn-success" id="update_especialidad">Guardar <i class='bx bx-save'></i></button>
        </div>
     </div>
   </div>
 </div>
@endsection

@section('js')
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>
    var TablaEspecialidades;
    var TOKEN = "{{$this->Csrf_Token()}}";
    var RUTA = "{{URL_BASE}}";
    var Especialidad_Id;
    $(document).ready(function(){
        mostrarLasEspecialidades();

        /*ACCIONES DE LA TABLA*/
        QuitarEspecialidadDeLista(TablaEspecialidades,"#especialidades tbody");
        ActiveEspecialidadDeLista(TablaEspecialidades,"#especialidades tbody");
        ForzarEliminadoEspecialidad(TablaEspecialidades,"#especialidades tbody");
        EditarEspecialidad(TablaEspecialidades,"#especialidades tbody");

        $('#create').click(function(){
            $('#venta_new_especialidad').modal("show");
        });
        $('#save_especialidad').click(function(){
            if($('#name_especialidad').val().trim().length ==0){
                $('#name_especialidad').focus();
            }else{
                saveEspecialidad();
            }
        });
        $('#update_especialidad').click(function(){
            if($('#name_especialidad_editar').val().trim().length ==0){
                $('#name_especialidad_editar').focus();
            }else{
              ModificarLaEspecialidad();
            }
        });
    });

    /*MOSTRAR LAS ESPECIALIDADES*/
    function mostrarLasEspecialidades(){
        TablaEspecialidades = $('#especialidades').DataTable({
            retrieve:true,
            responsive:true,
            processing:true,
            ajax:{
                url:RUTA+"especialidad/all?token_="+TOKEN,
                method:"GET",
                dataSrc:"Especialidades"
            },
            columns:[
                {"data":"nombre_esp",render:function(espe){
                    return espe.toUpperCase();
                }},
                {"data":null,render:function(dato){
                    if(dato.estado == 1){
                        return `<button class="btn btn-outline-primary btn-sm" id='papelera'><i class='bx bx-trash'></i></button>
                            <button class="btn btn-outline-warning btn-sm" id="editar"><i class='bx bxs-edit-alt'></i></button>
                            <span class="badge bg-success">ACTIVO  <i class='bx bx-check'></i></span>`;
                    }
                    return `<button class="btn btn-outline-success btn-sm" id="activar"><i class='bx bx-check'></i></button>
                            <button class="btn btn-outline-danger btn-sm" id="delete"><i class='bx bx-x'></i></button>
                            <span class="badge bg-danger">INACTIVO  <i class='bx bx-x'></i></span>`;
                },className:"text-center"}
            ],
            language:SpanishDataTable(),
        }).ajax.reload();
    }

    /// editar la especialdiad
    function EditarEspecialidad(Tabla,Tbody){
        $(Tbody).on('click',"#editar",function(){
            let fila = $(this).parents("tr");

            if(fila.hasClass("child")){
                fila = fila.prev();
            }

            let Data = Tabla.row(fila).data();
            Especialidad_Id = Data.id_especialidad;

            $('#ventana_editar_especialidad').modal("show");
            $('#name_especialidad_editar').val(Data.nombre_esp);
        });
    }

    /// modificar la especialidad
    function ModificarLaEspecialidad(){
        let FormUpdateEspecialidad = new FormData();
        FormUpdateEspecialidad.append("token_",TOKEN);
        FormUpdateEspecialidad.append("especialidad",$('#name_especialidad_editar').val());
        axios({
            url:RUTA+"especialidad/"+Especialidad_Id+"/update",
            method:"POST",
            data:FormUpdateEspecialidad
        }).then(function(response){
            if(response.data.error!= undefined){
                Swal.fire({
                    title:"ERROR DEL SISTEMA!!!",
                    text:response.data.response,
                    icon:"error",
                    target:document.getElementById('ventana_editar_especialidad')
                });
            }else{
                if(response.data.response === 'existe'){
                    Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:"NO SE HAN REALIZADO NINGUN CAMBIO!!!",
                    icon:"info",
                    target:document.getElementById('ventana_editar_especialidad')
                });
              }else{
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:"ESPECIALIDAD MODIFICADO CORRECTAMENTE!!!",
                    icon:"success",
                    target:document.getElementById('ventana_editar_especialidad')
                }).then(function(){
                    $('#ventana_editar_especialidad').modal("hide");
                    mostrarLasEspecialidades();
                })
              }
            }
        });
    }
    /// forzar eliminado por completo de la especialidad seleccionado delete/{id}/especialidad
    function ForzarEliminadoEspecialidad(Tabla,Tbody){
        $(Tbody).on('click','#delete',function(){
            let fila = $(this).parents("tr");

            if(fila.hasClass("child")){
                fila = fila.prev();
            }

            let Data = Tabla.row(fila).data();

       Swal.fire({
            title: "ESTAS SEGURO?",
            text: "Al aceptar que deseas borrar a la especialidad seleccionado, se borrará por completo!!!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!"
            }).then((result) => {
            if (result.isConfirmed) {
              ProcessBorradoEspecialidad(Data.id_especialidad);
            }
            });

        });
    }

    /// PROCESO DE BORRAR A LA ESPECIALIDAD
    function ProcessBorradoEspecialidad(id){
         let FormDeleteEspecialidad = new FormData();
        FormDeleteEspecialidad.append("token_",TOKEN);
        FormDeleteEspecialidad.append("estado",1);
        axios({
            url:RUTA+"delete/"+id+"/especialidad",
            method:"POST",
            data:FormDeleteEspecialidad
        }).then(function(response){
            if(response.data.error != undefined){
                Swal.fire({
                    title:"ERROR DEL SISTEMA!!",
                    text:response.data.error,
                    icon:"error",
                });
            }else{
                Swal.fire({
                    title:response.data.response == "ok" ? "CORRECTO!!":(response.data.response === 'existe' ? "MENSAJE DEL SISTEMA":"ERROR DEL SISTEMA!!!"),
                    text:response.data.response=="ok" ? 'ESPECIALIDAD BORRADO CORRECTAMENTE!!':(response.data.response === 'existe' ? 'LA CATEGORIA QUE DESEAS REGISTRAR YA EXISTE' :'ERROR AL BORRAR A LA ESPECIALIDAD SELECCIONADO!!'),
                    icon:response.data.response== "ok" ? "success":(response.data.response === 'existe' ? 'warning':"error"),
                }).then(function(){
                    mostrarLasEspecialidades();
                })
            }
        });
    }
    /// ELIMINAR DE LA LISTA DE LA TABLA
    function QuitarEspecialidadDeLista(Tabla,Tbody){
       $(Tbody).on('click','#papelera',function(){
        let fila = $(this).parents("tr");
        
        if(fila.hasClass("child")){
        fila = fila.prev();
        }
        
        
        let Data = Tabla.row(fila).data();

    Swal.fire({
        title: "ESTAS SEGURO?",
        text: "Al aceptar que deseas eliminar a la especialidad seleccionado, se quitará de la lista y ya no podrás usarlo, estará inactivo!!!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
        }).then((result) => {
        if (result.isConfirmed) {
         DesactivarEspecialidad(Data.id_especialidad);
        }
        });
        })
    }

    /// ACTIVAR A LA ESPECIALIDAD 
    function ActiveEspecialidadDeLista(Tabla,Tbody){
       $(Tbody).on('click','#activar',function(){
        let fila = $(this).parents("tr");
        
        if(fila.hasClass("child")){
        fila = fila.prev();
        }
        
        
        let Data = Tabla.row(fila).data();
        activar(Data.id_especialidad);
    
    })
  }

    /// PROCESO DE ACTIVACION DE LA ESPECIALIDAD
    /// DESCATIVAR LA ESPECIALIDAD
    function activar(id){
        let FormActiveEspecialidad = new FormData();
        FormActiveEspecialidad.append("token_",TOKEN);
        FormActiveEspecialidad.append("estado",1);
        axios({
            url:RUTA+"especialidad/"+id+"/delete",
            method:"POST",
            data:FormActiveEspecialidad
        }).then(function(response){
            if(response.data.error != undefined){
                Swal.fire({
                    title:"ERROR DEL SISTEMA!!",
                    text:response.data.error,
                    icon:"error",
                });
            }else{
                Swal.fire({
                    title:response.data.response == true ? "CORRECTO!!":(response.data.response === 'existe' ? "MENSAJE DEL SISTEMA":"ERROR DEL SISTEMA!!!"),
                    text:response.data.response==true ? 'ESPECIALIDAD HABILITADO NUEVAMENTE!!':(response.data.response === 'existe' ? 'LA CATEGORIA QUE DESEAS REGISTRAR YA EXISTE' :'ERROR AL REALIZAR LA ACTIVACION DE LA ESPECIALIDAD SELECCIONADO!!'),
                    icon:response.data.response== true ? "success":(response.data.response === 'existe' ? 'warning':"error"),
                }).then(function(){
                    mostrarLasEspecialidades();
                })
            }
        });
    }

    /// DESCATIVAR LA ESPECIALIDAD
    function DesactivarEspecialidad(id){
        let FormDesactiveEspecialidad = new FormData();
        FormDesactiveEspecialidad.append("token_",TOKEN);
        FormDesactiveEspecialidad.append("estado",0);
        axios({
            url:RUTA+"especialidad/"+id+"/delete",
            method:"POST",
            data:FormDesactiveEspecialidad
        }).then(function(response){
            if(response.data.error != undefined){
                Swal.fire({
                    title:"ERROR DEL SISTEMA!!",
                    text:response.data.error,
                    icon:"error",
                });
            }else{
                Swal.fire({
                    title:response.data.response == true ? "CORRECTO!!":(response.data.response === 'existe' ? "MENSAJE DEL SISTEMA":"ERROR DEL SISTEMA!!!"),
                    text:response.data.response==true ? 'ESPECIALIDAD ELIMINADO DE LISTA CORRECTAMENTE!!':(response.data.response === 'existe' ? 'LA CATEGORIA QUE DESEAS REGISTRAR YA EXISTE' :'ERROR AL ELIMINAR ESPECIALIDAD'),
                    icon:response.data.response== true ? "success":(response.data.response === 'existe' ? 'warning':"error"),
                }).then(function(){
                    mostrarLasEspecialidades();
                })
            }
        });
    }

    /// REGISTRAR UNA ESPECIALIDAD
    function saveEspecialidad(){
        let FormEspe = new FormData();
        FormEspe.append("token_",TOKEN);
        FormEspe.append("especialidad",$('#name_especialidad').val());
        FormEspe.append("precio",0);

        axios({
            url:RUTA+"especialidad/save",
            method:"POST",
            data:FormEspe,
        }).then(function(response){
             
            if(response.data.error != undefined){
                Swal.fire({
                    title:"ERROR DEL SISTEMA!!",
                    text:response.data.error,
                    icon:"error",
                    target:document.getElementById("venta_new_especialidad")
                });
            }else{
                $('#name_especialidad').focus();
                Swal.fire({
                    title:response.data.response == true ? "CORRECTO!!":(response.data.response === 'existe' ? "MENSAJE DEL SISTEMA":"ERROR DEL SISTEMA!!!"),
                    text:response.data.response==true ? 'ESPECIALIDAD REGISTRADO CORRECTAMENTE!!':(response.data.response === 'existe' ? 'LA CATEGORIA QUE DESEAS REGISTRAR YA EXISTE' :'ERROR AL REGISTRAR ESPECIALIDAD'),
                    icon:response.data.response== true ? "success":(response.data.response === 'existe' ? 'warning':"error"),
                    target:document.getElementById("venta_new_especialidad")
                }).then(function(){
                    mostrarLasEspecialidades();
                    $('#name_especialidad').val("");
                })
            }
        });
    }
</script>
@endsection