<?php 
namespace models;
use report\implementacion\Model;

class Receta extends Model
{
    protected string $Table = "receta ";

    protected $Alias = "as rc ";

    protected string $PrimayKey = "id_detalle_receta";

}