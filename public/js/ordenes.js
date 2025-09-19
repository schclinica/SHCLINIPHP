/*MOSTRAR LAS ORDENES*/
function mostrarOrdenes(){
    TablaOrdenes = $('#tabla_ordenes').DataTable({
        language:SpanishDataTable(),
        retrieve:true,
        responsive:true,
        ajax:{
            url:RUTA+"ordenes/all",
            method:"GET",
            dataSrc:"examenes",
        },
        columns:[
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
            }},
            {"data":"codigo_orden",render:function(codigo_examen){
                return codigo_examen.toUpperCase();
            }},
            {"data":"nombre_examen",render:function(name_examen){
                return name_examen.toUpperCase();
            }},
            {"data":"precio_examen",render:function(precio_examen){
                return precio_examen.toFixed(2);
            }},
            {"data":"nombre_categoria",render:function(name_categoria){
                return name_categoria.toUpperCase();
            }},
            {"data":"nombre_tipo_examen",render:function(name_tipo_examen){
                return name_tipo_examen.toUpperCase();
            }},
            {"data":"deleted_at",render:function(estado){
                return estado != null ? '<span class="badge bg-danger">Inhabilitado</span>' : '<span class="badge bg-success">Habilitado</span>'
            }}
        ]
    }).ajax.reload();
    ConfirmarEliminadoOrden(TablaOrdenes,' #tabla_ordenes tbody');
    //ClickActivarOrden(TablaOrdenes,' #tabla_ordenes tbody');
    ClickBorrarConfirmOrden(TablaOrdenes,' #tabla_ordenes tbody');
    ClickEditarOrden(TablaOrdenes,' #tabla_ordenes tbody');
}

/** MOSTRAR TIPO DE EXAMENES */
/*MOSTRAR LAS ORDENES*/
function mostrarTipoOrdenes(){
    TablaTipoExamenes = $('#tabla_tipo_examenes').DataTable({
        language:SpanishDataTable(),
        retrieve:true,
        ajax:{
            url:RUTA+"tipo-ordenes/all",
            method:"GET",
            dataSrc:"tipo_ordenes"
        },
        columns:[
            {"data":null,render:function(dato){
                if(dato.deleted_at == null){
                    return `
                  <div class='row'>
                    <div class='col-auto'>
                     <button class='btn btn-danger rounded btn-sm' id='eliminar'><i class="fas fa-trash-alt"></i></button>
                    </div>  
                    <div class='col-auto'>
                     <button class='btn btn-warning rounded btn-sm' id='editar'><i class="fas fa-edit"></i></button>
                    </div>
                  </div>
                `;
                }

                return `
                  <div class='row'>
                    <div class='col-auto'>
                     <button class='btn btn-success rounded btn-sm' id="activar"><i class="fas fa-check"></i></button>
                    </div>  
                    <div class='col-auto'>
                     <button class='btn btn-danger rounded btn-sm' id='borrar'><i class="fas fa-close"></i></button>
                    </div>  

                  </div>
                `;
            }},
            {"data":"codigo_tipo_examen"},
            {"data":"nombre_tipo_examen",render:function(tipo){
                return tipo.toUpperCase();
            }},
            {"data":"deleted_at",render:function(estado){
                return estado != null ? '<span class="badge bg-danger">Inhabilitado</span>' : '<span class="badge bg-success">Habilitado</span>'
            }}
        ]
    }).ajax.reload();
    EditarTipoExamen(TablaTipoExamenes,'#tabla_tipo_examenes tbody'); 
    ConfirmarEliminadoTipoOrden(TablaTipoExamenes,'#tabla_tipo_examenes tbody');
     
    ClickBorrarConfirmTipoOrden(TablaTipoExamenes,'#tabla_tipo_examenes tbody'); 
}

/**ELIMINAR TIPO EXAMEN */
function EditarTipoExamen(Tabla,Tbody){
  $(Tbody).on('click','#editar',function(){
    let fila = $(this).parents('tr');

    if(fila.hasClass("child")){
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();

    TipoExamenId = Data.id_tipo_examen;
    accionTipo = 'update';
    $('#text_save_tipo_orden').text("Guardar cambios")
    $('#codigo_tipo_orden').focus();
    $('#codigo_tipo_orden').val(Data.codigo_tipo_examen);
    $('#nombre_tipo_orden').val(Data.nombre_tipo_examen)
  });
}

/** Actualizar tipo examen */
function ActualizarTipoExamen(id,FormDataTipoOrdenUpdate){
    axios({
        url:RUTA+"tipo-orden/"+id+"/update",
        method:"POST",
        data:FormDataTipoOrdenUpdate
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:"Error, token invalid!!",
                icon:"error",
                target:document.getElementById('modal_create_tipo_orden')
            });
        }else{
            if(response.data.response === 'ok'){
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Tipo exámen modificado correctamente!!",
                    icon:"success",
                    target:document.getElementById('modal_create_tipo_orden')
                }).then(function(){
                   // $('#form_orden_editar')[0].reset();
                    mostrarTipoOrdenes();
                    $('#codigo_tipo_orden').val("");
                    $('#nombre_tipo_orden').val("");
                    $('#text_save_tipo_orden').text("Guardar")
                });  
            }else{
                
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Error al modificar tipo exámen!!",
                    icon:"error",
                    target:document.getElementById('modal_create_tipo_ordenn')
                });
                
            }
        }
    });
}
/**CONFIRMAR PARA ELIMINAR LA ORDEN */
function ConfirmarEliminadoTipoOrden(Tabla,Tbody){
    $(Tbody).on('click','#eliminar',function(){
      let fila = $(this).parents("tr");
  
      if(fila.hasClass("child")){
          fila = fila.prev();
      }
  
      let Data = Tabla.row(fila).data();
      TipoExamenId = Data.id_tipo_examen;
  
      Swal.fire({
          title: "Estas seguro de eliminar al tipo exámen "+Data.nombre_tipo_examen.toUpperCase()+"?",
          text: "Al aceptar, el tipo exámen se quitará de la lista y estará inhabilitado!",
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Si, eliminar!",
          target:document.getElementById('modal_create_tipo_orden')
        }).then((result) => {
          if (result.isConfirmed) {
             EliminarTipoExamen(TipoExamenId);
          }
        });
    });
  }
  
/**Metodo para eliminar examen */
function EliminarTipoExamen(id){
    let FormDeleteTipoExamen = new FormData();
    FormDeleteTipoExamen.append("token_",TOKEN);
    axios(
        {
            url:RUTA+"tipo-orden/"+id+"/eliminar",
            method:"POST",
            data:FormDeleteTipoExamen,
        }
    ).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:"Error, token invalid!!",
                icon:"error",
                target:document.getElementById('modal_create_tipo_orden')
            });
        }else{
            if(response.data.response === 'ok'){
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Tipo Exámen eliminado correctamente!!",
                    icon:"success",
                    target:document.getElementById('modal_create_tipo_orden')
                }).then(function(){
                    mostrarTipoOrdenes();
                });  
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Error al eliminar tipo exámen!!",
                    icon:"error",
                    target:document.getElementById('modal_create_tipo_orden')
                });
            }
        }
    });
}
/**CONFIRMAR PARA ACTIVAR EL TIPO ORDEN */
function ClickActivarTipoOrden(Tabla,Tbody){
    $(Tbody).on('click','#activar',function(){
      let fila = $(this).parents("tr");
  
      if(fila.hasClass("child")){
          fila = fila.prev();
      }
  
      let Data = Tabla.row(fila).data();
      TipoExamenId = Data.id_tipo_examen;
   
      ActivarTipoExamen(TipoExamenId);
    });
}

/**Metodo para Activar tipo examen */
function ActivarTipoExamen(id){
    let FormActivarTipoExamen = new FormData();
    FormActivarTipoExamen.append("token_",TOKEN);
    axios(
        {
            url:RUTA+"tipo-orden/"+id+"/activar",
            method:"POST",
            data:FormActivarTipoExamen,
        }
    ).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:"Error, token invalid!!",
                icon:"error",
                target:document.getElementById('modal_create_tipo_orden')
            });
        }else{
            if(response.data.response === 'ok'){
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Tipo exámen habilitado nuevamente!!",
                    icon:"success",
                    target:document.getElementById('modal_create_tipo_orden')
                }).then(function(){
                    mostrarTipoOrdenes();
                });  
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Error al habilitar tipo exámen!!",
                    icon:"error",
                    target:document.getElementById('modal_create_tipo_orden')
                });
            }
        }
    });
}
/**
 * Confirmar antes de borrar tipo orden
 */
function ClickBorrarConfirmTipoOrden(Tabla,Tbody){
    $(Tbody).on('click','#borrar',function(){
      let fila = $(this).parents("tr");
  
      if(fila.hasClass("child")){
          fila = fila.prev();
      }
  
      let Data = Tabla.row(fila).data();
      TipoExamenId = Data.id_tipo_examen;
      Swal.fire({
        title: "Estas seguro de borrar al tipo exámen "+Data.nombre_tipo_examen.toUpperCase()+"?",
        text: "Al aceptar, el tipo exámen se borrará de la base de datos y no podrás recuperarlo!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, borrar!",
        target:document.getElementById('modal_create_tipo_orden')
      }).then((result) => {
        if (result.isConfirmed) {
           BorrarTipoExamen(TipoExamenId);
        }
      });
       
    });
}
/**BORRAR EXAMEN */
function BorrarTipoExamen(id){
    let FormBorradoTipoExamen = new FormData();
    FormBorradoTipoExamen.append("token_",TOKEN);
    axios(
        {
            url:RUTA+"tipo-orden/"+id+"/borrar",
            method:"POST",
            data:FormBorradoTipoExamen,
        }
    ).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:"Error, token invalid!!",
                icon:"error",
                target:document.getElementById('modal_create_tipo_orden')
            });
        }else{
            if(response.data.response === 'ok'){
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Tipo exámen borrado correctamente!!",
                    icon:"success",
                    target:document.getElementById('modal_create_tipo_orden')
                }).then(function(){
                    mostrarTipoOrdenes();
                    mostrarOrdenes();
                });  
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Error al borrar tipo exámen!!",
                    icon:"error",
                    target:document.getElementById('modal_create_tipo_orden')
                });
            }
        }
    });
}



/**CONFIRMAR PARA ELIMINAR LA ORDEN */
function ConfirmarEliminadoOrden(Tabla,Tbody){
  $(Tbody).on('click','#eliminar',function(){
    let fila = $(this).parents("tr");

    if(fila.hasClass("child")){
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();
    ExamenId = Data.id_examen;

    Swal.fire({
        title: "Estas seguro de eliminar al exámen "+Data.nombre_examen.toUpperCase()+"?",
        text: "Al aceptar, el exámen se quitará de la lista y estará inhabilitado!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
      }).then((result) => {
        if (result.isConfirmed) {
           EliminarExamen(ExamenId);
        }
      });
  });
}

/**CONFIRMAR PARA ELIMINAR LA ORDEN */
function ClickActivarOrden(Tabla,Tbody){
    $(Tbody).on('click','#activar',function(){
      let fila = $(this).parents("tr");
  
      if(fila.hasClass("child")){
          fila = fila.prev();
      }
  
      let Data = Tabla.row(fila).data();
      ExamenId = Data.id_examen;
   
      ActivarExamen(ExamenId);
    });
}

/** EDITAR EL EXAMEN */
function ClickEditarOrden(Tabla,Tbody){
    $(Tbody).on('click','#editar',function(){
      let fila = $(this).parents("tr");
  
      if(fila.hasClass("child")){
          fila = fila.prev();
      }
  
      let Data = Tabla.row(fila).data();
      ExamenId = Data.id_examen;
      $('#modal_editar_orden').modal("show");
      $('#nombre_orden_editar').val(Data.nombre_examen);
      $('#precio_editar').val(Data.precio_examen.toFixed(2));
     
      $('#tipo_orden_editar').val(Data.id_tipo_examen);
      $('#codigo_orden_editar').val(Data.codigo_orden);

      ShowCategoryDisponiblesTipoEditar(Data.id_tipo_examen);
   
      $('#categoria_orden_editar').val(1);
    });
}

/// MOSTRAR CATEGORIAS DISPONIBLES
function ShowCategoryDisponiblesTipoEditar(id){
    let option = '<option selected disabled> ---- Seleccione ----</option>';
    axios({
        url:RUTA+"categorias/por-tipo/disponibles/"+id,
        method:"GET",
    }).then(function(response){
        if(response.data.categoriasdata.length > 0){
            response.data.categoriasdata.forEach(element => {
                option+=`<option value=`+element.id_categoria_examen+`>`+element.nombre_categoria.toUpperCase()+`</option>`;
            });
        }

        $('#categoria_orden_editar').html(option);
    });
}

/**
 * Confirmar antes de borrar a la orden
 */
function ClickBorrarConfirmOrden(Tabla,Tbody){
    $(Tbody).on('click','#borrar',function(){
      let fila = $(this).parents("tr");
  
      if(fila.hasClass("child")){
          fila = fila.prev();
      }
  
      let Data = Tabla.row(fila).data();
      ExamenId = Data.id_examen;
      Swal.fire({
        title: "Estas seguro de borrar al exámen "+Data.nombre_examen.toUpperCase()+"?",
        text: "Al aceptar, el exámen se borrará de la base de datos y no podrás recuperarlo!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, borrar!"
      }).then((result) => {
        if (result.isConfirmed) {
           BorrarExamen(ExamenId);
        }
      });
       
    });
}
  

/**Metodo para eliminar examen */
function EliminarExamen(id){
    let FormDeleteExamen = new FormData();
    FormDeleteExamen.append("token_",TOKEN);
    axios(
        {
            url:RUTA+"orden/"+id+"/eliminar",
            method:"POST",
            data:FormDeleteExamen,
        }
    ).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:"Error, token invalid!!",
                icon:"error",
                target:document.getElementById('app_examenes')
            });
        }else{
            if(response.data.response === 'ok'){
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Exámen eliminado correctamente!!",
                    icon:"success",
                    target:document.getElementById('app_examenes')
                }).then(function(){
                    mostrarOrdenes();
                });  
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Error al eliminar exámen!!",
                    icon:"error",
                    target:document.getElementById('app_examenes')
                });
            }
        }
    });
}

/**BORRAR EXAMEN */
function BorrarExamen(id){
    let FormBorradoExamen = new FormData();
    FormBorradoExamen.append("token_",TOKEN);
    axios(
        {
            url:RUTA+"orden/"+id+"/borrar",
            method:"POST",
            data:FormBorradoExamen,
        }
    ).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:"Error, token invalid!!",
                icon:"error",
                target:document.getElementById('app_examenes')
            });
        }else{
            if(response.data.response === 'ok'){
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Exámen borrado correctamente!!",
                    icon:"success",
                    target:document.getElementById('app_examenes')
                }).then(function(){
                    mostrarOrdenes();
                });  
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Error al borrar exámen!!",
                    icon:"error",
                    target:document.getElementById('app_examenes')
                });
            }
        }
    });
}

/**Metodo para Activar examen */
function ActivarExamen(id){
    let FormActivarExamen = new FormData();
    FormActivarExamen.append("token_",TOKEN);
    axios(
        {
            url:RUTA+"orden/"+id+"/activar",
            method:"POST",
            data:FormActivarExamen,
        }
    ).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:"Error, token invalid!!",
                icon:"error",
                target:document.getElementById('app_examenes')
            });
        }else{
            if(response.data.response === 'ok'){
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Exámen habilitado nuevamente!!",
                    icon:"success",
                    target:document.getElementById('app_examenes')
                }).then(function(){
                    mostrarOrdenes();
                });  
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Error al habilitar exámen!!",
                    icon:"error",
                    target:document.getElementById('app_examenes')
                });
            }
        }
    });
}

/**
 * Guardar tipo examen
 */
function saveTipoExamen(datosFormTipo){
    axios(
        {
            url:RUTA+"tipo_orden/store",
            method:"POST",
            data:datosFormTipo,
        }
    ).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:"Error, token invalid!!",
                icon:"error",
                target:document.getElementById('modal_create_tipo_orden')
            });
        }else{
            if(response.data.response === 'ok'){
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Tipo órden registrado correctamente!!",
                    icon:"success",
                    target:document.getElementById('modal_create_tipo_orden')
                }).then(function(){
                    $('#form_tipo_orden')[0].reset();
                });  
            }else{
               if(response.data.response === 'existe'){
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"El tipo de órden que deseas registrar ya existe por el código oh Nombre!!",
                    icon:"warning",
                    target:document.getElementById('modal_create_tipo_orden')
                });
               }else{
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Error al registrar tipo órden!!",
                    icon:"error",
                    target:document.getElementById('modal_create_tipo_orden')
                });
               }
            }
        }
    });
}

function saveExamen(datosFormOrden){
    axios(
        {
            url:RUTA+"orden/store",
            method:"POST",
            data:datosFormOrden,
        }
    ).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:"Error, token invalid!!",
                icon:"error",
                target:document.getElementById('modal_create_orden')
            });
        }else{
            if(response.data.response === 'ok'){
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"órden registrado correctamente!!",
                    icon:"success",
                    target:document.getElementById('modal_create_orden')
                }).then(function(){
                    $('#form_create_orden')[0].reset();
                    mostrarOrdenes();
                });  
            }else{
               if(response.data.response === 'existe'){
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"La órden oh éxamen que deseas registrar ya existe!!",
                    icon:"warning",
                    target:document.getElementById('modal_create_orden')
                });
               }else{
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Error al registrar órden!!",
                    icon:"error",
                    target:document.getElementById('modal_create_orden')
                });
               }
            }
        }
    });
}

/**MODIFICAR AL EXAMEN */
function ModificarExamen(id,datosFormOrdenEditar){
    axios(
        {
            url:RUTA+"orden/"+id+"/udpate",
            method:"POST",
            data:datosFormOrdenEditar,
        }
    ).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:"Error, token invalid!!",
                icon:"error",
                target:document.getElementById('modal_editar_orden')
            });
        }else{
            if(response.data.response === 'ok'){
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"exámen modificado correctamente!!",
                    icon:"success",
                    target:document.getElementById('modal_editar_orden')
                }).then(function(){
                   // $('#form_orden_editar')[0].reset();
                    mostrarOrdenes();
                });  
            }else{
                
                Swal.fire({
                    title:"Mensaje del sistema!!",
                    text:"Error al modificar exámen!!",
                    icon:"error",
                    target:document.getElementById('modal_editar_orden')
                });
                
            }
        }
    });
}


/**
 * Importartipo examen
 */
function importTipoExamen(datosFormTipo){
    axios(
        {
            url:RUTA+"tipo_orden/importar",
            method:"POST",
            data:datosFormTipo,
        }
    ).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:"Error, token invalid!!",
                icon:"error",
                target:document.getElementById('modal_create_tipo_orden')
            });
        }else{
            if(response.data.response === 'ok' || response.data.response === 'existe'){
                loading('#loading_create_tipo_orden','#4169E1','chasingDots');
                setTimeout(function(){
                    $('#loading_create_tipo_orden').loadingModal('hide');
                    $('#loading_create_tipo_orden').loadingModal('destroy');

                    Swal.fire({
                        title:"Mensaje del sistema!!",
                        text:"Datos del tipo órden importados correctamente!!",
                        icon:"success",
                        target:document.getElementById('modal_create_tipo_orden')
                    }).then(function(){
                        $('#import_excel_tipo_orden')[0].reset();
                        mostrarTipoOrdenes();
                        mostrarTipoOrdenesEdicionCombo();
                    });  
                },1000);  
                   
            }else{
                if(response.data.response === 'vacio'){
                    Swal.fire({
                        title:"Mensaje del sistema!!",
                        text:"Error, para continuar debe de seleccionar un archivo excel!!",
                        icon:"error",
                        target:document.getElementById('modal_create_tipo_orden')
                    });
                }else{
                    if(response.data.response === "archivo no aceptable"){
                        Swal.fire({
                            title:"Mensaje del sistema!!",
                            text:"Error, el archivo seleccionado es incorrecto, debe de seleccionar un archivo excel!!",
                            icon:"error",
                            target:document.getElementById('modal_create_tipo_orden')
                        });
                    }else{
                        Swal.fire({
                            title:"Mensaje del sistema!!",
                            text:"Error al importar datos del tipo órden!!",
                            icon:"error",
                            target:document.getElementById('modal_create_tipo_orden')
                        });
                    }
                }
            }
        }
    });
}


/**
 * Importartipo examen
 */
function importExamen(datosFormOrdenExcel){
    axios(
        {
            url:RUTA+"orden/importar",
            method:"POST",
            data:datosFormOrdenExcel,
        }
    ).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:"Error, token invalid!!",
                icon:"error",
                target:document.getElementById('modal_create_orden')
            });
        }else{
            if(response.data.response === 'ok' || response.data.response === 'existe'){
                loading('#loading_create_orden','#4169E1','chasingDots');
                setTimeout(function(){
                    $('#loading_create_orden').loadingModal('hide');
                    $('#loading_create_orden').loadingModal('destroy');

                    Swal.fire({
                        title:"Mensaje del sistema!!",
                        text:"Datos de los exámenes importados correctamente!!",
                        icon:"success",
                        target:document.getElementById('modal_create_orden')
                    }).then(function(){
                        $('#import_excel_orden')[0].reset();
                        mostrarOrdenes();
                    });  
                },1000);  
                   
            }else{
                if(response.data.response === 'vacio'){
                    Swal.fire({
                        title:"Mensaje del sistema!!",
                        text:"Error, para continuar debe de seleccionar un archivo excel!!",
                        icon:"error",
                        target:document.getElementById('modal_create_orden')
                    });
                }else{
                    if(response.data.response === "archivo no aceptable"){
                        Swal.fire({
                            title:"Mensaje del sistema!!",
                            text:"Error, el archivo seleccionado es incorrecto, debe de seleccionar un archivo excel!!",
                            icon:"error",
                            target:document.getElementById('modal_create_orden')
                        });
                    }else{
                        Swal.fire({
                            title:"Mensaje del sistema!!",
                            text:"Error al importar datos de los exámenes!!",
                            icon:"error",
                            target:document.getElementById('modal_create_orden')
                        });
                    }
                }
            }
        }
    });
}

/**MOSTRAR TIPO ORDENES DISPOIBLES */
function mostrarTipoOrdenesDisponibles(){
    let tipo_ordenes = '<option selected disabled> --- Seleccione Tipo ----</option>';
    axios({
        url:RUTA+"tipo_ordenes/disponibles",
        method:"GET",
    }).then(function(response){
        if(response.data.tipo_ordenes.length > 0){
            response.data.tipo_ordenes.forEach(element => {
                tipo_ordenes+=`<option value="`+element.id_tipo_examen+`">`+element.nombre_tipo_examen.toUpperCase()+`</option>`;
            });

            $('#tipo_orden').html(tipo_ordenes);
        }else{
            $('#tipo_orden').html(tipo_ordenes);   
        }
    });
}

/**MOSTRAR TIPO ORDENES COMBO */
function mostrarTipoOrdenesEdicionCombo(){
    let tipo_ordenes = '<option selected disabled>---Seleccionar ----</option>';
    axios({
        url:RUTA+"tipo-ordenes/all",
        method:"GET",
    }).then(function(response){
        if(response.data.tipo_ordenes.length > 0){
            response.data.tipo_ordenes.forEach(element => {
                tipo_ordenes+=`<option value="`+element.id_tipo_examen+`">`+element.nombre_tipo_examen.toUpperCase()+`</option>`;
            });

            $('#tipo_orden_editar').html(tipo_ordenes);
        }else{
            $('#tipo_orden_editar').html(tipo_ordenes);   
        }
    });
}