<?php

	class App
	{
		public function __construct()
		{
			
			/**
			 * La variable de $url nos viene del archivo .htaccess.
			 * Recogemos la ruta, la limpiamos de espacios y la dividimos en trozos
			 * El primer trozo es el controllador - $url[0]
			 * El segundo va a ser el método del controlador - $url[1]
			 * El tercero va a ser la variable - $url[2]
			 * 
			 */

			//comprobamos que la url lleva algo.
			$url = isset($_GET['url']) ? $_GET['url'] : null;
			$url = rtrim($url, '/');
			$url = explode('/', ucwords($url));

			

			// si la url no lleva nada

			if (empty($url[0])) {

				$archivoController = 'controllers/main.php';
				require_once $archivoController;
				$controller = new Main();
				$controller->loadModel(main);
				return false;	

			} 

			$archivoController = 'controllers/' . $url[0] . '.php';


			if(file_exists($archivoController))
			{
				require_once $archivoController;
				// llamamos al controlador
				$controller = new $url[0];
				// llamamos al modelo
				$controller->loadModel($url[0]);

				if (isset($url[1])) {

					/**
					 * 
					 * Cargamos el método del controlador
					 * Por ejemplo cargamos el controlador Main.php
					 * Y posteriormente el método saludos
					 * ruta sería curso_mvc/main/saludo
					 * 
					 */

					if (isset($url[2])) {

						$variable = $url[2];
						$controller->{$url[1]}($variable);
						
					} else {

						$controller->{$url[1]}();

					}

					
					
				}

			} else {

				require 'controllers/Errores.php';
				$controller = new Errores();

			}

			


		}

	}


?>