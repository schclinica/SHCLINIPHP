<?php 
namespace models;
use report\implementacion\Model;
class CajaFarmacia extends Model
{
    protected string $Table = "caja_farmacia "; 
    protected $Alias = "as cf ";

    protected string $PrimayKey = "id_caja";

}