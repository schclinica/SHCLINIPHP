<?php 
namespace models;
use report\implementacion\Model;

class Orden extends Model
{
    protected string $Table = "examenes ";

    protected $Alias = "as e ";

    protected string $PrimayKey = "id_examen";
}