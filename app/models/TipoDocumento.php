<?php 
namespace models;
use report\implementacion\Model;

class TipoDocumento extends Model
{
    protected string $Table = "tipo_documento ";

    protected $Alias = "as tp ";

    protected string $PrimayKey = "id_tipo_doc";
}