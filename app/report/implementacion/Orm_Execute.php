<?php 
namespace report\implementacion;

use report\repository\Orm;

class Orm_Execute 
{
 private static  $Orm;

 

 /**
  * Get the value of Orm
  *
  * @return Orm
  */
 public static function getOrm(): Orm
 {
  return self::$Orm;
 }

 /**
  * Set the value of Orm
  *
  * @param Orm $Orm
  */
 public static function setOrm(Orm $Orm)
 {
  self::$Orm = $Orm;
 
 }
}