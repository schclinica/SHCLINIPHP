<?php 
namespace models;
use report\implementacion\Model;

class Evaluacion_Pre_Operatoria extends Model
{
    protected string $Table = "evaluacion_pre_operatoria ";

    protected $Alias = "as epo ";

    protected string $PrimayKey = "id_evaluacion";


/** Proceso de registro de la evaluación pre operatoria */
public function saveEvaluation(string|null $indicaciones,string|null $antecedentes_importantes_,
string $molestias_importantes,string|null $pa,string|null $fcc,string|null $fr,string|null $to_,
string|null $sato_dos,string|null $peso,string|null $detalle_ex_fisico,string $resultados_estudios,
string $riesgo_quir_goldman,string $riesgo_quir_asa,string|null $sugerencias,int $atencion_medica_id,$FechaActual)
{
    return $this->Insert([
        "fecha_evaluacion"=>$FechaActual,
        "indicaciones" => $indicaciones,
        "antecedentes_importantes" => $antecedentes_importantes_,
        "molestias_importantes" => $molestias_importantes,
        "pa"=>$pa,"fcc" => $fcc,"fr" => $fr,"to_" => $to_,
        "sato_dos" => $sato_dos,"peso" => $peso,
        "detalle_ex_fisico" => $detalle_ex_fisico,
        "resultados_estudios" => $resultados_estudios,
        "riesgo_quir_goldman" => $riesgo_quir_goldman,
        "riesgo_quir_asa" => $riesgo_quir_asa,
        "sugerencias" => $sugerencias,
        "paciente_id" => $atencion_medica_id
    ]);
}
}