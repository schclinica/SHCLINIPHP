<?php
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\CitaMedica;
use models\Especialidad;

class PagoMedicoController extends BaseController
{
     /// Ver la vista de citas realizados del médico
     public static function ViewCitasRealizadosPago(){
        self::NoAuth();
        if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general')
        {
            /// mostrar todas las especialidades existentes
            $modelespecialidad = new Especialidad;
            $especialidades = $modelespecialidad->query()->Where("estado","=",1)->get();
           self::View_("medico.pagarmedico",compact("especialidades")); 
        }else{
            PageExtra::PageNoAutorizado();
        }
      }
      // ver las citas realizadas por médico para saber cuanto recauda para su pago
      public static function citasRealizadosPago(string $opcion,$medicoid,$fecha_inicio,$fecha_fin)
      {
        self::NoAuth();
        if(self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general')
        {
            $modelcita = new CitaMedica;

            $citaspormedico = $modelcita->procedure("proc_consultar_pago_medico","C",[$opcion,$medicoid,self::FechaActual("Y-m-d"),$fecha_inicio,$fecha_fin]);

            self::json(["citas" => $citaspormedico]);
        }else{
            self::json(["citas" => []]);
        }
      }
}