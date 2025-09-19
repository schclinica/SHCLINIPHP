<?php 
namespace Http\controllers;
use lib\BaseController;
use models\Provincia;
use report\implementacion\Model;

class ProvinciaController extends BaseController
{

/// propiedades
private static Model $ModelProvincia;

/// registrar provincias
public static function saveProvincia()
{
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::post("token_")))
    {
        /// instanciamos al modelo provincia

        self::$ModelProvincia = new Provincia;

        /// verificamos de que no exista duplicidad de datos
        $Provincia = self::$ModelProvincia->query()->Where("name_provincia","=",self::post("name_provincia"))->first();

        if($Provincia)
        {
            self::json(["response"=>"existe"]);
        }
        else
        {
            /// registramos la provincia
            $Respuesta = self::$ModelProvincia->Insert([
                "name_provincia" => self::post("name_provincia"),
                "id_departamento" => self::post("departamento_select")
            ]);

            self::json(["response"=>$Respuesta]);
        }
    }
}

 /// mostrar las provincias por departamento
 public static function showProvincias()
 {
    self::NoAuth();
     /// validamos el token

     if(self::ValidateToken(self::get("token_")))
     {
        self::$ModelProvincia = new Provincia;
        //mostramos las provincias en formato json

        $Provincias = self::$ModelProvincia->query()
        ->Join("departamento as dep","prov.id_departamento","=","dep.id_departamento")
        ->Where("dep.id_departamento","=", self::get("id_departamento"))
        ->And("prov.deleted_at","=","1")
        ->Or("dep.name_departamento","=", self::get("id_departamento"))
        ->orderBy("prov.name_provincia","asc")
        ->get();

        self::json(["response"=>$Provincias]);
     }
 }

 public static function showProvinciasPorId($id)
 {
    self::NoAuth();
     /// validamos el token

     if(self::ValidateToken(self::get("token_")))
     {
        self::$ModelProvincia = new Provincia;
        //mostramos las provincias en formato json

        $Provincias = self::$ModelProvincia->query()
        ->Join("departamento as dep","prov.id_departamento","=","dep.id_departamento")
        ->Where("dep.id_departamento","=", self::get("id_departamento"))
        ->And("prov.deleted_at","=","1")
        ->Or("dep.id_departamento","=", $id)
        ->orderBy("prov.name_provincia","asc")
        ->get();

        self::json(["response"=>$Provincias]);
     }
 }

 /// mostrar toda provincia existente
 public static function allProvincias()
 {
    self::NoAuth();
     /// validamos el token

     if(self::ValidateToken(self::get("token_")))
     {
        self::$ModelProvincia = new Provincia;
        //mostramos las provincias en formato json

        $Provincias = self::$ModelProvincia->query()
                     ->join("departamento as dep","prov.id_departamento","=","dep.id_departamento")
                     ->Where("prov.deleted_at","=","1")
                     ->get();

        self::json(["response"=>$Provincias]);
     }
 }

 /// mostrar las provincias eliminados de la lista
 public static function allProvinciasEliminados()
 {
    self::NoAuth();
     /// validamos el token

     if(self::ValidateToken(self::get("token_")))
     {
        self::$ModelProvincia = new Provincia;
        //mostramos las provincias en formato json

        $Provincias = self::$ModelProvincia->query()
                     ->join("departamento as dep","prov.id_departamento","=","dep.id_departamento")
                     ->Where("prov.deleted_at","=","2")
                     ->get();

        self::json(["response"=>$Provincias]);
     }
 }
 /**
  * Actualizar datos de provincia
  */
  public static function update($id)
  {
    self::NoAuth();
    if(self::ValidateToken(self::post("token_")))
    {
        self::$ModelProvincia = new Provincia();

        /// verificamos la existencia de la provincia en la BD
        $provinciaExiste = self::$ModelProvincia->query()->Where("name_provincia","=",self::post("prov"))
        ->And("prov.id_departamento","=",self::post("dep"))
        ->first();

        if(!$provinciaExiste)
        {
            $respuesta = self::$ModelProvincia->Update([
                "id_provincia"=>$id,
                "name_provincia"=>self::post("prov"),
                "id_departamento"=>self::post("dep")
            ]);
        }
        else
        {
            $respuesta = 'existe';
        }

        self::json(['response'=>$respuesta]);
    }
  }

  // Cambiamos el estado de la provincia 
  public static function CambiarEstatusProvincia($id,$estatus)
  {
    self::NoAuth();
    if(self::ValidateToken(self::post("token_")))
    {
        self::$ModelProvincia = new Provincia();

        $respuesta = self::$ModelProvincia->Update([
            "id_provincia"=>$id,
            "deleted_at"=>$estatus
        ]);

        self::json(['response'=>$respuesta]);
    }
  }

}