function FocusInputModal(idModal,inputFocus)
{
    /// abrimos el modal 
    $('#'+idModal).modal("show")
    $("#"+idModal).on('shown.bs.modal', function(){
        $("#"+inputFocus).focus();
        
    });
}

function Message(icono,titulo,mensaje,modal)
{
    Swal.fire({
        title: titulo,
        text:mensaje,
        icon:icono,
        target: document.getElementById(modal)
    }).then(function(){
     location.href=URL_BASE_+"tipo-documentos-existentes";
    });
}

function MessageRedirectAjax(icono,titulo,mensaje,modal)
{
    Swal.fire({
        title: titulo,
        text:mensaje,
        icon:icono,
        target: document.getElementById(modal)
    }) 
}

 
/// crud
function crud(ruta,form)
{
    let respuesta  = null;
    $.ajax({
        url:ruta,
        method:"POST",
        data:$('#'+form).serialize(),
        async:false,
        success:function(response)
        {
            response = JSON.parse(response);
            respuesta = response.response;
        },err:function(error)
        {
            respuesta = 'error';
        }
    })
    return respuesta;
}

/// consultar

function show(ruta,data_={})
{
    let respuesta  = null;
    $.ajax({
        url:ruta,
        method:"GET",
        data:data_,
        async:false,
        success:function(response)
        {
            response = JSON.parse(response);
            respuesta = response.response;
        }
    })
    return respuesta;
}

/** Método que agregar un cero a mes , caso que sea <10 */
function addCeroMonthDate(mes)
{
    mesText = '';
    if(mes <10)
    {
        mesText+='0'+mes;
    }

    return mesText;
}

function show_Personalice(ruta,data_={})
{
    let respuesta  = null;
    $.ajax({
        url:ruta,
        method:"GET",
        data:data_,
        async:false,
        success:function(response)
        {
            respuesta = JSON.parse(response);
        }
    })
    return respuesta;
}

/// pasar enter
function enter(id_input,pasarInput)
{
   $('#'+id_input).keypress(function(evento){

    /// detectamos al dar enter
    if(evento.which == 13)
    {
        evento.preventDefault();
        
        if($(this).val().trim().length == 0)
        {
            $(this).focus();
        }
        else
        {
            $('#'+pasarInput).focus();
        }
    }
   });
}
/// subir scroll hacia arriba
function subidaScroll(seleccion,tiempo)
{
    $(seleccion).animate(
        {
            scrollTop: '0px'
        },
        tiempo /// tiempo que dura la animacion
    )

}
function BajadaScroll(seleccion,tiempo)
{
    $(seleccion).animate(
        {
            scrollTop: '1770px'
        },
        tiempo /// tiempo que dura la animacion
    )

}
/// método para leer una imagen(Validando el tipo de archivo)
function ReadImagen(inputFile,imagenId,dataImagen)
{
    /// obtenemos el path del archivo seleccionado
    let FilePath = $(inputFile).val();
    /// le indicamos que solo aceptará las extensiones de tipo imagen => png y jpg
    var Extensiones = /(.png|jpg|ico)$/; /// especificamos las extensiones que solo se aceptará

    /// validamos si el archivo seleccionado es correcto
    if(!Extensiones.exec(FilePath))
    {
        MessageRedirectAjax('warning'
                           ,'¡ADVERTENCIA DEL SISTEMA!',
                           'El archivo que seleccionaste es incorrecto, solo se aceptan imágenes tipo [JPG-PNG]',
                           'modal-crear-docente') 
    }
    else
    {
        $('#'+imagenId).attr('src', URL.createObjectURL(dataImagen));/// realiza un preview a la imagen
    }
}

function ReadImagenTwo(inputFile,imagenId,dataImagen,modal)
{
    /// obtenemos el path del archivo seleccionado
    let FilePath = $(inputFile).val();
    /// le indicamos que solo aceptará las extensiones de tipo imagen => png y jpg
    var Extensiones = /(.png|jpg|ico)$/; /// especificamos las extensiones que solo se aceptará

    /// validamos si el archivo seleccionado es correcto
    if(!Extensiones.exec(FilePath))
    {
        MessageRedirectAjax('warning'
                           ,'¡ADVERTENCIA DEL SISTEMA!',
                           'El archivo que seleccionaste es incorrecto, solo se aceptan imágenes tipo [JPG-PNG]',
                           modal) 
    }
    else
    {
        $('#'+imagenId).attr('src', URL.createObjectURL(dataImagen));/// realiza un preview a la imagen
    }
}

function ReadImagenTree(inputFile,imagenId,dataImagen,modal)
{
    /// obtenemos el path del archivo seleccionado
    let FilePath = $(inputFile).val();
    /// le indicamos que solo aceptará las extensiones de tipo imagen => png y jpg
    var Extensiones = /(.jpg)$/; /// especificamos las extensiones que solo se aceptará

    /// validamos si el archivo seleccionado es correcto
    if(!Extensiones.exec(FilePath))
    {
        MessageRedirectAjax('warning'
                           ,'¡ADVERTENCIA DEL SISTEMA!',
                           'El archivo que seleccionaste es incorrecto, solo se aceptan imágenes tipo [JPG]',
                           modal) 
    }
    else
    {
        $('#'+imagenId).attr('src', URL.createObjectURL(dataImagen));/// realiza un preview a la imagen
    }
}

/// validar de que por lo menos exista un seleccionado => Caso para checkbox en Table html
function ContarEspecialidadSeleccionado(Tbody)
{
  let Contador = 0;
  $(Tbody+' input[type=checkbox]').each(function(){
    
    /// obtenemos las filas

    if($(this).is(':checked'))
    {
      Contador++;
    }
   
  });

  return Contador;
}

/// Traducir al español el Data Table
function SpanishDataTable()
{
    return  {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    };
}

/// devolver el día de la fecha actual
function getDayFechaActual()
{
    const fecha = new Date();

    let dia_number = fecha.getDate();
 
}

///loading
function loading(elemento,color,animacion) {
    
    $(elemento).loadingModal({
        position:'auto',
        
        text:'',
        
        color:color,
        
        opacity:'0.3',
        
        backgroundColor:'rgb(255, 250, 240)',
        
        animation:animacion
        
        });
  }

/** CALCULAR LA EDAD DEL PACIENTE EN AÑOS, MESES Y DIAS */
function calcularEdad(fechaNacimiento, fechaActual) {
    const nacimiento = new Date(fechaNacimiento);
    const actual = new Date(fechaActual);
  
    let años = actual.getFullYear() - nacimiento.getFullYear();
    let meses = actual.getMonth() - nacimiento.getMonth();
    let días = actual.getDate() - nacimiento.getDate();
  
    if (días < 0) {
      meses--;
      const diasMesAnterior = new Date(actual.getFullYear(), actual.getMonth(), 0).getDate();
      días = diasMesAnterior + días;
    }
  
    if (meses < 0) {
      años--;
      meses = 12 + meses;
    }
  
    return {
      años,
      meses,
      días
    };
  }



