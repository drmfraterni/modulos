<?php
namespace Drupal\alterar_formulario\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Cursos-eventos' Block
 *
 * @Block(
 *   id = "cursos_eventos",
 *   admin_label = @Translation("Cursos Eventos"),
 * )
 */
class CursosEventos extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    //var_dump($config['cursos_eventos_settings']);
    //var_dump($config['cursos_ruta_settings']);

   $data =  file_get_contents($config['cursos_ruta_settings']);



	  //\Drupal::logger('CursosEventos')->notice('xxxxxxxxxxxxxxxxxxxx');
    //http://localhost/cej-demo/sites/default/files/cursos/cursos.json
    $cat_facts = json_decode($data, true);

    $textos['titulo'] = $config['cursos_eventos_settings'];


    $diaHoy = date("d/m/Y");
    //var_dump($diaHoy);
    $diaHoy = "10/01/2020";

    //$textos['diaHoy'] = $diaHoy;


    foreach ($cat_facts['filas'] as $cat_fact) {

        $textos['fecha'][] = $cat_fact[0];
        $textos['horario'][]= $cat_fact[1];
        $textos['planta'][] = $cat_fact[2];
        $textos['dirigido'][] = $cat_fact[3];
        $textos['curso'][] = $cat_fact[4];   

    }

    if (!empty($config['cursos_eventos_settings'])) {
      $name = $config['cursos_eventos_settings'];
    }
    else {
      $name = $this->t('Esto es una prueba');
    }

    $ruta = $config['cursos_ruta_settings'];
    return array(
      '#datos' => $textos,
      '#theme' => 'cursos_eventos',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form = parent::blockForm($form, $form_state);

    $default_config = \Drupal::config('hello_world.settings');
    $config = $this->getConfiguration();

    $form['cursos_eventos_settings'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('Título alternativo'),
      '#description' => $this->t('Título para poner Próximos Eventos'),
      '#default_value' => isset($config['cursos_eventos_settings']) ? $config['cursos_eventos_settings'] : $default_config->get('Próximos eventos'),
    );
    $form['cursos_ruta_settings'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('Ruta del archivo de cursos'),
      '#description' => $this->t('Introduce la ruta del archivo de los cursos (el json)'),
      '#default_value' => isset($config['cursos_ruta_settings']) ? $config['cursos_ruta_settings'] : $default_config->get('http://localhost/cej-demo/sites/default/files/cursos/cursos.json'),
    );

    /*$form['actions']['boton_submit'] = array (
      '#type' => 'submit',
      '#title' => 'boton_submit',
      '#value' => $this->t('Boton submit'),
      '#submit' => [[ $this, 'boton_submit_form']],
    );*/

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('cursos_eventos_settings', $form_state->getValue('cursos_eventos_settings'));
    $this->setConfigurationValue('cursos_ruta_settings', $form_state->getValue('cursos_ruta_settings'));
  }

  /*public function boton_submit_form($form, FormStateInterface $form_state){
    $values = $form_state->getValues();
  }*/
}
