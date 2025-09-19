<?php
namespace Http\controllers;

use lib\BaseController;
use models\CitaMedica;
use models\ComprasFarmacia;
use models\OrdenMedico;
use models\Plan_Atencion;
use models\RecetaElectronico;
use models\Recibo;
use models\Triaje;
use models\VentasFarmacia;

class SerieController extends BaseController{

    /*GENERAR CORRELATIVO Y SERIE PARA LA CITA MEDICA*/
    public static function GenerateCorrelativoSerieCitaMedica()
    {
        self::NoAuth();
        $cita = new CitaMedica;

        if(self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1] || self::profile()->rol === self::$profile[3]){
            $usuario = self::profile()->id_usuario; $sede = self::profile()->sede_id;
            $Correlativo = "CTM-" . self::profile()->id_usuario;
            $data = $cita->query()
                ->Select("lpad(if(count(*) = 0,1,count(*)+1),25,'0') as serie")
                ->Where("sedecita_id","=",$sede)
                ->And("id_usuario","=",$usuario)
                ->first();
            $Serie = $data->serie;
            $DocumentoCitaMedia = $Correlativo."-".$Serie;
            self::json(["serie" => $DocumentoCitaMedia]);
            
        }else{
            self::json(["error" => "ERROR AL GENERAR SERIE!!!"]);
        }
    }

    /*GENERAR CORRELATIVO Y SERIE PARA EL RECIBO*/
    public static function GenerateCorrelativoSerieRecibo()
    {
        self::NoAuth();
        $recibo = new Recibo;

        if(self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1] || self::profile()->rol === self::$profile[3]){
            $usuario = self::profile()->id_usuario; $sede = self::profile()->sede_id;
            $Correlativo = "RC-" . self::profile()->id_usuario;
            $data = $recibo->query()
                ->Select("lpad(if(count(*) = 0,1,count(*)+1),25,'0') as serie")
                ->Join("cita_medica as cm","re.cita_id","=","cm.id_cita_medica")
                ->Where("cm.sedecita_id","=",$sede)
                ->And("re.usuario_id","=",$usuario)
                ->first();
            $Serie = $data->serie;
            $DocumentoRecibo = $Correlativo."-".$Serie;
            self::json(["serie" => $DocumentoRecibo]);
            
        }else{
            self::json(["error" => "ERROR AL GENERAR SERIE!!!"]);
        }
    }
      /*GENERAR EL FOLIO PARA EL TRIAJE*/
    public static function GenerateFolioTriaje()
    {
        self::NoAuth();
        $triaje = new Triaje;

        if(self::profile()->rol === self::$profile[4] || self::profile()->rol === self::$profile[3]){
            $usuario = self::profile()->id_usuario; $sede = self::profile()->sede_id;
            $Correlativo = "T-" . self::profile()->id_usuario;
            $data = $triaje->query()
                ->Select("lpad(if(count(*) = 0,1,count(*)+1),25,'0') as serie")
                ->Where("sede_id","=",$sede)
                ->And("usuario_id","=",$usuario)
                ->first();
            $Serie = $data->serie;
            $DocumentoTriaje = $Correlativo."-".$Serie;
            self::json(["serie" => $DocumentoTriaje]);
            
        }else{
            self::json(["error" => "ERROR AL GENERAR SERIE!!!"]);
        }
    }

    /*GENERAR EL FOLIO PARA EL HISTORIAL CLINICO*/
    public static function GenerateFolioHistorialClinico()
    {
        self::NoAuth();
        $atencion = new Plan_Atencion;

        if(self::profile()->rol === self::$profile[3]){
            $usuario = self::profile()->id_usuario; $sede = self::profile()->sede_id;
            $Correlativo = "HCE-" . self::profile()->id_usuario;
            $data = $atencion->query()
                ->Select("lpad(if(count(*) = 0,1,count(*)+1),25,'0') as serie")
                ->Where("sede_id","=",$sede)
                ->And("usuario_id","=",$usuario)
                ->first();
            $Serie = $data->serie;
            $DocumentoTriaje = $Correlativo."-".$Serie;
            self::json(["serie" => $DocumentoTriaje]);
            
        }else{
            self::json(["error" => "ERROR AL GENERAR SERIE!!!"]);
        }
    }

    /*GENERAR EL FOLIO PARA EL HISTORIAL CLINICO*/
    public static function GenerateCorrelativoSerieOrdenMedico()
    {
        self::NoAuth();
        $Orden = new OrdenMedico;

        if(self::profile()->rol === self::$profile[3]){
            $usuario = self::profile()->id_usuario; $sede = self::profile()->sede_id;
            $Correlativo = "OM-" . self::profile()->id_usuario;
            $data = $Orden->query()
                ->Select("lpad(if(count(*) = 0,1,count(*)+1),25,'0') as serie")
                ->Where("sede_id","=",$sede)
                ->And("usuario_id","=",$usuario)
                ->first();
            $Serie = $data->serie;
            $DocumentoTriaje = $Correlativo."-".$Serie;
            self::json(["serie" => $DocumentoTriaje]);
            
        }else{
            self::json(["error" => "ERROR AL GENERAR SERIE!!!"]);
        }
    }

    /*GENERAR LA SERIE PARA LA RECETA ELECTRONICO*/
    public static function GenerateCorrelativoSerieRecetaElectronico()
    {
        self::NoAuth();
        $receta = new RecetaElectronico;

        if(self::profile()->rol === self::$profile[3]){
            $usuario = self::profile()->id_usuario; $sede = self::profile()->sede_id;
            $Correlativo = "RCE-" . self::profile()->id_usuario;
            $data = $receta->query()
                ->Select("lpad(if(count(*) = 0,1,count(*)+1),25,'0') as serie")
                ->Where("sede_id","=",$sede)
                ->And("usuario_id","=",$usuario)
                ->first();
            $Serie = $data->serie;
            $DocumentoTriaje = $Correlativo."-".$Serie;
            self::json(["serie" => $DocumentoTriaje]);
            
        }else{
            self::json(["error" => "ERROR AL GENERAR SERIE!!!"]);
        }
    }

    /*GENERAR LA SERIE PARA LA RECETA ELECTRONICO*/
    public static function GenerateCorrelativoSerieCompras()
    {
        self::NoAuth();
        $compra = new ComprasFarmacia;

        if(self::profile()->rol === "admin_general" || self::profile()->rol === "admin_farmacia"){
            $usuario = self::profile()->id_usuario; $sede = self::profile()->sede_id;
            $Correlativo = "REC-" . self::profile()->id_usuario;
            $data = $compra->query()
                ->Select("lpad(if(count(*) = 0,1,count(*)+1),25,'0') as serie")
                ->Where("usuario_id","=",$usuario)
                ->first();
            $Serie = $data->serie;
            $DocumentoTriaje = $Correlativo."-".$Serie;
            self::json(["serie" => $DocumentoTriaje]);
            
        }else{
            self::json(["error" => "ERROR AL GENERAR SERIE!!!"]);
        }
    }

     /*GENERAR LA SERIE PARA LA RECETA ELECTRONICO*/
    public static function GenerateCorrelativoSerieVentas()
    {
        self::NoAuth();
        $venta = new VentasFarmacia;

        if(self::profile()->rol === "Farmacia"){
            $usuario = self::profile()->id_usuario; $sede = self::profile()->sede_id;
            $Correlativo = "BOL-" . self::profile()->id_usuario;
            $data = $venta->query()
                ->Select("lpad(if(count(*) = 0,1,count(*)+1),25,'0') as serie")
                ->Where("usuario_id","=",$usuario)
                ->And("sede_id","=",$sede)
                ->first();
            $Serie = $data->serie;
            $DocumentoTriaje = $Correlativo."-".$Serie;
            self::json(["serie" => $DocumentoTriaje]);
            
        }else{
            self::json(["error" => "ERROR AL GENERAR SERIE!!!"]);
        }
    }
}