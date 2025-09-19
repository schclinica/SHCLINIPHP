<?php 
namespace models;
use report\implementacion\Model;
class CategoriaOrden extends Model
{
    protected string $Table = "categoria_examenes "; 
    protected $Alias = "as ce ";

    protected string $PrimayKey = "id_categoria_examen";

}