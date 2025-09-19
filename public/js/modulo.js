//*REGISTRAR NUEVOS MODULOS*/ */
function saveModulo(NameModule){
    $.ajax({
        url:RUTAAPP+"modulo/store",
        method:"POST",
        data:{
            token_:TOKEN,
            moduloname:NameModule.val()
        },
        dataType:"json",
        success:function(response){
             if(response.errormodulo != undefined){
                $('#name_modulo').focus();
                $('#errors_name_modulo').text(response.errormodulo);
                $('#name_modulo').addClass("is-invalid");
             }else{
                $('#name_modulo').removeClass("is-valid");
                $('#errors_name_modulo').text("");
                if(response.error != undefined){
                Swal.fire({
                    title:"ERROR DEL SISTEMA!!",
                    text:response.error,
                    icon:"error",
                    target:document.getElementById('ventana_create_modulo')
                })
            }else{
                if(response.existe!= undefined){
                    Swal.fire({
                        title:"AVISO DEL SISTEMA!!",
                        text:response.existe,
                        icon:"info",
                        target:document.getElementById('ventana_create_modulo')
                    }).then(function(){
                    
                        $('#errors_name_modulo').show();
                        $("#errors_name_modulo").text("")
                    })
                }else{
                    NameModule.focus();
                    Swal.fire({
                        title:"MENSAJE DEL SISTEMA!!",
                        text:response.response,
                        icon:"success",
                        target:document.getElementById('ventana_create_modulo')
                    }).then(function(){
                        NameModule.val("");
                       
                        $('#errors_name_modulo').show();
                        $("#errors_name_modulo").text("");
                        showModulos();
                    })
                }
            }
             }
        }
    })
}
/**MOSTRAR A LOS MODULOS */
function showModulos(){
    TablaModulos = $('#tabla_modulos').DataTable({
        retrieve:true,
        responsive:true,
        language:SpanishDataTable(),
        ajax:{
            url:RUTAAPP+"modulos/all",
            method:"GET",
            dataSrc:"modulos"
        },
        columns:[
            {"data":"moduloname",render:function(moduloname){
                return moduloname.toUpperCase();
            }},
            {"data":null,render:function(dato){
                if(dato.deleted_at  == null){
                  return `<button class="btn btn-outline-primary btn-sm" id='papelera'><i class='bx bx-trash'></i></button>
                    <button class="btn btn-outline-warning btn-sm" id="editar"><i class='bx bxs-edit-alt'></i></button>
                    <span class="badge bg-success">ACTIVO <i class='bx bx-check'></i></span>`;
                    }
                    
                  return `<button class="btn btn-outline-success btn-sm" id="activar"><i class='bx bx-check'></i></button>
                    <button class="btn btn-outline-danger btn-sm" id="delete"><i class='bx bx-x'></i></button>
                    <span class="badge bg-danger">INACTIVO <i class='bx bx-x'></i></span>`;
            },className:"text-center"}
        ]
    }).ajax.reload();
}

/**CONFIRMAR ANTES DE QUITAR DE LISTA */
function  ConfirmDeleteListModulo(Tabla,Tbody){
  $(Tbody).on('click','#papelera',function(){
     let fila = $(this).parents("tr");

     if(fila.hasClass("child")){
        fila = fila.prev();
     }

     let Data = Tabla.row(fila).data();

     Swal.fire({
       title: "ESTAS SEGURO?",
       text: "Al aceptar se quitará de la lista al modulo seleccionado y ya no estará disponible para poder usarlo!!",
       icon: "question",
       showCancelButton: true,
       confirmButtonColor: "#3085d6",
       cancelButtonColor: "#DC143C",
       cancelButtonText:"Cancelar",
       confirmButtonText: "Si,quitar!",
       target:document.getElementById('ventana_create_modulo')
     }).then((result) => {
        
       if (result.isConfirmed) {
          ProcessEliminarModulo(Data.id_modulo);
       }
     });
  });
}

/// HABILITAR MODULO
function  ButtonClickHabilitarModulo(Tabla,Tbody){
  $(Tbody).on('click','#activar',function(){
     let fila = $(this).parents("tr");

     if(fila.hasClass("child")){
        fila = fila.prev();
     }

     let Data = Tabla.row(fila).data();
     ProcessHabilitarModulo(Data.id_modulo);
  });
}

/// PROCESO DE ELIMINAR AL MODULO
function ProcessEliminarModulo(id){
    let FormDeleteModuloPapelera = new FormData();
    FormDeleteModuloPapelera.append("token_",TOKEN);
  axios({
    url:RUTAAPP+"modulo/"+id+"/eliminar",
    method:"POST",
    data:FormDeleteModuloPapelera
  }).then(function(response){
     if(response.data.error != undefined){
        Swal.fire({
            title:"ERROR DEL SISTEMA!!!",
            text:response.data.error,
            icon:"error",
            target:document.getElementById('ventana_create_modulo')
        })
     }else{
         Swal.fire({
            title:"MENSAJE DEL SISTEMA!!!",
            text:response.data.response,
            icon:"success",
            target:document.getElementById('ventana_create_modulo')
        }).then(function(){
            showModulos();
        });
     }
  })
}

/// PROCESO DE HABILITAR MODULO
function ProcessHabilitarModulo(id){
    let FormHabilitarModulo = new FormData();
    FormHabilitarModulo.append("token_",TOKEN);
  axios({
    url:RUTAAPP+"modulo/"+id+"/habilitar",
    method:"POST",
    data:FormHabilitarModulo
  }).then(function(response){
     if(response.data.error != undefined){
        Swal.fire({
            title:"ERROR DEL SISTEMA!!!",
            text:response.data.error,
            icon:"error",
            target:document.getElementById('ventana_create_modulo')
        })
     }else{
         Swal.fire({
            title:"MENSAJE DEL SISTEMA!!!",
            text:response.data.response,
            icon:"success",
            target:document.getElementById('ventana_create_modulo')
        }).then(function(){
            showModulos();
        });
     }
  })
}
/**VER MODULOS DISPONIBLES */
function showModulosDisponibles(modulohtml){
    let option = '<option disabled selected>--- Seleccione ----</option>';

    axios({
        url:RUTAAPP+"modulos/disponibles",
        method:"GET",
    }).then(function(response){
         if(response.data.modulos != undefined && response.data.modulos.length > 0){
              response.data.modulos.forEach(modulo => {
                 option+=`<option value=`+modulo.id_modulo+`>`+modulo.moduloname.toUpperCase()+`</option>`;
              });
         } 

         $(modulohtml).html(option);
    });
}