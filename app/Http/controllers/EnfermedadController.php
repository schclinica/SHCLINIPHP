<?php
namespace Http\controllers;

use business\EnfermedadBusiness;
use Http\pageextras\PageExtra;
use lib\BaseController;
use models\Enfermedad;

class EnfermedadController extends BaseController{
   private static string  $TipoArchivoAceptable = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
   private static array $Errors = [];
    /// método para mostra la vista de gestionar enfermedades
    public static function index(){
      self::NoAuth();
       if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general' || self::profile()->rol=== 'Médico'){
         self::View_("enfermedades.index");
       }else{
         PageExtra::PageNoAutorizado();
       }
    } 

    /// registrar a la enfermedad
    public static function store(){
      self::NoAuth();
      if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general' || self::profile()->rol=== 'Médico'){
       if(self::ValidateToken(self::post("token_"))){

         if(self::post("codigo") == null){
            self::$Errors["codigo"] = "Ingrese el código de la enfermedad!!!";
             
         }
         if(self::post("enfermedad") == null){
            self::$Errors["enfermedad"] = "Ingrese nombre de la enfermedad!!";
         }

         if(count(self::$Errors) > 0){
            self::json(["errors" => self::$Errors]);
            exit;
         }
         $response = EnfermedadBusiness::saveEnfermedad(self::post("enfermedad"),self::post("descripcion"),self::post("codigo"));
         self::json(["response" => $response]);
       }else{
         self::json(["error_token" => "Error, token invalid!!!"]);
       }
      }else{
         self::json(["response" => "no-autorized"]);
      }
    }

    /// MOSTRAR LAS ENFERMEDADES
    public static function mostrar(){
      self::NoAuth();
      if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general' || self::profile()->rol=== 'Médico'){
       self::json(["enfermedades"=>EnfermedadBusiness::showEnfermedades()]);
      }else{
         self::json(["response" => "no-authorized!!!"]);
      }
    }

   /// MOSTRAR LAS ENFERMEDADES QUE ESTAN HABILITADAS
   public static function mostrarHabilitados()
   {
      self::NoAuth();
      if (self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general' || self::profile()->rol === 'Médico') {
         self::json(["response" => EnfermedadBusiness::showEnfermedadesHabilitadas()]);
      } else {
         self::json(["response" => "no-authorized!!!"]);
      }
   }

    /// ELIMINAR A LA ENFERMEDAD (SOFTDELETE) => ELIMINACION SUAVE
    public static function eliminarEnfermendad($id){
      self::NoAuth();
      if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general' || self::profile()->rol=== 'Médico'){
       if(self::ValidateToken(self::post("token_"))){
            self::json(["response" => EnfermedadBusiness::eliminar($id,self::FechaActual("Y-m-d H:i:s")) ? 'ok' :'error']);
       }else{
        self::json(["error_token" => "error token invalid!!!"]);
       }
      }else{
         self::json(["response"=>"no authorized!!!"]);
      }
    }

    /// ELIMINAR A LA ENFERMEDAD (SOFTDELETE) => ELIMINACION SUAVE
    public static function Habilitar($id){
      self::NoAuth();
      if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general' || self::profile()->rol=== 'Médico'){
      if(self::ValidateToken(self::post("token_"))){
           self::json(["response" => EnfermedadBusiness::HabilitarEnfermedad($id) ? 'ok' :'error']);
      }else{
       self::json(["error_token" => "error token invalid!!!"]);
      }
   }else{
      self::json(["respose" => "no authorized!!!"]);
   }
   }

   /// METODO PARA ACTUALIZAR DATOS DE LA ENFERMEDAD
   public static function update($id){
      self::NoAuth();
      if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general' || self::profile()->rol=== 'Médico'){
    if(self::ValidateToken(self::post("token_"))){

            if (self::post("codigo_editar") == null) {
               self::$Errors["codigo_editar"] = "Ingrese el código de la enfermedad!!!";
            }
            if (self::post("enfermedad_editar") == null) {
               self::$Errors["enfermedad_editar"] = "Ingrese nombre de la enfermedad!!";
            }

            if (count(self::$Errors) > 0) {
               self::json(["errors" => self::$Errors]);
               exit;
            }
         self::json(["response" => EnfermedadBusiness::modificarEnfermedad($id,self::post("enfermedad_editar"),self::post("descripcion_editar"),
         self::post("codigo_editar"))]);
    }else{
     self::json(["error_token" => "error token invalid!!!"]);
    }  
   }else{
      self::json(["response" => "no authorized!!!"]);
   }
   }

   /// BORAR A LA ENFERMEDAD
   public static function borrar($id){
      self::NoAuth();
      if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general' || self::profile()->rol=== 'Médico'){
     if(self::ValidateToken(self::post("token_"))){
         self::json(["response" => EnfermedadBusiness::borrarEnfermedad($id)]);
     }else{
      self::json(["error_token" => "error token invalid!!!"]);
     } 
   }else{
      self::json(["response" => "no authorized!!!"]);
   }
   } 

   /**
    * Para visualizar la vista del reporte de enfermedades
    */
    public static function reporteEnfermedades(){
      self::NoAuth();
      if(self::profile()->rol === 'Director' || self::profile()->rol === 'admin_general'){
         self::View_("reportes.enfermedades");
      }else{
         PageExtra::PageNoAutorizado();
      }
    }
    /**
     * MOSTRAR EL REPORTE DE ENFERMEDADES POR AÑO, MES Y DIARIO
     */
    public static function reportStadisticoEnfermedades(string $opc = 'anio'){
      self::NoAuth();
      $enfermedad = new Enfermedad;

      $enfermedades = $enfermedad->procedure("proc_reporte_enfermedades","C",[self::FechaActual("Y-m-d"),$opc]);

      self::json(["enfermedades" => $enfermedades]);
    }

    /**
     * PROCESO PARA IMPORTAR DATOS DESDE EXCEL A LA TABAL ENFERMEDADES
     */
   public static function importar()
   {
      self::NoAuth();
      if (self::ValidateToken(self::post("token_"))) {
         /**
          * Obtenemos al file seleccionado, si o si tiene que ser un excel
          */
         if (self::file_size("archivo_excel_enfermedades") > 0) {
            # Verificamos si el archivo seleccionado es un excel
            if (self::file_Type("archivo_excel_enfermedades") === self::$TipoArchivoAceptable) {
               $ArchivoSelect = self::ContentFile("archivo_excel_enfermedades");
               self::json(["response" => EnfermedadBusiness::ImportarDatosEnfermedad($ArchivoSelect)]);
            } else {
               self::json(['response' => "archivo no aceptable"]);
            }
         } else {
            self::json(['response' => "vacio"]);
         }
      }else{
         self::json(["error" => "token invalid!!"]);
      }
   }

   /** ASIGNAR LA ENFERMEDAD COMO DIAGNOSTICO AL PACIENTE */
   public static function AsignarEnfermedadDiagnostico($id){

      self::NoAuth();
      if(self::ValidateToken(self::post("token_"))){

         /// hacemos la consulta de la enfermedad
         $Enfermedad = new Enfermedad;

         $enfermedad_ = $Enfermedad->query()->Where("id_enfermedad","=",$id)->get();

         if($enfermedad_){
            if(!self::ExistSession("diagnostico")){
               self::Session("diagnostico",[]);
            }
   
            if(!array_key_exists(str_replace(" ","_",$enfermedad_[0]->enfermedad),$_SESSION["diagnostico"])){
               $_SESSION["diagnostico"][str_replace(" ","_",$enfermedad_[0]->enfermedad)]["enfermedad_desc"] = $enfermedad_[0]->enfermedad;
               $_SESSION["diagnostico"][str_replace(" ","_",$enfermedad_[0]->enfermedad)]["tipo"] = "p";
               $_SESSION["diagnostico"][str_replace(" ","_",$enfermedad_[0]->enfermedad)]["enfermedad_id"] = $enfermedad_[0]->id_enfermedad;

               self::json(["response" => "add"]);
            }else{
               self::json(["response" => "existe"]);
            }
         }else{
            self::json(["error" => "consulta-no-valida"]);
         }
      }
   }


    /** ASIGNAR LA ENFERMEDAD COMO DIAGNOSTICO AL PACIENTE */
   public static function showDiagnosticosDelPaciente(){

      self::NoAuth();
       
      if(self::profile()->rol === 'Médico'){
         if(self::ExistSession("diagnostico")){
            self::json(["diagnosticos" => self::getSession("diagnostico")]);
         }else{
            self::json(["diagnosticos" =>[]]);
         }
      }else{
         PageExtra::PageNoAutorizado();
      }
   }

    /** ASIGNAR LA ENFERMEDAD COMO DIAGNOSTICO AL PACIENTE */
    public static function EliminarEnfermedadDiagnostico($id){

      self::NoAuth();
      if(self::ValidateToken(self::post("token_"))){
         $enfermedad = new Enfermedad;
         $enfermedad_ = $enfermedad->query()->Where("id_enfermedad","=",$id)->get();
         if(isset($_SESSION["diagnostico"][str_replace(" ","_",$enfermedad_[0]->enfermedad)])){
            unset($_SESSION["diagnostico"][str_replace(" ","_",$enfermedad_[0]->enfermedad)]);
            self::json(["response" => "ok"]);
         }else{
            self::json(["error" => "error al eliminar"]);
         }
      }else{
         self::json(["error" => "consulta-no-valida"]);
      }
   }

   /** MODIFICAR EL TIPO DE DIAGNOSTICO */
   public static function ModificarEnfermedadDiagnostico($id){

      self::NoAuth();
      if(self::ValidateToken(self::post("token_"))){
         $enfermedad = new Enfermedad;
         $enfermedad_ = $enfermedad->query()->Where("id_enfermedad","=",$id)->get();
         if(isset($_SESSION["diagnostico"][trim(str_replace(" ","_",$enfermedad_[0]->enfermedad))])){
           $_SESSION["diagnostico"][trim(str_replace(" ","_",$enfermedad_[0]->enfermedad))]["tipo"]= self::post("tipo");
            self::json(["response" => "ok"]);
         }else{
            self::json(["error" => "error al modificar"]);
         }
      }else{
         self::json(["error" => "consulta-no-valida"]);
      }
   }
}