<?php

namespace App\Http\Controllers;

use Codedge\Fpdf\Fpdf\Fpdf;

class PDFQuote extends FPDF
{
    var $client;
    var $quote_date;
    var $months = array (1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
    // Tabla coloreada
    function Header()
    {
        // Logo
        $this->Image($_SERVER["DOCUMENT_ROOT"].'/img/logo_quote.png',10,8,33);
        // $this->Image($_SERVER["DOCUMENT_ROOT"].'/public/img/logo.png',10,8,33);
        // Arial bold 15
        $this->SetFont('Arial','',10);
        // Movernos a la derecha
        $this->Cell(30);
        // Título
        $date = explode("-",$this->quote_date);
        $this->Cell(160,6,$date[2]." de ".$this->months[intval($date[1])]." de ".$date[0],0,0,'R');
        // Salto de línea
        $this->Ln(6);
        $this->SetFont('Arial','B',15);
        $this->Cell(190,10,utf8_decode("Cotización"),0,0,'C');
        $this->Ln();
        $this->Cell(190,10,utf8_decode($this->client),0,0,'C');
    }

    function FancyTable()
    {
        $this->SetY(45);
        $this->SetX(10);

        // $this->MultiCell(190,210,"Hidalgo Export desde hace más de 25 años es tu aliado en la compra y logística para hacerte llegar a México lo que requieras para tus proyectos de interiorismo de tiendas de EUA, Canadá y Europa",1,'T','J',false);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(29,6,utf8_decode("Hidalgo Export"),0,'J',false);
        $this->SetY(45);
        $this->SetX(36);
        $this->SetFont('Arial','',10);
        $this->MultiCell(190,6,utf8_decode("desde hace más de 25 años es tu aliado en la compra y logística para hacerte llegar a México lo"),false);
        $this->MultiCell(190,6,utf8_decode("que requieras para tus proyectos de interiorismo de tiendas de EUA, Canadá y Europa."),'J',false);

        $yprev = $this->GetY();
        $yprev += 3;
        $this->SetY($yprev);
        $this->MultiCell(190,6,utf8_decode("Con"),'J',false);
        $this->SetY($yprev);
        $this->SetX(18);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(29,6,utf8_decode("Hidalgo Export"),0,'J',false);
        $this->SetY($yprev);
        $this->SetX(44);
        $this->SetFont('Arial','',10);
        $this->MultiCell(190,6,utf8_decode("tienes los siguientes beneficios:"),'J',false);

        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(18);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(190,6,utf8_decode("-"),'J',false);
        $this->SetY($yprev);
        $this->SetX(23);
        $this->SetFont('Arial','',10);
        $this->MultiCell(177,6,utf8_decode("Gracias a que contamos con todos los permisos, puedes importar cualquier pieza de interiorismo que requieras para tu proyecto (luminarias, telas, electrónica, línea blanca, estufas, muebles, arte, accesorios, etc.)"),false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(18);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(190,6,utf8_decode("-"),'J',false);
        $this->SetY($yprev);
        $this->SetX(23);
        $this->SetFont('Arial','',10);
        $this->MultiCell(177,6,utf8_decode("Nos encargamos de hacer el trámite para obtener las cuentas de trade con las tiendas/mueblerías para que nos den descuentos de diseñador/arquitecto y te trasladamos esos descuentos"),false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(18);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(190,6,utf8_decode("-"),'J',false);
        $this->SetY($yprev);
        $this->SetX(23);
        $this->SetFont('Arial','',10);
        $this->MultiCell(177,6,utf8_decode("Nos encargamos de cotizar con las tiendas las piezas de interiorismo que requieras y verificamos disponibilidad."),false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(18);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(190,6,utf8_decode("-"),'J',false);
        $this->SetY($yprev);
        $this->SetX(23);
        $this->SetFont('Arial','',10);
        $this->MultiCell(177,6,utf8_decode("Si no hay disponibilidad de las piezas que requieres por parte de la tienda, te proponemos otras opciones."),false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(18);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(190,6,utf8_decode("-"),'J',false);
        $this->SetY($yprev);
        $this->SetX(23);
        $this->SetFont('Arial','',10);
        $this->MultiCell(177,6,utf8_decode("Tax exemption (gracias a nuestro SALES TAX PERMIT no pagas el impuesto de EUA)"),false);

        $yprev = $this->GetY();
        $yprev += 3;
        $this->SetY($yprev);
        $this->SetX(10);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(190,6,utf8_decode("PROCESO"),'J',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(18);
        $this->SetFont('Arial','',10);
        $this->MultiCell(190,6,utf8_decode("1"),'J',false);
        $this->SetY($yprev);
        $this->SetX(23);
        $this->MultiCell(177,6,utf8_decode("Realiza tu compra"),false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(18);
        $this->SetFont('Arial','',10);
        $this->MultiCell(190,6,utf8_decode("Hay 2 Opciones:"),'J',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(23);
        $this->SetFont('Arial','U',10);
        $this->MultiCell(190,6,utf8_decode("Opción #1"),'J',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(23);
        $this->SetFont('Arial','',10);
        $this->MultiCell(177,6,utf8_decode(
"Puedes realizar tus compras directamente con la mueblería con tu tarjeta de crédito, de esta manera la orden sale a tu nombre.
Con esta opción, los impuestos se te acreditan en aproximadamente un mes al cabo de un trámite ante notario (manifiesto) con copia de la INE de la persona que aparece en la factura de la mueblería."
        ),'J',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(23);
        $this->SetFont('Arial','U',10);
        $this->MultiCell(190,6,utf8_decode("Opción #2"),'J',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(23);
        $this->SetFont('Arial','',10);
        $this->MultiCell(177,6,utf8_decode(
"Podemos hacer la compra por ti, solo nos tienes que indicar qué piezas deseas comprar, nos transfieres el monto a pagar y nosotros nos encargamos de pagarlo a la mueblería para que nos apliquen el descuento de interiorista y el tax exemption aplique automáticamente.
Con esta opción la orden puede salir a tu nombre o a nombre de Hidalgo Export, en cualquiera de los casos se hace el pago a la mueblería por medio de una transferencia bancaria."
        ),'J',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(18);
        $this->SetFont('Arial','',10);
        $this->MultiCell(190,6,utf8_decode("2"),'J',false);
        $this->SetY($yprev);
        $this->SetX(23);
        $this->MultiCell(177,6,utf8_decode("Una vez realizado el pago, la mueblería nos manda las piezas a nuestra bodega en Hidalgo Texas (404 E. Brazil Ave. Hidalgo, TX 78557)."),false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(18);
        $this->SetFont('Arial','',10);
        $this->MultiCell(190,6,utf8_decode("3"),'J',false);
        $this->SetY($yprev);
        $this->SetX(23);
        $this->MultiCell(177,6,utf8_decode("Una vez que nos llegan las piezas, las examinamos para asegurarnos de que hayan llegado en buen estado, de no ser así hacemos la devolución a la mueblería."),false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(18);
        $this->SetFont('Arial','',10);
        $this->MultiCell(190,6,utf8_decode("4"),'J',false);
        $this->SetY($yprev);
        $this->SetX(23);
        $this->MultiCell(177,6,utf8_decode("Enviamos tu pedido a la dirección de México donde nos indiques (te lo llevamos a cualquier parte del país)."),false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(18);
        $this->SetFont('Arial','',10);
        $this->MultiCell(190,6,utf8_decode("5"),'J',false);
        $this->SetY($yprev);
        $this->SetX(23);
        $this->MultiCell(177,6,utf8_decode("Al llegar, volvemos a desempacar las piezas para cerciorarnos de que hayan llegado en buen estado. Así mismo nos encargamos de llevarnos y desechar el material de empaque."),false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(18);
        $this->SetFont('Arial','',10);
        $this->MultiCell(190,6,utf8_decode("6"),'J',false);
        $this->SetY($yprev);
        $this->SetX(23);
        $this->MultiCell(177,6,utf8_decode("Realiza el pago de honorarios de Hidalgo Export por medio de transferencia 3 días antes de la entrega en México (puedes hacerlo a nuestra cuenta de México o de EUA)."),false);
    }

    function Charge($total, $broker, $pay, $iva, $payt, $destiny, $tax, $discount, $percent, $paytotalMxn)
    {
        $this->AddPage('P','Letter');
        $this->SetY(45);
        $this->SetX(10);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(20,6,utf8_decode("Destino"),1,'C',false);
        $this->SetY(45);
        $this->SetX(30);
        $this->SetFont('Arial','',10);
        $this->MultiCell(170,6,utf8_decode($destiny),1,'C',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(10);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(20,6,utf8_decode("¿Que"),"RL",'C',false);
        $this->SetY($yprev + 6);
        $this->SetX(10);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(20,6,utf8_decode("sigue?"),"RL",'C',false);
        $this->SetY($yprev + 12);
        $this->SetX(10);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(20,48,utf8_decode(""),"RLB",'C',false);
        $this->SetY($yprev);
        $this->SetX(30);
        $this->SetFont('Arial','',10);
        $this->MultiCell(20,6,utf8_decode("1."),0,'C',false);
        $this->SetY($yprev);
        $this->SetX(50);
        $this->MultiCell(150,6,utf8_decode("Indicar al proveedor que mande la mercancía a nuestra bodega:"),'R','L',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(50);
        $this->MultiCell(150,6,utf8_decode("Hidalgo Export\n404 E. Brazil Ave.\nHidalgo, TX 78557\nTEL +1 9568003778"),'R','C',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(30);
        $this->SetFont('Arial','',10);
        $this->MultiCell(20,6,utf8_decode("2."),0,'C',false);
        $this->SetY($yprev);
        $this->SetX(50);
        $this->MultiCell(150,6,utf8_decode("Recibimos su mercancía y le notificamos que haya llegado en buen estado."),'R','L',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(30);
        $this->SetFont('Arial','',10);
        $this->MultiCell(20,6,utf8_decode("3."),0,'C',false);
        $this->SetY($yprev);
        $this->SetX(50);
        $this->MultiCell(150,6,utf8_decode("Enviamos su mercancía al destino en aprox 15 días."),'R','L',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(30);
        $this->SetFont('Arial','',10);
        $this->MultiCell(20,6,utf8_decode("4."),0,'C',false);
        $this->SetY($yprev);
        $this->SetX(50);
        $this->MultiCell(150,6,utf8_decode("Tres días antes de la entrega en el destino, se pagan los honorarios de Hidalgo."),'R','L',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(30);
        $this->SetFont('Arial','',10);
        $this->MultiCell(20,6,utf8_decode("5."),0,'C',false);
        $this->SetY($yprev + 6);
        $this->SetX(30);
        $this->SetFont('Arial','',10);
        $this->MultiCell(20,6,utf8_decode(""),'B','C',false);
        $this->SetY($yprev);
        $this->SetX(50);
        $this->MultiCell(150,6,utf8_decode("Entregamos en el domicilio señalado, le acomodamos su mercancía y nos llevamos el material de empaque a desechar."),'RB','L',false);

        $yprev = $this->GetY() + 6;
        $this->SetY($yprev);
        $this->SetX(10);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(190,6,utf8_decode("DESGLOSE DE COBRO CON FACTURA DE LA MERCANCÍA Y LOS HONORARIOS DE HIDALGO EXPORT:"),0,'L',false);

        $yprev = $this->GetY() + 6;
        $this->SetY($yprev);
        $this->SetX(45);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(90,6,utf8_decode("COSTO DE LA MERCANCIA"),1,'L',false);
        $this->SetY($yprev);
        $this->SetX(135);
        $this->SetFont('Arial','',10);
        $this->MultiCell(30,6,"$".number_format(floatval(preg_replace('/[^\d\.]+/', '', $total)), 2, ".", ","),1,'R',false);
        if($discount != 0)
        {
            $yprev = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(45);
            $this->SetFont('Arial','B',10);
            $this->MultiCell(90,6,utf8_decode("DESCUENTO DE TIENDA"),1,'L',false);
            $this->SetY($yprev);
            $this->SetX(135);
            $this->SetFont('Arial','',10);
            $this->MultiCell(30,6,"$".number_format(floatval(preg_replace('/[^\d\.]+/', '', $discount)), 2, ".", ","),1,'R',false);
        }
        if($broker != 0)
        {
            $yprev = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(45);
            $this->SetFont('Arial','B',10);
            $this->MultiCell(90,6,utf8_decode("SHIPPING"),1,'L',false);
            $this->SetY($yprev);
            $this->SetX(135);
            $this->SetFont('Arial','',10);
            $this->MultiCell(30,6,"$".number_format(floatval(preg_replace('/[^\d\.]+/', '', $broker)), 2, ".", ","),1,'R',false);
        }
        if($tax != 0)
        {
            $yprev = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(45);
            $this->SetFont('Arial','B',10);
            $this->MultiCell(90,6,utf8_decode("TAX"),1,'L',false);
            $this->SetY($yprev);
            $this->SetX(135);
            $this->SetFont('Arial','',10);
            $this->MultiCell(30,6,"$".number_format(floatval(preg_replace('/[^\d\.]+/', '', $tax)), 2, ".", ","),1,'R',false);
        }
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(45);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(90,6,$percent.utf8_decode("% DE HONORARIOS HIDALGO EXPORT"),1,'L',false);
        $this->SetY($yprev);
        $this->SetX(135);
        $this->SetFont('Arial','',10);
        $this->MultiCell(30,6,"$".number_format(floatval(preg_replace('/[^\d\.]+/', '', $pay)), 2, ".", ","),1,'R',false);
        if($iva != 0)
        {
            $yprev = $this->GetY();
            $this->SetY($yprev);
            $this->SetX(45);
            $this->SetFont('Arial','B',10);
            $this->MultiCell(90,6,utf8_decode("IVA 16%"),1,'L',false);
            $this->SetY($yprev);
            $this->SetX(135);
            $this->SetFont('Arial','',10);
            $this->MultiCell(30,6,"$".number_format(floatval(preg_replace('/[^\d\.]+/', '', $iva)), 2, ".", ","),1,'R',false);
        }
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(45);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(90,6,utf8_decode("TOTAL EN USD"),1,'L',false);
        $this->SetY($yprev);
        $this->SetX(135);
        $this->SetFont('Arial','',10);
        $this->MultiCell(30,6,"$".number_format(floatval(preg_replace('/[^\d\.]+/', '', $payt)), 2, ".", ","),1,'R',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(45);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(90,6,utf8_decode("TOTAL EN MXN"),1,'L',false);
        $this->SetY($yprev);
        $this->SetX(135);
        $this->SetFont('Arial','',10);
        $this->MultiCell(30,6,"$".number_format(floatval(preg_replace('/[^\d\.]+/', '', $paytotalMxn)), 2, ".", ","),1,'R',false);

        $yprev = $this->GetY() + 6;
        $this->SetY($yprev);
        $this->SetX(10);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(150,6,utf8_decode("Los honorarios de Hidalgo Export incluyen:"),0,'L',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(20);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(10,6,utf8_decode("=>"),0,'C',false);
        $this->SetY($yprev);
        $this->SetX(30);
        $this->SetFont('Arial','',10);
        $this->MultiCell(150,6,utf8_decode("Revisión de mercancía."),0,'L',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(20);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(10,6,utf8_decode("=>"),0,'C',false);
        $this->SetY($yprev);
        $this->SetX(30);
        $this->SetFont('Arial','',10);
        $this->MultiCell(150,6,utf8_decode("Pago de impuestos en aduana de México"),0,'L',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(20);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(10,6,utf8_decode("=>"),0,'C',false);
        $this->SetY($yprev);
        $this->SetX(30);
        $this->SetFont('Arial','',10);
        $this->MultiCell(150,6,utf8_decode("Gastos de agencia aduanal"),0,'L',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(20);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(10,6,utf8_decode("=>"),0,'C',false);
        $this->SetY($yprev);
        $this->SetX(30);
        $this->SetFont('Arial','',10);
        $this->MultiCell(150,6,utf8_decode("Flete desde Hidalgo Texas a CDMX"),0,'L',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(20);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(10,6,utf8_decode("=>"),0,'C',false);
        $this->SetY($yprev);
        $this->SetX(30);
        $this->SetFont('Arial','',10);
        $this->MultiCell(150,6,utf8_decode("Seguro"),0,'L',false);
        $yprev = $this->GetY();
        $this->SetY($yprev);
        $this->SetX(20);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(10,6,utf8_decode("=>"),0,'C',false);
        $this->SetY($yprev);
        $this->SetX(30);
        $this->SetFont('Arial','',10);
        $this->MultiCell(150,6,utf8_decode("Acomodo de muebles y desecho de material de empaque"),0,'L',false);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    function PrintPDF($client,$destiny,$quote_date,$total, $broker, $pay, $iva, $payt, $tax, $discount, $percent, $paytotalMxn)
    {
        $this->client = $client;
        $this->quote_date = $quote_date;
        $this->AliasNbPages();
        $this->AddPage('P','Letter');
        $this->SetFont('Times','',12);
        $this->FancyTable();
        $disc = $total * $discount/100;
        $this->Charge($total, $broker, $pay, $iva, $payt, $destiny, $tax, $disc, $percent, $paytotalMxn);
    }
}
?>
