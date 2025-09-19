<?php 
namespace models;
use report\implementacion\Model;
class Compania extends Model
{
    protected string $Table = "compania "; 
    protected $Alias = "as com ";

    protected string $PrimayKey = "id_compania";

}