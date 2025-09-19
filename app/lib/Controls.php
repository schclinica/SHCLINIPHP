<?php 
/***** método que imprime el valor de una variable de entorno */
function env($name)
{
  return  $_ENV[$name];
}

/** calculando el tiempo de vida de la cookie */
function vidaCookie()
{
    return time()+env("TIEMPO_VIDA_SESSION");/// 1 minuto por defecto
}

/// cifrar un string
function cifrarCookie($data){
  return setcookie("remember",openssl_encrypt($data,env("METHOD_ENCRYPT"),env("CLAVE_ENCRYPT")),vidaCookie(),"/");
}

/// verificamos si existe la cookie
function existeCookie($NameCookie):bool
{
  return isset($_COOKIE[$NameCookie]);
}

/// obteneer el valor de la cookie desencriptada
function getValueCookie()
{
  return openssl_decrypt($_COOKIE['remember'],env("METHOD_ENCRYPT"),env("CLAVE_ENCRYPT"));
}

/// obtener la foto del usuario
function getFoto($foto)
{
  $Directorio = URL_BASE."public/asset/";
  if(empty($foto))
  {
    $Directorio.="img/avatars/anonimo_4.jpg";
  }
  else
  {
    $Directorio.="foto/".$foto;
  }
  return $Directorio;
}

/// generar token para recuperar contraseña
function generateToken()
{
  $Token =bin2hex(openssl_random_pseudo_bytes(32));
  return $Token;
}

/// validamoa que sea un email
function isEmail($mail)
{
 return filter_var($mail,FILTER_VALIDATE_EMAIL);
  
}

function utf8__(string $text)
{
  
  return iconv("UTF-8", "ISO-8859-1//TRANSLIT", $text);
}

function utf8Data(String $text){
  return iconv('ISO-8859-1', 'UTF-8',trim($text));
}

function acentos($cadena) 
{
   $search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±,Ã“,Ã ,Ã‰,Ã ,Ãš,â€œ,â€ ,Â¿,ü");
   $replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ó,Á,É,Í,Ú,\",\",¿,&uuml;");
   $cadena= str_replace($search, $replace, $cadena);

   return $cadena;
}
/// generamos número aleatorio
function DigitsAleatorio($cadena = "0123456789abcdefghijklmnopqrstuvwxyz",$inicio = 1,$fin = 6)
{
  return substr(str_shuffle($cadena),$inicio,$fin);
}

/// encriptar
function hashCifrado(int|string $valor)
{
  return hash_hmac("sha1",''.$valor.'',"rosales");
}

/** CALCULAR LA EDAD DEL PACIENTE EN AÑOS, MESES Y DIAS */
function calcularEdad($fechaNacimiento, $fechaActual) {
  $nacimiento = new DateTime($fechaNacimiento);
  $actual = new DateTime($fechaActual);
  $intervalo = $actual->diff($nacimiento);

  $años = $intervalo->y;
  $meses = $intervalo->m;
  $días = $intervalo->d;

  return [
    'años' => $años,
    'meses' => $meses,
    'días' => $días
  ];
}
 