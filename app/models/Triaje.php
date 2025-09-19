<?php 
namespace models;
use report\implementacion\Model;
class Triaje extends Model
{
    protected string $Table = "triaje "; 
    protected $Alias = "as tr ";

    protected string $PrimayKey = "id_triaje";
}