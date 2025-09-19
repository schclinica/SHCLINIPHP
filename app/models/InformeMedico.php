<?php 
namespace models;
use report\implementacion\Model;
class InformeMedico extends Model
{
    protected string $Table = "informe_medico ";

    protected $Alias = "as im ";

    protected string $PrimayKey = "id_informe";
}