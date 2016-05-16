<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<?php

    $url = "http://cedula.buholegal.com/$cedula/";
    
    $file_headers = @get_headers($url);
    if($file_headers[0] == 'HTTP/1.1 404 NOT FOUND') {
        echo "<h1>INFORMACION NO ENCONTRADA.</h1>";
    }
    else {
    
        $str = file_get_contents($url);
        $str2 = explode('<table width="100%" class="tablasadmin" align="center">', $str);
        $str3 = explode('</table>', $str2[1]);
        echo '<table width="100%" class="tablasadmin" align="center">'.$str3[0];
    
    }
    
    
?>
</body>