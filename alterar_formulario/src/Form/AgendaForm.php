<?php

namespace Drupal\alterar_formulario\Form;

use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;




class AgendaForm extends FormBase {

  protected $envioconf = FALSE;



  public function getFormId(){
    // NOMBRE DEL FORMULARIO
    return 'formulario_agenda';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

    //$this->envioconf = TRUE;

    if (!empty($this->envioconf)){
      drupal_set_message('EL FORMULARIO SE HA ENVIDO');
    }else{


      $form['fecha_inicio'] = array(
        '#type' => 'date',
        '#title' => 'Fecha de Inicio',
        '#required' => TRUE,
        '#default_value' => array('month' => 9, 'day' => 6, 'year' => 1962),
        '#format' => 'm/d/Y',
        '#description' => t('i.e. 20/09/2020'),
        //'#prefix' =>'<div class="col-12 col-md-6">',
        //'#suffix' => '</div>',
      );

      $form['fecha_fin'] = array(
        '#type' => 'date',
        '#title' => 'Fecha de Fin',
        '#required' => TRUE,
        '#default_value' => array('month' => 9, 'day' => 6, 'year' => 1962),
        '#format' => 'm/d/Y',
        '#description' => t('i.e. 30/09/2020'),
        //'#prefix' =>'<div class="col-12 col-md-6">',
        //'#suffix' => '</div>',
      );

      $form ['continuar'] = [
        '#type'  => 'submit',
        '#value' => $this->t('Enviar'),
        //'#prefix' =>'<div class="col-12">',
        //'#suffix' => '</div>',
        '#attribuates' => array(
          'parametro1' => $form_state->getValue('fecha_inicio'),
          'parametro2' => $form_state->getValue('fecha_fin'),

        ),
      ];

      return $form;

    }

  }

  /**
 * {@inheritdoc}
 */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    

  }



/**
* {@inheritdoc}
*/


  public function submitForm(array &$form, FormStateInterface $form_state) {
    //drupal_set_message($this->t('Su número telefónico es: @number', array('@number' => $form_state->getValue('phone_number'))));

    global $base_url;

    //posibles parámetros para la Búsqueda
    $fechaInicio = $form_state->getValue('fecha_inicio');
    $fechaFin = $form_state->getValue('fecha_fin');

    drupal_set_message($this->t('La Fecha de inicio es : @fechadeinicio y la fecha de fin es: @fechadefin', array('@fechadeinicio' => $form_state->getValue('fecha_inicio'), '@fechadefin' => $form_state->getValue('fecha_fin'))));

    $ruta="/ver-toda-agenda/$fechaInicio/$fechaFin";

    $response = new RedirectResponse($base_url."".$ruta);
    $response->send();
    
    return;

  }

}


?>
