/**MOSTRAR LOS PRODUCTOS POR SUCURSAL */
function showProductos(){
    TablaProductos = $('#lista_productos').DataTable({
        retrieve:true,
        language:SpanishDataTable(),
        ajax:{
            url:RUTA+"productos/all/farmacia",
            method:"GET",
            data:function(dato){
            dato.sede=$('#sede').val()
           },
           dataSrc:"productos",
        },
        columns:[
            {"data":null,render:function(dato){
                if(dato.lote === 'si'){
                    return `<button class="btn btn-outline-primary btn-sm" id="lote"><i class='bx bx-spray-can'></i></button>
                    <button class="btn btn-outline-info btn-sm" id="ver_lote"><i class='bx bxs-package'></i></button>`;
                }
                return '<span class="badge bg-danger">SIN LOTE !</span>';
            },className:"text-center"},
            {"data":"code_barra",render:function(code){
                return `<span class="badge bg-success">`+code+`</span>`;
            }},
            {"data":null,render:function(nameprod){
                return nameprod.lote === 'si' ? nameprod.nombre_producto+" <b class='badge bg-primary'>[ LOTE ! ] </b>" : nameprod.nombre_producto+"<b class='badge bg-danger'>[ SIN LOTE !]</b>";
            }},
            {"data":null,render:function(ct){
                return parseInt(ct.stock) <= parseInt(ct.stock_minimo) ? '<span class="badge bg-danger">'+ct.stock+'</span>' : '<span class="badge bg-primary">'+ct.stock+'</span>'
            },className:"text-center"},
             {"data":null,render:function(ct){
                return parseInt(ct.stock) >= parseInt(ct.stock_minimo) ? '<span class="badge bg-primary">Stock suficiente</span>' : (parseInt(ct.stock) > 0 && parseInt(ct.stock)<= parseInt(ct.stock_minimo) ? '<span class="badge bg-warning">Stock por agotarse</span>':'<span class="badge bg-danger">Stock agotado</span>');
            },className:"text-center"},
            {"data":"precio_venta",render:function(precio){
                return `<b>`+precio+`</b>`;
            },className:"text-center"},
            {"data":"name_tipo_producto",render:function(nametipo){
                return nametipo.toUpperCase();
            }},
            {"data":"name_presentacion",render:function(namepresentacion){
                return namepresentacion.toUpperCase();
            }},
            {"data":"name_laboratorio",render:function(namelab){
                return namelab.toUpperCase();
            }},
            {"data":"name_embalaje",render:function(namemb){
                return namemb.toUpperCase();
            }},
            {"data":"proveedor_name",render:function(proveedor){
                return proveedor.toUpperCase();
            }}
        ]
    });
}

/** AGREGAR LOTES A LOS PRODUCTOS */
function AgregarLote(Tabla,Tbody){
  $(Tbody).on('click','#lote',function(){
    
    let fila = $(this).parents("tr");
    if(fila.hasClass("child")){
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();
    PRODUCTOID = Data.id_producto;
    STOCKPRODUCTO = Data.stock;
    TotalCantidadProductoPorLotes(STOCKPRODUCTO);
    $('#productotexto').val(Data.nombre_producto.toUpperCase());
    $('#cantidad_total').val(Data.stock);
    $('#modal_add_lote').modal("show")
  });
}

function ShowLotesProducto(Tabla,Tbody){
  $(Tbody).on('click','#ver_lote',function(){
    
    let fila = $(this).parents("tr");
    if(fila.hasClass("child")){
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();
    PRODUCTOID = Data.id_producto;
    STOCKPRODUCTO = Data.stock;
    $('#prod_lotes').text(Data.nombre_producto.toUpperCase());
    MostrarLotesProducto();
    ConfirmarEliminadoLote(TablaLotesProducto,'#tabla_lotes_producto tbody');
         TablaLotesProducto.ajax.reload();
    $('#modal_show_lotes_producto').modal("show")
  });
}

/// GAURDAR LOTE DE UN PRODUCTO
function saveLoteProducto(){
    let FormLote = new FormData();
    FormLote.append("token_",TOKEN);
    FormLote.append("codigolote",$('#codigolote').val());
    FormLote.append("fechaprod",$('#fechaprodlote').val());
    FormLote.append("fechaven",$('#fechavenlote').val());
    FormLote.append("existencia",$('#existencialote').val());
    FormLote.append("producto",PRODUCTOID);
    FormLote.append("sede",$('#sede').val());
    axios({
        url:RUTA+"producto/agregar/lote",
        method:"POST",
        data:FormLote
    }).then(function(response){
        if(response.data.errors != undefined){
            if(response.data.errors.codigolote != undefined){
                $('#errorcodigo').text(response.data.errors.codigolote);
            }else{
                $('#errorcodigo').text("");
            }

            if(response.data.errors.existencia != undefined){
                $('#errorcantidad').text(response.data.errors.existencia);
            }else{
                $('#errorcantidad').text("");
            }
            return;
        }
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById('modal_add_lote')
            })
        }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.success,
                icon:"success",
                target:document.getElementById('modal_add_lote')
            }).then(function(){
               $('#formlote')[0].reset();
               TotalCantidadProductoPorLotes(STOCKPRODUCTO);
               $('#errorcodigo').text("");
               $('#errorcantidad').text("");
            });
        }
    })
}

/**OBTENER LA CANTIDAD TOTAL DE UN PRODUCTO DE SUS LOTES */
function TotalCantidadProductoPorLotes(stockproducto){
 
    axios({
        url:RUTA+"total-en-lote/producto/"+PRODUCTOID+"?sede="+$('#sede').val(),
        method:"GET",
    }).then(function(response){
        let TotaRestante = stockproducto - response.data.cantidad;
        $('#cantidad_total_restante').val(TotaRestante);
    })
}

/** VER LOS LOTES DE UN PRODUCTO */
function MostrarLotesProducto(){
    TablaLotesProducto = $('#tabla_lotes_producto').DataTable({
        retrieve:true,
        language:SpanishDataTable(),
        "order": [[ 2, "asc" ]],
        ajax:{
            url:RUTA+"producto/lotes",
            method:"GET",
            dataSrc:"lotes",
            data:function(dato){
                dato.producto=PRODUCTOID,
                dato.sede = $('#sede').val()
            }
        },
        columns:[
            {"data":"codigo_lote",render:function(lotecode){
                return `<b class="badge bg-primary">`+lotecode+`</b>`;
            }},
            {"data":"fecha_produccion"},
            {"data":"fecha_vencimiento"},
            {"data":"fecha_vencimiento",render:function(fechavencimientodata){
              /// primero vamos a obtener la fecha actual
              let fech = new Date(); 

              let Fecha = fech.getFullYear()+"-"+addCeroMonthDate(parseInt(fech.getMonth()+1))+"-"+fech.getDate();
 

              let actualFecha = new Date(Fecha);
              let FechaDataVencimiento = new Date(fechavencimientodata);

              if( FechaDataVencimiento <= actualFecha){
                return `<span class='badge bg-danger'><b>`+"Si 😢😔"+`</b></span>`;
              }
              return `<span class='badge bg-success'><b>`+"No 😀😁"+`</b></span>`;

            },className:"text-center"},
            {"data":"fecha_vencimiento",render:function(fechavencimientodata){
                /// primero vamos a obtener la fecha actual
                let fech = new Date(); 
  
                /// fecha actual
                let Fecha = fech.getFullYear()+"-"+addCeroMonthDate(parseInt(fech.getMonth()+1))+"-"+fech.getDate();
   
  
                let actualFecha = new Date(Fecha).getTime();
                let FechaDataVencimiento = new Date(fechavencimientodata).getTime();
  
                let diff =   FechaDataVencimiento -actualFecha;
                if(diff<= 0)
                {
                    return `<span class='badge bg-danger'>0 días</span>`;
                }
            

                diff = (diff/(1000*60*60*24)).toFixed(0);
                 
 
                return  `<span class='badge bg-success'><b class='text-white'>`+diff+` días</b></span>`;
  
              },className:"text-center"},
            {"data":"cantidadlote",className:"text-center"},
            {"data":null,render:function(){
                return `<button class='btn btn-outline-danger btn-sm' id="eliminar"><i class='bx bx-x'></i></button>
                <button class='btn btn-outline-danger btn-sm'><i class='bx bxs-edit-alt' ></i></button>`;
            },className:"text-center"}
        ],
        
    })
}

/** CONFORMAR ANTES DE ELIMINAR EL LOTE */
function ConfirmarEliminadoLote(Tabla, Tbody) {
  $(Tbody).on("click", "#eliminar", function () {
    let fila = $(this).parents("tr");
    if (fila.hasClass("child")) {
      fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();

    Swal.fire({
      title: "ESTAS SEGURO?",
      text: "AL ACEPTAR, EL LOTE SELECCIONADO SE ELIMINARÁ DE LA LISTA DE LOTES DEL PRODUCTO!!!",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "SI, eliminar!",
      target: document.getElementById("modal_show_lotes_producto"),
    }).then((result) => {
      if (result.isConfirmed) {
        EliminarLote(Data.codigo_lote,Data.producto_id);
      }
    });
  });
}

/// proceso de eliminar lote
function EliminarLote(codigo,producto){
 let FormDeleteLote = new FormData();
 FormDeleteLote.append("token_",TOKEN);
 FormDeleteLote.append("sede",$('#sede').val());
 axios({
    url:RUTA+"lote/"+codigo+"/"+producto+"/delete",
    method:"POST",
    data:FormDeleteLote
 }).then(function(response){
    if(response.data.error != undefined){
        $('#text_alerta').hide();
        $('#texto_alerta').text("");
        $('#texto_alerta_error').show(100);
        $('#texto_alerta_error').text(response.data.error);
    }else{
         $('#texto_alerta_error').hide();
         $('#texto_alerta_error').text("");
         $('#texto_alerta').show(100);
         $('#texto_alerta').text(response.data.success);
         MostrarLotesProducto();
         TablaLotesProducto.ajax.reload();
    }
 })
}