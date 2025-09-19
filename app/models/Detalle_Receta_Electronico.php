<?php 
namespace models;
use report\implementacion\Model;
class Detalle_Receta_Electronico extends Model
{
    protected string $Table = "detalle_receta_electronico "; 
    protected $Alias = "as dre ";

    protected string $PrimayKey = "id_det_rec";

}