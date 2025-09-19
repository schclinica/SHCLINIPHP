<?php 
namespace models;
use report\implementacion\Model;
class Presentacion extends Model
{
    protected string $Table = "presentacion_farmacia "; 
    protected $Alias = "as pf ";

    protected string $PrimayKey = "id_pesentacion";

}