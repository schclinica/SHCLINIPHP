<?php 
namespace models;
use report\implementacion\Model;

class Persona extends Model
{
    protected string $Table = "persona ";

    protected $Alias = "as p ";

    protected string $PrimayKey = "id_persona";

    /// consultamos persona por documento
    public  function PersonaPorDocumento(string|null $Documento)
    {
        return  $this->query()->Where("documento","=",$Documento)->first();

    }

    /// registramos a la persona

    public function RegistroPersona(array $data)
    {
        return $this->Insert([
            "documento"=>$data[0],
            "apellidos"=>$data[1],
            "nombres"=>$data[2],
            "genero"=>$data[3],
            "direccion"=>$data[4],
            "fecha_nacimiento"=>$data[5],
            "id_tipo_doc"=>$data[6],
            "id_distrito"=>$data[7],
            "id_usuario"=>$data[8]
        ]);
    }
}