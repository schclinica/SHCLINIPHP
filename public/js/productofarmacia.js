/** Mostrar productos existentes */
 
function showProducto()
{
    TablaListaProductos = $('#lista_productos').DataTable({
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
            url:RUTA+"app/farmacia/productos/all",
            method:"GET",
            dataSrc:"response",
        },
        columns:[
            {"data":"nombre_producto",},
            {"data":"deleted_at_prod",render:function(deleted_at){
                if(deleted_at == null)
                {
                 return `<button class='btn btn-danger rounded btn-sm' id='eliminar_productos'><i class='bx bx-x'></i></button>
                 <button class='btn btn-warning rounded btn-sm' id='editar_productos'><i class='bx bx-pencil' ></i></button>
                 <button class='btn btn-info rounded btn-sm' id='delete_productos'><i class='bx bx-trash'></i></button>
                 <button class='btn btn-primary rounded btn-sm' id='asignar_almacen'><i class='bx bxs-business'></i></button>
                 <button class="btn btn-outline-success btn-sm" id='add_price'><i class='bx bx-dollar'></i></button>
                 `;
                }
                return `<button class='btn btn-success rounded btn-sm' id='activarse_productos'><i class='bx bx-check'></i></button>
                <button class='btn btn-info rounded btn-sm' id='delete_productos'><i class='bx bx-trash'></i></button>`;
             }},
             {"data":"code_barra",render:function(codebarra){
                return `<b class='badge bg-primary'>`+codebarra+`</b>`;
             },className:"text-center"},
            {"data":null,render:function(nombreprod){
                if(parseInt(nombreprod.stock) <= parseInt(nombreprod.stock_minimo))
                {
                    return `<span class='text-danger'><b>`+nombreprod.nombre_producto.toUpperCase()+(nombreprod.lote==='si' ? '  <b class="badge bg-success">[LOTE!]</b>':'')+`</b></span>`;
                }
                return nombreprod.nombre_producto.toUpperCase()+(nombreprod.lote==='si' ? '  <b class="badge bg-success">[LOTE!]</b>':'');
            }},
            {"data":null,render:function(nombreprod){
                if(parseInt(nombreprod.stock) > parseInt(nombreprod.stock_minimo))
                {
                    return `<span class='text-primary'><b>`+"Tiene stock suficiente"+`</b></span>`;
                }
                else{
                    if(parseInt(nombreprod.stock) > 0 && ( parseInt(nombreprod.stock) <= parseInt(nombreprod.stock_minimo)))
                    {
                        return `<span class='text-warning'><b>`+"Stock insuficiente"+`</b></span>`;   
                    }
                    return `<span class='text-danger'><b>`+"No tiene stock"+`</b></span>`;
                }
            },className:"text-center"},
            // {"data":"fecha_vencimiento",render:function(fechavencimientodata){
            //   /// primero vamos a obtener la fecha actual
            //   let fech = new Date(); 

            //   let Fecha = fech.getFullYear()+"-"+addCeroMonthDate(parseInt(fech.getMonth()+1))+"-"+fech.getDate();
 

            //   let actualFecha = new Date(Fecha);
            //   let FechaDataVencimiento = new Date(fechavencimientodata);

            //   if( FechaDataVencimiento <= actualFecha){
            //     return `<span class='badge bg-danger'><b>`+"Si 😢😔"+`</b></span>`;
            //   }
            //   return `<span class='badge bg-success'><b>`+"No 😀😁"+`</b></span>`;

            // },className:"text-center"},
            // {"data":"fecha_vencimiento",render:function(fechavencimientodata){
            //     /// primero vamos a obtener la fecha actual
            //     let fech = new Date(); 
  
            //     /// fecha actual
            //     let Fecha = fech.getFullYear()+"-"+addCeroMonthDate(parseInt(fech.getMonth()+1))+"-"+fech.getDate();
   
  
            //     let actualFecha = new Date(Fecha).getTime();
            //     let FechaDataVencimiento = new Date(fechavencimientodata).getTime();
  
            //     let diff =   FechaDataVencimiento -actualFecha;
            //     if(diff<= 0)
            //     {
            //         return `<span class='badge bg-danger'>0 días</span>`;
            //     }
            

            //     diff = (diff/(1000*60*60*24)).toFixed(0);
                 
 
            //     return  `<span class='badge bg-success'><b class='text-white'>`+diff+` días</b></span>`;
  
            //   },className:"text-center"},
            {"data":"precio_venta",render:function(precio){
                return `<span class='badge bg-info'><b>`+MONEDA+" "+precio+`</b></span>`;
            }},
            {"data":null,render:function(stockdata){
                if(stockdata.stock <= stockdata.stock_minimo)
                {
                    return `<span class='badge bg-danger'><b>`+stockdata.stock+`</b></span>`;
                }
                return `<span class='badge bg-success'><b>`+stockdata.stock+`</b></span>`;
            }},
            {"data":"stock_minimo",render:function(stock){
                return `<span class='badge bg-primary'><b>`+stock+`</b></span>`;
            }},
            // {"data":"fecha_vencimiento",render:function(fechavencimiento){
            //     let NuevaFecha = fechavencimiento.split("-");
            //     let Anio = NuevaFecha[0];let Mes = NuevaFecha[1]; let Dia = NuevaFecha[2];

            //     return '<b>'+Dia+"/"+Mes+"/"+Anio+'</b>';
            // }},
            {"data":"name_tipo_producto",render:function(tipo){
                return tipo.toUpperCase();
            }},
            {"data":"name_presentacion",render:function(presentacion){
                return presentacion.toUpperCase();
            }},
            {"data":"name_laboratorio",render:function(lab){
                return lab.toUpperCase();
            }},
            {"data":"name_grupo_terapeutico",render:function(namegrupo){
                return namegrupo.toUpperCase();
            }},
            {"data":"name_embalaje",render:function(nameemb){
                return nameemb.toUpperCase();
            }},
            {"data":"proveedor_name",render:function(provedorname){
                return provedorname.toUpperCase();
            }}
        ]
    }).ajax.reload();
    TablaListaProductos.on('order.dt search.dt', function() {
        TablaListaProductos.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
}

function mostrarTipoProductoCombo()
{
    let option = ``;

    let tiposProductos = show(RUTA+"app/farmacia/mostrar_tipo_productos/no");
 
    tiposProductos.forEach(element => {
        option+=`<option value=`+element.id_tipo_producto+`>`+element.name_tipo_producto.toUpperCase()+`</option>`;
    });

    $('#tipo_select').html(option);
}

function mostrarPresentacionCombo()
{
    let option = ``;

    let tiposProductos = show(RUTA+"app/farmacia/presentaciones/all/no");
 
    tiposProductos.forEach(element => {
        option+=`<option value=`+element.id_pesentacion+`>`+element.name_presentacion.toUpperCase()+`</option>`;
    });

    $('#presentacion_select').html(option);
}
function mostrarLaboratoriosCombo()
{
    let option = ``;

    let tiposProductos = show(RUTA+"app/farmacia/laboratorio/all/no");
 
    tiposProductos.forEach(element => {
        option+=`<option value=`+element.id_laboratorio+`>`+element.name_laboratorio.toUpperCase()+`</option>`;
    });

    $('#laboratorio_select').html(option);
}

function mostrarGruposTerapeuticosCombo()
{
    let option = ``;

    let tiposProductos = show(RUTA+"app/farmacia/grupo_terapeutico/all/no");
 
    tiposProductos.forEach(element => {
        option+=`<option value=`+element.id_grupo_terapeutico+`>`+element.name_grupo_terapeutico.toUpperCase()+`</option>`;
    });

    $('#grupo_select').html(option);
}

function mostrarEmpaquesCombo()
{
    let option = ``;

    let tiposProductos = show(RUTA+"app/farmacia/empaques/all/no");
 
    tiposProductos.forEach(element => {
        option+=`<option value=`+element.id_embalaje+`>`+element.name_embalaje.toUpperCase()+`</option>`;
    });

    $('#embalaje_select').html(option);
}

function mostrarProveedoresCombo()
{
    let option = ``;

    let tiposProductos = show(RUTA+"app/farmacia/proveedores/all/no");
 
    tiposProductos.forEach(element => {
        option+=`<option value=`+element.id_proveedor+`>`+element.proveedor_name.toUpperCase()+`</option>`;
    });

    $('#proveedor_select').html(option);
}

function mostrarProveedoresComboReporte()
{
    let option = ``;

    let tiposProductos = show(RUTA+"app/farmacia/proveedores/all/no");
 
    tiposProductos.forEach(element => {
        option+=`<option value=`+element.id_proveedor+`>`+element.proveedor_name.toUpperCase()+`</option>`;
    });

    $('#proveedor_reporte').html(option);
}

/** Registrar productos */
function registrarProducto()
{
  let Respuesta = crud(RUTA+"app/farmacia/producto/store",'form_productos');

  if(Respuesta == 1)
  {
    Swal.fire({
        title:"Mensaje del sistema!",
        text:"Producto registrado correctamente!",
        icon:"success"
    }).then(function(){
        showProducto();

        $('#name_producto').focus();
        $('#form_productos')[0].reset();
    });
  }else{
    if(Respuesta === 'existe')
    {
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Ya existe el prodcuto que deseas registrar!",
            icon:"warning"
        }).then(function(){
            $('#name_producto').focus();
            $('#name_producto').val("");
        });   
    }else{
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Error al registrar producto!",
            icon:"error"
        });
    }
  }
}
/// asignar las sedes a los productos
/// asignar almacen al producto
function AsignarSedes(Tabla, Tbody) {
  $(Tbody).on("click", "#asignar_almacen", function (evento) {
    evento.preventDefault();
    let fila = $(this).parents("tr");

    if(fila.hasClass("child")){
        fila = fila.prev();
    }
     let Data = Tabla.row(fila).data();
     $("#producto_seleccionado_almacen").val(
       Data.nombre_producto.toUpperCase()
     );
     $('#producto_stock_seleccionado_almacen').val(Data.stock);
     IDPRODUCTO = Data.id_producto;
     showAlmacenes();
     AsignarAlmacenesAlProducto();

     loading("#farmacia_card", "#4169E1", "chasingDots");
     setTimeout(() => {
       $("#farmacia_card").loadingModal("hide");
       $("#farmacia_card").loadingModal("destroy");
       $("#modal_open_asignar_almacenes").modal("show");
     }, 300);
  
  });
}
// activar producto nuevamente
function ActivarProductos(Tabla,Tbody)
{
  $(Tbody).on('click','#activarse_productos',function(evento){
    evento.preventDefault();
 
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();
    IDPRODUCTO = Data.id_producto;
    /** Confirmamos si deseamos eliminar la presentación */
     HabilitarInhabilitarProductos(IDPRODUCTO,'h');
  });
}

/** Registrar productos */
function ModificarProducto(id)
{
  let Respuesta = crud(RUTA+"app/farmacia/udpate/"+id,'form_productos');

  if(Respuesta == 1)
  {
    Swal.fire({
        title:"Mensaje del sistema!",
        text:"Producto modificado correctamente!",
        icon:"success"
    }).then(function(){
        showProducto();
        ControlBotonProducto = 'save';
        $('#name_producto').focus();
        $('#form_productos')[0].reset();
    });
  }else{
    Swal.fire({
        title:"Mensaje del sistema!",
        text:"Error al modificar producto!",
        icon:"error"
    });
  }
}

/** Editar productos */
function EditarProductos(Tabla,Tbody)
{
    $(Tbody).on('click','#editar_productos',function(evento){
        evento.preventDefault();
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();
        IDPRODUCTO = Data.id_producto;
        ControlBotonProducto = 'update';
        $('#nombre_producto').focus();
        $('#nombre_producto').val(Data.nombre_producto);
        $('#precio_venta').val(Data.precio_venta);
        $('#code_barra').val(Data.code_barra);
        $('#stock_minimo').val(Data.stock_minimo);
       // $('#fecha_vencimiento').val(Data.fecha_vencimiento);
        $('#tipo_select').val(Data.id_tipo_producto);
        $('#presentacion_select').val(Data.id_pesentacion);
        $('#laboratorio_select').val(Data.id_laboratorio);
        $('#grupo_select').val(Data.id_grupo_terapeutico);
        $('#embalaje_select').val(Data.id_embalaje);
        $('#proveedor_select').val(Data.id_proveedor);
        if(Data.lote === 'si'){
            $('#lote').prop("checked",true);
        }else{
            $('#lote').prop("checked",false);
        }

        $('#code_barra').select();
    });
}

/** Editar productos */
function ConfirmarBorradoProductos(Tabla,Tbody)
{
    $(Tbody).on('click','#delete_productos',function(evento){
        evento.preventDefault();
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();
        IDPRODUCTO = Data.id_producto;
         /** Confirmamos si deseamos eliminar la presentación */
    Swal.fire({
        title: "Estas seguro de borrar al producto "+Data.nombre_producto+"?",
        text: "Al eliminarlo, se quitará del listado de productos para la venta y a la vez ya no podrá recuperarlos!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
      }).then((result) => {
        if (result.isConfirmed) {
          BorrarProducto(IDPRODUCTO);
        }
      });
    });
}

/** Confirmar eliminado de la lista a la productos */
function EliminadoConfirmProductos(Tabla,Tbody)
{
  $(Tbody).on('click','#eliminar_productos',function(evento){
    evento.preventDefault(); 
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();
    IDPRODUCTO = Data.id_producto;
    /** Confirmamos si deseamos eliminar la presentación */
    Swal.fire({
        title: "Estas seguro de eliminar al producto "+Data.nombre_producto+"?",
        text: "Al eliminarlo, se quitará de la lista para ser vendido a los pacientes!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
      }).then((result) => {
        if (result.isConfirmed) {
          HabilitarInhabilitarProductos(IDPRODUCTO,'i');
        }
      });
  });
}

/// proceso para eliminar e habilitar a la productos
function HabilitarInhabilitarProductos(id,condition)
{
    $.ajax({
        url:RUTA+"app/farmacia/habilitar/deshabilitar/productos/"+id+"/"+condition,
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
                text:condition === 'i'?"Producto quitado de la lista correctamente":"Producto activado nuevamente",
                icon:"success"
            }).then(function(){
               showProducto();
            });
           }else{
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al realizar el proceso de eliminado o activación del producto",
                icon:"error"
            });
           }
        }
    });
}


/**Borrar por completo al producto seleccionado */
function BorrarProducto(id)
{
    $.ajax({
        url:RUTA+"app/farmacia/delete/"+id,
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
                    text:"Producto borrado sin problemas!",
                    icon:"success"
                }).then(function(){
                    showProducto();
                    ControlBotonProducto = 'save';
                });
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error al borrar producto!",
                    icon:"error"
                });
            }
        },error:function(err)
        {
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al eliminar al producto seleccionado!",
                icon:"error"
            }) ;  
        }
    });
}

/// importar productos desde excel
function importDataProductosToExcel()
{
    let formImportProductos = new FormData(document.getElementById('form_import_productos'));
    $.ajax({
        url:RUTA+"productos/import/excel",
        method:"POST",
        data:formImportProductos,
        processData: false,  // <-- le indicamos a jQuery que no procese el `data`
        contentType: false,
        dataType:"json",
        success:function(response){
            if(response.response == 1 || response.response === 'existe')
            {
                 Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Productos importados correctamente 😁😎😀🎉!",
                    icon:"success"
                 }).then(function(){
                    showProducto();
                    $('#form_import_productos')[0].reset();
                 });
            }else{
               if(response.response === 'error_tipo')
               {
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error en el archivo seleccionado, debe de seleccionar un archivo excel office!",
                    icon:"error"
                }).then(function(){
                    $('#form_import_productos')[0].reset();
                });
               }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error al importar los productos 😢😔!",
                    icon:"error"
                 });
               }
            }
        },error:function(err){
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al importar los productos 😢😔!",
                icon:"error"
             });
        }
    });
}

/** MOSTRAR LOS ALMACENES */
function showAlmacenes(){
    let tr = '';
    axios({
        url:RUTA+"almacenes/disponibles/no-asignados/producto/"+IDPRODUCTO,
        method:"GET",
    }).then(function(response){
        if(response.data.sedes.length > 0){
            response.data.sedes.forEach(sede => {
                tr+=`<tr>
                   <td>
                     <div class="form-check form-switch">
                     <input class="form-check-input" type="checkbox" id="almacen`+sede.id_sede+`" value=`+sede.id_sede+` checked>
                     <label class="form-check-label" for="almacen`+sede.id_sede+`">`+sede.namesede.toUpperCase()+`</label>
                     </div>
                   </td>
                   <td><input type="number" class="form-control" id="stock" placeholder="0"  value="0" onkeydown="return event.keyCode !== 189 && event.keyCode !== 109" onkeypress="return event.charCode >= 48" min="0"></td>
                </tr>`;
            });
        }else{
            tr=`<td class="px-2 py-3" colspan="4"><span class="text-danger">No hay Almacenes para mostrar....</span></td>`;
        }
        $('#tabla_lista_almacenes').html(tr);

    });
}
 