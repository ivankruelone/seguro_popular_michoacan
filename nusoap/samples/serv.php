<?php
require_once('../lib/nusoap.php'); 
 
$server = new nusoap_server;
 
$server->configureWSDL('server', 'urn:server');
 
$server->wsdl->schemaTargetNamespace = 'urn:server';
 
$server->register('pollServer',
			array('value' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:server',
			'urn:server#pollServer');
 
function pollServer($value){
 
        if($value == 'Good'){
            return "The value of the server poll resulted in good information";
        }
        else{
            return "The value of the server poll showed poor information";
        }
}
 
$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) 
                ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';

// pass our posted data (or nothing) to the soap service                    
$server->service($POST_DATA);                
exit();
?>