<?php
namespace Drupal\alterar_formulario\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Calendario-Eventos' Block
 *
 * @Block(
 *   id = "calen_eventos",
 *   admin_label = @Translation("Calendario de eventos"),
 * )
 */
class CalendarioEventos extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    //$page['#attached']['library'][] = 'alternar_formulario/custom';
    $config = $this->getConfiguration();
    $textos['titulo'] = $config['calendario_settings'];

    /* PARA CUANDO TENGA JSON DE EVENTOS DE LA INTRACEJ   */

    //$data =  file_get_contents('https://servicios.ine.es/wstempus/js/ES/OPERACIONES_DISPONIBLES');
    //$data =  file_get_contents($config['cursos_ruta_settings']);
    //http://localhost/cej-demo/sites/default/files/cursos/cursos.json
    //$cat_facts = json_decode($data, true);

    /*
    foreach ($cat_facts['filas'] as $cat_fact) {
      $textos['fecha'][] = $cat_fact[0];
      $textos['horario'][]= $cat_fact[1];
      $textos['planta'][] = $cat_fact[2];
      $textos['dirigido'][] = $cat_fact[3];
      $textos['curso'][] = $cat_fact[4];
    }
    */

    //var_dump($fecha);
    //$ruta = $config['cursos_ruta_settings'];

    $textos['descrip'] =$config['description_settings'];

    return array(
      '#datos' => $textos,
      '#theme' => 'calendario',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form = parent::blockForm($form, $form_state);

    $default_config = \Drupal::config('calendario.settings');
    $config = $this->getConfiguration();

    $form['calendario_settings'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('Título alternativo'),
      '#description' => $this->t('Título para poner Próximos Eventos'),
      '#default_value' => isset($config['calendario_settings']) ? $config['calendario_settings'] : $default_config->get('Calendario'),
    );
    $form['description_settings'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('Descripción'),
      '#description' => $this->t('Pequeña descripción del calendario'),
      '#default_value' => isset($config['description_settings']) ? $config['description_settings'] : $default_config->get('Descripción del Calendario'),
    );
    $form['ruta_settings'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('Ruta datos json'),
      '#description' => $this->t('Ruta datos json'),
      '#default_value' => isset($config['ruta_settings']) ? $config['ruta_settings'] : $default_config->get(''),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('calendario_settings', $form_state->getValue('calendario_settings'));
    $this->setConfigurationValue('description_settings', $form_state->getValue('description_settings'));
  }


}
