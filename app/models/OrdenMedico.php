<?php 
namespace models;
use report\implementacion\Model;

class OrdenMedico extends Model
{
    protected string $Table = "ordenmedico ";

    protected $Alias = "as om ";

    protected string $PrimayKey = "id_orden_medico";
}