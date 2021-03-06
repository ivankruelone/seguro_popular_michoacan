<?php
require_once('tcpdf/config/lang/spa.php');
require_once('tcpdf/tcpdf.php');
global $header_alt;
$header_alt = $header;

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    

	//Page header
	public function Header() {
	   
       global $header_alt;
        
        $this->SetFont('helvetica', 'B', 7);

$html = <<<EOF
$header_alt
EOF;

        // output the HTML content
        $this->writeHTML($html, true, false, true, false, '');
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(9, 45, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 8);
$pdf->AddPage();
    $tabla = '
    <style>
        table
        {
            font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
        }
        th
        {
            font-weight: normal;
            border-bottom: 2px solid #000000;
        }
        td
        {
            border-bottom: 1px solid #000000;
        }
    </style>
    <table cellpadding="2">
        <thead>
            <tr>
                <th style="width: 12%; ">Clave</th>
                <th style="width: 65%; ">Descripcion</th>
                <th style="text-align: right; width: 10%; ">Piezas</th>
                <th style="text-align: center; width: 20%; ">Lote y cad</th>
            </tr>
        </thead>
        <tbody>';

        $piezas = 0;
        
        foreach($detalle->result() as $row2)
        {
            
            $tabla .= '
                    <tr>
                        <td style="width: 12%; ">'.$row2->cvearticulo.'</td>
                        <td style="width: 65%; ">'.$row2->susa.' '.$row2->descripcion.' '.$row2->pres.'<br />UBICACION: '.$row2->ubicacion.'</td>
                        <td style="text-align: right; width: 10%; ">'.$row2->piezas.'</td>
                        <td style="text-align: center; width: 20%; "></td>
                    </tr>
                    ';

                    $piezas = $piezas + $row2->piezas;

        }
        
        $tabla .= '
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Totales</td>
                <td style="text-align: right; width: 10%; ">'.$piezas.'</td>
                <td></td>
            </tr>
        </tfoot>
    </table>';
    
    
$html = <<<EOF
$tabla
EOF;


        // output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');    



// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('guia_'.$movimientoID.'.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
