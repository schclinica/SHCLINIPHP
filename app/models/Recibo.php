<?php 
namespace models;
use report\implementacion\Model;

class Recibo extends Model
{
    protected string $Table = "recibo ";

    protected $Alias = "as re ";

    protected string $PrimayKey = "id_recibo";

    /** 
     * Obtenemos el registro máximo del recibo
     */
    public function ObtenerMaxRecibo()
    {
        return $this->query()->select("max(id_recibo) as num")
        ->first();
    }
}