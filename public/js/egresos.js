/**
 * Mostrar los egresos existentes
 */
function MostrarLosEgresos()
{
    TablaEgresos = $('#tabla_egresos').DataTable({
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
            url:RUTA+"egreso/categorias/all",
            method:"GET",
            dataSrc:"response"
        },
        columns:[
            {"data":"name_categoria_egreso"},
            {"data":"id_categoria_egreso",className:"d-none"},
            {"data":"name_categoria_egreso",render:function(cat){
                return cat.toUpperCase();
            }}, 
            {"data":null,render:function(){
                return `<button class='btn btn-danger rounded btn-sm' id='eliminar_categoria'><i class='bx bx-x'></i></button>
                <button class='btn btn-warning rounded btn-sm' id='editarcategoria'><i class='bx bx-pencil'></i></button>`
            },className:"text-center"}, 
        ] 
    });

    TablaEgresos.on('order.dt search.dt', function() {
        TablaEgresos.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
}

/** 
 * Agregar a la tabla detalle las subcategorias de la categoria de un egreso
 */
function addDetalleSubCategoria(name_subcat,gasto_subcat,body)
{
    let tr =''; 

    tr+=`<tr>
    <td>`+name_subcat.val()+`</td>
    <td>`+parseFloat(gasto_subcat.val()).toFixed(2)+`</td>
    <td><button class='btn btn-danger rounded btn-sm' id='quitar_egreso'><i class='bx bx-trash' ></i></button></td>
    </tr>`;

    $('#'+body).append(tr);
}
/**
 * Quitar las subcategorias
 */

function quitarSubCategoria()
{
    $('#detalle_egresos_body').on('click','#quitar_egreso',function(){
        let fila = $(this).parents('tr');

        fila.remove();
    });
}

function quitarSubCategoriaIndex()
{
    $('#detalle_egresos_body_index').on('click','#quitar_egreso',function(){
        let fila = $(this).parents('tr');

        fila.remove();
    });
}
 


/// eliminar categoria
function DestroyCategoriaEgreso()
{
    $('#tabla_egresos tbody').on('click','#eliminar_categoria',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        IDCATEGORIAEGRESO = fila.find('td').eq(1).text();
        
        Swal.fire({
            title: "Estas seguro de eliminar a la categoria "+fila.find('td').eq(2).text()+"?",
            text: "Al aceptar, la categoría de egreso seleccionado se eliminará por completo junto a las sub categorías que tiene asociado!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, elimianr!"
          }).then((result) => {
            if (result.isConfirmed) {
               DeleteCategoriaEgreso(IDCATEGORIAEGRESO);
            }
          });
    });
}

/// editar las categorias
function EditarCategoriaEgreso()
{
    $('#tabla_egresos tbody').on('click','#editarcategoria',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }

        IDCATEGORIAEGRESO = fila.find('td').eq(1).text();
      
 
        $('#name_categoria_editar').val(fila.find('td').eq(2).text());
    
        $('#modal_editar_categoria').modal("show");
    });
}

/// modificar las categoria
function UpdateCategoriaEgreso(id)
{
  $.ajax({
    url:RUTA+"egreso/categoria/update/"+id,
    method:"POST",
    data:{
        _token:TOKEN,
        name_categoria:$('#name_categoria_editar').val(),
        fecha:$('#fecha_categoria_editar').val(),
        tipoeditar:$('#tipoeditar').val(),
        sedeeditar:$('#sedeeditar').val()
    },
    dataType:"json",
    success:function(response)
    {
        if(response.response == 1)
        {
             Swal.fire({
                title:"Mensaje del sistema!",
                text:"Categoría modificado correctamente!",
                icon:"success",
                target:document.getElementById('modal_editar_categoria')
             }).then(function(){
                MostrarLosEgresos();
             });
        }else{
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al modificar la categoría seleccionado!",
                icon:"error",
                target:document.getElementById('modal_editar_categoria') 
             })
        }
    },error:function(){
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Error al modificar la categoría seleccionado!",
            icon:"error",
            target:document.getElementById('modal_editar_categoria') 
         })
    }
  });
}

function DeleteCategoriaEgreso(id)
{
  $.ajax({
    url:RUTA+"egreso/categoria/delete/"+id,
    method:"POST",
    data:{
        _token:TOKEN,
        
    },
    dataType:"json",
    success:function(response)
    {
        if(response.response == 1)
        {
             Swal.fire({
                title:"Mensaje del sistema!",
                text:"Categoría eliminado correctamente!",
                icon:"success" 
             }).then(function(){
                MostrarLosEgresos();
             });
        }else{
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al eliminar la categoría seleccionado!",
                icon:"error" 
             })
        }
    },error:function(){
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Error al eliminar la categoría seleccionado!",
            icon:"error" 
         })
    }
  });
}

function addSubCategoria()
{
    $('#tabla_egresos tbody').on('click','#add_subcat',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }
         $('#modal_add_subcategoria_egresos_index').modal("show");
        IDCATEGORIAEGRESO = fila.find('td').eq(1).text();
        let NameCategoria = fila.find('td').eq(6).text();
        $('#categoria_index').val(NameCategoria);
        
    });
}

function editarSubCategoriaDeCategoriaDeEgresos()
{
    $('#tabla_egresos tbody').on('click','#editar_subcat',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }
         $('#modal_edit_delete_subcategoria_egresos_index').modal("show");
        IDCATEGORIAEGRESO = fila.find('td').eq(1).text();
        let NameCategoria = fila.find('td').eq(6).text();
        $('#categoria_index_edit').val(NameCategoria);
        MostrarLosSubCategoriasEgresos(IDCATEGORIAEGRESO);
        EliminarLaSubCategoria();
        EditarLaSubCategoria();
        
    });
}

/// mostrar las subcategorias
function MostrarLosSubCategoriasEgresos(id)
{
    TablaSubCategoriasData = $('#detalle_egresos_index_edit').DataTable({
        language: SpanishDataTable(),
        bDestroy: true,
        responsive: true,
        processing:true,
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
        ajax:{
            url:RUTA+"egreso/subcategorias/"+id,
            method:"GET",
            dataSrc:"response"
        },
        columns:[
            {"data":"name_subcategoria"},
            {"data":"id_subcategoria"},
            {"data":null,render:function(){
                return `<button class='btn btn-danger rounded btn-sm' id='eliminar_subcategoria'><i class='bx bx-x'></i></button>
                <button class='btn btn-warning rounded btn-sm' id='editar_subcategoria'> <i class='bx bx-pencil'></i></button>`
            }},
            {"data":"name_subcategoria"},
            {"data":"valor_gasto"},
        ],
        columnDefs:[
            { "sClass": "hide_me", target: 1 }
          ]
    });

    TablaSubCategoriasData.on('order.dt search.dt', function() {
        TablaSubCategoriasData.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
}


function EliminarLaSubCategoria()
{
    $('#detalle_egresos_index_edit tbody').on('click','#eliminar_subcategoria',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }
        
        IDSUBCATEGORIAEGRESO = fila.find('td').eq(1).text();
        
        Swal.fire({
            title: "Estas seguro de eliminar la subcategoría "+fila.find('td').eq(3).text()+"?",
            text: "Al eliminarlo ya no podrás recuperarlo!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!",
            target:document.getElementById('modal_edit_delete_subcategoria_egresos_index')
          }).then((result) => {
            if (result.isConfirmed) {
               processDeleteSubCategoria(IDSUBCATEGORIAEGRESO);
            }
          });
        
    });
}

/// editar las subcategorias
function EditarLaSubCategoria()
{
    $('#detalle_egresos_index_edit tbody').on('click','#editar_subcategoria',function(){
        let fila = $(this).parents('tr');

        if(fila.hasClass('child'))
        {
            fila = fila.prev();
        }
        $('#save_egresos_index_edit').attr("disabled",false)
        IDSUBCATEGORIAEGRESO = fila.find('td').eq(1).text();
        $('#name_subcategoria_index_edit').focus();
        $('#name_subcategoria_index_edit').select();
        $('#name_subcategoria_index_edit').val(fila.find('td').eq(3).text());
        $('#gasto_subcategoria_index_edit').val(fila.find('td').eq(4).text());
        
        
    });
}

/// proceso de eliminado de subcategoria

function processDeleteSubCategoria(id)
{
    $.ajax({
        url:RUTA+"egreso/subcategoria/delete/"+id,
        method:"POST",
        data:{
            _token:TOKEN,
            
        },
        dataType:"json",
        success:function(response)
        {
            if(response.response == 1)
            {
                 Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Sub categoría eliminado correctamente!",
                    icon:"success",
                    target:document.getElementById('modal_edit_delete_subcategoria_egresos_index') 
                 }).then(function(){
                    MostrarLosEgresos();
                   $('#modal_edit_delete_subcategoria_egresos_index').modal("hide")
                 });
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error al eliminar la sub categoría seleccionado!",
                    icon:"error" 
                 })
            }
        },error:function(){
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al eliminar la sub categoría seleccionado!",
                icon:"error" 
             });
        }
      });
}
