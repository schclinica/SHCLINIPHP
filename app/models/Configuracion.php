<?php 
namespace models;
use report\implementacion\Model;

class Configuracion extends Model
{
    protected string $Table = "configurar_data_empresa ";

    protected $Alias = "as cde ";

    protected string $PrimayKey = "id_data_empresa";


    /// registrar Horarios de atención de EsSalud
    public function RegistroHorarioBusiness(string|null $Dia,string|null $HoraInicial,string|null $Horafinal)
    {
        /// Validamos la existencia (Duplicidad)
        
        $HorarioBusiness = $this->query()->Where("dias_atencion","=",$Dia)->first();

        if(!$HorarioBusiness)
        {
            return $this->Insert([
                "dias_atencion"=>$Dia,
                "horario_atencion_inicial"=>$HoraInicial,
                "horario_atencion_cierre"=>$Horafinal
            ]);
        }

        return "existe";
    }

    // DEvolver un registro dependiendo el día
    public function getFilterDato($Dia)
    {
       return $this->query()->Where("dias_atencion","=",$Dia)->first();
    }

}