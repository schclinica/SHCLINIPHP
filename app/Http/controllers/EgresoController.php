<?php
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\Caja;
use models\CategoriaEgreso;
use models\Sede;
use models\SubCategoriaEgreso;

class EgresoController extends BaseController
{
    private static $Errors =[];
  /** Método para visualizar la vista de egresos existentes */
  public static function index()
  {
    self::NoAuth();
    if(self::profile()->rol === self::$profile[1]  ||  self::profile()->rol === "admin_general" || self::profile()->rol === "Director" || self::profile()->rol === "admin_farmacia" || self::profile()->rol === self::$profile[5]){
            if (self::profile()->rol === "admin_general") {
                self::View_("egresos.index");
            } else {
                self::View_("egresos.index");
            }
    }else{
        PageExtra::PageNoAutorizado();
    }
  }

  public static function gastosIndex(){
    self::NoAuth();
    if(self::profile()->rol === self::$profile[1]  ||  self::profile()->rol === "admin_general" || self::profile()->rol === "Director" || self::profile()->rol === "admin_farmacia" || self::profile()->rol === self::$profile[5]){
        $categoria = new CategoriaEgreso;
        $categorias = $categoria->query()->get();
        $sede = new Sede;
        $sedes = $sede->query()->Where("deleted_at", "is", null)->get();
        self::View_("egresos.gastosindex",compact("sedes","categorias"));
    }else{
        PageExtra::PageNoAutorizado();
    }
  }

  /** VER LA VISTA DE CREAR NUEVOS GASTOS */
  public static function createNuevoGasto(){
    self::NoAuth();
    if(self::profile()->rol === self::$profile[1] || self::profile()->rol === "Director" || self::profile()->rol === "admin_farmacia" || self::profile()->rol === self::$profile[5] || self::profile()->rol === "admin_general"){
        $categoria = new CategoriaEgreso;
        $categorias = $categoria->query()->get();
        $sede = new Sede;
        $sedes = $sede->query()->Where("deleted_at", "is", null)->get();
        self::View_("egresos.create",compact("categorias","sedes"));
    }else{
        PageExtra::PageNoAutorizado();
    }
  }
  /// crear nuevo egreso
  public static function create()
  {
    self::NoAuth();
    if(self::profile()->rol === self::$profile[1]  || self::profile()->rol === "admin_general" || self::profile()->rol === "Director" || self::profile()->rol === "admin_farmacia"  || self::profile()->rol === self::$profile[5]){
             
       self::View_("egresos.categoria");
    }else{
        PageExtra::PageNoAutorizado();
    }
  }

  /// registrar nuevo categoria egreso
  public static function saveCategoria()
  {
    self::NoAuth();
    if(self::profile()->rol === self::$profile[1]  ||  self::profile()->rol === "admin_general" || self::profile()->rol === "Director" || self::profile()->rol === "admin_farmacia" || self::profile()->rol === self::$profile[5])
    {
        if(self::ValidateToken(self::post("_token")))
        {
            if(self::post("categoria_name") == null){
                 self::Session("error","INGRESE EL NOMBRE DE LA CATEGORIA!!!");
                 self::RedirectTo("categoria-egreso/create");
                 exit;
            }
            $modelCategoriaEgreso = new CategoriaEgreso;
          
            $ExisteCategoriaEgreso = $modelCategoriaEgreso->query()->Where("name_categoria_egreso","=",trim(self::post("categoria_name")))
            ->first();

            if($ExisteCategoriaEgreso){
                self::Session("existe","YA EXISTE LA CATEGORIA QUE DESEAS REGISTRAR!!!");
                self::RedirectTo("categoria-egreso/create");
                exit;
            }else{
                $respuesta = $modelCategoriaEgreso->Insert([
                    "name_categoria_egreso" => self::post("categoria_name"),
                ]);
    
                if($respuesta){
                    self::Session("success","CATEGORIA REGISTRADO CORRECTAMENTE!!!");
                    self::RedirectTo("categorias-egresos");
                }else{
                    self::Session("error","ERROR AL REGISTRAR LA CATEGORÍA!!!");
                    self::RedirectTo("categoria-egreso/create");
                }
                exit;
            }
        }else{
           self::Session("error","ERROR, EL TOKEN ES INCORRECTO!!!");
        }
    }else{
        self::Session("error","ERROR, NO TIENES ACCESO PARA EJECUTAR ESTA TAREA!!!");
    }
    self::RedirectTo("categoria-egreso/create");
  }

  /// registrar nueva sub categorías de egreso
   /// registrar nuevo egreso
   public static function saveSubCategoria()
   {
     self::NoAuth();
     if( self::profile()->rol === self::$profile[1]  || self::profile()->rol === "admin_general" || self::profile()->rol === "Director" || self::profile()->rol === "admin_farmacia" || self::profile()->rol === self::$profile[5])
     {
         if(self::ValidateToken(self::post("_token")))
         {
            if(self::post("categoria") == null){ self::$Errors[] = "SELECCIONE UNA CATEGORIA DEL GASTO!!!";}
            
             if(self::post("gasto_name") == null){
                self::$Errors[] = "INGRES LA DESCRIPCIÓN DEL GASTO!!! ";
             }else{
                self::Session("gasto_name",self::post("gasto_name"));
             }
             if(self::post("monto_gasto") == null){
                self::$Errors[] = "INGRESE EL MONTO DEL GASTO!!!";
             }else{
                self::Session("monto_gasto",self::post("monto_gasto"));
             }
             if(count(self::$Errors)>0){
                self::Session("errors",self::$Errors);
                self::RedirectTo("gasto/create");
                exit;
             }
             $modelSubCategoriaEgreso = new SubCategoriaEgreso;
             $caja = new Caja;

             if(self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1]){
                $sede = self::profile()->sede_id;
                
             }
       
             $usuario = self::profile()->id_usuario;
             $sede = self::profile()->sede_id != null ? self::profile()->sede_id :self::post("sede");
             $tipo = (self::profile()->rol === 'Director' || self::profile()->rol === 'Admisión' || self::profile()->rol === self::$profile[4] ? 'c':(self::profile()->rol === 'admin_general'?self::post("tipo"):'f'));
             $respuesta = $modelSubCategoriaEgreso->Insert([
                 "name_subcategoria" => self::post("gasto_name"),
                 "valor_gasto" => self::post("monto_gasto"),
                 "categoriaegreso_id" => self::post("categoria"),
                 "usuario_id"=>$usuario,
                 "sede_id" => $sede,
                 "fecha" => self::post("fecha"),
                 "tipo"=> $tipo,
             ]);
 
             if($respuesta){
                self::Session("success","EL GASTO HA SIDO REGISTRADO CORRECTAMENTE!!!");
                self::destroySession("gasto_name");self::destroySession("monto_gasto");
                self::RedirectTo("gestionar-gastos");
             }else{
                self::Session("error","EL AL REGISTRAR AL GASTO!!!");
                self::RedirectTo("gasto/create");
             }
         }else{
            self::Session("error","ERROR , TOKEN INCORRECTO!!!");
            self::RedirectTo("gasto/create");
         }
     }else{
         self::Session("error","ERROR NO TIENES ACCESO PARA REALIZAR ESTA ACCION!!!");
         self::RedirectTo("gasto/create");
     }
   }


   /// MOSTRAR TODOS LOS GASTOS
   public static function showGastos(){
    self::NoAuth();

    if(self::profile()->rol === self::$profile[1]  ||  self::profile()->rol === "admin_general" || self::profile()->rol === "Director" || self::profile()->rol === "admin_farmacia" || self::profile()->rol === self::$profile[5])
    {
        $modelCategoriaEgreso = new CategoriaEgreso;

            if (self::profile()->rol === self::$profile[0] || self::profile()->rol === "admin_farmacia" || self::profile()->rol === self::$profile[1] || self::profile()->rol === self::$profile[5]) {
                $sede = self::profile()->sede_id;
                   if(self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1]){
                     $response = $modelCategoriaEgreso->query()->Join("subcategorias_egreso as sce", "ce.id_categoria_egreso", "=", "sce.categoriaegreso_id")
                    ->select("sce.categoriaegreso_id", "name_categoria_egreso", "name_subcategoria as name_gasto","valor_gasto","date_format(fecha,'%d/%m/%Y') as fecha","s.namesede","sce.tipo","sce.sede_id","u.name","u.rol","sce.id_subcategoria")
                    ->Join("sedes as s","sce.sede_id","=","s.id_sede")
                    ->Join("usuario as u","sce.usuario_id","=","u.id_usuario")
                    ->Where("sce.sede_id","=",$sede)
                    ->And("sce.tipo","=","c")
                    ->get();
                   }else{
                      $response = $modelCategoriaEgreso->query()->Join("subcategorias_egreso as sce", "ce.id_categoria_egreso", "=", "sce.categoriaegreso_id")
                    ->select("sce.categoriaegreso_id", "name_categoria_egreso", "name_subcategoria as name_gasto","valor_gasto", "date_format(fecha,'%d/%m/%Y') as fecha","s.namesede","sce.tipo","sce.sede_id","u.name","u.rol","sce.id_subcategoria")
                    ->Join("sedes as s","sce.sede_id","=","s.id_sede")
                    ->Join("usuario as u","sce.usuario_id","=","u.id_usuario")
                    ->Where("sce.sede_id","=",$sede)
                    ->And("sce.tipo","=","f")
                    ->get();
                   }
            } else {
                $response = $modelCategoriaEgreso->query()->Join("subcategorias_egreso as sce", "ce.id_categoria_egreso", "=", "sce.categoriaegreso_id")
                    ->select("sce.categoriaegreso_id", "name_categoria_egreso", "name_subcategoria as name_gasto","valor_gasto", "date_format(fecha,'%d/%m/%Y') as fecha","s.namesede","sce.tipo","sce.sede_id","u.name","u.rol","sce.id_subcategoria")
                    ->Join("sedes as s","sce.sede_id","=","s.id_sede")
                    ->Join("usuario as u","sce.usuario_id","=","u.id_usuario")
                    ->get();
            }
            self::json(["gastos"=>$response]);
        }else{
            self::json(["gastos"=>[]]);
        }

   }
   
   /// mostrar las categorias existentes con su respectivo subcategorias
   public static function mostrarCategoriasEgreso()
   {
    self::NoAuth();
    
    $categoriaGasto = new CategoriaEgreso;

    $categorias = $categoriaGasto->query()->get();

    self::json(["response"=> $categorias]);
   }

   /// Eliminamos la categoria y sus subcategorias
   public static function deleteCategoriaAndSubCategoria(int $id)
   {
     self::NoAuth();
     if(self::profile()->rol === self::$profile[1]  ||  self::profile()->rol === "admin_general" || self::profile()->rol === "Director" || self::profile()->rol === "admin_farmacia"  || self::profile()->rol === self::$profile[5])
     {
         if(self::ValidateToken(self::post("_token")))
         {
             
             /// obtenermos el id de la categoria de egreso
             $EgresoModelCat = new  CategoriaEgreso;

            
             $respuesta = $EgresoModelCat->delete($id);

             
 
             self::json(["response" => $respuesta]);
         }else{
             self::json(["response" => "token-invalidate"]);
         }
     }else{
         self::json(["response" => "no-authorized"]);
     }
   }
   // mostrar las sub categorías existentes de una categoría
   public static function mostrarSubCategoriasEgreso(int $id)
   {
    self::NoAuth();
    if(self::profile()->rol === self::$profile[1]  ||  self::profile()->rol === "admin_general" || self::profile()->rol === "Director" || self::profile()->rol === "admin_farmacia" || self::profile()->rol === self::$profile[5])
    {
        $modelCategoriaEgreso = new CategoriaEgreso;

        $response = $modelCategoriaEgreso->query()->Join("subcategorias_egreso as sce","ce.id_categoria_egreso","=","sce.categoriaegreso_id")
        ->select("sce.id_subcategoria","sce.name_subcategoria","valor_gasto")
        ->Where("ce.id_categoria_egreso","=",$id)
        ->get();

        self::json(["response" => $response]);
    }else{
        self::json(["response" => "no.authorized"]);
    }
   }

   /// eliminar una subcategoria
   public static function deleteSubCategoria(int $id)
   {
     self::NoAuth();
     if(self::profile()->rol === self::$profile[1]  || self::profile()->rol === "admin_general" || self::profile()->rol === "Director" || self::profile()->rol === "admin_farmacia"  || self::profile()->rol === self::$profile[5])
     {
         if(self::ValidateToken(self::post("_token")))
         {
             
             /// obtenermos el id de la categoria de egreso
             $EgresoModelSubCat = new  SubCategoriaEgreso;

            
             $respuesta = $EgresoModelSubCat->delete($id);

             
 
             self::json(["response" => $respuesta]);
         }else{
             self::json(["response" => "token-invalidate"]);
         }
     }else{
         self::json(["response" => "no-authorized"]);
     }
   }

   /// modificar datos de la sub categoría
   public static function updateSubCategoria(int $id)
   {
     self::NoAuth();
     if(self::profile()->rol === self::$profile[1]  || self::profile()->rol === "admin_general" || self::profile()->rol === "Director" ||  self::profile()->rol === "admin_farmacia"  || self::profile()->rol === self::$profile[5])
     {
         if(self::ValidateToken(self::post("_token")))
         {
             
             /// obtenermos el id de la categoria de egreso
             $EgresoModelSubCat = new  SubCategoriaEgreso;
             $sede = self::profile()->sede_id != null ? self::profile()->sede_id : self::post("sede");
             $tipo = (self::profile()->rol === 'Director' || self::profile()->rol === 'Admisión' || self::profile()->rol === self::$profile[4] ? 'c':(self::profile()->rol === 'admin_general'?self::post("tipo"):'f'));
             $respuesta = $EgresoModelSubCat->Update([
                "id_subcategoria" => $id,
                "name_subcategoria" => self::post("gasto_name"),
                "valor_gasto" => self::post("valor_gasto"),
                "categoriaegreso_id" => self::post("categoria"),
                "sede_id" => $sede,
                "fecha" => self::post("fecha"),
                "tipo" => $tipo
             ]);
             if($respuesta){
                self::json(["success" => "GASTO MODIFICADO CORRECTAMENTE!!"]);
             }else{
                self::json(["error" => "ERROR AL MODIFICAR GASTO!!"]);
             }
         }else{
             self::json(["error" => "ERROR, TOKEN INCORRECTO!!"]);
         }
     }else{
         self::json(["error" => "ERROR, NO ESTAS AUTHORIZADO PARA REALIZAR ESTA TAREA!!!"]);
     }
   }

    /// modificar datos de la categoría
    public static function updateCategoria(int $id)
    {
      self::NoAuth();
      if(self::profile()->rol === self::$profile[1]  || self::profile()->rol === "admin_general" || self::profile()->rol === "admin_farmacia" || self::profile()->rol === "Director"  || self::profile()->rol === self::$profile[5])
      {
          if(self::ValidateToken(self::post("_token")))
          {
              /// obtenermos el id de la categoria de egreso
              $EgresoModelCat = new  CategoriaEgreso;
              $respuesta = $EgresoModelCat->Update([
                 "id_categoria_egreso" => $id,
                 "name_categoria_egreso" => self::post("name_categoria"),
              ]);
              self::json(["response" => $respuesta]);
          }else{
              self::json(["response" => "token-invalidate"]);
          }
      }else{
          self::json(["response" => "no-authorized"]);
      }
    }
}