<?php 
namespace models;
use report\implementacion\Model;

class Especialidad_Medico extends Model
{
    protected string $Table = "medico_especialidades ";

    protected $Alias = "as med_esp ";

    protected string $PrimayKey = "id_medico_Esp";


    /// Asignar las especialidades a los médicos

    public function AsignEspecialidadMedico($id_especialidad,$id_medico)
    {
        return $this->Insert([
            "id_especialidad"=>$id_especialidad,
            "id_medico"=>$id_medico
        ]);
    }

}