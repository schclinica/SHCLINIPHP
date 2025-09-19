<?php 
namespace models;
use report\implementacion\Model;
class Cuota extends Model
{
    protected string $Table = "cuotaspoliza "; 
    protected $Alias = "as ctas ";

    protected string $PrimayKey = "id_cuota";

}