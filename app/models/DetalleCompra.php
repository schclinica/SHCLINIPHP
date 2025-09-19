<?php 
namespace models;
use report\implementacion\Model;

class DetalleCompra extends Model
{
    protected string $Table = "detalle_compra_farmacia ";

    protected $Alias = "as dcf ";

    protected string $PrimayKey = "id_detalle_compra";

   
}