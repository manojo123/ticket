<?php   
	$title="Lista de Tickets";
	include("lib/header.php");
	$update=0;

	if(isset($_GET['eliminar']))
	{
		$eliminar = htmlspecialchars($_GET['eliminar']);
		if(is_numeric($eliminar))
		{
			mysqli_query($conn,"DELETE FROM tickets WHERE id = $eliminar");
			$msgsystem = "<div class='alert alert-success'>Ticket eliminado exitosamente.</div>";
		}
	}
	if(isset($_GET['editar'])){$editar = htmlspecialchars($_GET['editar']);} else{$editar=0;}	

	if($_SERVER["REQUEST_METHOD"] === "POST")
	{
		$id = 		  htmlspecialchars($_POST['id']);
	    $desc 		= htmlspecialchars($_POST['desc']);
	    $prioridad 	= htmlspecialchars($_POST['prioridad']);

	    if($desc=="") {$error=1;$msgsystem='<div class="alert alert-warning">Por favor completar todos los campos</div>';}

	  	if(!isset($error))
	  	{
	  		mysqli_query($conn, "UPDATE tickets SET descripcion = '$desc', prioridad = $prioridad, fecha = CURRENT_TIMESTAMP WHERE id = $id");
	  		$msgsystem='<div class="alert alert-success">Ticket actualizado exitosamente</div>';
	  		$update=1;
	  	}
	}
?>

<div class="row">
	<div class="col-md-12">
		<?php include("layout/sidemenu.php"); ?>
		<div class="col-sm-9">
			<h3>Lista de Tickets</h3>
			
			<?php if(isset($msgsystem)){echo $msgsystem;}
			if(isset($_GET['nombreb'])){$nombreb=addslashes($_GET['nombreb']);} else{$nombreb = "";}
			if(isset($_GET['descb'])){$descb=addslashes($_GET['descb']);} else{$descb = "";} ?>

			<form method="GET">
				<p>
					<input type="text" name="nombreb" placeholder="Filtrar Nombre" value="<?php if($nombreb<>'') echo $nombreb; ?>">
					<input type="text" name="descb" placeholder="Filtrar Descripción" value="<?php if($descb<>'') echo $descb; ?>">
					<input type="submit" id="submit" value="Enviar" class="btn btn-sm btn-primary"/></p>	
				</p>
			</form>

			<?php 
			$busqueda = "WHERE nombre LIKE '%".$nombreb."%' AND descripcion LIKE '%".$descb."%'";
			$query = "SELECT id, nombre, descripcion, CASE prioridad WHEN 0 THEN 'Bajo' WHEN 1 THEN 'Medio' ELSE 'Urgente' END prioridad, UNIX_TIMESTAMP(fecha) as fecha FROM tickets ".$busqueda. "ORDER BY prioridad DESC, fecha ASC";
			$tickets = mysqli_query($conn,$query);
			if(!mysqli_num_rows($tickets))
			{ echo "<div class='alert alert-warning'>No hay tickets en el momento.</div>"; }
			else
			{ ?>
			<table class="table table-striped">
				<tr>
					<td><b>Nombre</b></td>
					<td><b>Descripción</b></td>
					<td><b>Prioridad</b></td>
					<td><b>Fecha</b></td>
					<td><b>Acción</b></td>
				</tr>
			<?php 
				while($row = mysqli_fetch_assoc($tickets)) 
				{
					if($editar == $row['id'] && !$update)
					{ ?>
						<form method="post" autocomplete="off">
						<?php
							echo "<tr><td>".$row['nombre']."</td>";
							echo '<td><textarea id="desc" name="desc" class="form-control" rows="3" placeholder="Descripción">'.$row["descripcion"].'</textarea></td>';
							echo '<td><select class="form-control" id="prioridad" name="prioridad">
					            	<option value="2" selected>Urgente</option>';
					        if($row['prioridad'] == "Medio") {$selected="selected";} else {$selected="";}
							echo '<option value="1" '.$selected.'>Medio</option>';
					        if($row['prioridad'] == "Bajo") {$selected="selected";} else {$selected="";}
							echo '<option value="0" '.$selected.'>Bajo</option></select></td>';

							echo "<td>".date("F j Y g:i:s", $row['fecha'])."</td>";
							echo "<input type='hidden' name='id' value='".$row["id"]."'>";
							echo "<td><input type='submit' id='submit' value='Enviar' class='btn btn-sm btn-success'/><a href='lista.php' class='btn btn-sm'>Cancelar</a></td></tr>"; ?>
						</form> 
					<?php }
					else
					{
						echo "<tr><td>".$row['nombre']."</td>";
						echo "<td>".$row['descripcion']."</td>";
						echo "<td>".$row['prioridad']."</td>";
						echo "<td>".date("F j Y g:i:s", $row['fecha'])."</td>";
						echo "<td>
						<a class='btn btn-sm btn-warning' href='lista.php?editar=".$row['id']."'>Editar</a> 
						<a class='btn btn-sm btn-danger' onclick='return confirm(\"Confirmar eliminación de ticket\")' href='lista.php?eliminar=".$row['id']."'>Eliminar</a></td></tr>";
					}

				}
    		} ?>
			</table>
		</div>
	</div>
</div>