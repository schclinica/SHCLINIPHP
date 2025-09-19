<?php
namespace lib;

trait Csrf 
{

   /// hacemos uso del trait session

   use Session;

   /// propiedades
   private static string $NameToken = "token";

 /*=======================================
  Crear el token 
 =========================================*/

 public static function Csrf_Token(){

   /// creamos el token
   //$Token =md5(uniqid(mt_rand(), true));
   $Token = bin2hex(random_bytes(32));/// token cifrado

   /// asignamos a una variable de session el token generado

   if(!self::ExistSession(self::$NameToken))
   {
    self::Session(self::$NameToken,$Token);
   }

    /// RETORNAMOS EL TOKEN

    return self::getSession(self::$NameToken);
 }

 /// generar el csrf 
 public static function Csrf()
 {
     /// creamos el token
   //$Token =md5(uniqid(mt_rand(), true));
   $Token = bin2hex(random_bytes(32));/// token cifrado

   $input = '';

   if(!self::ExistSession(self::$NameToken))
   {
    $input = '<input name="token_" value="'.$Token.'">';
   }

  echo $input;
 }

 /*============================
  Validamos el token
 ===============================*/

 public static function ValidateToken(string $token)
 {
   /// VERIFICAMOS EL TOKEN GENERADO CON EL TOKEN QUE ENVIA EL USUARIO
    if(self::ExistSession(self::$NameToken) and self::getSession(self::$NameToken) === $token)
    {
        return true;
    }

         /// caso que el token del csrf no coincidan , muestra este error 405
    	 header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
 		   exit;
 }
}