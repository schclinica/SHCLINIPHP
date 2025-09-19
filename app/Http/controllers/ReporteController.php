<?php 
namespace Http\controllers;

use FPDF;
use Http\pageextras\PageExtra;
use lib\BaseController;
use lib\PdfResultados;
use models\Caja;
use models\CajaFarmacia;
use models\CategoriaEgreso;
use models\CitaMedica;
use models\Empresa;
use models\ProductoFarmacia;
use models\SubCategoriaEgreso;

class ReporteController extends BaseController{

 /// ver la vista de reportes
 public static function index()
 {
    self::NoAuth();
    if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === 'Farmacia')
    {
        $model = new CitaMedica;$modelCaja = new Caja;$modelCaja1 = new Caja;$modelCaja2 = new Caja;

            $ReporteIngresosDia = $model->procedure("proc_ingresos_clinica_servicios", "c", ["total_hoy", 12, self::FechaActual("Y-m-d"), null, null]);
            $ReporteIngresosMes = $model->procedure("proc_ingresos_clinica_servicios", "c", ["total_mes", 12, self::FechaActual("Y-m-d"), null, null]);
            $ReporteIngresosAnio = $model->procedure("proc_ingresos_clinica_servicios", "c", ["total_anio", 12, self::FechaActual("Y-m-d"), null, null]);
        if(self::profile()->rol === self::$profile[0]){
            $sede = self::profile()->sede_id;
            $rol = self::profile()->rol;
        
            $ReporteCajaDia = $modelCaja->query()
                    ->select("sum(if(saldo_final_clinica is null,0,saldo_final_clinica)) as cajatotal")
                    ->Where("substr(ac.fecha_cierre_clinica,1,10)", "=", self::FechaActual("Y-m-d"))
                    ->And("sede_id","=",$sede)
                    ->get();

                $ReporteCajaMes = $modelCaja1->query()
                    ->Where("month(ac.fecha_cierre_clinica)", "=", self::FechaActual("m"))
                    ->And("year(ac.fecha_cierre_clinica)", "=", self::FechaActual("Y"))
                    ->And("sede_id","=",$sede)
                    ->select("sum(if(saldo_final_clinica is null,0,saldo_final_clinica)) as cajatotal")
                    ->get();

                $ReporteCajaAnio = $modelCaja2->query()
                    ->Where("year(ac.fecha_cierre_clinica)", "=", self::FechaActual("Y"))
                    ->And("sede_id","=",$sede)
                    ->select(" sum(if(saldo_final_clinica is null,0,saldo_final_clinica)) as cajatotal")
                    ->get();
        }else{
                if (self::profile()->rol === "admin_general") {
                    $ReporteCajaDia = $modelCaja->query()
                        ->select("sum(if(saldo_final_clinica is null,0,saldo_final_clinica)) as cajatotal")
                        ->Where("substr(ac.fecha_cierre_clinica,1,10)", "=", self::FechaActual("Y-m-d"))
                        ->get();

                    $ReporteCajaMes = $modelCaja1->query()
                        ->Where("month(ac.fecha_cierre_clinica)", "=", self::FechaActual("m"))
                        ->And("year(ac.fecha_cierre_clinica)", "=", self::FechaActual("Y"))
                        ->select("sum(if(saldo_final_clinica is null,0,saldo_final_clinica)) as cajatotal")
                        ->get();

                    $ReporteCajaAnio = $modelCaja2->query()
                        ->Where("year(ac.fecha_cierre_clinica)", "=", self::FechaActual("Y"))
                        ->select(" sum(if(saldo_final_clinica is null,0,saldo_final_clinica)) as cajatotal")
                        ->get();
                }
        }

        if(self::profile()->rol === "Farmacia" || self::profile()->rol === "admin_farmacia"){
            $ReporteIngresosDia = $model->procedure("proc_ingresos_clinica_servicios", "c", ["total_hoy", 12, self::FechaActual("Y-m-d"), null, null]);
            $ReporteIngresosMes = $model->procedure("proc_ingresos_clinica_servicios", "c", ["total_mes", 12, self::FechaActual("Y-m-d"), null, null]);
            $ReporteIngresosAnio = $model->procedure("proc_ingresos_clinica_servicios", "c", ["total_anio", 12, self::FechaActual("Y-m-d"), null, null]);
            $modelCaja = new CajaFarmacia;
            $sede = self::profile()->sede_id;
             $ReporteCajaDia = $modelCaja->query()
                    ->select("sum(saldo_final) as cajatotal")
                    ->Where("substr(cf.fecha_cierre,1,10)", "=", self::FechaActual("Y-m-d"))
                    ->And("sede_id","=",$sede)
                    ->get();

                $ReporteCajaMes = $modelCaja->query()
                    ->select("sum(saldo_final) as cajatotal")
                    ->Where("month(cf.fecha_cierre)", "=", self::FechaActual("m"))
                    ->And("year(cf.fecha_cierre)", "=", self::FechaActual("Y"))
                    ->And("sede_id","=",$sede)
                    ->get();

                $ReporteCajaAnio = $modelCaja->query()
                    ->select("sum(if(saldo_final is null,0,saldo_final)) as cajatotal")
                    ->Where("year(cf.fecha_cierre)", "=", self::FechaActual("Y"))
                    ->And("sede_id","=",$sede)
                    ->get();
        }
        
        self::View_("reportes.index",compact("ReporteIngresosDia","ReporteIngresosMes","ReporteIngresosAnio","ReporteCajaDia","ReporteCajaMes","ReporteCajaAnio"));
    }else{
        PageExtra::PageNoAutorizado();
    }
 }

 /// ver los reportes por mes de cada año  recaudación de los ingresos de la clinica
 public static function verIngresosPorMes()
 {
    self::NoAuth();
    if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general')
    {
      $model = new CitaMedica;

      if(self::profile()->rol === self::$profile[0]){
        $sede = self::profile()->sede_id;
        $respuesta = $model->query()
        ->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
      ->select("spanishmonthname(monthname(ctm.fecha_cita)) as mes","sum(monto_clinica) as total")
      ->Where("year(ctm.fecha_cita)","=",self::FechaActual("Y"))
      ->And("ctm.estado","<>","anulado")
      ->And("ctm.sedecita_id","=",$sede)
      ->GroupBy(["mes"])
      ->get();
      }else{
        $respuesta = $model->query()
      ->select("spanishmonthname(monthname(ctm.fecha_cita)) as mes","sum(monto_clinica) as total")
      ->Where("year(ctm.fecha_cita)","=",self::FechaActual("Y"))
      ->And("ctm.estado","<>","anulado")
      ->GroupBy(["mes"])
      ->get();
      }

      self::json(["response" => $respuesta]);
    }else{
        self::json(["response" => []]);
    }
 }

 /// ingesos recaudados por cada año
 public static function verIngresosPorAnio()
 {
    self::NoAuth();
    if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general')
    {
      $model = new CitaMedica;

      if(self::profile()->rol === self::$profile[0]){
        $sede = self::profile()->sede_id;
        $respuesta = $model->query()
        ->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
      ->select("year(ctm.fecha_cita) as anio","sum(monto_clinica) as total")
      ->Where("ctm.estado","<>","anulado")
      ->And("ctm.sedecita_id","=",$sede)
      ->GroupBy(["anio"])
      ->get();
      }else{
        $respuesta = $model->query()
      ->select("year(ctm.fecha_cita) as anio","sum(monto_clinica) as total")
      ->Where("ctm.estado","<>","anulado")
      ->GroupBy(["anio"])
      ->get();
      }

      self::json(["response" => $respuesta]);
    }else{
        self::json(["response" => []]);
    }
 }

 /** Mostrar los ingresos detallado de la clínica */
 public static function RepoIngresosClinica(string $fecha1='' ,string $fecha2 ='')
 {
  self::NoAuth();
  if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general')
  {
   $model = new CitaMedica;
   
   if(empty($fecha1) and empty($fecha2))
   {
    $respuesta =[];
   }else{
    $rol = self::profile()->rol;
    $sede = self::profile()->sede_id;
    $respuesta = $model->procedure("proc_ingresos_rango_fechas","c",[$fecha1,$fecha2,$rol,$sede]);
   }
 
   self::json(["response" => $respuesta]);
  }else{
    self::json(["response" => []]);
  }
 }

 public static function resultadosClinica()
 {
     self::NoAuth();

     if(self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[5] || self::profile()->rol === 'admin_general')
     {
         /// creamos el pdf
         $pdfResultados = new PdfResultados();
         $pdfResultados->setDirSede(self::profile()->sede_id!= null?(self::profile()->direccion_sede != null ? utf8__(self::profile()->name_departamento." - ".self::profile()->name_provincia." - ".self::profile()->name_distrito." - ".self::profile()->direccion_sede) : 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'):self::BusinesData()[0]->direccion);

         /// indicamos un título a la hoja
         $pdfResultados->SetTitle(utf8__("Estado de resultados-clínica"));

         /// agregamos una nueva hoja
         $pdfResultados->AddPage();

         /// indicamos los datos de la empresa
         
         /// Agremos un título a la hoja

         $pdfResultados->SetFont("Times","B",16);
         $pdfResultados->Ln(5);

         $pdfResultados->Cell(200,2,"Estado de resultados",0,1,"C");
         $pdfResultados->Cell(200,2,"_____________________________",0,1,"C");

         $pdfResultados->Ln(15);
         $modelCaja = new Caja;


         if(!isset($_GET["fi"]) or !isset($_GET["ff"])){
             PageExtra::PageNoAutorizado();
             return;
         }
            if (self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[5]) {
                $sede = self::profile()->sede_id;
                $responseIngresoClinica = $modelCaja->query()
                    ->select("sum(saldo_final_clinica) as saldo_final_clinica")
                    ->Where("estado_clinica", "=","c")
                    ->And("fecha_cierre_clinica", ">=", self::get("fi"))
                    ->And("fecha_cierre_clinica", "<=", self::get("ff"))
                    ->And("sede_id", "=", $sede)
                    ->first();
            } else {
                $responseIngresoClinica = $modelCaja->query()
                    ->select("sum(saldo_final_clinica) as saldo_final_clinica")
                    ->Where("estado_clinica", "=","c")
                    ->And("fecha_cierre_clinica", ">=", self::get("fi"))
                    ->And("fecha_cierre_clinica", "<=", self::get("ff"))
                    ->first();
            }
         if(!$responseIngresoClinica){
             PageExtra::PageNoAutorizado();
             exit;
         }
         $TotalCompra = 0.00;$TotalVenta = 0.00;$UtilidadBruta = 0.00;$UtilidadPerdidaNeta = 0.00;
         $Ganancia = 0.00;
         $pdfResultados->setFont("Times","B",12);
         $pdfResultados->SetTextColor(0,0,128);
         $pdfResultados->setX(20);
         $pdfResultados->Cell(60,7,utf8__("Total ingreso de la clínica ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.')),1,0,1);
         $pdfResultados->SetTextColor(0,0,0);
         $pdfResultados->Cell(110,7,$responseIngresoClinica->saldo_final_clinica != null ? number_format(($responseIngresoClinica->saldo_final_clinica),2,","," "):number_format(0,2,","," "),1,1,"R");
 
         $pdfResultados->Ln(2);
         /// mostramos las categorias por fecha
         $modelCategoria = new CategoriaEgreso;
         $modelSub = new SubCategoriaEgreso;
         $sedeC = self::profile()->sede_id;

        if (self::profile()->rol === self::$profile[0]) {
            $responseCategoria = $modelCategoria->procedure("proc_consultas", "C", [$sedeC, 5]);
        } else {
            $responseCategoria = $modelCategoria->procedure("proc_consultas", "C", [null, 6]);
        }

        
        $pdfResultados->Ln(5);$TotalEgresoCategoria = 0.00;$item = 0;
         foreach($responseCategoria as $cat)
         {
            $TotalEgreso = 0.00;
             $item++;
             $responseSub = $modelSub->query()
             ->Where("categoriaegreso_id","=",$cat->id_categoria_egreso)
             ->And("fecha",">=",$_GET["fi"])
             ->And("fecha","<=",$_GET["ff"])->get();
              //->select("group_concat('  ',concat(name_subcategoria,' => ',valor_gasto),' ')as datasub","sum(valor_gasto) as gasto")


             foreach($responseSub as $sub)
             {
                 
             $TotalEgreso+= $sub->valor_gasto;
             }
             $TotalEgresoCategoria+=$TotalEgreso;

             $pdfResultados->setFont("Times","B",12);
             $pdfResultados->SetTextColor(248, 248, 255);
             $pdfResultados->SetFillColor(65, 105, 225);
             $pdfResultados->SetDrawColor(65, 105, 225);
             $pdfResultados->setX(20);
             $pdfResultados->Cell(170,7,utf8__($item.".- ".$cat->name_categoria_egreso." ( ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.')." ) "." = ".$TotalEgreso),1,1,"L",true);
             $pdfResultados->setFont("Times","",10);
             $pdfResultados->SetTextColor(120,50,50);
             $pdfResultados->SetFillColor(248, 248, 255);
             foreach($responseSub as $sub)
             {
                 
                 $pdfResultados->setX(20);
                 $pdfResultados->Cell(140,7,utf8__($sub->name_subcategoria),1,0,'L',true);

                 $pdfResultados->Cell(30,7,(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.')." ".utf8__($sub->valor_gasto),1,1,'R',true);
             }
         }
         $pdfResultados->setFont("Times","B",12);
         $pdfResultados->SetTextColor(0,0,128);
         $pdfResultados->setX(20);
         $pdfResultados->Cell(50,7,utf8__("Total Egreso ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.')),1,0,1);
         $pdfResultados->SetTextColor(0,0,0);
         $pdfResultados->Cell(120,7,number_format($TotalEgresoCategoria,2,","," "),1,1,"R");

         $pdfResultados->SetTextColor(0,0,128);
         $pdfResultados->setX(20);
         $pdfResultados->Cell(50,7,utf8__("Utilidad o perdida neta ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.')),1,0,1);
         $pdfResultados->SetTextColor(0,0,0);

         $UtilidadPerdidaNeta = $responseIngresoClinica->saldo_final_clinica - $TotalEgresoCategoria;
         $pdfResultados->Cell(120,7,$UtilidadPerdidaNeta <=0? number_format(abs($UtilidadPerdidaNeta),2,","," ")." ".utf8__("Pérdida") : number_format(abs($UtilidadPerdidaNeta),2,","," ")." ".utf8__("Ganancia"),1,0,"R");
         /// vemos la hoja
         $pdfResultados->Output();

     }else{
         PageExtra::PageNoAutorizado();
     }
 }

 /// ver el reporte pdf de la historia de los ingresos de la clínica por cada mes del año
 public static function repoPdfHistoriaIngresosClinicaPorMes()
 {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'Director')
    {
        $repoHistoriaIngresosClinica = new PdfResultados();
        $repoHistoriaIngresosClinica->setDirSede(self::profile()->sede_id!= null?(self::profile()->direccion_sede != null ? utf8__(self::profile()->name_departamento." - ".self::profile()->name_provincia." - ".self::profile()->name_distrito." - ".self::profile()->direccion_sede) : 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'):self::BusinesData()[0]->direccion);
        /// le asignamos un título a la hoja
        $repoHistoriaIngresosClinica->SetTitle("Reporte-Historia-Ingresos-Clinica-Mes");
        /// Aperturamos una hoja
        $repoHistoriaIngresosClinica->AddPage();

        $repoHistoriaIngresosClinica->setFont("Times","B",14);
        $repoHistoriaIngresosClinica->Cell(200,"3",utf8__("HISTORIAL DE INGRESOS DE CADA MES"),0,1,"C");
        $repoHistoriaIngresosClinica->Cell(200,"3","____________________________________________",0,1,"C");

        $repoHistoriaIngresosClinica->SetDrawColor(65, 105, 225);
        $repoHistoriaIngresosClinica->Ln(10);
        $repoHistoriaIngresosClinica->SetX(25);
        $repoHistoriaIngresosClinica->setFont("Times","B",12);
        $repoHistoriaIngresosClinica->Cell(14,10,utf8__("AÑO  "),1,0,"C");
        $repoHistoriaIngresosClinica->setFont("Times","",12);
        $repoHistoriaIngresosClinica->Cell(14,10,"  ".self::FechaActual("Y"),1,0,"L");
        
        $repoHistoriaIngresosClinica->SetDrawColor(65, 105, 225);
 
 
        $repoHistoriaIngresosClinica->setFont("Times","B",12);
        $repoHistoriaIngresosClinica->Cell(42,10,utf8__("DESDE EL MES DE "),1,0,"C");
        $repoHistoriaIngresosClinica->setFont("Times","",12);
        $repoHistoriaIngresosClinica->Cell(35,10,"  ".self::FechaActual("Y"),1,1,"L");

        $repoHistoriaIngresosClinica->SetFillColor(72, 61, 139);
        $repoHistoriaIngresosClinica->SetDrawColor(248, 248, 255);
        $repoHistoriaIngresosClinica->SetTextColor(248, 248, 255);
 
        $repoHistoriaIngresosClinica->SetX(25);
        $repoHistoriaIngresosClinica->setFont("Times","B",12);
        $repoHistoriaIngresosClinica->Cell(50,10,utf8__("MES  "),1,0,"L",true);
        $repoHistoriaIngresosClinica->setFont("Times","",12);
        $repoHistoriaIngresosClinica->Cell(110,10,"MONTO",1,0,"R",true);
        /// mostramos el pdf
        $repoHistoriaIngresosClinica->Output();
    }else{
        PageExtra::PageNoAutorizado();
    }
 }

 /**
  * Generar un reporte (RECIBO) al sacar una cita mèdica
  */
  public static function reciboCitaMedica($folio){
    self::NoAuth();/// si no está authenticado , redirije al login
    $modelcita = new CitaMedica;
    //$MaxIdCita = self::post("folio");
 
    if((self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1]
    || self::profile()->rol === self::$profile[3] || self::profile()->rol === "admin_general"))
    {
        $modelReciboCita = new CitaMedica;
        /// consultamos para el reporte de la cita
        $citaRecibo = $modelReciboCita->procedure("proc_recibo_cita","c",[$folio]);

        if(!$citaRecibo){PageExtra::PageNoAutorizado();exit;}
    
        /// generamos el reporte
        $reciboCita = new PdfResultados();
        $reciboCita->setDirSede($citaRecibo[0]->direccion_sede != null ? utf8__($citaRecibo[0]->name_departamento." - ".$citaRecibo[0]->name_provincia." - ".$citaRecibo[0]->name_distrito." - ".$citaRecibo[0]->direccion_sede) : 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
        /// le dmaos un titulo
        $reciboCita->SetTitle("Recibo-Cita-Medica");
    
    
        
        /// agregar una pagina
        $reciboCita->AddPage();
    
        $reciboCita->SetFont("Times","B",18);
        /// le indicmos un title a la página
    
        $reciboCita->Cell(200,2,utf8__("Recibo cita médica"),0,1,"C");
        $reciboCita->Cell(200,3,"_____________________",0,1,"C");
    
    
        $reciboCita->Ln(14);
        /// indicamos los datos
    
        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
   
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,utf8__("Sede"),1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset(self::profile()->sede_id) ? ucwords(self::profile()->namesede):'------------------------------------------------------',1,1,"L");
        $reciboCita->setX(20);
        $reciboCita->Cell(35,10,"Fecha de la cita",1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(60,10,isset($citaRecibo[0]->fechacita) ? utf8__(self::getFechaText($citaRecibo[0]->fechacita)):'-------------',1,0,"L");
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,"Hora de la cita",1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(39,10,isset($citaRecibo[0]->hora_cita) ? $citaRecibo[0]->hora_cita:'-----------',1,1,"L");
    
    
        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,utf8__("Médico"),1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset($citaRecibo[0]->medicoatencion) ? "Med.".(utf8__($citaRecibo[0]->medicoatencion)):'------------------------------------------------------',1,1,"L");

        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,utf8__("Paciente"),1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset($citaRecibo[0]->pacienteatencion) ? utf8__($citaRecibo[0]->pacienteatencion):'---------------------------------------------------',1,1,"L");

        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,"Especialidad",1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset($citaRecibo[0]->nombre_esp) ? utf8__($citaRecibo[0]->nombre_esp):'-------------------------------------',1,1,"L");
    
         
        
        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,utf8__("Servicio"),1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset($citaRecibo[0]->name_servicio) ? utf8__($citaRecibo[0]->name_servicio):'--------------------------------------------',1,1,"L");
    
        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,utf8__("Acompañante"),1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset($citaRecibo[0]->nombre_apoderado) ? utf8__($citaRecibo[0]->nombre_apoderado):'--------------------------------------------',1,1,"L");
    
        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,utf8__("Total importe ").(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.'),1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset($citaRecibo[0]->monto_pago) ? utf8__($citaRecibo[0]->monto_pago):'0.00',1,0,"L");
    
       
      
    
    
        /// mostramos la página
        $reciboCita->Output();
    }else{
        PageExtra::Page404();
    }
  }

  public static function reciboCitaMedicaProgram($id){
    self::NoAuth();/// si no está authenticado , redirije al login
    $modelReciboCita = new CitaMedica;
    /// consultamos para el reporte de la cita
    $citaRecibo = $modelReciboCita->procedure("proc_recibo_cita","c",[$id]);


    if($citaRecibo && (self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1]
    || self::profile()->rol === self::$profile[3] || self::profile()->rol === "admin_general"))
    {
        /// generamos el reporte
        $reciboCita = new PdfResultados();
        $reciboCita->setDirSede($citaRecibo[0]->direccion_sede != null ? utf8__($citaRecibo[0]->name_departamento." - ".$citaRecibo[0]->name_provincia." - ".$citaRecibo[0]->name_distrito." - ".$citaRecibo[0]->direccion_sede) : 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
    
        /// le dmaos un titulo
        $reciboCita->SetTitle("Recibo-Cita-Medica");
    
    
        
        /// agregar una pagina
        $reciboCita->AddPage();
    
        $reciboCita->SetFont("Times","B",18);
        /// le indicmos un title a la página
    
        $reciboCita->Cell(200,2,utf8__("Recibo cita médica"),0,1,"C");
        $reciboCita->Cell(200,3,"_____________________",0,1,"C");
    
    
        $reciboCita->Ln(14);
        /// indicamos los datos
        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,utf8__("Sede"),1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset(self::profile()->sede_id) ? ucwords(self::profile()->namesede):$citaRecibo[0]->namesede,1,1,"L");
        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
    
        $reciboCita->Cell(35,10,"Fecha de la cita",1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(60,10,isset($citaRecibo[0]->fechacita) ? utf8__(self::getFechaText($citaRecibo[0]->fechacita)):'-------------',1,0,"L");
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,"Hora de la cita",1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(39,10,isset($citaRecibo[0]->hora_cita) ? $citaRecibo[0]->hora_cita:'-----------',1,1,"L");
    
    
        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,utf8__("Médico"),1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset($citaRecibo[0]->medicoatencion) ? "Med.".(utf8__($citaRecibo[0]->medicoatencion)):'------------------------------------------------------',1,1,"L");

        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,utf8__("Paciente"),1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset($citaRecibo[0]->pacienteatencion) ? utf8__($citaRecibo[0]->pacienteatencion):'---------------------------------------------------',1,1,"L");

        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,"Especialidad",1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset($citaRecibo[0]->nombre_esp) ? utf8__($citaRecibo[0]->nombre_esp):'-------------------------------------',1,1,"L");
    
         
        
        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,utf8__("Servicio"),1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset($citaRecibo[0]->name_servicio) ? utf8__($citaRecibo[0]->name_servicio):'--------------------------------------------',1,1,"L");
    
        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,utf8__("Acompañante"),1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset($citaRecibo[0]->nombre_apoderado) ? utf8__($citaRecibo[0]->nombre_apoderado):'--------------------------------------------',1,1,"L");
    
        $reciboCita->setX(20);
        $reciboCita->SetFont("Times","B",12);
        $reciboCita->Cell(35,10,utf8__("Total importe ").(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.'),1,0,"L");
        $reciboCita->SetFont("Times","",12);
        $reciboCita->Cell(134,10,isset($citaRecibo[0]->monto_pago) ? utf8__($citaRecibo[0]->monto_pago):'0.00',1,0,"L");
    
       
      
    
    
        /// mostramos la página
        $reciboCita->Output();
    }else{
        PageExtra::Page404();
    }
  }

  /// Ver reporte estadistico
  public static function ViewReportEstadistico(){
    
  }
}