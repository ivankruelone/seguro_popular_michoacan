<?php
/*
 *	$Id: client1.php,v 1.3 2007/11/06 14:48:24 snichol Exp $
 *
 *	Client sample that should get a fault response.
 *
 *	Service: SOAP endpoint
 *	Payload: rpc/encoded
 *	Transport: http
 *	Authentication: none
 */
require_once('../lib/nusoap.php');
$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
$useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
$client = new nusoap_client("http://72.32.11.237/LMS.Pfizer.Prepago.WebService/WSPrepago.asmx?WSDL", false,
						$proxyhost, $proxyport, $proxyusername, $proxypassword);
                         $client->soap_defencoding = 'UTF-8';
$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
	exit();
}
$client->setUseCurl($useCURL);
// This is an archaic parameter list
$params = array(
    'programa_IntId'	=> 1,
    'cadena_intId'	=> 999,
    'sucursal_intId'	=> 999,
    'terminal_intId'	=> 1,
    'usuario_strId'	=> "ivan",
    'tarjeta'	=> "1129999998",
    'cantidad'	=> 1,
    'sku'	=> "7501287688019"
);
$result = $client->call('ProcesarCompraDemo', $params, "http://lms.com.mx/WebServices/Prepago/ProcesarCompraDemo", "http://lms.com.mx/WebServices/Prepago/ProcesarCompraDemo");
if ($client->fault) {
	echo '<h2>Fault (Expect - The request contains an invalid SOAP body)</h2><pre>'; print_r($result); echo '</pre>';
} else {
	$err = $client->getError();
	if ($err) {
		echo '<h2>Error</h2><pre>' . $err . '</pre>';
	} else {
		echo '<h2>Result</h2><pre>'; print_r($result); echo '</pre>';
	}
}
echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
?>
