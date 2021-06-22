	<!-- HEADER -->

	<?php include 'views/templates/header.php'; ?>

	<!-- BARRA DE MENÚS -->

	<?php include 'views/templates/menu.php'; ?>

	<!-- CONTENT -->

	  	<section id="contenido">
	  		<div class="container-fluid">
	  			<div class="col-12 bg-warning ">
	  				<h1 class="display-3 text-center text-danger  p-3 my-5 "><?php echo $this->mensaje; ?></h1>
	  				<p class="img-error text-center my-5"><img src="public/img/error_404.jpg"></p>
	  				<p>&nbsp;</p>
	  			</div>
	  		</div>
	  	</section>

	<!-- FOOTER -->

	<?php include 'views/templates/footer.php'; ?>