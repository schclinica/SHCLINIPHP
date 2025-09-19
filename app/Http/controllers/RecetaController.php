<?php 
namespace Http\controllers;
use FPDF;
use Http\pageextras\PageExtra;
use lib\BaseController;
use lib\HeaderReceta;
use models\Detalle_Receta_Electronico;
use models\Empresa;
use models\Especialidad_Medico;
use models\Paciente;
use models\Plan_Atencion;
use models\ProductoFarmacia;
use models\Receta;
use models\RecetaElectronico;

class RecetaController extends BaseController
{
    /// generar reporte 
    private static $Model;
    public static function informe_receta_medica()
    {
        self::NoAuth();
     
       if(isset($_GET['v']) and self::profile()->rol === self::$profile[3])
       {
         
         $receta = new HeaderReceta("L","mm","A5");

          $modelData = new Receta;
          $modelEspecialidadesMedico = new Especialidad_Medico;

          $MedicoId = self::MedicoData()->id_medico;
          $dataEspecialidades = $modelEspecialidadesMedico->query()->Join("medico as m","med_esp.id_medico","=","m.id_medico")
          ->Join("especialidad as e","med_esp.id_especialidad","=","e.id_especialidad")
          ->select("nombre_esp")
          ->Where("med_esp.id_medico","=",$MedicoId)
          ->get();
 

          $sede = self::profile()->sede_id;
          $data = $modelData->procedure("proc_reporte_del_paciente","C",[self::get("v"),null,'receta',$sede]);
          if(!$data){PageExtra::Page404();exit;}

         $receta->setFechaActual(self::FechaActual("Y-m-d"));
         $receta->setFechaHora("12/05/2025 6:20:00 pm");
         $receta->setDatosOrde($data);
         $receta->setEspecialidadesMedico($dataEspecialidades);

         $receta->SetTitle("receta-medica-".$data[0]->pacientedata);

         $receta->AddPage();
         $receta->SetAutoPageBreak(true,35);
         $Item = 0;
         $receta->SetY(43);
          
         foreach($data as $recetadata){
            $Item > 1 ? $receta->Ln(1) : $receta->Ln(1);
            $Item++;
            $receta->SetX(8.5);
            $receta->setFont("Helvetica","",6);
            $receta->Cell(4,4,$Item.".",1,0,"C");

            $receta->SetX(13.5);
            $receta->setFont("Helvetica","",7);
            $receta->MultiCell(71,2.7,$recetadata->productodata != null ? strtoupper(utf8__($recetadata->productodata)) : '',0,0);

        
            $receta->SetX(90);
            $receta->setFont("Helvetica","",9);
            $receta->Cell(3,0,$recetadata->cantidad_producto != null ? $recetadata->cantidad_producto:'',0,0);

            $receta->SetX(112);
            $receta->setFont("Helvetica","",7);
            $receta->Cell(4,4,$Item.".",1,0,"C");

            $receta->SetX(117.5);
            $receta->setFont("Helvetica","",6.3);
            $receta->MultiCell(90.6,2.7, $recetadata->indicaciones!= null ? strtoupper(utf8__($recetadata->indicaciones)):'',0,0);
 
            $receta->Ln(1);
            $receta->setFont("Helvetica","B",10);
            $receta->SetX(5);
            $receta->Cell(202,1,"....................................................................................................      ....................................................................................................",0,1);
            $receta->SetMargins(10,20,10);
         }

         

        //  $ItemUno = 0;
       
        //   $receta->setY(43);
        //  foreach($data as $recetadata){
            
        //     $ItemUno++;
        //     $receta->SetX(112.5);
        //     $receta->setFont("Helvetica","",6);
        //     $receta->Cell(4,4,$ItemUno.".",1,0,"C");

        //     $receta->Ln(0.5);
        //     $receta->SetX(117.5);
        //     $receta->setFont("Helvetica","",6.3);
        //     $receta->MultiCell(91,3.5, $recetadata->indicaciones!= null ? strtoupper(utf8__($recetadata->indicaciones)):'',0,0);
 
           
        //     $receta->setFont("Helvetica","",9);
        //     $receta->SetX(110.5);
        //     $receta->Cell(110,1,"...........................................................................................................",0);
        //  }
      

          $receta->Output();/// mostramos el pdf
       }
       else
       {
        PageExtra::PageNoAutorizado();
       }
    }

    /**
     * Otro informe de la receta (personalizado)
     */
    public static function informe_receta_medica_personalizado()
    {
       
        self::NoAuth();
        
       if(isset($_GET['v']) and self::profile()->rol === self::$profile[3])
       {
        self::$Model = new RecetaElectronico;
        $RecetaDetalle = self::$Model->query()
        ->Join("detalle_receta_electronico as dre","dre.id_receta_electro","=","rel.id_receta_electro")
        ->where("serie_receta","=",$_GET["v"])
        ->get();
 
        if(count($RecetaDetalle) > 0)
        {
            $empresa = new Empresa;
            $DataEmpresa = $empresa->query()
            ->limit(1)
           ->first();
            $receta = new FPDF();
            $receta->SetTitle("Receta médica - ".$RecetaDetalle[0]->pacientedata,1);
            $receta->AddPage();/// Añadimos una nueva página

            $receta->SetX(10);
            $receta->Cell(50,10,$receta->Image(isset($DataEmpresa->logo)?"public/asset/empresa/".$DataEmpresa->logo:"public/asset/img/lgo_clinica_default.jpg" ,50,17,32,26,'PNG'),0,0,"C");
             
     
            $receta->SetX(10);
            $receta->SetDrawColor(112, 128, 144);
            $receta->SetFillColor(255, 255, 255);
            $receta->SetTextColor(0,0,0);
            $receta->SetFont("Arial","B",16);
           // $receta->Cell(190,0,utf8__('Receta médica'),0,1,'C',1);
            
            
            $receta->Image("public/asset/img/logo_clinica_2_transparente.png" ,60,30,100,100,'PNG');
            
            $receta->Ln(23);
            $receta->setX(50);
            $receta->SetFont("Arial","",10);
            $receta->SetFont("Arial","B",10);
            $receta->SetTextColor(0,0,0);
            $receta->Cell(179,23,utf8__(isset($DataEmpresa->nombre_empresa) ?$DataEmpresa->nombre_empresa:'XXXXXXXXXXXXXXX'),0,1,'L');
            $receta->SetFont("Arial","",10);
            $receta->SetY(33);
           
            $receta->Cell(295,23,str_replace("-","/",self::FechaActual("d-m-Y")),0,1,'C');
    
            $receta->SetFont("Arial","B",10);
            //$receta->SetY(43);
     
          
            $receta->setX(50);
            $receta->Cell(55,7,'Especialista',0,0,'L');
            $receta->SetFont("Arial","",10);
            $receta->Cell(83,7,utf8__(self::profile()->nombres." ".self::profile()->apellidos),0,1,'L');
            $receta->SetX(50);
            $receta->SetTextColor(0,0,0);
            $receta->SetFont("Arial","B",10);
            $receta->Cell(55,7,utf8__('Paciente'),0,0,'L');
            $receta->SetFont('Arial','',10);
            $receta->Cell(83,7,utf8__($RecetaDetalle[0]->pacientedata),0,1,'L');
            // $receta->SetX(40);
            // $receta->SetFont("Arial","B",10);
            // $receta->Cell(35,7,utf8__('Fecha atención'),1,0,'L');
            // $receta->SetFont("Arial","",10);
            // $receta->Cell(83,7,self::getFechaText($RecetaDetalle[0]->fecha_atencion).'          '.$RecetaDetalle[0]->horacita,1,1,'L');
            // $receta->SetFont("Arial","B",10);
            // $receta->SetX(40);
            // $receta->SetFont("Arial",'B',10);
            // $receta->Cell(118,7,'Tratamiento',1,1,'L');
            // $receta->SetX(40);
            // $receta->SetFont('Arial','',10);
            // $receta->MultiCell(118,7,$RecetaDetalle[0]->desc_plan != null ? utf8__(str_replace("\n"," - ",$RecetaDetalle[0]->desc_plan)):'----------------------------',1);
            // $receta->SetFont("Arial","B",10);
            // $receta->SetX(40);
            // $receta->Cell(35,7,'Plan de tratamiento ',1,0,'L');
            // $receta->SetFont("Arial","",10);
            // $receta->SetFillColor(105, 105, 105);
            // $receta->Cell(83,7,utf8__($RecetaDetalle[0]->plan_tratamiento),1,1,'L');
            // $receta->SetFont("Arial","B",10);
            // $receta->SetX(40);
            // $receta->SetFont("Arial","B",10);
            // $receta->Cell(118,7,utf8__('Descripción de los exámenes'),1,1,'L');
            // $receta->SetX(40);
            // $receta->SetFont("Arial","",8);
            // $receta->MultiCell(118,7,utf8__($RecetaDetalle[0]->desc_analisis_requerida == null?'----------------------------------------------------------------------------':$RecetaDetalle[0]->desc_analisis_requerida),1);

            // $receta->SetX(40);
    
            // $receta->SetFont("Arial","B",10);
            // $receta->Cell(118,7,utf8__("Próxima cita"),1,0,"L");

            // $receta->SetX(70);
            // $receta->SetFont("Arial","",10);
            // $receta->Cell(88,7,self::getFechaText($RecetaDetalle[0]->proxima_cita_medica),1);

            //$receta->Ln();
            $receta->SetX(50);
            $receta->SetFont("times","B",16);
            $receta->Cell(138,7,'Rx',0,1,'L');
            $receta->SetFont("Arial","I",7);
            $receta->SetX(50);
    
            $recetaDet = '';
            
            foreach($RecetaDetalle as $rec)
            {
               $recetaDet.='* '.$rec->productodata.PHP_EOL.mb_convert_encoding($rec->frecuencia, 'UTF-8', 'UTF-8').PHP_EOL.'Cantidad : '.$rec->cantidad_producto.PHP_EOL;
            }
            $receta->MultiCell(138,5,utf8__($recetaDet).
            '--------------------------------------------------------------------------------------------------------------------------------------------'.PHP_EOL.'TIEMPO TRATAMIENTO : '.utf8__($RecetaDetalle[0]->tiempo_tratamiento).PHP_EOL,0);
            // Arial italic 
            $receta->Ln(30);
            $receta->SetFont('Arial','B',10);
            $receta->SetDrawColor(105, 105, 105);
            $receta->Cell(0,10,'_____________________________________',0,1,'C');
            $receta->SetFont("Arial","B",10);
            $receta->Cell(0,0,utf8__("Firma Dr. ".self::profile()->nombres." ".self::profile()->apellidos),0,1,'C');
            
    #'D', "Receta médica - ".$RecetaDetalle[0]->paciente.".pdf", true 
            $receta->Output();/// mostramos el pdf
        }
        else
        {
            PageExtra::PageNoAutorizado();
        }
       }
       else
       {
        PageExtra::PageNoAutorizado();
       }
    }
    /**
     * Vista para generar una receta médica
     */
    public static function generarRecetaView()
    {
        self::NoAuth();
        if(self::profile()->rol === self::$profile[3])
        {
            self::View_("medico.generar_receta");
        }else{
            PageExtra::PageNoAutorizado();
        }
    }

    public static function buscarPaciente()
    {
        self::NoAuth();
        $datos = [];
        if(self::profile()->rol === self::$profile[3])
        {
            $modelPaciente = new Paciente;

            $datosPaciente = $modelPaciente->query()
                             ->Join("persona as per","pc.id_persona","=","per.id_persona")
                             ->get();
            $datos = ["pacientes" =>$datosPaciente];    
        }else{
             $datos = ["pacientes" => []];
        }

        self::json($datos);
    } 

    /// buscar los productos de la clinica para recetar al paciente
    public static function buscarProductos()
    {
        self::NoAuth();
        $datos = [];
        if(self::profile()->rol === self::$profile[3])
        {
            $modelProductos = new ProductoFarmacia;

            $sede = self::profile()->sede_id;
            $rol = self::profile()->rol;
            $datosProductos = $modelProductos->mostrar("",$rol,$sede); 
            $datos=["productos" => $datosProductos];
        }else{
             $datos = ["pacientes" => []];
        }

        self::json($datos);  
    }

    /// añadir a la cesta de la receta
    public static function anadirReceta()
    {
       self::NoAuth();

       if(self::profile()->rol === self::$profile[3])
       {
           if(self::ValidateToken(self::post("token_"))){
                /// verificamos si existe la sesion
                if (!isset($_SESSION["receta"])) {
                    $_SESSION["receta"] = [];
                }

                /// verificamos si la key existe, osea si el producto agregado existe
                if (!array_key_exists(self::post("producto"), $_SESSION["receta"])) {
                    $_SESSION["receta"][self::post("producto")]["producto"] = self::post("producto");
                    $_SESSION["receta"][self::post("producto")]["frecuencia"] = self::post("frecuencia");
                    $_SESSION["receta"][self::post("producto")]["dosis"] = self::post("dosis");
                    $_SESSION["receta"][self::post("producto")]["cantidad"] = self::post("cantidad");
                   
                    self::json(["response" => "agregado"]);
                }else{
                    self::json(["response" => "existe"]);
                }
           }else{
            self::json(["errortoken" => "Token invalid!"]);
           }
       }else{
        self::json(["erroracceso" => "No tienes authorizado esta tárea"]);
       }
    }

    /// mostrar los productos de la receta
    public static function showRecetaData()
    {
        self::NoAuth();
        if(self::profile()->rol === self::$profile[3])
        {
            if(self::ExistSession("receta")){
                $recetaDetalle = self::getSession("receta");

                self::json(["receta"=>$recetaDetalle]);
            }else{
                self::json(["receta"=>[]]);
            }
        }
    }

    /**
     * Eliminar un detalle de la receta seleccionado
     */
    public static function eliminarDetalleSeleccionado()
    {
        self::NoAuth();
        if(self::profile()->rol === self::$profile[3])
        {
            if(self::ValidateToken(self::post("token_")))
            {
                if(self::ExistSession("receta")){
                    unset($_SESSION["receta"][self::post("producto")]);
   
                   self::json(["response"=>"eliminado"]);
               }
            }else{
                self::json(["error_token"=>"Token Csrf invalid!"]);
            }
        } 
    }

    /**
     * Guardar la receta médica del paciente
     */
    public static function SaveReceta(){
        self::NoAuth();

        if(self::profile()->rol === self::$profile[3])
        {
            if(self::ValidateToken(self::post("token_")))
            {
                $modelReceta = new RecetaElectronico;

                $NumRecibo = self::post("seriereceta");
                $usuario = self::profile()->id_usuario;$sede = self::profile()->sede_id;

                $receta = $modelReceta->Insert([
                    "id_receta_electro" => $NumRecibo,
                   "fecha_receta" => self::FechaActual("Y:m:d H:i:s"),
                   "fecha_vencimiento" => self::post("fecha_vencimiento"),
                   "serie_receta" => $NumRecibo,
                   "id_paciente" => self::post("paciente_id"),
                   "id_medico" => self::MedicoData()->id_medico,
                   "atencion_id" => self::post("atencionid"),
                   "usuario_id" => $usuario,"sede_id" => $sede
                ]);

                /** Registramos en la tabla de receta electronica */
                if(self::ExistSession("receta") and $receta == true){

                    /// obtenemos la receta electronico registrado
                    $RecetaElectro = $modelReceta->query()->where("serie_receta","=",$NumRecibo)->first();
                    
                    $modelDetalleReceta = new Detalle_Receta_Electronico;
                    foreach(self::getSession("receta") as $receta)
                    {
                        $respuesta = $modelDetalleReceta->Insert([
                           "id_receta_electro" => $RecetaElectro->id_receta_electro,
                            "productodata" => $receta["medicamento"],
                            "indicaciones" => $receta["indicaciones"],
                            "cantidad_producto" => $receta["cantidad"],
                            "producto_id" => $receta["producto_id"] === 'null' ? null : $receta["producto_id"]
                          ]);
                    }
                    if($respuesta)
                    {
                        /// obtener el id de la receta 
                        self::json(["response" => "LA RECETA SE HA GUARDADO CORRECTAMENTE!!"]);
                        //url = "receta_medica?v=".$NumRecibo;

                        //echo "<script>window.open(".$url.",'_blank')</script>";

                        self::destroySession("receta");
                    } 
                    else{
                        self::json(["error" => "ERROR AL GUARDAR LA RECETA MÉDICA!!!"]);
                    }
                }else{
                    self::json(["error" => "ERROR AL GUARDAR LA RECETA MÉDICA"]);
                }
            }else{
                self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
            }
        }
    }

    /**
     * Cancelar o eliminar todo de la cesta carrito
     */
    public static function eliminarDeLaCestaReceta()
    {
        self::NoAuth();
       if(self::profile()->rol === self::$profile[3])
        {
            if(self::ValidateToken(self::post("token_")))
            {
               if(self::ExistSession("receta"))
               {
                  unset($_SESSION["receta"]);  
                  self::json(["response" => "ok"]);
               }
            }
        }
    }

    /**
     * Ver las recetas electronicas generados
     */
    public static function showRecetasGenerados(){
        self::NoAuth();

        if(self::profile()->rol === "Médico"){
            $modelreceta = new RecetaElectronico;
            $medico = self::MedicoData()->id_medico;
            $responseReceta = $modelreceta->query()
            ->Join("paciente as pc","rel.id_paciente","=","pc.id_paciente")
            ->Join("persona as p","pc.id_persona","=","p.id_persona")
            ->Where("rel.id_medico","=",$medico)
            ->get();

            self::json(["recetas"=>$responseReceta]);
        }else{
            self::json(["resetas"=>[]]);
        }
    }

    /**
     * Eliminar la receta
     */
    public static function deleteRecetaElectronica($id){
        self::NoAuth();
        if(self::profile()->rol === "Médico"){
            if(self::ValidateToken(self::post("token_"))){
                $modelreceta = new RecetaElectronico;

                $response = $modelreceta->delete($id);

                self::json(["response"=>$response?'ok':'error']);
            }else{
                self::json(["response"=>"token-invalid"]);
            }
        }else{
            self::json(["response"=>"no-authorized"]);
        }
    }

    /** AGREGAR A LA LISTA DE LA RECETA */
    public static function AgregarALaListaReceta($id){

        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
           
            if(!self::ExistSession("receta")){
                self::Session("receta",[]);
            }

            /// verificamos si existe el producto

            if(!array_key_exists(str_replace(" ","_",strtoupper(self::post("medicamento_desc"))),$_SESSION["receta"])){
                $_SESSION["receta"][str_replace(" ","_",strtoupper(self::post("medicamento_desc")))]["medicamento"] = self::post("medicamento_desc");
                $_SESSION["receta"][str_replace(" ","_",strtoupper(self::post("medicamento_desc")))]["indicaciones"] = self::post("indicacion_desc");
                $_SESSION["receta"][str_replace(" ","_",strtoupper(self::post("medicamento_desc")))]["cantidad"] = self::post("cantidad");
                $_SESSION["receta"][str_replace(" ","_",strtoupper(self::post("medicamento_desc")))]["producto_id"] = $id;
 
             self::json(["response" => "El medicamento ".self::post("medicamento_des")." a sido agregado correctamente!!"]);
            }else{
                self::json(["error" => "El medicamento ".self::post("medicamento_desc")." ya esta agregado!!"]);
            }
        }else{
            self::json(["error" => "error, token incorrecto!!"]);
        }
    }

    /** MOSTRAR EL DETALLE DE LA RECETA */
    public static function showDetalleReceta(){
         
        if(self::ExistSession("receta")){
            self::json(["detalle_receta" => self::getSession("receta")]);
        }else{
            self::json(["detalle_receta" => []]);
        }
    }

    /**ELIMINAR MEDICAMENTO AÑADIDO EN EL DETALLE DE LA RECETA */
    public static function QuitarMedicamentoReceta($id){
        self::NoAuth();
        if(self::ValidateToken(self::post("token_"))){
           
            if(isset($_SESSION["receta"][str_replace(" ","_",strtoupper($id))])){
                unset($_SESSION["receta"][str_replace(" ","_",strtoupper($id))]);
                self::json(["response" => "MEDICAMENTO QUITADO DE LA LISTA DE LA RECETA CORRECTAMENTE!!!"]);
            }else{
                self::json(["error" => "ERROR, NO SE PUDO QUITAR DE LA LISTA AL MEDICAMENTO DE LA RECETA!!!"]);
            }
        }else{
            self::json(["error" => "ERROR, TOKEN INCORRECTO!!!"]);
        }    
    }

 
}