<?php 
namespace models;
use report\implementacion\Model;

class TipoOrden extends Model
{
    protected string $Table = "tipo_examen ";

    protected $Alias = "as te ";

    protected string $PrimayKey = "id_tipo_examen";
}