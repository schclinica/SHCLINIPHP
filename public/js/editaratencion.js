/** EDITAR LA ATENCION MEDICA */
function EditarAtencionMedica(Tabla,Tbody){
  $(Tbody).on('click','#editar_atencion',function(){
    let fila = $(this).parents("tr");
    

    if(fila.hasClass("child")){
        fila = fila.prev();
    }

    ATENCIONEDITARID =fila.find('td').eq(6).text();
     EditarAtencionMedicaData(ATENCIONEDITARID);
    $('#modal_editar_atencion_medica').modal("show");
  });
}

$('#importe_pagar_cita_medica_editar').keyup(function(){
    let ImporteParaMedicoEdicion = $(this).val() * (MONTOPORCENTAJEMEDICOEDITAR/100); 
    let ImporteParaClinicaEdicion = $(this).val() * (MONTOCLINICAPORCENTAJEEDITAR/100); 

    $('#importe_pagar_monto_medico_editar').val(ImporteParaMedicoEdicion.toFixed(2));
    $('#importe_pagar_monto_clinica_editar').val(ImporteParaClinicaEdicion.toFixed(2));
});


/// cerrar ventana de editar atención medica
$('#cerrar_atencion_medica').click(function(){
    $('#modal_editar_atencion_medica').modal("hide");
});

/** EDITAR LA ATENCION MEDICA */
function EditarAtencionMedicaData(id){
    axios({
        url:RUTA+"editar-atencion-medica?v="+id,
        method:"GET",
    }).then(function(response){
         
        if(response.data.historia != undefined){

            const fechaNacimiento = response.data.historia[0].fecha_nacimiento;
            const fechaActual = new Date().toISOString().split("T")[0];

            const edad = calcularEdad(fechaNacimiento, fechaActual);

            $('#expediente_editar').val(response.data.historia[0].num_expediente);
            $('#fecha_atencion_editar').val(response.data.historia[0].fecha_atencion);
            $('#num_doc_editar').val(response.data.historia[0].documento);
            $('#paciente_editar').val(response.data.historia[0].pacientedata);
            $('#fecha_nac_editar').val(response.data.historia[0].fecha_nacimiento);
            $('#edad_editar').val(`${edad.años} años, ${edad.meses} meses y ${edad.días} días`);
            $('#consultorio_editar').val(response.data.historia[0].especialidadata.toUpperCase());
            $('#medico_editar').val(response.data.historia[0].medicodata.toUpperCase());
            $('#servicio_editar').val(response.data.historia[0].name_servicio!= null ? response.data.historia[0].name_servicio.toUpperCase() : "CONSULTA GENERAL");
            $('#temperatura_editar').val(response.data.historia[0].temperatura != null ? response.data.historia[0].temperatura.toUpperCase() : '________________________');

            $('#frecuencia_cardiaca_editar').val(response.data.historia[0].frecuencia_cardiaca != null ? response.data.historia[0].frecuencia_cardiaca.toUpperCase() : '________________________');
            $('#frecuencia_respiratoria_editar').val(response.data.historia[0].frecuencia_respiratoria != null ? response.data.historia[0].frecuencia_respiratoria.toUpperCase() : '________________________');
        

            $('#saturacion_oxigeno_editar').val(response.data.historia[0].saturacion_oxigeno != null ? response.data.historia[0].saturacion_oxigeno.toUpperCase() : '________________________');
            $('#talla_editar').val(response.data.historia[0].talla != null ? response.data.historia[0].talla.toUpperCase() : '________________________');
            $('#peso_editar').val(response.data.historia[0].peso != null ? response.data.historia[0].peso.toUpperCase() : '________________________');
            $('#imc_editar').val(response.data.historia[0].imc != null ? response.data.historia[0].imc : '________________________');
            $('#estado_imc_editar').val(response.data.historia[0].estado_imc != null ? response.data.historia[0].estado_imc : '________________________');
            $('#motivo_consulta_editar').val(response.data.historia[0].observacion);
            $('#antecedentes_editar').val(response.data.historia[0].antecedentes);
            $('#tiempo_editar').val(response.data.historia[0].tiempo_enfermedad);
            $('#antecedente_familiares_editar').val(response.data.historia[0].atecendentes_fam);
            $('#diabetes_editar').val(response.data.historia[0].diabetes);
            $('#hipertension_editar').val(response.data.historia[0].hipertencion_arterial);


            $('#transfusiones_editar').val(response.data.historia[0].transfusiones);
            $('#cirujias_previas_editar').val(response.data.historia[0].cirujias_previas);


            $('#medicamentos_actuales_editar').val(response.data.historia[0].medicamento_actuales);
            $('#alergias_editar').val(response.data.historia[0].alergias);
            $('#opcional_habito_editar').val(response.data.historia[0].otros);

            $('#vacunas_editar').val(response.data.historia[0].vacunas_completos);
            $('#examen_fisico_editar').val(response.data.historia[0].resultado_examen_fisico);
            $('#procedimiento_editar').val(response.data.historia[0].procedimiento);
            $('#diagnostico_general_editar').val(response.data.historia[0].diagnostico_general);

            $('#tiempo_tratamiento_editar').val(response.data.historia[0].tiempo_tratamiento);
            $('#tratamiento_editar').val(response.data.historia[0].desc_plan);
            $('#fecha_proxima_cita_editar').val(response.data.historia[0].proxima_cita);
            $('#importe_pagar_cita_medica_editar').val(response.data.historia[0].monto_pago);

            $('#importe_pagar_monto_medico_editar').val(response.data.historia[0].monto_medico);
            $('#importe_pagar_monto_clinica_editar').val(response.data.historia[0].monto_clinica);

            if(response.data.historia[0].genero === "2"){
                $('#divgestanteeditar').show();
                $('#edadgestacionaldiveditar').show();
                $('#fechadivpartoeditar').show();
                if(response.data.historia[0].gestante === "si"){
                    $('#gestanteeditar').prop("checked",true);
                    $('#edad_gestacionaleditar').val(response.data.historia[0].edad_gestacional);
                    $('#fecha_partoeditar').val(response.data.historia[0].fecha_parto);
                }else{
                    $('#gestanteeditar').prop("checked",false);
                    $('#edad_gestacionaleditar').val("");
                    $('#fecha_partoeditar').val(FECHAACTUALS);
                     $('#edadgestacionaldiveditar').hide();
                     $('#fechadivpartoeditar').hide();
                }
            }else{
                $('#divgestanteeditar').hide();
                $('#edadgestacionaldiveditar').hide();
                $('#fechadivpartoeditar').hide();
                $('#gestanteeditar').prop("checked",false);
                $('#edad_gestacionaleditar').val("");
                $('#fecha_partoeditar').val(FECHAACTUALS);
                $('#edadgestacionaldiveditar').hide();
                     $('#fechadivpartoeditar').hide();
            }
            MONTOPORCENTAJEMEDICOEDITAR = response.data.historia[0].precio_medico;
            MONTOCLINICAPORCENTAJEEDITAR = response.data.historia[0].precio_clinica;
            showDiagnosticosAsignadosEditionPaciente();
            

        }
    });
}
 

/// GUARDAR LOS CAMBIOS DE LA ATENCION MEDICA
$('#update_atencion_medica').click(function(){

    let EsGestante = 'no';let EdadGestacionalEditar = null;let FechaPartoEditar = null;
    if($('#gestanteeditar').is(":checked")){
      EsGestante = 'si';
      EdadGestacionalEditar = $('#edad_gestacionaleditar').val();
      FechaPartoEditar = $('#fecha_partoeditar').val();
    }else{
        EsGestante = 'no';
        EdadGestacionalEditar = null; FechaPartoEditar = null;
    }
    let FormAtencionModificar = new FormData();
    FormAtencionModificar.append("token_",TOKEN);
    FormAtencionModificar.append("antecedentes",$('#antecedentes_editar').val());
    FormAtencionModificar.append("tiempo_enfermedad",$('#tiempo_editar').val());

    FormAtencionModificar.append("alergias",$('#alergias_editar').val());
    FormAtencionModificar.append("otros",$('#opcional_habito_editar').val());
    FormAtencionModificar.append("vacunas_completos",$('#vacunas_editar').val());

    FormAtencionModificar.append("procedimiento",$('#procedimiento_editar').val());
    FormAtencionModificar.append("resultado_examen_fisico",$('#examen_fisico_editar').val());
    FormAtencionModificar.append("diagnostico_general",$('#diagnostico_general_editar').val());

    FormAtencionModificar.append("desc_plan",$('#tratamiento_editar').val());
    FormAtencionModificar.append("tiempo_tratamiento",$('#tiempo_tratamiento_editar').val());
    FormAtencionModificar.append("fecha_atencion",$('#fecha_atencion_editar').val());

    FormAtencionModificar.append("proxima_cita",$('#fecha_proxima_cita_editar').val());
    FormAtencionModificar.append("diabetes",$('#diabetes_editar').val());
    FormAtencionModificar.append("hipertencion_arterial",$('#hipertension_editar').val());


    FormAtencionModificar.append("transfusiones",$('#transfusiones_editar').val());
    FormAtencionModificar.append("cirujias_previas",$('#cirujias_previas_editar').val());
    FormAtencionModificar.append("medicamento_actuales",$('#medicamentos_actuales_editar').val());
    FormAtencionModificar.append("atecendentes_fam",$('#antecedente_familiares_editar').val());

    FormAtencionModificar.append("monto_pago",$('#importe_pagar_cita_medica_editar').val());
    FormAtencionModificar.append("monto_medico",$('#importe_pagar_monto_medico_editar').val());
    FormAtencionModificar.append("monto_clinica",$('#importe_pagar_monto_clinica_editar').val());
    FormAtencionModificar.append("motivo_consulta_editar",$('#motivo_consulta_editar').val());
    FormAtencionModificar.append("gestante",EsGestante);FormAtencionModificar.append("edad_gestacional",EdadGestacionalEditar);
    FormAtencionModificar.append("fecha_parto",FechaPartoEditar);
    axios({
        url:RUTA+"atencion-medica/"+ATENCIONEDITARID+"/update",
        method:"POST",
        data:FormAtencionModificar
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById("modal_editar_atencion_medica")
            });
        }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.response,
                icon:"success",
                target:document.getElementById("modal_editar_atencion_medica")
            })
        }
    })
});


/// VER MI HISTORIAL
$('#show_historial_medico').click(function(){
 window.open(RUTA+"paciente/historial?v="+ATENCIONEDITARID,"_blank");
});


function showDiagnosticosAsignadosEditionPaciente(){
    let tr = '';
    $.ajax({
        url:RUTA+"diagnostios/data/edition",
        method:"GET",
        dataType:"json",
        success:function(response){
            response = Object.values(response.diagnosticosedicion);
            if(response.length > 0){
                response.forEach(diag => {
                tr+=`<tr>
                     <td class='text-center'><button class='btn btn-outline-danger btn-sm' id='quitar_diagnostico' onclick="ConfirmEliminadoDiagnosticoEdicion('`+diag.enfermedad_id+`','`+diag.enfermedad_desc.toUpperCase()+`')">X</button></td>
                     <td class="d-none">`+diag.enfermedad_id+`</td>
                     <td>`+diag.enfermedad_desc.toUpperCase()+`</td>
                     <td>
                        <select class='form-select' id='tipo_editar'>
                        <option value='r' `+(diag.tipo_desc==='r' ? 'selected' : '')+`>REPETITIVO</option>
                        <option value='p' `+(diag.tipo_desc==='p' ? 'selected' : '')+`>PRESUNTIVO</option>
                        <option value='d' `+(diag.tipo_desc==='d' ? 'selected' : '')+`>DEFINITIVO</option>
                        </select>
                        </td>
                    </tr>`;
            });
         }else{
            tr = '<td colspan="3"><span class="text-danger px-2 py-3">No hay diagnósticos agregados.....</span></td>';
         }
         $('#lista_diagnostico_paciente_editar').html(tr);
        }
    })
 }


 //// CAMBIAR EL TIPO DE DIAGNOSTICO
 $('#lista_diagnostico_paciente_editar').on('change','#tipo_editar',function(){

    let fila = $(this).parents("tr");

    let IdDiagnostico = fila.find('td').eq(1).text();

    let TipoDiagnosticoEdicion = $(this).val();

    let FormModifyTipo = new FormData();
    FormModifyTipo.append("token_",TOKEN);
    FormModifyTipo.append("new_tipo",TipoDiagnosticoEdicion);

    axios({
        url:RUTA+"modificar-tipo_diagnostico-del-paciente/"+IdDiagnostico+"/update",
        method:"POST",
        data:FormModifyTipo
    }).then(function(response){
        console.log(response.data);
    });
 });


 ///VER LA LISTA DE ENFERMEDADES PARA VOLVER ASIGNAR

 $('#add_diagnostico_editar').click(function(){
   $('#modal_view_enfermedades_editar').modal("show");

 });



 /// CONFIRMAR ANTES DE QUITAR DE LA LISTA
 function ConfirmEliminadoDiagnosticoEdicion(id,diagnosticoTextEdition){
    Swal.fire({
        title: "ESTAS SEGURO?",
        text: "DESEAS QUITAR AL DIAGNOSTICO "+diagnosticoTextEdition+" DE LISTA DE DIAGNOSTICOS DEL PACIENTE?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Quitar!",
        target:document.getElementById('modal_editar_atencion_medica')
      }).then((result) => {
        if (result.isConfirmed) {
          QuitarDiagnosticoListaEdition(id);
        }
      });
 }

 /// QUITAR DE LA LISTA AL DIAGNOSTICO
 function QuitarDiagnosticoListaEdition(id){
    $.ajax({
        url:RUTA+"quitar-de-la-lista-de-diagnosticos-del-paciente/"+id,
        method:"POST",
        data:{token_:TOKEN},
        dataType:"json",
        success:function(response){
            if(response.error != undefined){
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!!",
                    text:response.error,
                    icon:"error",
                    target:document.getElementById('modal_editar_atencion_medica')
                });
            }else{
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!!",
                    text:response.response,
                    icon:"success",
                    target:document.getElementById('modal_editar_atencion_medica')
                }).then(function(){
                    showDiagnosticosAsignadosEditionPaciente();
                });
            }
        }
    })
 }

 /// VER LA LISTA DE ENFERMEDADES
 function showEnfermedadesParaDiagnosticoEdition(){
    TablaEnfermedadesEdition_ = $('#lista_enfermedades_editar').DataTable({
        retrieve:true,
        language:SpanishDataTable(),
        ajax:{
            url:RUTA+"enfermedades-habilitadas",
            method:"GET",
            dataSrc:"response",
        },
        columns:[
            {"data":"codigo_enfermedad"},
            {"data":"enfermedad",render:function(enfermeda_){
                return enfermeda_.toUpperCase();
            }},
            {"data":null,render:function(){
                return `<button class='btn btn-outline-primary btn-sm' id='asignar_diagnostico_edicion'><i class='bx bxs-send'></i></button>`;
            },className:'text-center'}
        ]
    })
 }

/*EMVIAR LA ENFERMEDAD PARA EL DIAGNOSTICO AL PACIENTE*/
function AsignarDiagnosticoEdition(Tabla,Tbody){
    $(Tbody).on('click','#asignar_diagnostico_edicion',function(){
            let fila = $(this).parents("tr");

            if(fila.hasClass("child")){
                fila = fila.prev();
            }

            let Data = Tabla.row(fila).data();
           
            let EnfermedadIdData = Data.id_enfermedad;
            let CodigoEnfermedadEdition = Data.codigo_enfermedad;

            ProcesoAsignarDiagnosticoEdition(CodigoEnfermedadEdition);
           // ProcesoAsignarDiagnostico(EnfermedadIdData);
    });
}


function ProcesoAsignarDiagnosticoEdition(code){
    let formAsignarDiagnostico = new FormData();
        formAsignarDiagnostico.append("token_",TOKEN);
        formAsignarDiagnostico.append("code",code);
        axios({
            url:RUTA+"agregar-nuevos-diagnosticos-al-paciente",
            method:"POST",
            data:formAsignarDiagnostico
        }).then(function(response){
            if(response.data.error != undefined){
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!!",
                    text:response.data.error,
                    icon:"error",
                    target:document.getElementById('modal_view_enfermedades_editar')
                })
            }else{
                 
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!!",
                    text:response.data.response,
                    icon:"success",
                    target:document.getElementById('modal_view_enfermedades_editar')
                }).then(function(){
                   showDiagnosticosAsignadosEditionPaciente();
                })
              
            }
    })
}