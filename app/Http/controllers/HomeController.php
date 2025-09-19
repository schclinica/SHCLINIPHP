<?php 
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\CitaMedica;
use models\Especialidad;
use models\Medico;
use models\Paciente;
use models\Plan_Atencion;
use models\RedesSocialesClinicaMedico;
use models\Sede;
use models\Servicio;
use models\Testimonio;
use models\TipoDocumento;
use models\Usuario;

class HomeController extends BaseController
{
    private static $Model;
    /// página de inicio sin iniciar session
    public static function PageInicio()
    {
        ///mostramos las especialidades existentes
        $especialidadModel = new Especialidad;
        $redes = new RedesSocialesClinicaMedico;
        $servicioModel = new Servicio;
        $dataEspecialidades = $especialidadModel->query()->Where("estado","=",1)->get(); 
        $tipodocmodel = new TipoDocumento;
        self::$Model = new Paciente;  
        ///mostrar a los médicos existentes
        $medicomodel = new Medico;
        $PacientesExistentes = self::$Model->query()->select("count(*) as cantidad_paciente")->first();
        $MedicosExistentes = $medicomodel->query()->select("count(*) as cantidad_medico")->first();
        $CantidadDeEspecialidades = $especialidadModel->query()
        ->select("count(*) cantidad_esp")
        ->first();

        $redesSocialesClinica = $redes->query()->Join("red_social as rs","rscm.red_id","=","rs.id_red_social")
        ->Where("rscm.pertenece","=","c")
        ->get();

        $CantidadServicios = $servicioModel->query()
        ->select("count(*) as cantidad_serv")
        ->first();
        $medicos = $medicomodel->query()->Join("persona as p","me.id_persona","=","p.id_persona")
        ->Join("usuario as u","p.id_usuario","=","u.id_usuario")
        ->Join("medico_especialidades as mesp","mesp.id_medico","=","me.id_medico")
        ->Join("especialidad as e","mesp.id_especialidad","=","e.id_especialidad")
        ->select("concat(p.apellidos,' ',p.nombres) as doctor","group_concat(e.nombre_esp) as especialidadesdata","me.experiencia",
        "u.foto","me.celular_num")
        ->GroupBy(["mesp.id_medico"])
        ->get();

        /**MOSTRAMOS LAS DESES DISPONIBLES */
        $sede = new Sede;
        $sedes = $sede->query()->Where("deleted_at","is",null)->get();
        /// mostramos los testimonio
        $modeltestimonios = new Testimonio;

        $testimonios = $modeltestimonios->query()
        ->procedure("proc_ultimo_testimoniopaciente","C");
        $DatosTipoDoc = $tipodocmodel->query()->Where("estado","=","1")->get();
         self::View_("home",compact("dataEspecialidades","DatosTipoDoc","medicos","testimonios","PacientesExistentes","MedicosExistentes","CantidadDeEspecialidades","CantidadServicios","redesSocialesClinica","sedes"));
        // echo "pagina principal";
    }

    /// página del dashboard
    public static function Dashboard()
    {
        self::NoAuth(); /// si no estas autneticado, redirige a login
        $repoFarmaciaVentaDia = 0;
        $repoFarmaciaVentaMes = 0;
        $repoFarmaciaVentaAnio = 0;
        /// mostramos los pacientes atendidos de hoy

        $PacientesAtendidosHoy = self::CitaMedicaFinalizadoAnulado();
  
        /// citas médicas anulados
        $CitasMedicasAnuladosHoy = self::CitaMedicaFinalizadoAnulado("anulado");

        /// citas sin concluir
        $Citas_Sin_Concluir_Hoy = self::CitaMedicaFinalizadoAnulado("pagado");

        /// citas médicas pendientes
        $Citas_Medicas_Pendientes = self::CitaMedicaFinalizadoAnulado("pendiente");

        /// Pacientes examinados
        $Pacientes_Examinados = self::CitaMedicaFinalizadoAnulado("examinado");

        /// Pacientes atendidos por el médico authenticado
        $Pacientes_Atendidos_Medico = self::PacientesAtendidos();

        /// Pacientes por médico que fueron anulados su cita médica por no asistir

        $Pacientes_Anulados_Medico = self::PacientesAtendidos('count(*) as pacientes_atendidos','anulado');

        /// Monto recaudado por día del médico
        $MontoRecaudadoMedicoHoy = self::$Model->procedure("proc_ingresos_clinica_servicios","c",["medico_diario",self::profile()->id_usuario,self::FechaActual("Y-m-d"),null,self::profile()->sede_id]);
       /// total recaudado por médico mensual
        $MontoRecaudadoMedicoMensual = self::$Model->procedure("proc_ingresos_clinica_servicios","c",["medico_mes",self::profile()->id_usuario,self::FechaActual("Y-m-d"),null,self::profile()->sede_id]);
        $MontoRecaudadoMedicoAnio = self::$Model->procedure("proc_ingresos_clinica_servicios","c",["medico_anio",self::profile()->id_usuario,self::FechaActual("Y-m-d"),null,self::profile()->sede_id]);

         /// total de citas médicas registrados del paciente

         $TotalDeCitasDelPacientes = self::showCitasPaciente();

         /// ver las citas concluidos del paciente authenticado
         $CitasConcluidosPaciente = self::showCitasPaciente('finalizado');

         /// Ver las citas no concluidos del paciente authenticado
         $CitasNoConcluidosPaciente = self::showCitasPaciente('anulado');
        /// obtener a los usuarios activos 
        $User_Active = self::UserActiveInactive();
        
        /// obtener a los usuarios inactivos en el sistema
        $User_Inactive = self::UserActiveInactive("2");

        /// Pacientes registrados
        self::$Model = new Paciente; $ModelMedico = new Medico;

        /// reporte de ventas farmacia
       if(self::profile()->rol === self::$profile[5] || self::profile()->rol === "admin_farmacia"){
        $sede = self::profile()->sede_id;
        $repoFarmaciaVentaDia = self::$Model->procedure("proc_ventas_farmacia_reporte","c",["dia",$sede]);
        $repoFarmaciaVentaMes = self::$Model->procedure("proc_ventas_farmacia_reporte","c",["mes",$sede]);
        $repoFarmaciaVentaAnio = self::$Model->procedure("proc_ventas_farmacia_reporte","c",["anio",$sede]);
       } 
        if(self::profile()->rol === self::$profile[0]){
            $sede = self::profile()->sede_id;
            $PacientesExistentes = self::$Model->query()
            ->Where("pc.pacientesede_id","=",$sede)
            ->select("count(*) as cantidad_paciente")->first();
            $MedicosExistentes = $ModelMedico->query()
             ->Where("me.medicosede_id","=",$sede)
            ->select("count(*) as cantidad_medico")->first();
        }else{
            $PacientesExistentes = self::$Model->query()->select("count(*) as cantidad_paciente")->first();
            $MedicosExistentes = $ModelMedico->query()->select("count(*) as cantidad_medico")->first();
        }
                                  
         
        self::View_("app",compact("PacientesAtendidosHoy","CitasMedicasAnuladosHoy","PacientesExistentes","MedicosExistentes","Citas_Sin_Concluir_Hoy","User_Active","User_Inactive","Citas_Medicas_Pendientes","Pacientes_Examinados","Pacientes_Atendidos_Medico","Pacientes_Anulados_Medico","MontoRecaudadoMedicoHoy","TotalDeCitasDelPacientes","CitasConcluidosPaciente","CitasNoConcluidosPaciente","repoFarmaciaVentaDia","repoFarmaciaVentaMes","repoFarmaciaVentaAnio",
    "MontoRecaudadoMedicoMensual","MontoRecaudadoMedicoAnio")); 
    }

    public static function Welcome(){
        self::NoAuth();

        $CantidadMedicos = 0;
        $CantidadPacientes = 0;
        $CantidadUsuarios =0;
        $Pacientes_Atendidos_Medico = 0;
        $Pacientes_Anulados_Medico = 0;
        $CantidadHistorialClinicoMedico = 0;
        $PacientesEnTriajeTotal = 0;
        $PacientesExaminados = 0;
        if(self::profile()->rol === 'Director' || self::profile()->rol === 'Admisión' || self::profile()->rol === 'admin_general'
        || self::profile()->rol === self::$profile[4]){
            $usuario = new Usuario;$paciente = new Paciente;$medico = new Medico;$atencion = new Plan_Atencion; 
            $cita = new CitaMedica;
            if(self::profile()->rol === self::$profile[1] || self::profile()->rol === self::$profile[0]){
                $sede = self::profile()->sede_id; 
                $CantidadPacientes = $paciente->query()->select("count(1) as numpaciente")
                 ->Where("pc.pacientesede_id","=",$sede)
                 ->get();

                  $CantidadMedicos = $medico->query()->select("count(1) as nummedico")
                  ->Where("me.medicosede_id","=",$sede)
                ->get();
                 $CantidadUsuarios = $usuario->query()->select("count(1) as numuser")
                 ->Where("u.sede_id","=",$sede)
                ->get();
            }else{
                 $CantidadPacientes = $paciente->query()->select("count(1) as numpaciente")
                 ->get();
                  $CantidadMedicos = $medico->query()->select("count(1) as nummedico")
                 ->get();
                 $CantidadUsuarios = $usuario->query()->select("count(1) as numuser")
                 ->get();
            }

            if(self::profile()->rol === self::$profile[4]){
                $sede = self::profile()->sede_id;
                $PacientesEnTriajeTotal = $cita->query()->select("count(1) as totalentriaje")
                ->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
                ->Where("estado","=","pagado")
                ->And("ctm.sedecita_id","=",$sede)
                ->get();
                 $PacientesExaminados = $cita->query()
                 ->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
                 ->select("count(1) as totalexaminados")
                 ->Where("estado","=","examinado")
                 ->And("ctm.sedecita_id","=",$sede)
                 ->get();
            }
        

            $MedicoIdValue = self::profile()->rol === 'Médico' ? self::MedicoData()->id_medico : null;
            if (self::profile()->rol === 'Médico') {
                 $sede = self::profile()->sede_id;
                $CantidadHistorialClinicoMedico = $atencion->query()->Join("triaje as t", "plan.id_triaje", "=", "t.id_triaje")
                    ->Join("cita_medica as ct", "t.id_cita_medica", "=", "ct.id_cita_medica")
                    ->Join("medico as m", "ct.id_medico", "=", "m.id_medico")
                    ->select("count(1) as cantidadhistorias")
                    ->Where("ct.id_medico", "=", $MedicoIdValue)
                    ->And("ct.sedecita_id","=",$sede)
                    ->get();
            } else {
                if(self::profile()->rol === self::$profile[1] || self::profile()->rol=== self::$profile[4] || self::profile()->rol === self::$profile[0]){
                    $sede = self::profile()->sede_id;
                    $CantidadHistorialClinicoMedico = $atencion->query()->Join("triaje as t", "plan.id_triaje", "=", "t.id_triaje")
                    ->Join("cita_medica as ct", "t.id_cita_medica", "=", "ct.id_cita_medica")
                    ->Join("medico as m", "ct.id_medico", "=", "m.id_medico")
                    ->select("count(1) as cantidadhistorias")
                    ->Where("ct.sedecita_id","=",$sede)
                    ->get();
                }else{
                    $CantidadHistorialClinicoMedico = $atencion->query()->Join("triaje as t", "plan.id_triaje", "=", "t.id_triaje")
                    ->Join("cita_medica as ct", "t.id_cita_medica", "=", "ct.id_cita_medica")
                    ->Join("medico as m", "ct.id_medico", "=", "m.id_medico")
                    ->select("count(1) as cantidadhistorias")
                    ->get();
                }
            }
        }else{
            if (self::profile()->rol === 'Médico') {
                $atencion = new Plan_Atencion;
                /// Pacientes atendidos por el médico authenticado
                $Pacientes_Atendidos_Medico = self::PacientesAtendidos();

                /// Pacientes por médico que fueron anulados su cita médica por no asistir

                $Pacientes_Anulados_Medico = self::PacientesAtendidos('count(*) as pacientes_atendidos', 'anulado');

                $MedicoIdValue = self::profile()->rol === 'Médico' ? self::MedicoData()->id_medico : null;
                 $sede = self::profile()->sede_id;
                    $CantidadHistorialClinicoMedico = $atencion->query()->Join("triaje as t", "plan.id_triaje", "=", "t.id_triaje")
                        ->Join("cita_medica as ct", "t.id_cita_medica", "=", "ct.id_cita_medica")
                        ->Join("medico as m", "ct.id_medico", "=", "m.id_medico")
                        ->select("count(1) as cantidadhistorias")
                        ->Where("ct.id_medico", "=", $MedicoIdValue)
                        ->And("ct.sedecita_id","=",$sede)
                        ->get();
                
            }
        }
        self::View_("welcome",compact("CantidadPacientes","CantidadMedicos","CantidadUsuarios",
                    "Pacientes_Atendidos_Medico","Pacientes_Anulados_Medico","CantidadHistorialClinicoMedico","PacientesEnTriajeTotal","PacientesExaminados"));
    }

    /// realizar consulta de citas médicas anulados y finalziados por día
    private static function CitaMedicaFinalizadoAnulado($estado = 'finalizado')
    {
        self::$Model = new CitaMedica;

        if(self::profile()->rol === self::$profile[1] || self::profile()->rol === self::$profile[4] || self::profile()->rol === self::$profile[0]){
            $sede = self::profile()->sede_id;
            $reporteCitas = self::$Model->query()
               ->select("count(*) as cantidad")
               ->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
               ->Where("ctm.fecha_cita","=",self::FechaActual("Y-m-d"))
               ->And("estado","=",$estado)
               ->And("ctm.sedecita_id","=",$sede)
               ->first();
               return $reporteCitas;
        }
        return self::$Model->query()->select("count(*) as cantidad")
               ->Where("ctm.fecha_cita","=",self::FechaActual("Y-m-d"))
               ->And("estado","=",$estado)->first();
    }

    /// obtener los usuario activos e inactivos
    private static function UserActiveInactive($estado = '1')
    {
        self::$Model = new Usuario;

        if(self::profile()->rol === self::$profile[0]){
            $sede = self::profile()->sede_id;
            $data =  self::$Model->query()->select("count(*) as cantidad_user")
               ->Where("estado","=",$estado)
               ->And("u.sede_id","=",$sede)
               ->first();
               return $data;
        }
        return self::$Model->query()->select("count(*) as cantidad_user")
               ->Where("estado","=",$estado)->first();
    }

    /// Pacientes atendidos por el médico authenticado
    private static function PacientesAtendidos($select="count(*) as pacientes_atendidos",$estado = 'finalizado')
    {
        $md = new CitaMedica;

        $User = self::profile()->id_usuario ?? 12;
        $sede = self::profile()->sede_id;

        $DatoMedicoCita =  $md->query()->Join("medico as m","ctm.id_medico","=","m.id_medico")
                ->select($select)
                ->Join("persona as p","m.id_persona","=","p.id_persona")
                ->Join("usuario as us","p.id_usuario","=","us.id_usuario")
                ->Where("ctm.fecha_cita","=",self::FechaActual("Y-m-d"))
                ->And("ctm.estado","=",$estado)
                ->And("us.id_usuario","=",$User)
                ->And("ctm.sedecita_id","=",$sede)
                ->first();
        return $DatoMedicoCita;
    }

     
    /// página del dashboard
    public static function Desktop()
    {
        self::NoAuth();/// si no estas autneticado, redirige a login
         
       if(self::profile()->rol === 'Director' or self::profile()->rol === 'Admisión' or self::profile()->rol === 'Enfermera-Triaje')
       {
        self::View_("Desktop"); 
       }
       else
       {
        PageExtra::PageNoAutorizado();
       }
    }

    /// mostrar pacientes de triaje
    public static function PacientesEnTriaje()
    {
        self::NoAuth();
        if(self::ValidateToken(self::get("token_")))
        {
            self::$Model = new CitaMedica;
            $rol = self::profile()->rol;
            if(self::profile()->rol === self::$profile[1]){
                $sede = self::profile()->sede_id;
                $Pacientes_Triaje = self::$Model->procedure("proc_pacientes_triaje_escritorio","c",[self::FechaActual("Y-m-d"),self::FechaActual("H:i:s"),$sede,$rol]);
            }else{
                $Pacientes_Triaje = self::$Model->procedure("proc_pacientes_triaje_escritorio","c",[self::FechaActual("Y-m-d"),self::FechaActual("H:i:s"),null,$rol]);
            }
            self::json(['response'=>$Pacientes_Triaje]);
        }
    }

    /// pacientes que pasan a la atención médica
    public static function show_pacientes_en_atencion_medica()
    {
        self::NoAuth();

        if (self::ValidateToken(self::get("token_"))) {
            if (self::profile()->rol === self::$profile[1] || self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === self::$profile[3]) {
                self::$Model = new CitaMedica;
                $medico = self::request("medico");
                $rol = self::profile()->rol;
                if (self::profile()->rol === self::$profile[1]) {
                    $sede = self::profile()->sede_id;
                    $Paciente_Cola_Atencion_Medica = self::$Model->procedure("proc_pacientes_atencion_medica", "c", [$medico, self::FechaActual("Y-m-d"), self::FechaActual("H:i:s"), $sede, $rol]);
                } else {
                    if (self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general') {
                        $Paciente_Cola_Atencion_Medica = self::$Model->procedure("proc_pacientes_atencion_medica", "c", [$medico, self::FechaActual("Y-m-d"), self::FechaActual("H:i:s"), null, $rol]);
                    } else {
                        $sede = self::profile()->sede_id;
                        $Paciente_Cola_Atencion_Medica = self::$Model->procedure("proc_pacientes_atencion_medica", "c", [$medico, self::FechaActual("Y-m-d"), self::FechaActual("H:i:s"), $sede, null]);
                    }
                }
                self::json(['response' => $Paciente_Cola_Atencion_Medica]);
            } else {
                PageExtra::PageNoAutorizado();
            }
        }
    }

    /// ver reporte de total de citas totales, concluidad y no concluidas del paciente
    private static function showCitasPaciente(string $estado = '')
    {
        self::$Model = new CitaMedica;
        $paciente = self::profile()->id_persona ?? 12;
        if(empty($estado))
        {
            $Resultado = self::$Model->query()
                        ->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
                        ->Join("persona as p","pc.id_persona","=","p.id_persona")
                        ->select("count(*) as cantidad_citas")
                        ->Where("p.id_persona","=",$paciente)
                        ->first();
        }
        else
        {
            $Resultado = self::$Model->query()
                        ->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
                        ->Join("persona as p","pc.id_persona","=","p.id_persona")
                        ->select("count(*) as cantidad_citas")
                        ->Where("p.id_persona","=",$paciente)
                        ->And("ctm.estado","=",$estado)
                        ->first();
        }

       return $Resultado;
    }

    public static function mostrarPacientesSeguimiento()
    {
        self::NoAuth();
        if(self::profile()->rol === 'Director' || self::profile()->rol === 'Admisión' || self::profile()->rol === 'admin_general')
        {
            $modelpaciente = new CitaMedica;
            $rol = self::profile()->rol;
            if(self::profile()->rol === self::$profile[1] || self::profile()->rol === self::$profile[0]){
                 $sede = self::profile()->sede_id;
                 $respuesta = $modelpaciente->procedure("proc_pacientes_seguimiento_cita","c",[self::FechaActual("Y-m-d"),$sede,$rol]);
            }else{
                 $respuesta = $modelpaciente->procedure("proc_pacientes_seguimiento_cita","c",[self::FechaActual("Y-m-d"),null,$rol]);
            }

            self::json(["response" => $respuesta]);

        }else{
            self::json(["response" =>[]]);
        }
    }

    public static function contacto()
    {
        $Remitente = self::post("email");
        $NameRemitente = self::post("name");
        $Asunto = self::post("subject");
        $Mensaje = "";

        $Mensaje.="<br>"."Correo : ".$Remitente."<br>"."PERSONA QUIEN NOS ENVIA MENSAJE : ".$NameRemitente."<br><br>".self::post("message");
        $emailSend = self::send_Email_Envio($Remitente,$NameRemitente,$Asunto,$Mensaje);
        self::json(["response" => $emailSend]);
    }
    
}