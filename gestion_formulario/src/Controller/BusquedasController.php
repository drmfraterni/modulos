<?php
namespace Drupal\gestion_formulario\Controller;

use Drupal;
use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;

class BusquedasController extends ControllerBase {


  public function busqueda_usuario() {
  // Utilizamos el formulario
          $form = $this->formBuilder()->getForm('Drupal\gestion_formulario\Form\BuscarUsuriosForm');
          //ksm($form);
          //drupal_set_message(t('Formulario: '.$nombre), 'status', FALSE);

  // Le pasamos el formulario y demás a la vista (tema configurado en el module)
          return [
              '#theme' => 'busqueda',
              '#titulo' => $this->t('Formulario para la Búsqueda de Usuarios'),
              '#descripcion' => 'Formulario para la búsqueda de usuarios para añadir',
              '#formulario' => $form
          ];


  }

  public function simulador() {
  // Utilizamos el formulario
          $form = $this->formBuilder()->getForm('Drupal\gestion_formulario\Form\SimuladorErteForm');
          //ksm($form);
          //drupal_set_message(t('Formulario: '.$nombre), 'status', FALSE);

  // Le pasamos el formulario y demás a la vista (tema configurado en el module)
          return [
              '#theme' => 'busqueda',
              '#titulo' => $this->t('Simulador cobro ERTE'),
              '#descripcion' => 'Este formulario sirve para ver cuando cobras en caso de que te manden a un ERTE',
              '#formulario' => $form
          ];


  }

}

?>
