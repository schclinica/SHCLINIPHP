<?php 
namespace business;

use lib\Request;
use models\Sede;

class SedeBussines{
     use Request;
    /** REGISTRAR LA SEDE */
    public static function Guardar(){

        $sede = new Sede;
        /// Validamos la existencia de la sede
        $ExisteSede = $sede->query()->Where("namesede", "=", self::post("namesede"))->get();

        if ($ExisteSede) {
            self::json(["existe" => "LA SUCURSAL QUE DESEAS REGISTRAR YA EXISTE!!!"]);
            return;
        }
        $respuesta = $sede->Insert([
            "namesede" => self::post("namesede"),
            "telefono_sede" => self::post("phone"),
            "correo_sede" => self::post("correo"),
            "distritosede_id" => self::post("distrito"),
            "direccion_sede" => self::post("direccion")
        ]);


        if ($respuesta) {
            self::json(["response" => "SUCURSAL REGISTRADO CORRECTAMENTE!!!"]);
        } else {
            self::json(["error" => "ERROR AL REGISTRAR LA SUCURSAL!!!"]);
        }
    }

    /**MODIFICAR LA SEDE */
    public static function modificar($id){
        $sede = new Sede;
        $ExisteSede = $sede->query()->Where("id_sede", "=", $id)->get();
        $respuesta = $sede->Update([
            "id_sede" => $id,
            "namesede" => self::post("namesede"),
            "telefono_sede" => self::post("phone"),
            "correo_sede" => self::post("correo"),
            "distritosede_id" => self::post("distrito") == "null" ? $ExisteSede[0]->distritosede_id  : self::post("distrito"),
            "direccion_sede" => self::post("direccion")
        ]);


        if ($respuesta) {
            self::json(["response" => "SUCURSAL MODIFICADO CORRECTAMENTE!!!"]);
        } else {
            self::json(["error" => "ERROR AL MODIFICAR LA SUCURSAL!!!"]);
        }
    }

    /** ENVIAR A LA PAPELERA A LA SEDE */
    public static function eliminar($id,string $fecha){
        $sede = new Sede;
        $response = $sede->Update([
            "id_sede" => $id,
            "deleted_at" => $fecha
        ]);

        if ($response) {
            self::json(["response" => "SUCURSAL ELIMINADO CORRECTAMENTE!!!"]);
        } else {
            self::json(["error" => "ERROR AL ELIMINAR LA SUCURSAL!!!"]);
        }
    }

    /* HABILITAR LA SEDE NUEVAMENTE */
     public static function activar($id){
        $sede = new Sede;
        $response = $sede->Update([
            "id_sede" => $id,
            "deleted_at" => null
        ]);

        if ($response) {
            self::json(["response" => "SUCURSAL HABILITADO CORRECTAMENTE!!!"]);
        } else {
            self::json(["error" => "ERROR AL HABILITAR LA SUCURSAL!!!"]);
        }
    }

    /* BORRAR LA SEDE  */
     public static function borrar($id){
        $sede = new Sede;
        $response = $sede->delete($id);

        if ($response) {
            self::json(["response" => "SUCURSAL BORRADO CORRECTAMENTE!!!"]);
        } else {
            self::json(["error" => "ERROR AL BORRAR LA SUCURSAL!!!"]);
        }
    }
    
}