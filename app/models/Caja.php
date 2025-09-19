<?php 
namespace models;
use report\implementacion\Model;
class Caja extends Model
{
    protected string $Table = "apertura_caja "; 
    protected $Alias = "as ac ";

    protected string $PrimayKey = "id_apertura_caja";

}