<?php
session_start();
unset($_SESSION['tablero']);
unset($_SESSION['visible']);
session_destroy();
header('Location:buscaminas.php');
?>