<?php
 
require_once('../lib/nusoap.php');
 
$url = "http://192.168.1.220/nusoap/samples/serv.php?wsdl";
 
$client = new nusoap_client($url);
 
$err = $client->getError();
 
if ($err) {
    echo '<p><b>Error: ' . $err . '</b></p>';
}
 
$args = array('value' => 'Good');
 
$return = $client->call('pollServer', array($args));
 
echo "<p>Value returned from the server is: " . $return . "</p>";
 
?>