<?php 
namespace models;
use report\implementacion\Model;

class AtencionMedica extends Model
{
    protected string $Table = "atencionmedica ";

    protected $Alias = "as atm ";

    protected string $PrimayKey = "id_atencion";

    
    /// registrar horarios del médico

    public function AsignaHorario($dia,$medico,$hora_inicio,$hora_final,int|null|string $tiempo_atencion)
    {
      return $this->Insert([
        "dia"=>$dia,
        "id_medico"=>$medico,
        "hora_inicio_atencion"=>$hora_inicio,
        "hora_final_atencion"=>$hora_final,
        "tiempo_atencion"=>$tiempo_atencion
      ]);
    }
}