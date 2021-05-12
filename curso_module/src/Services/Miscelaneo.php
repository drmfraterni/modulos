<?php

namespace Drupal\curso_module\Services;

/**
* Servicio de pruebas
*/


class Miscelaneo {

  public function repetir ($palabra, $cantidad = 3){

    return str_repeat($palabra, $cantidad);

  }

}