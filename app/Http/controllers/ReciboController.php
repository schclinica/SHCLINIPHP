<?php
namespace Http\controllers;
use lib\BaseController;
use models\Caja;
use models\CitaMedica;
use models\Detalle_Recibo;
use models\Recibo;
use models\Servicio;

class ReciboController extends BaseController
{

    /** Mostramos los pacientes para generarles un recibo */
    public static function Pacientes_Sin_Recibo()
    {
        /// verificamos que estee authenticado
        self::NoAuth();/// redirige al login sin no esta Authenticado
        /// verificamos que el perfil sea médico
        if(self::profile()->rol === self::$profile[3] || self::profile()->rol === self::$profile[1]) 
        {
         /// validamos el token
         if(self::ValidateToken(self::get("token_")))
         {
          $Medico_Id =  self::profile()->rol === 'Médico' ?  self::MedicoData()->id_medico : null;
            /// llamamos al procedimiento almacenado
            $model = new CitaMedica;
            $sede = self::profile()->sede_id;
            $respuesta = $model->procedure("proc_pacientes_para_recibo","c",[$Medico_Id,$sede]);

            // imprimos en formato json
            self::json(["response" => $respuesta]);
         }
         else{
            self::json(["response" => "token-invalidate"]);
         }
        }
        else{
            self::json(["response"=>"no-authorized"]);
        }
    }

    /** Agregar lo servicios al detalle del recibo */
    public static function addDetalleService($id){
     // verificamos que estee authenticado
     self::NoAuth();/// redirige al login sin no esta Authenticado
     /// verificamos que el perfil sea médico
     if(self::profile()->rol === self::$profile[3] || self::profile()->rol === self::$profile[1])
     {
      /// validamos el token
      if(self::ValidateToken(self::post("token_")))
      {
         /// añadimos a la cesta de detalle service
         self::addServiceCesta($id);
      }
      else{
         self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
      }
     }
     else{
         self::json(["error"=>"ERROR, NO ESTAS AUTHORIZADO PARA REALIZAR ESTA TAREA!!!"]);
     }   
    }


    /// proceso de añadir a la cesta los servicios
    private static function addServiceCesta($id)
    {
        if(!isset($_SESSION["detalle_service"]))
        {
           $_SESSION["detalle_service"] = [];
        }

        /// verificamos si existe ese sertvicio

        $service = new Servicio;
        $dataService = $service->query()->Where("id_servicio","=",$id)->get();

        if(!array_key_exists(str_replace(" ","_",$dataService[0]->name_servicio),$_SESSION["detalle_service"]))
        {
            /// si no existe , añadimos a la cesta 
            $_SESSION["detalle_service"][str_replace(" ","_",$dataService[0]->name_servicio)]["servicio"] = $dataService[0]->name_servicio;
            $_SESSION["detalle_service"][str_replace(" ","_",$dataService[0]->name_servicio)]["precio"] = $dataService[0]->precio_servicio;
            $_SESSION["detalle_service"][str_replace(" ","_",$dataService[0]->name_servicio)]["preciomedico"] = $dataService[0]->precio_medico;
            $_SESSION["detalle_service"][str_replace(" ","_",$dataService[0]->name_servicio)]["precioclinica"] = $dataService[0]->precio_clinica;
            $_SESSION["detalle_service"][str_replace(" ","_",$dataService[0]->name_servicio)]["cantidad"] = 1;
            $_SESSION["detalle_service"][str_replace(" ","_",$dataService[0]->name_servicio)]["service_id"] = $id;
        
             
            self::json(["response" => "SERVICIO AGEREGADO A LA LISTA CORRECTAMENTE!!!"]);
        }
        else{
            self::json(["error" => "EEROR AL AGREGAR SERVICIO A LA LISTA, NO SE PUEDE AGREGAR DUPLICIDAD!!!"]);
        } 
    }

    /**MODIFICAR EL PRECIO DEL SERVICIO PARA DAR DESCUENTOS */
    public static function ModifyPriceServiceRecibo(){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
            if(isset($_SESSION["detalle_service"][str_replace(" ","_",self::post("service"))])){
                $_SESSION["detalle_service"][str_replace(" ","_",self::post("service"))]["precio"] = self::post("nuevo_precio");
                self::json(["success"=>"PRECIO MODIFICADO!!!"]); 
            }else{
                self::json(["error" => "ERROR AL APLICAR DESCUENTO!!!"]);
            }
        }else{
            self::json(["error" => "ERROR, EL TOKEN ES INCORRECTO!!!"]);
        }
    }

    /** MOSTRAR LO QUE CONTIENE LA CESTA DDEL DETALLE */
    public static function mostrarCestaServiceDetalle()
    {
     /// verificamos que estee authenticado
     self::NoAuth();/// redirige al login sin no esta Authenticado
     /// verificamos que el perfil sea médico
     if(self::profile()->rol === self::$profile[3] || self::profile()->rol === self::$profile[1])
     {
      /// validamos el token
      if(self::ValidateToken(self::get("token_")))
      {
         if(self::ExistSession("detalle_service"))
         {
            self::json(["response" => self::getSession("detalle_service")]);
         }
         else{
            self::json(["response"=>'vacio']);
         }
      }
      else{
         self::json(["response" => "token-invalidate"]);
      }
     }
     else{
         self::json(["response"=>[]]);
     }   
    }
    
    /** Quitar servicios del carrito detalle */
    public static function QuitarServiceCart()
    {
    /// Verificamos que si no está authenticado, redirigimos al login
    self::NoAuth();
    /// verificamos que sea el médico quién realice esa acción
     if(self::profile()->rol === self::$profile[3] || self::profile()->rol === self::$profile[1])
     {
        /// validamos el token
        if(self::ValidateToken(self::post("token_")))
        {
            /// verificamos que exista la jey detalle_service
            if(isset($_SESSION["detalle_service"][str_replace(" ","_",self::post("service"))]))
            {
                /// eliminamos el servicio del carrito
                 unset($_SESSION['detalle_service'][str_replace(" ","_",self::post("service"))]);
                 self::json(["response" => "eliminado"]);
                 exit;
            }
        }else{
            self::json(["response" => "token-invalidate"]);
        }
     }
     else{
        self::json(["response" => "no-authorized"]);
     }
  }

  /** Registramos el recibo generado del paciente */
  public static function saveRecibo()
  {
     /// Verificamos que si no está authenticado, redirigimos al login
     self::NoAuth();
     /// verificamos que sea el médico quién realice esa acción
      if(self::profile()->rol === self::$profile[3] || self::profile()->rol === self::$profile[1])
      {
         /// validamos el token
         if(self::ValidateToken(self::post("token_")))
         {
            $recibo = new Recibo;

            $caja = new Caja;
            $sede = self::profile()->sede_id;
            $usuario = self::profile()->id_usuario;
            $DataCaja = $caja->query()
                      ->Where("sede_id","=",$sede)
                      ->And("estado_clinica","=","a")
                      ->limit(1)->first();
            $respuesta = $recibo->Insert([
                "id_recibo" => self::post("recibo_numero"),
                "numero_recibo" => self::post("recibo_numero"),
                "fecha_recibo" => self::FechaActual("Y-m-d H:i:s"),
                "monto_pagar" => self::post("monto"),
                "cita_id" => self::post("citaid"),
                "caja_id" => $DataCaja->id_apertura_caja,
                "usuario_id" => $usuario
            ]);

            if($respuesta)
            {
                $detalle_recibo = new Detalle_Recibo; $importe_ = 0.00;$importeMedico = 0.00;
                $importeClinica = 0.00;$ImporteTotalMedico = 0.00;
                $ImporteTotalClinica = 0.00;$ImporteTotal = 0.00;

                if(self::ExistSession("detalle_service"))
                {
                   foreach(self::getSession("detalle_service") as $detalle)
                   {
                    $importe_ = $detalle["precio"] * $detalle["cantidad"];
                    $importeMedico =  $importe_* ($detalle["preciomedico"]/100);
                    $importeClinica = $importe_ * ($detalle["precioclinica"]/100);
                    
                    $ImporteTotalMedico+=$importeMedico;
                    $ImporteTotalClinica+=$importeClinica;
                    $ImporteTotal+=$importe_;

                    $value = $detalle_recibo->Insert([
                        "servicio" => $detalle["servicio"],
                        "precio" => $detalle["precio"],
                        "cantidad" => $detalle["cantidad"], 
                        "importe" => $importe_,
                        "service_id" =>$detalle["service_id"],
                        "recibo_id" => $recibo->ObtenerMaxRecibo()->num 
                    ]);
                   } 

                    if($value)
                    {
                        /// actualizamos la cita médica del atributo recibo_generado en si
                        $citamedica = new CitaMedica;
                        $citamedica->Update([
                            "id_cita_medica" => self::post("citaid"),
                            "recibo_generado" => "si",
                            "monto_pago" => $ImporteTotal,
                            "monto_medico" => $ImporteTotalMedico,
                            "monto_clinica" => $ImporteTotalClinica,
                            "estado" => "pagado"
                        ]);
                        self::destroySession("detalle_service");
                        self::json(["response" => "ok"]);
                    }
                    else
                    {
                        /// eliminamos el recibo registrado
                        $recibo->delete($recibo->ObtenerMaxRecibo()->num);
                        self::json(["response" => "erroradrian"]);
                    }
                }else
                {
                 /// eliminamos el recibo registrado
                 $recibo->delete($recibo->ObtenerMaxRecibo()->num);
                 self::json(["response" => "error"]);  
             
                }
            }
            else
            {
                self::json(["response" => "error"]);
            }
         }
    }
  }

  /// Método que cancelar para generar el recibo del paciente
  public static function CancelRecibo(int $id)
  {
    /// Verificamos que si no está authenticado, redirigimos al login
    self::NoAuth();
    /// verificamos que sea el médico quién realice esa acción
     if(self::profile()->rol === self::$profile[3] || self::profile()->rol === self::$profile[1])
     {
        /// validamos el token
        if(self::ValidateToken(self::post("token_")))
        {
           $modelCita = new CitaMedica;
           
           $modelCita->Update([
            "id_cita_medica" => $id,
            "recibo_generado" => null
           ]);
           self::json(["response" => "ok"]);
        }else
        {
            self::json(["response" => "token-invalidate"]);
        }
    }else{
        self::json(["response" => "no-authorized"]);
    }
  }

  /// limpiar lo añadido en la cesta , para casos de que el usuario desea cancelar
  public static function cancelDataRecibo()
  {
     /// Verificamos que si no está authenticado, redirigimos al login
     self::NoAuth();
     /// verificamos que sea el médico quién realice esa acción
      if(self::profile()->rol === self::$profile[3] || self::profile()->rol === self::$profile[1])
      {
         /// validamos el token
         if(self::ValidateToken(self::post("token_")))
         {
            if(self::ExistSession("detalle_service"))
            {
                self::destroySession("detalle_service");
                self::json(["response" => 'ok']);
            }
         }else
         {
            self::json(["response" => "token-invalidate"]);
         }
      }
      else
      {
        self::json(["response" => "no-authorized"]);
      }
  }
  /**mostramos los recibos que ya han sido generados */
  public static function mostrar_recibos_generados()
  {
     /// Verificamos que si no está authenticado, redirigimos al login
     self::NoAuth();
     /// verificamos que sea el médico quién realice esa acción
      if(self::profile()->rol === self::$profile[3] || self::profile()->rol === self::$profile[1])
      {
         /// validamos el token
         if(self::ValidateToken(self::get("token_")))
         { 
          $model_recibo = new Recibo;
           $sede = self::profile()->sede_id;
          if(self::profile()->rol === self::$profile[3]){
                    $MedicoIdAuth_ = self::MedicoData()->id_medico;
                    $data = $model_recibo->query()
                        ->Join("cita_medica as cm", "re.cita_id", "=", "cm.id_cita_medica")
                        ->Join("medico as m", "cm.id_medico", "=", "m.id_medico")
                        ->Join("paciente as pc", "cm.id_paciente", "=", "pc.id_paciente")
                        ->Join("persona as p", "pc.id_persona", "=", "p.id_persona")
                        ->select(
                            "re.id_recibo",
                            "re.numero_recibo",
                            "re.fecha_recibo",
                            "concat(p.apellidos,' ',p.nombres) as paciente_",
                            "re.monto_pagar",
                            "re.cita_id"
                        )
                        ->Where("cm.id_medico", "=", $MedicoIdAuth_)
                        ->And("cm.sedecita_id","=",$sede)
                        ->get();
          }else{
                    $data = $model_recibo->query()
                        ->Join("cita_medica as cm", "re.cita_id", "=", "cm.id_cita_medica")
                        ->Join("medico as m", "cm.id_medico", "=", "m.id_medico")
                        ->Join("paciente as pc", "cm.id_paciente", "=", "pc.id_paciente")
                        ->Join("persona as p", "pc.id_persona", "=", "p.id_persona")
                        ->select(
                            "re.id_recibo",
                            "re.numero_recibo",
                            "re.fecha_recibo",
                            "concat(p.apellidos,' ',p.nombres) as paciente_",
                            "re.monto_pagar",
                            "re.cita_id"
                        )
                        ->Where("cm.sedecita_id","=",$sede)
                        ->get();
          }
          self::json(["response" => $data]);
         }
         else
         {
          self::json(["response" => "token-invalidate"]);
         }
     }else
     {
        self::json(["response" => "no-authorized"]);
     }
  }

  /*ELIMINAR EL RECIBO QUE SE HA GENERADO */
  public static function eliminarReciboBD($idrecibo,$citaid){
    if(self::ValidateToken(self::post("token_")))
    {
        $recibo = new Recibo;

        $response = $recibo->delete($idrecibo);

        if($response){
            $cita = new CitaMedica;
            $cita->Update([
                "id_cita_medica" => $citaid,
                "recibo_generado" => "no"
             ]);

             self::json(["success" => "EL RECIBO SE HA ELIMINADO CORRECTAMENTE!!!"]);
        }else{
            self::json(["error" => "OCURRIO UN ERROR INESPERADO AL ELIMINAR EL RECIBO!!!"]);
        }

    }else{
        self::json(["error" => "ERROR, EL TOKEN ES INCORRECTO!!!"]);
    }
  }

  /** OBTENER EL DETALLE DEL RECIBO */
  public static function getDetalleRecibo($id){
    self::NoAuth();

    if(self::profile()->rol === "Médico" || self::profile()->rol === "Admisión"){
        if(self::ValidateToken(self::post("token_"))){
            
            /// Hacemos una consulta al detalle del recibo por el id del recibo
            $detalleRecibo = new Detalle_Recibo;

            $detalle = $detalleRecibo->query()->Join("recibo as r","dr.recibo_id","=","r.id_recibo")
            ->Join("servicio as s","dr.service_id","=","s.id_servicio")
            ->Where("r.id_recibo","=",$id)->get();
                if (!self::ExistSession("recibodetalleeditar")) {
                    self::Session("recibodetalleeditar", []);
                }

                if (count($_SESSION["recibodetalleeditar"]) > 0) {
                    self::Session("recibodetalleeditar", []);
                }

                foreach($detalle as $det){
                    if (!array_key_exists(str_replace(" ","_",$det->name_servicio), $_SESSION["recibodetalleeditar"])) {
                        $_SESSION["recibodetalleeditar"][str_replace(" ","_",$det->name_servicio)]["servicio"] = $det->servicio;
                        $_SESSION["recibodetalleeditar"][str_replace(" ","_",$det->name_servicio)]["cantidad"] = $det->cantidad;
                        $_SESSION["recibodetalleeditar"][str_replace(" ","_",$det->name_servicio)]["precio"] = $det->precio;
                        $_SESSION["recibodetalleeditar"][str_replace(" ","_",$det->name_servicio)]["preciomedico"] = $det->precio_medico;
                        $_SESSION["recibodetalleeditar"][str_replace(" ","_",$det->name_servicio)]["precioclinica"] = $det->precio_clinica;
                        $_SESSION["recibodetalleeditar"][str_replace(" ","_",$det->name_servicio)]["serviceid"] = $det->service_id;
                        $_SESSION["recibodetalleeditar"][str_replace(" ","_",$det->name_servicio)]["detallereciboid"] = $det->id_detalle_recibo;

                    }
                }

        }else{
            self::json(["error" => "TOKEN INCORRECTO!!"]);
        }
    }else{
        self::json(["error" => "NO ESTAS AUTHORIZADO PARA REALIZAR ESTA TAREA!!"]);
    }
  }

  /// agregar a la lista detalle editar service
   public static function addServiceCestaEdition($id)
    {
        self::NoAuth();
        if (self::ValidateToken(self::post("token_"))) {
            if (!isset($_SESSION["recibodetalleditar"])) {
                $_SESSION["recibodetalleditar"] = [];
            }

            /// verificamos si existe ese sertvicio

            $service = new Servicio;
            $dataService = $service->query()->Where("id_servicio", "=", $id)->get();

            if (!array_key_exists(str_replace(" ", "_",$dataService[0]->name_servicio), $_SESSION["recibodetalleeditar"])) {
                /// si no existe , añadimos a la cesta 
                $_SESSION["recibodetalleeditar"][str_replace(" ", "_", $dataService[0]->name_servicio)]["servicio"] = $dataService[0]->name_servicio;
                $_SESSION["recibodetalleeditar"][str_replace(" ", "_", $dataService[0]->name_servicio)]["cantidad"] = 1;
                $_SESSION["recibodetalleeditar"][str_replace(" ", "_", $dataService[0]->name_servicio)]["precio"] = $dataService[0]->precio_servicio;
                $_SESSION["recibodetalleeditar"][str_replace(" ", "_", $dataService[0]->name_servicio)]["preciomedico"] = $dataService[0]->precio_medico;
                $_SESSION["recibodetalleeditar"][str_replace(" ", "_", $dataService[0]->name_servicio)]["precioclinica"] = $dataService[0]->precio_clinica;
                $_SESSION["recibodetalleeditar"][str_replace(" ", "_", $dataService[0]->name_servicio)]["serviceid"] = $dataService[0]->id_servicio;
                $_SESSION["recibodetalleeditar"][str_replace(" ", "_", $dataService[0]->name_servicio)]["detallereciboid"] = null;


                self::json(["response" => "SERVICIO AGREGADO A LA LISTA CORRECTAMENTE!!!"]);
        }
        else{
            self::json(["error" => "ERROR AL AGREGAR SERVICIO A LA LISTA, NO SE PUEDE AGREGAR DUPLICIDAD!!!"]);
        } 
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }
    }

  /** OBTENER LOS SERVICIOS DEL DETALLE AL REALIZAR LA EDICION */
  public static function showDetalleServiceEdition(){
     self::NoAuth();
    if(self::profile()->rol === 'Médico' || self::profile()->rol === 'Admisión'){
         if(self::ExistSession("recibodetalleeditar")){
              self::json(["detallerecibo" => $_SESSION["recibodetalleeditar"]]);
         }else{
              self::json(["detallerecibo" => []]);
         }
    }else{
        self::json(["detallerecibo" => []]);
    }
  }

  /** QUITAR DE LISTA DEL TALLE EDITADO SERVICIOS */
  public static function QuitarServiceDetalleEdition($id){
    self::NoAuth();
    if(self::profile()->rol === 'Médico' || self::profile()->rol === 'Admisión'){
      if(self::ValidateToken(self::post("token_"))){
          /// Hacemos una consulta al detalle del recibo por el id del recibo
        $detalleRecibo = new Servicio;

        $detalle = $detalleRecibo->query()->Where("serv.id_servicio","=",$id)->get();
         if(isset($_SESSION["recibodetalleeditar"][str_replace(" ","_",$detalle[0]->name_servicio)])){
            unset($_SESSION["recibodetalleeditar"][str_replace(" ","_",$detalle[0]->name_servicio)]);
            self::json(["response" => "SERVICIO QUITADO CORRECTAMENTE!!!"]);
         }else{
            self::json(["error" => "ERROR AL QUITAR DE LISTA AL SERVICIO!!"]);
         }
      }else{
         self::json(["error" => "ERROR, TOKEN INCORRECTO!!"]);
      }
    }else{
        self::json(["error" => "NO TIENES PERMISOS PARA REALIZAR ESTA ACCION!!"]);
    }
  }


  /** Registramos el recibo generado del paciente */
  public static function UpdateRecibo($id)
  {
     /// Verificamos que si no está authenticado, redirigimos al login
     self::NoAuth();
     /// verificamos que sea el médico quién realice esa acción
      if(self::profile()->rol === self::$profile[3] || self::profile()->rol === self::$profile[1])
      {
         /// validamos el token
         if(self::ValidateToken(self::post("token_")))
         {
            /// eliminar todas el detalle del recibo con respecto al id recibo
              
 
                $detalle_recibo = new Detalle_Recibo; 
                $importe_ = 0.00;$importeMedico = 0.00;
                $importeClinica = 0.00;$ImporteTotalMedico = 0.00;
                $ImporteTotalClinica = 0.00;$ImporteTotal = 0.00;

                $detalle_recibo->delete($id);
                $recibo = new Recibo;
                if(self::ExistSession("recibodetalleeditar"))
                {
                   foreach(self::getSession("recibodetalleeditar") as $detalle)
                   {
                    $importe_ = $detalle["precio"] * $detalle["cantidad"];
                    $importeMedico =  $importe_* ($detalle["preciomedico"]/100);
                    $importeClinica = $importe_ * ($detalle["precioclinica"]/100);
                    
                    $ImporteTotalMedico+=$importeMedico;
                    $ImporteTotalClinica+=$importeClinica;
                    $ImporteTotal+=$importe_;

                    $value = $detalle_recibo->Insert([
                        "servicio" => $detalle["servicio"],
                        "precio" => $detalle["precio"],
                        "cantidad" => $detalle["cantidad"], 
                        "importe" => $importe_,
                        "service_id" =>$detalle["serviceid"],
                        "recibo_id" => $id 
                    ]);
                   } 

                    if($value)
                    {
                        $recibo->Update([
                            "id_recibo" => $id,
                            "monto_pagar" => $ImporteTotal,
                        ]);
                        /// actualizamos la cita médica del atributo recibo_generado en si
                        $citamedica = new CitaMedica;
                        $citamedica->Update([
                            "id_cita_medica" => self::post("citaid"),
                            "monto_pago" => $ImporteTotal,
                            "monto_medico" => $ImporteTotalMedico,
                            "monto_clinica" => $ImporteTotalClinica,
                        ]);
                        self::destroySession("recibodetalleeditar");
                        self::json(["response" => "RECIBO DE PAGO MODIFICADO CORRECTAMENTE!!!"]);
                    }
                    else
                    {
                        /// eliminamos el recibo registrado
                        $recibo->delete($id);
                        self::json(["response" => "erroradrian"]);
                    }
                }else
                {
                 /// eliminamos el recibo registrado
                 $recibo->delete($id);
                 self::json(["response" => "error"]);  
             
                }
             
         }
    }
  }
}