<?php 
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\Especialidad;
use models\Especialidad_Medico;
use report\implementacion\Model;

class EspecialidadController extends BaseController
{

 private static $ModelEspecialidad;
 /** crear una nueva especialidad en la tabla especialidades */
  /// MOSTRAR LA VISTA PARA GESTIONAR ESPECIALIDADES
  public static function ViewGestionEspecialidades(){
    self::NoAuth();
    if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general'){
       self::View_("especialidades.index");
    }else{
      PageExtra::PageNoAutorizado();
    }
  }
 public static function store()
 {
    self::NoAuth();
    /// validar el token
    if(self::ValidateToken(self::post("token_")))
    {
        /// verificamos que no exista duplicidad

        self::$ModelEspecialidad = new Especialidad;

        self::json(['response'=>self::$ModelEspecialidad->create(self::post("especialidad"),self::post("precio"))]);
    }else{
      self::json(["error"=> "ERROR,TOKEN INCORRECTO!!!"]);
    }
 }
 
 /// proceso para modificar una especialidad
 public static function update($IdEspecialidad)
 {
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::post("token_")))
    {
        self::$ModelEspecialidad = new Especialidad;

        /// modificamos la especialidad, retorna [1,0, existe]
        self::json(['response'=>self::$ModelEspecialidad->Modificar(self::post("especialidad"),self::post("precio"),$IdEspecialidad)]);
    }else{
      self::json(["error" => "ERROR, EL TOKEN ES INCORRECTO!!!"]);
    }
 }

 /// Cambiar estado de una especialidad
 public static function CambiarEstadoEspecialidad($Id_Especialidad)
 {
    self::NoAuth();
    /// validamos el token, antes de deleiminar
    if(self::ValidateToken(self::post("token_")))
    {
        /// eliminamos
        self::$ModelEspecialidad = new Especialidad;

        self::json(['response'=>self::$ModelEspecialidad->CambiarEstado($Id_Especialidad,self::post("estado"))]);
    }
 }

 /// mostrar todas las especialidades
 public static function showEspecialidades()
 {
  self::NoAuth();
  /// Validamos el token 
  if(self::ValidateToken(self::get("token_")))
  {
    /// mostramos las especialidades
    self::$ModelEspecialidad = new Especialidad;

    $Especialidades = self::$ModelEspecialidad->query()->get();

    self::json(compact('Especialidades'));
  }

 }
 # MOSTRAR LAS ESPECIALIDADES PARA LAS CITAS MEDICAS
 public static function allEspecialidadesCitas()
 {
    self::NoAuth();

    if(self::profile()->rol === self::$profile[2] and isset(self::profile()->id_persona))
    {
    $EspecialidadesModel = new Especialidad;
    
    $especialidades = $EspecialidadesModel->query()
    ->Where("estado","=","1")
    ->get();

    return self::View_("cita_medica.especialidades_para_cita",compact("especialidades"));
    }
    else
    {
      self::RedirectTo("paciente/completar_datos");
    }
 }

 /// ver las especialidades del médico
 public static function EspecialidadesMedico()
 {
   self::NoAuth();
   if(self::profile()->rol === self::$profile[3])
   {
      self::$ModelEspecialidad = new Especialidad;

      $Especialidades = self::$ModelEspecialidad->procedure("proc_especialidadesfatantesmedico","c",[self::MedicoData()->id_medico]);
    self::View_("medico.especialidades",compact("Especialidades"));
   }else{
      PageExtra::PageNoAutorizado();
   }
 }

 public static function especialidadesNoAsignadasMedico()
 {
   self::NoAuth();
   if(self::profile()->rol === self::$profile[3])
   {
      self::$ModelEspecialidad = new Especialidad;

      $Especialidades = self::$ModelEspecialidad->procedure("proc_especialidadesfatantesmedico","c",[self::MedicoData()->id_medico]);

      self::json(["response" => $Especialidades]);
   }else{
      self::json(["response" => "no-authorized"]);
   }
 }

 /// mostrar las especialidades del médico en formato json
 public static function showEspecialidadesMedico()
 {
   self::NoAuth();
   if(self::profile()->rol === self::$profile[3])
   {
     $model = new Especialidad_Medico;

     $DataIdMedico = self::MedicoData()->id_medico;
     $respuesta = $model->query()->Join("especialidad as es","med_esp.id_especialidad","=","es.id_especialidad")
     ->Where("med_esp.id_medico","=",$DataIdMedico)->get();

     self::json(["response" => $respuesta]);
   } 
 }

  public static function showEspecialidadesMedicoPublic($id)
 {
   self::NoAuth();
   if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general')
   {
     $model = new Especialidad_Medico;

   
     $respuesta = $model->query()->Join("especialidad as es","med_esp.id_especialidad","=","es.id_especialidad")
     ->Where("med_esp.id_medico","=",$id)->get();

     self::json(["response" => $respuesta]);
   } 
 }

 /// eliminar la especialidad del médico
 public static function eliminarEspecialidadMedico(int $id)
 {
   self::NoAuth();
   if(self::profile()->rol === self::$profile[3] || self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general')
   {
     if(self::ValidateToken(self::post("token_"))) 
     {
      $model = new Especialidad_Medico;

      $response = $model->delete($id);
      self::json(["response" => $response ? 'ok':'error']);
     }else{
      self::json(["response" => "token-invalidate"]);
     }
   }else{
      self::json(["response" => "no.authorized"]);
   }
 }

 /// agregar nuevas especialidades del médico
 public static function addEspecialidadMedico()
 {
   self::NoAuth();
   if(self::profile()->rol === self::$profile[3])
   {
     if(self::ValidateToken(self::post("token_")))
     {
       if(self::post("select_esp") != null)
       {
         $modelesp = new Especialidad_Medico;

         foreach(self::post("select_esp") as $esp)
         {
            $value = $modelesp->Insert([
               "id_especialidad" => $esp,
               "id_medico" => self::MedicoData()->id_medico
            ]);
         }

         self::json(["response"=> $value?'ok':'error']);

       }else
       {
         self::json(["response" => "vacio"]);
       }
     }else{
      self::json(["response" => "token-invalidate"]);
     }
   }else{
      self::json(["response" => "no-authirized"]);
   }
 }

 /**
  * Forzar Eliminado de las especialidades
  */
  public static function forzarEliminado($id){
    self::NoAuth();
    $response = [];
    if(self::profile()->rol === self::$profile[0])
    {
     if(self::ValidateToken(self::post("token_")))
     {

      /// consultamos a la tabla 
      $modelmedesp = new Especialidad_Medico;
      $respuesta = $modelmedesp->query()->where("id_especialidad","=",$id)->get();

      if(count($respuesta) > 0)
      {
        $response = ["error" => "error-eliminado"];
      }else{
        $modelespdata = new Especialidad;

        $modelespdata->delete($id);

        $response = ["response" => "ok"] ;
      }
      
     }else{

      $response = ["error" => "error-token"];
     }

     self::json($response);
    }
  }
}