<?php 
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\{CitaMedica, Especialidad, Triaje};


class TriajeController extends BaseController
{
    private static $ModelPacientesTriaje,$Model;
    
    /// mostrar la vista de pacientes que pasan a triaje
    public static function index()
    {
        self::NoAuth();

        if(self::profile()->rol === self::$profile[4] or self::profile()->rol === self::$profile[3])
        {
            $esp = new Especialidad;
            if(self::profile()->rol === "Médico")
            {
                $Medico = self::MedicoData()->id_medico;
                $especialidades = $esp->query()
                ->Join("medico_especialidades as me","me.id_especialidad","=","esp.id_especialidad")
                ->Join("medico as m","me.id_medico","=","m.id_medico")
                ->select("esp.id_especialidad","esp.nombre_esp")
                ->Where("esp.estado","=",1)
                ->And("me.id_medico","=",$Medico)
                ->get();
            }else{
                $especialidades = $esp->query()->Where("estado","=",1)->get();
            }
            self::View_("paciente.triaje",compact("especialidades"));
        }
        else
        {
            PageExtra::PageNoAutorizado();
        }
    }

    /// mostrar pacientes que pasan a triaje por día
    public static function mostrarPacientesTriaje()
    {
        self::NoAuth();
        if(self::ValidateToken(self::get("token_")))
        {
            self::$ModelPacientesTriaje = new CitaMedica;
             
            /// mostramos a todos los pacientes que pasan directo a triaje
            if(self::profile()->rol === 'Médico')
            {
                $sede= self::MedicoData()->medicosede_id;
                # capturamos el id de la persona authenticado
                $Persona_Id = self::profile()->id_persona;
                $Pacientes_Triaje = self::$ModelPacientesTriaje->procedure("proc_pacientes_triaje","c",[$Persona_Id,self::FechaActual("Y-m-d"),self::FechaActual("H:i:s"),$sede]);
            }
            else
            {  
                $sede= self::profile()->sede_id;
                $Pacientes_Triaje = self::$ModelPacientesTriaje->procedure("proc_pacientes_triaje","c",[null,self::FechaActual("Y-m-d"),self::FechaActual("H:i:s"),$sede]);
            }

            self::json(['response'=>$Pacientes_Triaje]);
        }
    }

    /// registrar pacientes que sacaron una cita mèdica a triaje
    public static function save()
    {
        self::NoAuth();
        if(self::ValidateToken(self::post("token_")))
        {
            self::$Model = new Triaje;
            self::$ModelPacientesTriaje = new CitaMedica;
            $sede = self::profile()->sede_id; $usuario = self::profile()->id_usuario;
            $resultado = self::$Model->Insert([
                "id_triaje" => self::post("folio"),
                "presion_arterial"=>self::post("presion_arterial"),
                "temperatura"=>self::post("temperatura"),
                "frecuencia_cardiaca"=>self::post("frecuencia_cardiaca"),
                "frecuencia_respiratoria"=>self::post("frecuencia_resp"),
                "saturacion_oxigeno"=>self::post("saturacion_oxigeno"),
                "talla"=>self::post("talla"),
                "peso"=>self::post("peso"),
                "imc"=>self::post("imc"),
                "estado_imc"=>self::post("estado_imc"),
                "id_cita_medica"=>self::post("cita_id"),
                "sede_id" => $sede,"usuario_id" => $usuario
            ]);

            if($resultado)
            {
                self::$ModelPacientesTriaje->Update([
                    "id_cita_medica"=>self::post("cita_id"),
                    "estado"=>"examinado",
                    "id_especialidad" => self::post("especialidad"),
                    "observacion" => self::post("motivo")
                ]);
                self::json(['response'=>'ok']);
            }
            else
            {
                self::json(['response'=>'error']);
            }
        }
    }

    /// consultar triaje por cita médica
    public static function consulta_triaje($cita)
    {
        self::NoAuth();
        if(self::ValidateToken(self::get("token_")))
        {
            self::$Model = new Triaje;
            $data_ = self::$Model->query()->Where("id_cita_medica","=",$cita)->first();
            self::json(['response'=>$data_]);
        }
    }

    /// actualziar triaje de un paciente 
    public static function update($triaje_id)
    {
        self::NoAuth();

        if(self::ValidateToken(self::post("token_")))
        {
            self::$Model = new Triaje;
            $modelCita = new CitaMedica;

            $resultado = self::$Model->Update([
                "id_triaje"=>$triaje_id,
                "presion_arterial"=>self::post("presion_arterial"),
                "temperatura"=>self::post("temperatura"),
                "frecuencia_cardiaca"=>self::post("frecuencia_cardiaca"),
                "frecuencia_respiratoria"=>self::post("frecuencia_resp"),
                "saturacion_oxigeno"=>self::post("saturacion_oxigeno"),
                "talla"=>self::post("talla"),
                "peso"=>self::post("peso"),
                "imc"=>self::post("imc"),
                "estado_imc"=>self::post("estado_imc"),
            ]);

            if($resultado)
            {
                $modelCita->Update([
                    "id_cita_medica"=>self::post("cita_id"),
                    "id_especialidad" => self::post("especialidad"),
                    "observacion" => self::post("motivo")
                ]);
                self::json(['response'=>'ok']);
            }
            else
            {
                self::json(['response'=>'error']);
            }
            
        }
    }
    public static function PrintFechaText(string $fecha)
    {
      if(self::ValidateToken(self::get("token_")))
      {
        $Dia = self::getDayDate($fecha);
         if($fecha === self::FechaActual("Y-m-d"))
         {
            $fecha = explode("-",$fecha);
            $fecha = $fecha[2]."/".$fecha[1]."/".$fecha[0];
            self::json(['response'=>$Dia." , ".self::getFechaText($fecha)." - Hoy"]);
         }
         else
         {
            $fecha = explode("-",$fecha);
            $fecha = $fecha[2]."/".$fecha[1]."/".$fecha[0];
            self::json(['response'=>$Dia." , ".self::getFechaText($fecha)]);
         }
      }
    }

    /**
     * Mostrar pacientes que sacaraon la cita en una fecha - mostrar por médico y fecha
     */
    public static function PacientesTriajePersonalizado($fecha)
    {
        self::NoAuth();

        if(self::ValidateToken(self::get("token_")))
        {
            $model= new CitaMedica;
            $sede = self::profile()->sede_id;
            if(self::profile()->rol === 'Médico')
            {
                $MedicoIdData = self::MedicoData()->id_medico;
                $Pacientes = $model->query()->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
                ->Join("persona as p","pc.id_persona","=","p.id_persona")
                ->select("concat(p.apellidos,' ',p.nombres) as paciente","pc.id_paciente","ctm.hora_cita","observacion","ctm.estado",
                "ctm.fecha_cita","ctm.id_cita_medica","ctm.id_horario")
                ->Where("ctm.fecha_cita","=",$fecha)
                ->And("ctm.id_medico","=",$MedicoIdData)
                ->And("ctm.sedecita_id","=",$sede)
                ->InWhere("ctm.estado",["'pagado'","'pendiente'"])
                ->orderBy("ctm.hora_cita","asc")
                ->get();
            }else{
                $Pacientes = $model->query()->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
                ->Join("persona as p","pc.id_persona","=","p.id_persona")
                ->select("concat(p.apellidos,' ',p.nombres) as paciente","pc.id_paciente","ctm.hora_cita","observacion","ctm.estado",
                "ctm.fecha_cita","ctm.id_cita_medica","ctm.id_horario")
                ->Where("ctm.fecha_cita","=",$fecha)
                ->And("ctm.sedecita_id","=",$sede)
                ->InWhere("ctm.estado",["'pagado'","'pendiente'"])
                ->orderBy("ctm.hora_cita","asc")
                ->get();
            }

            self::json(['pacientes'=>$Pacientes]); 
        }
    }

    public static function pruebas()
    {
        $model = new CitaMedica;

        print_r(
       $model->query()->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
        ->Join("persona as p","pc.id_persona","=","p.id_persona")
        ->select("concat(p.apellidos,' ',p.nombres) as paciente","pc.id_paciente","ctm.hora_cita","observacion")
        ->Where("ctm.fecha_cita","=","2023-11-19")
        ->InWhere("ctm.estado",["'pagado'","'pendiente'"])
        ->get());
    }


}