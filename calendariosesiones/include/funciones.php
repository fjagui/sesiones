<?php
/**
 * Función para limpiar datos de entrada.
 *
 * Función que limpia la información intruducida por el usuario
 * en un formulario. 
 * Evita distintos tipos de ataques.
 * @param string        $dato Cadena a limpiar.
 * @return string       Cadena limpiada.
 */    
   function limpiarDatos($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
  }
  
 
  