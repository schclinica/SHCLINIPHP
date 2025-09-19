<?php
namespace lib;
use FPDF;
class Pdf extends FPDF
{

    function Header()
    {
        $this->SetX(6);
        $this->Cell(130,50,$this->Image("public/asset/img/essalud.jpg",25,17,69,18));
        $this->Cell(80);
        $this->Ln(35);
    }
    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'B', 10);

        // Número de página
        $this->Cell(0, 10, $this->PageNo() , 0, 0, 'C');
    }
}