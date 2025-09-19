<?php 
namespace models;
use report\implementacion\Model;
class RecetaElectronico extends Model
{
    protected string $Table = "recetaelectronico "; 
    protected $Alias = "as rel ";

    protected string $PrimayKey = "id_receta_electro";

}