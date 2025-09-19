 enter('medicamento_receta','indicaciones_receta');
 enter('indicaciones_receta','cantidad_receta');



 /**AGREGAR A LA LISTA DE LA RECETA */
 $('#cantidad_receta').keypress(function(evento){
    if(evento.which == 13){
        evento.preventDefault();
        if($(this).val().trim().length == 0){
            $(this).focus();
        }else{
            
            AgregarMedicamentoADetalleReceta(ProductoFarmaciaId !== undefined ? ProductoFarmaciaId : null);
        }
    }
 });
 
 /** AGREGAR MEDICAMENTO A LA LISTA */
 function AgregarMedicamentoADetalleReceta(id){

    let FormListaDetalleReceta = new FormData();
    FormListaDetalleReceta.append("token_",TOKEN);
    FormListaDetalleReceta.append("medicamento_desc",$('#medicamento_receta').val());
    FormListaDetalleReceta.append("indicacion_desc",$('#indicaciones_receta').val());
    FormListaDetalleReceta.append("cantidad",$('#cantidad_receta').val());
    axios({url:RUTA+"receta/guardar/"+id,method:"POST",data:FormListaDetalleReceta
        }).then(function(response){

            if(response.data.error != undefined){
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:response.data.error,
                    icon:"error",
                    target:document.getElementById('modal_receta_medica')
                }).then(function(){
                    $('#medicamento_receta').val("");
                    $('#indicaciones_receta').val("");
                    $('#cantidad_receta').val("");
                    ProductoFarmaciaId=null;
                    $('#medicamento_receta').focus();
                });
            }else{
                 $('#medicamento_receta').focus();
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:response.data.response,
                    icon:"success",
                    target:document.getElementById('modal_receta_medica')
                }).then(function(){
                    $('#medicamento_receta').val("");
                    $('#indicaciones_receta').val("");
                    $('#cantidad_receta').val("");
                    ProductoFarmaciaId=null;
                    MostrarDetalleReceta();
                });
            }
        });
 }


 /*GENERA LA RECETA MEDICA DEL PACIENTE*/
 function GenerateRecetaMedicaPaciente(Tabla,Tbody){
    $(Tbody).on('click','#realizar_receta',function(){
        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        ATENCIONID_ = fila.find('td').eq(6).text();
        MEDICOIDDATA_ = fila.find('td').eq(8).text();
        PACIENTEIDDATA_ = fila.find('td').eq(7).text();
         
        $('#modal_receta_medica').modal("show");
        MostrarDetalleReceta();
       GenerarSerieCorrelativoRecetaElectronico()
    });
}


/// GUARDAR LA RECETA ELECTRONICA
function saveRecetaElecronica(){
   
    let FormSaveReceta = new FormData();
    FormSaveReceta.append("token_",TOKEN);
    FormSaveReceta.append("seriereceta",$('#seriereceta').val());
    FormSaveReceta.append("fecha_vencimiento",$('#fecha_vencimiento').val());
    FormSaveReceta.append("paciente_id",PACIENTEIDDATA_);
    FormSaveReceta.append("atencionid",ATENCIONID_);
    axios({
        url:RUTA+"receta/save",
        method:"POST",
        data:FormSaveReceta
    }).then(function(response){
        if(response.data.error!= undefined){
            Swal.fire(
                {
                    title:"MENSAJE DEL SISTEMA!!",
                    text:response.data.error,
                    icon:"error",
                    target:document.getElementById('modal_receta_medica')
                }
            )
        }else{
         $('#modal_receta_medica').modal("hide");
            Swal.fire(
                {
                    title:"MENSAJE DEL SISTEMA!!",
                    text:response.data.response,
                    icon:"success",
                    //target:document.getElementById('modal_receta_medica')
                }
            ).then(function(){
               GenerarSerieCorrelativoRecetaElectronico();
                $('#indicaciones_receta').val("");
                $('#medicamento_receta').val("");
                $('#cantidad_receta').val("");
                ProductoFarmaciaId = null;
                pacientesAtendidos(TIPOREPO, "2023-08-20");

            });
        }
    });
}
 

/// MOSTRAR DETALLE DE LA RECETA
function MostrarDetalleReceta(){

    let tr = '';
    $.ajax({
        url:RUTA+"receta/detalle/all",
        method:"GET",
        dataType:"json",
        success:function(response){
 
            response = Object.values(response.detalle_receta);
            if(response.length > 0){
                response.forEach(detalle => {
                    tr+=`<tr>
                    <td class="d-none">`+detalle.producto_id+`</td>
                    <td class="text-center">`+detalle.cantidad+`</td>
                    <td>`+detalle.medicamento.toUpperCase()+`</td>
                    <td>`+detalle.indicaciones.toUpperCase()+`</td>
                    <td><button class="btn btn-danger btn-sm" onclick="QuitarMedicamentoLista('`+detalle.medicamento+`','`+detalle.medicamento+`')"><b>X</b></button></td>
                    </tr>`;
                });
            }else{
                tr=`<td colspan="4"><span class="text-danger">No hay medicamentos agregados en la lista.....</span></td>`;
            }

            $('#lista_receta_medica_paciente').html(tr);
        }
    });
}


/// BUSCAR LOS MEDICAMENTOS(ABRIR MODAL)
$('#buscar_producto_receta').click(function(){
 $('#modal_search_producto_receta').modal("show");
 mostrarProductos();

 SelectMedicamento(TablaConsultarProductos,'#tabla_productos_search tbody');
});


/** MOSTRAR EN UN MODAL LOS MEDICAMENTOS EXISTENTES */
/// consultar productos
function mostrarProductos()
{
    TablaConsultarProductos = $('#tabla_productos_search').DataTable({
        language:SpanishDataTable(),
        retrieve:true,
        "columnDefs": [{
        "searchable": false,
        "orderable": false,
        "targets": 0
        }],
        ajax:{
            url:RUTA+"consultar/productos/clinica",
            method:"GET",
            dataSrc:"productos",
        },
        columns:[
            {"data":"nombre_producto"},
            {"data":"name_tipo_producto",render:function(datap){
                return `<span class='badge bg-danger'>`+datap.toUpperCase()+`</span>`;
            }},
            {"data":null,render:function(prod){
                return prod.nombre_producto.toUpperCase()
            }},
            {"data":null,render:function(){
                return `<button class='btn btn-primary btn-rounded btn-sm' id='seleccionar_producto'><i class='fas fa-cart-shopping'></i></button>`;
            }}
        ]
    }).ajax.reload();
    /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
   TablaConsultarProductos.on( 'order.dt search.dt', function () {
    TablaConsultarProductos.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();
}

/** SELECCIONAR PRODUCTO */
 
 function SelectMedicamento(Tabla,Tbody){
    $(Tbody).on('click','#seleccionar_producto',function(){
        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        ProductoFarmaciaId = Data.id_producto;
        let medicamento = Data.nombre_producto;
        $('#modal_search_producto_receta').modal("hide");
        $('#medicamento_receta').val(medicamento);
        $('#indicaciones_receta').focus();
    });
}

/**CONFIRMAR ANTES DE QUITAR MEDICAMENTO DE LA LISTA */
function QuitarMedicamentoLista(id,medicamentoText){
    Swal.fire({
        title: "ESTAS SEGURO DE QUITAR AL MEDICAMENTO "+medicamentoText+" DE LA LISTA!!?",
        text: "AL ACEPTAR, SE QUITARÁ DE LA LISTA DE LA RECETA!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        target:document.getElementById('modal_receta_medica')
      }).then((result) => {
        if (result.isConfirmed) {
          ProcesoQuitarMedicamento(id);
        }
      });
}

/**QUITAR DE LA LISTA AL MEDICAMENTO */
function ProcesoQuitarMedicamento(id){

  let FormQuitarMedicamentoLista = new FormData();
  FormQuitarMedicamentoLista.append("token_",TOKEN);
  axios({
    url:RUTA+"quitar-medicamento/"+id+"/receta",
    method:"POST",
    data:FormQuitarMedicamentoLista
  }).then(function(response){
    if(response.data.error != undefined){
        Swal.fire({
            title:"MENSAJE DEL SISTEMA!!!",
            text:response.data.error,
            icon:"error",
            target:document.getElementById('modal_receta_medica')
        });
    }else{
        Swal.fire({
            title:"MENSAJE DEL SISTEMA!!!",
            text:response.data.response,
            icon:"success",
            target:document.getElementById('modal_receta_medica')
        }).then(function(){
            MostrarDetalleReceta();
            ProductoFarmaciaId = null;

        })
    }
  });
}


/*GENERAR EL NUMERO DE SERIE PARA LA RECETA ELECTRONICO*/
  function GenerarSerieCorrelativoRecetaElectronico(){
    axios({
        url:RUTA+"receta-electronico/generate/serie",
        method:"GET",
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.error,
                icon:"error"
            });
        }else{
            $('#seriereceta').val(response.data.serie)
        }
    });
  }