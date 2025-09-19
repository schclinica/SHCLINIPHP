<?php 
namespace models;
use report\implementacion\Model;

class Detalle_Ordenes_Paciente extends Model
{
    protected string $Table = "detalle_ordenes_pacientes ";

    protected $Alias = "as dop ";

    protected string $PrimayKey = "id_detalle_orden_paciente";
}