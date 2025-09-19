<?php 
namespace models;
use report\implementacion\Model;

class VentasFarmacia extends Model
{
    protected string $Table = "ventas_farmacia ";

    protected $Alias = "as vf ";

    protected string $PrimayKey = "id_venta";

    /** 
     * Obtenemos el registro máximo de la tabla ventas farmacia
     */
    public function ObtenerMaxVenta()
    {
        return $this->query()->select("max(id_venta) as num_venta")
        ->first();
    }
}