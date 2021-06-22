<?php

	class View
	{

		public function __construct(){

			//echo "<p>Vista base</p>";

			
		}


		public function render($nombre) 
		{

			require 'views/' . $nombre. '.php';

		}

	}



?>