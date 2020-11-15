<?php
/**
 * Cierre de sesion, destrucción de variables y redirección
 * 
 */ 
session_start();
unset($_SESSION);
session_destroy();
header('Location: index.php');
