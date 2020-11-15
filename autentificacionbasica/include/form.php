<?php
echo "<form action = \"".htmlspecialchars($_SERVER["PHP_SELF"])."\" method = \"POST\" >";
echo "<label>user:</label><input type=\"text\" name=\"usuario\" value = \"".$usuario."\" placeholder = \"".$msgUsuario."\">";
echo "<label>psw:</label><input type=\"password\" name=\"psw\" value = \"".$psw."\" placeholder = \"".$msgPassword."\">";
echo "<input type=\"submit\" value =\"Enviar\" name=\"enviar\">";
echo "</form>";