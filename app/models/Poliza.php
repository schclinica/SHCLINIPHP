<?php 
namespace models;
use report\implementacion\Model;
class Poliza extends Model
{
    protected string $Table = "polizas "; 
    protected $Alias = "as pol";

    protected string $PrimayKey = "id_poliza";

}