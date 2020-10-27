<?php
include 'conectaBD.php';
$pdo=conectadb();

session_start();

	if(isset($_GET['eliminar'])){
		$codEquipo=$_GET['eliminar'];
		$stmt=$pdo->query("delete from equipos where codEquipo=$codEquipo");
		
		$_SESSION['message']="Equipo eliminado";
		$_SESSION['msg_type']="danger";
		
		header("location: visualizar.php");
	}
	
?>


