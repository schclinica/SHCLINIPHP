<?php
namespace Http\controllers;

use lib\ApiPeru;
use lib\BaseController;

class ApiPeruController extends BaseController{

    /** CONSULTAR DATOS DE LA PERSONA POR  DNI */
    public static function BuscarPersonaDni(){
        self::NoAuth();
         
           ApiPeru::SearchDataPersonDni(self::post("dni"));
 
        
    }

}