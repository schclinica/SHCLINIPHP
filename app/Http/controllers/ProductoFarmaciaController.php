<?php 
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use lib\PdfResultados;
use models\Embalaje;
use models\GrupoTerapeutico;
use models\Laboratorio;
use models\Presentacion;
use models\ProductoFarmacia;
use models\Proveedor;
use models\TipoProducto;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProductoFarmaciaController extends BaseController
{
    /** Mostrar todos los productos existentes */
    public static function all()
    {
        self::NoAuth();

        if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
        {
            $modelProducto = new ProductoFarmacia;
            $rol = self::profile()->rol; $sede = self::profile()->sede_id != null ? self::profile()->sede_id:null;
            self::json(["response" => $modelProducto->mostrar(self::FechaActual("Y-m-d"),$rol,$sede)]);
        }else{
            self::json(["response" => []]);
        }
    }

    /**Registrar productos */
    public static function store()
    {
        self::NoAuth();
        if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia') 
        {
            if(self::ValidateToken(self::post("_token")))
            {
                $modelProducto = new ProductoFarmacia;

                $response = $modelProducto->saveProducto(self::post("nombre_producto"),
                self::post("precio_venta"),self::post("stock_minimo"),
                self::post("tipo_select"),self::post("presentacion_select"),self::post("laboratorio_select"),
                self::post("grupo_select"),self::post("embalaje_select"),self::post("proveedor_select"),self::post("code_barra"),(self::post("lote")=== 'on' ? 'si':'no'));

                self::json(["response" => $response]);
            }else{
                self::json(["response" => "token-invalidate"]);
            }
        }else{
            self::json(["response" => "no-authorized"]);
        }
    }

     /**Actualizar productos */
     public static function update(int $id)
     {
         self::NoAuth();
         if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
         {
             if(self::ValidateToken(self::post("_token")))
             {
                 $modelProducto = new ProductoFarmacia;
 
                 $response = $modelProducto->updateProducto(self::post("nombre_producto"),
                 self::post("precio_venta"),self::post("code_barra"),self::post("stock_minimo"),
                 self::post("tipo_select"),self::post("presentacion_select"),self::post("laboratorio_select"),
                 self::post("grupo_select"),self::post("embalaje_select"),self::post("proveedor_select"),$id,self::post("lote")=== 'on' ? 'si':'no');
 
                 self::json(["response" => $response]);
             }else{
                 self::json(["response" => "token-invalidate"]);
             }
         }else{
             self::json(["response" => "no-authorized"]);
         }
     }

      /**Actualizar productos */
      public static function destroy(int $id)
      {
          self::NoAuth();
          if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
          {
              if(self::ValidateToken(self::post("_token")))
              {
                  $modelProducto = new ProductoFarmacia;
  
                  $response = $modelProducto->Borrar($id);
                  self::json(["response" => $response]);
              }else{
                  self::json(["response" => "token-invalidate"]);
              }
          }else{
              self::json(["response" => "no-authorized"]);
          }
      }

      /** Activar e inhabilitar producto registrado */

      public static function HabilitaInhabilitaProducto(int $id,string $Condition)
      {
        self::NoAuth();
        if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
        {
            if(self::ValidateToken(self::post("_token")))
            {
                $modelProducto = new ProductoFarmacia;

                $response = $modelProducto->HabilitarInhabilitarProductosRegistrados($id,$Condition,self::FechaActual("Y-m-d H:i:s"));
                self::json(["response" => $response]);
            }else{
                self::json(["response" => "token-invalidate"]);
            }
        }else{
            self::json(["response" => "no-authorized"]);
        }
      }
      /** Importar datos a la tabla productos*/
      public static function importarDatos()
      {
        self::NoAuth();

        /// esta acción será realizado por el administrador
        if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
        {
           if(self::ValidateToken(self::post("_token")))
           {
             if(self::file_Type("excel_productos") === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
             {
                self::importDataExcelProductos(self::ContentFile("excel_productos"));
             }else{
                self::json(["response" =>"error_tipo"]);
             }
           }else{
            self::json(["response" => "token-invalidate"]);
           }
        }else{
            self::json(["response" => "no-authorized"]);
        }
      } 


      /// proceso para importar datos de excel a la tabla productos
      private static function importDataExcelProductos($fileExcel)
      {
        /// cargamos al documento
        $DocumentoExcel = IOFactory::load($fileExcel);

        /// seleccionamos la hoja del archivo excel seleccionado
        $Hoja = $DocumentoExcel->getSheet(0);
        /// Obtenemos la cantidad de filas de la hoja

        $RowFilasHoja = $Hoja->getHighestDataRow();

        $modelTipo = new TipoProducto; $modelPresentacion = new Presentacion;
        $modelLaboratorio = new Laboratorio; $modelGrupo = new GrupoTerapeutico;
        $modelEmpaque = new Embalaje; $modelProveedor = new Proveedor;
        
        for($fila = 2;$fila <= $RowFilasHoja; $fila ++)
        {
            $CodeBarraProducto = $Hoja->getCell("A".$fila)->getValue();
            $NombreProducto = $Hoja->getCell("B".$fila)->getValue();
            $PrecioVentaProducto = $Hoja->getCellByColumnAndRow(3,$fila);
            $StockMinimoProducto = $Hoja->getCellByColumnAndRow(4,$fila);
            $TipoProducto = $Hoja->getCellByColumnAndRow(5,$fila);
            $PresentacionProducto = $Hoja->getCellByColumnAndRow(6,$fila);
            $LaboratorioProducto = $Hoja->getCellByColumnAndRow(7,$fila);
            $GrupoProducto = $Hoja->getCellByColumnAndRow(8,$fila);
            $EmpaqueProducto = $Hoja->getCellByColumnAndRow(9,$fila);
            $ProveedorProducto = $Hoja->getCellByColumnAndRow(10,$fila);
            $Lote = $Hoja->getCellByColumnAndRow(11,$fila);
         
            $Id_TipoProducto = $modelTipo->query()->Where("name_tipo_producto","=",$TipoProducto)->first()->id_tipo_producto;
            $Id_PresentacionProducto = $modelPresentacion->query()->Where("name_presentacion","=",$PresentacionProducto)->first()->id_pesentacion;
            $Id_LaboratorioProducto = $modelLaboratorio->query()->Where("name_laboratorio","=",$LaboratorioProducto)->first()->id_laboratorio;
            $Id_GrupoTerapeuticoProducto = $modelGrupo->query()->Where("name_grupo_terapeutico","=",$GrupoProducto)->first()->id_grupo_terapeutico;
            $Id_EmpaqueProducto = $modelEmpaque->query()->Where("name_embalaje","=",$EmpaqueProducto)->first()->id_embalaje;
            $Id_ProveedorProducto = $modelProveedor->query()->Where("proveedor_name","=",$ProveedorProducto)->first()->id_proveedor;
          
           
            $modelProducto = new ProductoFarmacia;

            /// verificamos , si existe el producto
            $ExisteProducto = $modelProducto->query()->Where("nombre_producto","=",$NombreProducto)->first();

            if(!$ExisteProducto)
            {
                $ValueResponse = $modelProducto->Insert([
                "nombre_producto" => $NombreProducto,"precio_venta" => $PrecioVentaProducto,
                "code_barra" => $CodeBarraProducto,"stock_minimo" => is_null($StockMinimoProducto)? 5:$StockMinimoProducto,
                "tipo_id" => $Id_TipoProducto,"presentacion_id" => $Id_PresentacionProducto,
                "laboratorio_id" => $Id_LaboratorioProducto,"grupo_terapeutico_id" => $Id_GrupoTerapeuticoProducto,
                "empaque_id" => $Id_EmpaqueProducto,"proveedor_id" => $Id_ProveedorProducto,
                "lote" => $Lote
                ]);
            }else{
                $ValueResponse = 'existe';
            }
        }

        self::json(["response" => $ValueResponse]);
      }

      /// reporte de productos que tiene x días por vencer
      public static function reporteProductosPorVencer()
      {
        self::NoAuth();

        if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
        {
            $modelProductoRepo = new ProductoFarmacia;
            
            /// abrimos el pdf
            $pdfprod = new PdfResultados();

            $pdfprod->setDirSede(self::BusinesData()[0]->direccion!= null ? self::BusinesData()[0]->direccion:'XXXXXXXXXXXXXXXXXXXXXXXXX');
            $pdfprod->SetTitle("Reporte de productos por vencer");

            $pdfprod->AddPage();
            $pdfprod->setFont("Times","B",17);

            $pdfprod->Cell(200,3,"Productos por vencer",0,1,"C");
            $pdfprod->Cell(200,3,"________________________",0,1,"C");

            if(!isset($_GET["dias"]) or !isset($_GET["v"])){PageExtra::Page404();exit;}

            if($_GET["dias"] === 'mas de 100 días'){
                $respuesta = $modelProductoRepo->procedure("proc_productos_repo_por_vencer","c",[101,intval(self::get("v"))]);    
            }else{
                $respuesta = $modelProductoRepo->procedure("proc_productos_repo_por_vencer","c",[intval(self::get("dias")),intval(self::get("v"))]); 
            }
           
            if($respuesta)
            {
                $pdfprod->Ln(10);
                $pdfprod->SetX(75);
                $pdfprod->setFont("Times","B",12);
                $pdfprod->SetFillColor(248, 248, 255);
                $pdfprod->setTextColor(0,0,0);
                $pdfprod->SetDrawColor(105,105,105);
                $pdfprod->Cell(30,10,"Proveedor",1,0,"L",true);
                $pdfprod->setFont("Times","",12);
                $pdfprod->Cell(80,10,utf8__($respuesta[0]->proveedordata),1,1,"L",true);
                $pdfprod->SetX(75);
                $pdfprod->Cell(30,10,\utf8__("Días a vencer"),1,0,"L",true);
                $pdfprod->setFont("Times","",12);
                $pdfprod->Cell(80,10,utf8__($_GET["dias"]),1,1,"L",true);
                /// diseñamos las columnas de la tabla
                $pdfprod->Ln(10);
                $pdfprod->SetX(15);
                $pdfprod->setFont("Times","B",12);
                $pdfprod->SetFillColor(72, 61, 139);
                $pdfprod->setTextColor(255, 250, 250);
                 
                $pdfprod->Cell(110,10,"Producto",1,0,"L",true);
                $pdfprod->Cell(45,10,"Fecha vencimiento",1,0,"L",true);
                $pdfprod->Cell(30,10,"Quedan",1,1,"L",true);

                foreach($respuesta as $prod)
                {
                    $pdfprod->SetX(15);
                    $pdfprod->setFont("Times","",8);
                    $pdfprod->SetFillColor(105, 105, 105);
                  
                    $pdfprod->SetTextColor(105, 105, 105);
                    $pdfprod->Cell(110,10,$prod->producto,1,0,"L");
                    $pdfprod->Cell(45,10,$prod->fechavencimiento,1,0,"L");
                    $pdfprod->SetTextColor(178, 34, 34);
                    $pdfprod->Cell(30,10,utf8__($prod->dias_restantes_para_vencer),1,1,"L");
                }
            }else{
                PageExtra::Page404();
            }
            $pdfprod->Output();
            
        }else{
            PageExtra::PageNoAutorizado();
        }
      }
}