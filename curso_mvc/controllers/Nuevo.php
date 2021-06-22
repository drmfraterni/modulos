<?php

	class Nuevo extends Controller
	{	


		public function __construct()
		{
			parent::__construct();
			//$this->view->mensaje = "Error Genérico";
			
			
			$this->view->render('nuevo/index');



		}

		public function registrarAlumno()
		{
			$matricula = $_REQUEST['matricula'];
			$nombre = $_REQUEST['nombre'];
			$apellidos = $_REQUEST['apellidos'];


			$this->model->insert(['matricula' => $matricula, 'nombre' => $nombre, 'apellidos' => $apellidos]);


		}


	}



?>