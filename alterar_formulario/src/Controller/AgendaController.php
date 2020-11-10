<?php
namespace Drupal\alterar_formulario\Controller;


use Drupal;
use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\Response;
use Drupal\alterar_formulario\Services\Calendario;
use Symfony\Component\HttpFoundation\Request;
/**
* Controlador para la Agenda   
*/

class AgendaController extends ControllerBase {

	private $urlConfig;

	private $cambioMes = false;

	private $fecha_inicio;

	private $sefecha_fin;


	public function ver_agenda($fecha_inicio, $fecha_fin) {
		$this->urlConfig = $this->config('alterar_formulario.settings');
		
		$req = \Drupal::request();
		//$rutaBase = $req->getSchemeAndHttpHost().$req->getBaseUrl().'/ver-toda-agenda/';
		$rutaBase = $req->getSchemeAndHttpHost().$req->getBaseUrl().'/ver-toda-agenda';


		// FORMATEO DE LAS FECHAS DE INICIO Y FIN  //
		// comprobramos si es para cambio de calendario $this->cambioMes = true
		// o si se elige un evento $this->cambioMes = false
		if($fecha_inicio == null || $fecha_inicio == '' || empty($fecha_inicio)) {
			$this->fecha_inicio = date("Ymd");
		} else {
			$this->fecha_inicio = self::formatearFecha($fecha_inicio);
		}

		if($fecha_fin == null || $fecha_fin == '' || empty($fecha_fin)) {
			if($this->cambioMes) {
				$this->fecha_fin = self::formatearFecha($fecha_inicio, date('t',strtotime($this->fecha_inicio ))); 
			} else {
			        $this->fecha_fin = $this->fecha_inicio;
			}
		} else {
			$this->fecha_fin = self::formatearFecha($fecha_fin);
		}
		
		$direccion = $this->urlConfig->get('agenda') .$this->fecha_inicio .'\\' .$this->fecha_fin;
		//$direccion = 'http://localhost/portal/sites/default/files/cursos/cursos.json';


		// CONSTRUCCIÓN DEL CALENDARIO  //	
		$usCalendar = new Calendario();


		$fecha = strtotime($this->fecha_inicio);

		$diaHoy = date("d-m-Y", $fecha);		
		$month = date("Y-m", $fecha);
		$elmes = date("m", $fecha);

		$controlDia = date("Y-m-d", $fecha);
		$agenda['diahoy'] = $controlDia;
		
		$verMeses = $usCalendar->calendar_month($month);
		$verMes = $usCalendar->spanish_month($verMeses['month']);


		$agenda['data'] = $verMeses;
		$agenda['elmes'] = $verMes;

		//Drupal::logger('alterar_formulario') ->notice($rutaBase);
		$data = file_get_contents($direccion);

		//var_dump($data);
		//die();
		$cat_facts = json_decode(utf8_encode($data), true);

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

	    	}
		$textos['cantEventosMes'] = 0;

		if(isset($textos['eventMes'])){
			$textos['cantEventosMes'] = count($textos['eventMes']);
		}

		return array(
			'#base' => $rutaBase,
      		'#datos' => $cat_facts['filas'],
      		'#agenda' => $agenda,
	      	'#theme' => 'toda_agenda'
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
		$fechaEvents = null;
		$events = explode("/", $eventos);
		$diaf1    = $events[0];  
  		$mesf1  = $events[1];  
  		$anyof1  = $events[2];

  		if ($mesf1 == $mes){  			
  			$fechaEvents = $anyof1."-".$mesf1."-".$diaf1;
  		}

  		return $fechaEvents;


	}

	public function formatearFecha ($fecha, $diadef="01"){
		$ret =  date("Ymd");
		$nfecha = explode("-", $fecha);
		if(count($nfecha) == 1) { // No viene separada por guión
			 $nfecha = explode("/", $fecha);
		}
		if(count($nfecha) == 3) {
			$anyo = $nfecha[0];  
	  		$mes  = $nfecha[1];  
			$dia  = $nfecha[2];
			if(checkdate ($mes , $dia , $anyo)) {
				$ret = $anyo."".$mes."".$dia;
			} elseif(checkdate ($anyo , $mes , $dia)) {
				$ret = $dia."".$mes."".$anyo;
			}
		} elseif(count($nfecha) == 2) { // Viene año y mes, el mes como nombre corto, "Oct"
			$mes = date('m', strtotime($nfecha[1]));
			$ret =  $nfecha[0]."".$mes."".$diadef;
			$this->cambioMes = true; // Indica que se trata de la consulta de un mes entero
		}
  		return $ret;
  	}



}


