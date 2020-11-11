<?php

namespace Drupal\alterar_formulario\Controller;


use Drupal;
use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\Response;
use Drupal\alterar_formulario\Services\Miscelaneo;
use Symfony\Component\HttpFoundation\Request;
use Drupal\taxonomy\Entity\Term;

/**
* Controlador para la Galería   
*/

class GaleriaController extends ControllerBase {

	private $numPag; 

	public function ver_galeria($page) {

      // incluimos el formulario para filtrar       

      $form = $this->formBuilder()->getForm('Drupal\alterar_formulario\Form\FiltroGaleriaForm');

	if (!empty($page)){
		$this->numPag = $page; 
	}else{

		$this->numPag = 1;
	}

      $titulo = \Drupal::request()->query->get('titulo');
      $annio = \Drupal::request()->query->get('annio');
      $mes = \Drupal::request()->query->get('mes');




	// SACAMOS TODAS LAS IMÁGENES
      $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
      $query->condition('type', 'galeria_imagen');

      if (!empty($titulo)){
            $query->condition('title','%'.$titulo.'%', 'LIKE');

      }

      if (!empty($mes)){
            $query->condition('field_mes',$mes);

      }

      if (!empty($annio)){
            $query->condition('field_a',$annio);

      }


      $query->sort('title', 'ASC');
      // ->condition('field_ficha', $clave);

      $nids = $query->execute();

      $galeriaImg['mensaje'] = FALSE;



      if (empty($nids)) {

            $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
            $query->condition('type', 'galeria_imagen');
            $query->sort('title', 'ASC');
            // ->condition('field_ficha', $clave);
            $nids = $query->execute();
            $galeriaImg['mensaje'] = TRUE;


      }


      $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);

      // Datos que cogemos de Services > Miscelaneo
      $nuevoTit = new Miscelaneo (); // instanciamos la funciones de Miscelaneo
      $galeriaImg['urlBase'] = $nuevoTit->urlCompleta(); // ruta completa


      $ctit = "";
      $elemId = array();

      // ELEMENTOS DE LA BASE DE DATOS

      foreach ($nodes as $node){

      	$comprobartit = $nuevoTit->limpiarTitulo(strip_tags($node->get('title')->value)); 

      	// Sacamos los que no están repetidos	

      	if ($ctit != $comprobartit) {

			// Los título que vamos a ver.
      		$galeriaImg['titulo'][] = strip_tags($node->get('title')->value);
      		$galeriaImg['imagUrl'][] = substr($node->get('field_galeria_imagen')->entity->uri->value, 8);
      		//termino referenciado en taxonomia año
      		$annio = $node->get('field_a')->target_id;
      		//código de referencia anual taxonomia
      		$nAnnio[] = $annio;
      		$termAnnio[] = taxonomy_term_load($annio);
      		//termino referenciado en taxonomia mes
      		$nmes = $node->get('field_mes')->target_id;
      		//código de referencia mesual taxonomia
      		$elmes[] = $nmes; 
      		$termMes[] = taxonomy_term_load($nmes);

      	} 

      	// todos los titulos
      	$galeriaImg['allTit'][] = strip_tags($node->get('title')->value); 
      	$ctit = $nuevoTit->limpiarTitulo(strip_tags($node->get('title')->value));

      }




      // Sacamos el número de veces que cada elemento está repetidos
      $nt = array_count_values($galeriaImg['allTit']);
      $galeriaImg['contador'] = count($nt);
      $paginas = (count($nt))/20;
      // Sabemos cuantas páginas tenemos.
      $galeriaImg['paginas'] = ceil($paginas);
      $galeriaImg['numPag'] = $this->numPag;



      for ($i=0; $i < count($nt) ; $i++) { 

      	$galeriaImg['repe'][$i] = $nt[$galeriaImg['titulo'][$i]];
      	$galeriaImg['nannio'][$i] = $nAnnio[$i];
      	//$galeriaImg['annio'][$i] = $termAnnio[$i]->get('name')->value;
            $galeriaImg['annio'][$i] =(!empty($termAnnio[$i]) ? $termAnnio[$i]->get('name')->value : '');
      	//$galeriaImg['mes'][$i] = $termMes[$i]->get('name')->value;
            $galeriaImg['mes'][$i] = (!empty($termMes[$i]) ? $termMes[$i]->get('name')->value : '');
      	$galeriaImg['elmes'][$i] = $elmes[$i];

      }
	
	return [
        '#theme' => 'galeria_imagenes',
        '#galeria' => $galeriaImg,
        '#formulario' => $form,
      ];

    }

}


