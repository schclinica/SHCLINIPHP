/***
 * Mostrar todos los proveedores
 */
function mostrarProveedores()
{
    TablaListaProveedores = $('#lista_proveedores').DataTable({
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
            url:RUTA+"app/farmacia/proveedores/all/si",
            method:"GET",
            dataSrc:"response"
        },
        columns:[
            {"data":"proveedor_name"},
            {"data":"proveedor_name",render:function(proveedor_name){
                return proveedor_name.toUpperCase();
            }},
            {"data":"contacto_name",render:function(contacto_name){
                return contacto_name.toUpperCase();
            }},
            {"data":"telefono",render:function(telefono){
                if(telefono == null)
                {
                    return `<span class='text-danger'>No especifica teléfono</span>`;
                }
                return telefono;
            }},
            {"data":"correo",render:function(correo){
                if(correo == null)
                {
                    return `<span class='text-danger'>No especifica correo</span>`;
                }
                return correo;
            }},
            {"data":"direccion",render:function(direccion){
                if(direccion == null)
                {
                    return `<span class='text-danger'>No especifica teléfono</span>`;
                }
                return direccion;
            }},
            {"data":"pagina_web",render:function(paginaweb){
                if(paginaweb == null)
                {
                    return `<span class='text-danger'>No especifica teléfono</span>`;
                }
                return paginaweb;
            }},
            {"data":"deleted_at",render:function(deleted_at){
                if(deleted_at == null)
                {
                 return `<button class='btn btn-danger rounded btn-sm' id='eliminar_proveedor'><i class='bx bx-x'></i></button>
                 <button class='btn btn-warning rounded btn-sm' id='editar_proveedor'><i class='bx bx-pencil' ></i></button>
                 <button class='btn btn-info rounded btn-sm' id='delete_proveedor'><i class='bx bx-trash'></i></button>`;
                }
                return `<button class='btn btn-success rounded btn-sm' id='activar_proveedor'><i class='bx bx-check'></i></button>
                <button class='btn btn-info rounded btn-sm' id='delete_proveedor'><i class='bx bx-trash'></i></button>`;
            }}
 
        ]
    }).ajax.reload();
    TablaListaProveedores.on('order.dt search.dt', function() {
        TablaListaProveedores.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
}

/** 
 * Registrar nuevos proveedores
 */
function saveProveedor()
{
    let Respuesta = crud(RUTA+"app/farmacia/proveedor/save","form_proveedores");

    if(Respuesta == 1)
    {
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Proveedor registrado correctamente!",
            icon:"success"  
        }).then(function(){
            $('#proveedor_name').focus();
            $('#proveedor_name').val("");
            $('#contacto_name').val("");
            $('#telefono').val("");
            $('#correo').val("");
            $('#direccion').val("");
            $('#paginaweb').val("");
            mostrarProveedores();
        });
    }else{
        if(Respuesta === 'existe')
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Ya existe el proveedor que deseas registrar!",
                icon:"warning"
            }).then(function(){
              $('#proveedor_name').focus();
              $('#proveedor_name').val(""); 
            });
        }else{
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al registrar proveedor!",
                icon:"error"
            });
        }
    }
}

/** Editar proveedores */
function EditarProveedor(Tabla,Tbody)
{
    $(Tbody).on('click',"#editar_proveedor",function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        ControlBotonProveedor = 'update';
        let Data = Tabla.row(fila).data();
        IDPROVEEDOR = Data.id_proveedor;

        $('#proveedor_name').focus();
        $('#proveedor_name').val(Data.proveedor_name);
        $('#contacto_name').val(Data.contacto_name);
        $('#telefono').val(Data.telefono);
        $('#correo').val(Data.correo);
        $('#direccion').val(Data.direccion);
        $('#paginaweb').val(Data.pagina_web);
        $('#proveedor_name').select();
    });
}
/** Modificar proveedores */
function updateProveedor(id)
{
    let Respuesta = crud(RUTA+"app/farmacia/proveedor/update/"+id,'form_proveedores');

    if(Respuesta == 1)
    {
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Proveedor modificado correctamente!",
            icon:"success"  
        }).then(function(){
            ControlBotonProveedor = 'save';
            $('#proveedor_name').focus();
            $('#proveedor_name').val("");
            $('#contacto_name').val("");
            $('#telefono').val("");
            $('#correo').val("");
            $('#direccion').val("");
            $('#paginaweb').val("");
            mostrarProveedores();
        });
    }else{
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Error al modificar proveedor!",
            icon:"error"
        });
    }

}

/**Confirmar eliminado de proveedores */
function ConfirmEliminadoProveedor(Tabla,Tbody)
{
    $(Tbody).on('click','#eliminar_proveedor',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        IDPROVEEDOR = Data.id_proveedor;
        Swal.fire({
            title: "Estas seguro de eliminar al proveedor "+Data.proveedor_name+"?",
            text: "Al eliminarlo, se quitará de la lista para crear productos!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!"
          }).then((result) => {
            if (result.isConfirmed) {
              HabilitarInhabilitarProveedores(IDPROVEEDOR,'i');  
            }
          });
    });
}

/** Habilitar e inhabilitar proveedores */
function HabilitarInhabilitarProveedores(id,condition)
{
    $.ajax({
        url:RUTA+"app/farmacia/proveedor/habilitar_inhabilitar/"+id+"/"+condition,
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
                text:condition === 'i'?"Proveedor quitado de la lista correctamente":"Proveedor activado nuevamente",
                icon:"success"
            }).then(function(){
              mostrarProveedores();
            });
           }else{
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al realizar el proceso de eliminado o activación del proveedor seleccionado",
                icon:"error"
            });
           }
        }
    });
}

/** Aciavar proveedores   */
function ActivateProveedor(Tabla,Tbody)
{
    $(Tbody).on('click','#activar_proveedor',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        IDPROVEEDOR = Data.id_proveedor;
        HabilitarInhabilitarProveedores(IDPROVEEDOR,'h');
    });
}

/** Confirmar borrado de proveedores */
function DeleteConfirmProveedor(Tabla,Tbody)
{
    $(Tbody).on('click',"#delete_proveedor",function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        ControlBotonProveedor = 'update';
        let Data = Tabla.row(fila).data();
        IDPROVEEDOR = Data.id_proveedor;

        Swal.fire({
            title: "Estas seguro de borrar al proveedor "+Data.proveedor_name+"?",
            text: "Al eliminarlo, se borrará de la base de datos por completo!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!"
          }).then((result) => {
            if (result.isConfirmed) {
                DeleteProveedor(IDPROVEEDOR);
            }
          });
    });
}

function DeleteProveedor(id){
    $.ajax({
        url:RUTA+"app/farmacia/proveedor/delete/"+id,
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
                    text:"Proveedor eliminado por completo!",
                    icon:"success"
                }).then(function(){
                   mostrarProveedores();
                });
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error al eliminar proveedor seleccionado!",
                    icon:"error"
                });
            }
        },error:function(err)
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al eliminar al proveedor seleccionado!",
                icon:"error"
            }) ;  
        }
    });
}