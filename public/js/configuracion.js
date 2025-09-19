/// listar los horarios
 
function listarHorarios(Dias,HoraInicio,HoraFinal,almacenamiento=[])
{

    /// Validamos antes de listar
    if(VerifyBeforeList(Dias) !== 'existe'){
    if(!ValidaDuplicado(Dias,almacenamiento))
    {
        $('.lista').show(550);

        $('#store_atencion').show(550);

        $('#dias_atencion').val("Lunes");

        $('#dias_atencion').focus();

        almacenamiento.push({"Dias":Dias,"Hora_Inicial":HoraInicio,"Hora_Final":HoraFinal});

        mostrarHorariosListados(almacenamiento);
    }
    else
    {
        MessageRedirectAjax('warning','¡ADVERTENCIA!','El Horario que deseas asignar ya existe','body_');
    }
  }
  else{
    $('#dias_atencion').val("Lunes");

    $('#dias_atencion').focus();
    MessageRedirectAjax('warning','Mensaje del sistema','No se puede agregar este horario,porque ya existe','body_');
  }
}

/// verificamos de que no exista un horario duplicado

function ValidaDuplicado(Data,array)
{
   
    for(let i=0;i<array.length;i++)
    {
        if(array[i].Dias === Data)
        {
            return true;
        }
    }
    return false;
}

function mostrarHorariosListados(arrayHorario=[])
{
    let tr = '';
   arrayHorario.forEach((horario,index) => {
    tr+=`
    <tr>
    <td>`+horario.Dias+`</td>
    <td>`+horario.Hora_Inicial+`</td>
    <td>`+horario.Hora_Final+`</td>
    <td>
    <button class='btn btn-rounded btn-danger btn-sm' onclick=quitarHorarioDeLista(`+index+`) ><i class='bx bxs-message-square-minus'></i></button>
    </td>
    </tr>
    `;
   });

   $('#lista-horarios').html(tr)
}

/// quitar Horarios  de lista

function quitarHorarioDeLista(index)
{
   Horarios.splice(index,1); /// eliminamos el índice del array

   mostrarHorariosListados(Horarios);/// volvemos a mostrar

 
   if(Horarios.length == 0)
   {
    $('.lista').hide(550);

    $('#store_atencion').hide(550)
   }

   return false;
}

/// Registrar Horarios de atención de EsSalud
function storeHorarioEsSalud()
{
   let Message__ = null;

   Horarios.forEach((horario)=> {
    
    Message__ = $.ajax({
        url:RUTA+"store-horario-essalud",
        method:"POST",
        data:{token_:TOKEN,dias:horario.Dias,hi:horario.Hora_Inicial,hf:horario.Hora_Final},
        async:false,
        sucess:function(response)
        {
            response = JSON.parse(response);

            return response.response;
        }
    });
   });

 return Message__.status;
}

/// Validamos antes de listar los horarios 
function VerifyBeforeList(dia_)
{
    let response = show(RUTA+'verificar-horario-before-list?token_='+TOKEN,{dia:dia_});
    
    return response;
}