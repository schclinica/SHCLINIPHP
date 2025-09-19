<?php
namespace Http\controllers;

use business\SedeBussines;
use Http\pageextras\PageExtra;
use lib\BaseController;
use models\Sede;

class SedesController extends BaseController {

    /**
     * MOSTRAR LA VISTA DE MOSTRAR TODAS LAS SEDES
     */
    public static function ViewGestionSedes(){
       self::NoAuth();

       if(self::profile()->rol === 'admin_general'){
         self::View_("sedes.index");
       }else{
        PageExtra::PageNoAutorizado();
       }
    }
    /**
     * Registrar las sedes
     */
    public static function save(){
        self::NoAuth();

        if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general'){
            if(self::ValidateToken(self::post("token_"))){   
              SedeBussines::Guardar();
            }else{
                self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
            }
        }else{
            self::json(["error" => "NO ESTAS AUTORIZADO PARA REALIZAR ESTA ACCIÓN!!!"]);
        }
    }

    /**MOSTRAR LAS SEDES */
    public static function showSedes(){
       self::NoAuth();
       if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general'){
       $sede = new Sede;

       $sedes = $sede->query()->LeftJoin("distritos as dis","sed.distritosede_id","=","dis.id_distrito")
       ->select("sed.namesede","sed.id_sede","sed.telefono_sede","sed.correo_sede","sed.direccion_sede",
       "dep.name_departamento","prov.name_provincia","dis.name_distrito","sed.distritosede_id","sed.deleted_at",
       "dep.id_departamento","prov.id_provincia")
       ->LeftJoin("provincia as prov","dis.id_provincia","=","prov.id_provincia")
       ->LeftJoin("departamento as dep","prov.id_departamento","=","dep.id_departamento")
       ->get();

       self::json(["sedes" => $sedes]);
     }else{
        self::json(["sedes"=>[]]);
     }
    }

    /** MODIFICAR LA SEDE */
    public static function updateSede($id){
      self::NoAuth();
      if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general'){
          if(self::ValidateToken(self::post("token_"))){
             SedeBussines::modificar($id);
          }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
          }
      }else{
        self::json(["error" => "NO TIENES PERMISO PARA EJECUTAR ESTA ACCION!!"]);
      }

    }
    /**ELIMINAR LA SEDE */
    public static function EliminarSede($id){
      self::NoAuth();
      if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general'){
          if(self::ValidateToken(self::post("token_"))){
             SedeBussines::eliminar($id,self::FechaActual("Y-m-d H:i:s"));
          }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
          }
      }else{
        self::json(["error" => "NO TIENES PERMISO PARA EJECUTAR ESTA ACCION!!"]);
      }

    }
    /**HABILITAR LA SEDE */
    public static function HabilitarSede($id){
      self::NoAuth();
      if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general'){
          if(self::ValidateToken(self::post("token_"))){
             SedeBussines::activar($id);
          }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
          }
      }else{
        self::json(["error" => "NO TIENES PERMISO PARA EJECUTAR ESTA ACCION!!"]);
      }

    }

     /**BORRAR LA SEDE */
    public static function BorrarSede($id){
      self::NoAuth();
      if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general'){
          if(self::ValidateToken(self::post("token_"))){
             SedeBussines::borrar($id);
          }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
          }
      }else{
        self::json(["error" => "NO TIENES PERMISO PARA EJECUTAR ESTA ACCION!!"]);
      }

    }
    /**MOSTRAR LAS SEDES QUE SOLO ESTAN DISPONIBLES */
    public static function showSedesDisponibles(){
       self::NoAuth();
       if(self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1] || self::profile()->rol === self::$profile[4] || self::profile()->rol === 'admin_general' || self::profile()->rol === 'Médico'){
       $sede = new Sede;

       $sedes = $sede->query()->Where("deleted_at","is",null)->get();

       self::json(["sedes" => $sedes]);
     }else{
        self::json(["sedes"=>[]]);
     }
    }
}