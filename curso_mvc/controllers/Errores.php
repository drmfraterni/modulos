<?php

	class Errores extends Controller
	{
		

		public function __construct()
		{
			parent::__construct();
			$this->view->mensaje = "¡¡¡Hubo un error o la página no existe!!!";
			$this->view->render('errores/index');


		}


	}



?>