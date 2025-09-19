<?php 
namespace models;
use report\implementacion\Model;
class SubCategoriaEgreso extends Model
{
    protected string $Table = "subcategorias_egreso "; 
    protected $Alias = "as sce ";

    protected string $PrimayKey = "id_subcategoria";

}