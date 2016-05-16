<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reportes extends CI_Controller
{
    var $valuacion = 0;

    public function __construct()
    {
        parent::__construct();

        if (!Current_User::user()) {
            redirect('welcome');
        }
        
        $this->load->model('reportes_model');
        $this->load->helper('utilities');
        $this->valuacion = $this->session->userdata('valuacion');
        date_default_timezone_set('America/Mexico_City');

    }
    
    public function recetas_periodo()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/recetas_periodo_js";
        $data['programa'] = $this->reportes_model->getProgramasCombo();
        $data['requerimiento'] = $this->reportes_model->getRequerimientoCombo();
        $this->load->view('main', $data);
    }
    
    public function recetas_periodo_detalle()
    {
        set_time_limit(0);
        ini_set('memory_limit','-1');
        
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $idprograma = $this->input->post('idprograma');
        $tiporequerimiento = $this->input->post('tiporequerimiento');
        
        $data['query'] = $this->reportes_model->recetas_periodo_detalle($fecha1, $fecha2, $idprograma, $tiporequerimiento);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }
    
    function imprimeReporte()
    {
        set_time_limit(0);
        ini_set('memory_limit','-1');
        
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $idprograma = $this->input->post('idprograma');
        $tiporequerimiento = $this->input->post('tiporequerimiento');

        $programas = $this->reportes_model->getProgramasCombo();
        $requerimientos = $this->reportes_model->getRequerimientoCombo();

        $data['cabeza'] = $this->reportes_model->getReporteRecetasCabeza($fecha1, $fecha2, $idprograma, $tiporequerimiento, $programas, $requerimientos);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['query'] = $this->reportes_model->recetas_periodo_detalle($fecha1, $fecha2, $idprograma, $tiporequerimiento);
        $this->load->view('impresiones/reporteRecetas', $data);
    }
    
    public function reporte_productos()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/recetas_periodo_js";
        $data['programa'] = $this->reportes_model->getProgramasCombo();
        $data['requerimiento'] = $this->reportes_model->getRequerimientoCombo();
        $this->load->view('main', $data);
    }    
    
    public function productos_periodo_detalle()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $idprograma = $this->input->post('idprograma');
        $tiporequerimiento = $this->input->post('tiporequerimiento');
        
        $data['query'] = $this->reportes_model->recetas_periodo_detalle($fecha1, $fecha2, $idprograma, $tiporequerimiento);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }

    
    public function recetas_periodo_anterior()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/recetas_periodo_js";
        $data['sucursal'] = $this->reportes_model->getSucursalesCombo();
        $this->load->view('main', $data);
    }
    
    public function recetas_periodo_anterior_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $clvsucursal = $this->input->post('clvsucursal');
        
        $data['query'] = $this->reportes_model->recetas_periodo_detalle_anterior($fecha1, $fecha2, $clvsucursal);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }

    public function consumo()
    {
        $data['subtitulo'] = "Reporte de Consumos";
        $data['js'] = "reportes/recetas_periodo_js";
        $this->load->view('main', $data);
    }
    
    function consumo_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        
        $data['query'] = $this->reportes_model->getConsumo($fecha1, $fecha2);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        
        $data['subtitulo'] = "Reporte de Consumos";
        $this->load->view('main', $data);
    }

    public function negado()
    {
        $data['subtitulo'] = "Reporte de Negados";
        $data['js'] = "reportes/recetas_periodo_js";
        $this->load->view('main', $data);
    }

    function negado_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        
        $data['query'] = $this->reportes_model->getNegado($fecha1, $fecha2);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        
        $data['subtitulo'] = "Reporte de Negados";
        $this->load->view('main', $data);
    }

    function imprimeConsumo($fecha1, $fecha2)
    {
        set_time_limit(0);
        ini_set('memory_limit','-1');
        
        $data['cabeza'] = $this->reportes_model->getReporteConsumoCabeza($fecha1, $fecha2);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['query'] = $this->reportes_model->getConsumo($fecha1, $fecha2);
        $this->load->view('impresiones/reporteConsumo', $data);
    }

    function imprimeNegado($fecha1, $fecha2)
    {
        set_time_limit(0);
        ini_set('memory_limit','-1');
        
        $data['cabeza'] = $this->reportes_model->getReporteNegadoCabeza($fecha1, $fecha2);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['query'] = $this->reportes_model->getNegado($fecha1, $fecha2);
        $this->load->view('impresiones/reporteNegado', $data);
    }
    
    function inventario_por_area()
    {
        $this->reportes_model->getExcel(0, null, null);
        
        $filename = $this->uri->segment(2).'_'.date('Ymd_his').'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                     
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    
    function esi()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/esi_js";
        $this->load->view('main', $data);
    }

    function esi_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
    
        $this->reportes_model->getExcel(1, $fecha1, $fecha2);
        
        $filename = $this->uri->segment(2).'_'.date('Ymd_his').'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                     
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    
    function esi_por_clave()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/esi_por_clave_js";
        $this->load->view('main', $data);
    }
    
    function esi_por_clave_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $articulo = $this->input->post('articulo');
        
        $this->reportes_model->getExcel(1, $fecha1, $fecha2, $articulo);
        
        $filename = $this->uri->segment(2).'_'.date('Ymd_his').'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                     
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    function entradas()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/entradas_js";
        $this->load->view('main', $data);
    }

    function entradas_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $proveedorID = $this->input->post('proveedorID');
        
        $this->reportes_model->getMovimiento(1, $fecha1, $fecha2, $proveedorID);
        
        $filename = $this->uri->segment(2).'_'.date('Ymd_his').'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                     
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    function salidas()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/salidas_js";
        $this->load->view('main', $data);
    }

    function salidas_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $clvsucursal = $this->input->post('clvsucursal');
        
        $this->reportes_model->getMovimiento(2, $fecha1, $fecha2, null, $clvsucursal);
        
        $filename = $this->uri->segment(2).'_'.date('Ymd_his').'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                     
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    function esiFile()
    {
        $this->reportes_model->getExcel(1, '2015-06-29', '2015-07-02');
        
        $ruta = './downloads/';
        $filename = $this->uri->segment(2).'.xlsx';
        $objWriter = new PHPExcel_Writer_Excel2007($this->excel);
        $objWriter->save($ruta.$filename);
    }

    function esiByMail()
    {
        $dia = $this->reportes_model->getFechaDiaAnterior();
        
        $this->reportes_model->getExcel(1, $dia, $dia);
        
        $ruta = './downloads/';
        $filename = $this->uri->segment(2).'_'.date('Ymd').'.xlsx';
        $objWriter = new PHPExcel_Writer_Excel2007($this->excel);
        $objWriter->save($ruta.$filename);
        
        $cc = 'ivan.zuniga@farfenix.com.mx';
        $correo = $this->reportes_model->getCorreos($this->uri->segment(2));
        $subject = 'ENTRADAS, SALIDAS E INVENTARIO';
        
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.farfenix.com.mx',
            'smtp_user' => $cc,
            'smtp_pass' => '73dek',
            'mailtype'  => 'text', 
            'charset'   => 'iso-8859-1'
        );
        
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        
        $this->email->from($cc, APLICACION);
        $this->email->to($correo);
        $this->email->cc($cc);
        $this->email->attach($ruta.$filename);
        $this->email->subject($subject);
        $this->email->message('SALUDOS.');

        $this->email->send();

        unlink($ruta.$filename);
    }

    function esiMensualByMail()
    {
        $dia = $this->reportes_model->getFechaMesAnterior();
        
        $this->reportes_model->getExcel(1, $dia->primer_dia, $dia->ultimo_dia);
        
        $ruta = './downloads/';
        $filename = $this->uri->segment(2).'_'.date('Ymd').'.xlsx';
        $objWriter = new PHPExcel_Writer_Excel2007($this->excel);
        $objWriter->save($ruta.$filename);
        
        $cc = 'ivan.zuniga@farfenix.com.mx';
        $correo = $this->reportes_model->getCorreos($this->uri->segment(2));
        $subject = 'ENTRADAS, SALIDAS E INVENTARIO';
        
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.farfenix.com.mx',
            'smtp_user' => $cc,
            'smtp_pass' => '73dek',
            'mailtype'  => 'text', 
            'charset'   => 'iso-8859-1'
        );
        
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        
        $this->email->from($cc, APLICACION);
        $this->email->to($correo);
        $this->email->cc($cc);
        $this->email->attach($ruta.$filename);
        $this->email->subject($subject);
        $this->email->message('SALUDOS.');

        $this->email->send();

        unlink($ruta.$filename);
        
        $this->reportes_model->inventarioMensual();
    }

    function cxp()
    {
        $data['subtitulo'] = "Facturas para cuentas por pagar";
        $data['js'] = "reportes/esi_js";
        $this->load->view('main', $data);
    }
    
    function cxp_submit()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $fecha1  = $this->input->post('fecha1');
        $fecha2  = $this->input->post('fecha2');
        $orden = $this->input->post('orden');
        
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['orden'] = $orden;
        
        $data['header'] = $this->reportes_model->header($fecha1, $fecha2, $orden);
        $data['detalle'] = null;
        
        $this->load->view('impresiones/cxp', $data);
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

  public function programaAll()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/fechasAll";
        $data['sucursal'] = $this->reportes_model->getSucursalesByJur2();
        $data['programas'] = $this->reportes_model->getProgramas();
        $data['juris'] = $this->reportes_model->getJuris();
        $data['tipo_sucursal'] = $this->reportes_model->getTiposSucursal();
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $this->load->view('main', $data);
    }
    
     function programaAll_submit()
    {
        ini_set("memory_limit","1024M");
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $juris = $this->input->post('juris');
        $sucursal = $this->input->post('sucursal');
        $tipo_sucursal = $this->input->post('tipo_sucursal');
        $suministro = $this->input->post('suministro');
        $idprograma = $this->input->post('idprograma');
        
        $todo = $this->input->post('todo');
        
        $data['query'] = $this->reportes_model->getProgramaByAll($fecha1, $fecha2, $suministro, $juris, $sucursal, $tipo_sucursal);
        
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        $data['js'] = "reportes/graficaProgramas2";
        //$data['js'] = "metro/remision_concentrado_js";
        $this->load->view('main', $data);
    }
    
    public function programaAll2()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/programaAll2_js";
        $data['sucursal'] = $this->reportes_model->getSucursalesByJur2();
        $data['tipo_sucursal'] = $this->reportes_model->getTiposSucursal();
        $data['programas'] = $this->reportes_model->getProgramas();
        $data['juris'] = $this->reportes_model->getJuris();
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $this->load->view('main', $data);
    }
    
    function programaAll2_submit()
    {
        ini_set("memory_limit","512M");
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $juris = $this->input->post('juris');
        $sucursal = $this->input->post('sucursal');
        $tipo_sucursal = $this->input->post('tipo_sucursal');
        $suministro = $this->input->post('suministro');
        $idprograma = $this->input->post('idprograma');
        //$todo = $this->input->post('todo');
        $data['query'] = $this->reportes_model->getProgramaByProgramaByAll($fecha1, $fecha2, $suministro, $idprograma, $juris, $sucursal, $tipo_sucursal);
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        $data['js'] = "reportes/grafica";
        //$data['js'] = "metro/remision_concentrado_js";
        $this->load->view('main', $data);
    }

 function claveAll()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/programaAll2_js";
        $data['programas'] = $this->reportes_model->getProgramas();
        $data['juris'] = $this->reportes_model->getJuris();
        $data['tipo_sucursal'] = $this->reportes_model->getTiposSucursal();
        $data['sucursal'] = $this->reportes_model->getSucursalesByJur2();
        $this->load->view('main', $data);
    }
    
    function claveAll_submit()
    {
        ini_set("memory_limit","512M");
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $juris = $this->input->post('juris');
        $sucursal = $this->input->post('sucursal');
        $tipo_sucursal = $this->input->post('tipo_sucursal');
        $clave = $this->input->post('cveArticulo');
        $idprograma = $this->input->post('idprograma');
        
        $data['clave'] = $clave;
        $data['completo'] = $this->reportes_model->getCompletoByCvearticulo($clave);
        $data['query'] = $this->reportes_model->getByClaveByAll($fecha1, $fecha2, $sucursal, $clave, $idprograma, $juris, $tipo_sucursal);
        
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        $data['js'] = "reportes/grafica";
        //$data['js'] = "metro/remision_concentrado_js";
        $this->load->view('main', $data);
 
        
    }
    
    public function pacienteAll()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/programaAll2_js";
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $data['juris'] = $this->reportes_model->getJuris();
        $data['sucursal'] = $this->reportes_model->getSucursalesByJur2();
        $this->load->view('main', $data);
    }
    
    function pacienteAll_submit()
    {
        ini_set("memory_limit","1024M");
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $juris = $this->input->post('juris');
        $sucursal = $this->input->post('sucursal');
        $expediente = $this->input->post('expedienteAll');
        $suministro = $this->input->post('suministro');
        
        $data['expediente'] = $expediente;
        $data['paciente'] = $this->reportes_model->getPacienteByCvepacienteJur($expediente);
        $data['query'] = $this->reportes_model->getByCvePacienteAll($expediente, $fecha1, $fecha2, $sucursal, $suministro, $juris);
        
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        $data['js'] = "reportes/grafica";
        //$data['js'] = "metro/remision_concentrado_js";
        $this->load->view('main', $data);
    }
    
     public function medicoAll(){
        $data['subtitulo'] = "";
        $data['js'] = "reportes/medicoAll_js";
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $data['juris'] = $this->reportes_model->getJuris();
        $data['sucursal'] = $this->reportes_model->getSucursalesByJur2();
        $this->load->view('main', $data);
    }


function medicoAll_submit(){
        ini_set("memory_limit","1024M");
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $juris = $this->input->post('juris');
        $sucursal = $this->input->post('sucursal');
        $cveMedico = $this->input->post('cveMedicoAll');
        $suministro = $this->input->post('suministro');        
        $data['cveMedico'] = $cveMedico;
        $data['medico'] = $this->reportes_model->getNombreMedicoByCveMedicoJur($cveMedico);
        $data['query'] = $this->reportes_model->getByCveMedicoAll($cveMedico, $fecha1, $fecha2, $sucursal, $suministro, $juris);
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        $data['js'] = "reportes/grafica";
        //$data['js'] = "metro/remision_concentrado_js";
        $this->load->view('main', $data);
    }
   
    public function recetas_periodoAll(){
        $data['subtitulo'] = "";
        $data['js'] = "reportes/recetas_periodo_js";
        $data['programa'] = $this->reportes_model->getProgramasCombo();
        $data['requerimiento'] = $this->reportes_model->getRequerimientoCombo();
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $data['juris'] = $this->reportes_model->getJuris();
        $data['sucursal'] = $this->reportes_model->getSucursalesByJur2();
        $data['nivelDeAtencion'] = $this->reportes_model->getNivelAtencionCombo2();
        $this->load->view('main', $data);
    }
    
    public function recetas_periodo_detalleAll(){
        ini_set("memory_limit","1024M");

        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $idprograma = $this->input->post('idprograma');
        $tiporequerimiento = $this->input->post('tiporequerimiento');
        $cvesuministro = $this->input->post('cvesuministro');
        $nivelatencion = $this->input->post('nivel');
        $juris = $this->input->post('juris');
        $sucursal = $this->input->post('sucursal');
        $data['query'] = $this->reportes_model->recetas_periodo_detalleAll($fecha1, $fecha2, $idprograma, $tiporequerimiento, $cvesuministro, $nivelatencion, $sucursal, $juris);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }
    
    function busca_cveArticulo(){
        $term = $this->input->get_post('term');
        echo $this->reportes_model->getArticuloByCveArticulo($term);
    }
    
    function busca_expediente(){
        $term = $this->input->get_post('term');
        echo $this->reportes_model->getPacienteByExpediente($term);
    }
    
    function busca_cveMedicoAll(){
        $term = $this->input->get_post('term');
        $juris = $this->input->post('juris');
        $sucursal = $this->input->post('sucursal');
        echo $this->reportes_model->getMedicoByCveMedicoAll($term, $sucursal, $juris);
    }
    
    
    function programaExcel($reporte){
        ini_set("memory_limit","2048M");
        $query = $this->reportes_model->executeQuery($reporte);
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array( 'memoryCacheSize' => '512MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle($reporte);
        $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A1:M1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A2', 'REPORTE DE MEDICAMENTO Y MATERIAL DE CURACIÓN ENTREGADO A LOS');
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A2:M2');
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A3', 'SERVICIO DE SALUD DE MICHOACÁN, CORRESPONDIENTES AL PERIODO DEL');
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A3:M3');
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A4', "" . ' AL ' . "" );
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A4:M4');
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A5', '#');
        $this->excel->getActiveSheet()->setCellValue('B5', 'CLAVE');
        $this->excel->getActiveSheet()->setCellValue('C5', 'DESCRIPCION');
        $this->excel->getActiveSheet()->setCellValue('D5', 'POBLACION ABIERTA');
        $this->excel->getActiveSheet()->setCellValue('E5', 'SEGURO POPULAR');
        $this->excel->getActiveSheet()->setCellValue('F5', 'OPORTUNIDADES');
        $this->excel->getActiveSheet()->setCellValue('G5', 'PROGRAMAS PRIORITARIOS');
        $this->excel->getActiveSheet()->setCellValue('H5', 'BENEFICENCIA PUBLICA');
        $this->excel->getActiveSheet()->setCellValue('I5', 'ADULTO MAYOR');
        $this->excel->getActiveSheet()->setCellValue('J5', 'PAQUETES');
        $this->excel->getActiveSheet()->setCellValue('K5', 'SIGLO MEDICO SIGLO XXI');
        $this->excel->getActiveSheet()->setCellValue('L5', 'CLINICA DE HERIDAS');
        $this->excel->getActiveSheet()->setCellValue('M5', 'TOTAL');

        $this->excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('I5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('J5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('K5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('L5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('M5')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('K5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('L5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('M5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(90);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        
        $this->excel->getActiveSheet()->getRowDimension('5')->setRowHeight(40);

        $this->excel->getActiveSheet()->getStyle('A5:M5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
        $this->excel->getActiveSheet()->getStyle('A5:M5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $this->excel->getActiveSheet()->getStyle('A5:M5')->getAlignment()->setWrapText(true);
        
        if($this->session->userdata('superuser') == 1){

            $this->excel->getActiveSheet()->mergeCells('A1:S1');
            $this->excel->getActiveSheet()->mergeCells('A2:S2');
            $this->excel->getActiveSheet()->mergeCells('A3:S3');
            $this->excel->getActiveSheet()->mergeCells('A4:S4');
    
            $this->excel->getActiveSheet()->setCellValue('N5', 'PRECIO UNITARIO');
            $this->excel->getActiveSheet()->setCellValue('O5', 'IMPORTE');
            $this->excel->getActiveSheet()->setCellValue('P5', 'IVA');
            $this->excel->getActiveSheet()->setCellValue('Q5', 'SERVICIO');
            $this->excel->getActiveSheet()->setCellValue('R5', 'IVA SERVICIO');
            $this->excel->getActiveSheet()->setCellValue('S5', 'SUBTOTAL');
            
            $this->excel->getActiveSheet()->getStyle('N5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('O5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('P5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('Q5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('R5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('S5')->getFont()->setBold(true);
            
            $this->excel->getActiveSheet()->getStyle('N5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('O5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('P5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('Q5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('R5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('S5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
    
            $this->excel->getActiveSheet()->getStyle('N5:S5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
            $this->excel->getActiveSheet()->getStyle('N5:S5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('N5:S5')->getAlignment()->setWrapText(true);
        }
        $inicio = 6;
        $fila = $inicio;
        $no = 1;  
        $cantidadsurtida = 0;
        $cantidadrequerida = 0;
        $tImporte = 0;
        $tIVA = 0;
        $tServicio = 0;
        $tServicioIVA = 0;
        $total = 0;      
        if($query->num_rows() > 0){
        foreach($query->result() as $row)
        {
              $piezas = $row->cansur;
              $importe = $row->preciosinser * $piezas;
              if((int)$row->tipoprod == 1){
              $iva = $row->preciosinser * $piezas * .16;
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
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $no);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $row->cvearticulo);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $row->completo);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $row->pa);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, $row->sp);
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, $row->op);
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, $row->pp);
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, $row->bp);
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, $row->am);
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, $row->pq);
            $this->excel->getActiveSheet()->setCellValue('K'.$fila, $row->sm);
            $this->excel->getActiveSheet()->setCellValue('L'.$fila, $row->ch);
            $this->excel->getActiveSheet()->setCellValue('M'.$fila, $row->todo);
                
            if($this->session->userdata('superuser') == 1){
                $this->excel->getActiveSheet()->setCellValue('N'.$fila, $row->preciosinser);
                $this->excel->getActiveSheet()->setCellValue('O'.$fila, $tImporte);
                $this->excel->getActiveSheet()->setCellValue('P'.$fila, $tIVA);
                $this->excel->getActiveSheet()->setCellValue('Q'.$fila, $tServicio);
                $this->excel->getActiveSheet()->setCellValue('R'.$fila, $tServicioIVA);
                $this->excel->getActiveSheet()->setCellValue('S'.$fila, $total);
                    
            }
                $no++;
                
            
            $fila++;
        }
        
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, '=SUM(D'.$inicio.':D'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, '=SUM(E'.$inicio.':E'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, '=SUM(F'.$inicio.':F'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, '=SUM(G'.$inicio.':G'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, '=SUM(H'.$inicio.':H'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('I'.$fila, '=SUM(I'.$inicio.':I'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('J'.$fila, '=SUM(J'.$inicio.':J'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('K'.$fila, '=SUM(K'.$inicio.':K'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('L'.$fila, '=SUM(L'.$inicio.':L'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('M'.$fila, '=SUM(M'.$inicio.':M'.($fila - 1).')');
        
        if($this->session->userdata('superuser') == 1){
            $this->excel->getActiveSheet()->setCellValue('O'.$fila, '=SUM(O'.$inicio.':O'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('P'.$fila, '=SUM(P'.$inicio.':P'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('Q'.$fila, '=SUM(Q'.$inicio.':Q'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('R'.$fila, '=SUM(R'.$inicio.':R'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('S'.$fila, '=SUM(S'.$inicio.':S'.($fila - 1).')');
        
        }

        $this->excel->getActiveSheet()->getStyle('N'.$inicio.':S'.($fila))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->excel->getActiveSheet()->getStyle('D'.$inicio.':M'.($fila))->getNumberFormat()->setFormatCode('#,##0');
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                ),
            ),
        );
        
        $this->excel->getActiveSheet()->getStyle('A'.($inicio).':O'.($fila - 1))->getAlignment()->setWrapText(true);
        
        $this->excel->createSheet();

        $this->excel->setActiveSheetIndex(1);
        $this->excel->getActiveSheet()->setTitle('Grafica');
        
        $dataseriesLabels1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'DISTRIBUCION', NULL, 1));
        $xAxisTickValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', $reporte.'!$D$5:$L$5', NULL, 9));
        $dataSeriesValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('Number', $reporte.'!$D$'.$fila.':$L$'.$fila, NULL, 9));

        $series1 = new PHPExcel_Chart_DataSeries(
        	PHPExcel_Chart_DataSeries::TYPE_PIECHART,				// plotType
        	PHPExcel_Chart_DataSeries::GROUPING_STANDARD,			// plotGrouping
        	range(0, count($dataSeriesValues1)-1),					// plotOrder
        	$dataseriesLabels1,										// plotLabel
        	$xAxisTickValues1,										// plotCategory
        	$dataSeriesValues1										// plotValues
        );
        
        $layout1 = new PHPExcel_Chart_Layout();
        $layout1->setShowVal(TRUE);
        $layout1->setShowPercent(TRUE);
        
        $plotarea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
        $legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
        $title1 = new PHPExcel_Chart_Title('DISTRIBUCION');
        
        //	Create the chart
        $chart1 = new PHPExcel_Chart(
        	'chart1',		// name
        	$title1,		// title
        	$legend1,		// legend
        	$plotarea1,		// plotArea
        	true,			// plotVisibleOnly
        	0,				// displayBlanksAs
        	NULL,			// xAxisLabel
        	NULL			// yAxisLabel		- Pie charts don't have a Y-Axis
        );
        
        //	Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('A1');
        $chart1->setBottomRightPosition('H20');
        $this->excel->getActiveSheet()->addChart($chart1);
        }else{
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, "NO HAY DATOS QUE MOSTRAR.");
        }

        $filename = $reporte.date('Ymd_his').'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->setIncludeCharts(TRUE);
        $objWriter->save('php://output');    
    }
    
    function programa2Excel($reporte){
      ini_set("memory_limit","2048M");
        $query = $this->reportes_model->executeQuery($reporte);
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array( 'memoryCacheSize' => '512MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle($reporte);
        $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A1:M1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A2', 'REPORTE DE MEDICAMENTO Y MATERIAL DE CURACIÓN ENTREGADO A LOS');
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A2:M2');
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A3', 'SERVICIO DE SALUD DE MICHOACÁN, CORRESPONDIENTES AL PERIODO DEL');
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A3:M3');
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A4', "" . ' AL ' . "" );
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A4:M4');
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A5', '#');
        $this->excel->getActiveSheet()->setCellValue('B5', 'CLAVE');
        $this->excel->getActiveSheet()->setCellValue('C5', 'DESCRIPCION');
        $this->excel->getActiveSheet()->setCellValue('D5', 'CANTIDAD REQUERIDA');
        $this->excel->getActiveSheet()->setCellValue('E5', 'CANTIDAD SURTIDA');
        if($this->session->userdata('superuser') == 1){
        $this->excel->getActiveSheet()->setCellValue('F5', 'PRECIO UNITARIO');
        $this->excel->getActiveSheet()->setCellValue('G5', 'IMPORTE');
        $this->excel->getActiveSheet()->setCellValue('H5', 'IVA');
        $this->excel->getActiveSheet()->setCellValue('I5', 'SERVICIO');
        $this->excel->getActiveSheet()->setCellValue('J5', 'IVA SERVICIO');
        $this->excel->getActiveSheet()->setCellValue('K5', 'SUBTOTAL');
        //$this->excel->getActiveSheet()->setCellValue('L5', 'CLINICA DE HERIDAS');
        //$this->excel->getActiveSheet()->setCellValue('M5', 'TOTAL');
        }
        $this->excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('I5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('J5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('K5')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('K5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(90);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        
        $this->excel->getActiveSheet()->getRowDimension('5')->setRowHeight(40);

        $this->excel->getActiveSheet()->getStyle('A5:K5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
        $this->excel->getActiveSheet()->getStyle('A5:K5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $this->excel->getActiveSheet()->getStyle('A5:K5')->getAlignment()->setWrapText(true);
        
        $inicio = 6;
        $fila = $inicio;
        $no = 1;  
        $req = 0;
        $sur = 0;
        $tImporte = 0;
        $tIVA = 0;
        $tServicio = 0;
        $tServicioIVA = 0;
        $total = 0;     
        if($query->num_rows() > 0){
        foreach($query->result() as $row)
        {
                 $piezas = $row->surtida;                
                 $importe = $row->preciosinser * $piezas; 
                 if((int)$row->tipoprod == 1){
                    $iva = $row->preciosinser * $piezas * .16;
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
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $no);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $row->cvearticulo);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $row->completo);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, number_format($row->requerida,0));
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($row->surtida,0));
            if($this->session->userdata('superuser') == 1){
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($row->preciosinser, 2));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($importe, 2));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($iva, 2));
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($servicio, 2));
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, number_format($servicio_iva, 2));
            $this->excel->getActiveSheet()->setCellValue('K'.$fila, number_format($subtotal, 2));
            }   
                $no++;
                
            
            $fila++;
        }
        
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, '=SUM(D'.$inicio.':D'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, '=SUM(E'.$inicio.':E'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, '=SUM(F'.$inicio.':F'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, '=SUM(G'.$inicio.':G'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, '=SUM(H'.$inicio.':H'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('I'.$fila, '=SUM(I'.$inicio.':I'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('J'.$fila, '=SUM(J'.$inicio.':J'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('K'.$fila, '=SUM(K'.$inicio.':K'.($fila - 1).')');

        $this->excel->getActiveSheet()->getStyle('N'.$inicio.':S'.($fila))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->excel->getActiveSheet()->getStyle('D'.$inicio.':M'.($fila))->getNumberFormat()->setFormatCode('#,##0');
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                ),
            ),
        );
        
        $this->excel->getActiveSheet()->getStyle('A'.($inicio).':O'.($fila - 1))->getAlignment()->setWrapText(true);
        
        $this->excel->createSheet();

        $this->excel->setActiveSheetIndex(1);
        $this->excel->getActiveSheet()->setTitle('Grafica');
        
        $dataseriesLabels1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'DISTRIBUCION', NULL, 1));
        $xAxisTickValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', $reporte.'!$D$5:$L$5', NULL, 9));
        $dataSeriesValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('Number', $reporte.'!$D$'.$fila.':$L$'.$fila, NULL, 9));

        $series1 = new PHPExcel_Chart_DataSeries(
        	PHPExcel_Chart_DataSeries::TYPE_PIECHART,				// plotType
        	PHPExcel_Chart_DataSeries::GROUPING_STANDARD,			// plotGrouping
        	range(0, count($dataSeriesValues1)-1),					// plotOrder
        	$dataseriesLabels1,										// plotLabel
        	$xAxisTickValues1,										// plotCategory
        	$dataSeriesValues1										// plotValues
        );
        
        $layout1 = new PHPExcel_Chart_Layout();
        $layout1->setShowVal(TRUE);
        $layout1->setShowPercent(TRUE);
        
        $plotarea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
        $legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
        $title1 = new PHPExcel_Chart_Title('DISTRIBUCION');
        
        //	Create the chart
        $chart1 = new PHPExcel_Chart(
        	'chart1',		// name
        	$title1,		// title
        	$legend1,		// legend
        	$plotarea1,		// plotArea
        	true,			// plotVisibleOnly
        	0,				// displayBlanksAs
        	NULL,			// xAxisLabel
        	NULL			// yAxisLabel		- Pie charts don't have a Y-Axis
        );
        
        //	Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('A1');
        $chart1->setBottomRightPosition('H20');
        $this->excel->getActiveSheet()->addChart($chart1);
        }else{
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, "NO HAY DATOS QUE MOSTRAR.");
        }

        $filename = $reporte.date('Ymd_his').'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->setIncludeCharts(TRUE);
        $objWriter->save('php://output');      
    }
    
    function Clave_Excel($reporte){
        ini_set("memory_limit","2048M");
        $query = $this->reportes_model->executeQuery($reporte);
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array( 'memoryCacheSize' => '512MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle($reporte);
        $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A1:M1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A2', 'REPORTE DE MEDICAMENTO Y MATERIAL DE CURACIÓN ENTREGADO A LOS');
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A2:M2');
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A3', 'SERVICIO DE SALUD DE MICHOACÁN, CORRESPONDIENTES AL PERIODO DEL');
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A3:M3');
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A4', "" . ' AL ' . "" );
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A4:M4');
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A5', '#');
        $this->excel->getActiveSheet()->setCellValue('B5', '# SUC.');
        $this->excel->getActiveSheet()->setCellValue('C5', 'SUCURSAL');
        $this->excel->getActiveSheet()->setCellValue('D5', 'FECHA');
        $this->excel->getActiveSheet()->setCellValue('E5', 'PROGRAMA');
        $this->excel->getActiveSheet()->setCellValue('F5', 'FOLIO');
        $this->excel->getActiveSheet()->setCellValue('G5', 'CLAVE PACIENTE');
        $this->excel->getActiveSheet()->setCellValue('H5', 'PACIENTE');
        $this->excel->getActiveSheet()->setCellValue('I5', 'CLAVE MEDICO');
        $this->excel->getActiveSheet()->setCellValue('J5', 'MEDICO');
        $this->excel->getActiveSheet()->setCellValue('K5', 'REQUERIDAS');
        $this->excel->getActiveSheet()->setCellValue('L5', 'SURTIDAS');
    
        $this->excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('I5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('J5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('K5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('L5')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('K5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('L5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(90);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        
        $this->excel->getActiveSheet()->getRowDimension('5')->setRowHeight(40);

        $this->excel->getActiveSheet()->getStyle('A5:L5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
        $this->excel->getActiveSheet()->getStyle('A5:L5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $this->excel->getActiveSheet()->getStyle('A5:L5')->getAlignment()->setWrapText(true);
        
        if($this->session->userdata('superuser') == 1){

            $this->excel->getActiveSheet()->mergeCells('A1:R1');
            $this->excel->getActiveSheet()->mergeCells('A2:R2');
            $this->excel->getActiveSheet()->mergeCells('A3:R3');
            $this->excel->getActiveSheet()->mergeCells('A4:R4');
    
            $this->excel->getActiveSheet()->setCellValue('M5', 'PRECIO UNITARIO');
            $this->excel->getActiveSheet()->setCellValue('N5', 'IMPORTE');
            $this->excel->getActiveSheet()->setCellValue('O5', 'IVA');
            $this->excel->getActiveSheet()->setCellValue('P5', 'SERVICIO');
            $this->excel->getActiveSheet()->setCellValue('Q5', 'IVA SERVICIO');
            $this->excel->getActiveSheet()->setCellValue('R5', 'SUBTOTAL');
            
            $this->excel->getActiveSheet()->getStyle('M5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('N5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('O5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('P5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('Q5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('R5')->getFont()->setBold(true);
            
            $this->excel->getActiveSheet()->getStyle('N5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('O5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('P5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('Q5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('R5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('S5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
    
            $this->excel->getActiveSheet()->getStyle('M5:R5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
            $this->excel->getActiveSheet()->getStyle('M5:R5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('M5:R5')->getAlignment()->setWrapText(true);
        }
        $inicio = 6;
        $fila = $inicio;
        $no = 1;  
        $req = 0;
        $sur = 0;
        $tImporte = 0;
        $tIVA = 0;
        $tServicio = 0;
        $tServicioIVA = 0;
        $total = 0;     
        if($query->num_rows() > 0){
        foreach($query->result() as $row)
        {
              $piezas = $row->cansur;
              $importe = $row->preciosinser * $piezas;
              if((int)$row->tipoprod == 1){
                 $iva = $row->preciosinser * $piezas * .16;
              }else{
                 $iva = 0;
              }                      
              $servicio = $piezas * 8.55;
              $servicio_iva = $servicio * .16;
              $subtotal = $importe + $iva + $servicio + $servicio_iva;
              $tImporte = $tImporte + $importe;
              $tIVA = $tIVA + $iva;
              $tServicio = $tServicio + $servicio;
              $tServicioIVA = $tServicioIVA + $servicio_iva;
              $total = $total + $subtotal;      
                                    
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $no);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $row->clvsucursal);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $row->descsucursal);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $row->fecha);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, $row->programa);
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, utf8_encode($row->folioreceta));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, utf8_encode($row->cvepaciente));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, utf8_encode($row->paciente));
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, utf8_encode($row->cvemedico));
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, utf8_encode($row->nombremedico));
            $this->excel->getActiveSheet()->setCellValue('K'.$fila, number_format($row->canreq, 0));
            $this->excel->getActiveSheet()->setCellValue('L'.$fila, number_format($row->cansur, 0));
                
            if($this->session->userdata('superuser') == 1){
                $this->excel->getActiveSheet()->setCellValue('M'.$fila, number_format($row->preciosinser, 2));
                $this->excel->getActiveSheet()->setCellValue('N'.$fila, number_format($importe, 2));
                $this->excel->getActiveSheet()->setCellValue('O'.$fila, number_format($iva, 2));
                $this->excel->getActiveSheet()->setCellValue('P'.$fila, number_format($servicio, 2));
                $this->excel->getActiveSheet()->setCellValue('Q'.$fila, number_format($servicio_iva, 2));
                $this->excel->getActiveSheet()->setCellValue('R'.$fila, number_format($subtotal, 2)); 
            }
             $no++;
             $fila++;
        }
        
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, '=SUM(D'.$inicio.':D'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, '=SUM(E'.$inicio.':E'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, '=SUM(F'.$inicio.':F'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, '=SUM(G'.$inicio.':G'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, '=SUM(H'.$inicio.':H'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('I'.$fila, '=SUM(I'.$inicio.':I'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('J'.$fila, '=SUM(J'.$inicio.':J'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('K'.$fila, '=SUM(K'.$inicio.':K'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('L'.$fila, '=SUM(L'.$inicio.':L'.($fila - 1).')');
        
        if($this->session->userdata('superuser') == 1){
            $this->excel->getActiveSheet()->setCellValue('M'.$fila, '=SUM(M'.$inicio.':M'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('N'.$fila, '=SUM(O'.$inicio.':O'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('O'.$fila, '=SUM(P'.$inicio.':P'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('P'.$fila, '=SUM(Q'.$inicio.':Q'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('Q'.$fila, '=SUM(R'.$inicio.':R'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('R'.$fila, '=SUM(S'.$inicio.':S'.($fila - 1).')');
        }

        $this->excel->getActiveSheet()->getStyle('N'.$inicio.':S'.($fila))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->excel->getActiveSheet()->getStyle('D'.$inicio.':M'.($fila))->getNumberFormat()->setFormatCode('#,##0');
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                ),
            ),
        );
        
        $this->excel->getActiveSheet()->getStyle('A'.($inicio).':O'.($fila - 1))->getAlignment()->setWrapText(true);
        
        $this->excel->createSheet();

        $this->excel->setActiveSheetIndex(1);
        $this->excel->getActiveSheet()->setTitle('Grafica');
        
        $dataseriesLabels1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'DISTRIBUCION', NULL, 1));
        $xAxisTickValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', $reporte.'!$D$5:$L$5', NULL, 9));
        $dataSeriesValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('Number', $reporte.'!$D$'.$fila.':$L$'.$fila, NULL, 9));

        $series1 = new PHPExcel_Chart_DataSeries(
        	PHPExcel_Chart_DataSeries::TYPE_PIECHART,				// plotType
        	PHPExcel_Chart_DataSeries::GROUPING_STANDARD,			// plotGrouping
        	range(0, count($dataSeriesValues1)-1),					// plotOrder
        	$dataseriesLabels1,										// plotLabel
        	$xAxisTickValues1,										// plotCategory
        	$dataSeriesValues1										// plotValues
        );
        
        $layout1 = new PHPExcel_Chart_Layout();
        $layout1->setShowVal(TRUE);
        $layout1->setShowPercent(TRUE);
        
        $plotarea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
        $legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
        $title1 = new PHPExcel_Chart_Title('DISTRIBUCION');
        
        //	Create the chart
        $chart1 = new PHPExcel_Chart(
        	'chart1',		// name
        	$title1,		// title
        	$legend1,		// legend
        	$plotarea1,		// plotArea
        	true,			// plotVisibleOnly
        	0,				// displayBlanksAs
        	NULL,			// xAxisLabel
        	NULL			// yAxisLabel		- Pie charts don't have a Y-Axis
        );
        
        //	Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('A1');
        $chart1->setBottomRightPosition('H20');
        $this->excel->getActiveSheet()->addChart($chart1);
        }else{
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, "NO HAY DATOS QUE MOSTRAR.");
        }

        $filename = $reporte.date('Ymd_his').'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->setIncludeCharts(TRUE);
        $objWriter->save('php://output');     
    }
    
    function Paciente_Excel($reporte){
      ini_set("memory_limit","2048M");
        $query = $this->reportes_model->executeQuery($reporte);
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array( 'memoryCacheSize' => '512MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle($reporte);
        $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A1:M1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A2', 'REPORTE DE MEDICAMENTO Y MATERIAL DE CURACIÓN ENTREGADO A LOS');
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A2:M2');
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A3', 'SERVICIO DE SALUD DE MICHOACÁN, CORRESPONDIENTES AL PERIODO DEL');
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A3:M3');
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A4', "" . ' AL ' . "" );
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A4:M4');
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A5', '#');
        $this->excel->getActiveSheet()->setCellValue('B5', '# SUC.');
        $this->excel->getActiveSheet()->setCellValue('C5', 'SUCURSAL');
        $this->excel->getActiveSheet()->setCellValue('D5', 'FECHA');
        $this->excel->getActiveSheet()->setCellValue('E5', 'FOLIO');
        $this->excel->getActiveSheet()->setCellValue('F5', 'PROGRAMA');
        $this->excel->getActiveSheet()->setCellValue('G5', 'CLAVE MEDICO');
        $this->excel->getActiveSheet()->setCellValue('H5', 'MEDICO');
        $this->excel->getActiveSheet()->setCellValue('I5', 'CLAVE');
        $this->excel->getActiveSheet()->setCellValue('J5', 'DESCRIPCION');
        $this->excel->getActiveSheet()->setCellValue('K5', 'REQUERIDAS');
        $this->excel->getActiveSheet()->setCellValue('L5', 'SURTIDAS');
        //$this->excel->getActiveSheet()->setCellValue('M5', 'TOTAL');

        $this->excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('I5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('J5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('K5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('L5')->getFont()->setBold(true);
        //$this->excel->getActiveSheet()->getStyle('M5')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('K5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('L5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$this->excel->getActiveSheet()->getStyle('M5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(90);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        
        $this->excel->getActiveSheet()->getRowDimension('5')->setRowHeight(40);

        $this->excel->getActiveSheet()->getStyle('A5:L5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
        $this->excel->getActiveSheet()->getStyle('A5:L5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $this->excel->getActiveSheet()->getStyle('A5:L5')->getAlignment()->setWrapText(true);
        
        if($this->session->userdata('superuser') == 1){

            $this->excel->getActiveSheet()->mergeCells('A1:R1');
            $this->excel->getActiveSheet()->mergeCells('A2:R2');
            $this->excel->getActiveSheet()->mergeCells('A3:R3');
            $this->excel->getActiveSheet()->mergeCells('A4:R4');
    
            $this->excel->getActiveSheet()->setCellValue('M5', 'PRECIO UNITARIO');
            $this->excel->getActiveSheet()->setCellValue('N5', 'IMPORTE');
            $this->excel->getActiveSheet()->setCellValue('O5', 'IVA');
            $this->excel->getActiveSheet()->setCellValue('P5', 'SERVICIO');
            $this->excel->getActiveSheet()->setCellValue('Q5', 'IVA SERVICIO');
            $this->excel->getActiveSheet()->setCellValue('R5', 'SUBTOTAL');
            
            $this->excel->getActiveSheet()->getStyle('M5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('N5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('O5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('P5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('Q5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('R5')->getFont()->setBold(true);
            
            $this->excel->getActiveSheet()->getStyle('M5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('N5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('O5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('P5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('Q5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('R5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
    
            $this->excel->getActiveSheet()->getStyle('M5:R5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
            $this->excel->getActiveSheet()->getStyle('M5:R5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('M5:R5')->getAlignment()->setWrapText(true);
        }
        $inicio = 6;
        $fila = $inicio;
        $no = 1;  
        $req = 0;
        $sur = 0;
        $tImporte = 0;
        $tIVA = 0;
        $tServicio = 0;
        $tServicioIVA = 0;
        $total = 0;     
        if($query->num_rows() > 0){
        foreach($query->result() as $row)
        {
         $piezas = $row->cansur;
         $importe = $row->preciosinser * $piezas;
         if((int)$row->tipoprod == 1){
            $iva = $row->preciosinser * $piezas * .16;
         }else{$iva = 0;}
         $servicio = $piezas * 8.55;
         $servicio_iva = $servicio * .16;
         $subtotal = $importe + $iva + $servicio + $servicio_iva;
         $tImporte = $tImporte + $importe;
         $tIVA = $tIVA + $iva;
         $tServicio = $tServicio + $servicio;
         $tServicioIVA = $tServicioIVA + $servicio_iva;
         $total = $total + $subtotal;   
                                        
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $no);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $row->clvsucursal);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $row->descsucursal);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $row->fecha);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, utf8_encode($row->folioreceta));
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, utf8_encode($row->programa));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, utf8_encode($row->cvemedico));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, utf8_encode($row->nombremedico));
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, utf8_encode($row->cvearticulo));
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, utf8_encode($row->completo));
            $this->excel->getActiveSheet()->setCellValue('K'.$fila, number_format($row->canreq, 0));
            $this->excel->getActiveSheet()->setCellValue('L'.$fila, number_format($row->cansur, 0));
                
            if($this->session->userdata('superuser') == 1){
                $this->excel->getActiveSheet()->setCellValue('M'.$fila, number_format($row->preciosinser, 2));
                $this->excel->getActiveSheet()->setCellValue('N'.$fila, number_format($importe, 2));
                $this->excel->getActiveSheet()->setCellValue('O'.$fila, number_format($iva, 2));
                $this->excel->getActiveSheet()->setCellValue('P'.$fila, number_format($servicio, 2));
                $this->excel->getActiveSheet()->setCellValue('Q'.$fila, number_format($servicio_iva, 2));
                $this->excel->getActiveSheet()->setCellValue('R'.$fila, number_format($subtotal, 2));    
            }
                $no++;
                $fila++;
        }
        
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, '=SUM(D'.$inicio.':D'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, '=SUM(E'.$inicio.':E'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, '=SUM(F'.$inicio.':F'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, '=SUM(G'.$inicio.':G'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, '=SUM(H'.$inicio.':H'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('I'.$fila, '=SUM(I'.$inicio.':I'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('J'.$fila, '=SUM(J'.$inicio.':J'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('K'.$fila, '=SUM(K'.$inicio.':K'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('L'.$fila, '=SUM(L'.$inicio.':L'.($fila - 1).')');
        
        if($this->session->userdata('superuser') == 1){
            $this->excel->getActiveSheet()->setCellValue('M'.$fila, '=SUM(M'.$inicio.':M'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('O'.$fila, '=SUM(O'.$inicio.':O'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('P'.$fila, '=SUM(P'.$inicio.':P'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('Q'.$fila, '=SUM(Q'.$inicio.':Q'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('R'.$fila, '=SUM(R'.$inicio.':R'.($fila - 1).')');
        
        }

        $this->excel->getActiveSheet()->getStyle('N'.$inicio.':S'.($fila))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->excel->getActiveSheet()->getStyle('D'.$inicio.':M'.($fila))->getNumberFormat()->setFormatCode('#,##0');
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                ),
            ),
        );
        
        $this->excel->getActiveSheet()->getStyle('A'.($inicio).':O'.($fila - 1))->getAlignment()->setWrapText(true);
        
        $this->excel->createSheet();

        $this->excel->setActiveSheetIndex(1);
        $this->excel->getActiveSheet()->setTitle('Grafica');
        
        $dataseriesLabels1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'DISTRIBUCION', NULL, 1));
        $xAxisTickValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', $reporte.'!$D$5:$L$5', NULL, 9));
        $dataSeriesValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('Number', $reporte.'!$D$'.$fila.':$L$'.$fila, NULL, 9));

        $series1 = new PHPExcel_Chart_DataSeries(
        	PHPExcel_Chart_DataSeries::TYPE_PIECHART,				// plotType
        	PHPExcel_Chart_DataSeries::GROUPING_STANDARD,			// plotGrouping
        	range(0, count($dataSeriesValues1)-1),					// plotOrder
        	$dataseriesLabels1,										// plotLabel
        	$xAxisTickValues1,										// plotCategory
        	$dataSeriesValues1										// plotValues
        );
        
        $layout1 = new PHPExcel_Chart_Layout();
        $layout1->setShowVal(TRUE);
        $layout1->setShowPercent(TRUE);
        
        $plotarea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
        $legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
        $title1 = new PHPExcel_Chart_Title('DISTRIBUCION');
        
        //	Create the chart
        $chart1 = new PHPExcel_Chart(
        	'chart1',		// name
        	$title1,		// title
        	$legend1,		// legend
        	$plotarea1,		// plotArea
        	true,			// plotVisibleOnly
        	0,				// displayBlanksAs
        	NULL,			// xAxisLabel
        	NULL			// yAxisLabel		- Pie charts don't have a Y-Axis
        );
        
        //	Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('A1');
        $chart1->setBottomRightPosition('H20');
        $this->excel->getActiveSheet()->addChart($chart1);
        }else{
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, "NO HAY DATOS QUE MOSTRAR.");
        }

        $filename = $reporte.date('Ymd_his').'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->setIncludeCharts(TRUE);
        $objWriter->save('php://output');      
    }
    
    function Medico_Excel($reporte){
        ini_set("memory_limit","2048M");
        $query = $this->reportes_model->executeQuery($reporte);
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array( 'memoryCacheSize' => '512MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle($reporte);
        $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A1:M1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A2', 'REPORTE DE MEDICAMENTO Y MATERIAL DE CURACIÓN ENTREGADO A LOS');
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A2:M2');
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A3', 'SERVICIO DE SALUD DE MICHOACÁN, CORRESPONDIENTES AL PERIODO DEL');
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A3:M3');
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A4', "" . ' AL ' . "" );
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A4:M4');
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A5', '#');
        $this->excel->getActiveSheet()->setCellValue('B5', 'FECHA');
        $this->excel->getActiveSheet()->setCellValue('C5', 'FOLIO');
        $this->excel->getActiveSheet()->setCellValue('D5', 'PROGRAMA');
        $this->excel->getActiveSheet()->setCellValue('E5', 'CLAVE PACIENTE');
        $this->excel->getActiveSheet()->setCellValue('F5', 'PACIENTE');
        $this->excel->getActiveSheet()->setCellValue('G5', 'CLAVE');
        $this->excel->getActiveSheet()->setCellValue('H5', 'DESCRIPCIOM');
        $this->excel->getActiveSheet()->setCellValue('I5', 'REQUERIDAS');
        $this->excel->getActiveSheet()->setCellValue('J5', 'SURTIDAS');
    
        $this->excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('I5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('J5')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(90);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        
        $this->excel->getActiveSheet()->getRowDimension('5')->setRowHeight(40);

        $this->excel->getActiveSheet()->getStyle('A5:J5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
        $this->excel->getActiveSheet()->getStyle('A5:J5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $this->excel->getActiveSheet()->getStyle('A5:J5')->getAlignment()->setWrapText(true);
        
        if($this->session->userdata('superuser') == 1){

            $this->excel->getActiveSheet()->mergeCells('A1:P1');
            $this->excel->getActiveSheet()->mergeCells('A2:P2');
            $this->excel->getActiveSheet()->mergeCells('A3:P3');
            $this->excel->getActiveSheet()->mergeCells('A4:P4');
    
            $this->excel->getActiveSheet()->setCellValue('K5', 'PRECIO UNITARIO');
            $this->excel->getActiveSheet()->setCellValue('L5', 'IMPORTE');
            $this->excel->getActiveSheet()->setCellValue('M5', 'IVA');
            $this->excel->getActiveSheet()->setCellValue('N5', 'SERVICIO');
            $this->excel->getActiveSheet()->setCellValue('O5', 'IVA SERVICIO');
            $this->excel->getActiveSheet()->setCellValue('P5', 'SUBTOTAL');
            
            $this->excel->getActiveSheet()->getStyle('K5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('L5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('M5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('N5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('O5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('P5')->getFont()->setBold(true);
            
            $this->excel->getActiveSheet()->getStyle('K5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('L5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('M5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('N5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('O5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('P5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
    
            $this->excel->getActiveSheet()->getStyle('K5:P5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
            $this->excel->getActiveSheet()->getStyle('K5:P5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('K5:P5')->getAlignment()->setWrapText(true);
        }
        $inicio = 6;
        $fila = $inicio;
        $no = 1;  
        $req = 0;
        $sur = 0;
        $tImporte = 0;
        $tIVA = 0;
        $tServicio = 0;
        $tServicioIVA = 0;
        $total = 0;    
        if($query->num_rows() > 0){
        foreach($query->result() as $row)
        {
            
            $piezas = $row->cansur;
            $importe = $row->preciosinser * $piezas;
            if((int)$row->tipoprod == 1){
               $iva = $row->preciosinser * $piezas * .16;
            }else{$iva = 0;}
            $servicio = $piezas * 8.55;
            $servicio_iva = $servicio * .16;
            $subtotal = $importe + $iva + $servicio + $servicio_iva;
            $tImporte = $tImporte + $importe;
            $tIVA = $tIVA + $iva;
            $tServicio = $tServicio + $servicio;
            $tServicioIVA = $tServicioIVA + $servicio_iva;
            $total = $total + $subtotal;    
                                    
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $no);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $row->fecha);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, utf8_encode($row->folioreceta));
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, utf8_encode($row->programa));
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, utf8_encode($row->cvepaciente));
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, utf8_encode($row->paciente));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, utf8_encode($row->cvearticulo));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, utf8_encode($row->completo));
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format ($row->canreq, 0));
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, number_format ($row->cansur, 0));
                
            if($this->session->userdata('superuser') == 1){
                $this->excel->getActiveSheet()->setCellValue('K'.$fila,number_format($row->preciosinser, 2));
                $this->excel->getActiveSheet()->setCellValue('L'.$fila,number_format($importe, 2));
                $this->excel->getActiveSheet()->setCellValue('M'.$fila,number_format($iva, 2));
                $this->excel->getActiveSheet()->setCellValue('N'.$fila,number_format($servicio, 2));
                $this->excel->getActiveSheet()->setCellValue('O'.$fila,number_format($servicio_iva, 2));
                $this->excel->getActiveSheet()->setCellValue('P'.$fila,number_format($subtotal, 2));
            }
             $no++;
             $fila++;
        }
        
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, '=SUM(D'.$inicio.':D'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, '=SUM(E'.$inicio.':E'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, '=SUM(F'.$inicio.':F'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, '=SUM(G'.$inicio.':G'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, '=SUM(H'.$inicio.':H'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('I'.$fila, '=SUM(I'.$inicio.':I'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('J'.$fila, '=SUM(J'.$inicio.':J'.($fila - 1).')');
        
        if($this->session->userdata('superuser') == 1){
            $this->excel->getActiveSheet()->setCellValue('K'.$fila, '=SUM(K'.$inicio.':K'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('L'.$fila, '=SUM(L'.$inicio.':L'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('M'.$fila, '=SUM(M'.$inicio.':M'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('N'.$fila, '=SUM(O'.$inicio.':O'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('O'.$fila, '=SUM(P'.$inicio.':P'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('P'.$fila, '=SUM(Q'.$inicio.':Q'.($fila - 1).')');
        }

        $this->excel->getActiveSheet()->getStyle('N'.$inicio.':S'.($fila))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->excel->getActiveSheet()->getStyle('D'.$inicio.':M'.($fila))->getNumberFormat()->setFormatCode('#,##0');
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                ),
            ),
        );
        
        $this->excel->getActiveSheet()->getStyle('A'.($inicio).':O'.($fila - 1))->getAlignment()->setWrapText(true);
        
        $this->excel->createSheet();

        $this->excel->setActiveSheetIndex(1);
        $this->excel->getActiveSheet()->setTitle('Grafica');
        
        $dataseriesLabels1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'DISTRIBUCION', NULL, 1));
        $xAxisTickValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', $reporte.'!$D$5:$L$5', NULL, 9));
        $dataSeriesValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('Number', $reporte.'!$D$'.$fila.':$L$'.$fila, NULL, 9));

        $series1 = new PHPExcel_Chart_DataSeries(
        	PHPExcel_Chart_DataSeries::TYPE_PIECHART,				// plotType
        	PHPExcel_Chart_DataSeries::GROUPING_STANDARD,			// plotGrouping
        	range(0, count($dataSeriesValues1)-1),					// plotOrder
        	$dataseriesLabels1,										// plotLabel
        	$xAxisTickValues1,										// plotCategory
        	$dataSeriesValues1										// plotValues
        );
        
        $layout1 = new PHPExcel_Chart_Layout();
        $layout1->setShowVal(TRUE);
        $layout1->setShowPercent(TRUE);
        
        $plotarea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
        $legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
        $title1 = new PHPExcel_Chart_Title('DISTRIBUCION');
        
        //	Create the chart
        $chart1 = new PHPExcel_Chart(
        	'chart1',		// name
        	$title1,		// title
        	$legend1,		// legend
        	$plotarea1,		// plotArea
        	true,			// plotVisibleOnly
        	0,				// displayBlanksAs
        	NULL,			// xAxisLabel
        	NULL			// yAxisLabel		- Pie charts don't have a Y-Axis
        );
        
        //	Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('A1');
        $chart1->setBottomRightPosition('H20');
        $this->excel->getActiveSheet()->addChart($chart1);
        }else{
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, "NO HAY DATOS QUE MOSTRAR.");
        }

        $filename = $reporte.date('Ymd_his').'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->setIncludeCharts(TRUE);
        $objWriter->save('php://output');    
    }
    
    function periodoExcel($reporte){
        ini_set("memory_limit","2048M");
        $query = $this->reportes_model->executeQuery($reporte);
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array( 'memoryCacheSize' => '512MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle($reporte);
        $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A1:M1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A2', 'REPORTE DE MEDICAMENTO Y MATERIAL DE CURACIÓN ENTREGADO A LOS');
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A2:M2');
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A3', 'SERVICIO DE SALUD DE MICHOACÁN, CORRESPONDIENTES AL PERIODO DEL');
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A3:M3');
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A4', "" . ' AL ' . "" );
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A4:M4');
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->setCellValue('A5', '#');
        $this->excel->getActiveSheet()->setCellValue('B5', 'FOLIO RECETA');
        $this->excel->getActiveSheet()->setCellValue('C5', 'UNIDAD');
        $this->excel->getActiveSheet()->setCellValue('D5', 'CLAVE PACIENTE');
        $this->excel->getActiveSheet()->setCellValue('E5', 'NOMBRE');
        $this->excel->getActiveSheet()->setCellValue('F5', 'PATERNO');
        $this->excel->getActiveSheet()->setCellValue('G5', 'MATERNO');
        $this->excel->getActiveSheet()->setCellValue('H5', 'CLAVE ARTICULO');
        $this->excel->getActiveSheet()->setCellValue('I5', 'DESCRIPCION');
        $this->excel->getActiveSheet()->setCellValue('J5', 'CLAVE MEDICO');
        $this->excel->getActiveSheet()->setCellValue('K5', 'NOMBRE MEDICO');
        $this->excel->getActiveSheet()->setCellValue('L5', 'PROGRAMA');
        $this->excel->getActiveSheet()->setCellValue('M5', 'REQUERIMIENTO');
        $this->excel->getActiveSheet()->setCellValue('N5', 'FECHA CONSULTA');
        $this->excel->getActiveSheet()->setCellValue('O5', 'FECHA SURTIDO');
        $this->excel->getActiveSheet()->setCellValue('P5', 'CANTIDAD REQUERIDA');
        $this->excel->getActiveSheet()->setCellValue('Q5', 'CANTIDAD SURTIDA');
    
        $this->excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('I5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('J5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('K5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('L5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('M5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('N5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('O5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('P5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('Q5')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('K5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('L5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('M5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('N5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('O5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('P5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('Q5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(90);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
        
        $this->excel->getActiveSheet()->getRowDimension('5')->setRowHeight(40);

        $this->excel->getActiveSheet()->getStyle('A5:Q5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
        $this->excel->getActiveSheet()->getStyle('A5:Q5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $this->excel->getActiveSheet()->getStyle('A5:Q5')->getAlignment()->setWrapText(true);
        
        if($this->session->userdata('superuser') == 1){

            $this->excel->getActiveSheet()->mergeCells('A1:W1');
            $this->excel->getActiveSheet()->mergeCells('A2:W2');
            $this->excel->getActiveSheet()->mergeCells('A3:W3');
            $this->excel->getActiveSheet()->mergeCells('A4:W4');
    
            $this->excel->getActiveSheet()->setCellValue('R5', 'PRECIO UNITARIO');
            $this->excel->getActiveSheet()->setCellValue('S5', 'IMPORTE');
            $this->excel->getActiveSheet()->setCellValue('T5', 'IVA');
            $this->excel->getActiveSheet()->setCellValue('U5', 'SERVICIO');
            $this->excel->getActiveSheet()->setCellValue('V5', 'IVA SERVICIO');
            $this->excel->getActiveSheet()->setCellValue('W5', 'SUBTOTAL');
            
            $this->excel->getActiveSheet()->getStyle('R5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('S5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('T5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('U5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('V5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('W5')->getFont()->setBold(true);
            
            $this->excel->getActiveSheet()->getStyle('R5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('S5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('T5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('U5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('V5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('W5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
            $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(20);
    
            $this->excel->getActiveSheet()->getStyle('R5:W5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
            $this->excel->getActiveSheet()->getStyle('R5:W5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('R5:W5')->getAlignment()->setWrapText(true);
        }
        $inicio = 6;
        $fila = $inicio;
        $no = 1;  
        $cantidadsurtida = 0;
        $cantidadrequerida = 0;
        $tImporte = 0;
        $tIVA = 0;
        $tServicio = 0;
        $tServicioIVA = 0;
        $total = 0;
        if($query->num_rows() > 0){
        foreach($query->result() as $row)
        {
            
         $piezas = $row->cansur;
         $importe = $row->preciosinser * $piezas;
         if((int)$row->tipoprod == 1){
            $iva = $row->preciosinser * $piezas * IVA;
         }else{$iva = 0;}
         $servicio = $piezas * SERVICIO;
         $servicio_iva = $servicio * IVA;
         $subtotal = $importe + $iva + $servicio + $servicio_iva;
         $tImporte = $tImporte + $importe;
         $tIVA = $tIVA + $iva;
         $tServicio = $tServicio + $servicio;
         $tServicioIVA = $tServicioIVA + $servicio_iva;
         $total = $total + $subtotal;
                                    
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $no);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $row->folioreceta);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $row->descsucursal);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $row->cvepaciente);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, utf8_encode($row->nombre));
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, utf8_encode($row->apaterno));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, utf8_encode($row->amaterno));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, utf8_encode($row->cvearticulo));
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, utf8_encode($row->descripcion));
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, utf8_encode($row->cvemedico));
            $this->excel->getActiveSheet()->setCellValue('K'.$fila, utf8_encode($row->nombremedico));
            $this->excel->getActiveSheet()->setCellValue('L'.$fila, utf8_encode($row->programa));
            $this->excel->getActiveSheet()->setCellValue('M'.$fila, utf8_encode($row->requerimiento));
            $this->excel->getActiveSheet()->setCellValue('N'.$fila, $row->fecha);
            $this->excel->getActiveSheet()->setCellValue('O'.$fila, $row->fechaexp);
            $this->excel->getActiveSheet()->setCellValue('P'.$fila, number_format($row->canreq, 0));
            $this->excel->getActiveSheet()->setCellValue('Q'.$fila, number_format($row->cansur, 0));
                
            if($this->session->userdata('superuser') == 1){
                $this->excel->getActiveSheet()->setCellValue('R'.$fila,number_format($row->preciosinser, 2));
                $this->excel->getActiveSheet()->setCellValue('S'.$fila,number_format($importe, 2));
                $this->excel->getActiveSheet()->setCellValue('T'.$fila,number_format($iva, 2));
                $this->excel->getActiveSheet()->setCellValue('U'.$fila,number_format($servicio, 2));
                $this->excel->getActiveSheet()->setCellValue('V'.$fila,number_format($servicio_iva, 2));
                $this->excel->getActiveSheet()->setCellValue('W'.$fila,number_format($subtotal, 2));
            }
             $no++;
             $fila++;
        }
        
        $this->excel->getActiveSheet()->setCellValue('P'.$fila, '=SUM(D'.$inicio.':D'.($fila - 1).')');
        $this->excel->getActiveSheet()->setCellValue('Q'.$fila, '=SUM(E'.$inicio.':E'.($fila - 1).')');
        
        if($this->session->userdata('superuser') == 1){
            $this->excel->getActiveSheet()->setCellValue('R'.$fila, '=SUM(K'.$inicio.':K'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('S'.$fila, '=SUM(L'.$inicio.':L'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('T'.$fila, '=SUM(M'.$inicio.':M'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('U'.$fila, '=SUM(O'.$inicio.':O'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('V'.$fila, '=SUM(P'.$inicio.':P'.($fila - 1).')');
            $this->excel->getActiveSheet()->setCellValue('W'.$fila, '=SUM(Q'.$inicio.':Q'.($fila - 1).')');
        }

        $this->excel->getActiveSheet()->getStyle('N'.$inicio.':S'.($fila))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->excel->getActiveSheet()->getStyle('D'.$inicio.':M'.($fila))->getNumberFormat()->setFormatCode('#,##0');
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                ),
            ),
        );
        
        $this->excel->getActiveSheet()->getStyle('A'.($inicio).':O'.($fila - 1))->getAlignment()->setWrapText(true);
        
        $this->excel->createSheet();

        $this->excel->setActiveSheetIndex(1);
        $this->excel->getActiveSheet()->setTitle('Grafica');
        
        $dataseriesLabels1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'DISTRIBUCION', NULL, 1));
        $xAxisTickValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', $reporte.'!$D$5:$L$5', NULL, 9));
        $dataSeriesValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('Number', $reporte.'!$D$'.$fila.':$L$'.$fila, NULL, 9));

        $series1 = new PHPExcel_Chart_DataSeries(
        	PHPExcel_Chart_DataSeries::TYPE_PIECHART,				// plotType
        	PHPExcel_Chart_DataSeries::GROUPING_STANDARD,			// plotGrouping
        	range(0, count($dataSeriesValues1)-1),					// plotOrder
        	$dataseriesLabels1,										// plotLabel
        	$xAxisTickValues1,										// plotCategory
        	$dataSeriesValues1										// plotValues
        );
        
        $layout1 = new PHPExcel_Chart_Layout();
        $layout1->setShowVal(TRUE);
        $layout1->setShowPercent(TRUE);
        
        $plotarea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
        $legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
        $title1 = new PHPExcel_Chart_Title('DISTRIBUCION');
        
        //	Create the chart
        $chart1 = new PHPExcel_Chart(
        	'chart1',		// name
        	$title1,		// title
        	$legend1,		// legend
        	$plotarea1,		// plotArea
        	true,			// plotVisibleOnly
        	0,				// displayBlanksAs
        	NULL,			// xAxisLabel
        	NULL			// yAxisLabel		- Pie charts don't have a Y-Axis
        );
        
        //	Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('A1');
        $chart1->setBottomRightPosition('H20');
        $this->excel->getActiveSheet()->addChart($chart1);
        }else{
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, "NO HAY DATOS QUE MOSTRAR.");
        }

        $filename = $reporte.date('Ymd_his').'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->setIncludeCharts(TRUE);
        $objWriter->save('php://output');   
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    
    public function rsu()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/rsu_js";
        $this->load->view('main', $data);;
    }
    
    public function rsu_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        
        
        $data['query'] = $this->reportes_model->rsu_surtidas($fecha1, $fecha2);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }
    
    
     public function causes()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/causes_js";
        $data['causes'] = $this->reportes_model->getCausesCombo();
        $this->load->view('main', $data);;
    }
    
    public function causes_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $causes = $this->input->post('causes');
        
        
        $data['query'] = $this->reportes_model->claves_causes($fecha1, $fecha2, $causes);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }
    
    
     public function mayor()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/causes_js";
        $this->load->view('main', $data);;
    }
    
    public function mayor_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        
        
        $data['query'] = $this->reportes_model->claves_mayor_movimiento($fecha1, $fecha2);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }
    
     public function menor()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/causes_js";
        $this->load->view('main', $data);;
    }
    
    public function menor_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        
        
        $data['query'] = $this->reportes_model->claves_menor_movimiento($fecha1, $fecha2);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }
}