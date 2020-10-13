<?php
namespace Drupal\alterar_formulario\Controller;

use Drupal;
use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\Response;

class ProteccionController extends ControllerBase {


 public function form_proteccion () {

  //$form = $this->formBuilder()->getForm('Drupal\alterar_formulario\Form\ProteccionForm');

  $form = \Drupal::formBuilder()->getForm('Drupal\alterar_formulario\Form\ProteccionForm');


  return [
    '#theme' => 'proteccion',
    '#titulo' => $this->t('Formulario de Protección de Datos'),
    '#descripcion' => NULL,
    '#formulario' => $form,
    ];


 }


	  

}

?>
