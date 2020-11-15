<?php
/**
 * Autentificación básica.
 *
 * Configuración para el control y procesamiento del formulario de login
 * Se incluye en las vistas donde sea necesario.
 * 
 * @package autentificacionBasica.
 * 
 */

$usuario=$psw="";
$msgUsuario=$msgPassword="";
$lprocesaFormulario=false;
if (isset($_POST["enviar"])) {
	$lprocesaFormulario="true";
    
	/*Limpiamos datos de entrada*/
    $usuario = limpiarDatos($_POST["usuario"]);
    $psw = limpiarDatos($_POST["psw"]);
   
    /*Comprobación de usuario*/
	if (empty($usario)) {
	   $clase_error="clase_error";
	   $msgUsuario= "&#9888; Obligatorio";
    }
	/*Comprobación de cotraseña*/
    if (empty($psw)) {
       $clase_error="clase_error";
	   $msgPassword=  "&#9888; Obligatorio";
	   $lprocesaFormulario = false;
	}
}
if ($lprocesaFormulario) {
    /*Comprobación de credenciales*/
	if (($usuario == 'admin') and ($psw == 'admin')) {
		$_SESSION['aut'] = true;
		$_SESSION['user'] = 'Administrador';
	}
}	