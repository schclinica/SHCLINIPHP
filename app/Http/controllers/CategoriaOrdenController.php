<?php
namespace Http\controllers;

use business\OrdenBusines;
use lib\BaseController;
use models\CategoriaOrden;

class CategoriaOrdenController extends BaseController{
    private static string  $TipoArchivoAceptable = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
    /** MOSTRAR LAS CATEGORIAS ORDEN */
    public static function showCategoriasOrden(){

        self::NoAuth();
        if(self::profile()->rol === "Médico" || self::profile()->rol ==='Director' || self::profile()->rol === 'admin-general'){
            $categoriaOrden = new CategoriaOrden;

            $categorias = $categoriaOrden->query()->Join("tipo_examen as te", "ce.grupotipo_id", "=", "te.id_tipo_examen")
                ->get();

            self::json(["categorias" => $categorias]);
        }else{
            self::json(["categorias" => []]);
        }
    }


    /// CREAR UNA CATEGORIA
    public static function save(){
        self::NoAuth();

        if(self::profile()->rol === "Médico" || self::profile()->rol ==='Director' || self::profile()->rol === 'admin-general'){
             if(self::ValidateToken(self::post("token_"))){
                $categoriaOrden = new CategoriaOrden;

                /// validamos la existencia
                $ExisteCategoria = $categoriaOrden->query()->Where("codigo_categoria","=",self::post("codigo_categoria"))
                ->Or("nombre_categoria","=",self::post("nombre_categoria"))->get();

                if($ExisteCategoria){
                    self::json(["error" => "LA CATEGORIA QUE DESEAS REGISTRAR YA EXISTE POR CÓDIGO | NOMBRE"]);
                    exit;
                }

                $response = $categoriaOrden->Insert([
                    "codigo_categoria" => self::post("codigo_categoria"),
                    "nombre_categoria" => self::post("nombre_categoria"),
                    "grupotipo_id" => self::post("tipo_categoria")
                ]);

                if($response){
                    self::json(["success" => "CATEGORIA REGISTRADO CORRECTAMENTE!!!"]);
                }else{
                    self::json(["error" => "ERROR AL REGISTRAR CATEGORIA"]);
                }
             }else{
                self::json(["error" => "ERROR, TOKEN INVALID!!"]);
             }
        }else{
            self::json(["error" => "NO TIENES PERMISOS PARA CREAR NUEVAS CATEGORIAS!!!"]);
        }    
    }

    /// MODIFICAR LOS DATOS DE LA CATEGORIA

    public static function update($id){
        self::NoAuth();

        if(self::profile()->rol === "Médico" || self::profile()->rol ==='Director' || self::profile()->rol === 'admin-general'){
             if(self::ValidateToken(self::post("token_"))){
                $categoriaOrden = new CategoriaOrden;
 
                $response = $categoriaOrden->Update([
                    "id_categoria_examen" => $id,
                    "codigo_categoria" => self::post("codigo_categoria"),
                    "nombre_categoria" => self::post("nombre_categoria"),
                    "grupotipo_id" => self::post("tipo_categoria")
                ]);

                if($response){
                    self::json(["success" => "CATEGORIA MODIFICADO CORRECTAMENTE!!!"]);
                }else{
                    self::json(["error" => "ERROR AL MODIFICAR CATEGORIA"]);
                }
             }else{
                self::json(["error" => "ERROR, TOKEN INVALID!!"]);
             }
        }else{
            self::json(["error" => "NO TIENES PERMISOS PARA MODIFICAR LAS CATEGORIAS!!!"]);
        }    
    }

    /// DESHABILITAR LA CATEGORIA
    public static function eliminar($id){
        if(self::profile()->rol === "Médico" || self::profile()->rol ==='Director' || self::profile()->rol === 'admin-general'){
            if(self::ValidateToken(self::post("token_"))){
                $categoriaOrden = new CategoriaOrden;

                $response = $categoriaOrden->Update([
                    "id_categoria_examen" => $id,
                    "estado_eliminado" => self::FechaActual("Y-m-d H:i:s")
                ]);

                if($response){
                    self::json(["success" => "CATEGORIA ELIMINADO CORRECTAMENTE!!"]);
                }else{
                    self::json(["error" => "ERROR AL ELIMINAR LA CATEGORIA!!!"]);
                }
            }else{
                self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
            }   
        }else{
            self::json(["error" => "NO ESTAS AUTHORIZADO PARA ELIMINAR LA CATEGORIA!!!"]);
        }  
    }

    /// activar la categoria
    public static function activar($id){
        if(self::profile()->rol === "Médico" || self::profile()->rol ==='Director' || self::profile()->rol === 'admin-general'){
            if(self::ValidateToken(self::post("token_"))){
                $categoriaOrden = new CategoriaOrden;

                $response = $categoriaOrden->Update([
                    "id_categoria_examen" => $id,
                    "estado_eliminado" => null
                ]);

                if($response){
                    self::json(["success" => "CATEGORIA HABILITADO CORRECTAMENTE!!"]);
                }else{
                    self::json(["error" => "ERROR AL ACTIVAR LA CATEGORIA!!!"]);
                }
            }else{
                self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
            }   
        }else{
            self::json(["error" => "NO ESTAS AUTHORIZADO PARA HABILITAR LA CATEGORIA!!!"]);
        }  
    }


    /// ELIMINAR DE LA BASE DE DATOS
    public static function borrar($id){
        self::NoAuth();
        if(self::profile()->rol === "Médico" || self::profile()->rol ==='Director' || self::profile()->rol === 'admin-general'){
            if(self::ValidateToken(self::post("token_"))){
                $categoriaOrden = new CategoriaOrden;

                $response = $categoriaOrden->delete($id);

                if($response){
                    self::json(["success" => "CATEGORIA BORRADO CORRECTAMENTE!!"]);
                }else{
                    self::json(["error" => "ERROR AL BORRAR LA CATEGORIA!!!"]);
                }
            }else{
                self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
            }   
        }else{
            self::json(["error" => "NO ESTAS AUTHORIZADO PARA BORRAR LA CATEGORIA!!!"]);
        }  
    }

    /// IMPORTAR DATOS
    public static function importarDatosCategory(){
        self::NoAuth();
        if(self::profile()->rol === "Médico" || self::profile()->rol ==='Director' || self::profile()->rol === 'admin-general'){
            if(self::ValidateToken(self::post("token_"))){
               /**
       * Obtenemos al file seleccionado, si o si tiene que ser un excel
       */
        if(self::file_size("archivo_excel_categoria") > 0)
        {
            # Verificamos si el archivo seleccionado es un excel
            if (self::file_Type("archivo_excel_categoria") === self::$TipoArchivoAceptable) {
                $ArchivoSelect = self::ContentFile("archivo_excel_categoria");
                    self::json(["response" => OrdenBusines::ImportarDatosCategoryOrden($ArchivoSelect)]);
                } else {
                    self::json(['error' => "ERROR, EL ARCHIVO SELECCIONADO ES INCORRECTO, DEBES DE SELECCIONAR UN EXCEL!!!"]);
                }
            } else {
                  self::json(['error' => "ERROR, DEBES DE SELECCIONAR UN ARCHIVO EXCEL!!!"]);
            }
            }else{
                self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
            }
        }else{
            self::json(["error" => "ERROR, NO TIENES PERMISOS PARA IMPORTAR LOS DATOS DE LA CATEGORIA!!!"]);
        }   
            
    }

     //// MOSTRAR TODAS LAS CATEGORIAS DISPONINLES AL SELECCION UN TIPO
     public static function showCategoriaPorTipo($id){
        self::NoAuth();
        if(self::profile()->rol === "Médico" || self::profile()->rol ==='Director' || self::profile()->rol === 'admin-general'){
          $categoriasOrder = new CategoriaOrden;
   
          $dataCategorias = $categoriasOrder->query()->Join("tipo_examen as te","ce.grupotipo_id","=","te.id_tipo_examen")
          ->Where("ce.grupotipo_id","=",$id)
          ->And("ce.estado_eliminado","is",null)->get();
   
          self::json(["categoriasdata"=>$dataCategorias]);
        }else{
         self::json(["categoriasdata"=>[]]);
        } 
       }

       /// mostrar las categorias

}