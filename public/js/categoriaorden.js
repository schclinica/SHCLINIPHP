$('#view_form_excel_categoria').click(function(){
accionCategoria ='importar';
$('#import_excel_categoria').show();
$('#form_save_categoria').hide();
$('#div_import_excel_categoria').hide();
$('#div_save_form_categoria').show();
});

$('#view_form_register_categoria').click(function(){
    $('#import_excel_categoria').hide();
    $('#form_save_categoria').show();
    $('#div_import_excel_categoria').show();
    $('#div_save_form_categoria').hide();
});


$('#create_categoria').click(function(evento){
    evento.preventDefault();
    
  $('#modal_create_categoria').modal("show");
  ShowCategoriasOrden();
  EditarCategoriaOrden(TablacategoriasOrden,'#tabla_categorias tbody');
  ConfirmEliminadoCategoriaOrden(TablacategoriasOrden,'#tabla_categorias tbody');
  ActiveClickCategoriaOrden(TablacategoriasOrden,'#tabla_categorias tbody');
  ConfirmBorradoCategoriaOrden(TablacategoriasOrden,'#tabla_categorias tbody');
});

$('#cerrar_modal_categoria').click(function(){
    $('#modal_create_categoria').modal("hide");
    $('#form_save_categoria')[0].reset();
    CATEGORIAIDORDEN = null;
    accionCategoria = 'save';

    $('#import_excel_categoria').hide();
    $('#form_save_categoria').show();
    $('#div_import_excel_categoria').show();
    $('#div_save_form_categoria').hide();
});

/**MOSTRAR TIPO ORDENES DISPOIBLES */
function mostrarTipoCategoriaDisponibles(){
    let tipo_ordenes = '';
    axios({
        url:RUTA+"tipo_ordenes/disponibles",
        method:"GET",
    }).then(function(response){
        if(response.data.tipo_ordenes.length > 0){
            response.data.tipo_ordenes.forEach(element => {
                tipo_ordenes+=`<option value="`+element.id_tipo_examen+`">`+element.nombre_tipo_examen.toUpperCase()+`</option>`;
            });

            $('#tipo_categoria').html(tipo_ordenes);
        }else{
            $('#tipo_categoria').html(tipo_ordenes);   
        }
    });
}

/** MOSTRAR LAS CATEGORIAS ORDEN */
function ShowCategoriasOrden(){
    TablacategoriasOrden = $('#tabla_categorias').DataTable({
        retrieve:true,
        ajax:{
            url:RUTA+"categorias/orden",
            method:"GET",
            dataSrc:"categorias"
        },
        columns:[
            {"data":null,render:function(dato){
                if(dato.estado_eliminado == null){
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
            {"data":"codigo_categoria"},
            {"data":"nombre_tipo_examen",render:function(tipo){
                return tipo.toUpperCase();
            }},
            {"data":"nombre_categoria",render:function(category){
                return category.toUpperCase();
            }},
            {"data":null,render:function(dato){
                return dato.estado_eliminado != null ? '<span class="badge bg-danger">DESHABILITADO</span>':'<span class="badge bg-success">HABILITADO</span>';
            }}
        ]
    }).ajax.reload();
}

/// EDITAR UNA CATEGORIA
function EditarCategoriaOrden(Tabla,Tbody){
    $(Tbody).on('click','#editar',function(){

        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        CATEGORIAIDORDEN = Data.id_categoria_examen;
        accionCategoria = 'editar';
        $('#import_excel_categoria').hide();
        $('#form_save_categoria').show();
        $('#div_import_excel_categoria').show();
        $('#div_save_form_categoria').hide();
        $('#text_modal_create_categoria').text("Editar Categoría");
        $('#codigo_categoria').val(Data.codigo_categoria);
        $('#nombre_categoria').val(Data.nombre_categoria);
        $('#tipo_categoria').val(Data.id_tipo_examen);
       
    });
}

/// activar la categoria
function ActiveClickCategoriaOrden(Tabla,Tbody){
    $(Tbody).on('click','#activar',function(){

        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        CATEGORIAIDORDEN = Data.id_categoria_examen;
        ActivarCategoriaOrder(CATEGORIAIDORDEN);
       
    });
}

/// CONFIRMAR ANTES DE ELIMINAR LA CATEGORIA
function ConfirmEliminadoCategoriaOrden(Tabla,Tbody){
    $(Tbody).on('click','#eliminar',function(){

        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        CATEGORIAIDORDEN = Data.id_categoria_examen;
        Swal.fire({
          title: "Estas seguro?",
          text: "Deseas eliminar a la categoria "+Data.nombre_categoria+" ?",
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Si, eliminar!",
          target:document.getElementById('modal_create_categoria')
        }).then((result) => {
          if (result.isConfirmed) {
            EliminarCategoriaOrder(CATEGORIAIDORDEN);
          }
        });
       
    });
}

/// CONFIRMAR ANTES DE BORRAR LA CATEGORIA
function ConfirmBorradoCategoriaOrden(Tabla,Tbody){
    $(Tbody).on('click','#borrar',function(){

        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();

        CATEGORIAIDORDEN = Data.id_categoria_examen;
        Swal.fire({
          title: "Estas seguro?",
          text: "Deseas borrar a la categoria "+Data.nombre_categoria+" ?",
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Si, eliminar!",
          target:document.getElementById('modal_create_categoria')
        }).then((result) => {
          if (result.isConfirmed) {
            BorrarCategoriaOrder(CATEGORIAIDORDEN);
          }
        });
       
    });
}

/// BORRAR LA CATEGORIA
function BorrarCategoriaOrder(id){
    let FormCategoryOrderBorrar = new FormData();
    FormCategoryOrderBorrar.append("token_",TOKEN);
    axios({
        url:RUTA+"categoria/order/borrar/"+id,
        method:"POST",
        data:FormCategoryOrderBorrar
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById('modal_create_categoria')
            });
        }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.success,
                icon:"success",
                target:document.getElementById('modal_create_categoria')
            }).then(function(){
                ShowCategoriasOrden();
                CATEGORIAIDORDEN=null;
                accionCategoria='save';
            });
        }
    }); 
}

/// ELIMINAR categoria
function  EliminarCategoriaOrder(id){
    let FormCategoryOrderEliminar = new FormData();
    FormCategoryOrderEliminar.append("token_",TOKEN);
    axios({
        url:RUTA+"categoria/order/eliminar/"+id,
        method:"POST",
        data:FormCategoryOrderEliminar
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById('modal_create_categoria')
            });
        }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.success,
                icon:"success",
                target:document.getElementById('modal_create_categoria')
            }).then(function(){
                ShowCategoriasOrden();
                CATEGORIAIDORDEN=null;
                accionCategoria='save';
            });
        }
    });
}

/// ACTIVAR LA CATEGORIA
function  ActivarCategoriaOrder(id){
    let FormCategoryOrderActive = new FormData();
    FormCategoryOrderActive.append("token_",TOKEN);
    axios({
        url:RUTA+"categoria/order/activar/"+id,
        method:"POST",
        data:FormCategoryOrderActive
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById('modal_create_categoria')
            });
        }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.success,
                icon:"success",
                target:document.getElementById('modal_create_categoria')
            }).then(function(){
                ShowCategoriasOrden();
                CATEGORIAIDORDEN=null;
                accionCategoria='save';
            });
        }
    });
}

/// registrar categoria
function saveCategoriaOrden(){
    let FormCategoryOrder = new FormData(document.getElementById('form_save_categoria'));
    axios({
        url:RUTA+"categoria/order/save",
        method:"POST",
        data:FormCategoryOrder
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById('modal_create_categoria')
            });
        }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.success,
                icon:"success",
                target:document.getElementById('modal_create_categoria')
            }).then(function(){
                ShowCategoriasOrden();
                $('#form_save_categoria')[0].reset();
            });
        }
    });
}


/// registrar categoria
function modificarCategoriaOrden(id){
    let FormCategoryOrderUpdate = new FormData(document.getElementById('form_save_categoria'));
    axios({
        url:RUTA+"categoria/order/update/"+id,
        method:"POST",
        data:FormCategoryOrderUpdate
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById('modal_create_categoria')
            });
        }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.success,
                icon:"success",
                target:document.getElementById('modal_create_categoria')
            }).then(function(){
                ShowCategoriasOrden();
                $('#form_save_categoria')[0].reset();
                CATEGORIAIDORDEN=null;
                accionCategoria='save';
                $('#text_modal_create_categoria').text("Crear categoría")
            });
        }
    });
}

function importCategoriaOrder(){
    let datosFormOrdenCategory = new FormData(document.getElementById('import_excel_categoria'));
    axios(
        {
            url:RUTA+"categoria/order/importar-datos",
            method:"POST",
            data:datosFormOrdenCategory,
        }
    ).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"Mensaje del sistema!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById('modal_create_categoria')
            });
        }else{
            if(response.data.response === 'ok' || response.data.response === 'existe'){
                loading('#loading_create_categoria','#4169E1','chasingDots');
                setTimeout(function(){
                    $('#loading_create_categoria').loadingModal('hide');
                    $('#loading_create_categoria').loadingModal('destroy');

                    Swal.fire({
                        title:"Mensaje del sistema!!",
                        text:"Datos de la categoría importados correctamente!!",
                        icon:"success",
                        target:document.getElementById('modal_create_categoria')
                    }).then(function(){
                        $('#import_excel_categoria')[0].reset();
                        ShowCategoriasOrden();
                        accionCategoria = 'save';
                        $('#text_modal_create_categoria').text("Crear categoría")
                        //mostrarTipoOrdenesEdicionCombo();
                    });  
                },1000);  
            }
        }
    });
}

/// MOSTRAR TODAS LAS CATEGORIAS DISPONIBLES DEPENDIENDO DEL TIPO DE ORDEN
function ShowCategoryDisponiblesTipo(id){
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

        $('#categoria_orden').html(option);
    });
}


