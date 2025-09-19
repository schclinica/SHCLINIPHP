///mostrar la historia de las ventas
function mostrarHistoriaVentas(fechadataventa)
{
    lista_historial_ventas = $('#lista_historial_ventas').DataTable({
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
            url:RUTA+"app/farmacia/historia_ventas/all?fecha_venta="+fechadataventa,
            method:"GET",
            dataSrc:"response"
        },
        columns:[
            {"data":"num_venta"},
            {"data":null,render:function(){
                return `<button class='btn btn-primary rounded btn-sm' id='print_venta'> <i class='bx bx-printer'></i></button>
                <button class='btn btn-warning rounded btn-sm' id='editar_venta'> <i class='fas fa-pencil'></i></button>`;
            }},
            {"data":"num_venta"},
            {"data":"fecha_emitido"},
            {"data":"clientedata",render:function(clientedata){
                return clientedata.toUpperCase();
            }},
            {"data":"name",render:function(vendedor){
                return vendedor.toUpperCase();
            }},
            {"data":"total_venta",render:function(dtaventatotalventa){
                return dtaventatotalventa; 
            }},
            {"data":"monto_recibido",render:function(dtaventamontorecibido){
                return dtaventamontorecibido 
            }},
            {"data":"vuelto",render:function(dtaventavuelto){
                return dtaventavuelto 
            }},
 
        ]
    });

    lista_historial_ventas.on('order.dt search.dt', function() {
        lista_historial_ventas.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
}

/// mandar imprimir el ticket de venta
function printTicketDeVenta()
{
    $('#lista_historial_ventas').on('click','#print_venta',function(){
        let fila = $(this).parents("tr");

        if(fila.hasClass("child"))
        {
            fila = fila.prev();
        }

        let NumeroDeLaVenta = fila.find("td").eq(2).text();

        window.open(RUTA+"app/farmacia/tiecket_de_venta?v="+NumeroDeLaVenta,"_target");
    });
}