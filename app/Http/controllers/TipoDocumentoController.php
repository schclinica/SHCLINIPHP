<?php 

namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\Persona;
use models\TipoDocumento;
use report\implementacion\Model;

 class TipoDocumentoController extends BaseController
 {
 
 /// propiedades
 private static Model $modelTipoDoc;   
/*=======================================
Visualizar la página de listado de tipos de 
documentos
=========================================*/ 

public static function index()
{
  
  self::NoAuth();/// si no esta authenticado, redirige al login

  if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
  {
    self::$modelTipoDoc = new TipoDocumento;

    /// enviamos los tipos de documentos existentes
    $TipoDocumentos = self::$modelTipoDoc->query()->get();
    self::View_("documento.index",compact("TipoDocumentos"));
    return;
  }

  PageExtra::PageNoAutorizado();
}
  
/*=======================================
Visualizar la página de crear documentos
=========================================*/

public static function create()
{
    self::NoAuth();/// si no esta authenticado, redirige al login

    if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
      self::View_("documento.create");
      return;
    }

    PageExtra::PageNoAutorizado();
}

/*=====================================
Guardar tipo de documentos(crear nuevo)
======================================*/
public static function save()
{ 
  self::NoAuth();/// si no esta authenticado, redirige al login
   if(self::ValidateToken(self::post("token_")))
   {
    self::$modelTipoDoc = new TipoDocumento;

     /// verificamos que dimos click en el boton

     if(self::onClick("save"))
     {
      if(empty(self::post("name-tipo-doc"))):
        self::Session("mensaje","error");
        /// redirigimos a la misma ruta
      self::RedirectTo("new-tipo-documento"); 
      else:
        self::GuardarTipoDocumento();
      endif;
      /// redirigimos a la misma ruta
     }
   }
}

/// Activar el tipo documento
public static function ActivarTipoDocumento($id){
  if(self::ValidateToken(self::post("token_"))){
    $tipodoc = new TipoDocumento;

    $response = $tipodoc->Update([
      "id_tipo_doc" => $id,
      "estado" => "1"
    ]);

    self::json(["response" => $response?'ok':'error']);
  }else{
    self::json(["errortoken" => "token-invalid"]);
  }
}

/// Eliminar el tipo documento
public static function BorrarTipoDocumento($id){
  if(self::ValidateToken(self::post("token_"))){
    $tipodoc = new TipoDocumento;

    $persona = new Persona;

    $personaDocumento = $persona->query()->where("id_tipo_doc","=",$id)->get();

    if(count($personaDocumento) <= 0){

    $response = $tipodoc->delete($id);

    self::json(["response" => $response?'ok':'error']);
  }else{
    self::json(["response" => "existe"]);
  }
  }else{
    self::json(["errortoken" => "token-invalid"]);
  }
}

/// proceso para registrar tipo documento

private static function GuardarTipoDocumento()
{
  
  // consultamos si existe ese tipo de documento a registrar

  $TipoDoc = self::$modelTipoDoc->query()->Where("name_tipo_doc","=",self::post("name-tipo-doc"))->first();
       
  if($TipoDoc)
  {
   self::Session('mensaje','existe');

   self::Session("name-tipo-doc",self::post("name-tipo-doc"));
   self::RedirectTo("new-tipo-documento"); 
  }
  else{
   /// registramos al tipo documento
   self::Session("mensaje",self::$modelTipoDoc->Insert([
     "name_tipo_doc"=>self::post("name-tipo-doc")
     
   ]));  
   self::RedirectTo("tipo-documentos-existentes"); 
  }
}

/// proceso para actualizar tipo documento

public static function update($id)
{
  self::NoAuth();/// si no esta authenticado, redirige al login
  if(self::ValidateToken(self::post("token_")))
  {
     /// verificar si existe ese tipo de documento

     self::$modelTipoDoc = new TipoDocumento;

     $Documento = self::$modelTipoDoc->query()->Where("name_tipo_doc","=",self::post("documento"))->first();

     if($Documento)
     {
      echo "existe";
     }
     else{
       
      /// actualizamo datos

     echo self::$modelTipoDoc->Update([
        "id_tipo_doc"=>$id,
        "name_tipo_doc"=>self::post("documento")
      ]);
      
     }
  }
}

public static function delete_($id)
{
  self::NoAuth();/// si no esta authenticado, redirige al login
  if(self::ValidateToken(self::post("token_")))
  {
    /// eliminamos el registro

    self::$modelTipoDoc = new TipoDocumento;

    echo self::$modelTipoDoc->Update([
      "id_tipo_doc"=>$id,
      "estado"=>"0"
    ]);
  }
}

/// mostramos los tipos documentos en formato json
public static function showTipoDocumentos()
{
  self::NoAuth();/// si no esta authenticado, redirige al login
  if(self::ValidateToken(self::get("token_")))
  {
    /// instanciamos la model tipo documentos
    self::$modelTipoDoc = new TipoDocumento;

    /// obtenemos los tipos de documentos
    $Tipo_Documentos =  self::$modelTipoDoc->query()->Where("estado","=",1)->get();
    
    
    /// enviamos en formato json

    echo json_encode([
      "response" => $Tipo_Documentos,
      "status" => 200
    ]);

  }
}

/// crear tipo documento por ajax
public static function save_tipo_doc()
{
  self::NoAuth();
  /// validamos el token
  if(self::ValidateToken(self::post("token_")))
  {
    /// proceso de guardar tipo documento

    // consultamos si existe ese tipo de documento a registrar

    self::$modelTipoDoc = new TipoDocumento;

    $TipoDoc = self::$modelTipoDoc->query()->Where("name_tipo_doc","=",self::post("tipo-doc"))->first();
    
    if($TipoDoc)
    {
      echo json_encode([
        "response"=>"existe"
      ]);
    }
    else
    {
      $Response = self::$modelTipoDoc->Insert([
        "name_tipo_doc"=>self::post("tipo-doc")
      ]); 

      echo json_encode([
        "response"=>$Response
      ]);
    }
    
  }
}

}