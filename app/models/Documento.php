<?php 
namespace models;
use report\implementacion\Model;
class Documento extends Model
{
    protected string $Table = "tipo_documento_emision "; 
    protected $Alias = "as tde ";

    protected string $PrimayKey = "id_tipo_documento_emision";

}