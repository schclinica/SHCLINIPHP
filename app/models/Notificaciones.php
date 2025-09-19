<?php 
namespace models;
use report\implementacion\Model;

class Notificaciones extends Model
{
    protected string $Table = "notificaciones ";

    protected $Alias = "as nf ";

    protected string $PrimayKey = "id_notificacion";
}