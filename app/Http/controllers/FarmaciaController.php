<?php
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\Embalaje;
use models\GrupoTerapeutico;
use models\Laboratorio;
use models\Presentacion;
use models\Proveedor;
use models\Sede;
use models\TipoDocumento;
use models\TipoProducto;
use models\VentasFarmacia;
use Windwalker\Utilities\Attributes\Prop;

class FarmaciaController extends BaseController
{
 /** Método para mostrar la vista principal de la farmacia */
 public static function index()
 {
  self::NoAuth();
    /// validamos que este authenticado
    /// modificado
    if(self::profile()->rol === self::$profile[5] || self::profile()->rol === self::$profile[0] || self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {  $almacenes = [];
       // enviamos los tipos de documentos existentes
       $modelTipoDoc = new TipoDocumento; $modelVenta = new VentasFarmacia;
       $TipoDocumentos =  $modelTipoDoc->query()->Where("estado","=","1")->get();
       $IndexVenta = $modelVenta->ObtenerMaxVenta()->num_venta;
       if(self::profile()->rol === "admin_general" || self::profile()->rol === "admin_farmacia"){
        /// mostramos las sucursales
        $almacen = new Sede;
        $almacenes = $almacen->query()->Where("deleted_at","is",null)->get();
       }
       self::View_("farmacia.index",compact("TipoDocumentos","IndexVenta","almacenes"));
    }else{
        PageExtra::PageNoAutorizado();
    }
 }

 

 /** Gaurdar el tipo de producto de la farmacia */
 public static function saveTipoProducto()
 {
   self::NoAuth();
   if( self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
   {
    if(self::ValidateToken(self::post("_token")))
    {
      $modelTipoProducto = new TipoProducto;
      $ExisteTipo = $modelTipoProducto->query()->Where("name_tipo_producto","=",trim(self::post("name_tipo_producto")))->get();

      if(!$ExisteTipo)
      {
         $response = $modelTipoProducto->Insert([
            "name_tipo_producto" => self::post("name_tipo_producto"),
         ]);
      }else{
         $response = 'existe';
      }

      self::json(["response" => $response]);
    }else{
      self::json(["response" => "token-invalidate"]);
    }
   }else{
     self::json(["response" => "no-authorized"]); 
   }
 }
 /**Mostrar los tipos de productos de la farmacia */
 public static function mostrarTipoProductos($mostrar_todos = 'si')
 {
   self::NoAuth();
   if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
   {
      $modelTipoProducto = new TipoProducto;
      $respuesta =  $mostrar_todos === 'si' ? $modelTipoProducto->query()->get():$modelTipoProducto->query()->Where("deleted_at","is",null)->get() ;

      self::json(["response"=>$respuesta]);
   }else{
     self::json(["response" => []]); 
   }

 }
  /// modificar los datos de tipo de productos
  public static function modificarTipoProducto(int $id)
  {
     self::NoAuth();
     if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
     {
      if(self::ValidateToken(self::post("_token")))
      {
         $modelTipo = new TipoProducto;
         $response = $modelTipo->Update([
            "id_tipo_producto" => $id,
            "name_tipo_producto" => self::post("name_tipo_producto")
         ]);
         self::json(["response" => $response]);
      }else{
         self::json(["response" => "token_invalidate"]);
      }
     }else{
         self::json(["response" => "no-authorized"]);
     }
  }

  /// Habilitar e inhabilitar el tipo de producto
  public static function HabilitarInhabilitarTipoProducto(int $id,string $Condition)
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
     if(self::ValidateToken(self::post("_token")))
     {
      $modelTipo = new TipoProducto;

      $response = $modelTipo->Update([
        "id_tipo_producto" => $id,
        "deleted_at" => $Condition === 'i' ? self::FechaActual("Y-m-d H:i:s"):null
      ]);
      self::json(["response" => $response]);
     }else{
      self::json(["response" => "token-invalidate"]);
     }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }
  /** Eliminar el tipo de producto de la base de datos */
  public static function DeleteTipoProducto(int $id)
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
     if(self::ValidateToken(self::post("_token")))
     {
       $modelTipoProducto = new TipoProducto;

       $response = $modelTipoProducto->delete($id);
       self::json(["response" => $response]);
     }else{
      self::json(["response" => "token-invalidate"]);
     }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }

  /** Registrar una nueva presentación */
  public static function savePresentacion()
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
      if(self::ValidateToken(self::post("_token")))
      {
        $modelPresentacion = new Presentacion;
        $ExistePresentacion = $modelPresentacion->query()->Where("name_presentacion","=",trim(self::post("name_presentacion")))->first();

        if($ExistePresentacion)
        {
          $response = 'existe';
        }else{
          $response = $modelPresentacion->Insert([
            "name_presentacion" => self::post("name_presentacion"),
            "name_corto_presentacion" => self::post("name_corto_presentacion")
          ]);
        }
        self::json(["response" => $response]);
      }else{
        self::json(["response" =>"token-invalidate"]);
      }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }
  /**Mostrar todas las presentaciones existenets */
  public static function mostrarPresnetaciones($mostrar_todos)
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
       $modelPresent = new Presentacion;
       $respuesta = $mostrar_todos === 'si'?$modelPresent->query()->get():$modelPresent->query()->Where("deleted_at","is",null)->get();
 
       self::json(["response"=>$respuesta]);
    }else{
      self::json(["response" => []]); 
    }
  }
  /** Actualizar los datos de la presentación */
  public static function updatePresentacion(int $id)
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
     if(self::ValidateToken(self::post("_token")))
     {
        $modelPresent = new Presentacion;
        $response = $modelPresent->Update([
           "id_pesentacion" => $id,
           "name_presentacion" => self::post("name_presentacion"),
           "name_corto_presentacion" => self::post("name_corto_presentacion")
        ]);
        self::json(["response" => $response]);
     }else{
        self::json(["response" => "token_invalidate"]);
     }
    }else{
        self::json(["response" => "no-authorized"]);
    }
  }

  /** Habilitar e inhabilitar las presentaciones de los productos */
  public static function HabilitarInhabilitarPresentaciones(int $id,string $Condition)
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
     if(self::ValidateToken(self::post("_token")))
     {
      $modelPresent = new Presentacion;

      $response = $modelPresent->Update([
        "id_pesentacion" => $id,
        "deleted_at" => $Condition === 'i' ? self::FechaActual("Y-m-d H:i:s"):null
      ]);
      self::json(["response" => $response]);
     }else{
      self::json(["response" => "token-invalidate"]);
     }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }

   /** Eliminar la presentación de la base de datos */
   public static function DeletePresentacion(int $id)
   {
     self::NoAuth();
     if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
     {
      if(self::ValidateToken(self::post("_token")))
      {
        $modelPresentacion = new Presentacion;
 
        $response = $modelPresentacion->delete($id);
        self::json(["response" => $response]);
      }else{
       self::json(["response" => "token-invalidate"]);
      }
     }else{
       self::json(["response" => "no-authorized"]);
     }
   }

  /** registrar datos del laboratorio */
  public static function saveLaboratorio()
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
     if(self::ValidateToken(self::post("_token")))
     {
      $modelLaboratorio = new Laboratorio;
      $ExisteLaboratorio = $modelLaboratorio->query()->Where("name_laboratorio","=",trim(self::post("name_laboratorio")))->first();
      
      if($ExisteLaboratorio)
      {
        $response = 'existe';
      }else{
        $response = $modelLaboratorio->Insert([
          "name_laboratorio" => self::post("name_laboratorio"),
          "direccion" => self::post("direccion_laboratorio")
        ]);
      }
      self::json(["response" => $response]);
     }else{
      self::json(["response" => "token-invaldiate"]);
     }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }

  /**Modificando datos del laboratorio */
  public static function updateLaboratorio(int $id)
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
     if(self::ValidateToken(self::post("_token")))
     {
      $modelLaboratorio = new Laboratorio;
      $response = $modelLaboratorio->Update([
        "id_laboratorio" => $id,
        "name_laboratorio" => self::post("name_laboratorio"),
        "direccion" => self::post("direccion_laboratorio")
      ]);

      self::json(["response" => $response]);
     }else{
      self::json(["response" => "token-invalidate"]);
     }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }

  /**Mostrar todos los laboratorios existenets */
  public static function mostrarLaboratorios($mostrar_todos)
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
       $modelLaboratorio = new Laboratorio;
       $respuesta = $mostrar_todos === 'si' ? $modelLaboratorio->query()->get():$modelLaboratorio->query()->Where("deleted_at","is",null)->get();
 
       self::json(["response"=>$respuesta]);
    }else{
      self::json(["response" => []]); 
    }
  }

  /** Habilitar e Inhabilitar laboratorios */
  public static function HabilitarInhabilitarLaboratorios(int $id,string $Condition)
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
     if(self::ValidateToken(self::post("_token")))
     {
      $modelLaboratorio = new Laboratorio;

      $response = $modelLaboratorio->Update([
        "id_laboratorio" => $id,
        "deleted_at" => $Condition === 'i' ? self::FechaActual("Y-m-d H:i:s"):null
      ]);
      self::json(["response" => $response]);
     }else{
      self::json(["response" => "token-invalidate"]);
     }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }

 /** Eliminar la presentación de la base de datos */
 public static function DeleteLaboratorio(int $id)
 {
   self::NoAuth();
   if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
   {
    if(self::ValidateToken(self::post("_token")))
    {
      $modelLaboratorio = new Laboratorio;

      $response = $modelLaboratorio->delete($id);
      self::json(["response" => $response]);
    }else{
     self::json(["response" => "token-invalidate"]);
    }
   }else{
     self::json(["response" => "no-authorized"]);
   }
 }

  /** Registrar nuevo grupo terapeútico */
  public static function saveGrupoTerapeutico()
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
     if(self::ValidateToken(self::post("_token")))
     {
      $modelGrupo = new GrupoTerapeutico;
      $ExisteGrupo = $modelGrupo->query()->Where("name_grupo_terapeutico","=",trim(self::post("name_grupo")))->first();
      
      if($ExisteGrupo)
      {
        $response = 'existe';
      }else{
        $response = $modelGrupo->Insert([
          "name_grupo_terapeutico" => trim(self::post("name_grupo")),
          
        ]);
      }
      self::json(["response" => $response]);
     }else{
      self::json(["response" => "token-invaldiate"]);
     }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }

  /** Mostrar los grupos terapeuticos */
  public static function mostrarGruposTerapeuticos(string $mostrar_todos)
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
       $modelGrupos = new GrupoTerapeutico;
       $respuesta = $mostrar_todos === 'si' ? $modelGrupos->query()->get():$modelGrupos->query()->Where("deleted_at","is",null)->get();
 
       self::json(["response"=>$respuesta]);
    }else{
      self::json(["response" => []]); 
    }
  }

  /** Modificar datos del grupo terapeuticos */
  public static function updateGrupoTerapeutico(int $id)
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
      if(self::ValidateToken(self::post("_token")))
      {
        $modelGrupo = new GrupoTerapeutico;
        $respuesta = $modelGrupo->Update([
          "id_grupo_terapeutico" => $id,
          "name_grupo_terapeutico" => self::post("name_grupo")
        ]);
        self::json(["response" => $respuesta]);
      }else{
        self::json(["response" => "token-invalidate"]);
      }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }
  /** Habilitar e Inhabilitar los grupos terapeuticos */
  public static function HabilitarInhabilitarGrupos(int $id,string $Condition)
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
     if(self::ValidateToken(self::post("_token")))
     {
      $modelGrupo = new GrupoTerapeutico;

      $response = $modelGrupo->Update([
        "id_grupo_terapeutico" => $id,
        "deleted_at" => $Condition === 'i' ? self::FechaActual("Y-m-d H:i:s"):null
      ]);
      self::json(["response" => $response]);
     }else{
      self::json(["response" => "token-invalidate"]);
     }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }

   /** Eliminar grupos terapeuticos de la base de datos */
 public static function DeleteGrupos(int $id)
 {
   self::NoAuth();
   if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
   {
    if(self::ValidateToken(self::post("_token")))
    {
      $modelGrupo = new GrupoTerapeutico;

      $response = $modelGrupo->delete($id);
      self::json(["response" => $response]);
    }else{
     self::json(["response" => "token-invalidate"]);
    }
   }else{
     self::json(["response" => "no-authorized"]);
   }
 }

  /** Registrar embalajes */
  public static function saveEmbalaje()
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
     if(self::ValidateToken(self::post("_token")))
     {
      $modelEmbalaje = new Embalaje;

      $ExisteEmbalaje = $modelEmbalaje->query()->Where("name_embalaje","=",trim(self::post("name_embalaje")))->first();

      if($ExisteEmbalaje)
      {
        $response = 'existe';
      }else{
        $response = $modelEmbalaje->Insert([
          "name_embalaje" => self::post("name_embalaje")
        ]);
      }

      
      self::json(["response" => $response]);
     }else{
      self::json(["response" => "token-invalidate"]);
     }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }

  /** Mostrar empaques o embalajes existentes */
  public static function mostrarEmbalajes(string $mostrar_todos)
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
       $modelembalaje = new Embalaje;
       $respuesta = $mostrar_todos === 'si' ? $modelembalaje->query()->get():$modelembalaje->query()->Where("deleted_at","is",null)->get();
 
       self::json(["response"=>$respuesta]);
    }else{
      self::json(["response" => []]); 
    }
  }

  /** Actualizar datos del embalaje */
  public static function updateEmpaque(int $id)
  {
    self::NoAuth();
    if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
    {
     if(self::ValidateToken(self::post("_token")))
     {
      $modelEmbalaje = new Embalaje;
 
        $response = $modelEmbalaje->Update([
          "id_embalaje" => $id,
          "name_embalaje" => self::post("name_embalaje")
        ]);
      
      self::json(["response" => $response]);
     }else{
      self::json(["response" => "token-invalidate"]);
     }
    }else{
      self::json(["response" => "no-authorized"]);
    }
  }

   /** Habilitar e Inhabilitar los embalajes */
   public static function HabilitarInhabilitarEmbalajes(int $id,string $Condition)
   {
     self::NoAuth();
     if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
     {
      if(self::ValidateToken(self::post("_token")))
      {
       $modelEmbalaje = new Embalaje;
 
       $response = $modelEmbalaje->Update([
         "id_embalaje" => $id,
         "deleted_at" => $Condition === 'i' ? self::FechaActual("Y-m-d H:i:s"):null
       ]);
       self::json(["response" => $response]);
      }else{
       self::json(["response" => "token-invalidate"]);
      }
     }else{
       self::json(["response" => "no-authorized"]);
     }
   }

     /** Eliminar Embalajes de la base de datos */
 public static function DeleteEmbalajes(int $id)
 {
   self::NoAuth();
   if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
   {
    if(self::ValidateToken(self::post("_token")))
    {
      $modelEmbalaje = new Embalaje;

      $response = $modelEmbalaje->delete($id);
      self::json(["response" => $response]);
    }else{
     self::json(["response" => "token-invalidate"]);
    }
   }else{
     self::json(["response" => "no-authorized"]);
   }
 }

 /** Registrar nuevos proveedores */
 public static function saveProveedor()
 {
  self::NoAuth();
  /** Este proceso solo debe de ser realizado por el administardor */
  if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
  {
    if(self::ValidateToken(self::post("_token")))
    {
      $modelProveedor = new Proveedor;

      $ExisteProveedor = $modelProveedor->query()->Where("proveedor_name","=",trim(self::post("proveedor_name")))->first();
      if($ExisteProveedor)
      {
        $response = 'existe';
      }else{
        $response = $modelProveedor->Insert([
          "proveedor_name" => self::post("proveedor_name"),
          "contacto_name" => self::post("contacto_name"),
          "telefono" => self::post("telefono"),
          "correo" => self::post("correo"),
          "direccion" => self::post("direccion"),
          "pagina_web" => self::post("paginaweb"),
        ]);
      }
      self::json(["response" => $response]);
    }else{
      self::json(["response" => "token-invalidate"]);
    }
  }else{
    self::json(["response" => "no-authorized"]);
  }
 }
 /** mostrar todos los proveedores */
 public static function mostrarProveedores(string $mostrar_todos)
 {
  self::NoAuth();
  if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
  {
   $modelProveedor = new Proveedor;

   $response = $mostrar_todos === 'si' ? $modelProveedor->query()->get():$modelProveedor->Where("deleted_at","is",null)->query()->get();

   self::json(["response" => $response]);
  }else{
    self::json(["response" => "no.authorized"]);
  }
 }

 /** Modificar datos del proveedor */
 public static function updateProveedor(int $id)
 {
  self::NoAuth();
  /** Este proceso solo debe de ser realizado por el administardor */
  if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
  {
    if(self::ValidateToken(self::post("_token")))
    {
      $modelProveedor = new Proveedor;

       
        $response = $modelProveedor->Update([
          "id_proveedor" => $id,
          "proveedor_name" => self::post("proveedor_name"),
          "contacto_name" => self::post("contacto_name"),
          "telefono" => self::post("telefono"),
          "correo" => self::post("correo"),
          "direccion" => self::post("direccion"),
          "pagina_web" => self::post("paginaweb"),
        ]);
       
      self::json(["response" => $response]);
    }else{
      self::json(["response" => "token-invalidate"]);
    }
  }else{
    self::json(["response" => "no-authorized"]);
  }
 }

 /** Eliminar proveedores */
 public static function BorrarProveedor(int $id)
 {
  self::NoAuth();
  if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
  {
    if(self::ValidateToken(self::post("_token")))
    {
      $modelProveedor = new Proveedor;
      $response = $modelProveedor->delete($id);
      self::json(["response" => $response]);
    }else{
      self::json(["response" => "token-invalidate"]);
    }
  }else{
    self::json(["response" => "no-authorized"]);
  }
 }

 /** Habilitar e Inhabilitar proveedores */
 public static function HabilitarInhabilitarProveedores(int $id,string $Condition)
 {
   self::NoAuth();
   if(self::profile()->rol === 'admin_general' || self::profile()->rol === 'admin_farmacia')
   {
    if(self::ValidateToken(self::post("_token")))
    {
     $modelProveedor = new Proveedor;

     $response = $modelProveedor->Update([
       "id_proveedor" => $id,
       "deleted_at" => $Condition === 'i' ? self::FechaActual("Y-m-d H:i:s"):null
     ]);
     self::json(["response" => $response]);
    }else{
     self::json(["response" => "token-invalidate"]);
    }
   }else{
     self::json(["response" => "no-authorized"]);
   }
 }

}