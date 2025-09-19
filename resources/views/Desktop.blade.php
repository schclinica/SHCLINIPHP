@extends($this->Layouts("dashboard"))

@section('title_dashboard', 'Desktop')
@section('clase_ocultar','d-none')
@section('expandir','layout-content-navbar layout-without-menu')
@section('contenido')
  <div class="card p-4">
    <div class="card-text"><p class="h4 float-start">Escritorio</p><p class="float-end"> <a href="{{$this->route('dashboard')}}"><i class='bx bx-arrow-back'></i> Volver</a></p></div>
    <div class="row">
        <div class="col-xl-5 col-lg-5 col-12">
            <table class="table">
                <thead>
                    <th colspan="2" class="text-primary">Pacientes-Triaje</th>
                    <tr>
                        <th>#</th>
                        <th>PACIENTE</th>
                        <th>HORA DE PASE <i class='bx bxs-calendar'></i></th>
                    </tr>
                </thead>
                <tbody id="listado_pacientes_triaje">
                   
                </tbody>
            </table>
        </div>

        <div class="col-xl-7 col-lg-7 col-12">
            <table class="table">
                <thead>
                    <th colspan="2" style="color:#0000FF">Pacientes- Atención médica</th>
                    <tr>
                        <th>#</th>
                        <th>PACIENTE</th>
                        <th>MÉDICO</th>
                        <th>HORA ATENCIÓN</th>
                    </tr>
                </thead>
                <tbody id="listado_pacientes_atencion_medica">

                </tbody>
            </table>
        </div>
    </div>
  </div>
@endsection
@section('js')
<script>
    var RUTA = "{{URL_BASE}}" // la url base del sistema
    var TOKEN = "{{$this->Csrf_Token()}}";
</script>
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>
$(document).ready(function(){
    
 setInterval(() => {
    PacientesEnTriaje();
 }, 500);

 setInterval(() => {
    PacientesEnColaAtencionMedica();
 }, 500);
});

/// mostrar los pacientes que se le mandan a triaje
function PacientesEnTriaje()
{
    let response = show(RUTA+"triaje/pacientes/desktop?token_="+TOKEN);

    let tr = '';let contador = 0;

     if(response.length > 0)
     {
        response.forEach(paciente => {
        contador++;

        tr+=`
        <tr>
        <td><span class='badge bg-primary'>`+contador+`</span></td>
        <td>`+paciente.paciente+`</td>    
        <td><span class="badge bg-info">`+paciente.hora_cita_+`</span></td>
        </tr>
        `;
    });
    }else
    {
        tr+='<td colspan="2"><span class="text-danger">No hay pacientes en triaje...</span></td>';
    }

    $('#listado_pacientes_triaje').html(tr);
}

function PacientesEnColaAtencionMedica()
{
    let response = show(RUTA+"pacientes/cola/atencion_medica?token_="+TOKEN);

    let tr = '';let contador = 0;

     if(response.length > 0)
     {
        response.forEach(paciente => {
        contador++;

        tr+=`
        <tr>
        <td><span class='badge bg-primary'>`+contador+`</span></td>
        <td>`+paciente.paciente_atencion+`</td>    
        <td><span class="badge bg-info">`+paciente.medico_atencion+`</span></td>
        <td><span class="badge bg-warning">`+paciente.hora_de_la_cita+`</span></td>
        </tr>
        `;
    });
    }else
    {
        tr+='<td colspan="2"><span class="text-danger">Aún no hay pacientes para atención médica...</span></td>';
    }

    $('#listado_pacientes_atencion_medica').html(tr);
}
</script>
@endsection