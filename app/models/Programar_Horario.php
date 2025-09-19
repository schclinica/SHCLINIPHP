<?php 
namespace models;
use report\implementacion\Model;
class Programar_Horario extends Model
{
    protected string $Table = "horas_programadas ";

    protected $Alias = "as hp ";

    protected string $PrimayKey = "id_horario";

    public function saveProgramacionHorario(int|null $atencion,string $hora_inicio,string $hora_final)
    {
        return $this->Insert([
            "id_atencion"=>$atencion,
            "hora_inicio"=>$hora_inicio,
            "hora_final"=>$hora_final
        ]);
    }
}