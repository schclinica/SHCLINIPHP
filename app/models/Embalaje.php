<?php 
namespace models;
use report\implementacion\Model;
class Embalaje extends Model
{
    protected string $Table = "embalaje_farmacia "; 
    protected $Alias = "as emb ";

    protected string $PrimayKey = "id_embalaje";

}