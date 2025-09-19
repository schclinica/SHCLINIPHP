<?php 
namespace models;
use report\implementacion\Model;
class DocumentosPaciente extends Model
{
    protected string $Table = "documentospaciente "; 
    protected $Alias = "as dp ";

    protected string $PrimayKey = "id_doc_paciente";

}