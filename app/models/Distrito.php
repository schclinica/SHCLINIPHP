<?php 
namespace models;
use report\implementacion\Model;

class Distrito extends Model
{
    protected string $Table = "distritos ";

    protected $Alias = "as dis ";

    protected string $PrimayKey = "id_distrito";
}