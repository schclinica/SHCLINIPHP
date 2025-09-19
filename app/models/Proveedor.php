<?php 
namespace models;
use report\implementacion\Model;
class Proveedor extends Model
{
    protected string $Table = "proveedores_farmacia "; 
    protected $Alias = "as pf ";

    protected string $PrimayKey = "id_proveedor";

}