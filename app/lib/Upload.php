<?php 
namespace lib;
trait Upload 
{
use Request;
// propiedades
public static string $DestinoFoto = "public/asset/foto/"; /// ruta donde se almacena las fotos de los usuarios

private static string|null $NameFoto = null; // recuperamos el nombre de la foto

private static array $TypeImagenAccep = ['image/jpeg','image/png','image/jpg','image/x-icon'];


/// Método que realiza la subida de la foto al servidor
public static function CargarFoto(string $file)
{
 $TipoImagen = ".jpg";
 // validamos de q por lo menos selecciono un archivo

 if(self::file_size($file) > 0)
 {
    /// verificamos que sea una imágen tipo jpg o png

    if(in_array(self::file_Type($file),self::$TypeImagenAccep))
    {
      /// obtenemos el tipo de imagen

      if(self::file_Type($file) === self::$TypeImagenAccep[1])
      {
        $TipoImagen =".png";
      }else{
        if(self::file_Type($file) === self::$TypeImagenAccep[2])
        {
          $TipoImagen = ".ico";
        }
      }

      /// obtenemos el nombre de la imagen

      $NameImagen = date("Ymd").rand().$TipoImagen;
      
      self::$NameFoto = $NameImagen;

      /// Verificamos si se subio correctamente
      return self::Upload($NameImagen,$file);

    }
    else
    {
        return 'no-accept';
    }
    
 }
 else{
    /// si no hay foto seleccionado por defecto toma nulo
    self::$NameFoto = null;

    return 'vacio';
 
 }
}

/// recuperamos el nombre de la foto
public static function getNameFoto():null|string
{
  return self::$NameFoto;
}

public static function Upload($NameArchivo,$Archivo){
  
    if(!file_exists(self::$DestinoFoto))
    {
      /// lo creamos 
      mkdir(self::$DestinoFoto,true);
      
    }

    self::$DestinoFoto.=$NameArchivo;

    /// subimos la imagen al servidor

    return move_uploaded_file(self::ContentFile($Archivo),self::$DestinoFoto);
}
}