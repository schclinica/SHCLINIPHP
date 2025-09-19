<?php 
namespace models;
use report\implementacion\Model;
class UsuarioRole extends Model
{
    protected string $Table = "usuario_roles "; 
    protected $Alias = "as ur ";

    protected string $PrimayKey = "id_user_rol";

}