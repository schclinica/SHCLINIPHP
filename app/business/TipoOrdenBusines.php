<?php
namespace business;

use models\TipoOrden;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TipoOrdenBusines {

    /**
     * Registrar Tipo Orden
     */

     public static function RegistrarTipoOrden(String $Codigo, String $TipoName){
        $tipoOrden = new TipoOrden;

        $TipoOrden = $tipoOrden->query()->Where("nombre_tipo_examen","=",$TipoName)
        ->Or("codigo_tipo_examen","=",$Codigo)
        ->get();

        if($TipoOrden){ return "existe";}

        $response = $tipoOrden->Insert([
            "codigo_tipo_examen" => $Codigo,
            "nombre_tipo_examen" => $TipoName
        ]) ? 'ok' : 'error';

        return $response;
     }

     /**
      * MOSTRAR LOS TIPOS DE ORDENES QUE ESTAN DISPONIBLES
     */
    public static function showTipoOrdenesDisponibles(){
        $orden_tipo = new TipoOrden;

        return $orden_tipo->query()->Where("deleted_at","is",null)->get();
    }

    /**
      * MOSTRAR LOS TIPOS DE ORDENES 
     */
    public static function showTipoOrdenesAll(){
      $orden_tipo = new TipoOrden;

      return $orden_tipo->query()->get();
  }


     /**Importar datos desde excel a tipo orden */
     public static function ImportarDatosTipoOrden($ArchivoExcel){

        /// indicamos al model
        $modelTipo_Orden = new TipoOrden;
        # recibimos el archivo excel seleccionado
        $DocumentExcel = IOFactory::load($ArchivoExcel);
        # indicamos la hoja 0, recomendable , debe de ser solo 1 hoja por documento
        $HojaData = $DocumentExcel->getSheet(0);
        #Obtenemos la cantidad de filas
        $FilasDocumento = $HojaData->getHighestDataRow();

        /// iteramos la fila
        for($row = 2;$row<=$FilasDocumento;$row++){
            /// obtenemos el código
            $Codigo = $HojaData->getCellByColumnAndRow(1,$row);
            $NombreTipo = $HojaData->getCellByColumnAndRow(2,$row);

            if(!self::ExisteTipoOrden($NombreTipo,$Codigo))
            {
              $Respuesta = $modelTipo_Orden->Insert([
                "codigo_tipo_examen" => $Codigo,
                "nombre_tipo_examen" => $NombreTipo
              ]) ? 'ok' : 'error';
            }
            else
            {
              $Respuesta = "existe";
            }
        }

        return $Respuesta;
     }


     /**Existe Tipo Orden */
    private static function ExisteTipoOrden($TipoName,$Codigo)
    {
        $modelTipoOrden_ = new TipoOrden;

        return $modelTipoOrden_->query()->Where("nombre_tipo_examen","=",$TipoName)
        ->Or("codigo_tipo_examen","=",$Codigo)
        ->get();

    }

    /** Actualizat tipo examen */
    public static function modificarTipoOrden($id,$Codigo,$Nombre){
      $modelTipoOrden_ = new TipoOrden;

      return $modelTipoOrden_->Update([
        "id_tipo_examen" => $id,
        "codigo_tipo_examen" => $Codigo,
        "nombre_tipo_examen" => $Nombre
      ]) ? 'ok' : 'error';
    }

    /**ELIMINAR TIPO ORDEN */
    public static function eliminarTipoOrden($id,String $Fecha){
      $modelTipoOrden_ = new TipoOrden;

      return $modelTipoOrden_->Update([
        "id_tipo_examen" => $id,
        "deleted_at" => $Fecha
      ]) ? 'ok' : 'error';
    }

    /**Activar TIPO ORDEN */
    public static function ActivarTipoOrden($id){
      $modelTipoOrden_ = new TipoOrden;

      return $modelTipoOrden_->Update([
        "id_tipo_examen" => $id,
        "deleted_at" => null
      ]) ? 'ok' : 'error';
    }

    /**Activar TIPO ORDEN */
    public static function BorrarTipoOrden($id){
      $modelTipoOrden_ = new TipoOrden;

      return $modelTipoOrden_->delete($id) ? 'ok' : 'error';
    }
}