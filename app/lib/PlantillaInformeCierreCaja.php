<?php 
namespace lib;

use FPDF;
use Http\pageextras\PageExtra;
use models\Empresa;

class PlantillaInformeCierreCaja extends FPDF{
    use Session,Fecha;
    private String $UsuarioInforme;
    private String $TitleDoc;
    private String $DireccionSede;
    public function setUsuarioInforme(String $usu){
        $this->UsuarioInforme = $usu;
    }
    public function getUsuarioInforme(){
        return $this->UsuarioInforme;
    }
// Cabecera de página
function Header()
{
  /// agregamos datos de la empresa
     $empresa = new Empresa;
     $DataEmpresa = $empresa->query()
                    ->limit(1)->first();

    if(!$DataEmpresa){PageExtra::PageNoAutorizado();}
    $this->setFont("Times","B",15);
    $this->SetTextColor(0,0,0);
    $this->Cell(190,0,$this->Image(isset($DataEmpresa->logo)?"public/asset/empresa/".$DataEmpresa->logo:"public/asset/img/lgo_clinica_default.jpg",80,5,40,40,'PNG'),0,0,false);
    $this->Ln(36);

    $this->setFont("Times","B",12);
    $this->MultiCell(190,5,utf8__(isset($DataEmpresa->nombre_empresa) ?$DataEmpresa->nombre_empresa:'XXXXXXXXXXXXXXX')." RUC : ".(isset($DataEmpresa->ruc)  ? $DataEmpresa->ruc:" RUC: xxxxxxxxxxx")." ".PHP_EOL.(self::profile()->direccion_sede != null ? utf8__(self::profile()->name_departamento." - ".self::profile()->name_provincia." - ".self::profile()->name_distrito." - ".self::profile()->direccion_sede) : $this->getDireccionSede()),0,"C",false);

    $this->Ln(9);
    $this->setFont("Times","B",13);
    $this->Cell(190,2,utf8__($this->getTitleDoc()),0,1,"C");
    $this->Cell(190,2,"_______________________________________________",0,1,"C");
    $this->Ln(8);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-55);
   $this->SetFillColor(0,0,0);
    $this->SetTextColor(0,0,0);
    $this->Ln(13);
    $this->SetX(20);
    $this->SetFont("Times","B",12);
    $this->Cell(34,"4",utf8__("Recibí conforme :  "),0,0,"L");
    $this->Cell(130,"4",utf8__("______________________________________________________________"),0,1,"L");

    $this->Ln(4);
 
    $this->SetX(20);
    $this->SetFont("Times","B",12);
    $this->Cell(45,"6",utf8__("Usuario quién entrega :  "),0,0,"L");
    $this->SetFont("Times","",12);
    $this->Cell(60,"6",utf8__($this->getUsuarioInforme()),0,1,"L");
    $this->Ln(4);

    $Fecha_ = explode("-",self::FechaActual("Y-m-d"));
    $this->setFont("Times","B",12);
    $this->SetX(152);
    $this->Cell(180,10,self::getFechaText($Fecha_[2]."/".$Fecha_[1]."/".$Fecha_[0]),0,1);
}

    /**
     * Get the value of TitleDoc
     *
     * @return String
     */
    public function getTitleDoc(): String
    {
        return $this->TitleDoc;
    }

    /**
     * Set the value of TitleDoc
     *
     * @param String $TitleDoc
     *
     * @return self
     */
    public function setTitleDoc(String $TitleDoc): self
    {
        $this->TitleDoc = $TitleDoc;

        return $this;
    }

    /**
     * Get the value of DireccionSede
     *
     * @return String
     */
    public function getDireccionSede(): String
    {
        return $this->DireccionSede;
    }

    /**
     * Set the value of DireccionSede
     *
     * @param String $DireccionSede
     *
     * @return self
     */
    public function setDireccionSede(String $DireccionSede): self
    {
        $this->DireccionSede = $DireccionSede;

        return $this;
    }
}