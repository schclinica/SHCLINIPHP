<?php 
namespace lib;

use FPDF;

class HeaderHistorial extends FPDF{
use Session;
function Header()
{

    // Logo
   /// empresa
   $empresa = $this->BusinesData();
   /// nombre de la empresa
   $this->SetY(10);
   $this->SetX(5);
   $this->setFont("Helvetica","B",13);
   $this->Cell(100,2,utf8__($empresa[0]->nombre_empresa),0,1,"L");
   
   $this->Ln(3);
   $this->SetX(5);
   $this->setFont("Helvetica","",10);
   $this->Cell(100,2,self::profile()->direccion_sede != null ? utf8__(self::profile()->name_departamento." - ".self::profile()->name_provincia." - ".self::profile()->name_distrito." - ".self::profile()->direccion_sede) : 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',0,1,"L");

   $this->Ln(3);
   $this->SetX(5);
   $this->Cell(8,0.5,utf8__("Telf:"),0,0,"L");

   $this->Cell(50,0.5,(self::profile()->telefono_sede!= null ? utf8__(self::profile()->telefono_sede):'XXX XXX XXX'),0,1,"L");

   $this->Ln(3);
   $this->SetX(5);
   $this->Cell(10,0.5,utf8__("Email:"),0,0,"L");

   $this->Cell(50,0.5,(self::profile()->correo_sede!= null ? utf8__(self::profile()->correo_sede):'XXXXXXXXXXXXXXXXXXXXXX'),0,0,"L");
   // Logo
   
   $this->Image(URL_BASE."public/asset/empresa/".$empresa[0]->logo,128,6,75,19,"jpg");
    // Salto de línea
    $this->Ln(14);
}


// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    
    $this->Cell(0,10,$this->PageNo(),0,0,'C');
}

function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));

        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        if (strpos($corners, '2')===false)
            $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k,($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        if (strpos($corners, '3')===false)
            $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        if (strpos($corners, '4')===false)
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        if (strpos($corners, '1')===false)
        {
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k ));
            $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-$y)*$k ));
        }
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }

}