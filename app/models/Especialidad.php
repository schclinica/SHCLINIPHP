<?php 
namespace models;
use report\implementacion\Model;

class Especialidad extends Model
{
    protected string $Table = "especialidad ";

    protected $Alias = "as esp ";

    protected string $PrimayKey = "id_especialidad";

    /// Registro de especialidades

    public function create(string|null $especialidad,$precio)
    {
        /// verificamos que no exista duplicidad

        $Especialidad = $this->query()->Where("nombre_esp","=",$especialidad)->first();

        if(!$Especialidad)
        {
             return $this->Insert(
                ["nombre_esp"=>$especialidad,"precio_especialidad"=>$precio]
             );
        }

        return "existe";
    }
    /// modificar una especialidad
    public function Modificar(string|null $especialidad,$precio, $IdEspecialidad)
    {
        /// verificar la existencia de una especialidad
        $Especialidad = $this->query()->Where("nombre_esp","=",$especialidad)->first();
        
        if(!$Especialidad)
        {
            /// modificamos 

            return $this->Update([
                "id_especialidad"=>$IdEspecialidad,
                "nombre_esp"=>$especialidad,
            ]);
        }

        return "existe";
       
       
    }
    /// eliminado lógico a cada especialidad
    public function CambiarEstado(int|null $Id_Especialidad,$estado)
    {
        return $this->Update([
            "id_especialidad"=>$Id_Especialidad,
            "estado"=>$estado
        ]);
    }

}