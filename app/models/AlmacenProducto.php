<?php 
namespace models;

use report\implementacion\Model;

class AlmacenProducto extends Model{
    protected string $Table = "almacen_productos "; 
    protected $Alias = "as ap ";

    protected string $PrimayKey = "id_producto_almacen";
}