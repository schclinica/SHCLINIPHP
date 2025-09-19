<?php 
namespace Http\controllers;
use FPDF;
use Http\pageextras\PageExtra;
use lib\BaseController;
use lib\Pdf;
use lib\PlantillaRecibo;
use lib\PlantillaTicket;
use lib\QrCode as LibQrCode;
use models\CitaMedica;
use models\Detalle_Recibo;
use models\Empresa;
use models\InformeMedico;
use models\Medico;
use models\Recibo;
use QRcode;

class InformeMedicoController extends BaseController

{
    use LibQrCode;
    private static $Model;
    # método para guardar el informe médico
    public static function save($Id_atencion_Medico)
    {
        self::NoAuth();

        if(self::ValidateToken(self::post("token_")))
        {
            self::$Model = new InformeMedico;

            $respuesta = self::$Model->Insert([
                "descripcion_medica"=>self::post("detalle_informe"),
                "id_atencion_medica"=>$Id_atencion_Medico
            ]);

            self::json(['response'=>$respuesta?'ok':'error']);
        }

    }

    /// actualizar el informe
    public static function update($id)
    {
        self::NoAuth();

        if(self::ValidateToken(self::post("token_")))
        {
            self::$Model = new InformeMedico;

            $respuesta = self::$Model->Update([
                "id_informe"=>$id,
                "descripcion_medica"=>self::post("detalle_informe"),
            ]);

            self::json(['response'=>$respuesta?'ok':'error']);
        }

    }

    /// verificamos si esa atención médica del paciente ya a sido registrado su informe
    public static function verificarInforme($id)
    {
        self::NoAuth();
        if(self::ValidateToken(self::get("token_")))
        {
            self::$Model = new InformeMedico;

            $Respuesta = self::$Model->query()->Where("id_atencion_medica","=",$id)->get();

            self::json(['response'=>$Respuesta]);
        }
    }

    /// vista para consultar el informe médico del paciente 
    public static function viewInformeMedico()
    {
        self::NoAuth();

        if(self::profile()->rol === self::$profile[2])
        {
           self::View_("paciente.consultar_informe_medico");
        }
        else
        {
            PageExtra::PageNoAutorizado();
        }
    }

    /// ver las atenciones médicas con la fecha 
    public static function showInformeMedicoPaciente()
    {
        self::NoAuth();

        if(self::profile()->rol === self::$profile[2])
        {
           if(self::ValidateToken(self::get("token_")))
           {
            $JsonData = '';

                
            self::$Model = new InformeMedico;
            $medico = new Medico;
            $Persona = self::profile()->id_persona;
            /// consultamos
            $Informe = self::$Model->query()
            ->Join("atencion_medica as atm","im.id_atencion_medica","=","atm.id_atencion_medica")
            ->Join("triaje as tr","atm.id_triaje","=","tr.id_triaje")
            ->Join("cita_medica as ctm","tr.id_cita_medica","=","ctm.id_cita_medica")
            ->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
            ->Join("persona as p","pc.id_persona","=","p.id_persona")
            ->select("im.id_informe","date_format(ctm.fecha_cita,'%d / %m / %Y') as fecha","ctm.id_medico")
            ->Where("p.id_persona","=",$Persona)
            ->get();

           foreach($Informe as $inform):

                    $JsonData .= '{
                "id_informe":"' . $inform->id_informe . '",
                "fecha":"' . $inform->fecha . '",
                "medico":';

                    $dataMedico = $medico->query()->Join("persona as p", "me.id_persona", "=", "p.id_persona")
                    ->select("concat(p.apellidos,' ',p.nombres) as medico")
                    ->Where("me.id_medico", "=", $inform->id_medico)
                        ->get();

                    foreach ($dataMedico as $dta) :
                        $JsonData .= '"' . $dta->medico . '"';
                    endforeach;

                    $JsonData .= '},';

                endforeach;

                # eliminamos la última coma
                $JsonData = rtrim($JsonData, ",");


                $JsonData = '{"response":[' . $JsonData . ']}';

                echo $JsonData;

           
           }
        }
        else
        {
            PageExtra::PageNoAutorizado();
        }
    }

    # generar informe médico del paciente pdf
    public static function GenerateInformeMedicoPdf()
    {
        self::NoAuth();

        if(self::profile()->rol === self::$profile[2])
        {
           if(isset($_GET['id']))
           {
            self::PdfInformeMedico();
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

    /// generamos el pdf del informe médico
    private static function PdfInformeMedico()
    {
        self::$Model = new InformeMedico;
        $persona = self::profile()->id_persona;
        /// consultamos
        $Informe = self::$Model->query()
        ->Join("atencion_medica as atm","im.id_atencion_medica","=","atm.id_atencion_medica")
        ->Join("triaje as tr","atm.id_triaje","=","tr.id_triaje")
        ->Join("cita_medica as ctm","tr.id_cita_medica","=","ctm.id_cita_medica")
        ->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
        ->Join("persona as p","pc.id_persona","=","p.id_persona")
        ->Where("im.id_informe","=",self::get("id"))
        ->And("p.id_persona","=",$persona)
        ->first();

        $DataMedico = new CitaMedica;

        $Data_  = $DataMedico->query()->Join("medico as med","ctm.id_medico","=","med.id_medico")
                  ->Join("persona as p","med.id_persona","=","p.id_persona")
                  ->Where("ctm.id_cita_medica","=",$Informe->id_cita_medica)->first();

        if($Informe)
        {

          $FechaAtencion = $Informe->fecha_cita; $FechaAtencion = explode("-",$FechaAtencion);

          $FechaAtencion = $FechaAtencion[2]."/".$FechaAtencion[1]."/".$FechaAtencion[0];

          $FechaAtencion = self::getFechaText($FechaAtencion);
 
          $HoraCita = $Informe->hora_cita;  
          
           // capturamos la fecha de nacimiento del paciente
           $FechaNacimiento = $Informe->fecha_nacimiento; $FechaActual = self::FechaActual("Y-m-d");

           if($FechaNacimiento != null)
           {
            $FechaNacimiento = explode("-",$FechaNacimiento); $FechaActual = explode("-",$FechaActual);
            /// Calculamos la edad del paciente
            $Edad = ($FechaActual[1] >= $FechaNacimiento[1] and $FechaActual[2] >= $FechaNacimiento[2])
                    ? intval($FechaActual[0])-intval($FechaNacimiento[0])." años"
                    :intval($FechaActual[0])-intval($FechaNacimiento[0]-1)." años";
           }
           else
           {
            $FechaNacimiento ="No especifica";
            $Edad = "Sin calcular (Fecha nacimiento no especificado)";
           }

          $Text_Diagnostico_ = "";
          $Text_Diagnostico = explode(" ",$Informe->diagnostico?? ''); $contador = 0;
          $Text_Tratamiento_ = "";
          $Text_Tratamiento = explode(" ",$Informe->desc_plan??'');$contador_tr = 0;
           
          foreach($Text_Diagnostico as $data)
          {
            $contador++;
            $Text_Diagnostico_.="   -   ".$data;
          }

          $Text_Diagnostico_ = ltrim($Text_Diagnostico_,"   -   ");

          foreach($Text_Tratamiento as $data_)
          {
            $contador_tr++;
            $Text_Tratamiento_.="   -   ".$data_;
          }
          $Text_Tratamiento_ = ltrim($Text_Tratamiento_,"   -   ");
          
          self::GeneratePdf($Informe->apellidos." ".$Informe->nombres,$Informe->descripcion_medica,$Text_Diagnostico_,$Text_Tratamiento_,$Informe->desc_analisis_requerida,$Informe->resultado_examen_fisico,$Informe->documento,$Edad,$FechaAtencion,$HoraCita,$Data_->apellidos." ".$Data_->nombres);
        }
        else
        {
            PageExtra::PageNoAutorizado();
        }

    }

    private static function GeneratePdf($paciente,$intro_informe,$diagnosticos,$tratamiento,$analisis,$examen_clinico,$documento,$Edad_,$FechaAtencion,$HoraAtencion,$medico='')
    {
        $pdf_informe = new Pdf();
        $pdf_informe->SetTitle(utf8__("informe-médico - ".$paciente));
        $pdf_informe->AddPage();
        $pdf_informe->SetX(6);
        $pdf_informe->SetY(48);
        $pdf_informe->SetFont("Arial","B",16);
        $pdf_informe->Cell(198,3,utf8__("INFORME MEDICO"),0,1,"C");
        $pdf_informe->SetX(99);
        $pdf_informe->Cell(19,1,"___________________",0,1,"C");
        $pdf_informe->SetY(60);
        $pdf_informe->SetX(24);
        $pdf_informe->SetFont("Arial","B",12);
        $pdf_informe->Cell(24,8,"PACIENTE: ",0,0,"L");
        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->Write(8,strtoupper(utf8__($paciente)));

        $pdf_informe->Ln();
        $pdf_informe->SetX(24);
        $pdf_informe->SetFont("Arial","B",12);
        $pdf_informe->Cell(10,8,"DNI: ",0,0,"L");
        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->Write(8,$documento);

        $pdf_informe->Ln();
        $pdf_informe->SetX(24);
        $pdf_informe->SetFont("Arial","B",12);
        $pdf_informe->Cell(15,8,"EDAD: ",0,0,"L");
        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->Write(8,utf8__($Edad_));

        $pdf_informe->Ln();
        $pdf_informe->SetX(24);
        $pdf_informe->SetFont("Arial","B",12);
        $pdf_informe->Cell(18,8,utf8__("FECHA DE ATENCIÓN: "),0,0,"L");
        $pdf_informe->SetX(72);
        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->Write(8,$FechaAtencion);

        $pdf_informe->Ln();
        $pdf_informe->SetX(24);
        $pdf_informe->SetFont("Arial","B",12);
        $pdf_informe->Cell(18,8,utf8__("HORA DE ATENCIÓN: "),0,0,"L");

        $pdf_informe->SetX(69);
        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->Write(8,$HoraAtencion);

        $pdf_informe->SetY(104);
        $pdf_informe->SetX(24);
        $pdf_informe->Cell(198,0,"-------------------------------------------------------------------------------------------------------------------",0,1,"L");

        $pdf_informe->SetY(108);
        $pdf_informe->SetX(24);
        $pdf_informe->MultiCell(164,6,utf8__($intro_informe));

        /// Exámen clinico
        $pdf_informe->SetFont("Arial","B",12);
        $pdf_informe->SetX(24);
        $pdf_informe->Cell(50,10,"- EXAMEN CLINICO: ",0,1);

        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->SetX(44);

        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->SetX(36);
        
        $pdf_informe->MultiCell(144,6,utf8__($examen_clinico));
        /// diagnosticos
        $pdf_informe->SetFont("Arial","B",12);
        $pdf_informe->SetX(24);
        $pdf_informe->Cell(50,10,"- DIAGNOSTICO: ",0,1);

        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->SetX(36);
        
        $pdf_informe->MultiCell(144,6,utf8__($diagnosticos));

        /// tratamiento a realizar
        $pdf_informe->SetFont("Arial","B",12);
        $pdf_informe->SetX(24);
        $pdf_informe->Cell(164,10,"- TRATAMIENTO: ",0,1);

        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->SetX(36);
        
        $pdf_informe->MultiCell(144,7,utf8__($tratamiento));

        /// anaálisis del paciente
        $pdf_informe->SetFont("Arial","B",12);
        $pdf_informe->SetX(24);
        $pdf_informe->Cell(50,10,"- ANALISIS: ",0,1);

        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->SetX(36);

        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->SetX(36);
        
        $pdf_informe->MultiCell(144,7, $analisis != null ? utf8__($analisis):utf8__('El paciente no requiere análisis por el momento') );

        /// sugerencia para el paciente
        $pdf_informe->SetFont("Arial","B",12);
        $pdf_informe->SetX(24);
        $pdf_informe->Cell(50,10,"- SUGERENCIA: ",0,1);

        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->SetX(36);

        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->SetX(36);
        
        $pdf_informe->MultiCell(152,6,utf8__("Señor(a) paciente , debe de continuar con su control y tratamientos , hasta que note su mejoría, gracias!!!"));

        $pdf_informe->SetX(24);
        $pdf_informe->Cell(164,10,utf8__("Es todo cuanto informó para los fines que se estime pertinente."),0,1,"C");

        $pdf_informe->SetX(115);
        $pdf_informe->SetFont("Arial","B",12);
        $pdf_informe->Write(8,"Carhuaz, ".self::getFechaText(self::FechaActual("d/m/Y")));

        $pdf_informe->Ln();

        $pdf_informe->Cell(190,10,"Atentamente.",0,1,"C");

        $pdf_informe->SetFont("Arial","",12);
        $pdf_informe->Cell(190,10,utf8__("Méd. ".$medico),0,1,"C");

        $pdf_informe->Output();
    }
    
public static function prueba()
{
    self::NoAuth();
    if(isset($_GET['v']) and (self::profile()->rol === self::$profile[3] || self::profile()->rol === self::$profile[1]))
     {
        // capturamos el id de recibo
        
        $ReciboId = self::get("v");
      

        $modelrecibo = new Recibo;

        if(self::profile()->rol === self::$profile[3]){
            $sede = self::profile()->sede_id;
            $medico = self::MedicoData()->id_medico;
            $dataRecibo = $modelrecibo->query()
            ->Join("cita_medica as cm","re.cita_id","=","cm.id_cita_medica")
            ->Join("paciente as pc","cm.id_paciente","=","pc.id_paciente")
            ->Join("persona as p","pc.id_persona","=","p.id_persona")
            ->Where("id_recibo","=",$ReciboId)
            ->And("cm.id_medico","=",$medico)
            ->And("pc.pacientesede_id","=",$sede)
            ->Or("re.numero_recibo","=",trim($ReciboId))
            ->get();
        }else{
            $sede = self::profile()->sede_id;
            $dataRecibo = $modelrecibo->query()
            ->Join("cita_medica as cm","re.cita_id","=","cm.id_cita_medica")
            ->Join("paciente as pc","cm.id_paciente","=","pc.id_paciente")
            ->Join("persona as p","pc.id_persona","=","p.id_persona")
            ->Where("id_recibo","=",$ReciboId)
            ->And("pc.pacientesede_id","=",$sede)
            ->Or("re.numero_recibo","=",trim($ReciboId))
            ->get();
        }

        if($dataRecibo)
        {
            $pdf = new PlantillaRecibo("P","mm",[73,258]);
            $pdf->setRecibo($dataRecibo);
            $pdf->SetTitle("ticket");
        
            /// consultamos el logo de la empresa
           
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(true,60);
         
            /// body
            $modelDetalle = new Detalle_Recibo;$Total = 0.00;$SubTotal = 0.00;$Igv_ = 0.00;
            $valorIva_ = count(self::BusinesData()) == 1 ? self::BusinesData()[0]->iva_valor:18;
            $DetalleRespuesta = $modelDetalle->query()
            ->Join("recibo as rc","dr.recibo_id","=","rc.id_recibo")
            ->Where("dr.recibo_id","=",self::get("v"))
            ->Or("rc.numero_Recibo","=",$ReciboId)
            ->get();
             
            $pdf->SetFont("Courier","",7);
         
            foreach($DetalleRespuesta as $respuesta):
             $Total+= $respuesta->importe;
             $SubTotal = $Total / (1+($valorIva_/100));
             $Igv_ = $Total - $SubTotal;
        
            $pdf->MultiCell(60,3,utf8__($respuesta->servicio),0,"C");
            $pdf->Ln(3);
            $pdf->SetX(5);
            $pdf->Write(2.8,$respuesta->cantidad);
        
             
            $pdf->SetX(34);
            $pdf->Cell(12,0,$respuesta->precio,0,1,"L");
         
            $pdf->SetX(54);
            $pdf->Cell(12,0,$respuesta->importe,0,1,"L");
            
            $pdf->Ln(3);
            $pdf->SetX(3);
            $pdf->Cell(67,2,"-------------------------------------------",0,0,"C");
            $pdf->Ln(3);
         
            endforeach;
            
            $pdf->setTotal($Total);$pdf->setIgv($Igv_);$pdf->setSubTotal($SubTotal);
            $pdf->SetFont("Courier","B",7);
  
            $pdf->SetX(4);
            $pdf->Cell(20,3,"TOTAL A PAGAR ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.'),0,0,"L");
            $pdf->SetFont("Courier","",7);
            $pdf->SetFont("Courier","",8);
            $pdf->SetX(54);
            $pdf->Cell(20,3,number_format($Total,2,","," "),0,1,"L");
         
        
            $pdf->Ln();
            $pdf->SetFont("Courier","B",8);
            $pdf->SetX(4);
            $pdf->Cell(20,3,"SUB TOTAL ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.'),0,0,"L");
        
            $pdf->SetFont("Courier","",8);
            $pdf->SetX(54);
            $pdf->Cell(20,3,number_format($SubTotal,2,","," "),0,1,"L");
        
            $pdf->Ln();
            $pdf->SetFont("Courier","B",8);
            $pdf->SetX(4);
            $pdf->Cell(20,3,"IGV ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda." => ".self::BusinesData()[0]->iva_valor."%" :'S/. => 18%'),0,0,"L");
        
            $pdf->SetFont("Courier","",8);
            $pdf->SetX(54);
            $pdf->Cell(20,3,number_format($Igv_,2,","," "),0,1,"L");
        
            $pdf->Ln(3);
            $pdf->SetX(0);
            $pdf->Cell(74,3,"---------------------------------------",0,1,"C");
  
        
            $pdf->Output();   
        
            unlink(self::$DirectorioQr);
        }else
        {
           PageExtra::PageNoAutorizado(); 
        }
    }else
    {
        PageExtra::PageNoAutorizado(); 
    }
}
}
#self::getFechaText(self::FechaActual("d/m/Y"))