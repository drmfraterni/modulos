	<!-- HEADER -->

	<?php include 'views/templates/header.php'; ?>

	<!-- BARRA DE MENÚS -->

	<?php include 'views/templates/menu.php'; ?>

	<!-- CONTENT -->

	  	<section id="contenido" class="my-5">
	  		<div class="container">
	  			<h1>FORMULARIO PARA CREAR UN NUEVO ALUMNO</h1>


	  			<form action="<?php echo constant('URL'); ?>/nuevo/registrarAlumno" method="POST" class="form-nuevo my-5">
	  				<div class="form-group col-sm-12 col-md-8">
					   <label for="matricula">Matricula</label>
					   <input type="text" name="matricula" class="form-control" id="matricula" aria-describedby="matriculaHelp" placeholder="Matrícula" required>
					</div>
					<div class="form-group col-sm-12 col-md-8">
					   <label for="nombre">Nombre</label>
					   <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Introducir el nombre" required>
					</div>
					<div class="form-group col-sm-12 col-md-8">
					   <label for="apellidos">Apellidos</label>
					   <input type="text" name="apellidos" class="form-control" id="apellidos" placeholder="Introducir los apellidos" required>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>

	  			</form>
	  		</div>
	  	</section>

	<!-- FOOTER -->

	<?php include 'views/templates/footer.php'; ?>
	      