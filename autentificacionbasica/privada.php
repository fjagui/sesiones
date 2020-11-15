<?php
/**
 * Autentificación básica.
 *
 * Script privado, solo se puede acceder si el usuario se encuentra
 * autentificado en el sistema.
 * 
 * @package autentificacionBasica.
 *
 */ 

/*Recuperamos sesión*/
session_start();

/*Variable para determinar el acceso a la parte privada*/
$autorizacion = $_SESSION['aut'] ?? false;
if (!$autorizacion) {
	header('Location: index.php'); //Redirección al index. Sería posible enviar código de error.
}

/*Funciones utilizadas en el programa.*/
include_once("include/funciones.php");
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
    /*No se mostrará el formulario de login en una página privada*/
   	displayLogout();
?>
</header>
<nav>
    <?php 
	/* Opciones de navegación en función del estado de autentificación, true*/
	displayMenu($_SESSION['aut'])?>;
</nav>
<p>Hola privada</p>	
</body>
</html>
