<?php
namespace models;

use report\implementacion\Model;

class Enfermedad extends Model{
    
    protected string $Table = "enfermedades "; 
    protected $Alias = "as enf";

    protected string $PrimayKey = "id_enfermedad";
}