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

	public function ver_galeria(){

	// SACAMOS TODAS LAS IMÁGENES
      $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
      $query->condition('type', 'galeria_imagen'); //
      $query->sort('title', 'ASC');
      // ->condition('field_ficha', $clave);
      $nids = $query->execute();
      $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);

      // Datos que cogemos de Services > Miscelaneo
      $nuevoTit = new Miscelaneo (); // instanciamos la funciones de Miscelaneo
      $galeriaImg['urlBase'] = $nuevoTit->urlCompleta(); // ruta completa

      //var_dump($galeriaImg['urlBase']);
      //die();

      $ctit = "";

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
      		$termAnnio[] = taxonomy_term_load($annio);
      		//termino referenciado en taxonomia mes
      		$nmes = $node->get('field_mes')->target_id;
      		$termMes[] = taxonomy_term_load($nmes);

      	} 

      	// todos los titulos
      	$galeriaImg['allTit'][] = strip_tags($node->get('title')->value); 
      	$ctit = $nuevoTit->limpiarTitulo(strip_tags($node->get('title')->value));

      }




      // Sacamos el número de veces que cada elemento estárepetidos
      $nt = array_count_values($galeriaImg['allTit']);
      $galeriaImg['contador'] = count($nt);



      for ($i=0; $i < count($nt) ; $i++) { 

      	$galeriaImg['repe'][$i] = $nt[$galeriaImg['titulo'][$i]];
      	$galeriaImg['annio'][$i] = $termAnnio[$i]->get('name')->value;
      	$galeriaImg['mes'][$i] = $termMes[$i]->get('name')->value;

      }

	
		return [
            '#theme' => 'galeria_imagenes',
            '#galeria' => $galeriaImg,
        ];

    }

}


