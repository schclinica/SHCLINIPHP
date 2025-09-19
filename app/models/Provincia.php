<?php 
namespace models;
use report\implementacion\Model;

class Provincia extends Model
{
    protected string $Table = "provincia ";

    protected $Alias = "as prov ";

    protected string $PrimayKey = "id_provincia";
}