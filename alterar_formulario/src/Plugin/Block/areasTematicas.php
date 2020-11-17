<?php
namespace Drupal\alterar_formulario\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\alterar_formulario\Services\Miscelaneo;

/**
 * Provides a 'AreasTematicas' Block
 *
 * @Block(
 *   id = "areas_tematicas",
 *   admin_label = @Translation("Áreas tematicas"),
 * )
 */
class areasTematicas extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {


    // sacamos todas las Áreas temáticas de la base de datos
      $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
      $query->condition('type', 'secretaria_general'); //
      $query->condition('field_ambito',44); // el 4 es noticia taxonomia tipo información
      $query->sort('title', 'ASC');
      $nids = $query->execute();
      $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);

      // metemos en convocatorias todos los campos con sus características
      foreach ($nodes as $node){
          $areaTematica['titulo'][] = strip_tags($node->get('title')->value);
          $areaTematica['urlNode'][] = $node->toUrl()->toString();
          $areaTematica['destacada'][] = substr($node->get('field_destacada')->entity->uri->value, 8);

      }
      // Datos que cogemos de Services > Miscelaneo
      $rutaAbs = new Miscelaneo (); // instanciamos la funciones de Miscelaneo
      $areaTematica['urlBase'] = $rutaAbs->urlCompleta(); // ruta completa


      return array(
      '#datos' => $areaTematica,
      '#theme' => 'areasTematicas',
    );


  }

}

