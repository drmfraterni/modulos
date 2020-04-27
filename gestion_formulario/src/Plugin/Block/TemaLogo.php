<?php

namespace Drupal\alterar_formulario\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Clase de control del bloque HolaMundo.
 *
 * @Block(
 *   id = "TemaLogo",
 *   admin_label = @Translation("Tema Logo"),
 *   category = @Translation("Tema Logo"),
 * )
 */
class HolaMundo extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    
    return [
      '#markup' => $this->t('Hola mundo!'),
    ];
  }

}
