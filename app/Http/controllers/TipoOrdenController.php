<?php
namespace Http\controllers;

use business\TipoOrdenBusines;
use lib\BaseController;

class TipoOrdenController extends BaseController{
    private static string  $TipoArchivoAceptable = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
    /**
     * Registrar nuevos tipos de ordenes
     */
    public static function store(){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
            self::json(["response" => TipoOrdenBusines::RegistrarTipoOrden(
                self::post("codigo_tipo_orden"),
                self::post("nombre_tipo_orden"))]);
        }else{
            self::json(["error" => "token invalid!!!"]);
        }
    }

    /**
     * PROCESO PARA IMPORTAR DATOS DESDE EXCEL A TIPO ORDEN
     */
    public static function importar(){
        self::NoAuth();
    if(self::ValidateToken(self::post("token_")))
    {
      /**
       * Obtenemos al file seleccionado, si o si tiene que ser un excel
       */
        if(self::file_size("archivo_excel_tipo_orden") > 0)
        {
          # Verificamos si el archivo seleccionado es un excel
          if(self::file_Type("archivo_excel_tipo_orden") === self::$TipoArchivoAceptable)
          {
            $ArchivoSelect = self::ContentFile("archivo_excel_tipo_orden");
            self::json(["response" => TipoOrdenBusines::ImportarDatosTipoOrden($ArchivoSelect)]);
          }else
          {
            self::json(['response'=>"archivo no aceptable"]);
          }
        }
        else
        {
          self::json(['response'=>"vacio"]);
        }
    }
    }

    /** MOSTRAR LAS ORDENES DISPONIBLES */
    public static function showTipoOrdenes_Disponibles(){
        self::NoAuth();
        self::json(["tipo_ordenes" => TipoOrdenBusines::showTipoOrdenesDisponibles()]);
    }

    /** MOSTRAR LOS TIPOS ORDENES  */
    public static function showTipoOrdenes(){
        self::NoAuth();
        self::json(["tipo_ordenes" => TipoOrdenBusines::showTipoOrdenesAll()]);
    }

    /** modificar  */
    public static function modificar($id){
      if(self::ValidateToken(self::post("token_"))){
        self::json(["response" => TipoOrdenBusines::modificarTipoOrden($id,
         self::post("codigo_tipo_orden"),self::post("nombre_tipo_orden")
        )]);
      }else{
        self::json(["error" => "token invalid!!"]);
      }
    }

    /** eliminar tipo examen de la lista*/
    public static function eliminar($id){
      if(self::ValidateToken(self::post("token_"))){
        self::json(["response" => TipoOrdenBusines::eliminarTipoOrden($id,self::FechaActual("Y-m-d H:i:s"))]);

      }else{
        self::json(["error" => "token invalid!!"]);
      }
    }

    /** Activar tipo examen de la lista*/
    public static function activar($id){
      if(self::ValidateToken(self::post("token_"))){
        self::json(["response" => TipoOrdenBusines::ActivarTipoOrden($id)]);

      }else{
        self::json(["error" => "token invalid!!"]);
      }
    }

     /** Activar tipo examen de la lista*/
     public static function borrar($id){
      if(self::ValidateToken(self::post("token_"))){
        self::json(["response" => TipoOrdenBusines::BorrarTipoOrden($id)]);

      }else{
        self::json(["error" => "token invalid!!"]);
      }
    }
}