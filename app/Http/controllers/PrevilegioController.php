<?php 
namespace Http\controllers;

use lib\BaseController;
use models\Permision;
use models\Role_Permisos;

class PrevilegioController extends BaseController{

    private static array $Errors = [];
    /** MOSTRAR LA VISTA DE GESTION DE PREVILEGIOS */
    public static function index(){

        self::NoAuth();

        self::View_("auth.permission");
    }

    /// GUARDAR EL PERMISO
    public static function savePermiso(){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){

            self::ValidateFormSavePrevilegio();
            $permission = new Permision;
            /// verificamos la existencia del permisso
            $ExistePermiso = $permission->query()->Where("nombre_previlegio","=",trim(self::post("name_previlegio")))
            ->Or("alias_previlegio","=",trim(self::post("alias_previlegio")))->get();

            if($ExistePermiso){self::json(["existe" => "EL PERMISO QUE DESEAS REGISTRAR YA EXISTE!!"]);return;}
            $response = $permission->Insert([
                "nombre_previlegio" => trim(self::post("name_previlegio")),
                "modulo_id" => self::post("modulo"),
                "alias_previlegio" => trim(self::post("alias_previlegio"))
            ]);

            $response ? self::json(["response" => "PERMISO REGISTRADO CORRECTAMENTE!!!"]) : self::json(["error" => "ERROR AL REGISTRAR PERMISO!!!"]);
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }

    private static function ValidateFormSavePrevilegio(){

        if(self::post("name_previlegio") == null){
             self::$Errors["name_previlegio"] = "INGRESE NOMBRE DEL PREVILEGIO!!";
        }

        if(self::post("alias_previlegio") == null){
            self::$Errors["alias_previlegio"] = "INGRESE ALIAS DEL PREVILEGIO!!";
        }

        if(count(self::$Errors) > 0){
            self::json(["errors" => self::$Errors]);
            exit;
        }
    }
    /// MOSTRAR LOS PREVILEGIOS EXISTENTES
    public static function showPrevilegios(){
        self::NoAuth();

        $previlegio = new Permision;

        $permisos = $previlegio->query()
        ->select("pr.id_previlegio","pr.nombre_previlegio","pr.alias_previlegio","mods.moduloname","pr.modulo_id","pr.daleted_at")
        ->Join("modulos as mods","pr.modulo_id","=","mods.id_modulo")->get();

        self::json(["permisos" => $permisos]);
    }

    /// QUITAR DE LISTA A LOS PREVILEGIOS(ENVIAR A LA PAPELERA => INACTIVAR)
    public static function eliminar($id){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
            $previlegio = new Permision;
            $response = $previlegio->Update([
                "id_previlegio" => $id,
                "daleted_at" => self::FechaActual("Y-m-d H:i:s")
            ]);

            $response ? self::json(["response" => "PREVILEGIO INHABILITADO CORRECTAMENTE!!!"]): self::json(["error" => "ERROR AL INHABILITAR AL PREVILEGIO SELECCIONADO!!!"]);
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }
    /// HABILITAR EL PREVILEGIO
    public static function activar($id){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
            $previlegio = new Permision;
            $response = $previlegio->Update([
                "id_previlegio" => $id,
                "daleted_at" => null
            ]);

            $response ? self::json(["response" => "PREVILEGIO HABILITADO CORRECTAMENTE!!!"]): self::json(["error" => "ERROR AL HABILITAR AL PREVILEGIO SELECCIONADO!!!"]);
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }
    /// BORRAR AL PERMISO SELECCIONADO
    public static function borrar($id){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
            $permiso = new Permision;
            $response = $permiso->delete($id);
            $response ? self::json(["response" => "PERMISO BORRADO CORRECTAMENTE!!!"]): self::json(["error" => "ERROR AL BORRAR PERMISO SELECCIONADO!!!"]); 
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }

    /// MODIFICAR DATOS DEL PREVILEGIO
 public static function modificar($id){
    self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
            $permiso = new Permision;
            $response =$permiso->Update([
                "id_previlegio" => $id,
                "modulo_id" => self::post("modulo"),
                "nombre_previlegio" => trim(self::post("nombre_previlegio")),
                "alias_previlegio" => trim(self::post("alias_previlegio"))
            ]);
            $response ? self::json(["response" => "PERMISO MODIFICADO CORRECTAMENTE!!!"]): self::json(["error" => "ERROR AL MODIFICAR PERMISO SELECCIONADO!!!"]); 
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }

    /** LA ESCUCHA DE TODOS LOS PREVILEGIOS */
    public static function allPrevilegios(){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
            $previlegio = new Permision;
           if(self::post("todos") === "true"){
              $permisosAll = $previlegio->procedure("proc_previlegios_no_asignados_rol_modulo","C",[self::post("rol"),self::post("modulo")]);
             
            if(!isset($_SESSION["permisos_role"])){
                $_SESSION["permisos_role"] = [];
            }
            foreach($permisosAll as $permiso){
                if(!array_key_exists($permiso->id_previlegio,$_SESSION["permisos_role"])){
                $_SESSION["permisos_role"][$permiso->id_previlegio]["permiso"] = $permiso->nombre_previlegio;
                $_SESSION["permisos_role"][$permiso->id_previlegio]["permisoid"] = $permiso->id_previlegio;
               }

            }
           }else{
             self::CancelAsignedPermisosRole();
           }
        }else{
            self::json(["error" => "TOKEN INCORRECTO!!"]);
        }
    }

     public static function addQuitarPrevilegio(){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
            $previlegio = new Permision;
           if(self::post("check") === "true"){
              $permiso = $previlegio->query()->Where("id_previlegio","=",self::post("idprev"))->get();
             
            if(!isset($_SESSION["permisos_role"])){
                $_SESSION["permisos_role"] = [];
            }
             
            if(!array_key_exists($permiso[0]->id_previlegio,$_SESSION["permisos_role"])){
                $_SESSION["permisos_role"][$permiso[0]->id_previlegio]["permiso"] = $permiso[0]->nombre_previlegio;
                $_SESSION["permisos_role"][$permiso[0]->id_previlegio]["permisoid"] = $permiso[0]->id_previlegio;
                self::json(["response" => "Permisos agregado a lista"]);
            }
           }else{
             if(isset($_SESSION["permisos_role"][self::post("idprev")])){
                unset($_SESSION["permisos_role"][self::post("idprev")]);
                self::json(["response" => "permisos quitado"]);
             }
           }
        }else{
            self::json(["error" => "TOKEN INCORRECTO!!"]);
        }
    }

    /// GUARDAR LOS PERMISOS ASIGNADOS A LOS ROELS
    public static function savePermissionsAsignedRole(int $rol){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
            if(self::ExistSession("permisos_role") && count(self::getSession("permisos_role"))> 0){
                $permisorole = new Role_Permisos;
                foreach(self::getSession("permisos_role") as $permiso){
                    $Value = $permisorole->Insert([
                        "role_id" => $rol,
                        "previlegio_id" => $permiso["permisoid"]
                    ]);
                }
                if($Value){
                    self::json(["response" => "PERMISOS ASIGNADOS CORRECTAMENTE AL ROL SELECCIONADO!!"]);
                    self::CancelAsignedPermisosRole();
                }else{
                    self::json(["error" => "ERROR AL ASIGNAR PERMISOS!!"]);
                }
                /**PROGRAMAR ESTE PROCESO MAÑANA JUEVES */
            }else{
                self::json(["error" => "SELECCIONE POR LO MENOS UN PERMISO PARA ASIGNAR AL ROL SELECCIONADO!!"]);
            }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }
    /// cancelar el asignado de permisos
    private static function CancelAsignedPermisosRole(){
        
        if(self::ExistSession("permisos_role")){
            self::destroySession("permisos_role");
        }
    }
    /// BOTON SALIR Y CANCELAR EL ASIGNADO DE PERMISOS AL ROL
    public static function CancelarPermisosRole(){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
            self::CancelAsignedPermisosRole();
        }
    }
}