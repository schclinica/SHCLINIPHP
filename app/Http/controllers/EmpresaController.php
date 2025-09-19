<?php

namespace Http\controllers;

use lib\BaseController;
use models\Empresa;

class EmpresaController extends BaseController
{
    /** Método para registrar los datos de la empresa */
    public static function store()
    {
        /// validamos si esta authenticado
        self::NoAuth();
        /// validamos que esta acción, solo sea realizado por el administrador
        if (self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[3]
        || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia') {
            /// validamos el token
            
            if (self::ValidateToken(self::post("token_"))) {
                self::$DestinoFoto = "public/asset/empresa/";
                $Upload = self::CargarFoto("logo_clinica");

                if ($Upload !== 'no-accept') {
                    /// registramos
                    $modelEmpresa = new Empresa;

                    $respuesta = $modelEmpresa->Insert([
                        "nombre_empresa" => self::post("name_clinica"),
                        "ruc" => self::post("ruc_clinica"),
                        "direccion" => self::post("direccion"),
                        "telefono" => self::post("phone_clinica"),
                        "wasap" => self::post("wasap"),
                        "message_wasap" => self::post("message_wasap"),
                        "simbolo_moneda" => self::post("simbolo_moneda"),
                        "iva_valor" => self::post("valor_iva"),
                        "pagina_web" => self::post("paginaweb_clinica"),
                        "contacto" => self::post("email_clinica"),
                        "mapa_url" => self::post("mapa"),
                        "quienes_son" => self::post("historia"),
                        "mision" => self::post("mision"),
                        "vision" => self::post("vision"),
                        "logo" => self::getNameFoto()
                    ]);

                    self::json(["response" => $respuesta ? 'ok' : 'error']);
                }
            } else {
                self::json(["response" => "token_invalidate"]);
            }
        } else {
            self::json(["response" => "no-authorized"]);
        }
    }

    /// SUBIR EL ICONO DE LA EMPRESA
    public static function subirIconoBusiness($id){
        self::NoAuth();

        if(self::ValidateToken(self::post("token_"))){
            $empresa = new Empresa;

            $ExisteIcono = $empresa->query()->Where("id_empresa_data", "=", $id)->get();

            if ($ExisteIcono[0]->icono != null) {
                $path = "public/asset/empresa/" . $ExisteIcono[0]->icono;
                unlink($path);
            }
            self::$DestinoFoto = "public/asset/empresa/";

            $upload = self::CargarFoto("icono_select");

            if($upload === 'no-accept' || $upload === "vacio"){
                self::json(["error" => "EL ARCHIVO SELECCIONADO ES INCORRECTO, SOLO SE ACEPTAN ARCHIVOS CON EXTENSION .ICON!!"]);
                exit;
            }
            $response = $empresa->Update([
                "id_empresa_data" => $id,
                "icono" => self::getNameFoto()
            ]);
            if($response){
                self::json(["response" => "ICONO SUBIDO CORRECTAMENTE!!!"]);
            }else{
                self::json(["error" => "ERROR AL SUBIR EL ICONO DE LA EMPRESA!!!"]);
            }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }

    /** Mostrar la data de la empresa*/
    public static function all()
    {
        /// validamos que este authenticado al sistema
        self::NoAuth(); # si no está authenticado , redirije al login
        /// validamos que solo lo realice el médico
        if (self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[3] ||
        self::profile()->rol === 'admin_farmacia' || self::profile()->rol === 'admin_general') {
            /// validamos el token Csrf (seguridad Petición Http)

            if (self::ValidateToken(self::get("token_"))) {
                /// mostramos los datos de la clínica
                $modelclinica = new Empresa;

                $response = $modelclinica->query()
                    ->get();

                /// enviamos en formato json
                self::json(["response" => $response]);
            } else {
                self::json(["response" => "token-invalidate"], 302);
            }
        } else {
            self::json(["response" => "no-authorized"], 404);
        }
    }

    /** Eliminar la clínica registrada */
    public static function eliminar(int|null $id)
    {
        /// verifica que estee authenticado
        self::NoAuth();
        /// verificamos que sea un admin o médico
        if (self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[3]
        || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === 'admin_general') {
            /// validamos el token de seguridad
            if (self::ValidateToken(self::post("token_"))) {
                $model_clinica = new Empresa;

                /// consultamos, y obtenemos la foto de la clínica
                $FotoClinica = $model_clinica->query()
                    ->Where("id_empresa_data", "=", $id)
                    ->select("logo")
                    ->first();

                $response = $model_clinica->delete($id);

                if ($FotoClinica->logo != null) {
                    $DestinoFoto_ = "public/asset/empresa/" . $FotoClinica->logo;
                    unlink($DestinoFoto_);
                }

                self::json(["response" => $response ? 'ok' : 'error']);
            } else {
                self::json(["response" => "token-invalidate"], 302);
            }
        } else {
            self::json(["response" => "no-authorized"], 404);
        }
    }
 /// modificar los datos de las empresas
 public static function update(int|null $id)
 {
    /// verificamos que este authenticado
    self::NoAuth();

    /// verificamos que solo el médico o admin, realice esta acción
    if(self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[3] 
    || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
        
        /// verificamos el token Csrf
        if(self::ValidateToken(self::post("token_")))
        {
            /// consultamos la data de empresa (foto), de acuerdo al id
            $empresa = new Empresa;
            $FotoEmpresa = $empresa->query()->Where("id_empresa_data","=",$id)->first()->logo;

            /// verificamos si se ha seleccionado una foto o no

            self::$DestinoFoto = "public/asset/empresa/";
            $Upload = self::CargarFoto("logo_clinica");
            if($Upload !== 'no-accept')
            {
                if($Upload === 'vacio')
                {
                 $NombreFoto = $FotoEmpresa;
                }
                else
                {
                 $NombreFoto = self::getNameFoto();
                 /// eliminamos la foto anterior
                 $FotoEmpresa != null ? unlink("public/asset/empresa/".$FotoEmpresa):'';
                }

                /// actualizamos los datos

                $response = $empresa->Update([
                    "id_empresa_data" => $id,
                    "nombre_empresa" => self::post("name_clinica"),
                    "ruc" => self::post("ruc_clinica"),
                    "direccion" => self::post("direccion"),
                    "telefono" => self::post("phone_clinica"),
                    "wasap" => self::post("wasap"),
                    "message_wasap" => self::post("message_wasap"),
                    "simbolo_moneda" => self::post("simbolo_moneda"),
                    "iva_valor" => self::post("valor_iva"),
                    "pagina_web" => self::post("paginaweb_clinica"),
                    "contacto" => self::post("email_clinica"),
                    "mapa_url" => self::post("mapa"),
                    "quienes_son" => self::post("historia"),
                    "mision" => self::post("mision"),
                    "vision" => self::post("vision"),
                    "logo" => $NombreFoto
                ]);

                self::json(["response" => $response ? 'ok':'error']);
            }else{
              self::json(["response" => "error-imagen"]);   
            }
        }
        else{
          self::json(["response" => "token-invalidate"]);  
        }
    }
    else
    {
     self::json(["response" => "no-authorized"],404);
    }
 }

 /**
  * Modificar toda de la empresa controller
  */

  public static function subirImagenBanner($clinicaId){
    self::NoAuth();
    
    $model = new Empresa;

  if(self::ValidateToken(self::post("token_")))
   {
        /// cambiamos el destino de almacenar la foto
    self::$DestinoFoto = "public/asset/empresa/";
    /// consultamos la empresa
    $empresa = $model->query()->Where("id_empresa_data","=",$clinicaId)->first();

    if($empresa->imagen_banner != null && self::file_size("file_banner") > 0){
        /// obtenemos el directorio
        $DirectorioImagen = "public/asset/empresa/".$empresa->imagen_banner;

        unlink($DirectorioImagen);
        
    }

    if($empresa->foto_portada_video != null && self::file_size("portada_video") > 0 ){
        /// obtenemos el directorio
        $DirectorioImagenPortada = "public/asset/empresa/".$empresa->foto_portada_video;

        unlink($DirectorioImagenPortada);
    }

    $Imagen = self::CargarFoto("file_banner");

    $NombreImagenBanner = $empresa->imagen_banner != null && self::file_size("file_banner") <= 0 ? $empresa->imagen_banner : self::getNameFoto();
    self::$DestinoFoto = "public/asset/empresa/";
    $ImagenPortadaVideo = self::CargarFoto("portada_video");
    $NombrePortadaVideoImg = $empresa->foto_portada_video != null && self::file_size("portada_video")<= 0 ? $empresa->foto_portada_video : self::getNameFoto();
 
    if( $Imagen !== 'no-accept' && $ImagenPortadaVideo!=='no-accept'){
        $response = $model->update([
            "id_empresa_data" => $clinicaId,
            "imagen_banner" => $NombreImagenBanner,
            "video_url" => self::post("video"),
            "foto_portada_video" => $NombrePortadaVideoImg
        ]);
        self::json(["response" => $response ? 'ok' : 'error']);
    }else{
        self::json(["response" => "error-subida"]);
    }
  }else{
    self::json(["response" => "error-token"]);
  }

  }


  /** MODIFICAR DATOS DE LA EMPRESA(IMAGEN DE FONDO) */
  public static function SubirImgFondoEmpresa($id){
    if(self::ValidateToken(self::post("token_"))){
        $empresa = new Empresa;

        self::$DestinoFoto = "public/asset/empresa/";
        $imgUpload = self::CargarFoto("file_img_fondo");
        if($imgUpload !== 'vacio' && $imgUpload !== 'no-accept'){
            $NombrePortadaImg = self::getNameFoto();

            $empresa_ = $empresa->query()->Where("id_empresa_data","=",$id)->get();
            if($empresa_ && $empresa_[0]->img_fondo != null){
                $PathImgFondo = "public/asset/empresa/".$empresa_[0]->img_fondo;

                unlink($PathImgFondo);
            }

            $response = $empresa->Update([
                "id_empresa_data" => $id,
                "img_fondo" =>$NombrePortadaImg
            ]);
    
            self::json(["response" => $response ? 'ok' : 'error']);
        }else{
            self::json(["error" => "Error, debes de seleccionar un archivo que sea una imágen!!"]);
        }
    }else{
        self::json(["error"=>"Error, el token es incorrecto!!"]);
    }
  }
}
