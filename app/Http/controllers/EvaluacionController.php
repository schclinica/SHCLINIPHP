<?php
namespace Http\controllers;

use FPDF;
use Http\pageextras\PageExtra;
use lib\BaseController;
use lib\PdfEvaluacionInforme;
use models\Evaluacion_Pre_Operatoria;

class EvaluacionController extends BaseController
{
  /// aperturamos la vista de crear la evaluación pre operatoria del paciente
  public static function create()
  {
   if(self::profile()->rol === 'Médico')
   {
    self::View_("medico.evaluacion_informes");
   }
   else
   {
    PageExtra::PageNoAutorizado();
   }
  }

 
  /// método para registrar la evaluación de un paciente 
  public static function store()
  {
    /// validamos que este authenticado
    self::NoAuth();
    /// validamos que esta acción sea realizado por el médico
    if(self::profile()->rol === 'Médico')
    {
      /// validamos el token 
      if(self::ValidateToken(self::post("token_")))
      {
        $modelEvaluacion = new Evaluacion_Pre_Operatoria;

        /// validamos si ya existe una evaluación pre operatoria de un paciente con respecto a una fecha
        $ExisteEvaluacion = $modelEvaluacion->query()->Where("paciente_id","=",self::post("atencion_id"))
        ->And("fecha_evaluacion","=",self::FechaActual("Y-m-d"))->get();
        if(!$ExisteEvaluacion){
        $respuesta = $modelEvaluacion->saveEvaluation(self::post("indicaciones"),self::post("ant_importantes"),
        self::post("molestias_importantes"),self::post("pa"),self::post("fcc"),self::post("fr"),self::post("to_"),
        self::post("sato_dos"),self::post("peso"),self::post("det_ex_fisico"),self::post("resultados"),
        self::post("goldman"),self::post("asa"),self::post("sugerencias"),self::post("atencion_id"),self::FechaActual("Y-m-d"));

        self::json(["response" => $respuesta ? 'ok':'error']);
        }else
        {
          self::json(["response"=>"existe"]);
        }
      }else
      {
        self::json(["response" => "token-invalidate"]);
      }
    }else
    {
     self::json(["response" => "no-authorized"]);
    }
  }  
  
  /// mostrar los datos de la evaluación pre operatoria
  public static function mostrarEvaluacionPreOperatoria()
  {
    /// validamos que este authenticado
    self::NoAuth();
    /// validamos , para que solo el médico pueda acceder a estos datos
    if(self::profile()->rol === 'Médico')
    {
     $model = new Evaluacion_Pre_Operatoria;
     $Fecha = self::get("fecha");
     $respuesta = $model->procedure("proc_data_evaluacion_pre_operativa","c",[$Fecha]);

     self::json(["evaluacion" => $respuesta]);
    }
    else
    {
     self::json(["response" => "no-authorized"]);
    }
  }
  /// Generar el pdf de la evaluación pre operatoria
  public static function GeneratePdfEvaluationOperatoria()
  {
    /// validamos la authenticación
    self::NoAuth();

    /// validamos que se un médico, quién realice esta acción
    if(self::profile()->rol === 'Médico')
    {
      $modelEvaluation = new Evaluacion_Pre_Operatoria;
      $responseEvaluation = $modelEvaluation->query()->join("paciente as pc","epo.paciente_id","=","pc.id_paciente")
      ->join("persona as p","pc.id_persona","=","p.id_persona")
      ->Where("epo.id_evaluacion","=",self::get("v"))
      ->first();
       
      if(!$responseEvaluation){PageExtra::Page404();exit;}/// si jo existe la data, muestra una página de 404

      /// sacamos la edad del paciente
      /// si existe la fecha de nacimiento , calculamos su edad, caso contrario muetra vacio
      if($responseEvaluation->fecha_nacimiento)
      {
       $NuevaFechaNac = explode("-",$responseEvaluation->fecha_nacimiento);
       $FechaActual = explode("-",self::FechaActual("Y-m-d"));
       /// obtenemos el anio, mes y día
       $Anio = $NuevaFechaNac[0]; $Mes = $NuevaFechaNac[1]; $Dia = $NuevaFechaNac[2];
       $AnioActual = $FechaActual[0];$MesActual = $FechaActual[1]; $DiaActual = $FechaActual[2];

       if($MesActual <= $Mes)
       {
        $Edad = "Por cumplir ".($AnioActual - $Anio). " años";
       }
       else{
        $Edad = (($AnioActual -$Anio) -1)." años";
       }


      }
      else
      {
        $Edad = "____________________";
      }
      $evaluacion = new PdfEvaluacionInforme();

      /// le indicamos un título al pdf
      $evaluacion->SetTitle(utf8__("Evaluación pre-operatoria"));

      /// obtenemos los datos del médico
      $evaluacion->setDoctor(self::profile()->apellidos." ".self::profile()->nombres);
      $evaluacion->setTitleHeader("EVALUACIÓN PRE-OPERATORIA");
      /// agregamos una nueva página
      $evaluacion->AddPage();   
     
      /// datos del paciente
    
     
      $evaluacion->SetFont("Times","B",11);
      $evaluacion->SetX(15);
      $evaluacion->Cell(40,7,"Nombre del paciente",1,0,"L");

      $evaluacion->SetX(55);
      $evaluacion->SetFont("Times","",11);
      $evaluacion->Cell(75,7,utf8__($responseEvaluation->apellidos.' '.$responseEvaluation->nombres),1,0,"L");


      $evaluacion->SetFont("Times","B",11);
      $evaluacion->Cell(16,7,"Edad:",1,0,"L");

      $evaluacion->SetFont("Times","",11);
      $evaluacion->Cell(46,7,utf8__($Edad),1,1,"L");

      /// indicaciones
      $evaluacion->SetX(15);
      $evaluacion->SetFont("Times","B",12);
      $evaluacion->MultiCell(177,7,"Indicaciones",1,"L");

      $evaluacion->SetX(15);
      $evaluacion->SetFont("Times","",12);
      $evaluacion->MultiCell(177,5.5,utf8__($responseEvaluation->indicaciones != null ? $responseEvaluation->indicaciones:''),1,"L");

      /// antecedentes importantes
      $evaluacion->SetX(15);
      $evaluacion->SetFont("Times","B",12);
      $evaluacion->MultiCell(177,7,"Antecedentes importantes",1,"L");

      $evaluacion->SetX(15);
      $evaluacion->SetFont("Times","",12);
      $evaluacion->MultiCell(177,5.5,utf8__($responseEvaluation->antecedentes_importantes != null ? $responseEvaluation->antecedentes_importantes:''),1,"L");

      /// molestias importantes actuales
      $evaluacion->SetX(15);
      $evaluacion->SetFont("Times","B",12);
      $evaluacion->MultiCell(177,7,"Molestias importantes actuales",1,"L");

      $evaluacion->SetX(15);
      $evaluacion->SetFont("Times","",12);
      $evaluacion->MultiCell(177,5.5,utf8__($responseEvaluation->molestias_importantes),1,"L");

      /// exámen físico
      $evaluacion->SetX(15);
      $evaluacion->SetFont("Times","B",12);
      $evaluacion->MultiCell(177,7,utf8__("Exámen físico"),1,"L");

      $evaluacion->SetX(15);
      $evaluacion->Cell(10,7,"P/A",1,0,"L");
      $evaluacion->SetFont("Times","",12);
      $evaluacion->Cell(50,7,utf8__($responseEvaluation->pa != null ? $responseEvaluation->pa:''),1,0,"L");

      $evaluacion->setFont("Times","B",12);
      $evaluacion->Cell(16,7,"FcC",1,0,"L");
      $evaluacion->SetFont("Times","",12);
      $evaluacion->Cell(50,7,$responseEvaluation->fcc != null ? $responseEvaluation->fcc:'',1,0,"L");

      $evaluacion->setFont("Times","B",12);
      $evaluacion->Cell(10,7,"FR",1,0,"L");
      $evaluacion->SetFont("Times","",12);
      $evaluacion->Cell(41,7,$responseEvaluation->fr != null ? $responseEvaluation->fr:'',1,1,"L");

      $evaluacion->SetX(15);
      $evaluacion->SetFont("Times","B",12);
      $evaluacion->Cell(10,7,"TO",1,0,"L");
      $evaluacion->SetFont("Times","",12);
      $evaluacion->Cell(50,7,$responseEvaluation->to_ != null ? $responseEvaluation->to_:'',1,0,"L");

      $evaluacion->setFont("Times","B",12);
      $evaluacion->Cell(16,7,"SaTO2",1,0,"L");
      $evaluacion->SetFont("Times","",12);
      $evaluacion->Cell(50,7,$responseEvaluation->sato_dos != null ? $responseEvaluation->sato_dos:'',1,0,"L");

      $evaluacion->setFont("Times","B",12);
      $evaluacion->Cell(10,7,"Peso",1,0,"L");
      $evaluacion->SetFont("Times","",12);
      $evaluacion->Cell(41,7,$responseEvaluation->peso != null ? $responseEvaluation->peso:'',1,1,"L");

      $evaluacion->SetX(15);
      $evaluacion->SetFont("Times","",12);
      $evaluacion->MultiCell(177,5.5,utf8__($responseEvaluation->detalle_ex_fisico != null ? $responseEvaluation->detalle_ex_fisico:''),1,"L");

      /// resultados de estudios realizados
       /// molestias importantes actuales
       $evaluacion->SetX(15);
       $evaluacion->SetFont("Times","B",12);
       $evaluacion->MultiCell(177,7,"Resultados de estudios realizados",1,"L");
 
       $evaluacion->SetX(15);
       $evaluacion->SetFont("Times","",12);
       $evaluacion->MultiCell(177,5.5,utf8__($responseEvaluation->resultados_estudios),1,"L");

       /// riesgo quirurgico
       $evaluacion->SetX(15);
       $evaluacion->SetFont("Times","B",12);
       $evaluacion->MultiCell(177,7,utf8__("Riesgo Quirúrgico"),1,"L");

       $evaluacion->SetX(15);
       $evaluacion->Cell(24,7,"Goldman",1,0,"L");
       $evaluacion->SetFont("Times","",12);
       $evaluacion->Cell(75,7,utf8__($responseEvaluation->riesgo_quir_goldman),1,0,"L");
 
       $evaluacion->setFont("Times","B",12);
       $evaluacion->Cell(15,7,"ASA",1,0,"L");
       $evaluacion->SetFont("Times","",12);
       $evaluacion->Cell(63,7,utf8__($responseEvaluation->riesgo_quir_asa),1,1,"L");

       /// sugerencias
       $evaluacion->SetX(15);
       $evaluacion->SetFont("Times","B",12);
       $evaluacion->MultiCell(177,7,"Sugerencias",1,"L");
 
       $evaluacion->SetX(15);
       $evaluacion->SetFont("Times","",12);
       $evaluacion->MultiCell(177,5.5,utf8__($responseEvaluation->sugerencias != null ? $responseEvaluation->sugerencias:''),1,"L");
 

      
      /// salida del pdf

      $evaluacion->Output();
    
    }else{
      PageExtra::Page404();
    }
  }

  /// editar los datos de la evaluación pre operativa
  public static function editarEvaluationPreOperativa(int|null $id){

    /// validamos que este authenticado
    self::NoAuth();
    /// validamos que esta acción sea realizado por el médico
    if(self::profile()->rol === 'Médico')
    {
      $modelEvaluation = new Evaluacion_Pre_Operatoria;
      $responseEvaluation = $modelEvaluation->query()->join("paciente as pc","epo.paciente_id","=","pc.id_paciente")
      ->join("persona as p","pc.id_persona","=","p.id_persona")
      ->Where("epo.id_evaluacion","=",$id)
      ->first();
      self::json(["response" => $responseEvaluation]);
    }
    else{
      self::json(["response" =>[]]);
    }
  }

  /// modificamos los datos de la evaluación pre operativa
  public static function update(int $id)
  {
    /// validamos la authenticación
    self::NoAuth();
    /// validamos que sea el médico quién realice esta acción
    if(self::profile()->rol === 'Médico'){
      /// validamos el token
      if(self::ValidateToken(self::post("token_")))
      {
       /// actualizamos los datos de la evaluación pre operativa
       $evaluation = new Evaluacion_Pre_Operatoria;

       $respuesta = $evaluation->Update([
        "id_evaluacion" => $id,
        "indicaciones" => self::post("indicaciones"),
        "antecedentes_importantes" => self::post("ant_importantes"),
        "molestias_importantes" => self::post("molestias"),
        "pa"=>self::post("pa"),"fcc" => self::post("fcc"),"fr" => self::post("fr"),"to_" => self::post("to"),
        "sato_dos" => self::post("sato_dos"),"peso" => self::post("peso"),
        "detalle_ex_fisico" => self::post("examen_fisico"),
        "resultados_estudios" => self::post("resultados"),
        "riesgo_quir_goldman" => self::post("riesgo_goldman"),
        "riesgo_quir_asa" => self::post("riesgo_asa"),
        "sugerencias" => self::post("sugerencias"),
       ]);
       self::json(["response" => $respuesta ? 'ok':'error']);
      }else{
        self::json(["response" => "token-invalidate"]);
      }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }

}