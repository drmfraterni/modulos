<?php

namespace Drupal\boulder_zone\Controller;

use Drupal;
use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;

class PortadaController extends ControllerBase {


    public function portada() {
    

        return [
          '#type' => 'markup',
          '#markup' => $this->t('Hello, World!'),
        ];
    }


}

?>
