/** Mostrar los grupos terapeúticos */
function mostrarGruposTerapeuticos()
{
    TablaListaGrupos = $('#lista_grupo_terapeuticos').DataTable({
        language: SpanishDataTable(),
        retrieve: true,
        responsive: true,
        processing: true,
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
        ajax:{
            url:RUTA+"app/farmacia/grupo_terapeutico/all/si",
            method:"GET",
            dataSrc:"response"
        },
        columns:[
            {"data":"name_grupo_terapeutico"},
            {"data":"name_grupo_terapeutico",render:function(namegrupo){
                return namegrupo.toUpperCase();
            }},
            {"data":"deleted_at",render:function(deleted_at){
                if(deleted_at == null)
                {
                 return `<button class='btn btn-danger rounded btn-sm' id='eliminar_grupo'><i class='bx bx-x'></i></button>
                 <button class='btn btn-warning rounded btn-sm' id='editar_grupo'><i class='bx bx-pencil' ></i></button>
                 <button class='btn btn-info rounded btn-sm' id='delete_grupo'><i class='bx bx-trash'></i></button>`;
                }
                return `<button class='btn btn-success rounded btn-sm' id='activar_grupo'><i class='bx bx-check'></i></button>
                <button class='btn btn-info rounded btn-sm' id='delete_grupo'><i class='bx bx-trash'></i></button>`;
            }}
        ]
    }).ajax.reload();
    TablaListaGrupos.on('order.dt search.dt', function() {
        TablaListaGrupos.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
}

/// editar grupo terapeútico
function EditarGrupo(Tabla,Tbody)
{
    $(Tbody).on('click','#editar_grupo',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        IDGRUPO = Data.id_grupo_terapeutico;
        ControlBotonGrupo = 'update';
        $('#name_grupo').focus();
        $('#name_grupo').val(Data.name_grupo_terapeutico);
        $('#name_grupo').select();
    });
}
/// confirmar eliminado del grupo terapeutico
function ConfirmEliminadoGrupo(Tabla,Tbody)
{
    $(Tbody).on('click','#eliminar_grupo',function(){
     
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        IDGRUPO = Data.id_grupo_terapeutico;
        Swal.fire({
            title: "Estas seguro de eliminar al grupo terapeútico "+Data.name_grupo_terapeutico+"?",
            text: "Al eliminarlo, se quitará de la lista para crear productos!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!"
          }).then((result) => {
            if (result.isConfirmed) {
                HabilitarInhabilitarGrupos(IDGRUPO,'i');
            }
          });
    });
}

/** Aciavar los grupos terapeúticos */
function ActivateGrupo(Tabla,Tbody)
{
    $(Tbody).on('click','#activar_grupo',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        IDGRUPO = Data.id_grupo_terapeutico;
        HabilitarInhabilitarGrupos(IDGRUPO,'h');
    });
}
/** Registrar nuevo grupo terapeutico */
function saveGrupo()
{
    let Respuesta = crud(RUTA+"app/farmacia/grupo_terapeutico/save",'form_grupos');

    if(Respuesta == 1)
    {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Grupo terapeútico registrado correctamente!",
                icon:"success"
            }).then(function(){
                $('#name_grupo').focus();$('#name_grupo').val("");

                mostrarGruposTerapeuticos();
            });
    }else{
        if(Respuesta === 'existe')
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Ya existe el grupo terapeútico que deseas registrar!",
                icon:"warning"
            }).then(function(){
                $('#name_grupo').focus();$('#name_grupo').val("");
            });
        }else{
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al registrar el grupo terapeútico!",
                icon:"error"
            });
        }
    }
}

/** Modificar los datos del grupo terapeutico */
function updateGrupo(id)
{
    let Respuesta = crud(RUTA+"app/farmacia/grupo_terapeutico/update/"+id,'form_grupos');

    if(Respuesta == 1)
    {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Grupo terapeútico modificado correctamente!",
                icon:"success"
            }).then(function(){
                $('#name_grupo').focus();$('#name_grupo').val("");
                ControlBotonGrupo = 'save';
                mostrarGruposTerapeuticos();
            });
    }else{
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Error al modificar el grupo terapeútico!",
            icon:"error"
        });
    }
}

/** Proceso para habilitar e inhabilitar el laboratorio */
function HabilitarInhabilitarGrupos(id,condition)
{
    $.ajax({
        url:RUTA+"app/farmacia/grupo_terapeutico/habilitar_inhabilitar/"+id+"/"+condition,
        method:"POST",
        data:{
            _token:TOKEN
        },
        dataType:"json",
        success:function(response)
        {
           if(response.response == 1)
           {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:condition === 'i'?"Grupo terapeútico quitado de la lista correctamente":"Grupo terapeútico activado nuevamente",
                icon:"success"
            }).then(function(){
              mostrarGruposTerapeuticos();
            });
           }else{
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al realizar el proceso de eliminado o activación del grupo terapeútico",
                icon:"error"
            });
           }
        }
    });
}

/** Confirmar eliminado de la presentación de los productos*/ 
function DeleteConfirmGrupos(Tabla,Tbody) 
{
  $(Tbody).on('click','#delete_grupo',function(){
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();

    IDGRUPO = Data.id_grupo_terapeutico;
    Swal.fire({
    title: "Estas seguro de borrar al grupo "+Data.name_grupo_terapeutico+"?",
    text: "Al eliminarlo, se borrará de la base de datos por completo!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminar!"
  }).then((result) => {
    if (result.isConfirmed) {
        DeleteGrupos(IDGRUPO);
    }
  });
  });
}

/*Eliminar la presentación de la base de datos*/
function DeleteGrupos(id)
{
    $.ajax({
        url:RUTA+"app/farmacia/grupo_terapeutico/delete/"+id,
        method:"POST",
        data:{
            _token:TOKEN
        },
        dataType:'json',
        success:function(response)
        {
            if(response.response == 1)
            {
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Grupo terapeútico eliminado por completo!",
                    icon:"success"
                }).then(function(){
                    mostrarGruposTerapeuticos();
                });
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error al eliminar grupo terapeútico seleccionado!",
                    icon:"error"
                })  
            }
        },error:function(err)
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al eliminar el grupo seleccionado!",
                icon:"error"
            }) ;  
        }
    });
}