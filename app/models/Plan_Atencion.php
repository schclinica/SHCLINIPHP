<?php 
namespace models;
use report\implementacion\Model;

class Plan_Atencion extends Model
{
    protected string $Table = "atencion_medica ";

    protected $Alias = "as plan ";

    protected string $PrimayKey = "id_atencion_medica";

     /// modificando para el hosting
    /// registrar la atencion médica del paciente
    public function guardar(String $Expediente,string $antecedente,string $tiempo_enfermedad,string|null $alergias,string $vacuna,
                           string|null $procedimiento,string $examen_fisico,string|null $diagnostico_general,
                           string|null $desc_tratamiento,string|null $TiempoTratamiento,$triaje,$fecha_procima_cita,
                           string|null $ant_familiares,string|null $otros,String|null $FechaAtencion,
                           string|null $diabetes,string|null $hiper_arterial,string|null $transfusiones,string|null $cirujias,
                           string|null $medicamentoActuales,int|null $usuario,int|null $sede,string|null $gestante,string|null $edad_gestacional,string|null $fecha_parto)
    {
        return $this->Insert([
            "id_atencion_medica" => $Expediente,
            "num_expediente" => $Expediente,
            "antecedentes"=>$antecedente,
            "tiempo_enfermedad"=>$tiempo_enfermedad,
            "alergias"=>$alergias,
            "otros" => $otros,
            "vacunas_completos"=>$vacuna,
            "procedimiento" => $procedimiento,
            "resultado_examen_fisico"=>$examen_fisico,
            "diagnostico_general"=>$diagnostico_general,
            "desc_plan"=>$desc_tratamiento,
            "tiempo_tratamiento" =>$TiempoTratamiento,
            "fecha_atencion" => $FechaAtencion,
            "proxima_cita"=>$fecha_procima_cita,
            "id_triaje"=>$triaje,
            "atecendentes_fam" => $ant_familiares,
            "gestante" => $gestante,
            "edad_gestacional" => $edad_gestacional,
            "fecha_parto" => $fecha_parto,
            "diabetes" => $diabetes,
            "hipertencion_arterial" => $hiper_arterial,
            "transfusiones" => $transfusiones,
            "cirujias_previas" => $cirujias,
            "medicamento_actuales" => $medicamentoActuales,
            "usuario_id" => $usuario,"sede_id" => $sede
        ]);
    }
}