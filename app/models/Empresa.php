<?php 
namespace models;
use report\implementacion\Model;

class Empresa extends Model
{
    protected string $Table = "empresa_datos ";

    protected $Alias = "as ed ";

    protected string $PrimayKey = "id_empresa_data";
}