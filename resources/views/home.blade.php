@extends($this->Layouts("plantilla_home"))

@section("title_home",isset($this->BusinesData()[0]->nombre_empresa) ? $this->BusinesData()[0]->nombre_empresa:'clinica-online')


@section('contenido')
  
  @include($this->getComponents("home.header"))

  @include($this->getComponents("home.section"))

  @include($this->getComponents("home.main"))

  @include($this->getComponents("home.footer"))
@endsection

@section('js')
<script src="{{ URL_BASE }}public/js/control.js"></script>
<script>
var RUTA = "{{ URL_BASE }}" // la url base del sistema
$(document).ready(function(){

 /*** Variables del formulario*/ 
 let TipoDocumento = $('#tipodoc'),Documento = $('#documento'),NamePersona = $('#name'),
     Email = $('#email'),Phone = $('#phone'),Fecha = $('#fecha_solicitud'),
     Especialidad = $('#especialidad'),Doctor = $('#doctor');
     Sede = $('#sede');
  /// guardar la solicitud de la cita
  $('#save_solicitud').click(function(ev){
    ev.preventDefault();
    if(Sede.val() == null){
      Sede.focus();
      Swal.fire({
        title:"MENSAJE DEL SISTEMA!!",
        text:"SELECCIONE UNA SEDE EN LO CUAL DESEA SOLICITAR UNA CITA MEDICA!!",
        icon:"info"
      });
    }else{
      /// Validamos antes de procesar la solicitud
    if(Documento.val().trim().length == 0)
    {
      Documento.focus();
    }
    else{
      if(NamePersona.val().trim().length == 0)
      {
        NamePersona.focus();
      }else{
        if(Phone.val().trim().length == 0)
        {
          Phone.focus();
        }else{
          if(Especialidad.val() == null)
          {
            Especialidad.focus();
            Swal.fire({
              title:"Mensaje del sistema!",
              text:"Antes de procesar su solicitud, debe de seleccionar una especialidad!",
              icon:"warning"
            }).then(function(){
              Especialidad.focus();
            });
          }else{
            SaveProcessSolicitud();
          }
        }
      }
    }
    }
    
  });
});

$('#envioemail').click(function(evento){
evento.preventDefault();
let NameRemitente = $('#name_contacto');
let EmailRemitente = $('#email_contacto');
let Asunto = $('#subject');
let Mensaje = $('#message_contacto');

if(NameRemitente.val().trim().length == 0)
{
 NameRemitente.focus();
}else{
  if(EmailRemitente.val().trim().length == 0)
  {
    EmailRemitente.focus();
  }else{
    if(Asunto.val().trim().length == 0)
    {
      Asunto.focus();
    }else{
      if(Mensaje.val().trim().length == 0)
      {
       Mensaje.focus();
      }else{
        loading('#div_contacto','#4169E1','chasingDots') 
       
       $.ajax({
        url:RUTA+"contact/clinica",
        method:"POST",
        data:$('#form_contacto').serialize(),
        dataType:"json",
        success:function(response)
        {
           
          setTimeout(() => {
            $('#div_contacto').loadingModal('hide');
            $('#div_contacto').loadingModal('destroy');
            if(response.response == 1)
            {
            $('#mensaje_contacto_success').show(500);
            $('#mensaje_contacto_erro').hide();
            $('#form_contacto')[0].reset();
            }else{
            $('#mensaje_contacto_success').hide();
            $('#mensaje_contacto_erro').show(500);
          }
          }, 1000);
        }
       })
      }
    }
  }
}

});
/// mostrar los médicos acorde a la especialidad seleccionado
$('#especialidad').change(function(){
 let IdEspe = $(this).val();
 
 mostrarDoctores(IdEspe,$('#sede'));
});
$('#sede').change(function(){
  $('#especialidad').prop("selectedIndex",0);
  $('#doctor').empty();
});

 function mostrarDoctores(id,sedeData)
 {
  
  let option = '';
  $.ajax({
    url:RUTA+"medicos-por-especialidad-home/"+id+"?sede="+sedeData.val(),
    method:"GET",
    dataType:"json",
    success:function(response){
     
      if(response.response.length > 0)
      {
        response.response.forEach(doctor => {
          option+=`<option value=`+doctor.id_medico+`>`+doctor.doctor.toUpperCase()+`</option>`;
        });
      }
       
      $('#doctor').html(option);
    }
  });
 }

 /// procesar la solicitud
 function SaveProcessSolicitud()
 {
  let respuesta = crud(RUTA+"notificaciones/save",'form_save_notificaciones');

  if(respuesta == 1)
  {
    Swal.fire({
      title:"Mensaje del sistema!",
      text:"Su solicitud a sido procesado correctamente, en breve uno de nuestro equipo se comunicará con usted!",
      icon:"success"
    }).then(function(){
      $('#form_save_notificaciones')[0].reset(); $('#doctor option').remove();
    });
  }else{
    if(respuesta === 'existe')
    {
      Swal.fire({
      title:"Mensaje del sistema!",
      text:"Lo sentimos , pero uste tiene una solicitud en proceso para el día de hoy!",
      icon:"warning"
    }).then(function(){
      $('#form_save_notificaciones')[0].reset();
      $('#doctor option').remove();
    });
    }else{
      Swal.fire({
      title:"Mensaje del sistema!",
      text:"Lo sentimos , pero acaba de ocurrir un error inesperado, vuelva intentar más tarde!",
      icon:"error"
    });
    }
  }
 }
 
</script>
@endsection
 
 