<?php 
namespace models;
use report\implementacion\Model;
class VistaVentas extends Model
{
    protected string $Table = "consultaproductoventa "; 
    protected $Alias = "as cpv ";

    protected string $PrimayKey = "id_producto";

}