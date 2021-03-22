<?php
/**
 * La funcion siempre debe comenzar con tres guiones bajo y el nombre del mismo archivo PHP
 * SIN extension, y recibir una variable; esta variable puede tener el nombre que se desee.
 */
function ___descargamasiva($datos)
{
    // Se extraen los datos necesarios del parametro de entrada
    if(!is_array($datos)) {
        return array('respuesta' => 'ERROR 01: No se recibieron los parametros en formato de array.');
    }else {
        // Se lee la operacion
        if(!isset($datos['operacion'])){
            return array('respuesta' => 'ERROR 02: No se recibio operacion.');
        }else {
            $operacion = $datos['operacion'];
        }
        
        switch($operacion) {
            case 'SOLICITUD':
                    // Se lee el usuario
                    if(!isset($datos['PAC']['usuario'])){
                        return array('respuesta' => 'ERROR 03: No se recibio usuario en credenciales.');
                    }else {
                        $usuario = $datos['PAC']['usuario'];
                    }
                    
                    // Se lee la contraseña
                    if(!isset($datos['PAC']['pass'])){
                        return array('respuesta' => 'ERROR 04: No se recibio contraseña en credenciales.');
                    }else {
                        $contrasena = $datos['PAC']['pass'];
                    }
                    
                    // Se lee la licencia
                    if(!isset($datos['licencia'])){
                        return array('respuesta' => 'ERROR 05: No se recibio licencia.');
                    }else {
                        $licencia = $datos['licencia'];
                    }
                    
                    // Se lee el RFC
                    if(!isset($datos['rfc'])){
                        return array('respuesta' => 'ERROR 06: No se recibio RFC.');
                    }else {
                        $rfc = $datos['rfc'];
                    }
                    
                    // Se lee la clave
                    if(!isset($datos['clave'])){
                        return array('respuesta' => 'ERROR 07: No se recibio clave CIEC.');
                    }else {
                        $clave = $datos['clave'];
                    }
                    
                    // Se lee el tipo de descarga
                    if(!isset($datos['tipo'])){
                        return array('respuesta' => 'ERROR 08: No se recibio tipo de descarga.');
                    }else {
                        $tipo = $datos['tipo'];
                    }
                    
                    // Se lee la fecha de inicio
                    if(!isset($datos['fini'])){
                        return array('respuesta' => 'ERROR 09: No se recibio fecha de inicio.');
                    }else {
                        $fini = $datos['fini'];
                    }
                    
                    // Se lee la fecha final
                    if(!isset($datos['fini'])){
                        return array('respuesta' => 'ERROR 10: No se recibio fecha final.');
                    }else {
                        $ddin = $datos['ffin'];
                    }
                    
                    // Si se recibieron todos los parametros se incluye nusoap
                    if(!class_exists('nusoap_client'))
                    {
                        if(function_exists('libreria_mash'))
                        {
                            libreria_mash('nusoap');
                        }
                        else
                        {
                       
                            if(file_exists($ruta."lib/nusoap/nusoap.php")==false)
                            {
                                unset($res);
                                $res['pac']=0;
                                $res['produccion']=$produccion;
                                $res['codigo_mf_numero']=7;
                                $res['codigo_mf_texto']='ERROR AL AGREGAR LA LIBRERIA NUSOAP AGREGE include "ruta/nusoap.php";';
                                $res['cancelada']=1;
                                $res['servidor']=0;
                                _cfdi_almacena_error_();
                                return $res;
                                
                            }
                            include $ruta."nusoap/nusoap.php";
                        }
                    }                    
                    // Se crea el cliente
                    $client = new nusoap_client('https://cfdi.servidordedicado.com.mx/ws/wservice.php?wsdl','wsdl');
                    
                    // Se crean los parametros a enviar
                    $params = array('usuario' => $usuario, 'contrasena' => $contrasena, 'licencia' => $licencia, 'rfc' => $rfc, 'clave' => $clave, 'tipo' => $tipo, 'fini' => $fini, 'ffin' => $ffin);
                    
                    // Se envia la solicitud
                    $resp = $client->call('SolicitudDescarga', $params);
                    
                    // Se devuelve la respuesta
                    return array('respuesta' => $resp);
            case 'CONSULTA':
                    // Se lee el identificador
                    if(!isset($datos['identificador'])){
                        return array('respuesta' => 'ERROR 11: No se recibio identificador.');
                    }else {
                        $identificador = $datos['identificador'];
                    }
                    
                    // Si se recibieron todos los parametros se incluye nusoap
                    if(!class_exists('nusoap_client'))
                        require_once 'nusoap/nusoap.php';
                    
                    // Se crea el cliente
                    $client = new nusoap_client('https://cfdi.servidordedicado.com.mx/ws/wservice.php?wsdl','wsdl');
                    
                    // Se crean los parametros a enviar
                    $params = array('identificador' => $identificador);
                    
                    // Se envia la solicitud
                    $resp = $client->call('ConsultaEstado', $params);
                    
                    // Se devuelve la respuesta
                    return $resp;
            default:
                return array('respuesta' => 'ERROR 12: Tipo de descarga desconocido, debe ser un valor entre RECIBIDOS y EMITIDOS');
        }
    }
}