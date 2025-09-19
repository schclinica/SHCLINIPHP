<?php
namespace Http\controllers;

use lib\BaseController;
use models\Modulo;

class ModuloController extends BaseController{

    /** REGISTRAR NUEVOS MODULOS */
    public static function store(){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){

            /// verificamos que se ingrese si o si el nombre del modulo
            if(self::post("moduloname") == null){self::json(["errormodulo" => "INGRESE EL NOMBRE DEL MODULO!!!"]);exit;}
            $modulo = new Modulo;
            /// VERIFICAMOS SI EXISTE
            $ExisteModulo = $modulo->query()->Where("moduloname","=",self::post("moduloname"))->get();
            if($ExisteModulo){self::json(["existe" => "EL MODULO QUE DESEAS REGISTRAR YA EXISTE!!"]);return;}
            $response = $modulo->Insert([
                "moduloname" => trim(self::post("moduloname"))
            ]);
            $response ? self::json(["response" => "MODULO REGISTRADO CORRECTAMENTE!!"]) : self::json(["error" =>"ERROR AL REGISTRAR MODULO!!!"]);
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }

    /** MOSTRAR A TODOS LOS MODULOS EXISTENTES */
    public static function showModulos(){
        self::NoAuth();

        $modulo = new Modulo;
        $modulos = $modulo->query()->get();

        self::json(["modulos" => $modulos]);
    }

    /** MOSTRAR LOS MODULOS QUE ESTAN DISPONIBLES */
    public static function showModulesDisponibles(){
        self::NoAuth();

        $modulo = new Modulo;
        $modulos = $modulo->query()->Where("deleted_at","is",null)->orderBy("moduloname","asc")->get();

        self::json(["modulos" => $modulos]);
    }
    /** DESHABILITAR Y HABILITAR AL MODULO */
    public static function eliminar($id){
        self::NoAuth();
        
        if(self::ValidateToken(self::post("token_"))){
            $modulo = new Modulo;

            $response = $modulo->Update([
                "id_modulo" => $id,
                "deleted_at" => self::FechaActual("Y-m-d H:i:s")
            ]);
            $response ? self::json(["response" => "MODULO ELIMINADO CORRECTAMENTE!!"]) : self::json(["error" => "ERROR AL ELIMINAR MODULO!!!"]);
   
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }
    /** HABILITAR Y HABILITAR AL MODULO */
    public static function habilitar($id){
        self::NoAuth();
        
        if(self::ValidateToken(self::post("token_"))){
            $modulo = new Modulo;

            $response = $modulo->Update([
                "id_modulo" => $id,
                "deleted_at" => null
            ]);
            $response ? self::json(["response" => "MODULO HABILITADO NUEVAMENTE!!"]) : self::json(["error" => "ERROR AL HABILITAR MODULO SELECCIONADO!!!"]);
   
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }
}