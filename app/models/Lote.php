<?php 
namespace models;
use report\implementacion\Model;
class Lote extends Model
{
    protected string $Table = "lotes "; 
    protected $Alias = "as l ";

    protected string $PrimayKey = "codigo_lote";

}