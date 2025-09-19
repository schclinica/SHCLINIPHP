<?php
namespace Http\controllers;
use lib\BaseController;
use models\Distrito;
use report\implementacion\Model;

class DistritoController extends BaseController
{
/// propiedades
private static Model $ModelDistrito;
/// método que realiza el registro de un nuevo distrito
public static function save()
{

 self::NoAuth();
 /// validamos el token
 if(self::ValidateToken(self::post("token_")))
 {
    self::$ModelDistrito = new Distrito;
    /// verificamos que no exista duplicidad antes de registrar

    $Distrito = self::$ModelDistrito->query()->Where("name_distrito","=",self::post("name_distrito"))
    ->And("id_provincia","=",self::post("provincia_select_dis"))
    ->first();

    if($Distrito)
    {
        self::json(["response"=>"existe"]);
    }
    else
    {
        $Respuesta = self::$ModelDistrito->Insert([
            "name_distrito"=>self::post("name_distrito"),
            "id_provincia"=>self::post("provincia_select_dis")
        ]);

        self::json(["response"=>$Respuesta]);
    }
 }
}

/// método para mostrar los distritos deacuerdo a la provincia
public static function showDistritos_provincia($Id_provincia)
{
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::get("token_")))
    {
        /// mostramos los distritos
        self::$ModelDistrito = new Distrito;

        $Distritos = self::$ModelDistrito->query()->Join("provincia as pr","dis.id_provincia","=","pr.id_provincia")
        ->Where("dis.id_provincia","=",$Id_provincia)
        ->And("dis.deleted_at","=","1")
        ->Or("pr.name_provincia","=",$Id_provincia)->get();

        self::json(["response"=>$Distritos]);
    }
}

/// MOSTRAR DISTRITOS POR EL ID DE LA PROVINCIA
public static function showDistritos_provinciaPorId($Id_provincia)
{
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::get("token_")))
    {
        /// mostramos los distritos
        self::$ModelDistrito = new Distrito;

        $Distritos = self::$ModelDistrito->query()->Join("provincia as pr","dis.id_provincia","=","pr.id_provincia")
        ->Where("dis.id_provincia","=",$Id_provincia)
        ->And("dis.deleted_at","=","1")
        ->Or("pr.id_provincia","=",$Id_provincia)
        ->orderBy("dis.name_distrito","asc")
        ->get();
        self::json(["response"=>$Distritos]);
    }
}

/// mostrar provincias existentes
public static function showDistritosAll()
{
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::get("token_")))
    {
        /// mostramos los distritos
        self::$ModelDistrito = new Distrito;

        $Distritos = self::$ModelDistrito->query()->Join("provincia as pr","dis.id_provincia","=","pr.id_provincia")
        ->Join("departamento as dep","pr.id_departamento","=","dep.id_departamento")
        ->Where("dis.deleted_at","=","1")
        ->get();
        self::json(["response"=>$Distritos]);
    }
}

public static function showDistritosEliminados()
{
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::get("token_")))
    {
        /// mostramos los distritos
        self::$ModelDistrito = new Distrito;

        $Distritos = self::$ModelDistrito->query()->Join("provincia as pr","dis.id_provincia","=","pr.id_provincia")
        ->Join("departamento as dep","pr.id_departamento","=","dep.id_departamento")
        ->Where("dis.deleted_at","=","2")
        ->get();
        self::json(["response"=>$Distritos]);
    }
}

/// actualizar los distritos
public static function update($id)
{
  self::NoAuth();
  if(self::ValidateToken(self::post("token_")))
  {
      $ModelComuna = new Distrito();

      /// verificamos la existencia de la provincia en la BD
      $provinciaExiste = $ModelComuna->query()->Where("name_distrito","=",self::post("name_distrito_editar"))
      ->And("dis.id_provincia","=",self::post("provincia_select_dis_editar"))
      ->first();

      if(!$provinciaExiste)
      {
          $respuesta = $ModelComuna->Update([
              "id_distrito"=>$id,
              "name_distrito"=>self::post("name_distrito_editar"),
              "id_provincia"=>self::post("provincia_select_dis_editar")
          ]);
      }
      else
      {
          $respuesta = 'existe';
      }

      self::json(['response'=>$respuesta]);
  }
}

/// eliminar distrito
public static function delete($id)
{
  self::NoAuth();
  if(self::ValidateToken(self::post("token_")))
  {
      $ModelComuna = new Distrito();

      
      $respuesta = $ModelComuna->Update([
        "id_distrito"=>$id,
        "deleted_at" => "2"
      ]);
      self::json(['response'=>$respuesta]);
  }
}

/// activar distrito
public static function Activate($id)
{
  self::NoAuth();
  if(self::ValidateToken(self::post("token_")))
  {
      $ModelComuna = new Distrito();

      
      $respuesta = $ModelComuna->Update([
        "id_distrito"=>$id,
        "deleted_at" => "1"
      ]);
      self::json(['response'=>$respuesta]);
  }
}
}