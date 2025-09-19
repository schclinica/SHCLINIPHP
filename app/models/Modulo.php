<?php 
namespace models;
use report\implementacion\Model;
class Modulo extends Model
{
    protected string $Table = "modulos "; 
    protected $Alias = "as modu ";

    protected string $PrimayKey = "id_modulo";

}