<?php 
namespace models;
use report\implementacion\Model;

class Detalle_Recibo extends Model
{
    protected string $Table = "detalle_recibo ";

    protected $Alias = "as dr ";

    protected string $PrimayKey = "recibo_id";

    
}