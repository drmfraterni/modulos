<?php

	class Controller
	{


		public function __construct(){

			//echo '<p>Controlador Base</p>';

			$this->view = new View();

		}

		public function loadModel($model)
		{
			$url = 'models/'.$model.'model.php';

			if (file_exists($url)) {

				require $url;

				$modelName = $model.'Model';
				$this->model = new $modelName();


				
			}
		}

	}



?>