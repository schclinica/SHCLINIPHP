<?php
namespace Http\controllers;

use business\OrdenBusines;
use Http\pageextras\PageExtra;
use lib\BaseController;
use models\CategoriaOrden;
use models\Orden;
use models\OrdenMedico;

/**
 * VERSION 4.0 SISTEMA CLINICO FARMACIA
 */
class OrdenController extends BaseController
{
    private static string  $TipoArchivoAceptable = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
    /**
     * MOSTRAR LA VISTA DE GESTIONAR A LAS ORDENES DE EXAMENES MEDICOS
     */
    public static function index(){
        self::NoAuth();
        if(self::profile()->rol === "Médico" || self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general'){
            self::View_("orden.index");
        }else{
            PageExtra::PageNoAutorizado();
        }
    }

    /** REGISTRAR NUEVA ORDEN */
    public static function store(){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
            self::json(["response" => OrdenBusines::saveOrden(self::post("codigo_orden"),self::post("nombre_orden"),self::post("categoria_orden"),
            self::post("precio"))]);
        }else{
            self::json(["error" => "token invalid!!"]);
        }    
    }

    /**
     * PROCESO PARA IMPORTAR DATOS DESDE EXCEL A ORDEN
     */
    public static function importar(){
        self::NoAuth();
    if(self::ValidateToken(self::post("token_")))
    {
      /**
       * Obtenemos al file seleccionado, si o si tiene que ser un excel
       */
        if(self::file_size("archivo_excel") > 0)
        {
          # Verificamos si el archivo seleccionado es un excel
          if(self::file_Type("archivo_excel") === self::$TipoArchivoAceptable)
          {
            $ArchivoSelect = self::ContentFile("archivo_excel");
            self::json(["response" => OrdenBusines::ImportarDatosOrden($ArchivoSelect)]);
          }else
          {
            self::json(['response'=>"archivo no aceptable"]);
          }
        }
        else
        {
          self::json(['response'=>"vacio"]);
        }
    }
    }

    /**MOSTRAR DATOS DE LOS EXAMENES REGISTRADOS */
    public static function showExamenes(){

        self::NoAuth();
        $examen = new Orden;
        
        $examenes = $examen->query()
        ->Join("categoria_examenes as ce","e.categoriaorden_id","=","ce.id_categoria_examen")
        ->Join("tipo_examen as te","ce.grupotipo_id","=","te.id_tipo_examen")
        ->select("e.codigo_orden","te.id_tipo_examen","te.nombre_tipo_examen","e.id_examen","e.nombre_examen","e.precio_examen",
        "e.deleted_at","ce.grupotipo_id","ce.codigo_categoria","ce.nombre_categoria","ce.id_categoria_examen","e.codigo_orden")
        ->get();

        self::json(["examenes" => $examenes]);
    }

    /**MOSTRAR DATOS DE LOS EXAMENES REGISTRADOS */
    public static function showExamenesDisponbles($id){
      $examen = new Orden;
      
      $examenes = $examen->query()
      ->Join("categoria_examenes as ce","e.categoriaorden_id","=","ce.id_categoria_examen")
      ->Join("tipo_examen as te","ce.grupotipo_id","=","te.id_tipo_examen")
      ->select("e.codigo_orden","te.id_tipo_examen","te.nombre_tipo_examen","e.id_examen","e.nombre_examen","e.precio_examen",
      "e.deleted_at","e.categoriaorden_id","ce.nombre_categoria","ce.codigo_categoria")
      ->Where("e.deleted_at","is",null)
      ->And("ce.grupotipo_id","=",$id)
      ->get();

      self::json(["examenes" => $examenes]);
   }

    /// eliminar
    public static function Eliminar($id){
     if(self::ValidateToken(self::post("token_"))){
      self::json(["response" => OrdenBusines::EliminarOrden($id,self::FechaActual("Y-m-d H:i:s"))]);  
     }else{
        self::json(["error" => "token invalid!!"]);
     }   
  }

  /// Activar
  public static function Activar($id){
    if(self::ValidateToken(self::post("token_"))){
     self::json(["response" => OrdenBusines::ActivarOrden($id)]);  

    }else{
       self::json(["error" => "token invalid!!"]);
    }   
 }

  /// Borrar a la orden
  public static function Borrar($id){
    if(self::ValidateToken(self::post("token_"))){
     self::json(["response" => OrdenBusines::BorrarOrden($id)]);  

    }else{
       self::json(["error" => "token invalid!!"]);
    }   
 }

/// modificar a la orden
 public static function modificar($id){
    if(self::ValidateToken(self::post("token_"))){
     self::json(["response" => OrdenBusines::ModificarOrden($id,
     self::post("codigo_orden_editar"),
     self::post("nombre_orden_editar"),
     self::post("precio_editar"),
     self::post("categoria_orden_editar")
     )]);  

    }else{
       self::json(["error" => "token invalid!!"]);
    }   
 }


 /** Agregar nueva orden medica al paciente */
  /** ASIGNAR LA ENFERMEDAD COMO DIAGNOSTICO AL PACIENTE */
  public static function AsignarOrdenPaciente($id){

    self::NoAuth();
    if(self::ValidateToken(self::post("token_"))){

       /// hacemos la consulta de la enfermedad
       $Orden = new Orden;

       $orden_ = $Orden->query()
       ->Join("categoria_examenes as ce","e.categoriaorden_id","=","ce.id_categoria_examen")
       ->Join("tipo_examen as te","ce.grupotipo_id","=","te.id_tipo_examen")->Where("e.id_examen","=",$id)->get();

       if($orden_){
          if(!self::ExistSession("orden")){
             self::Session("orden",[]);
          }
 
          if(!array_key_exists(str_replace(" ","_",$orden_[0]->nombre_examen),$_SESSION["orden"])){
             $_SESSION["orden"][str_replace(" ","_",$orden_[0]->nombre_examen)]["examen_desc"] = $orden_[0]->nombre_examen;
             $_SESSION["orden"][str_replace(" ","_",$orden_[0]->nombre_examen)]["codigo_examen"] = $orden_[0]->codigo_orden;
             $_SESSION["orden"][str_replace(" ","_",$orden_[0]->nombre_examen)]["cantidad"] = 1;
             $_SESSION["orden"][str_replace(" ","_",$orden_[0]->nombre_examen)]["tipo_examen"] = $orden_[0]->nombre_tipo_examen;
             $_SESSION["orden"][str_replace(" ","_",$orden_[0]->nombre_examen)]["precio_examen"] = $orden_[0]->precio_examen;
             $_SESSION["orden"][str_replace(" ","_",$orden_[0]->nombre_examen)]["examen_id"] = $orden_[0]->id_examen;
             $_SESSION["orden"][str_replace(" ","_",$orden_[0]->nombre_examen)]["categoria_orden"] = $orden_[0]->nombre_categoria;
             self::json(["response" => "add"]);
          }else{
             self::json(["response" => "existe"]);
          }
       }else{
          self::json(["error" => "consulta-no-valida"]);
       }
    }
 }


 /// MODIFICAR EL PRECIO DE LA ORDEN MEDICA
 public static function ModifyPriceOrdenMedica(){
   if(self::ValidateToken(self::post("token_"))){

      $orden = new Orden;

      $examenData = $orden->query()->Where("codigo_orden","=",self::post("codeorden"))->get();

      if(isset($_SESSION["orden"][str_replace(" ","_",$examenData[0]->nombre_examen)])){
         $_SESSION["orden"][str_replace(" ","_",$examenData[0]->nombre_examen)]["precio_examen"]= self::post("new_precio");
         self::json(["response" => "PRECIO MODIFICADO CORRECTAMENTE!!!"]);
      }else{
         self::json(["error" => "ERROR AL MODIFICAR EL PRECIO DE LA ÓRDEN!!!"]);
      }
   }else{
      self::json(["error" => "ERROR, EL TOKEN ES INCORRECTO!!!"]);
   }
 }

    /** MOSTRAR LOS EXAMENES QUE TIENE QUE SACARSE EL PACIENTE */
    public static function showOrdersDelPaciente(){

      self::NoAuth();
       
      if(self::profile()->rol === 'Médico'){
         if(self::ExistSession("orden")){
            self::json(["orders" => self::getSession("orden")]);
         }else{
            self::json(["orders" =>[]]);
         }
      }else{
         PageExtra::PageNoAutorizado();
      }
   }

   /**QUITAR LA ORDEN */
   public static function quitarLaOrden($id){
      self::NoAuth();
      if(self::ValidateToken(self::post("token_"))){
         $order = new Orden;
         $order_ = $order->query()->Where("id_examen","=",$id)->get();
         if(isset($_SESSION["orden"][str_replace(" ","_",$order_[0]->nombre_examen)])){
            unset($_SESSION["orden"][str_replace(" ","_",$order_[0]->nombre_examen)]);
            self::json(["response" => "ok"]);
         }else{
            self::json(["error" => "error al eliminar"]);
         }
      }else{
         self::json(["error" => "consulta-no-valida"]);
      }
   }


   /** HISTORIAL DE ORDENES */
   public static function historialOrdenes(){
      self::NoAuth();
      if(self::profile()->rol === 'Médico'){
         self::View_("medico.historial-ordenes");
      }else{
         PageExtra::PageNoAutorizado();
      }
   }

   /** VER LAS ORDENES REGISTRADOS */
   public static function showOrdenesHistorial(){
       self::NoAuth();
      if(self::profile()->rol === 'Médico'){
        $orden = new OrdenMedico;

        $medico = self::MedicoData()->id_medico;
        $historial_orden  = $orden->query()->Join("paciente as pc","om.paciente_id","=","pc.id_paciente")
        ->Join("persona as p","pc.id_persona","=","p.id_persona")
        ->Join("atencion_medica as atm","om.atencion_id","=","atm.id_atencion_medica")
        ->Join("triaje as t","atm.id_triaje","=","t.id_triaje")
        ->Join("cita_medica as ct","t.id_cita_medica","=","ct.id_cita_medica")
        ->Where("ct.id_medico","=",$medico)
        ->get();

        self::json(["historial_orden"=>$historial_orden]);
      }else{
         self::json(["error" => "NO ESTAS AUTHORIZADO PARA VER EL HISTORIAL DE ORDENES REGISTRADOS!!!"]);
      }
   }

   /** ELIMINAR LA ORDEN MEDICA */
   public static function eliminarOrden($id){
       self::NoAuth();
      if(self::profile()->rol === 'Médico'){
         if(self::ValidateToken(self::post("token_"))){
            $orden = new OrdenMedico;

            $response = $orden->delete($id);

            if($response){
               self::json(["response" => 'ORDEN MEDICA ELIMINADO CORRECTAMENTE!!!']);
            }else{
               self::json(["error" => "ERROR AL ELIMINAR LA ORDEN MÉDICA SELECCIONADO!!"]);
            }
         }else{
            self::json(["error" => "TOKEN INCORRECTO!!"]);
         }
      }else{
         self::json(["error" => "NO ESTAS AUTHORIZADO PARA REALZIAR ESTE PROCESO!!!"]);
      }
   }
}