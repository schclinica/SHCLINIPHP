<?php 
namespace models;
use report\implementacion\Model;
class Role_Permisos extends Model
{
    protected string $Table = "role_previlegios "; 
    protected $Alias = "as rp ";

    protected string $PrimayKey = "id_role_previlegio";

}