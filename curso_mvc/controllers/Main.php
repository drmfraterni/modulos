<?php

	class main extends Controller
	{

		public function __construct()
		{
			parent::__construct();
			
			$this->view->render('main/index');
			
		}

		public function saludo($prueba = null)
		{

			if ($prueba == null) {

				$mensaje = "Os damos la bienvenida SIN VARIABLE";
				
			} else {

				$mensaje = "Os damos la bienvenida con la variable: " . $prueba;

			}

			
			echo $mensaje;


			
		}

	}



?>