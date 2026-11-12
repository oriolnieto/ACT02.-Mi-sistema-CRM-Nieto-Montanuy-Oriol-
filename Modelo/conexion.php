<?php
    //variables necessaries per la conexió
	define("HOST_DB", "localhost");
	define("USER_DB", "root");
	define("PASS_DB", "");
	define("NAME_DB", "mvc_crm");

    //crear la conexió a la base de dades
	$conexion = mysqli_connect(
		constant("HOST_DB"),
		constant("USER_DB"),
		constant("PASS_DB"),
		constant("NAME_DB")
	);
?>