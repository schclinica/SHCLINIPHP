<?php 
namespace models;
use report\implementacion\Model;
class CategoriaEgreso extends Model
{
    protected string $Table = "categoria_egresos "; 
    protected $Alias = "as ce ";

    protected string $PrimayKey = "id_categoria_egreso";

}