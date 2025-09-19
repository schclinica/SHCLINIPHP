<?php 
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\CitaMedica;
use models\Medico;
use models\Paciente;
use models\Persona;
use models\Sede;
use models\TipoDocumento;
use models\Usuario;
class GestionUserController extends BaseController
{

    private static $ModelUser,$ModelPerson,$ModelTipoDoc,$Model;
    private static array $Errors = [];

    /// mostrar la vista de gestionar los usuarios
    public static function index()
    {
        // para ver esta vista , el usuario debe de estar authenticado
        self::NoAuth();// SI NO ESTÀ AUTHENTICADO, REDIRIGE A LOGIN
        
        /// le autorizamos, solo a los del perfil Director
        if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
        {
         self::$ModelTipoDoc = new TipoDocumento;$sede = new Sede;
         $TipoDocumentos = self::$ModelTipoDoc->query()->where("estado","=","1")->get();
         /// MOSTRAR LAS SEDES
         $sedes = $sede->query()->Where("deleted_at","is",null)->get();
         //$TipoDocumentosEditar = self::$ModelTipoDoc->query()->get();
         self::View_("usuario.IndexView", compact("TipoDocumentos","sedes"));
         return;
        }
        
        PageExtra::PageNoAutorizado();
    }

    /// mostrar todos los usuarios
    public static function showUsers()
    {
     /// esto será visible solo si el usuario está authenticado al sistema
     self::NoAuth(); /// si no está authenticado, redirige a login   
           /// validar el token
           if(self::ValidateToken(self::get("token_")))
           {
             /// mostramos los usuarios
             self::$ModelUser = new Usuario;

             if(self::profile()->rol === 'admin_farmacia')
             {
              $sede = self::profile()->sede_id;
              $Data = self::$ModelUser->query()->LeftJoin("persona as p","p.id_usuario","=","u.id_usuario")
                     ->LeftJoin("distritos as dis","p.id_distrito","=","dis.id_distrito")
                     ->LeftJoin("provincia as pr","dis.id_provincia","=","pr.id_provincia")
                     ->LeftJoin("departamento as dep","pr.id_departamento","=","dep.id_departamento")
                     ->LeftJoin("sedes as s","u.sede_id","=","s.id_sede")
                     ->select("p.nombres",
                     "p.documento","p.id_tipo_doc","p.apellidos","u.name","p.genero",
                     "u.email","u.rol","u.foto","u.id_usuario","u.estado","p.id_distrito",
                     "dep.id_departamento","pr.id_provincia","p.fecha_nacimiento","p.id_persona",
                     "u.sede_id","s.namesede"
                     )
                     ->Where("u.sede_id","=",$sede)
                     ->InWhere('u.rol',["'Farmacia','admin_farmacia'"])
                     ->get();
             }else{
              if(self::profile()->rol === 'Director')
              {
                $sede = self::profile()->sede_id;
                $Data = self::$ModelUser->query()->LeftJoin("persona as p","p.id_usuario","=","u.id_usuario")
                ->LeftJoin("distritos as dis","p.id_distrito","=","dis.id_distrito")
                ->LeftJoin("provincia as pr","dis.id_provincia","=","pr.id_provincia")
                ->LeftJoin("departamento as dep","pr.id_departamento","=","dep.id_departamento")
                ->LeftJoin("sedes as s","u.sede_id","=","s.id_sede")
                ->select("p.nombres",
                "p.documento","p.id_tipo_doc","p.apellidos","u.name","p.genero",
                "u.email","u.rol","u.foto","u.id_usuario","u.estado","p.id_distrito",
                "dep.id_departamento","pr.id_provincia","p.fecha_nacimiento","p.id_persona",
                "u.sede_id","s.namesede"
                )
                ->Where("u.sede_id","=",$sede)
                
                ->get();
              }else{
                $Data = self::$ModelUser->query()->LeftJoin("persona as p","p.id_usuario","=","u.id_usuario")
                ->LeftJoin("distritos as dis","p.id_distrito","=","dis.id_distrito")
                ->LeftJoin("provincia as pr","dis.id_provincia","=","pr.id_provincia")
                ->LeftJoin("departamento as dep","pr.id_departamento","=","dep.id_departamento")
                ->LeftJoin("sedes as s","u.sede_id","=","s.id_sede")
                ->select("p.nombres",
                "p.documento","p.id_tipo_doc","p.apellidos","u.name","p.genero",
                "u.email","u.rol","u.foto","u.id_usuario","u.estado","p.id_distrito",
                "dep.id_departamento","pr.id_provincia","p.fecha_nacimiento","p.id_persona",
                "u.sede_id","s.namesede"
                )
                ->Where("u.rol","=","Médico")
                ->Or("u.rol","=","Paciente")
                ->Or("u.rol","=","Enfermera-Triaje")
                ->Or("u.rol","=","Director")
                ->Or("u.rol","=","Admisión")
                ->Or("u.rol","=","Farmacia")
                ->Or("u.rol","=","admin_farmacia")
                ->Or("u.rol","=","admin_general")
                ->get();
              }
             }
             
            self::json(["usuarios"=>$Data]);
           }
    }

    /// crear nueva cuenta de usuarios 
    public static function save()
    {
      /// esta acción solo será si esta authenticado
      self::NoAuth();# si no esta authenticado, redirije al login
      // validamos el token
       if(self::ValidateToken(self::post("token_")))
       { 
        
         /// validamos datos obligatorios
         if(empty(self::post("documento"))){self::$Errors[] ='Complete el campo # documento';}

         if(empty(self::post("apellidos"))){self::$Errors[]='Complete sus apellidos';};

         if(empty(self::post("nombres"))){self::$Errors[]='Complete sus nombres';}

         if(empty(self::post("name"))){self::$Errors[]='Complete su nombre de usuario';}

         if(empty(self::post("email"))){
          self::$Errors[]='Ingrese un email';
        }else{
          if(!filter_var(self::post("email"),FILTER_VALIDATE_EMAIL))
          {
            self::$Errors[]='Ingrese un email válido';
          }
        }

         if(empty(self::post("password"))){self::$Errors[]='Ingrese una contraseña para su logín';}

         if(self::post("distrito") === "null"){self::$Errors[] = "Seleccione un distrito para este usuario!!.";}
         if(self::profile()->rol === "admin_general" && self::post("rol")!="admin_general"){
           if(self::post("sede") === "null"){self::$Errors[] = "Seleccione una sede en lo que operará este usuario!!.";}
         }
         if(count(self::$Errors) > 0)
         {
          self::json(['response'=>self::$Errors]);
         }
         else
         {
          self::saveProcess();
         }

       }
    }

    /// proceso para realizar el registro
    private static function saveProcess()
    {
      self::$ModelPerson = new Persona;
      self::$ModelUser = new Usuario;

      /// validamos que no exista duplicidad por tipo documento
      $PersonaPorTipoDoc = self::$ModelPerson->query()->Where("documento","=",self::post("documento"))->first();
      /// validamos que no exista duplicidad por nombre de usuario y email del usuario
      $UserName = self::$ModelUser->query()->Where("name","=",self::post("name"))->first();

      $UserEmail = self::$ModelUser->query()->Where("email","=",self::post("email"))->first();

      if($PersonaPorTipoDoc) {self::$Errors [] = 'Ya existe esa persona con ese # documento';}

      if($UserName){self::$Errors [] = 'Ya existe un usuario con ese nombre';}

      if($UserEmail){self::$Errors [] = 'Ya existe un usuario con ese email';}

      # validamos que si la cantidad de errores supera a 0, => se encontró errores
      
      if(count(self::$Errors) > 0)
      {
        self::json(['response'=>self::$Errors]);
      }
      else{
        /// realizamos el registro de nuevos usuarios al sistema
        # primero registramos al usuario

         $sede = ((self::profile()->rol === "admin_general" && self::post("sede") === "null") ? null:(self::profile()->rol === "admin_general" && self::post("sede") != "null"? self::post("sede"):self::profile()->sede_id)) ;
        
        self::$ModelUser->RegistroUsuario(
          [
          self::post("name"),
          self::post("email"),
          password_hash(self::post("password"),PASSWORD_BCRYPT),
          ],
          self::post("rol"),
          null,
          $sede
        );

        /// OBTENEMOS AL USUARIO REGISTRADO
        $UsuarioSave = self::$ModelUser->query()->Where("name","=",self::post("name"))->first(); 

        $PersonaSave = self::$ModelPerson->RegistroPersona([

                self::post("documento"),
                self::post("apellidos"),
                self::post("nombres"),
                self::post("genero"),
                self::post("direccion"),
                self::post("fecha_nac"),
                self::post("tipo_doc"),
                self::post("distrito"),
                $UsuarioSave->id_usuario
        ]);

        self::json(['response'=>$PersonaSave]);
      }
    }

    /// modificar datos del usuario
    public static function updateUser($id_persona,$id_user)
    {
      self::NoAuth();
      if(self::ValidateToken(self::post("token_")))
      {
       self::processModifyUser($id_persona,$id_user);
      }
    }

    /// proceso para actualziar datos del usuario
    private static function processModifyUser($id_persona,$id_user)
    {
      self::$ModelPerson = new Persona;
      self::$ModelUser = new Usuario;

        $resp = self::$ModelPerson->Update([
          "id_persona"=>$id_persona,"documento"=>self::post("doc"),"apellidos"=>self::post("apellidos"),"nombres"=>self::post("nombres"),
          "genero"=>self::post("genero"),"direccion"=>self::post("direccion"),
          "fecha_nacimiento"=>self::post("fecha_nac"),"id_tipo_doc"=>self::post("tipo_doc"),
          "id_distrito"=>self::post("distrito")
       ]);

        if($resp)
        {
         self::$ModelUser->Update([
           "id_usuario"=>$id_user,"name"=>self::post("name"),"email"=>self::post("email"),"rol"=>self::post("rol"),
           "estado"=>self::post("estado"),"sede_id" =>  self::profile()->sede_id != null ? self::profile()->sede_id:self::post("sede")
          ]);

          if(self::profile()->rol === "admin_general"){
            if(self::post("rol") === "Paciente"){
              $paciente = new Paciente;
              $pacienteId = $paciente->query()->Where("id_persona","=",$id_persona)->first();

              $paciente->Update([
                "id_paciente" => $pacienteId->id_paciente,
                "pacientesede_id" => self::post("sede")
              ]);
            }

            if(self::post("rol") === "Médico"){
              $medico = new Medico;
              $MedicoId = $medico->query()->Where("id_persona","=",$id_persona)->first();

              $medico->Update([
                "id_medico" => $MedicoId->id_medico,
                "medicosede_id" => self::post("sede")
              ]);
            }
          }

          self::json(['response'=>'ok']);
        }
        else
        {
         self::json(['response'=>'error']);
        }

    }

    // mostrar a los pacientes que aún no tienen una cuenta asignada
    public static function showPacientesSinCuenta()
    {
       self::NoAuth();
       
       if(self::ValidateToken(self::get("token_")))
       {
        self::$ModelPerson = new Persona;

        if(self::profile()->rol === "admin_general"){
          $Pacientes = self::$ModelPerson->query()
                    ->Join("paciente as pc","pc.id_persona","=","p.id_persona")
                    ->Where("p.id_usuario","is",null)
                    ->get();
        }else{
          $sede = self::profile()->sede_id;
          $Pacientes = self::$ModelPerson->query()
                    ->Join("paciente as pc","pc.id_persona","=","p.id_persona")
                    ->Where("p.id_usuario","is",null)
                    ->And("pc.pacientesede_id","=",$sede)
                    ->get();
        }

        self::json(['pacientes'=>$Pacientes]);    
       }

    }

    /// agregarle una cuenta de usuario al paciente
    public static function createAccountPaciente($persona)
    {
    self::NoAuth();

    if (self::ValidateToken(self::post("token_"))) {
      self::$ModelUser = new Usuario;
      self::$ModelPerson = new Persona;
      # verificamos que el email no se duplique
      $email_Existe = self::$ModelUser->query()->Where("email", "=", self::post("email"))->first();

      if ($email_Existe) {
        self::json(['response' => 'existe']);
      } else {

        $resp = self::$ModelUser->Insert([
          "name" => self::post("name"),
          "email" => self::post("email"),
          "rol"=>"Paciente",
          "pasword" => password_hash(self::post("pass"), PASSWORD_BCRYPT)
        ]);

        if ($resp) {
          # obtenemos al usuario creado
          $usuario = self::$ModelUser->query()->Where("email", "=", self::post("email"))
          ->And("name", "=", self::post("name"))->first();

          self::$ModelPerson->Update([
            "id_persona" => $persona,
            "id_usuario" => $usuario->id_usuario
          ]);

          self::json(['response' => 'ok']);
        } else {
          self::json(['response' => 'error']);
        }
      }
    }
    }

    /// eliminar una cuenta de usuario
    public static function deleteUser($Id)
    {
      self::NoAuth();
      if(self::ValidateToken(self::post("token_")))
      {
        self::$ModelUser = new Usuario;
        self::$Model = new CitaMedica;

        # verificamos si el usuario que deseamos eliminar ya a realizado alguna tarea en las citas médicas
        //$Verificar = self::$Model->query()->Where("id_usuario","=",$Id)->first();
        $VerificarPaciente = self::$Model->procedure("proc_existeusercitamedica","C",[$Id,1]);
        $VerificarMedico = self::$Model->procedure("proc_existeusercitamedica","C",[$Id,2]);
        if($VerificarPaciente || $VerificarMedico)
        {
          self::json(['response'=>'existe']);
        }
        else
        {
         if($Id == self::profile()->id_usuario)
         {
          self::json(["response" => "no"]);

         }else{
            /// consultamos si los usuarios que no sean medico ni paciente se puedan eliminar
            $cita = new CitaMedica;

            $responseCita = $cita->query()->where("id_usuario","=",$Id)->get();

            if($responseCita){
              self::json(["response" => "existe"]);
            }else{
              # caso no existir lo elimina
            $respuesta = self::$ModelUser->delete($Id);
            if($respuesta){self::json(['response'=>'ok']);}else{self::json(['response'=>'error']);};
            }
         }
        }
      }
    }

    /// contenido de envio de correos electronico al paciente , la info de sus credenciales
    public static function ContentInfoCredenciales()
    {
      echo '
      <h1 style="color:blue">Hola,</h1>
      ';
    }
}