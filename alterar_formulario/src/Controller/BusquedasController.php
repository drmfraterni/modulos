<?php
namespace Drupal\alterar_formulario\Controller;

use Drupal;
use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\Response;

class BusquedasController extends ControllerBase {


 public function registro_usuarios(){

      $urlConfig = $this->config('alterar_formulario.settings');
      $direccion = $urlConfig->get('direccion');
	  
      //$direccion = 'http://localhost/prueba/prueba.html';
        return [
            '#theme' => 'registro_usuarios',
            '#titulo' => $this->t('IDENTIFICACIÓN'),
            '#direccion' => $direccion,
        ];

  }
  public function externa_cabecera(){

      $cabecera = true;

        return [
            '#theme' => 'gestion_externa',
            '#titulo' => $this->t('cabecera'),
            '#cabecera' => $cabecera,
        ];

  }

  public function vista_convocatorias(){

      // vemos las convocatorias //
      /*$ruta = \drupal::request()->getSchemeAndHttpHost();

      if ($ruta == "http://localhost"){
        $ruta = $ruta."/portal/";
      }*/

      $urlConfig = $this->config('alterar_formulario.settings');
      $direccion = $urlConfig->get('ambito');
	  //\Drupal::logger('BusquedasController')->notice($direccion);

      $convocatorias = [ 'ruta' => $direccion ];

      $convocatorias['urlAmbito'] = self::nuevaRuta();


      // sacamos todas las convocatorias de la base de datos
      $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
      $query->condition('type', 'convocatoria'); //
      $query->condition('field_tipo_convocatoria', 4); // el 4 es noticia taxonomia tipo información
      $query->sort('field_convocatoria_orden', 'ASC');
      $query->sort('field_fecha_convocatoria', 'DESC');
      // ->condition('field_ficha', $clave);
      $nids = $query->execute();
      $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);

      // metemos en convocatorias todos los campos con sus características
      foreach ($nodes as $node){
          $convocatorias['titulo'][] = strip_tags($node->get('title')->value);
          $cadena = strip_tags($node->get('body')->value);
          $convocatorias['descripcion'][] = substr($cadena,0,150);
          $convocatorias['enlace'][] = $node->get('field_enlace_convocatoria')->target_id;
          $convocatorias['fecha'][] = $node->get('field_fecha_convocatoria')->value;
          $convocatorias['imagen'][] = (!empty($node->get('field_imagen_convocatoria')->path) ? $node->get('field_imagen_convocatoria')->path->value : '');
          $convocatorias['imagen']['target_id'][] = $node->get('field_imagen_convocatoria')->target_id;
          $convocatorias['imagen']['url'][] = substr($node->get('field_imagen_convocatoria')->entity->uri->value, 8);
          $convocatorias['tipo'][] = $node->get('field_tipo_convocatoria')->target_id;
          //dirección url del nodo
          $convocatorias['urlNode'][] = $node->toUrl()->toString();

      }

      // devolvemos la información al tema
      return [
          '#theme' => 'destacados',
          '#titulo' => $this->t('Últimas Noticias'),
          '#convocatorias' => $convocatorias,
      ];

  }
  
	  /**
	  *
	  **/
	public function calendario_datos( $fecha_inicio, $fecha_fin ){
		//$url = 'http://10.12.99.62:8080/IntraCEJ/ws?accion=nivel&usr=jmrodriguez&pwd=&app=IntraCEJ&nivel=WSCalendario&params=' .$fecha_inicio .'\\' .$fecha_fin;

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
		
		$datos2 = [];
		if (!empty($datos)){
			$datos = json_decode(utf8_encode($datos));
			foreach($datos ->filas as $dato){
				$d = explode('/', $dato[0]);
				$h = explode(' - ', $dato[1]);
				$h1 = explode('.', str_replace(':', '.', $h[0]));
				$datos2[] = [
					'title' => $dato[4], 
					'description' => $dato[4] .' - '. $dato[2], 
					'datetime' => 'new Date(' .intval($d[2]). ',' .intval($d[1]). ',' .intval($d[0]). ',' .intval($h1[0]). ',' .intval($h1[1]). ')', 
					//'url' => '',
				];
			}
		}
		$response = new Response();
		$response->setContent( json_encode($datos2) );
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}



  function nuevaRuta () {  // rutas para que carguen las imágenes en el carousel


    if (isset($_SERVER['REQUEST_URI'])){
      $subruta = explode("/", $_SERVER['REQUEST_URI']);
      $subruta = "/".$subruta[1];

    }

    if (isset($_SERVER['HTTPS'])) {
      $pt = "https://";
    }else{
      $pt = "http://";
    }


    $nr = $pt.$_SERVER['HTTP_HOST'].$subruta;

    return $nr;

  }


	  

}

?>
