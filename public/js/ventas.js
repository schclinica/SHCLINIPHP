/**
 * Proceso para buscar cliente
 */

$('#documento_venta').keyup(function(){
 if($(this).val().trim().length === 0 ||( $(this).val().trim().length >= 8 && $(this).val().trim().length <=11))
 {
   $('.error_buscar_cliente').hide();
   $('#cliente_venta').val("");
    $.ajax({
     url:RUTA+"app/farmacia/buscar_cliente/"+$(this).val(),
     method:"GET",
     dataType:"json",
     success:function(response)
     {
      
       if(!Array.isArray(response.response))
       {
        $('.error_buscar_cliente_two').hide();
        const cliente_text = response.response.apellidos+" "+response.response.nombres;
        $('#cliente_venta').val(cliente_text);
        CLIENTE_VENTA_ID = response.response.id_cliente;
       }else{
        $('.error_buscar_cliente_two').show(400);
        $('#cliente_venta').val("");
        CLIENTE_VENTA_ID = null;
       }
     }
    });
 }else{
   $('.error_buscar_cliente').show(300);
   $('.error_buscar_cliente_two').hide();
   $('#cliente_venta').val("");
   CLIENTE_VENTA_ID = null;
 }
});

/// dar requisito al input de  monto_recibido
$('#monto_recibido').keypress(function(evento){
 if(evento.which == 13)
 {
  evento.preventDefault();
  if($(this).val().trim().length == 0)
  {
    $(this).focus();
  }else{
     if(parseFloat($(this).val()) < $('#total_venta').val())
     {
      Swal.fire({
        title:"Mensaje del sistema!",
        text:"La cantidad ingresada debe ser superior a la cantidad total de la venta!",
        icon:"error"
    });
      $('#msg_error_monto').show(400);
      $('#vuelto').val("0.00");
     }else{
      $('#msg_error_monto').hide();
      /// calculamos el monto del vuelto
      let vuelto = parseFloat($(this).val()) - parseFloat($('#total_venta').val());

      $('#vuelto').val(vuelto.toFixed(2));
     }
  }
 }
});

/** Listar clientes para buscar  */
function ListarClientesParaLaVenta()
{
  TablaSearchClienteVenta = $('#lista_search_cliente').DataTable({
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
      {"data":null},
      {"data":"num_doc"},
      {"data":null,render:function(clientedata){
        return clientedata.apellidos.toUpperCase()+" "+clientedata.nombres.toUpperCase();
      }},
      {"data":null,render:function(){
        return `<button class='btn btn-primary rounded btn-sm' id='seleccionar_cliente_venta'><i class='bx bx-check'></i></button>`;
      }}
    ]
  }).ajax.reload();
  /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
  TablaSearchClienteVenta.on('order.dt search.dt', function() {
    TablaSearchClienteVenta.column(0, {
        search: 'applied',
        order: 'applied'
    }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
    });
}).draw();
}

function ListarProductosParaLaVenta()
{
  TablaSearchProductoVenta = $('#lista_search_productos').DataTable({
    language: SpanishDataTable(),
    bDestroy: true,
    responsive: true,
    processing: true,
    "columnDefs": [{
        "searchable": false,
        "orderable": false,
        "targets": 0
    }],
    ajax:{
      url:RUTA+"app/farmacia/consultar_productos",
      method:"GET",
      dataSrc:"response"
    },
    columns:[
      {"data":"producto"},
      {"data":"id_producto"},
      {"data":null,render:function(stockdatabutton){

        if(parseInt(stockdatabutton.stock) > 0  )
        {
          return `<button class='btn btn-outline-success rounded btn-sm' id='add_prod_venta'><i class='bx bxs-cart-add'></i></button>`;
         // return `<button class='btn btn-outline-success rounded btn-sm' id='add_prod_venta' disabled><i class='bx bxs-cart-add'></i></button>`;
        }
        return '';
         
      },className:"text-center"},
      {"data":"producto",render:function(productoname){
        return productoname.toUpperCase();
      }},
      {"data":null,render:function(stockdata){
        if(parseInt(stockdata.stock) <= parseInt(stockdata.stock_minimo))
        {
            return `<span class='badge bg-danger'><b>`+stockdata.stockprincipal+`</b></span>`;
        }
        return `<span class='badge bg-success'><b>`+stockdata.stockprincipal+`</b></span>`;
      }},
       {"data":null,render:function(stockdata){
        if(parseInt(stockdata.stock) <= parseInt(stockdata.stock_minimo))
        {
            return `<span class='badge bg-danger'><b>`+stockdata.stock+`</b></span>`;
        }
        return `<span class='badge bg-success'><b>`+stockdata.stock+`</b></span>`;
      },className:"text-center"},
      {"data":null,render:function(nombreprod){
        if(parseInt(nombreprod.stockprincipal)>0 && ( parseInt(nombreprod.stockprincipal) <= parseInt(nombreprod.stock_minimo)))
        {
            return `<span class='text-warning'><b>`+"Stock insuficiente"+`</b></span>`;
        }
        else{
          if(parseInt(nombreprod.stockprincipal) === 0)
          {
            return `<span class='text-danger'><b>`+"Sin stock"+`</b></span>`;
          }
          return `<span class='text-primary'><b>`+"Tiene stock suficiente"+`</b></span>`;
        }
      }},
      {"data":"precio_venta",render:function(precio){
        return `<span class='badge bg-primary'>`+precio+`</span>`;
      },className:"text-center"},
      {"data":"marca",render:function(marca){
        return marca.toUpperCase();
      },className:"text-center"},
      {"data":"tipo",render:function(tipo){
        return tipo.toUpperCase();
      },className:"text-center"},
      {"data":"presentacion",render:function(presentacion){
        return presentacion.toUpperCase();
      }},
      {"data":"grupo",render:function(gruponame){
        return gruponame.toUpperCase();
      }},
      {"data":"tipoprice",className:"d-none"}
    ],
    columnDefs:[
      { "sClass": "hide_me", target: 1 }
    ]
    
  });
  /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
  TablaSearchProductoVenta.on('order.dt search.dt', function() {
    TablaSearchProductoVenta.column(0, {
        search: 'applied',
        order: 'applied'
    }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
    });
}).draw();
}

/** Seleccionar cliente para la venta */
function SeleccionarCliente(Tabla,Tbody)
{
 $(Tbody).on('click','#seleccionar_cliente_venta',function(){
  let fila = $(this).parents("tr");

  if(fila.hasClass('child'))
  {
    fila = fila.prev();
  }

  let Data = Tabla.row(fila).data();

  CLIENTE_VENTA_ID = Data.id_cliente ;

  $('#cliente_venta').val(Data.apellidos+" "+Data.nombres);
  $('#documento_venta').val(Data.num_doc);
  $('#modal_listado_clientes').modal("hide");
 });
}

/// Agregar productos a la cesta
function addCestaProducto(Tabla,Tbody)
{
  $(Tbody).on('click','#add_prod_venta',function(){
    let fila = $(this).parents('tr');

    if(fila.hasClass('child')){
      fila = fila.prev();
    }

    let DataId = fila.find('td').eq(1).text();
    let PriceIdent = fila.find("td").eq(12).text();
  
    $.ajax({
      url:RUTA+"app/farmacia/venta/add_cesta/"+DataId+"/"+PriceIdent,
      method:"POST",
      dataSrc:"response",
      dataType:"json",
      success:function(response)
      {
        if(response.error != undefined){
            Swal.fire({
              title:"MENSAJE DEL SISTEMA!!",
              text:response.error,
              icon:"error",
              target:document.getElementById('modal_listado_producto_venta')
            })
            return;
        }
        if(response.response === 'agregado')
        {
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Producto agregado a la cesta correctamente",
            showConfirmButton: false,
            timer: 1000,
            target:document.getElementById('modal_listado_producto_venta')
          }).then(function(){
            showProductosDeLaCesta();
            
          });
           
        }else{
            Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Cantidad modificado del producto "+fila.find('td').eq(3).text(),
            showConfirmButton: false,
            timer: 1000,
            target:document.getElementById('modal_listado_producto_venta')
          }).then(function(){
            showProductosDeLaCesta();
          });
        }
      }
    });
  });
}

/** 
 * Mostrar los productos añadidos a la cesta
 */
function showProductosDeLaCesta()
{
  let productosCesta = show_Personalice(RUTA+"app/farmacia/venta/show_productos_cesta");
 
  let tr = '';let productosCestaData = [];let importe = 0.00;let TotalPagar = 0.00;
  let SubTotal = 0.00;let IvaVenta = 0.00;
  if(!Array.isArray(productosCesta.response)){
   
    let productosCestaData = Object.values(productosCesta.response);
     
     
    productosCestaData.forEach(element => {
      importe = element.precio*element.cantidad;
      TotalPagar+=importe;
      SubTotal = TotalPagar / (1 + (IvaData / 100)); /// igv incluido

      IvaVenta = TotalPagar - SubTotal;
      tr+=`<tr>
      <td class="text-center"><button class="btn btn-outline-danger btn-sm rounded" id='quitar_producto_detalle'><i class='bx bx-trash'></i></button></td>
      <td class='text-center col-1'><input type="text" id="cantidad_en_cesta" class="form-control text-center" value=`+element.cantidad+` onkeydown="return event.keyCode !== 189 && event.keyCode !== 109" onkeypress="return event.charCode >= 48" min="0"></td>
      <td class="text-center">`+element.descripcion_precio+`</td>
      <td class="text-center">`+element.empaque.toUpperCase()+`</td>
      <td class="text-center">`+element.precio+`</td>
      <td class="text-center">`+importe.toFixed(2)+`</td>
      <td class="text-center d-none">`+element.producto_id+`</td>
      <td class="text-center d-none">`+element.priceident+`</td>
      </tr>`;;
    });
      $('#sub_total_venta').val(SubTotal.toFixed(2));
      $('#igv_venta').val(IvaVenta.toFixed(2));
      $('#total_venta').val(TotalPagar.toFixed(2))
     
  }else{
    
   tr+=`<td colspan="5"><span class="text-danger">No hay productos agregados a la cesta...</span></td>'`;
  }

  $('#lista_detalle_venta_body').html(tr);
}

/// quitar producto de la cesta
function QuitarProductoCesta()
{
  $('#lista_detalle_venta_body').on('click','#quitar_producto_detalle',function(){
    let fila = $(this).parents('tr');
    let ProductoText = fila.find('td').eq(2).text();
    let Producto = fila.find('td').eq(6).text();
    let PriceIdent = fila.find("td").eq(7).text();
    Swal.fire({
      title: "Estas seguro de quitar el producto "+ProductoText+" de la cesta ?",
      text: "Al aceptar, automaticamente se quitará de la lista de la cesta!",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar!"
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url:RUTA+"app/farmacia/venta/quitar_producto_cesta/"+Producto+"/"+PriceIdent,
          method:"POST",
          dataType:"json",
          data:{
            producto:ProductoText
          },
          success:function(response){
           if(response.response === "eliminado")
           {
            Swal.fire({
              title:"Mensaje del sistema!",
              text:"Producto quitado de la cesta correctamente!",
              icon:"success"
            }).then(function(){
              if($('#lista_detalle_venta_body tr').length == 1)
              {
                $('#igv_venta').val("0.00");$('#total_venta').val("0.00");$('#sub_total_venta').val("0.00");
                
              } 
                showProductosDeLaCesta();
              
            });
           }else{
            Swal.fire({
              title:"Mensaje del sistema!",
              text:"Error al eliminar producto de la cesta!",
              icon:"error"
            });
           }
          },error:function(err){
            Swal.fire({
              title:"Mensaje del sistema!",
              text:"Error al eliminar producto de la cesta!",
              icon:"error"
            });
          }
        });  
      }
    });
  });
}

/// modificar la cantidad del producto añadido a la cesta
function modificarCantidadProductoCesta()
{
 
  $('#lista_detalle_venta_body').on('keyup','#cantidad_en_cesta',function(evento){
     
       if(parseInt($(this).val()) == 0)
       {
         
         showProductosDeLaCesta();
         $(this).focus(); 
       } 
 
   });
  $('#lista_detalle_venta_body').on('keypress','#cantidad_en_cesta',function(evento){
   if(evento.which === 13)
    {
      evento.preventDefault();

      if($(this).val().trim().length > 0)
      {
        let fila = $(this).parents('tr');
        
        
         let Producto = fila.find('td').eq(6).text();
        let PriceIdent = fila.find("td").eq(7).text();
        $.ajax({
          url:RUTA+"app/farmacia/venta/update_cantidad__producto_cesta/"+Producto+"/"+PriceIdent,
          method:"POST",
          data:{
            cantidad_cesta:$(this).val()
          },
          dataType:"json",
          success:function(response){
            if (response.error != undefined) {
              Swal.fire({
                title: "MENSAJE DEL SISTEMA!!",
                text: response.error,
                icon: "error",
              }).then(function(){
                showProductosDeLaCesta()
              });
              return;
            }
            if(response.response === 'modificado')
            {
              Swal.fire({
                title:"Mensaje del sistema!",
                text:"Cantidad modificado correctamente!",
                icon:"success"
              }).then(function(){
                showProductosDeLaCesta();
              });
            }else{
              if(response.response === 'stock-insuficiente')
              {
                Swal.fire({
                  title:"Mensaje del sistema!",
                  text:"La cantidad ingresada supera al stock del producto en esta sucursal, ingrese una cantidad menor a su stock!",
                  icon:"error"
                }).then(function(){
                  showProductosDeLaCesta();
                });
              }else{
                Swal.fire({
                  title:"Mensaje del sistema!",
                  text:"Error al modificar la cantidad del producto!",
                  icon:"error"
                });
              }
            }
          }
        });
      }else{
        showProductosDeLaCesta();
      }
    }

  });
}

/// cancelar la venta
function CancelVentaDetalle()
{
  $.ajax({
    url:RUTA+"app/farmacia/venta/cancel",
    method:"POST",
    data:{
      _token:TOKEN
    },
    success:function(response){
      showProductosDeLaCesta();
    }
  });
}

/// obtener número de la serie de la venta
function getSerieVenta()
{
  $.ajax({
    url:RUTA+"venta-farmacia/generate-serie-correlativo",
    method:"GET",
    dataType:"json",
    success:function(response){
     $('#serie').val(response.serie);
    }
  });
}

/// guardar la venta
function saveVentaFarmacia()
{
  $.ajax({
    url:RUTA+"app/farmacia/venta/save",
    method:"POST",
    data:{
      _token:TOKEN,serie_venta:$('#serie').val(),fecha_emision:$('#fecha_emision_venta').val(),
      cliente_id:CLIENTE_VENTA_ID,monto_recibido:$('#monto_recibido').val(),vuelto:$('#vuelto').val()
    },
    dataType:"json",
    success:function(response)
    {
     if(response.response == 1)
     {
      Swal.fire({
        title:"Mensaje del sistema!",
        text:"Venta realizado con éxito",
        icon:"success"
      }).then(function(){
        /// mandmao a imprirmir el ticket
        window.open(RUTA+"app/farmacia/tiecket_de_venta?v="+$('#serie').val(),"_target");
        ListarProductosParaLaVenta();
        CancelVentaDetalle();
        getSerieVenta();
        CLIENTE_VENTA_ID = null;
        $('#documento_venta').val("");
        $('#cliente_venta').val("Público en general");
        $('#fecha_emision_venta').val(FechaActualVenta);
        $('#igv_venta').val("0.00");$('#total_venta').val("0.00");$('#sub_total_venta').val("0.00");
        $('#monto_recibido').val("");$('#vuelto').val("0.00");
        $('#msg_error_monto').hide();
      });
     }
    }
  })
}
