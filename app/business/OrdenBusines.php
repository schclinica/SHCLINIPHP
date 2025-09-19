<?php
namespace business;

use models\CategoriaOrden;
use models\Orden;
use models\TipoOrden;
use PhpOffice\PhpSpreadsheet\IOFactory;

class OrdenBusines{

    /** GUARDAR NUEVA ORDEN */
    public static function saveOrden(String $CodigoOrden, String $NombreExamen,int $TipoExamen,float $precio){
        $Orden = new Orden;

        $OrdenExiste = $Orden->query()->Where("nombre_examen","=",$NombreExamen)->get();

        if($OrdenExiste){ return "existe";}

        $response = $Orden->Insert([
            "codigo_orden" => $CodigoOrden,
            "nombre_examen" => $NombreExamen,
            "categoriaorden_id" => $TipoExamen,
            "precio_examen" => $precio
        ]) ? 'ok' : 'error';

        return $response;
    }


     /**Importar datos desde excel a tipo orden */
     public static function ImportarDatosOrden($ArchivoExcel){

        /// indicamos al model
        $Orden = new Orden;
        # recibimos el archivo excel seleccionado
        $DocumentExcel = IOFactory::load($ArchivoExcel);
        # indicamos la hoja 0, recomendable , debe de ser solo 1 hoja por documento
        $HojaData = $DocumentExcel->getSheet(0);
        #Obtenemos la cantidad de filas
        $FilasDocumento = $HojaData->getHighestDataRow();

        /// iteramos la fila
        for($row = 2;$row<=$FilasDocumento;$row++){
            /// obtenemos el código
            $Codigo_Orden = $HojaData->getCellByColumnAndRow(1,$row);
            $Nombre = $HojaData->getCellByColumnAndRow(2,$row);
            $Precio = $HojaData->getCellByColumnAndRow(3,$row);
            $Codigo = $HojaData->getCellByColumnAndRow(4,$row);

            if(!self::ExisteOrden($Nombre,$Codigo_Orden))
            {
              $Respuesta = $Orden->Insert([
                "codigo_orden" => $Codigo_Orden,
                "nombre_examen" => $Nombre,
                "precio_examen" => $Precio,
                "categoriaorden_id"=> self::ObtenerCategoriaOrderId($Codigo)[0]->id_categoria_examen
              ]) ? 'ok' : 'error';
            }
            else
            {
              $Respuesta = "existe";
            }
        }

        return $Respuesta;
     }

     /// IMPORTAR DATOS DE CATEGORIA ORDEN

      /**Importar datos desde excel a tipo orden */
      public static function ImportarDatosCategoryOrden($ArchivoExcel){

        /// indicamos al model
        $CategoriaOrder = new CategoriaOrden;
        # recibimos el archivo excel seleccionado
        $DocumentExcel = IOFactory::load($ArchivoExcel);
        # indicamos la hoja 0, recomendable , debe de ser solo 1 hoja por documento
        $HojaData = $DocumentExcel->getSheet(0);
        #Obtenemos la cantidad de filas
        $FilasDocumento = $HojaData->getHighestDataRow();

        /// iteramos la fila
        for($row = 2;$row<=$FilasDocumento;$row++){
            /// obtenemos el código
            $Codigo_Categoria = $HojaData->getCellByColumnAndRow(1,$row);
            $Nombre_Categoria = $HojaData->getCellByColumnAndRow(2,$row);
            $Tipo = $HojaData->getCellByColumnAndRow(3,$row);

            if(!self::ExisteCategoryOrden($Nombre_Categoria,$Codigo_Categoria))
            {
              $Respuesta = $CategoriaOrder->Insert([
                "codigo_categoria" => $Codigo_Categoria,
                "nombre_categoria" => $Nombre_Categoria,
                "grupotipo_id"=> self::ObtenerTipoExamenId($Tipo)[0]->id_tipo_examen
              ]) ? 'ok' : 'error';
            }
            else
            {
              $Respuesta = "existe";
            }
        }

        return $Respuesta;
     }

     /** SI EXISTE LA CATEGORIA */
     private static function ExisteCategoryOrden($NameCategory,$CodigoCategory)
     {
         $modelCategoryOrden = new CategoriaOrden;
 
         return $modelCategoryOrden->query()->Where("codigo_categoria","=",$CodigoCategory)
         ->Or("nombre_categoria","=",$NameCategory)
         ->get();
 
     }

      /**Existe   Orden */
    private static function ExisteOrden($NameExamen,$CodigoData)
    {
        $modelOrden_ = new Orden;

        return $modelOrden_->query()->Where("nombre_examen","=",$NameExamen)
        ->Or("codigo_orden","=",$CodigoData)
        ->get();

    }

    /**CONSULTAR TIPO DE EXAMEN POR CODIGO */

    private static function ObtenerTipoExamenId($codigo_)
    {
        $modelOrden_Tipo = new TipoOrden;

        return $modelOrden_Tipo->query()->select("id_tipo_examen")->Where("codigo_tipo_examen","=",$codigo_)->get();

    }


     /**CONSULTAR LA CATEGORIA POR CODIGO */

     private static function ObtenerCategoriaOrderId($codigo_)
     {
         $modelCategoryOrder = new CategoriaOrden;
 
         return $modelCategoryOrder->query()->select("id_categoria_examen")->Where("codigo_categoria","=",$codigo_)->get();
 
     }

    /// CONSULTAR TIPO 

    /** ELIMINAR  */
    public static function EliminarOrden($id,String $Fecha){

        $orden = new Orden;

        return $orden->Update(["id_examen" => $id,
        "deleted_at" => $Fecha
        ]) ? 'ok' : 'error';
    }

    /// Activar
    public static function ActivarOrden($id){

        $orden = new Orden;

        return $orden->Update(["id_examen" => $id,
        "deleted_at" => null
        ]) ? 'ok' : 'error';
    }

    /**BORRAR LA ORDEN */
    public static function BorrarOrden($id){
        $orden = new Orden;
        return $orden->delete($id) ? 'ok': 'error';
    }

    /// MODIFICAR
    public static function ModificarOrden($id,String $CodigoOrden,$NombreExamen,$Precio,String|null $tipo_examen){

        $orden = new Orden;

        if($tipo_examen != null){
          return $orden->Update(["id_examen" => $id,
          "codigo_orden" => $CodigoOrden,
          "nombre_examen" => $NombreExamen,
          "precio_examen" => $Precio,
          "categoriaorden_id" => $tipo_examen
          ]) ? 'ok' : 'error';
        }

        return $orden->Update(["id_examen" => $id,
        "codigo_orden" => $CodigoOrden,
        "nombre_examen" => $NombreExamen,
        "precio_examen" => $Precio,
        ]) ? 'ok' : 'error';
    }
}