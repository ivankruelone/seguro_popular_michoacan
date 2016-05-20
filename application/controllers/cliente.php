<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cliente extends CI_Controller
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

    function inventario()
    {
        $data['subtitulo'] = "Inventario de las sucursales";
        $data['valuacion'] = $this->valuacion;
        $data['query'] = $this->reportes_model->getInventarioGroupBySucursal();
        $this->load->view('main', $data);
    }
    
    function inventario_detalle($clvsucursal)
    {
        $data['subtitulo'] = "Inventario a detalle.";
        $data['valuacion'] = $this->valuacion;
        $data['query'] = $this->reportes_model->getInvetarioDetalleBySucursal($clvsucursal);
        $this->load->view('main', $data);
    }

    function inventario_total()
    {
        $data['subtitulo'] = "Inventario total por clave";
        $data['valuacion'] = $this->valuacion;
        $data['query'] = $this->reportes_model->getInventarioTotalByClave();
        $this->load->view('main', $data);
    }

    function programaAll()
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

    function programaAll2()
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

    function pacienteAll()
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

    function medicoAll(){
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

    function recetas_periodoAll(){
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
    
    function recetas_periodo_detalleAll(){
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
    
    
    function causes()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/causes_js";
        $data['causes'] = $this->reportes_model->getCausesCombo();
        $this->load->view('main', $data);;
    }
    
    function causes_submit()
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
    
    
    function mayor()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/causes_js";
        $this->load->view('main', $data);;
    }
    
    function mayor_submit()
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
    
    function menor()
    {
        $data['subtitulo'] = "";
        $data['js'] = "reportes/causes_js";
        $this->load->view('main', $data);;
    }
    
    function menor_submit()
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
    