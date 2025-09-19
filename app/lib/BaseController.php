<?php 
namespace lib;

class BaseController extends View
{
 
  public static  array $profile =['Director','Admisión','Paciente','Médico','Enfermera-Triaje','Farmacia'];
  /// traits

   use ConfigDirectories,Csrf,Upload,Fecha,Email;

  /**
   * Método que apunta la ruta exacta
   */

   public function route(string $routeController)
   {
    return URL_BASE.$routeController;
   }

   /***************** No eliminar el contenido que se escirieb en el input ******************** */

   public static function old(string $NameInput)
   {
      $Valor="";

      if(self::ExistSession($NameInput))
      {
        $Valor = self::getSession($NameInput);

        /// eliminamos la session del input

        self::destroySession($NameInput);
      }

      return $Valor;
   }
  
}