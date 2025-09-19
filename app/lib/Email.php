<?php 
namespace lib;

use PHPMailer\PHPMailer\PHPMailer;

trait Email 
{
    public static function send_($destino,$NameUserDestino,$Asunto,$Contenido)
    {
        /// instanciamos a la clase email
        $email = new PHPMailer();

        $email->isSMTP();// conexión mediante SMTP

        $email->SMTPAuth = true;/// le ddecimos que se authentifique

        $email->SMTPSecure = env("SMTP_SECURE");

        $email->Host = env("HOST_MAILER");

        $email->Port = env("PUERTO_MAILER");

        $email->Username = env("USERNAME_MAILER");

        $email->Password = env("PASSWORD_MAILER");

        /// especificamos quien va a realizar el envio del correo electrónico (REMITENTE)
        $email->setFrom(env("REMITENTE_ENVIO_CORREO"),env("NAME_REMITENTE_CORREO"));

        /// especificamos a quién se le va enviar el correo

        $email->addAddress($destino,$NameUserDestino);

        $email->Subject = $Asunto;
        $email->Body = $Contenido;
        $email->CharSet ="UTF-8";
        $email->isHTML(true);
 
        return $email->send();/// 
    }


    public static function send_Email_Envio($destino,$NameUserDestino,$Asunto,$Contenido)
    {
        /// instanciamos a la clase email
        $email = new PHPMailer();

        $email->isSMTP();// conexión mediante SMTP

        $email->SMTPAuth = true;/// le ddecimos que se authentifique

        $email->SMTPSecure = env("SMTP_SECURE");

        $email->Host = env("HOST_MAILER");

        $email->Port = env("PUERTO_MAILER");

        $email->Username = env("USERNAME_MAILER");

        $email->Password = env("PASSWORD_MAILER");

        /// especificamos quien va a realizar el envio del correo electrónico (REMITENTE)
        $email->setFrom($destino,$NameUserDestino);

        /// especificamos a quién se le va enviar el correo

        $email->addAddress(isset(self::BusinesData()[0]->contacto) ? self::BusinesData()[0]->contacto:env("USERNAME_MAILER"),isset(self::BusinesData()[0]->nombre_empresa) ? self::BusinesData()[0]->nombre_empresa:"TuClinicaOnline");

        $email->Subject = $Asunto;
        $email->Body = $Contenido;
        $email->CharSet ="UTF-8";
        $email->isHTML(true);
 
        return $email->send();/// 
    
}
}
