<?php 
namespace models;
use report\implementacion\Model;

class DetalleVenta extends Model
{
    protected string $Table = "detalle_venta_farmacia ";

    protected $Alias = "as dv ";

    protected string $PrimayKey = "id_detalle_venta";

     
}