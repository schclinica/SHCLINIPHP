<?php 
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\Testimonio;

class TestimonioController extends BaseController
{
/// mostrar todos los testimonios
public static function index()
{
    self::NoAuth();
    if(self::profile()->rol === 'Paciente' and isset(self::profile()->id_persona))
    {
        self::View_("paciente.testimonios");
    }else{
        PageExtra::PageNoAutorizado();
    }
}

/// guardar nuevo testimonio
public static function save()
{
    self::NoAuth();
    if(self::profile()->rol === 'Paciente')
    {
     
     if(self::ValidateToken(self::post("_token")))
     {
        $PacienteId = self::PacienteData()->id_paciente;
        $modelTestimonio = new Testimonio;
        $ExisteEnLaMismaFecha = $modelTestimonio->query()->Where("substr(fechahora,1,10)","=",self::FechaActual("Y-m-d"))
        ->And("paciente_id","=",$PacienteId)
        ->first();

        if($ExisteEnLaMismaFecha)
        {
            $respuesta = 'existe';
        } else{
        $respuesta = $modelTestimonio->Guardar(
           self::post("desc_testimonio"),
           self::FechaActual("Y-m-d H:i:s"),
           $PacienteId);
        }
        self::json(["response" => $respuesta]);
     }else{
        self::json(["response" => "token-invalidate"]);
     }
    }else{
        self::json(["response" => "no-authorized"]);
    }
}

//// modificar el testimonio
/// guardar nuevo testimonio
public static function modificar(int $id)
{
    self::NoAuth();
    if(self::profile()->rol === 'Paciente')
    {
     
     if(self::ValidateToken(self::post("_token")))
     {
        $modelTestimonio = new Testimonio;
         
        $respuesta = $modelTestimonio->Update([
            "id_testimonio" => $id,
            "descripcion_testimonio" => self::post("desc_testimonio"),
        ]);
        
        self::json(["response" => $respuesta]);
     }else{
        self::json(["response" => "token-invalidate"]);
     }
    }else{
        self::json(["response" => "no-authorized"]);
    }
}

/// mostrar los testimonios
public static function showTestimonios()
{
 self::NoAuth();
 if(self::profile()->rol === 'Paciente')
 {
    $modelTestimonio = new Testimonio;

    $PacienteId = self::PacienteData()->id_paciente;
    $datos = $modelTestimonio->query()
    ->Where("paciente_id","=",$PacienteId)->get();

    self::json(["testimonios"=> $datos]);
 }else{
    self::json(["testimonios" => []]);
 }
}

/// elimina el testimonio publicado
public static function Eliminar(int $id)
{
    self::NoAuth();
    if(self::profile()->rol === 'Paciente')
    {
     
     if(self::ValidateToken(self::post("_token")))
     {
        $modelTestimonio = new Testimonio;

        $respuesta = $modelTestimonio->delete($id);
        self::json(["response" => $respuesta]);
     }else{
        self::json(["token-invalidate"]);
     }

    }else{
        self::json(["response" => "no-authorized"]);
    }
}
}