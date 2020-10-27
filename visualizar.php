<?php
include 'conectaBD.php';
$pdo=conectadb();

?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
		

        <style>
#datos {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#datos td, #datos th {
    border: 1px solid #ddd;
    padding: 3px;
}

#datos tr:nth-child(even){background-color: #f2f2f2;}

#datos tr:hover {background-color: #ddd;}

#datos th {
    padding-top: 5px;
    padding-bottom: 5px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}	
    .tableBt {
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
 .formbutton {
    background-color: white;
    border: 2px solid blue;
    color: blue;
    padding: 15px 32px;
    text-align: right;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    float: right;    
}
    .formbutton:hover {
     background-color: blue; 
     color: white;
     {
    background-color: white;
    border: 2px solid blue;
    color: blue;
    padding: 15px 32px;
    text-align: right;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    float: right;    
}
   
</style>
      
    </head>
    
	<body>
		
		<?php
		if(isset($_SESSION['message'])): ?>
			<div class="alert alert -<?=$_SESSION['msg_type']?>">
			
			<?php
				echo $_SESSION['message'];
				unset($_SESSION['message']);
		
			?>
		</div>
		<?php endif ?> 
		
		
		
			<table id="datos">
				<tr>
			<th>codEquipo</th>
			<th>nomEquipo</th> 
			<th>codLiga</th>
			<th>localidad</th>
			<th>internacional</th>
	
			</tr>
		
			<?php
		
			$stmt=$pdo->query('select * from equipos');
			$stmt->execute();
			
			while($row=$stmt->fetch()){
				$equipo=["codEquipo"=> $row["codEquipo"], "nomEquipo"=> $row["nomEquipo"], "codLiga"=>$row["codLiga"], "localidad"=>$row["localidad"], "internacional"=>$row["internacional"]];
				$json=json_encode($equipo);
				$equipos=str_replace("\"", "'",$json);
				?>
				<tr>
				
				<td><?php echo $equipo["codEquipo"] ?></td>
				<td><div id="nomEquipo"><?php echo $equipo["nomEquipo"] ?></div></td>
				<td><?php echo $equipo["codLiga"] ?></td>
				<td><?php echo $equipo["localidad"] ?></td>
				<td><?php echo $equipo["internacional"] ?></td>
				<td>

					<a href="modificarEquipo.php?modificar=<?php echo $equipos; ?>" 
						class="tableBt">Modificar </a>
				</td>
				<td>
					
					<a href="eliminarEquipo.php?eliminar=<?php echo $row['codEquipo']; ?>" onClick="return ConfirmDelete();"
						class="tableBt">Eliminar</a>
				</td>		
								</tr>
     
				<?php
			}
			?>

      
			</table>
			
			<a href="modificarEquipo.php" 
						class="formbutton">Alta </a>
	
		
	
	<script>
		function ConfirmDelete()
    {
		var nomEquipo=document.getElementById("nomEquipo");
		var x = confirm("Va a eliminar " + nomEquipo.textContent);
		if (x)
          return true;
		else
        return false;
    }
	</script>

        
      

    <footer>MICHAEL JONAY TRUJILLO PADILLA</footer>
    
	</body>
</html>
