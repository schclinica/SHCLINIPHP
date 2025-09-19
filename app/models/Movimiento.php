<?php
namespace models;

use report\implementacion\Model;

class Movimiento extends Model{
    protected string $Table = "movimientos ";

    protected $Alias = "as mov ";

    protected string $PrimayKey = "id_movimiento";
}