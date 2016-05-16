<?php
	require_once('../lib/nusoap.php');
	
	$url	    = 'http://50.56.31.170:8080/services/recarga';
	$namespace  = 'http://DefaultNamespace';
	$soapAction = 'http://50.56.31.170:8080/services/recarga/recarga';
	$webMethod  = 'recarga';
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
	$xml = "<xml>
<fecha>".date("Y/m/d H:i:s")."</fecha>
<establecimiento>278</establecimiento>
<passwd>fesh3tep7rez3</passwd>
<terminal>98653254933</terminal>
<cajero>Famarmacias278</cajero>
<cadena>283</cadena>
<carrier>1</carrier>
<referencia1>5561089689</referencia1>
<monto>50</monto>
<trx>1</trx>
</xml>";
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