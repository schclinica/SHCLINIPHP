<?php 
namespace config;

use PDO;

trait Conection 
{

/// variable que recibe la conexión
 private static $Conector = null;

 /// variable para realizar consultas
 public static string $Query;

 public static $Result = null;

 public  static $PPS = null;

 /* Realiza la conexión a la base de datos */

 public static function getConection()
 {
    try {
      self::$Conector = new PDO(DRIVER,$_ENV['USERNAME'],$_ENV["PASSWORD"]);

      self::$Conector->exec("set names utf8");

    } catch (\Throwable $th) {
       echo "<h1>".$th->getMessage()."</h1";
    }

    return self::$Conector;
 }

 /** Cerrar la conexión */

 public static function closeDataBase()
 {
    if(self::$Conector != null){self::$Conector = null;}

    if(self::$Result != null){self::$Result = null;}

    if(self::$PPS != null){self::$PPS = null;}
 }
}