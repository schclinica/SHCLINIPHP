<?php 
namespace models;
use report\implementacion\Model;

class Electrocardiograma extends Model
{
    protected string $Table = "informe_electrocardiograma ";

    protected $Alias = "as ie ";

    protected string $PrimayKey = "id_informe_electro";

    /// registramos los datos del informe electrocardiograma
    public function save(
     string $pa,string $obeso,string $fecha,string|null $indicacion,
     string|null $ekg_anterior,string|null $medicamento_cv,string|null $solicitante,
     string|null $frecuencia,string|null $ritmo,string|null $p_data,string|null $pr_data,
     string|null $qrs_data,string|null $aqrs_data,string|null $qt_data,string|null $hallazgos,
     int $paciente_id
    )
    {

        return $this->Insert([
            "pa" => $pa,"obeso" => $obeso,"fecha" => $fecha,"indicacion" => $indicacion,
            "ekg_anterior" => $ekg_anterior,"medicamento_cv" => $medicamento_cv,
            "solicitante" => $solicitante,"frecuencia" => $frecuencia,"ritmo" => $ritmo,
            "p_data" => $p_data,"pr_data" => $pr_data,"qrs_data" => $qrs_data,
            "aqrs_data" => $aqrs_data,"qt_data" => $qt_data,"hallazgos" => $hallazgos,
            "paciente_id" => $paciente_id
        ]);
    }
}