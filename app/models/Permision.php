<?php 
namespace models;

use report\implementacion\Model;

class Permision extends Model{
    protected string $Table = "previlegios "; 
    protected $Alias = "as pr ";

    protected string $PrimayKey = "id_previlegio";
}