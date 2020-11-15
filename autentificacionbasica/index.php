<?php
/**
 * Autentificación básica.
 *
 * Sistema de autentificación básica con sesiones con zona pública y privada.
 * Usuario: admin
 * Password: admin
 * Se usan estilos solo para el mensaje de error.
 * 
 * @package autentificacionBasica.
 * 
 */ 

/*Creamos la sesión y declaramos*/
session_start();
if (!isset($_SESSION['aut'])) {
	$_SESSION['aut'] = false;     // Valor true al autentificar.
	$_SESSION['user'] ='Invitado'; //Información de usuario.
}
/*Funciones utilizadas en el programa.*/
include_once('include/funciones.php');

/*Configuración y gestión del formulario.*/
include('include/confform.php')	
?>
	
<!-- LA VISTA -->
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/estilos.css"-->
	<title>Autentificación básica</title>
</head>
<body>
<header>
<h1>Autentificación básica.</h1>
<h2>Usted está como: <?php echo $_SESSION['user'];?></h2>

<?php
    /*Caja de información de cuenta o formulario de login en función de la autentificación*/ 
    if ($_SESSION['aut']) {
    	displayLogout();  //Información de cuenta
    } 
    else {
	     include('form.php'); // Formulario
	}
?>
</header>
<nav>
	<?php
	    /*Opciones de navegación en función del estado de autentificación.*/
		displayMenu($_SESSION['aut'])
	?>;
</nav>


<p>Hola index</p>	
</body>
</html>
