<?php 
namespace lib;

trait Request 
{

 /*==================================
 Validamos por POST
 =====================================*/

 public static function post(string $name)
 {
    if(isset($_POST[$name]))
    {
        return !empty($_POST[$name])? $_POST[$name]:null;
    }

    return null;
 }

 /// Entrada de datos por petición GET
 public static function get(string $name)
 {
    if(isset($_GET[$name]))
    {
        return !empty($_GET[$name])? $_GET[$name]:'';
    }

    return '';
 }


  /// Entrada de datos por petición GET
  public static function request(string $name)
  {
     if(isset($_REQUEST[$name]))
     {
         return !empty($_REQUEST[$name])? $_REQUEST[$name]:null;
     }
 
     return null;
  }
 
 public static function file_(string $name)
 {
    if(isset($_FILES[$name]))
    {
        return $_FILES[$name];
    }

    return null;
 }

 public static function file_Type(string $name)
 {
   if(self::file_($name) != null)
   {
      return self::file_($name)['type'];
   }
 }

 public static function file_size(string $name)
 {
  
      return self::file_($name)['size'];
    
 }

 public static function file_Name(string $name)
 {
  
      return self::file_($name)['name'];
    
 }

 public static function ContentFile(string $name)
 {
  
      return self::file_($name)['tmp_name'];
    
 }



 public static function onClick($Name)
 {
    return isset($_REQUEST[$Name]);
 }

 /***************** REDIRIGIR A UNA PAGINA ******************** */
 public static function RedirectTo(string $redirect)
 {
   header("location:".URL_BASE.$redirect);
 }

 public static function json(array $data,$code = 200):void
 {
   http_response_code($code);
   echo json_encode($data);
 }

 /// eliminar variables de un array
 public static function destroyVariableArray(array $data,$VariableDelete)
 {
   for($i=0;$i<count($data);$i++)
   {
      unset($data[$i]->$VariableDelete);
   }
 }


}