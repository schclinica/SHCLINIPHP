<?php 
namespace lib;

use FPDF;

class HeaderReceta extends FPDF
{
 use Fecha;
 private array $datosOrde =[];
 private String $FechaActual;
 private String $FechaHora;
 private array $EspecialidadesMedico = [];
 use Session,QrCode;
// Cabecera de página
function Header()
{
    /// empresa
    $empresa = $this->BusinesData();

    /// separacion
    $this->setTextColor(0,0,128);
    $this->SetFillColor(248,248,248);
    $this->SetY(0);
    $this->SetX(107);
    $this->SetFont("Times","",1);
    $this->Cell(0.02,148.5,"",1,0);
    /// nombre de la empresa
    $this->SetY(4.2);
    $this->SetX(5);
    $this->setFont("Helvetica","B",7);
    $this->Cell(100,1,strtoupper(utf8__($empresa[0]->nombre_empresa)),0,1,"L");
    
    $this->Ln(3);
    $this->SetX(5);
    $this->setFont("Helvetica","",5);
    $this->Cell(100,0,self::profile()->direccion_sede != null ? utf8__(self::profile()->name_departamento." - ".self::profile()->name_provincia." - ".self::profile()->name_distrito." - ".self::profile()->direccion_sede) : 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',0,1,"L");

    $this->Ln(2);
    $this->SetX(5);
    $this->Cell(5,0,utf8__("Telf:"),0,0,"L");
 
    $this->Cell(50,0,(self::profile()->telefono_sede!= null ? utf8__(self::profile()->telefono_sede):'XXX XXX XXX'),0,1,"L");

    $this->Ln(2);
    $this->SetX(5);
    $this->Cell(6,0,utf8__("Email:"),0,0,"L");
 
    $this->Cell(50,0,(self::profile()->correo_sede!= null ? utf8__(self::profile()->correo_sede):'XXXX@gmail.com'),0,0,"L");
    // Logo
    
    $this->Image(URL_BASE."public/asset/empresa/".$empresa[0]->logo,62.8,2,40,11,"PNG");
     
  
    $this->SetX(5.5);

       $this->setFont("Helvetica","B",12);
       $this->Cell(52,1,"_________________________________________",0);

       $this->Ln(4.5);
       $this->SetX(5.5);
       $this->SetFont("Helvetica","",5);
       $this->Cell(10,2,"PACIENTE:",0,0);

       $this->SetFont("Helvetica","B",4.5);
       $this->Cell(8,2,utf8__($this->datosOrde[0]->pacientedata),0,0);



       # num documento
    
       $this->SetX(50);
       $this->SetFont("Helvetica","",5);
       $this->Cell(7,2,utf8__("N° DOC:"),0,0);

       $this->SetFont("Helvetica","B",5);
       $this->Cell(8,2,$this->datosOrde[0]->documento,0,0);

       $this->SetX(71);
       $this->SetFont("Helvetica","",4.5);
       $this->Cell(5,2,utf8__("# REC: "),0,0);

       $this->SetFont("Helvetica","B",4.5);
       $this->Cell(8,2,$this->datosOrde[0]->serie_receta,0,1);


       $this->Ln(1);

       $Edad = calcularEdad($this->datosOrde[0]->fecha_nacimiento,$this->FechaActual);
       $this->SetX(5.5);
       $this->SetFont("Helvetica","",5);
       $this->Cell(8,2,"EDAD:",0,0);

       $this->SetFont("Helvetica","B",5);
       $this->Cell(8,2,utf8__($Edad["años"]." año(s)"." ".$Edad["meses"]." mes(es)"),0,0);

       $this->Ln(0.5);
       $this->SetX(35);
       $this->SetFont("Helvetica","",5);
       $this->Cell(9,2,"GENERO:",0,0);

       $this->SetFont("Courier","B",5);
       $this->Cell(8,2,$this->datosOrde[0]->genero === '1' ? 'MASCULINO' : ($this->datosOrde[0]->genero === '2' ? 'FEMENINO' : 'SIN ESPECIFICAR'),0,0);


       $this->SetX(56);
       $this->SetFont("Helvetica","",4.5);
       $this->Cell(8,2,utf8__("MÉDICO:"),0,0);

       $this->SetFont("Helvetica","B",4.5);
       $this->Cell(8,2,strtoupper(utf8__($this->datosOrde[0]->medicodata)),0,1);

       $this->SetX(5.5);
       $this->SetFont("Helvetica","",5);
       $this->Cell(15,2,"FECHA RECETA:",0,0);

       $this->SetFont("Helvetica","B",5);
       $this->Cell(8,2, $this->datosOrde[0]->fecha_receta!= null ? utf8__(self::FechaFormat($this->datosOrde[0]->fecha_receta)) :'',0,0);

       $this->Ln(0.5);
       $this->SetX(40);
       $this->SetFont("Helvetica","",5);
       $this->Cell(14.5,2,utf8__("VÁLIDA HASTA:"),0,0);

       $this->SetFont("Helvetica","B",5);
       $this->Cell(8,2,$this->datosOrde[0]->fecha_vencimiento!= null ? self::FechaFormat($this->datosOrde[0]->fecha_vencimiento) : '',0,0);



       
       $this->SetX(70);
       $this->SetFont("Helvetica","",5);
       $this->Cell(6,2,utf8__("CMP:"),0,0);

       $this->SetFont("Helvetica","B",5);
       $this->Cell(8,2,utf8__($this->datosOrde[0]->colegiatura),0,1);
     
       $this->SetX(5.5);
       $this->setFont("Helvetica","B",12);
       $this->Cell(52,0,"_________________________________________",0);

       
       $this->Ln(6);

       $this->SetX(0);
       $this->SetFont("Helvetica","B",20);
       $this->Cell(110,4,utf8__("RECETA MÉDICA"),0,1,"C");


       /// columnas 
       $this->Ln(4);
       $this->setX(7.5);
       $this->setFont("Helvetica","",5);
       $this->Cell(3,1,"Rp/.",0,0);

       $this->setX(13.7);
       $this->setFont("Helvetica","",5);
       $this->Cell(3,1,utf8__("DESCRIPCIÓN"),0,0);

       $this->setX(87);
       $this->setFont("Helvetica","",5);
       $this->Cell(3,1,utf8__("CANTIDAD"),0,1);

 
       $this->setX(5.5);
       $this->Image("public/asset/empresa/".self::BusinesData()[0]->img_fondo,8,61,95,23);

       /// otra parte de la hoja

       /// nombre de la empresa
       $this->SetY(4.2);
       $this->SetX(109);
       $this->setFont("Helvetica","B",7);
       $this->Cell(100,1,strtoupper(utf8__($empresa[0]->nombre_empresa)),0,1,"L");
       
       $this->Ln(3);
       $this->SetX(109);
       $this->setFont("Helvetica","",5);
       $this->Cell(100,0,self::profile()->direccion_sede != null ? utf8__(self::profile()->name_departamento." - ".self::profile()->name_provincia." - ".self::profile()->name_distrito." - ".self::profile()->direccion_sede) : 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',0,1,"L");
   
       $this->Ln(2);
       $this->SetX(109);
       $this->Cell(5,0,utf8__("Telf:"),0,0,"L");
    
       $this->Cell(50,0,(self::profile()->telefono_sede!= null ? utf8__(self::profile()->telefono_sede):'XXX XXX XXX'),0,1,"L");
   
       $this->Ln(2);
       $this->SetX(109);
       $this->Cell(6,0,utf8__("Email:"),0,0,"L");
    
       $this->Cell(50,0,(self::profile()->correo_sede!= null ? utf8__(self::profile()->correo_sede):'XXXX@gmail.com'),0,0,"L");
       // Logo
       
       $this->Image(URL_BASE."public/asset/empresa/".$empresa[0]->logo,166.8,2,40,11);
        
     
       $this->SetX(109.5);
   
          $this->setFont("Helvetica","B",12);
          $this->Cell(52,1,"_________________________________________",0);
   
          $this->Ln(4.5);
          $this->SetX(109.5);
          $this->SetFont("Helvetica","",5);
          $this->Cell(10,2,"PACIENTE:",0,0);
   
          $this->SetFont("Helvetica","B",4.5);
          $this->Cell(8,2,utf8__($this->datosOrde[0]->pacientedata),0,0);
   
   
   
          # num documento
       
          $this->SetX(152);
          $this->SetFont("Helvetica","",4.5);
          $this->Cell(7,2,utf8__("N° DOC:"),0,0);
   
          $this->SetFont("Helvetica","B",4.5);
          $this->Cell(8,2,$this->datosOrde[0]->documento,0,0);
   
          $this->SetX(173);
          $this->SetFont("Helvetica","",4.5);
          $this->Cell(6,2,utf8__("# REC:"),0,0);
   
          $this->SetFont("Helvetica","B",4.5);
          $this->Cell(8,2,$this->datosOrde[0]->serie_receta,0,1);
   
   
          $this->Ln(1);
   
          $Edad = calcularEdad($this->datosOrde[0]->fecha_nacimiento,$this->FechaActual);
          $this->SetX(109.5);
          $this->SetFont("Helvetica","",5);
          $this->Cell(8,2,"EDAD:",0,0);
   
          $this->SetFont("Helvetica","B",5);
          $this->Cell(8,2,utf8__($Edad["años"]." año(s)"." ".$Edad["meses"]." mes(es)"),0,0);
   
          $this->Ln(0.5);
          $this->SetX(136);
          $this->SetFont("Helvetica","",4.5);
          $this->Cell(9,2,"GENERO:",0,0);
   
          $this->SetFont("Courier","B",4.5);
          $this->Cell(8,2,$this->datosOrde[0]->genero === '1' ? 'MASCULINO' : ($this->datosOrde[0]->genero === '2' ? 'FEMENINO' : 'SIN ESPECIFICAR'),0,0);
   
   
          $this->SetX(157);
          $this->SetFont("Helvetica","",4.5);
          $this->Cell(8,2,utf8__("MÉDICO:"),0,0);
   
          $this->SetFont("Helvetica","B",4.5);
          $this->Cell(8,2,strtoupper(utf8__($this->datosOrde[0]->medicodata)),0,1);
   
          $this->SetX(109.5);
          $this->SetFont("Helvetica","",5);
          $this->Cell(15,2,"FECHA RECETA:",0,0);
   
          $this->SetFont("Helvetica","B",5);
          $this->Cell(8,2,$this->datosOrde[0]->fecha_receta!= null ? utf8__(self::FechaFormat($this->datosOrde[0]->fecha_receta)) :'',0,0);
   
          $this->Ln(0.5);
          $this->SetX(144);
          $this->SetFont("Helvetica","",5);
          $this->Cell(14.5,2,utf8__("VÁLIDA HASTA:"),0,0);
   
          $this->SetFont("Helvetica","B",5);
          $this->Cell(8,2, $this->datosOrde[0]->fecha_vencimiento!= null ? self::FechaFormat($this->datosOrde[0]->fecha_vencimiento) : '',0,0);
   
   
   
          
          $this->SetX(174);
          $this->SetFont("Helvetica","",5);
          $this->Cell(6,2,utf8__("CMP:"),0,0);
   
          $this->SetFont("Helvetica","B",5);
          $this->Cell(8,2,utf8__($this->datosOrde[0]->colegiatura),0,1);
        
          $this->SetX(109.5);
          $this->setFont("Helvetica","B",12);
          $this->Cell(52,0,"_________________________________________",0);
       //aqui

       $this->Ln(6);

       $this->SetX(104);
       $this->SetFont("Helvetica","B",20);
       $this->Cell(110,4,utf8__("INDICACIONES"),0,1,"C");


       $this->Ln(4);
       $this->setX(111.5);
       $this->setFont("Helvetica","",5);
       $this->Cell(3,1,"Rx/.",0,0);

       
       $this->Image("public/asset/empresa/".self::BusinesData()[0]->img_fondo,111,61,95,23);
       /// tabla
        
        
 
    
    // Salto de línea
    if($this->PageNo() >1){
        $this->Ln(5);
    }else{
        $this->Ln(1);
    }
}

// Pie de página
function Footer()
{
   
    self::setDirectorioQr("public/qr/receta.png");
    self::GenerateQr($this->datosOrde[0]->documento."|".$this->datosOrde[0]->pacientedata."|".$this->datosOrde[0]->serie_receta."|".$this->datosOrde[0]->fecha_receta);
    // Posición: a 1,5 cm del final
    // Posición: a 1,5 cm del final
    $this->SetY(-16);
    $this->SetAutoPageBreak(true,55); 
 
    // Arial italic 8
      // Posición: a 1,5 cm del final
 

     /// primero

     $this ->SetX(5.5);
     $this ->SetFont("Helvetica","B",6);
  
     if(self::MedicoData()->firma != null){
          $this->Image(URL_BASE."public/asset/imgfirmas/".self::MedicoData()->firma,5.5,120,55,15,"JPG");
      }
     $this ->Cell(120,2,"________________________________________________",0,1);
     $this ->Ln(1.5);
     $this ->SetX(5.5);
     $this ->Cell(9,3,"Nombre:",0,0);
 
     $this ->Cell(25,3,utf8__($this->datosOrde[0]->medicodata),0,1);
 
    $EspecialidadesData = $this->EspecialidadesMedico;
        $esp = "";
        foreach ($EspecialidadesData as $data) {
            $esp .= $data->nombre_esp . "-";
        };

         
     $this ->SetX(5.5);
     $this ->Cell(15,3,"Especialidad:",0,0);
 
     $this ->Cell(25,3,utf8__(rtrim($esp,"-")),0,1);
 
     $this ->SetX(5.5);
     $this ->Cell(7,3,"CMP:",0,0);
 
     $this ->Cell(3,3,utf8__($this->datosOrde[0]->colegiatura),0,0);

     /// codigo qr de prueba
     $this->SetX(148);
     $this->Cell(74,0,$this->Image(URL_BASE.self::getDirectorioQr(),80,120,24,22),0,1,"C");

     $this->setX(57);
     $this->setFont("Helvetica","",6);
     $this->Cell(24,2,utf8__("FECHA PRÓXIMA CITA: "),0,0);

     $this->setFont("Helvetica","B",6);
     $this->Cell(12,2,self::FechaFormat($this->datosOrde[0]->proxima_cita),0,1);


     // fin


     /// segundo
     $this->SetY(-16);
      $this ->SetX(108);
      $this ->SetFont("Helvetica","B",6);
      if(self::MedicoData()->firma != null){
          $this->Image(URL_BASE."public/asset/imgfirmas/".self::MedicoData()->firma,108,120,55,15,"JPG");
      }
      $this ->Cell(120,2,"________________________________________________",0,1);
      $this ->Ln(1.5);
      $this ->SetX(109);
      $this ->Cell(9,3,"Nombre:",0,0);
  
      $this ->Cell(25,3,utf8__($this->datosOrde[0]->medicodata),0,1);
  
  
      $this ->SetX(109);
      $this ->Cell(15,3,"Especialidad:",0,0);
  
      $this ->Cell(25,3,utf8__(rtrim($esp,"-")),0,1);
  
      $this ->SetX(109);
      $this ->Cell(7,3,"CMP:",0,0);
  
      $this ->Cell(3,3,utf8__($this->datosOrde[0]->colegiatura),0,0);

      /// codigo qr de prueba
      $this->SetX(148);
      $this->Cell(74,0,$this->Image(URL_BASE.self::getDirectorioQr(),183,120,24,22),0,1,"C");

      $this->setX(160);
      $this->setFont("Helvetica","",6);
      $this->Cell(24,2,utf8__("FECHA PRÓXIMA CITA: "),0,0);

      $this->setFont("Helvetica","B",6);
      $this->Cell(12,2,self::FechaFormat($this->datosOrde[0]->proxima_cita),0,1);
}


 /**
  * Set the value of datosOrde
  *
  * @param array $datosOrde
  *
  * @return self
  */
 public function setDatosOrde(array $datosOrde): self
 {
  $this->datosOrde = $datosOrde;

  return $this;
 }


 /**
  * Set the value of FechaActual
  *
  * @param String $FechaActual
  *
  * @return self
  */
 public function setFechaActual(String $FechaActual): self
 {
  $this->FechaActual = $FechaActual;

  return $this;
 }


 /**
  * Set the value of FechaHora
  *
  * @param String $FechaHora
  *
  * @return self
  */
 public function setFechaHora(String $FechaHora): self
 {
  $this->FechaHora = $FechaHora;

  return $this;
 }


 /**
  * Set the value of EspecialidadesMedico
  *
  * @param array $EspecialidadesMedico
  *
  * @return self
  */
 public function setEspecialidadesMedico(array $EspecialidadesMedico): self
 {
  $this->EspecialidadesMedico = $EspecialidadesMedico;

  return $this;
 }
}