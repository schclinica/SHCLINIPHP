<?php 
namespace Http\controllers;

use FPDF;
use Http\pageextras\PageExtra;
use lib\BaseController;
use lib\HeaderOrder;
use models\Especialidad_Medico;
use models\Orden;

class ReportesController extends BaseController{


   public static function GenerateOrdenLaboratorioPacienteordeid(string|null $id){

     /// validamos que este authenticado
     self::NoAuth();
     /// valdiamos para que sea el médico , quién realice esta acción
     if(self::profile()->rol === 'Médico' )
     {
     
      
      $ordenLaboratorio = new HeaderOrder("L","mm","A5");
      $modelEspecialidadesMedico = new Especialidad_Medico;
      $MedicoId = self::MedicoData()->id_medico;
      $dataEspecialidades = $modelEspecialidadesMedico->query()->Join("medico as m", "med_esp.id_medico", "=", "m.id_medico")
        ->Join("especialidad as e", "med_esp.id_especialidad", "=", "e.id_especialidad")
        ->select("nombre_esp")
        ->Where("med_esp.id_medico", "=", $MedicoId)
        ->get();

      $modelData = new Orden;
       $sede = self::profile()->sede_id;
       $data = $modelData->procedure("proc_reporte_del_paciente","C",[null,$id,'orden_medico',$sede]);

       if(!$data){self::RedirectTo("nueva_atencion_medica");exit;}

       $ordenLaboratorio->setTitle("Orden-Medico-".$data[0]->serieorden);
       $ordenLaboratorio->setFechaActual(self::FechaActual("Y-m-d"));
       $ordenLaboratorio->setFechaHora($data[0]->fecha_registro_orden);
       $ordenLaboratorio->setDatosOrde($data);
       $ordenLaboratorio->setEspecialidadesMedico($dataEspecialidades);

       $ordenLaboratorio->addPage();

       $ordenLaboratorio->SetAutoPageBreak(true,31);
       $ordenLaboratorio->Image("public/asset/empresa/".self::BusinesData()[0]->img_fondo,20,65,175,37);
       
       foreach($data as $dato){
        $ordenLaboratorio->Ln(4);
        $ordenLaboratorio->SetX(9);
        $ordenLaboratorio->SetFont("Helvetica","",8.5);
        $ordenLaboratorio->Cell(5,1,$dato->code_orden,0,0);
 
        $ordenLaboratorio->SetX(32);
        $ordenLaboratorio->MultiCell(83,3,utf8__($dato->descripcion_orden),0,0);
 
        $ordenLaboratorio->SetX(125);
        $ordenLaboratorio->Cell(5,1,strtoupper(utf8__($dato->categoria_orden)),0,0);
 
        $ordenLaboratorio->SetX(193);
        $ordenLaboratorio->Cell(5,1,utf8__($dato->cantidad),0,1);

       }
       $ordenLaboratorio->Output();
     }else
     {
      PageExtra::PageNoAutorizado();
     }

   }
    /** Generar la órden del laboratorio para el paciente en pdf */
   public static function GenerateOrdenLaboratorioPaciente(string|null $id)
   {
     /// validamos que este authenticado
     self::NoAuth();
     /// valdiamos para que sea el médico , quién realice esta acción
     if(self::profile()->rol === 'Médico' )
     {
     
      
      $ordenLaboratorio = new HeaderOrder("L","mm","A5");
      $modelEspecialidadesMedico = new Especialidad_Medico;
      $MedicoId = self::MedicoData()->id_medico;
      $dataEspecialidades = $modelEspecialidadesMedico->query()->Join("medico as m", "med_esp.id_medico", "=", "m.id_medico")
        ->Join("especialidad as e", "med_esp.id_especialidad", "=", "e.id_especialidad")
        ->select("nombre_esp")
        ->Where("med_esp.id_medico", "=", $MedicoId)
        ->get();

      $modelData = new Orden;
       $sede = self::profile()->sede_id;
       $data = $modelData->procedure("proc_reporte_del_paciente","C",[$id,null,'orden_medico',$sede]);

       if(!$data){self::RedirectTo("nueva_atencion_medica");exit;}

       $ordenLaboratorio->setTitle("Orden-Medico-".$data[0]->serieorden);
       $ordenLaboratorio->setFechaActual(self::FechaActual("Y-m-d"));
       $ordenLaboratorio->setFechaHora($data[0]->fecha_registro_orden);
       $ordenLaboratorio->setDatosOrde($data);
       $ordenLaboratorio->setEspecialidadesMedico($dataEspecialidades);

       $ordenLaboratorio->addPage();

       $ordenLaboratorio->SetAutoPageBreak(true,31);
       $ordenLaboratorio->Image("public/asset/empresa/".self::BusinesData()[0]->img_fondo,20,65,175,37);
       
       foreach($data as $dato){
        $ordenLaboratorio->Ln(4);
        $ordenLaboratorio->SetX(9);
        $ordenLaboratorio->SetFont("Helvetica","",8.5);
        $ordenLaboratorio->Cell(5,1,$dato->code_orden,0,0);
 
        $ordenLaboratorio->SetX(32);
        $ordenLaboratorio->MultiCell(83,3,utf8__($dato->descripcion_orden),0,0);
 
        $ordenLaboratorio->SetX(125);
        $ordenLaboratorio->Cell(5,1,strtoupper(utf8__($dato->categoria_orden)),0,0);
 
        $ordenLaboratorio->SetX(193);
        $ordenLaboratorio->Cell(5,1,utf8__($dato->cantidad),0,1);

       }
       $ordenLaboratorio->Output();
     }else
     {
      PageExtra::PageNoAutorizado();
     }
   }
}