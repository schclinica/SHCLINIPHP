/**A LA ESCUCHA DEL CHECKBOX */
$("#all_sedes").click(function(){
  let Check = $(this).is(":checked");/// true | false
  if(Check){
    $('#tabla_lista_almacenes input[type="checkbox"]').prop("checked",true);
    $('#tabla_lista_almacenes input[type="text"]').prop("disabled",false);
    $('#tabla_lista_almacenes select').prop("disabled",false);
    AsignarAlmacenesAlProducto();
  }else{
    $('#tabla_lista_almacenes input[type="checkbox"]').prop("checked",false);
    $('#tabla_lista_almacenes input[type="text"]').prop("disabled",true);
    $('#tabla_lista_almacenes select').prop("disabled",true);
    QuitarAlmacenesAlProducto();
  }
});
/*A LA ESCUCHA DEL CHECKED DE LA TABLA */
 $('#tabla_lista_almacenes').on('click','input[type="checkbox"]',function(){
    let fila = $(this).closest("tr");
    let Checked = $(this).is(":checked");
    let IdAlmacen = $(this).val();
    if(Checked){
       fila.find("#price_venta").prop("disabled",false);
       fila.find("#stock").prop("disabled",false);
       fila.find("#presentacion").prop("disabled",false);
       AddAlmacenAlProducto(IdAlmacen);
    }else{
        fila.find("#price_venta").prop("disabled",true);
        fila.find("#stock").prop("disabled",true);
        fila.find("#presentacion").prop("disabled",true);
        QuitarAlmacenAlProducto(IdAlmacen);
    }
 });
 
 /// modificar cantidad
 $('#tabla_lista_almacenes').on('keyup','#stock',function(evento){
    let Precio;
    let fila = $(this).closest("tr");
    let IdSede = fila.find("input[type='checkbox']").val();
        
        cantidad =  $(this).val().trim().length == 0 ? "0":$(this).val();
 
        ModificarStockAlmacenProducto(IdSede,cantidad);
 });

 /**ASIGNAR TODOS LOS ALMACENES AL PRODUCTO POR DEFECTO */
 function AsignarAlmacenesAlProducto(){
    let FormAsignedAlmacenes = new FormData();
    FormAsignedAlmacenes.append("token_",TOKEN);
    axios({
        url:RUTA+"productos/asignar-almacen/"+IDPRODUCTO,
        method:"POST",
        data:FormAsignedAlmacenes
    }) 
 }

  /**QUITAR TODOS LOS ALMACENES AL PRODUCTO POR DEFECTO */
 function QuitarAlmacenesAlProducto(){
    let FormQuitarAlmacenes = new FormData();
    FormQuitarAlmacenes.append("token_",TOKEN);
    axios({
        url:RUTA+"producto/quitar/almacenes",
        method:"POST",
        data:FormQuitarAlmacenes
    }) 
 }

 /**GUARDAR LOS ALMACENES DEL PRODUCTO */
 function SaveAlmacenProdcuto(){
    let FormSaveAlmacenes = new FormData();
    FormSaveAlmacenes.append("token_",TOKEN);
    FormSaveAlmacenes.append("producto",IDPRODUCTO);
    axios({
        url:RUTA+"producto/almacenes/save",
        method:"POST",
        data:FormSaveAlmacenes
    }).then(function(response){
       if(response.data.error != undefined){
         Swal.fire({
            title:"MENSAJE DEL SISTEMA!!",
            text:response.data.error,
            icon:"error",
            target:document.getElementById("modal_open_asignar_almacenes")
         })
       }else{
         Swal.fire({
            title:"MENSAJE DEL SISTEMA!!",
            text:response.data.response,
            icon:"success",
            target:document.getElementById("modal_open_asignar_almacenes")
         }).then(function(){
            showAlmacenes();
            $('#tabla_lista_almacenes input[type="checkbox"]').prop("checked",false);
            $('#tabla_lista_almacenes input[type="text"]').val("0");
            showProducto();
         });
       }
    });
 }

 /// AGRGAR ALMACEN SELECIONADO
  function AddAlmacenAlProducto(id){
    let FormAddAlmacen = new FormData();
    FormAddAlmacen.append("token_",TOKEN);
    axios({
        url:RUTA+"/producto/add/almacen/"+id,
        method:"POST",
        data:FormAddAlmacen
    });
 }
  /// QUITAR ALMACEN SELECIONADO
  function QuitarAlmacenAlProducto(id){
    let FormQuitarAlmacen = new FormData();
    FormQuitarAlmacen.append("token_",TOKEN);
    axios({
        url:RUTA+"producto/quitar/almacen/"+id,
        method:"POST",
        data:FormQuitarAlmacen
    });
 }
 /// MODIFICAR EL PRECIO
   function ModificarPriceAlmacenProducto(id,precioData){
    let FormModificarAlmacen = new FormData();
    FormModificarAlmacen.append("token_",TOKEN);
    FormModificarAlmacen.append("precio",precioData);
    axios({
        url:RUTA+"producto/modificar-precio/almacen/"+id,
        method:"POST",
        data:FormModificarAlmacen
    }) 
 }

  /// MODIFICAR EL STOCK
function ModificarStockAlmacenProducto(id,stockData){
    let FormModificarstockAlmacen = new FormData();
    FormModificarstockAlmacen.append("token_",TOKEN);
    FormModificarstockAlmacen.append("stock",stockData);
    axios({
        url:RUTA+"producto/modificar-stock/almacen/"+id,
        method:"POST",
        data:FormModificarstockAlmacen
    }) 
 }

 /**MODIFICAR LA PRESENTACION DEL PRODUCTO DE LA LISTA */
function ModificarPresentacionAlmacenProducto(id,valuePresentacion){
    let FormModificarPresentacionAlmacen = new FormData();
    FormModificarPresentacionAlmacen.append("token_",TOKEN);
    FormModificarPresentacionAlmacen.append("presentacion",valuePresentacion);
    axios({
        url:RUTA+"producto/almacen/presentacion/modificar/"+id,
        method:"POST",
        data:FormModificarPresentacionAlmacen
    }) 
 }
 /// VER LAS PRESENTACIONES DISPONIBLES
 function showPresentacionesDisponibles(){
    let option = '<option selected disabled>----Seleccione -----</option>';
    $.ajax({
      url: RUTA + "presentaciones/disponibles",
      method: "GET",
      dataType: "json",
      async: false,
      success: function (response) {
        if (response.presentaciones.length > 0) {
          response.presentaciones.forEach((presentacion) => {
            option +=`<option value=` +presentacion.id_pesentacion+`>`+(presentacion.name_presentacion.toUpperCase()+" - "+presentacion.name_corto_presentacion.toUpperCase()) +`</option>`;
          });
        }
      },
    });
    return option;
}
 
/// MOSTRAR LOS PRODUCTOS POR ALMACEN
function MostrarProductosPorAlmacen(){
   ProductosAlmacen = $('#lista_productos_almacen').DataTable({
      retrieve:true,
      language: SpanishDataTable(),
      ajax:{
         url:RUTA+"productos/por/almacen",
         method:"GET",
         data:function(dato){
            dato.id=IDPRODUCTO
         },
         dataSrc:"productos"
      },
      columns:[
        
         {"data":"namesede",render:function(namesede){
            return namesede.toUpperCase();
         }},
         {"data":null,render:function(datop){
            return (datop.nombre_producto+" "+datop.name_presentacion+"  "+datop.name_corto_presentacion).toUpperCase();
         }},
         {"data":"name_presentacion",render:function(name_presentacion){
            return name_presentacion.toUpperCase();
         }},
         {"data":"precio_venta",className:"text-center"},
         {"data":"stock",className:"text-center"},
          {"data":null,render:function(){
            return  `<button class="btn btn-outline-danger btn-sm" id='delete'><i class='bx bx-x'></i></button>
            <button class="btn btn-outline-warning btn-sm" id='editar'><i class='bx bxs-edit-alt'></i></button>
            `;
         },className:"text-center"}
      ]
   });
}
/// CONFIRMAR ANTES DE ELIMINAR ALMACEN DEL PRODUCTO
function ConfirmarEliminadoAlmacenDelProducto(Tabla,Tbody){
   $(Tbody).on('click','#delete',function(){
      let fila = $(this).parents("tr");
      if(fila.hasClass("child")){
         fila = fila.prev();
      }

      let Data = Tabla.row(fila).data();
      PRODUCTO_ALMACEN_ID = Data.id_producto_almacen;
      Swal.fire({
        title: "Estas seguro?",
        text: "Al aceptar el Almacen del producto seleccionado se quitará de la lista!!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        target:document.getElementById('modal_open_asignar_almacenes')
      }).then((result) => {
        if (result.isConfirmed) {
           ProcesoDeleteAlmacenProducto(PRODUCTO_ALMACEN_ID);
        }
      });
   })
}

/// CONFIRMAR ANTES DE ELIMINAR ALMACEN DE LA LISTA DEL PRODUCTO
function ProcesoDeleteAlmacenProducto(id){
   let FormDeleteProductoAlmacen = new FormData();
   FormDeleteProductoAlmacen.append("token_",TOKEN);
    
  axios({
    url:RUTA+"producto/"+id+"/eliminar-su-almacen-asignado/delete",
    method:"POST",
    data:FormDeleteProductoAlmacen
  }).then(function(response){
     if(response.data.error != undefined){
       Swal.fire({
         title:"MENSAJE DEL SISTEMA!!!",
         text:response.data.error,
         icon:"error",
         target:document.getElementById('modal_open_asignar_almacenes')
       })
     }else{
           
          Swal.fire({
            title: "MENSAJE DEL SISTEMA!!!",
            text: response.data.response,
            icon: "success",
            target: document.getElementById("modal_open_asignar_almacenes"),
          }).then(function () {
            MostrarProductosPorAlmacen();
            ProductosAlmacen.ajax.reload();
            showAlmacenes();
            AsignarAlmacenesAlProducto();
          });
     }
  })
}

/// asignar precios al producto de cada almacen
function addPrecioProductoAlmacen(Tabla,Tbody){
 $(Tbody).on('click','#add_price',function(evento){
   evento.preventDefault();
   let fila = $(this).parents("tr");
   if(fila.hasClass("child")){
      fila = fila.prev();
   }
 
   let Data = Tabla.row(fila).data();
   IDPRODUCTO = Data.id_producto;
   $('#text_precios').text(Data.nombre_producto.toUpperCase())
  $('#presentacion_select_prices').html(showPresentacionesDisponibles());
  showPreciosProducto();
   $('#modal_open_add_prices').modal("show");
   TablaListaPrecios.ajax.reload();
  editarPreciosProducto(TablaListaPrecios,'#lista_precios tbody');
  ConfirmEliminadoPrecioProductoPresentacion(TablaListaPrecios,'#lista_precios tbody');
 });
}
/// editar precio y stock del producto
function EditarPrecioStockProductoAlmacen(Tabla,Tbody){
 $(Tbody).on('click','#editar',function(){
   let fila = $(this).parents("tr");
   if(fila.hasClass("child")){
      fila = fila.prev();
   }
   
   let Data = Tabla.row(fila).data();
   PRODUCTO_ALMACEN_ID = Data.id_producto_almacen;
   AuxStockEdit = Data.stock;
   $('#almacen_editar_texto').val(Data.namesede.toUpperCase());
   $('#producto_editar_texto').val((Data.nombre_producto+" "+Data.name_presentacion+"  "+Data.name_corto_presentacion).toUpperCase());
   $('#modal_open_editar_price_stock_producto').modal("show");
   $('#stock_prod_almacen').val(Data.stock);
   $('#precio_prod_almacen').val(Data.precio_venta)
 });
}
/// AGREGAR PRECIO AL PRODUCTO
function saveAddPrecioProducto(id,presentacionData,cantidadData,precioData){
   let FormAddSavePrecioProducto = new FormData();
   FormAddSavePrecioProducto.append("token_",TOKEN);
   FormAddSavePrecioProducto.append("presentacion",presentacionData.val());
   FormAddSavePrecioProducto.append("cantidad",cantidadData.val());
   FormAddSavePrecioProducto.append("precio",precioData.val());
  axios({
    url:RUTA+"agregar-precio/producto/"+id,
    method:"POST",
    data:FormAddSavePrecioProducto
  }).then(function(response){
     if(response.data.error != undefined){
       Swal.fire({
         title:"MENSAJE DEL SISTEMA!!!",
         text:response.data.error,
         icon:"error",
         target:document.getElementById('modal_open_add_prices')
       })
     }else{
        if (response.data.existe != undefined) {
          Swal.fire({
            title: "MENSAJE DEL SISTEMA!!!",
            text: response.data.existe,
            icon: "warning",
            target: document.getElementById("modal_open_add_prices"),
          });
        } else {
          Swal.fire({
            title: "MENSAJE DEL SISTEMA!!!",
            text: response.data.response,
            icon: "success",
            target: document.getElementById("modal_open_add_prices"),
          }).then(function () {
            showPreciosProducto();
            TablaListaPrecios.ajax.reload();
            presentacionData.prop("selectedIndex", 0);
            cantidadData.val("");
            precioData.val("");
          });
        }
     }
  })
}
/// ver la lista de los precios
function showPreciosProducto(){
   TablaListaPrecios = $('#lista_precios').DataTable({
retrieve:true,
language: SpanishDataTable(),
      ajax:{
         url:RUTA+"productos/precios",
         method:"GET",
         data:function(dato){
            dato.id=IDPRODUCTO
         },
         dataSrc:"producto_precios"
      },
      columns:[
       
         {"data":null,render:function(datop){
            return (datop.name_presentacion+"  "+datop.name_corto_presentacion).toUpperCase();
         }},
         {"data":"cantidadp"},
         {"data":"preciop"},
          {"data":null,render:function(){
            return  `<button class="btn btn-outline-danger btn-sm" id='delete'><i class='bx bx-x'></i></button>
            <button class="btn btn-outline-warning btn-sm" id='editar'><i class='bx bxs-edit-alt'></i></button>
            `;
         },className:"text-center"}
      ]
   });
}
 

/**EDITAR LOS PRECIOS DEL PRODUCTO */
function editarPreciosProducto(Tabla,Tbody){
   $(Tbody).on('click','#editar',function(){
      let fila = $(this).parents("tr");
      if(fila.hasClass("child")){
         fila = fila.prev();
      }

      let Data = Tabla.row(fila).data();
      PRODUCTO_PRESETACION_ID = Data.id_producto_empaque_precio;
      $('#presentacion_select_prices').val(Data.presentacion_id);
      $('#cantidad_add').val(Data.cantidadp);
      $('#precio_add').val(Data.preciop);
      AccionButtonProducto_Presentacion = 'update';
   });
}
// CONFIRMAR ANTES DE ELIMINAR AL PRECIO DEL PRODUCTO
function ConfirmEliminadoPrecioProductoPresentacion(Tabla,Tbody){
   $(Tbody).on('click','#delete',function(){
      let fila = $(this).parents("tr");
      if(fila.hasClass("child")){
         fila = fila.prev();
      }

      let Data = Tabla.row(fila).data();
      PRODUCTO_PRESETACION_ID = Data.id_producto_empaque_precio;
      Swal.fire({
        title: "Estas seguro?",
        text: "Al aceptar, se borrará el precio de la presentación!!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        target:document.getElementById('modal_open_add_prices')
      }).then((result) => {
        if (result.isConfirmed) {
          ProcesoDeletePrecioProductoPresentacion(PRODUCTO_PRESETACION_ID);
        }
      });
   });
}

/// PROCESO DE ELIMINAR AL PRECIO DEL PRODUCTO DE LA LISTA 
function ProcesoDeletePrecioProductoPresentacion(id){
   let FormDeletePreciosProductoPresentacion = new FormData();
   FormDeletePreciosProductoPresentacion.append("token_",TOKEN);
    
  axios({
    url:RUTA+"producto/"+id+"/quitar-precio-de-lista/delete",
    method:"POST",
    data:FormDeletePreciosProductoPresentacion
  }).then(function(response){
     if(response.data.error != undefined){
       Swal.fire({
         title:"MENSAJE DEL SISTEMA!!!",
         text:response.data.error,
         icon:"error",
         target:document.getElementById('modal_open_add_prices')
       })
     }else{
           
          Swal.fire({
            title: "MENSAJE DEL SISTEMA!!!",
            text: response.data.response,
            icon: "success",
            target: document.getElementById("modal_open_add_prices"),
          }).then(function () {
            showPreciosProducto();
            TablaListaPrecios.ajax.reload();
          });
     }
  })
}

/// MODIFICAR LOS NUEVOS PRECIOS DEL PRODUCTO
function ModificarPreciosProductoPresentacion(id,presentacionData,precioData,stockData){
   let FormAddModifyPreciosProductoPresentacion = new FormData();
   FormAddModifyPreciosProductoPresentacion.append("token_",TOKEN);
   FormAddModifyPreciosProductoPresentacion.append("stock",stockData.val());
   FormAddModifyPreciosProductoPresentacion.append("precio",precioData.val());
   FormAddModifyPreciosProductoPresentacion.append("presentacion",presentacionData.val());
  axios({
    url:RUTA+"producto/update/precios/presentacion/"+id,
    method:"POST",
    data:FormAddModifyPreciosProductoPresentacion
  }).then(function(response){
     if(response.data.error != undefined){
       Swal.fire({
         title:"MENSAJE DEL SISTEMA!!!",
         text:response.data.error,
         icon:"error",
         target:document.getElementById('modal_open_add_prices')
       })
     }else{
           
          Swal.fire({
            title: "MENSAJE DEL SISTEMA!!!",
            text: response.data.response,
            icon: "success",
            target: document.getElementById("modal_open_add_prices"),
          }).then(function () {
            showPreciosProducto();
            TablaListaPrecios.ajax.reload();
            AccionButtonProducto_Presentacion = 'save';
            presentacionData.prop("selectedIndex",0);
            precioData.val("");
            stockData.val("");
          });
     }
  })
}
/// modificar el precio y stock del producto
function ModificarPrecioStockProductoAlmacen(id,precioData,stockData){
   let FormAddModifyStockPrecioProducto = new FormData();
   FormAddModifyStockPrecioProducto.append("token_",TOKEN);
   FormAddModifyStockPrecioProducto.append("stock",stockData.val());
  axios({
    url:RUTA+"producto/modificar/precio-stock/"+id,
    method:"POST",
    data:FormAddModifyStockPrecioProducto
  }).then(function(response){
     if(response.data.error != undefined){
       Swal.fire({
         title:"MENSAJE DEL SISTEMA!!!",
         text:response.data.error,
         icon:"error",
         target:document.getElementById('modal_open_editar_price_stock_producto')
       })
     }else{
          $('#modal_open_editar_price_stock_producto').modal("hide")
          Swal.fire({
            title: "MENSAJE DEL SISTEMA!!!",
            text: response.data.response,
            icon: "success",
            target: document.getElementById("modal_open_asignar_almacenes"),
          }).then(function () {
            MostrarProductosPorAlmacen();
            ProductosAlmacen.ajax.reload();
            showProducto();
          });
     }
  })
}