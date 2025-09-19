/// método que muestra a los tipos de documentos en format json
function ShowTipoDocumentos()
{
    /// inicializamos la option del select
    let option ="";
    
    $.ajax(
        {
          url:RUTA+"documentos-existentes?token_="+TOKEN,

          method:"GET",

          success:function(response)
          {
            response = JSON.parse(response)
            
            if(response.response.length > 0)
            {
                response.response.forEach(documento => {
                    
                    option+=
                     `
                      <option value=`+documento.id_tipo_doc+`>`+documento.name_tipo_doc.toUpperCase()+`</option>
                    `;
                });
            }
            $('#tipo_doc').html(option)
          } 
        }
    )
}

function ShowTipoDocumentosIdElement(id)
{
    /// inicializamos la option del select
    let option ="";
    
    $.ajax(
        {
          url:RUTA+"documentos-existentes?token_="+TOKEN,

          method:"GET",

          success:function(response)
          {
            response = JSON.parse(response)
            
            if(response.response.length > 0)
            {
                response.response.forEach(documento => {
                    
                    option+=
                     `
                      <option value=`+documento.id_tipo_doc+`>`+documento.name_tipo_doc.toUpperCase()+`</option>
                    `;
                });
            }
            $('#'+id).html(option)
          } 
        }
    )
}
/// método que muestrar los departamentos en format json
function ShowDepartamentos(idselect)
{
    /// inicializamos la option del select
    let option ="<option disabled selected> --- Seleccione --- </option>";
    
    $.ajax(
        {
          url:RUTA+"departamento/mostrar?token_="+TOKEN,

          method:"GET",

          success:function(response)
          {
            response = JSON.parse(response)
            
            if(response.response.length > 0)
            {
                response.response.forEach(documento => {
                    
                    option+=
                     `
                      <option value=`+documento.id_departamento+`>`+documento.name_departamento.toUpperCase()+`</option>
                    `;
                });
            }
            $('#'+idselect).html(option)
          } 
        }
    )
}

/// proceso para crear tipo de documentos(PETICIÓN AJAX)

function saveTipoDocumento(form_id)
{

  let response = crud(RUTA+"tipodoc/save",form_id);

   
     if(response == 1)
     {
      MessageRedirectAjax('success','Mensaje del sistema','Tipo de documento registrado','modal-crear-docente')
      $('#'+form_id)[0].reset()
      ShowTipoDocumentos()
     }
     else
     {
      if(response === 'existe')
      {
       MessageRedirectAjax('warning','Mensaje del sistema','Tipo de documento ya existe','modal-crear-docente')
      }
      else
      {
        MessageRedirectAjax('error','Mensaje del sistema','Error al registrar tipo documento ):','modal-crear-docente')
      }
     }
}

/// proceso para crear departamentos(PETICIÓN AJAX)

function saveDepartamento(form_id)
{
  let response = crud(RUTA+"departemento/save",form_id);
  

     if(response == 1)
     {
      MessageRedirectAjax('success','Mensaje del sistema','Depatamento registrado','modal-crear-docente')

      $('#'+form_id)[0].reset() /// reseteamos el form

      ShowDepartamentos('departamento'); /// mostrar los departamentos existentes para formulario de pacientes

      ShowDepartamentos('departamento_select') /// mostrar los departamentos existentes para formulario de provincias
      ShowDepartamentos('departamento_select_dis')
     }
     else
     {
      if(response === 'existe')
      {
       MessageRedirectAjax('warning','Mensaje del sistema','El deparatemento que deseas registrar ya existe','modal-crear-docente')
      }
      else
      {
        MessageRedirectAjax('error','Mensaje del sistema','Error al registrar departamento ):','modal-crear-docente')
      }
     }

}

/// registrar provincias

function saveProvincia(form_id)
{

  let response = crud(RUTA+"provincia/save",form_id);

  
  if(response == 1)
  {
   MessageRedirectAjax('success','Mensaje del sistema','Provincia registrado','modal-crear-docente')

   $('#'+form_id)[0].reset() /// reseteamos el form

   allProvincias(); /// mostramos las provincias a registrar uno nuevo
   ShowDepartamentos('provincia_select_dis')
  }
  else
  {
   if(response === 'existe')
   {
    MessageRedirectAjax('warning','Mensaje del sistema','La Provincia que deseas registrar ya existe','modal-crear-docente')
   }
   else
   {
     MessageRedirectAjax('error','Mensaje del sistema','Error al registrar provincia ):','modal-crear-docente')
   }
  }
}

/// mostrar las provincias existentes por departamento

function showProvincias(id_dep,select_id)
{
  let option ="<option disabled selected>--- Seleccione ---</option>";
  
  let resultado = show(RUTA+"provincia/mostrar?token_="+TOKEN,{id_departamento:id_dep});
  
  if(resultado.length > 0)
  {
   resultado.forEach(provincia => {
    
    option+= "<option value="+provincia.id_provincia+">"+provincia.name_provincia.toUpperCase()+"</option>";

   });
   
  }
  $('#'+select_id).html(option);
}

/// crear distritos
function saveDistrito(form_id)
{
  let response = crud(RUTA+"distrito/save",form_id);
  

  if(response == 1)
  {
   MessageRedirectAjax('success','Mensaje del sistema','Distrito registrado correctamente','modal-crear-docente')

   $('#name_distrito').val("");
   $('#name_distrito').focus();
  }
  else
  {
   if(response === 'existe')
   {
    MessageRedirectAjax('warning','Mensaje del sistema','El distrito que deseas registrar ya existe','modal-crear-docente')
   }
   else
   {
     MessageRedirectAjax('error','Mensaje del sistema','Error al registrar distrito ):','modal-crear-docente')
   }
  }
}

/// mostramos los distros por provincia

function showDistritos_Provincia(id_prov,select_id)
{
  let option ="<option disabled selected>--- Seleccione ---</option>";
  
  let resultado = show(RUTA+"distritos/mostrar-para-la-provincia/"+id_prov+"?token_="+TOKEN);

  if(resultado.length > 0)
  {
   resultado.forEach(distrito => {
    
    option+= "<option value="+distrito.id_distrito+">"+distrito.name_distrito.toUpperCase()+"</option>";

   });
   
  }
  $('#'+select_id).html(option);
}

/// mostrar los médicos existentes
function mostrarMedicos()
{
  var Medicos =  $('#tabla-medicos').DataTable({

    retrieve:true,
    
  }) 

  /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
 
  /*======================================================================================*/
 
}

/// Mostrar Médicos existentes
function ShowMedicos()
{
  var Medicos_ = $('#tabla-medicos').DataTable({
    processing: true,
    retrieve:true,
    responsive:true,
    language:SpanishDataTable(),

    "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
    "ajax":{
      url:RUTA+"medico/all?token_="+TOKEN,
      method:"GET",
      dataSrc:"Medicos"
    },
    "columns":[
      {"data":"documento"},
      {"data":"name_tipo_doc",render:function(data){return data.toUpperCase()}},
      {"data":"documento",render:function(data){return data.toUpperCase()}},
      {"data":null,render:function(datas){return datas.apellidos.toUpperCase()+' '+datas.nombres.toUpperCase()}},
      {"data":"genero",render:function(data){return data == 1?'<span class="badge bg-primary">MASCULINO</span>':'<span class="badge bg-danger">FEMENINO</span>'}},
      {"data":"fecha_nacimiento",render:function(fechanac){
        let fecha = fechanac.split("-");

        let AnioNac = fecha[0],MesNac = fecha[1],DiaNac = fecha[2];

        return DiaNac+"/"+MesNac+"/"+AnioNac;
      }},
      {"data":null,render:function(data){return data.direccion==null?'<span class="badge bg-danger">'+data.name_departamento+' - '+data.name_provincia+' - '+data.name_distrito.toUpperCase()+'</span>':data.name_departamento.toUpperCase()+' - '+data.name_provincia.toUpperCase()+' - '+' '+data.name_distrito.toUpperCase()+' - '+data.direccion.toUpperCase()}},
      {"data":"celular_num",render:function(data){return data==null?'<span class="badge bg-danger">No especifica</span>':data.toUpperCase()}},
      {"data":"universidad_graduado",render:function(data){return data==null?'<span class="badge bg-danger">No especifica</span>':data.toUpperCase()}},
      {"data":"cmp",render:function(cmp){
        return cmp != null ? cmp : '<span class="text-danger">No indicó su colegiatura...</span>'
      }},
      {"data":"foto",render:function(data_foto){
        if(data_foto == null)
        {
          return `<img src='`+RUTA+`public/asset/img/default.png' style="width: 36px;height: 33px;border-radius:50%">`
        }

        return `<img src='`+RUTAFOTO+data_foto+`'  style="width: 36px;height: 33px;border-radius:50%">`
      }},
      {"data":"namesede",render:function(sedename){
        if(sedename != null){
          return sedename.toUpperCase();
        }
        return '<span class="badge bg-danger">NO ESPECIFICA</span>';
      }},
      {"defaultContent":`
        <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2">
        <button class="btn rounded btn-outline-warning btn-sm" id='editar_medico'><i class='bx bxs-edit-alt'></i></button>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2">
        <button class="btn rounded btn-outline-danger btn-sm" id='delete_medicos'><i class='bx bxs-message-square-x'></i></button>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2">
        <button class="btn rounded btn-outline-primary btn-sm" id='especialidades_asign' ><i class='bx bx-list-check'></i></button>
        </div>
         <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2">
        <button class="btn rounded btn-outline-success btn-sm" id='especialidades_medico' ><i class='bx bx-cube'></i></button>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2">
        <button class="btn rounded btn-outline-dark btn-sm" id='firma' ><i class='bx bx-bookmark-alt-minus'></i></button>
        </div>
        </div>
      `}
    ]
  
  }).ajax.reload()
   /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
   Medicos_.on( 'order.dt search.dt', function () {
    Medicos_.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();
  /*======================================================================================*/

  AsignarEspecialidades('#tabla-medicos tbody',Medicos_);
  EditarMedico('#tabla-medicos tbody',Medicos_);
  ConfirmEliminadoMedicos('#tabla-medicos tbody',Medicos_);
  VerEspecialidadesDelMedico('#tabla-medicos tbody',Medicos_);
  AgregarFirma('#tabla-medicos tbody',Medicos_);
}

/// VER LAS ESPECIALIDADES DEL MEDICO
function VerEspecialidadesDelMedico(Tbody,Tabla){
   $(Tbody).on("click", "#especialidades_medico", function () {
     let fila = $(this).parents("tr");

     if (fila.hasClass("child")) {
       fila = fila.prev();
     }

     let Data = Tabla.row(fila).data();
     ID_MEDICO_SELECT = Data.id_medico;
     
     $('#medicotextespdatos').val((Data.apellidos+" "+Data.nombres).toUpperCase());
     mostrarTodaEspecialidadAsignadoAlMedico(ID_MEDICO_SELECT);
     $("#modal_view_especialidades_medico").modal("show");
   });
}
/// agregar firma
function AgregarFirma(Tbody,Tabla){
   $(Tbody).on("click", "#firma", function () {
     let fila = $(this).parents("tr");

     if (fila.hasClass("child")) {
       fila = fila.prev();
     }

     let Data = Tabla.row(fila).data();
     ID_MEDICO_SELECT = Data.id_medico;
      
     if(Data.firma != null){
        $('#imagen_firma_preview').attr("src",RUTA+"public/asset/imgfirmas/"+Data.firma);
        $('#save_firma_medico').hide();$('#eliminar_imagen').show();
     }else{
       
       $('#imagen_firma_preview').attr("src",RUTA+"public/asset/img/default.png");
       $('#save_firma_medico').show();$('#eliminar_imagen').hide();
     }
      
     $("#ventana_ingresar_firma").modal("show");
   });
}

/// MOSTRAR LAS ESPECIALIDADES DEL MEDICO SELECCIONADO
function mostrarTodaEspecialidadAsignadoAlMedico(id){
  TablaMedicoEspecialidadesAll = $('#Tabla_med_especialidades_datos').DataTable({
    bDestroy:true,
    responsive:true,
    ajax:{
      url:RUTA+"especialidades/all-por-medico-seleccionado/"+id,
      method:"GET",
      dataSrc:"response"
    },
    columns:[
      {"data":"id_medico_esp",className:"d-none"},
      {"data":"nombre_esp",render:function(especialida){
         return especialida.toUpperCase()
      },className:"text-primary"},
      {"data":null,render:function(){
         return `<button class="btn btn-outline-danger rounded btn-sm" id="quitar_esp_medico_tabla"><i class='bx bx-trash-alt'></i></button>`;
      },className:"text-center"}
    ]
  })

}

/**Confirmar antes de eliminar a un medico */
function ConfirmEliminadoMedicos(Tbody,Tabla)
{
 
  $(Tbody).on('click','#delete_medicos',function(){
  
     let filaSeleccionadoMedico = $(this).parents("tr");

     if(filaSeleccionadoMedico.hasClass('child'))
     {
       filaSeleccionadoMedico = filaSeleccionadoMedico.prev();
     }

     let Data = Tabla.row(filaSeleccionadoMedico).data();
     let medicoDataText = (Data.apellidos+" "+Data.nombres).toUpperCase();
     let UserDataId = Data.id_usuario;
     let MedicoDataId = Data.id_medico;
     Swal.fire({
      title: "Estas seguro de eliminar al médico "+medicoDataText+"?",
      text: "Al aceptar, se borrará por completo al médico y ya no podrás recuperarlo!",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar!",
      target:document.getElementById('content')
    }).then((result) => {
      if (result.isConfirmed) {
         eliminarMedico(UserDataId,MedicoDataId);
      }
    })
     
  });
}

/**eliminar al médico */
function eliminarMedico(id,idmedico){
  $.ajax({
    url:RUTA+"medico/"+id+"/"+idmedico+"/delete",
    method:"POST",
    data:{
      token_:TOKEN
    },
    dataType:"json",
    success:function(response){
     if(response.response === 'ok')
      {
        Swal.fire({
          title:"Mensaje del sistema!",
          text:"Médico eliminado correctamente!",
          icon:"success",
        }).then(function(){
           ShowMedicos();
        });
      }else{
       if(response.response === 'existe'){
        Swal.fire({
          title:"Mensaje del sistema!",
          text:"No se puede eliminar al médico, porque cuenta con historial en los procesos de la clinica!",
          icon:"error",
        })
       }else{
        Swal.fire({
          title:"Mensaje del sistema!",
          text:"Hubo un error al eliminar al médico!",
          icon:"error",
        })
       }
      }
    }
  });
}
function EditarMedico(Tbody,Tabla){
  $(Tbody).on('click','#editar_medico',function(){
    let filaSeleccionada = $(this).parents('tr');

    if(filaSeleccionada.hasClass("child")){
      filaSeleccionada = filaSeleccionada.prev();
    }
    $('#modal_editar_medico').modal("show");
    let Data = Tabla.row(filaSeleccionada).data();
    MEDICOID = Data.id_medico;PERSONAID = Data.id_persona;
    
    $('#tipo_doc_editar').val(Data.id_tipo_doc);
    $('#dni_editar').val(Data.documento);
    $('#apellido_editar').val(Data.apellidos);
    $('#nombre_editar').val(Data.nombres);
    $('#genero_editar').val(Data.genero);
    $('#direccion_editar').val(Data.direccion);
    $('#fechanac_editar').val(Data.fecha_nacimiento);
    $('#dep_editar').val(Data.id_departamento);
    showProvincias($('#dep_editar').val(),'prov_editar');
    $('#prov_editar').val(Data.id_provincia);
    showDistritos_Provincia($('#prov_editar').val(),'distrito_editar');
    $('#distrito_editar').val(Data.id_distrito);
    $('#telefono_editar').val(Data.celular_num);
    $('#universidad_editar').val(Data.universidad_graduado);
    $('#cmp_editar').val(Data.cmp);
    $('#experiencia_editar').val(Data.experiencia);
    Data.medicosede_id != null ? $('#sedeeditar').val(Data.medicosede_id):$('#sedeeditar').prop("selectedIndex",0); 
    //alert(Data.id_medico+" "+Data.id_persona)
     
  });
}
/// registrar médicos

function saveMedico(data_)
{
  
  axios({
    url:RUTA+'medico/save',
    method:"POST",
    data:data_
  }).then(function(response){
    if(response.data.errors != undefined){
      let li='';
      $('#alerta_errores_medico').show(100);

      response.data.errors.forEach(error=> {
        li+=`<li>`+error+`</li>`;
      });

      $('#errores_medico').html(li);
      return;
    }
   
    if(response.data.response == 1)
    {
      MessageRedirectAjax('success','Mensaje del sistema','Médico registrado correctamente (:','modal-crear-docente');

      ShowMedicos(); /// refrescamos el DataTable

      $('#form_medico')[0].reset()
      $('#errores_medico').empty();
      $('#alerta_errores_medico').hide();
      $('#imagen_').attr('src',RUTA+"public/asset/img/avatars/anonimo_2.png");
    }
    else
    {
      MessageRedirectAjax('error','Mensaje del sistema','Error , el archivo es incorrecto (:','modal-crear-docente')
    }
  });
}

/// Asignar especialidades a los médicos
function AsignarEspecialidades(Tbody,Tabla)
{  
  $(Tbody).on('click','#especialidades_asign',function(){

      /// Fila Seleccionada
      let Fila = $(this).parents('tr');

      /// como tenemos un DataTable responsive , verificamo si existe la clase child
      if(Fila.hasClass('child'))
      {
        Fila = Fila.prev();
      }

      /// obtenemos la Data
      let Data = Tabla.row(Fila).data();

      ID_MEDICO_SELECT = Data.id_medico;

      let Medico = Data.apellidos+" "+Data.nombres;

      MEDICO =Medico;

      /// mostramos las especialidades no asignados del médico
      VerEspecialidadesNoAsignadosDelMedico(ID_MEDICO_SELECT,'')

      $('#medico_seleccionado').val(Medico)
      /// Abrimos un modal para asignar especialidades al médico
      
      FocusInputModal('modal-asignar-especialidad','medico_seleccionado');
       
  });
}

/// registrar especialidades

function CrearEspecialidades(data_form)
{
  axios({
    url:RUTA+"especialidad/save",
    method:"POST",
    data:data_form
  }).then(function(response){
    if(response.data.response == 1)
    {
      MessageRedirectAjax('success','Mensaje del sistema','Especialidad agregado con éxito','modal-asignar-especialidad');
      VerEspecialidadesNoAsignadosDelMedico(ID_MEDICO_SELECT,'')
      $('#form_especialidad')[0].reset()
    }
    else{
      if(response.data.response == 'existe')
      {
        MessageRedirectAjax('warning','Mensaje del sistema','La especialidad que deseas registrar ya existe','modal-asignar-especialidad')
      }
      else
      {
        MessageRedirectAjax('error','Mensaje del sistema','Error al registrar la especialidad','modal-asignar-especialidad')
      }
    }
  })
}

/// MOSTRAR LAS ESPECIALIDADES NO ASIGNADOS , PERO POR MÉDICO
function VerEspecialidadesNoAsignadosDelMedico(idMedico,buscador)
{
  let tr='';
  axios({
    url:RUTA+`medico/`+idMedico+`/`+buscador+`/especialidades-no-asignados?token_=`+TOKEN,
    method:"GET"

  }).then(function(response){
    let Data = response.data; let Item = 0;

    //Data.Especialidades.forEach(especialidad => {
    for(especialidad of Data.Especialidades) {
      Item++;
      tr+=`
      <tr>
      <td>`+Item+`</td>
      <td>`+especialidad.nombre_esp.toUpperCase()+`</td>
      <td><span class='badge bg-success'>`+(especialidad.precio_especialidad == null ? 0:especialidad.precio_especialidad)+`</span></td>
      <td>
      <div class='row'>
      <div class='col-xl-2 col-lg-3 col-md-3 col-12'>
      <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" id="seleccion_especialidades" value=`+especialidad.id_especialidad+`>
      </div>
      </div>
      <div class='col-xl-2 col-lg-3 col-12 mx-2 my-xl-0 my-lg-0 my-1'>
       <button class='btn btn-rounded btn-warning btn-sm'><i class='bx bxs-edit-alt' onclick=\"editar(`+especialidad.id_especialidad+`,'`+especialidad.nombre_esp+`',`+especialidad.precio_especialidad+`)\";></i></button>
      </div>
      <div class='col-xl-2 col-lg-3 col-12  mx-2 my-xl-0 my-lg-0 my-1'>
      <button class='btn btn-rounded btn-danger btn-sm'><i class='bx bxs-message-square-x' onclick=ConfirmDelete(`+especialidad.id_especialidad+`,'`+especialidad.nombre_esp.replace(" ","_")+`')></i></button>
     </div>
      </div>
      </td>
      </tr>
      `;
    }

    $('#lista-especialidades').html(tr)
  });
}


/// obtener especialidades seleccionados por médico

function AsignarEspecialidadesMedico()
{
  let Respuesta = null;
  $('#lista-especialidades input[type=checkbox]').each(function(){

    if($(this).is(':checked'))
    {
      Respuesta =  SaveEspecialidadMedico($(this).val(),ID_MEDICO_SELECT);
    }
  });
   
 if( Respuesta)
 {
  MessageRedirectAjax('success','Mensaje del sistema','Especialidades asignados correctamente al médico '+MEDICO,'modal-asignar-especialidad')

  VerEspecialidadesNoAsignadosDelMedico(ID_MEDICO_SELECT,'')}
 else{
  MessageRedirectAjax('error','Mensaje del sistema','Error al asignar especialidades al médico '+MEDICO,'modal-asignar-especialidad')
 }
}

/// Registrar especialidades por médico
async function SaveEspecialidadMedico(especialidad,medico_)
{
  let Message = null;
  let Data = new FormData();
  Data.append("token_",TOKEN);
  Data.append("especialidad",especialidad);
  Data.append("medico",medico_);
 
  try {
    return axios({
      url:RUTA+"medico/asignar-especialidad",
      method:"POST",
      data:Data,
      responseType: 'text'
    }).then(function(response){
      return response.data.response;
    });
  
  } catch (error) {
    return 'error';
  }
 

}

/// editar las especialidades
function editar(id,especialidad,precio)
{
  IDESPECIALIDAD = id;
  $('#nueva-especialidad').prop('checked',false)
  $('#title_new_especialidad').text('Nuevo');
  if(precio == null){
     
    $('#precio').val(0);
  }else{
    $('#precio').val(precio);
  }
  $('#especialidad').val(especialidad);
   
  Control_Especialidad = 'update';
  $('#form-new-especialidad').show(800)
  subidaScroll('.modal-body,html', 0)
}

/// modificar una especialidad

function modificarEspecialidad(id_especialidad)
{
  let Data = new FormData(form_especialidad);
  axios({
    url:RUTA+"especialidad/"+id_especialidad+"/update",
    method:"POST",
    data:Data
  }).then(function(response){

    if(response.data.response == 1 || response.data.response === 'existe')
    {
      MessageRedirectAjax('success','Mensaje del sistema','La especialidad se a modificado correctamente ','modal-asignar-especialidad')

      /// reseteamos el formulario
      $('#form_especialidad')[0].reset();/// reseteamos todo el formulario de especialidades
      /// mostramos nuevamente las especialidades con respecto al médico
      VerEspecialidadesNoAsignadosDelMedico(ID_MEDICO_SELECT,'')

    }
    else
    {
      MessageRedirectAjax('error','Error al modificar ','modal-asignar-especialidad')
    }
  });
}

/// Eliminar una especialidad

function CambiarEstadoEspecialidad(id_especialidad,estado,tipoMensaje)
{
  let Data = new FormData();
  Data.append("token_",TOKEN);
  Data.append("estado",estado)
  axios({
    url:RUTA+"especialidad/"+id_especialidad+"/delete",
    method:"POST",
    data:Data
  }).then(function(response){

    if(response.data.response == 1)
    {
      MessageRedirectAjax('success','Mensaje del sistema','La especialidad se a '+tipoMensaje+' correctamente ','modal-asignar-especialidad')
            /// mostramos nuevamente las especialidades con respecto al médico
      VerEspecialidadesNoAsignadosDelMedico(ID_MEDICO_SELECT,'');
            /// mostrar todad las especialidades en el Data Table
      MostrarEspecialidades_();
            
    }
    else
    {
      MessageRedirectAjax('error','Error al eliminar la especialidad ','modal-asignar-especialidad')
    }
  });
}

/// confirmar antes de eliminar una especialidad
function ConfirmDelete(id,especialidad)
{
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success mx-2',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
  })
  
  swalWithBootstrapButtons.fire({
    title: 'Deseas eliminar la especialidad '+especialidad+' ? ',
    text: "Al eliminar la especialidad , se quitará de la lista de especialidades existentes!",
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Si, Eliminar!',
    cancelButtonText: 'No, cancelar!',
    reverseButtons: true,
    target: document.getElementById('modal-asignar-especialidad')
  }).then((result) => {
    if (result.isConfirmed) {
      CambiarEstadoEspecialidad(id,"0",'eliminado');/// Eliminamos la especialidad seleccionado
    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {
      swalWithBootstrapButtons.fire(
        {
          title:'Mensaje del sistema!',
          text:'Sus datos están a salvo.',
          icon:'error',
          target: document.getElementById('modal-asignar-especialidad')
        }
      )
    }
  })
}

/// mostrar las especialidades en DataTable
function MostrarEspecialidades_()
{
  var TablaEspecialidades = $('#Table_especialidades_gestion').DataTable({
    retrieve:true,
    responsive:true,
    autoWidth: true,
    language:SpanishDataTable(),
    "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
    "order": [[1, 'asc']], /// enumera indice de las columnas de Datatable
    "ajax":{
      url:RUTA+`especialidad/all?token_=`+TOKEN,
      method:"GET",
      dataSrc:"Especialidades"
    },
    "columns":[
      {"data":null},
      {"data":"nombre_esp",render:function(dta){return dta.toUpperCase()}},
      {"data":"estado",render:function(data_){return data_== 1?'<span class="badge bg-info">Activo</span>':'<span class="badge bg-danger">Eliminado</span>'}},
      {"data":null,render:function(dato){

        if(dato.estado == 1)
        {
          return `
          <div class="row">
          <div class="col-xl-2 col-lg-3 col-md-4 col-sm-5 col-12">
          <button class="btn rounded btn-danger btn-sm" id='delete-esp-data'><i class='bx bxs-trash-alt'></i></button>
          </div>
          </div>
          `;
        }

        return `
        <div class="row">
         <div class="col-xl-2 col-lg-3 col-md-4 col-sm-5 col-12">
          <button class="btn rounded btn-danger btn-sm" id='delete-esp-data'><i class='bx bxs-trash-alt'></i></button>
          </div>
  
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-5 col-12">
        <button class="btn rounded btn-success btn-sm" id='habilitar'><i class='bx bx-check'></i></button>
        </div>
   
        </div>
        `;

      }}
    ],
    
  }).ajax.reload();

   /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
   TablaEspecialidades.on( 'order.dt search.dt', function () {
    TablaEspecialidades.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();
  /*======================================================================================*/

  ActivarEstadoEspecialidad('#Table_especialidades_gestion tbody',TablaEspecialidades);
  ForzarEliminadoEspecialidad('#Table_especialidades_gestion tbody',TablaEspecialidades)
 
}

/// cambiar el estado de la espcialidad en 1, osea en activo
function ActivarEstadoEspecialidad(Tbody,Tabla)
{
  $(Tbody).on('click','#habilitar',function(){

    /// obtenemos el Id de la especialidad que deseamos activar
    let fila = $(this).parents('tr');

    /// verificamos que cuándo sea responsive en pantallas pequeños 

    if(fila.hasClass('child'))
    {
      fila = fila.prev();
    }

    /// obtenemos la data
    let Data = Tabla.row(fila).data();

    CambiarEstadoEspecialidad(Data.id_especialidad,"1",'activado');/// Activamos la especialidad seleccionado
   
  });
}

/// Forzar eliminado de especialidades
function ForzarEliminadoEspecialidad(Tbody,Tabla)
{
  $(Tbody).on('click','#delete-esp-data',function(){

    /// obtenemos el Id de la especialidad que deseamos activar
    let fila = $(this).parents('tr');

    /// verificamos que cuándo sea responsive en pantallas pequeños 

    if(fila.hasClass('child'))
    {
      fila = fila.prev();
    }

    /// obtenemos la data
    let Data = Tabla.row(fila).data();

    Swal.fire({
      title: "Estas seguro de eliminar a la especialidad "+Data.nombre_esp+"?",
      text: "Al aceptar , se eliminará por completo la especialidad y no se podrá recuperar !",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar!",
      target:document.getElementById('modal-asignar-especialidad')
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url:RUTA+"delete/"+Data.id_especialidad+"/especialidad",
          method:"POST",
          data:{
            token_:TOKEN,
          },
          dataType:"json",
          success:function(response){
            if(response.response === 'ok')
            {
              MessageRedirectAjax('success','Mensaje del sistema','Especialidad eliminado correctamente! ','modal-asignar-especialidad')
              /// mostramos nuevamente las especialidades con respecto al médico
              VerEspecialidadesNoAsignadosDelMedico(ID_MEDICO_SELECT,'');
              MostrarEspecialidades_();
            }else{
              MessageRedirectAjax('error','Mensaje del sistema!','Error al eliminar la especialidad seleccionada! ','modal-asignar-especialidad')
            }
          }
        })  
      }
    });
   
  });
}
/// mostrar los médicos y las especialidades asignados

/// mostrar las especialidades en DataTable
function MostrarMedicosEspecialidades_()
{
  var TablaEspecialidadesMedico_ = $('#tabla-medico-programacion-horarios__').DataTable({
    retrieve:true,
    responsive:true,
    autoWidth:true,
    processing:true,
    language:SpanishDataTable(),
    "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
    "order": [[1, 'asc']], /// enumera indice de las columnas de Datatable
    "ajax":{
      url:RUTA+`medicos_y_sus_especialidades?token_=`+TOKEN,
      method:"GET",
      dataSrc:"medicos"
    },
    "columns":[
      {"data":null},
      {"data":null,render:function(dta){return dta.apellidos.toUpperCase()+" "+dta.nombres.toUpperCase()}},
      {"data":null,render:function(dta_){

        let Especialidad ='';
         
        if(dta_.especialidades.length > 0)
        {
          dta_.especialidades.forEach(especialidad => {
            Especialidad+='<span class="badge bg-primary m-1 text-white">'+especialidad.nombre_especialidad+'</span>'
          });
        }
        else{
          Especialidad+='<span class="badge bg-danger">No tiene especialidades asignados..</span>'
        }

        return Especialidad;
      }},
      {"defaultContent":`

      <div class="row">
      <div class="col-xl-4 col-lg-3 col-md-4 col-sm-2 col-2 m-1">
      <button class='btn btn-rounded btn-danger btn-sm' id='asignar_horario'><i class='bx bxs-calendar'></i></button>
      </div>

      <div class="col-xl-4 col-lg-3 col-md-4 col-sm-2 col-2 m-1">
      <button class="btn rounded btn-info btn-sm" id='programar_horario_atencion' ><i class='bx bx-list-check'></i></button>
      </div>
 
      </div>
      `}
    ],
    
  }).ajax.reload();

   /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
   TablaEspecialidadesMedico_.on( 'order.dt search.dt', function () {
    TablaEspecialidadesMedico_.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();
  /*======================================================================================*/
 
  asignar_Hoario_Medico('#tabla-medico-programacion-horarios__ tbody',TablaEspecialidadesMedico_);
  ProgramerHorariosMedico('#tabla-medico-programacion-horarios__ tbody',TablaEspecialidadesMedico_);
  OpenModalEspecialidadesMedico('#tabla-medico-programacion-horarios__ tbody',TablaEspecialidadesMedico_);
}

/// mostrar las especialidades del mèdico
function OpenModalEspecialidadesMedico(Tbody,Tabla)
{
  $(Tbody).on('click','#servicio_add',function(){

    /// abrimos el modal
    $('#modal_asignar_servicios_medico').modal('show')
   
    let fila = $(this).parents('tr');

    if(fila.hasClass('child'))
    {
      fila = fila.prev();
    }

    /// capturamos la data
    let Data = Tabla.row(fila).data();

    ID_MEDICO_SELECT = Data.id_medico;

    $('#medico_esp_').val(Data.apellidos+" "+Data.nombres);
    MostrarEspecialidadMedico(ID_MEDICO_SELECT);
  });
}

function MostrarEspecialidadMedico(medico_id)
{
  let Data_ = show_Personalice(RUTA+"especialidades_del_medico/"+medico_id+"?token_="+TOKEN);

  let tr = ''; let item = 0;

  Data_.especialidades.forEach(esp => {
    item++;
    tr+=`
    <tr>
    <td>`+item+`</td><td class='d-none'>`+esp.id_medico_esp+`</td><td>`+esp.nombre_esp.toUpperCase()+`</td>
    <td class="text-center">
    <input class="form-check-input" type="radio" id="seleccion" name='seleccion'>
    <button class='btn  btn-success btn-sm' style='border-radius:50%' onclick=viewServiceMedico(`+esp.id_medico_esp+`)><i class='bx bxs-binoculars'></i></button>
    </td>
    </tr>
    `;
  });

  $('.body_medico_esp_').html(tr);
}

/// programando la seleccion de la especialidad para agregarle los servicios
function escuchaEspecialidadSelect()
{
  $('.body_medico_esp_').on('click','#seleccion',function(){

    BajadaScroll('.modal-body,html', 400);

    let fila = $(this).closest('tr');

    if($(this).is(':checked'))
    {
      $('#div_medico_esp_').show(600);
      $('#servicio').focus();

      ID_MEDICO_ESPECIALIDAD = fila.find('td').eq(1).text();
    }
    
  });
}

function viewServiceMedico(id)
{
  ID_MEDICO_ESPECIALIDAD =id;
 Swal.fire({
  html:`
   <div class='table-responsive'>
   <table id="table_service_view"  class='table table-bordered table-sm nowrap'>
   <thead>
       <tr>
           <th>PROCEDIMIENTO</th>
           <th>GESTIONAR</th>
       </tr>
   </thead>
</table></div>

<div class='form-group srv' style='display:none'>
<label for='proc'><b>Procedimiento (*) </b></label>
<input type='text' id='proc' name='proc' class='form-control'>
</div>
<span class="text-success mensaje_procedim suces" style="display: none">Procedimiento modificado correctamente 😎</span>
<span class="text-danger mensaje_procedim error" style="display: none">Error al modificar el procedimiento ☹️ </span>
  `,
  target: document.getElementById("modal_asignar_servicios_medico")
 }).then(function(){
  $('.srv').hide();
  $('#proc').val("");
 });

 
 MostrarProcedimientosTabla(id)
 $('#proc').keypress(function(evento){
  if(evento.which == 13)
  {
    if($(this).val().trim().length == 0)
    {
      $(this).focus();
    }
    else
    {
      modificarProcedimientosMedico_(ID_SERVICE,$(this).val());
    }
  }
 });
}

function MostrarProcedimientosTabla(id)
{
  var MostrarTabla = $('#table_service_view').DataTable({
    responsive:true,
    retrieve:true,
    ajax:{
      url:RUTA+"show_procedimientos_medico/"+id+"?token_="+TOKEN,
      method:"GET",
      dataSrc:"response",
    },
    columns:[
      {"data":"name_servicio"},
      {"data":null,render:function(){return '<button class="btn btn-rounded btn-warning btn-sm" id="editar_proc"><i class="bx bxs-edit-alt"></i></button><button class="btn btn-rounded btn-danger btn-sm" id="delete_proc"><i class="bx bx-x"></i></button>';}}
    ],
     
   }).ajax.reload();
  
   editarProcedimiento(MostrarTabla,'#table_service_view tbody');
   ConfirmDeleteProcedimiento(MostrarTabla,'#table_service_view tbody')
}
function modificarProcedimientosMedico_(id,procedim)
{
  $.ajax({
    url:RUTA+"medico/update/procedimientos_por_especialidad/"+id,
    method:"POST",
    data:{token_:TOKEN,servicio:procedim},
    success:function(response)
    {
      response = JSON.parse(response);

      if(response.response == 1)
      {
        $('.suces').show(300);
        $('.error').hide();
        MostrarProcedimientosTabla(ID_MEDICO_ESPECIALIDAD);
      }
      else
      {
        $('.suces').hide();
        $('.error').show(300);
      }

    }
  });
}

 

function editarProcedimiento(Tabla,Tbody)
{
  $(Tbody).on('click','#editar_proc',function(){

    $('.srv').show(300);
    let Fila_ = $(this).parents('tr');

    if(Fila_.hasClass('child'))
    {
      Fila_ = Fila_.prev();
    }

    let Datas = Tabla.row(Fila_).data();

    ID_SERVICE = Datas.id_servicio;
     
    $('#proc').val(Datas.name_servicio);
    
    $('#proc').select();
  });
}

/// confirmar antes de eliminar el procedimiento asignado al  médico
function ConfirmDeleteProcedimiento(Tabla,Tbody)
{
  $(Tbody).on('click','#delete_proc',function(){

    /// capturamos la fila seleccionado
    let Fila = $(this).parents('tr');

    if(Fila.hasClass('child'))
    {
      Fila = Fila.prev();
    }

    /// capturamos la data de la fila seleccionado
    let data_ = Tabla.row(Fila).data();
    Swal.fire({
      title: 'Estas seguro?',
      text: "Al elimianr dicho procedimiento, automáticamente se quitará de la lista de procedimientos asignados al médico con respecto a su especialidad!",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, eliminar!',
      target: document.getElementById("modal_asignar_servicios_medico")
    }).then((result) => {
      if (result.isConfirmed) {
        
        EliminarProcedimientos(data_.id_servicio);
      }
    })
  });
}
/// eliminar procedimientos
function EliminarProcedimientos(id)
{
  $.ajax({
    url:RUTA+"procedimiento/"+id+"/delete",
    method:"POST",
    data:{token_:TOKEN},
    success:function(respuesta)
    {
      respuesta = JSON.parse(respuesta);
      
      if(respuesta.response === 'existe')
        {
          Swal.fire({
            title:'Mensaje del sistema',
            text:'No se puede eliminar dicho procedimiento',
            icon:'error',
            target: document.getElementById("modal_asignar_servicios_medico")
          })
        }
        else
        {
          if(respuesta.response == 1)
          {
            Swal.fire({
              title:'Mensaje del sistema',
              text:'Procedimiento eliminado correctamente 😁',
              icon:'success',
              target: document.getElementById("modal_asignar_servicios_medico")
            })
          }
          else
          {
            Swal.fire({
              title:'Mensaje del sistema',
              text:'Acaba de ocurrir un error al intentar eliminar el procedimiento',
              icon:'error',
              target: document.getElementById("modal_asignar_servicios_medico")
            })
          }
        }
    }
  });

 
}
/// asignar Horario a los médicos
function asignar_Hoario_Medico (Tbody,Tabla)
{
  $(Tbody).on('click','#asignar_horario',function(){

    $('#save').show();
    $('#lista_horario_medico__').empty();

    /// obtenemos la fila seleccioanda
    let Fila = $(this).parents('tr');

    /// evaluamos si la tabla tiene la clase child(table responsive)
    if(Fila.hasClass('child'))
    {
      Fila = Fila.prev();
    }

    /// obtenemos la data

    let Data = Tabla.row(Fila).data();

    ID_MEDICO_SELECT = Data.id_medico;
    MEDICO = Data.apellidos+"  "+Data.nombres;

    $('#medico_asignar').val(Data.apellidos+" "+Data.nombres);
    
    /// mostramos el formulario de asignar horarios
    showDiasAsignedHorarioMedico(ID_MEDICO_SELECT)
    $('.form_horario_asign_medico').show(500);
    $('#dia').focus();
    BajadaScroll('.modal-body,html', 300);


  });
}

function ProgramerHorariosMedico(Tbody,Tabla)
{
   $(Tbody).on('click','#programar_horario_atencion',function(){
    let fila_Seleccionada = $(this).parents('tr');

    if(fila_Seleccionada.hasClass('child'))
    {
      fila_Seleccionada = fila_Seleccionada.prev();
    }

    let Data = Tabla.row(fila_Seleccionada).data();

   
    
    let MEDICO = Data.apellidos+" , "+Data.nombres;
    ID_MEDICO_SELECT = Data.id_medico;
    showDaysProgrammerHorario(Data.id_medico);
    $('#model_programar_horario_medico').modal("show");

    $('#medico_ph').val(MEDICO)
   });
}

// mostrar horario respecto al día
function showHorario(Dia_semana)
{
  let response = show(RUTA+'mostrar-horario-por-dia?token_='+TOKEN,{dia:Dia_semana});
  
  $('#hora_inicial').val(response.horario_atencion_inicial); $('#hora_final').val(response.horario_atencion_cierre);

  $('#hora_final').focus();
}

/// Listar Horarios a cada médico
function listHourMedico(dia_,hora_inicio_,hora_fina_)
{
  var tr=`
   <tr>
   <td>`+dia_+`</td><td>`+hora_inicio_+`</td><td>`+hora_fina_+`</td>
   <td><button class='btn btn-rounded btn-danger btn-sm' id='quitar_horario_atencion'> <i class='bx bx-x'></i></button></td>
   </tr>
   `;

   $('#lista_horario_medico__').append(tr);

  
}

/// validar Existencia en Tabla
function ExisteDato(Dato)
{
    let Bandera = false;
    Tabla_Data = document.getElementById('lista_horario_medico__');

    for(let i=0; i<Tabla_Data.rows.length;i++)
    {
        if(Tabla_Data.rows[i].cells[0].innerHTML == Dato)
        {
           Bandera = true;
        }
    }

    return Bandera;
}

/// asignar horarios de atención médica
function asignarHorarioMedico(ta)
{
  let mensaje = null;
  $('#lista_horario_medico__ tr').each(function(){
    let fila = $(this).closest('tr');
    let Dia_ = fila.find('td').eq(0).text();
    let Hora_Inicial = fila.find('td').eq(1).text();
    let Hora_Final = fila.find('td').eq(2).text();

    $.ajax({
      url:RUTA+"medico/asignar-horario_atencio-medica",
      method:"POST",
      data:{"token_":TOKEN,dia:Dia_,medico:ID_MEDICO_SELECT,hi:Hora_Inicial,hf:Hora_Final,tiempo_atencion:ta},
      async:false,
      success:function (response) {

        response = JSON.parse(response);

        mensaje = response.response;
      },
    })
  });

  return mensaje;
}

/// mostramos los días 
function showDiasAsignedHorarioMedico(medico__)
{

  let option = '<option>--- Días de atención médica ----</option>';
  

  //let Respuesta = show(RUTA+"mostrar_dias/"+medico__);

  axios({
    method:"GET",
    url:RUTA+"mostrar_dias/"+medico__+"?token_="+TOKEN,
  }).then(function(response){
    
    response.data.forEach(dia_ => {
      option+=`
    <option value=`+dia_.dias_atencion+`>`+dia_.dias_atencion+`</option>
    `;
    });

    $('#dia').html(option)
  });
}

/// mostrar los dias de atención de cada médico para la programación de horarios
function showDaysProgrammerHorario(id)
{
  var TablaDiasHorariosMedico = $('#tabla_programacion_horario').DataTable({
    processing: true,
    responsive:true,
    bDestroy:true,
    autoWidth:true,
    language:SpanishDataTable(),
    "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
    //"order": [[0, 'asc']], /// enumera indice de las columnas de Datatable
    "ajax":{
      url:RUTA+"horarios_no_programados/"+id,
      method:"GET",
      dataSrc:"dias",
    },
    "columns":[
      {"data":"dia"},
      {"data":"id_atencion"},
      {"data":"dia",render:function(dta_){return '<span class="badge bg-warning">'+dta_+'</span>';}},
      {"data":"hora_inicio_atencion",render:function(dta_){return '<input type="time" id="hi" class="form-control" value='+dta_+'>';}},
      {"data":"hora_final_atencion",render:function(dta_){return '<input type="time" id="hf" class="form-control" value='+dta_+'>';}},
      {"data":"tiempo_atencion",render:function(dta_){return '<span class="badge bg-success" id="ta">'+dta_+'</span>';}},
      {"defaultContent":`
      <div class="row">
      <div class="col-xl-4 col-lg-3 col-md-4 col-sm-5 col-12 m-1">
      <button class='btn btn-rounded btn-danger btn-sm' id='programar_horario_cita'><i class='bx bxs-calendar'></i></button>
      </div>

      <div class="col-xl-4 col-lg-3 col-md-4 col-sm-5 col-12 m-1">
      <button class="btn rounded btn-info btn-sm" id='editar_horario_atencionmedica' ><i class='bx bxs-edit-alt'></i></button>
      </div>
 
      `},
    ],
    columnDefs:[
      { "sClass": "hide_me", target: 1 }
    ]

  }) 
   /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
   TablaDiasHorariosMedico.on( 'order.dt search.dt', function () {
    TablaDiasHorariosMedico.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();

  // ocultar la columna 2
 
  
  /*======================================================================================*/
  
}

/// programar los horarios de los médicos para su posible la reserva de atención médica
function ProgramarHorarioMedico(Tbody)
{
  $(Tbody).on('click','#programar_horario_cita',function(){

    let fila = $(this).closest('tr');

    if(fila.hasClass('child'))
    {
       fila = fila.prev();
    }
 
    ID_ATENCION_ = fila.find('td').eq(1).text();

    $('#form_ph').show(200);
    $('#modal_footer_program_horarios').show(200);
    $('.div_ph_').show(200);

    let hi__ = fila.find('#hi').val();
    let hf =  fila.find('#hf').val();
    let ta =  fila.find('#ta').text();
    //alert(hi__)
   $('#hora_in').val(hi__);
   $('#hora_fn').val(hf);
   $('#tiempo_atencion_paciente_').val(ta);
 
  });
}

/// Generar Horarios del médico
function generateHorario()
{
  $('#lista_generate_horario').empty();
  let tr ='';
  $.ajax({
    url:RUTA+"medico/generar_horarios?token_="+TOKEN,
    method:"GET",
    data:{hi:$('#hora_in').val(),hf:$('#hora_fn').val(),intervalo:$('#tiempo_atencion_paciente_').val()},
    success:function(response)
    {
      response = JSON.parse(response);

      response.response.forEach(Horario => {
        tr+=`<tr>
        <td>`+Horario.horario_inicial+`</td><td>`+Horario.horario_final+`</td>
        <td class='text-center'><button class="btn btn-rounded btn-danger btn-sm" id='quitar'><i class='bx bx-x'></i></button></td>
        </tr>`;
      });

      $('#lista_generate_horario').append(tr);
    }
  })
}
/// quitar de lista un horario generado
function deleteHorarioMedico()
{
  $('#lista_generate_horario').on('click','#quitar',function(){
    let fila_a_quitar = $(this).closest('tr');
    fila_a_quitar.remove();
  });
}
/// quitar un horario de atención del médico
function deleteHorarioAtencionMedico()
{
  $('#lista_horario_medico__').on('click','#quitar_horario_atencion',function(){
    let fila_a_quitar = $(this).closest('tr');
    fila_a_quitar.remove();
  });
}

/// guardamos la programación de horarios del médico seleccionado
function guardarProgramacionHorariosMedico(atencion_,horainit,horafin)
{
  let respuesta = null;
  $.ajax({
   url:RUTA+"medico/programacion_horarios",
   method:"POST",
   data:{token_:TOKEN,atencion:atencion_,hi:horainit,hf:horafin},
   async:false,
   success:function(response)
   {
     response = JSON.parse(response);

     respuesta = response.response;
   }
  });
  return respuesta;
}

/// enviar a la base de datos la programación de horarios del médico
function saveProgaramarHorarios()
{
  let Respuesta = null;
  $('#lista_generate_horario tr').each(function () {
    
    let Desde = $(this).find('td').eq(0).text();
    let Hasta = $(this).find('td').eq(1).text();
    Respuesta = guardarProgramacionHorariosMedico(ID_ATENCION_,Desde,Hasta);
  })

  /// Mostramos la respuesta

  if(Respuesta)
  {
    
    Swal.fire({
      title: 'Mensaje del sistema',
      text:'La programación de horarios para el médico a sido registrado correctamente',
      icon:'success',
      target: document.getElementById('model_programar_horario_medico')
  }).then(function(){

    showDaysProgrammerHorario(ID_MEDICO_SELECT)
    $('#form_ph').hide(200);
    $('#modal_footer_program_horarios').hide(200);
    $('.div_ph_').hide(200);
    $('#lista_generate_horario').empty();
    $('#save_ph__').hide();
    //$('#loading_program_horario').hide(50);
  });
   // MessageRedirectAjax('success','Mensaje del sistema','guardado','model_programar_horario_medico')
  }else
  {
    Swal.fire({
      title:'Mensaje del sistema',
      text:'Error al guardar la programación de horarios',
      icon:'error'
    })
  }
}

/// Añadir a la lista los procedimiento de cada especialidad asignado al médico
function addProcedimiento(procedimiento)
{
  if(!existeProcedimiento(procedimiento))
  {   
  let tr = '';
   CONTAR++;
  tr+=`
  <tr>
  <td>`+(CONTAR)+`</td>
  <td>`+procedimiento+`</td>
  <td>
  <button class='btn btn-rounded btn-danger btn-sm quitar'><i class='bx bx-x'></i></button>
  </td>
  </tr>
  `;
  $('.body_procedimiento_').append(tr);
  ordenar();
  }
  else{
    Swal.fire({
      title:"¡ADVERTENCIA!",
      text:"El procedimiento "+procedimiento+" ya esta agregado a la lista",
      icon:"warning",
      target: document.getElementById("modal_asignar_servicios_medico")
    }).then(function(){
      $('#servicio').val("");
      $('#servicio').focus();
    });
  }
 
}

/// quitar de lista los procedimiento de cada especialidad asignado al médico
function deleteProcedimiento()
{
  $('.body_procedimiento_').on('click','.quitar',function(){
    
    let fila = $(this).closest('tr');

    fila.remove();
    ordenar();
  });
}

function existeProcedimiento(procedim)
{
  let bandera = false;
  $('.body_procedimiento_ tr').each(function(){
   
     if($(this).find('td').eq(1).text() === procedim)
     {
       bandera = true;
     }
 
  });

  return bandera;
}

function ordenar()
{
  var num = 1;
  $('.body_procedimiento_ tr').each(function(){
    $(this).find('td').eq(0).text(num);
    num++;
  });
}

///verificar si dicho procedimiento, ya existe en la especialidad del médico
function VerificarProdimientoExistencia(medico_especialidad,procedim)
{
  let respuesta =  show(RUTA+"verificar_existencia_de_procedimiento_medico/"+medico_especialidad+"/"+procedim+"?token_="+TOKEN);

 return respuesta;
}
/// guadar procdimientos a la especialidad del médico
function saveProcedimientosMedico()
{
  let resp = null;
   $('.body_procedimiento_ tr').each(function(){
    let servicio_ = $(this).find('td').eq(1).text();
    $.ajax({
      url:RUTA+"medico/save/procedimientos_por_especialidad",
      method:"POST",
      data:{token_:TOKEN,servicio:servicio_,medico_esp:ID_MEDICO_ESPECIALIDAD},
      async:false,
      success:function(respuesta)
      {
        respuesta = JSON.parse(respuesta);
        resp = respuesta.response;
      }
     });
   });
   return resp;
}

/// actualizar datos del médico
async function modificarDatosDelPaciente(documento_,apellidos_,nombre_,genero_,direccion_,fechanac_,tipodoc_,distrito_,telefono_,universidad_,experiencia_,personadata,medicodata,
  cmp,sedeupdate
)
{
  /// enviamos los datos en un formData
  data = new FormData();
  data.append('token_',TOKEN);data.append('doc',documento_.val()); data.append('apellidos',apellidos_.val());
  data.append('nombres',nombre_.val()); data.append('genero',genero_.val()); 
  data.append('direccion',direccion_.val());data.append('fecha_nac',fechanac_.val());
  data.append('tipo_doc',tipodoc_.val());data.append('distrito',distrito_.val());
  data.append('telefono',telefono_.val());data.append('universidad',universidad_.val());data.append('experiencia',experiencia_.val());
  data.append("cmp",cmp.val());data.append("sedeeditar",sedeupdate.val());
  const  resp = await axios.post(RUTA+'medico/'+personadata+'/'+medicodata+'/update',data,function(){});

  if(resp.data.response === 'success')
  {
    Swal.fire(
      {
        title:'Mensaje del sistema!',
        text:'Datos del médico modificados correctamente',
        icon:'success',
        target:document.getElementById('modal_editar_medico')
      }
    ).then(function(){
      //$('#modal_editar_medico').modal('hide');
      ShowMedicos();
    })
  }else
  {
    Swal.fire(
      {
        title:'Mensaje del sistema!',
        text:'Ocurrió un error al intentar actualizar los datos del médico',
        icon:'error',
        target:document.getElementById('modal_editar_medico')
      }
    )
  }
}

/// ELIMINAR LA ESPECIALIDAD SELECCIONADO DEL MEDICO
function DeleteEspecialidadMedico(id){
  let DeleteFormEspecialidadMedico = new FormData();
  DeleteFormEspecialidadMedico.append("token_",TOKEN);
  axios({
    url:RUTA+"medico/especialidad/delete/"+id,
    method:"POST",
    data:DeleteFormEspecialidadMedico
  }).then(function(response){
     if(response.data.response === 'ok'){
       Swal.fire({
        title:"CORRECTO!!!",
        text:"ESPECIALIDAD DEL MEDICO QUITADO CORRECTAMENTE!!",
        icon:"success",
        target:document.getElementById('modal_view_especialidades_medico')
       }).then(function(){
         mostrarTodaEspecialidadAsignadoAlMedico(ID_MEDICO_SELECT);
       });
     }else{
       Swal.fire({
        title:"ERROR DEL SISTEMA!!!",
        text:"ERROR AL ELIMINAR LA ESPECIALIDAD SELECCIONADO DEL MEDICO!!",
        icon:"error",
        target:document.getElementById('modal_view_especialidades_medico')
       })
     }
  });
}



