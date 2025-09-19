<?php 
namespace models;
use report\implementacion\Model;

class Usuario  extends  Model
{
  protected string $Table = "usuario ";

  protected $Alias = "as u ";

  protected string $PrimayKey = "id_usuario";

  /// consultar usuario por username
  public function UsuarioPorUsername(string|null $Username)
  {
    return $this->query()->Where("name","=",$Username)->first();
  }

   /// consultar usuario por Email
   public function UsuarioPorEmail(string|null $Email)
   {
     return $this->query()->Where("email","=",$Email)->first();
   }
   /// creamos usuario
   public function RegistroUsuario(array $data,string $rol,$foto,int|null $sede)
   {
    return $this->Insert([
      "name"=>$data[0],
      "email"=>$data[1],
      "pasword"=>$data[2],
      "rol"=>$rol,
      "foto"=>$foto,
      "sede_id" => $sede
    ]);
   }
}