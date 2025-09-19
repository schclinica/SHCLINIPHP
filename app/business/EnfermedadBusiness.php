<?php
namespace business;

use models\Enfermedad;
use models\Plan_Atencion;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EnfermedadBusiness{
    

    /**
     * Registrar a la enfermedad
     */
    public static function saveEnfermedad(
        String $Enfermedad,String|null $Desc_Enfermedad,
        String $Codigo
    ):String{

        $enfermedad = new Enfermedad;

        /// verificamos la existencia

        $ExisteEnfermedad = $enfermedad->query()->Where("enfermedad","=",$Enfermedad)
        ->Or("codigo_enfermedad","=",$Codigo)
        ->get();

        if($ExisteEnfermedad){
            return "existe";
        }
        $response = $enfermedad->Insert([
            "codigo_enfermedad" => $Codigo,
            "enfermedad" => $Enfermedad,
            "descripcion_enfermedad" => $Desc_Enfermedad
        ]);

        return $response ? 'ok' : 'error';
    }

    //// MOSTRAR LAS ENFERMEDADES EXISTENTES
    public static function showEnfermedades():array{
        $enfermedad = new Enfermedad;

        $enfermedades = $enfermedad->query()->get();

        return $enfermedades;
    }

    //// MOSTRAR LAS ENFERMEDADES QUE ESTAN HABILITDAS
    public static function showEnfermedadesHabilitadas():array{
        $enfermedad = new Enfermedad;

        $enfermedades = $enfermedad->query()->Where("deleted_at","is",null)->get();

        return $enfermedades;
    }

    //// ELIMINAR DE LA LISTA A LA ENFERMEDAD
    public static function eliminar($id,String $FechaDeleted):bool{
      $enfermedad = new Enfermedad;

      return $enfermedad->Update([
        "id_enfermedad" => $id,
        "deleted_at" => $FechaDeleted
      ]);
    }

    //// HABILITAR A LA ENFERMEDAD
    public static function HabilitarEnfermedad($id):bool{
        $enfermedad = new Enfermedad;
  
        return $enfermedad->Update([
          "id_enfermedad" => $id,
          "deleted_at" => null
        ]);
    }

    /// ACUALIZAR DATOS DE LA ENFERMEDAD
    public static function modificarEnfermedad($id,$enfermedadData,$descripcion,String $Codigo){
        $enfermedad = new Enfermedad;
  
        $ExisteEnfermedad = $enfermedad->query()->Where("enfermedad","=",$enfermedadData)
        ->And("codigo_enfermedad","=",$Codigo)
        ->get();

        if($ExisteEnfermedad){
            return "existe";
        }
        return $enfermedad->Update([
          "id_enfermedad" => $id,
          "codigo_enfermedad" => $Codigo,
          "enfermedad" => $enfermedadData,
          "descripcion_enfermedad" => $descripcion
        ]) ? 'ok' : 'error';
    }

    /// ELIMINAR LA ENFERMEDAD DE LA BASE DE DATOS
    public static function borrarEnfermedad($id){

        //// VERIFICAMOS SI LA ENFERMEDAD EXISTE EN LA ATENCION MEDIDA
        $atencionmedica = new Plan_Atencion;

        $responseAtencionMedica = $atencionmedica->query()->Where("enfermedad_id","=",$id)->get();

        if($responseAtencionMedica){
            return "not-delete";
        }

        $enfermedad = new Enfermedad;

        return $enfermedad->delete($id) ? 'ok' : 'error';
    }

    /** IMPORTAR DATOS  */
    public static function ImportarDatosEnfermedad($ArchivoExcel){
        
        /// indicamos al model
        $Enfermeda = new Enfermedad;
        # recibimos el archivo excel seleccionado
        $DocumentExcel = IOFactory::load($ArchivoExcel);
        # indicamos la hoja 0, recomendable , debe de ser solo 1 hoja por documento
        $HojaData = $DocumentExcel->getSheet(0);
        #Obtenemos la cantidad de filas
        $FilasDocumento = $HojaData->getHighestDataRow();

        /// iteramos la fila
        for($row = 2;$row<=$FilasDocumento;$row++){
            /// obtenemos el código
            $CodigoEnfermedad = $HojaData->getCellByColumnAndRow(1,$row);
            $Enfermedad = $HojaData->getCellByColumnAndRow(2,$row);
            $DescripcionEnfermedad = $HojaData->getCellByColumnAndRow(3,$row);

            if(!self::ExisteEnfermedad($Enfermedad,$CodigoEnfermedad))
            {
               
              $Respuesta = $Enfermeda->Insert([
                "codigo_enfermedad" => $CodigoEnfermedad,
                "enfermedad" => $Enfermedad,
                "descripcion_enfermedad" => $DescripcionEnfermedad != "" ? $DescripcionEnfermedad : null
              ]) ? 'ok' : 'error';
            }
            else
            {
              $Respuesta = "existe";
            }
        }

        return $Respuesta;
    }

      /**Existe enfermedad */
      private static function ExisteEnfermedad($NameEnfermedad,$Codigo)
      {
          $modelEnfermedad_ = new Enfermedad;
  
          return $modelEnfermedad_->query()->Where("enfermedad","=",$NameEnfermedad)
          ->Or("codigo_enfermedad","=",$Codigo)
          ->get();
  
      }
}