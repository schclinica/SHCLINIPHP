<?php 
namespace models;
use report\implementacion\Model;
class Servicio extends Model
{
    protected string $Table = "servicio ";

    protected $Alias = "as serv ";

    protected string $PrimayKey = "id_servicio";
}