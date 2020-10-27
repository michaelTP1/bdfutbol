
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
 .button {
    background-color: brown;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}
</style>


</head>
<body>
<?php
try {
include 'conectaBD.php';
$pdo=conectadb();
echo $_POST['nomEquipoLabel'].$_POST['ligaSelect']. $_POST['localidadLabel'].$_POST['Internacional']."\n";



$stmt = $pdo->prepare('CALL insertar_equipo(:param1, :param2, :param3, :param4, @existe, @inserto)');
$stmt->bindParam(':param1', $_POST['nomEquipoLabel']);
$stmt->bindParam(':param2', $_POST['ligaSelect']);
$stmt->bindParam(':param3', $_POST['localidadLabel']);
$stmt->bindParam(':param4', $_POST['Internacional']);
$stmt->execute(); //ejecuto
$stmt=$pdo->query('select @existe as parametroSalida1');
$row = $stmt->fetch();
if ($row['parametroSalida1'] == 1) {
	echo 'Existe la Liga. <br>';
	echo 'Se insertó el registro.';
}
else {
	echo 'No existe la Liga, introduzca una correcta, por favor. <br>';
	echo 'No se insertó el registro.';
}

$pdo=null;
}
catch (Exception $e) {
	
echo 'No se puede introducir un equipo porque los datos no son correctos.';
}
?>
<br><br>
	<a href="modificarEquipo.php" class="button">Volver </a>
    <footer>MICHAEL JONAY TRUJILLO PADILLA</footer>
</body>
</html>

<?php /*
include 'conectaBD.php';
$pdo=conectadb();

session_start();
	
	if(isset($_POST)){
	
	
	if( $_POST['internacionalCb']=='internacional'){
		$internacional='true';
	}else
		$internacional='false';
	//echo $_POST['nomEquipoLabel'].$_POST['ligaSelect'].$_POST['localidadLabel'].$internacional;
	
	
	try{
		$stmt = $pdo->prepare('CALL insertar_equipo(:nomEquipo, :codLiga, :localidad, :internacional, @liga_existe, @error_insercion)');
		
		$stmt->bindParam(':nomEquipo', $_POST['nomEquipoLabel']);
		$stmt->bindParam(':codLiga', $_POST['ligaSelect']);
		$stmt->bindParam(':localidad', $_POST['localidadLabel']);
		$stmt->bindParam(':internacional', $internacional);
		$stmt->execute(); //ejecuto
		
		$stmt=$pdo->query('select @existe as parametroSalida1');
		$row = $stmt->fetch();
		$pdo=null;
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
		
//				$stmt = $pdo->prepare('select @liga_existe, @error_insercion');
//
	}
		
*/
?>