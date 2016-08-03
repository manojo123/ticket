<?php   
	$title="Emitir Tickets";
	include("lib/header.php");    
?>

<?php
	if($_SERVER["REQUEST_METHOD"] === "POST")
	{
	    $nombre 	= htmlspecialchars($_POST['nombre']);
	    $desc 		= htmlspecialchars($_POST['desc']);
	    $prioridad 	= htmlspecialchars($_POST['prioridad']);

	    if($nombre=="" || $desc=="") {$error=1;$msgsystem='<div class="alert alert-warning">Por favor completar todos los campos</div>';}

	  	if(!isset($error))
	  	{
	  		mysqli_query($conn, "INSERT INTO tickets (nombre, descripcion, prioridad, fecha) VALUES('$nombre','$desc','$prioridad',CURRENT_TIMESTAMP)");
	  		$msgsystem='<div class="alert alert-success">Ticket enviado exitosamente</div>';
	  	}
	}
?>

<div class="row">
	<div class="col-md-12">
		<?php include("layout/sidemenu.php"); ?>
		<div class="col-sm-9">
			<h3>Emitir Ticket</h3>
			<?php if(isset($msgsystem)) echo $msgsystem; ?>
			<form method="post" autocomplete="off">
				<p><input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre" value="<?php if(isset($error)){ print $_POST['nombre']; } ?>"></p>
				<p><textarea id="desc" name="desc" class="form-control" rows="10" placeholder="Descripción"><?php if(isset($error)){ print $_POST['desc']; } ?></textarea></p>
				<p><select class="form-control" id="prioridad" name="prioridad">
	            	<option value="2" selected>Urgente</option>
	            	<option value="1" <?php if(isset($error) && $_POST['prioridad'] == "1"){echo "selected";} ?>>Medio</option>
	            	<option value="0" <?php if(isset($error) && $_POST['prioridad'] == "0"){echo "selected";} ?>>Bajo</option>
	            </select></p>
	            <p><input type="submit" id="submit" value="Enviar" class="btn btn-lg btn-success" style="width: 100%;"/></p>
	        </form>
		</div>
	</div>
</div>

<body>
<html>
