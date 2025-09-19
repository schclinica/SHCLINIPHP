<?php 
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\CitaMedica;
use models\Departamento;
use models\DocumentosPaciente;
use models\Paciente;
use models\Persona;
use models\Sede;
use models\TipoDocumento;
use models\Usuario;

class PacienteController extends BaseController
{
 
 private static array $ErrorExistencia = [];   
 private static $ModelPersona,$ModelPaciente,$ModelUser;
/*===============
MUESTRA EL FORMULARIO
DE CREAR PACIENTES
==================*/
public static function create()
{
    self::NoAuth();
    if (self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1]
    || self::profile()->rol === self::$profile[3] || self::profile()->rol === 'admin_general') :
      self::$ModelPersona = new TipoDocumento;
      $TipoDocumentos = self::$ModelPersona->query()->get();
      self::View_("paciente.index",compact("TipoDocumentos"));
      return;
    endif;
    PageExtra::PageNoAutorizado();
}
/// proceso para registrar paciente
public static function save()
{
  self::NoAuth();
       /// validamos el token
   
      if(self::ValidateToken(self::post("token_")))
      {
        self::proccessSavePaciente();
      }
      
}

private static function proccessSavePaciente()
{
 self::$ModelPersona = new Persona;
 self::$ModelUser = new Usuario;
 self::$ModelPaciente = new Paciente;

 /// verificar la existencia por el # documento de la persona

  if(self::$ModelPersona->PersonaPorDocumento(self::post("documento")))
  {
   self::$ErrorExistencia[] = 'La persona con el # documento '.self::post("documento").' ya existe';
  }
 
  if(self::post("username")!= null and self::post("email")!=null)
  {
    if(self::$ModelUser->UsuarioPorUsername(self::post("username")))
    {
     self::$ErrorExistencia[] = 'El usuario con el nombre '.self::post("username").' ya está en uso';     
    }
   
    if(self::$ModelUser->UsuarioPorEmail(self::post("email"))) 
     {
       self::$ErrorExistencia[] = 'El usuario con el email'.self::post("email").' ya existe';  
     }
  }

   /// verificamos si existe errores de existencia de datos

   if(count(self::$ErrorExistencia) > 0)
   {
        self::json(["response"=>self::$ErrorExistencia]);
   }

   else
   {
        /// registramos al usuario
        if(self::post("username")!= null  and self::post("email")!=null and self::post("password")!=null)
        {
          $sede = self::profile()->sede_id != null ? self::profile()->sede_id : self::post("sede");
          self::$ModelUser->RegistroUsuario([self::post("username"),self::post("email"),password_hash(self::post("password"),PASSWORD_BCRYPT)],"paciente",null,$sede);
          /// registro de la persona
          $Usuario = self::$ModelUser->query()->Where("name","=",self::post("username"))->first();
          $user = $Usuario->id_usuario;
        }
        else{
          $user = null;
        }

        self::$ModelPersona->RegistroPersona([
                self::post("documento"),
                self::post("apellidos"),
                self::post("nombres"),
                self::post("genero"),
                self::post("direccion"),
                self::post("fecha_nac"),
                self::post("tipo_doc"),
                self::post("distrito"),
                $user
        ]);

        /// registrar al paciente

        $Persona = self::$ModelPersona->query()->Where("documento","=",self::post("documento"))->first();
        $SedeData = (self::profile()->rol != "Admisión" && self::profile()->rol!="Director") ? self::post("sede"):self::profile()->sede_id;
        $Resouesta = self::$ModelPaciente->RegistroPaciente([
                self::post("telefono"),
                self::post("facebok"),
                self::post("wasap"),
                self::post("estado-civil"),
                self::post("apoderado"),
                $Persona->id_persona,
                $SedeData
        ]);

        self::json(["response"=>$Resouesta]);

   }
}

/// mostrar todos los pacienets existentes
public static function PacientesExistentes()
{
  self::NoAuth();
  /// VALIDAMOS EL TOKEN
  if(self::ValidateToken(self::get("token_")))
  {
    self::$ModelPaciente = new Paciente;

    if(self::profile()->rol === "admin_general"){
      $Pacientes = self::$ModelPaciente->query()
                 ->Join("persona as per","pc.id_persona","=","per.id_persona")

                 ->Join("tipo_documento as tp","per.id_tipo_doc","=","tp.id_tipo_doc")
                 ->LeftJoin("distritos as dis","per.id_distrito","=","dis.id_distrito")
                 ->LeftJoin("provincia as pr","dis.id_provincia","=","pr.id_provincia")
                 ->LeftJoin("departamento as dep","pr.id_departamento","=","dep.id_departamento")
                 ->LeftJoin("sedes as s","pc.pacientesede_id","=","s.id_sede")
                 ->orderBy("pc.id_paciente","asc")
                 ->get();
    }else{
      $sede = self::profile()->sede_id;
      $Pacientes = self::$ModelPaciente->query()
                 ->Join("persona as per","pc.id_persona","=","per.id_persona")

                 ->Join("tipo_documento as tp","per.id_tipo_doc","=","tp.id_tipo_doc")
                 ->LeftJoin("distritos as dis","per.id_distrito","=","dis.id_distrito")
                 ->LeftJoin("provincia as pr","dis.id_provincia","=","pr.id_provincia")
                 ->LeftJoin("departamento as dep","pr.id_departamento","=","dep.id_departamento")
                 ->LeftJoin("sedes as s","pc.pacientesede_id","=","s.id_sede")
                 ->Where("pc.pacientesede_id","=",$sede)
                 ->orderBy("pc.id_paciente","asc")
                 ->get();
    }
  
    unset($Pacientes[0]->pasword);/// no consideramos el pasword
  
    self::json(compact("Pacientes"));
  }
}

/// actualzar datos del paciente
public static function modificarPaciente($personaId,$PacienteId)
{
  self::NoAuth();
  if(self::ValidateToken(self::post("token_")))
  {
    self::$ModelPersona = new Persona; self::$ModelPaciente = new Paciente;
    $respuesta = 0;

    /// verificamos si existe duplicidad en el # documento
    $ExisteNumDocumento = self::$ModelPersona->query()->Where("documento","=",self::post("doc"))->first();

    if($ExisteNumDocumento)
    {
      $respuesta = self::$ModelPersona->Update([
        "id_persona"=>$personaId,"apellidos"=>self::post("apellidos"),"nombres"=>self::post("nombres"),"genero"=>self::post("genero"),"direccion"=>self::post("direccion"),
        "fecha_nacimiento"=>self::post("fecha_nac"),"id_tipo_doc"=>self::post("tipo_doc"),
        "id_distrito"=>self::post("distrito")
      ]);
    }
    else
    {
        /// modificamos los datos de la persona
        $respuesta = self::$ModelPersona->Update([
          "id_persona" => $personaId, "documento" => self::post("doc"), "apellidos" => self::post("apellidos"), "nombres" => self::post("nombres"), "genero" => self::post("genero"),
          "direccion" => self::post("direccion"),"fecha_nacimiento" => self::post("fecha_nac"), 
          "id_tipo_doc" => self::post("tipo_doc"),"id_distrito" => self::post("distrito")
        ]);
    }

    if($respuesta)
    {
      /// actualizamos los datos del paciente
      self::$ModelPaciente->Update([
        "id_paciente"=>$PacienteId,"telefono"=>self::post("telefono"),"facebook"=>self::post("facebook"),
        "whatsapp"=>self::post("wasap"),"estado_civil"=>self::post("estado_civil"),
        "nombre_apoderado"=>self::post("apoderado"),"pacientesede_id" => self::post("sedeeditar")
      ]);

      /// modificamos la sede del usuairo correspondiente al paciente
      $DataPersona = self::$ModelPersona->query()->Where("documento","=",self::post("doc"))->first();
      if($DataPersona->id_usuario != null){
        $usu = new Usuario;
        $usu->Update([
          "id_usuario" => $DataPersona->id_usuario,
          "sede_id" => self::post("sedeeditar")
        ]);
      }

      self::json(['response'=>'success']);
    }else{self::json(['response'=>'error']);}
  }
}
/// método para visualizar la vista de completar datos del paciente
public static function completaDatos()
{
  self::NoAuth();

  if(self::profile()->rol === self::$profile[2] and !isset(self::profile()->id_persona))
  {
  $sede = new Sede;
  $modelTipoDoc = new TipoDocumento; $modelDep = new Departamento;
  $TipoDocumentos = $modelTipoDoc->query()->get();
  $Departamentos = $modelDep->query()->Where("deleted_at","=","1")->get();
  $sedes = $sede->query()->Where("deleted_at","is",null)->get();

  return self::View_("usuario.Completar_Datos",compact("TipoDocumentos","Departamentos","sedes"));
  }
  else
  {
    PageExtra::PageNoAutorizado();
  }
}

// completar datos del paciente
public static function completeDatos()
{
self::NoAuth();
 
  if(self::ValidateToken(self::post("token_")))
  {
    
    $modelPersona = new Persona; $modelPaciente = new Paciente;
    
    # Validamos la existencia del paciente por # documento

    $Persona = $modelPersona->query()->Where("documento","=",self::post("doc"))->first();

    if($Persona)
    {
      self::json(['response'=>'existe']);
    }
    else
    {
      $Persona = self::post("persona");

      $Persona = explode(" ",$Persona); #Rosales Cadillo Abelardo Adrian

      // validamos que la persona hay proporcionado sus apellidos y nombres
      if(count($Persona) >= 2 and count($Persona)<=4)
      {
          $Apellidos = $Persona[0] . " " . $Persona[1];
           
          $PrimerNombre = isset($Persona[2]) ? $Persona[2]: '';
          $SegundoNombre = isset($Persona[3]) ? $Persona[3] : '';
          $Nombres = $PrimerNombre." ".$SegundoNombre;

          # registramos los datos
          $response = $modelPersona->RegistroPersona([
            self::post("doc"),
            $Apellidos, $Nombres, self::post("genero"), self::post("direccion"),
            self::post("fecha_nac"), self::post("tipo_doc"), self::post("distrito"),
            self::getSession("remember")
          ]);

          if ($response) {
            # registramos datos secundarios del paciente
            $Persona_ = $modelPersona->query()->Where("documento", "=", self::post("doc"))->first();

            $modelPaciente->RegistroPaciente([
              self::post("telefono"), self::post("facebok"), self::post("wasap"),
              self::post("estado_civil"), self::post("apoderado"), $Persona_->id_persona,
              self::post("sede")
            ]);

            if($Persona_->id_usuario!= null){
              $usuario = new Usuario;
              $usuario->Update([
                "id_usuario" => $Persona_->id_usuario,
                "sede_id" => self::post("sede")
              ]);
            }
            self::json(['response' => 'ok']);
          } else {
            self::json(['response' => 'error']);
          }
      }
      else
      {
        /// mostramos un mensaje de error para que complete los datos
        self::json(["response"=>"error-persona"]);
      }
    }
  }

}
# ver las citas registrados
public static function CitasRegistrados()
{
  self::NoAuth();
  if(self::profile()->rol === self::$profile[2]  and isset(self::profile()->id_persona) ):
    self::View_("paciente.citasregistrados");
  endif;
}

#devolver la data de la citas registrados del paciente
public static function DataCitasRegistrados()
{
  self::NoAuth();
  if(self::ValidateToken(self::get("token_")))
  {
    $modelData = new CitaMedica;
    $CitasRegistrados = $modelData->procedure("proc_show_citas_registrados","c",[self::profile()->id_usuario]);

    self::json(['response'=>$CitasRegistrados]);
  }
}

/**
 * Eliminar al paciente
 */
public static function eliminar($id,$pacienteid){
  if(self::ValidateToken(self::post("token_")))
  {
    //$modelpaciente = new CitaMedica;
    //$datapacientecita = $modelpaciente->query()->where("id_paciente","=",$pacienteid)->get();
    // if(count($datapacientecita) > 0){
    //   $mensaje = "existe";
    // } else {
    // $medicomodel = new Usuario;

    // $User = $medicomodel->query()->where("id_usuario","=",$id)->get();
    // if($User){
    //   $response = $medicomodel->delete($id);
    // }else{
    //   $pacientemodel = new Paciente;
    //   $response = $pacientemodel->delete($pacienteid);
    // }
    $modelUser = new Usuario;
    $response = $modelUser->delete($id);
    $mensaje = $response ? 'ok':'error';
  //}

    self::json(["response" => $mensaje]);
  }else{
    self::json(["response" => "token-invalid"]);
  }
}

/**SUBIR DOCUMENTOS PARA EL PACIENTE (SUBIR A HOSTING) */
public static function uploadDocumentos()
 {
  if(self::ValidateToken(self::post("token_")))
  {
     self::procesoUploadDocumentos();
  }
 }

 /// proceso de subida de documentos del paciente(subir al hosting)
 private static function procesoUploadDocumentos()
 {
  /// verificando de que seleccionemos por lo menos un archivo
  if(self::file_size("documentos")[0] > 0){

     
      $responseDocument = "aceptable";
      /// Verificar que los archivos seleccionados sean pdf
       
        foreach(self::file_Name("documentos") as $key=>$doc){
          $Documento = self::file_Name("documentos")[$key];
          
          $Documento = explode(".",$Documento)[1];
          
          if($Documento !== 'pdf' && $Documento !== 'png' && $Documento !== 'jpg' && $Documento !== 'jpeg')
          {
            $responseDocument = "no-aceptable";
          }  
        }
        
       if($responseDocument === "aceptable"){
        /// destino donde se subiran los documentos pdf
        $Destino = "public/asset/documentos_pdf";
         if(!file_exists($Destino)){
           /// creamos el directorio
           mkdir($Destino);  
         }
         $modeldocpaciente = new DocumentosPaciente;
         /// recorremos todos documentos que deseamos subir
         foreach(self::file_Name("documentos") as $key=>$doc){
          $DocumentoUpload = self::file_Name("documentos")[$key];
          $DocumentoUpload = explode(".",$DocumentoUpload)[1];

          /// Generar el nuevo nombre del archivo
          $NameDocumento = self::FechaActual("YmdHis").$key.".".$DocumentoUpload;

          $ContentDocumento = self::ContentFile("documentos")[$key];

          $DestinoPdf=$Destino."/".$NameDocumento;

          

          if(move_uploaded_file($ContentDocumento,$DestinoPdf)){
             $modeldocpaciente->Insert([
              "documento_file" => $NameDocumento,
              "fecha_subida" => self::post("fecha"),
              "id_paciente" => self::post("paciente")
             ]);
            $response = "success";
          }else{
            $response = "error";
          }
        }
       }else{
         $response = "error-upload";
       }
  }else{
      $response = "vacio";
  }
   self::json(["response" => $response]);
 }

 /**
  * Mostrar los documentos subidos (hosting)
  */
  public static function showDocumentUpload($pacienteid){
    self::NoAuth();

    $model = new DocumentosPaciente;

    $documentos = $model->query()->Join("paciente as pc","dp.id_paciente","=","pc.id_paciente")
                                 ->select("id_doc_paciente","descripcion_doc","documento_file","fecha_subida")
                                 ->where("dp.id_paciente","=",$pacienteid)
                                 ->get();
    self::json(["documentos" => $documentos]);
  }

  /**Actualizar la descripcion del paciente (subir host) */
  public static function updateUploadDocumentoDescripcion($id)
  {
  if(self::ValidateToken(self::post("token_")))
  {
      $modelupload = new DocumentosPaciente;
      $response = $modelupload->Update([
        "id_doc_paciente" => $id,
        "descripcion_doc" => self::post("doc_desc")
      ]);

      self::json(["response" => $response?'ok':'error']);
  }
  }

  /**
   * Eliminar documento subido del paciente
   */
  public static function deleteDocumentoUpload($id)
  {
    if(self::ValidateToken(self::post("token_")))
     {
       $modeldoc = new DocumentosPaciente;
       $response = $modeldoc->delete($id);
        if($response){
          if(file_exists(self::post("documento"))){
            unlink(self::post("documento"));
            self::json(["response" => "eliminado"]);
          }else{
            self::json(["response" => "error"]);
          }
        }else{
          self::json(["response" => "error"]);
        }
     }
  }
}