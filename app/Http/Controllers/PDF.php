<?php

namespace App\Http\Controllers;

use Codedge\Fpdf\Fpdf\Fpdf;


class PDF extends FPDF
{
    function Header()
    {

    }

    function Footer()
    {
        // Posición a 1,5 cm del final
        $this->SetY(-15);
        // Arial itálica 8
        $this->SetFont('Arial','I',8);
        // Color del texto en gris
        $this->SetTextColor(128);
        // Número de página
    }

    function ChapterTitle($num, $label)
    {
    }

    function Payment($order,$cellar,$comition,$dlls,$date,$pkgs)
    {
        // dd("entre a pay");
        $this->SetFont('Arial','',13);
        $this->Cell(180,10,$date,0,0,'R');
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(50,10,'SR(A):',0,0);
        $this->SetFont('Arial','',13);
        $this->Cell(0,10,utf8_decode($order->projectName),0,0);//Aqui nombre
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(50,10,'DIRECCION:',0,0);
        $this->SetFont('Arial','',13);
        $this->MultiCell(120,10,utf8_decode($order->address),0,'L');//Aqui direccion
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(50,10,'TELEFONO:',0,0);
        $this->SetFont('Arial','',13);
        $this->MultiCell(120,10,$order->cellphone,0,'L');//Aqui direccion
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(70,10,'',0,0);
        $this->Cell(50,10,'DLLS',0,0,'C');
        $this->Cell(50,10,'PESOS',0,0,'C');
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(70,10,'TOTAL DE MERCANCIA',0,0);
        $this->SetFont('Arial','',13);
        $this->Cell(50,10,$cellar,0,0,'C');
        $this->Cell(50,10,"$" . number_format((floatval(preg_replace('/[^\d\.]+/', '', $cellar)) * floatval($dlls)), 2, ".", ","),0,0,'C');
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(70,10,'% DE IMPORTACION',0,0);
        $this->SetFont('Arial','',13);
        $this->Cell(50,10,$comition,0,0,'C');
        $this->Cell(50,10,"$" . number_format((floatval(preg_replace('/[^\d\.]+/', '', $comition)) * floatval($dlls)), 2, ".", ","),0,0,'C');
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(70,10,'MANIFIESTO',0,0);
        $this->Cell(50,10,'-',0,0,'C');
        $this->Cell(50,10,'-',0,0,'C');
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(70,10,'TOTAL $',0,0);
        $this->SetFont('Arial','',13);
        $this->Cell(50,10,$comition,0,0,'C');
        $this->Cell(50,10,"$" . number_format((floatval(preg_replace('/[^\d\.]+/', '', $comition)) * floatval($dlls)), 2, ".", ","),0,0,'C');
        $this->Ln();
        $this->Cell(70,10,'TIPO DE CAMBIO $',0,0);
        $this->SetFont('Arial','',13);
        $this->Cell(50,10,'1',0,0,'C');
        $this->Cell(50,10,$dlls,0,0,'C');
        $this->Ln();
        $this->SetFont('Arial','',9);
        $this->Ln();
        $this->MultiCell(150,5,utf8_decode('NOTA:En caso de querer manifiesto, favor de agregar copia de identificación oficial (IFE, Visa Laser, Pasaporte) sin este documento no es posible elaborar manifiesto, el cual tiene una vigencia de 1 mes Hidalgo Export no se hace responsable en el caso de que se pierdan las taxas.'),0,'L');
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(50,8,'ACEPTO MANIFIESTO',0,0);
        $this->Cell(120,5,'     (SI)                (NO)',"B",'C');
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(50,8,'Firma de conformidad',0,0);
        $this->Cell(120,5,'',"B",'C');
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(50,11,'RECIBI LA MERCANCIA EN BUEN ESTADO Y COMPLETA',0,0);
        $this->Cell(120,5,'',"",'C');
        $this->Ln();
        $this->Cell(30,12,'ENTREGO:',0,0);
        $this->Cell(30,8,'',"B",'C');
        $this->Cell(60,5,'',"",'C');
        $this->Cell(40,12,'TOTAL BULTOS:',"",'C');
        $this->Cell(40,12,'2',"",'C');
        $this->Ln();
        $this->Cell(60,8,'EMAIL:',0,0,"C");
        $this->Cell(80,6,'',"B",'C');
    }
    function NoPayment($order,$date,$pkgs)
    {
        // dd("entre a no pay");
        $this->SetFont('Arial','',13);
        $this->Cell(180,10,$date,0,0,'R');
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(50,10,'SR(A):',0,0);
        $this->SetFont('Arial','',13);
        $this->Cell(0,10,utf8_decode($order->projectName),0,0);//Aqui nombre
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(50,10,'DIRECCION:',0,0);
        $this->SetFont('Arial','',13);
        $this->MultiCell(120,10,utf8_decode($order->address),0,'L');//Aqui direccion
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(50,10,'TELEFONO:',0,0);
        $this->SetFont('Arial','',13);
        $this->MultiCell(120,10,$order->cellphone,0,'L');//Aqui direccion
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(70,10,'',0,0);
        $this->Cell(50,10,'DLLS',0,0,'C');
        $this->Cell(50,10,'PESOS',0,0,'C');
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(70,10,'TOTAL DE MERCANCIA',0,0);
        $this->Cell(50,10,'SOLO',0,0,'C');
        $this->Cell(50,10,'',0,0,'C');
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(70,10,'% DE IMPORTACION',0,0);
        $this->Cell(50,10,'ENTREGAR',0,0,'C');
        $this->Cell(50,10,'',0,0,'C');
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(70,10,'MANIFIESTO',0,0);
        $this->Cell(50,10,'',0,0,'C');
        $this->Cell(50,10,'',0,0,'C');
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(70,10,'TOTAL $',0,0);
        $this->Cell(50,10,'',0,0,'C');
        $this->Cell(50,10,'',0,0,'C');
        $this->Ln();
        $this->Cell(70,10,'TIPO DE CAMBIO $',0,0);
        $this->Cell(50,10,'',0,0,'C');
        $this->Cell(50,10,'',0,0,'C');
        $this->Ln();
        $this->SetFont('Arial','',9);
        $this->Ln();
        $this->MultiCell(150,5,utf8_decode('NOTA:En caso de querer manifiesto, favor de agregar copia de identificación oficial (IFE, Visa Laser, Pasaporte) sin este documento no es posible elaborar manifiesto, el cual tiene una vigencia de 1 mes Hidalgo Export no se hace responsable en el caso de que se pierdan las taxas.'),0,'L');
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(50,8,'ACEPTO MANIFIESTO',0,0);
        $this->Cell(120,5,'     (SI)                (NO)',"B",'C');
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(50,8,'Firma de conformidad',0,0);
        $this->Cell(120,5,'',"B",'C');
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(50,11,'RECIBI LA MERCANCIA EN BUEN ESTADO Y COMPLETA',0,0);
        $this->Cell(120,5,'',"",'C');
        $this->Ln();
        $this->Cell(30,12,'ENTREGO:',0,0);
        $this->Cell(30,8,'',"B",'C');
        $this->Cell(60,5,'',"",'C');
        $this->Cell(40,12,'TOTAL BULTOS:',"",'C');
        $this->Cell(40,12,'2',"",'C');
        $this->Ln();
        $this->Cell(60,8,'EMAIL:',0,0,"C");
        $this->Cell(80,6,'',"B",'C');
    }

    function PrintChapter($order,$cellar,$comition,$dlls,$date,$pkgs)
    {
        // dd($order);
        setlocale(LC_ALL,"es_ES");
        $this->SetMargins(20, 40, 2);
        $this->AddPage();

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        list($year, $month, $day) = preg_split('[-]', $date);
        $auxDate = $day." ".$meses[intval($month)-1]." ".$year;
        // dd($cellar,$comition);
        if($cellar == "1" && $comition == "1")
            $this->NoPayment($order,$auxDate,$pkgs);
        else
            $this->Payment($order,$cellar,$comition,$dlls,$auxDate,$pkgs);
    }
}

?>
