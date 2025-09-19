<?php 
namespace models;
use report\implementacion\Model;
class Producto extends Model
{
    protected string $Table = "productos "; 
    protected $Alias = "as prod ";

    protected string $PrimayKey = "id_producto";

}