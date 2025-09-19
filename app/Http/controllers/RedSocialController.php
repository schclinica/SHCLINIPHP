<?php 
namespace Http\controllers;

use lib\BaseController;
use models\RedesSocialesClinicaMedico;
use models\RedSocial;
use models\RedSosial;

class RedSocialController extends BaseController{

    private static  array $Errors = [];

    /// METODO PARA MOSTRAR LA VISTA DE GESTIONAR REDES SOCIALES
    public static function index()
    {
        self::View_("redsocial.index");
    }


    /// método para registrar nueva red social
    public static function save()
    {
      if(self::ValidateToken(self::post("token_")))
      {
       
        if(self::ValidateSaveForm() > 0){

            self::json(["errors" => self::$Errors]);
        }else{
            $redsocial = new RedSocial;

            /// verificamos la existencia

            $ExisteRedSocial = $redsocial->query()->where("nombre_red_social","=",self::post("name_red_social"))->first();

            if($ExisteRedSocial){
                self::json(["existe" => "existe"]);
                exit;
            }
            $response = $redsocial->Insert([
                "nombre_red_social" => self::post("name_red_social"),
                "icono" => self::post("icono_red_social")
            ]);

            self::json(["response" => $response ? 'ok' : 'error']);

            self::$Errors = [];
        }

      }else{
        self::json(["error" => "error-token"]);
      }
    }

    /// validacion del formulario
    private static function ValidateSaveForm(){
       if(self::post("name_red_social") == null){
         self::$Errors [] = "Ingrese el nombre de la red social!";
       }

       if(self::post("icono_red_social") == null){
        self::$Errors [] = "Indique un icono a la red social!";
       }


       return count(self::$Errors);
    }


    /// MOSTRAR LAS REDES SOCIALES REGISTRADOS
    public static function showRedesSociales()
    {
        $redsocial = new RedSocial;

        $redes_sociales = $redsocial->query()->get();

        self::json(["redes_sociales" => $redes_sociales]);
    }

    /// Metodo para quitar de la lista
    public static function eliminar($id)
    {
        if(self::ValidateToken(self::post("token_")))
        {
            $redsocial = new RedSocial;

            $response = $redsocial->Update([
                "id_red_social" => $id,
                "deleted_at" => self::FechaActual("Y-m-d h:i:s")
            ]);

            self::json(["response" => $response ? 'ok' : 'error']);
        }else{
            self::json(["error" => "token-invalid!"]);
        }
    }

    /// mostrar las redes sociales habilitadas
    public static function showRedesSocialesHabilitadas()
    {
        $redsocial = new RedSocial;

        $redes_sociales = $redsocial->query()
        ->LeftJoin("redes_sociales_clinica_medico as rscm","rscm.red_id","=","rs.id_red_social")
       
        ->Where("rscm.red_id","is",null)
        ->And("rs.deleted_at","is",null)
        ->get();

        self::json(["redes_sociales_habilitadas" => $redes_sociales]);
    }
    /// método para activar
    public static function activar($id){
        if(self::ValidateToken(self::post("token_")))
        {
            $redsocial = new RedSocial;

            $response = $redsocial->Update([
                "id_red_social" => $id,
                "deleted_at" => null
            ]);

            self::json(["response" => $response ? 'ok' : 'error']);
        }else{
            self::json(["error" => "token-invalid!"]);
        }
    }

     /// método para  borrar
     public static function borrar($id){
        if(self::ValidateToken(self::post("token_")))
        {
            $redsocial = new RedSocial;

            $response = $redsocial->delete($id);

            self::json(["response" => $response ? 'ok' : 'error']);
        }else{
            self::json(["error" => "token-invalid!"]);
        }
    }

    /// modificar los datos de la red social
    public static function updateRedSocial($id)
    {
        if(self::ValidateToken(self::post("token_")))
        {

            if(self::ValidateSaveForm() > 0){
                self::json(["errors" => self::$Errors]);
            }else{
                $redsocial = new RedSocial;

                $response = $redsocial->Update([
                    "id_red_social" => $id,
                    "nombre_red_social" => self::post("name_red_social"),
                    "icono" => self::post("icono_red_social")
                ]);

                self::json(["response" => $response ? 'ok' : 'error']);
            }

        }else{
            self::json(["error" => "token-invalid!"]);
        }
    }

    /// mostrar las redes sociales de la clinica
    public static function showRedesSocialesClinica()
    {
        $redsocial = new RedesSocialesClinicaMedico;

        $redes_sociales = $redsocial->query()->Join("red_social as rs","rscm.red_id","=","rs.id_red_social")
        ->Where("rscm.pertenece","=","c")
        ->get();

        self::json(["redes_sociales" => $redes_sociales]);
    }

    /// ASIGNAR UNA RED SOCIAL A LA CLINICA
    public static function asignarRedSocialClinica()
    {
        if(self::ValidateToken(self::post("token_")))
        {
            $redsocial = new RedesSocialesClinicaMedico;

            $response = $redsocial->Insert([
                "red_id" => self::post("red"),
                "link_red_social" => self::post("link")
            ]);

            self::json(["response" => $response ? 'ok' : 'error']);
         }else{
            self::json(["error" => "invalid token!"]);
        }
    }

    /// Modificar la red social del usuario
    public static function updateRedSocialUser($id){
        if(self::ValidateToken(self::post("token_"))){
          $redsocial = new RedesSocialesClinicaMedico;

          $response = $redsocial->Update([
            "id_red_social_clim" => $id,
            "link_red_social" => self::post("link")
          ]);

          self::json(["response" => $response ? 'ok' : 'error']);
        }else{
            self::json(["error" => "token-invalid!"]);
        }
    }

    /// Borrar la red social asignado
    public static function borrarRedSocialClinica($id){
        if(self::ValidateToken(self::post("token_")))
        {
            $redsocialClinica = new RedesSocialesClinicaMedico;

            $response = $redsocialClinica->delete($id);

            self::json(["response" => $response ? 'ok' : 'error']);
        }else{
            self::json(["error" => "token-invalid!"]);
        }
    }
}