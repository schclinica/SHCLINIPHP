<?php
namespace Http\controllers;
use lib\BaseController;
use models\Usuario;
use report\repository\CrudRepository;

class UsuarioController extends BaseController
{

private static CrudRepository $ModelUsuario;

public function __construct()
{
}
 
public static function create()
{
   
  self::$ModelUsuario = new Usuario;
  
  $data = self::$ModelUsuario->all();

  self::View_("usuario.IndexView",compact("data"));

}

}