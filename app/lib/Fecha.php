<?php 
namespace lib;

trait Fecha 
{
    /// obtener la fecha actual
    public  static function FechaActual(string $formato,$strToTime = 0)
    {
    date_default_timezone_set('America/Lima');

     if($strToTime != 0){
        return date($formato,$strToTime);
     }
     return date($formato);
    }
 
    /// obtener fecha dias antes o depues
    public function addRestFecha(string $formato,$operacion="-8900 day") /// por defecto resta 24 años de la fecha actual
    {
        /// Fecha Actual
        $FechaActual = $this->FechaActual($formato);

        return date($formato,strtotime($FechaActual.$operacion));
        
    }

    /// obtenemos el día el letra , segun la fecha seleccionado
    public static function getDayDate($fecha)
    {
        /// obtener la cantidad de segundo desde 1970 hasta el momento de ejecución
        $TotalSegundos = strtotime($fecha);
        /// obtener el # de día de la semana
        $DiaSemana = date("w",$TotalSegundos);

        switch($DiaSemana):
            case 0:return 'Domingo';
            case 1:return 'Lunes';
            case 2:return 'Martes';
            case 3:return 'Miércoles';
            case 4:return 'Jueves';
            case 5:return 'Viernes';
            case 6:return 'Sábado';
        endswitch;
    }

    /// convertir fechas a texto , ejemplo => 2 de enero de 1998
    public static function getFechaText($fecha)
    {
        $FechaText = '';
        $fecha = explode("/",$fecha); # 12/09/2023
        $Day  = $fecha[0]; $Year = $fecha[2];
        $Month =  strval($fecha[1]);

        
        /// verificamos segun el més de la fecha

        switch($Month)
        {
            case 1: $FechaText = $Day." de enero del ".$Year; break;
            case 2: $FechaText = $Day." de febrero del ".$Year; break;
            case 3: $FechaText = $Day." de marzo del ".$Year; break;
            case 4: $FechaText = $Day." de abril del ".$Year; break;
            case 5: $FechaText = $Day." de mayo del ".$Year; break;
            case 6: $FechaText = $Day." de junio del ".$Year; break;
            case 7: $FechaText = $Day." de julio del ".$Year; break;
            case 8: $FechaText = $Day." de agosto del ".$Year; break;
            case 9: $FechaText = $Day." de septiembre del ".$Year; break;
            case 10: $FechaText = $Day." de octubre del ".$Year; break;
            case 11: $FechaText = $Day." de noviembre del ".$Year; break;
            case 12: $FechaText = $Day." de diciembre del ".$Year; break;
        }
        return $FechaText;
    }

    /**
     * Convertimos el mes de ingles a español
     */

     public static function MonthSpanish(string $Month)
     {
          switch($Month)
          {
            case "January":return "Enero"; break;
            case "February":return "Febrero"; break;
            case "March":return "Marzo"; break;
            case "April":return "Abril"; break;
            case "May":return "Mayo"; break;
            case "June":return "Junio"; break;
            case "July":return "Julio"; break;
            case "August":return "Agosto"; break;
            case "September":return "Setiembre"; break;
            case "October":return "Octubre"; break;
            case "November":return "Noviembre"; break;
            case "December":return "Diciembre"; break;
          }
     }
     
     public static function getMonthName(int $Month)
     {
          switch($Month)
          {
            case 1:return "Enero"; break;
            case 2:return "Febrero"; break;
            case 3:return "Marzo"; break;
            case 4:return "Abril"; break;
            case 5:return "Mayo"; break;
            case 6:return "Junio"; break;
            case 7:return "Julio"; break;
            case 8:return "Agosto"; break;
            case 9:return "Setiembre"; break;
            case 10:return "Octubre"; break;
            case 11:return "Noviembre"; break;
            case 12:return "Diciembre"; break;
          }
     }

     public static function FechaFormat(String $Fecha){

        $Hora = "";
        $FechaText = explode(" ",$Fecha)[0];
        if(count(explode(" ",$Fecha)) > 1){$Hora = explode(" ",$Fecha)[1];}

        $FechaText = explode("-",$FechaText);

        return $FechaText[2]."/".$FechaText[1]."/".$FechaText[0]." ".$Hora;
     }
     

}

