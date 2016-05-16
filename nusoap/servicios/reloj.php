<?php
require_once('../lib/nusoap.php'); 
require_once('../lib/crud.php'); 
 
$server = new nusoap_server;
 
$server->configureWSDL('reloj', 'urn:reloj');
 
$server->wsdl->schemaTargetNamespace = 'urn:reloj';
 
$server->register('checar',
			array('value' => 'xsd:string', 'mac' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:reloj',
			'urn:reloj#checar');
 
function checar($value, $mac){
 
        return checado($value, $mac);
}

function checado($codtar, $mac)
{
    if($mac == "00241D281BBE"){
        
        $db = new Database();
        $db->connect();
        $db->select('catalogo.cat_empleado', "id, date(now()) as fecha, nomina, completo, checa, entrada, salida, tolerancia", 'codtar = '.(string)$codtar);
        $res = $db->getResult();
        
        $db3 = new Database();
        $db3->select("checador", "*", "empleado_id = ".$res['id']." and date(checado) = date(now())");
        $res3 = $db3->getResult();
        
        if(count($res3) == 0){
            $tipo_checado = 1;
        }else{
            $tipo_checado = 0;
        }
        
        $db1 = new Database();
        $db1->insert('checador',array($res['id'],"now()",$tipo_checado), "empleado_id, checado, tipo_checado");  
        $res1 = $db1->getResult();  
        
        $db2 = new Database();
        $db2->select("checador", "*", "id = ".$res1);  
        $res2 = $db2->getResult();
        
    
        if($res2['tipo_checado'] == 0){
            
            $tip_che = "Registrada";
            $atiempo = null;
            
        }elseif($res2['tipo_checado'] == 1){
            
            $tip_che = "Entrada";
            $sql = "SELECT TIMEDIFF((SELECT '".$res['fecha']." ".$res['entrada']."' + INTERVAL ".$res['tolerancia']." + 1 MINUTE), '".$res2['checado']."') as time;";
            
            $db4 = new Database();
            $db4->select_directo($sql);
            $res4 = $db4->getResult();
            
            $primer = substr($res4['time'], 0, 1);
            if($primer == "-"){
                $atiempo = "Retardo";
            }else{
                $atiempo = "A tiempo";
                
                $db5 = new Database();
                $db5->update('checador', array('atiempo' => 1), array('id'=>$res1));
                
            }
            
    
        }else{
            $tip_che = null;
            $atiempo = null;
        }
        
        $domtree = new DOMDocument('1.0', 'UTF-8');
        $xmlRoot = $domtree->createElement("datos");
        $xmlRoot = $domtree->appendChild($xmlRoot);
        
        $xmlRoot->setAttribute('completo', $res['completo']);
        $xmlRoot->setAttribute('checado', $res2['checado']);
        $xmlRoot->setAttribute('tipo_checado', $tip_che);
        $xmlRoot->setAttribute('atiempo', $atiempo." ".$res4['time']);
        return $domtree->saveXML();
    }else{
        $domtree = new DOMDocument('1.0', 'UTF-8');
        $xmlRoot = $domtree->createElement("datos");
        $xmlRoot = $domtree->appendChild($xmlRoot);
        
        $xmlRoot->setAttribute('completo', "Equipo no autorizado");
        $xmlRoot->setAttribute('checado', "Equipo no autorizado");
        $xmlRoot->setAttribute('tipo_checado', "Equipo no autorizado");
        $xmlRoot->setAttribute('atiempo', "Equipo no autorizado");
        return $domtree->saveXML();
    }
}
 
$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) 
                ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';

// pass our posted data (or nothing) to the soap service                    
$server->service($POST_DATA);                
exit();
?>