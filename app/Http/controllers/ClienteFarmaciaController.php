<?php
namespace Http\controllers;

use lib\BaseController;
use models\ClienteFarmacia;

class ClienteFarmaciaController extends BaseController
{
  /** Registrar nuevos clientes a la base de datos */
  public static function store()
  {
    self::NoAuth();
    if(self::profile()->rol === self::$profile[5])
    {
        if(self::ValidateToken(self::post("_token")))
        {

            $modelCliente = new ClienteFarmacia;

            $response = $modelCliente->RegistrarCliente(
                self::post("tipo_doc"),self::post("num_doc"),self::post("name_cliente"),self::post("apellidos_cliente"),
                self::post("direccion_cliente"),self::post("telefono_cliente")
            );

            self::json(["response" => $response]);
        }else{
            self::json(["response" => "token-invalite"]);
        }
    }else{
        self::json(["response" => "no-authorized"]);
    }
  }

  /** Actualizar datos del cliente */
  public static function update(int $id)
  {
    self::NoAuth();
    if(self::profile()->rol === self::$profile[5])
    {
        if(self::ValidateToken(self::post("_token")))
        {

            $modelCliente = new ClienteFarmacia;

            $response = $modelCliente->ModificarCliente(
                self::post("tipo_doc"),self::post("num_doc"),self::post("name_cliente"),self::post("apellidos_cliente"),
                self::post("direccion_cliente"),self::post("telefono_cliente"),$id
            );

            self::json(["response" => $response]);
        }else{
            self::json(["response" => "token-invalite"]);
        }
    }else{
        self::json(["response" => "no-authorized"]);
    }
  }

   /** Actualizar datos del cliente */
   public static function destroy(int $id)
   {
     self::NoAuth();
     if(self::profile()->rol === self::$profile[5])
     {
         if(self::ValidateToken(self::post("_token")))
         {
 
             $modelCliente = new ClienteFarmacia;
 
             $response = $modelCliente->BorrarCliente($id);
 
             self::json(["response" => $response]);
         }else{
             self::json(["response" => "token-invalite"]);
         }
     }else{
         self::json(["response" => "no-authorized"]);
     }
   }

  /** Mostrar todos los clientes */
  public static function mostrarClientes()
  {
    self::NoAuth();

    if(self::profile()->rol === self::$profile[5])
    {
     $modelCliente = new ClienteFarmacia;

     $repuesta = $modelCliente->query()->Join("tipo_documento as td","cf.tipo_doc_id" ,"=","td.id_tipo_doc")->get();

     self::json(["response" => $repuesta]);
    }else{
        self::json(["response" =>[]]);
    }
  }

  /// Habilitar e inhabilitar clientes
  public static function HabilitarInhabilitarCliente(int $id,string $Condition)
  {
    self::NoAuth();
    if(self::profile()->rol === self::$profile[5])
    {
     if(self::ValidateToken(self::post("_token")))
     {
      $modelCliente = new ClienteFarmacia;

      $response = $modelCliente->Update([
        "id_cliente" => $id,
        "deleted_at_cli" => $Condition === 'i' ? self::FechaActual("Y-m-d H:i:s"):null
      ]);
      self::json(["response" => $response]);
     }else{
      self::json(["response" => "token-invalidate"]);
     }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }
}