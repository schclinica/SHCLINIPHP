<?php 
namespace lib;

use FPDF;
use models\Empresa;

class PlantillaTicket extends FPDF{
// Cabecera de página
use Session;
use QrCode;
private $Recibo;
private float $Total;
private float $SubTotal;
private float $Igv;
function Header()
{
        /// consultamos el logo de la empresa
        $empresa = new Empresa;
        $DataEmpresa = $empresa->query()
            ->limit(1)
            ->first();

        $this->Cell(210, 0, $this->Image(isset($DataEmpresa->logo) ? "public/asset/empresa/" . $DataEmpresa->logo : "public/asset/img/lgo_clinica_default.jpg", 6.5, null, 60, 28, 'PNG'), 0, 1, "C");

        $this->Ln(2);
        $this->SetFont("Courier", "B", 9);
        $this->SetX(0);

        $this->MultiCell(70, 2.9, isset($DataEmpresa->nombre_empresa) ?  "Farmacia - " . utf8__($DataEmpresa->nombre_empresa) : 'xxxxxxxxxxxxxxxxxxxxxxxxxx', 0, "C");

        $this->Ln(3);
        $this->SetFont("Courier", "B", 9);
        $this->SetX(0);
        $this->Cell(74, 2, isset($DataEmpresa->ruc)  ? " RUC: " . $DataEmpresa->ruc : " RUC: xxxxxxxxxxx", 0, 1, "C");

        $this->Ln();
        $this->SetX(0);
        $this->MultiCell(74, 3, self::profile()->direccion_sede != null ? utf8__(self::profile()->name_departamento . " - " . self::profile()->name_provincia . " - " . self::profile()->name_distrito . " - " . self::profile()->direccion_sede) : 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 0, "C");

        $this->Ln(3);
        $this->SetX(0);
        $this->Cell(74, 2, utf8__("Teléfono") . " : " . (isset(self::profile()->telefono_sede) ? self::profile()->telefono_sede : 'xxx xxx xxx'), 0, 1, "C");

        $this->Ln(1);
        $this->SetFont("Courier", "", 8);


        $this->Ln(2);
        $this->SetX(4);
        $this->setFont("Courier", "B", 8);
        $this->Cell(10, 3, utf8__("Fecha de emisión : "), 0, 0, "L");
        $this->SetX(34);
        $this->setFont("Courier", "", 8);
        $this->Cell(10, 3, $this->getRecibo()[0]->fecha_emision, 0, 1);

        $this->Ln(2);
        $this->SetX(3);
        $this->setFont("Courier", "B", 7);
        $this->Cell(4, 3, utf8__("N° de venta : "), 0, 0, "L");
        $this->SetX(22.8);
        $this->setFont("Courier", "", 6.5);
        $this->Cell(10, 3, $this->getRecibo()[0]->num_venta, 0, 1);

        $this->setFont("Courier", "B", 8);
        $this->Ln(3);
        $this->SetX(0);
        $this->MultiCell(74, 2, !is_null($this->getRecibo()[0]->cliente_id)?utf8__($this->getRecibo()[0]->apellidos." ".$this->getRecibo()[0]->nombres):utf8__('PÚBLICO EN GENERAL'),0,"C");
        
        $this->Ln(3);
            $this->SetX(0);
            $this->Cell(74,3,"---------------------------------------",0,1,"C");
        
            /*** GENERANDO EL DETALLE DE LA BOLETA***** */
            $this->Ln(3);
            $this->SetX(3);
            $this->MultiCell(10, 0, "CANT", 0, "C");
            
            $this->SetX(14);
            $this->MultiCell(21, 0, "DESCRIPCION", 0, "C");
    
        
            $this->SetX(34);
            $this->MultiCell(21, 0, "P.UNITARIO", 0, "C");
     
            $this->SetX(54);
            $this->MultiCell(15, 0, "IMPORTE", 0, "C");
        
            $this->Ln(3);
            $this->SetX(0);
            $this->Cell(74,3,"---------------------------------------",0,1,"C");
    }

// Pie de página
function Footer()
{
      $empresa = new Empresa;
        $DataEmpresa1 = $empresa->query()
            ->limit(1)
            ->first();
    // Posición: a 1,5 cm del final
    $this->SetY(-40);
   
         $this->SetFont("Courier","B",7);
   
            self::$DirectorioQr = "public/qr/qrticket.png";
            /// codigo qr de prueba
            self::GenerateQr((isset($DataEmpresa1->ruc)?$DataEmpresa1->ruc:'xxxxxxxxxx')."|".$this->getRecibo()[0]->num_venta."|".number_format($this->getIgv(),2,","," ")."|".number_format($this->getTotal(),2,","," ")."|".$this->getRecibo()[0]->fecha_emision."|".
            utf8__($this->getRecibo()[0]->apellidos." ".$this->getRecibo()[0]->nombres));
        
            $this->SetX(27);
            $this->Cell(74,0,$this->Image(URL_BASE.self::getDirectorioQr(),null,null,18,18),0,1,"C");
            $this->Ln(0);
            $this->setX(5);
            $this->setFont("Courier","B",8);
            $this->Cell(65,3,"Gracias por la preferencia",0,1,"C");
            $this->Cell(57,3,"Vuelva pronto!",0,1,"C");
}


/**
 * Get the value of Recibo
 *
 * @return array
 */
public function getRecibo() 
{
return $this->Recibo;
}

/**
 * Set the value of Recibo
 *
 * @param array $Recibo
 *
 * @return self
 */
public function setRecibo(  $Recibo): self
{
$this->Recibo = $Recibo;

return $this;
}

/**
 * Get the value of SubTotal
 *
 * @return float
 */
public function getSubTotal(): float
{
return $this->SubTotal;
}

/**
 * Set the value of SubTotal
 *
 * @param float $SubTotal
 *
 * @return self
 */
public function setSubTotal(float $SubTotal): self
{
$this->SubTotal = $SubTotal;

return $this;
}

/**
 * Get the value of Igv
 *
 * @return float
 */
public function getIgv(): float
{
return $this->Igv;
}

/**
 * Set the value of Igv
 *
 * @param float $Igv
 *
 * @return self
 */
public function setIgv(float $Igv): self
{
$this->Igv = $Igv;

return $this;
}

/**
 * Get the value of Total
 *
 * @return float
 */
public function getTotal(): float
{
return $this->Total;
}

/**
 * Set the value of Total
 *
 * @param float $Total
 *
 * @return self
 */
public function setTotal(float $Total): self
{
$this->Total = $Total;

return $this;
}
}