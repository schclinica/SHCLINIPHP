/**
 * Mostrar todos los empaques existentes
 */
function mostrarEmpaques()
{
    TablaListaEmpaques = $('#lista_embalajes').DataTable({
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
            url:RUTA+"app/farmacia/empaques/all/si",
            method:"GET",
            dataSrc:"response"
        },
        columns:[
            {"data":"name_embalaje"},
            {"data":"name_embalaje",render:function(namegrupo){
                return namegrupo.toUpperCase();
            }},
            {"data":"deleted_at",render:function(deleted_at){
                if(deleted_at == null)
                {
                 return `<button class='btn btn-danger rounded btn-sm' id='eliminar_embalaje'><i class='bx bx-x'></i></button>
                 <button class='btn btn-warning rounded btn-sm' id='editar_embalaje'><i class='bx bx-pencil' ></i></button>
                 <button class='btn btn-info rounded btn-sm' id='delete_embalaje'><i class='bx bx-trash'></i></button>`;
                }
                return `<button class='btn btn-success rounded btn-sm' id='activar_embalaje'><i class='bx bx-check'></i></button>
                <button class='btn btn-info rounded btn-sm' id='delete_embalaje'><i class='bx bx-trash'></i></button>`;
            }}
        ]
    }).ajax.reload();
    TablaListaEmpaques.on('order.dt search.dt', function() {
        TablaListaEmpaques.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
}

/** Registrar nuevo embalaje */
function saveEmbalaje()
{
    let Respuesta = crud(RUTA+"app/farmacia/empaques/save",'form_empaque');

    if(Respuesta == 1)
    {
        Swal.fire({
           title:"Mensaje del sistema!",
           text:"Nuevo empaque registrado correctamente!",
           icon:"success"  
        }).then(function(){
            $('#name_embalaje').focus();
            $('#name_embalaje').val("");
            mostrarEmpaques();
        });
    }else{
        if(Respuesta === 'existe')
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Ya existe el empaque que deseas registrar!",
                icon:"warning"  
             }).then(function(){
                $('#name_embalaje').val("");
                $('#name_embalaje').focus();
             });  
        }else{
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al registrar nuevo empaque!",
                icon:"error"  
             });
        }
    }
}

/** Modificar datos del empaque */
/** Registrar nuevo embalaje */
function updateEmbalaje(id)
{
    let Respuesta = crud(RUTA+"app/farmacia/empaques/update/"+id,'form_empaque');

    if(Respuesta == 1)
    {
        Swal.fire({
           title:"Mensaje del sistema!",
           text:"Empaque modificado correctamente!",
           icon:"success"  
        }).then(function(){
            $('#name_embalaje').focus();
            $('#name_embalaje').val("");
            ControlBotonEmbalaje = 'save';
            mostrarEmpaques();
        });
    }else{
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Error al modificar el empaque seleccionado!",
            icon:"error"  
         });
    }
}

/** Editar los empaques */
function EditarEmpaque(Tabla,Tbody)
{
    $(Tbody).on('click','#editar_embalaje',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        IDEMBALAJE = Data.id_embalaje;
        ControlBotonEmbalaje = 'update';
        $('#name_embalaje').focus();
        $('#name_embalaje').val(Data.name_embalaje);
        $('#name_embalaje').select();
    });
}

/**Confirmar eliminado del empaque */
function ConfirmEliminadoEmpaque(Tabla,Tbody)
{
    $(Tbody).on('click','#eliminar_embalaje',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        IDEMBALAJE = Data.id_embalaje;
        Swal.fire({
            title: "Estas seguro de eliminar al empaque "+Data.name_embalaje+"?",
            text: "Al eliminarlo, se quitará de la lista para crear productos!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!"
          }).then((result) => {
            if (result.isConfirmed) {
              HabilitarInhabilitarEmpaques(IDEMBALAJE,'i');  
            }
          });
    });
}

/** Aciavar los grupos terapeúticos */
function ActivateEmpaque(Tabla,Tbody)
{
    $(Tbody).on('click','#activar_embalaje',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        IDEMBALAJE = Data.id_embalaje;
        HabilitarInhabilitarEmpaques(IDEMBALAJE,'h');
    });
}

function HabilitarInhabilitarEmpaques(id,condition)
{
    $.ajax({
        url:RUTA+"app/farmacia/empaques/habilitar_inhabilitar/"+id+"/"+condition,
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
                text:condition === 'i'?"Empaque quitado de la lista correctamente":"Empaque activado nuevamente",
                icon:"success"
            }).then(function(){
              mostrarEmpaques();
            });
           }else{
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al realizar el proceso de eliminado o activación del empaque seleccionado",
                icon:"error"
            });
           }
        }
    });
}


/** Confirmar eliminado de la presentación de los productos*/ 
function DeleteConfirmEmbalajes(Tabla,Tbody) 
{
  $(Tbody).on('click','#delete_embalaje',function(){
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();

    IDEMBALAJE = Data.id_embalaje;
    Swal.fire({
    title: "Estas seguro de borrar al empaque "+Data.name_embalaje+"?",
    text: "Al eliminarlo, se borrará de la base de datos por completo!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminar!"
  }).then((result) => {
    if (result.isConfirmed) {
        DeleteEmbalajes(IDEMBALAJE);
    }
  });
  });
}

/*Eliminar la presentación de la base de datos*/
function DeleteEmbalajes(id)
{
    $.ajax({
        url:RUTA+"app/farmacia/empaques/delete/"+id,
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
                    text:"Emapaque eliminado por completo!",
                    icon:"success"
                }).then(function(){
                   mostrarEmpaques();
                });
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error al eliminar empaque seleccionado!",
                    icon:"error"
                })  
            }
        },error:function(err)
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al eliminar el empaque seleccionado!",
                icon:"error"
            }) ;  
        }
    });
}