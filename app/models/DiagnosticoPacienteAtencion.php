<?php 
namespace models;
use report\implementacion\Model;
class DiagnosticoPacienteAtencion extends Model
{
    protected string $Table = "diagnostico_paciente_atencion "; 
    protected $Alias = "as dpa ";

    protected string $PrimayKey = "id_diagnostico_paciente_atencion";

}