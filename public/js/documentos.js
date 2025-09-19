/** MOSTRAR A LOS TIPOS DE DOCUMENTOS DE EMISION */
function showTypeDocumentEmition(){
    TablaDocumentos = $('#lista_documentos').DataTable({
        retrieve:true,
        language:SpanishDataTable(),
        ajax:{
            url:RUTA+"documentos-emision/all",
            method:"GET",
            dataSrc:"documentos"
        },
        columns:[
            {"data":null,render:function(){
                return `<button class="btn btn-outline-danger btn-sm" id="eliminar"><i class='bx bx-x'></i></button>
                 <button class="btn btn-outline-warning btn-sm" id="editar"><i class='bx bx-edit-alt' ></i></button>`;
            }},
            {"data":"name_documento"},
            {"data":"seriedoc",className:"text-center"},
            {"data":"correlativo_inicial",className:"text-center"},
            {"data":"correlativo_final",className:"text-center"},
            {"data":"estado",render:function(estado){
                return estado === 'a' ? '<span class="badge bg-success">ACTIVO</span>':'<span class="badge bg-danger">INACTIVO</span>'
            }},
            {"data":"namesede",render:function(sede){
                return sede.toUpperCase();
            }}
        ]
    }).ajax.reload();
}

/** CONFIRMAR ANTES DE ELIMINAR EL DOCUMENTO */
function ConfirmEliminadoDocumento(Tabla,Tbody){
    $(Tbody).on('click','#eliminar',function(){
        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }
        let Data = Tabla.row(fila).data();
        DOCID = Data.id_documento_emision;
        Swal.fire({
          title: "ESTAS SEGURO?",
          text: "Al aceptar , el documento seleccionado se quitará de la lista!!!",
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Si, eliminar!",
        }).then((result) => {
          if (result.isConfirmed) {
             Eliminar(DOCID);
          }
        });
    });
}

/// EDITAR
function EditarDocumento(Tabla,Tbody){
    $(Tbody).on('click','#editar',function(){
        let fila = $(this).parents("tr");

        if(fila.hasClass("child")){
            fila = fila.prev();
        }
        let Data = Tabla.row(fila).data();
        DOCID = Data.id_documento_emision;
        $('#nombredoceditar').val(Data.name_documento);
        $('#seriedoceditar').val(Data.seriedoc);
        $('#sedeeditar').val(Data.sede_id);
        $('#tipoeditar').val(Data.tipo);
        $('#correlativodocinicialeditar').val(Data.correlativo_inicial);
        $('#correlativodocfinaleditar').val(Data.correlativo_final);
        $('#estado').val(Data.estado);
        $('#editar_documento').modal("show");
    });
}

/// PROCESO DE ELIMINAR
function Eliminar(id){
    let FormDelete = new FormData();
    FormDelete.append("token_",TOKEN);
    axios.post(RUTA+"documento-emision/"+id+"/eliminar",FormDelete).then(function(response){
         if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.error,
                icon:"error"
            });
         }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.success,
                icon:"success"
            }).then(function(){
                showTypeDocumentEmition();
            });
         }
    });
}

/// actualizar
function modificar(id){
     
    axios({url:RUTA+"documento-emision/"+id+"/update",method:"POST",data:$('#form_update_doc').serialize()}).then(function(response){
        if(response.data.info != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.info,
                icon:"info",
                target:document.getElementById('editar_documento')
            });
            return;
         } 
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById('editar_documento')
            });
         }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.success,
                icon:"success",
                target:document.getElementById('editar_documento')
            }).then(function(){
                showTypeDocumentEmition();
            });
         }
    });
}