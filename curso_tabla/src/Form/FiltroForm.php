<?php

namespace Drupal\curso_tabla\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class FiltroForm extends FormBase {

  /**
   * @var EntityTypeManagerInterface
   */
  private $entityTypeManager;
  /**
   * @var SessionInterface
   */
  private $session;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, SessionInterface $session) {
    $this->entityTypeManager = $entityTypeManager;
    $this->session = $session;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('session')
    );
  }

  public function getFormId() {
    return 'curso_tabla_filter';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $filters = $this->session->get('curso_tabla_filters', []);

    $type_options = [
      'none' => '- Ninguno -'
    ];

    /** @var NodeTypeInterface[] $node_types */
    $node_types = $this->entityTypeManager->getStorage('node_type')->loadMultiple();

    foreach ($node_types as $key => $node_type) {
      $type_options[$key] = $node_type->label();
    }

    $form['titulo'] = [
      '#type' => 'textfield',
      '#title' => 'Titulo',
      '#default_value' => isset($filters['titulo']) ? $filters['titulo'] : '',
    ];

    $form['tipo'] = [
      '#type' => 'select',
      '#title' => 'Tipo',
      '#options' => $type_options,
      '#default_value' => isset($filters['tipo']) ? $filters['tipo'] : 'none',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => 'Filtrar'
    ];

    $form['actions']['reset'] = [
      '#type' => 'submit',
      '#value' => 'Reset',
      '#submit' => ['::resetSubmit',]
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $filtro = [];

    $filtro['titulo'] = $form_state->getValue('titulo');
    $filtro['tipo'] = $form_state->getValue('tipo');

    // Sesión del usuario

    $this->session->set('curso_tabla_filters', $filtro);
  }

  public function resetSubmit(array &$form, FormStateInterface $form_state) {
    $this->session->set('curso_tabla_filters', []);
  }
}
