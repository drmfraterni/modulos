<?php
namespace Drupal\alterar_formulario\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\UncacheableDependencyTrait;

/**
 * Provides a 'Agenda' Block
 *
 * @Block(
 *   id = "agenda",
 *   admin_label = @Translation("Agenda"),
 * )
 */
class Agenda extends BlockBase {


  use UncacheableDependencyTrait;

  /**
   * {@inheritdoc}
   */
  public function build() {
    

    $config = $this->getConfiguration();

    //var_dump($config['cursos_eventos_settings']);
    //var_dump($config['cursos_ruta_settings']);

  

   $mensaje = $config['cursos_mensaje_settings'];
   $fechaPrueba = $config['cursos_fechafija_settings'];

   if (empty($mensaje)){
    $mensaje = "NO HAY EVENTOS NI CURSOS";
   }

   

    //\Drupal::logger('CursosEventos')->notice('xxxxxxxxxxxxxxxxxxxx');
    //http://localhost/cej-demo/sites/default/files/cursos/cursos.json

    $textos['titulo'] = $config['cursos_eventos_settings'];

    /* DIAS Y MESES  */
    $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", 
    "Abril", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", 
    "Noviembre", "Diciembre");

    $diaHoy = date("d/m/Y");
    //$diaHoy = "22/01/2020";
    //$diaHoy = "21/01/2020";
    //$diaHoy = "10/08/2020";

    if (!empty($fechaPrueba)){
      $this->messenger()->addStatus($this->t('Estás forzando la fecha de la Agenda. 
        Acuérdate en borrar la FECHA DE PRUEBAS <strong>@fecha</strong> y decha la <strong>FECHA 
        ACTUAL @fechaActual</strong>', ['@fecha' => $fechaPrueba, '@fechaActual' => $diaHoy]));
      $diaHoy = $fechaPrueba;
    }
    $diaTexto = $dias[date("w")];
    $diaMes = $meses[date("n")];
    $diaMesNum = date("m");
    $diaNum = date("d");
    $diaYear = date("Y");


    $textos['factual']['diaHoy'] = $diaHoy;
    $textos['factual']['diaTexto'] = $diaTexto;
    $textos['factual']['diaMes'] = $diaMes;
    $textos['factual']['diaNum'] = $diaNum;

    //var_dump($diaHoy);

    $textos['eventos'] = false;
    $textos['cantidad'] = 0;

    // SERVIDOR $data =  file_get_contents($config['cursos_ruta_settings']  .date('Ymd') .'\\' .date('Ymd'));
    $data =  file_get_contents($config['cursos_ruta_settings']);  // LOCAL 
    $cat_facts = json_decode(utf8_encode($data), true);

    $recodarTit = "";

    foreach ($cat_facts['filas'] as $cat_fact) {



      if ($cat_fact[0] == $diaHoy){

        $tit = self::limpiarTitulo($cat_fact[4]); // limpiamos el título del curso actual
        $rTit = self::limpiarTitulo($recodarTit); // limpiamos el título del curso anterior

        if ($tit !== $rTit) {
          //$prb[] = "distinto";
            $textos['fecha'][] = $cat_fact[0];
            $textos['horario'][]= $cat_fact[1];
            $textos['planta'][] = $cat_fact[2];
            $textos['dirigido'][] = $cat_fact[3];
            $textos['curso'][] = $cat_fact[4];
            $textos['tipo'][] = $cat_fact[5];
            $textos['eventos'] = true; 
            $textos['cantidad']++;
          }

        $recodarTit = $cat_fact[4];

      }  

          

    }

    $textos['circulos'] = ($textos['cantidad']%3);


    if ($textos['eventos'] == false){
        
        $textos['fecha'][] = $diaHoy;
        $textos['nota'] = $mensaje;

    }
    //var_dump($textos['cantidad']);

    if (!empty($config['cursos_eventos_settings'])) {
      $name = $config['cursos_eventos_settings'];
    }
    else {
      $name = $this->t('Esto es una prueba');
    }

    $textos['nuevaRuta'] = self::nuevaRuta()."/ver-toda-agenda/".$diaYear."-".$diaMesNum."-".$diaNum;


    //$ruta = $config['cursos_ruta_settings'];
    return array(
      '#datos' => $textos,
      '#theme' => 'agenda',
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
      '#default_value' => isset($config['cursos_eventos_settings']) ? $config['cursos_eventos_settings'] : $default_config->get('AGENDA'),
    );
    $form['cursos_ruta_settings'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('Ruta del archivo de cursos'),
      '#description' => $this->t('Introduce la ruta del archivo de los cursos (el json)'),
      '#default_value' => isset($config['cursos_ruta_settings']) ? $config['cursos_ruta_settings'] : $default_config->get('http://localhost/cej-demo/sites/default/files/cursos/cursos.json'),
    );

    $form['cursos_mensaje_settings'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('Mensaje'),
      '#description' => $this->t('Personalización del mensaje para cuando no hay EVENTOS.<br> 
      Por defecto muestra el mensaje: <strong>-NO HAY EVENTOS PARA HOY-</strong>'),
      '#default_value' => isset($config['cursos_mensaje_settings']) ? $config['cursos_mensaje_settings'] : $default_config->get('NO HAY EVENTOS PARA HOY'),
    );

    $form['cursos_fechafija_settings'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('Fecha de prueba'),
      '#description' => $this->t('Introduce una fecha para obligar a la agenda que nos la muestre.<br> 
      Esta fecha lo sustituirá por el día de hoy. Después de hacer las pruebas hay que borrarla.<br>
      Fecha con varios eventos: 22/01/2020.<br>
      Fecha con un evento: 11/08/2020 <br>
      Fecha sin eventos: 10/08/2020.
      '),
      '#default_value' => isset($config['cursos_fechafija_settings']) ? $config['cursos_fechafija_settings'] : $default_config->get(''),
    );





    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('cursos_eventos_settings', $form_state->getValue('cursos_eventos_settings'));
    $this->setConfigurationValue('cursos_ruta_settings', $form_state->getValue('cursos_ruta_settings'));
    $this->setConfigurationValue('cursos_mensaje_settings', $form_state->getValue('cursos_mensaje_settings'));
    $this->setConfigurationValue('cursos_fechafija_settings', $form_state->getValue('cursos_fechafija_settings'));
  }

  public function nuevaRuta () {  // 


      if (isset($_SERVER['REQUEST_URI'])){
        $subruta = explode("/", $_SERVER['REQUEST_URI']);
        $subruta = "/".$subruta[1];

      }

      if (isset($_SERVER['HTTPS'])) {
        $pt = "https://";
      }else{
        $pt = "http://";
      }


      $nr = $pt.$_SERVER['HTTP_HOST'].$subruta;

      return $nr;

    }

    public function limpiarTitulo ($tit){

      $limpiar = trim(strtolower($tit));
      $separar = explode(" ", $limpiar);
      $nuevo = implode("", $separar);

      $cadBuscar = array("á", "Á", "é", "É", "í", "Í", "ó", "Ó", "ú", "Ú");
      $cadPoner = array("a", "A", "e", "E", "i", "I", "o", "O", "u", "U");
      $nuevo = str_replace ($cadBuscar, $cadPoner, $nuevo); 

      //var_dump($nuevo);


      return $nuevo;
    }


}
