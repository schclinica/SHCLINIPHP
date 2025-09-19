<?php
namespace Http\controllers;

use FPDF;
use Http\pageextras\PageExtra;
use lib\BaseController;
use lib\PdfResultados;
use lib\PlantillaTicket;
use models\ClienteFarmacia;
use models\DetalleVenta;
use models\Empresa;
use models\ProductoFarmacia;
use models\VentasFarmacia;
use lib\QrCode as LibQrCode;
use models\CajaFarmacia;
use models\CategoriaEgreso;
use models\Lote;
use models\SubCategoriaEgreso;
use models\VistaVentas;

use function Windwalker\where;

class VentaFarmaciaController extends BaseController 
{
    use LibQrCode;
    /** Buscar Cliente para agregar a la venta */
    public static function BuscarCliente(string $num_doc)
    {
        self::NoAuth();
        /// mdoficado
        if(self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0])
        {
            $modelCliente = new ClienteFarmacia;

            $respuestaServer = $modelCliente->query()->Where("num_doc","=",$num_doc)->first();

            self::json(["response" => $respuestaServer]);
        }else{
            self::json(["response"=>["no-authorized"]]);
        }
    }

    /*Consultar productos para la venta*/
    public static function ConsultarProductos()
    {
        self::NoAuth();

        if(self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0])
        {
            $modelProductos = new ProductoFarmacia;
            $sede = self::profile()->sede_id;

            $response = $modelProductos->procedure("proc_show_productos_venta","C",[self::FechaActual("Y-m-d"),$sede]);

            self::json(["response" => $response]);
        }else{
            self::json(["response" => []]);
        }
    }

    /** Añadir a la cesta del carrito de la venta farmacia */
    public static function addCestaProducto(int $id,$priceIdent)
    {
        self::NoAuth();
        if(self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0])
        {
            $sede = self::profile()->sede_id;
            /// consultamos el producto, con respecto al id seleccionado
            $ProductoModel = new VistaVentas;
            
            $Producto = $ProductoModel->query()
            ->Where("id_sede","=",$sede)
            ->And("id_producto","=",$id)
            ->And("priceindent","=",$priceIdent)->first();

            if($Producto)
            {
                /// verificamos si existe la session carrito_farmacia
            if(!isset($_SESSION['carrito_farmacia']))
            {
               $_SESSION['carrito_farmacia'] = [];
            }

                if (count(self::getSession("carrito_farmacia")) == 0) {

                    if ($Producto->ct > $Producto->stock) {
                        self::json(["error" => "NO HAY STOCK SUFICIENTE PARA EL PRODUCTO " . strtoupper($Producto->nombre_producto) . " EN ESTA SUCURSAL:  " . "STOCK DISPONIBLE: " . $Producto->stock . "  -  " . "CANTIDAD QUE DESEAS INGRESAR PARA LA VENTA: " . $Producto->ct . " unidades"]);
                     return;
                    }

                }
               /// verificamos si existe el prodcuto. Si existe solo aumentamos la cantida
               $Totalcantidad = self::SobrepasaStockProductoAdd($Producto->nombre_producto,$Producto->ct);
               
             if($Totalcantidad > $Producto->stock){
               self::json(["error" => "NO HAY STOCK SUFICIENTE PARA EL PRODUCTO ".strtoupper($Producto->nombre_producto)." EN ESTA SUCURSAL:  " . "STOCK DISPONIBLE: " . $Producto->stock . "  -  " . "CANTIDAD QUE DESEAS INGRESAR PARA LA VENTA: " . $Totalcantidad." unidades"]);
             }else{
                if (!array_key_exists($Producto->id_producto . "" . $Producto->priceindent, $_SESSION["carrito_farmacia"])) {
                     
                         $SimboloMoneda = self::BusinesData()[0]->simbolo_moneda !== null ? self::BusinesData()[0]->simbolo_moneda : 'S/.';
                        $_SESSION["carrito_farmacia"][$Producto->id_producto . "" . $Producto->priceindent]["descripcion"] = $Producto->nombre_producto;
                        $_SESSION["carrito_farmacia"][$Producto->id_producto . "" . $Producto->priceindent]["descripcion_precio"] = $Producto->nombre_producto . " " . $Producto->presentacion . "(" . $Producto->ct . ")" . "(" . $SimboloMoneda . " " . $Producto->precio . ")";
                        $_SESSION["carrito_farmacia"][$Producto->id_producto . "" . $Producto->priceindent]["precio"] = $Producto->precio;
                        $_SESSION["carrito_farmacia"][$Producto->id_producto . "" . $Producto->priceindent]["cantidad"] = 1;
                        $_SESSION["carrito_farmacia"][$Producto->id_producto . "" . $Producto->priceindent]["cantidad_por_presentacion"] = $Producto->ct;
                        $_SESSION["carrito_farmacia"][$Producto->id_producto . "" . $Producto->priceindent]["empaque"] = $Producto->marca;
                        $_SESSION["carrito_farmacia"][$Producto->id_producto . "" . $Producto->priceindent]["producto_almacen"] = $Producto->id_producto_almacen;
                        $_SESSION["carrito_farmacia"][$Producto->id_producto . "" . $Producto->priceindent]["producto_id"] = $Producto->id_producto;
                        $_SESSION["carrito_farmacia"][$Producto->id_producto . "" . $Producto->priceindent]["priceident"] = $Producto->priceindent;
                        $_SESSION["carrito_farmacia"][$Producto->id_producto . "" . $Producto->priceindent]["presentacion"] = $Producto->presentacion;
                        $_SESSION["carrito_farmacia"][$Producto->id_producto . "" . $Producto->priceindent]["lote"] = $Producto->lote;
                        //$_SESSION["carrito_farmacia"][$Producto->id_producto]["cantidad"] = 1;
                        self::json(["response" => "agregado"]);
                    } else {
 
                       $_SESSION["carrito_farmacia"][$Producto->id_producto . "" . $Producto->priceindent]["cantidad"] += 1;
                      self::json(["response" => "agregado doble"]);
                         
                    }
                }
            }
        }else{
            self::json(["response"=>"no-authorized"]);
        }
    }

    /**CONTAR LA CANTIDAD DE UNIDADES DE UN MISMO PRODUCTO */
    private static function SobrepasaStockProducto($producto,$NuevaCantidadData,$inputCantidad,$ct):int{
        $cantidad = 0;$Beforecantidad = 0;$c=0;
        if(isset($_SESSION["carrito_farmacia"])){
            foreach(self::getSession("carrito_farmacia") as $carrito){
                if($carrito["descripcion"] === $producto){
                      
                     $Beforecantidad+= ($carrito["cantidad"] * $carrito["cantidad_por_presentacion"]);
                     $cantidad= $Beforecantidad+(($inputCantidad - $ct)*$NuevaCantidadData);
                }
            }
        }
        return $cantidad;
    }

     private static function SobrepasaStockProductoAdd($producto,$NuevaCantidadData):int{
        $cantidad = 0;$Beforecantidad = 0;$c=0;
        if(isset($_SESSION["carrito_farmacia"])){
            foreach(self::getSession("carrito_farmacia") as $carrito){
                if($carrito["descripcion"] === $producto){
                      
                     $Beforecantidad+= ($carrito["cantidad"] * $carrito["cantidad_por_presentacion"]);
                     $cantidad= $Beforecantidad+$NuevaCantidadData;
                }
            }
        }
        return $cantidad;
    }
 
    
    /** 
     * Mostrar productos agregados a la cesta
     */
    public static function showProductosCesta()
    {
        self::NoAuth();

        if(self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0])
        {
            if(isset($_SESSION["carrito_farmacia"]))
            {
                self::json(["response"=>self::getSession("carrito_farmacia")]);
            }else{
                self::json(["response"=>[]]);  
            }
        }else{
            self::json(["response"=>[]]);
        }
    }

    /**
     * Quitar productos de la cesta
     */
    public static function QuitarProductoCesta($productoId,$priceIdent)
    {
        self::NoAuth();

        if(self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0])
        {
            if(isset($_SESSION["carrito_farmacia"][$productoId."".$priceIdent]))
            {
              unset($_SESSION["carrito_farmacia"][$productoId."".$priceIdent]);
              self::json(["response" => "eliminado"]);
            }
            else{
                self::json(["response" => "error a eliminar"]);
            }
        }else{
            self::json(["response" => "no-authorized"]);
        }
    }

    /// modificar la cantidad de la cesta
    public static function ModifyCantidadProductoCesta($productoId,$PriceIdent)
    {
        self::NoAuth();

        if(self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0])
        {
            $sede = self::profile()->sede_id;
            if(isset($_SESSION["carrito_farmacia"][$productoId."".$PriceIdent]))
            {
              $modelProductoStock = new VistaVentas;

              $StockSuficiente = $modelProductoStock->query()
            ->Where("id_sede","=",$sede)
            ->And("id_producto","=",$productoId)
            ->And("priceindent","=",$PriceIdent)->first();
                $ct = $_SESSION["carrito_farmacia"][$StockSuficiente->id_producto . "" . $StockSuficiente->priceindent]["cantidad"];
                $TotalEnCantidad = self::SobrepasaStockProducto($StockSuficiente->nombre_producto,$StockSuficiente->ct,self::post("cantidad_cesta"),$ct);
             
                if (($TotalEnCantidad)> $StockSuficiente->stock) {
                  self::json(["error" => "NO HAY STOCK SUFICIENTE PARA EL PRODUCTO ".strtoupper($StockSuficiente->nombre_producto)." EN ESTA ESTA SUCURSAL:  " . "STOCK DISPONIBLE " . $StockSuficiente->stock . "  -  " . "CANTIDAD QUE DESEAS INGRESAR PARA LA VENTA: " . $TotalEnCantidad ." unidades"]);
                }else {
                    $_SESSION["carrito_farmacia"][$productoId . "" . $PriceIdent]["cantidad"] = self::post("cantidad_cesta");
                    self::json(["response" => "modificado"]);    
                }
              
            }
            else{
                self::json(["response" => "error a modificar"]);
            }
        }else{
            self::json(["response" => "no-authorized"]);
        }
    }

    /// Cancelar la venta 
    public static function CancelVentaFarmacia()
    {
        self::NoAuth();

        if(self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0])
        {
            if(self::ValidateToken(self::post("_token")))
            {
                
                   self::destroySession("carrito_farmacia"); 

                   self::json(["response" => "carrito-vacio"]);
                 
            }else{
                self::json(["response" => "token-invalidate"]);
            }
        }else{
            self::json(["response" => "no-authorized"]);
        }
    }

    /// obtener la serie de la venta
    public static function getSerieVenta()
    {
        self::NoAuth();
        if(self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0])
        { 
            $modelVenta = new VentasFarmacia;
            $IndexVenta = $modelVenta->ObtenerMaxVenta()->num_venta;
            $serieVenta  = self::FechaActual('YmdHis').self::profile()->id_usuario." - ". ($IndexVenta == null ? 1 : $IndexVenta+ 1) ;
            self::json(["response" => $serieVenta]);
            
        }else{
            self::json(["response" => "no-authorized"]);
        }
    }

    /// Guardar la venta
    public static function saveVenta()
    {
      /// verificamos que esté authenticado
      self::NoAuth();
      /// verificamos que seal el farmacetico(a) quien realice esta acción
      if(self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0])
      {
        if(self::ValidateToken(self::post("_token")))
        {
            self::GuardadoProcesoVenta();
        }else{
            self::json(["response" => "token-invalidate"]);
        }
      }else{
        self::json(["response" => "no-authorized"]);
      }
    }

    /// proceso para guardar la venta
    private static function GuardadoProcesoVenta()
    {
        $modelVenta = new VentasFarmacia;

        /// calculamos el total de la venta
        if(self::ExistSession("carrito_farmacia"))
        {
            $sede = self::profile()->sede_id;
            $TotalDeLaVenta = 0.00;$ImportePorProducto = 0.00;$ImporteDetalle = 0.00;
            foreach($_SESSION["carrito_farmacia"] as $carritoVenta)
            {
                $ImportePorProducto = $carritoVenta["precio"] * $carritoVenta["cantidad"];
                $TotalDeLaVenta+= $ImportePorProducto;
            }
                 $caja = new CajaFarmacia;
                 $sede = self::profile()->sede_id;
                 $DataCaja = $caja->query()->Where("sede_id","=",$sede)->And("estado_caja","=","a")->limit(1)->first();
            $respuestaVenta = $modelVenta->Insert([
                "id_venta" => self::post("serie_venta"),
                "num_venta" => self::post("serie_venta"),
                "fecha_emision" => self::post("fecha_emision"),
                "cliente_id" => self::post("cliente_id"),
                "usuario_id" => self::profile()->id_usuario,
                "monto_recibido" => self::post("monto_recibido"),
                "vuelto" => self::post("vuelto"),
                "total_venta" => $TotalDeLaVenta,
                "sede_id" => $sede,
                "caja_id" => $DataCaja->id_caja
            ]);

            if($respuestaVenta)
            {
                /// obtenemos el id de la venta
                $VentaId = $modelVenta->query()->Where("num_venta","=",self::post("serie_venta"))->first();

                $modelDetalleVenta = new DetalleVenta;
                $FechaActual = self::FechaActual("Y-m-d");

                foreach($_SESSION["carrito_farmacia"] as $cestaDetalle)
                {
                    $ImporteDetalle = $cestaDetalle["precio"] * $cestaDetalle["cantidad"];
                    $respuestaDetalleVenta = $modelDetalleVenta->Insert([
                        "venta_id" => $VentaId->id_venta,"producto" => $cestaDetalle["descripcion_precio"],
                        "presentacion" =>$cestaDetalle["presentacion"],
                        "cantidad" => $cestaDetalle["cantidad"],"precio_venta" => $cestaDetalle["precio"],
                        "importe_venta" => $ImporteDetalle,"producto_id" => $cestaDetalle["producto_id"],
                        "almaceproducto_id"=> $cestaDetalle["producto_almacen"],
                        "unidades_por_paquete" => $cestaDetalle["cantidad_por_presentacion"]
                    ]);
                    if ($cestaDetalle["lote"] === 'si') {
                        $lote = new Lote;
                        $lotes = $lote->query()->Where("producto_id", "=", $cestaDetalle["producto_id"])->And("sede_id", "=", $sede)
                            ->And("cantidadlote", ">", 0)
                            ->And("fecha_vencimiento", ">=", $FechaActual)
                            ->orderBy("fecha_vencimiento", "asc")->get();
                        $cantidadVenta = $cestaDetalle["cantidad"] * $cestaDetalle["cantidad_por_presentacion"];

                        foreach ($lotes as $milote) {
                            if ($cantidadVenta > $milote->cantidadlote) {
                                /// actualizar el lote
                                $lote->procedure("proc_lotes", "a", [2, $milote->codigo_lote, $milote->producto_id, $sede, 0]);
                                $cantidadVenta -= $milote->cantidadlote;
                            } else {
                                $nuevaCantidad = $milote->cantidadlote - $cantidadVenta;
                                $lote->procedure("proc_lotes", "a", [3, $milote->codigo_lote, $milote->producto_id, $sede, $nuevaCantidad]);
                                $cantidadVenta = 0;
                            }
                        }
                    }
                }
                 /// obtenemos la caja de la farmacia que esta abierto
                 $TotalVentasPorCaja = $modelVenta->query()->select("sum(total_venta) as total")->Where("sede_id","=",$sede)->And("caja_id","=",$DataCaja->id_caja)
                 ->get();
                  
                 $caja->Update([
                    "id_caja" => $DataCaja->id_caja,
                    "ingreso_ventas" =>  $TotalVentasPorCaja[0]->total
                 ]);
                 self::json(["response" => $respuestaDetalleVenta]);
            }else{
                self::json(["response" => "error-venta"]);
            }
        }

    }

    /**
     * Ver historial de las ventas
     */
    public static function mostrarHistorialVentas()
    {
        self::NoAuth();

        if(self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0])
        {
            $modelVentaFarmacia = new VentasFarmacia;
            $sede = self::profile()->sede_id;
            $respuesta = $modelVentaFarmacia->procedure("proc_historial_ventas","c",[self::get("fecha_venta"),$sede]);
            self::json(["response" => $respuesta]);
        }else{
            self::json(["response" => []]);
        }
    }

    /// imprimir ticket de venta
    public static function GenerateTicket(){
        self::NoAuth();
    if(isset($_GET['v']) and (self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0]))
     {
        // capturamos el id de recibo
        
        $VentaId = self::get("v");
      
        $sede = self::profile()->sede_id;
        $modelVenta = new VentasFarmacia;

        $dataRecibo = $modelVenta->query()
        ->LeftJoin("clientes_farmacia as cf","vf.cliente_id","=","cf.id_cliente")
        ->Where("vf.num_venta","=",$VentaId)
        ->And("vf.sede_id","=",$sede)
        ->get();

        if($dataRecibo)
        {
            $pdf = new PlantillaTicket("P","mm",[73,258]);
            $pdf->setRecibo($dataRecibo);
            $pdf->SetTitle("ticket");
        
            $pdf->AddPage();
        
            $pdf->SetAutoPageBreak(true,45);
         
            /// body
            $modelDetalle = new DetalleVenta;$Total = 0.00;$SubTotal = 0.00;$Igv_ = 0.00;
            $valorIva_ = count(self::BusinesData()) == 1 ? self::BusinesData()[0]->iva_valor:18;
            $DetalleRespuesta = $modelDetalle->query()
            ->Join("ventas_farmacia as vf","dv.venta_id","=","vf.id_venta")
            ->Where("vf.id_venta","=",self::get("v"))
            ->Or("vf.num_venta","=",$VentaId)
            ->get();
             
            $pdf->SetFont("Courier","",7);
         
            foreach($DetalleRespuesta as $respuesta):
             $Total+= $respuesta->importe_venta;
             $SubTotal = $Total / (1+($valorIva_/100));
             $Igv_ = $Total - $SubTotal;
        
            $pdf->MultiCell(60,3,utf8__($respuesta->producto),0,"C");
            $pdf->Ln(3);
            $pdf->SetX(5);
            $pdf->Write(2.8,$respuesta->cantidad);
        
             
            $pdf->SetX(34);
            $pdf->Cell(12,0,$respuesta->precio_venta,0,1,"L");
         
            $pdf->SetX(54);
            $pdf->Cell(12,0,$respuesta->importe_venta,0,1,"L");
            
            $pdf->Ln(3);
            $pdf->SetX(3);
            $pdf->Cell(67,2,"-------------------------------------------",0,0,"C");
            $pdf->Ln(3);
         
            endforeach;
             
             
            $pdf->setTotal($Total);$pdf->setIgv($Igv_);
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
        
            $pdf->Ln();
            $pdf->SetFont("Courier","B",8);
            $pdf->SetX(4);
            $pdf->Cell(20,3,"Monto recibido ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.'),0,0,"L");
        
            $pdf->SetFont("Courier","",8);
            $pdf->SetX(54);
            $pdf->Cell(20,3,number_format($dataRecibo[0]->monto_recibido,2,","," "),0,1,"L");

            $pdf->Ln();
            $pdf->SetFont("Courier","B",8);
            $pdf->SetX(4);
            $pdf->Cell(20,3,"Vuelto ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.'),0,0,"L");
        
            $pdf->SetFont("Courier","",8);
            $pdf->SetX(54);
            $pdf->Cell(20,3,number_format($dataRecibo[0]->vuelto,2,","," "),0,1,"L");
    
        
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

    /// reporte de productos para saber las ganancias, acorde al precio de venta y precio de compra
    public static function showGananciasRepoProductos()
    {
        self::NoAuth();
        if(self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0])
        {
          $modelProductos = new ProductoFarmacia;

          $response = $modelProductos->procedure("proc_productos_calculo_ganancia","c",[self::get("fi"),self::get("ff")]);

          self::json(["response" => $response]);
        }else{
            self::json(["response" => []]);
        }
    }
    /// ver resultados en pdf
    public static function resultados()
    {
        self::NoAuth();

        if(self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0])
        {
            /// creamos el pdf
            $pdfResultados = new PdfResultados();

            /// indicamos un título a la hoja
            $pdfResultados->SetTitle("Estado de resultados");

            /// agregamos una nueva hoja
            $pdfResultados->AddPage();

            /// indicamos los datos de la empresa
            
            /// Agremos un título a la hoja

            $pdfResultados->SetFont("Times","B",16);
            $pdfResultados->Ln(5);

            $pdfResultados->Cell(200,2,"Estado de resultados",0,1,"C");
            $pdfResultados->Cell(200,2,"_____________________________",0,1,"C");

            $pdfResultados->Ln(15);
            $modelProductos = new ProductoFarmacia;


            if(!isset($_GET["fi"]) or !isset($_GET["ff"])){
                PageExtra::PageNoAutorizado();
                return;
            }
            $responseGanancia = $modelProductos->procedure("proc_productos_calculo_ganancia","c",[self::get("fi"),self::get("ff")]);
            // if(!$responseGanancia){
            //     PageExtra::PageNoAutorizado();
            //     return;
            // }
            $TotalCompra = 0.00;$TotalVenta = 0.00;$UtilidadBruta = 0.00;$UtilidadPerdidaNeta = 0.00;
            $Ganancia = 0.00;
            foreach($responseGanancia as $resp){
              $TotalCompra+=$resp->precio_de_compra;
              $TotalVenta+=$resp->precio_venta;
              $UtilidadBruta = $TotalVenta-$TotalCompra;
              $Ganancia+=$resp->ganancia;
            }

            $pdfResultados->setFont("Times","B",12);
            $pdfResultados->SetTextColor(0,0,128);
            $pdfResultados->setX(20);
            $pdfResultados->Cell(60,7,utf8__("Total precio de venta ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.')),1,0,1);
            $pdfResultados->SetTextColor(0,0,0);
            $pdfResultados->Cell(110,7,number_format($TotalVenta,2,","," "),1,1,"R");

            $pdfResultados->setFont("Times","B",12);
            $pdfResultados->SetTextColor(0,0,128);
            $pdfResultados->setX(20);
            $pdfResultados->Cell(60,7,utf8__("Total precio de compra ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.')),1,0,1);
            $pdfResultados->SetTextColor(0,0,0);
            $pdfResultados->Cell(110,7,number_format($TotalCompra,2,","," "),1,1,"R");

            $pdfResultados->setFont("Times","B",12);
            $pdfResultados->SetTextColor(0,0,128);
            $pdfResultados->setX(20);
            $pdfResultados->Cell(60,7,utf8__("Utilidad bruta ".(count(self::BusinesData()) == 1 ? self::BusinesData()[0]->simbolo_moneda:'S/.')),1,0,1);
            $pdfResultados->SetTextColor(0,0,0);
            $pdfResultados->Cell(110,7,number_format($UtilidadBruta,2,","," "),1,0,"R");

            $pdfResultados->Ln(10);
            /// mostramos las categorias por fecha
            $modelCategoria = new CategoriaEgreso;
            $modelSub = new SubCategoriaEgreso;

            $responseCategoria = $modelCategoria->query()->Where("fecha_categoria",">=",self::get("fi"))
            ->And("fecha_categoria","<=",self::get("ff"))->get();

             
            $pdfResultados->Ln(5);$TotalEgreso = 0.00;$TotalEgresoCategoria = 0.00;$item = 0;
            foreach($responseCategoria as $cat)
            {
                $item++;
                $responseSub = $modelSub->query()
                ->Where("categoriaegreso_id","=",$cat->id_categoria_egreso)->get();
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

            $UtilidadPerdidaNeta = $Ganancia-$TotalEgresoCategoria;
            $pdfResultados->Cell(120,7,$UtilidadPerdidaNeta <=0? number_format(abs($UtilidadPerdidaNeta),2,","," ")." ".utf8__("Pérdida") : number_format(abs($UtilidadPerdidaNeta),2,","," ")." ".utf8__("Ganancia"),1,0,"R");
            /// vemos la hoja
            $pdfResultados->Output();
            

        }else{
            PageExtra::PageNoAutorizado();
        }
    }

    /// reporte de ventas por mes 
    public static function reporteVentasGraficoEstadicstico(string $tipo)
    {
        self::NoAuth();

        if(self::profile()->rol === 'Farmacia' || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === 'admin_general' || self::profile()->rol === self::$profile[0]){
            $model = new VentasFarmacia;
            $sede = self::profile()->sede_id;
            $response = $model->procedure("proc_ventas_farmacia_reporte_grafico","c",[$tipo,$sede]);

            self::json(["response" => $response]);
        }else{
            self::json(["response" =>[]]);
        }
    }

    /// mostrar la cantidad de ventas realizadas a cada producto
    public static function CantidadVentasPorProducto(string $opc)
    {
        self::NoAuth();

        if(self::profile()->rol === 'Farmacia'  || self::profile()->rol === 'admin_farmacia' || self::profile()->rol === 'admin_general' || self::profile()->rol === "Director")
        {
            $modelp = new DetalleVenta;

            $sede = self::profile()->sede_id;
            switch($opc){
                case "todos":
                    $respuesta = $modelp->query()->Join("productos_farmacia as pf","dv.producto_id","=","pf.id_producto")
                    ->Join("ventas_farmacia as vf","dv.venta_id","=","vf.id_venta")
                    ->select("pf.nombre_producto as producto","count(*) cantidad_ventas","dv.producto_id")
                    ->Where("vf.sede_id","=",$sede)
                     ->GroupBy(["dv.producto_id"])->get();
                    break;
                 case "mes":

                    $respuesta = $modelp->query()->Join("productos_farmacia as pf","dv.producto_id","=","pf.id_producto")
                    ->select("pf.nombre_producto as producto","count(*) cantidad_ventas","dv.producto_id")
                    ->Join("ventas_farmacia as vf","dv.venta_id","=","vf.id_venta")
                    ->Where("year(vf.fecha_emision)","=",self::FechaActual("Y"))
                    ->And("month(vf.fecha_emision)","=",self::FechaActual("m"))
                    ->And("vf.sede_id","=",$sede)
                    ->GroupBy(["dv.producto_id"])->get();
                  break;

                  case "anio":

                    $respuesta = $modelp->query()->Join("productos_farmacia as pf","dv.producto_id","=","pf.id_producto")
                    ->Join("ventas_farmacia as vf","dv.venta_id","=","vf.id_venta")
                     ->select("pf.nombre_producto as producto","count(*) cantidad_ventas","dv.producto_id")
                    ->Where("year(vf.fecha_emision)","=",self::FechaActual("Y"))
                     ->And("vf.sede_id","=",$sede)
                    ->GroupBy(["dv.producto_id"])->get();
                  break;
                  default:
                  $respuesta = $modelp->query()->Join("productos_farmacia as pf","dv.producto_id","=","pf.id_producto")
                  ->Join("ventas_farmacia as vf","dv.venta_id","=","vf.id_venta")
                   ->select("pf.nombre_producto as producto","count(*) cantidad_ventas","dv.producto_id")
                  ->Where("year(vf.fecha_emision)","=",self::FechaActual("Y"))
                  ->And("month(vf.fecha_emision)","=",self::FechaActual("m"))
                  ->And("day(vf.fecha_emision)","=",self::FechaActual("d"))
                 ->And("vf.sede_id","=",$sede)
                  ->GroupBy(["dv.producto_id"])->get();
                  break;

                

            }

            self::json(["response" => $respuesta]);
        }else{
            self::json(["response" => []]);
        }
    }
}

 
