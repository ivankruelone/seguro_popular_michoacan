<?php
    require_once('../lib/crud.php'); 
    $db = new Database();
    $db->connect();
    $db->select('receptores', '*', "rfc = 'FFC6611235C0'");
    $res = $db->getResult();
    
    $xmlDoc = new DOMDocument('1.0', 'UTF-8');
    $root = $xmlDoc->appendChild(
    $xmlDoc->createElement("receptores"));
    $xmlDoc->formatOutput = true;

    
    if(count($res) > 0){

        $tutTag = $root->appendChild(
        $xmlDoc->createElement("receptor"));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("encontrado"))->appendChild(
        $xmlDoc->createTextNode('si'));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("rfc"))->appendChild(
        $xmlDoc->createTextNode($row['refc']));
        
        $tutTag->appendChild(
        $xmlDoc->createAttribute("razon"))->appendChild(
        $xmlDoc->createTextNode($row['razon']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("calle"))->appendChild(
        $xmlDoc->createTextNode($row['calle']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("exterior"))->appendChild(
        $xmlDoc->createTextNode($row['exterior']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("interior"))->appendChild(
        $xmlDoc->createTextNode($row['interior']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("colonia"))->appendChild(
        $xmlDoc->createTextNode($row['colonia']));
        
        echo $xmlDoc->saveXML();
        
    }else{

        $tutTag = $root->appendChild(
        $xmlDoc->createElement("receptor"));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("encontrado"))->appendChild(
        $xmlDoc->createTextNode('no'));

        echo $xmlDoc->saveXML();
        
    }

?>