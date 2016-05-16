<?php
	require_once('../lib/nusoap.php');
	
	$url	    = 'http://72.32.11.237/LMS.Pfizer.Prepago.WebService/WSPrepago.asmx?WSDL';
	$namespace  = 'http://lms.com.mx/WebServices/Prepago';
	$soapAction = 'http://lms.com.mx/WebServices/Prepago/ProcesarCompra';
	$webMethod  = 'ProcesarCompra';
	$useWSDL 	= true;

	$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : NULL;
	$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : NULL;
	$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : NULL;
	$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : NULL;
	$useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
	
	$client = new nusoap_client($url, $useWSDL, $proxyhost, $proxyport, $proxyusername, $proxypassword);
	$client->soap_defencoding = 'UTF-8';
	$client->setUseCurl($useCURL);
	$client->useHTTPPersistentConnection();
	
	$Programa_intId = 1;
	$Cadena_intId 	= 999;
	$Sucursal_intId = 999;
	$Terminal_intId = 1;
	$Usuario_strId 	= 1;
	$Tarjeta 		= '1119999999';
	$xml =  "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<soap:Envelope xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">" .
			"	<soap:Body>" .
			"		<ProcesarCompra xmlns=\"http://lms.com.mx/WebServices/Prepago\">" .
			"			<Compra Programa_intId=\"$Programa_intId\" Cadena_intId=\"$Cadena_intId\" Sucursal_intId=\"$Sucursal_intId\" Terminal_intId=\"$Terminal_intId\" Usuario_strId=\"$Usuario_strId\" Tarjeta=\"$Tarjeta\" xmlns=\"http://www.lms.com.mx/WebServices/Prepago\">" .
			"				<Pedido SKU=\"7501287688019\" Cantidad=\"1\" />" .
			"			</Compra>" .
			"		</ProcesarCompra>" .
			"	</soap:Body>" .
			"</soap:Envelope>";
	$result = $client->send($xml, $soapAction);

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