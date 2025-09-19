<?php
namespace lib;
use FPDF;
class PdfEvaluacionInforme extends FPDF
{
    private static string $Doctor;
    private static string $TitleHeader;
    
    public static function setDoctor(string $medico){
        self::$Doctor = $medico;
    }

    public static function setTitleHeader(string $title)
    {
        self::$TitleHeader = $title;
    }

    private static function getTitleHeader()
    {
        return self::$TitleHeader;
    }

    private static function getDoctor()
    {
        return self::$Doctor;
    }
    function Header()
    {
       
             /// agregamos un título a la hoja
        $this->Ln(7);
        $this->SetFont("Times","B",14);
        $this->Cell(200,0,utf8__(self::getTitleHeader()),0,1,"C");
        $this->Cell(200,4,utf8__("___________________________________________"),0,1,"C");
        $this->Cell(80);
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
        $this->Cell(200, 5, utf8__("DR.".strtoupper(self::getDoctor())) , 0, 1, 'C');
        $this->Cell(200, 5, utf8__("MEDICINA INTERNA") , 0, 0, 'C');
    }
}