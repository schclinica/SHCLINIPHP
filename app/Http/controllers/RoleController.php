<?php 
namespace Http\controllers;

use lib\BaseController;
use models\Role;
use models\UsuarioRole;

class RoleController extends BaseController
{
    /** MOSTRAR LA VISTA PRINCIPAL DE ROLES */
    public static function index(){
        self::NoAuth();
        self::View_("auth.roles");
    }

    /// registrar un nuevo rol
    public static function store(){
        self::NoAuth();

        if(self::ValidateToken(self::post("token_"))){
            $role = new Role;

            $ExisteRole = $role->query()->Where("nombre_rol","=",trim(self::post("rolename")))->get();

            if($ExisteRole){
                self::json(["existe" => "EL ROL QUE DESEAS CREAR YA EXISTE!!!"]);
            }else{
                $response = $role->Insert([
                    "nombre_rol" => trim(self::post("rolename"))
                ]);

            if($response){self::json(["response" => "ROL CREADO CORRECTAMENTE!!!"]);}else{self::json(["error" => "ERROR AL REGISTRAR ROL!!!" ]);}
            }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }
    /// mostrar a los roles
    public static function showRoles(){
        self::NoAuth();
        $role = new Role;

        $allRoles = $role->query()->get();

        self::json(["roles" => $allRoles]);
    }
    /// INACTIVAR ROL
    public static function eliminar($id){
        self::NoAuth();

        if(self::ValidateToken(self::post("token_"))){
            $role = new Role;
            $respuesta = $role->Update([
                "id_rol" => $id,
                "deleted_at" => self::FechaActual("Y-m-d H:i:s")
            ]);
            if($respuesta){self::json(["response" => "ROL ELIMINADO CORRECTAMENTE!!!"]);}else{self::json(["error" => "ERROR AL ELIMINAR ROL!!!" ]);}
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }

    /// ACTIVAR AL ROL SELECCIONADO
    public static function activar($id){
        self::NoAuth();

        if(self::ValidateToken(self::post("token_"))){
             $role = new Role;
             $respuesta = $role->Update([
                "id_rol" => $id,
                "deleted_at" => null
            ]);
            if($respuesta){self::json(["response" => "ROL HABILITADO NUEVAMENTE!!!"]);}else{self::json(["error" => "ERROR AL HABILITAR ROL!!!" ]);}
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }

    /// FORZAR ELIMINADO DEL ROL
    public static function forzarEliminado($id){
        self::NoAuth();

        if(self::ValidateToken(self::post("token_"))){
            $role = new Role;

            $response = $role->delete($id);
            if($response){
                self::json(["response" => "ROL BORRADO CORRECTAMENTE!!!"]);
            }else{
                self::json(["error" => "ERROR AL BORRAR AL ROL SELECCIONADO!!!"]);
            }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }

    /// GUARDAR CAMBIOS (MODIFICAR DATOS DEL ROL)
    public static function modificar($id){
        self::NoAuth();

        if(self::ValidateToken(self::post("token_"))){
            $role = new Role;
            $ExisteRole = $role->query()->Where("nombre_rol","=",self::post("namerole"))->get();

            if($ExisteRole){
                self::json(["existe" => "NO HAY NINGUN CAMBIOS REALIZADO!!!"]);
                exit;
            }
            $response = $role->Update([
                "id_rol" => $id,
                "nombre_rol" => self::post("namerole")
            ]);
             if($response){self::json(["response" => "ROL MODIFICADO CORRECTAMENTE!!!"]);}else{self::json(["error" => "ERROR AL MODIFICAR ROL!!!" ]);}
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }

    /// VER LOS ROLES QUE AUN NO HAN SIDO ASIGNADO A UN USUARIO
    public static function showRolesNotAsignedUser($id){
        self::NoAuth();
 
        $role = new Role;
         $datosRoles = $role->procedure("proc_consultas","C",[$id,1]);
        
        self::json(["roles" => $datosRoles]);
    }
    /// MOSTRAR LOS ROLES QUE HAN SIDO ASINADOS A LOS USUARIOS
    public static function showRolesAsignedUser($id){
        self::NoAuth();
 
        $role = new Role;
         $datosRoles = $role->procedure("proc_consultas","C",[$id,2]);
        
        self::json(["roles" => $datosRoles]);
    }

    /// ASIGNAR ROLES AL USUARIO
    public static function AsignarRoleUser($id){
        self::NoAuth();

        if(self::ValidateToken(self::post("token_"))){
            if(!isset($_SESSION["roleuser"])){
                $_SESSION["roleuser"] = [];
            }
            $role = new Role;

            $rolData = $role->query()->Where("id_rol","=",$id)->get();

            /// verificamos si el rol existe
            if(!array_key_exists($rolData[0]->id_rol,$_SESSION["roleuser"])){
                $_SESSION["roleuser"][$rolData[0]->id_rol]["nombrerol"] = $rolData[0]->nombre_rol;
                $_SESSION["roleuser"][$rolData[0]->id_rol]["idrol"] = $rolData[0]->id_rol;
                self::json(["response" => "rol agregado"]);
            }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }

    /// QUITAR ROL DE LA LISTA
    public static function quitarRoleList($id){
        self::NoAuth();

        if(self::ValidateToken(self::post("token_"))){
            $role = new Role;
            $rol = $role->query()->Where("id_rol","=",$id)->get();
            /// VERIFICAMOS SI EL QUE DESEAMOS ELIMINAR EXISTE EN A LISTA DE SESION
            if(isset($_SESSION["roleuser"][$rol[0]->id_rol])){
              /// eliminamos de la lista
              unset($_SESSION["roleuser"][$rol[0]->id_rol]);
              self::json(["response" => "ROL QUITADO DE LA LISTA CORRECTAMENTE!!!"]);
            }else{
              self::json(["error" => "ERROR AL QUITAR ROL DE LA LA LISTA!!!"]);
            }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }
    /// GUARDAR ROLES AL USUARIO
    public static function GuardarRolesDelUsuario($id){

        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
            if(self::ExistSession("roleuser") && count($_SESSION["roleuser"]) ){
                foreach(self::getSession("roleuser") as $role){
                    $rol = new UsuarioRole;

                    $Value = $rol->Insert([
                        "usuario_id"  => $id,
                        "rol_id" => $role["idrol"]
                    ]);
                }

                if($Value){self::json(["response" => "ROLES ASIGNADOS CORRECTAMENTE AL USUARIO!!!"]);self::destroySession("roleuser");}else{self::json(["erro" => "ERROR AL GUARDAR LOS ROLES DEL USUARIO!!"]);}
            }else{
                self::json(["error" => "ASIGNE POR LO MENOS UN ROL AL USUARIO PARA GUARDAR!!!"]);
            }
        }else{
            self::json(["error" => "ERROR , TOKEN INCORRECTO!!!"]);
        }
    }

    /// BORRAR TODOS LOS ROLES AÑADIDOS A LA LISTA DE SESION
    public static function clearRolesUser(){
        self::NoAuth();

        if(self::ValidateToken(self::post("token_"))){
            if (self::ExistSession("roleuser")) {
                self::destroySession("roleuser");
                self::json(["response" => "LISTA DE ROLES LIMPIADOS!!!"]);
            } else {
                self::json(["error" => "ERROR, NO HAY NADA PARA CANCELAR!!!"]);
            }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }
    /// QUITAR ROL DE LA LISTA DEL USUARIO
    public static function eliminarRoleListUser($id){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
            $roleUser = new UsuarioRole;

            $response = $roleUser->delete($id);
            if($response){
                self::json(["response" => "ROL QUITADO DE LA LISTA CORRECTAMENTE!!!"]);
            }else{
                self::json(["error" => "ERROR AL INTENTAR ELIMINAR AL ROL DEL USUARIO!!"]);
            }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!"]);
        }
    }
    /// MOSTRAR AQUELLOS PREVILEGIOS QUE TODAVIA NO HAN SIDO ASIGNADOS AL ROL SELECCIONADO
    public static function showPrevilegiosNoAsignedRole($id){
      self::NoAuth();

      $role = new Role;

      $previlegios_no_asignados = $role->procedure("proc_consultas","C",[$id,3]);

      self::json(["permisosnoasignados" => $previlegios_no_asignados]);
    } 
}