<?php 
namespace models;
use report\implementacion\Model;

class Paciente extends Model
{
    protected string $Table = "paciente ";

    protected $Alias = "as pc ";

    protected string $PrimayKey = "id_paciente";

    /// registro de pacientes
    public function RegistroPaciente(array $data)
    {
        return $this->Insert([
            "telefono"=>$data[0],
            "facebook"=>$data[1],
            "whatsapp"=>$data[2],
            "estado_civil"=>$data[3],
            "nombre_apoderado"=>$data[4],
            "id_persona"=>$data[5],
            "pacientesede_id" => $data[6],
        ]);
    }
}