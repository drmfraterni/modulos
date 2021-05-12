<?php

namespace Drupal\curso_module\Controller;

use Drupal;
use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;
use Drupal\curso_module\Services\Repetir;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\curso_module\Services\Miscelaneo;


class CursoController extends ControllerBase {

    public function home() {

        return [
            '#markup' => 'El markup de nuestro controlador'
        ];

    }
    /**
     * Introducimos parámetros en un controlador
     */

    public function parametros($pagina) {

        $this->messenger()->addMessage($pagina, 'custom');

        return [
            '#markup' => 'La página es: '. $pagina,
        ];

    }

    /**
     * Introducimos un nodos, la etiqueta
    */

    public function nodos(NodeInterface $node) {

        //Instanciamos un servicio
        $prueba = \Drupal::service('curso_module.repetir');
        $resultado = $prueba->repetir('curso ', 5);

        return [
            '#theme' => 'curso_plantilla',
            '#etiqueta' => $node->bundle(),
            '#tipo' => $resultado,
        ];

    }




}

?>

