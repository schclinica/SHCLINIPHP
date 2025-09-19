<?php 
namespace models;
use report\implementacion\Model;
class TipoProducto extends Model
{
    protected string $Table = "tipo_producto_farmacia "; 
    protected $Alias = "as tpf ";

    protected string $PrimayKey = "id_tipo_producto";

}