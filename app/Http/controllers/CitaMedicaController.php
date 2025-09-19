<?php 

namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\{CitaMedica, Configuracion, Especialidad, Especialidad_Medico, Notificaciones, Paciente, Persona, Plan_Atencion, Programar_Horario, Servicio, TipoDocumento};
 

class CitaMedicaController extends BaseController

{
    private static $ModelEspecialidad,$ModelService,$ModelMedicEsp,$ModelPersona,$Model,$ModelHp,$ModelTipoDoc;

    private static array $error = [];
    /// mostramos la vista para crear citas médicas
    public static function index()
    {
        self::NoAuth();

        if((self::profile()->rol === self::$profile[0]|| self::profile()->rol === self::$profile[1] ||self::profile()->rol === self::$profile[3] ||  self::profile()->rol === self::$profile[2]) and isset(self::profile()->id_persona))
        {
            self::$Model = new Configuracion;

            $FechaActual  = self::FechaActual("Y-m-d");

            $DiaActual = self::getDayDate($FechaActual);

            $Es_Dia_Laborable = self::$Model->query()->Where("dias_atencion","=",$DiaActual)->And("laborable","=","si")->first();

           if($Es_Dia_Laborable) # cambiamos de domingo a lunes
           {
                /*
            Le indicamos que solo el sistema se abrirá de lunes a sábado , que es el día de atención
            de EsSalud -Carhuaz
            */
                if(self::profile()->rol === self::$profile[3])
                {
                    $modeMedicoEspe = new Especialidad_Medico;

                    $MedicoDataId = self::MedicoData()->id_medico;
                    $Especialidades = $modeMedicoEspe->query()->distinct()->Join("medico as m","med_esp.id_medico","=","m.id_medico")
                    ->Join("especialidad as es","med_esp.id_especialidad","=","es.id_especialidad")
                    ->select("es.id_especialidad","es.nombre_esp")
                    ->Where("med_esp.id_medico","=",$MedicoDataId)
                    ->And("es.estado","=",1)
                    ->get();
                }else
                {
                    self::$ModelEspecialidad = new Especialidad;
                    
                    $Especialidades = self::$ModelEspecialidad->query()->Where("esp.estado","=",1)->get();
                }

                self::$ModelService = new Servicio;
                self::$ModelTipoDoc = new TipoDocumento;
                $TipoDocumentos =  self::$ModelTipoDoc->query()->Where("estado", "=", "1")->get();
                self::View_("cita_medica.new", compact("Especialidades", "TipoDocumentos"));
           }
           else{
            PageExtra::PageNoAutorizado();
           }
        }
        else
        {
            PageExtra::PageNoAutorizado();
        }
    }

    /// obtener el día en letras , para consultarlo desde ajax
    public static function obtenerDia()
    {
        self::NoAuth();
        /// validamos el token
        if(self::ValidateToken(self::request("token_")))
        {
            $Dia = self::getDayDate(self::request("fecha"));

            self::json(['response'=>$Dia]);
        }
    }
    /// mostrar todos los médicos con respecto a una especialidad
    public static function mostrar_medicos_por_especialidad($especialidad = null)
    {
       self::NoAuth();
       /// validar el token
       if(self::ValidateToken(self::get("token_")))
       {
        if(self::profile()->rol === self::$profile[1] || self::profile()->rol === self::$profile[0]){
            $sede = self::profile()->sede_id;
            self::$ModelMedicEsp = new Especialidad_Medico;
            $Medicos = self::$ModelMedicEsp->query()
                ->Join("medico as m", "med_esp.id_medico", "=", "m.id_medico")
                ->Join("persona as p", "m.id_persona", "=", "p.id_persona")
                ->Where("med_esp.id_especialidad", "=", $especialidad)
                ->And("m.medicosede_id", "=",$sede)
                ->select("med_esp.id_medico_esp", "m.id_medico", "med_esp.id_especialidad", "concat(p.apellidos,' ',p.nombres) as medico")
                ->get();
        }else{
             self::$ModelMedicEsp = new Especialidad_Medico;
             $Medicos = self::$ModelMedicEsp->query()
             ->Join("medico as m","med_esp.id_medico","=","m.id_medico")
             ->Join("persona as p","m.id_persona","=","p.id_persona")
             ->Where("med_esp.id_especialidad","=",$especialidad)
             ->select("med_esp.id_medico_esp","m.id_medico","med_esp.id_especialidad","concat(p.apellidos,' ',p.nombres) as medico")
             ->get();
        }

        self::json(['medicos'=>$Medicos]);
       }  
         
    }

    /// ver los procedimiento que realiza el médico de acuerdo a la especialidad que tiene asignado
    public static function verProcedimientosMedico($id,$id_especialidad)
    {
      self::NoAuth();
      if(self::ValidateToken(self::get("token_")))
      {
        self::$ModelService = new Servicio;

        if(self::profile()->rol === self::$profile[3])
        {
        $Data = self::$ModelService->query()->Join("especialidad as e","serv.especialidad_id","=","e.id_especialidad")
        ->Join("medico_especialidades as me","me.id_especialidad","=","e.id_especialidad")
        ->Join("medico as m","me.id_medico","=","m.id_medico")
        ->Where("e.id_especialidad","=",$id_especialidad)
        ->And("deleted_at","is",null)->get();;  
        }else
        {
        $Data = self::$ModelService->query()->Where("serv.especialidad_id","=",$id)
        ->And("deleted_at","is",null)->get();
        }
        self::json(['response'=>$Data]);
      }
    }

    /// consultar la existencia de un paciente
    public static function consultarPaciente($documento)
    {
        self::NoAuth();
        // validamos el token
        if(self::ValidateToken(self::get("token_")))
        {
            self::$ModelPersona = new Persona;
            /// obtenemos el paciente
            $paciente = self::$ModelPersona->query()
            ->Join("paciente as pc","pc.id_persona","=","p.id_persona")
            ->LeftJoin("usuario as u","p.id_usuario","=","u.id_usuario")
            ->select("pc.id_paciente","p.id_persona","concat(p.apellidos,' ',p.nombres) as paciente","u.email")
            ->Where("p.documento","like",$documento)->get();

            if($paciente)
            {
                self::json(['response'=>$paciente]);
            }
            else
            {
                self::json(['response'=>'no existe']);
            }
        }
    }

    /// mostrar los horarios disponibles
    public static function horarios_disponibles_medico($medico,$dia)
    {
         self::NoAuth();

         if(self::ValidateToken(self::get("token_")))
         {
            // mostramos los horarios
            $fechaActual = self::FechaActual("Y-m-d");
            self::$Model = new Programar_Horario;
           
                $horarios = self::$Model->procedure("proc_horas_programadas_medico","c",[$medico,$dia,self::get("fecha"),self::FechaActual("H:i:s")]);
            self::json(['response'=>$horarios]);
         } 
    }

    /// consultar precio

    public static function getPrecio($id)
    {
        self::NoAuth();
        if(self::ValidateToken(self::get("token_")))
        {
            /// consultamos el precio a pagar
             self::$ModelEspecialidad = new Especialidad;
             $precio = self::$ModelEspecialidad->query()->Where("id_especialidad","=",$id)->first();
             self::json(['response'=>$precio]);
        }
    }

    /// registrar cita médica
    public static function saveCitaMedica()
    {
        self::NoAuth();
        if(self::ValidateToken(self::post("token_")))
        {
            self::$Model = new CitaMedica;
            self::$ModelHp = new Programar_Horario;

            
            /// registramos

            $Color_Texto = self::post("estado")=== 'pendiente'?'#FFFFFF':'#4169E1';
            $Color_Fondo = self::post("estado")=== 'pendiente'?'#FF4500':'#00FF7F';
            
            $Resultado = self::$Model->Insert([
                "id_cita_medica" => self::post("serie"),
                "fecha_cita"=>self::post("fecha"),
                "observacion"=>self::post("observacion"),
                "estado"=>self::post("estado"),
                "id_horario"=>self::post("id_horario"),
                "id_paciente"=>self::post("paciente"),
                "id_servicio"=>self::post("servicio"),
                "id_usuario"=>self::profile()->id_usuario,
                "color_texto"=>$Color_Texto,
                "color_fondo"=>$Color_Fondo,
                "hora_cita"=>self::post("hora_cita"),
                "monto_pago"=>is_null(self::post("monto")) ? 0.00:self::post("monto"),
                "monto_medico" => self::post("monto_medico"),
                "monto_clinica" => self::post("monto_clinica"),
                "id_medico"=>self::post("medico"),
                "id_especialidad"=>self::post("especialidad"),
                "sedecita_id" => self::profile()->sede_id != null ? self::profile()->sede_id : self::post("sede")
            ]);

            if($Resultado == 1)
            {
                            /// actualizamos el estado
            self::$ModelHp->Update([
                "id_horario"=>self::post("id_horario"),
                "estado"=>"reservado"
            ]);

            /// ENVIAMOS CORREO
            if(self::profile()->rol === 'Paciente')
            {
             $FechaSend = explode("-",self::post("fecha")); $FechaSend_ = $FechaSend[2]."/".$FechaSend[1]."/".$FechaSend[0];
            self::send_(self::post("correo"),self::post("name_"),"Cita médica registrada",
            self::ContentAgendaCitaCorreo($FechaSend_,self::post("hora_cita"),self::post("doctora"),self::post("esp"),self::post("serv")=== '--- Seleccione ---'?'No especifica el servicio...':self::post("serv")));
            }
            self::json(['response'=>'ok']);
            }
            else
            {
                self::json(['response'=>'error']);
            }
        }
    }

    /// contendido de EsSalud para cita agendada del paciente
    private static function ContentAgendaCitaCorreo($fecha,$hora,$doctora,$especialidad,$servicio)
    {
        return
        '
        Hola, estima@ paciente '.self::profile()->name.' le acabamos de enviar
        el detalle de la reserva de la cita médica que acaba de reaizar,por favor no olvide en asistir puntual a
        para su atención médica, gracias!
        <table border="2px" style="width: 960px;">
        <thead style="background: #4169E1">
          <tr>
              <th style="color: #E0FFFF;">#</th>
              <th style="color: #E0FFFF;">FECHA</th>
              <th style="color: #E0FFFF;">HORA</th>
              <th style="color: #E0FFFF;">ESPECIALISTA</th>
              <th style="color: #E0FFFF;">ESPECIALIDAD</th>
              <th style="color: #E0FFFF;">SERVICIO</th>
          </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>'.self::getDayDate($fecha).' '.self::getFechaText($fecha).'</td>
            <td>'.$hora.'</td>
            <td>DR.'.$doctora.'</td>
            <td>'.$especialidad.'</td>
            <td>'.$servicio.'</td>
        </tr>
      </tbody>
       </table>
        ';
    }

    /// ver citas programados(la vista HTML)
    public static function ver_citas_programados()
    {
        self::NoAuth();

        self::$Model = new Configuracion;

        $FechaActual  = self::FechaActual("Y-m-d");

        $DiaActual = self::getDayDate($FechaActual);

        $Es_Dia_Laborable = self::$Model->query()->Where("dias_atencion","=",$DiaActual)->And("laborable","=","si")->first();

        if($Es_Dia_Laborable)
        {
            // verificamos que solo el usuario de rol Admisión pueda realizar esta operaci´ón
        if(self::profile()->rol === self::$profile[1] || self::profile()->rol === 'Médico' || self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general')
        {
            self::View_("cita_medica.citas_programados");
        }
        else
        {
            PageExtra::PageNoAutorizado();
        }
        }
        else
        {
            PageExtra::PageNoAutorizado();
        }
    }

    /// mostrar citas programados
    public static function citas_Programados(string $opcion,$fecha='')
    {
       self::NoAuth();

       if(self::ValidateToken(self::get("token_")))
       {
        self::$Model = new Programar_Horario;

        if(self::profile()->rol === 'Médico'){
            $sede = self::profile()->sede_id;
            $Citas_Programados = self::$Model->procedure("proc_citas_programados","c",[$opcion,self::FechaActual("Y-m-d"),"Médico",self::MedicoData()->id_medico,$fecha,$sede]);
        }else{
            if(self::profile()->rol == self::$profile[1] || self::profile()->rol === self::$profile[0]){
                $sede = self::profile()->sede_id;
                $Citas_Programados = self::$Model->procedure("proc_citas_programados","c",[$opcion,self::FechaActual("Y-m-d"),"Admisión",1,$fecha,$sede]);
            }else{
                $Citas_Programados = self::$Model->procedure("proc_citas_programados","c",[$opcion,self::FechaActual("Y-m-d"),"Director",1,$fecha,null]);
            }
        }

        self::json(['response'=>$Citas_Programados]);
       }
    }
/*

*/
    /// anular cita médica
    public static function AnularCitaMedica(){

        self::NoAuth();
        if(self::ValidateToken(self::post("token_")))
        {
            self::$Model = new CitaMedica;
            self::$ModelHp = new Programar_Horario;

            /**ACTUALIZACION DEL CODIGO PARA ENVIAR CORREO A LOS PACIENTES , INDICANDO QUE ANULARON SU CITA */
            $usuario = self::$Model->query()->LeftJoin("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
            ->LeftJoin("persona as p","pc.id_persona","=","p.id_persona")
            ->LeftJoin("usuario as u","p.id_usuario","=","u.id_usuario")
            ->select("ctm.id_usuario","u.email","u.name","ctm.fecha_cita","ctm.hora_cita","concat(p.apellidos,' ',p.nombres) as personadata")
            ->where("ctm.id_cita_medica","=",self::post("cita"))
            ->first();

           

            $hora_cita = self::$ModelHp->Update([
                "id_horario"=>self::post("horario"),
                "estado"=>"disponible"
            ]);

           /// modificar el estado de la cita médica
           if($hora_cita)
           {
            $respuesta = self::$Model->Update([
                "id_cita_medica"=>self::post("cita"),
                "estado"=>"anulado",
                "color_texto"=>"#0000FF",
                "color_fondo"=>"#FF8C00",
                "id_horario" =>null
               ]);
    
               self::json(['response'=>$respuesta]);
               /// enviamos un correo al paciente(còdigo actualizar)
            if($usuario->email != null){
                 
                self::send_($usuario->email,$usuario->name,"¡AVISO IMPORTANTE!",self::ContentEmailAnulationCitaMedicaPaciente($usuario->personadata,$usuario->fecha_cita." ( ".$usuario->hora_cita." )"));
            } 
            
           }
        }
    }

    /// Enviar código de actualización
    protected static function ContentEmailAnulationCitaMedicaPaciente($paciente,$FechaHora){
        return "
          <h2 style='color=blue'>¡Anulación de la cita médica agendada!</h2>
          <br>
          <p>Señor(a) ".$paciente." su cita agendada en la fecha y hora ".$FechaHora." a sido anulada por motivos de que 
          no asistió a su cita u otros motivos, esperamos volver a verlo pronto en una próxima cita!</p>
        ";
    }

     /// Confirmar pago de la cita médica reservada
     public static function ConfirmarPagoCitaMedica(){

        self::NoAuth();
        if(self::ValidateToken(self::post("token_")))
        {
            self::$Model = new CitaMedica;

           /// modificar el estado de la cita médica
         
            $respuesta = self::$Model->Update([
                "id_cita_medica"=>self::post("cita"),
                "estado"=>"pagado",
                "color_texto"=>"#4169E1",
                "color_fondo"=>"#00FF7F"
               ]);
    
            self::json(['response'=>$respuesta]);
        }
    }

    /// mostrar las citas médicas 
    public static function citasMedicasCalendar()
    {
     self::NoAuth();
     
     if(self::ValidateToken(self::get("token_")))
     { self::$Model = new CitaMedica;
        if(self::profile()->rol === self::$profile[0])
        {
            $sede = self::profile()->sede_id;
            $data = self::$Model->procedure("proc_show_calendar_citas","C",[null,$sede]);
        }else{
            $sede = self::profile()->id_sede;
            if(self::profile()->rol === self::$profile[1]){
              $data = self::$Model->procedure("proc_show_calendar_citas","C",[null,$sede]);
            }else{
             $MedicoIdentificador = self::MedicoData()->id_medico;
             $data = self::$Model->procedure("proc_show_calendar_citas","C",[$MedicoIdentificador,$sede]);
            }
        }
        self::json($data);
     }
    }

    /// registrar a nuevos pacientes
    public static function savePaciente()
    {
        self::NoAuth();
        if(self::ValidateToken(self::post("token_")))
        {
            /// validamos los campos 
            if(empty(self::post("apell"))){self::$error [] = 'Complete los apellidos del paciente';}

            if(empty(self::post("nomb"))){self::$error [] = 'Complete los nombres del paciente';}

            if(empty(self::post("telefono"))){self::$error [] = 'Complete el # telefónico del paciente';}

            if(empty(self::post("wasap"))){self::$error [] = 'Complete su WhatsApp del paciente';}
            if(count(self::$error) > 0)
            {
                self::json(['response'=>self::$error]);
            }
            else
            {
                self::guardarPaciente();
            }
        }
    }

    /// proceso de registro de pacientes
    private static function guardarPaciente()
    {
        /// invocamos a los modelos
        self::$ModelPersona = new Persona;
        self::$Model = new Paciente;

        /// registramos a la persona

        $resultPersona = self::$ModelPersona->Insert([
            "documento"=>self::post("doc"),
            "apellidos"=>self::post("apell"),
            "nombres"=>self::post("nomb"),
            "genero"=>self::post("genero"),
            "direccion"=>self::post("direccion"),
            "id_tipo_doc"=>self::post("tipo_doc"),
            "id_distrito"=>self::post("distrito"),
            "fecha_nacimiento" => self::post("fecha_de_nacimiento_modal_paciente")
        ]);

        if($resultPersona)
        {
            $persona = self::$ModelPersona->query()->Where("documento","=",self::post("doc"))->first();

            $responsePaciente = self::$Model->Insert([
                "telefono"=>self::post("telefono"),
                "whatsapp"=>self::post("wasap"),
                "estado_civil"=>self::post("estado_civil"),
                "id_persona"=>$persona->id_persona,
                "pacientesede_id" => self::profile()->sede_id != null ? self::profile()->sede_id: self::post("sede")
            ]);

            if($responsePaciente)
            {
                self::json(['response'=>'ok']);
            }
            else
            {
                self::json(['response'=>'error']);
            }
        }
    }

    /** 
     * Mostrar pacientes que no han sido atendidos
    */

    public static function pacientes_no_atendidos()
    {
        self::NoAuth();

       if(self::ValidateToken(self::get("token_")))
       {
       
            self::$Model = new CitaMedica;

            $sede = self::profile()->sede_id;
            $Pacientes = self::$Model->procedure("proc_pacientes_pendientes_no_atendidos","C",[$sede]);

            self::json(['pacientes'=>$Pacientes]);
        
            /**
             * 
             * > addtime(trim(substr(ct.hora_cita,1,8)),'00:05:00')
             */
       }
    }

    /**
     * Actualizar la cita médica
     */
    public static function actualizarCitaMedica($id_cita,$Hora_Id_cita)
    {
        self::NoAuth();

        if(self::ValidateToken(self::post("token_")))
        {
            self::$Model = new CitaMedica; self::$ModelHp = new Programar_Horario;

            $DatosModificar = [
            "id_cita_medica"=>$id_cita,"fecha_cita"=>self::post("fecha_cita"),"observacion"=>self::post("obs"),
            "id_horario"=>self::post("horario"),"id_paciente"=>self::post("paciente"),"id_servicio"=>self::post("serv"),
            "hora_cita"=>self::post("horacita"),"monto_pago"=>self::post("monto"),"id_medico"=>self::post("medico"),
            "id_especialidad"=>self::post("esp"),"monto_medico" => self::post("importe_modal_medico"),
            "monto_clinica" => self::post("importe_modal_clinica")
            ];
            $respuesta = self::$Model->Update($DatosModificar);

            if($respuesta)
            {
                /// modificamos la tabla programación de horarios
               if(!is_null($Hora_Id_cita))
               {
                self::$ModelHp->Update([
                    "id_horario"=>$Hora_Id_cita,
                    "estado"=>"disponible",
                ]);

                self::$ModelHp->Update([
                    "id_horario"=>self::post("horario"),
                    "estado"=>"reservado",
                ]);
                

                ///modificamos el nuevo horario
                
               }

                self::json(['response'=>'ok']);
            }
            else
            {
                self::json(['response'=>'error']);
            }
        }
    }

    # paciente agenda la cita médica
    public static function agendarCitaPaciente()
    {
        
        self::NoAuth();

        if (self::profile()->rol === self::$profile[2]) {

            self::$Model = new Configuracion;

            $FechaActual  = self::FechaActual("Y-m-d");

            $DiaActual = self::getDayDate($FechaActual);

            $Es_Dia_Laborable = self::$Model->query()->Where("dias_atencion", "=", $DiaActual)->And("laborable", "=", "si")->first();

            if ($Es_Dia_Laborable) # cambiamos de domingo a lunes
            {

                self::Session("espec_id", self::get("espe_id"));
                self::Session("medic_id", self::get("medico_id"));
                self::Session("name_medico", self::get("medico"));
                self::Session("medico_esp_data",self::get("id_medesp_"));
                /*
            Le indicamos que solo el sistema se abrirá de lunes a sábado , que es el día de atención
            de EsSalud -Carhuaz
            */
                if (self::ExistSession("name_medico") and !empty(self::get("espe_id")) and !empty(self::get("medico_id"))) {
                    self::$ModelEspecialidad = new Especialidad;
                    self::$ModelService = new Servicio;
                    self::$ModelTipoDoc = new TipoDocumento;
                    $Especialidades = self::$ModelEspecialidad->query()->get();
                    $TipoDocumentos =  self::$ModelTipoDoc->query()->Where("estado", "=", "1")->get();
                    self::View_("cita_medica.new", compact("Especialidades", "TipoDocumentos"));
                } else {
                    PageExtra::PageNoAutorizado();
                }
            } else {
                PageExtra::PageNoAutorizado();
            }
        } else {
            PageExtra::PageNoAutorizado();
        }
    }

    # mostrar los pacientes para consultarlo
    public static function consultarPacienteCitaMedica()
    {
        self::NoAuth();
         if(self::ValidateToken(self::get("token_")))
         {
            if(self::get("doc")!=''){
                self::$Model = new Paciente;
                $doc = self::get("doc");
                $sede = self::profile()->sede_id;
                $Pacientes = self::$Model->query()->Join("persona as p", "pc.id_persona", "=", "p.id_persona")
                    ->LeftJoin("usuario as u", "p.id_usuario", "=", "u.id_usuario")
                    ->LeftJoin("sedes as s", "pc.pacientesede_id", "=", "s.id_sede")
                    ->select(
                        "pc.id_paciente",
                        "p.id_persona",
                        "concat(p.apellidos,' ',p.nombres) as paciente",
                        "p.documento",
                        "u.email",
                        "s.namesede"
                    )
                    ->Where("p.documento", "like", '%'.$doc.'%')
                    ->get();
            }else{
                 self::$Model = new Paciente;
                $sede = self::profile()->sede_id;
                $Pacientes = self::$Model->query()->Join("persona as p","pc.id_persona","=","p.id_persona")
                ->LeftJoin("usuario as u","p.id_usuario","=","u.id_usuario")
                ->LeftJoin("sedes as s","pc.pacientesede_id","=","s.id_sede")
                ->select("pc.id_paciente","p.id_persona","concat(p.apellidos,' ',p.nombres) as paciente","p.documento","u.email",
                "s.namesede")
                ->Where("pc.pacientesede_id","=",$sede)
                ->get();
            }
             
            self::json(['pacientes'=>$Pacientes]);
         }
    }
# Citas médicas anulados
public static function citasAnulados()
{
    self::NoAuth();
    if(self::ValidateToken(self::get("mitoken_")))
    {
        if(self::profile()->rol === self::$profile[1]):
            $modelCitas = new CitaMedica;
      
            $sede = self::profile()->sede_id;
            $Citas = $modelCitas->procedure("proc_show_citas_anulados","c",[$sede]);
      
            self::json(['citas_anulados'=>$Citas]);
          endif;
    }
}

# eliminar Citas médicas Agendas de estados anulado
public static function DeleteCitasAnulados($id)
{
    self::NoAuth();

    if(self::ValidateToken(self::post("token_")))
    {
        $modelCita = new CitaMedica;

        $response = $modelCita->delete($id);
        
        self::json(['response'=>$response?'ok':'error']);
    }
}
/**
 * Ver citas médicas registradas por cada mes de acuerdo a un año
 */
public static function CitasMedicasPorMes()
{
 self::NoAuth();
 
 $modelCitasMonth = new CitaMedica;

 $Respuesta = $modelCitasMonth->query()->Where("estado","=","finalizado")
 ->select("count(*) as cantidad","month(fecha_cita) as mes_number")
 ->GroupBy(["month(fecha_cita)","year(fecha_cita)"])->get();
 
 self::json(['response'=>$Respuesta]);
}

/**
 * Consultar precio del servicio
 */
public static function getPriceServicio(mixed $id)
{
    self::NoAuth();

    $modelServicio = new Servicio;

    $Servicio = $modelServicio->query()->where("id_servicio","=",$id)->first();

    self::json(["servicio"=>$Servicio]);
}

/**
 * Mostrar al médico por defecto al realizar una cita médica
 */
public static function showMedicoDefault($especialidad){
    self::NoAuth();

    $modelMedico = new Especialidad_Medico;

    $datosMedico = $modelMedico->query()->Join("medico as m","med_esp.id_medico","=","m.id_medico")
    ->Join("especialidad as esp","med_esp.id_especialidad","=","esp.id_especialidad")
    ->Join("persona as per","m.id_persona","=","per.id_persona")
    ->select("concat(per.apellidos,' ',per.nombres) as medicodata","med_esp.id_medico_esp","med_esp.id_medico", "esp.id_especialidad")
    ->where("esp.nombre_esp","=",$especialidad)
    ->Or("esp.id_especialidad","=",$especialidad)
    ->limit(1)
    ->first();

    self::json(["medico"=>$datosMedico]);
}
/// Generar Expediente automatico para registrar la atencion medica
public static function GenerateNumExpediente(String $tipo){

    self::NoAuth();
    $atencionexpediente = new Plan_Atencion;

    $CodigoExpediente = $atencionexpediente->procedure("proc_generate_expediente","C",[$tipo]);

    self::json(["expediente_num" => $CodigoExpediente[0]->serialexpediente]);
}

}