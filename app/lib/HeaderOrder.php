<?php 
namespace lib;

use FPDF;

class HeaderOrder extends FPDF
{

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
    /// nombre de la empresa
    $this->SetY(8);
    $this->SetX(5);
    $this->setFont("Helvetica","B",10);
    $this->Cell(100,1,utf8__($empresa[0]->nombre_empresa),0,1,"L");
    
    $this->Ln(3);
    $this->SetX(5);
    $this->setFont("Helvetica","",6);
    $this->Cell(100,0,self::profile()->direccion_sede != null ? utf8__(self::profile()->name_departamento." - ".self::profile()->name_provincia." - ".self::profile()->name_distrito." - ".self::profile()->direccion_sede) : 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',0,1,"L");

    $this->Ln(3);
    $this->SetX(5);
    $this->Cell(5,0,utf8__("Telf:"),0,0,"L");
 
    $this->Cell(50,0,(self::profile()->telefono_sede!= null ? utf8__(self::profile()->telefono_sede):'XXX XXX XXX'),0,1,"L");

    $this->Ln(3);
    $this->SetX(5);
    $this->Cell(6,0,utf8__("Email:"),0,0,"L");
 
    $this->Cell(50,0,(self::profile()->correo_sede!= null ? utf8__(self::profile()->correo_sede):'XXXX@gmail.com'),0,0,"L");
    // Logo
    
    $this->Image(URL_BASE."public/asset/empresa/".$empresa[0]->logo,145,2,55,19,"PNG");
     
    $this->Ln(2);
    $this->SetX(5.5);

       $this->setFont("Helvetica","B",12);
       
       $this->Cell(140,2,"___________________________________________________________________________________",0);

       $this->Ln(4.5);
       $this->SetX(5.5);
       $this->SetFont("Helvetica","",5);
       $this->Cell(11,2,"PACIENTE:",0,0);

       $this->SetFont("Helvetica","B",7);
       $this->Cell(8,2,utf8__($this->datosOrde[0]->pacientedata),0,0);



       # num documento
    
       $this->SetX(85);
       $this->SetFont("Helvetica","",5);
       $this->Cell(16,2,utf8__("N° DOCUMENTO:"),0,0);

       $this->SetFont("Helvetica","B",7);
       $this->Cell(8,2,$this->datosOrde[0]->documento,0,0);


       $this->SetX(138);
       $this->SetFont("Helvetica","",5);
       $this->Cell(8,2,utf8__("MÉDICO:"),0,0);

       $this->SetFont("Helvetica","B",7);
       $this->Cell(8,2,utf8__($this->datosOrde[0]->medicodata),0,1);

       $this->Ln(1);

       $Edad = calcularEdad($this->datosOrde[0]->fecha_nacimiento,$this->FechaActual);
       $this->SetX(5.5);
       $this->SetFont("Helvetica","",5);
       $this->Cell(8,2,"EDAD:",0,0);

       $this->SetFont("Helvetica","B",7);
       $this->Cell(8,2,utf8__($Edad["años"]." año(s)"." ".$Edad["meses"]." mes(es)"),0,0);

       $this->SetX(85);
       $this->SetFont("Helvetica","",5);
       $this->Cell(13,2,utf8__("FECHA/HORA:"),0,0);

       $this->SetFont("Helvetica","B",6);
       $this->Cell(8,2,$this->FechaHora,0,0);


       $this->SetX(138);
       $this->SetFont("Helvetica","",5);
       $this->Cell(6,2,utf8__("CMP:"),0,0);

       $this->SetFont("Helvetica","B",7);
       $this->Cell(8,2,$this->datosOrde[0]->colegiatura,0,1);


       $this->Ln(2);
       $this->SetX(5.5);
       $this->SetFont("Helvetica","",5);
       $this->Cell(9,2,"GENERO:",0,0);

       $this->SetFont("Courier","B",7);
       $this->Cell(8,2,$this->datosOrde[0]->genero === '1' ? 'MASCULINO' : ($this->datosOrde[0]->genero === '2' ? 'FEMENINO' : 'SIN ESPECIFICAR'),0,0);

       $this->SetX(138);
       $this->SetFont("Helvetica","",5);
       $this->Cell(9,2,"RNE:",0,0);
 



       $this->Ln(1);
       $this->SetX(5.5);
       $this->setFont("Helvetica","B",12);
       $this->Cell(140,2,"___________________________________________________________________________________",0);

       $this->Ln(9);

       $this->SetX(0);
       $this->SetFont("Helvetica","B",19);
       $this->setTextColor(0,0,128);
       $this->Cell(210,4,utf8__("ORDEN MÉDICA N° : ".$this->datosOrde[0]->serieorden),0,1,"C");

       /// tabla
       $this->Ln(7);
       $this->SetX(9);
       $this->SetFont("Helvetica","B",8);
       $this->setTextColor(0,0,0);
       $this->Cell(5,1,"Cod.",0,0);

       $this->SetX(32);
       $this->Cell(5,1,utf8__("Descripción CPT."),0,0);

       $this->SetX(125);
       $this->Cell(5,1,utf8__("Tipo"),0,0);

       $this->SetX(190);
       $this->Cell(5,1,utf8__("Cant"),0,0);


       $this->Ln(1);
       $this->SetX(5.5);
       $this->setFont("Helvetica","B",12);
       $this->Cell(140,2,"___________________________________________________________________________________",0,1);

 
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
    // Posición: a 1,5 cm del final
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
      // Posición: a 1,5 cm del final
 
      $this ->SetX(60);
      $this ->SetX(115);
      $this ->SetFont("Helvetica","B",6);
      if(isset(self::MedicoData()->firma)){
        $this->Image((isset(self::MedicoData()->firma) ? URL_BASE."public/asset/imgfirmas/".self::MedicoData()->firma : ""),115,121,50,14,"JPG");
      }
      $this ->Cell(120,2,"________________________________________________",0,1);
      $this ->Ln(1.5);
      $this ->SetX(120);
      $this ->Cell(13,3,"Nombre:",0,0);
  
      $this ->Cell(25,3,utf8__($this->datosOrde[0]->medicodata),0,1);

        $EspecialidadesData = $this->EspecialidadesMedico;
        $esp = "";
        foreach ($EspecialidadesData as $data) {
            $esp .= $data->nombre_esp . "-";
        };

      $this ->SetX(120);
      $this ->Cell(15,3,"Especialidad:",0,0);
  
      $this ->Cell(25,3,utf8__(rtrim($esp,"-")),0,1);
  
      $this ->SetX(130);
      $this ->Cell(7,3,"CMP:",0,0);
  
      $this ->Cell(3,3,utf8__($this->datosOrde[0]->colegiatura),0,0);

      /// codigo qr de prueba

      self::setDirectorioQr("public/qr/ordenmedico.png");
       
      self::GenerateQr($this->datosOrde[0]->documento."|".$this->datosOrde[0]->pacientedata."|".$this->datosOrde[0]->serieorden."|".$this->datosOrde[0]->fecha_registro_orden);
      $this->SetX(145);
      $this->Cell(74,0,$this->Image(URL_BASE.self::getDirectorioQr(),177,124,22,22),0,1,"C");
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