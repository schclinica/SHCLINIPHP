<?php 
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\Departamento;
use report\implementacion\Model;

use function Windwalker\where;

class DepartamentoController extends BaseController
{
  private static Model $ModelDepartamento; 
 /// mostrar la vista de crear departamentos, provincias y distritos
 public static function viewDepartementos()
 {
    /// si no está authenticado, redirige a login
    self::NoAuth();

    if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
      self::View_("departamentos.index");
      return;
    }

    PageExtra::PageNoAutorizado();
 }

 //crear departamentos para asignarle al paciente mediante ajax
public static function saveDepartamento()
{
   self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::post("token_")))
    {
      /// instanciamos al modelo departamento
      self::$ModelDepartamento = new Departamento;

      /// verificar la existencia del departamento
      $Departamento = self::$ModelDepartamento->query()->Where("name_departamento","=",self::post("nombre_dep"))->first();

      if($Departamento)
      {
         self::json([
            "response"=>"existe"
         ]);
      }
      else
      {
         /// registramos
         $Respuesta = self::$ModelDepartamento->Insert([
            "name_departamento"=>self::post("nombre_dep")
         ]);

         self::json([
            "response"=>$Respuesta
         ]);
      }
        
    }
}

/// mostrar los departamentos existentes
public static function showDepartamentos()
{
   self::NoAuth();
   /// validamos token
   if(self::ValidateToken(self::get("token_")))
   {
      self::$ModelDepartamento = new Departamento;
      // mostramos los departamentos en formato json

      $Departamentos = self::$ModelDepartamento
          ->query()->where("deleted_at","=","1")
          ->orderBy("name_departamento","asc")
          ->get();

      self::json(["response"=>$Departamentos]);
   }
}
/// mostrar departamentos eliminados
public static function showDepartamentosEliminados()
{
   self::NoAuth();
   /// validamos token
   if(self::ValidateToken(self::get("token_")))
   {
      self::$ModelDepartamento = new Departamento;
      // mostramos los departamentos en formato json

      $Departamentos = self::$ModelDepartamento
          ->query()->where("deleted_at","=","2")->get();

      self::json(["response"=>$Departamentos]);
   }
}

/// Actualizar datos del departamento editado
public static function update($id)
{
   self::NoAuth();
   if(self::ValidateToken(self::post("token_")))
   {
      self::$ModelDepartamento = new Departamento();

      /// verificamos la existencia del departamento
      $ExisteDepartamento = self::$ModelDepartamento->query()->Where("name_departamento","=",self::post("dep"))->first();

      if(!$ExisteDepartamento)
      {
         $respuesta = self::$ModelDepartamento->Update([
            "id_departamento"=>$id,
            "name_departamento"=>self::post("dep")
         ]);
      }
      else
      {
        $respuesta = 'existe';
      }
      self::json(['response'=>$respuesta]);   
   }
}

/// eliminar departamentos
public static function cambiarEstado($id,$estado)
{
   self::NoAuth();

   if(self::ValidateToken(self::post("token_")))
   {
      self::$ModelDepartamento = new Departamento();

      $respuesta = self::$ModelDepartamento->Update([
         "id_departamento"=>$id,
         "deleted_at"=>$estado
      ]);

      self::json(['response'=>$respuesta]);
   }
}


}