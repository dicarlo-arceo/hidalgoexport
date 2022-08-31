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
        $this->MultiCell(30,10,"Tienda",1,'C',true);
        $this->SetY(10);
        $this->SetX(40);
        $this->MultiCell(30,10,"# de Item",1,'C',true);
        $this->SetY(10);
        $this->SetX(70);
        $this->MultiCell(100,10,utf8_decode("Descripción"),1,'C',true);
        $yprev = $this->GetY();
        $this->SetY(10);
        $this->SetX(170);
        $this->MultiCell(10,10,"BO",1,'C',true);
        $this->SetY(10);
        $this->SetX(180);
        $this->MultiCell(10,10,"Exist",1,'C',true);
        $this->SetY(10);
        $this->SetX(190);
        $this->MultiCell(10,10,"TR",1,'C',true);
        $this->SetY(10);
        $this->SetX(200);
        $this->MultiCell(20,10,"Estatus",1,'C',true);
        $this->SetY(10);
        $this->SetX(220);
        $this->MultiCell(30,10,"$ Net",1,'C',true);
        $this->SetY(10);
        $this->SetX(250);
        $this->MultiCell(30,10,"$ Total",1,'C',true);
        $this->Ln();
        $this->SetFont('Arial','',8);

        foreach($data as $row)
        {
            // dd($row->description);
            if($ycurr > 180)
            {
                $this->AddPage('L');
                $yprev = 10;
            }
            $this->SetY($yprev);
            $this->MultiCell(30,6,utf8_decode($row->store),"T",'C',false);
            $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(40);
            $this->MultiCell(30,6,utf8_decode($row->item_number),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(70);
            $this->MultiCell(100,6,utf8_decode($row->description),"T",'L',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(170);
            $this->MultiCell(10,6,utf8_decode($row->back_order),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(180);
            $this->MultiCell(10,6,utf8_decode($row->existence),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(190);
            $this->MultiCell(10,6,utf8_decode($row->tr),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(200);
            $this->MultiCell(20,6,utf8_decode($row->name),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(220);
            $this->MultiCell(30,6,"$" . number_format(floatval(preg_replace('/[^\d\.]+/', '', $row->net_price)), 2, ".", ","),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(250);
            $this->MultiCell(30,6,"$" . number_format(floatval(preg_replace('/[^\d\.]+/', '', $row->total_price)), 2, ".", ","),"T",'C',false);
            if($ycurr < $this->GetY()) $ycurr = $this->GetY();
            $yprev = $ycurr;
            $this->Ln();
        }
        // Restauración de colores y fuentes
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('Arial','',13);
    }
    function AddPayment($ord,$cellar,$comition,$mxn_total,$iva,$mxn_invoice,$usd_total,$broker,$expenses)
    {
        $this->SetMargins(10, 20, 10);
        $this->AddPage();
        $this->SetFont('Arial','B',22);
        $this->Cell(180,10,"Detalles de Pago",0,0,'C');
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(90,10,'TIPO DE CAMBIO',1,0,'C',true);
        $this->Cell(90,10,'PORCENTAJE',1,0,'C',true);
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(90,10,$ord->exc_rate,1,0,'C');
        $this->Cell(90,10,$ord->percentage,1,0,'C');
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(90,10,'CONCEPTO',1,0,'C',true);
        $this->Cell(90,10,'TOTAL',1,0,'C',true);
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(90,10,"BODEGA","LR",0,'L');
        $this->Cell(90,10,$cellar,"LR",0,'L');
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(90,10,utf8_decode("IMPORTACIÓN"),"LR",0,'L');
        $this->Cell(90,10,$comition,"LR",0,'L');
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(90,10,utf8_decode("BROKER"),"LR",0,'L');
        $this->Cell(90,10,"$".number_format(floatval(preg_replace('/[^\d\.]+/', '', $broker)), 2, ".", ","),"LR",0,'L');
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(90,10,utf8_decode("PAQUETERÍA"),"LR",0,'L');
        $this->Cell(90,10,"$".number_format(floatval(preg_replace('/[^\d\.]+/', '', $expenses)), 2, ".", ","),"LR",0,'L');
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(90,10,"TOTAL A PAGAR","LR",0,'L');
        $this->Cell(90,10,$usd_total,"LR",0,'L');
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(90,10,"TOTAL EN PESOS","LR",0,'L');
        $this->Cell(90,10,$mxn_total,"LR",0,'L');
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(90,10,"IVA","LR",0,'L');
        $this->Cell(90,10,$iva,"LR",0,'L');
        $this->Ln();
        $this->SetFont('Arial','',13);
        $this->Cell(90,10,"PRECIO A FACTURAR","LRB",0,'L');
        $this->Cell(90,10,$mxn_invoice,"LRB",0,'L');
    }
    function PrintPDF($data,$ord,$cellar,$comition,$mxn_total,$iva,$mxn_invoice,$usd_total,$broker,$expenses)
    {
        // dd($data);

        // $this->SetMargins(0, 0, 0);
        $this->SetAutoPageBreak(false);
        $this->AddPage('L');
        $this->FancyTable($data);
        // dd($ord);
        if($cellar != "1" && $comition != "1")
            $this->AddPayment($ord,$cellar,$comition,$mxn_total,$iva,$mxn_invoice,$usd_total,$broker,$expenses);
    }
}
?>
