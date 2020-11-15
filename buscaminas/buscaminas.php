<?php
/**
 * Buscaminas
 *
 * Implementación del juego buscaminas en PHP con el objetivo de proundizar en
 * manejo de sesiones, peticiones get, recursividad.
 * Mejoras.
 *    Visualización
 * 	  Desactivación de minas
 *    Bloqueo del juego terminado.
 *
 */ 

/* FUNCIONES DEL JUEGO*/

/**
 * Mostrar tablero auxiliar
 * Función que muestra el tablero de juego y su contenido
 * Crea un enlace en cada casilla de la tabla 
 * enviando por la url fila, columna.
 * Función auxiliar para mostrar durante el desarrollo.
 * No mostrar en producción.
 */
function mostrarTablero()
{
    echo "<table>";
    for($fila=0; $fila < TAM; $fila++) {
        echo "<tr>";
	    for($columna = 0; $columna < TAM; $columna++) {
    	    echo "<td>";
			echo "<a href=\"buscaminas.php?fila=$fila&columna=$columna\">";
		    echo $_SESSION['tablero'][$fila][$columna];
		    echo "</a></td>";
	    }
	    echo "</tr>";
    }	
    echo "</table>";	
}

/**
 * Mostrar tablero 
 * Función que muestra el tablero de juego e implementa la funcionalidad.
 * Crea un enlace en cada casilla de la tabla y cuyo contenido es un cero.
 * 
 * 
 */
 function mostrarVisible()  // tablero de juego
{
    echo "<table>";
    for($fila=0; $fila < TAM; $fila++) {
        echo "<tr>";
	    for($columna = 0; $columna < TAM; $columna++) {
    	    echo "<td>";
			if ($_SESSION['visible'][$fila][$columna] == 1) {  //Si la casilla ya está visible
			    if ($_SESSION['tablero'][$fila][$columna] == 0) { 
				    echo "*"; //Mostramos vacío
				}
				else { 
				    echo $_SESSION['tablero'][$fila][$columna]; //Mostramos minas adyacentes.
				}
			}
			else { // Casilla no visible
			    echo "<a href=\"buscaminas.php?fila=$fila&columna=$columna\">";	 //Mostramos enlace			
                echo $_SESSION['visible'][$fila][$columna];
		        echo "</a>";				
			}
		    echo "</td>";
	    }
	    echo "</tr>";
    }	
    echo "</table>";	
}


/**
 * Crear Tablero
 * Función que genera los dos tableros.
 * El array visible se creará con tadas las casillas ocultas.
 * El tablero se creará generando números aleatorios y calculando el número de minas pegadas a cada casilla
 *  
 */
function crearTablero()
{
    //Inicializa el tablero
    for ($fila = 0;$fila < TAM;$fila++){
	    for ($columna=0; $columna < TAM; $columna++){
           $_SESSION['tablero'][$fila][$columna]=0;
		   $_SESSION['visible'][$fila][$columna]=0;
		}
	}
	
	//utilizaremos 9 para representar que hay una mina.	
	//Pone diez minas
	for ($n=0 ; $n<NUMMINAS; $n++){
	//Busca una posición aleatoria donde no haya otra bomba
	   do {
           $fila    = mt_rand(0,TAM -1);
	       $columna = mt_rand(0,TAM -1);
	   }
	   while ($_SESSION['tablero'][$fila][$columna]==9); 
	   //Pone la bomba
	   //echo "Ponemos bomba en $fila, $columna <br/>";
	   $_SESSION['tablero'][$fila][$columna]=9;
	   //Recorre el contorno de la bomba e incrementa los contadores
	   for ($f=max(0, $fila-1);$f <= min(TAM-1,$fila+1);$f++){
	       for ($c=max(0,$columna-1);$c <= min(TAM-1,$columna+1);$c++){
    		   if ($_SESSION['tablero'][$f][$c]!=9){ //Si no es bomba
			       $_SESSION['tablero'][$f][$c]++; //Incrementa el contador
			   }
		    }
		}
	}
}

/**
 * Comprueba ganador
 * Función que verifica que se han terminado el juego sin explotar ninguna mina.
 * Cuenta las casillas visibles y si es igual al número de casillas menos el número de minas
 * el juego ha sido ganado 
 * 
 * @return logico lganador: Falso si se produce una exploxión o true si se ha ganado.
 */
function comGanador()
{
    $lganador=false;
	$numOcultos = 0;
	$numVisibles = 0;
	foreach ($_SESSION['visible'] as $ind=>$valF) {
       foreach ($valF as $ind2=>$valor) {
           if ($valor==0) {
		      $numOcultos++;
		   }
		   else {
		      $numVisibles++;
		   }
	    }
	}
    if ($numVisibles == NUMCASILLAS - NUMMINAS) {
        $lganador = true;
    }
    return $lganador;
}


/**
 * Pulsar casilla.
 * Función que implementa la funcionalidad del juego.
 * Se pulsa sobre un enlace, se envían por la url la fila y la columna y se genera
 * una llamada recursiva para ir destapando casillas.
 * 
 * @param integer f fila
 * @param integer c columna
 * @return int 0 pierde, 1 gana
 */
function clicCasilla($f, $c)
{
   	/*Si la casilla esta oculta */
	if ($_SESSION['visible'][$f][$c] ==0) {
	    /*Destapamos casilla*/
		$_SESSION['visible'][$f][$c] = 1;
		/*Comprobamos mina; break recursividad */
		if ($_SESSION['tablero'][$f][$c]==9){
	        return 0;
	    } 
	    else {
		    /*Comprobamos ganador */ 
		    if (comGanador()){
		        /*Detapadas todas las casillar; break recursividad*/
		    	return 1;
		    }
		    else {
		        /*Si no hay minas cercanas */
		        if ($_SESSION['tablero'][$f][$c]==0){
			    /*Recorre las casillas cercanas y tambien las ejecuta*/
                    	for ($if=max(0, $f-1);$if <= min(TAM-1,$f+1);$if++){
	                       for ($ic=max(0,$c-1);$ic <= min(TAM-1,$c+1);$ic++){
    				           clicCasilla($if, $ic);
					        }
				        }
			    }
		    }
	    }
    }
}

/*FIN DECLARACIÓN DE FUNCIONES*/

 /*Definición de constantes.*/
 define("TAM", 10);
 define("NUMMINAS",10);
 define("NUMCASILLAS", TAM*TAM);
 $resultado = "";
 //Inicio y definición de sesión y variables.
 session_start();
 if (!isset($_SESSION['tablero'])) {
	 $_SESSION['tablero'] = array();
	 $_SESSION['visible'] = array();
	 crearTablero();
 }
 /* Desarrollo de la jugada */
 if (isset($_GET['fila'])) {
	$filEntrada = $_GET['fila'];
	$colEntrada = $_GET['columna'];
	$resultado = clicCasilla($filEntrada, $colEntrada) ?? "";
 }
 
 echo '<a href="cierrasesion.php">Reiniciar</a>';
 mostrarTablero();
 echo "<br/>";
 mostrarVisible();
 echo $resultado;
?>