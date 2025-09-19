<?php 
namespace models;

use report\implementacion\Model;

class DocumentoEmision extends Model{
       protected string $Table = "documentosemision "; 
    protected $Alias = "as de ";

    protected string $PrimayKey = "id_documento_emision";

}