<?php

namespace Drupal\gestion_formulario\Form;

use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\taxonomy\Entity\Term;



class SimuladorErteForm extends FormBase {

  protected $envioconf = FALSE;



  public function getFormId(){
    // NOMBRE DEL FORMULARIO
    return 'simulador_erte';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

      $textoHtml = "Horas de reducción.<br> Si el ERTE es toda la jornada sería 8 horas ";


      $form ['salario'] = array (
        '#type'     => 'number',
        '#title'    => $this->t('Salario Bruto Anual'),
        '#description' => $this->t('Introduce el <strong>Sueldo Bruto Anual</strong> '),
        '#required' => TRUE,
      );
      $form ['reduccion'] = array (
        '#type'     => 'number',
        '#title'    => $this->t('Horas de Reducción'),
        '#description' => $this->t($textoHtml),
        '#default_value'  => 8,
        '#required' => TRUE,
      );
      $form ['hijos'] = array (
        '#type'     => 'number',
        '#title'    => $this->t('Número de hijos'),
        '#default_value'  => 0,
        '#description' => $this->t('Introduce el número de hijos '),
        '#required' => TRUE,
      );
      $form ['submit'] = [
          '#type'  => 'submit',
          '#value' => $this->t('Enviar'),
      ];

      return $form;


  }

  /**
 * {@inheritdoc}
 */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Uno de los campos tiene que estar relleno
    // el campo nombre no puede tener caracteres numéricos
    // el campo cd_tarjeta no puede tener carácteres alfabéticos.

    $control = FALSE;

    if (is_numeric($form_state->getValue('salario'))){
      $control = TRUE;
    }

    if (is_numeric($form_state->getValue('reduccion')) && $control == TRUE){
      $control = TRUE;
    }

    if (is_numeric($form_state->getValue('hijos')) && $control == TRUE){
      $control = TRUE;
    }


    if ($control==FALSE){
      $form_state->setErrorByName('control', $this->t('Alguno de los campos no es un NÚMERO'));
    }


  }



/**
* {@inheritdoc}
*/

public function submitForm(array &$form, FormStateInterface $form_state) {

      $mensajes = array();
      //posibles parámetros para la Búsqueda
      $salarioBruto = $form_state->getValue('salario');
      $horas = $form_state->getValue('reduccion');
      $hijos = $form_state->getValue('hijos');


      $form_state->setRedirect('gestion_formulario.simulador_resultado',
    ['salarioBruto' => $salarioBruto, 'horas' => $horas, 'hijos' => $hijos ]);
      //return;

  }



}



?>
