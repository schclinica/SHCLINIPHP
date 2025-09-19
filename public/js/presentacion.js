/** Mostrar las presentaciones */
function showPresentaciones()
{
    TablaListaPresentacion= $('#lista_presentacion').DataTable({
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
        url:RUTA+"app/farmacia/presentaciones/all/si",
        method:"GET",
        dataSrc:"response",
    },
    columns:[
        {"data":"name_presentacion"},
        {"data":"name_presentacion",render:function(name_){
            return name_.toUpperCase()
        }},
        {"data":"name_corto_presentacion",render:function(name){
            return `<span class='badge bg-primary'>`+name+`</span>`;
        }},
        {"data":"deleted_at",render:function(deleted_at){
           if(deleted_at == null)
           {
            return `<button class='btn btn-danger rounded btn-sm' id='eliminar_presentacion'><i class='bx bx-x'></i></button>
            <button class='btn btn-warning rounded btn-sm' id='editar_presentacion'><i class='bx bx-pencil' ></i></button>
            <button class='btn btn-info rounded btn-sm' id='delete_presentacion'><i class='bx bx-trash'></i></button>`;
           }
           return `<button class='btn btn-success rounded btn-sm' id='activar_presentacion'><i class='bx bx-check'></i></button>
           <button class='btn btn-info rounded btn-sm' id='delete_presentacion'><i class='bx bx-trash'></i></button>`;
        }}
    ]
 }).ajax.reload();
  /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
  TablaListaPresentacion.on('order.dt search.dt', function() {
    TablaListaPresentacion.column(0, {
        search: 'applied',
        order: 'applied'
    }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
    });
}).draw();
}

/** Registrar una nueva presentación para los productos de la farmacia*/
function save(form,ruta)
{
 let Respuesta =  crud(ruta+"app/farmacia/presentacion/save",form);
  if(Respuesta == 1)
  {
    Swal.fire({
        title:"Mensaje del sistema!",
        text:"La presentación se ha registrado correctamente!",
        icon:"success"
    }).then(function(){
        showPresentaciones();
    });
  }else{
    if(Respuesta === 'existe')
    {
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Ya existe la presentación que desea registrar!",
            icon:"warning"
        }).then(function(){
            $('#name_presentacion').focus();
            $('#name_presentacion').val("");
            $('#name_corto_presentacion').val("");
        }); 
    }else{
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Error al registrar una nueva presentación!",
            icon:"error"
        });   
    }
  }
}

/** Editar la presentación */
function editarPresentacion(Tabla,Tbody)
{
 $(Tbody).on('click','#editar_presentacion',function(){
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();
    IDPRESENTACION = Data.id_pesentacion;
   
    ControlBotonPresnetacion = 'update';
    $('#name_presentacion').focus();
    $('#name_presentacion').val(Data.name_presentacion);
    $('#name_corto_presentacion').val(Data.name_corto_presentacion);
    $('#name_presentacion').select(); 
 });
}

/** Modificar los datos de la presentación */
function modificar(id)
{
    let Respuesta =  crud(RUTA+"app/farmacia/presentacion/update/"+id,'form_presentacion'); 

    if(Respuesta == 1)
    {
      Swal.fire({
          title:"Mensaje del sistema!",
          text:"La presentación se ha modificado correctamente!",
          icon:"success"
      }).then(function(){
          $('#name_presentacion').focus();
          $('#name_presentacion').val("");
          $('#name_corto_presentacion').val("");
          ControlBotonPresnetacion = 'save';
          showPresentaciones();
      });
    }else{
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Error al modificar la presentación seleccionada!",
            icon:"error"
        }); 
    }
}

/** Eliminar de la lista a la presentación */
function Eliminar(Tabla,Tbody)
{
  $(Tbody).on('click','#eliminar_presentacion',function(){ 
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();
    IDPRESENTACION = Data.id_pesentacion;
    /** Confirmamos si deseamos eliminar la presentación */
    Swal.fire({
        title: "Estas seguro de eliminar a la presentación "+Data.name_presentacion+"?",
        text: "Al eliminarlo, se quitará de la lista para crear productos!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
      }).then((result) => {
        if (result.isConfirmed) {
          HabilitarInhabilitarPresentaciones(IDPRESENTACION,'i');
        }
      });
  });
}

// activar la presentación
function Activar(Tabla,Tbody)
{
  $(Tbody).on('click','#activar_presentacion',function(){ 
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();
    IDPRESENTACION = Data.id_pesentacion;
    /** Confirmamos si deseamos eliminar la presentación */
     HabilitarInhabilitarPresentaciones(IDPRESENTACION,'h');
  });
}

/// proceso para eliminar e habilitar a la presentación
function HabilitarInhabilitarPresentaciones(id,condition)
{
    $.ajax({
        url:RUTA+"app/farmacia/habilitar_e_inhabilitar/presentacion/"+id+"/"+condition,
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
                text:condition === 'i'?"Presentación quitado de la lista correctamente":"Presentación activado nuevamente",
                icon:"success"
            }).then(function(){
               showPresentaciones(); 
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

/** Confirmar eliminado de la presentación de los productos*/ 
function DeleteConfirmPresentacion(Tabla,Tbody) 
{
  $(Tbody).on('click','#delete_presentacion',function(){
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();

    IDPRESENTACION = Data.id_pesentacion;
    Swal.fire({
    title: "Estas seguro de borrar a la presentación "+Data.name_presentacion+"?",
    text: "Al eliminarlo, se borrará de la base de datos por completo!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminar!"
  }).then((result) => {
    if (result.isConfirmed) {
        DeletePresentacion(IDPRESENTACION);
    }
  });
  });
}

/*Eliminar la presentación de la base de datos*/
function DeletePresentacion(id)
{
    $.ajax({
        url:RUTA+"app/farmacia/presentacion/delete/"+id,
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
                    text:"La presentación a sido eliminado por completo!",
                    icon:"success"
                }).then(function(){
                    showPresentaciones();
                });
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error al eliminar presentación seleccionado!",
                    icon:"error"
                })  
            }
        },error:function(err)
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al eliminar la presentación seleccionado!",
                icon:"error"
            }) ;  
        }
    });
}