<?php
namespace lib;
use FPDF;
use models\Empresa;

class PdfResultados extends FPDF
{
    private String $dirSede;
    function Header()
    {
       
             /// agregamos un título a la hoja
        $this->Ln(7);
        $empresa = new Empresa;
        $DataEmpresa = $empresa->query()
        ->limit(1)
       ->first();

       $this->setFont("Times","B",12);
       $this->Ln(6);
       $this->SetX(20);
      
       $this->Cell(60,4,utf8__($DataEmpresa->nombre_empresa),0,1);
       $this->Ln(4);
       $this->SetX(1);
       $this->Cell(74,2,isset($DataEmpresa->ruc)  ? " RUC: ".$DataEmpresa->ruc:" RUC: xxxxxxxxxxx",0,1,"C");
       $this->Ln(5);
       $this->SetX(20);
       $this->Cell(124,2,$this->getDirSede(),0,1,"L");
       
       $this->Cell(83,10,$this->Image(isset($DataEmpresa->logo) ? "public/asset/empresa/".$DataEmpresa->logo:"public/asset/img/lgo_clinica_default.jpg",140.5,5,55,46,'JPG'),0,1);

         $this->Ln(10);
    }
    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-20);
        // Arial italic 8
        $this->SetFont('Times', 'B', 12);

        // Número de página
        // $this->Cell(200, 5, utf8__("DR.".strtoupper(self::getDoctor())) , 0, 1, 'C');
        // $this->Cell(200, 5, utf8__("MEDICINA INTERNA") , 0, 0, 'C');
    }

    /**
     * Get the value of dirSede
     *
     * @return String
     */
    public function getDirSede(): String
    {
        return $this->dirSede;
    }

    /**
     * Set the value of dirSede
     *
     * @param String $dirSede
     *
     * @return self
     */
    public function setDirSede(String $dirSede): self
    {
        $this->dirSede = $dirSede;

        return $this;
    }
}