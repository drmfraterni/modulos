<?php

namespace Drupal\curso_module\Services;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
* Servicio de pruebas
*/


class Miscelaneo {

  /**
   * @var MessengerInterface
   */

  private $messenger;

  /**
   * @var EntityTypeManagerInterface
   */

  private $entityTypeManager;

  public function __construct(MessengerInterface $messenger, EntityTypeManagerInterface $entityTypeManager)
  {
    $this->messenger = $messenger;
    $this->entityTypeManager = $entityTypeManager;
    
  }

  public function repetir ($palabra, $cantidad = 3){

    $this->messenger->addMessage('Esto es un mesaje desde el servicio messenger');

    //var_dump($this->entityTypeManager);

    return str_repeat($palabra, $cantidad);

  }

}