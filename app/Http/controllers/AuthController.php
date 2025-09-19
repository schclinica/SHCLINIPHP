<?php 
namespace Http\controllers;
use lib\BaseController;
use models\Usuario;
class AuthController extends BaseController
{

    private static $ModelUser,$Error=[];
    

    /// Visualiza el formulariode login
    public static function loginView()
    {
        self::Auth();
     
        self::View_("auth.ViewLogin");
    }

    /**
     * Reeenviar codigo nuevamente
     */
    public static function sendCodigoEmailUser()
    {
       /// generamos el codigo
       if(!empty(self::get("id"))){
            $CodigoReenvio = DigitsAleatorio();
            $modelUser = new Usuario;
            $modelUser->Update([
                "id_usuario" => self::get("id"),
                "expired"=>time()+env("TIEMPO_VIDA_SESSION")*20,/// 20 minutos
                "codigo_confirm"=> $CodigoReenvio
            ]);
            $Usuario = $modelUser->query()->where("id_usuario", "=", self::get("id"))->first();

            if (self::send_($Usuario->email, $Usuario->name, "Código de confirmación de la cuenta", self::ContentSendEmailConfirmAccount($Usuario->codigo_confirm))) {
               self::json(["response" => "codigo-reenviado"]);
            }
       }else{
        self::json(["response" => "error"]);
       }
    } 

    /// crear cuenta de usuario para pacientes
    public static function createAccountPaciente()
    {
        self::Auth();

        self::View_("paciente.createAccount");
    }

    public static function ConfirmAccountPaciente()
    {
        self::Auth();
        

        /// realizamos una consulta para mostrar la vista 
        self::$ModelUser = new Usuario;

        /// consultamos al usuario por el código que se le a enviado a su correo y además el tiempo de expiración del código
        $User = self::$ModelUser->query()
                ->Where("id_usuario","=",self::get("id"))
                ->And("token_password","=",self::get("token"))
                ->And("estado","=","2")
                ->And("expired",">",time())
                ->first();
        if($User)
        {
            self::View_("paciente.confirm_account");
        }
        else
        {
            /// eliminamos la cuenta de manera automática , ya que el usuario no confirmo su cuenta
            self::$ModelUser->delete(self::get("id"));
            self::RedirectTo("login");
        }
    }
    /// vista para confirmar la cuenta del paciente que se registra al sistema


    /// guardamos la cuenta del paciente 
    public static function saveAccountPaciente()
    {
        self::Auth();// si está authenticado vuelve al Dashboard
        if(self::ValidateToken(self::post("token_")))
        {
          if(!empty($_POST['usuario']))
          {
            self::Session("usuario",$_POST['usuario']);
          }

          if(!empty($_POST['correo']))
          {
             if(!filter_var(self::post("correo"),FILTER_VALIDATE_EMAIL))
             {
                self::Session("correo",$_POST['correo']);
                self::Session("response","error_correo");
                self::RedirectTo("create_account_paciente");
                return;
             }
             self::Session("correo",$_POST['correo']);
          }

          if(!empty(self::post("pasword")))
          {
           self::Session("pasword",$_POST['pasword']);
          }

          if(!empty(self::post("confirm_pass")))
          {
            self::Session("confirm_pass",self::post("confirm_pass"));
          }

          self::proccessAccountPaciente();
        }
    }

    /// proceso para registro de la cuenta de usuario del paciente
    private static function proccessAccountPaciente()
    {
        self::$ModelUser = new Usuario();

        /// Validamos la existencia por correo electrónico, ya que el correo debe ser único por cada usuario

        $Paciente = self::$ModelUser->query()->Where("email","=",self::post("correo"))->first();

        if(!$Paciente){ /// si no existe paciente 
            /// tiempo de vida del código para confirma la cuenta
            $Tiempo_Expired = time() + (env("TIEMPO_VIDA_SESSION")*20); /// por defecto 20 minutos
            $CodigoAleatorio = DigitsAleatorio();
            $response = self::$ModelUser->Insert([
                "name"=>self::post("usuario"),
                "email"=>self::post("correo"),
                "pasword"=>password_hash(self::post("pasword"),PASSWORD_BCRYPT),
                "rol"=>"Paciente",
                "estado"=>"2",
                "token_password"=>generateToken(),
                "expired"=>$Tiempo_Expired,
                "codigo_confirm"=> $CodigoAleatorio
            ]);/// esperemos 1 minuto
    
            /**
             * una ves registrada la cuenta de usuario del paciente, actualizamos el campo
             * codigo_confirm para que el usuario ´pueda activar su cuenta, dicho código se le envía 
             * vía correo
             */
    
           if($response)
           {
             
            /// si todo esta okey, actualizamos el campo código_confirm
            $modelUpdateCod = new Usuario;
            /// obtenemos al usuario con el còdigo actualizado
            $User_Save = $modelUpdateCod->query()->Where("email","=",self::post("correo"))->first();

            /// enviamos el correo por email
            if( self::send_($User_Save->email,$User_Save->name,"Código de confirmación de la cuenta",self::ContentSendEmailConfirmAccount($User_Save->codigo_confirm)))
            {
                self::Session("success_","Le hemos enviado un código de activación al correo ".$User_Save->email." .El código solo tiene 20 minutos de valides.");
                self::RedirectTo("paciente/confirm_account?id=".$User_Save->id_usuario."&&token=".$User_Save->token_password);
            }
           }
           else
           {
            self::Session("response","error");
            self::RedirectTo("create_account_paciente");
           }
        }else{
            self::Session("response","existe-paciente");
            self::RedirectTo("create_account_paciente");
        }
    }

    /// contenido de la confiirmación de la cuenta
    private static function ContentSendEmailConfirmAccount($codigo)
    {
        return ' <td class="esd-stripe" style="background-color: #fafafa;" bgcolor="#fafafa" align="center">
        <table class="es-content-body" style="background-color: #ffffff;" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center">
            <tbody>
                <tr>
                    <td class="esd-structure es-p40t es-p20r es-p20l" style="background-color: transparent; background-position: left top;" bgcolor="transparent" align="left">
                        <table width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td class="esd-container-frame" width="560" valign="top" align="center">
                                        <table style="background-position: left top;" width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-block-image es-p5t es-p5b" align="center" style="font-size:0"><a target="_blank"><img src="https://tlr.stripocdn.email/content/guids/CABINET_dd354a98a803b60e2f0411e893c82f56/images/23891556799905703.png" alt style="display: block;" width="175"></a></td>
                                                </tr>
                                                <tr>
                                                    <td class="esd-block-text es-p15t es-p15b" align="center">
                                                        <h1 style="color: #333333; font-size: 20px;"><strong>Código de activación de la  </strong></h1>
                                                        <h1 style="color: #333333; font-size: 20px;"><strong>&nbsp;cuenta</strong></h1>
                                                    </td>
                                                </tr>
                                               
                                               
                                                <tr>
                                                  <h2><b> CÓDIGO DE ACTIVACIÓN: '.$codigo.'</b></h2>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="esd-structure es-p20t es-p10r es-p10l" style="background-position: center center;" align="left">
                        <!--[if mso]><table width="580" cellpadding="0" cellspacing="0"><tr><td width="199" valign="top"><![endif]-->
                        <table class="es-left" cellspacing="0" cellpadding="0" align="left">
                            <tbody>
                                <tr>
                                    <td class="esd-container-frame" width="199" align="left">
                                        <table style="background-position: center center;" width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-block-text es-p15t es-m-txt-c" align="right">
                                                        <p style="font-size: 16px; color: #666666;"><strong>Follow us:</strong></p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--[if mso]></td><td width="20"></td><td width="361" valign="top"><![endif]-->
                        <table class="es-right" cellspacing="0" cellpadding="0" align="right">
                            <tbody>
                                <tr>
                                    <td class="esd-container-frame" width="361" align="left">
                                        <table style="background-position: center center;" width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-block-social es-p10t es-p5b es-m-txt-c" align="left" style="font-size:0">
                                                        <table class="es-table-not-adapt es-social" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="es-p10r" valign="top" align="center"><a target="_blank" href><img src="https://tlr.stripocdn.email/content/assets/img/social-icons/rounded-gray/facebook-rounded-gray.png" alt="Fb" title="Facebook" width="32"></a></td>
                                                                    <td class="es-p10r" valign="top" align="center"><a target="_blank" href><img src="https://tlr.stripocdn.email/content/assets/img/social-icons/rounded-gray/twitter-rounded-gray.png" alt="Tw" title="Twitter" width="32"></a></td>
                                                                    <td class="es-p10r" valign="top" align="center"><a target="_blank" href><img src="https://tlr.stripocdn.email/content/assets/img/social-icons/rounded-gray/instagram-rounded-gray.png" alt="Ig" title="Instagram" width="32"></a></td>
                                                                    <td class="es-p10r" valign="top" align="center"><a target="_blank" href><img src="https://tlr.stripocdn.email/content/assets/img/social-icons/rounded-gray/youtube-rounded-gray.png" alt="Yt" title="Youtube" width="32"></a></td>
                                                                    <td class="es-p10r" valign="top" align="center"><a target="_blank" href><img src="https://tlr.stripocdn.email/content/assets/img/social-icons/rounded-gray/linkedin-rounded-gray.png" alt="In" title="Linkedin" width="32"></a></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--[if mso]></td></tr></table><![endif]-->
                    </td>
                </tr>
                <tr>
                    <td class="esd-structure es-p5t es-p20b es-p20r es-p20l" style="background-position: left top;" align="left">
                        <table width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td class="esd-container-frame" width="560" valign="top" align="center">
                                        <table width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                     
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>';
    }

    /// validar el código de confirmación del paciente registrado
    public static function VerifyCodUserPaciente()
    {
        self::Auth();
        /// validamos el token Csrf
        if (self::ValidateToken(self::post("token_"))) {
            self::$ModelUser = new Usuario;

            if (!empty(self::post("usercodigo"))) {
                /// consultamos al usuario por el código que se le a enviado a su correo y además el tiempo de expiración del código
                $User = self::$ModelUser->query()
                    ->Where("codigo_confirm", "=", self::post("usercodigo"))
                    ->And("expired", ">", time())
                    ->first();
                if ($User) {

                    self::$ModelUser->Update([
                        "id_usuario" => $User->id_usuario,
                        "estado" => "1",
                        "token_password" => null
                    ]);


                    self::Session("response", "tu cuenta a sido activada, ya puedes ingresar a tu cuenta y sacar una cita, muchas felicidades!!!😁");
                    self::RedirectTo("login");
                } else {
                    self::Session("response", "error");
                    echo "<script>history.back()</script>";
                }
            }
            else
            {    
            self::Session("response", "vacio");
            echo "<script>history.back()</script>";  
            }
        }
        
    }

    public static function resetForm()
    {
        self::Auth();

        if (self::get("id") !== '' and self::get("token") !== '') {
            self::$ModelUser = new Usuario;
            // capturamos los valores
            $Usuario_Id = self::get("id");
            $Usuario_Token = self::get("token");
            $Tiempo_expired = time(); /// hora actual

            // capturamo al usuairo con estos datos
            $Usuario = self::$ModelUser->query()->Where("id_usuario", "=", $Usuario_Id)
                ->And("token_password", "=", $Usuario_Token)
                ->And("request_password", "=", "1")
                ->And("expired",">",$Tiempo_expired)->first();


            if ($Usuario) {
                self::View_("email.reset_password");
            } else {
                self::RedirectTo("login");
            }
        }
    }
   

    /// Acción del boton entrar al sistema
    public static function signIn()
    {
        self::Auth();/// redirige al dashboard
        /// validamoa el token
        if(self::ValidateToken(self::post("token_")))
        {
            /// validamos
            if(!empty(self::post("email_username")))
            {
                self::Session("email_username",self::post("email_username"));
            } 

            if(!empty(self::post("password")))
            {
                self::Session("password",self::post("password"));
            } 
            self::Attemp([
                "nick"=>self::post("email_username"),
                "password"=>self::post("password")
            ]);
           self::RedirectTo("login");
            
        }
    }

    /// proceso para el acceso al sistema
    private static function Attemp(array $credenciales)
    {
        /// consultamos usuario mediante las credenciales
        self::$ModelUser = new Usuario;

        /// consultamos usuario por nombre de usuario o email
        
        $Usuario = self::$ModelUser->query()->Where("name","=",$credenciales['nick'])
                   ->Or("email","=",$credenciales['nick'])->first();

       /// verificamos que exista el usuario

       if($Usuario)
       {
         if($Usuario->estado === '1')
         {
              /// capturamos al usuario 
         $NombreUser = $Usuario->name;
         $EmailUser = $Usuario->email;
         /// verificamos

         if($credenciales['nick'] === $NombreUser || $credenciales['nick'] === $EmailUser)
         {
            /// verificamos el password
            if(password_verify($credenciales['password'],$Usuario->pasword))
            {
              /// obtenemos el valor del checkbox 
              $RememberMe = self::post("remember_me") === 'on'?true:false;

              self::SessionSistema($RememberMe,$Usuario->id_usuario);

              /// redirigimos al Dashboard
              if($Usuario->rol === self::$profile[1])
              {
                self::RedirectTo("crear-nueva-cita-medica");
              }
              else
              {
               if($Usuario->rol === self::$profile[2] and !isset(self::profile()->id_persona))
               {
                self::RedirectTo("paciente/completar_datos");
               }
               else
               {
                 if($Usuario->rol === self::$profile[5])
                 {
                    self::RedirectTo("app/farmacia");
                 }else{
                    self::RedirectTo("dashboard");
                 }
               }
              }
              exit;

            }
            else
            {
               self::Session("error_pas","La contraseña es incorrecta");
            }
         }
         else
         {
            self::Session("error_user","Nombre de usuario incorrecto");
         }
        }else
        {
            self::Session("error_user","Señor(a) usuario, su cuenta está inactiva");
        }
       }
       else
       {
          self::Session("error_user","No se a podido encontrar tu cuenta");
       }

   
       
    }

    /// mostrar la vista de olvidaste la contraseña

    public static function RestaurarPassword()
    {
        self::Auth();/// si el usuario está authenticado, redirecciona al dashboard
        self::View_("auth.ViewResetPassword");
    }

    /// cerrar la session del sistema

    public static function logout()
    {
        self::NoAuth();/// si no estas authenticado , redirige a login

        /// validamoa el token
        if(self::ValidateToken(self::post("token_")))
        {
            self::logout_();
        }
    }

    /// validamoa antes de enviar el correo de reseteo password
    public static function sendEmailResetPassword()
    {
        /// validamoa el token
        if(self::ValidateToken(self::post("token_")))
        {
            /// validamoa que sea un email
            if(!isEmail(self::post("email")))
            {
                self::Session("error","Ingrese un email correcto");
            }
            else
            {
             $Email = self::post("email");
             $token = generateToken();
             

             if(self::sendEmail($Email,$token))
             {
                self::Session("success","Le hemos enviado un correo para que pueda cambiar su contraseña");
             }
             else{
                self::Session("error","Ese correo no existe, vuelva a ingresar nuevamente");
             }
            }

           self::RedirectTo("reset-password");
        }
    }

    /// enviamos el correo al usuario, para que pueda resetear su contraseña

    private static function generateToken($Id,$token,$request_pass,$expired)
    {

        self::$ModelUser = new Usuario;

        /// actualizamos al usuario para indicarle que se va resetear la contraseña
       return self::$ModelUser->Update([
            "id_usuario"=>$Id,
            "token_password"=>$token,
            "request_password"=>$request_pass,
            "expired"=>$expired
        ]);
        
        /// instanciamos a la clase php mailer
        //$mail = new PHPMailer;

    }

    /// verificamos que exista el correo en la bd
    private static function sendEmail($correo,$token){

         /// verificamos que el email exista en la BD
         self::$ModelUser = new Usuario;
         $Usuario = self::$ModelUser->query()->Where("email","=",$correo)->select("id_usuario","name","email")->first();

         if($Usuario)
         {
            if($Usuario->email === $correo){

                /// generamos el tiempo de expiración del reseteo de contraseña

                $Tiempo_Expired = time() + (env("TIEMPO_VIDA_SESSION")*20); /// por defecto 5 minutos
             /// actualizamos al usuario para enviarle el token de reset password
              if(self::generateToken($Usuario->id_usuario,$token,"1",$Tiempo_Expired))
              {
                /// enviamos el correo al usuario para que pueda resetear su contraseña

               return self::send_($Usuario->email,$Usuario->name,"Reset Password",self::contentResetPassword($Usuario->id_usuario,$token));
              }
             
            }
             
         }
         return false;
    }
    
    /// generamos un contenido para el reseteo de contraseña

    private static function contentResetPassword($User_Id,$Token)
    {
        return ' <td class="esd-stripe" style="background-color: #fafafa;" bgcolor="#fafafa" align="center">
        <table class="es-content-body" style="background-color: #ffffff;" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center">
            <tbody>
                <tr>
                    <td class="esd-structure es-p40t es-p20r es-p20l" style="background-color: transparent; background-position: left top;" bgcolor="transparent" align="left">
                        <table width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td class="esd-container-frame" width="560" valign="top" align="center">
                                        <table style="background-position: left top;" width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-block-image es-p5t es-p5b" align="center" style="font-size:0"><a target="_blank"><img src="https://tlr.stripocdn.email/content/guids/CABINET_dd354a98a803b60e2f0411e893c82f56/images/23891556799905703.png" alt style="display: block;" width="175"></a></td>
                                                </tr>
                                                <tr>
                                                    <td class="esd-block-text es-p15t es-p15b" align="center">
                                                        <h1 style="color: #333333; font-size: 20px;"><strong>FORGOT YOUR </strong></h1>
                                                        <h1 style="color: #333333; font-size: 20px;"><strong>&nbsp;PASSWORD?</strong></h1>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="esd-block-text es-p40r es-p40l" align="center">
                                                        <p>Hola!</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="esd-block-text es-p35r es-p40l" align="left">
                                                        <p style="text-align: center;">¡Hubo una solicitud para cambiar su contraseña!</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="esd-block-text es-p25t es-p40r es-p40l" align="center">
                                                        <p>Si no realizó esta solicitud, simplemente ignore este correo electrónico. De lo contrario, haga clic en el botón a continuación para cambiar su contraseña:</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="esd-block-button es-p40t es-p40b es-p10r es-p10l" align="center"><span class="es-button-border"><a href="'.URL_BASE.'reset_password?id='.$User_Id.'&&token='.$Token.'" class="es-button" target="_blank">Recuperar mi contraseña</a></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="esd-structure es-p20t es-p10r es-p10l" style="background-position: center center;" align="left">
                        <!--[if mso]><table width="580" cellpadding="0" cellspacing="0"><tr><td width="199" valign="top"><![endif]-->
                        <table class="es-left" cellspacing="0" cellpadding="0" align="left">
                            <tbody>
                                <tr>
                                    <td class="esd-container-frame" width="199" align="left">
                                        <table style="background-position: center center;" width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-block-text es-p15t es-m-txt-c" align="right">
                                                        <p style="font-size: 16px; color: #666666;"><strong>Follow us:</strong></p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--[if mso]></td><td width="20"></td><td width="361" valign="top"><![endif]-->
                        <table class="es-right" cellspacing="0" cellpadding="0" align="right">
                            <tbody>
                                <tr>
                                    <td class="esd-container-frame" width="361" align="left">
                                        <table style="background-position: center center;" width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-block-social es-p10t es-p5b es-m-txt-c" align="left" style="font-size:0">
                                                        <table class="es-table-not-adapt es-social" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="es-p10r" valign="top" align="center"><a target="_blank" href><img src="https://tlr.stripocdn.email/content/assets/img/social-icons/rounded-gray/facebook-rounded-gray.png" alt="Fb" title="Facebook" width="32"></a></td>
                                                                    <td class="es-p10r" valign="top" align="center"><a target="_blank" href><img src="https://tlr.stripocdn.email/content/assets/img/social-icons/rounded-gray/twitter-rounded-gray.png" alt="Tw" title="Twitter" width="32"></a></td>
                                                                    <td class="es-p10r" valign="top" align="center"><a target="_blank" href><img src="https://tlr.stripocdn.email/content/assets/img/social-icons/rounded-gray/instagram-rounded-gray.png" alt="Ig" title="Instagram" width="32"></a></td>
                                                                    <td class="es-p10r" valign="top" align="center"><a target="_blank" href><img src="https://tlr.stripocdn.email/content/assets/img/social-icons/rounded-gray/youtube-rounded-gray.png" alt="Yt" title="Youtube" width="32"></a></td>
                                                                    <td class="es-p10r" valign="top" align="center"><a target="_blank" href><img src="https://tlr.stripocdn.email/content/assets/img/social-icons/rounded-gray/linkedin-rounded-gray.png" alt="In" title="Linkedin" width="32"></a></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--[if mso]></td></tr></table><![endif]-->
                    </td>
                </tr>
                <tr>
                    <td class="esd-structure es-p5t es-p20b es-p20r es-p20l" style="background-position: left top;" align="left">
                        <table width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td class="esd-container-frame" width="560" valign="top" align="center">
                                        <table width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                     
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>';
    
    }

    /// guardar cambios password
    public static function UpdatePasswordReset($id_user)
    {
        /// verificamos que esteee authenticado
        self::Auth();
        /// validamos el token
        if(self::ValidateToken(self::post("token_")))
        {
           if(empty(self::post("password")))
           {
            self::$Error [] = 'Ingrese su nueva password';
           }
           else
           {
             if(strlen(self::post("password"))<=7)
             {
                self::$Error[] = 'El password debe de tener una logitud de 8 caracteres como mínimo';
             }
                self::Session("password",self::post("password"));
           }

           if(empty(self::post("password_repeat")))
           {
            self::$Error [] = 'Vuelva a escribir su nueva password';
           }
           else
           {
             if(strlen(self::post("password_repeat"))<=7)
             {
                self::$Error[] = 'Su confirmación del password debe de tener una logitud de 8 caracteres como mínimo';
             }
           
           }

           if(self::post("password") !== self::post("password_repeat"))
           {
            self::$Error[] = 'Los passwords no coinciden';
           }


           if(count(self::$Error) > 0)
           {
            $token = self::post("token");
            self::Session("error_reset",self::$Error);
               /// redirigimos 
            self::RedirectTo("reset_password?id=".$id_user."&&token=".$token);
           }
           else
           {
           
            self::resetPasswordUpdate($id_user);
               /// redirigimos 
               self::RedirectTo("login");
           }
        }
    }
   
    /// modificar el password del usuario por correo
    private static function resetPasswordUpdate($usuario)
    {
        self::$ModelUser = new Usuario;

        $Respuesta = self::$ModelUser->Update([
           "id_usuario"=>$usuario,
           "pasword"=>password_hash(self::post("password"),PASSWORD_BCRYPT),
           "token_password"=>null,
           "request_password"=>"0",
           "expired"=>0
        ]);

        if($Respuesta)
        {
         /// almacenamos la respuesta en session
        self::Session("success_reset","Tu password a sido modificado correctamente,\nahora puedes inciar sesión sin problemas :)");
        }
    }
}