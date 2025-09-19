<?php 
namespace Http\controllers;

use DateTime;
use FPDF;
use Http\pageextras\PageExtra;
use lib\BaseController;
use lib\HeaderHistorial;
use lib\QrCode;
use models\{AtencionMedica,CitaMedica,Configuracion, Detalle_Ordenes_Paciente, DiagnosticoPacienteAtencion, Empresa, Especialidad,Especialidad_Medico,
Medico, OrdenMedico, Paciente, Persona,Plan_Atencion,Programar_Horario,Receta,Recibo,Servicio,Usuario};

use PhpOffice\PhpSpreadsheet\IOFactory;
use Windwalker\Utilities\Attributes\Prop;

use function Windwalker\where;

class MedicoController extends BaseController
{
  use QrCode;
private static array $ErrorExistencia = [];   

private static $ModelPersona,$ModelMedico,$ModelUser,$ModelEspecialidadMedico,$ModelEspecialidad,$ModelConfig,$ModelProgramHorario,$ModelCitaMedica,$ModelAtencionMedica,$ModelServicio,$Model;

private static string  $TipoArchivoAceptable = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
/*** MOSTRAR LA VISTA DE GESTIÓN DE MEDICOS */
public static function index()
{
    self::NoAuth();/// si no esta authenticado, redirige al login
    if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general')
    {
      self::View_("medico.gestionmedico");
      return;
    }

    PageExtra::PageNoAutorizado();
}

/** MÉTODO PARA REGISTRAR A LOS MÉDICOS*/
public static function save()
{
  self::NoAuth();
 /// Validamos el token de envio
  if(self::ValidateToken(self::post("token_")))
  {
    /// Validamos antes de cargar la foto
   
    if(self::CargarFoto("foto") !== 'no-accept')/// osea si vacio tomara null 
    {
       self::proccessSaveMedico(self::getNameFoto()); 
    }else{
      self::json(["error" => "LA IMAGEN SELECCIONADO ES INCORRECTO!!!"]);
    }
  }else{
      self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
    }
    
 
}

private static function proccessSaveMedico($FotoUsuario)
{
 self::$ModelPersona = new Persona;
 self::$ModelUser = new Usuario;
 self::$ModelMedico= new Medico;

 /// verificar la existencia por el # documento de la persona

  if(self::$ModelPersona->PersonaPorDocumento(self::post("documento")))
  {
   self::$ErrorExistencia[] = 'La persona con el # documento '.self::post("documento").' ya existe';
  }
 
  if(self::$ModelUser->UsuarioPorUsername(self::post("username")))
  {
   self::$ErrorExistencia[] = 'El usuario con el nombre '.self::post("username").' ya está en uso';     
  }
 
  if(self::$ModelUser->UsuarioPorEmail(self::post("email"))) 
   {
     self::$ErrorExistencia[] = 'El usuario con el email'.self::post("email").' ya existe';  
   }

   /// verificamos si existe errores de existencia de datos

   if(count(self::$ErrorExistencia) > 0)
   {
        self::json(["errors"=>self::$ErrorExistencia]);
   }

   else
   {
         $sede = self::profile()->sede_id != null ? self::profile()->sede_id:self::post("sede");
        /// registramos al usuario
        self::$ModelUser->RegistroUsuario([self::post("username"),self::post("email"),password_hash(self::post("password"),PASSWORD_BCRYPT)],"médico",$FotoUsuario,$sede);
        /// registro de la persona
        $Usuario = self::$ModelUser->query()->Where("name","=",self::post("username"))->first();

        self::$ModelPersona->RegistroPersona([
                self::post("documento"),
                self::post("apellidos"),
                self::post("nombres"),
                self::post("genero"),
                self::post("direccion"),
                self::post("fecha_nac"),
                self::post("tipo_doc"),
                self::post("distrito"),
                $Usuario->id_usuario
        ]);

        /// registrar al paciente

        $Persona = self::$ModelPersona->query()->Where("documento","=",self::post("documento"))->first();

        $Respuesta = self::$ModelMedico->RegistroMedico([
                self::post("telefono"),
                self::post("universidad"),
                self::post("experiencia"),
                $Persona->id_persona,
                self::post("cmp"),
                $sede
        ]);

        
        self::json(["response"=>$Respuesta]);

   }
}

/// Mostrar a los mèdicos existentes
public static function mostrarMedicos()
{
  self::NoAuth();
  // Validamos el token
  if(self::ValidateToken(self::get("token_")))
  {
    self::$ModelMedico = new Medico;

    if(self::profile()->rol === self::$profile[0]){
      $sede = self::profile()->sede_id;
      $Medicos = self::$ModelMedico->query()->Join("persona as per","me.id_persona","=","per.id_persona")
               ->Join("usuario as us","per.id_usuario","=","us.id_usuario")
               ->Join("tipo_documento as tp","per.id_tipo_doc","=","tp.id_tipo_doc")
               ->Join("distritos as d","per.id_distrito","=","d.id_distrito")
               ->Join("provincia as pr","d.id_provincia","=","pr.id_provincia")
               ->Join("departamento as dep","dep.id_departamento","=","pr.id_departamento")
               ->LeftJoin("sedes as s","me.medicosede_id","=","s.id_sede")
               ->Where("me.medicosede_id","=",$sede)
               ->orderBy("per.apellidos","asc")
               ->get();
    }else{
      $Medicos = self::$ModelMedico->query()->Join("persona as per","me.id_persona","=","per.id_persona")
               ->Join("usuario as us","per.id_usuario","=","us.id_usuario")
               ->Join("tipo_documento as tp","per.id_tipo_doc","=","tp.id_tipo_doc")
               ->Join("distritos as d","per.id_distrito","=","d.id_distrito")
               ->Join("provincia as pr","d.id_provincia","=","pr.id_provincia")
               ->Join("departamento as dep","dep.id_departamento","=","pr.id_departamento")
               ->LeftJoin("sedes as s","me.medicosede_id","=","s.id_sede")
               ->orderBy("per.apellidos","asc")
               ->get();
    }
  
    self::destroyVariableArray($Medicos,"pasword");
               
    self::json(compact("Medicos"));/// retorna en en formato JSON
  }
}


 /// mostramos las especialidades del médico que aún no tiene asignado

 public static function mostrarEspecialidadesNoAsignados(int|null $IdMedico,string $buscador)
 {
  self::NoAuth();
   /// validamos el token
   if(self::ValidateToken(self::get("token_")))
   {
    self::$ModelMedico = new Medico;
    /// obtenmos la data
    $Especialidades = self::$ModelMedico->procedure("proc_esp_not_asign_medico","c",[$IdMedico,'%'.$buscador.'%']);

    self::json(["Especialidades" => $Especialidades]);
   }
 }
 /// asignar especialidades al médico
 public static function AsignarEspecialidad()
 {
  self::NoAuth();
  /// validamos el token
  if(self::ValidateToken(self::post("token_")))
  {
    self::$ModelEspecialidadMedico = new Especialidad_Medico;

    self::json(['response'=>self::$ModelEspecialidadMedico->AsignEspecialidadMedico(self::post("especialidad"),self::post("medico"))]);
  }
 }
 /// método para mostrar los médicos y las especialidades
 public static function MostrarMedicoEspecialidades()
 {
  self::NoAuth();
    /// validamos el token

    if (self::ValidateToken(self::get("token_"))) {

      $DataMedicoJson = '';

      self::$ModelMedico = new Medico;

      self::$ModelEspecialidadMedico = new Especialidad_Medico;

      self::$ModelEspecialidad = new Especialidad;

      if(self::profile()->rol === self::$profile[0]){
      $sede = self::profile()->sede_id;
      $Medicos = self::$ModelMedico->query()->join("persona as per", "me.id_persona", "=", "per.id_persona")
      ->Where("me.medicosede_id","=",$sede)
      ->get();
      }else{
        $Medicos = self::$ModelMedico->query()->join("persona as per", "me.id_persona", "=", "per.id_persona")->get();
      }

    foreach ($Medicos as $medico) {
      $DataMedicoJson .= '{
      "id_medico":"' . $medico->id_medico . '",
      "apellidos":"' . $medico->apellidos . '",
      "nombres":"' . $medico->nombres . '",
      "especialidades":
      ';

        $DataMedicoJson .= '[';

        /// consultamos de la tabla medico especialidades

        foreach (self::$ModelEspecialidadMedico->query()->Where("id_medico", "=", $medico->id_medico)->get() as $especialidad_medico) {
          foreach (self::$ModelEspecialidad->query()->Where("id_especialidad", "=", $especialidad_medico->id_especialidad)->get() as $especialidad) {
              $DataMedicoJson .= '
              {
                "id_especialidad_medico":"' . $especialidad_medico->id_medico_esp . '",
                "id_especialidad":"' . $especialidad->id_especialidad . '",
                "nombre_especialidad":"' . $especialidad->nombre_esp . '"
              },';
          }
        }

        /// eliminamos la ultima coma

        $DataMedicoJson = rtrim($DataMedicoJson, ",");

        $DataMedicoJson .= ']},';
      }

      /// eliminamos la ultima coma

      $DataMedicoJson = rtrim($DataMedicoJson, ",");

      /// convertimos en un array json

      $DataMedicoJson = '{"medicos":[' . $DataMedicoJson . ']}';;

      echo $DataMedicoJson;
    }
  }

  /// devolvemos el horario de atención con respecto al día
  public static function getHoarioEsSaludPorDia()
  {
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::get("token_")))
    {
      self::$ModelConfig = new Configuracion;
      /// verificamos la existencia

      $Resultado=  self::$ModelConfig->getFilterDato(self::get("dia"));

      self::json(['response'=>$Resultado]);
    }
  }

  /// Asignar horarios de atención a cada médico

 public static function AsignarHorariosAtencion()
 {
  self::NoAuth();
  if(self::ValidateToken(self::post("token_")))
  {
    self::$ModelAtencionMedica = new AtencionMedica; 
    $Resultado = self::$ModelAtencionMedica
                 ->AsignaHorario(self::post("dia"),self::post("medico"),self::post("hi"),self::post("hf"),self::post("tiempo_atencion"));

   self::json(['response'=>$Resultado]);
  }
 }
  /// generar horario del médico
  public static function generateHorario()
  {
    self::NoAuth();
    if(self::ValidateToken(self::get("token_")))
    {
      $Horarios ='';
    $inicio = self::get('hi'); //$_POST['hora_inicio'] ?? '';
    $final =  self::get("hf");//$_POST['hora_final'] ?? '';
    $incr =  intval(self::get("intervalo"));//$_POST['tiempo_atencion'] ?? ''; // Minutos
    $Hora_Inicial = new DateTime($inicio);
    $Hora_Final = new DateTime($inicio);
    $Hora_inicial_generado = 0;
    $Hora_Final_generado = 0;
    while ($Hora_Final_generado < $final) {

      

      $Hora_Final->modify('+' . $incr . ' minutes');

      $Hora_inicial_generado = $Hora_Inicial->format('H:i:s');

      $Hora_Final_generado = $Hora_Final->format('H:i:s');

      /// personalizamos el json
      $Horarios.= '
      {
        "horario_inicial":"'.$Hora_inicial_generado.'",
        "horario_final":"'.$Hora_Final_generado.'"
      },';

      $Hora_Inicial->modify('+' . $incr . ' minutes');
     }
    
    $Horarios = rtrim($Horarios,",");
    echo '{"response":['.$Horarios.']}';
    }
  }
  /// guardar la programación de horarios del médico

  public static function guardarProgramacionDeHorarios()
  {
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::post("token_")))
    {
      /// guardamos la programación de horarios del médico
      self::$ModelProgramHorario = new Programar_Horario;

      /// atención médica
      $Atencion_ = self::post("atencion");
      /// Hora de inicio en la cúal se genera el horario de atención
      $Hora_Inicial = self::post("hi");
      /// Hora final en la cuál se genera el horario de atención
      $Hora_Final = self::post("hf");

      self::json(['response'=>self::$ModelProgramHorario->saveProgramacionHorario($Atencion_,$Hora_Inicial,$Hora_Final)]);
    }
  }

  /// mostrar las especialidades del médico

  public static function showEspecialidadesMedico(int $medico)
  {
    self::NoAuth();
    /// validamos el token
     
     if(self::ValidateToken(self::get("token_")))
     {
      self::$ModelEspecialidadMedico = new Especialidad_Medico;

      $Data = self::$ModelEspecialidadMedico->query()->Join("especialidad as esp","med_esp.id_especialidad","=","esp.id_especialidad")
      ->Join("medico as m","med_esp.id_medico","=","m.id_medico")
      ->select("id_medico_esp","med_esp.id_especialidad","nombre_esp")
      ->Where("m.id_medico","=",$medico)->get();

      self::json(['especialidades'=>$Data]);
     }
    
  }
  ///verificar si dicho procedimiento, ya existe en la especialidad del médico
  public static function verifyprocedimEspecialidad($medico,$procedim)
  {
    self::NoAuth();
    /// verifica el token
    if(self::ValidateToken(self::get("token_")))
    {
      self::$ModelServicio = new Servicio;
      /// consultar
      $Data = self::$ModelServicio->query()->Where("id_medico_esp","=",$medico)
      ->And("name_servicio","=",$procedim)->first();

      self::json(['response'=>$Data? true: false]);
    }
  }

  /// guadar procdimientos a la especialidad del médico
  public static function saveProcedimientoMedico()
  {
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::post("token_")))
    {
      self::$ModelServicio = new Servicio;

      $Resultado = self::$ModelServicio->Insert([
        "name_servicio"=>self::post("servicio"),
        "id_medico_esp"=>self::post("medico_esp")
      ]);

      self::json(['response'=>$Resultado]);
      
    }
  }

  /// mostrar los procedimiento de cada especialidad del médico
  public static function showProcedimientoMedico($id)
  {
    self::NoAuth();
    // validamos el token
    if(self::ValidateToken(self::get("token_")))
    {
      self::$ModelServicio = new Servicio;
      // mostramos los datos de los procedimientos
      $Data = self::$ModelServicio->query()->Where("id_medico_esp","=",$id)->get();

      self::json(['response'=>$Data]);
    }
  }

  /// modificar procedimientos del médico
  public static function modificarProcedimiento($id)
  {
    self::NoAuth();
    /// validamos token
    if(self::ValidateToken(self::post("token_")))
    {
      self::$ModelServicio = new Servicio;

      $Respuesta = self::$ModelServicio->Update([
        "id_servicio"=>$id,
        "name_servicio"=>self::post("servicio")
      ]);

      self::json(['response'=>$Respuesta]);
    }
  }

  /// eliminar el procedimiento asignado al mèdico 
  public static function deleteProcedimiento($id)
  {
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::post("token_")))
    {

      /// verificamos que el procedimiento no estee en una cita respectivo
      self::$ModelCitaMedica = new CitaMedica;
      $ConsultaCitaMedica = self::$ModelCitaMedica->query()->
      Where("id_servicio","=",$id)->first();

      if($ConsultaCitaMedica)
      {
        self::json(['response'=>'existe']);
      }
      else
      {
        /// eliminamos dicho procedimiento asignado al médico
        self::$ModelServicio = new Servicio;

        $DeleteRespuesta = self::$ModelServicio->delete($id); 
        self::json(['response'=>$DeleteRespuesta]);
      }
    }
  }
  /// mostrar horarios disponibles del medico
  public static function showHorariosMedico()
  {
    self::NoAuth();

    if(self::profile()->rol === 'Médico')
    {
      self::$ModelMedico = new AtencionMedica;
      $user_id = self::profile()->id_usuario;
      $Horario_Medico = self::$ModelMedico->query()->Join("medico as m","atm.id_medico","=","m.id_medico")
      ->Join("persona as p","m.id_persona","=","p.id_persona")->Join("usuario as u","p.id_usuario","=","u.id_usuario")
      ->Where("u.id_usuario","=",$user_id)
      ->select("atm.id_atencion","m.id_medico","atm.dia","atm.hora_inicio_atencion","atm.hora_final_atencion")->get();
      self::View_("medico.horarios",compact("Horario_Medico"));
    }
  }

  /// mostrar los horarios programados del médico con respecto a un día

  public static function showHorariosProgramdosMedico($dia)
  {
    self::NoAuth();

    if(self::profile()->rol === 'Médico')
    {
      self::$ModelMedico = new Programar_Horario;
      $Horario_Medico_ = self::$ModelMedico->query()->Where("id_atencion","=",$dia)
      ->orderBy("hora_inicio","asc")
      ->get();

      self::json(['response'=>$Horario_Medico_]);
    }
  }

  /// activar y desactivar horarios del médico
  public static function active_desactive_horario_medico($id,$estado)
  {
    self::NoAuth();
    if(self::ValidateToken(self::post("token_")))
    {
      self::$ModelProgramHorario = new Programar_Horario;

      $resultado = self::$ModelProgramHorario->Update([
        "id_horario"=>$id,
        "estado"=>$estado
      ]);

      if($resultado)
      {
        self::json(['response'=>'ok']);
      }
      else
      {
        self::json(['response'=>'error']);
      }
    }
  }
  /// atención médica
  public static function atencion_medico_paciente()
  {
    self::NoAuth();
    self::$ModelConfig = new Configuracion;
    $FechaActual  = self::FechaActual("Y-m-d");

    $DiaActual = self::getDayDate($FechaActual);

    $Es_Dia_Laborable = self::$ModelConfig->query()->Where("dias_atencion","=",$DiaActual)->And("laborable","=","si")->first();

   if($Es_Dia_Laborable)
   {
    if(self::profile()->rol === self::$profile[3] || self::profile()->rol === self::$profile[1] || self::profile()->rol === self::$profile[4])
    {
      self::View_("medico.atencion_medica");
    }
    else
    {
      PageExtra::PageNoAutorizado();
    }
   }
   else{
    PageExtra::PageNoAutorizado();
   }
  }
  /// registrar la atencion médica del paciente
  public static function saveAtencionMedica(){

    self::NoAuth();

    if(self::ValidateToken(self::post("token_")))
    {
      self::$Model = new Plan_Atencion;
      $usuario = self::profile()->id_usuario;$sede = self::profile()->sede_id;

      # registramos la atención médica del paciente

      $respuesta = self::$Model->guardar(self::post("expediente"),self::post("antecedente"),self::post("tiempo_enfermedad"),
      self::post("alergias"), self::post("vacuna"),self::post("procedimiento"),self::post("examen_fisico"),
      self::post("diagnostico_general"),self::post("desc_tratamiento"),self::post("tiempo_tratamiento"), self::post("triaje"),
      self::post("proxima_cita"),self::post("ant_fam"),self::post("otros"),self::post("fechaatencion"),
      self::post("diabetes"),self::post("hiper_arterial"),self::post("transfusiones"),self::post("cirujias"),
      self::post("medicamentos_actuales"),$usuario,$sede,self::post("gestante"),self::post("edad_gestacional"),self::post("fecha_parto"));

      if($respuesta)
      {

        /// registramos el detalle del diagnostico

        if(self::ExistSession("diagnostico")){

          /// obtenemos la atencion  registrada
          $atenciondata = self::$Model->query()->Where("num_expediente","=",self::post("expediente"))->get();
          $diagnosticoPaciente = new DiagnosticoPacienteAtencion;
          foreach(self::getSession("diagnostico") as $diagnostico){

            $responseDiagnostico = $diagnosticoPaciente->Insert([
              "atencion_id" => $atenciondata[0]->id_atencion_medica,
              "enfermedad_id" => $diagnostico["enfermedad_id"],
              "enfermedad_desc" => $diagnostico["enfermedad_desc"],
              "tipodiagnostico" => $diagnostico["tipo"]
            ]);
          } 
          if($responseDiagnostico){
            
          // modificamos los estado de la cita médica a finalziado y el horario a disponible

        self::$ModelCitaMedica = new CitaMedica;

        self::$ModelProgramHorario = new Programar_Horario;

        self::$ModelCitaMedica->Update([
          "id_cita_medica"=>self::post("cita_medica"),
          "observacion"=>self::post("obs"),
          "estado"=>"finalizado",
          "monto_pago" => self::post("monto_pago"),
          "monto_medico" => self::post("monto_medico"),
          "monto_clinica" => self::post("monto_clinica"),
          "id_horario"=> null
        ]);

        self::$ModelProgramHorario->Update([
          "id_horario"=>self::post("horario_id"),
          "estado"=>"disponible"
        ]);
        
        self::destroySession("diagnostico");

        self::json(['response'=>'ok']);
        }
        }else{
          // modificamos los estado de la cita médica a finalziado y el horario a disponible

        self::$ModelCitaMedica = new CitaMedica;

        self::$ModelProgramHorario = new Programar_Horario;

        self::$ModelCitaMedica->Update([
          "id_cita_medica"=>self::post("cita_medica"),
          "observacion"=>self::post("obs"),
          "estado"=>"finalizado",
          "monto_pago" => self::post("monto_pago"),
          "monto_medico" => self::post("monto_medico"),
          "monto_clinica" => self::post("monto_clinica"),
          "id_horario"=> null
        ]);

        self::$ModelProgramHorario->Update([
          "id_horario"=>self::post("horario_id"),
          "estado"=>"disponible"
        ]);
        
        self::destroySession("diagnostico");

        self::json(['response'=>'ok']);
        }
      }
      else
      {
        self::json(['response'=>'error']);
      }
    }
  }

  /// registrar la receta del paciente
  public static function saveRecetaPaciente()
  {
    self::NoAuth();
    if(self::ValidateToken(self::post("token_")))
    {
      /// registramos la receta del paciente
      self::$Model = new Receta;
      self::$ModelAtencionMedica = new Plan_Atencion;

      $AtencionMedica = self::$ModelAtencionMedica->query()->Where("id_triaje","=",self::post("triaje_id"))->first();

      $resp = self::$Model->Insert([
        "medicamento"=>self::post("medic"),
        "dosis"=>self::post("dosis"),
        "tiempo_dosis"=>self::post("tiempo_dosis"),
        "cantidad"=>self::post("cantidad"),
        "id_atencion_medica"=>$AtencionMedica->id_atencion_medica
      ]);


      self::json(['resp'=>$resp]);
      
    }
  }

  /// ver pacientes atendidos por el médico
  public static function showPacientesAtendidos(int $opcion,string|null $fecha)
  {
    self::NoAuth();

    if(self::ValidateToken(self::get("token_")))
    {
      self::$Model = new Plan_Atencion;
      $usuario = self::profile()->id_usuario;
      $sede = self::profile()->sede_id;
      $Pacientes_Atendidos = self::$Model->procedure("proc_pacientes_atendidos_medico","c",[$opcion,$fecha,$usuario,self::FechaActual("Y-m-d"),$sede]);

      self::json(['response'=>$Pacientes_Atendidos]);
    }
  }
  # mostrar los médicos por especialidad
  public static function medicoPorEspecialidad()
  {
    self::NoAuth();

    if(self::profile()->rol === self::$profile[2])
    {
      $ModelMedico = new Especialidad_Medico;

       if(!empty(self::get("esp_id")))
       {

        $sede = self::profile()->sede_id;
        $medicos =  $ModelMedico->query()->Join("medico as m","med_esp.id_medico","=","m.id_medico")
        ->Join("especialidad as es","med_esp.id_especialidad","=","es.id_especialidad")
        ->Join("persona as p","m.id_persona","=","p.id_persona")->
        Join("usuario as u","p.id_usuario","=","u.id_usuario")->
        select("m.id_medico","p.apellidos","p.nombres","es.id_especialidad","u.foto","m.celular_num","es.nombre_esp","med_esp.id_medico_esp")
        ->where("es.id_especialidad","=",self::get("esp_id"))
        ->And("m.medicosede_id","=",$sede)
        ->get();

             
         self::View_("cita_medica.seleccionar_medico_cita",compact("medicos"));
       }else
       {
        self::RedirectTo("seleccionar-especialidad");
       }
       
       
    }
  }

  # VER EL PERFIL DEL MÉDICO
  public static function profileMedic(? string $id = null)
  {
    self::NoAuth();
 
    if(self::profile()->rol === self::$profile[2])
    {
      $modelMedico = new Medico;

      $medico = $modelMedico->query()->Join("persona as p","me.id_persona","=","p.id_persona")
      ->Join("usuario as u","p.id_usuario","=","u.id_usuario")
      ->Where("me.id_medico","=",$id)
      ->first();
      if($medico)
      {
        unset($medico->pasword);
        self::View_("medico.perfil",compact("medico")); 
      }
      else
      {
        self::RedirectTo("seleccionar-especialidad");
      }
    }
    else
    {
      PageExtra::PageNoAutorizado();
    }
  }
  /// agregar horario al médico
  public static function addPersonalizadoHourMedico($atencion)
  {
    self::NoAuth();
    if(self::ValidateToken(self::post("token_")))
    {
      $model = new Programar_Horario;

      $response = $model->Insert([
        "id_atencion"=>$atencion,
        "hora_inicio"=>self::post("hi"),
        "hora_final"=>self::post("hf"),
        "estado"=>"disponible"
      ]);

      if($response)
      {
        self::json(['response'=>'ok']);
      }
      else{
        self::json(['response'=>'error']);
      }
    }
  }
  # eliminar un horario del médico
  public static function deleteHorario($id)
  {
    self::NoAuth();

    if(self::ValidateToken(self::post("token_")))
    {
      $model = new Programar_Horario; $modelCita = new CitaMedica;

      $cita = $modelCita->query()->Where("id_horario","=",$id)->get();

      if(count($cita)>0){
        self::json(['response'=>'existe']);
      }
      else
      {
        $response = $model->delete($id);

        self::json(['response'=>$response?'ok':'error']);
      }
    }
  }
  /// modifcamos el horario
  public static function updateHorario($id)
  {
    self::NoAuth();

    if(self::ValidateToken(self::post("token_")))
    {
      $modelHorario = new Programar_Horario;

      $response = $modelHorario->Update(
        [
          "id_horario"=>$id,
          "hora_inicio"=>self::post("hi"),
          "hora_final"=>self::post("hf"),
          "estado" => self::post("estado_horariodata"),
          "desc_motivo" => self::post("motivo")
        ]
      );

      self::json(['response'=>$response?'ok':'error']);
    }
  }

  // importar los datos del horario
  public static function ImportarHorario()
  {
    self::NoAuth();
    if(self::ValidateToken(self::post("token_")))
    {
      /**
       * Obtenemos al file seleccionado, si o si tiene que ser un excel
       */
        if(self::file_size("file") > 0)
        {
          # Verificamos si el archivo seleccionado es un excel
          if(self::file_Type("file") === self::$TipoArchivoAceptable)
          {
            $ArchivoSelect = self::ContentFile("file");
            self::procesoImportHorario($ArchivoSelect); 
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


  /**
   * Realizamos el proceso de importación del archivo excel
   */
  private static function procesoImportHorario($ArchivoExcel)
  {
    $modelHorario = new Programar_Horario;
    # recibimos el archivo excel seleccionado
    $DocumentExcel = IOFactory::load($ArchivoExcel);
    # indicamos la hoja 0, recomendable , debe de ser solo 1 hoja por documento
    $HojaData = $DocumentExcel->getSheet(0);
    #Obtenemos la cantidad de filas
    $FilasDocumento = $HojaData->getHighestDataRow();

    # recorremos
    for($fila = 2;$fila<=$FilasDocumento; $fila++)
    {
      $Hora = $HojaData->getCellByColumnAndRow(1,$fila);
      $Hora = explode("-",$Hora);
      $Hora_Inicial = $Hora[0];
      $Hora_Final = $Hora[1];
     
      if(!self::ExisteHorario(self::post("atencion_data"),$Hora_Inicial,$Hora_Final))
      {
        $Respuesta = $modelHorario->Insert([
          "id_atencion"=>self::post("atencion_data"),
          "hora_inicio"=>$Hora_Inicial,
          "hora_final"=>$Hora_Final
        ]);
      }
      else
      {
        $Respuesta = "existe";
      }
    }

    self::json(['response'=>$Respuesta]);
  }

  /**
   * Validamos si existe el horarios agregado
   */
  private static function ExisteHorario($atencion,$hi,$hf)
  {
    $modelH = new Programar_Horario;

    return $modelH->query()->where("id_atencion","=",$atencion)
    ->And("hora_inicio","=",$hi)
    ->And("hora_final","=",$hf)
    ->first();
  }

  /**
   * Este método muestra la vista para que el médico pueda
   * importar los datos de los días de atención desde un archivo excel
   */
  public static function ViewImportDiasDeAtencion()
  {
    self::NoAuth();
    if(self::profile()->rol === 'Médico')
    {      
      self::View_("medico.import_dias_atencion");
    }
  }
  /**
   * Importar los días de atención del médico, seleccionando 
   * un archivo excel
   */
  public static function ImportDiasAtencion()
  {
    self::NoAuth();

    if(self::profile()->rol === self::$profile[3])
    {
      #Validar de que se haya seleccionado un archivo
      if(self::file_size("excel") > 0)
      {
        # Ahora validamos de que el archivo seleccionado sea si o si un excel
        if(self::file_Type("excel") === self::$TipoArchivoAceptable)
        {
          $ArchivoContent = self::ContentFile("excel");
          self::ProcesoImportDiasAtencion($ArchivoContent);
        }
        else
        {
          self::json(['response'=>"error-tipo-archivo"]);
        }
      }else
      {
        self::json(['response'=>'vacio']);
      }
    }
  }

  /**
   * Realizamos el proceso para importar los días de atención del médico
   */
  private static function ProcesoImportDiasAtencion($archivo)
  {
   $modelDiasAtencion = new AtencionMedica;

   $DocumentoExcel = IOFactory::load($archivo);

   $HojaActual = $DocumentoExcel->getSheet(0); # hoja 0 del archivo excel
   #obtenemos la cantidad de filas o registros de la hoja excel actual

   $FilasHojaExcel = $HojaActual->getHighestDataRow();

   $Medico_Id = self::MedicoData()->id_medico;

   for($fila_data = 2; $fila_data<=$FilasHojaExcel;$fila_data++)
   {
    $DiaAtencion = $HojaActual->getCellByColumnAndRow(1,$fila_data);
    $HorarioAtencion =  $HojaActual->getCellByColumnAndRow(2,$fila_data);
    $Intervalo_Horario_Atencion =   $HojaActual->getCellByColumnAndRow(3,$fila_data);

    /// Ahora desglosamos el horario de atención para obtener el inicio y final de la hora
    $HorarioAtencion = explode("-",$HorarioAtencion); # lo convierte en un array indexado
    $HoraInicio = $HorarioAtencion[0]; $HorarioFinal = $HorarioAtencion[1];

   # antes de insertar verificamos la existencia por día y médico
 
    # insertamos a la tabla atencionmedica , que indica los días de atención del médico
    
    if(!self::verificarExistenciaAtencion($Medico_Id,$DiaAtencion))
    {
        $Respuesta = $modelDiasAtencion->AsignaHorario(
        $DiaAtencion,self::MedicoData()->id_medico,$HoraInicio,$HorarioFinal,$Intervalo_Horario_Atencion
      );
    }
    else
    {
      $Respuesta = "existe";
    }
   }

   self::json(['response'=>$Respuesta?'ok':'error']);
  }
  private static function verificarExistenciaAtencion($medico,$dia)
  {
   $model = new AtencionMedica;
   return  $model->query()->Where("id_medico","=",$medico)
    ->And("dia","=",$dia)->first();
  }

  /** Realizamos la consulta el historial clínico de los pacientes */
  public static function showHistorialClinicoPaciente(string $pacienteDoc)
  {
    self::NoAuth();
    /// Validamos el token
    if(self::ValidateToken(self::get("token_")))
    {
       
      $modelHistorial = new Plan_Atencion;

      /// obtenemos al médico logueado

      if(self::profile()->rol === 'Médico'){
        $MedicoId = self::MedicoData()->id_medico;

        $Historial = $modelHistorial->query()->Join("triaje as tr","plan.id_triaje","=","tr.id_triaje")
        ->Join("cita_medica as ct","tr.id_cita_medica","=","ct.id_cita_medica")
        ->Join("paciente as pc","ct.id_paciente","=","pc.id_paciente")
        ->Join("persona as p","pc.id_persona","=","p.id_persona")
        ->Join("especialidad as esp","ct.id_especialidad","=","esp.id_especialidad")
        ->Where("ct.id_medico","=",$MedicoId)
        ->and("p.documento","=",$pacienteDoc)
        ->select("ct.id_cita_medica","pc.id_paciente","concat(apellidos,' ',nombres) as paciente_",
        "ct.fecha_cita","esp.nombre_esp","plan.id_atencion_medica")
        ->get();
      }else{
        $sede = self::profile()->sede_id;
        $Historial = $modelHistorial->query()->Join("triaje as tr","plan.id_triaje","=","tr.id_triaje")
        ->Join("cita_medica as ct","tr.id_cita_medica","=","ct.id_cita_medica")
        ->Join("paciente as pc","ct.id_paciente","=","pc.id_paciente")
        ->Join("persona as p","pc.id_persona","=","p.id_persona")
        ->Join("especialidad as esp","ct.id_especialidad","=","esp.id_especialidad")
        ->Where("p.documento","=",$pacienteDoc)
        ->And("ct.sedecita_id","=",$sede)
        ->select("ct.id_cita_medica","pc.id_paciente","concat(apellidos,' ',nombres) as paciente_",
        "ct.fecha_cita","esp.nombre_esp","plan.id_atencion_medica")
        ->get();
      }
 


     self::json(['historial'=>$Historial]);
    }
  }

  /// traemos a los pacientes del médico
  public static function showPacientes()
  {
    self::NoAuth();
    /// Validamos el token
    if(self::ValidateToken(self::get("token_")))
    {
      $modelHistorial = new Plan_Atencion;

      /// obtenemos al médico logueado
       $sede = self::profile()->sede_id;
      if(self::profile()->rol === 'Médico'){
        $MedicoId = self::MedicoData()->id_medico;

        $Historial = $modelHistorial->query()->distinct()->Join("triaje as tr","plan.id_triaje","=","tr.id_triaje")
        ->Join("cita_medica as ct","tr.id_cita_medica","=","ct.id_cita_medica")
        ->Join("paciente as pc","ct.id_paciente","=","pc.id_paciente")
        ->Join("persona as p","pc.id_persona","=","p.id_persona")
        ->Join("especialidad as esp","ct.id_especialidad","=","esp.id_especialidad")
        ->Where("ct.id_medico","=",$MedicoId)
        ->And("pc.pacientesede_id","=",$sede)
        ->select("concat(apellidos,' ',nombres) as paciente_","p.documento","pc.id_paciente")
        ->get();
      }else{
         

        $paciente = new Paciente;
        $Historial = $paciente->query()->Join("persona as p","pc.id_persona","=","p.id_persona")
        ->Join("tipo_documento as t","p.id_tipo_doc","=","t.id_tipo_doc")
        ->select("concat(apellidos,' ',nombres) as paciente_","p.documento","pc.id_paciente","p.genero","t.name_tipo_doc")
        ->Where("pc.pacientesede_id","=",$sede)
        ->get();
      }
 
     self::json(['personas'=>$Historial]);
    }
  }

  /// ver el historial del paciente
  public static function reporteHistorialPaciente()
  {
    self::NoAuth();
    if(!empty(self::get('v')))
    {
      $historial = new HeaderHistorial(); 

      $modelData = new Plan_Atencion;

      $sede = self::profile()->sede_id;
      $data = $modelData->procedure("proc_reporte_del_paciente","C",[self::get("v"),null,'historia',$sede]);
      if(!$data){
        PageExtra::PageNoAutorizado();
        return;
      }
      
      $historial->SetTitle(utf8__("Historial médico-". $data[0]->pacientedata."-".$data[0]->num_expediente));

      $historial->AddPage();

      $historial->setFont("Helvetica","B",20);

      $historial->setX(0);
      $historial->Cell(210,0,utf8__("HISTORIAL CLINICO"),0,1,"C");

      $historial->Ln(6.5);
      $historial->setX(5.5);
      
      $historial->SetFillColor(230,230,230);
      $historial->SetDrawColor(220,220,220);
      $historial->RoundedRect(5.5, 43, 199, 21.5, 2,"1234","DF");

      /// DATOS DEL PACIENTE
      /// expediente
      
      $historial->Ln(2);
      $historial->setX(8);
      $historial->setFont("Helvetica","",10);
      $historial->setTextColor(0,0,128);
      $historial->Cell(120,2,utf8__("N° FOLIO: "),0,0,"C");

      $historial->setX(50);
      $historial->setFont("Helvetica","B",10);
      $historial->Cell(120,2,$data[0]->num_expediente,0,1,"C");

      $historial->setTextColor(0,0,0);
      $historial->Ln(3.4);
      $historial->setX(8);
      $historial->setFont("Helvetica","",10);
      $historial->Cell(12,2,"PACIENTE: ",0,0);

      $historial->setX(30);
      $historial->setFont("Helvetica","B",10);
      $historial->Cell(12,2,strtoupper(utf8__($data[0]->pacientedata)),0,0);

      

      /// # documento
      $historial->Ln(3);
      $historial->setX(145);
      $historial->setFont("Helvetica","",10);
      $historial->Cell(12,2,"DOCUMENTO: ",0,0);

      $historial->setX(170);
      $historial->setFont("Helvetica","B",10);
      $historial->Cell(12,2,$data[0]->documento,0,1);


      /// fecha de nacimiento
      $historial->Ln(3);
      $historial->setX(145);
      $historial->setFont("Helvetica","",10);
      $historial->Cell(12,2,"FECHA NCTO: ",0,0);

      $historial->setX(170);
      $historial->setFont("Helvetica","B",10);
      $historial->Cell(12,2,($data[0]->fecha_nacimiento != null ? self::FechaFormat($data[0]->fecha_nacimiento) : ''),0,0);

      ///edad
      $Edad =  $data[0]->fecha_nacimiento != null ? calcularEdad($data[0]->fecha_nacimiento,self::FechaActual("Y-m-d")) : '';
      $historial->setY(56.3);
      $historial->setX(8);
      $historial->setFont("Helvetica","",10);
      $historial->Cell(12,2,"EDAD: ",0,0);

      $historial->setX(30);
      $historial->setFont("Helvetica","B",10);
      $historial->Cell(12,2,utf8__($Edad["años"]." año(s)"." ".$Edad["meses"]." mes(es)"),0,0);


      $historial->setX(90);
      $historial->setFont("Helvetica","",10);
      $historial->Cell(12,2,utf8__("GÉNERO: "),0,0);

      $historial->setX(108);
      $historial->setFont("Helvetica","B",10);
      $historial->Cell(12,2,utf8__($data[0]->genero === '1' ? 'MASCULINO' : 'FEMENINO'),0,1);

      /// GENERO

      /// DOMICILIO
      $Distrito = $data[0]->name_distrito != null ? $data[0]->name_distrito :'';
      $Provincia = $data[0]->name_provincia != null ? $data[0]->name_provincia:'';
      $Departamento = $data[0]->name_departamento != null ? $data[0]->name_departamento:'';
      $Direccion = $data[0]->direccion != null ? $data[0]->direccion :'';
      $historial->setY(60.5);
      $historial->setX(8);
      $historial->setFont("Helvetica","",10);
      $historial->Cell(12,2,"DOMICILIO: ",0,0);

      $historial->setX(30);
      $historial->setFont("Helvetica","B",10);
      $historial->Cell(12,2,utf8__(rtrim($Distrito." - ".$Provincia." - ".$Departamento." - ".$Direccion," - ")),0,1);

      /// servicio
      $historial->setX(5.5);
       
      $historial->RoundedRect(5.5, 67, 199, 22, 2,"1234" );

      /// nombre del servico

      $historial->setY(72);
      $historial->setX(35);
      $historial->setFont("Helvetica","",10);
      $historial->Cell(13,2,"SERVICIO:",0,0);

      $historial->setX(55);
      $historial->setFont("Helvetica","B",9);
      $historial->Cell(10,2,($data[0]->name_servicio != null ? strtoupper(utf8__($data[0]->name_servicio)):'CONSULTA GENERAL'),0,1);

      /// profesional tratante
      $historial->Ln(3);
      $historial->setX(7);
      $historial->setFont("Helvetica","",10);
      $historial->Cell(16,2,"PROFESIONAL TRATANTE:",0,0);

      $historial->setX(55);
      $historial->setFont("Helvetica","B",10);
      $historial->Cell(10,2,"Dr. ".utf8__($data[0]->medicodata) ,0,1);


       /// Fecha de atencion
       $historial->Ln(3);
       $historial->setX(14.5);
       $historial->setFont("Helvetica","",10);
       $historial->Cell(16,2,utf8__("FECHA DE ATENCIÓN:"),0,0);
 
       $historial->setX(55);
       $historial->setFont("Helvetica","B",10);
       $historial->Cell(10,2,utf8__(self::getFechaText(self::FechaFormat($data[0]->fecha_atencion))) ,0,1);



       /// codigo QR
       self::setDirectorioQr("public/qr/historialqr.png");
       
      self::GenerateQr($data[0]->documento."|".$data[0]->pacientedata."|".$data[0]->num_expediente."|".$data[0]->fecha_atencion);
      $historial->SetX(145);
      $historial->Cell(74,0,$historial->Image(URL_BASE.self::getDirectorioQr(),181,67,21.5,21.5),0,1,"C");



       /// SIGNOS VITALES(EXAMEN FISICO)
       $historial->Ln(11);
       $historial->setX(6);
       $historial->SetFont("Helvetica","B",11);
       $historial->Cell(60,2,utf8__("SIGNOS VITALES - EXAMEN FÍSICO"),0,1);

       $historial->setX(6);
       $historial->setFont("Helvetica","",12);
       $historial->SetTextColor(105,105,105);
       $historial->Cell(190,1,utf8__("____________________________________________________________________________________"),0,1);
       
       $historial->Ln(5);
       $historial->setTextColor(0,0,0);
       $historial->setFont("Helvetica","B",10);
       $historial->setX(8);
       $historial->Cell(18,2,utf8__("Presión arterial: "),0,0);
       
       $historial->setX(35);
       $historial->setFont("Helvetica","I",10);
       $historial->Cell(10,2,($data[0]->presion_arterial != null ? utf8__($data[0]->presion_arterial) : ''),0,0);


       $historial->setFont("Helvetica","B",10);
       $historial->setX(75);
       $historial->Cell(18,2,utf8__("Temperatura C°: "),0,0);
       
       $historial->setX(105);
       $historial->setFont("Helvetica","I",10);
       $historial->Cell(10,2,($data[0]->temperatura != null ? utf8__($data[0]->temperatura) : ''),0,0);


       $historial->setFont("Helvetica","B",10);
       $historial->setX(130);
       $historial->Cell(18,2,utf8__("Frecuencia cardiaca: "),0,0);
       
       $historial->setX(166);
       $historial->setFont("Helvetica","I",10);
       $historial->Cell(10,2,($data[0]->frecuencia_cardiaca != null ? utf8__($data[0]->frecuencia_cardiaca) : ''),0,1);


       $historial->Ln(4);
       $historial->setTextColor(0,0,0);
       $historial->setFont("Helvetica","B",10);
       $historial->setX(8);
       $historial->Cell(18,2,utf8__("Saturación de oxígeno: "),0,0);
       
       $historial->setX(48.5);
       $historial->setFont("Helvetica","I",10);
       $historial->Cell(10,2,($data[0]->saturacion_oxigeno != null ? utf8__($data[0]->saturacion_oxigeno) : ''),0,0);


       $historial->setFont("Helvetica","B",10);
       $historial->setX(75);
       $historial->Cell(18,2,utf8__("Talla: "),0,0);
       
       $historial->setX(86);
       $historial->setFont("Helvetica","I",10);
       $historial->Cell(10,2,($data[0]->talla != null ? utf8__($data[0]->talla." mt") : ''),0,0);


       $historial->setFont("Helvetica","B",10);
       $historial->setX(130);
       $historial->Cell(18,2,utf8__("Peso: "),0,0);
       
       $historial->setX(141.5);
       $historial->setFont("Helvetica","I",10);
       $historial->Cell(10,2,($data[0]->peso != null ? utf8__($data[0]->peso." Kg") : ''),0,1);




       $historial->Ln(4);
       $historial->setTextColor(0,0,0);
       $historial->setFont("Helvetica","B",10);
       $historial->setX(8);
       $historial->Cell(18,2,utf8__("IMC: "),0,0);
       
       $historial->setX(17);
       $historial->setFont("Helvetica","I",10);
       $historial->Cell(10,2,($data[0]->imc != null ? utf8__($data[0]->imc) : ''),0,0);


       $historial->setFont("Helvetica","B",10);
       $historial->setX(75);
       $historial->Cell(18,2,utf8__("ESTADO IMC: "),0,0);
       
       $historial->setX(100);
       $historial->setFont("Helvetica","I",10);
       $historial->Cell(10,2,($data[0]->estado_imc != null ? strtoupper(utf8__($data[0]->estado_imc)) : ''),0,0);

       
       $historial->Ln(9);
       $historial->setX(6);
       $historial->SetFont("Helvetica","B",11);
       $historial->Cell(60,2,utf8__("ANTECEDENTES"),0,1);

       $historial->setX(6);
       $historial->setFont("Helvetica","",12);
       $historial->SetTextColor(105,105,105);
       $historial->Cell(190,1,utf8__("____________________________________________________________________________________"),0,1);

        $historial->Ln(4);
       $historial->setFont("Helvetica","",8);
       $historial->setX(8);
       $historial->SetTextColor(0,0,0);
       $historial->MultiCell(190,5,($data[0]->antecedentes != null ? utf8__($data[0]->antecedentes):''),0,"L");


      if ($data[0]->genero === "2") {
       $historial->Ln(4);
       $historial->setX(6);
       $historial->SetFont("Helvetica","B",11);
       $historial->Cell(60,2,utf8__("DATOS DE GESTACIÓN"),0,1);   
      
       $historial->Ln(4);
       $historial->setFont("Helvetica","B",10);
       $historial->setX(8);
       $historial->SetTextColor(0,0,0);
       $historial->Cell(23,2,"Gestando ? : ",0,0);

       $historial->setFont("Helvetica","",10);
       $historial->Cell(10,2,$data[0]->gestante,0,0);

       

       $historial->setFont("Helvetica","B",10);
       $historial->setX(40);
       $historial->SetTextColor(0,0,0);
       $historial->Cell(33,2,"Edad gestacional : ",0,0);

       $historial->setFont("Helvetica","",10);
       $historial->Cell(10,2,($data[0]->edad_gestacional!= null ? $data[0]->edad_gestacional : '------------------------------------------------'),0,0);

       
       $historial->setFont("Helvetica","B",10);
       $historial->setX(145);
       $historial->SetTextColor(0,0,0);
       $historial->Cell(28,2,"Fecha de parto : ",0,0);

       $historial->setFont("Helvetica","",10);
       $historial->Cell(10,2,($data[0]->fecha_parto != null ? $data[0]->fecha_parto : '-------------------------'),0,0);

      }
       /// antecedentes patologicos
       $historial->Ln(7);
       $historial->setX(6);
       $historial->SetTextColor(0,0,0);
       $historial->SetFont("Helvetica","B",11);
       $historial->Cell(60,2,utf8__("ANTECEDENTES PATOLÓGICOS"),0,1);

       $historial->setX(6);
       $historial->setFont("Helvetica","",12);
       $historial->SetTextColor(105,105,105);
       $historial->Cell(190,1,utf8__("____________________________________________________________________________________"),0,1);


       $historial->Ln(5);
       $historial->setTextColor(0,0,0);
       $historial->setFont("Helvetica","B",10);
       $historial->setX(8);
       $historial->Cell(18,2,chr(149)." ".utf8__("DIABETES: "),0,0);
       
       $historial->setX(30);
       $historial->setFont("Helvetica","I",10);
       $historial->Cell(10,2,($data[0]->diabetes != null ? strtoupper($data[0]->diabetes) : ''),0,0);


       $historial->setFont("Helvetica","B",10);
       $historial->setX(75);
       $historial->Cell(18,2,chr(149)." ".utf8__("HIPERTENSIÓN ARTERIAL: "),0,0);
       
       $historial->setX(124.5);
       $historial->setFont("Helvetica","I",10);
       $historial->Cell(10,2,($data[0]->hipertencion_arterial != null ? strtoupper($data[0]->hipertencion_arterial) : ''),0,0);


       $historial->setFont("Helvetica","B",10);
       $historial->setX(150);
       $historial->Cell(18,2,chr(149)." ".utf8__("TRANSFUSIONES: "),0,0);
       
       $historial->setX(184.5);
       $historial->setFont("Helvetica","I",10);
       $historial->Cell(10,2,($data[0]->transfusiones != null ? strtoupper($data[0]->transfusiones) : ''),0,1);


       $historial->Ln(4);
       $historial->setTextColor(0,0,0);
       $historial->setFont("Helvetica","B",10);
       $historial->setX(8);
       $historial->Cell(18,2,chr(149)." ".utf8__("TIEMPO ENFERMEDAD: "),0,0);
       
       $historial->setX(52);
       $historial->setFont("Helvetica","I",10);
       $historial->Cell(10,2,($data[0]->tiempo_enfermedad != null ? utf8__($data[0]->tiempo_enfermedad) : ''),0,0);



       $historial->setFont("Helvetica","B",10);
       $historial->setX(75);
       $historial->Cell(18,2,chr(149)." ".utf8__("VACUNAS COMPLETOS: "),0,0);
       
       $historial->setX(120);
       $historial->setFont("Helvetica","I",10);
       $historial->Cell(10,2,($data[0]->vacunas_completos != null ? strtoupper($data[0]->vacunas_completos === 'se' ? 'NO' : $data[0]->vacunas_completos) : ''),0,1);



       $historial->Ln(4);
       $historial->setTextColor(0,0,0);
       $historial->setFont("Helvetica","B",10);
       $historial->setX(8);
       $historial->Cell(18,2,chr(149)." ".utf8__("ALERGIAS: "),0,0);
       
       $historial->setX(31);
       $historial->setFont("Helvetica","I",10);
       $historial->MultiCell(160,1.9,($data[0]->alergias != null ? utf8__($data[0]->alergias) : ''),0,1);


       $historial->Ln(4);
       $historial->setTextColor(0,0,0);
       $historial->setFont("Helvetica","B",10);
       $historial->setX(8);
       $historial->Cell(18,2,chr(149)." ".utf8__("MEDICAMENTOS ACTUALES: "),0,0);
       
       $historial->setX(63);
       $historial->setFont("Helvetica","I",10);
       $historial->MultiCell(160,2,($data[0]->medicamento_actuales != null ? utf8__($data[0]->medicamento_actuales) : ''),0,1);

       $historial->Ln(4);
       $historial->setTextColor(0,0,0);
       $historial->setFont("Helvetica","B",10);
       $historial->setX(8);
       $historial->Cell(18,2,chr(149)." ".utf8__("CIRUJIAS PREVIAS: "),0,0);
       
       $historial->setX(47);
       $historial->setFont("Helvetica","I",10);
       $historial->MultiCell(160,2,($data[0]->cirujias_previas != null ? utf8__($data[0]->cirujias_previas) : ''),0,0);


       $historial->Ln(4);
       $historial->setTextColor(0,0,0);
       $historial->setFont("Helvetica","B",10);
       $historial->setX(8);
       $historial->Cell(18,2,chr(149)." ".utf8__("OTROS: "),0,0);
       
       $historial->setX(25);
       $historial->setFont("Helvetica","I",10);
       $historial->MultiCell(160,2,($data[0]->otros != null ? utf8__($data[0]->otros) : ''),0,0);

       /// motivo de la consulta
       $historial->Ln(6);
       $historial->setX(6);
       $historial->SetTextColor(0,0,0);
       $historial->SetFont("Helvetica","B",11);
       $historial->Cell(60,2,utf8__("MOTIVO DE LA CONSULTA"),0,1);

       $historial->setX(6);
       $historial->setFont("Helvetica","",12);
       $historial->SetTextColor(105,105,105);
       $historial->Cell(190,1,utf8__("____________________________________________________________________________________"),0,1);

       $historial->Ln(4);
       $historial->setFont("Helvetica","",8);
       $historial->setX(8);
       $historial->SetTextColor(0,0,0);
       $historial->MultiCell(190,5,($data[0]->observacion != null ? utf8__($data[0]->observacion):''),0,"L");

       /// DIAGNOSTICO GENERAL
       $historial->Ln(3);
       $historial->setX(6);
       $historial->SetTextColor(0,0,0);
       $historial->SetFont("Helvetica","B",11);
       $historial->Cell(60,2,utf8__("DIAGNOSTICO GENERAL"),0,1);

       $historial->setX(6);
       $historial->setFont("Helvetica","",12);
       $historial->SetTextColor(105,105,105);
       $historial->Cell(190,1,utf8__("____________________________________________________________________________________"),0,1);

       $historial->Ln(4);
       $historial->setFont("Helvetica","",8);
       $historial->setX(8);
       $historial->SetTextColor(0,0,0);
       $historial->MultiCell(190,5,($data[0]->diagnostico_general != null ? utf8__($data[0]->diagnostico_general):''),0,"L");

       /// exámen fisico
       $historial->Ln(3);
       $historial->setX(6);
       $historial->SetTextColor(0,0,0);
       $historial->SetFont("Helvetica","B",11);
       $historial->Cell(60,2,utf8__("EXAMEN FÍSICO"),0,1);

       $historial->setX(6);
       $historial->setFont("Helvetica","",12);
       $historial->SetTextColor(105,105,105);
       $historial->Cell(190,1,utf8__("____________________________________________________________________________________"),0,1);

       $historial->Ln(4);
       $historial->setFont("Helvetica","",8);
       $historial->setX(8);
       $historial->SetTextColor(0,0,0);
       $historial->MultiCell(190,5,($data[0]->resultado_examen_fisico != null ? utf8__($data[0]->resultado_examen_fisico):''),0,"L");

        
       ///fin

       $historial->Ln(3);
       $historial->setX(6);
       $historial->SetTextColor(0,0,0);
       $historial->SetFont("Helvetica","B",11);
       $historial->Cell(60,2,utf8__("PROCEDIMIENTO"),0,1);

       $historial->setX(6);
       $historial->setFont("Helvetica","",12);
       $historial->SetTextColor(105,105,105);
       $historial->Cell(190,1,utf8__("____________________________________________________________________________________"),0,1);

       $historial->Ln(4);
       $historial->setFont("Helvetica","",8);
       $historial->setX(8);
       $historial->SetTextColor(0,0,0);
       $historial->MultiCell(190,5,($data[0]->procedimiento != null ? utf8__($data[0]->procedimiento):''),0,"L");


       // fin

       $historial->Ln(3);
       $historial->setX(6);
       $historial->SetTextColor(0,0,0);
       $historial->SetFont("Helvetica","B",11);
       $historial->Cell(60,2,utf8__("TRATAMIENTO"),0,1);

       $historial->setX(6);
       $historial->setFont("Helvetica","",12);
       $historial->SetTextColor(105,105,105);
       $historial->Cell(190,1,utf8__("____________________________________________________________________________________"),0,1);

       $historial->Ln(5);
       $historial->setFont("Helvetica","B",8);
       $historial->setX(8);
       $historial->SetTextColor(0,0,0);
       
       $historial->Cell(4,1,"RP/.",0,0);
       $historial->setX(20);
       $historial->Cell(4,1,utf8__("DESCRIPCIÓN"),0,0);

       $historial->setX(70);
       $historial->Cell(4,1,utf8__("CANTIDAD"),0,0);

       $historial->setX(90);
       $historial->Cell(4,1,utf8__("RX/."),0,0);

       $recetaDetalle = $modelData->procedure("proc_reporte_del_paciente","C",[self::get("v"),null,'receta',$sede]);

       $itemReceta = 0;
       foreach($recetaDetalle as $receta){
        $itemReceta++;
       $historial->Ln(5);
       $historial->setFont("Helvetica","I",7.5);
       $historial->setX(8);
       $historial->SetTextColor(0,0,0);
       
       $historial->Cell(4,1,$itemReceta,0,0);
       $historial->setX(20);
       $historial->MultiCell(48,2.5,utf8__($receta->productodata),0,0);

       $historial->setX(70);
       $historial->Cell(15,0,utf8__($receta->cantidad_producto),0,0,"C");

       $historial->setX(90);
       $historial->MultiCell(112,2.5,$itemReceta." .   ".utf8__($receta->indicaciones),0,0);
       }
       

       /// diagnostico ci 10
 
       $historial->Ln(7);
       $historial->setX(6);
       $historial->SetTextColor(0,0,0);
       $historial->SetFont("Helvetica","B",11);
       $historial->Cell(60,2,utf8__("DIAGNOSTICO CIE 10: "),0,1);

       $historial->setX(6);
       $historial->setFont("Helvetica","",12);
       $historial->SetTextColor(105,105,105);
       $historial->Cell(190,1,utf8__("____________________________________________________________________________________"),0,1);

       $diagnosticoDetalle = $modelData->procedure("proc_reporte_del_paciente","C",[self::get("v"),null,'diagnostico',$sede]);

       $itemDiagnostico = 0;
       foreach($diagnosticoDetalle as $diagnostico){
        $itemDiagnostico++;
       $historial->Ln(5);
       $historial->setFont("Helvetica","B",8.5);
       $historial->setX(8);
       $historial->SetTextColor(0,0,0);
       
       $historial->Cell(4,1,$diagnostico->codigo_enfermedad,0,0);
       $historial->setX(40);
       $historial->Cell(4,1,utf8__($diagnostico->enfermedad_desc),0,0);

       $historial->setX(130);
       $historial->Cell(15,1,utf8__($diagnostico->tipodiagnostico === 'p' ? 'PRESUNTIVO' : ($diagnostico->tipodiagnostico === 'r' ? 'REPETITIVO' : 'DEFINITIVO')),0,0,"C");

       }
       $historial->Ln(1.5);
       $historial->setX(6);
       $historial->setFont("Helvetica","",12);
       $historial->SetTextColor(105,105,105);
       $historial->Cell(190,1,utf8__("____________________________________________________________________________________"),0,1);

       /// ORDEN MEDICA
       $historial->Ln(12);
       $historial->setX(6);
       $historial->setFont("Helvetica","B",11);
       $historial->SetTextColor(0,0,0);
       $historial->Cell(18,2,utf8__("ORDEN MEDICA LABORATORIO: "),0,1);
        $historial->setX(6);
       $historial->setFont("Helvetica","",12);
       $historial->Cell(190,1,utf8__("____________________________________________________________________________________"),0,1);

       $historial->Ln(4);
       $historial->setX(10);
       $historial->setFont("Helvetica","B",9);
       $historial->Cell(40,2,utf8__("CÓDIGO"),0,0);
       $historial->Cell(100,2,utf8__("DESCRIPCIÓN"),0,0);
       $historial->Cell(30,2,"TIPO",0,1);


       /// MOSTRAR LAS ORDENES DE LABORATORIO
       $DataOrden = $modelData->query()->Join("ordenmedico as o","o.atencion_id","=","plan.id_atencion_medica")
       ->Join("detalle_ordenes_pacientes as dop","dop.orden_medico_id","=","o.id_orden_medico")
       ->select("dop.code_orden","dop.descripcion_orden","dop.tipo_orden")
       ->Where("plan.num_expediente","=",$data[0]->num_expediente)
       ->get();

       $historial->setFont("Helvetica","",8);
       foreach($DataOrden as $orden){
       $historial->Ln(4);
       $historial->setX(10);
       $historial->Cell(40,2,$orden->code_orden,0,0);
       $historial->Cell(100,2,$orden->descripcion_orden,0,0);
       $historial->Cell(30,2,$orden->tipo_orden,0,1);

       }
      
       /// proxima cita
       $historial->Ln(12);
       $historial->setX(13);
       $historial->setFont("Helvetica","B",11);
       $historial->SetTextColor(0,0,0);
       $historial->Cell(18,2,utf8__("PRÓXIMA CITA: "),0,0);

       
       $historial->setFont("Helvetica","",11);
       $historial->setX(44);
       $historial->Cell(18,2,utf8__(self::getFechaText(self::FechaFormat($data[0]->proxima_cita))),0,1);
       
       $historial->setX(13);
       $historial->setFont("Helvetica","B",11);
       $historial->SetTextColor(0,0,0);
       
       $historial->Ln(10);
        if(self::MedicoData()->firma != null){
          $historial->Image(URL_BASE."public/asset/imgfirmas/".self::MedicoData()->firma,70,null,65,17,"JPG");
        }
 
       $historial->setX(65);
       $historial->setFont("Helvetica","B",11);
       $historial->SetTextColor(0,0,0);
       $historial->Cell(18,7,utf8__("________________________________________"),0,1);
       $historial->setX(78);
       $historial->Cell(18,2,utf8__("Med.".self::profile()->apellidos." ".self::profile()->nombres),0,1);
      $historial->Output();
    }
    else
    {
      //self::RedirectTo("nueva_atencion_medica");
      PageExtra::Page404();
    }
  }

  /** Ver la vista de consultar los servicios del médico */
  public static function MisServicios()
  {
    self::NoAuth();

    /// traemos a las especialidades del médico
    $medicoesp = new Especialidad_Medico;

    $MedicoAuth = self::MedicoData()->id_medico;
    $Data = $medicoesp->query()->Join("medico as med","med_esp.id_medico","=","med.id_medico")
    ->Join("especialidad as esp","med_esp.id_especialidad","=","esp.id_especialidad")
    ->Where("med_esp.id_medico","=",$MedicoAuth)
    ->get();
    
    self::View_("medico.mis_servicios",compact("Data"));
  }

  /** ver los servicios del médico en json */
  public static function dataServiciosMedico(int|null|string $id)
  {
    self::NoAuth();
    if(self::ValidateToken(self::get("token_")))
    {
      $servicioModel = new Servicio;

      $DataServicio = $servicioModel->query()
      ->Join("especialidad as es","serv.especialidad_id","=","es.id_especialidad")
      
      ->where("deleted_at","is",null)
    
      ->limit(self::get("limit"))
      ->get();
 
    }
    else
    {
      $DataServicio = [];
    }

    self::json(["response"=>$DataServicio]);
  }

  /** ver los servicios del médico en json */
  public static function dataServiciosMedicoEliminados(int|null|string $id)
  {
    self::NoAuth();
    if(self::ValidateToken(self::get("token_")))
    {
      $servicioModel = new Servicio;

      $DataServicio = $servicioModel->query()->where("serv.especialidad_id","=",$id)
      ->And("deleted_at","is not",null)
      ->get();
 
    }
    else
    {
      $DataServicio = [];
    }

    self::json(["response"=>$DataServicio]);
  }
  
  public static function addServicio()
  {
    /// verificamos que el token Csrf estee validado
    if(self::ValidateToken(self::post("token_")))
    {
      $servicioModel = new Servicio;

      /// verificamos la existencia del servicio

      $ServicioExiste = $servicioModel->query()->Where("name_servicio","=",self::post("name_servicio"))->first();
    
      if(!$ServicioExiste)
      {

        $Response = $servicioModel->Insert([
          "name_servicio" => self::post("name_servicio"),
          "precio_servicio" => self::post("precio_servicio"),
          "id_medico_esp" => self::post("medico_esp") 
        ]);
  
        self::json(["response" => $Response?'ok':'error']);
      }
      else
      {
        self::json(["response" => 'existe']);
      }
    }
    else
    {
      self::json(["response" => 'token-invalidate']);
    }
  }

  /** Importar mediante excel los datos */
  public static function importDatService()
  {
    /// validamos el token 
    if(self::ValidateToken(self::post("token_")))
    {
      /// validamos de que exista el archivo seleccionado
      if(self::file_size("excel_file") > 0)
      {
           /// Ahora validamos que sea un archivo excel
          if(self::file_Type("excel_file") === self::$TipoArchivoAceptable)
          {
           /// realizamos el import data del servicio
           self::importarServicioExcelMedico(self::ContentFile("excel_file"));
          }else
          {
            self::json(["response" => "archivo no acceptable"]);
          }
      }
      else
      {
        self::json(["response"=>"vacio"]);
      }
    }
  }

  /// proceso importar datos del servicio excel al la tabla servicio
  private static function importarServicioExcelMedico($archivo)
  {
   $modelService = new Servicio;
   /// llamar a la libreria office
   $office = IOFactory::load($archivo);

   /// indicamos la hoja 0 
   $HojaCero = $office->getSheet(0);

   /// indicamos la cantidad de filas que tiene esa hoja 0
   $RowsHoja = $HojaCero->getHighestDataRow();


   for($fila_row = 2;$fila_row  <= $RowsHoja;$fila_row++ )
   {
     $NombreServicio = $HojaCero->getCellByColumnAndRow(1,$fila_row);

     $PrecioServicio = $HojaCero->getCellByColumnAndRow(2,$fila_row);

     // agregamos a la tabla servicio
     if(self::existeServicio($NombreServicio, self::post("medico_esp")))
     {
      $Response = 'existe';
     }
     else
     {
      $Response = $modelService->Insert([
        "name_servicio" => $NombreServicio,
        "precio_servicio" => $PrecioServicio,
        "id_medico_esp" => self::post("medico_esp")
       ]);
     }
   }

   self::json(['response' => $Response?'ok':($Response === 'existe'?'existe':'error')]);
  }

  /**
   * Verificamos si existe ya el servicio
   */
  private static function existeServicio(string $servicio,int $medico_esp)
  {
    $modelService = new Servicio;

    return $modelService->query()->Where("name_servicio","=",$servicio)
    ->And("id_medico_esp","=",$medico_esp)->first();
  }

  /** Modificar los servicios del médico */
  public static function updateServicio(int $id)
  {
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::post("token_")))
    {
      $modelservice = new Servicio;

      $Response = $modelservice->Update([
        "id_servicio" => $id,
        "name_servicio" => self::post("name_servicio"),
        "precio_servicio" => self::post("precio_servicio")
      ]);

      self::json(["response" => $Response ? 'ok':'error']);
    }
    else
    {
      self::json(["response"=>"token_invalidate"]);
    }
  }

  /** Eliminar servicio del médico */
  public static function DeleteSoftServicio(int $id)
  {
   self::NoAuth();
   /// validamos el token
   if(self::ValidateToken(self::post("token_")))
   {
    $modelService = new Servicio;

    $Respuesta = $modelService->Update([
      "id_servicio" => $id,
      "deleted_at" => self::FechaActual("Y-m-d H:i:s")
    ]);

    self::json(["response" => $Respuesta ? 'ok':'error']);
   }
   else
   {
    self::json(["response" => "invalidate_token"]);
   }
  }

   /** EVolver activar el servicio del médico */
   public static function ActiveSoftServicio(int $id)
   {
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::post("token_")))
    {
     $modelService = new Servicio;
 
     $Respuesta = $modelService->Update([
       "id_servicio" => $id,
       "deleted_at" => null
     ]);
 
     self::json(["response" => $Respuesta ? 'ok':'error']);
    }
    else
    {
     self::json(["response" => "invalidate_token"]);
    }
   }

   /// método para visualizar un reporte estadístico por año
   public static function CitasPorAnio_Gr_Estadistico(string $tipo = 'anual')
   {
    /// validamos el token
    if(self::ValidateToken(self::get("token_")))
    {
      $modelReporte = new CitaMedica;
      $rol = self::profile()->rol;
      $sede = self::profile()->sede_id;
      $respuesta = $modelReporte->procedure("proc_reporte_estadistico_citas","c",[$tipo,$rol,$sede]);
      self::json(['reporte'=>$respuesta]);
    }
   }
 
   /**Citas médicas finalzizados por mes de acuerdo a un médico */
   public static function cantidad_pacientes_atendidos()
   {
    /**Validamos el token */
    if(self::ValidateToken(self::get("token_")))
    {
      $modelData = new CitaMedica;
      $medico = self::MedicoData()->id_medico;
      $respuesta = $modelData->query()
      ->select("spanishmonthname(monthname(ctm.fecha_cita)) as mes","count(*) as cantidad")
      ->Where("ctm.id_medico","=",$medico)
      ->And("ctm.estado","=","finalizado")
      ->And("year(ctm.fecha_cita)","=",self::FechaActual("Y"))
      ->GroupBy(["mes"])->get();
      self::json(["response"=>$respuesta]);
    }
   }

   /** Ver la vista de generar recibo del médico */

   public static function recibo()
   {
    /// verificamos que estee authenticado
    self::NoAuth();// si no está authenticado redirige al logín
    /// verificamos que quién realice esta acción sea el médico
    if(self::profile()->rol === 'Médico' || self::profile()->rol === self::$profile[1])
    {
      /// creamos el modelo del recibo
      $modelRecibo = new Recibo;$medico_especialidad = new Especialidad_Medico;
      $IdRecibo = $modelRecibo->ObtenerMaxRecibo()->num;

      if(self::profile()->rol === 'Médico'){
        $MedicoId_ = self::MedicoData()->id_medico;
        $Data = $medico_especialidad->query()->Join("medico as med", "med_esp.id_medico", "=", "med.id_medico")
          ->Join("especialidad as esp", "med_esp.id_especialidad", "=", "esp.id_especialidad")
          ->Where("med_esp.id_medico", "=", $MedicoId_)
          ->get();
      }else{
        $especialidad = new Especialidad;
        $Data = $especialidad->query()->get();
      }
      return self::View_("medico.recibo",compact("IdRecibo","Data"));
    }
    
    PageExtra::PageNoAutorizado();
   }

    

   /// editar la órden de laboratorio
   public static function editarOrdenLaboratorio(string $id){
    self::NoAuth();
    if(self::profile()->rol === 'Médico')
    {
       /// validamos token
       if(self::ValidateToken(self::get("token_")))
       {
          $atencionModel = new  Plan_Atencion;

          $respuesta = $atencionModel->query()->Where("id_atencion_medica","=",$id)->first();

          self::json(["response" => $respuesta]);

       }else{
        self::json(["response" => "token-invalidate"]);
       }
    }else{
      self::json(["response" => "no-authorized"]);
    }
   }
   /// generar nueva orden de laboratorio
   public static function UpdateOrdenLaboratorio(string $id)
   {
    self::NoAuth();
    if(self::profile()->rol === 'Médico')
    {
      if(self::ValidateToken(self::post("token_")))
      {
        $ordenmedica = new OrdenMedico;
        $detalleordenes_paciente = new Detalle_Ordenes_Paciente;
        $usuario = self::profile()->id_usuario;$sede = self::profile()->sede_id;
        $respuestaUpdate = $ordenmedica->Insert([
          "id_orden_medico" => self::post("serieorden"),
          "atencion_id" => $id,
          "paciente_id"=>self::post("paciente"),
          "serieorden" => self::post("serieorden"),
          "fecha_registro_orden" => self::FechaActual("d/m/Y H:i:s A"),
          "usuario_id" => $usuario,"sede_id" => $sede
        ]);

        if($respuestaUpdate){
          $ordenData = $ordenmedica->query()->Where("serieorden","=",self::post("serieorden"))->get();
           if(self::ExistSession("orden")){
            foreach(self::getSession("orden") as $orde){
              $detalleordenes_paciente->Insert([
                "orden_medico_id" => $ordenData[0]->id_orden_medico,
                "examen_id" => $orde["examen_id"],
                "code_orden" => $orde["codigo_examen"],
                "descripcion_orden" => $orde["examen_desc"],
                "categoria_orden" => $orde["categoria_orden"],
                "cantidad"=>1,
                "tipo_orden" => $orde["tipo_examen"]
              ]);
            }
           }

           unset($_SESSION["orden"]);
        }

        self::json(["response" => $respuestaUpdate ? 'ok':'error']);
      }
      else{
        self::json(["response" => "token-invalidate"]);
      }
    }else{
      self::json(["response" => "no-authorized"]);
    }
   }


   /**
    * Modificar los datos del médico
    */
    public static function updateMedico($personaId,$MedicoId)
    {
      self::NoAuth();
      if(self::ValidateToken(self::post("token_")))
      {
        self::$ModelPersona = new Persona; $modelMedico = new Medico;
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
              "id_tipo_doc" => self::post("tipo_doc"),"id_distrito" => self::post("distrito"),
            ]);
        }
    
        if($respuesta)
        {
          /// actualizamos los datos del paciente
           $modelMedico->Update([
            "id_medico"=>$MedicoId,"celular_num"=>self::post("telefono"),"universidad_graduado"=>self::post("universidad"),
            "experiencia"=>self::post("experiencia"),"cmp" => self::post("cmp"),
            "medicosede_id" => self::post("sedeeditar")
          ]);
        /// modificamos la sede del usuairo correspondiente al paciente
        if ($ExisteNumDocumento->id_usuario != null) {
          $usu = new Usuario;
          $usu->Update([
            "id_usuario" => $ExisteNumDocumento->id_usuario,
            "sede_id" => self::post("sedeeditar")
          ]);
        }
          self::json(['response'=>'success']);
        }else{self::json(['response'=>'error']);}
      }
    }

    /**
     * Proceso para eliminar a un médico
     */
    public static function eliminar($id,$medicoid){
      if(self::ValidateToken(self::post("token_")))
      {
        $modelmedico = new CitaMedica;
        $datamedicocita = $modelmedico->query()->where("id_medico","=",$medicoid)->get();
        if(count($datamedicocita) > 0){
          $mensaje = "existe";
      } else {
        $medicomodel = new Usuario;
        $response = $medicomodel->delete($id);

        $mensaje = $response ? 'ok':'error';
        }

        self::json(["response" => $mensaje]);
      }else{
        self::json(["response" => "token-invalid"]);
      }
    }

   /**
    * Mostrar los dias de trabajo del mèdico
    */
    public static function showDiasTrabajo(){
      self::NoAuth();
      if(self::profile()->rol === self::$profile[3]){
      
      $sede = self::profile()->sede_id;
        /// enviamos los días de atencion del médico
      $modelmedico = new AtencionMedica;
      $medico = self::MedicoData()->id_medico;
      $dias = $modelmedico->query()
      ->Join("medico as m","atm.id_medico","=","m.id_medico")
      ->Where("atm.id_medico","=",$medico)
      ->And("m.medicosede_id","=",$sede)
      ->get();
      self::json(["dias"=>$dias]);
      }else{
        self::json(["dias" => []]);
      }
    }

    /// eliminar dia de atencion del médico
    public static function deleteDiaAtencion($id){
     self::NoAuth();

      if(self::ValidateToken(self::post("token_"))){
        $modelmedico = new AtencionMedica;
        $modelHorasProgramadas = new Programar_Horario;

        $HoraProgramada = $modelHorasProgramadas->query()->Where("id_atencion","=",$id)->get();

        if($HoraProgramada){
          self::json(["response" => "existe"]);
        }else{
          /// eliminamos
          $response = $modelmedico->delete($id);

          self::json(["response" => $response ? 'ok':'error']);
        }
      }else{
        self::json(["response"=>"error-token"]);
      }
    }

    /// Actualizar dia de trabajo

    public static function updateDiaTrabajo($id){
      self::NoAuth();
      if(self::ValidateToken(self::post("token_"))){
        $modelmedico = new AtencionMedica;
        $response = $modelmedico->update([
          "id_atencion" => $id,
          "dia" => self::post("dia"),
          "hora_inicio_atencion" => self::post("hora_inicio"),
          "hora_final_atencion" => self::post("hora_final")
        ]);

        self::json(["response" => $response ? 'ok': 'error']);
      }else{
        self::json(["response"=>"error-token"]);
      }
    }

    /**Ver reporte de los ingresos del médico por cada mes detallado */
    public static function reporteIngresosDetalladoMedicoPorMes(){
      self::NoAuth();

      /// ver todos los años regstrados
      $cita = new CitaMedica;
      $anios = $cita->query()->distinct()->select("year(ctm.fecha_cita) as anio")
      ->orderBy("anio","desc")
      ->get();
      self::View_("medico.reporte_ingresos_mes",compact("anios"));
    }

    public static function  showMedicosData(){
      self::NoAuth();

      $medico = new Medico;

      if(self::profile()->rol === self::$profile[0]){
        $sede = self::profile()->sede_id;
        $medicos = $medico->query()->Join("persona as p","me.id_persona","=","p.id_persona")
        ->Where("me.medicosede_id","=",$sede)
        ->get();
      }else{
        $medicos = $medico->query()->Join("persona as p","me.id_persona","=","p.id_persona")->get();
      }

      self::json(["medicos"=>$medicos]);
    }

    public static function  showReporteIngresosMensualMedico($medico,string $anio){
      self::NoAuth();
       
      $citamedico = new CitaMedica;

      $reportemedicoImporteMes = $citamedico->query()
                                  ->select("spanishmonthname(monthname(ctm.fecha_cita)) as mes","sum(monto_medico) as monto")
                                  ->where("id_medico","=",$medico)
                                  ->And("year(ctm.fecha_cita)","=",$anio)
                                  ->GroupBy(["mes"])
                                  ->orderBy("monto","desc")
                                  ->get();
                  self::json(["response" => $reportemedicoImporteMes]);
    }

    /// subir la firma del médico
    public static function subirFirma($id){
      self::NoAuth();
      if(self::profile()->rol === "admin_general" || self::profile()->rol === "Director"){
        if(self::ValidateToken(self::post("token_"))){
          self::$DestinoFoto = "public/asset/imgfirmas/";
          $FirmaImagen = self::CargarFoto("firma_imagen");
          if($FirmaImagen === "no-accept"){
            self::json(["error" => "ERROR, EL ARCHIVO SELECCIONADO ES INCORRECTO, SOLO SE ACEPTAN IMÁGENES TIPO JPG!!"]);
          }else{
            if($FirmaImagen === "vacio"){
              self::json(["error" => "DEBES DE SELECCIONAR UNA IMÁGEN !!!"]);
            }else{
              $medico = new Medico;
              $response = $medico->Update([
                "id_medico" => $id,
                "firma" => self::getNameFoto()
              ]);
              if($response){
                self::json(["success" => "LA FIRMA DEL MÉDICO A SIDO SUBIDO!!!"]);
              }else{
                self::json(["error" => "ERROR, AL SUBIR LA FIRMA!!!"]);
              }
            }
          }
        }else{
          self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
      }else{
        self::json(["error" => "ERROR, NO TIENE PERMISOS PARA SUBIR LA FIRMA DEL MÉDICO!!!"]);
      }
    }
    /// eliminar imagen firma
    public static function EliminarFirma($id){
      self::NoAuth();
      if(self::profile()->rol === "admin_general" || self::profile()->rol === "Director"){
        if(self::ValidateToken(self::post("token_"))){
           $medico = new Medico;
           $medicodata = $medico->query()->Where("id_medico","=",$id)->first();
           if($medicodata->firma != null){
             unlink("public/asset/imgfirmas/".$medicodata->firma);
             $medico->Update([
              "id_medico" => $id,
              "firma" => null
             ]);
             self::json(["success" => "LA FIRMA DEL MÉDICO SELECCIONADO A SIDO ELIMINADO!!"]);
           } 
        }else{
          self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
      }else{
        self::json(["error" => "ERROR, NO TIENE PERMISOS PARA SUBIR LA FIRMA DEL MÉDICO!!!"]);
      }
    }
}