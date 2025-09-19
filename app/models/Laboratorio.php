<?php 
namespace models;
use report\implementacion\Model;
class Laboratorio extends Model
{
    protected string $Table = "laboratorio_farmacia "; 
    protected $Alias = "as lf ";

    protected string $PrimayKey = "id_laboratorio";

}