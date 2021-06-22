<?php

	class Ayuda extends Controller
	{
		

		public function __construct()
		{
			parent::__construct();
			$this->view->mensaje = "Error Genérico";
			$this->view->render('ayuda/index');


		}


	}



?>