<?php
	
	/**
	 *  El nuevo modelo extiende de Model libs/Models
	 *  En libs/Models viene la conexión a la base de datos
	 */

	class NuevoModel extends Model
	{

		public function __construct()
		{
			parent::__construct();
		}

		public function insert($datos)
		{

			
			/* CONSULTA EN LA BBDD
			
			$query = $this->db->connect()->prepare('SELECT matricula as id, nombre, apellidos FROM alumnos');
			$query->execute();
			$resultado = $query->fetchAll();
			var_dump($resultado);
			*/

			// INSERTAR DATOS EN LA BBDD

			try{

				$query = $this->db->connect()->prepare('INSERT INTO alumnos (matricula, nombre, apellidos) VALUES (:matricula, :nombre, :apellidos)');
				$query->execute(['matricula' => $datos['matricula'], 'nombre' => $datos['nombre'], 'apellidos' => $datos['apellidos']]);
					echo "insertar datos";

			}catch(PDOException $e){

				//echo $e->getMessage();
				echo "El dni ya existe";

			}

			
		}

	}



?>