
<!doctype html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
<?php
try {
include 'conectaBD.php';
$pdo=conectadb();

echo $_REQUEST['codEquipo']. $_REQUEST['nomEquipoLabel'].$_REQUEST['ligaSelect']. $_REQUEST['localidadLabel'].$_REQUEST['Internacional']."\n";


$stmt = $pdo->prepare('update equipos
							set nomEquipo= :param1,
							set codLiga= :param2,
							set localidad= :param3,
							set internacional= :param4
							where codEquipo= :param5;'
							);

	
	

	
$stmt->bindParam(':param1', $_REQUEST['nomEquipoLabel']);
$stmt->bindParam(':param2', $_REQUEST['ligaSelect']);
$stmt->bindParam(':param3', $_REQUEST['localidadLabel']);
$stmt->bindParam(':param4', $_REQUEST['Internacional']);
$stmt->bindParam(':param4', $_REQUEST['codEquipo']);
$stmt->execute(); //ejecuto


$pdo=null;
}
catch (Exception $e) {
echo 'No se puede actualizar un equipo porque los datos no son correctos.';

}
?>
<br/><br/>
<a href="modificarEquipo.php" class="button">Volver </a>
    <footer>MICHAEL JONAY TRUJILLO PADILLA</footer>
</body>
</html>

