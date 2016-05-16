<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


define('COMPANIA', 'FARMACIAS EL FENIX DEL CENTRO S. A. DE C. V.');
define('APLICACION', 'MICHOACAN');
define('OFFICE', 'FRONT-OFFICE');
define('SLOGAN', '...');
define('SERVICIO', '8.60'); 
define('SUCURSAL', 100);
define('IVA', 0.16);
define('RECETA_PEDIDO', 1);
define('USER_FACTURACION', '');
define('PASS_FACTURACION', '');
define('PATENTE', 0);
define('ALMACEN', 12000);
define('CIE103', 0);
define('CIE104', 0);

define('REMISION_LINEA1', 'GOBIERNO DEL ESTADO DE MICHOACAN');
define('REMISION_LINEA2', 'SECRETARIA DE SALUD');
define('REMISION_LINEA3', 'FARMACIAS EL FENIX DEL CENTRO S. A. DE C. V.');

define('ROJO', '#F78181');
define('VERDE', '#9FF781');
define('AMBAR', '#FFA07A');

define('TASKS', 'off');
define('NOTIFICATIONS', 'off');
define('MESSAGES', 'off');



/* End of file constants.php */
/* Location: ./application/config/constants.php */