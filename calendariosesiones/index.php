<?php
/**
 * Calendario mejorado.
 *
 * El objetivo es profundizar en el conocimiento del manejo de sesiones y
 * y peticiones get.
 * Añadimos al calendario la opción de seleccionar una
 * fecha y añadir tareas en esa fecha.
 * Trabajamos con sesiones.
 * Mejoras propuestas:
 *    Marcar los días que incluyen tareas.
 *    Incluir enlace al cierre de sesión.
 *
 */ 
session_start();
if (!isset($_SESSION['tareas'])){
	$_SESSION['tareas']=array();
}

/*Funciones utilizadas en el programa.*/
include_once("include/funciones.php");
/*Constantes con la definición del día festivo.*/
include_once("config/constantes.php");
   
/*Establecemos valores iniciales de las variables*/
$ndiaActual = date("j"); //Día actual sin ceros iniciales.
$nmesActual = date("m"); // número del mes actual (1-12)
$anyoActual = date("Y"); // Año
$cnfSelected=""; //Variable para marcar valor por defecto.
$formularioValido = true; //Variable para controlar cuando se procesa el formulario.
$msgErrorAnyo = $msgErrorMes = $msgErrorTarea = ""; //Variables para almacenar mensajes de error.
$clase_error="";

/*Comprobamos si recibimos la fecha por la url, ejemplo fecha=13-11-2020*/
if (isset($_GET['fecha'])){
	/*Expr reg de fecha por si se introduce directamente la url*/
	$expRegular = "/^([1-9]|[0-2][0-9]|3[0-1])(\/|-)([1-9]|0[1-9]|1[0-2])(\/|-)(\d{4})$/";
	if(!preg_match($expRegular, $_GET['fecha'])) {
		header('Location: ' . $_SERVER['PHP_SELF']);
	}
	/*Separamos día, mes, año*/
	$datosFecha = explode("-",$_GET['fecha']);
	$ndiaActual = $datosFecha[0]; 
	$nmesActual = $datosFecha[1]; 
	$anyoActual = $datosFecha[2];   
}

$mesActual = array_keys($aMeses)[$nmesActual-1]; // nombre del mes actual. -1 para indice correcto
$fecha = "$ndiaActual-$nmesActual-$anyoActual";  //Expansión de variables

/*Validación del formulario del lado del servidor.*/
if (isset($_POST["enviar"])) {
	/*En este caso no es necesario pero lo incluimos para insistir en su importancia*/
    $anyoActual = limpiarDatos($_POST["anyo"]);
    $nmesActual = limpiarDatos($_POST["mes"]);
    $mesActual = array_keys($aMeses)[$nmesActual-1];
    /*Comprobación del mes aunque sea una lista desplegable*/
	if (empty($nmesActual)) {
	   $clase_error="clase_error";
	   $msgErrorMes= "<span class=\"".$clase_error."\">El mes no puede estar vacío</span>";
	   $formularioValido = false;
	}
	/*Comprobación del año*/
    if (empty($anyoActual)) {
       $clase_error="clase_error";
	   $msgErrorAnyo= "<span class=\"".$clase_error."\">El año no puede estar vacío</span>";
	   $formularioValido = false;
	}
}

/*Procesamiento y validación del segundo 2 formulario*/
if (isset($_POST['nueva'])){
    if (empty($_POST['tarea'])) {
		$clase_error="clase_error";
		$msgErrorTarea= "<span class=\"".$clase_error."\">La tarea no puede estar vacía</span>";
    } 
	else {
	    $_SESSION['tareas'][]=array('fecha'=>$_POST['fecha'],
								    'tarea'=>limpiarDatos($_POST['tarea']));
    }
}

if ($formularioValido) {
	   /*Ajustamos días del calendario*/
	   
       $fecha=$ndiaActual."-".$nmesActual."-".$anyoActual;
       
	   /*Añadimos un día a febrero si el año es bisiesto.*/
       if ($nmesActual == 2 and checkdate(2,29,$anyoActual)) {
           $aMeses[$mesActual]["ndias"]++;
    
       }
       /*Calculamos día de la semana del primer día del mes.*/
       $nDiasMes=$aMeses[$mesActual]["ndias"];
       $diaSemana = date("w",strtotime($anyoActual."-".$nmesActual."-"."1"));
       $numeroHuecos = ($diaSemana + 6) % 7 ;// Desplazamos por que el 0 corresponde al domingo.
       
	   /*Actualizacomos los días festivos con la Semana Santa*/
       if ($nmesActual ==date('n',easter_date((int)$anyoActual))) {
		   /*Domingo de Pascua*/
           $dp = date('j',easter_date((int)$anyoActual));
           array_push($aMeses[array_keys($aMeses)[$nmesActual-1]]["festivos"],array("dia"=>$dp-3,"tipo"=>"Nacional"));
           array_push($aMeses[array_keys($aMeses)[$nmesActual-1]]["festivos"],array("dia"=>$dp-2,"tipo"=>"Nacional"));
       }
   }


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Calendario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css"-->
</head>
<body>
    <section>
        <?php
        //Bucle que genera la lista desplegable.
        echo "<form action = \"".htmlspecialchars($_SERVER["PHP_SELF"])."\" method = \"POST\" >";
        echo "<select name=\"mes\">";
        $i=1;
        foreach ($aMeses as $clave=>$valor) {
            $cnfSelected = ($clave==$mesActual) ? "Selected" : "";
            echo "<option value=\"".$i."\"" .$cnfSelected.">".$clave."</option>";
            $i++;
          
        }  
        echo "</select>";
        echo $msgErrorMes;
        
        echo "<input type=\"number\" name=\"anyo\" value = \"".$anyoActual."\" min=\"1900\" max=\"2050\">";
        echo $msgErrorAnyo;
        echo "<br/>";
        echo "<input type=\"submit\" value =\"Actualizar\" name=\"enviar\">";
        echo "</form>";
        ?>
    </section>
    <section>
    <?php
     if ($formularioValido) {
	   /*Procedimiento para imprimir el calendario */
       echo  $mesActual."-". $anyoActual."<br/>"; 
       echo "<table>";
       echo "<th>L</th><th>M</th><th>X</th><th>J</th><th>V</th><th>S</th><th>D</th>";
       echo "<tr>";
       for($i=1;$i<=$numeroHuecos;$i++) {
          echo "<td></td>";
       }
       for ($i=1;$i<=$nDiasMes;$i++) {
          $clase_dia="clase_dialaboral";
          $strfecha = "$i-$nmesActual-$anyoActual";  
          /*Clase para el día de hoy*/
		  if ($ndiaActual== $i and 
		      $nmesActual == date("m") and
			  $anyoActual == date("Y")) {
				 $clase_dia="clase_diahoy";
		  } 
		  
		  /*Clase para el día la fecha seleccionada*/
		  if (($ndiaActual== $i)and isset($_GET['fecha'])) {
		      $clase_dia="clase_fechaseleccionada";
		  } 
		  
          /* Clase para domingos.*/ 
          if (date("w",strtotime($anyoActual."-".$nmesActual."-".$i )) == 0) {
              $clase_dia="clase_diadomingo";
          }
		  
		  /*Clase para días festivos.*/
          $key=array_search($i,array_column($aMeses[$mesActual]["festivos"],"dia"),true);
          if ($key !== false) {
            if ($i == array_column($aMeses[$mesActual]["festivos"],"dia")[$key]) {
                 switch  (array_column($aMeses[$mesActual]["festivos"],"tipo")[$key] ){
                    case "Nacional":
                        $clase_dia="clase_diafestivo";
                        break;
                    case "Local":
                        $clase_dia="clase_dialocal";
                        break;
                    case "Comunidad":
                        $clase_dia="clase_diacomunidad";
                        break;
                    default:
                          //dia laboral
                    break;    
                }
               
            }
        }
        echo "<td class=\"$clase_dia\">
		      <a href=\"".htmlspecialchars($_SERVER["PHP_SELF"])."?fecha=$strfecha\">
			   $i
			  </a>
			  </td>";
        if (($i + $diaSemana-1) % 7 == 0) {
              echo "</tr><tr>";
          }
       }
       echo "</table>";
	   echo "Fecha: $fecha<br\>";
	   echo "<form action = \"".htmlspecialchars($_SERVER["PHP_SELF"])."?fecha=$fecha\" method = \"POST\" >";
       echo "<input type=\"text\" name=\"tarea\" value = \"\">";
	   echo $msgErrorTarea;
       echo "<input type=\"hidden\" name=\"fecha\" value = \"$fecha\">";
	   echo "<br/>";
       echo "<input type=\"submit\" value =\"Añadir\" name=\"nueva\">";
       echo "</form>";
	   
	   /*Mostramos las tareas del día*/
	   foreach ($_SESSION['tareas'] as $clave=>$valor){
		   if($valor['fecha']==$fecha){
		   echo $valor['tarea']."<br/>";}
	   }
    }
    else {
        echo "Calendario indefinido";
    }
    ?>
    </section>
</body>
</html>