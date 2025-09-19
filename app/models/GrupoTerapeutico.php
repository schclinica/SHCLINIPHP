<?php 
namespace models;
use report\implementacion\Model;
class GrupoTerapeutico extends Model
{
    protected string $Table = "grupo_terapeutico_farmacia "; 
    protected $Alias = "as gtf ";

    protected string $PrimayKey = "id_grupo_terapeutico";

}