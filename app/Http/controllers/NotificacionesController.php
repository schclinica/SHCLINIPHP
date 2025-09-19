<?php
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\Especialidad_Medico;
use models\Notificaciones;

use function Windwalker\where;

class NotificacionesController extends BaseController
{
 /// método para visualizar la página de notificaciones del día
 public static function index()
 {
    self::NoAuth();
    if(self::profile()->rol === 'Admisión' || self::profile()->rol === 'Médico')
    {
       self::View_("cita_medica.notificaciones");
    }else{
        PageExtra::PageNoAutorizado();
    }
 }

 /// mostrar los médicos existentes acorde a una especialidad seleccionado
 public static function mostrarMedicosPorEspecialidad($id)
 {
       
       $modelmedicoesp = new Especialidad_Medico;
       $sede = self::get("sede");
       $respuestaMedicos = $modelmedicoesp->query()
       ->Join("especialidad as es","med_esp.id_especialidad","=","es.id_especialidad")
       ->Join("medico as m","med_esp.id_medico","=","m.id_medico")
       ->Join("persona as p","m.id_persona","=","p.id_persona")
       ->select("concat(p.apellidos,' ',p.nombres) as doctor","m.id_medico")
       ->Where("es.id_especialidad","=",$id)
       ->And("m.medicosede_id","=",$sede)
       ->get();

       self::json(["response" => $respuestaMedicos]);
     
 }

 /// procesar la solicitud 
 public static function saveSolicitud()
 {
   /// Validamos el token
   if(self::ValidateToken(self::post("_token")))
   {
      $modelNotificacion = new Notificaciones;

      /// validamos si existe la misma persona quien solicita la cita en la misma fecha
      $ExisteSolicirud = $modelNotificacion->query()
      ->where("num_documento","=",trim(self::post("documento")))
      ->And("especialidad_id","=",self::request("especialidad"))
      ->And("medico_id","=",self::request("doctor"))
      ->And("estado_not","=","sa")
      ->And("sede_id","=",self::post("sede"))
      ->And("fecha_cita","=",self::FechaActual("Y-m-d"))->first();
      if($ExisteSolicirud)
      {
         $respuesta = 'existe';
      }else{
         $respuesta = $modelNotificacion->Insert([
            "tipo_doc_id" => self::post("tipodoc"),
            "num_documento" => self::post("documento"),
            "nombre_remitente" => self::post("name"),
            "email" => self::post("email"),
            "celular" => self::post("phone"),
            "fecha_cita" => self::post("fecha_solicitud"),
            "especialidad_id" => self::post("especialidad"),
            "medico_id" => self::request("doctor"),
            "sede_id" => self::post("sede"),
            "mensaje" => self::request("message") 
         ]);
      }
      self::json(["response" => $respuesta]);
   }else{
      self::json(["response" => "token-invalidate"]);
   }
 }

 /// mostrar las solicitudes de citas registradas de personas que visitan la página
 public static function mostrarLasNotificaciones()
 {
   self::NoAuth();
   if(self::profile()->rol === 'Admisión' || self::profile()->rol === 'Médico')
   {
      $modelNotificaciones = new Notificaciones;
       $sede = self::profile()->sede_id;
      if(self::profile()->rol === 'Admisión')
      {
         $Notificaciones = $modelNotificaciones->query()
         ->Join("especialidad as es","nf.especialidad_id","=","es.id_especialidad")
         ->Join("medico as med","nf.medico_id","=","med.id_medico")
         ->Join("tipo_documento as td","nf.tipo_doc_id","=","td.id_tipo_doc")
         ->Join("persona as p","med.id_persona","=","p.id_persona")
         ->Join("sedes as s","nf.sede_id","=","s.id_sede")
         ->Where("fecha_cita","=",self::FechaActual("Y-m-d"))
         ->And("med.medicosede_id","=",$sede)
         ->get();
      }else{
         $MedicoId = self::MedicoData()->id_medico;
         $Notificaciones = $modelNotificaciones->query()
         ->Join("especialidad as es","nf.especialidad_id","=","es.id_especialidad")
         ->Join("medico as med","nf.medico_id","=","med.id_medico")
         ->Join("tipo_documento as td","nf.tipo_doc_id","=","td.id_tipo_doc")
         ->Join("persona as p","med.id_persona","=","p.id_persona")
         ->Join("sedes as s","nf.sede_id","=","s.id_sede")
         ->Where("nf.medico_id","=",$MedicoId)
          ->And("med.medicosede_id","=",$sede)
         ->And("fecha_cita","=",self::FechaActual("Y-m-d"))->get();
      }
      self::json(["response" => $Notificaciones]);
   }else{
      self::json(["response" => []]);
   }
 }

 /// Atender la solicitud de la persona
 public static function AtenderRechazarSolicitud(int $id,string $estado)
 {
   self::NoAuth();

   if(self::profile()->rol === 'Médico' || self::profile()->rol === 'Admisión')
   {
      if(self::ValidateToken(self::post("_token")))
      {
         $modelNotificacion = new Notificaciones;

         $respuesta = $modelNotificacion->Update([
            "id_notificacion" => $id,"estado_not" => $estado
         ]);

        if($respuesta)
        {
         $estado === 'a' ? self::json(["response" => "ok-atendido"]):self::json(["response" => "ok-rechazado"]);
        }
      }else{
         self::json(["response" => "token-invalidate"]);
      }
   }else{
      self::json(["response" => "no-authorized"]);
   }
 }
}