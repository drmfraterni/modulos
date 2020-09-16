<?php
namespace Drupal\alterar_formulario\Controller;


use Drupal;
use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\Response;
use Drupal\alterar_formulario\Services\Calendario;

/**
* Controlador para la Agenda   
*/

class AgendaController extends ControllerBase {

	private $urlConfig;

	private $cambioMes = false;

	private $fecha_inicio;

	private $fecha_fin;


	public function ver_agenda($fecha_inicio, $fecha_fin) {

		// configuración ruta del servidor
		// Este parte se configura en la aplicación en la opción:
		// configuración > servicio web > oficina virtual > Dirección del servidor

		$this->urlConfig = $this->config('alterar_formulario.settings');
		$rutaBase = $this->urlConfig->get('ambito');

		$textos['rutaBase'] = $rutaBase;

		// fin ruta web ejemplo : http://localhost/portal


		// FORMATEO DE LAS FECHAS DE INICIO Y FIN  //
		// comprobramos si es para cambio de calendario $this->cambioMes = true
		// o si se elige un evento $this->cambioMes = false

		$this->fecha_inicio =  self::formatearFecha($fecha_inicio);
		$this->fecha_fin = self::formatearFecha($fecha_fin);

		// FILTROS PARA LA CARGA DEL CALENDARIO  //
		// Vista del calendario por mes //
		if ($this->cambioMes == true){

			$fechaCalendario = $fecha_inicio;

			$this->$urlConfig = $this->config('alterar_formulario.settings');
			$direccion = $this->$urlConfig->get('agenda');


		}else{

			// vistas del calendario con una fecha determinada
			// vista general por defecto donde coge de partida el dia en el que estamos 
			if (empty($fecha_inicio)){

				// configuración del json por defecto
				// configuración > servicio web > oficina virtual > Dirección del json de AGENDA

				$this->$urlConfig = $this->config('alterar_formulario.settings');
				$direccion = $this->$urlConfig->get('agenda');

			}

			// vista de los eventos en una día elegido en el calendario //
			if ($fecha_inicio && empty($fecha_fin)){

				//$fecha_inicio = self::formatearFecha($fecha_inicio);
				//$fecha_fin = self::formatearFecha($fecha_fin);

				// ruta del json pasando los parámetros de las fechas.
				$direccion = 'http://10.12.99.62:8080/IntraCEJ/ws?accion=nivel&usr=jmrodriguez&pwd=&app=IntraCEJ&nivel=WSCalendario&params=' .$this->fecha_inicio .'\\' .$this->fecha_inicio;

			}

			// vista de los eventos en un periodo de tiempo
			if ($fecha_fin){

				//$fecha_inicio = self::formatearFecha($fecha_inicio);
				//$fecha_fin = self::formatearFecha($fecha_fin);

				// ruta del json pasando los parámetros de las fechas.
				$direccion = 'http://10.12.99.62:8080/IntraCEJ/ws?accion=nivel&usr=jmrodriguez&pwd=&app=IntraCEJ&nivel=WSCalendario&params=' .$this->fecha_inicio .'\\' .$this->fecha_fin;
			}

		}

		// finde los filtros de eventos para fechas.

		// CONSTRUCCIÓN DEL CALENDARIO  //	

		//	nos traemos el servicio calendario de archivo Calendario.php 
		// instanciamos la clase Calendario para construir el calendario
		$usCalendar = new Calendario();


		if ($fechaCalendario){
			$cambioCalendario = $usCalendar->cambiodecalendario($fechaCalendario);
			$month = $cambioCalendario['fecha'];			
			$elmes = $cambioCalendario['mes'];
			$calAnyo = date("Y");
			$entra = 'nuevo mes del calendario';
			$diaHoy ="01/".$elmes."/".$calAnyo;

			
		}else{
			$diaHoy = date("d/m/Y");
			$month = date("Y-m");
			$elmes = date("m");
			$entra = 'calendario por defecto';
		}

		$verMeses = $usCalendar->calendar_month($month);
		$verMes = $usCalendar->spanish_month($verMeses['month']);

		$agenda['data'] = $verMeses;
		$agenda['elmes'] = $verMes;

		// FIN DE CALENDARIO


		// CONSTRUCCIÓN DEL LISTADO DE EVENTOS //


		//$fechaInicio = $fecha_inicio;
		//$fechaFin = $fecha_fin;

		//$urlConfig = $this->config('alterar_formulario.settings');
      	//$direccion = $urlConfig->get('agenda');

		//$url = 'http://10.12.99.62:8080/IntraCEJ/ws?accion=nivel&usr=jmrodriguez&pwd=&app=IntraCEJ&nivel=WSCalendario&params=' .$fecha_inicio .'\\' .$fecha_fin;

		// MÉTODO 1 DE CARGA //
		/*
		$url = 'http://localhost/cej-demo/sites/default/files/cursos/cursos.json';
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_POST, 0);
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_HTTPPROXYTUNNEL, 0);
		curl_setopt($handle, CURLOPT_PROXY, '192.168.100.250:8086');
		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
		$datos = curl_exec($handle);
		if(curl_errno($handle)) {
			print curl_error($handle);
		}	
		curl_close($handle);
		$cat_facts = json_decode(utf8_encode($datos));

		*/

		// MÉTODO 2 DE CARGA  //


		$rutaFile = $direccion;
		$data =  file_get_contents($rutaFile);
		$cat_facts = json_decode($data, true);


		$textos['cantInicio'] = 0;
		$textos['cantidad'] = 0;

		// Una vez cargado el json sacamos los distintos campos y además filtrados.

		foreach ($cat_facts['filas'] as $cat_fact) {
		
	        $textos['fecha'][] = $cat_fact[0];
	        $eventMes = self::eventosMes($cat_fact[0], $elmes);

	        // vemos las fechas de los eventos del mes en el que estamos.
	        if (!empty($eventMes)){
	        	//$textos['eventMes'][$textos['cantidad']] = $eventMes;
	        	$textos['eventMes'][] = $eventMes;
	        }
	        // cambiamos el formato de fecha a Y-m-d
	        // $textos['nfecha'][] = self::formatearFecha($cat_fact[0]);
	        $textos['horario'][]= $cat_fact[1];
	        $textos['planta'][] = $cat_fact[2];
	        $textos['dirigido'][] = $cat_fact[3];
	        $textos['curso'][] = $cat_fact[4];  
	        $textos['cantidad']++; 
	        // COMPARANDO FECHAS PARA MOSTRAR EN TODOS LOS EVENTOS DEL DÍA EN EL QUE ESTAMOS
	        $ncontrol = self::comparadorFechas($diaHoy, $cat_fact[0], $textos['cantInicio'] );
	        if ($ncontrol == "true"){
	        	$textos['cantInicio']++;
	        }

	    }


	    $textos['cantEventosMes'] = count($textos['eventMes']);

    	// MOSTRAMOS EL FORMULARIO PARA EL FILTRO POR FECHAS  //

    	//$form = $this->formBuilder()->getForm('Drupal\alterar_formulario\Form\AgendaForm');





		return array(
      		'#datos' => $textos,
      		'#agenda' => $agenda,
      		'#theme' => 'toda_agenda'
      		//'#formulario' => $form
    	);

   
	}

	public function comparadorFechas($f1, $f2, $contador){

		$vf1 = explode("/", $f1);
		$vf2 = explode("/", $f2);

		$diaf1    = $vf1[0];  
  		$mesf1  = $vf1[1];  
  		$anyof1  = $vf1[2];

  		$diaf2    = $vf2[0];  
  		$mesf2  = $vf2[1];  
  		$anyof2  = $vf2[2];

  		$fechaf1 = $anyof1."/".$mesf1."/".$diaf1;
  		$fechaf2 = $anyof2."/".$mesf2."/".$diaf2;

  		$control = "false";
  		
  		if ($fechaf2 <= $fechaf1){
  			$control ="true";
  		
  		}
  		
  		return $control;
	}

	public function eventosMes ($eventos, $mes){

		$events = explode("/", $eventos);
		$diaf1    = $events[0];  
  		$mesf1  = $events[1];  
  		$anyof1  = $events[2];

  		if ($mesf1 == $mes){  			
  			$fechaEvents = $anyof1."-".$mesf1."-".$diaf1;
  		}

  		return $fechaEvents;


	}

	public function formatearFecha ($fecha){

		$nfecha = explode("-", $fecha);


		$anyo    = $nfecha[0];  
  		$mes  = $nfecha[1];  
  		$dia  = $nfecha[2];

  		if ($anyo!= null && $dia == null){
  			$this->cambioMes = true;
  		}

  		
  		$fechaNueva = $anyo."".$mes."".$dia;

  		return $fechaNueva;
  	}



}


