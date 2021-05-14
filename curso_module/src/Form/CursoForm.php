<?php

namespace Drupal\curso_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\curso_module\Services\Miscelaneo;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CursoForm extends FormBase
{

  /**@var control de formulario, por ejemplo:
   * para los pasos de un fomulario.
   */
  private $control;

  /**@var Repetir */
  private $repetir;
  /**@var EntityTypeManagerInterface */
  private $entityTypeManager;

  /** Sirve para inyectar los servicios */
  
  public function __construct(Miscelaneo $repetir, EntityTypeManagerInterface $entityTypeManager)
  {
      $this->repetir = $repetir;
      $this->entityTypeManager = $entityTypeManager;   
  }

  public static function create(ContainerInterface $container) {
      return new static (
          $container->get('curso_module.repetir'),
          $container->get('entity_type.manager')
      );
  }




/**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId()
  {
      return 'curso_module_curso_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   *   https://api.drupal.org/api/drupal/elements
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    /**
     * Para ver todos los posibles campos que podemos utilizar en un formulario.
     * https://api.drupal.org/api/drupal/elements
     * 
     * Para ver dentro de un campo las opciones que tiene:
     * https://api.drupal.org/api/drupal/developer%21topics%21forms_api_reference.html/7.x
     * 
     */

    if (!empty($this->control) && $this->control == true )
    {
        $textoInformativo = " ya has enviado el formulario";

        $form ['final']= [
            '#type' => 'markup',
            '#markup' => $textoInformativo,
            '#format' => 'full_html',
            '#prefix' => '<div class="informativo">',
            '#suffix' => '</div>', 
            
  
        ];
    }
   
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => 'Título',
      '#default_value' => 'valor por defecto',
      '#size' => 60,
      '#maxlength' => 128,
      '#required' => TRUE,
      '#attributes' => [
          'class' => [
              'curso-nuevo',
              'curso-escuela',
            ]
          ],
    ];

    $form['phone'] = [
      '#type' => 'tel',
      '#title' => 'teléfono',
      '#required' => TRUE,
    ];

    $form['checkbox'] = [
        '#type' => 'checkbox',
        '#title' => 'Nuestro Checkbox',
        '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => 'Enviar',
    ];

    


      return $form;
  }

  /**
   * Form validation handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {

    if (strlen($form_state->getValue('phone')) < 4) {
        $form_state->setErrorByName('phone', 'El campo teléfono como mínimo va a terner 4 caracteres');
    }

  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {

    $values = $form_state->getValues();
    
    dpm($values);

    $titulo = $values['title'];
    $telefono = $values['phone'];
    $opcion =  $values['checkbox'];

    $op = ($opcion == 0) ? 'no Acepta' : 'si Acepta';


    $this->messenger()->addStatus('El campo title contenía el texto: '. $titulo);
    $this->messenger()->addStatus('El campo Teléfono contenía el Número: '. $telefono);
    $this->messenger()->addStatus('El campo Checkbox contenía el texto: '. $op);

    $this->control = true;

    $form_state->setRebuild();


  }




}

