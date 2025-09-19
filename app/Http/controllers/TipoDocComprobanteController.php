<?php
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\DocumentoEmision;
use models\Sede;

class TipoDocComprobanteController extends BaseController
{

     /**MOSTRAR LA VISTA DE TIPO DE COMPROBANTES */
     public static function index(){
        self::NoAuth();
        if(self::profile()->rol === "admin_general" || self::profile()->rol === "Director"){
            if(self::profile()->rol === "admin_general"){
                $sede = new Sede;
                $sedes = $sede->query()->Where("deleted_at","is",null)->get();
                self::View_("business.tipodoc",compact("sedes"));
                return;
            }
             self::View_("business.tipodoc");
        }else{
            PageExtra::PageNoAutorizado();
        }
     }

     /** REGISTRAR EL DOCUMENTO DE EMISION */
     public static function store(){
        self::NoAuth();
        if(self::profile()->rol === "admin_general" || self::profile()->rol === "Director"){
            $doc = new DocumentoEmision;
                            
            /// verificamos la existencia por sucursal
            $sede = (self::profile()->sede_id != null ? self::profile()->sede_id : self::post("sede"));
            $existe = $doc->query()->Where("name_documento","=",trim(self::post("nombredoc")))
                          ->And("seriedoc","=",self::post("seriedoc"))
                          ->And("sede_id","=",$sede)
                          ->And("estado","=","a")->first();
            if($existe){
                self::json(["error"=>"ERROR, NO PUEDES REGISTRAR UN NUEVO TIPO DOCUMENTO DE EMISION DEL MISMO TIPO, YA QUE YA EXISTE UNO QUE ESTA ACTIVO EN LA SUCURSAL!!"]);
            }else{
                 $existeSerie = $doc->query()->Where("seriedoc","=",trim(self::post("seriedoc")))->first();
                  if($existeSerie){
                    self::json(["error"=>"LA SERIE ".self::post("seriedoc")." YA EXISTE EN LA OTRA SUCURSAL!!!"]);
                  }else{
                    $response = $doc->Insert([
                    "name_documento" => self::post("nombredoc"),
                    "tipo" => self::post("tipo"),
                    "seriedoc" => self::post("seriedoc"),
                    "correlativo_inicial" => intval(self::post("correlativodocinicial")),
                    "correlativo_final" => intval(self::post("correlativodocfinal")),
                    "sede_id" => $sede
                ]);
                    if ($response) {
                        self::json(["success" => "DOCUMENTO DE EMISION CON LA SERIE " . self::post("seriedoc") . " A SIDO REGISTRADO CORRECTAMENTE!!"]);
                    } else {
                        self::json(["error" => "ERROR AL REGISTRAR DOCUMENTO DE EMISIÓN!!"]);
                    }
            }
            }
        }else{
            self::json(["error" => "ERROR, NO ESTAS AUTHORIZADO PARA REALIZAR ESTA TAREA!!!"]);
        }
     }
    
     /// MOSTRAR LOS DOCUEMTNOS DE EMISION
     public static function showDocumentosEmision(){
        self::NoAuth();
        if(self::profile()->rol === "admin_general" || self::profile()->rol === "Director"){
            $doc = new DocumentoEmision;
             if(self::profile()->rol === "Director"){
                $sede = self::profile()->sede_id;
                $documentos = $doc->query()
                ->select("de.id_documento_emision","de.name_documento","de.seriedoc"
                ,"de.correlativo_inicial","de.correlativo_final","s.namesede","de.estado","de.tipo","de.sede_id","de.estado")
                ->Join("sedes as s","de.sede_id","=","s.id_sede")
                ->Where("sede_id","=",$sede)->get();
             }else{
               $documentos = $doc->query()
               ->select("de.id_documento_emision","de.name_documento","de.seriedoc"
                ,"de.correlativo_inicial","de.correlativo_final","s.namesede","de.estado","de.tipo","de.sede_id","de.estado")
                ->Join("sedes as s","de.sede_id","=","s.id_sede")
               ->get(); 
             }
             self::json(["documentos" => $documentos]);
        }else{
            self::json(["documentos" =>[]]);
        }
     }

     /// ELIMINAR
     public static function eliminar($id){
        self::NoAuth();
        if(self::profile()->rol === "admin_general" || self::profile()->rol === "Director")
        {
            if(self::ValidateToken(self::post("token_")))
            {
                $doc = new DocumentoEmision;
                $documento = $doc->query()->Where("id_documento_emision","=",$id)->first();
                $res = $doc->delete($id);
                if($res){
                    self::json(["success" => "EL DOCUMENTO ".$documento->name_documento." CON LA SERIE ".$documento->seriedoc." HA SIDO ELIMINADO!!!"]);
                }else{
                    self::json(["error" => "ERROR AL ELIMINAR EL DOCUMENTO DE EMISION SELECCIONADO!!!"]);
                }
            }else{
                self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
            }
        }else{
            self::json(["error" => "ERROR, NO TIENES PERMISOS PARA REALIZAR ESTA TAREA!!!"]);
        }
     }

     /// actualizar 
     
 public static function update($id){
        self::NoAuth();
        if(self::profile()->rol === "admin_general" || self::profile()->rol === "Director"){
            $doc = new DocumentoEmision;
            $existeSerie = $doc->query()->select("count(*) as cantidad")->Where("seriedoc","=",trim(self::post("seriedoceditar")))->first();          
            /// verificamos la existencia por sucursal
            $sede = (self::profile()->sede_id != null ? self::profile()->sede_id : self::post("sedeeditar"));
           
            if($existeSerie->cantidad >=1){
                $response = $doc->Update([
                    "id_documento_emision" => $id,
                    "name_documento" => self::post("nombredoceditar"),
                    "tipo" => self::post("tipoeditar"),
                    "correlativo_inicial" => intval(self::post("correlativodocinicialeditar")),
                    "correlativo_final" => intval(self::post("correlativodocfinaleditar")),
                    "sede_id" => $sede,
                    "estado" => self::post("estado")
                ]);
            }else{
                 
                $response = $doc->Update([
                    "id_documento_emision" => $id,
                    "name_documento" => self::post("nombredoceditar"),
                    "tipo" => self::post("tipoeditar"),
                    "seriedoc" => self::post("seriedoceditar"),
                    "correlativo_inicial" => intval(self::post("correlativodocinicialeditar")),
                    "correlativo_final" => intval(self::post("correlativodocfinaleditar")),
                    "sede_id" => $sede,
                    "estado" => self::post("estado")
                ]);
            }
              if ($response) {
                        self::json(["success" => "DOCUMENTO DE EMISION MODIFICADO CORRECTAMENTE!!"]);
                    } else {
                        self::json(["error" => "ERROR AL MODIFICAR DOCUMENTO DE EMISIÓN!!"]);
                    }
        }else{
            self::json(["error" => "ERROR, NO ESTAS AUTHORIZADO PARA REALIZAR ESTA TAREA!!!"]);
        }
     }
}