<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administracion extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();

        if (!Current_User::user()) {
            redirect('welcome');
        }

        $this->load->model('admin_model');
        $this->load->helper('utilities');

    }
    
    public function usuario()
    {
        $data['subtitulo'] = "Usuarios del sistema";
        $data['query'] = $this->admin_model->getUsuario();
        //$data['js'] = "movimiento/nuevo_js";
        $this->load->view('main', $data);
    }

    function getjuris()
    {
    	$nivelUsuario = $this->input->post('nivelUsuario');
    	echo $this->admin_model->getJurisOptions($nivelUsuario);
    }

    function getSucursales()
    {
    	$nivelUsuario = $this->input->post('nivelUsuario');
    	$numjurisd = $this->input->post('numjurisd');
    	echo $this->admin_model->getSucursalesOptions($nivelUsuario, $numjurisd);
    }
    
    function getPuesto()
    {
        $nivelUsuario = $this->input->post('nivelUsuario');
        echo $this->admin_model->getPuestoOptions($nivelUsuario);
    }

    function usuario_nuevo()
    {
        $data['subtitulo'] = "Usuarios del sistema";
        $data['clvpuesto'] = $this->admin_model->getPuestoCombo();
        $data['clvsucursal'] = $this->util->getSucursalesCombo();
        $data['jurisd'] = $this->util->getJurisCombo();
        $data['clvnivel'] = $this->admin_model->getNivelUsuario();
        $data['valuacion'] = $this->admin_model->getValuacionUsuario();
        $data['js'] = "administracion/usuario_nuevo_js";
        $this->load->view('main', $data);    
    }

    function usuario_nuevo_submit()
    {
        $clvusuario = trim($this->input->post('clvusuario'));
        $password = trim($this->input->post('password'));
        $nombreusuario = trim($this->input->post('nombreusuario'));
        $clvsucursal = $this->input->post('clvsucursal');
        $clvpuesto = $this->input->post('clvpuesto');
        $clvnivel = $this->input->post('clvnivel');
        $numjurisd = $this->input->post('numjurisd');
        $this->admin_model->insertaUsuario($clvusuario, $password, $nombreusuario, $clvsucursal, $clvpuesto, $clvnivel, $numjurisd);
        redirect('administracion/usuario');
    }

    function usuario_permisos($usuario)
    {
        $data['subtitulo'] = "Usuarios del sistema: Asigna Permisos";
        $data['query'] = $this->admin_model->getPermisosByUsuario($usuario);
        $data['usuario'] = $usuario;
        $data['js'] = "administracion/usuario_permisos_js";
        $this->load->view('main', $data);
    }
    
    function savePermiso()
    {
        $usuario = $this->input->post('usuario');
        $submenu = $this->input->post('submenu');
        
        $this->admin_model->savePermiso($usuario, $submenu);
    }

    public function profile()
    {
        $data['subtitulo'] = "Perfil de usuario";
        $data['query'] = $this->admin_model->getUsuarioByUsuario();
        $data['js'] = "administracion/profile_js";
        $this->load->view('main', $data);
    }
    
    function change_password()
    {
        $data['subtitulo'] = "Perfil de usuario";
        //$data['js'] = "administracion/profile_js";
        $this->load->view('main', $data);
    }
    
    function change_password_submit()
    {
        $oldP = $this->input->post('oldP');
        $password1 = $this->input->post('password1');
        $password2 = $this->input->post('password2');
        
        
        $checkOld = $this->admin_model->checkOldPassword($oldP);
        
        if((int)$checkOld > 0)
        {
            if($password1 == $password2)
            {
                $this->admin_model->saveNewPassword($password1);
                $this->session->set_flashdata('correcto', 'Password cambiado correctamente, el proximo inicio de sesion deberas ponerlo.');
                redirect('administracion/change_password');
            }else{
                $this->session->set_flashdata('error', 'El password nuevo no coicide ambas veces.');
                redirect('administracion/change_password');
            }
        }else{
            $this->session->set_flashdata('error', 'El password anterior es incorrecto.');
            redirect('administracion/change_password');
        }
        
    }
    
    function upload_avatar()
    {
        $uploaddir = './assets/avatars/';
        $file = basename($_FILES['userfile']['name']);
        $uploadfile = $uploaddir . $file;

        $config['image_library'] = 'gd2';
        $config['source_image'] = $uploadfile;
        $config['create_thumb'] = false;
        $config['maintain_ratio'] = true;
        $config['width'] = 400;
        $config['height'] = 400;
        $config['master_dim'] = 'auto';

        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {

            $this->load->library('image_lib', $config);
            $this->image_lib->resize();

            echo $this->admin_model->update_avatar($file);
        } else {
            echo "error";
        }
    
    }

    function sucursal()
    {
        $data['subtitulo'] = "Catalogo de sucursales";
        $data['tipos'] = $this->util->getTipoSucursal();
        //$data['js'] = "catalogosweb/productos_por_secuencia_js";
        $this->load->view('main', $data);
    }

    function sucursal_edita($clvsucursal)
    {
        $data['subtitulo'] = "Edita Sucursal";
        $data['js'] = "administracion/sucursal_js";
        $data['query'] = $this->util->getSucursalByClvsucursal($clvsucursal);
        $data['query2'] = $this->util->getSucursalExtByClvsucursal($clvsucursal);
        $data['juris'] = $this->util->getJurisCombo();
        $data['dia'] = $this->util->getDiaCombo();
        $data['nivelAtencion'] = $this->util->getNivelAtencionCombo();
        $this->load->view('main', $data);
    }

    function sucursal_edita_submit()
    {
        //nombreSucursalPersonalizado, domicilioSucursalPersonalizado
        $clvsucursal = trim($this->input->post('clvsucursal'));
        $nombreSucursalPersonalizado = trim($this->input->post('nombreSucursalPersonalizado'));
        $domicilioSucursalPersonalizado = trim($this->input->post('domicilioSucursalPersonalizado'));
        $numjurisd = $this->input->post('numjurisd');
        $nivelAtencion = $this->input->post('nivelAtencion');
        $diaped = $this->input->post('diaped');
        $director = $this->input->post('director');
        $administrador = $this->input->post('administrador');

        $this->admin_model->actualizaSucursal($clvsucursal, $numjurisd, $nombreSucursalPersonalizado, $domicilioSucursalPersonalizado, $nivelAtencion, $diaped, $director, $administrador);
        $this->util->actNombreSucursal();
        redirect('administracion/sucursal');
    }

    function sucursal_servicios($clvsucursal)
    {
        $data['subtitulo'] = "Configura los servicios";
        $data['js'] = "administracion/sucursal_servicios_js";
        $data['query'] = $this->admin_model->getServiciosByClvSucursal($clvsucursal);
        $data['clvsucursal'] = $clvsucursal;
        $data['sucursal'] = $this->util->getSucursalNombreByClvSucursal($clvsucursal);
        $this->load->view('main', $data);
    }

    function saveSucursalServicio()
    {
        $clvsucursal = $this->input->post('clvsucursal');
        $cveservicios = $this->input->post('cveservicios');

        $this->admin_model->guardaSucursalServicio($clvsucursal, $cveservicios);
    }

    function articulo($cvesuministro)
    {
        $this->load->library('pagination');
        $this->load->model('Catalogosweb_model');

        $config['base_url'] = site_url('administracion/articulo/'.$cvesuministro);
        $config['total_rows'] = $this->Catalogosweb_model->getCountArticulo($cvesuministro);
        $config['per_page'] = 500;
        $config['uri_segment'] = 4;
        
        $this->pagination->initialize($config); 
        $data['subtitulo'] = "Catalogo de articulos";
        $data['query'] = $this->Catalogosweb_model->getArticulosLimit($cvesuministro, $config['per_page'], $this->uri->segment(4));
        $data['numReg'] = $this->admin_model->getCountNivelServicios();
        $data['programa'] = $this->admin_model->getPrograma();
        $data['nivelAtencion'] = $this->admin_model->getNivelAtencion();
        $data['js'] = "administracion/articulo_js";
        $this->load->view('main', $data);
    }

    function saveArticuloCobertura()
    {
        $id = $this->input->post('id');
        $idprograma = $this->input->post('idprograma');
        $nivelatencion = $this->input->post('nivelatencion');

        $this->admin_model->guardaArticuloCobertura($id, $idprograma, $nivelatencion);

    }

    function buffer()
    {
        $data['subtitulo'] = "Buffer de sucursales y almacen";
        $data['query'] = $this->util->getSucursalFarmacia();
        //$data['js'] = "catalogosweb/productos_por_secuencia_js";
        $this->load->view('main', $data);
    }

    function buffer_edita($tipoprod, $clvsucursal)
    {
        $this->admin_model->addBufferByClvSucursal($clvsucursal);
        $this->load->library('pagination');
        $this->load->model('Catalogosweb_model');

        $config['base_url'] = site_url('administracion/buffer_edita/'.$tipoprod.'/'.$clvsucursal);
        $config['total_rows'] = $this->admin_model->getCountArticuloBuffer($clvsucursal, $tipoprod);
        $config['per_page'] = 500;
        $config['uri_segment'] = 5;
        
        $this->pagination->initialize($config); 
        $data['subtitulo'] = "Catalogo de articulos";
        $data['query'] = $this->admin_model->getArticulosLimitBuffer($tipoprod, $clvsucursal, $config['per_page'], $this->uri->segment(5));
        $data['js'] = "administracion/articulo_js";
        $data['clvsucursal'] = $clvsucursal;
        $this->load->view('main', $data);
    }

    function saveBuffer()
    {
        $id = $this->input->post('id');
        $clvsucursal = $this->input->post('clvsucursal');
        $buffer = $this->input->post('buffer');

        $this->admin_model->guardaBuffer($id, $clvsucursal, $buffer);
    }

    function perfiles()
    {
        $data['subtitulo'] = "Administracion de Perfiles";
        $data['query'] = $this->admin_model->getPuesto();
        //$data['js'] = "movimiento/nuevo_js";
        $this->load->view('main', $data);
    }

    function perfil_nuevo()
    {
        $data['subtitulo'] = "Nuevo perfil de usuario";
        $data['clvnivel'] = $this->admin_model->getNivelUsuario();
        $data['valuacion'] = $this->admin_model->getValuacionUsuario();
        //$data['js'] = "administracion/usuario_nuevo_js";
        $this->load->view('main', $data);    
    }

    function perfil_nuevo_submit()
    {
        $puesto = $this->input->post('puesto');
        $nivelUsuarioID = $this->input->post('nivelUsuarioID');
        $valuacion = $this->input->post('valuacion');
        $consulta = $this->input->post('consulta');

        $this->admin_model->insertPuesto($puesto, $nivelUsuarioID, $valuacion, $consulta);
        redirect('administracion/perfiles');
    }

    function perfil_permisos($clvpuesto)
    {
        $data['subtitulo'] = "Perfiles del sistema: Asigna Permisos";
        $data['query'] = $this->admin_model->getPermisosByClvPuesto($clvpuesto);
        $data['clvpuesto'] = $clvpuesto;
        $data['js'] = "administracion/perfil_permisos_js";
        $this->load->view('main', $data);
    }

    function savePermisoPerfil()
    {
        $clvpuesto = $this->input->post('clvpuesto');
        $submenu = $this->input->post('submenu');
        
        $this->admin_model->savePermisoPerfil($clvpuesto, $submenu);
    }

    function perfil_bulk_puesto()
    {
        $clvpuesto = $this->input->post('clvpuesto');
        $this->admin_model->savePermisoByClvPuestoBulk($clvpuesto);
        redirect('administracion/perfiles');
    }

    function perfil_edita($clvpuesto)
    {
        $data['subtitulo'] = "Edita perfil de usuario";
        $data['clvnivel'] = $this->admin_model->getNivelUsuario();
        $data['valuacion'] = $this->admin_model->getValuacionUsuario();
        $data['query'] = $this->admin_model->getPerfilByClvPuesto($clvpuesto);
        //$data['js'] = "administracion/usuario_nuevo_js";
        $this->load->view('main', $data);    
    }

    function perfil_edita_submit()
    {
    	$clvpuesto = $this->input->post('clvpuesto');
        $puesto = $this->input->post('puesto');
        $nivelUsuarioID = $this->input->post('nivelUsuarioID');
        $valuacion = $this->input->post('valuacion');
        $consulta = $this->input->post('consulta');

        $this->admin_model->updatePuesto($clvpuesto, $puesto, $nivelUsuarioID, $valuacion, $consulta);
        $this->admin_model->updateValuacionByClvPuesto($clvpuesto);
        redirect('administracion/perfiles');
    }

    function descarga_catalogo()
    {
        $this->admin_model->getCatalogoArticulos();
        
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
}