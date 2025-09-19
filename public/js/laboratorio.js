/** Mostrar los laboratorios */
function showLaboratorios()
{
    TablaListaLaboratorios = $('#lista_laboratorios').DataTable({
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
        url:RUTA+"app/farmacia/laboratorio/all/si",
        method:"GET",
        dataSrc:"response"
    },
    columns:[
        {"data":"name_laboratorio"},
        {"data":"name_laboratorio",render:function(namelab){
            return namelab.toUpperCase();
        }},
        {"data":"direccion",render:function(direccion){
            if(direccion == null)
            {
                return `<span class='badge bg-danger'>No especifica la dirección</span>`;
            }
            return direccion;
        }},
        {"data":"deleted_at",render:function(deleted_at){
            if(deleted_at == null)
            {
             return `<button class='btn btn-danger rounded btn-sm' id='eliminar_laboratorio'><i class='bx bx-x'></i></button>
             <button class='btn btn-warning rounded btn-sm' id='editar_laboratorio'><i class='bx bx-pencil' ></i></button>
             <button class='btn btn-info rounded btn-sm' id='delete_laboratorio'><i class='bx bx-trash'></i></button>`;
            }
            return `<button class='btn btn-success rounded btn-sm' id='activar_laboratorio'><i class='bx bx-check'></i></button>
            <button class='btn btn-info rounded btn-sm' id='delete_laboratorio'><i class='bx bx-trash'></i></button>`;
        }}
    ]
    }).ajax.reload();
     /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
  TablaListaLaboratorios.on('order.dt search.dt', function() {
    TablaListaLaboratorios.column(0, {
        search: 'applied',
        order: 'applied'
    }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
    });
}).draw();
}

/** Editar laboratorio */
function editarLaboratorio(Tabla,Tbody)
{
    $(Tbody).on('click','#editar_laboratorio',function(){
        let fila = $(this).parents('tr');
        
        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        IDLABORATORIO = Data.id_laboratorio;

        ControlButtonLaboratorio = 'update';
        $('#name_laboratorio').focus();
        $('#name_laboratorio').val(Data.name_laboratorio);
        $('#direccion_laboratorio').val(Data.direccion);
        $('#name_laboratorio').select();
    });
}
function EliminarLaboratorio(Tabla,Tbody)
{
    $(Tbody).on('click','#eliminar_laboratorio',function(){
        let fila = $(this).parents('tr');
        
        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        IDLABORATORIO = Data.id_laboratorio;
        Swal.fire({
            title: "Estas seguro de eliminar al laboratorio "+Data.name_laboratorio+"?",
            text: "Al eliminarlo, se quitará de la lista para crear productos!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!"
          }).then((result) => {
            if (result.isConfirmed) {
             HabilitarInhabilitarLaboratorios(IDLABORATORIO,'i');
            }
          });
         
    });
}
/** Activar laboratorio */
function ActivarLaboratorio(Tabla,Tbody)
{
    $(Tbody).on('click','#activar_laboratorio',function(){
        let fila = $(this).parents('tr');
        
        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        IDLABORATORIO = Data.id_laboratorio;

        HabilitarInhabilitarLaboratorios(IDLABORATORIO,'h');
        
    });
}

/** Proceso para habilitar e inhabilitar el laboratorio */
function HabilitarInhabilitarLaboratorios(id,condition)
{
    $.ajax({
        url:RUTA+"app/farmacia/laboratorio/habilitar_inhabilitar/"+id+"/"+condition,
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
                text:condition === 'i'?"Laboratorio quitado de la lista correctamente":"Laboratorio activado nuevamente",
                icon:"success"
            }).then(function(){
               showLaboratorios();
            });
           }else{
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al realizar el proceso de eliminado o activación de la presentación",
                icon:"error"
            });
           }
        }
    });
}
/** Registrar nuevo laboratorio */
function saveLaboratorio()
{
    let Respuesta = crud(RUTA+"app/farmacia/laboratorio/save",'form_laboratorio');
    if(Respuesta == 1)
    {
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Laboratorio registrado correctamente!",
            icon:"success"
        }).then(function(){
            $('#name_laboratorio').focus();$('#name_laboratorio').val("");
            $('#direccion_laboratorio').val("");
            showLaboratorios();

        });
    }else{
        if(Respuesta === 'existe')
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Ya existe el laboratorio que desea registrar!",
                icon:"warning"
            }).then(function(){
                $('#name_laboratorio').focus();
                $('#name_laboratorio').val("");
                $('#direccion_laboratorio').val("");
            });
        }else{
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al registrar laboratorio!",
                icon:"error"
            })
        }
    }
}

/** Modificar datos del laboratorio */
function updateLaboratorio(id)
{
    let Respuesta = crud(RUTA+"app/farmacia/laboratorio/update/"+id,'form_laboratorio');
    if(Respuesta == 1)
    {
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Laboratorio modificado correctamente!",
            icon:"success"
        }).then(function(){
            $('#name_laboratorio').focus();$('#name_laboratorio').val("");
            $('#direccion_laboratorio').val("");
            ControlButtonLaboratorio = 'save';
            showLaboratorios();

        });
    }else{
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Error al modificar los datos del laboratorio!",
            icon:"error"
        });
    }
}

/** Confirmar eliminado del laboratorio de los productos*/ 
function DeleteConfirmLaboratorio(Tabla,Tbody) 
{
  $(Tbody).on('click','#delete_laboratorio',function(){
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();

    IDLABORATORIO = Data.id_laboratorio;
    Swal.fire({
    title: "Estas seguro de borrar al laboratorio "+Data.name_laboratorio+"?",
    text: "Al eliminarlo, se borrará de la base de datos por completo!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminar!"
  }).then((result) => {
    if (result.isConfirmed) {
        DeleteLaboratorio(IDLABORATORIO);
    }
  });
  });
}

/*Eliminar la presentación de la base de datos*/
function DeleteLaboratorio(id)
{
    $.ajax({
        url:RUTA+"app/farmacia/laboratorio/delete/"+id,
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
                    text:"El laboratorio a sido eliminado por completo!",
                    icon:"success"
                }).then(function(){
                    showLaboratorios();
                });
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error al eliminar el laboratorio seleccionado!",
                    icon:"error"
                }) ; 
            }
        },error:function(err)
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al eliminar el laboratorio seleccionado!",
                icon:"error"
            }) ;  
        }
    });
}