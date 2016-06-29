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

foreach($detalle->result() as $det)
{
// add a page


$html = <<<EOF
<h1 style="text-align: center; ">AREA: $det->area</h1>
EOF;

        // output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

if($det->areaID == null)
{
    $_areaID = 'null';
}else
{
    $_areaID = $det->areaID;
}


$sql = "SELECT pasilloID, ifnull(pasillo, 'SIN INVENTARIO') as pasillo, pasilloTipo FROM movimiento_prepedido m
join articulos a using(id)
left join inventario i on m.id = i.id  and i.clvsucursal = ?
left join ubicacion u on u.ubicacion = i.ubicacion and areaID = $_areaID and pasilloTipo <> 2
where m.movimientoID = ?
group by pasilloID;";

$query = $this->db->query($sql, array($this->session->userdata('clvsucursal'), $movimientoID));

foreach($query->result() as $row)
{

$html = <<<EOF
<h2 style="text-align: center; ">PASILLO: $row->pasillo</h2>
EOF;

        // output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

if($det->areaID == null && $row->pasilloID == null)
{
    $sql2 = "SELECT * FROM movimiento_prepedido m
    join articulos a using(id)
    left join inventario i on m.id = i.id  and clvsucursal = ?
    left join ubicacion u on u.ubicacion = i.ubicacion and i.clvsucursal = u.clvsucursal
    where m.movimientoID = ? and u.areaID is null and pasilloID is null
    group BY m.movimientoPrepedido;";

    $query2 = $this->db->query($sql2, array($this->session->userdata('clvsucursal'), $movimientoID));
}else
{
    if($row->pasilloTipo == 3)
    {
        $sql2 = "SELECT * FROM movimiento_prepedido m
    join articulos a using(id)
    left join inventario i using(id)
    left join ubicacion u using(ubicacion)
    where m.movimientoID = ? and areaID = ? and pasilloID = ? and i.clvsucursal = ? and m.id not in (SELECT a.id FROM movimiento_prepedido m
    join articulos a using(id)
    left join inventario i using(id)
    left join ubicacion u using(ubicacion)
    where m.movimientoID = ? and pasilloTipo in(1, 2) and i.clvsucursal = ?
    group BY m.movimientoPrepedido)
    group BY m.movimientoPrepedido;";
        $query2 = $this->db->query($sql2, array($movimientoID, $det->areaID, $row->pasilloID, $this->session->userdata('clvsucursal'), $movimientoID, $this->session->userdata('clvsucursal')));
    }else
    {
        $sql2 = "SELECT * FROM movimiento_prepedido m
        join articulos a using(id)
        left join inventario i using(id)
        left join ubicacion u using(ubicacion)
        where m.movimientoID = ? and areaID = ? and pasilloID = ? and i.clvsucursal = ?
        group BY m.movimientoPrepedido;";
        $query2 = $this->db->query($sql2, array($movimientoID, $det->areaID, $row->pasilloID, $this->session->userdata('clvsucursal')));
    }
}





if($query2->num_rows() > 0)
{
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
                <th style="width: 20%; ">Sustancia</th>
                <th style="width: 30%; ">Descripcion</th>
                <th style="width: 25%; ">Presentacion</th>
                <th style="text-align: right; width: 10%; ">Piezas</th>
                <th style="text-align: center; width: 10%; ">Ubicacion</th>
            </tr>
        </thead>
        <tbody>';

        $piezas = 0;
        
        foreach($query2->result() as $row2)
        {
            
            $tabla .= '
                    <tr>
                        <td style="width: 12%; " rowspan="2">'.$row2->cvearticulo.'</td>
                        <td style="width: 20%; " rowspan="2">'.$row2->susa.'</td>
                        <td style="width: 30%; " rowspan="2">'.$row2->descripcion.'</td>
                        <td style="width: 25%; " rowspan="2">'.$row2->pres.'</td>
                        <td style="text-align: right; width: 10%; ">'.$row2->piezas.'</td>
                        <td style="text-align: center; width: 10%; ">'.$row2->pasilloID.'-'.$row2->moduloID.'-'.$row2->nivelID.'-'.$row2->posicionID.'</td>
                    </tr>
                    <tr>
                        <td colspan="2"><br /><br /><br /></td>
                    </tr>';

                    $piezas = $piezas + $row2->piezas;

        }
        
        $tabla .= '
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Totales</td>
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
}
    
}

   
}



// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('guia_'.$movimientoID.'.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
