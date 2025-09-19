<?php
namespace Http\controllers;

use lib\BaseController;
use models\CitaMedica;
use models\DiagnosticoPacienteAtencion;
use models\Enfermedad;
use models\Plan_Atencion;
use models\TipoOrden;

class AtencionController extends BaseController{

    /** MOSTRAR LOS DATOS DE LA ATENCION MEDICA POR LA ID DE LA ATENCION*/
    public static  function EditarAtencionMedica(){
        $atencion = new Plan_Atencion;

        $sede = self::profile()->sede_id;
        $responseAtencion = $atencion->procedure("proc_reporte_del_paciente","C",[self::get("v"),null,'historia',$sede]);

        if(!self::ExistSession("diagnosticoeditar")){
            self::Session("diagnosticoeditar",[]);
        } 

        /// consultar la tabla de diagnosticos
        $diagnostico = new DiagnosticoPacienteAtencion;

        $DiagnosticosPaciente = $diagnostico->query()
        ->Join("enfermedades as e","dpa.enfermedad_id","=","e.id_enfermedad")
        ->Where("atencion_id","=",self::get("v"))->get();

        /// verificamos si existe el diagnostico en el historial
         
            if(count($_SESSION["diagnosticoeditar"]) > 0){
            self::Session("diagnosticoeditar",[]);
        
        
        foreach($DiagnosticosPaciente as $diagnosticoData){
            if(!array_key_exists(str_replace(" ","_",$diagnosticoData->codigo_enfermedad),$_SESSION["diagnosticoeditar"])){
              $_SESSION["diagnosticoeditar"][str_replace(" ","_",$diagnosticoData->codigo_enfermedad)]["codigo_enfermedad"] = $diagnosticoData->codigo_enfermedad;
              $_SESSION["diagnosticoeditar"][str_replace(" ","_",$diagnosticoData->codigo_enfermedad)]["enfermedad_desc"] = $diagnosticoData->enfermedad_desc;
              $_SESSION["diagnosticoeditar"][str_replace(" ","_",$diagnosticoData->codigo_enfermedad)]["tipo_desc"] = $diagnosticoData->tipodiagnostico;
              $_SESSION["diagnosticoeditar"][str_replace(" ","_",$diagnosticoData->codigo_enfermedad)]["enfermedad_id"] = $diagnosticoData->enfermedad_id;
            } 
          }

        }
        self::json(["historia"=> $responseAtencion]);
    }


    /// MODIFICAR EL TIPO DE DIAGNOSTICO
    public static function modificarTipoDiagnosticoPaciente($id){
        
        if(self::ValidateToken(self::post("token_")))
        {

            $enfermedad = new Enfermedad;

            $DataEnfermedad = $enfermedad->query()->Where("id_enfermedad","=",$id)->get();

            if(isset($_SESSION["diagnosticoeditar"][str_replace(" ","_",$DataEnfermedad[0]->codigo_enfermedad)])){
                $_SESSION["diagnosticoeditar"][str_replace(" ","_",$DataEnfermedad[0]->codigo_enfermedad)]["tipo_desc"] = self::post("new_tipo");
                self::json(["response" => "ok"]);
            }else{
                self::json(["error" => "NO EXISTE LA SESION"]);
            }

        }else{
            self::json(["error" => "TOKEN INVALID!!"]);
        }
    }

    /// QUITAR DIAGNOSTICO DE LA LISTA DE DIAGNOSTICOS DEL PACIENTE
    public static function QuitarDiagnosticoDeLaLista($id){
        if(self::ValidateToken(self::post("token_")))
        {
            $enfermedad = new Enfermedad;

            $Data = $enfermedad->query()->Where("id_enfermedad","=",$id)->get();

            if(isset($_SESSION["diagnosticoeditar"][str_replace(" ","_",$Data[0]->codigo_enfermedad)])){
                
                unset($_SESSION["diagnosticoeditar"][str_replace(" ","_",$Data[0]->codigo_enfermedad)]);

                self::json(["response" => "DIAGNOSTICO QUITADO DE LA LISTA CORRECTAMENTE!!!"]);
            }else{
                self::json(["error" => "NO EXISTE LA SESION"]);
            }
        }else{
            self::json(["error" => "ERROR, EL TOKEN ES INCORRECTO!!!"]);
        }
    }
    //// MOSTRAR LOS DIAGNOSTICOS DE LA EDICION

    public static function showDiagnosticosEdition(){
        if(self::ExistSession("diagnosticoeditar")){
            self::json(["diagnosticosedicion" => self::getSession("diagnosticoeditar")]);
        }
    }

    /** MODIFICAR LA ATENCION MEDICA */
    public static function ModificarAtencionMedica($id){
        if(self::ValidateToken(self::post("token_"))){
            $plantAtencion = new Plan_Atencion;

            $response = $plantAtencion->Update([
                "id_atencion_medica" => $id,"antecedentes" => self::post("antecedentes"),
                "tiempo_enfermedad" => self::post("tiempo_enfermedad"),"alergias" => self::post("alergias"),
                "otros" => self::post("otros"),"vacunas_completos" => self::post("vacunas_completos"),
                "procedimiento" => self::post("procedimiento"),"resultado_examen_fisico"=>self::post("resultado_examen_fisico"),
                "diagnostico_general" => self::post("diagnostico_general"),"desc_plan" => self::post("desc_plan"),
                "tiempo_tratamiento" => self::post("tiempo_tratamiento"),"fecha_atencion" => self::post("fecha_atencion"),
                "proxima_cita" => self::post("proxima_cita"),"diabetes" => self::post("diabetes"),"hipertencion_arterial"=>self::post("hipertencion_arterial"),
                "transfusiones" => self::post("transfusiones"),"cirujias_previas" => self::post("cirujias_previas"),
                "medicamento_actuales" => self::post("medicamento_actuales"),"atecendentes_fam" => self::post("atecendentes_fam"),
                "gestante" => self::post("gestante"),"edad_gestacional" =>(self::post("gestante") === 'si' ?  self::post("edad_gestacional") : null),"fecha_parto" => (self::post("gestante") == "si" ? self::post("fecha_parto") : null)
            ]);

            if($response){
                $CitaId = $plantAtencion->query()->Join("triaje as t","plan.id_triaje","=","t.id_triaje")->select("t.id_cita_medica")
                ->Where("plan.id_atencion_medica","=",$id)
                ->get();

                $cita = new CitaMedica;

                $cita->Update([
                    "id_cita_medica" => $CitaId[0]->id_cita_medica,
                    "observacion" => self::post("motivo_consulta_editar"),
                    "monto_pago" => self::post("monto_pago"),
                    "monto_medico" => self::post("monto_medico"),
                    "monto_clinica" => self::post("monto_clinica")
                ]);

                /// ELIMINAR TODO LOS DIAGNOSTICOS QUE LE HEMOS ASIGNADO AL PACIENTE
                $diagnosticoPacienteDelete = new DiagnosticoPacienteAtencion;
                $diagnosticoPacienteDelete->procedure("proc_eliminar_diagnosticos_paciente","d",[$id]);
                $diagnosticoPaciente = new DiagnosticoPacienteAtencion;
                if(count($_SESSION["diagnosticoeditar"]) > 0){
                    foreach($_SESSION["diagnosticoeditar"] as $diagnosticoDato){

                        $responseAsignedDiagnostico = $diagnosticoPaciente->Insert([
                            "atencion_id" => $id,
                            "enfermedad_id" => $diagnosticoDato["enfermedad_id"],
                            "enfermedad_desc" => $diagnosticoDato["enfermedad_desc"],
                            "tipodiagnostico" => $diagnosticoDato["tipo_desc"]
                        ]);
                    }
                }
                
                self::json(["response" => "LA ATENCIÓN MÉDICA A SIDO MODIFICADO CORRECTAMENTE!!"]);
              
            }else{
                self::json(["error" => "ERROR AL MODIFICAR LA ATENCIÓN MÉDICA"]);
            }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }

    /// AGREGAR NUEVOS DIAGNOSTICOS A LA LISTA, EN CASO NO EXISTA
    public static function AgregarNuevosDiagnosticosALista(){

      if(self::ValidateToken(self::post("token_"))){
        if(!array_key_exists(str_replace(" ","_",self::post("code")),$_SESSION["diagnosticoeditar"]))
        {
            $enfermedad = new Enfermedad;

            $diagnosticoData = $enfermedad->query()->Where("codigo_enfermedad","=",self::post("code"))->get();
            $_SESSION["diagnosticoeditar"][str_replace(" ","_",$diagnosticoData[0]->codigo_enfermedad)]["codigo_enfermedad"] = $diagnosticoData[0]->codigo_enfermedad;
            $_SESSION["diagnosticoeditar"][str_replace(" ","_",$diagnosticoData[0]->codigo_enfermedad)]["enfermedad_desc"] = $diagnosticoData[0]->enfermedad;
            $_SESSION["diagnosticoeditar"][str_replace(" ","_",$diagnosticoData[0]->codigo_enfermedad)]["tipo_desc"] = "r";
            $_SESSION["diagnosticoeditar"][str_replace(" ","_",$diagnosticoData[0]->codigo_enfermedad)]["enfermedad_id"] = $diagnosticoData[0]->id_enfermedad;

            self::json(["response" => "LA ENFERMEDAD A SIDO AGREGADO CORRECTAMENTE COMO DIAGNOSTICO AL PACIENTE!!!"]);
        }else{
         self::json(["error" => "LA ENFERMEDAD QUE DESEAS AGREGAR COMO DIAGNOSTICO AL PACIENTE , YA EXISTE!!!"]);
        }
      }else{
        self::json(["error" => "ERROR, EL TOKEN ES INCORRECTO!!"]);
      }
    }


    /// MOSTRAR LOS TIPOS DE DIAGNOSTICO
    public static function showTipoDiagnosticos(){

        $diagnostico = new TipoOrden;

        $diagnosticos = $diagnostico->query()->Where("deleted_at","is",null)->get();

        self::json(["diagnosticos" => $diagnosticos]);
    }
}