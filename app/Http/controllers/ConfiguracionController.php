<?php 
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use lib\BaseDatos;
use models\Configuracion;
use report\implementacion\Model;

class ConfiguracionController extends BaseController
{
  use BaseDatos;

  private static $ModelConfig;

    /// mostrar la vista de configurar empresa
    public static function index()
    {
      self::NoAuth();
      if(isset(self::profile()->rol))
      {
        if(self::profile()->rol === 'Director' ||  self::profile()->rol === 'admin_farmacia' ||  self::profile()->rol === 'admin_general')
        {
          
          self::View_("business.informacion");
        }
        else
        {
          PageExtra::PageNoAutorizado();
        }
      }
      else
      {
        self::View_("business.informacion");
      }
    }

    // Registrar la configuración de la empresa

    public static function storeHorarioBusiness()
    {
      self::NoAuth();
      /// Validamos el token 
      if(self::ValidateToken(self::post("token_")))
      {
        self::$ModelConfig = new Configuracion;
        /// mandar registro a la BD
        self::json(["response"=>self::$ModelConfig->RegistroHorarioBusiness(self::post("dias"),self::post("hi"),self::post("hf"))]);
      }
    }

    /// validamos la existencia al listar
    public static function existeBeforeList()
    {
      self::NoAuth();
      /// validamos el token
      if(self::ValidateToken(self::get("token_")))
      {
        self::$ModelConfig = new Configuracion;
        /// verificamos la existencia

        $Resultado=  self::$ModelConfig->getFilterDato(self::get("dia")) ? 'existe':'no-existe';

        self::json(['response'=>$Resultado]);
      }
    }

  /// mostrando los días para asignar el horario al médico
 public static function showDias($medico)
 {
  self::NoAuth();

  if(self::ValidateToken(self::get("token_")))
  {
    self::$ModelConfig = new Configuracion;
    /// mostramos los días
    $Dias = self::$ModelConfig->procedure("proc_dias_no_asigned_horario","c",[$medico]);

    self::json($Dias);
  }

 }

/*procedimiento  para mostrar horarios de atención 
médica que aún no tiene horas programados para la reserva de citas
*/
    
public static function DiasAtencion_No_Programados($medico)
{
  self::NoAuth();
  self::$ModelConfig = new Configuracion;
  /// mostramos los días
  $Dias_No_Programados = self::$ModelConfig->procedure("proc_view_atencion_no_programada","c",[$medico]);

  self::json(['dias'=>$Dias_No_Programados]);
}

/// mostrar los días de atención de EsSalud
public static function mostrar_dias_atencion()
{
  self::NoAuth();
  if(self::ValidateToken(self::get("token_")))
  {
    self::$ModelConfig = new Configuracion;

    $data = self::$ModelConfig->query()
    ->orderBy("id_data_empresa","asc")
    ->get();

    self::json(['response'=>$data]);
  }
}

/// cambiar de estado a los dias de atención de EsSalud
public static function cambiar_estado_dia_atencion($id,$estado)
{
  self::NoAuth();

  if(self::ValidateToken(self::post("token_")))
  {
    self::$ModelConfig = new Configuracion;
    $resultado =self::$ModelConfig->Update([
      "id_data_empresa"=>$id,
      "laborable"=>$estado
    ]);

   if($resultado)
   {
    self::json(['response'=>'ok']);
   }
   else
   {
    self::json(['response'=>'error']);
   }
  }
}

/// realiza el proceso de la copia de seguridad
public static function copia_bd()
{
 self::NoAuth();
 
 if(self::ValidateToken(self::post("token_")))
 {
  if(self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[3])
 {
    if(empty(self::post("copia_")))
    {
      self::Session("mensaje_error","Ingrese el nombre del respaldo");
      self::RedirectTo("Configurar-datos-empresa-EsSalud");
    }
    else
    {
      self::copia(self::post("copia_"));
    }
 }
 else
 {
  PageExtra::PageNoAutorizado();
 }
 }
}

/// restaurar sistema
public static function restore_sistema()
{

  if(self::ValidateToken(self::post("token_")))
  {
      # verificamos el tipo de archivo que se aceptarán
      if(self::file_Type("archivo_bd") === "application/octet-stream")
      {
       self::restaurarSistema(self::ContentFile("archivo_bd"));
      }
      else
      {
        self::json(['mensaje'=>'error-archivo']);
      }
  }
}

/// modificar el horario de atencion
public static function updateHorario($id)
{
  self::NoAuth();
  if(self::ValidateToken(self::post("token_")))
  {
    self::$ModelConfig = new Configuracion;

    $respuesta = self::$ModelConfig->Update([
    "id_data_empresa"=>$id,
    "horario_atencion_inicial"=>self::post("hi"),
    "horario_atencion_cierre"=>self::post("hc")
    ]);

    self::json(['response'=>$respuesta?'ok':'error']);
  }
}
    
/// mostrar la vista de configuración del sistema
public static function configSistema()
{
  self::NoAuth();

  self::View_("sistema_setting");
}

/// proceso para cambiar colores al menu del sistema
public static function cambiarColorMenu()
{
  self::NoAuth();

  if(self::ValidateToken(self::post("_token")))
  {
   
   
    // if(!self::ExistSession("color_texto"))
    // {
    //   self::Session("color_texto",0);
    // }

    self::Session("color_fondo",self::post("color_fondo"));

    self::Session("color_texto",self::post("color_texto"));
    self::json(["response"=>self::getSession("color_fondo"),"color_texto"=>self::getSession("color_texto")]);
  }
}

public static function CancelarColorMenu()
{
  self::NoAuth();

  if(self::ValidateToken(self::post("_token")))
  {
   
    self::Session("color_fondo","#F8F8FF");

    self::Session("color_texto","#E6E6FA"); /// RosalesDemo@3400
    self::json(["response"=>self::getSession("color_fondo"),"color_texto"=>self::getSession("color_texto")]);
  }
}
}