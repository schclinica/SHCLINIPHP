<?php
namespace lib;

use models\Empresa;
use models\Medico;
use models\Notificaciones;
use models\Paciente;
use models\Usuario;

trait Session 
{
  use Request;
/*============================
Generar una variable de session
===============================*/
public static function Session(string $NameSession,$valor)
{
    $_SESSION[$NameSession] = $valor;
}
/*============================
Verificar si una variable de session
existe
===============================*/

public static function ExistSession(String $NameSession)
{
    return isset($_SESSION[$NameSession]);
}
/*============================
Obtener el valor de una variable
de session
===============================*/
public static function getSession(string $NameSession)
{
  return self::ExistSession($NameSession) ? $_SESSION[$NameSession]:'';
}
/*============================
Eliminar una variable de session
en especifico
===============================*/

public static function destroySession(string $NamseSession)
{
    if(self::ExistSession($NamseSession))
     unset($_SESSION[$NamseSession]);
}

/*============================
Eliminar toda variable de session
===============================*/

public static function destroyFullSession()
{
    session_destroy();
}

/*============================
Eliminar la Cookie
===============================*/

public static function DestroyCookie(string $NameCookie)
{
  if(existeCookie($NameCookie))
  {
    unset($_COOKIE[$NameCookie]); /// eliminamos la variable cookie
    /// destruimos la cookie
    setcookie($NameCookie,"",time()-3600,"/");
  }
}
 /// recordar la session
 public static function SessionSistema($Recordar,$data)
 {
   if($Recordar)
   {
    /// creamos una cookie
    cifrarCookie($data);
   }
   else
   {
    self::Session("remember",$data);
   }
   
 }

 /// verificamos si esta authenticado
 public static function Auth()
 {
  if(existeCookie("remember") || self::ExistSession("remember"))
  {
    /// redirigimos al dashboard
    self::RedirectTo("dashboard");
    exit;/// finaliza
  }
 }

 public static function authenticado()
 {
  if(existeCookie("remember") || self::ExistSession("remember"))
  {
    /// redirigimos al dashboard
   return true;
  }
 }


  /// verificamos si no esta authenticado
  public static function NoAuth()
  {
    if(!isset(self::profile()->rol) || self::profile()->estado === '2')
      {
        if(isset($_COOKIE['remember']))
        {
           /// elimino la variable de la cookie
           unset($_COOKIE['remember']);

           self::DestroyCookie("remember");
           
        }
        session_destroy();
        self::RedirectTo("login");
      }

   if(!existeCookie("remember") and !self::ExistSession("remember"))
   {
     /// redirigimos al dashboard
     self::RedirectTo("./");
     exit;/// finaliza
   }
  }

  
  public static function NoAuthenticado()
  {
   if(!existeCookie("remember") and !self::ExistSession("remember"))
   {
     /// redirigimos al dashboard
      return true;
   }
  }
 
 /// Cerrar la session del sistema
 public static function logout_()
 {
  if(self::ExistSession("remember"))
  {
    self::destroySession("remember");/// eliminamos la session
  }

  self::DestroyCookie("remember");
  self::destroyFullSession();

  /// redirigimos 
  self::RedirectTo("login");
  exit;
 }
 /// obtenemos el perfil del usuario
public static function profile()
{
  $Usuario = null;
  /// verificamos, si el usuario no dio recordar la session
  if(self::ExistSession("remember"))
  {
    $Usuario = self::getSession("remember");
  }

  if(existeCookie("remember"))
  {
    $Usuario = getValueCookie();
  }

  // consultamos 
  $user = new Usuario;

  return $user->query()
  ->LeftJoin("persona as p","p.id_usuario","=","u.id_usuario")
  ->LeftJoin("sedes as s","u.sede_id","=","s.id_sede")
  ->LeftJoin("distritos as dis","s.distritosede_id","=","dis.id_distrito")
  ->LeftJoin("provincia as pr","dis.id_provincia","=","pr.id_provincia")
  ->LeftJoin("departamento as dep","pr.id_departamento","=","dep.id_departamento")
  ->Where("u.id_usuario","=",$Usuario)->first();
}

/// obtener paciente
public static function PacienteData()
{
  $modelPaciente = new Paciente;
  
  $Persona_Id = self::profile()->id_persona;
  return $modelPaciente->query()->Where("id_persona","=",$Persona_Id)->first();
}

/// mostrar datos de la empresa
public static function BusinesData()
{
  $modelBusiness = new Empresa;

  $response = $modelBusiness->query()->get();

  return $response;

}

/**
 * Obenemos datos del médico, de acuerdo a la persona que inicio sesioon
 */
public static function MedicoData()
{
  if(isset(self::profile()->id_persona))
  {
    // obtenemomos el id de la persona logueada al sistema
    $PersonaId = self::profile()->id_persona;

    $MedicoModel = new Medico;

    return $MedicoModel->query()->Where("id_persona","=",$PersonaId)->first();
  }
}

/// ver la cantidad de notificaciones
public static function CantidadNotificaciones()
{
  if(self::profile()->rol === 'Admisión' || self::profile()->rol === 'Médico')
  {
  $modelNotificaciones = new Notificaciones;

  if(self::profile()->rol === 'Admisión'){
  $sede = self::profile()->sede_id;
  $CantidadNotificaciones = $modelNotificaciones->query()
  ->select("count(*) as cantidad_notificaciones")
  ->Where("estado_not","=","sa")
  ->And("sede_id","=",$sede)
  ->And("fecha_cita","=",self::FechaActual("Y-m-d"))->first();
  }else{
    $medicoId = self::MedicoData()->id_medico;
    $CantidadNotificaciones = $modelNotificaciones->query()
  ->select("count(*) as cantidad_notificaciones")
  ->Where("estado_not","=","sa")
  ->And("nf.medico_id","=",$medicoId)
  ->And("fecha_cita","=",self::FechaActual("Y-m-d"))->first();
  }
  return $CantidadNotificaciones;
  }
  return null;
}
}