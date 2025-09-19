<?php 
namespace models;
use report\implementacion\Model;

class Departamento extends Model
{
    protected string $Table = "departamento ";

    protected $Alias = "as dep ";

    protected string $PrimayKey = "id_departamento";
}