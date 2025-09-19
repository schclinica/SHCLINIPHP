<?php 
namespace lib;

use QRcode as GlobalQRcode;

require_once 'app/phpqrcode/qrlib.php';

trait QrCode {
private static string $DirectorioQr = "public/qr/qr.png";
/** Generar una Qr */
public static function GenerateQr(string $Content,int $Tamanio = 10,string $Level = "M",
int $FrameSize = 3)
{
   /// destino de la qr
 
   $DestinoQr = self::$DirectorioQr;
  
   GlobalQRcode::png($Content,$DestinoQr,$Level,$Tamanio,$FrameSize);
} 

public static function getDirectorioQr()
{
    return self::$DirectorioQr;
}


/**
 * Set the value of DirectorioQr
 *
 * @param string $DirectorioQr
 */
public static function setDirectorioQr(string $DirectorioQr)
{
self::$DirectorioQr = $DirectorioQr;
}
}