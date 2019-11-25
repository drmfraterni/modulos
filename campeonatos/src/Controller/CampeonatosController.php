<?php
namespace Drupal\campeonatos\Controller;

use Drupal;
use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;

class CampeonatosController extends ControllerBase {


  public function alta_participante() {
  // Utilizamos el formulario
          $form = $this->formBuilder()->getForm('Drupal\campeonatos\Form\AltaParticipanteForm');
          //ksm($form);
          //drupal_set_message(t('Formulario: '.$nombre), 'status', FALSE);

  // Le pasamos el formulario y demás a la vista (tema configurado en el module)
          return [
              '#theme' => 'participante',
              '#titulo' => $this->t('Formulario alta de participantes'),
              '#descripcion' => 'Formulario para el alta de participantes',
              '#formulario' => $form
          ];


  }

  public function puntos_campeonato($clave) {
  // Utilizamos el formulario
          $form = $this->formBuilder()->getForm('Drupal\campeonatos\Form\PuntosCampeonatoForm', $clave);
          //ksm($form);
          //drupal_set_message(t('Formulario: '.$nombre), 'status', FALSE);

  // Le pasamos el formulario y demás a la vista (tema configurado en el module)
          return [
              '#theme' => 'participante',
              '#titulo' => $this->t('BLOQUES REALIZADOS'),
              '#descripcion' => 'Formulario de puntos de la Liga interna',
              '#formulario' => $form
          ];


  }

}

?>
