<?php 
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use lib\PdfEvaluacionInforme;
use models\Electrocardiograma;

class ElectrocardiogramaController extends BaseController
{
 /// guardar los datos del informe de electrocardiograma
 public static function store()
 {
    /// verificamos que el usuario este authenticado
    self::NoAuth();
    /// verificamsos, que sea un médico quién realice esta acción
    if(self::profile()->rol === 'Médico')
    {
      /// verificamos el token Csrf
      if(self::ValidateToken(self::post("token_")))
      {
        $electrocardiograma = new Electrocardiograma;
        /// verificamos si existe un registro de electrocardiograma de un paciente con respecto a una fecha
        $ExisteElectrocardiograma = $electrocardiograma->query()
        ->Where("paciente_id","=",self::post("paciente_id"))
        ->And("fecha","=",self::FechaActual("Y-m-d"))->first();

        if(!$ExisteElectrocardiograma)
        {
            $respuesta = $electrocardiograma->save(
                self::post("pa"),self::post("obeso"),self::FechaActual("Y-m-d"),self::post("indicacion"),
                self::post("ekg_anterior"),self::post("medicamento_cv"),self::post("solicitante"),
                self::post("frecuencia"),self::post("ritmo"),self::post("p_data"),self::post("pr_data"),
                self::post("qrs_data"),self::post("aqrs_data"),self::post("qt_data"),self::post("hallazgos"),
                self::post("paciente_id")
            );
            self::json(["response" => $respuesta ? 'ok':'error'],200);
        }else{
            self::json(["response" => "existe"],200);
        }
      }  
    }else{
        self::json(["response" => "no-authorized"],404);
    }
 }

 /// mostramos todos los informes de electrocardiograma generado
 public static function showInformesElectrocardiogramas()
 {
    /// validamos la authenticación
    self::NoAuth();

    /// Validamos que sea el médico, quién realice esta acción
    if(self::profile()->rol === 'Médico')
    {
        $modelElectroCardiograma = new Electrocardiograma;

        $Fecha = self::get("fecha");

        $respuesta = $modelElectroCardiograma->procedure("proc_pacientes_informe_electrocardiograma","c",[$Fecha]);

        self::json(["response" => $respuesta]);
    }
    else{
        self::json(["response" => "no-authorized"]);
    }
 }

 /// mostramos en pdf los informes de electrocardiograma
 public static function informeElectroCardiograma(int $id)
 {
    /// validamos que este authenticado
    self::NoAuth();
    /// validamos, que sea el médico quién realice esta acción
    if(self::profile()->rol === 'Médico')
    {
        /// mostramos los datos del informe electrocardiograma
        $electrocardiogramaModel = new Electrocardiograma;

        $DataElectrocardiograma = $electrocardiogramaModel->query()->Join("paciente as pc","ie.paciente_id","=","pc.id_paciente")
        ->Join("persona as p","pc.id_persona","=","p.id_persona")
        ->Where("ie.id_informe_electro","=",$id)
        ->first();
       
        if($DataElectrocardiograma)
        {
            /// obtenemos la fecha en que se registró el informe de electrocardiograma
            $FechaElectrocardiograma = $DataElectrocardiograma->fecha;
            $FechaElectrocardiograma = explode("-",$FechaElectrocardiograma);

            $NuevaFechaElectro = $FechaElectrocardiograma[2]."/".$FechaElectrocardiograma[1]."/".$FechaElectrocardiograma[0];
            
            /// proceso para calcular la edad 
            if($DataElectrocardiograma->fecha_nacimiento != null)
            {
                $NuevaFechaNac = explode("-", $DataElectrocardiograma->fecha_nacimiento);
                $FechaActual = explode("-", self::FechaActual("Y-m-d"));
                /// obtenemos el anio, mes y día
                $Anio = $NuevaFechaNac[0];
                $Mes = $NuevaFechaNac[1];
                $Dia = $NuevaFechaNac[2];
                $AnioActual = $FechaActual[0];
                $MesActual = $FechaActual[1];
    
                if ($MesActual <= $Mes) {
                    $Edad = "Por cumplir " . ($AnioActual - $Anio) . " años";
                } else {
                    $Edad = (($AnioActual - $Anio) - 1) . " años";
                }
            }else{
                $Edad = "No especifica...";
            }

            $informeElectrocardiograma = new PdfEvaluacionInforme();

            $informeElectrocardiograma->setDoctor(self::profile()->apellidos." ".self::profile()->nombres);
            $informeElectrocardiograma->setTitleHeader("INFORME DE ELECTROCARDIOGRAMA");
            /// indicamos un titulo al pdf
            $informeElectrocardiograma->SetTitle("Informe-ectrocardiograma");
    
            /// agregamos una hoja
            $informeElectrocardiograma->AddPage();
    
            /// datos del paciente
            $informeElectrocardiograma->SetFont("Times","B",12);
            $informeElectrocardiograma->SetX(15);
            $informeElectrocardiograma->Cell(40,8,"Nombre del paciente",1,0,"L");
    
            $informeElectrocardiograma->setFont("Times","",12);
            $informeElectrocardiograma->Cell(73,8,utf8__($DataElectrocardiograma->apellidos." ".$DataElectrocardiograma->apellidos),1,0,"L");
            $informeElectrocardiograma->setFont("Times","B",12);
            $informeElectrocardiograma->Cell(13,8,"Edad",1,0,"L");
            $informeElectrocardiograma->setFont("Times","",12);
            $informeElectrocardiograma->Cell(50,8,utf8__($Edad),1,1,"L");
    
            $informeElectrocardiograma->SetX(15);
            $informeElectrocardiograma->setFont("Times","B",12);
            $informeElectrocardiograma->Cell(13,8,"Sexo",1,0,"L");
    
            $informeElectrocardiograma->setFont("Times","",12);
            $informeElectrocardiograma->Cell(20,8,$DataElectrocardiograma->genero == '1' ? 'Masculino':'Femenino',1,0,"L");
            $informeElectrocardiograma->setFont("Times","B",12);
            $informeElectrocardiograma->Cell(10,8,"P/A",1,0,"L");
            $informeElectrocardiograma->setFont("Times","",12);
            $informeElectrocardiograma->Cell(25,8,utf8__($DataElectrocardiograma->pa != null ? $DataElectrocardiograma->pa:''),1,0,"L");
            $informeElectrocardiograma->SetFont("Times","B",12);
            $informeElectrocardiograma->Cell(14,8,"Obeso",1,0,"L");
            $informeElectrocardiograma->setFont("Times","",12);
            $informeElectrocardiograma->Cell(38,8,utf8__($DataElectrocardiograma->obeso != null ? $DataElectrocardiograma->obeso:''),1,0,"L");
            $informeElectrocardiograma->SetFont("Times","B",12);
            $informeElectrocardiograma->Cell(13,8,"Fecha",1,0,"L");
            $informeElectrocardiograma->setFont("Times","",12);
            $informeElectrocardiograma->Cell(43,8,utf8__(self::getFechaText($NuevaFechaElectro)),1,1,"L");
    
            $informeElectrocardiograma->setX(15);
            $informeElectrocardiograma->setFont("Times","B",12);
            $informeElectrocardiograma->Cell(22,8,utf8__("Indicación"),1,0,"L");
    
            $informeElectrocardiograma->SetFont("Times","",12);
            $informeElectrocardiograma->Cell(91,8,utf8__($DataElectrocardiograma->indicacion != null ? $DataElectrocardiograma->indicacion:''),1,0,"L");
            $informeElectrocardiograma->setFont("Times","B",12);
            $informeElectrocardiograma->Cell(28,8,"EKG anterior",1,0,"L");
            $informeElectrocardiograma->setFont("Times","",12);
            $informeElectrocardiograma->Cell(35,8,utf8__($DataElectrocardiograma->ekg_anterior != null ? $DataElectrocardiograma->ekg_anterior:''),1,1,"L");
    
            $informeElectrocardiograma->SetFont("Times","B",12);
            $informeElectrocardiograma->SetX(15);
            $informeElectrocardiograma->Cell(176,8,"Medicamento CV",1,1,"L");
            $informeElectrocardiograma->setFont("Times","",12);
            $informeElectrocardiograma->SetX(15);
            $informeElectrocardiograma->MultiCell(176,5.5,utf8__($DataElectrocardiograma->medicamento_cv != null ? $DataElectrocardiograma->medicamento_cv:''),1,"L");
    
            $informeElectrocardiograma->SetFont("Times","B",12);
            $informeElectrocardiograma->SetX(15);
            $informeElectrocardiograma->Cell(36,8,"Solicitante",1,0,"L");
            $informeElectrocardiograma->setFont("Times","",12);
            $informeElectrocardiograma->Cell(140,8,utf8__($DataElectrocardiograma->solicitante != null ? $DataElectrocardiograma->solicitante:''),1,1,"L");
    
            $informeElectrocardiograma->Ln(3);
            $informeElectrocardiograma->setX(15);
            $informeElectrocardiograma->MultiCell(176,50,"",1);
    
            $informeElectrocardiograma->Ln(8);
            $informeElectrocardiograma->setX(15);
            $informeElectrocardiograma->MultiCell(176,50,"",1);
    
            $informeElectrocardiograma->Ln(4);
            $informeElectrocardiograma->setFont("Times","B",12);
            $informeElectrocardiograma->setX(15);
            $informeElectrocardiograma->Cell(24,8,"Frecuencia",1,0,'L');
            $informeElectrocardiograma->SetFont("Times","",12);
            $informeElectrocardiograma->Cell(30,8,utf8__($DataElectrocardiograma->frecuencia != null ? $DataElectrocardiograma->frecuencia:''),1,0,"L");
            
            $informeElectrocardiograma->setFont("Times","B",12);
            $informeElectrocardiograma->Cell(16,8,"Ritmo",1,0,'L');
            $informeElectrocardiograma->SetFont("Times","",12);
            $informeElectrocardiograma->Cell(30,8,utf8__($DataElectrocardiograma->ritmo != null ? $DataElectrocardiograma->ritmo:''),1,0,"L");
    
            $informeElectrocardiograma->setFont("Times","B",12);
            $informeElectrocardiograma->Cell(6,8,"P",1,0,'L');
            $informeElectrocardiograma->SetFont("Times","",12);
            $informeElectrocardiograma->Cell(30,8,utf8__($DataElectrocardiograma->p_data != null ? $DataElectrocardiograma->p_data:''),1,0,"L");
    
            $informeElectrocardiograma->setFont("Times","B",12);
            $informeElectrocardiograma->Cell(10,8,"PR",1,0,'L');
            $informeElectrocardiograma->SetFont("Times","",12);
            $informeElectrocardiograma->Cell(30,8,utf8__($DataElectrocardiograma->pr_data != null ? $DataElectrocardiograma->pr_data:''),1,1,"L");
    
            $informeElectrocardiograma->SetX(15);
            $informeElectrocardiograma->setFont("Times","B",12);
            $informeElectrocardiograma->Cell(12,8,"QRS",1,0,'L');
            $informeElectrocardiograma->SetFont("Times","",12);
            $informeElectrocardiograma->Cell(47,8,utf8__($DataElectrocardiograma->qrs_data != null ? $DataElectrocardiograma->qrs_data:''),1,0,"L");
    
            $informeElectrocardiograma->setFont("Times","B",12);
            $informeElectrocardiograma->Cell(15,8,"AQRS",1,0,'L');
            $informeElectrocardiograma->SetFont("Times","",12);
            $informeElectrocardiograma->Cell(47,8,utf8__($DataElectrocardiograma->aqrs_data != null ? $DataElectrocardiograma->aqrs_data:''),1,0,"L");
    
            $informeElectrocardiograma->setFont("Times","B",12);
            $informeElectrocardiograma->Cell(9,8,"QT",1,0,'L');
            $informeElectrocardiograma->SetFont("Times","",12);
            $informeElectrocardiograma->Cell(46,8,utf8__($DataElectrocardiograma->qt_data != null ? $DataElectrocardiograma->qt_data:''),1,1,"L");
    
            $informeElectrocardiograma->SetX(15);
            $informeElectrocardiograma->SetFont("Times","B",12);
            $informeElectrocardiograma->Cell(176,8,"Hallazgos",1,1,"L");
            $informeElectrocardiograma->SetX(15);
            $informeElectrocardiograma->setFont("Times","",12);
            $informeElectrocardiograma->MultiCell(176,5.5,utf8__($DataElectrocardiograma->hallazgos != null ? $DataElectrocardiograma->hallazgos:''),1,"L");
    
    
            /// abrimos el pdf
    
            $informeElectrocardiograma->Output();
        }else{
            PageExtra::Page404();
        }
        
    }
    else{
        PageExtra::PageNoAutorizado();
    }
 }

 /// editamos los datos
 public static function editar(int|null $id)
 {
/// validamos que este authenticado
self::NoAuth();
/// validamos que esta acción sea realizado por el médico
if(self::profile()->rol === 'Médico')
{
    $modelElectrocardiograma_data = new Electrocardiograma;
    $responseElectrocardiograma_data = $modelElectrocardiograma_data->query()->join("paciente as pc", "ie.paciente_id", "=", "pc.id_paciente")
    ->join("persona as p", "pc.id_persona", "=", "p.id_persona")
    ->Where("ie.id_informe_electro", "=", $id)
    ->first();
      self::json(["response" => $responseElectrocardiograma_data]);
    } else {
        self::json(["response" => []]);
    }
 }

 /// modificar los datos del informe de electrocardiograma
 public static function update(int|null $id)
 {
    /// validamos la authenticación
    self::NoAuth();
    /// validamos que sea el médico quién realice esta acción
    if(self::profile()->rol === 'Médico'){
      /// validamos el token
      if(self::ValidateToken(self::post("token_")))
      {
       /// actualizamos los datos de la evaluación pre operativa
       $model_electro_data = new Electrocardiograma;

       $respuesta = $model_electro_data->Update([
        "id_informe_electro" => $id,
        "pa" => self::post("pa"),
        "obeso" => self::post("obeso"),
        "indicacion"=>self::post("indicacion"),"ekg_anterior" => self::post("ekg_anterior"),"medicamento_cv" => self::post("medicamento_cv"),"solicitante" => self::post("solicitante"),
        "frecuencia" => self::post("frecuencia"),"ritmo" => self::post("ritmo"),
        "p_data" => self::post("p_data"),
        "pr_data" => self::post("pr_data"),
        "qrs_data" => self::post("qrs_data"),
        "aqrs_data" => self::post("aqrs_data"),
        "qt_data" => self::post("qt_data"),
        "hallazgos" => self::post("hallazgos"),
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