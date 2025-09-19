<?php 
namespace models;
use report\implementacion\Model;
class Role extends Model
{
    protected string $Table = "roles "; 
    protected $Alias = "as r ";

    protected string $PrimayKey = "id_rol";

}