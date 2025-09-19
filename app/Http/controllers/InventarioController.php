<?php
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\Lote;
use models\ProductoFarmacia;
use models\Sede;

class InventarioController extends BaseController
{
    private static $Errors =[];
    /** VER LA VIATA DE GESTION DE INVENTARIOS */
    public static function index(){

        self::NoAuth();

        if(self::profile()->rol === "admin_general" || self::profile()->rol === "admin_farmacia"){

            $sede = new Sede;
            $sedes = $sede->query()->Where("deleted_at","is",null)->get();
            self::View_("farmacia.inventario",compact("sedes"));
        }else{
            PageExtra::PageNoAutorizado();
        }
    }

    // MOSTRAR LOS PRODUCTOS POR SUCURSAL
    public static function showProductosSucursal(){
        self::NoAuth();
        $producto = new ProductoFarmacia;
        if(self::profile()->rol === "admin_general" || self::profile()->rol === "admin_farmacia"){
            $sede = self::profile()->sede_id != null ? self::profile()->sede_id : self::get("sede");
            $productos = $producto->query()->Join("tipo_producto_farmacia as tpf","prod_far.tipo_id","=","tpf.id_tipo_producto")
       ->Join("presentacion_farmacia as pf","prod_far.presentacion_id","=","pf.id_pesentacion")
       ->Join("laboratorio_farmacia lf","prod_far.laboratorio_id","=","lf.id_laboratorio")
       ->Join("grupo_terapeutico_farmacia as gtf","prod_far.grupo_terapeutico_id","=","gtf.id_grupo_terapeutico")
       ->Join("embalaje_farmacia ef","prod_far.empaque_id","=","ef.id_embalaje")
       ->Join("proveedores_farmacia as prof","prod_far.proveedor_id","=","prof.id_proveedor")
       ->Join("almacen_productos as ap","ap.producto_id","=","prod_far.id_producto")
       ->select("prod_far.id_producto","prod_far.nombre_producto","prod_far.precio_venta",
               "if(sum(ap.stock) is null,0,sum(ap.stock)) as stock","prod_far.stock_minimo","tpf.name_tipo_producto","pf.name_presentacion",
               "lf.name_laboratorio","gtf.name_grupo_terapeutico","ef.name_embalaje","prof.proveedor_name","prod_far.deleted_at_prod",
               "prod_far.code_barra","tpf.id_tipo_producto","pf.id_pesentacion","lf.id_laboratorio",
               "gtf.id_grupo_terapeutico","ef.id_embalaje","prof.id_proveedor","prod_far.lote")

        ->Where("ap.sede_id","=",$sede)
       ->GroupBy(["prod_far.id_producto"])
       ->get();

        self::json(["productos" => $productos]);
        }else{
            self::json(["productos" => []]);
        }
    }

    /// AGREGAR LOTE
    public static function saveLote(){
        self::NoAuth();

        if(self::profile()->rol === "admin_general" || self::profile()->rol === "admin_farmacia"){
            if (self::ValidateToken(self::post("token_"))) {

                if(self::post("codigolote") == null){
                     self::$Errors["codigolote"] = "INGRESE CODIGO LOTE!!";
                }

                if(self::post("existencia") == null){
                    self::$Errors["existencia"] = "INGRESE LA CANTIDAD !!";
                }
                if(count(self::$Errors) > 0){
                    self::json(["errors" => self::$Errors]);
                    exit;
                }
                $lote = new Lote;
                $sede = self::profile()->sede_id != null ? self::profile()->sede_id : self::post("sede");
                /// verificamos si existe el codigo del lote en una sucursal
                $loteExiste = $lote->query()->Where("codigo_lote", "=", trim(self::post("codigolote")))
                    ->And("sede_id", "=", $sede)->first();

                if ($loteExiste) {
                    self::json(["existe" => "EL CODIGO DE LOTE QUE DESEAS REGISTRAR YA EXISTE!!!"]);
                    exit;
                }

                $response = $lote->Insert([
                    "codigo_lote" => self::post("codigolote"),
                    "fecha_produccion" => self::post("fechaprod"),
                    "fecha_vencimiento" => self::post("fechaven"),
                    "cantidadlote" => self::post("existencia"),
                    "producto_id" => self::post("producto"),
                    "sede_id" => $sede
                ]);

                if ($response) {
                    self::json(["success" => "LOTE " . self::post("codigolote") . " AGREGADO CORRECTAMENTE PARA EL PRODUCTO!!!"]);
                } else {
                    self::json(["error" => "ERROR AL AGREGAR LOTE!!"]);
                }
           }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
           }
        }else
        {
            self::json(["error" => "NO TIENES PERMISOS PARA REALIZAR ESTA TAREA!!!"]);
        }
    }

    /// OBTENER LA CANTIDAD DE LOTE POR PRODUCTO
    public static function TotalLoteProducto($producto){
        self::NoAuth();
        $lote = new Lote;
        $sede = self::profile()->sede_id != null ? self::profile()->sede_id : self::get("sede");
        self::json(["cantidad" => $lote->query()->select("if(sum(cantidadlote) is null,0,sum(cantidadlote)) as cantidad")->Where("producto_id","=",$producto)->And("sede_id","=",$sede)->first()->cantidad]);
    }

    /// mostrar los lotes de un producto con respecto a un producto y sede
    public static function showLotesProducto(){
        self::NoAuth();
        if(self::profile()->rol === "admin_general" || self::profile()->rol === "admin_farmacia"){
            $lote = new Lote;$FechaActual = self::FechaActual("Y-m-d");
            $producto = self::get("producto");
            $sede = self::profile()->sede_id != null ? self::profile()->sede_id:self::get("sede");
            $lotes = $lote->query()->Join("productos_farmacia as pf","l.producto_id","=","pf.id_producto")
            ->Join("sedes as s","l.sede_id","=","s.id_sede")
            ->select("codigo_lote","l.fecha_produccion","l.fecha_vencimiento","cantidadlote","producto_id")
            ->Where("l.producto_id","=",$producto)
            ->And("l.sede_id","=",$sede)
            ->And("l.cantidadlote",">",0)
            ->orderBy("l.fecha_vencimiento","asc")
            ->get();
            self::json(["lotes" => $lotes]);
        }else{
            self::json(["lotes" =>[]]);
        }
    }

    /** ELIMINAR EL LOTE DE UN PRPDUCTO */
    public static function eliminarLoteProducto($codigo,$producto){
        self::NoAuth();

        if(self::profile()->rol === "admin_general" || self::profile()->rol === "admin_farmacia")
        {
            if(self::ValidateToken(self::post("token_"))){
                $lote = new Lote;

                $sede = self::profile()->sede_id != null ? self::profile()->sede_id : self::post("sede");
                $response = $lote->procedure("proc_lotes","d",[1,$codigo,$producto,$sede,0]);
                if($response){
                    self::json(["success" => "LOTE ".$codigo." ELIMINADO CORRECTAMENTE!!!"]);
                }else{
                    self::json(["error" => "ERROR AL ELIMINAR LOTE"]);
                }
            }else{
                self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
            }
        }else{
            self::json(["error" => "NO ESTAS AUTORIZADO PARA REALIZAR ESTA ACCIÓN!!!"]);
        }
    }
}