/**
 * Mostrar un reporte de producto para saber las ganancias a corde al precio
 * de compra y precio de venta
 */
function showRepoProductos(fi,ff)
{
    TablaRepoProductos = $('#reporte_productos_ganancias').DataTable({
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
            
            // converting to interger to find total
            var intVal = function ( i ) {
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1 :
            typeof i === 'number' ?
            i : 0;
            };
            
            // computing column Total the complete result
            var monTotalPriceCompra = api
            .column( 2 )
            .data()
            .reduce( function (a, b) {
            return intVal(a) + intVal(b);
            }, 0 );
            var monTotalPriceVenta = api
            .column( 3 )
            .data()
            .reduce( function (a, b) {
            return intVal(a) + intVal(b);
            }, 0 );
            var Ganancia= api
            .column( 4 )
            .data()
            .reduce( function (a, b) {
            return intVal(a) + intVal(b);
            }, 0 );
            
            
            // Update footer by showing the total with the reference of the column index
            $( api.column( 0 ).footer() ).html('Total');
            $( api.column( 2 ).footer() ).html("<b>"+monTotalPriceCompra.toFixed(2)+"</b>");
            $( api.column( 3 ).footer() ).html("<b>"+monTotalPriceVenta.toFixed(2)+"</b>");
            $( api.column( 4 ).footer() ).html("<b>"+Ganancia.toFixed(2)+"</b>");
            
            },  
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
        url:RUTA+"app/farmacia/reporte/productos_para_ganancias?fi="+fi+"&&ff="+ff,
        method:"GET",
        dataSrc:"response"
    },
    columns:[
        {"data":"nombre_producto"},
        {"data":"prods"},
        {"data":"precio_de_compra",render:function(preciocompra){
            return preciocompra == null ? "0.00" :preciocompra;
        }},
        {"data":"precio_venta"},
        {"data":"ganancia"},
    ]
    });
  
    /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
  TablaRepoProductos.on('order.dt search.dt', function() {
    TablaRepoProductos.column(0, {
        search: 'applied',
        order: 'applied'
    }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
    });
}).draw();
}