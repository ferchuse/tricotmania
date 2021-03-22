<?php
error_reporting(0);
/*
v 1.00
////////////////////////////////////////////////////////////////////////////////////
VERIFICADOR DE COMPATIBILIDAD PARA SERVIDORES WEB

La finalidad de este archivo es ver que cuentas 
con todo lo necesario para generar un CFDi

EN CASO DE TENER FALLAS PIDE APOYO A www.multifacturas.com
////////////////////////////////////////////////////////////////////////////////////
*/


echo "<h1>Prueba de requerimientos tecnicos</h1>";
echo "Para mejorar la compatibilidad con sistemas operativos la mayoria de procedimientos cuenta con dos metodos por lo tanto dos pruebas";

/////////////////////////////////////////////////
////        VERSION DE PHP 
/////////////////////////////////////////////////
echo "<h2>PRUEBA VERSION PHP</h2>";    
    $php_minimo=53;

    list($uno,$dos,$tres)=explode('.',PHP_VERSION);
    $php_actual=intval("$uno$dos");

    ECHO "<p>Version actual ".PHP_VERSION."</p>";
    
    if($php_actual<$php_minimo)
    {
        echo "<p>ESTADO <b>: ERROR, VERSION MINIMA 5.3</b></p>";
    }
    else
    {
        echo "<p><b>ESTADO : OK</b></p>";
    }


    if (function_exists('ioncube_loader_version'))
{
        ECHO "<p>IONCUBE : <b>OK</b></p>";
}
else
{
        ECHO "<p>ERROR : <b>IONCUBE NO INSTALADO</b></p>";
}
/////////////////////////////////////////////////
////        PRUEBA XSL 
/////////////////////////////////////////////////
echo "<hr/><h2>PRUEBA XSL (generar cadena original)</h2>";

echo "<p>METODO 1 :";
$xsl='';
    if(function_exists('shell_exec'))
    {
        $respuesta=shell_exec('xsltproc');
        if(strpos($respuesta,'--verbose'))
        {
            ECHO "OK";
            $xsl.="OK";
        }
        else
        {
            ECHO "ERROR : Requiere xsltproc";
        }
        
    }
    else
    {
        ECHO "ERROR : permiso shell_exec";
    }

echo "<br/>METODO 2 :";
    if(class_exists("DOMDocument")==true AND class_exists("XSLTProcessor")==true)
    {
            ECHO "OK";
            $xsl.="OK";
    }
    else
    {
            ECHO "ERROR : Requiere libreria XSL y DOM en php";
    }
    
    if($xsl=='')
    {
        $resultado='ERROR';
    }
    else
    {
        $resultado='OK';
    }


    echo "<p><b>ESTADO : $resultado</b></p>";
    echo "</p>";
/////////////////////////////////////////////////
////        PRUEBA XSD 
/////////////////////////////////////////////////
echo "<hr/><h2>PRUEBA XSD (validar campos y estructura)</h2>";
    if(file_exists("xsd/cfdv32.xsd")==true)
    {
        echo "<p><b>ESTADO : $resultado</b></p>";
    }
    else
    {
        echo "<p><b>ESTADO : ADVERTENCIA!!!! falta la carpeta xsd, sin ella no se puede validar el XML antes de timbrar haciendo mas probable consumas timbres inecesarios</b></p>";
    }


/////////////////////////////////////////////////
////        PRUEBA OPENSSL 
/////////////////////////////////////////////////
echo "<hr/><h2>PRUEBA OPENSSL (generar cadena sello)</h2>";    


echo "<p>METODO 1 :";
$openssl='';
    if(function_exists('shell_exec'))
    {
        
//        $respuesta=shell_exec('openssl --');
        $respuesta=shell_exec('openssl --');
        if(strpos($respuesta,'x509'))
        {
            ECHO "OK";
            $openssl.="OK";
        }
        else
        {
            ECHO "ERROR : Requiere ejecutable openssl";
        }
        
    }
    else
    {
        ECHO "ERROR : permiso shell_exec";
    }

echo "<br/>METODO 2 :";
    if(function_exists('openssl_sign')==true)
    {
            ECHO "OK";
            $openssl.="OK";
    }
    else
    {
            ECHO "ERROR : Requiere libreria OpenSSL para PHP";
    }
    
    if($openssl=='')
    {
        $resultado='ERROR';
    }
    else
    {
        $resultado='OK';
    }


    echo "<p><b>ESTADO $resultado</b></p>";
    echo "</p>";


        


/////////////////////////////////////////////////
////        CONEXION AL WEB SERVICE 
/////////////////////////////////////////////////
echo "<hr/><h2>PRUEBAS DIVERSAS</h2>";    

echo "<p>ARCHIVOS XSLT : ";
if(file_exists('xslt/cadenaoriginal_3_2.xslt')==true )
{
    echo "<b> OK</b></p>";
} 
else
{
    echo "<b> ERROR FALTA CARPETA XSLT SIN ELLA NO SE PUEDE GENERAR EL SELLO</b></p>";
}

echo "<br/>";

echo "<p>PERMISO DE ESCRITURA : ";
$rand=rand('111,999').".txt";
file_put_contents("tmp/$rand",$rand);

$tmp=file_get_contents("tmp/$rand");

if($tmp==$rand)
{
    echo "CARPETA 'tmp' <b>OK</b></p>";
    $tmp='OK';
} 
else
{
    echo "<b> ERROR NO HAY PERMISO DE ESCRITURA EN CARPETA 'tmp'</p>";
}




echo "<br/>";
if(function_exists('simplexml_load_file')==true)
{
        ECHO "<p>LIBRERIA simplexml : <b>OK</b></p>";
}
else
{
        ECHO "<p>ERROR : Requiere libreria simplexml para PHP</p>";
}



echo "<br/>";
if(class_exists("DOMDocument")==false )
{
        ECHO "<p>LIBRERIA DOMDocument : <b>OK</b></p>";
}
else
{
        ECHO "<p>RECOMENDACION : Requiere libreria DOMDocument en PHP para mejorar la velocidad de timbrado</p>";
}
echo "<br/>";



$res= file_get_contents("http://pac1.multifacturas.com/pac?wsdl");
if(strlen($res)>5000)
{
        ECHO "<p>COMUNICACION AL SERVIDOR DE TIMBRADO : <b>OK</b></p>";
}
else
{
        ECHO "<p>ERROR : <b>FALLA comunicacion al servidor de timbrado, revise firewall o conexion a internet</b></p>";
}


/////////////////////////////////////////////////
////        GENERACION PEM 
/////////////////////////////////////////////////
echo "<hr/><h2>GENERACION DE CERTIFICADO FORMATO PEM</h2>";    


    
    include "lib/cfdi32_multifacturas.php";
    $error='OK';
    if(function_exists('certificado_pem'))
    {
        
        $cer= file_get_contents("certificados/GUAF880601NA6.cer");
        $key= file_get_contents("certificados/GUAF880601NA6.key");
        file_put_contents('tmp/GUAF880601NA6.cer',$cer);
        file_put_contents('tmp/GUAF880601NA6.key',$key);
        // unlink('tmp/GUAF880601NA6.cer.pem');
       // unlink('tmp/GUAF880601NA6.key.pem');
        $datos['conf']['cer'] = 'tmp/GUAF880601NA6.cer.pem';
        $datos['conf']['key'] = 'tmp/GUAF880601NA6.key.pem';
        $datos['conf']['pass'] = 'FLDSMDFR.-1600';
        certificado_pem($datos);
        if(file_exists('tmp/GUAF880601NA6.cer.pem'))
        {
            echo "<p>ARCHIVOS .CER.PEM   <b>OK</b></p>";
        }
        else
        {
            echo "<p>ERROR GENERANDO ARCHIVO .CER.PEM   <b>OK</b></p>";
            $error.='SI';
        }

        if(file_exists('tmp/GUAF880601NA6.key.pem'))
        {
            echo "<p>ARCHIVOS .KEY.PEM   <b>OK</b></p>";
        }
        else
        {
            echo "<p>ERROR GENERANDO ARCHIVO .KEY.PEM   <b>OK</b></p>";
            $error.='SI';
        }
        
    }
    else
    {
        $error.='SI';
        ECHO "<p>ERROR : <b>FALTA el archivo cfdi32_multifacturas.php para realizar la ultima prueba</b></p>";
    }
    
    if($error!='OK')
    {
        echo "<h1>ERROR GRAVE, NO SE PUEDEN PROCESAR LOS CERTIFICADOS</h1>";
    }



echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/>";

?>
<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//v2.zopim.com/?j6hHk2P8H4b6tpuKivQTfR55KBIMl9YR';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->