<?php 
namespace models;
use report\implementacion\Model;
class CitaMedica extends Model
{
    protected string $Table = "cita_medica "; 
    protected $Alias = "as ctm ";

    protected string $PrimayKey = "id_cita_medica";

}