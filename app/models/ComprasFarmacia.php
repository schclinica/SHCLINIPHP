<?php 
namespace models;
use report\implementacion\Model;

class ComprasFarmacia extends Model
{
    protected string $Table = "compras_farmacia ";

    protected $Alias = "as cf ";

    protected string $PrimayKey = "id_compra";

    /** 
     * Obtenemos el registro máximo de la tabla compras farmacia
     */
    public function ObtenerMaxCompra()
    {
        return $this->query()->select("max(id_compra) as num_compra")
        ->first();
    }
}