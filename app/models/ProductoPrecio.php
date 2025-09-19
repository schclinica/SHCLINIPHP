<?php 
namespace models;
use report\implementacion\Model;
class ProductoPrecio extends Model
{
    protected string $Table = "producto_precios "; 
    protected $Alias = "as pp ";

    protected string $PrimayKey = "id_producto_empaque_precio";

}