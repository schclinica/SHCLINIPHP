<?php 
namespace models;
use report\implementacion\Model;
class Ramo extends Model
{
    protected string $Table = "ramos "; 
    protected $Alias = "as ram";

    protected string $PrimayKey = "id_ramo";

}