<?php 
namespace models;
use report\implementacion\Model;

class Sede extends Model
{
    protected string $Table = "sedes ";

    protected $Alias = "as sed ";

    protected string $PrimayKey = "id_sede";
}