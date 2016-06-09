<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php
require(APPPATH.'/libraries/REST_Controller.php');

class Exchange extends REST_Controller
{
    
    function actualizaFactura_post()
    {
        $this->load->model('facturacion_model');

        $user = $this->post('user');
        $pass = $this->post('pass');
        $json = $this->post('json');
        $arr = json_decode($json);
        
        $data = $this->facturacion_model->actualizaFactura($arr);
        
        $this->response($data);
    }
    
}