<?php 
namespace Http\controllers;
use lib\BaseController;
use models\Especialidad_Medico;
use models\Persona;
use models\TipoDocumento;
use models\Usuario;

class ProfileController extends BaseController
{

    private static $Model,$ModelUsu;
    /// mostrar la vista del perfíl del usuario authenticado
    public static function index()
    {
        $especialidad = new Especialidad_Medico;
         if(self::profile()->rol === 'Médico')
         {
            $Medico_Id =  self::MedicoData()->id_medico;
            $Cargo = $especialidad->query()->Join("medico as m","med_esp.id_medico","=","m.id_medico")
            ->Join("especialidad as e","med_esp.id_especialidad","=","e.id_especialidad")
            ->Where("med_esp.id_medico","=",$Medico_Id)->get();
            self::View_("auth.profile",compact("Cargo"));
         }else{
        self::View_("auth.profile");
         }
    }
    /// editar el perfil del usuario

    public static function editar()
    {
        self::NoAuth();

        self::$Model = new TipoDocumento;

        $TipoDocs = self::$Model->query()->get();

        self::View_("auth.editar_perfil",compact("TipoDocs"));
    }

    /// modificar password

    public static function ActualizarPasswordActual()
    {
        self::NoAuth();

        if(self::ValidateToken(self::post("token_")))
        {
            self::$Model = new Usuario;

            $Usuario_Id = self::profile()->id_usuario;

            $Usuario = self::$Model->query()->Where("id_usuario","=",$Usuario_Id)->first();

            if($Usuario)
            {
                /// obtenemos el hash password
                $Password_Hash = $Usuario->pasword;

                // comparamos con el password actual(de lo que se ingresa)

                if(password_verify(self::post("pa"),$Password_Hash)){

                    /// mandamos actualizar el password del usuario
                    
                   $respuesta = self::$Model->Update([
                    "id_usuario"=>$Usuario_Id,
                    "pasword"=>password_hash(self::post("password"),PASSWORD_BCRYPT)
                   ]);

                    self::json(['response'=>$respuesta?'ok':'error']);
                }
                else
                {
                    self::json(['response'=>'no']);
                }
                 
            }
        }
    }

    /// mosificar su perfil
    public static function updatePerfil()
    {
        self::NoAuth();

        if(self::ValidateToken(self::post("token_")))
        {
            
            # verificamos si hemos seleccionado un archivo

            self::$ModelUsu = new Usuario;
            self::$Model = new Persona;

            $upload = self::CargarFoto("foto");

             if($upload!== 'no-accept')
             {
                # en caso no se haya seleccionado nungún archivo , no actualizamos la foto del usuario
                if($upload === 'vacio')
                {

                  $respuesta = self::$ModelUsu->Update([
                    "id_usuario"=>self::profile()->id_usuario,
                    "name"=>self::post("username"),
                    "email"=>self::post("email")
                  ]);

                  if($respuesta)
                  {
                    self::$Model->Update([
                        "id_persona"=>self::profile()->id_persona,
                        "id_tipo_doc"=>self::post("tipo_doc"),
                        "documento"=>self::post("documento"),
                        "apellidos"=>self::post("apellidos"),
                        "nombres"=>self::post("nombres"),
                        "genero"=>self::post("genero"),
                        "fecha_nacimiento"=>self::post("fecha_nac"),
                        "direccion"=>self::post("direccion")
                    ]);
                    self::Session("success","Perfil modificado correctamente");
                  }
                }
                else
                {
                    # actualziamos la foto del usuario con la foto, primero obtenemos la foto actual
                    $FotoActual = self::profile()->foto;
                    
                    if($FotoActual != null)
                    {
                        $DestinoFotoActual = "public/asset/foto/".$FotoActual;
                        # eliminamos la foto actual
                        unlink($DestinoFotoActual);
                    }

                    # actualizamos la foto
                    $respuesta = self::$ModelUsu->Update([
                        "id_usuario"=>self::profile()->id_usuario,
                        "name"=>self::post("username"),
                        "email"=>self::post("email"),
                        "foto"=>self::getNameFoto()
                      ]);
    
                      if($respuesta)
                      {
                        self::$Model->Update([
                            "id_persona"=>self::profile()->id_persona,
                            "id_tipo_doc"=>self::post("tipo_doc"),
                            "documento"=>self::post("documento"),
                            "apellidos"=>self::post("apellidos"),
                            "nombres"=>self::post("nombres"),
                            "genero"=>self::post("genero"),
                            "fecha_nacimiento"=>self::post("fecha_nac"),
                            "direccion"=>self::post("direccion")
                        ]);
                        self::Session("success","Perfil modificado correctamente");
                      }
                }
             }
             else
             {
                self::Session("error","El archivo seleccionado no es correcto");
            
             }

             self::RedirectTo("profile/editar");
        }
    }
}