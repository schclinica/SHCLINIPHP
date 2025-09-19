<?php
namespace Http\controllers;

use lib\BaseController;
use lib\Email;
use models\Caja;
use models\CajaFarmacia;
use models\Movimiento;
use Ramsey\Uuid\Uuid;

class MovimientoController extends BaseController{
 use Email;
 /**REGISTRAR MOVIMIENTO  */
 public static function store($tipo){
    self::NoAuth();
    if(self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1]){
        if(self::ValidateToken(self::post("token_"))){
            $movimiento = new Movimiento;$caja = new Caja;
            $sede = self::profile()->sede_id;
            $DataCaja = $caja->query()
                      ->Where("sede_id","=",$sede)
                      ->And("estado_clinica","=","a")
                      ->limit(1)->first();
            $Code = Uuid::uuid4();
            $usuario = self::profile()->id_usuario;

            /// Verificamos la existencia de un movimiento en una caja aperturada 
            $movimientoExiste = $movimiento->query()
                                ->Where("descripcion_movimiento","=",self::post("desc_mov"))
                                ->And("caja_id","=",$DataCaja->id_apertura_caja)
                                ->And("tipo_movimiento","=",$tipo)
                                ->And("tipo","=","c")
                                ->And("sede_id","=",$sede)
                                ->first();

            $TipoMov = ($tipo === 'g' ? 'GASTO':($tipo==='p' ? 'PRÉSTAMO':'DEPÓSITO'));

            if($movimientoExiste){
                self::json(["existe" => "EL MOVIMIENTO ".self::post("desc_mov")." DE TIPO ".$TipoMov." YA ESTA REGISTRADO PARA ESTA APERTURA!!"]);
                exit;
            }
            $response = $movimiento->Insert([
                "id_movimiento" => $Code->toString(),"descripcion_movimiento" => self::post("desc_mov"),
                "tipo_movimiento" => $tipo,"monto_movimiento" => self::post("monto"),
                "caja_id" => $DataCaja->id_apertura_caja,"sede_id" => $sede,
                "usuario_id" => $usuario,"fecha_registro" => self::post("fecha"),"tipo"=>"c"
            ]);
            if($response){
                self::json(["success" => "EL ".$TipoMov." A SIDO REGISTRADO CORRECTAMENTE!!!"]);
            }else{
                self::json(["error" => "ERROR AL REGISTRAR EL ".$TipoMov]);
            }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }else{
        self::json(["error" => "NO TIENES PERMISOS PARA EJECUTAR ESTA TAREA!!"]);
    }
 }
 /** VER LOS MOVIMIENTOS DE LA CLINICA */
 public static function showMovimientosClinica($tipo){
    self::NoAuth();
    if(self::profile()->rol === self::$profile[1]){
        $movimiento = new Movimiento; $caja = new Caja;
         $sede = self::profile()->sede_id;
         $DataCaja = $caja->query()
                      ->Where("sede_id","=",$sede)
                      ->And("estado_clinica","=","a")
                      ->limit(1)->first();
        if($DataCaja){
            $movimientos = $movimiento->query()->Where("caja_id","=",$DataCaja->id_apertura_caja)
                                  ->And("sede_id","=",$sede)
                                  ->And("tipo_movimiento","=",$tipo)
                                  ->get();
         self::json(["movimientos" => $movimientos]);
        }else{
            self::json(["movimientos"=>[]]);
        }
    }else{
        self::json(["movimientos" => []]);
    }
 }
 /// eliminar movimiento
 public static function eliminarmovimiento($id){
   self::NoAuth();
        if (self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1]) {
            if (self::ValidateToken(self::post("token_"))) {
                $mov = new Movimiento;
                $response = $mov->delete($id);
                if ($response) {
                    self::json(["success" => "MOVIMIENTO ELIMINADO !!!"]);
                } else {
                    self::json(["error" => "ERROR AL ELIMINAR MOVIMIENTO!!!"]);
                }
            } else {
                self::json(["ERROR, TOKEN INCORRECTO!!"]);
            }
   }else{
    self::json(["error" => "ERROR AL ELIMINAR MOVIMIENTO!!!"]);
   }
 }

 /** REGISTRAR LOS MOVIMIENTOS DE LA FARMACIA(GASTO, PRESTAMOS OH DEPOSITOS) */
  public static function storeCajaFarmacia($tipo){
    self::NoAuth();
    if(self::profile()->rol === "Farmacia" || self::profile()->rol === "admin_farmacia"){
        if(self::ValidateToken(self::post("token_"))){
            $movimiento = new Movimiento;$caja = new CajaFarmacia;
            $sede = self::profile()->sede_id;
             $DataCaja = $caja->query()
                      ->Where("sede_id","=",$sede)
                      ->And("estado_caja","=","a")
                      ->limit(1)->first();
            if($DataCaja){
                    $Code = Uuid::uuid4();
                    $usuario = self::profile()->id_usuario;
                    $response = $movimiento->Insert([
                        "id_movimiento" => $Code->toString(),
                        "descripcion_movimiento" => self::post("desc_mov"),
                        "tipo_movimiento" => $tipo,
                        "monto_movimiento" => self::post("monto"),
                        "caja_farmacia_id" => $DataCaja->id_caja,
                        "sede_id" => $sede,
                        "usuario_id" => $usuario,
                        "fecha_registro" => self::post("fecha"),
                        "tipo" => "f"
                    ]);

                    $TipoMov = ($tipo === 'g' ? 'GASTO' : ($tipo === 'p' ? 'PRÉSTAMO' : 'DEPÓSITO'));

                    if ($response) {
                        self::json(["success" => "EL " . $TipoMov . " A SIDO REGISTRADO CORRECTAMENTE!!!"]);
                         self::ActualizarCajaFarmacia($DataCaja->id_caja,$caja,$movimiento);
                    } else {
                        self::json(["error" => "ERROR AL REGISTRAR EL " . $TipoMov]);
                    }
            }else{
                self::json(["error" => "ERROR, NO EXISTE UNA CAJA APERTURADO!!!"]);
            }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }else{
        self::json(["error" => "NO TIENES PERMISOS PARA EJECUTAR ESTA TAREA!!"]);
    }
 }

 private static function ActualizarCajaFarmacia($Id,$caja,$movimiento){
   
    $GastoTotal = $movimiento
    ->query()
    ->select("if(sum(monto_movimiento) is null,0.00,sum(monto_movimiento)) as gasto")
    ->Where("caja_farmacia_id","=",$Id)
    ->And("tipo_movimiento","=","g")
    ->And("tipo","=","f")
    ->get();

    $PrestamoTotal = $movimiento
    ->query()
    ->select("if(sum(monto_movimiento) is null,0.00,sum(monto_movimiento)) as prestamo")
    ->Where("caja_farmacia_id","=",$Id)
    ->And("tipo_movimiento","=","p")
    ->And("tipo","=","f")
    ->get();

    $DepositoTotal = $movimiento
    ->query()
    ->select("if(sum(monto_movimiento) is null,0.00,sum(monto_movimiento)) as deposito")
    ->Where("caja_farmacia_id","=",$Id)
    ->And("tipo_movimiento","=","d")
    ->And("tipo","=","f")
    ->get();
   $caja->Update([
     "id_caja" => $Id,
     "total_gastos" => $GastoTotal[0]->gasto,
     "total_prestamos" => $PrestamoTotal[0]->prestamo,
     "total_depositos" => $DepositoTotal[0]->deposito 
   ]);
 }
 
  
}
