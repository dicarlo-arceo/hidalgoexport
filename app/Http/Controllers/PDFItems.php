<?php

namespace App\Http\Controllers;

use Codedge\Fpdf\Fpdf\Fpdf;

class PDFItems extends FPDF
{
    // Tabla coloreada
    function FancyTable($data)
    {
        $ycurr = 0;
        $yprev = 0;
        // Colores, ancho de línea y fuente en negrita
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(.3);
        $this->SetFont('Arial','',10);
        // Cabecera
        $this->SetY(10);
        $this->MultiCell(20,10,"Tienda",1,'C',true);
        $this->SetY(10);
        $this->SetX(30);
        $this->MultiCell(20,10,"# de Item",1,'C',true);
        $this->SetY(10);
        $this->SetX(50);
        $this->MultiCell(50,10,utf8_decode("Descripción"),1,'C',true);
        $yprev = $this->GetY();
        $this->SetY(10);
        $this->SetX(100);
        $this->MultiCell(10,10,"BO",1,'C',true);
        $this->SetY(10);
        $this->SetX(110);
        $this->MultiCell(10,10,"Exist",1,'C',true);
        $this->SetY(10);
        $this->SetX(120);
        $this->MultiCell(10,10,"TR",1,'C',true);
        $this->SetY(10);
        $this->SetX(130);
        $this->MultiCell(20,10,"Estatus",1,'C',true);
        $this->SetY(10);
        $this->SetX(150);
        $this->MultiCell(20,10,"$ Net",1,'C',true);
        $this->SetY(10);
        $this->SetX(170);
        $this->MultiCell(20,10,"$ Total",1,'C',true);
        $this->Ln();
        $this->SetFont('Arial','',8);

        foreach($data as $row)
        {
            // dd($row->description);
            if($ycurr > 260)
            {
                $this->AddPage();
                $yprev = 10;
            }
            $this->SetY($yprev);
            $this->MultiCell(20,10,utf8_decode($row->store),"T",'C',false);
            $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(30);
            $this->MultiCell(20,10,utf8_decode($row->item_number),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(50);
            $this->MultiCell(50,10,utf8_decode($row->description),"T",'L',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(100);
            $this->MultiCell(10,10,utf8_decode($row->back_order),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(110);
            $this->MultiCell(10,10,utf8_decode($row->existence),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(120);
            $this->MultiCell(10,10,utf8_decode($row->tr),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(130);
            $this->MultiCell(20,10,utf8_decode($row->name),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(150);
            $this->MultiCell(20,10,"$" . number_format(floatval(preg_replace('/[^\d\.]+/', '', $row->net_price)), 2, ".", ","),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(170);
            $this->MultiCell(20,10,"$" . number_format(floatval(preg_replace('/[^\d\.]+/', '', $row->total_price)), 2, ".", ","),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $yprev = $ycurr;
            $this->Ln();
        }
        // Restauración de colores y fuentes
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('Arial','',13);
        // Datos
        // $fill = false;
        // foreach($data as $row)
        // {
        //     $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        //     $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        //     $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
        //     $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
        //     $this->Ln();
        //     $fill = !$fill;
        // }
        // // Línea de cierre
        // $this->Cell(array_sum($w),0,'','T');
    }
    function PrintPDF($data)
    {
        // dd($data);

        // $this->SetMargins(0, 0, 0);
        $this->SetAutoPageBreak(false);
        $this->AddPage();
        $this->FancyTable($data);
    }
}
?>
