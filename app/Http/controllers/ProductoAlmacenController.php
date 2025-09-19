<?php
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\AlmacenProducto;
use models\Presentacion;
use models\ProductoPrecio;
use models\Sede;

class ProductoAlmacenController extends BaseController 
{
    /**
     * ASIGNAR AL PRODUCTO EN QUE ALMACEN SE VENDERA
     */
    public static function asignar($id){
        self::NoAuth();
        if(self::profile()->rol === "admin_general"){
             if(self::ValidateToken(self::post("token_"))){
                self::ProcesoAsignarAlmacenesProducto($id);
             }else{
                self::json(["error" => "ERROR , TOKEN INCORRECTO!!"]);
             }
        }else{
            self::json(["error" => "NO TIENES PERMISOS PARA REALIZAR ESTA ACCION!!!"]);
        }
    }

    /// QUITAR LOS ALMACENES AGREGADOS POR DEFECTO
    public static function QuitarTodoAlmacenes(){
        self::NoAuth();
         if(self::profile()->rol === "admin_general"){
             if(self::ValidateToken(self::post("token_"))){
                 if(self::ExistSession("almacenproducto")){
                    self::destroySession("almacenproducto");
                    //self::json(["response" => "ALMACENES QUITADOS!!"]);
                 } 
             }else{
                self::json(["error" => "ERROR , TOKEN INCORRECTO!!"]);
             }
        }else{
            self::json(["error" => "NO TIENES PERMISOS PARA REALIZAR ESTA ACCION!!!"]);
        }
    }
    /** PROCESO DE ASIGNAR LOS ALMACENES AL PRODUCTO */
    private static function ProcesoAsignarAlmacenesProducto($id){
        $sede = new Sede;

        if(!isset($_SESSION["almacenproducto"])){
            $_SESSION["almacenproducto"] = [];
        }

        $sedeData = $sede->procedure("proc_consultas","C",[$id,4]);
        
        foreach($sedeData as $almacen){
            /// validamos
            if (!array_key_exists($almacen->id_sede,$_SESSION["almacenproducto"])) {
                $_SESSION["almacenproducto"][$almacen->id_sede]["id"] = $almacen->id_sede;
                $_SESSION["almacenproducto"][$almacen->id_sede]["almacen"] = $almacen->namesede;
                $_SESSION["almacenproducto"][$almacen->id_sede]["stock"] = 0;
            }
        }
        //self::json(["response" => "almacenes agregados!!"]);
    }

    /// proceso de guardado
    public static function saveAlmacenesProducto(){
        self::NoAuth();
        $Respuesta = true;
         if(self::profile()->rol === "admin_general"){
             if(self::ValidateToken(self::post("token_"))){
                 if(self::ExistSession("almacenproducto") && count(self::getSession("almacenproducto")) > 0){
                    $productoAlmacen = new AlmacenProducto;
                     foreach(self::getSession("almacenproducto") as $alm){
                             $productoAlmacen->Insert([
                            "producto_id" => self::post("producto"),
                            "sede_id" => $alm["id"],
                            "stock" => $alm["stock"],
                     ]);
                    }
                    if($Respuesta === "error"){
                        self::json(["error" => "DEBES DE ASIGNAR UNA PRESENTACION POR DEFECTO AL PRODUCTO!!!"]);
                    }else {
                      self::destroySession("almacenproducto");
                      self::json(["response" => "ALMACENES ASIGNADOS AL PRODUCTO CORRECTAMENTE!!!"]);
                    }
                 }else{
                    self::json(["error" => "DEBES DE SELECCIONAR POR LO MENOS UN ALMACEN PARA EL PRODUCTO!!"]);
                 }
             }else{
                self::json(["error" => "ERROR , TOKEN INCORRECTO!!"]);
             }
        }else{
            self::json(["error" => "NO TIENES PERMISOS PARA REALIZAR ESTA ACCION!!!"]);
        }
    }

    /// QUITAR UN ALMACEN 
     public static function QuitarAlmacenLista($id){
        self::NoAuth();
         if(self::profile()->rol === "admin_general"){
             if(self::ValidateToken(self::post("token_"))){
                $sede = new Sede;
                $almacen = $sede->query()->Where("id_sede","=",$id)->get();
                 if(isset($_SESSION["almacenproducto"][$almacen[0]->id_sede])){
                     unset($_SESSION["almacenproducto"][$almacen[0]->id_sede]);
                 } 
             }else{
                self::json(["error" => "ERROR , TOKEN INCORRECTO!!"]);
             }
        }else{
            self::json(["error" => "NO TIENES PERMISOS PARA REALIZAR ESTA ACCION!!!"]);
        }
    }
    

    /// modificar el stock
    public static function ModifyStockProductoAlmacen($id){
        self::NoAuth();
         if(self::profile()->rol === "admin_general"){
             if(self::ValidateToken(self::post("token_"))){
                $sede = new Sede;
                $almacen = $sede->query()->Where("id_sede","=",$id)->get();
                 if(isset($_SESSION["almacenproducto"][$almacen[0]->id_sede])){
                   $_SESSION["almacenproducto"][$almacen[0]->id_sede]["stock"] = self::post("stock");
                   //self::json(["response" => "stock modificado"]);
                 } 
             }else{
                self::json(["error" => "ERROR , TOKEN INCORRECTO!!"]);
             }
        }else{
            self::json(["error" => "NO TIENES PERMISOS PARA REALIZAR ESTA ACCION!!!"]);
        }
    }
    /// Agregar almacen a la lista
     public static function AgregarAlmacenLista($id){
        self::NoAuth();
         if(self::profile()->rol === "admin_general"){
            if (self::ValidateToken(self::post("token_"))) {
                $sede = new Sede;
                $almacen = $sede->query()->Where("id_sede", "=", $id)->get();
                if (!isset($_SESSION["almacenproducto"])) {
                    $_SESSION["almacenproducto"] = [];
                }
                if (!array_key_exists($almacen[0]->id_sede, $_SESSION["almacenproducto"])) {
                    $_SESSION["almacenproducto"][$almacen[0]->id_sede]["id"] = $almacen[0]->id_sede;
                    $_SESSION["almacenproducto"][$almacen[0]->id_sede]["almacen"] = $almacen[0]->namesede;
                    $_SESSION["almacenproducto"][$almacen[0]->id_sede]["stock"] = 0;
                }
            } else {
                self::json(["error" => "ERROR , TOKEN INCORRECTO!!"]);
            }
        }else{
            self::json(["error" => "NO TIENES PERMISOS PARA REALIZAR ESTA ACCION!!!"]);
        }
    }
    /// MOSTRAR LOS ALMACENES QUE AUN NO HAN SIDO ASIGNADO AL PRODCUTO
    public static function showSedesDisponibles($id){
       self::NoAuth();
       if(self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1] || self::profile()->rol === self::$profile[4] || self::profile()->rol === 'admin_general'){
       $sede = new Sede;

       $sedes = $sede->procedure("proc_consultas","C",[$id,4]);

       self::json(["sedes" => $sedes]);
     }else{
        self::json(["sedes"=>[]]);
     }
    }
    /**MOSTRAR LAS PRESENTACIONES DISPONIBLES */
    public static function showPresentacionesDisponibles(){
     self::NoAuth();
     if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === "admin_farmacia"){
       $presentacion = new Presentacion;
       $presentaciones = $presentacion->query()->Where("deleted_at","is",null)->get();

       self::json(["presentaciones" => $presentaciones]);
     }else{
        self::json(["presentaciones"=>[]]);
     }
    }
    /** MOSTRAR LOS PRODUCTOS POR CADA ALMACEN */
    public static function showProductosPorAlmacen(){
        self::NoAuth();
       if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general'){
         $almacenproducto = new AlmacenProducto;
         $productos = $almacenproducto->query()->Join("productos_farmacia as pf","ap.producto_id","=","pf.id_producto")
         ->Join("sedes as s","ap.sede_id","=","s.id_sede")
         ->Join("presentacion_farmacia as prf","pf.presentacion_id","=","prf.id_pesentacion")
         ->select("id_producto_almacen","producto_id","nombre_producto","namesede","pf.precio_venta","ap.stock","prf.name_presentacion","prf.name_corto_presentacion")
         ->Where("ap.producto_id","=",self::get("id"))
         ->get();
         self::json(["productos"=> $productos]);
       }else{
        self::json(["productos"=>[]]);
       }
    }
    /**AGREGAR PRECIOS A LOS PRODUCTOS POR CADA ALMACEN*/
    public static function addPriceProducto($id){
        self::NoAuth();
        
        if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === "admin_farmacia"){
            if(self::ValidateToken(self::post("token_"))){
                $precioproducto = new ProductoPrecio;
                /// verificamos la existencia
                $Existe =  $precioproducto->query()->Where("producto_id","=",$id)
                ->And("presentacion_id","=",self::post("presentacion"))->get();
                if($Existe){
                    self::json(["existe" => "NO SE PERMITE DUPLICIDAD, YA HAS AGREGADO UN PRECIO CON ESA PRESENTACIÓN A ESTE PRODUCTO EN ESTE ALMACEN SELECCIONADO!!"]);
                    return;
                }
                 $response = $precioproducto->Insert([
                "producto_id" => $id,
                "presentacion_id" => self::post("presentacion"),
                "cantidadp" => self::post("cantidad"),
                "preciop" => self::post("precio")
            ]);
            if($response){
                self::json(["response" => "PRECIO AGREGADO CORRECTAMENTE AL PRODUCTO!!!"]);
            }else{
                self::json(["error" => "ERROR AL AGREGAR PRECIO AL PRODUCTO!!!"]);
            }
            }else{
                self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
            }
        }else{
            self::json(["error" => "NO ESTAS AUTHORIZADO PARA REALZIAR ESTA ACCIÓN!!!"]);
        }
    }
    /// MOSTRAR LOS PRECIOS DE LOS PRODUCTOS
    public static function showProductosPrecios(){
        self::NoAuth();
       if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === "admin_farmacia"){
         $productoprecio = new ProductoPrecio;
         $productos = $productoprecio->query()
         ->Join("productos_farmacia as pf","pp.producto_id","=","pf.id_producto")
         ->Join("presentacion_farmacia as prf","pp.presentacion_id","=","prf.id_pesentacion")
         ->select("id_producto_empaque_precio","pp.presentacion_id","producto_id","nombre_producto","pp.preciop","pp.cantidadp","prf.name_presentacion","prf.name_corto_presentacion")
         ->Where("pp.producto_id","=",self::get("id"))
         ->get();
         self::json(["producto_precios"=> $productos]);
       }else{
        self::json(["producto_precios"=>[]]);
       }
    }
   
    /**MODFICAR EL PRECIO Y STOCK DEL PRODCUTO POR ALMACEN */
     public static function ModificarPrecioStockProducto($id){
     self::NoAuth();
     if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general'){
        if(self::ValidateToken(self::post("token_"))){
                $almacenprod = new AlmacenProducto;
                $response = $almacenprod->Update([
                    "id_producto_almacen" => $id,
                    "stock" => intval(self::post("stock"))
                ]);
                if($response){
                    self::json(["response" => "LOS CAMBIOS HAN SIDO GUARDADOS CORRECTAMENTE!!!"]);
                }else{
                    self::json(["error" => "ERROR AL MODIFICAR AL PRODUCTO!!"]);
                }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!"]);
        }
     }else{
        self::json(["error"=>"ERROR, NO TIENE PERMISO PARA REALIZAR ESTA ACCIÓN!!!"]);
     }
    }
    /// MODIFICAR LOS NUEVOS PRECIOS DEL PRODUCTO
    public static function ModificarPreciosProductoPresentacion($id){
     self::NoAuth();
     if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === "admin_farmacia"){
        if(self::ValidateToken(self::post("token_"))){
                $productoPrice = new ProductoPrecio;
                $response = $productoPrice->Update([
                    "id_producto_empaque_precio" => $id,
                    "preciop" => self::post("precio"),
                    "cantidadp" => self::post("stock"),
                    "presentacion_id"=>self::post("presentacion")
                ]);
                if($response){
                    self::json(["response" => "LOS CAMBIOS HAN SIDO GUARDADOS CORRECTAMENTE!!!"]);
                }else{
                    self::json(["error" => "ERROR AL MODIFICAR AL PRODUCTO!!"]);
                }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!"]);
        }
     }else{
        self::json(["error"=>"ERROR, NO TIENE PERMISO PARA REALIZAR ESTA ACCIÓN!!!"]);
     }
    }
    /**QUITAR EL PRECIO AL PRODUCTO  */
    public static function eliminarPrecioProductoPresentacion($id){
        self::NoAuth();
        if (self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === "admin_farmacia") {
            if (self::ValidateToken(self::post("token_"))) {
                $productoPrice = new ProductoPrecio;
                $response =  $productoPrice->delete($id);
                if ($response) {
                    self::json(["response" => "EL PRECIO SE HA ELIMINADO CORRECTAMENTE PARA EL PRODUCTO SELECCCIONADO!!!"]);
                } else {
                    self::json(["error" => "ERROR AL ELIMINAR PRECIO DEL PRODUCTO!!"]);
                }
            } else {
                self::json(["error" => "ERROR, TOKEN INCORRECTO!!"]);
            }
        } else {
            self::json(["error" => "ERROR, NO TIENE PERMISO PARA REALIZAR ESTA ACCIÓN!!!"]);
        }
    }

    /// eliminar un producto con respecto a una almacen
     public static function eliminarProductoAlmacen($id){
        self::NoAuth();
        if (self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general') {
            if (self::ValidateToken(self::post("token_"))) {
                $almacenprod = new AlmacenProducto;
                $response =  $almacenprod->delete($id);
                if ($response) {
                    self::json(["response" => "EL ALMACEN A SIDO QUITADO CORRECTAMENTE PARA EL PRODUCTO SELECCIONADO!!!"]);
                } else {
                    self::json(["error" => "ERROR AL ELIMINAR ALMACEN DEL PRODUCTO!!"]);
                }
            } else {
                self::json(["error" => "ERROR, TOKEN INCORRECTO!!"]);
            }
        } else {
            self::json(["error" => "ERROR, NO TIENE PERMISO PARA REALIZAR ESTA ACCIÓN!!!"]);
        }
    }
}