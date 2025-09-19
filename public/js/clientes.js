/**
 * Mostrar todos los clientes
 */
function mostrarClientes()
{
    TablaListaClientes = $('#lista_clientes').DataTable({
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
            url:RUTA+"app/farmacia/clientes/all",
            method:"GET",
            dataSrc:"response"
        },
        columns:[
            {"data":"num_doc"},
            {"data":"deleted_at_cli",render:function(deleted_at_cli){
                if(deleted_at_cli == null)
                {
                 return `<button class='btn btn-danger rounded btn-sm' id='eliminar_clientes'><i class='bx bx-x'></i></button>
                 <button class='btn btn-warning rounded btn-sm' id='editar_clientes'><i class='bx bx-pencil' ></i></button>
                 <button class='btn btn-info rounded btn-sm' id='delete_clientes'><i class='bx bx-trash'></i></button>`;
                }
                return `<button class='btn btn-success rounded btn-sm' id='activar_clientes'><i class='bx bx-check'></i></button>
                <button class='btn btn-info rounded btn-sm' id='delete_clientes'><i class='bx bx-trash'></i></button>`;
             }},
            {"data":"name_tipo_doc",render:function(tipodoc){
                return `<span class='badge bg-primary'>`+tipodoc+`</span>`;
            }},
            {"data":"num_doc"},
            {"data":null,render:function(datacli){
                return datacli.apellidos.toUpperCase()+" "+datacli.nombres.toUpperCase();
            }},
            {"data":"direccion",render:function(dircli){
                if(dircli == null)
                {
                    return `<span class='badge bg-danger'>No indicó dirección</span>`;
                }
                return dircli.toUpperCase();
            }},
            {"data":"telefono_or_wasap",render:function(telefcli){
                if(telefcli == null)
                {
                    return `<span class='badge bg-danger'>No indicó teléfono o whatsApp</span>`;
                }
                return telefcli.toUpperCase();
            }},
  
            
        ]
    }).ajax.reload();
    TablaListaClientes.on('order.dt search.dt', function() {
        TablaListaClientes.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
}

/** 
 * Registrar nuevo cliente
 */
function saveCliente()
{
    let Respuesta = crud(RUTA+"app/farmacia/cliente/store","form_clientes");

    if(Respuesta == 1)
    {
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Cliente registrado sin problemas !",
            icon:"success"
        }).then(function(){
            $('#form_clientes')[0].reset();
            mostrarClientes();
        });
    }else{
        if(Respuesta === 'existe')
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Ya existe el cliente con ese # documento!", 
                icon:"warning"
            }).then(function(){
                $('#num_doc').focus();
                $('#num_doc').val("");
            });
        }else{
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al registrar cliente!", 
                icon:"error"
            });
        }
    }
}
/*
* Modificar datos del cliente
*/
function updateCliente(id)
{
   let Respuesta = crud(RUTA+"app/farmacia/cliente/update/"+id,"form_clientes");

   if(Respuesta == 1)
   {
       Swal.fire({
           title:"Mensaje del sistema!",
           text:"Cliente modificado sin problemas !",
           icon:"success"
       }).then(function(){
           ControlBotonClientes = 'save';
           $('#form_clientes')[0].reset();
           mostrarClientes();
       });
   }else{
    Swal.fire({
        title:"Mensaje del sistema!",
        text:"Error al modificar cliente!", 
        icon:"error"
    });
   }
}

/** 
 * Editar clientes
 */

function EditarClientes(Tabla,Tbody)
{
  $(Tbody).on('click','#editar_clientes',function(){
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();

    IDCLIENTE = Data.id_cliente;
    ControlBotonClientes = 'update';

    $('#num_doc').focus();$('#name_cliente').val(Data.nombres);
    $('#tipo_doc').val(Data.tipo_doc_id);
    $('#num_doc').val(Data.num_doc);$("#apellidos_cliente").val(Data.apellidos);
    $('#direccion_cliente').val(Data.direccion);$('#telefono_cliente').val(Data.telefono);
    $('#num_doc').select();
  });
}

/**
 * Confirmar borrado del cliente
 */
function ConfirmarBorradoClientes(Tabla,Tbody)
{
    $(Tbody).on('click','#delete_clientes',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();
        IDCLIENTE = Data.id_cliente;
         /** Confirmamos si deseamos eliminar la presentación */
    Swal.fire({
        title: "Estas seguro de borrar al cliente "+Data.nombres+" "+Data.apellidos+"?",
        text: "Al eliminarlo, se quitará del listado de clientes para realizar una venta y a la vez ya no podrá recuperarlos!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
      }).then((result) => {
        if (result.isConfirmed) {
          BorrarCliente(IDCLIENTE);
        }
      });
    });
}


/**Borrar por completo al producto seleccionado */
function BorrarCliente(id)
{
    $.ajax({
        url:RUTA+"app/farmacia/cliente/delete/"+id,
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
                    text:"Cliente borrado sin problemas!",
                    icon:"success"
                }).then(function(){
                    mostrarClientes();
                    ControlBotonClientes = 'save';
                });
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error al borrar clientes!",
                    icon:"error"
                });
            }
        },error:function(err)
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al eliminar al cliente seleccionado!",
                icon:"error"
            }) ;  
        }
    });
}

/** Confirmar eliminado de la lista a la clientes */
function EliminadoConfirmClientes(Tabla,Tbody)
{
  $(Tbody).on('click','#eliminar_clientes',function(){ 
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();
    IDCLIENTE = Data.id_cliente;
    /** Confirmamos si deseamos eliminar la presentación */
    Swal.fire({
        title: "Estas seguro de eliminar al cliente "+Data.nombres+" "+Data.apellidos+"?",
        text: "Al eliminarlo, se quitará de la lista para realizar una venta!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
      }).then((result) => {
        if (result.isConfirmed) {
          HabilitarInhabilitarClientes(IDCLIENTE,'i');
        }
      });
  });
}

/// proceso para eliminar e habilitar a la productos
function HabilitarInhabilitarClientes(id,condition)
{
    $.ajax({
        url:RUTA+"app/farmacia/cliente/habilitar/inhabilitar/"+id+"/"+condition,
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
                text:condition === 'i'?"Cliente quitado de la lista correctamente":"Cliente activado nuevamente",
                icon:"success"
            }).then(function(){
               mostrarClientes();
            });
           }else{
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al realizar el proceso de eliminado o activación del cliente",
                icon:"error"
            });
           }
        }
    });
}

// activar clientes 
function ActivarClientes(Tabla,Tbody)
{
  $(Tbody).on('click','#activar_clientes',function(){ 
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();
    IDCLIENTE = Data.id_cliente;
    /** Confirmamos si deseamos eliminar la presentación */
     HabilitarInhabilitarClientes(IDCLIENTE,'h');
  });
}


 