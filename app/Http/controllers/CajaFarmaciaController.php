<?php
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use lib\PlantillaInformeCierreCaja;
use models\Caja;
use models\CajaFarmacia;
use models\Movimiento;
use models\VentasFarmacia;
use Ramsey\Uuid\Uuid;

class CajaFarmaciaController extends BaseController{
 /**MOSTRAR LA VISTA DE MOSTRAR EL LISTADO DE LAS CAJAS APERTURADOS */  
public static function index(){
    self::NoAuth();
    if(self::profile()->rol === 'Farmacia' || self::profile()->rol === 'admin_farmacia'){
        $caja = new CajaFarmacia;
        $sede = self::profile()->sede_id;
        $cajaAbierta = $caja->query()->Where("sede_id","=",$sede)->And("estado_caja","=","a")->limit(1)->first();
        self::View_("farmacia.caja",compact("cajaAbierta"));
    }else{
        PageExtra::PageNoAutorizado();
    }
}

/**MOSTRAR LA CAJA ABIERTA */
public static function showCajaAbierta(){
    self::NoAuth();
    if(self::profile()->rol === 'Farmacia' || self::profile()->rol === "admin_farmacia"){
        $sede = self::profile()->sede_id; $caja = new CajaFarmacia;
        $cajaAbierta = $caja->query()->Where("sede_id","=",$sede)->And("estado_caja","=","a")->limit(1)->first();
        self::json(["caja" => $cajaAbierta]);
    }else{
        self::json(["error" => "NO ESTAS AUTORIZADO PARA VER LA CAJA APERTURADA!!!"]);
    }
}
 /** VERIFICAR SI HAY UNA CAJA APERTURADA PARA LA FARMACIA*/
 public static function VerificarCajaAperturadaFarmacia(){
  self::NoAuth();
  $caja = new CajaFarmacia;
  if(self::profile()->rol === 'Farmacia' || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === "admin_general"){
    $sede = self::profile()->sede_id;
    $CajaApertura = $caja->query()->Where("sede_id","=",$sede)
    ->And("estado_caja","=","a")
    ->get();
  }else{
    $CajaApertura = [];
  }
  self::json(["caja" => $CajaApertura]);
 }
 /**APERTURAR CAJA */
    public static function store()
    {
        self::NoAuth();
        if (self::profile()->rol === 'Farmacia' || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === "admin_general") {
            if (self::ValidateToken(self::post("token_"))) {
                $caja = new CajaFarmacia;
                $sede = self::profile()->sede_id != null ? self::profile()->sede_id : self::post("sede");
                /// validar
                $CajaAbierta = $caja->query()->Where("sede_id", "=", $sede)->And("estado_caja", "=", "a")->get();
                $usuario = self::profile()->id_usuario;
                if ($CajaAbierta) {
                    self::json(["error" => "YA EXISTE UNA CAJA ABIERTA EN TU SUCURSAL!!!"]);
                    exit;
                }

                $Code = Uuid::uuid4();
                $response = $caja->Insert([
                    "id_caja" => $Code->toString(),
                    "saldo_inicial" => self::post("saldo_inicial"),
                    "fecha_apertura" => self::FechaActual("Y-m-d H:i:s"),
                    "sede_id" => $sede,
                    "usuario_id" => $usuario
                ]);

                if ($response) {
                    self::json(["success" => "LA CAJA A SIDO APERTURADO CORRECTAMENTE!!!"]);
                } else {
                    self::json(["error" => "ERROR AL APERTURAR CAJA!!!"]);
                }
            } else {
                self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
            }
        } else {
            self::json(["error" => "NO TIENES PERMISOS PARA REALIZAR ESTA ACCION!!"]);
        }
    }
 /**ELIMINAR CAJA */
 public static function eliminarCaja(){
    self::NoAuth();
    if(self::profile()->rol === 'Farmacia' || self::profile()->rol === 'admin_farmacia'){
        ///seleccionamos la caja abierta
        $caja = new CajaFarmacia;
        $sede = self::profile()->sede_id;
        $CajaAbierta = $caja->query()->Where("sede_id", "=", $sede)->And("estado_caja", "=", "a")->get();
        if($CajaAbierta){
            $response = $caja->delete($CajaAbierta[0]->id_caja);

            if($response){
                self::json(["success" => "LA CAJA HA SIDO ELIMINADO CORRECTAMENTE!!!"]);
            }else{
                self::json(["error" => "ERROR AL ELIMINAR LA CAJA!!!"]);
            }
        }else{
            self::json(["error" => "ERROR, LA CAJA NO EXISTE!!!"]);
        }
    }else{
        self::json(["error" => "ERROR, NO ESTAS AUTHORIZADO PARA REALIZAR ESTA TAREA!!!"]);
    }
 }
 /** CERRAR LA CAJA */
 public static function cerrarLaCaja(){
    self::NoAuth();
    if(self::profile()->rol === 'Farmacia' || self::profile()->rol === 'admin_farmacia'){
        ///seleccionamos la caja abierta
        $caja = new CajaFarmacia;
        $sede = self::profile()->sede_id;
        $CajaAbierta = $caja->query()->Where("sede_id", "=", $sede)->And("estado_caja", "=", "a")->get();
        if($CajaAbierta){
            $TotalSaldoFinal = (($CajaAbierta[0]->saldo_inicial + $CajaAbierta[0]->ingreso_ventas + $CajaAbierta[0]->total_depositos)-($CajaAbierta[0]->total_gastos + $CajaAbierta[0]->total_prestamos));
            $response = $caja->Update([
                "id_caja" => $CajaAbierta[0]->id_caja,
                "fecha_cierre" => self::FechaActual("Y-m-d H:i:s"),
                "saldo_final" => $TotalSaldoFinal,
                "estado_caja" => "c"
            ]);

            if($response){
                self::json(["success" => "LA CAJA HA SIDO CERRADO CORRECTAMENTE!!!"]);
            }else{
                self::json(["error" => "ERROR AL CERRAR LA CAJA!!!"]);
            }
        }else{
            self::json(["error" => "ERROR, LA CAJA QUE DESEAS CERRAR NO EXISTE!!!"]);
        }
    }else{
        self::json(["error" => "ERROR, NO ESTAS AUTHORIZADO PARA REALIZAR ESTA TAREA!!!"]);
    }
 }
 public static function informeCierreCajaFarmacia($id)
  {
    self::NoAuth();

    if( self::profile()->rol === "Farmacia" || self::profile()->rol === "admin_farmacia" || self::profile()->rol === "admin_general"){
       
    
        /// datos del cierre de caja

    $Cajamodel = new CajaFarmacia;
    $sede = self::profile()->sede_id;
    if(self::profile()->rol === "Farmacia" || self::profile()->rol === "admin_farmacia"){
        //$cajaId = $Cajamodel->query()->Where("");
        $dataCaja = $Cajamodel->query()
        ->Join("usuario as u","cf.usuario_id","=","u.id_usuario")
        ->Join("sedes as s","cf.sede_id","=","s.id_sede")
        ->Where("cf.id_caja", "=", $id)
        ->And("cf.sede_id","=",$sede)
        ->first();
        
        unset($dataCaja->pasword);
    }else{
       $dataCaja = $Cajamodel->query()
        ->Join("usuario as u","cf.usuario_id","=","u.id_usuario")
        ->Join("sedes as s","cf.sede_id","=","s.id_sede")
        ->Where("cf.id_caja", "=", $id)
        ->first();
        
        unset($dataCaja->pasword);
  
    }
     $informeCierreCaja = new PlantillaInformeCierreCaja();
     $informeCierreCaja->setDireccionSede($dataCaja->namesede);

     $informeCierreCaja->setTitleDoc("INFORME DE CIERRE DE CAJA- FARMACIA");
     $informeCierreCaja->SetTitle("Informe de cierre de caja");

     $informeCierreCaja->AddPage();
     $informeCierreCaja->SetAutoPageBreak(true,60);

    $informeCierreCaja->setUsuarioInforme($dataCaja->name);

    if(!$dataCaja){PageExtra::PageNoAutorizado();}
    $SaldoInit =  $dataCaja->saldo_inicial;
    $SaldoFin =   ($dataCaja->ingreso_ventas + $dataCaja->saldo_inicial + $dataCaja->total_depositos) - ($dataCaja->total_gastos + $dataCaja->total_prestamos);
   
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
    $informeCierreCaja->Cell(67,"6",$dataCaja->fecha_apertura,1,1,"R");

     $informeCierreCaja->SetDrawColor(0, 0, 128);
    $informeCierreCaja->SetX(20);
    $informeCierreCaja->SetFont("Times","B",12);
    $informeCierreCaja->Cell(100,"6","Fecha cierre ",1,0,"L");
    $informeCierreCaja->SetFont("Times","",12);
    $informeCierreCaja->Cell(67,"6",($dataCaja->fecha_cierre != null ? $dataCaja->fecha_cierre:''),1,1,"R");

    $informeCierreCaja->SetDrawColor(0, 0, 128);
    $informeCierreCaja->SetX(20);
    $informeCierreCaja->SetFont("Times","B",12);
    $informeCierreCaja->Cell(100,"6","Estado De La Caja ",1,0,"L");
    $informeCierreCaja->SetFont("Times","",12);
    $informeCierreCaja->Cell(67,"6",($dataCaja->estado_caja === 'c' ? 'Cerrado' : 'Abierto'),1,1,"R");

    $informeCierreCaja->SetDrawColor(0, 0, 128);
    $informeCierreCaja->SetX(20);
    $informeCierreCaja->SetFont("Times","B",12);
    $informeCierreCaja->Cell(100,"6","Saldo inicial  entregado ",1,0,"L");
    $informeCierreCaja->SetFont("Times","",12);
    $informeCierreCaja->Cell(67,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".$SaldoInit,1,1,"R");

    
      $informeCierreCaja->SetDrawColor(0, 0, 128);
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFont("Times","B",12);
      $informeCierreCaja->Cell(100,"6",utf8__("Ingreso de la farmacia ") ,1,0,"L");
      $informeCierreCaja->SetFont("Times","",12);
      $informeCierreCaja->Cell(67,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".$dataCaja->ingreso_ventas,1,1,"R");
      
      $informeCierreCaja->SetDrawColor(0, 0, 128);
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFont("Times","B",12);
      $informeCierreCaja->Cell(100,"6",utf8__("Total en Gastos: ") ,1,0,"L");
      $informeCierreCaja->SetFont("Times","",12);
      $informeCierreCaja->Cell(67,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".($dataCaja->total_gastos),1,1,"R");
 
      $informeCierreCaja->SetDrawColor(0, 0, 128);
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFont("Times","B",12);
      $informeCierreCaja->Cell(100,"6",utf8__("Total en Préstamos: ") ,1,0,"L");
      $informeCierreCaja->SetFont("Times","",12);
      $informeCierreCaja->Cell(67,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".$dataCaja->total_prestamos,1,1,"R");
 
      $informeCierreCaja->SetDrawColor(0, 0, 128);
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFont("Times","B",12);
      $informeCierreCaja->Cell(100,"6",utf8__("Total en Depósitos: ") ,1,0,"L");
      $informeCierreCaja->SetFont("Times","",12);
      $informeCierreCaja->Cell(67,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".$dataCaja->total_depositos,1,1,"R");
 
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
      if(self::profile()->rol === "Farmacia" || self::profile()->rol === "admin_farmacia"){
        $detalleGastos = $gasto->query()
      ->Join("caja_farmacia as cf","mov.caja_farmacia_id","=","cf.id_caja")
      ->Where("mov.sede_id","=",$sede)
      ->And("mov.caja_farmacia_id","=",$id)
      ->And("mov.tipo","=","f")
      ->InWhere("tipo_movimiento",["'g','p','d'"])
      ->get();
      }else{
        $detalleGastos = $gasto->query()
      ->Join("caja_farmacia as cf","mov.caja_farmacia_id","=","cf.id_caja")
      ->Where("mov.caja_farmacia_id","=",$id)
      ->And("mov.tipo","=","f")
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
    
    //  / DETALLE DE INGRESOS DE LA CLINICA
      $informeCierreCaja->Ln(2);
      $informeCierreCaja->setX(20);
      $informeCierreCaja->Cell(167,10,utf8__("Ingresos de la Farmacia"),1,1,"L",true);
      $informeCierreCaja->setX(20);
      $informeCierreCaja->SetTextColor(0,0,0);
      $informeCierreCaja->setFont("Times","B",10);
      $informeCierreCaja->Cell(97,8,utf8__("N° VENTA"),1,0,"L",false);
      $informeCierreCaja->Cell(35,8,utf8__("FECHA ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')),1,0,"C",false);
      $informeCierreCaja->Cell(35,8,utf8__("MONTO TOTAL ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')),1,1,"C",false);
     $recibo = new VentasFarmacia;
      if(self::profile()->rol === "Farmacia" || self::profile()->rol === "admin_farmacia"){
        $reciboDetalle = $recibo->query()
     ->Where("vf.caja_id","=",$id)
     ->And("vf.sede_id","=",$sede)
     ->get();
      }else{
        $reciboDetalle = $recibo->query()
     ->Where("vf.caja_id","=",$id)
     ->get();
      }

      $TotalImporte = 0.00; 
     foreach($reciboDetalle as $rec){
      $TotalImporte+=$rec->total_venta;
      $informeCierreCaja->setX(20);
      $informeCierreCaja->SetTextColor(0,0,0);
      $informeCierreCaja->setFont("Times","",10);
      $informeCierreCaja->Cell(97,6,utf8__($rec->num_venta),1,0,"L",false);
      $informeCierreCaja->Cell(35,6,utf8__($rec->fecha_emision),1,0,"C",false);
      $informeCierreCaja->Cell(35,6,utf8__($rec->total_venta),1,1,"C",false);
     }
      
      $informeCierreCaja->SetX(20);
      $informeCierreCaja->SetFillColor(65, 105, 225);
      $informeCierreCaja->SetTextColor(248, 248, 255);
      $informeCierreCaja->SetFont("Times","B",10);
      $informeCierreCaja->Cell(132,"6",utf8__("Total: "),1,0,"L",true);
      $informeCierreCaja->SetFont("Times","B",12);

      $informeCierreCaja->Cell(35,"6",(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda : 'S/.')." ".(number_format($TotalImporte,2,',')),1,1,"C",true);
       
    /// manda firma
    
    $informeCierreCaja->Output();
    }else{
      PageExtra::PageNoAutorizado();
    }   
  }

  /// VER EL RESUMEN DE CAJA 
  public static function ResumenCajaView(){
    self::NoAuth();
    if(self::profile()->rol === 'Farmacia' || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === 'admin_general'){
        $caja = new CajaFarmacia;
        if(self::profile()->rol === "Farmacia"){
             $sede = self::profile()->sede_id;
             $usuario = self::profile()->id_usuario;
             $DetalleCaja = $caja->query()
             ->Join("usuario as u","cf.usuario_id","=","u.id_usuario")
             ->Join("sedes as s","cf.sede_id","=","s.id_sede")
             ->Where("cf.sede_id","=",$sede)
             ->And("cf.usuario_id","=",$usuario)
             ->orderBy("cf.fecha_apertura","asc")
             ->get();
        }else{
            if(self::profile()->rol === "admin_farmacia"){
             $sede = self::profile()->sede_id;
             $DetalleCaja = $caja->query()
             ->Join("usuario as u","cf.usuario_id","=","u.id_usuario")
             ->Join("sedes as s","cf.sede_id","=","s.id_sede")
             ->Where("cf.sede_id","=",$sede)
             ->orderBy("cf.fecha_apertura","asc")
             ->get();
            }else{
             $DetalleCaja = $caja->query()
             ->Join("usuario as u","cf.usuario_id","=","u.id_usuario")
             ->Join("sedes as s","cf.sede_id","=","s.id_sede")
             ->orderBy("cf.fecha_apertura","asc")
             ->get();
            }
        }
        self::View_("farmacia.resumencaja",compact("DetalleCaja"));
    }else{
        PageExtra::PageNoAutorizado();
    }
  }
}