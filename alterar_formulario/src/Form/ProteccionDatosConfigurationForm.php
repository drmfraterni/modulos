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
class ProteccionDatosConfigurationForm extends ConfigFormBase {

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
    return 'alterar_formulario_proteccion_datos_settings';
  }

  /**
   *
   * {@inheritdoc}
   *
   */
  protected function getEditableConfigNames() {
    return [
      'alterar_formulario.formsettings',
    ];
  }

  /**
   *
   * {@inheritdoc}
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {

    $config = $this->config('alterar_formulario.formsettings');

    $form['texto_responsable'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Datos del Solicitente'),
      '#description' => $this->t('Introduce el texto de la parte de los datos de solicitente'),
      '#default_value' => $config->get('texto_responsable'),
      '#size' => 100,
      '#resizable' => TRUE,

    ];

    $form['texto_solicitud'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Texto de la solicitud'),
      '#description' => $this->t('Introduce el texto de la parte de la solicitud'),
      '#default_value' => $config->get('texto_solicitud'),
      '#rows' => 5,
      '#cols' => 7,
      '#resizable' => TRUE,

    ];

    $form['texto_acceso'] = [
      '#type' => 'textarea',
      '#title' => $this->t('ACCESO'),
      '#description' => $this->t('Introduce el texto de la parte de <strong>ACCESO</strong>'),
      '#default_value' => $config->get('texto_acceso'),
      '#rows' => 5,
      '#cols' => 7,
      '#resizable' => TRUE,

    ];

    $form['texto_rectificacion'] = [
      '#type' => 'textarea',
      '#title' => $this->t('RECTIFICACIÓN'),
      '#description' => $this->t('Introduce el texto de la parte de <strong>RECTIFICACIÓN</strong>'),
      '#default_value' => $config->get('texto_rectificacion'),
      '#rows' => 5,
      '#cols' => 7,
      '#resizable' => TRUE,

    ];

    $form['texto_supresion'] = [
      '#type' => 'textarea',
      '#title' => $this->t('SUPRESIÓN'),
      '#description' => $this->t('Introduce el texto de la parte de <strong>SUPRESIÓN</strong>'),
      '#default_value' => $config->get('texto_supresion'),
      '#rows' => 5,
      '#cols' => 7,
      '#resizable' => TRUE,

    ];

    $form['texto_oposicion'] = [
      '#type' => 'textarea',
      '#title' => $this->t('OPOSICIÓN'),
      '#description' => $this->t('Introduce el texto de la parte de <strong>OPOSICIÓN</strong>'),
      '#default_value' => $config->get('texto_oposicion'),
      '#rows' => 5,
      '#cols' => 7,
      '#resizable' => TRUE,

    ];

    $form['texto_limitacion'] = [
      '#type' => 'textarea',
      '#title' => $this->t('LIMITACIÓN DE TRATAMIENTO'),
      '#description' => $this->t('Introduce el texto de la parte de <strong>LIMITACIÓN DE TRATAMIENTO</strong>'),
      '#default_value' => $config->get('texto_limitacion'),
      '#rows' => 5,
      '#cols' => 7,
      '#resizable' => TRUE,

    ];

    $form['texto_portabilidad'] = [
      '#type' => 'textarea',
      '#title' => $this->t('PORTABILIDAD DE DATOS'),
      '#description' => $this->t('Introduce el texto de <strong>PORTABILIDAD DE DATOS</strong>'),
      '#default_value' => $config->get('texto_portabilidad'),
      '#rows' => 5,
      '#cols' => 7,
      '#resizable' => TRUE,

    ];

    $form['correo_cej'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CORREO A ENVIAR EL FORMULARIO'),
      '#description' => $this->t('Introduce los correos a enviar separado por ";" '),
      '#default_value' => $config->get('correo_cej'),
      '#resizable' => TRUE,

    ];
 

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    parent::submitForm($form, $form_state);
    //$values = $form_state->getValues();

    $this->config('alterar_formulario.formsettings')->set('texto_responsable', $form_state->getValue('texto_responsable'))->save();
    $this->config('alterar_formulario.formsettings')->set('texto_solicitud', $form_state->getValue('texto_solicitud'))->save();
    $this->config('alterar_formulario.formsettings')->set('texto_acceso', $form_state->getValue('texto_acceso'))->save();
    $this->config('alterar_formulario.formsettings')->set('texto_rectificacion', $form_state->getValue('texto_rectificacion'))->save();
    $this->config('alterar_formulario.formsettings')->set('texto_supresion', $form_state->getValue('texto_supresion'))->save();
    $this->config('alterar_formulario.formsettings')->set('texto_oposicion', $form_state->getValue('texto_oposicion'))->save();
    $this->config('alterar_formulario.formsettings')->set('texto_limitacion', $form_state->getValue('texto_limitacion'))->save();
    $this->config('alterar_formulario.formsettings')->set('texto_portabilidad', $form_state->getValue('texto_portabilidad'))->save();
    $this->config('alterar_formulario.formsettings')->set('correo_cej', $form_state->getValue('correo_cej'))->save();

    

    }


  }
