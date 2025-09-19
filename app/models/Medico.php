<?php 
namespace models;
use report\implementacion\Model;

class Medico extends Model
{
    protected string $Table = "medico ";

    protected $Alias = "as me ";

    protected string $PrimayKey = "id_medico";

    /// registro de médicos
    public function RegistroMedico(array $data)
    {
        return $this->Insert([
            "celular_num"=>$data[0],
            "universidad_graduado"=>$data[1],
            "experiencia"=>$data[2],
            "id_persona"=>$data[3],
            "cmp" => $data[4],
            "medicosede_id" => $data[5]
        ]);
    }

}