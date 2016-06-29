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
        $data['js'] = "cliente/clientes_reportes_js";
        $data['sucursal'] = $this->reportes_model->getSucursalesCliente();
        $data['programas'] = $this->reportes_model->getProgramas();
        $data['juris'] = $this->reportes_model->getJurisCliente();
        $data['tipo_sucursal'] = $this->reportes_model->getTipoSucursalCliente();
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $data['nivel_atencion'] = $this->reportes_model->getNivelAtencionCliente();
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
        $nivel_atencion = $this->input->post('nivel_atencion');
        $suministro = $this->input->post('suministro');
                
        $data['query'] = $this->reportes_model->getProgramaByAllCliente($fecha1, $fecha2, $suministro, $juris, $sucursal, $tipo_sucursal, $nivel_atencion);
        
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        $data['js'] = "reportes/programaAll_submit_js";
        $this->load->view('main', $data);
    }

    function programaAll2()
    {
        $data['subtitulo'] = "";
        $data['js'] = "cliente/clientes_reportes_js";
        $data['sucursal'] = $this->reportes_model->getSucursalesCliente();
        $data['programas'] = $this->reportes_model->getProgramas();
        $data['juris'] = $this->reportes_model->getJurisCliente();
        $data['tipo_sucursal'] = $this->reportes_model->getTipoSucursalCliente();
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $data['nivel_atencion'] = $this->reportes_model->getNivelAtencionCliente();
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
        $nivel_atencion = $this->input->post('nivel_atencion');
        $suministro = $this->input->post('suministro');
        $idprograma = $this->input->post('idprograma');
        //$todo = $this->input->post('todo');
        $data['query'] = $this->reportes_model->getProgramaByProgramaByAllCliente($fecha1, $fecha2, $suministro, $idprograma, $juris, $sucursal, $tipo_sucursal, $nivel_atencion);
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        $data['js'] = "reportes/grafica";
        $this->load->view('main', $data);
    }

    function claveAll()
    {
        $data['subtitulo'] = "";
        $data['js'] = "cliente/clientes_reportes_js";
        $data['sucursal'] = $this->reportes_model->getSucursalesCliente();
        $data['programas'] = $this->reportes_model->getProgramas();
        $data['juris'] = $this->reportes_model->getJurisCliente();
        $data['tipo_sucursal'] = $this->reportes_model->getTipoSucursalCliente();
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $data['nivel_atencion'] = $this->reportes_model->getNivelAtencionCliente();
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
        $nivel_atencion = $this->input->post('nivel_atencion');
        $idprograma = $this->input->post('idprograma');
        $clave = $this->input->post('cveArticulo');
        
        $data['clave'] = $clave;
        $data['completo'] = $this->reportes_model->getCompletoByCvearticulo($clave);
        $data['query'] = $this->reportes_model->getByClaveByAllCliente($fecha1, $fecha2, $sucursal, $clave, $idprograma, $juris, $tipo_sucursal, $nivel_atencion, $clave, $data['completo']);
        
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        $data['js'] = "reportes/grafica";
        //$data['js'] = "metro/remision_concentrado_js";
        $this->load->view('main', $data);
 
        
    }

    function pacienteAll()
    {
        $data['subtitulo'] = "";
        $data['js'] = "cliente/clientes_reportes_js";
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $data['sucursal'] = $this->reportes_model->getSucursalesCliente();
        $data['programas'] = $this->reportes_model->getProgramas();
        $data['juris'] = $this->reportes_model->getJurisCliente();
        $data['tipo_sucursal'] = $this->reportes_model->getTipoSucursalCliente();
        $data['nivel_atencion'] = $this->reportes_model->getNivelAtencionCliente();
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
        $tipo_sucursal = $this->input->post('tipo_sucursal');
        $nivel_atencion = $this->input->post('nivel_atencion');
        
        $data['expediente'] = $expediente;
        $data['paciente'] = $this->reportes_model->getPacienteByCvepacienteJur($expediente);
        $data['query'] = $this->reportes_model->getByCvePacienteAllCliente($expediente, $fecha1, $fecha2, $sucursal, $suministro, $juris, $tipo_sucursal, $nivel_atencion, $expediente, $data['paciente']);
        
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        $data['js'] = "reportes/grafica";
        //$data['js'] = "metro/remision_concentrado_js";
        $this->load->view('main', $data);
    }

    function medicoAll(){
        $data['subtitulo'] = "";
        $data['js'] = "cliente/clientes_reportes_js";
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $data['sucursal'] = $this->reportes_model->getSucursalesCliente();
        $data['programas'] = $this->reportes_model->getProgramas();
        $data['juris'] = $this->reportes_model->getJurisCliente();
        $data['tipo_sucursal'] = $this->reportes_model->getTipoSucursalCliente();
        $data['nivel_atencion'] = $this->reportes_model->getNivelAtencionCliente();
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
        $tipo_sucursal = $this->input->post('tipo_sucursal');
        $nivel_atencion = $this->input->post('nivel_atencion');



        $data['cveMedico'] = $cveMedico;
        $data['medico'] = $this->reportes_model->getNombreMedicoByCveMedicoJur($cveMedico);
        $data['query'] = $this->reportes_model->getByCveMedicoAllCliente($cveMedico, $fecha1, $fecha2, $sucursal, $suministro, $juris, $tipo_sucursal, $nivel_atencion, $cveMedico, $data['medico']);
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        $data['js'] = "reportes/grafica";
        //$data['js'] = "metro/remision_concentrado_js";
        $this->load->view('main', $data);
    }

    function recetas_periodoAll(){
        $data['subtitulo'] = "";
        $data['js'] = "cliente/clientes_reportes_js";
        $data['sucursal'] = $this->reportes_model->getSucursalesCliente();
        $data['programas'] = $this->reportes_model->getProgramas();
        $data['juris'] = $this->reportes_model->getJurisCliente();
        $data['tipo_sucursal'] = $this->reportes_model->getTipoSucursalCliente();
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $data['nivel_atencion'] = $this->reportes_model->getNivelAtencionCliente();
        $this->load->view('main', $data);
    }
    
    function recetas_periodo_detalleAll(){
        ini_set("memory_limit","1024M");

        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');

        $juris = $this->input->post('juris');
        $sucursal = $this->input->post('sucursal');
        $tipo_sucursal = $this->input->post('tipo_sucursal');
        $nivel_atencion = $this->input->post('nivel_atencion');
        $suministro = $this->input->post('suministro');
        $idprograma = $this->input->post('idprograma');

        $data['query'] = $this->reportes_model->recetas_periodo_detalleAllCliente($fecha1, $fecha2, $juris, $sucursal, $tipo_sucursal, $nivel_atencion, $suministro, $idprograma);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }

    public function rsu()
    {
        $data['subtitulo'] = "";
        $data['js'] = "cliente/clientes_reportes_js";
        $data['sucursal'] = $this->reportes_model->getSucursalesCliente();
        $data['programas'] = $this->reportes_model->getProgramas();
        $data['juris'] = $this->reportes_model->getJurisCliente();
        $data['tipo_sucursal'] = $this->reportes_model->getTipoSucursalCliente();
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $data['nivel_atencion'] = $this->reportes_model->getNivelAtencionCliente();
        $this->load->view('main', $data);;
    }
    
    public function rsu_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $juris = $this->input->post('juris');
        $sucursal = $this->input->post('sucursal');
        $tipo_sucursal = $this->input->post('tipo_sucursal');
        $nivel_atencion = $this->input->post('nivel_atencion');
        $suministro = $this->input->post('suministro');
        $idprograma = $this->input->post('idprograma');
        
        
        $data['query'] = $this->reportes_model->rsu_surtidasCliente($fecha1, $fecha2, $juris, $sucursal, $tipo_sucursal, $nivel_atencion, $suministro, $idprograma);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }
    
    
    function causes()
    {
        $data['subtitulo'] = "";
        $data['js'] = "cliente/clientes_reportes_js";
        $data['sucursal'] = $this->reportes_model->getSucursalesCliente();
        $data['programas'] = $this->reportes_model->getProgramas();
        $data['juris'] = $this->reportes_model->getJurisCliente();
        $data['tipo_sucursal'] = $this->reportes_model->getTipoSucursalCliente();
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $data['nivel_atencion'] = $this->reportes_model->getNivelAtencionCliente();
        $data['causes'] = $this->reportes_model->getCausesCombo();
        $this->load->view('main', $data);;
    }
    
    function causes_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $causes = $this->input->post('causes');
        $juris = $this->input->post('juris');
        $sucursal = $this->input->post('sucursal');
        $tipo_sucursal = $this->input->post('tipo_sucursal');
        $nivel_atencion = $this->input->post('nivel_atencion');
        $suministro = $this->input->post('suministro');
        
        
        $data['query'] = $this->reportes_model->claves_causesCliente($fecha1, $fecha2, $causes, $juris, $sucursal, $tipo_sucursal, $nivel_atencion, $suministro);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }
    
    
    function mayor()
    {
        $data['subtitulo'] = "";
        $data['js'] = "cliente/clientes_reportes_js";
        $data['sucursal'] = $this->reportes_model->getSucursalesCliente();
        $data['programas'] = $this->reportes_model->getProgramas();
        $data['juris'] = $this->reportes_model->getJurisCliente();
        $data['tipo_sucursal'] = $this->reportes_model->getTipoSucursalCliente();
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $data['nivel_atencion'] = $this->reportes_model->getNivelAtencionCliente();
        $this->load->view('main', $data);;
    }
    
    function mayor_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $juris = $this->input->post('juris');
        $sucursal = $this->input->post('sucursal');
        $tipo_sucursal = $this->input->post('tipo_sucursal');
        $nivel_atencion = $this->input->post('nivel_atencion');
        $suministro = $this->input->post('suministro');
        $idprograma = $this->input->post('idprograma');
        
        
        $data['query'] = $this->reportes_model->claves_mayor_movimientoCliente($fecha1, $fecha2, $juris, $sucursal, $tipo_sucursal, $nivel_atencion, $suministro, $idprograma);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }
    
    function menor()
    {
        $data['subtitulo'] = "";
        $data['js'] = "cliente/clientes_reportes_js";
        $data['sucursal'] = $this->reportes_model->getSucursalesCliente();
        $data['programas'] = $this->reportes_model->getProgramas();
        $data['juris'] = $this->reportes_model->getJurisCliente();
        $data['tipo_sucursal'] = $this->reportes_model->getTipoSucursalCliente();
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $data['nivel_atencion'] = $this->reportes_model->getNivelAtencionCliente();
        $this->load->view('main', $data);;
    }
    
    function menor_submit()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $juris = $this->input->post('juris');
        $sucursal = $this->input->post('sucursal');
        $tipo_sucursal = $this->input->post('tipo_sucursal');
        $nivel_atencion = $this->input->post('nivel_atencion');
        $suministro = $this->input->post('suministro');
        $idprograma = $this->input->post('idprograma');
        
        
        $data['query'] = $this->reportes_model->claves_menor_movimientoCliente($fecha1, $fecha2, $juris, $sucursal, $tipo_sucursal, $nivel_atencion, $suministro, $idprograma);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }

    function getSucursales()
    {
    	$juris = $this->input->post('juris');
    	$tipo_sucursal = $this->input->post('tipo_sucursal');
    	$nivel_atencion = $this->input->post('nivel_atencion');

    	echo $this->reportes_model->getSucursalesClienteSelect($juris, $tipo_sucursal, $nivel_atencion);
    }

}
    