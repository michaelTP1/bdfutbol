<!DOCTYPE html>
<?php 
include 'conectaBD.php';
$pdo=conectadb();
?>


<html lang="es">  
  <head>    
   
    <meta charset="UTF-8">
	
	<style>
		
    .volverButton {
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

 .submitButton {
    background-color: white;
    border: 2px solid blue;
    color: blue;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
       
}
    .submitButton:hover {
     background-color: blue; 
     color: white;
}
	</style>
    
  </head>  
  <body>    
        
    <section>  
	
	<?php
		if(isset ($_GET['modificar'])){
			echo "<h1> Modificar Equipo </h1>";
			//echo $_GET['modificar'];
			$equipos=str_replace("'", "\"",$_GET['modificar']);
			$equipo=json_decode($equipos, true);
			echo $equipo['nomEquipo'];
		?>
		
			<form id="form" method="post" action="equipoUpdate.php?codEquipo= <?php echo $equipo['codEquipo'] ?>">
				<label> Nombre del equipo: <input type="text" id="nomEquipoLabel" name="nomEquipoLabel" value="<?php echo $equipo['nomEquipo'] ?> "<br/><br/><br/></label>
				<select id="ligaSelect" name="ligaSelect">
					<?php
		
						$stmt=$pdo->query('select nomLiga, codLiga from ligas');
		
						while($row=$stmt->fetch()){
							$ligas=$row["nomLiga"];
							$codLiga=$row["codLiga"];
							if($codLiga==$equipo['codLiga'])
								echo "<option value=".$codLiga." selected>".$ligas."</option> <br/>";
							else
								echo "<option value=".$codLiga.">".$ligas."</option> <br/>";
						
          
						};
					?>
					</select>
					<br/><br/>
					<label>Localidad: <input type="text" id="localidadLabel" name="localidadLabel" value="<?php echo $equipo['localidad'] ?> "</label>
					<br/><br/>
					<label>Internacional</label>
					<?php
						if($equipo['internacional']==1){
							?>
							<label>Internacional
								<input type="radio" id="Internacional" name="Internacional" value="1" checked>
									Sí
								<input type="radio" id="Internacional" name="Internacional" value="0" >
									No 
							</label>
  <br><?php
						}else{
							?>
							<label>Internacional
								<input type="radio" id="Internacional" name="Internacional" value="1" >
									Sí
								<input type="radio" id="Internacional" name="Internacional" value="0" checked>
									No 
							</label>
							<?php
						} ?>	
				
				
				
			</select>
			<br/><br/>
			<input type="submit" class="submitButton">
			<br/><br/>
		</form>
		
			
	<?php			
		} else {
			echo "<h1> Alta Equipo </h1>";
			?>
			<form id="form" method="post" action="equipoInsert.php">
				<label> Nombre del equipo: <input type="text" id="nomEquipoLabel" name="nomEquipoLabel"<br/><br/><br/></label>
				<select id="ligaSelect" name="ligaSelect">
					<?php
		
						$stmt=$pdo->query('select nomLiga, codLiga from ligas');
		
						while($row=$stmt->fetch()){
							$ligas=$row["nomLiga"];
							$codLiga=$row["codLiga"];
								echo "<option value=".$codLiga.">".$ligas."</option> <br/>";
						};
					?>
					</select>
					<br/><br/>
					<label>Localidad: <input type="text" id="localidadLabel" name="localidadLabel"</label>
					<br/><br/>
					
					<label>Internacional
								<input type="radio" id="Internacional" name="Internacional" value="1" >
									Sí
								<input type="radio" id="Internacional" name="Internacional" value="0" checked>
									No 
							</label>	
				
				
				</select>
				<br/><br/>
				<input type="submit" class="submitButton">
			</form>
		
		<br/><br/>
		<a href="visualizar.php"class="volverButton">Volver </a>

		<?php
		}

		?>	
	
    </section>
	    <footer>MICHAEL JONAY TRUJILLO PADILLA</footer>
  </body>  
</html>

