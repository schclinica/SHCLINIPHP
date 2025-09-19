<?php
namespace Http\controllers;

use FPDF;
use Http\pageextras\PageExtra;
use lib\BaseController;
use lib\PdfResultados;
use lib\PlantillaInformeCierreCaja;
use models\Caja;
use models\CategoriaEgreso;
use models\Movimiento;
use models\Recibo;

class CajaController extends BaseController
{
  /// mostramos la vista para gestionar la caja

  public static function index()
  {
    self::NoAuth();
    if(self::profile()->rol === self::$profile[1]  || self::profile()->rol === 'Director' || self::profile()->rol === "admin_general")
    {
     self::View_("caja.index");
    }else{
        PageExtra::PageNoAutorizado();
    }
  }
 /** VERIFICAR SI HAY UNA CAJA APERTURADA PARA LA CLINICA*/
 public static function VerificarCajaAprturadaClinica(){
  self::NoAuth();
  $caja = new Caja;
  if(self::profile()->rol !== "admin_general"){
    $sede = self::profile()->sede_id;
    $CajaApertura = $caja->query()->Where("sede_id","=",$sede)
    ->And("estado_clinica","=","a")
    ->get();
  }else{
    $CajaApertura = [];
  }
  self::json(["caja" => $CajaApertura]);
 }
  /// guardar la apertura de una caja
  public static function store()
  {
    self::NoAuth();
    if(self::profile()->rol === self::$profile[1] || self::profile()->rol === 'admin_general' ||self::profile()->rol === 'Director' || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === 'Farmacia'){
      if(self::ValidateToken(self::post("_token")))
      {
       $modelCajaApertura = new Caja; $Input = "";

       $usuario = self::profile()->id_usuario;
       /// verificamos que sea el director quién apertura la caja  
       if(self::profile()->rol === 'Director' || self::profile()->rol === self::$profile[1])
       {
        /// calculamos el saldo final 
        /// validamos antes de registrar la apertura de caja
        $sede = self::profile()->sede_id;
        $existeAperturaEnClinica = $modelCajaApertura->query()
            ->Where("sede_id","=",$sede)
            ->And("estado_clinica", "=", "a")
            ->get();
         if($existeAperturaEnClinica){
           self::json(["response" => "error-caja"]);
           exit;
         }else{
          $respuesta = $modelCajaApertura->Insert([
          "fecha_apertura_clinica" => self::FechaActual("Y-m-d H:i:s"),
          "saldo_inicial_clinica" => self::post("monto_apertura"),
          "estado_clinica"=>"a",
          "sede_id" => self::profile()->sede_id,
          "usuario_id" => $usuario,
        ]);
        }

       }else{
        if(self::profile()->rol === 'Farmacia' || self::profile()->rol === 'admin_farmacia')
        {
         
         $respuesta = $modelCajaApertura->Insert([
          "fecha_apertura_farmacia" => self::FechaActual("Y-m-d H:i:s"),
          "saldo_inicial_farmacia" => self::post("monto_apertura"),
          "estado_farmacia" => "a",
          "sede_id" => self::profile()->sede_id,
          "usuario_id" => $usuario,
          "tipo" => "f"
          ]);
  
        } 
       }

       self::json(["response" =>$respuesta]);
      }else{
        self::json(["response" => "token-invalidate"]);
      }
    }else{
      self::json(["response" => "no-authorized"],403);
    }
  }

  /// mostrar la apertura d ecaja
  public static function mostrarAperturasCaja()
  {
    self::NoAuth();
    if(self::profile()->rol === self::$profile[1]  ||  self::profile()->rol === 'Director' || self::profile()->rol === "admin_general"){
       
       $modelCajaApertura = new Caja;
       $usuario = self::profile()->id_usuario;
        $sede = self::profile()->sede_id;
       if(self::profile()->rol === self::$profile[0]){
        $respuesta = $modelCajaApertura->query()
        ->Join("usuario as u","ac.usuario_id","=","u.id_usuario")
        ->Where("ac.sede_id","=",$sede)
        ->orderBy("ac.id_apertura_caja","desc")
        ->get();
       }else{
         if(self::profile()->rol === self::$profile[1]){
          $respuesta = $modelCajaApertura->query()
        ->Join("usuario as u","ac.usuario_id","=","u.id_usuario")
        ->Where("ac.sede_id","=",$sede)
        ->And("usuario_id","=",$usuario)
        ->orderBy("ac.id_apertura_caja","desc")
        ->get();
         }else{
          $respuesta = $modelCajaApertura->query()
        ->Join("usuario as u","ac.usuario_id","=","u.id_usuario")
        ->orderBy("ac.id_apertura_caja","desc")
        ->get();
         }
       }
    
        self::json(["response" =>$respuesta]);
    }else{
      self::json(["response" => "no-authorized"],403);
    }
  }

  /**
   * Eliminar la caja aperturada
   */
  public static function EliminarCajaApertura($id)
  {
    $modelcaja = new Caja;

    $response = $modelcaja->delete($id);

    if($response){
       self::json(["response" => "ok"]);
    }else{
      self::json(["response" => "error"]);
    }
  }

  /// CERRAR LA CAJA APERTURADA
  public static function CerrarCajaAperturada(int $id)
  {
    self::NoAuth();

    if( self::profile()->rol === self::$profile[1]   || self::profile()->rol === 'Director' || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === 'Farmacia'){
      if(self::ValidateToken(self::post("_token")))
      {
       $modelCajaApertura = new Caja;$modelMov = new Movimiento; $Saldo_Final = 0.00;
        /// calculamos el ingreso de la clínica
        $modelcita = new Recibo;
 
        /// obtenemos el total egresos
        if (self::profile()->rol === self::$profile[1]  || self::profile()->rol === self::$profile[0]) {
          $sede = self::profile()->sede_id;
          $CajaActual = $modelCajaApertura->query()
          ->Where("id_apertura_caja","=",$id)
          ->first();
          $TotalEgreso = $modelMov->query()
            ->select("sum(monto_movimiento) as total_gasto")
            ->Where("fecha_registro", "=", self::FechaActual("Y-m-d"))
            ->And("sede_id", "=", $sede)
            ->And("tipo_movimiento","=","g")
            ->And("mov.tipo","=","c")
            ->And("caja_id","=",$id)
            ->first();
            $TotalPrestamo = $modelMov->query()
            ->select("sum(monto_movimiento) as total_prestamo")
            ->Where("fecha_registro", "=", self::FechaActual("Y-m-d"))
            ->And("sede_id", "=", $sede)
            ->And("tipo_movimiento","=","p")
            ->And("mov.tipo","=","c")
            ->And("caja_id","=",$id)
            ->first();

            /// Total depositos
             $TotalDeposito = $modelMov->query()
            ->select("sum(monto_movimiento) as total_deposito")
            ->Where("fecha_registro", "=", self::FechaActual("Y-m-d"))
            ->And("sede_id", "=", $sede)
            ->And("tipo_movimiento","=","d")
            ->And("mov.tipo","=","c")
            ->And("caja_id","=",$id)
            ->first();
          /// obtenemos el ingreso total de la clínica al cerrar la caja
          $IngrestoTotalClinica = $modelcita->query()
            ->Join("cita_medica as ctm","re.cita_id","=","ctm.id_cita_medica")
            ->select("sum(ctm.monto_pago) as total")
            ->Where("fecha_cita", "=", self::FechaActual("Y-m-d"))
            ->And("estado", "<>", "anulado")
            ->And("ctm.sedecita_id","=",$sede)
            ->And("re.caja_id","=",$id)
            ->first();
        
             
            $GastoTotal = (isset($TotalEgreso->total_gasto) ? $TotalEgreso->total_gasto:0.00);
            $PrestamoTotal = (isset($TotalPrestamo->total_prestamo) ? $TotalPrestamo->total_prestamo : 0.00);
            $DepositoTotal = (isset($TotalDeposito->total_deposito) ? $TotalDeposito->total_deposito:0.00);
        /// calculamos el saldo final 
        $Saldo_Final = (($CajaActual->saldo_inicial_clinica +$IngrestoTotalClinica->total + $DepositoTotal) - ($GastoTotal + $PrestamoTotal));
        $respuesta = $modelCajaApertura->Update([
          "id_apertura_caja" => $id,
          "fecha_cierre_clinica" => self::FechaActual("Y-m-d H:i:s"),
          "ingreso_clinica" => $IngrestoTotalClinica->total == null ? 0.00 :$IngrestoTotalClinica->total,
          "saldo_final_clinica" => $Saldo_Final,
          "total_egreso_clinica" => isset($TotalEgreso->total_gasto) ? $TotalEgreso->total_gasto:0.00,
          "estado_clinica" => "c",
          "total_prestamo" => $PrestamoTotal,
          "total_deposito" => $DepositoTotal
        ]);
       }
       self::json(["response" => $respuesta]);
      }else{
        self::json(["token-invalidate"]);
      }
    }else{
      self::json(["response" => "no-authrized"]);
    }
  }

  /// ver el informe de cierre de caja
  public static function informeCierreCaja(int $id)
  {
       self::NoAuth();

    if( self::profile()->rol === self::$profile[1]  || self::profile()->rol === 'admin_general' || self::profile()->rol === 'Director'){
    $Cajamodel = new Caja;
    $sede = self::profile()->sede_id;
    if(self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1]){
        
        $dataCaja = $Cajamodel->query()
        ->Join("usuario as u","ac.usuario_id","=","u.id_usuario")
        ->Join("sedes as s","ac.sede_id","=","s.id_sede")
        ->Where("id_apertura_caja", "=", $id)
        ->And("ac.sede_id","=",$sede)
        ->first();
        
    }else{
      $dataCaja = $Cajamodel->query()
        ->Join("usuario as u","ac.usuario_id","=","u.id_usuario")
        ->Join("sedes as s","ac.sede_id","=","s.id_sede")
        ->Where("id_apertura_caja", "=", $id)
        ->first();
    } 
      unset($dataCaja->pasword);
     $informeCierreCaja = new PlantillaInformeCierreCaja();

     $informeCierreCaja->setUsuarioInforme($dataCaja->name);
     $informeCierreCaja->setDireccionSede($dataCaja->namesede);
     $informeCierreCaja->setTitleDoc("INFORME DE CIERRE DE CAJA- CLÍNICA");
     $informeCierreCaja->SetTitle("Informe de cierre de caja");

     $informeCierreCaja->AddPage();

     $informeCierreCaja->SetAutoPageBreak(true,60);

      
    /// datos del cierre de caja

    if(!$dataCaja){PageExtra::PageNoAutorizado();}
    $SaldoInit =  $dataCaja->saldo_inicial_clinica;
    $SaldoFin =  $dataCaja->saldo_final_clinica;
   
    $informeCierreCaja->SetDrawColor(0, 0, 128);
    $informeCierreCaja->SetX(20);
    $informeCierreCaja->SetFont("Times","B",12);
    $informeCierreCaja->Cell(100,"6","Sucursal ",1,0,"L");
    $informeCierreCaja->SetFont("Times","",12);
    $informeCierreCaja->Cell(67,"6",$dataCaja->namesede,1,1,"R");

    $informeCierreCaja->SetDrawColor(0, 0, 128);
    $informeCierreCaja->SetX(20);
    $informeCierreCaja->SetFont("Times","B",12);
    $informeCierreCaja->Cell(100,"6","Fecha Apertura ",1,0,"L");
    $informeCierreCaja->SetFont("Times","",12);
    $informeCierreCaja->Cell(67,"6",$dataCaja->fecha_apertura_clinica,1,1,"R");

     $informeCierreCaja->SetDrawColor(0, 0, 128);
    $informeCierreCaja->SetX(20);
    $informeCierreCaja->SetFont("Times","B",12);
    $informeCierreCaja->Cell(100,"6","Fecha cierre ",1,0,"L");
    $informeCierreCaja->SetFont("Times","",12);
    $informeCierreCaja->Cell(67,"6",($dataCaja->fecha_cierre_clinica != null ? $dataCaja->fecha_cierre_clinica:''),1,1,"R");

    $informeCierreCaja->SetDrawColor(0, 0, 128);
    $informeCierreCaja->SetX(20);
    $informeCierreCaja->SetFont("Times","B",12);
    $informeCierreCaja->Cell(100,"6","Estado De La Caja ",1,0,"L");
    $informeCierreCaja->SetFont("Times","",12);
    $informeCierreCaja->Cell(67,"6",($dataCaja->estado_clinica === 'c' ? 'Cerrado' : 'Abierto'),1,1,"R");

    $informeCierreCaja->SetDrawColor(0, 0, 128);
    $informeCierreCaja->SetX(20);
    $informeCierreCaja->SetFont("Times","B",12);
    $informeCierreCaja->Cell(100,"6","Saldo inicial  entregado ",1,0,"L");
    $informeCierreCaja->SetFont("Times","",12);
    $informeCierreCaja->Cell(67,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".$SaldoInit,1,1,"R");

    
      $informeCierreCaja->SetDrawColor(0, 0, 128);
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFont("Times","B",12);
      $informeCierreCaja->Cell(100,"6",utf8__("Ingreso de la clínica ") ,1,0,"L");
      $informeCierreCaja->SetFont("Times","",12);
      $informeCierreCaja->Cell(67,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".$dataCaja->ingreso_clinica,1,1,"R");
      
      $informeCierreCaja->SetDrawColor(0, 0, 128);
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFont("Times","B",12);
      $informeCierreCaja->Cell(100,"6",utf8__("Total en Gastos: ") ,1,0,"L");
      $informeCierreCaja->SetFont("Times","",12);
      $informeCierreCaja->Cell(67,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".$dataCaja->total_egreso_clinica,1,1,"R");
 
      $informeCierreCaja->SetDrawColor(0, 0, 128);
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFont("Times","B",12);
      $informeCierreCaja->Cell(100,"6",utf8__("Total en Préstamos: ") ,1,0,"L");
      $informeCierreCaja->SetFont("Times","",12);
      $informeCierreCaja->Cell(67,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".$dataCaja->total_prestamo,1,1,"R");
 
      $informeCierreCaja->SetDrawColor(0, 0, 128);
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFont("Times","B",12);
      $informeCierreCaja->Cell(100,"6",utf8__("Total en Depósitos: ") ,1,0,"L");
      $informeCierreCaja->SetFont("Times","",12);
      $informeCierreCaja->Cell(67,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".$dataCaja->total_deposito,1,1,"R");
 
      /// saldo entregado al final del cierre
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFillColor(65, 105, 225);
      $informeCierreCaja->SetTextColor(248, 248, 255);
      $informeCierreCaja->SetFont("Times","B",12);
      $informeCierreCaja->Cell(100,"6",utf8__("Saldo entregado al final del cierre "),1,0,"L",true);
      $informeCierreCaja->SetFont("Times","B",12);
      $informeCierreCaja->Cell(67,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".(number_format($SaldoFin,2,',')),1,1,"R",true);
     
   /// Gastos
     
      $informeCierreCaja->Ln(2);
      $informeCierreCaja->setX(20);
      $informeCierreCaja->Cell(167,10,utf8__(" Detalle de gastos,préstamos y depósitos"),1,1,"L",true);
      $informeCierreCaja->setX(20);
      $informeCierreCaja->SetTextColor(0,0,0);
      $informeCierreCaja->setFont("Times","B",10);
      $informeCierreCaja->Cell(120,8,utf8__("Descripción"),1,0,"L",false);
      $informeCierreCaja->Cell(20,8,utf8__("Tipo"),1,0,"L",false);
      $informeCierreCaja->Cell(27,8,utf8__("Costo  ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')),1,1,"R",false);
      
      $gasto = new Movimiento;
      if(self::profile()->rol === "Director" || self::profile()->rol === "Admisión"){
        $detalleGastos = $gasto->query()
      ->Join("apertura_caja as ap","mov.caja_id","=","ap.id_apertura_caja")
      ->Where("mov.sede_id","=",$sede)
      ->And("mov.caja_id","=",$id)
      ->And("mov.tipo","=","c")
      ->InWhere("tipo_movimiento",["'g','p','d'"])
      ->get();
      }else{
        $detalleGastos = $gasto->query()
      ->Join("apertura_caja as ap","mov.caja_id","=","ap.id_apertura_caja")
      ->Where("mov.caja_id","=",$id)
      ->And("mov.tipo","=","c")
      ->InWhere("tipo_movimiento",["'g','p','d'"])
      ->get();
      }
      
      $informeCierreCaja->setFont("Times","",10);
      $TotalGasto = 0.00;$TotalEnPrestamos = 0.00;$TotalEnDepositos= 0.00;
      foreach($detalleGastos as $gastod){
        if($gastod->tipo_movimiento === 'g'){$TotalGasto+=$gastod->monto_movimiento;}
        if($gastod->tipo_movimiento === 'p'){$TotalEnPrestamos+=$gastod->monto_movimiento;}
        if($gastod->tipo_movimiento === 'd'){$TotalEnDepositos+=$gastod->monto_movimiento;}
      $informeCierreCaja->setX(20);
      $informeCierreCaja->SetTextColor(0,0,0);
      $informeCierreCaja->Cell(120,6,utf8__($gastod->descripcion_movimiento),1,0,"L",false);
      $informeCierreCaja->Cell(20,6,utf8__($gastod->tipo_movimiento === 'g' ? 'Gasto':($gastod->tipo_movimiento === 'p'?'Préstamo':'Depósito')),1,0,"L",false);
      $informeCierreCaja->Cell(27,6,((($gastod->tipo_movimiento==='g' or $gastod->tipo_movimiento === 'p')?'-':'+').utf8__($gastod->monto_movimiento)),1,1,"R",false);
      }
      /// total en gastos
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFillColor(65, 105, 225);
      $informeCierreCaja->SetTextColor(248, 248, 255);
      $informeCierreCaja->SetFont("Times","B",10);
      $informeCierreCaja->Cell(120,"6",utf8__("Total Gasto:"),1,0,"L",true);
      $informeCierreCaja->SetFont("Times","B",12);
      $informeCierreCaja->Cell(47,"6","- ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".(number_format(($TotalGasto),2,',')),1,1,"R",true);
    
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFillColor(65, 105, 225);
      $informeCierreCaja->SetTextColor(248, 248, 255);
      $informeCierreCaja->SetFont("Times","B",10);
      $informeCierreCaja->Cell(120,"6",utf8__("Total Préstamos"),1,0,"L",true);
      $informeCierreCaja->SetFont("Times","B",12);
      $informeCierreCaja->Cell(47,"6","- ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".(number_format(($TotalEnPrestamos),2,',')),1,1,"R",true);
    
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFillColor(65, 105, 225);
      $informeCierreCaja->SetTextColor(248, 248, 255);
      $informeCierreCaja->SetFont("Times","B",10);
      $informeCierreCaja->Cell(120,"6",utf8__("Total Depósitos"),1,0,"L",true);
      $informeCierreCaja->SetFont("Times","B",12);
      $informeCierreCaja->Cell(47,"6","+ ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".(number_format(($TotalEnDepositos),2,',')),1,1,"R",true);
    
     /// DETALLE DE INGRESOS DE LA CLINICA
      $informeCierreCaja->Ln(2);
      $informeCierreCaja->setX(20);
      $informeCierreCaja->Cell(167,10,utf8__("Ingresos de la clínica"),1,1,"L",true);
      $informeCierreCaja->setX(20);
      $informeCierreCaja->SetTextColor(0,0,0);
      $informeCierreCaja->setFont("Times","B",10);
      $informeCierreCaja->Cell(58,8,utf8__("N° RECIBO"),1,0,"L",false);
      $informeCierreCaja->Cell(30,8,utf8__("Monto Total ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')),1,0,"C",false);
      $informeCierreCaja->Cell(32,8,utf8__("Monto Clínica ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')),1,0,"C",false);
      $informeCierreCaja->Cell(47,8,utf8__("Monto Médico  ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')),1,1,"C",false);
     $recibo = new Recibo;
     if(self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1]){
      $reciboDetalle = $recibo->query()->Join("cita_medica as ct","re.cita_id","=","ct.id_cita_medica")
     ->Join("detalle_recibo as dr","dr.recibo_id","=","re.id_recibo")
     ->Where("re.caja_id","=",$id)
     ->And("ct.sedecita_id","=",$sede)
     ->And("ct.fecha_cita", "=", self::FechaActual("Y-m-d"))
     ->get(); 
     }else{
       $reciboDetalle = $recibo->query()->Join("cita_medica as ct","re.cita_id","=","ct.id_cita_medica")
     ->Join("detalle_recibo as dr","dr.recibo_id","=","re.id_recibo")
     ->Where("re.caja_id","=",$id)
     ->And("ct.fecha_cita", "=", self::FechaActual("Y-m-d"))
     ->get(); 
     }

      $TotalImporte = 0.00;$TotalClinica = 0.00;$TotalMedico = 0.00;
     foreach($reciboDetalle as $rec){
      $TotalImporte+=$rec->monto_pago;
      $TotalClinica+=$rec->monto_clinica;
      $TotalMedico+=$rec->monto_medico;
      $informeCierreCaja->setX(20);
      $informeCierreCaja->SetTextColor(0,0,0);
      $informeCierreCaja->setFont("Times","",10);
      $informeCierreCaja->Cell(58,6,utf8__($rec->numero_recibo),1,0,"L",false);
      $informeCierreCaja->Cell(30,6,utf8__($rec->monto_pago),1,0,"C",false);
      $informeCierreCaja->Cell(32,6,utf8__($rec->monto_clinica!= null ? $rec->monto_clinica : 0.00),1,0,"C",false);
      $informeCierreCaja->Cell(47,6,($rec->monto_medico != null ? $rec->monto_medico : 0.00),1,1,"C",false);
     }
      
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFillColor(65, 105, 225);
      $informeCierreCaja->SetTextColor(248, 248, 255);
      $informeCierreCaja->SetFont("Times","B",10);
      $informeCierreCaja->Cell(58,"6",utf8__("Total: "),1,0,"L",true);
      $informeCierreCaja->SetFont("Times","B",12);
       $informeCierreCaja->Cell(30,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".(number_format($TotalImporte,2,',')),1,0,"C",true);
      $informeCierreCaja->Cell(32,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".(number_format($TotalClinica,2,',')),1,0,"C",true);
      $informeCierreCaja->Cell(47,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".(number_format($TotalMedico,2,',')),1,0,"C",true);
    /// manda firma
    
    $informeCierreCaja->Output();
    }else{
      PageExtra::PageNoAutorizado();
    }  
  }

  /// modificar la apertura de caja
  public static function update(int $id)
  {
    self::NoAuth();
    if( self::profile()->rol === self::$profile[1]    ||self::profile()->rol === 'Director'){
      if(self::ValidateToken(self::post("_token")))
      {
       $modelCajaApertura = new Caja;

       if(self::profile()->rol === 'Director' || self::profile()->rol === self::$profile[1])
       {
        /// calculamos el saldo final 
        
        $respuesta = $modelCajaApertura->Update([
          "id_apertura_caja" => $id,
          "saldo_inicial_clinica" => self::post("monto_apertura_editar"),
         ]);
        
       } 
       self::json(["response" =>$respuesta]);
      }else{
        self::json(["response" => "token-invalidate"]);
      }
    }else{
      self::json(["response" => "no-authorized"],403);
    }
  }

  // update apertura de caja , si ya existe 
  public static function updateExists(int $id)
  {
    self::NoAuth();
    if(self::profile()->rol === self::$profile[1]  || self::profile()->rol === 'Director' || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === 'Farmacia'){
      if(self::ValidateToken(self::post("_token")))
      {
       $modelCajaApertura = new Caja;
 
       if(self::profile()->rol === 'Director' || self::profile()->rol === self::$profile[1])
       {
        /// calculamos el saldo final 
        $respuesta = $modelCajaApertura->Update([
          "id_apertura_caja" => $id,
          "fecha_apertura_clinica" => self::FechaActual("Y-m-d H:i:s"),
          "saldo_inicial_clinica" => self::post("monto_apertura_far"),
          "estado_clinica" => "a"
         ]);
        
       }else{
        if(self::profile()->rol === 'Farmacia' || self::profile()->rol === 'admin_farmacia')
        {
          $respuesta = $modelCajaApertura->Update([
            "id_apertura_caja" => $id,
            "fecha_apertura_farmacia" => self::FechaActual("Y-m-d H:i:s"),
            "saldo_inicial_farmacia" => self::post("monto_apertura_far"),
            "estado_farmacia" => "a"
           ]);
        } 
      }
       self::json(["response" =>$respuesta]);
      }else{
        self::json(["response" => "token-invalidate"]);
      }
    }else{
      self::json(["response" => "no-authorized"],403);
    }
  }
  /// eliminar caja aperturada
  public static function delete(int $id)
  {
    self::NoAuth();
    if(self::profile()->rol === self::$profile[1]  || self::profile()->rol === 'admin_general' ||self::profile()->rol === 'Director' || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === 'Farmacia'){
      if(self::ValidateToken(self::post("_token")))
      {
       $modelCajaApertura = new Caja;

       $respuesta = $modelCajaApertura->delete($id);

       self::json(["response" =>$respuesta]);
      }else{
        self::json(["response" => "token-invalidate"]);
      }
    }else{
      self::json(["response" => "no-authorized"],403);
    }
  }

  /// confirmamos la caja de cierre
  public static function confirmarCierreCaja()
  {
    self::NoAuth();
    if(self::profile()-> rol === 'admin_general' || self::profile()->rol === 'Director' ||  self::profile()->rol === 'admin_farmacia')
    {
     return self::View_("caja.confirmar_caja_cierre");
    }else{
      PageExtra::PageNoAutorizado();
    }
  }

  /// confirmar el cierre de caja por completo

  public static function cerrarConfirmCaja(int $id)
  {
    self::NoAuth();

    if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === self::$profile[1]  || self::profile()->rol === self::$profile[5] )
    {
      if(self::ValidateToken(self::post("_token")))
      {
        $modelCaja = new Caja;
        $modelEgreso = new CategoriaEgreso;

        if (self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[5] || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === self::$profile[1]) {
          $sede = self::profile()->sede_id;
          $dataEgreso = $modelEgreso->query()->Join("subcategorias_egreso as se", "se.categoriaegreso_id", "=", "ce.id_categoria_egreso")
            ->Where("ce.fecha_categoria", "=", self::FechaActual("Y-m-d"))
            ->And("sede_id","=",$sede)
            ->select("sum(se.valor_gasto) as gasto")
            ->first();
        }  
        $response = $modelCaja->Update([
          "id_apertura_caja" => $id,
          "fecha_cierre" => self::FechaActual("Y-m-d H:i:s"),
          "total_egreso" => $dataEgreso->gasto,
          "estado_caja" => "c"
        ]);

        self::json(["response" => $response]);
      }else{
        self::json(["response" => "token-invalidate"]);
      }
    }else{
      self::json(["response" => "no-authorized"]);
    }
    
  }

  /// ver reporte de caja
  public static function ReporteCajaPorFechas()
  {
    self::NoAuth();
    if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === 'admin_general')
    {
     $reporte = new PdfResultados();
     $reporte->setDirSede(self::profile()->sede_id!= null?(self::profile()->direccion_sede != null ? utf8__(self::profile()->name_departamento." - ".self::profile()->name_provincia." - ".self::profile()->name_distrito." - ".self::profile()->direccion_sede) : 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'):self::BusinesData()[0]->direccion);
     $reporte->SetTitle("Reporte-historial-de-caja");
     $reporte->AddPage();

     /// Indicamos el tipo de letra
     $reporte->setFont("Times","B",16);
     $reporte->Cell(200,3,"Historial de caja",0,1,"C");
     $reporte->Cell(200,3,"______________________",0,1,"C");
     

     /// consultamos
     $modelCajaRepo = new Caja;

     $reporte->Ln(10);
     $reporte->setX(21);
     if(isset($_GET['fi']) and isset($_GET['ff']))
     {

      $FechaI = self::get("fi"); $FechaF = self::get("ff");

      $FechaI = explode("-",$FechaI); $FechaF = explode("-",$FechaF);
      $rol =self::profile()->rol; $sede = self::profile()->sede_id;

      $resultados = $modelCajaRepo->procedure("proc_reporte_caja_fechas","c",[4,self::get("fi"),self::get("ff"),"rango_fechas",$rol,$sede]);
      
       //if(!$resultados){PageExtra::PageNoAutorizado();exit;}

      $reporte->setFont("Times","B",12);
      $reporte->SetDrawColor(119, 136, 153);
      $reporte->Cell(20,10,"Desde",1,0,"L" );
      $reporte->setFont("Times","",12);
      $reporte->Cell(60,10," ".$FechaI[2]."/".$FechaI[1]."/".$FechaI[0],1,0,"L");

      $reporte->setFont("Times","B",12);
      $reporte->Cell(20,10,"Hasta",1,0,"L" );
      $reporte->setFont("Times","",12);
      $reporte->Cell(60,10," ".$FechaF[2]."/".$FechaF[1]."/".$FechaF[0],1,1,"L");


     }
     else{
       if(isset($_GET['select_tiempo']))
       {
        $rol =self::profile()->rol; $sede = self::profile()->sede_id;
        $resultados = $modelCajaRepo->procedure("proc_reporte_caja_fechas","c",[self::get("select_tiempo"),"2024-03-03","2024-03-03","mes",$rol,$sede]);
      
        //if(!$resultados){PageExtra::PageNoAutorizado();exit;}
        $reporte->setFont("Times","B",12);
        $reporte->SetDrawColor(119, 136, 153);
        $reporte->Cell(20,10,"Mes",1,0,"L");
        $reporte->setFont("Times","",12);
        $reporte->Cell(140,10," ".(strtoupper(self::getMonthName(self::get("select_tiempo"))))." DEL ".self::FechaActual("Y"),1,1,"L");
       }else{
        PageExtra::PageNoAutorizado();
        exit;
       }
     }

     $reporte->setFont("Times","B",12);
     $reporte->SetFillColor(220, 20, 60);
     $reporte->SetDrawColor(128, 128, 128);
     $reporte->SetTextColor(240, 255, 255);
     $reporte->setX(21);
     $reporte->Cell(55,10,"Fecha de apertura",1,0,"C",true);
     $reporte->Cell(55,10,"Fecha de cierre",1,0,"C",true);
     $reporte->Cell(50,10,"Total en caja ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.'),1,1,"C",true);

     /// body del reporte
     $TotalSaldo = 0.00;
     
      $reporte->setFont("Times","",12);  
      $reporte->SetTextColor(0,0,0);
      foreach ($resultados as $key => $res) {
       $reporte->setX(21);
       $reporte->Cell(55,7,$res->fechaapertura,1,0,"C");
       $reporte->Cell(55,7,$res->fechacierre,1,0,"C");
       $reporte->Cell(50,7,$res->cajatotal,1,1,"C");
       
       $TotalSaldo+= $res->cajatotal;
      }
     

     /// footer

     $reporte->setFont("Times","B",12);
     $reporte->SetFillColor(65, 105, 225);
     $reporte->SetTextColor(240, 255, 255);
     $reporte->setX(21);
     $reporte->Cell(110,7,"Total en Caja ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.'),1,0,"L",true);
     $reporte->Cell(50,7,number_format($TotalSaldo,2,","," "),1,0,"C",true);


     $reporte->Output();
    }else{
      PageExtra::PageNoAutorizado();
    }
  }
}