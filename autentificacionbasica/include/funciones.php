<?php
/**
 * Función para limpiar datos de entrada.
 *
 * Función que limpia la información intruducida por el usuario
 * en un formulario. 
 * Evita distintos tipos de ataques.
 * 
 * @param string        $dato Cadena a limpiar.
 * @return string       Cadena limpiada.
 */    
function limpiarDatos($dato): string
{
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

/**
 * Función para mostrar opciones de cuenta
 *
 * Función para mostrar las opciones asociadas a la cuenta de usuario.
 * logout en el caso actual.
 *     
 * @return void
 */    
function displayLogout(): void
{
   echo "<a href=\"cerrarsesion.php\">Salir</a>";
}

/**
 * Función para mostrar el menu.
 *
 * Función para mostrar el menú en función de si el 
 * usuario está o no autentificado en el sistema.
 * Preferible hacerlo con objetos.
 * @param boolean        $userAut  Variable lógica
 * @return void
 */    
function displayMenu($userAut): void
{
    if ($userAut) {
        echo "<a href=\"index.php\">Inicio | </a>";
        echo "<a href=\"publica.php\">Público | </a>";
        echo "<a href=\"privada.php\">Privado</a>";
    }
    else {
        echo "<a href=\"index.php\">Inicio |</a>";
        echo "<a href=\"publica.php\">Público</a>";
    }
}

  