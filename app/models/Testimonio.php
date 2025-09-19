<?php 
namespace models;
use report\implementacion\Model;

class Testimonio extends Model
{
    protected string $Table = "testimonios ";

    protected $Alias = "as tes ";

    protected string $PrimayKey = "id_testimonio";

    /// proceso para guardar testimonio por los pacientes
    public function Guardar(string $desc_testimonio,string $fecha,int $paciente)
    {
        return $this->Insert([
            "descripcion_testimonio" => $desc_testimonio,
            "fechahora" => $fecha,
            "paciente_id" => $paciente
        ]);
    }
}