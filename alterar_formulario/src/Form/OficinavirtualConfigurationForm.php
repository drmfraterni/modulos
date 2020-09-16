<?php

/**
 * @file
 * Contains \Drupal\rdls_subscriptions\Form\MuprespaNodeUnistepConfigurationForm.
 */
namespace Drupal\alterar_formulario\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Path\AliasManagerInterface;
use Drupal\Core\Path\PathValidatorInterface;
use Drupal\Core\Routing\RequestContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Defines a form that configures form module settings.
 */
class OficinavirtualConfigurationForm extends ConfigFormBase {

 /**
  * The path alias manager.
  *
  * @var \Drupal\Core\Path\AliasManagerInterface
  */
  protected $aliasManager;

 /**
  * The path validator.
  *
  * @var \Drupal\Core\Path\PathValidatorInterface
  */
  protected $pathValidator;

 /**
  * The request context.
  *
  * @var \Drupal\Core\Routing\RequestContext
  */
 protected $requestContext;

 /**
  * Constructs a MuprespaDuesConfigurationForm object.
  *
  * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
  *         The factory for configuration objects.
  * @param \Drupal\Core\Path\AliasManagerInterface $alias_manager
  *         The path alias manager.
  * @param \Drupal\Core\Path\PathValidatorInterface $path_validator
  *         The path validator.
  * @param \Drupal\Core\Routing\RequestContext $request_context
  *         The request context.
  */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    AliasManagerInterface $alias_manager,
    PathValidatorInterface $path_validator,
    RequestContext $request_context) {
    parent::__construct ( $config_factory );

    $this->aliasManager = $alias_manager;
    $this->pathValidator = $path_validator;
    $this->requestContext = $request_context;
 }

 /**
  *
  * {@inheritdoc}
  *
  */
  public static function create(ContainerInterface $container) {
    return new static (
      $container->get('config.factory'),
      $container->get('path.alias_manager'),
      $container->get('path.validator'),
      $container->get('router.request_context')
    );
  }

  /**
   *
   * {@inheritdoc}
   *
   */
   public function getFormId() {
    return 'alterar_formulario_admin_settings';
  }

  /**
   *
   * {@inheritdoc}
   *
   */
  protected function getEditableConfigNames() {
    return [
      'alterar_formulario.settings',
    ];
  }

  /**
   *
   * {@inheritdoc}
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {

    $config = $this->config('alterar_formulario.settings');
    $prueba = $config->get('direccion');


    $form['direccion'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Dirección de la Oficina Virtual'),
      '#description' => $this->t('Introduce la ruta de la oficina virtual ej.: https://cej.alten.es/oficina-virtual'),
      '#default_value' => $config->get('direccion'),

    ];
    $form['ambito'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Dirección del servidor'),
      '#description' => $this->t('Introduce la ruta de la oficina virtual ej.: http://localhost/portal'),
      '#default_value' => $config->get('ambito'),

    ];

    $form['agenda'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Dirección del json de AGENDA'),
      '#description' => $this->t('Introduce la ruta del json para la AGENDA: http://localhost/portal'),
      '#default_value' => $config->get('agenda'),

    ];


    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $values = $form_state->getValues();
    $this->config('alterar_formulario.settings')->set('direccion', $form_state->getValue('direccion'))->save();
    $this->config('alterar_formulario.settings')->set('ambito', $form_state->getValue('ambito'))->save();
    $this->config('alterar_formulario.settings')->set('agenda', $form_state->getValue('agenda'))->save();

    parent::submitForm($form, $form_state);

    }


  }
