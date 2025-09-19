<?php 
namespace models;
use report\implementacion\Model;

class ClienteFarmacia extends Model
{
    protected string $Table = "clientes_farmacia ";

    protected $Alias = "as cf ";

    protected string $PrimayKey = "id_cliente";

    /** Registrar cliente */
    public function RegistrarCliente(int $tipodocid,string $num_doc,string $nombres,string $apellidos,
    string|null $direccion,string|null $telefono_or_wasap)
    {
        $ExisteCliente = $this->query()->Where("tipo_doc_id","=",$tipodocid)
        ->And("num_doc","=",$num_doc)->first();

        if($ExisteCliente)
        {
            return 'existe';
        }

        return $this->Insert([
            "tipo_doc_id" => $tipodocid,"num_doc" => $num_doc,"nombres" => $nombres,"apellidos" => $apellidos,
            "direccion" => $direccion,"telefono_or_wasap" => $telefono_or_wasap
        ]);
    }

    /** Actualizar datos del cliente */
    public function ModificarCliente(int $tipodocid,string $num_doc,string $nombres,string $apellidos,
    string|null $direccion,string|null $telefono_or_wasap,int $idcli)
    {
         
        return $this->Update([
            "id_cliente" => $idcli,
            "tipo_doc_id" => $tipodocid,"num_doc" => $num_doc,"nombres" => $nombres,"apellidos" => $apellidos,
            "direccion" => $direccion,"telefono_or_wasap" => $telefono_or_wasap
        ]);
    }

    /** Borrar cliente de la base de datos */
    public function BorrarCliente(int $id)
    {
        return $this->delete($id);
    }
    

}