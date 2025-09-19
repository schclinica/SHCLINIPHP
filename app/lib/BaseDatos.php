<?php 
namespace lib;

use ZipArchive;

trait BaseDatos
{
    use Session;
    private static string $NombreCopia;

    private static string $NameArchivoZip;
    private static string $ComandSql;

    /// realiza la copia de seguridad
    public static function copia(string $copiaName)
    {
        $copiaName = str_replace(" ","_",$copiaName);
        # indicamos el nombre de la copia de seguridad del sistema

        $NameArchivo = $copiaName."_".date("YmdHis").rand();

        self::$NombreCopia = $copiaName."_".date("YmdHis").rand().".sql";

        self::$ComandSql = "mysqldump -h".env("SERVIDOR")." -u".env("USERNAME")." -p".env("PASSWORD")." ".env("BASEDATOS")." > ".self::$NombreCopia;

 
        system(self::$ComandSql,$resultado);
       
       
        # Indicamos el comando  para la copia

        if($resultado == 0)
        {
           # indicamos el nombre del archivo ZIP
           self::$NameArchivoZip = $NameArchivo.".zip";

           # ahora realizamos el zipeado del archivo sql
           $Zip = new ZipArchive;

          /* madamos a descarga automática
           ===================================*/
        
           header("Cache-Control: public");
           header("Content-Description: File Transfer");
           header("Content-Disposition: attachment; filename=".basename(self::$NameArchivoZip));
           header("Content-Type: application/zip");
           header("Content-Transfer-Encoding: binary");

            /// creamos el zip  a partir del nombre del zip

            if ($Zip->open(self::$NameArchivoZip, ZIPARCHIVE::CREATE) === true) :

                $Zip->addFile(self::$NombreCopia); // añadimo el .sql

                $Zip->close(); // cerramos el zip

                unlink(self::$NombreCopia); /// eliminamos el archivo .sql

                /// eliminamos el bufffer generado
                ob_clean(); flush();

                readfile((self::$NameArchivoZip)); // leemos el archivo zip

                unlink(self::$NameArchivoZip); /// eliminamos el zip de  la raiz del proyecto

            endif;
           
        }
        else
        {
           self::Session("mensaje_error","Error al realizar la copia de seguridad");
           unlink(self::$NombreCopia); /// eliminamos el archivo .sql

           self::RedirectTo("Configurar-datos-empresa-EsSalud");
        }
    }

    /// restaurar sistema
    public static function restaurarSistema($archivo)
    {
        # Comando para realizar la restauración del sistema
        /**
         * Si no tiene password, , quitar -p
         */
        $CommandRestore = "mysql -h".env("SERVIDOR")." -u".env("USERNAME")." -p".env("PASSWORD")." ".env("BASEDATOS")." < ".$archivo; 

        system($CommandRestore,$output);

        switch($output)
        {
            case 0:
               self::json(['mensaje'=>'success']); 
            break;
            default:
              self::json(['mensaje'=>'error']);
            break;
        }
    }
}