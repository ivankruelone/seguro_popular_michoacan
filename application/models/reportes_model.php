<?php
class Reportes_model extends CI_Model {

    /**
     * Catalogos_model::__construct()
     * 
     * @return
     */

    var $clvpuesto = null;

    function __construct()
    {
        parent::__construct();
        $this->clvpuesto = $this->session->userdata('clvpuesto');
    }
    
    function recetas_periodo_detalle($fecha1, $fecha2, $idprograma, $tiporequerimiento)
    {
        
        
        $this->db->select("descsucursal, programa, requerimiento, folioreceta, apaterno, amaterno, nombre, canreq, cvepaciente, cveservicio, cvearticulo, susa, descripcion, pres, cansur, nombremedico, cvemedico, fecha, fechaexp", false);
        $this->db->from('receta r');
        $this->db->join('receta_detalle d', 'r.consecutivo = d.consecutivo');
        $this->db->join('sucursales s', 'r.clvsucursal=s.clvsucursal');
        $this->db->join('articulos a', 'a.id = d.id');
        $this->db->join('programa p', 'r.idprograma = p.idprograma');
        $this->db->join('temporal_requerimiento q', 'r.tiporequerimiento = q.tiporequerimiento');
        $this->db->where('fecha >=', $fecha1);
        $this->db->where('fecha <=', $fecha2);
        $this->db->where('r.clvsucursal', $this->session->userdata('clvsucursal'));
        
        if($idprograma == 1000)
        {
        
            
        }else{
            $this->db->where('r.idprograma', $idprograma);            
        }
        
        if($tiporequerimiento == 1000)
        {
            
        }else{
            $this->db->where('r.tiporequerimiento', $tiporequerimiento);
        }
        
        $this->db->order_by('r.fecha, r.folioreceta * 1');
        
        $query = $this->db->get();
        
        return $query;
        
    }
    
    function recetas_periodo_detalle_anterior($fecha1, $fecha2, $clvsucursal)
    {
        
        $this->db->select("descsucursal, folioreceta, apaterno, amaterno, nombre, cvepaciente, cveservicio, r.cvearticulo, r.descripcion, costounitario, a.iva, presentacion, cantidadsurtida, nombremedico, cvemedico, fecha, fechaexp", false);
        $this->db->from('receta r');
        $this->db->join('sucursales s', 'r.cvecentrosalud=s.clvsucursal', 'LEFT');
        $this->db->join('articulos a', 'a.cvearticulo=r.cvearticulo', 'LEFT');
        $this->db->where('fecha >=', $fecha1);
        $this->db->where('fecha <=', $fecha2);
        $this->db->where('cvecentrosalud', $clvsucursal);
        $this->db->where('r.status', 't');
        $query = $this->db->get();
        
        return $query;
        
    }

    function getSucursalesCombo()
    {
        $query = $this->db->get('sucursales');
        
        $a = array();
        
        foreach($query->result() as $row)
        {
            $a[$row->clvsucursal] = $row->descsucursal;
        }
        
        return $a;
    }

    function getProgramasCombo()
    {
        $query = $this->db->get('programa');
        
        $a = array('1000' => 'TODOS');
        
        foreach($query->result() as $row)
        {
            $a[$row->idprograma] = $row->programa;
        }
        
        return $a;
    }

    function getRequerimientoCombo()
    {
        $query = $this->db->get('temporal_requerimiento');
        
        $a = array('1000' => 'TODOS');
        
        foreach($query->result() as $row)
        {
            $a[$row->tiporequerimiento] = $row->requerimiento;
        }
        
        return $a;
    }

    function  getReporteRecetasCabeza($fecha1, $fecha2, $idprograma, $tiporequerimiento, $programas, $requerimientos)
    {
        
        $tabla = '<table>
            <tr>
                <td style="text-align: center; " colspan="9" ><b>'.COMPANIA.'</b></td>
            </tr>
            <tr>
                <td style="width: 7%;">UNIDAD: </td>
                <td style="width: 7%;"><b>'.$this->session->userdata('clvsucursal').'</b></td>
                <td style="width: 7%;">SUCURSAL: </td>
                <td style="width: 50%;"><b>'.$this->session->userdata('sucursal').'</b></td>
                <td style="width: 8%;">GENERADO: </td>
                <td style="width: 21%;"><b>'.date('d/m/Y H:i:s').'</b></td>
            </tr>
            <tr>
                <td style="width: 12%;">REQUERIMIENTO: </td>
                <td style="width: 10%;"><b>'.$requerimientos[$tiporequerimiento].'</b></td>
                <td style="width: 10%;">PROGRAMA: </td>
                <td style="width: 39%;"><b>'.$programas[$idprograma].'</b></td>
                <td style="width: 8%;">PERIODO: </td>
                <td style="width: 21%;"><b>DEL '.$fecha1.' AL '.$fecha2.'</b></td>
            </tr>
        </table>
        ';
        
        $tabla .= "
        <br />";
        
        $tabla .= "
        <table>
            <thead>
                <tr>
                    <th style=\"width: 6%;\"><b>Fecha</b></th>
                    <th style=\"width: 9%;\"><b>Folio</b></th>
                    <th style=\"width: 7%;\"><b>Cve. Pac.</b></th>
                    <th style=\"width: 13%;\"><b>Paciente</b></th>
                    <th style=\"width: 7%;\"><b>Cve. Medico</b></th>
                    <th style=\"width: 15%; text-align: left; \"><b>Medico</b></th>
                    <th style=\"width: 7%; text-align: left; \"><b>Cve. Art.</b></th>
                    <th style=\"width: 19%; text-align: left; \"><b>Descripcion</b></th>
                    <th style=\"width: 6%; text-align: right; \"><b>P. unitario</b></th>
                    <th style=\"width: 6%; text-align: right; \"><b>Cant. sol.</b></th>
                    <th style=\"width: 6%; text-align: right; \"><b>Cant. sur.</b></th>
                </tr>
            </thead>
            </table>";
        return $tabla;
    }
    
    function getReporteConsumoCabeza($fecha1, $fecha2)
    {
        $tabla = '<table>
            <tr>
                <td style="text-align: center; "><b>'.COMPANIA.'</b></td>
                <td style="text-align: center; "><b>'.$this->session->userdata('clvsucursal').' - '.$this->session->userdata('sucursal').'</b></td>
            </tr>
            <tr>
                <td style="text-align: center; "><b>REPORTE DE CONSUMO, PERIODO: '.$fecha1.' AL '.$fecha2.'</b></td>
                <td style="text-align: center; ">FECHA DE GENERACION: <b>'.date('d/m/Y H:i:s').'</b></td>
            </tr>
        </table>
        ';
        
        $tabla .= "
        <br />";
        
        $tabla .= "
        <table>
            <thead>
                <tr>
                    <th style=\"width: 10%;\"><b>Clave</b></th>
                    <th style=\"width: 20%;\"><b>Sustancia Activa</b></th>
                    <th style=\"width: 30%;\"><b>Descripcion</b></th>
                    <th style=\"width: 20%;\"><b>Presentacion</b></th>
                    <th style=\"width: 10%; text-align: right; \"><b>Requeridas</b></th>
                    <th style=\"width: 10%; text-align: right; \"><b>Surtidas</b></th>
                </tr>
            </thead>
            </table>";
        return $tabla;
    }
    
    function getReporteNegadoCabeza($fecha1, $fecha2)
    {
        $tabla = '<table>
            <tr>
                <td style="text-align: center; "><b>'.COMPANIA.'</b></td>
                <td style="text-align: center; "><b>'.$this->session->userdata('clvsucursal').' - '.$this->session->userdata('sucursal').'</b></td>
            </tr>
            <tr>
                <td style="text-align: center; "><b>REPORTE DE NEGADOS, PERIODO: '.$fecha1.' AL '.$fecha2.'</b></td>
                <td style="text-align: center; ">FECHA DE GENERACION: <b>'.date('d/m/Y H:i:s').'</b></td>
            </tr>
        </table>
        ';
        
        $tabla .= "
        <br />";
        
        $tabla .= "
        <table>
            <thead>
                <tr>
                    <th style=\"width: 10%;\"><b>Clave</b></th>
                    <th style=\"width: 20%;\"><b>Sustancia Activa</b></th>
                    <th style=\"width: 40%;\"><b>Descripcion</b></th>
                    <th style=\"width: 20%;\"><b>Presentacion</b></th>
                    <th style=\"width: 10%; text-align: right; \"><b>Negados</b></th>
                </tr>
            </thead>
            </table>";
        return $tabla;
    }

    function getConsumo($fecha1, $fecha2)
    {
        $sql = "SELECT cvearticulo, susa, descripcion, pres, sum(canreq) as canreq, sum(cansur) as cansur
FROM receta_detalle d
join receta r using(consecutivo)
join articulos a using(id)
where fecha between ? and ? and r.clvsucursal = ?
group by id
order by tipoprod asc, cvearticulo * 1 asc;";

        $query = $this->db->query($sql, array($fecha1, $fecha2, $this->session->userdata('clvsucursal')));
        
        return $query;
    }

    function getNegado($fecha1, $fecha2)
    {
        $sql = "SELECT cvearticulo, susa, descripcion, pres, sum(canreq - cansur) as negado
FROM receta_detalle d
join receta r using(consecutivo)
join articulos a using(id)
where fecha between ? and ? and r.clvsucursal = ?
group by id
having negado > 0
order by tipoprod asc, cvearticulo * 1 asc
;";

        $query = $this->db->query($sql, array($fecha1, $fecha2, $this->session->userdata('clvsucursal')));
        
        return $query;
    }
    
    function getFechaDiaAnterior()
    {
        $sql = "select date(now() - interval 1 day) as dia;";
        $query = $this->db->query($sql);
        $row = $query->row();
        
        return $row->dia;
    }
    
    function inventarioMensual()
    {
        $sql = "insert into inventario_historico (SELECT *, extract(year from now()) as anio, extract(month from now()) as mes FROM inventario i where cantidad <> 0);";
        $this->db->query($sql);
    }
    
    function getFechaMesAnterior()
    {
        $sql = "select date(now() - interval 1 day) as dia;";
        $query = $this->db->query($sql);
        $row = $query->row();
        
        $ultimo_dia = $row->dia;
        
        $primer_dia = substr($ultimo_dia, 0, 8) . '01';
        
        $data = new stdClass();
        $data->primer_dia = $primer_dia;
        $data->ultimo_dia = $ultimo_dia;
        
        return $data;
    }

    function getCorreos($segment)
    {
        $this->db->where('segment', $segment);
        $query = $this->db->get('correo');
        
        $row = $query->row();
        
        return $row->correo;
    }
    
    function getExcel($es = 0, $fecha1, $fecha2,$cvearticulo = null)
    {
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }


        $sql = "SELECT areaID, area FROM inventario i
left join articulos a using(id)
left join ubicacion u using(ubicacion)
where cantidad <> 0 and i.clvsucursal = ?
group by areaID;";
            
        $query = $this->db->query($sql, array($this->session->userdata('clvsucursal')));
        
        $hoja = 0;
        
        foreach($query->result() as $row)
        {
            $this->excel->createSheet($hoja);
            $this->excel->setActiveSheetIndex($hoja);
            $this->excel->getActiveSheet()->getTabColor()->setRGB('FFFF00');
            
            if($row->area == null)
            {
                $row->area = 'INDEFINIDA';
            }

            $this->excel->getActiveSheet()->setTitle($row->area);
            
            $this->excel->getActiveSheet()->mergeCells('A1:O1');
            $this->excel->getActiveSheet()->mergeCells('A2:O2');

            $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
            $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->setCellValue('A2', APLICACION.'     '.date('d/M/Y H:i:s'));
            $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
            
            if($cvearticulo == null)
            {
                $sql2 = "SELECT *, DATEDIFF(caducidad, now()) as dias FROM inventario i
    left join articulos a using(id)
    left join ubicacion u using(ubicacion)
    where cantidad <> 0 and areaID = ? and i.clvsucursal = ?
    order by cvearticulo * 1;";
    
                $query2 = $this->db->query($sql2, array($row->areaID, $this->session->userdata('clvsucursal')));
            }else{
                $sql2 = "SELECT *, DATEDIFF(caducidad, now()) as dias FROM inventario i
    left join articulos a using(id)
    left join ubicacion u using(ubicacion)
    where cantidad <> 0 and areaID = ? and cvearticulo = ? and i.clvsucursal = ?
    order by cvearticulo * 1;";
    
    
                $query2 = $this->db->query($sql2, array($row->areaID, (string)$cvearticulo, $this->session->userdata('clvsucursal')));
            }

            $num = 3;
            
            $data_empieza = $num + 1;
            
            $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
            $this->excel->getActiveSheet()->setCellValue('B'.$num, 'CLAVE');
            $this->excel->getActiveSheet()->setCellValue('C'.$num, 'EAN');
            $this->excel->getActiveSheet()->setCellValue('D'.$num, 'NOMBRE COMERCIAL');
            $this->excel->getActiveSheet()->setCellValue('E'.$num, 'SUSTANCIA ACTIVA');
            $this->excel->getActiveSheet()->setCellValue('F'.$num, 'DESCRIPCION');
            $this->excel->getActiveSheet()->setCellValue('G'.$num, 'PRESENTACION');
            $this->excel->getActiveSheet()->setCellValue('H'.$num, 'CANTIDAD');
            $this->excel->getActiveSheet()->setCellValue('I'.$num, 'LOTE');
            $this->excel->getActiveSheet()->setCellValue('J'.$num, 'CADUCIDAD');
            $this->excel->getActiveSheet()->setCellValue('K'.$num, 'LABORATORIO / FABRICANTE');
            $this->excel->getActiveSheet()->setCellValue('L'.$num, 'COSTO');
            $this->excel->getActiveSheet()->setCellValue('M'.$num, 'AREA');
            $this->excel->getActiveSheet()->setCellValue('N'.$num, 'PASILLO');
            $this->excel->getActiveSheet()->setCellValue('O'.$num, 'IMPORTE');
            
            $i = 1;
            
            if($query2->num_rows() > 0)
            {
                
            foreach($query2->result()  as $row2)
            {
                $num++;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                $this->excel->getActiveSheet()->setCellValue('B'.$num, $row2->cvearticulo);
                $this->excel->getActiveSheet()->setCellValue('C'.$num, $row2->ean);
                $this->excel->getActiveSheet()->setCellValue('D'.$num, $row2->comercial);
                $this->excel->getActiveSheet()->setCellValue('E'.$num, $row2->susa);
                $this->excel->getActiveSheet()->setCellValue('F'.$num, $row2->descripcion);
                $this->excel->getActiveSheet()->setCellValue('G'.$num, $row2->pres);
                $this->excel->getActiveSheet()->setCellValue('H'.$num, $row2->cantidad);
                $this->excel->getActiveSheet()->setCellValue('I'.$num, $row2->lote);
                $this->excel->getActiveSheet()->setCellValue('J'.$num, $row2->caducidad);
                $this->excel->getActiveSheet()->setCellValue('K'.$num, $row2->marca);
                $this->excel->getActiveSheet()->setCellValue('L'.$num, $row2->costo);
                $this->excel->getActiveSheet()->setCellValue('M'.$num, $row2->area);
                $this->excel->getActiveSheet()->setCellValue('N'.$num, $row2->pasillo);
                $this->excel->getActiveSheet()->setCellValue('O'.$num, '=H'.$num.'*L'.$num);
                
                if($row2->dias <= 0)
                {
                    $this->excel->getActiveSheet()->getStyle('A' . $num . ':N' . $num)->getFill()->applyFromArray(array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                             'rgb' => 'FFA07A'
                        )
                    ));
                }elseif($row2->dias > 0 && $row2->dias <= 90){
                    $this->excel->getActiveSheet()->getStyle('A' . $num . ':N' . $num)->getFill()->applyFromArray(array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                             'rgb' => 'B0E0E6'
                        )
                    ));
                }
                
                $i++;
                
            }
            
            $data_termina = $num;
            
            $this->excel->getActiveSheet()->setCellValue('H'.($data_termina + 1), '=sum(H'.$data_empieza.':H'.$data_termina.')');
            $this->excel->getActiveSheet()->setCellValue('O'.($data_termina + 1), '=sum(O'.$data_empieza.':O'.$data_termina.')');
            
            
            $this->excel->getActiveSheet()->getStyle('H'.$data_empieza.':H'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
            $this->excel->getActiveSheet()->getStyle('L'.$data_empieza.':L'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->getStyle('O'.$data_empieza.':O'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->getStyle('C'.$data_empieza.':C'.$data_termina)->getNumberFormat()->setFormatCode('0');
            $this->excel->getActiveSheet()->getStyle('B'.$data_empieza.':B'.$data_termina)->getNumberFormat()->setFormatCode('0');
            
            $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
            
            $this->excel->getActiveSheet()->getStyle('E'.$data_empieza.':G'.$data_termina)->getAlignment()->setWrapText(true);
            
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FFFF0000'),
                    ),
                ),
            );
            
            $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':O'.($data_termina + 1))->applyFromArray($styleArray);
            
            $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
            $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':O'.($data_termina + 1));
            
            
            }
            $hoja++;
        }
        
//INVENTARIO TOTAL        
            $this->excel->createSheet($hoja);
            $this->excel->setActiveSheetIndex($hoja);
            $this->excel->getActiveSheet()->getTabColor()->setRGB('FFFF00');
            
            $this->excel->getActiveSheet()->setTitle('INVENTARIO TOTAL');
            
            $this->excel->getActiveSheet()->mergeCells('A1:O1');
            $this->excel->getActiveSheet()->mergeCells('A2:O2');

            $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
            $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->setCellValue('A2', APLICACION.'      '.date('d/M/Y H:i:s'));
            $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
            
            if($cvearticulo == null)
            {
                $sql2 = "SELECT *, DATEDIFF(caducidad, now()) as dias FROM inventario i
    left join articulos a using(id)
    left join ubicacion u using(ubicacion)
    where cantidad <> 0 and i.clvsucursal = ?
    order by cvearticulo * 1;";
    
                $query2 = $this->db->query($sql2, array($this->session->userdata('clvsucursal')));
            }else{
                $sql2 = "SELECT *, DATEDIFF(caducidad, now()) as dias FROM inventario i
    left join articulos a using(id)
    left join ubicacion u using(ubicacion)
    where cantidad <> 0 and cvearticulo = ? and i.clvsucursal = ?
    order by cvearticulo * 1;";
    
                $query2 = $this->db->query($sql2, array((string)$cvearticulo, $this->session->userdata('clvsucursal')));
            }
            
            
            $num = 3;
            
            $data_empieza = $num + 1;
            
            $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
            $this->excel->getActiveSheet()->setCellValue('B'.$num, 'CLAVE');
            $this->excel->getActiveSheet()->setCellValue('C'.$num, 'EAN');
            $this->excel->getActiveSheet()->setCellValue('D'.$num, 'NOMBRE COMERCIAL');
            $this->excel->getActiveSheet()->setCellValue('E'.$num, 'SUSTANCIA ACTIVA');
            $this->excel->getActiveSheet()->setCellValue('F'.$num, 'DESCRIPCION');
            $this->excel->getActiveSheet()->setCellValue('G'.$num, 'PRESENTACION');
            $this->excel->getActiveSheet()->setCellValue('H'.$num, 'CANTIDAD');
            $this->excel->getActiveSheet()->setCellValue('I'.$num, 'LOTE');
            $this->excel->getActiveSheet()->setCellValue('J'.$num, 'CADUCIDAD');
            $this->excel->getActiveSheet()->setCellValue('K'.$num, 'LABORATORIO / FABRICANTE');
            $this->excel->getActiveSheet()->setCellValue('L'.$num, 'COSTO');
            $this->excel->getActiveSheet()->setCellValue('M'.$num, 'AREA');
            $this->excel->getActiveSheet()->setCellValue('N'.$num, 'PASILLO');
            $this->excel->getActiveSheet()->setCellValue('O'.$num, 'IMPORTE');
            
            $i = 1;
            
            if($query2->num_rows() > 0)
            {
                
            foreach($query2->result()  as $row2)
            {
                $num++;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                $this->excel->getActiveSheet()->setCellValue('B'.$num, $row2->cvearticulo);
                $this->excel->getActiveSheet()->setCellValue('C'.$num, $row2->ean);
                $this->excel->getActiveSheet()->setCellValue('D'.$num, $row2->comercial);
                $this->excel->getActiveSheet()->setCellValue('E'.$num, $row2->susa);
                $this->excel->getActiveSheet()->setCellValue('F'.$num, $row2->descripcion);
                $this->excel->getActiveSheet()->setCellValue('G'.$num, $row2->pres);
                $this->excel->getActiveSheet()->setCellValue('H'.$num, $row2->cantidad);
                $this->excel->getActiveSheet()->setCellValue('I'.$num, $row2->lote);
                $this->excel->getActiveSheet()->setCellValue('J'.$num, $row2->caducidad);
                $this->excel->getActiveSheet()->setCellValue('K'.$num, $row2->marca);
                $this->excel->getActiveSheet()->setCellValue('L'.$num, $row2->costo);
                $this->excel->getActiveSheet()->setCellValue('M'.$num, $row2->area);
                $this->excel->getActiveSheet()->setCellValue('N'.$num, $row2->pasillo);
                $this->excel->getActiveSheet()->setCellValue('O'.$num, '=H'.$num.'*L'.$num);
                

                if($row2->dias <= 0)
                {
                    $this->excel->getActiveSheet()->getStyle('A' . $num . ':N' . $num)->getFill()->applyFromArray(array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                             'rgb' => 'FFA07A'
                        )
                    ));
                }elseif($row2->dias > 0 && $row2->dias <= 90){
                    $this->excel->getActiveSheet()->getStyle('A' . $num . ':N' . $num)->getFill()->applyFromArray(array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                             'rgb' => 'B0E0E6'
                        )
                    ));
                }

                $i++;
                
            }
            
            $data_termina = $num;
            
            $this->excel->getActiveSheet()->setCellValue('H'.($data_termina + 1), '=sum(H'.$data_empieza.':H'.$data_termina.')');
            $this->excel->getActiveSheet()->setCellValue('O'.($data_termina + 1), '=sum(O'.$data_empieza.':O'.$data_termina.')');
            
            
            $this->excel->getActiveSheet()->getStyle('H'.$data_empieza.':H'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
            $this->excel->getActiveSheet()->getStyle('L'.$data_empieza.':L'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->getStyle('O'.$data_empieza.':O'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->getStyle('C'.$data_empieza.':C'.$data_termina)->getNumberFormat()->setFormatCode('0');
            $this->excel->getActiveSheet()->getStyle('B'.$data_empieza.':B'.$data_termina)->getNumberFormat()->setFormatCode('0');
            
            $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
            
            $this->excel->getActiveSheet()->getStyle('E'.$data_empieza.':G'.$data_termina)->getAlignment()->setWrapText(true);
            
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FFFF0000'),
                    ),
                ),
            );
            
            $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':O'.($data_termina + 1))->applyFromArray($styleArray);
            
            $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
            $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':O'.($data_termina + 1));
            
            }
            
            $hoja++;

//FIN INVENTARIO TOTAL
        if($es == 1)
        {
            $fecha1 = $fecha1 . ' 00:00:00';
            $fecha2 = $fecha2 . ' 23:59:59';
            
            if($cvearticulo == null)
            {
                $sql3 = "SELECT tipoMovimiento, tipoMovimientoDescripcion FROM movimiento m
    join movimiento_detalle d using(movimientoID)
    join articulos a using(id)
    join tipo_movimiento t using(tipoMovimiento)
    join subtipo_movimiento s using(tipoMovimiento, subtipoMovimiento)
    where statusMovimiento = 1
    and fechaCierre between ? and ? and m.clvsucursal = ?
    group by tipoMovimiento;";
                
                $query3 = $this->db->query($sql3, array($fecha1, $fecha2, $this->session->userdata('clvsucursal')));
            }else{
                $sql3 = "SELECT tipoMovimiento, tipoMovimientoDescripcion FROM movimiento m
    join movimiento_detalle d using(movimientoID)
    join articulos a using(id)
    join tipo_movimiento t using(tipoMovimiento)
    join subtipo_movimiento s using(tipoMovimiento, subtipoMovimiento)
    where statusMovimiento = 1
    and fechaCierre between ? and ? and cvearticulo = ? and m.clvsucursal = ?
    group by tipoMovimiento;";
                
                $query3 = $this->db->query($sql3, array($fecha1, $fecha2, $cvearticulo, $this->session->userdata('clvsucursal')));
            }
            
            
            
            foreach($query3->result() as $row3)
            {
                $this->excel->createSheet($hoja);
                $this->excel->setActiveSheetIndex($hoja);
                
                if($row3->tipoMovimiento == 1)
                {
                    $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
                }else{
                    $this->excel->getActiveSheet()->getTabColor()->setRGB('FF0000');
                }
                
                
                
                $this->excel->getActiveSheet()->setTitle($row3->tipoMovimientoDescripcion);
                
                if($cvearticulo == null)
                {
                    $sql4 = "SELECT movimientoID, orden, referencia, fecha, fechaCierre, clvsucursalReferencia, observaciones, nuevo_folio, piezas, costo, lote, caducidad, ean, marca, comercial, cvearticulo, susa, descripcion, pres, subtipoMovimientoDescripcion, rfc, razon, descsucursal, nombreusuario FROM movimiento m
    join movimiento_detalle d using(movimientoID)
    join articulos a using(id)
    join tipo_movimiento t using(tipoMovimiento)
    join subtipo_movimiento s using(tipoMovimiento, subtipoMovimiento)
    join proveedor p using(proveedorID)
    join sucursales u on m.clvsucursalReferencia = u.clvsucursal
    join usuarios o using(usuario)
    where statusMovimiento = 1 and tipoMovimiento = ?
    and fechaCierre between ? and ? and m.clvsucursal = ?
    order by fechaCierre, movimientoID, cvearticulo * 1;";
                    
                    $query4 = $this->db->query($sql4, array($row3->tipoMovimiento, $fecha1, $fecha2, $this->session->userdata('clvsucursal')));
                }else{
                    $sql4 = "SELECT movimientoID, orden, referencia, fecha, fechaCierre, clvsucursalReferencia, observaciones, nuevo_folio, piezas, costo, lote, caducidad, ean, marca, comercial, cvearticulo, susa, descripcion, pres, subtipoMovimientoDescripcion, rfc, razon, descsucursal, nombreusuario FROM movimiento m
    join movimiento_detalle d using(movimientoID)
    join articulos a using(id)
    join tipo_movimiento t using(tipoMovimiento)
    join subtipo_movimiento s using(tipoMovimiento, subtipoMovimiento)
    join proveedor p using(proveedorID)
    join sucursales u on m.clvsucursalReferencia = u.clvsucursal
    join usuarios o using(usuario)
    where statusMovimiento = 1 and tipoMovimiento = ?
    and fechaCierre between ? and ? and cvearticulo = ? and m.clvsucursal = ?
    order by fechaCierre, movimientoID, cvearticulo * 1;";
                    
                    $query4 = $this->db->query($sql4, array($row3->tipoMovimiento, $fecha1, $fecha2, $cvearticulo, $this->session->userdata('clvsucursal')));
                }
                
                
                $this->excel->getActiveSheet()->mergeCells('A1:V1');
                $this->excel->getActiveSheet()->mergeCells('A2:V2');
    
                $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
                $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->setCellValue('A2', APLICACION . ' DESDE ' . $fecha1 . ' HASTA ' . $fecha2);
                $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);


                $num = 3;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, 'ID MOVIMIENTO');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'TIPO');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'ORDEN');
                $this->excel->getActiveSheet()->setCellValue('E'.$num, 'REFERENCIA');
                $this->excel->getActiveSheet()->setCellValue('F'.$num, 'FECHA DOC.');
                $this->excel->getActiveSheet()->setCellValue('G'.$num, 'FECHA CIERRE');
                $this->excel->getActiveSheet()->setCellValue('H'.$num, 'CLAVE');
                $this->excel->getActiveSheet()->setCellValue('I'.$num, 'EAN');
                $this->excel->getActiveSheet()->setCellValue('J'.$num, 'COMERCIAL');
                $this->excel->getActiveSheet()->setCellValue('K'.$num, 'SUSTANCIA ACTIVA');
                $this->excel->getActiveSheet()->setCellValue('L'.$num, 'DESCRIPCION');
                $this->excel->getActiveSheet()->setCellValue('M'.$num, 'PRESENTACION');
                $this->excel->getActiveSheet()->setCellValue('N'.$num, 'CANTIDAD');
                $this->excel->getActiveSheet()->setCellValue('O'.$num, 'COSTO');
                $this->excel->getActiveSheet()->setCellValue('P'.$num, 'LOTE');
                $this->excel->getActiveSheet()->setCellValue('Q'.$num, 'CADUCIDAD');
                $this->excel->getActiveSheet()->setCellValue('R'.$num, 'MARCA');
                $this->excel->getActiveSheet()->setCellValue('S'.$num, 'RAZON SOCIAL');
                $this->excel->getActiveSheet()->setCellValue('T'.$num, 'SUCURSAL DESTINO');
                $this->excel->getActiveSheet()->setCellValue('U'.$num, 'USUARIO');
                $this->excel->getActiveSheet()->setCellValue('V'.$num, 'IMPORTE');
                
                $i = 1;

                foreach($query4->result()  as $row4)
                {
                    $num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row4->movimientoID);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row4->subtipoMovimientoDescripcion);
                    $this->excel->getActiveSheet()->setCellValue('D'.$num, $row4->orden);
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row4->referencia);
                    $this->excel->getActiveSheet()->setCellValue('F'.$num, $row4->fecha);
                    $this->excel->getActiveSheet()->setCellValue('G'.$num, $row4->fechaCierre);
                    $this->excel->getActiveSheet()->setCellValue('H'.$num, $row4->cvearticulo);
                    $this->excel->getActiveSheet()->setCellValue('I'.$num, $row4->ean);
                    $this->excel->getActiveSheet()->setCellValue('J'.$num, $row4->comercial);
                    $this->excel->getActiveSheet()->setCellValue('K'.$num, $row4->susa);
                    $this->excel->getActiveSheet()->setCellValue('L'.$num, $row4->descripcion);
                    $this->excel->getActiveSheet()->setCellValue('M'.$num, $row4->pres);
                    $this->excel->getActiveSheet()->setCellValue('N'.$num, $row4->piezas);
                    $this->excel->getActiveSheet()->setCellValue('O'.$num, $row4->costo);
                    $this->excel->getActiveSheet()->setCellValue('P'.$num, $row4->lote);
                    $this->excel->getActiveSheet()->setCellValue('Q'.$num, $row4->caducidad);
                    $this->excel->getActiveSheet()->setCellValue('R'.$num, $row4->marca);
                    $this->excel->getActiveSheet()->setCellValue('S'.$num, $row4->razon);
                    $this->excel->getActiveSheet()->setCellValue('T'.$num, $row4->descsucursal);
                    $this->excel->getActiveSheet()->setCellValue('U'.$num, $row4->nombreusuario);
                    $this->excel->getActiveSheet()->setCellValue('V'.$num, '=N'.$num.'*O'.$num);
                    
                    $i++;
                    
                }
                
                $data_termina = $num;

                $this->excel->getActiveSheet()->setCellValue('N'.($data_termina + 1), '=sum(N'.$data_empieza.':N'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('V'.($data_termina + 1), '=sum(V'.$data_empieza.':V'.$data_termina.')');
                
                
                $this->excel->getActiveSheet()->getStyle('N'.$data_empieza.':N'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
                $this->excel->getActiveSheet()->getStyle('O'.$data_empieza.':O'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('V'.$data_empieza.':V'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('E'.$data_empieza.':E'.$data_termina)->getNumberFormat()->setFormatCode('0');
                $this->excel->getActiveSheet()->getStyle('H'.$data_empieza.':H'.$data_termina)->getNumberFormat()->setFormatCode('0');
                $this->excel->getActiveSheet()->getStyle('I'.$data_empieza.':I'.$data_termina)->getNumberFormat()->setFormatCode('0');
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                
                $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
                
                $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
                $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
                $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
                
                $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':M'.$data_termina)->getAlignment()->setWrapText(true);
                
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':V'.($data_termina + 1))->applyFromArray($styleArray);
                
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':V'.($data_termina + 1));
    
                $hoja++;
            }
            
        }
        
        if($cvearticulo == null)
        {
            $sql5 = "SELECT * FROM kardex k
    join articulos a using(id)
    join subtipo_movimiento s using(subtipoMovimiento)
    join usuarios u using(usuario)
    where subtipoMovimiento = 11 and fechaKardex between ? and ? and k.clvsucursal = ?;";
            
            $query5 = $this->db->query($sql5, array($fecha1, $fecha2, $this->session->userdata('clvsucursal')));
        }else{
            $sql5 = "SELECT * FROM kardex k
    join articulos a using(id)
    join subtipo_movimiento s using(subtipoMovimiento)
    join usuarios u using(usuario)
    where subtipoMovimiento = 11 and fechaKardex between ? and ? and cvearticulo = ? and k.clvsucursal = ?;";
            
            $query5 = $this->db->query($sql5, array($fecha1, $fecha2, $cvearticulo, $this->session->userdata('clvsucursal')));
        }
        
        
        if($query5->num_rows()  > 0)
        {
            $this->excel->createSheet($hoja);
            $this->excel->setActiveSheetIndex($hoja);
            $this->excel->getActiveSheet()->getTabColor()->setRGB('4682B4');
            $this->excel->getActiveSheet()->setTitle('AJUSTES');
            
            
                $this->excel->getActiveSheet()->mergeCells('A1:N1');
                $this->excel->getActiveSheet()->mergeCells('A2:N2');
    
                $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
                $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->setCellValue('A2', APLICACION . ' DESDE ' . $fecha1 . ' HASTA ' . $fecha2);
                $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);


                $num = 3;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, 'ID KARDEX');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'TIPO');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'FECHA AJUSTE');
                $this->excel->getActiveSheet()->setCellValue('E'.$num, 'CLAVE');
                $this->excel->getActiveSheet()->setCellValue('F'.$num, 'COMERCIAL');
                $this->excel->getActiveSheet()->setCellValue('G'.$num, 'SUSTANCIA ACTIVA');
                $this->excel->getActiveSheet()->setCellValue('H'.$num, 'DESCRIPCION');
                $this->excel->getActiveSheet()->setCellValue('I'.$num, 'PRESENTACION');
                $this->excel->getActiveSheet()->setCellValue('J'.$num, 'CANTIDAD ANTERIOR');
                $this->excel->getActiveSheet()->setCellValue('K'.$num, 'CANTIDAD NUEVA');
                $this->excel->getActiveSheet()->setCellValue('L'.$num, 'LOTE');
                $this->excel->getActiveSheet()->setCellValue('M'.$num, 'CADUCIDAD');
                $this->excel->getActiveSheet()->setCellValue('N'.$num, 'USUARIO');
                
                $i = 1;

                foreach($query5->result()  as $row5)
                {
                    $num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row5->kardexID);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row5->subtipoMovimientoDescripcion);
                    $this->excel->getActiveSheet()->setCellValue('D'.$num, $row5->fechaKardex);
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row5->cvearticulo);
                    $this->excel->getActiveSheet()->setCellValue('F'.$num, $row5->comercial);
                    $this->excel->getActiveSheet()->setCellValue('G'.$num, $row5->susa);
                    $this->excel->getActiveSheet()->setCellValue('H'.$num, $row5->descripcion);
                    $this->excel->getActiveSheet()->setCellValue('I'.$num, $row5->pres);
                    $this->excel->getActiveSheet()->setCellValue('J'.$num, $row5->cantidadOld);
                    $this->excel->getActiveSheet()->setCellValue('K'.$num, $row5->cantidadNew);
                    $this->excel->getActiveSheet()->setCellValue('L'.$num, $row5->lote);
                    $this->excel->getActiveSheet()->setCellValue('M'.$num, $row5->caducidad);
                    $this->excel->getActiveSheet()->setCellValue('N'.$num, $row5->nombreusuario);
                    
                    $i++;
                    
                }
                
                $data_termina = $num;

                $this->excel->getActiveSheet()->setCellValue('J'.($data_termina + 1), '=sum(J'.$data_empieza.':J'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('K'.($data_termina + 1), '=sum(K'.$data_empieza.':K'.$data_termina.')');
                $this->excel->getActiveSheet()->getStyle('J'.$data_empieza.':J'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
                $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':K'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
                
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                
                $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
                
                $this->excel->getActiveSheet()->getStyle('G'.$data_empieza.':I'.$data_termina)->getAlignment()->setWrapText(true);
                
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':N'.($data_termina + 1))->applyFromArray($styleArray);
                
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':N'.($data_termina + 1));

            $hoja++;
        }
        
    }

    function getMovimiento($tipoMovimiento, $fecha1, $fecha2, $proveedorID = null, $clvsucursal = null)
    {
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
        
        $hoja = 0;
        
            $fecha1 = $fecha1 . ' 00:00:00';
            $fecha2 = $fecha2 . ' 23:59:59';
            
            
                $this->excel->createSheet($hoja);
                $this->excel->setActiveSheetIndex($hoja);
                
                if($tipoMovimiento == 1)
                {
                    $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
                    $this->excel->getActiveSheet()->setTitle('ENTRADA');
                }else{
                    $this->excel->getActiveSheet()->getTabColor()->setRGB('FF0000');
                    $this->excel->getActiveSheet()->setTitle('SALIDA');
                }
                
                
                if($tipoMovimiento == 1)
                {
                    
                    if($proveedorID == null)
                    {
                        $sql4 = "SELECT movimientoID, orden, referencia, fecha, fechaCierre, clvsucursalReferencia, observaciones, nuevo_folio, piezas, costo, lote, caducidad, ean, marca, comercial, cvearticulo, susa, descripcion, pres, subtipoMovimientoDescripcion, rfc, razon, descsucursal, nombreusuario FROM movimiento m
        join movimiento_detalle d using(movimientoID)
        join articulos a using(id)
        join tipo_movimiento t using(tipoMovimiento)
        join subtipo_movimiento s using(tipoMovimiento, subtipoMovimiento)
        join proveedor p using(proveedorID)
        join sucursales u on m.clvsucursalReferencia = u.clvsucursal
        join usuarios o using(usuario)
        where statusMovimiento = 1 and tipoMovimiento = ?
        and fechaCierre between ? and ? and m.clvsucursal = ?
        order by fechaCierre, movimientoID, cvearticulo * 1;";
                        
                        $query4 = $this->db->query($sql4, array($tipoMovimiento, $fecha1, $fecha2, $this->session->userdata('clvsucursal')));
                    }else{
                        $sql4 = "SELECT movimientoID, orden, referencia, fecha, fechaCierre, clvsucursalReferencia, observaciones, nuevo_folio, piezas, costo, lote, caducidad, ean, marca, comercial, cvearticulo, susa, descripcion, pres, subtipoMovimientoDescripcion, rfc, razon, descsucursal, nombreusuario FROM movimiento m
        join movimiento_detalle d using(movimientoID)
        join articulos a using(id)
        join tipo_movimiento t using(tipoMovimiento)
        join subtipo_movimiento s using(tipoMovimiento, subtipoMovimiento)
        join proveedor p using(proveedorID)
        join sucursales u on m.clvsucursalReferencia = u.clvsucursal
        join usuarios o using(usuario)
        where statusMovimiento = 1 and tipoMovimiento = ?
        and fechaCierre between ? and ? and proveedorID = ? and m.clvsucursal = ?
        order by fechaCierre, movimientoID, cvearticulo * 1;";
                        
                        $query4 = $this->db->query($sql4, array($tipoMovimiento, $fecha1, $fecha2, $proveedorID, $this->session->userdata('clvsucursal')));
                    }

                }else{
                    
                    if($clvsucursal == null)
                    {
                        $sql4 = "SELECT movimientoID, orden, referencia, fecha, fechaCierre, clvsucursalReferencia, observaciones, nuevo_folio, piezas, costo, lote, caducidad, ean, marca, comercial, cvearticulo, susa, descripcion, pres, subtipoMovimientoDescripcion, rfc, razon, descsucursal, nombreusuario FROM movimiento m
        join movimiento_detalle d using(movimientoID)
        join articulos a using(id)
        join tipo_movimiento t using(tipoMovimiento)
        join subtipo_movimiento s using(tipoMovimiento, subtipoMovimiento)
        join proveedor p using(proveedorID)
        join sucursales u on m.clvsucursalReferencia = u.clvsucursal
        join usuarios o using(usuario)
        where statusMovimiento = 1 and tipoMovimiento = ?
        and fechaCierre between ? and ? and m.clvsucursal = ?
        order by fechaCierre, movimientoID, cvearticulo * 1;";
                        
                        $query4 = $this->db->query($sql4, array($tipoMovimiento, $fecha1, $fecha2, $this->session->userdata('clvsucursal')));
                    }else{
                        $sql4 = "SELECT movimientoID, orden, referencia, fecha, fechaCierre, clvsucursalReferencia, observaciones, nuevo_folio, piezas, costo, lote, caducidad, ean, marca, comercial, cvearticulo, susa, descripcion, pres, subtipoMovimientoDescripcion, rfc, razon, descsucursal, nombreusuario FROM movimiento m
        join movimiento_detalle d using(movimientoID)
        join articulos a using(id)
        join tipo_movimiento t using(tipoMovimiento)
        join subtipo_movimiento s using(tipoMovimiento, subtipoMovimiento)
        join proveedor p using(proveedorID)
        join sucursales u on m.clvsucursalReferencia = u.clvsucursal
        join usuarios o using(usuario)
        where statusMovimiento = 1 and tipoMovimiento = ?
        and fechaCierre between ? and ? and m.clvsucursalReferencia = ? and m.clvsucursal = ?
        order by fechaCierre, movimientoID, cvearticulo * 1;";
                        
                        $query4 = $this->db->query($sql4, array($tipoMovimiento, $fecha1, $fecha2, $clvsucursal, $this->session->userdata('clvsucursal')));
                    }

                }
                
                
                
                
                $this->excel->getActiveSheet()->mergeCells('A1:V1');
                $this->excel->getActiveSheet()->mergeCells('A2:V2');
    
                $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
                $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->setCellValue('A2', APLICACION . ' DESDE ' . $fecha1 . ' HASTA ' . $fecha2);
                $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);


                $num = 3;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, 'ID MOVIMIENTO');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'TIPO');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'ORDEN');
                $this->excel->getActiveSheet()->setCellValue('E'.$num, 'REFERENCIA');
                $this->excel->getActiveSheet()->setCellValue('F'.$num, 'FECHA DOC.');
                $this->excel->getActiveSheet()->setCellValue('G'.$num, 'FECHA CIERRE');
                $this->excel->getActiveSheet()->setCellValue('H'.$num, 'CLAVE');
                $this->excel->getActiveSheet()->setCellValue('I'.$num, 'EAN');
                $this->excel->getActiveSheet()->setCellValue('J'.$num, 'COMERCIAL');
                $this->excel->getActiveSheet()->setCellValue('K'.$num, 'SUSTANCIA ACTIVA');
                $this->excel->getActiveSheet()->setCellValue('L'.$num, 'DESCRIPCION');
                $this->excel->getActiveSheet()->setCellValue('M'.$num, 'PRESENTACION');
                $this->excel->getActiveSheet()->setCellValue('N'.$num, 'CANTIDAD');
                $this->excel->getActiveSheet()->setCellValue('O'.$num, 'COSTO');
                $this->excel->getActiveSheet()->setCellValue('P'.$num, 'LOTE');
                $this->excel->getActiveSheet()->setCellValue('Q'.$num, 'CADUCIDAD');
                $this->excel->getActiveSheet()->setCellValue('R'.$num, 'MARCA');
                $this->excel->getActiveSheet()->setCellValue('S'.$num, 'RAZON SOCIAL');
                $this->excel->getActiveSheet()->setCellValue('T'.$num, 'SUCURSAL DESTINO');
                $this->excel->getActiveSheet()->setCellValue('U'.$num, 'USUARIO');
                $this->excel->getActiveSheet()->setCellValue('V'.$num, 'IMPORTE');
                
                $i = 1;

                foreach($query4->result()  as $row4)
                {
                    $num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row4->movimientoID);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row4->subtipoMovimientoDescripcion);
                    $this->excel->getActiveSheet()->setCellValue('D'.$num, $row4->orden);
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row4->referencia);
                    $this->excel->getActiveSheet()->setCellValue('F'.$num, $row4->fecha);
                    $this->excel->getActiveSheet()->setCellValue('G'.$num, $row4->fechaCierre);
                    $this->excel->getActiveSheet()->setCellValue('H'.$num, $row4->cvearticulo);
                    $this->excel->getActiveSheet()->setCellValue('I'.$num, $row4->ean);
                    $this->excel->getActiveSheet()->setCellValue('J'.$num, $row4->comercial);
                    $this->excel->getActiveSheet()->setCellValue('K'.$num, $row4->susa);
                    $this->excel->getActiveSheet()->setCellValue('L'.$num, $row4->descripcion);
                    $this->excel->getActiveSheet()->setCellValue('M'.$num, $row4->pres);
                    $this->excel->getActiveSheet()->setCellValue('N'.$num, $row4->piezas);
                    $this->excel->getActiveSheet()->setCellValue('O'.$num, $row4->costo);
                    $this->excel->getActiveSheet()->setCellValue('P'.$num, $row4->lote);
                    $this->excel->getActiveSheet()->setCellValue('Q'.$num, $row4->caducidad);
                    $this->excel->getActiveSheet()->setCellValue('R'.$num, $row4->marca);
                    $this->excel->getActiveSheet()->setCellValue('S'.$num, $row4->razon);
                    $this->excel->getActiveSheet()->setCellValue('T'.$num, $row4->descsucursal);
                    $this->excel->getActiveSheet()->setCellValue('U'.$num, $row4->nombreusuario);
                    $this->excel->getActiveSheet()->setCellValue('V'.$num, '=N'.$num.'*O'.$num);
                    
                    $i++;
                    
                }
                
                $data_termina = $num;

                $this->excel->getActiveSheet()->setCellValue('N'.($data_termina + 1), '=sum(N'.$data_empieza.':N'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('V'.($data_termina + 1), '=sum(V'.$data_empieza.':V'.$data_termina.')');
                
                
                $this->excel->getActiveSheet()->getStyle('N'.$data_empieza.':N'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
                $this->excel->getActiveSheet()->getStyle('O'.$data_empieza.':O'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('V'.$data_empieza.':V'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('E'.$data_empieza.':E'.$data_termina)->getNumberFormat()->setFormatCode('0');
                $this->excel->getActiveSheet()->getStyle('H'.$data_empieza.':H'.$data_termina)->getNumberFormat()->setFormatCode('0');
                $this->excel->getActiveSheet()->getStyle('I'.$data_empieza.':I'.$data_termina)->getNumberFormat()->setFormatCode('0');
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                
                $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
                
                $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
                $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
                $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
                
                $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':M'.$data_termina)->getAlignment()->setWrapText(true);
                
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':V'.($data_termina + 1))->applyFromArray($styleArray);
                
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':V'.($data_termina + 1));
    
                $hoja++;
        
    }

    function header($fecha1, $fecha2, $orden)
    {
        
        if($orden == 0)
        {
            $o = 'TODAS';
        }else{
            $o = $orden;
        }
        
        $logo = array(
                                  'src' => base_url().'assets/img/logo.png',
                                  'width' => '120'
                        );
                        
        
        
        $tabla = '<table cellpadding="1">
            <tr>
                <td rowspan="3" width="100px">'.img($logo).'</td>
                <td rowspan="3" width="450px" align="center"><font size="8">'.COMPANIA.'<br />REPORTE DE FACTURAS PARA CUENTAS POR PAGAR.<br />'.APLICACION.'</font></td>
                <td width="75px">Fecha inicial: </td>
                <td width="95px" align="right">'.$fecha1.'</td>
            </tr>
            <tr>
                <td width="75px">Fecha final: </td>
                <td width="95px" align="right">'.$fecha2.'</td>
            </tr>
            <tr>
                <td width="75px">Orden: </td>
                <td width="95px" align="right">'.$o.'</td>
            </tr>
        </table>';
        
        return $tabla;
    }
    
    function getFacturas($fecha1, $fecha2, $orden)
    {
        $fecha1 = $fecha1 . ' 00:00:00';
        $fecha2 = $fecha2 . ' 23:59:59';
        
        if($orden == 0)
        {
            $o = null;
        }else{
            $o = ' and m.orden = ' . $orden;
        }
        
        $sql = "SELECT referencia, razon, fecha, orden, nuevo_folio, observaciones, nombreusuario, movimientoID
FROM movimiento m
join proveedor o using(proveedorID)
left join usuarios u using(usuario)
where statusMovimiento = 1 and subtipoMovimiento = 1 and fechaCierre between ? and ? and m.clvsucursal = ? $o
order by fechaCierre, referencia;";

        $query = $this->db->query($sql, array($fecha1, $fecha2, $this->session->userdata('clvsucursal')));
        
        return $query;
    }
    
    function getFacturaDetalle($movimientoID)
    {
        $sql = "SELECT *
FROM movimiento m
join proveedor o using(proveedorID)
join movimiento_detalle d using(movimientoID)
join articulos a using(id)
where movimientoID = ? and m.clvsucursal = ?
order by fechaCierre, referencia;";

        
        $query = $this->db->query($sql, array($movimientoID, $this->session->userdata('clvsucursal')));
        
        return $query;
    }
    
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function getSucursalesByJur2($juris = 0)
    {
        if($juris == 0)
        {
            
        }else{
            $this->db->where('numjurisd', $juris);
        }

        $this->db->where('activa', 1);
        $this->db->where('tiposucursal', 1);
        $this->db->order_by('clvsucursal');
        $query = $this->db->get('sucursales');
        
        $a = array('0' => 'TODAS');
        foreach($query->result() as $row)
        {
            $a[$row->clvsucursal] = $row->clvsucursal. ' - ' . trim($row->descsucursal);
        }
        
        return $a;
    }
    
    function getSucursalesCliente()
    {

        switch ($this->clvpuesto) {
            case 15:
                $this->db->where('clvsucursal', $this->session->userdata('clvsucursal'));
                $a = array();
                break;
            case 16:
                $this->db->where('numjurisd', $this->session->userdata('numjurisd'));
                $a = array();
                break;
            case 17:
            case 18:
                $a = array('0' => 'TODAS');
                break;
            default:
               
        }

        $this->db->where('activa', 1);
        $this->db->where('tiposucursal', 1);
        $this->db->order_by('clvsucursal');
        $query = $this->db->get('sucursales');
        
        foreach($query->result() as $row)
        {
            $a[$row->clvsucursal] = $row->clvsucursal. ' - ' . trim($row->descsucursal);
        }
        
        return $a;
    }

    function getProgramas()
    {
        $this->db->where('activo', 1);
        $this->db->order_by('idprograma');
        $query = $this->db->get('programa');
        
        $a = array('1000' => 'TODOS');
        foreach($query->result() as $row)
        {
            $a[$row->idprograma] = $row->idprograma. ' - ' . trim($row->programa);
        }
        
        return $a;
    }
    
    function getJuris()
    {
        $this->db->where('jurisdiccionActiva', 1);
        $this->db->order_by('numjurisd');
        $query = $this->db->get('jurisdiccion');
        
        $a = array('0' => 'TODAS');
        foreach($query->result() as $row)
        {
            $a[$row->numjurisd] = $row->numjurisd. ' - ' . trim($row->jurisdiccion);
        }
        
        return $a;
    }
    
    function getJurisCliente()
    {

        switch ($this->clvpuesto) {
            case 15:
                $this->db->where('numjurisd', $this->session->userdata('numjurisd'));
                $a = array();
                break;
            case 16:
                $this->db->where('numjurisd', $this->session->userdata('numjurisd'));
                $a = array();
                break;
            case 17:
            case 18:
                $a = array('0' => 'TODAS');
                break;
            default:
               
        }

        $this->db->where('jurisdiccionActiva', 1);
        $this->db->order_by('numjurisd');
        $query = $this->db->get('jurisdiccion');
        
        
        foreach($query->result() as $row)
        {
            $a[$row->numjurisd] = $row->numjurisd. ' - ' . trim($row->jurisdiccion);
        }
        
        return $a;
    }

    function getSuministroCombo()
    {
        $query = $this->db->get('temporal_suministro');
        
        $a = array('1000' => 'TODO');
        
        foreach($query->result() as $row)
        {
            
            $a[$row->cvesuministro] = $row->suministro;
            
        }
        
        return $a;
    }
    /*
     function getTiposSucursal()
    {
        $this->db->order_by('nivelatencion');
        $query = $this->db->get('temporal_nivel_atencion');
        
        $a = array('0' => 'TODOS');
        foreach($query->result() as $row)
        {
            $a[$row->nivelatencion] = $row->nivelatencion. ' - ' . trim($row->tipo_sucursal);
        }
        
        return $a;
    }    
    
    */
    
    
     function getProgramaByAll($fecha1, $fecha2, $suministro, $juris, $sucursal, $tipo_sucursal)
    {
        $suministro = (int) $suministro;
        if($suministro == 1000)
        {
            $tipo = null;
        }else{
            $tipo = "and a.tipoprod = $suministro";
        }
        
        if($sucursal== 0)
        {
            if((int)$juris == 0)
            {
                $filtro = null;
            }else{
                $filtro = "and s.numjurisd = $juris";
            }
        }else{
            
            $filtro = "and r.clvsucursal = '$sucursal'";
            
        }
        
        if($tipo_sucursal == 0)
        {
            $nivel_sucursal = null;
        }else{
            $nivel_sucursal = "and s.tiposucursal = $tipo_sucursal";
        }

        $sql = "
       select a.cvearticulo, concat(a.susa,' ',a.descripcion, ' ',a.pres) as completo,a.tipoprod
, (select sum(case when r.idprograma = '0' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as pa
, (select sum(case when r.idprograma = '1' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as sp
, (select sum(case when r.idprograma = '2' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as op
, (select sum(case when r.idprograma = '3' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as pp
, (select sum(case when r.idprograma = '4' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as bp
, (select sum(case when r.idprograma = '5' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as am
, (select sum(case when r.idprograma = '6' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as pq
, (select sum(case when r.idprograma = '7' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as sm
, (select sum(case when r.idprograma = '8' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as ch
, (select sum(p.cansur) from receta_detalle p where p.consecutivo = x.consecutivo) as todo,
(select sum(x.precio) from receta_detalle p where p.consecutivo = x.consecutivo group by p.consecutivo) as subtotal,
preciosinser,a.servicio,cansur
from articulos a
join receta_detalle x on x.id = a.id
join receta r on x.consecutivo = r.consecutivo
join sucursales s on r.clvsucursal = s.clvsucursal $nivel_sucursal
join temporal_suministro tt on a.tipoprod = tt.cvesuministro $tipo
where r.fecha between ? and ? $filtro 
group by a.cvearticulo,r.idprograma";


        $query = $this->db->query($sql, array($fecha1, $fecha2));
        //echo $this->db->last_query();
        $this->insertaQuery($this->db->last_query());
        
        //echo $this->db->last_query();
        return $query;
    }
    
    
     function getProgramaByAll_farmacia($fecha1, $fecha2, $suministro)
    {
        $suministro = (int) $suministro;
        if($suministro == 1000)
        {
            $tipo = null;
        }else{
            $tipo = "and a.tipoprod = $suministro";
        }
        

        $sql = "
       SELECT a.cvearticulo, concat(a.susa,' ',a.descripcion, ' ',a.pres) as completo,a.tipoprod
, (select sum(case when r.idprograma = '0' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as pa
, (select sum(case when r.idprograma = '1' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as sp
, (select sum(case when r.idprograma = '2' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as op
, (select sum(case when r.idprograma = '3' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as pp
, (select sum(case when r.idprograma = '4' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as bp
, (select sum(case when r.idprograma = '5' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as am
, (select sum(case when r.idprograma = '6' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as pq
, (select sum(case when r.idprograma = '7' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as sm
, (select sum(case when r.idprograma = '8' then cansur else 0 end) from receta_detalle p where r.consecutivo = p.consecutivo) as ch
, (select sum(p.cansur) from receta_detalle p where p.consecutivo = x.consecutivo) as todo,
(select sum(x.precio) from receta_detalle p where p.consecutivo = x.consecutivo group by p.consecutivo) as subtotal,
preciosinser,a.servicio,cansur
from articulos a
join receta_detalle x on x.id = a.id
join receta r on x.consecutivo = r.consecutivo
join sucursales s on r.clvsucursal = s.clvsucursal
join temporal_suministro tt on a.tipoprod = tt.cvesuministro $tipo
where r.fecha between ? and ? and r.clvsucursal = ? 
group by a.cvearticulo,r.idprograma";


        $query = $this->db->query($sql, array($fecha1, $fecha2, (string)$this->session->userdata('clvsucursal')));
        //echo $this->db->last_query();
        $this->insertaQuery($this->db->last_query());
        
        //echo $this->db->last_query();
        return $query;
    }

     function insertaQuery($query_string,$aa){
        $this->db->where('usuario', $this->session->userdata('usuario'));
        $this->db->where('reporte', $this->uri->segment(2));
        $query = $this->db->get('temporal_query');
        if($query->num_rows() == 0)
        {
            $data = array(
                'usuario'   => $this->session->userdata('usuario'),
                'reporte'   => $this->uri->segment(2),
                'query'     => $query_string,
                'titulo'    => $aa
                );
                
            $this->db->insert('temporal_query', $data);
        }else{

            $where = array(
                'usuario'   => $this->session->userdata('usuario'),
                'reporte'   => $this->uri->segment(2)
                );
            
            $data = array('query' => $query_string,'titulo'=>$aa);
            $this->db->update('temporal_query', $data, $where);
        }
        
    }

function getProgramaByProgramaByAll($fecha1, $fecha2, $suministro, $idprograma, $juris, $sucursal)

    {
        $suministro = (int) $suministro;
        if($suministro == 1000)
        {
            $tipo = null;
        }else{
            $tipo = "and tipoprod = $suministro";
        }
        
        
        if($sucursal == 0)
        {
            if($juris == 0)
            {
                $filtro = null;
            }else{
                $filtro = "and s.numjurisd = $juris";
            }
        }else{
            
            $filtro = "and r.clvsucursal = '$sucursal'";
            
        }
        
        if((int)$idprograma == 1000)
        {
            $programa = null;
        }else{
            $programa = "and r.idprograma = '$idprograma'";
        }
         
         
        $aa = $this->getTitulos($fecha1,$fecha2,$suministro,$idprograma);        
        $sql = "SELECT a.cvearticulo, concat(a.susa,'-',a.descripcion, '',a.pres)
        as completo, a.tipoprod, sum(canreq)
        as requerida, sum(cansur) as surtida, precioven
        from articulos a join receta_detalle d on a.id = d.id
        join receta r on d.consecutivo = r.consecutivo
        join sucursales s on r.clvsucursal = s.clvsucursal
        where fecha between ? and ? $tipo $filtro $programa
        group by a.cvearticulo, a.tipoprod, completo,
        precioven order by tipoprod, a.cvearticulo,
        replace(a.cvearticulo, 'S/C', '');";

        
        $query = $this->db->query($sql, array($fecha1, $fecha2));
        
        $this->insertaQuery($this->db->last_query(),$aa);
        
        return $query;
    }


function getProgramaByProgramaByAll_farmacia($fecha1, $fecha2, $suministro, $idprograma)

    {
        $suministro = (int) $suministro;
        if($suministro == 1000)
        {
            $tipo = null;
        }else{
            $tipo = "and tipoprod = $suministro";
        }
        
        
       
        if((int)$idprograma == 1000)
        {
            $programa = null;
        }else{
            $programa = "and r.idprograma = '$idprograma'";
        }
        
        $aa = $this->getTitulos($fecha1,$fecha2,$suministro,$idprograma);
        
        $sql = "SELECT a.cvearticulo, concat(a.susa,'-',a.descripcion, '',a.pres)
        as completo, a.tipoprod, sum(canreq)
        as requerida, sum(cansur) as surtida, precioven
        from articulos a join receta_detalle d on a.id = d.id
        join receta r on d.consecutivo = r.consecutivo
        join sucursales s on r.clvsucursal = s.clvsucursal
        where fecha between ? and ? and r.clvsucursal = ? $tipo $programa
        group by a.cvearticulo, a.tipoprod, completo,
        preciosinser order by tipoprod, a.cvearticulo,
        replace(a.cvearticulo, 'S/C', '');";

        
        $query = $this->db->query($sql, array($fecha1, $fecha2, (string)$this->session->userdata('clvsucursal')));
        
        $this->insertaQuery($this->db->last_query(),$aa);
        
        return $query;
    }
    
    function getTitulos($fecha1,$fecha2,$suministro,$idprograma)
    {
        
        $suministro = (int) $suministro;
        if($suministro == 1000)
        {
            $t1 = 'SUMINISTRO DE:  TODO';
        }else{
            $s1 = "select * from temporal_suministro where cvesuministro = $suministro";
            $q1 = $this->db->query($s1);
            $r1 = $q1->row();
            $t1 = 'SUMINISTRO DE: '.$r1->suministro;
        }
        
    
        if((int)$idprograma == 1000)
        {
            $t2 = 'DEL PROGRAMA DE: TODO';
        }else{
            $s2 = "select * from programa where idprograma = $idprograma";
            $q2 = $this->db->query($s2);
            $r2 = $q2->row();
            $t2 = 'DEL PROGRAMA: '.$r2->programa;
        }
        
        $tituloss = 'REPORTE DEL '.$t1.'  '.$t2.'. EN EL PERIODO DEL: '.$fecha1.' AL '.$fecha2.'.';
        return $tituloss;
        
         
    }
    
    function getTitulos2($fecha1,$fecha2,$clave,$idprograma)
    {
        if((int)$idprograma == 1000)
        {
            $t2 = 'DEL PROGRAMA DE: TODO';
        }else{
            $s2 = "select * from programa where idprograma = $idprograma";
            $q2 = $this->db->query($s2);
            $r2 = $q2->row();
            $t2 = 'DEL PROGRAMA: '.$r2->programa;
        }
        
        $s3 = $this->getCompletoByCvearticulo($clave);
        $t3 = 'DEL ARTICULO: '.$clave.' - '.$s3;
        
        $tituloss = 'REPORTE DE MEDICAMENTO Y MATERIAL DE CURACION '.$t2.' '.$t3.'. EN EL PERIODO DEL: '.$fecha1.' AL '.$fecha2.'.';
        return $tituloss;
        
         
    }
    
    function getTitulos22($fecha1, $fecha2, $sucursal, $clave, $idprograma, $juris, $tipo_sucursal)
    {
        
          if($sucursal == 0)
        {
            if($juris == 0)
            {
                $t1 = ' EN GENERAL';  
            }else{
                $s1 = "select * from jurisdiccion where numjurisd = $jur";
                $q1 = $this->db->query($s1);
                $r1 = $q1->row();
                $t1 = 'DENTRO DE LA JURISDICCION DE: '.$r1->jurisdiccion;
            }
        }else{
                $s1 = "select * from sucursales where clvsucursal = $sucursal";
                $q1 = $this->db->query($s1);
                $r1 = $q1->row();
                $t1 = 'DENTRO DE LA SUCURSAL DE: '.$r1->descsucursal;
        } 
        
        
         if((int)$idprograma == 1000)
        {
            $t2 = '';
        }else{
            $s2 = "select * from programa where idprograma = $idprograma";
            $q2 = $this->db->query($s2);
            $r2 = $q2->row();
            $t2 = 'DEL PROGRAMA: '.$r2->programa;
        }
        
        
        if($tipo_sucursal == 0)
        {
            $t3 = '';
        }else{
            $s3 = "select * from temporal_nivel_atencion where nivelatencion = $tipo_sucursal";
            $q3 = $this->db->query($s3);
            $r3 = $q3->row();
            $t3 = ' CON TIPO DE SUCURSAL DE: '. $r3->tipo_sucursal;
        }
        
        
        $s4 = $this->getCompletoByCvearticulo($clave);
        $t4 = 'DEL ARTICULO: '.$clave.' - '.$s4;
        
        $tituloss = 'REPORTE DE MEDICAMENTO Y MATERIAL DE CURACION '.$t1.' '.$t2.' '.$t3.' '.$t4.'. EN EL PERIODO DEL: '.$fecha1.' AL '.$fecha2.'.';
        return $tituloss;
        
          
    }
    
    
    function getTitulos3($fecha1,$fecha2,$suministro)
    {
       $suministro = (int) $suministro;
        if($suministro == 1000)
        {
            $t1 = 'SUMINISTRO DE:  TODO';
        }else{
            $s1 = "select * from temporal_suministro where cvesuministro = $suministro";
            $q1 = $this->db->query($s1);
            $r1 = $q1->row();
            $t1 = 'SUMINISTRO DE: '.$r1->suministro;
        }
        
        $tituloss = 'REPORTE '.$t1.'. EN EL PERIODO DEL: '.$fecha1.' AL '.$fecha2.'.';
        return $tituloss;
        
         
    }
    
    function getTitulos33($fecha1, $fecha2, $sucursal, $suministro, $juris)
    {
       $suministro = (int) $suministro;
        if($suministro == 1000)
        {
            $t1 = ' GENERAL DE TODOS LOS SUMINISTROS';
        }else{
            $s1 = "select * from temporal_suministro where cvesuministro = $suministro";
            $q1 = $this->db->query($s1);
            $r1 = $q1->row();
            $t1 = ' DEL SUMINISTRO DE: '.$r1->suministro;
        }
        
       if($sucursal == 0)
        {
            if($juris == 0)
            {
                $t2 = '';  
            }else{
                $s2 = "select * from jurisdiccion where numjurisd = $jur";
                $q2 = $this->db->query($s2);
                $r2 = $q2->row();
                $t2 = 'DENTRO DE LA JURISDICCION DE: '.$r2->jurisdiccion;
            }
        }else{
                $s2 = "select * from sucursales where clvsucursal = $sucursal";
                $q2 = $this->db->query($s2);
                $r2 = $q2->row();
                $t2 = 'DENTRO DE LA SUCURSAL DE: '.$r2->descsucursal;
        } 
        
        
      
        $tituloss = 'REPORTE '.$t1.' '.$t2.'. EN EL PERIODO DEL: '.$fecha1.' AL '.$fecha2.'.';
        return $tituloss;
        
    }
    
    
    function getTitulos4($fecha1,$fecha2,$idprograma,$tiporequerimiento,$cvesuministro)
    {
         if($idprograma == 1000){
            $t1 = 'DEL PROGRAMA DE: TODO ';
        }else{
            $s1 = "select * from programa where idprograma = $idprograma";
            $q1 = $this->db->query($s1);
            $r1 = $q1->row();
            $t1 = 'DEL PROGRAMA: '.$r1->programa;
        }
        
        if($tiporequerimiento == 1000){
            $t3 = 'DE TODOS LOS REQUERIMIENTOS';
        }else{
            $s3 = "select * from temporal_requerimiento where tiporequerimiento = $tiporequerimiento";
            $q3 = $this->db->query($s3);
            $r3 = $q3->row();
            $t3 = ' DEL REQUERIMIENTO: '.$r3->requerimiento;
        }

         $cvesuministro = (int) $cvesuministro;
        if($cvesuministro == 1000)
        {
            $t2 = 'SUMINISTRO DE:  TODO ';
        }else{
            $s2 = "select * from temporal_suministro where cvesuministro = $cvesuministro";
            $q2 = $this->db->query($s2);
            $r2 = $q2->row();
            $t2 = ' SUMINISTRO DE: '.$r2->suministro;
        }
        
        $tituloss = 'REPORTE DEL '.$t2.' '.$t1.' '.$t3.'. EN EL PERIODO DEL: '.$fecha1.' AL '.$fecha2.'.';
        return $tituloss;
    }
    
    
    
    
    function getTitulos5($fecha1, $fecha2, $idprograma, $tiporequerimiento, $sucursal, $juris)
    {
        if($idprograma == 1000){
            $t1 = 'DEL PROGRAMA DE: TODO';
        }else{
            $s1 = "select * from programa where idprograma = $idprograma";
            $q1 = $this->db->query($s1);
            $r1 = $q1->row();
            $t1 = 'DEL PROGRAMA: '.$r1->programa;
        }
        
        if($tiporequerimiento == 1000){
            $t3 = 'DE TODOS LOS REQUERIMIENTOS';
        }else{
            $s3 = "select * from temporal_requerimiento where tiporequerimiento = $tiporequerimiento";
            $q3 = $this->db->query($s3);
            $r3 = $q3->row();
            $t3 = ' DEL REQUERIMIENTO: '.$r3->requerimiento;
        }
        $jur = $this->session->userdata('jur');
          if($sucursal == 0)
        {
            if($juris == 0)
            {
                $t2 = '';  
            }else{
                $s2 = "select * from jurisdiccion where numjurisd = $jur";
                $q2 = $this->db->query($s2);
                $r2 = $q2->row();
                $t2 = 'DENTRO DE LA JURISDICCION DE: '.$r2->jurisdiccion;
            }
        }else{
                $s2 = "select * from sucursales where clvsucursal = $sucursal";
                $q2 = $this->db->query($s2);
                $r2 = $q2->row();
                $t2 = 'DENTRO DE LA SUCURSAL DE: '.$r2->descsucursal;
        } 
        
        $tituloss = 'REPORTE '.$t2.' '.$t1.' '.$t3.'. EN EL PERIODO DEL: '.$fecha1.' AL '.$fecha2.'.';
        return $tituloss;  
    }
    
    
    function getTitulos6($fecha1,$fecha2,$causes)
    {

        if($causes == 0){
           $t1 = ' DE LOS NO CAUSES' ;
        }else{
           $t1 = ' DE LOS CAUSES' ; 
        }
        $tituloss = 'REPORTE '.$t1.'. EN EL PERIODO DEL: '.$fecha1.' AL '.$fecha2.'.';
        return $tituloss;
        
    }
    
 
    
    
    

function getCompletoByCvearticulo($cveArticulo)
    {
        $sql = "SELECT concat(susa,' ',descripcion,' ',pres) as completo FROM articulos WHERE cvearticulo = $cveArticulo ";

        $query = $this->db->query($sql);
        
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->completo;
            
        }else{
            return null;
        }
    }
    
    function getByClave($fecha1, $fecha2, $sucursal, $clave, $idprograma)
    {
        
        if((int)$idprograma == 1000)
        {
            $programa = null;
        }else{
            $programa = "and idprograma = '$idprograma'";
        }
        
        $sql = "select 
        fecha, folioreceta, programa, cvepaciente, 
        concat(nombre,' ',apaterno,' ',amaterno) as paciente, 
        cvemedico, nombremedico, canreq, cansur 
        from receta r join receta_detalle d on d.consecutivo = r.consecutivo
        join programa p using(idprograma)
        where clvsucursal = ? and fecha between ? and ? and cvearticulo = ? $programa
        order by fecha";
        
        $query = $this->db->query($sql, array($sucursal, $fecha1, $fecha2, $clave));
        return $query;
    }
    
    
    function getByClaveByAll($fecha1, $fecha2, $sucursal, $clave, $idprograma, $juris, $tipo_sucursal)
    {

        if($sucursal== 0)
        {
            if((int)$juris == 0)
            {
                $filtro = null;
            }else{
                $filtro = "and s.numjurisd = $juris";
            }
        }else{
            
            $filtro = "and r.clvsucursal = '$sucursal'";
            
        }
        
        if((int)$idprograma == 1000)
        {
            $programa = null;
        }else{
            $programa = "and r.idprograma = '$idprograma'";
        }
        
        if($tipo_sucursal == 0)
        {
            $set_nivel_atencion = null;
        }else{
            $set_nivel_atencion = "and s.tiposucursal = $tipo_sucursal";
        }

       $aa = $this->getTitulos22($fecha1, $fecha2, $sucursal, $clave, $idprograma, $juris, $tipo_sucursal);

        $sql = "select 
            r.clvsucursal, descsucursal, programa, fecha, tipoprod, precioven, folioreceta, 
            cvepaciente, concat(nombre,' ',apaterno,' ',amaterno) as paciente, cvemedico, 
            nombremedico, r.clvsucursal, descsucursal, canreq, cansur 
            from receta r join receta_detalle d on d.consecutivo = r.consecutivo
            join sucursales s on r.clvsucursal = s.clvsucursal $set_nivel_atencion
            join programa p using(idprograma) 
            join articulos a using(id)
            where fecha between ? and ? and cvearticulo = ? $filtro $programa 
            order by fecha";
            
        $query = $this->db->query($sql, array($fecha1, $fecha2, $clave));
        
        $this->insertaQuery($this->db->last_query(),$aa);
        
        return $query;
    }
    
    function getByClaveByAll_farmacia($fecha1, $fecha2, $clave, $idprograma)
    {

        if((int)$idprograma == 1000)
        {
            $programa = null;
        }else{
            $programa = "and r.idprograma = '$idprograma'";
        }


        $aa = $this->getTitulos2($fecha1,$fecha2,$clave,$idprograma);
        $sql = "SELECT 
            r.clvsucursal, descsucursal, programa, fecha, tipoprod, precioven, folioreceta, 
            cvepaciente, concat(nombre,' ',apaterno,' ',amaterno) as paciente, cvemedico, 
            nombremedico, r.clvsucursal, descsucursal, canreq, cansur 
            from receta r join receta_detalle d on d.consecutivo = r.consecutivo
            join sucursales s on r.clvsucursal = s.clvsucursal
            join programa p using(idprograma) 
            join articulos a using(id)
            where fecha between ? and ? and cvearticulo = ? and r.clvsucursal = ? $programa 
            order by fecha";
            
        $query = $this->db->query($sql, array($fecha1, $fecha2, $clave, (string)$this->session->userdata('clvsucursal')));
        
        $this->insertaQuery($this->db->last_query(),$aa);
        
        return $query;
    }

     function getPacienteByCvepacienteJur($cvepaciente)
    {
        $sql = "SELECT concat(nombre,' ',apaterno,' ',amaterno) as paciente 
        FROM paciente WHERE cvepaciente = '$cvepaciente' LIMIT 1;";

        $query = $this->db->query($sql);
        
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->paciente;
            
        }else{
            return null;
        }
    }
    
    function getByCvePacienteAll($cvepaciente, $fecha1, $fecha2, $sucursal, $suministro, $juris)
    {
        $suministro = (int) $suministro;
        if($suministro == 1000)
        {
            $tipo = null;
        }else{
            $tipo = " and  tipoprod = $suministro";
        }
        
        if($sucursal == 0){
            
            if($juris == 0)
            {
                $suc = null;
            }else{
                $suc = "and s.numjurisd = $juris";
            }
            
            
        }else{
            $suc = "and r.clvsucursal = '$sucursal'";
        }
        
        $aa = $this->getTitulos33($fecha1, $fecha2, $sucursal, $suministro, $juris);    
        $sql = "select programa, tipoprod, precioven, r.clvsucursal, descsucursal, 
        fecha, folioreceta, cvemedico, nombremedico, cvearticulo, concat(susa,' ',descripcion,' ',pres)
        as completo, canreq, cansur from receta r join receta_detalle d on d.consecutivo = r.consecutivo
        join articulos a using(id)
        join sucursales s on r.clvsucursal = s.clvsucursal
        join programa p using(idprograma)
        where fecha between ? and ? and cvepaciente = '$cvepaciente' $tipo $suc
        order by fecha, folioreceta;";
    
        $query = $this->db->query($sql, array($fecha1, $fecha2));

        $this->insertaQuery($this->db->last_query(),$aa);
        
        return $query;
    }
    
    function getByCvePacienteAll_farmacia($cvepaciente, $fecha1, $fecha2, $suministro)
    {
        $suministro = (int) $suministro;
        if($suministro == 1000)
        {
            $tipo = null;
        }else{
            $tipo = " and  tipoprod = $suministro";
        }
        
        $aa = $this->getTitulos3($fecha1,$fecha2,$suministro);   
        $sql = "SELECT programa, tipoprod, precioven, r.clvsucursal, descsucursal, 
        fecha, folioreceta, cvemedico, nombremedico, cvearticulo, concat(susa,' ',descripcion,' ',pres)
        as completo, canreq, cansur from receta r join receta_detalle d on d.consecutivo = r.consecutivo
        join articulos a using(id)
    join sucursales s on r.clvsucursal = s.clvsucursal
    join programa p using(idprograma)
    where fecha between ? and ? and cvepaciente = ? and r.clvsucursal = ? $tipo
    order by fecha, folioreceta;";
    
        $query = $this->db->query($sql, array($fecha1, $fecha2, $cvepaciente, (string)$this->session->userdata('clvsucursal')));

        $this->insertaQuery($this->db->last_query(),$aa);
        
        return $query;
    }

    function getNombreMedicoByCveMedicoJur($cvemedico)
    {
        $this->db->select('nombremedico');
        $this->db->where('cvemedico', $cvemedico);
        $this->db->limit(1);
        $query = $this->db->get('medico');
        
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->nombremedico;
            
        }else{
            return null;
        }
    }
    
    function getByCveMedicoAll($cvemedico, $fecha1, $fecha2, $sucursal, $suministro, $juris)
    {
        $suministro = (int) $suministro;
        if($suministro == 1000)
        {
            $tipo = null;
        }else{
            $tipo = " and  tipoprod = $suministro";
        }
        
        if($sucursal == 0)
        {
            if($juris == 0)
            {
                $suc = null;
            }else{
                $suc = "s.numjurisd = $juris";
            }
        }else{
            $suc = "and r.clvsucursal = '$sucursal'";
        }
        $aa = $this->getTitulos33($fecha1,$fecha2,$sucursal, $suministro, $juris); 
        $sql = "select programa, tipoprod, precioven, r.clvsucursal, descsucursal, 
        fecha, folioreceta, cvepaciente, concat(trim(apaterno),' ',trim(amaterno),' ',trim(nombre)) as paciente, 
        cvearticulo, concat(susa,' ',descripcion,' ',pres)as completo, canreq, cansur
    from receta r join receta_detalle d on d.consecutivo = r.consecutivo 
    join articulos a using(id) 
    join sucursales s on r.clvsucursal = s.clvsucursal
    join programa p using(idprograma)
    where fecha between ? and ? and cvemedico = ? $tipo $suc
    order by fecha, folioreceta;";
    
        $query = $this->db->query($sql, array($fecha1, $fecha2, $cvemedico));
       
        $this->insertaQuery($this->db->last_query(),$aa);
        
        return $query;
    }
    
    function getByCveMedicoAll_farmacia($cvemedico, $fecha1, $fecha2, $suministro)
    {
        $suministro = (int) $suministro;
        if($suministro == 1000)
        {
            $tipo = null;
        }else{
            $tipo = " and  tipoprod = $suministro";
        }
        
        $aa = $this->getTitulos3($fecha1,$fecha2,$suministro); 
        $sql = "SELECT programa, tipoprod, precioven, r.clvsucursal, descsucursal, 
        fecha, folioreceta, cvepaciente, concat(trim(apaterno),' ',trim(amaterno),' ',trim(nombre)) as paciente, 
        cvearticulo, concat(susa,' ',descripcion,' ',pres)as completo, canreq, cansur
    from receta r join receta_detalle d on d.consecutivo = r.consecutivo 
    join articulos a using(id) 
    join sucursales s on r.clvsucursal = s.clvsucursal
    join programa p using(idprograma)
    where fecha between ? and ? and r.clvsucursal = ? and cvemedico = ? $tipo
    order by fecha, folioreceta;";
    
        $query = $this->db->query($sql, array((string)$fecha1, (string)$fecha2, (string)$this->session->userdata('clvsucursal'), (string)$cvemedico));
       
        $this->insertaQuery($this->db->last_query(),$aa);
        
        return $query;
    }

    function recetas_periodo_detalleAll($fecha1, $fecha2, $idprograma, $tiporequerimiento, $cvesuministro, $nivelatencion, $sucursal, $juris)
    {
        
        if($idprograma == 1000){
            $pro = null;
        }else{
            $pro = "and r.idprograma = $idprograma";
        }
        
        if($tiporequerimiento == 1000){
            $req = null;
        }else{
            $req = "and r.tiporequerimiento = $tiporequerimiento";
        }
        $jur = $this->session->userdata('jur');
          if($sucursal == 0)
        {
            if($juris == 0)
            {
                $dato = null;  
            }else{
                $dato = "and s.numjurisd = $jur";
            }
            
        }else{
                $dato = "and r.clvsucursal = $sucursal";
        }
        /*
        if($cvesuministro == 100){
           $sumis = null;
        }else{
            $sumis = "and r.cvesuministro = $cvesuministro";
        }*/
        $aa = $this->getTitulos5($fecha1, $fecha2, $idprograma, $tiporequerimiento, $sucursal, $juris);
        $s = "SELECT descsucursal, precioven, tipoprod, programa, requerimiento, folioreceta, apaterno, amaterno, nombre, canreq,
             cvepaciente, cie103, cie104, cveservicio, x.cvearticulo, concat(x.susa,' ',x.descripcion,' ', x.pres) as descripcion, cansur, nombremedico, cvemedico,
            fecha, fechaexp
            from receta r
            join sucursales s on r.clvsucursal=s.clvsucursal
            join programa p on r.idprograma = p.idprograma
            join temporal_requerimiento q on r.tiporequerimiento = q.tiporequerimiento
            join receta_detalle d on d.consecutivo = r.consecutivo
            join articulos x on d.id=x.id
            where fecha between ? and ? $pro  $req $dato ";
        $query = $this->db->query($s, array($fecha1, $fecha2));
        $this->insertaQuery($this->db->last_query(),$aa);
        return $query;
        
    }
    
    function recetas_periodo_detalleAll_farmacia($fecha1, $fecha2, $idprograma, $tiporequerimiento, $cvesuministro)
    {
        
        if($idprograma == 1000){
            $pro = null;
        }else{
            $pro = "and r.idprograma = $idprograma";
        }
        
        if($tiporequerimiento == 1000){
            $req = null;
        }else{
            $req = "and r.tiporequerimiento = $tiporequerimiento";
        }
        /*
        if($cvesuministro == 1000){
           $sumis = null;
        }else{
            $sumis = "and r.cvesuministro = $cvesuministro";
        }*/
        $aa = $this->getTitulos4($fecha1,$fecha2,$idprograma,$tiporequerimiento,$cvesuministro);
        $s = "SELECT descsucursal, precioven, tipoprod, programa, requerimiento, folioreceta, apaterno, amaterno, nombre, canreq,
             cvepaciente, cie103, cie104, cveservicio, x.cvearticulo, concat(x.susa,' ',x.descripcion,' ', x.pres) as descripcion, cansur, nombremedico, cvemedico,
            fecha, fechaexp
            from receta r
            join sucursales s on r.clvsucursal=s.clvsucursal
            join programa p on r.idprograma = p.idprograma
            join temporal_requerimiento q on r.tiporequerimiento = q.tiporequerimiento
            join receta_detalle d on d.consecutivo = r.consecutivo
            join articulos x on d.id=x.id
            where fecha between ? and ? and r.clvsucursal = ? $pro $req";//$sumis
        $query = $this->db->query($s, array($fecha1, $fecha2, (int)$this->session->userdata('clvsucursal')));
        $this->insertaQuery($this->db->last_query(),$aa);
        return $query;
        
    }

    function getNivelAtencionCombo2()
    {
        $this->db->order_by('nivelatencion');
        $query = $this->db->get('temporal_nivel_atencion');
        
        $a = array();
        
        foreach($query->result() as $row)
        {
            
            $a[$row->nivelatencion] = utf8_encode($row->nivelatenciondescripcion);
            
        }
        
        return $a;
    }
    
    
    function getTiposSucursal()
    {
        $this->db->order_by('nivelatencion');
        $query = $this->db->get('temporal_nivel_atencion');
        
        $a = array('0' => 'TODOS');
        foreach($query->result() as $row)
        {
            $a[$row->nivelatencion] = $row->nivelatencion. ' - ' . trim($row->tipo_sucursal);
        }
        
        return $a;
    }
    
    function getArticuloByCveArticulo($term)
    {
        
        $term = strtoupper($term);
        $sql = "select trim(descripcion) as descripcion, trim(cvearticulo) as cvearticulo, 
        trim(susa) as susa from articulos where (cvearticulo like '%$term%' or 
            susa like '%$term%' or descripcion like '%$term%') limit 20;";
        
        $query = $this->db->query($sql);
        

        $a = array();
        
        if($query->num_rows() > 1)
        {
            $retorno = '[';
            
            foreach($query->result() as $row){
                
                
                $retorno .= '{"descripcion":"'.utf8_encode($row->descripcion).'","susa":"'.utf8_encode($row->susa).'","cveArticulo":"'.$row->cvearticulo.'","value":"'.$row->cvearticulo.'|'.utf8_encode($row->descripcion).'|'.utf8_encode($row->susa).'"},';
            }
            
            $retorno = substr($retorno, 0, -1);
            $retorno .= ']';
            
            return $retorno;
            
        }elseif($query->num_rows() == 1){
            
                $row = $query->row();
                $retorno = '[{"descripcion":"'.utf8_encode($row->descripcion).'","susa":"'.utf8_encode($row->susa).'","cveArticulo":"'.$row->cvearticulo.'","value":"'.$row->cvearticulo.'|'.utf8_encode($row->descripcion).'|'.utf8_encode($row->susa).'"}]';
                return $retorno;
            
        }else{
                $retorno = '[{"descripcion":"","susa":"","cveArticulo":"","value":"Sin resultados, o no esta asociada al programa de salud"}]';
                return $retorno;
        }
    }
    
    function getPacienteByExpediente($term)
    {
        $term = strtoupper($term);
        $sql = "select trim(cvepaciente) as cvepaciente, concat(nombre,' ',apaterno,' ',
            amaterno) as paciente 
        from paciente where cvepaciente like '%$term%' OR 
        nombre like '%$term%' limit 20;";
        
        $query = $this->db->query($sql, $this->session->userdata('clvsucursal'));
        


        $a = array();
        
        if($query->num_rows() > 1)
        {
            $retorno = '[';
            
            foreach($query->result() as $row){
                
                
                $retorno .= '{"cvepaciente":"'.utf8_encode($row->cvepaciente).'","paciente":"'.utf8_encode($row->paciente).'","value":"'.$row->cvepaciente.'|'.utf8_encode($row->paciente).'"},';
            }
            
            $retorno = substr($retorno, 0, -1);
            $retorno .= ']';
            
            return $retorno;
            
        }elseif($query->num_rows() == 1){
            
                $row = $query->row();
                $retorno = '[{"cvepaciente":"'.utf8_encode($row->cvepaciente).'","paciente":"'.utf8_encode($row->paciente).'","value":"'.$row->cvepaciente.'|'.utf8_encode($row->paciente).'"}]';
                return $retorno;
            
        }else{
                $retorno = '[{"cvepaciente":"","paciente":"","value":"Sin resultados."}]';
                return $retorno;
        }
        
    }
    
    function getMedicoByCveMedicoAll($term, $sucursal, $juris)
    {
        $term = strtoupper($term);
        
        if($sucursal == 0)
        {
            if($juris == 0)
            {
                $suc = null;
            }else{
                $suc = "and s.numjurisd = $juris";
            }
            
        }else{
            $suc = "and m.clvsucursal = '$sucursal'";
        }
        

        $sql = "select trim(cvemedico) as cvemedico, max(trim(nombremedico)) 
        as nombremedico from medico m where (cvemedico like '%$term%' 
        or nombremedico like '%$term%') $suc group by cvemedico limit 20;";

        $query = $this->db->query($sql);
            
        

        $a = array();
        
        if($query->num_rows() > 1)
        {
            $retorno = '[';
            
            foreach($query->result() as $row){
                
                
                $retorno .= '{"cvemedico":"'.utf8_encode($row->cvemedico).'","nombremedico":"'.utf8_encode($row->nombremedico).'","value":"'.$row->cvemedico.'|'.utf8_encode($row->nombremedico).'"},';
            }
            
            $retorno = substr($retorno, 0, -1);
            $retorno .= ']';
            
            return $retorno;
            
        }elseif($query->num_rows() == 1){
            
                $row = $query->row();
                $retorno = '[{"cvemedico":"'.utf8_encode($row->cvemedico).'","nombremedico":"'.utf8_encode($row->nombremedico).'","value":"'.$row->cvemedico.'|'.utf8_encode($row->nombremedico).'"}]';
                return $retorno;
            
        }else{
                $retorno = '[{"cvemedico":"","nombremedico":"","value":"Sin resultados."}]';
                return $retorno;
        }
        
        
        
        
        
    }
    
    
    function getQuery($reporte)
    {
        $this->db->where('usuario', $this->session->userdata('usuario'));
        $this->db->where('reporte', $reporte);
        
        $query = $this->db->get('temporal_query');
        
        if($query->num_rows() == 0){
            return null;
        }else{
            $row = $query->row();
            return $row->query;
        }
    }
    
    function getTitulo($reporte)
    {
        $this->db->where('usuario', $this->session->userdata('usuario'));
        $this->db->where('reporte', $reporte);
        
        $query = $this->db->get('temporal_query');
        
        if($query->num_rows() == 0){
            return null;
        }else{
            $row = $query->row();
            return $row->titulo;
        }
    }
    
    
    function executeQuery($reporte){
       $sql = $this->getQuery($reporte);
        if($sql == null){
            return null;
        }else{
            return $this->db->query($sql);
        } 
    }
    
    function executeTitulo($reporte){
       $sql = $this->getTitulo($reporte);
        if($sql == null){
            return null;
        }else{
            return $sql;
        } 
    }
    
    function rsu_surtidas($fecha1,$fecha2)
    {
        $aa = 'REPORTE DE RECETAS SURTIDAS POR UNIDAD EN EL PERIODO DE: '.$fecha1.' AL '.$fecha2;
        $sql = "SELECT clvsucursal, descsucursal, count(*) as cuenta
                FROM receta r
                join sucursales s using(clvsucursal)
                where fecha between ? and ?
                group by clvsucursal
                order by cuenta desc;";
        $q  = $this->db->query($sql, array((string)$fecha1, (string)$fecha2));
        $this->insertaQuery($this->db->last_query(),$aa);
        return $q;
    }

    function rsu_surtidas_farmacia($fecha1,$fecha2)
    {
        
        $aa = 'REPORTE DE RECETAS SURTIDAS POR UNIDAD EN EL PERIODO DE: '.$fecha1.' AL '.$fecha2;
        $sql = "SELECT clvsucursal, descsucursal, count(*) as cuenta
                FROM receta r
                join sucursales s using(clvsucursal)
                where fecha between ? and ? and r.clvsucursal = ?
                group by clvsucursal
                order by cuenta desc;";
        $q  = $this->db->query($sql, array((string)$fecha1, (string)$fecha2, (int)$this->session->userdata('clvsucursal')));
        $this->insertaQuery($this->db->last_query(),$aa);
        return $q;
    }

    function claves_causes($fecha1, $fecha2, $causes)
    {
        $aa = $this->getTitulos6($fecha1, $fecha2, $causes);
        $sql = "SELECT cvearticulo, susa, descripcion, pres, sum(cansur) as surtido FROM receta_detalle d
join receta r using(consecutivo)
join articulos a using(id)
where fecha between ? and ? and cause = ?
group by id
order by surtido desc;";
        $q  = $this->db->query($sql, array((string)$fecha1, (string)$fecha2, $causes));
        $this->insertaQuery($this->db->last_query(),$aa);
        return $q;
    }
    
    function claves_causes_farmacia($fecha1, $fecha2, $causes)
    {
        $aa = $this->getTitulos6($fecha1, $fecha2, $causes);
        $sql = "SELECT cvearticulo, susa, descripcion, pres, sum(cansur) as surtido FROM receta_detalle d
join receta r using(consecutivo)
join articulos a using(id)
where fecha between ? and ? and cause = ? and r.clvsucursal = ?
group by id
order by surtido desc;";
        $q  = $this->db->query($sql, array((string)$fecha1, (string)$fecha2, $causes, (int)$this->session->userdata('clvsucursal')));
        $this->insertaQuery($this->db->last_query(),$aa);
        return $q;
    }

    function claves_mayor_movimiento($fecha1, $fecha2)
    {
        $aa = 'REPORTE DE CLAVES EN MAYOR MOVIMIENTO EN EL PERIODO DE : '.$fecha1.' AL '.$fecha2;
        $sql = "SELECT cvearticulo, susa, descripcion, pres, sum(cansur) as surtido FROM receta_detalle d
join receta r using(consecutivo)
join articulos a using(id)
where fecha between ? and ?
group by id
order by surtido desc
limit 20;";
        $q  = $this->db->query($sql, array((string)$fecha1, (string)$fecha2));
        $this->insertaQuery($this->db->last_query(),$aa);
        return $q;
    }
    
    function claves_mayor_movimiento_farmacia($fecha1, $fecha2)
    {
        $aa = 'REPORTE DE CLAVES EN MAYOR MOVIMIENTO EN EL PERIODO DE : '.$fecha1.' AL '.$fecha2;
        $sql = "SELECT cvearticulo, susa, descripcion, pres, sum(cansur) as surtido FROM receta_detalle d
join receta r using(consecutivo)
join articulos a using(id)
where fecha between ? and ? and r.clvsucursal = ?
group by id
order by surtido desc
limit 20;";
        $q  = $this->db->query($sql, array((string)$fecha1, (string)$fecha2, (int)$this->session->userdata('clvsucursal')));
        $this->insertaQuery($this->db->last_query(),$aa);
        return $q;
    }

    function claves_menor_movimiento_farmacia($fecha1,$fecha2)
    {
        $aa = 'REPORTE DE CLAVES EN MENOR MOVIMIENTO EN EL PERIODO DE : '.$fecha1.' AL '.$fecha2;
        $sql = "SELECT cvearticulo, susa, descripcion, pres, sum(cansur) as surtido FROM receta_detalle d
join receta r using(consecutivo)
join articulos a using(id)
where fecha between ? and ? and r.clvsucursal = ?
group by id
order by surtido asc
limit 20;";
        $q  = $this->db->query($sql, array((string)$fecha1, (string)$fecha2, (int)$this->session->userdata('clvsucursal')));
        $this->insertaQuery($this->db->last_query(),$aa);
        return $q;
    }

    function claves_menor_movimiento($fecha1,$fecha2)
    {
        $aa = 'REPORTE DE CLAVES EN MENOR MOVIMIENTO EN EL PERIODO DE : '.$fecha1.' AL '.$fecha2;
        $sql = "SELECT cvearticulo, susa, descripcion, pres, sum(cansur) as surtido FROM receta_detalle d
join receta r using(consecutivo)
join articulos a using(id)
where fecha between ? and ?
group by id
order by surtido asc
limit 20;";
        $q  = $this->db->query($sql, array((string)$fecha1, (string)$fecha2));
        $this->insertaQuery($this->db->last_query(),$aa);
        return $q;
    }

    function getCausesCombo()
    {
        $arr = array('0' => 'NO CAUSES', '1' => 'CAUSES');
        return $arr;
    }

    function getInventarioGroupBySucursal()
    {

        $filtro = null;

        switch ($this->clvpuesto) {
            case 15:
                $filtro = " and clvsucursal = " . $this->session->userdata('clvsucursal');
                break;
            case 16:
                $filtro = " and numjurisd = " . $this->session->userdata('numjurisd');
                break;
            case 17:
            case 18:
                
                break;
            default:
               
        }

        $sql = "SELECT clvsucursal, descsucursal, sum(cantidad) as cantidad, sum(cantidad * precioven) as importe, sum(case when tipoprod = 1 then cantidad * precioven * 0.16 else 0 end) as iva_producto, sum(cantidad * servicio) as servicio, sum(cantidad * servicio * 0.16) as iva_servicio
FROM inventario i
join sucursales s using(clvsucursal)
join articulos a using(id)
where cantidad > 0 and activa = 1 $filtro
group by clvsucursal
order by clvsucursal;";
        
        $query = $this->db->query($sql);

        return $query;
    }

    function getInvetarioDetalleBySucursal($clvsucursal)
    {
        $sql = "SELECT clvsucursal, descsucursal, id, cvearticulo, susa, descripcion, pres, lote, caducidad, sum(cantidad) as cantidad, sum(cantidad * precioven) as importe, sum(case when tipoprod = 1 then cantidad * precioven * 0.16 else 0 end) as iva_producto, sum(cantidad * servicio) as servicio, sum(cantidad * servicio * 0.16) as iva_servicio

FROM inventario i
join sucursales s using(clvsucursal)
join articulos a using(id)
where cantidad > 0 and activa = 1 and clvsucursal = ?
group by id, lote
order by tipoprod, cvearticulo * 1;";

        $query = $this->db->query($sql, array($clvsucursal));

        return $query;

    }

    function getInventarioTotalByClave()
    {

        $filtro = null;

        switch ($this->clvpuesto) {
            case 15:
                $filtro = " and clvsucursal = " . $this->session->userdata('clvsucursal');
                break;
            case 16:
                $filtro = " and numjurisd = " . $this->session->userdata('numjurisd');
                break;
            case 17:
            case 18:
                
                break;
            default:
               
        }

        $sql = "SELECT id, cvearticulo, susa, descripcion, pres, sum(cantidad) as cantidad, sum(cantidad * precioven) as importe, sum(case when tipoprod = 1 then cantidad * precioven * 0.16 else 0 end) as iva_producto, sum(cantidad * servicio) as servicio, sum(cantidad * servicio * 0.16) as iva_servicio
FROM inventario i
join sucursales s using(clvsucursal)
join articulos a using(id)
where cantidad > 0 and activa = 1 $filtro
group by id
order by tipoprod, cvearticulo * 1;";

        $query = $this->db->query($sql);

        return $query;
    }
    
    
    
    
    
    function inv_excel(){
     set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
        
        $hoja = 0;
        
            
            $this->excel->createSheet($hoja);
            $this->excel->setActiveSheetIndex($hoja);
               
        $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
        $this->excel->getActiveSheet()->setTitle('INVENTARIO DE SUCURSALES');
             
        $query = $this->getInventarioGroupBySucursal();  
                
        $this->excel->getActiveSheet()->mergeCells('A1:I1');
        $this->excel->getActiveSheet()->mergeCells('A2:I2');
    
        $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A2', 'INVENTARIO DE SUCURSALES'.'   '.date('d-M-Y H:i:s'));
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);


                $num = 3;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, '# SUCURSAL');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'SUCURSAL');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'INVENTARIO');
                if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->setCellValue('E'.$num, 'IMPORTE');
                $this->excel->getActiveSheet()->setCellValue('F'.$num, 'IVA PRODUCTO');
                $this->excel->getActiveSheet()->setCellValue('G'.$num, 'SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('H'.$num, 'IVA SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('I'.$num, 'SUBTOTAL');
                }
                
                $i = 1;
                $cantidad = 0;
                $importe = 0;
                $iva_producto = 0;
                $servicio = 0;
                $iva_servicio =0;
                
                foreach($query->result()  as $row)
                {
                    $subtotal = $row->importe + $row->iva_producto + $row->servicio + $row->iva_servicio;
                    $num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row->clvsucursal);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row->descsucursal);
                    $this->excel->getActiveSheet()->setCellValue('D'.$num, $row->cantidad);
                    if($this->session->userdata('valuacion') == 1){
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row->importe);
                    $this->excel->getActiveSheet()->setCellValue('F'.$num, $row->iva_producto);
                    $this->excel->getActiveSheet()->setCellValue('G'.$num, $row->servicio);
                    $this->excel->getActiveSheet()->setCellValue('H'.$num, $row->iva_servicio);
                    $this->excel->getActiveSheet()->setCellValue('I'.$num, $subtotal);
                    }
                    
                    $i++;
                    
                    $cantidad = $cantidad + $row->cantidad;
                    $importe = $importe + $row->importe;
                    $iva_producto = $iva_producto + $row->iva_producto;
                    $servicio = $servicio + $row->servicio;
                    $iva_servicio = $iva_servicio + $row->iva_servicio;
                    
                }
                   $data_termina = $num;
                   
                    $subtotal_total = $importe + $iva_producto + $servicio + $iva_servicio;
                    $this->excel->getActiveSheet()->setCellValue('D'.($data_termina + 1), '=sum(D'.$data_empieza.':D'.$data_termina.')');
                    if($this->session->userdata('valuacion') == 1){
                    $this->excel->getActiveSheet()->setCellValue('E'.($data_termina + 1), '=sum(E'.$data_empieza.':E'.$data_termina.')');
                    $this->excel->getActiveSheet()->setCellValue('F'.($data_termina + 1), '=sum(F'.$data_empieza.':F'.$data_termina.')');
                    $this->excel->getActiveSheet()->setCellValue('G'.($data_termina + 1), '=sum(G'.$data_empieza.':G'.$data_termina.')');
                    $this->excel->getActiveSheet()->setCellValue('H'.($data_termina + 1), '=sum(H'.$data_empieza.':H'.$data_termina.')');
                    $this->excel->getActiveSheet()->setCellValue('I'.($data_termina + 1), '=sum(I'.$data_empieza.':I'.$data_termina.')');
                    } 
                
                $this->excel->getActiveSheet()->getStyle('D'.$data_empieza.':D'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('E'.$data_empieza.':E'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('F'.$data_empieza.':F'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('G'.$data_empieza.':G'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('H'.$data_empieza.':H'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('I'.$data_empieza.':I'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  

                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);     
                
                $this->excel->getActiveSheet()->getStyle('D'.$data_empieza.':I'.$data_termina)->getAlignment()->setWrapText(true);
                
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':I'.($data_termina + 1))->applyFromArray($styleArray);
                
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':I'.($data_termina + 1));
    
                $hoja++;   
    }
    
    
    
    function get_invdetalle_excel($clvsucursal){
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
        
        $hoja = 0;
        
            
                $this->excel->createSheet($hoja);
                $this->excel->setActiveSheetIndex($hoja);
               
                $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
                $this->excel->getActiveSheet()->setTitle('DETALLE DE INVENTARIO');
             
           $query = $this->getInvetarioDetalleBySucursal($clvsucursal);
            
           if($this->session->userdata('valuacion') == 1){
             $this->excel->getActiveSheet()->mergeCells('A1:I1');
             $this->excel->getActiveSheet()->mergeCells('A2:I2');
           }else{     
             $this->excel->getActiveSheet()->mergeCells('A1:N1');
             $this->excel->getActiveSheet()->mergeCells('A2:N2');
           }
                
    
        $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A2', 'INVENTARIO DETALLADO DE LA SUCURSAL: '.$clvsucursal);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);


                $num = 4;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '# SUCURSAL');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, 'SUCURSAL');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'ID');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'CLAVE');
                $this->excel->getActiveSheet()->setCellValue('E'.$num, 'SUSTANCIA ACTIVA');
                $this->excel->getActiveSheet()->setCellValue('F'.$num, 'DESCRIPCION');
                $this->excel->getActiveSheet()->setCellValue('G'.$num, 'PRESENTACION');
                $this->excel->getActiveSheet()->setCellValue('H'.$num, 'LOTE/CADUCIDAD');
                $this->excel->getActiveSheet()->setCellValue('I'.$num, 'INVENTARIO');
                if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->setCellValue('J'.$num, 'IMPORTE');
                $this->excel->getActiveSheet()->setCellValue('K'.$num, 'IVA PRODUCTO');
                $this->excel->getActiveSheet()->setCellValue('L'.$num, 'SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('M'.$num, 'IVA SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('N'.$num, 'SUBTOTAL');
                }
                
                $i = 1;
                $cantidad = 0;$importe = 0;$iva_producto = 0;$servicio = 0;$iva_servicio =0;
                foreach($query->result()  as $row)
                {
                    $subtotal = $row->importe + $row->iva_producto + $row->servicio + $row->iva_servicio;
                    $num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $row->clvsucursal);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row->descsucursal);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row->id);
                    $this->excel->getActiveSheet()->setCellValue('D'.$num, $row->cvearticulo);
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row->susa);
                    $this->excel->getActiveSheet()->setCellValue('F'.$num, $row->descripcion);
                    $this->excel->getActiveSheet()->setCellValue('G'.$num, $row->pres);
                    $this->excel->getActiveSheet()->setCellValue('H'.$num, $row->lote.' / '.$row->caducidad);
                    $this->excel->getActiveSheet()->setCellValue('I'.$num, $row->cantidad);
                    if($this->session->userdata('valuacion') == 1){
                    $this->excel->getActiveSheet()->setCellValue('J'.$num, $row->importe);
                    $this->excel->getActiveSheet()->setCellValue('K'.$num, $row->iva_producto);
                    $this->excel->getActiveSheet()->setCellValue('L'.$num, $row->servicio);
                    $this->excel->getActiveSheet()->setCellValue('M'.$num, $row->iva_servicio);
                    $this->excel->getActiveSheet()->setCellValue('N'.$num, $subtotal);
                    }
                    
                    $i++;
                    
                    $cantidad = $cantidad + $row->cantidad;
                    $importe = $importe + $row->importe;
                    $iva_producto = $iva_producto + $row->iva_producto;
                    $servicio = $servicio + $row->servicio;
                    $iva_servicio = $iva_servicio + $row->iva_servicio;
                    
                }
                    $data_termina = $num;
                    $subtotal_total = $importe + $iva_producto + $servicio + $iva_servicio;
                     $this->excel->getActiveSheet()->setCellValue('I'.($data_termina + 1), '=sum(I'.$data_empieza.':I'.$data_termina.')');
                    if($this->session->userdata('valuacion') == 1){
                     $this->excel->getActiveSheet()->setCellValue('J'.($data_termina + 1), '=sum(J'.$data_empieza.':J'.$data_termina.')');
                     $this->excel->getActiveSheet()->setCellValue('K'.($data_termina + 1), '=sum(K'.$data_empieza.':K'.$data_termina.')');
                     $this->excel->getActiveSheet()->setCellValue('L'.($data_termina + 1), '=sum(L'.$data_empieza.':L'.$data_termina.')');
                     $this->excel->getActiveSheet()->setCellValue('M'.($data_termina + 1), '=sum(M'.$data_empieza.':M'.$data_termina.')');
                     $this->excel->getActiveSheet()->setCellValue('N'.($data_termina + 1), '=sum(N'.$data_empieza.':N'.$data_termina.')');
                    }
                    
                $this->excel->getActiveSheet()->getStyle('I'.$data_empieza.':I'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('J'.$data_empieza.':J'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':K'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('L'.$data_empieza.':L'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('M'.$data_empieza.':M'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('N'.$data_empieza.':N'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  

                
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);                
                $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);    
                
                $this->excel->getActiveSheet()->getStyle('I'.$data_empieza.':N'.$data_termina)->getAlignment()->setWrapText(true);
                
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':N'.($data_termina + 1))->applyFromArray($styleArray);
                
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':N'.($data_termina + 1));
    
                $hoja++;  
    }
    
    
    
    
    function get_inv_total_excel(){
      
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
        
        $hoja = 0;
        
            
                $this->excel->createSheet($hoja);
                $this->excel->setActiveSheetIndex($hoja);
               
                $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
                $this->excel->getActiveSheet()->setTitle('INVENTARIO TOTAL');
             
           $query = $this->getInventarioTotalByClave();    
                
        $this->excel->getActiveSheet()->mergeCells('A1:I1');
        $this->excel->getActiveSheet()->mergeCells('A2:I2');
                
    
        $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A2', 'INVENTARIO TOTAL'.date('d/M/Y H:i:s'));
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVerticalHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);


                $num = 4;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, 'ID');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'CLAVE');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'SUSTANCIA ACTIVA');
                $this->excel->getActiveSheet()->setCellValue('E'.$num, 'DESCRIPCION');
                $this->excel->getActiveSheet()->setCellValue('F'.$num, 'PRESENTACION');
                $this->excel->getActiveSheet()->setCellValue('G'.$num, 'INVENTARIO');
                if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->setCellValue('H'.$num, 'IMPORTE');
                $this->excel->getActiveSheet()->setCellValue('I'.$num, 'IVA PRODUCTO');
                $this->excel->getActiveSheet()->setCellValue('J'.$num, 'SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('K'.$num, 'IVA SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('L'.$num, 'SUBTOTAL');
                }
                
                $i = 1;
                $cantidad = 0;$importe = 0;$iva_producto = 0;$servicio = 0;$iva_servicio =0;
                foreach($query->result()  as $row)
                {
                    $subtotal = $row->importe + $row->iva_producto + $row->servicio + $row->iva_servicio;
                    $num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row->id);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row->cvearticulo);
                    $this->excel->getActiveSheet()->setCellValue('D'.$num, $row->susa);
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row->descripcion);
                    $this->excel->getActiveSheet()->setCellValue('F'.$num, $row->pres);
                    $this->excel->getActiveSheet()->setCellValue('G'.$num, $row->cantidad);
                    if($this->session->userdata('valuacion') == 1){
                    $this->excel->getActiveSheet()->setCellValue('H'.$num, $row->importe);
                    $this->excel->getActiveSheet()->setCellValue('I'.$num, $row->iva_producto);
                    $this->excel->getActiveSheet()->setCellValue('J'.$num, $row->servicio);
                    $this->excel->getActiveSheet()->setCellValue('K'.$num, $row->iva_servicio);
                    $this->excel->getActiveSheet()->setCellValue('L'.$num, $subtotal);
                    }
                    
                    $i++;
                    
                    $cantidad = $cantidad + $row->cantidad;
                    $importe = $importe + $row->importe;
                    $iva_producto = $iva_producto + $row->iva_producto;
                    $servicio = $servicio + $row->servicio;
                    $iva_servicio = $iva_servicio + $row->iva_servicio;
                    
                }
                    $data_termina = $num;
                    $subtotal_total = $importe + $iva_producto + $servicio + $iva_servicio;
                     $this->excel->getActiveSheet()->setCellValue('G'.($data_termina + 1), '=sum(G'.$data_empieza.':G'.$data_termina.')');
                    if($this->session->userdata('valuacion') == 1){
                     $this->excel->getActiveSheet()->setCellValue('H'.($data_termina + 1), '=sum(H'.$data_empieza.':H'.$data_termina.')');
                     $this->excel->getActiveSheet()->setCellValue('I'.($data_termina + 1), '=sum(I'.$data_empieza.':I'.$data_termina.')');
                     $this->excel->getActiveSheet()->setCellValue('J'.($data_termina + 1), '=sum(J'.$data_empieza.':J'.$data_termina.')');
                     $this->excel->getActiveSheet()->setCellValue('K'.($data_termina + 1), '=sum(K'.$data_empieza.':K'.$data_termina.')');
                     $this->excel->getActiveSheet()->setCellValue('L'.($data_termina + 1), '=sum(L'.$data_empieza.':L'.$data_termina.')');
                    }
                    
                $this->excel->getActiveSheet()->getStyle('G'.$data_empieza.':G'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('H'.$data_empieza.':H'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('I'.$data_empieza.':I'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('J'.$data_empieza.':J'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':K'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
                $this->excel->getActiveSheet()->getStyle('L'.$data_empieza.':L'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  

                
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);                
                $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);     
                
                $this->excel->getActiveSheet()->getStyle('G'.$data_empieza.':L'.$data_termina)->getAlignment()->setWrapText(true);
                
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':L'.($data_termina + 1))->applyFromArray($styleArray);
                
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':L'.($data_termina + 1));
    
                $hoja++;
         }
         
         
 //////////////////////////////////////////////////////////////////////////////////////////////////
 
 function reportes_resumen_entradas($fecha1, $fecha2)
    {
        $sql = "SELECT (SELECT count(referencia) as folio FROM movimiento m where fechaCierre between '$fecha1' and '$fecha2'
                and tipoMovimiento = 1 and subtipoMovimiento in (1,3))as folios,
                count(distinct c.cvearticulo) as claves,sum(b.piezas) as piezas,count(distinct b.lote) as lotes,count(distinct d.razon) as proveedor
                FROM movimiento a
                join movimiento_detalle b on b.movimientoID = a.movimientoID
                join articulos c on c.id = b.id
                join proveedor d on d.proveedorID = a.proveedorID
                where a.fechaCierre between '$fecha1' and '$fecha2' and a.tipoMovimiento = 1 and a.subtipoMovimiento in (1,3);";

        $query = $this->db->query($sql);
        
        return $query;
    }
    
    function reportes_resumen_entradas_detalle($fecha1, $fecha2)
    {
        $sql = "SELECT a.referencia,c.cvearticulo,b.piezas,c.susa,c.descripcion,c.pres,b.lote,d.razon
                FROM movimiento a
                join movimiento_detalle b on b.movimientoID = a.movimientoID
                join articulos c on c.id = b.id
                join proveedor d on d.proveedorID = a.proveedorID
                where a.fechaCierre between '$fecha1' and '$fecha2' and a.subtipoMovimiento in (1,3)
                order by a.referencia;";

        $query = $this->db->query($sql);
        
        return $query;
    }
    
    function entradas_por_clave($fecha1, $fecha2,$cvearticulo)
    {
        $sql = "SELECT count(distinct a.referencia) as facturas,c.cvearticulo,c.descripcion,count(distinct b.lote) as lotes,
                count(distinct b.caducidad) as caducidad,
                sum(b.piezas) as piezas,count(distinct d.razon) as proveedor
                FROM movimiento a
                join movimiento_detalle b on b.movimientoID = a.movimientoID
                join articulos c on c.id = b.id
                join proveedor d on d.proveedorID = a.proveedorID
                where a.fechaCierre between ? and ? and c.cvearticulo = ?
                and a.tipoMovimiento = 1 and a.subtipoMovimiento in (1,3);";

        $query = $this->db->query($sql, array($fecha1, $fecha2,$cvearticulo));
        
        return $query;
    }
        
    function entradas_por_clave_detalle($fecha1, $fecha2,$cvearticulo)
    {
        $sql = "SELECT a.referencia,c.cvearticulo,c.descripcion,b.lote,b.caducidad,b.piezas,d.razon FROM movimiento a
                join movimiento_detalle b on b.movimientoID = a.movimientoID
                join articulos c on c.id = b.id
                join proveedor d on d.proveedorID = a.proveedorID
                where a.fechaCierre between '$fecha1' and '$fecha2' and c.cvearticulo = '$cvearticulo'
                and a.tipoMovimiento = 1 and a.subtipoMovimiento in (1,3)
                order by a.referencia;";

        $query = $this->db->query($sql);
        
        return $query;
    }  
    
    function reportes_resumen_salidas($fecha1, $fecha2)
    {
        $sql = "SELECT (SELECT count(referencia) as folio FROM movimiento m where fechaCierre between '$fecha1' and '$fecha2'
                and tipoMovimiento = 2 and subtipoMovimiento in (4,5,6,7,8,9,13))as folios,
                count(distinct c.cvearticulo) as claves,sum(b.piezas) as piezas,count(distinct b.lote) as lotes,
                count(distinct a.clvsucursalReferencia) as sucdestino
                FROM movimiento a
                join movimiento_detalle b on b.movimientoID = a.movimientoID
                join articulos c on c.id = b.id
                join sucursales e on e.clvsucursal = a.clvsucursalReferencia
                where a.fechaCierre between '$fecha1' and '$fecha2'
                and a.tipoMovimiento = 2 and a.subtipoMovimiento in (4,5,6,7,8,9,13)
                order by a.referencia;";

        $query = $this->db->query($sql);
        
        return $query;
    }
    
    function reportes_resumen_salidas_detalle($fecha1, $fecha2)
    {
        $sql = "SELECT a.referencia,c.cvearticulo,b.piezas,c.susa,c.descripcion,c.pres,b.lote,a.clvsucursalReferencia,e.descsucursal
                FROM movimiento a
                join movimiento_detalle b on b.movimientoID = a.movimientoID
                join articulos c on c.id = b.id
                join proveedor d on d.proveedorID = a.proveedorID
                join sucursales e on e.clvsucursal = a.clvsucursalReferencia
                where a.fechaCierre between '$fecha1' and '$fecha2' and tipoMovimiento = 2 
                and a.subtipoMovimiento in (4,5,6,7,8,9,13)
                order by a.referencia;";

        $query = $this->db->query($sql);
        
        return $query;
    }
    
    function salidas_por_clave($fecha1, $fecha2,$cvearticulo)
    {
        $sql = "SELECT count(distinct a.referencia) as facturas,c.cvearticulo,c.descripcion,count(distinct b.lote) as lotes,
                count(distinct b.caducidad) as caducidad,
                sum(b.piezas) as piezas,count(distinct a.clvsucursalReferencia) as Destino
                FROM movimiento a
                join movimiento_detalle b on b.movimientoID = a.movimientoID
                join articulos c on c.id = b.id
                join proveedor d on d.proveedorID = a.proveedorID
                join sucursales e on e.clvsucursal = a.clvsucursalReferencia
                where a.fechaCierre between '$fecha1' and '$fecha2' and c.cvearticulo = '$cvearticulo'
                and a.tipoMovimiento =  2 and a.subtipoMovimiento in (4,5,6,7,8,9,13);";

        $query = $this->db->query($sql);
        
        return $query;
    }
        
    function salidas_por_clave_detalle($fecha1, $fecha2,$cvearticulo)
    {
        $sql = "SELECT a.referencia,c.cvearticulo,c.descripcion,b.lote,b.caducidad,b.piezas,a.clvsucursalReferencia,e.descsucursal
                FROM movimiento a
                join movimiento_detalle b on b.movimientoID = a.movimientoID
                join articulos c on c.id = b.id
                join proveedor d on d.proveedorID = a.proveedorID
                join sucursales e on e.clvsucursal = a.clvsucursalReferencia
                where a.fechaCierre between '$fecha1' and '$fecha2' and c.cvearticulo = '$cvearticulo'
                and a.tipoMovimiento =  2 and a.subtipoMovimiento in (4,5,6,7,8,9,13)
                order by a.referencia;";

        $query = $this->db->query($sql);
        
        return $query;
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function getPrograma2Excel($reporte){
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
                
        $hoja = 0;
            
                $this->excel->createSheet($hoja);
                $this->excel->setActiveSheetIndex($hoja);
                
                
                $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
                $this->excel->getActiveSheet()->setTitle('MEDICAMENTO Y MATERIAL');
              
                $query4 = $this->reportes_model->executeQuery($reporte);
                $titulo = $this->reportes_model->executeTitulo($reporte);
                $succ = $this->session->userdata('clvsucursal');
                $s = "select * from sucursales where clvsucursal = $succ";
                $q = $this->db->query($s);
                $r = $q->row();
                $sucx = $r->descsucursal;
                
                if($this->session->userdata('valuacion') == 1){
                   $this->excel->getActiveSheet()->mergeCells('A1:K1');
                   $this->excel->getActiveSheet()->mergeCells('A2:K2');
                   $this->excel->getActiveSheet()->mergeCells('A3:K3');  
                }else{
                   $this->excel->getActiveSheet()->mergeCells('A1:E1');
                   $this->excel->getActiveSheet()->mergeCells('A2:E2');
                   $this->excel->getActiveSheet()->mergeCells('A3:E3');
                }
    
                $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->setCellValue('A2', $titulo);
               	$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                if($this->session->userdata('valuacion') == 1){
                }else{
                $this->excel->getActiveSheet()->setCellValue('A3', 'SUCURSAL:  '.$succ.' - '.$sucx);
				$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                }


                $num = 5;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, 'CLAVE');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'DESCRIPCION');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'CANT REQ.');
                $this->excel->getActiveSheet()->setCellValue('E'.$num, 'CANT SUR.');
				if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->setCellValue('F'.$num, 'PRECIO UNITARIO');
                $this->excel->getActiveSheet()->setCellValue('G'.$num, 'IMPORTE');
                $this->excel->getActiveSheet()->setCellValue('H'.$num, 'IVA');
                $this->excel->getActiveSheet()->setCellValue('I'.$num, 'SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('J'.$num, 'IVA SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('K'.$num, 'SUBTOTAL');
                }
                
				
				$i = 1; 
				$req = 0;
				$sur = 0;
				$tImporte = 0;
				$tIVA = 0;
				$tServicio = 0;
				$tServicioIVA = 0;
				$total = 0;     
                foreach($query4->result()  as $row4)
                {
                    $piezas = $row4->surtida;                
					$importe = $row4->precioven * $piezas; 
					 if((int)$row4->tipoprod == 1){
						$iva = $row4->precioven * $piezas * .16;
                     }else{
						$iva = 0;
                    }
					$servicio = $piezas * SERVICIO;
					$servicio_iva = $servicio * IVA;
					$subtotal = $importe + $iva + $servicio + $servicio_iva;
					$tImporte = $tImporte + $importe;
					$tIVA = $tIVA + $iva;
					$tServicio = $tServicio + $servicio;
					$tServicioIVA = $tServicioIVA + $servicio_iva;
					$total = $total + $subtotal;    
					
					$num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row4->cvearticulo);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row4->completo);
                    $this->excel->getActiveSheet()->setCellValue('D'.$num, $row4->requerida);
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row4->surtida);
					 if($this->session->userdata('valuacion') == 1){
                    $this->excel->getActiveSheet()->setCellValue('F'.$num, $row4->precioven);
                    $this->excel->getActiveSheet()->setCellValue('G'.$num, $importe);
                    $this->excel->getActiveSheet()->setCellValue('H'.$num, $iva);
                    $this->excel->getActiveSheet()->setCellValue('I'.$num, $servicio);
                    $this->excel->getActiveSheet()->setCellValue('J'.$num, $servicio_iva);
                    $this->excel->getActiveSheet()->setCellValue('K'.$num, $subtotal);
					 }
                    $i++;
                    
                }
                
                $data_termina = $num;

                $this->excel->getActiveSheet()->setCellValue('D'.($data_termina + 1), '=sum(D'.$data_empieza.':D'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('E'.($data_termina + 1), '=sum(E'.$data_empieza.':E'.$data_termina.')');
				if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->setCellValue('F'.($data_termina + 1), '=sum(F'.$data_empieza.':F'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('G'.($data_termina + 1), '=sum(G'.$data_empieza.':G'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('H'.($data_termina + 1), '=sum(H'.$data_empieza.':H'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('I'.($data_termina + 1), '=sum(I'.$data_empieza.':I'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('J'.($data_termina + 1), '=sum(J'.$data_empieza.':J'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('K'.($data_termina + 1), '=sum(K'.$data_empieza.':K'.$data_termina.')');
                }
                
                $this->excel->getActiveSheet()->getStyle('D'.$data_empieza.':D'.$data_termina)->getNumberFormat()->setFormatCode('0');
                $this->excel->getActiveSheet()->getStyle('E'.$data_empieza.':E'.$data_termina)->getNumberFormat()->setFormatCode('0');
				if($this->session->userdata('valuacion') == 1){
				//$this->excel->getActiveSheet()->getStyle('N'.$data_empieza.':N'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
                $this->excel->getActiveSheet()->getStyle('F'.$data_empieza.':F'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('G'.$data_empieza.':G'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$this->excel->getActiveSheet()->getStyle('H'.$data_empieza.':H'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('I'.$data_empieza.':I'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$this->excel->getActiveSheet()->getStyle('J'.$data_empieza.':J'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':K'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 }
 
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':M'.$data_termina)->getAlignment()->setWrapText(true);
                }
                
                
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':K'.($data_termina + 1))->applyFromArray($styleArray);
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':K'.($data_termina + 1));
                }else{
                 $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':E'.($data_termina + 1))->applyFromArray($styleArray);
                 $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                 $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':E'.($data_termina + 1));  
                }
                $hoja++;  
    } 
    
    
    
    function getClaveExcel($reporte){
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
        
        $hoja = 0;
            
                $this->excel->createSheet($hoja);
                $this->excel->setActiveSheetIndex($hoja);
                
                
                $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
                $this->excel->getActiveSheet()->setTitle('MEDICAMENTO Y MATERIAL');
              
                $query4 = $this->reportes_model->executeQuery($reporte);
                $titulo = $this->reportes_model->executeTitulo($reporte);
                $succ = $this->session->userdata('clvsucursal');
                $s = "select * from sucursales where clvsucursal = $succ";
                $q = $this->db->query($s);
                $r = $q->row();
                $sucx = $r->descsucursal;
                
                  
                if($this->session->userdata('valuacion') == 1){
                   $this->excel->getActiveSheet()->mergeCells('A1:R1');
                   $this->excel->getActiveSheet()->mergeCells('A2:R2');
                   $this->excel->getActiveSheet()->mergeCells('A3:R3');  
                }else{
                   $this->excel->getActiveSheet()->mergeCells('A1:L1');
                   $this->excel->getActiveSheet()->mergeCells('A2:L2');
                   $this->excel->getActiveSheet()->mergeCells('A3:L3');
                }
                
                $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->setCellValue('A2', $titulo);
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(10);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                if($this->session->userdata('valuacion') == 1){
                }else{
                $this->excel->getActiveSheet()->setCellValue('A3', 'SUCURSAL: '.$succ.' - '.$sucx);
				$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                }


                $num = 5;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, '# SUC.');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'SUCURSAL');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'FECHA');
                $this->excel->getActiveSheet()->setCellValue('E'.$num, 'PROGRAMA');
                $this->excel->getActiveSheet()->setCellValue('F'.$num, 'FOLIO');
                $this->excel->getActiveSheet()->setCellValue('G'.$num, 'CLAVE PACIENTE');
                $this->excel->getActiveSheet()->setCellValue('H'.$num, 'PACIENTE');
                $this->excel->getActiveSheet()->setCellValue('I'.$num, 'CLAVE MEDICO');
                $this->excel->getActiveSheet()->setCellValue('J'.$num, 'MEDICO');
                $this->excel->getActiveSheet()->setCellValue('K'.$num, 'REQUERIDAS');
				$this->excel->getActiveSheet()->setCellValue('L'.$num, 'SURTIDAS');
				if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->setCellValue('M'.$num, 'PRECIO UNITARIO');
                $this->excel->getActiveSheet()->setCellValue('N'.$num, 'IMPORTE');
                $this->excel->getActiveSheet()->setCellValue('O'.$num, 'IVA');
                $this->excel->getActiveSheet()->setCellValue('P'.$num, 'SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('Q'.$num, 'IVA SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('R'.$num, 'SUBTOTAL');
                }
                
				
				$i = 1; 
				$req = 0;
				$sur = 0;
				$tImporte = 0;
				$tIVA = 0;
				$tServicio = 0;
				$tServicioIVA = 0;
				$total = 0;     
                foreach($query4->result()  as $row4)
                {
                    $piezas = $row4->cansur;                
					$importe = $row4->precioven * $piezas; 
					 if((int)$row4->tipoprod == 1){
						$iva = $row4->precioven * $piezas * .16;
                     }else{
						$iva = 0;
                    }
					$servicio = $piezas * SERVICIO;
					$servicio_iva = $servicio * IVA;
					$subtotal = $importe + $iva + $servicio + $servicio_iva;
					$tImporte = $tImporte + $importe;
					$tIVA = $tIVA + $iva;
					$tServicio = $tServicio + $servicio;
					$tServicioIVA = $tServicioIVA + $servicio_iva;
					$total = $total + $subtotal;    
					
					$num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row4->clvsucursal);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row4->descsucursal);
                    $this->excel->getActiveSheet()->setCellValue('D'.$num, $row4->fecha);
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row4->programa);
					$this->excel->getActiveSheet()->setCellValue('F'.$num, $row4->folioreceta);
                    $this->excel->getActiveSheet()->setCellValue('G'.$num, $row4->cvepaciente);
                    $this->excel->getActiveSheet()->setCellValue('H'.$num, $row4->paciente);
                    $this->excel->getActiveSheet()->setCellValue('I'.$num, $row4->cvemedico);
                    $this->excel->getActiveSheet()->setCellValue('J'.$num, $row4->nombremedico);
                    $this->excel->getActiveSheet()->setCellValue('K'.$num, $row4->canreq);
					$this->excel->getActiveSheet()->setCellValue('L'.$num, $row4->cansur);
					 if($this->session->userdata('valuacion') == 1){
					$this->excel->getActiveSheet()->setCellValue('M'.$num, $row4->precioven);
                    $this->excel->getActiveSheet()->setCellValue('N'.$num, $importe);
                    $this->excel->getActiveSheet()->setCellValue('O'.$num, $iva);
                    $this->excel->getActiveSheet()->setCellValue('P'.$num, $servicio);
                    $this->excel->getActiveSheet()->setCellValue('Q'.$num, $servicio_iva);
                    $this->excel->getActiveSheet()->setCellValue('R'.$num, $subtotal);
					 }
                    $i++;
                    
                }
                
                $data_termina = $num;

                $this->excel->getActiveSheet()->setCellValue('K'.($data_termina + 1), '=sum(K'.$data_empieza.':K'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('L'.($data_termina + 1), '=sum(L'.$data_empieza.':L'.$data_termina.')');
				if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->setCellValue('M'.($data_termina + 1), '=sum(M'.$data_empieza.':M'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('N'.($data_termina + 1), '=sum(N'.$data_empieza.':N'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('O'.($data_termina + 1), '=sum(O'.$data_empieza.':O'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('P'.($data_termina + 1), '=sum(P'.$data_empieza.':P'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('Q'.($data_termina + 1), '=sum(Q'.$data_empieza.':Q'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('R'.($data_termina + 1), '=sum(R'.$data_empieza.':R'.$data_termina.')');
                }
                
                $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':K'.$data_termina)->getNumberFormat()->setFormatCode('0');
                $this->excel->getActiveSheet()->getStyle('L'.$data_empieza.':L'.$data_termina)->getNumberFormat()->setFormatCode('0');
				if($this->session->userdata('valuacion') == 1){
			    $this->excel->getActiveSheet()->getStyle('M'.$data_empieza.':M'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('N'.$data_empieza.':N'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$this->excel->getActiveSheet()->getStyle('O'.$data_empieza.':O'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('P'.$data_empieza.':P'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$this->excel->getActiveSheet()->getStyle('Q'.$data_empieza.':Q'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('R'.$data_empieza.':R'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 }
 
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
                $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':R'.$data_termina)->getAlignment()->setWrapText(true);
                }
                
                
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':R'.($data_termina + 1))->applyFromArray($styleArray);
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':R'.($data_termina + 1));
                }else{
                 $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':L'.($data_termina + 1))->applyFromArray($styleArray);
                 $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                 $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':L'.($data_termina + 1));  
                }
                $hoja++;  
    }  
    
    
    function getPacienteExcel($expediente,$reporte){
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
        
        $hoja = 0;
            
                $this->excel->createSheet($hoja);
                $this->excel->setActiveSheetIndex($hoja);
                
                
                $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
                $this->excel->getActiveSheet()->setTitle('SUMINISTRO');
              
                $query4 = $this->reportes_model->executeQuery($reporte);
                $titulo = $this->reportes_model->executeTitulo($reporte);
                $succ = $this->session->userdata('clvsucursal');
                $s = "select * from sucursales where clvsucursal = $succ";
                $q = $this->db->query($s);
                $r = $q->row();
                $sucx = $r->descsucursal;
                
                $paciente = $this->getPacienteByCvepacienteJur($expediente);
             
                
                if($this->session->userdata('valuacion') == 1){
                   $this->excel->getActiveSheet()->mergeCells('A1:R1');
                   $this->excel->getActiveSheet()->mergeCells('A2:R2');
                   $this->excel->getActiveSheet()->mergeCells('A3:R3'); 
                   $this->excel->getActiveSheet()->mergeCells('A4:R4'); 
                }else{
                   $this->excel->getActiveSheet()->mergeCells('A1:L1');
                   $this->excel->getActiveSheet()->mergeCells('A2:L2');
                   $this->excel->getActiveSheet()->mergeCells('A3:L3');
                   $this->excel->getActiveSheet()->mergeCells('A4:L4');
                }
                $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->setCellValue('A2', $titulo);
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                if($this->session->userdata('valuacion') == 1){
                }else{
                $this->excel->getActiveSheet()->setCellValue('A3', 'SUCURSAL: '.$succ.' - '.$sucx);
				$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                }
                $this->excel->getActiveSheet()->setCellValue('A4', 'PACIENTE: '.$expediente.' - '.$paciente);
				$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);


                $num = 5;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, '# SUC.');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'SUCURSAL');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'FECHA');
                $this->excel->getActiveSheet()->setCellValue('E'.$num, 'FOLIO');
                $this->excel->getActiveSheet()->setCellValue('F'.$num, 'PROGRAMA');
                $this->excel->getActiveSheet()->setCellValue('G'.$num, 'CLAVE MEDICO');
                $this->excel->getActiveSheet()->setCellValue('H'.$num, 'MEDICO');
                $this->excel->getActiveSheet()->setCellValue('I'.$num, 'CLAVE');
                $this->excel->getActiveSheet()->setCellValue('J'.$num, 'DESCRIPCION');
                $this->excel->getActiveSheet()->setCellValue('K'.$num, 'REQUERIDAS');
				$this->excel->getActiveSheet()->setCellValue('L'.$num, 'SURTIDAS');
				if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->setCellValue('M'.$num, 'PRECIO UNITARIO');
                $this->excel->getActiveSheet()->setCellValue('N'.$num, 'IMPORTE');
                $this->excel->getActiveSheet()->setCellValue('O'.$num, 'IVA');
                $this->excel->getActiveSheet()->setCellValue('P'.$num, 'SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('Q'.$num, 'IVA SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('R'.$num, 'SUBTOTAL');
                }
                
				
				$i = 1; 
				$req = 0;
				$sur = 0;
				$tImporte = 0;
				$tIVA = 0;
				$tServicio = 0;
				$tServicioIVA = 0;
				$total = 0;     
                foreach($query4->result()  as $row4)
                {
                    $piezas = $row4->cansur;                
					$importe = $row4->precioven * $piezas; 
					 if((int)$row4->tipoprod == 1){
						$iva = $row4->precioven * $piezas * .16;
                     }else{
						$iva = 0;
                    }
					$servicio = $piezas * SERVICIO;
					$servicio_iva = $servicio * IVA;
					$subtotal = $importe + $iva + $servicio + $servicio_iva;
					$tImporte = $tImporte + $importe;
					$tIVA = $tIVA + $iva;
					$tServicio = $tServicio + $servicio;
					$tServicioIVA = $tServicioIVA + $servicio_iva;
					$total = $total + $subtotal;    
					
					$num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row4->clvsucursal);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row4->descsucursal);
                    $this->excel->getActiveSheet()->setCellValue('D'.$num, $row4->fecha);
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row4->folioreceta);
					$this->excel->getActiveSheet()->setCellValue('F'.$num, $row4->programa);
                    $this->excel->getActiveSheet()->setCellValue('G'.$num, $row4->cvemedico);
                    $this->excel->getActiveSheet()->setCellValue('H'.$num, $row4->nombremedico);
                    $this->excel->getActiveSheet()->setCellValue('I'.$num, $row4->cvearticulo);
                    $this->excel->getActiveSheet()->setCellValue('J'.$num, $row4->completo);
                    $this->excel->getActiveSheet()->setCellValue('K'.$num, $row4->canreq);
					$this->excel->getActiveSheet()->setCellValue('L'.$num, $row4->cansur);
					 if($this->session->userdata('valuacion') == 1){
					$this->excel->getActiveSheet()->setCellValue('M'.$num, $row4->precioven);
                    $this->excel->getActiveSheet()->setCellValue('N'.$num, $importe);
                    $this->excel->getActiveSheet()->setCellValue('O'.$num, $iva);
                    $this->excel->getActiveSheet()->setCellValue('P'.$num, $servicio);
                    $this->excel->getActiveSheet()->setCellValue('Q'.$num, $servicio_iva);
                    $this->excel->getActiveSheet()->setCellValue('R'.$num, $subtotal);
					 }
                    $i++;
                    
                }
                
                $data_termina = $num;

                $this->excel->getActiveSheet()->setCellValue('K'.($data_termina + 1), '=sum(K'.$data_empieza.':K'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('L'.($data_termina + 1), '=sum(L'.$data_empieza.':L'.$data_termina.')');
				if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->setCellValue('M'.($data_termina + 1), '=sum(M'.$data_empieza.':M'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('N'.($data_termina + 1), '=sum(N'.$data_empieza.':N'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('O'.($data_termina + 1), '=sum(O'.$data_empieza.':O'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('P'.($data_termina + 1), '=sum(P'.$data_empieza.':P'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('Q'.($data_termina + 1), '=sum(Q'.$data_empieza.':Q'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('R'.($data_termina + 1), '=sum(R'.$data_empieza.':R'.$data_termina.')');
                }
                
                $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':K'.$data_termina)->getNumberFormat()->setFormatCode('0');
                $this->excel->getActiveSheet()->getStyle('L'.$data_empieza.':L'.$data_termina)->getNumberFormat()->setFormatCode('0');
				if($this->session->userdata('valuacion') == 1){
				//$this->excel->getActiveSheet()->getStyle('M'.$data_empieza.':M'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
                $this->excel->getActiveSheet()->getStyle('M'.$data_empieza.':M'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('N'.$data_empieza.':N'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$this->excel->getActiveSheet()->getStyle('O'.$data_empieza.':O'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('P'.$data_empieza.':P'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$this->excel->getActiveSheet()->getStyle('Q'.$data_empieza.':Q'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('R'.$data_empieza.':R'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 }
 
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
                $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':R'.$data_termina)->getAlignment()->setWrapText(true);
                }
                
                
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':R'.($data_termina + 1))->applyFromArray($styleArray);
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':R'.($data_termina + 1));
                }else{
                 $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':L'.($data_termina + 1))->applyFromArray($styleArray);
                 $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                 $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':L'.($data_termina + 1));  
                }
                $hoja++;  
    }   
    
    function getMedicoExcel($cveMedico,$reporte){
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
        
        $hoja = 0;
            
                $this->excel->createSheet($hoja);
                $this->excel->setActiveSheetIndex($hoja);
                
                
                $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
                $this->excel->getActiveSheet()->setTitle('REPORTE DE MEDICO');
              
                $query4 = $this->reportes_model->executeQuery($reporte);
                $titulo = $this->reportes_model->executeTitulo($reporte);
                $succ = $this->session->userdata('clvsucursal');
                $s = "select * from sucursales where clvsucursal = $succ";
                $q = $this->db->query($s);
                $r = $q->row();
                $sucx = $r->descsucursal;
                $medico= $this->getNombreMedicoByCveMedicoJur($cveMedico);
                
                
                if($this->session->userdata('valuacion') == 1){
                   $this->excel->getActiveSheet()->mergeCells('A1:P1');
                   $this->excel->getActiveSheet()->mergeCells('A2:P2');
                   $this->excel->getActiveSheet()->mergeCells('A3:P3');  
                   $this->excel->getActiveSheet()->mergeCells('A4:P4'); 
                }else{
                   $this->excel->getActiveSheet()->mergeCells('A1:J1');
                   $this->excel->getActiveSheet()->mergeCells('A2:J2');
                   $this->excel->getActiveSheet()->mergeCells('A3:J3');
                   $this->excel->getActiveSheet()->mergeCells('A4:J4'); 
                }
                $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->setCellValue('A2', $titulo);
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                if($this->session->userdata('valuacion') == 1){
                }else{
                $this->excel->getActiveSheet()->setCellValue('A3', 'SUCURSAL: '.$succ.' - '.$sucx);
				$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                }
                $this->excel->getActiveSheet()->setCellValue('A4', 'MEDICO: '.$cveMedico.' - '.$medico);
				$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);


                $num = 5;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, 'FECHA');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'FOLIO');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'PROGRAMA');
                $this->excel->getActiveSheet()->setCellValue('E'.$num, 'CLAVE PACIENTE');
                $this->excel->getActiveSheet()->setCellValue('F'.$num, 'PACIENTE');
                $this->excel->getActiveSheet()->setCellValue('G'.$num, 'CLAVE');
                $this->excel->getActiveSheet()->setCellValue('H'.$num, 'DESCRIPCION');
                $this->excel->getActiveSheet()->setCellValue('I'.$num, 'REQUERIDAS');
				$this->excel->getActiveSheet()->setCellValue('J'.$num, 'SURTIDAS');
				if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->setCellValue('K'.$num, 'PRECIO UNITARIO');
                $this->excel->getActiveSheet()->setCellValue('L'.$num, 'IMPORTE');
                $this->excel->getActiveSheet()->setCellValue('M'.$num, 'IVA');
                $this->excel->getActiveSheet()->setCellValue('N'.$num, 'SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('O'.$num, 'IVA SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('P'.$num, 'SUBTOTAL');
                }
                
				
				$i = 1; 
				$req = 0;
				$sur = 0;
				$tImporte = 0;
				$tIVA = 0;
				$tServicio = 0;
				$tServicioIVA = 0;
				$total = 0;     
                foreach($query4->result()  as $row4)
                {
                    $piezas = $row4->cansur;                
					$importe = $row4->precioven * $piezas; 
					 if((int)$row4->tipoprod == 1){
						$iva = $row4->precioven * $piezas * .16;
                     }else{
						$iva = 0;
                    }
					$servicio = $piezas * SERVICIO;
					$servicio_iva = $servicio * IVA;
					$subtotal = $importe + $iva + $servicio + $servicio_iva;
					$tImporte = $tImporte + $importe;
					$tIVA = $tIVA + $iva;
					$tServicio = $tServicio + $servicio;
					$tServicioIVA = $tServicioIVA + $servicio_iva;
					$total = $total + $subtotal;    
					
					$num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row4->fecha);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row4->folioreceta);
                    $this->excel->getActiveSheet()->setCellValue('D'.$num, $row4->programa);
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row4->cvepaciente);
					$this->excel->getActiveSheet()->setCellValue('F'.$num, $row4->paciente);
                    $this->excel->getActiveSheet()->setCellValue('G'.$num, $row4->cvearticulo);
                    $this->excel->getActiveSheet()->setCellValue('H'.$num, $row4->completo);
                    $this->excel->getActiveSheet()->setCellValue('I'.$num, $row4->canreq);
					$this->excel->getActiveSheet()->setCellValue('J'.$num, $row4->cansur);
					 if($this->session->userdata('valuacion') == 1){
					$this->excel->getActiveSheet()->setCellValue('K'.$num, $row4->precioven);
                    $this->excel->getActiveSheet()->setCellValue('L'.$num, $importe);
                    $this->excel->getActiveSheet()->setCellValue('M'.$num, $iva);
                    $this->excel->getActiveSheet()->setCellValue('N'.$num, $servicio);
                    $this->excel->getActiveSheet()->setCellValue('O'.$num, $servicio_iva);
                    $this->excel->getActiveSheet()->setCellValue('P'.$num, $subtotal);
					 }
                    $i++;
                    
                }
                
                $data_termina = $num;

                $this->excel->getActiveSheet()->setCellValue('I'.($data_termina + 1), '=sum(I'.$data_empieza.':I'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('J'.($data_termina + 1), '=sum(J'.$data_empieza.':J'.$data_termina.')');
				if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->setCellValue('K'.($data_termina + 1), '=sum(K'.$data_empieza.':K'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('L'.($data_termina + 1), '=sum(L'.$data_empieza.':L'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('M'.($data_termina + 1), '=sum(M'.$data_empieza.':M'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('N'.($data_termina + 1), '=sum(N'.$data_empieza.':N'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('O'.($data_termina + 1), '=sum(O'.$data_empieza.':O'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('P'.($data_termina + 1), '=sum(P'.$data_empieza.':P'.$data_termina.')');
                }
                
                $this->excel->getActiveSheet()->getStyle('I'.$data_empieza.':I'.$data_termina)->getNumberFormat()->setFormatCode('0');
                $this->excel->getActiveSheet()->getStyle('J'.$data_empieza.':J'.$data_termina)->getNumberFormat()->setFormatCode('0');
				if($this->session->userdata('valuacion') == 1){
				//$this->excel->getActiveSheet()->getStyle('M'.$data_empieza.':K'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
                $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':K'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('L'.$data_empieza.':L'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$this->excel->getActiveSheet()->getStyle('M'.$data_empieza.':M'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('N'.$data_empieza.':N'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$this->excel->getActiveSheet()->getStyle('O'.$data_empieza.':O'.$data_termina)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('P'.$data_empieza.':P'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 }
 
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                if($this->session->userdata('valuacion') == 1){
				$this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);	
                $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                $this->excel->getActiveSheet()->getStyle('I'.$data_empieza.':P'.$data_termina)->getAlignment()->setWrapText(true);
                }
                
                
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':P'.($data_termina + 1))->applyFromArray($styleArray);
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':P'.($data_termina + 1));
                }else{
                 $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':J'.($data_termina + 1))->applyFromArray($styleArray);
                 $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                 $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':J'.($data_termina + 1));  
                }
                $hoja++;  
    }
    
    
    function getPeriodoExcel($reporte){
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
        
        $hoja = 0;
            
                $this->excel->createSheet($hoja);
                $this->excel->setActiveSheetIndex($hoja);
                
                
                $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
                $this->excel->getActiveSheet()->setTitle('MEDICAMENTO Y MATERIAL');
              
                $query4 = $this->reportes_model->executeQuery($reporte);
                $titulo = $this->reportes_model->executeTitulo($reporte);
                $succ = $this->session->userdata('clvsucursal');
                $s = "select * from sucursales where clvsucursal = $succ";
                $q = $this->db->query($s);
                $r = $q->row();
                $sucx = $r->descsucursal;
                
                if($this->session->userdata('valuacion') == 1){
                   $this->excel->getActiveSheet()->mergeCells('A1:W1');
                   $this->excel->getActiveSheet()->mergeCells('A2:W2');
                   $this->excel->getActiveSheet()->mergeCells('A3:W3');  
                }else{
                   $this->excel->getActiveSheet()->mergeCells('A1:Q1');
                   $this->excel->getActiveSheet()->mergeCells('A2:Q2');
                   $this->excel->getActiveSheet()->mergeCells('A3:Q3');
                }
                $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->setCellValue('A2', $titulo);
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                if($this->session->userdata('valuacion') == 1){
                }else{
                $this->excel->getActiveSheet()->setCellValue('A3', 'SUCURSAL: '.$succ.' - '.$sucx);
				$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                }


                $num = 5;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, 'FOLIO RECETA');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'UNIDAD');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'CLAVE PACIENTE');
                $this->excel->getActiveSheet()->setCellValue('E'.$num, 'NOMBRE');
                $this->excel->getActiveSheet()->setCellValue('F'.$num, 'PATERNO');
                $this->excel->getActiveSheet()->setCellValue('G'.$num, 'MATERNO');
                $this->excel->getActiveSheet()->setCellValue('H'.$num, 'CLAVE ARTICULO');
                $this->excel->getActiveSheet()->setCellValue('I'.$num, 'DESCRIPCION');
				$this->excel->getActiveSheet()->setCellValue('J'.$num, 'CLAVE MEDICO');
				$this->excel->getActiveSheet()->setCellValue('K'.$num, 'NOMBRE MEDICO');
				$this->excel->getActiveSheet()->setCellValue('L'.$num, 'PROGRAMA');
                $this->excel->getActiveSheet()->setCellValue('M'.$num, 'REQUERIMIENTO');
                $this->excel->getActiveSheet()->setCellValue('N'.$num, 'FECHA CONSULTA');
                $this->excel->getActiveSheet()->setCellValue('O'.$num, 'FECHA SURTIDO');
                $this->excel->getActiveSheet()->setCellValue('P'.$num, 'CANT REQ.');
                $this->excel->getActiveSheet()->setCellValue('Q'.$num, 'CANT SUR.');
				if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->setCellValue('R'.$num, 'PRECIO UNITARIO');
                $this->excel->getActiveSheet()->setCellValue('S'.$num, 'IMPORTE');
                $this->excel->getActiveSheet()->setCellValue('T'.$num, 'IVA');
                $this->excel->getActiveSheet()->setCellValue('U'.$num, 'SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('V'.$num, 'IVA SERVICIO');
                $this->excel->getActiveSheet()->setCellValue('W'.$num, 'SUBTOTAL');
                }
                
				
				$i = 1; 
				$req = 0;
				$sur = 0;
				$tImporte = 0;
				$tIVA = 0;
				$tServicio = 0;
				$tServicioIVA = 0;
				$total = 0;     
                foreach($query4->result()  as $row4)
                {
                    $piezas = $row4->cansur;                
					$importe = $row4->precioven * $piezas; 
					 if((int)$row4->tipoprod == 1){
						$iva = $row4->precioven * $piezas * .16;
                     }else{
						$iva = 0;
                    }
					$servicio = $piezas * SERVICIO;
					$servicio_iva = $servicio * IVA;
					$subtotal = $importe + $iva + $servicio + $servicio_iva;
					$tImporte = $tImporte + $importe;
					$tIVA = $tIVA + $iva;
					$tServicio = $tServicio + $servicio;
					$tServicioIVA = $tServicioIVA + $servicio_iva;
					$total = $total + $subtotal;    
					
					$num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row4->folioreceta);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row4->descsucursal);
                    $this->excel->getActiveSheet()->setCellValue('D'.$num, $row4->cvepaciente);
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row4->nombre);
					$this->excel->getActiveSheet()->setCellValue('F'.$num, $row4->apaterno);
                    $this->excel->getActiveSheet()->setCellValue('G'.$num, $row4->amaterno);
                    $this->excel->getActiveSheet()->setCellValue('H'.$num, $row4->cvearticulo);
                    $this->excel->getActiveSheet()->setCellValue('I'.$num, $row4->descripcion);
					$this->excel->getActiveSheet()->setCellValue('J'.$num, $row4->cvemedico);
					$this->excel->getActiveSheet()->setCellValue('K'.$num, $row4->nombremedico);
                    $this->excel->getActiveSheet()->setCellValue('L'.$num, $row4->programa);
                    $this->excel->getActiveSheet()->setCellValue('M'.$num, $row4->requerimiento);
                    $this->excel->getActiveSheet()->setCellValue('N'.$num, $row4->fecha);
                    $this->excel->getActiveSheet()->setCellValue('O'.$num, $row4->fechaexp);
                    $this->excel->getActiveSheet()->setCellValue('P'.$num, $row4->canreq);
					$this->excel->getActiveSheet()->setCellValue('Q'.$num, $row4->cansur);
					 if($this->session->userdata('valuacion') == 1){
					$this->excel->getActiveSheet()->setCellValue('R'.$num, $row4->precioven);
                    $this->excel->getActiveSheet()->setCellValue('S'.$num, $importe);
                    $this->excel->getActiveSheet()->setCellValue('T'.$num, $iva);
                    $this->excel->getActiveSheet()->setCellValue('U'.$num, $servicio);
                    $this->excel->getActiveSheet()->setCellValue('V'.$num, $servicio_iva);
                    $this->excel->getActiveSheet()->setCellValue('W'.$num, $subtotal);
					 }
                    $i++;
                    
                }
                
                $data_termina = $num;

                $this->excel->getActiveSheet()->setCellValue('P'.($data_termina + 1), '=sum(P'.$data_empieza.':P'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('Q'.($data_termina + 1), '=sum(Q'.$data_empieza.':Q'.$data_termina.')');
				if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->setCellValue('R'.($data_termina + 1), '=sum(R'.$data_empieza.':R'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('S'.($data_termina + 1), '=sum(S'.$data_empieza.':S'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('T'.($data_termina + 1), '=sum(T'.$data_empieza.':T'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('U'.($data_termina + 1), '=sum(U'.$data_empieza.':U'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('V'.($data_termina + 1), '=sum(V'.$data_empieza.':V'.$data_termina.')');
                $this->excel->getActiveSheet()->setCellValue('W'.($data_termina + 1), '=sum(W'.$data_empieza.':W'.$data_termina.')');
                }
                
                $this->excel->getActiveSheet()->getStyle('P'.$data_empieza.':P'.$data_termina)->getNumberFormat()->setFormatCode('0');
                $this->excel->getActiveSheet()->getStyle('Q'.$data_empieza.':Q'.$data_termina)->getNumberFormat()->setFormatCode('0');
				if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->getStyle('R'.$data_empieza.':R'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('S'.$data_empieza.':S'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$this->excel->getActiveSheet()->getStyle('T'.$data_empieza.':T'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('U'.$data_empieza.':U'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$this->excel->getActiveSheet()->getStyle('V'.$data_empieza.':V'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->getStyle('W'.$data_empieza.':W'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 }
 
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);	
                $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                if($this->session->userdata('valuacion') == 1){
				$this->excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);	
                $this->excel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
                $this->excel->getActiveSheet()->getStyle('I'.$data_empieza.':W'.$data_termina)->getAlignment()->setWrapText(true);
                }
                
                
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                if($this->session->userdata('valuacion') == 1){
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':W'.($data_termina + 1))->applyFromArray($styleArray);
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':W'.($data_termina + 1));
                }else{
                 $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':Q'.($data_termina + 1))->applyFromArray($styleArray);
                 $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                 $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':Q'.($data_termina + 1));  
                }
                $hoja++;   
    }
    
    
    function getRsuExcel($reporte){
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
        
        $hoja = 0;
            
                $this->excel->createSheet($hoja);
                $this->excel->setActiveSheetIndex($hoja);
                
                
                $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
                $this->excel->getActiveSheet()->setTitle('RECETAS SURTIDAS');
              
                $query4 = $this->reportes_model->executeQuery($reporte);//$this->reportes_model->rsu_surtidas_farmacia($fecha1, $fecha2);
                $titulo = $this->reportes_model->executeTitulo($reporte);
                $succ = $this->session->userdata('clvsucursal');
                $s = "select * from sucursales where clvsucursal = $succ";
                $q = $this->db->query($s);
                $r = $q->row();
                $sucx = $r->descsucursal;
                
               
               $this->excel->getActiveSheet()->mergeCells('A1:D1');
               $this->excel->getActiveSheet()->mergeCells('A2:D2');
               if($this->session->userdata('valuacion') == 1){
               }else{
               $this->excel->getActiveSheet()->mergeCells('A3:D3'); 
               } 
                
                $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->setCellValue('A2', $titulo);
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(10);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                if($this->session->userdata('valuacion') == 1){
                }else{
                $this->excel->getActiveSheet()->setCellValue('A3', 'SUCURSAL: '.$succ.' - '.$sucx);
				$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                }


                $num = 5;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, '# SUCURSAL');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'SUCURSAL');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'RECETAS SURTIDAS');
                
				
				$i = 1; 
				
                foreach($query4->result()  as $row4)
                {
                  					
					$num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row4->clvsucursal);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row4->descsucursal);
                    $this->excel->getActiveSheet()->setCellValue('D'.$num, $row4->cuenta);
                    $i++;
                    
                }
                
                $data_termina = $num;

                $this->excel->getActiveSheet()->setCellValue('D'.($data_termina + 1), '=sum(D'.$data_empieza.':D'.$data_termina.')');
                
                $this->excel->getActiveSheet()->getStyle('D'.$data_empieza.':D'.$data_termina)->getNumberFormat()->setFormatCode('0');
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $this->excel->getActiveSheet()->getStyle('A'.$data_empieza.':D'.$data_termina)->getAlignment()->setWrapText(true);
              
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':D'.($data_termina + 1))->applyFromArray($styleArray);
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':D'.($data_termina + 1));
             
                $hoja++;    
    }
    
    function getCausesExcel($reporte){
         set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
        
        $hoja = 0;
            
                $this->excel->createSheet($hoja);
                $this->excel->setActiveSheetIndex($hoja);
                
                
                $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
                $this->excel->getActiveSheet()->setTitle('CLAVES CAUSES');
              
                $query4 = $this->reportes_model->executeQuery($reporte);//$this->reportes_model->rsu_surtidas_farmacia($fecha1, $fecha2);
                $titulo = $this->reportes_model->executeTitulo($reporte);
                $succ = $this->session->userdata('clvsucursal');
                $s = "select * from sucursales where clvsucursal = $succ";
                $q = $this->db->query($s);
                $r = $q->row();
                $sucx = $r->descsucursal;
                
                $this->excel->getActiveSheet()->mergeCells('A1:F1');
                $this->excel->getActiveSheet()->mergeCells('A2:F2');
                if($this->session->userdata('valuacion') == 1){
                }else{
                $this->excel->getActiveSheet()->mergeCells('A3:F3');
                }
                $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->mergeCells('A1:F1');
                $this->excel->getActiveSheet()->setCellValue('A2', $titulo);
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(10);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                if($this->session->userdata('valuacion') == 1){
                }else{
                $this->excel->getActiveSheet()->setCellValue('A3', 'SUCURSAL: '.$succ.' - '.$sucx);
				$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                }


                $num = 5;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, 'CLAVE');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'SUSTANCIA ACTIVA');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'DESCRIPCION');
				$this->excel->getActiveSheet()->setCellValue('E'.$num, 'PRESENTACION');
				$this->excel->getActiveSheet()->setCellValue('F'.$num, 'CANT SUR.');
                
				
				$i = 1; 
				
                foreach($query4->result()  as $row4)
                {
                  					
					$num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row4->cvearticulo);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row4->susa);
					$this->excel->getActiveSheet()->setCellValue('D'.$num, $row4->descripcion);
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row4->pres);
                    $this->excel->getActiveSheet()->setCellValue('F'.$num, $row4->surtido);
                    $i++;
                    
                }
                
                $data_termina = $num;

                $this->excel->getActiveSheet()->setCellValue('F'.($data_termina + 1), '=sum(F'.$data_empieza.':F'.$data_termina.')');
                
                $this->excel->getActiveSheet()->getStyle('F'.$data_empieza.':F'.$data_termina)->getNumberFormat()->setFormatCode('0');
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $this->excel->getActiveSheet()->getStyle('A'.$data_empieza.':F'.$data_termina)->getAlignment()->setWrapText(true);
              
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':F'.($data_termina + 1))->applyFromArray($styleArray);
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':F'.($data_termina + 1));
             
                $hoja++;  
    }
    
    
    function getMayorExcel($reporte){
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
        
        $hoja = 0;
            
                $this->excel->createSheet($hoja);
                $this->excel->setActiveSheetIndex($hoja);
                
                
                $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
                $this->excel->getActiveSheet()->setTitle('CLAVES MAYOR');
              
                $query4 = $this->reportes_model->executeQuery($reporte);//$this->reportes_model->rsu_surtidas_farmacia($fecha1, $fecha2);
                $titulo = $this->reportes_model->executeTitulo($reporte);
                $succ = $this->session->userdata('clvsucursal');
                $s = "select * from sucursales where clvsucursal = $succ";
                $q = $this->db->query($s);
                $r = $q->row();
                $sucx = $r->descsucursal;
                
                $this->excel->getActiveSheet()->mergeCells('A1:F1');
                $this->excel->getActiveSheet()->mergeCells('A2:F2');
                if($this->session->userdata('valuacion') == 1){
                }else{
                $this->excel->getActiveSheet()->mergeCells('A3:F3');
                }
                $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->setCellValue('A2', $titulo);
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(10);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                if($this->session->userdata('valuacion') == 1){
                }else{
                $this->excel->getActiveSheet()->setCellValue('A3', 'SUCURSAL: '.$succ.' - '.$sucx);
				$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                }

                $num = 5;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, 'CLAVE');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'SUSTANCIA ACTIVA');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'DESCRIPCION');
				$this->excel->getActiveSheet()->setCellValue('E'.$num, 'PRESENTACION');
				$this->excel->getActiveSheet()->setCellValue('F'.$num, 'CANTIDAD SURTIDA');
                
				
				$i = 1; 
				
                foreach($query4->result()  as $row4)
                {
                  					
					$num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row4->cvearticulo);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row4->susa);
					$this->excel->getActiveSheet()->setCellValue('D'.$num, $row4->descripcion);
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row4->pres);
                    $this->excel->getActiveSheet()->setCellValue('F'.$num, $row4->surtido);
                    $i++;
                    
                }
                
                $data_termina = $num;

                $this->excel->getActiveSheet()->setCellValue('F'.($data_termina + 1), '=sum(F'.$data_empieza.':F'.$data_termina.')');
                
                $this->excel->getActiveSheet()->getStyle('F'.$data_empieza.':F'.$data_termina)->getNumberFormat()->setFormatCode('0');
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $this->excel->getActiveSheet()->getStyle('A'.$data_empieza.':F'.$data_termina)->getAlignment()->setWrapText(true);
              
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':F'.($data_termina + 1))->applyFromArray($styleArray);
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':F'.($data_termina + 1));
             
                $hoja++;    
    }
    
    
    function getMenorExcel($reporte){
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
        
        $hoja = 0;
            
                $this->excel->createSheet($hoja);
                $this->excel->setActiveSheetIndex($hoja);
                
                
                $this->excel->getActiveSheet()->getTabColor()->setRGB('32CD32');
                $this->excel->getActiveSheet()->setTitle('CLAVES MENOR');
              
                $query4 = $this->reportes_model->executeQuery($reporte);//$this->reportes_model->rsu_surtidas_farmacia($fecha1, $fecha2);
                $titulo = $this->reportes_model->executeTitulo($reporte);
                $succ = $this->session->userdata('clvsucursal');
                $s = "select * from sucursales where clvsucursal = $succ";
                $q = $this->db->query($s);
                $r = $q->row();
                $sucx = $r->descsucursal;
                
                $this->excel->getActiveSheet()->mergeCells('A1:F1');
                $this->excel->getActiveSheet()->mergeCells('A2:F2');
                if($this->session->userdata('valuacion') == 1){
                }else{
                $this->excel->getActiveSheet()->mergeCells('A3:F3');
                }
                $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
				$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->mergeCells('A1:F1');
                $this->excel->getActiveSheet()->setCellValue('A2', $titulo);
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(10);
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                if($this->session->userdata('valuacion') == 1){
                }else{
                $this->excel->getActiveSheet()->setCellValue('A3', 'SUCURSAL: '.$succ.' - '.$sucx);
				$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
				$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                }

                $num = 5;
                
                $data_empieza = $num + 1;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
                $this->excel->getActiveSheet()->setCellValue('B'.$num, 'CLAVE');
                $this->excel->getActiveSheet()->setCellValue('C'.$num, 'SUSTANCIA ACTIVA');
                $this->excel->getActiveSheet()->setCellValue('D'.$num, 'DESCRIPCION');
				$this->excel->getActiveSheet()->setCellValue('E'.$num, 'PRESENTACION');
				$this->excel->getActiveSheet()->setCellValue('F'.$num, 'CANTIDAD SURTIDA');
                
				
				$i = 1; 
				
                foreach($query4->result()  as $row4)
                {
                  					
					$num++;
                    
                    $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                    $this->excel->getActiveSheet()->setCellValue('B'.$num, $row4->cvearticulo);
                    $this->excel->getActiveSheet()->setCellValue('C'.$num, $row4->susa);
					$this->excel->getActiveSheet()->setCellValue('D'.$num, $row4->descripcion);
                    $this->excel->getActiveSheet()->setCellValue('E'.$num, $row4->pres);
                    $this->excel->getActiveSheet()->setCellValue('F'.$num, $row4->surtido);
                    $i++;
                    
                }
                
                $data_termina = $num;

                $this->excel->getActiveSheet()->setCellValue('F'.($data_termina + 1), '=sum(F'.$data_empieza.':F'.$data_termina.')');
                
                $this->excel->getActiveSheet()->getStyle('F'.$data_empieza.':F'.$data_termina)->getNumberFormat()->setFormatCode('0');
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $this->excel->getActiveSheet()->getStyle('A'.$data_empieza.':F'.$data_termina)->getAlignment()->setWrapText(true);
              
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                
                $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':F'.($data_termina + 1))->applyFromArray($styleArray);
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
                $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':F'.($data_termina + 1));
             
                $hoja++;     
    }
    
    
    function getNecesidadesExcel()
    {
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
        	die($cacheMethod . " caching method is not available" . EOL);
        }
        $this->load->model('almacen_model');
        $query = $this->almacen_model->calculaPedidoAlmacen();
        
            $hoja = 0;
            $this->excel->createSheet($hoja);
            $this->excel->setActiveSheetIndex($hoja);
            $this->excel->getActiveSheet()->getTabColor()->setRGB('EAAC1C');
            $this->excel->getActiveSheet()->setTitle('NECESIDADES DEL ALMACEN');
            $succ = $this->session->userdata('clvsucursal');
            if($this->session->userdata('valuacion') == 1){
               $titulo = 'REPORTE GENERAL DE NECESIDADES DE ALMACEN'.' '.APLICACION.'     '.date('d/M/Y H:i:s');
            }else{
                $succ = $this->session->userdata('clvsucursal');
                $s = "select * from sucursales where clvsucursal = $succ";
                $q = $this->db->query($s);
                $r = $q->row();
                $sucx = $r->descsucursal;
                $titulo = 'REPORTE GENERAL DE NECESIDADES DE ALMACEN'.' '.APLICACION.' SUCURSAL '.$succ.' - '.$sucx.date('d/M/Y H:i:s');
            }
                        
            $this->excel->getActiveSheet()->mergeCells('A1:L1');
            $this->excel->getActiveSheet()->mergeCells('A2:L2');
            $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
            $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->setCellValue('A2', $titulo);
            $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
            
            $num = 3;
            $data_empieza = $num + 1;
            
            $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
            $this->excel->getActiveSheet()->setCellValue('B'.$num, 'ID');
            $this->excel->getActiveSheet()->setCellValue('C'.$num, 'CLAVE');
            $this->excel->getActiveSheet()->setCellValue('D'.$num, 'SUSTANCIA ACTIVA');
            $this->excel->getActiveSheet()->setCellValue('E'.$num, 'DESCRIPCION');
            $this->excel->getActiveSheet()->setCellValue('F'.$num, 'PRESENTACION');
            $this->excel->getActiveSheet()->setCellValue('G'.$num, 'BUFER');
            $this->excel->getActiveSheet()->setCellValue('H'.$num, 'INVENTARIO');
            $this->excel->getActiveSheet()->setCellValue('I'.$num, 'FACTOR BUFFER');
            $this->excel->getActiveSheet()->setCellValue('J'.$num, 'PEDIDO');
            $this->excel->getActiveSheet()->setCellValue('K'.$num, 'EXCEDENTES');
            $this->excel->getActiveSheet()->setCellValue('L'.$num, 'SOBRANTES');
            
            $i = 1;
            
            if($query->num_rows() > 0)
            {
            
             $bufferFarmacias = 0;$inventario = 0;$pedido = 0;
             $excedente = 0;$sobrante = 0;
                
            foreach($query->result()  as $row)
            {                
                $num++;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, $i);
                $this->excel->getActiveSheet()->setCellValue('B'.$num, $row->id);
                $this->excel->getActiveSheet()->setCellValue('C'.$num, $row->cvearticulo);
                $this->excel->getActiveSheet()->setCellValue('D'.$num, $row->susa);
                $this->excel->getActiveSheet()->setCellValue('E'.$num, $row->descripcion);
                $this->excel->getActiveSheet()->setCellValue('F'.$num, $row->pres);
                $this->excel->getActiveSheet()->setCellValue('G'.$num, $row->bufferFarmacias);
                $this->excel->getActiveSheet()->setCellValue('H'.$num, $row->inventario);
                $this->excel->getActiveSheet()->setCellValue('I'.$num, $row->factor);
                $this->excel->getActiveSheet()->setCellValue('J'.$num, $row->pedido);
                $this->excel->getActiveSheet()->setCellValue('K'.$num, $row->excedente);
                $this->excel->getActiveSheet()->setCellValue('L'.$num, $row->sobrante);
                //
                //$this->excel->getActiveSheet()->getRowDimension($num)->setRowHeight(20);
                //$this->excel->getActiveSheet()->getRowDimension($num)->setVisible(true);
                //$this->excel->getActiveSheet()->setCellValue('m'.$num, '=H'.$num.'*L'.$num);
                
                if($row->factor < 70 && $row->bufferFarmacias > 0)
                {
                    $this->excel->getActiveSheet()->getStyle('A' . $num . ':L' . $num)->getFill()->applyFromArray(array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                             'rgb' => '5BD244'
                        )
                    ));
                }elseif($row->factor > 150){
                    $this->excel->getActiveSheet()->getStyle('A' . $num . ':L' . $num)->getFill()->applyFromArray(array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                             'rgb' => 'FF6961'
                        )
                    ));
                }elseif($row->sobrante > 0){
                    $this->excel->getActiveSheet()->getStyle('A' . $num . ':L' . $num)->getFill()->applyFromArray(array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                             'rgb' => 'FBAD89'
                        )
                    ));
                }
                
                $i++;
                
            }
            
            $data_termina = $num;
            
            $this->excel->getActiveSheet()->setCellValue('G'.($data_termina + 1), '=sum(G'.$data_empieza.':G'.$data_termina.')');
            $this->excel->getActiveSheet()->setCellValue('H'.($data_termina + 1), '=sum(H'.$data_empieza.':H'.$data_termina.')');
            $this->excel->getActiveSheet()->setCellValue('I'.($data_termina + 1), '=sum(I'.$data_empieza.':I'.$data_termina.')');
            $this->excel->getActiveSheet()->setCellValue('J'.($data_termina + 1), '=sum(J'.$data_empieza.':J'.$data_termina.')');
            $this->excel->getActiveSheet()->setCellValue('K'.($data_termina + 1), '=sum(K'.$data_empieza.':K'.$data_termina.')');
            $this->excel->getActiveSheet()->setCellValue('L'.($data_termina + 1), '=sum(L'.$data_empieza.':L'.$data_termina.')');
            
            
            $this->excel->getActiveSheet()->getStyle('G'.$data_empieza.':G'.$data_termina)->getNumberFormat()->setFormatCode('0');
            $this->excel->getActiveSheet()->getStyle('H'.$data_empieza.':H'.$data_termina)->getNumberFormat()->setFormatCode('0');
            $this->excel->getActiveSheet()->getStyle('I'.$data_empieza.':I'.$data_termina)->getNumberFormat()->setFormatCode('0');
            $this->excel->getActiveSheet()->getStyle('J'.$data_empieza.':J'.$data_termina)->getNumberFormat()->setFormatCode('0');
            $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':K'.$data_termina)->getNumberFormat()->setFormatCode('0');
            $this->excel->getActiveSheet()->getStyle('L'.$data_empieza.':L'.$data_termina)->getNumberFormat()->setFormatCode('0');
            
            $this->excel->getActiveSheet()->getStyle('G'.$data_empieza.':G'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
            $this->excel->getActiveSheet()->getStyle('H'.$data_empieza.':H'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
            $this->excel->getActiveSheet()->getStyle('I'.$data_empieza.':I'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
            $this->excel->getActiveSheet()->getStyle('J'.$data_empieza.':J'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
            $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':K'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
            $this->excel->getActiveSheet()->getStyle('L'.$data_empieza.':L'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
            
            $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);//setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            
            $this->excel->getActiveSheet()->getStyle('A'.$data_empieza.':L'.$data_termina)->getAlignment()->setWrapText(true);
            
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FFFF0000'),
                    ),
                ),
            );
            
            $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':L'.($data_termina + 1))->applyFromArray($styleArray);
            
            $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
            $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':L'.($data_termina + 1));
            
            
            }
            $hoja++;
        }
    
    
    

}