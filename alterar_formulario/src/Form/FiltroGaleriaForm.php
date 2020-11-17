<?php

namespace Drupal\alterar_formulario\Form;

use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
// use Drupal\Core\Mail\MailManagerInterface;
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
use Drupal\alterar_formulario\Services\Miscelaneo;




class FiltroGaleriaForm extends FormBase {

  protected $envioconf = FALSE;



  public function getFormId(){
    // NOMBRE DEL FORMULARIO
    return 'filtro_galeria_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

    // instanciamos el servicio Miscelaneo para sacar los términos de la taxonomia

    $nuevoElem = new Miscelaneo ();
    $terminoAnnio = $nuevoElem-> taxTerminos('galeria_ano');
    $terminoMes = $nuevoElem-> taxTerminos('galeria_mes');




    //$this->envioconf = TRUE;

    if (!empty($this->envioconf)){
      drupal_set_message('EL FORMULARIO SE HA ENVIDO');
    }else{

      $form ['titulo'] = array (
        '#type'     => 'textfield',
        '#title'    => $this->t('<strong>Título</strong>'),
        '#required' => FALSE,
      );
      

      $form['annio'] = array (
        '#type' => 'select',
        '#target_type' => 'taxonomy_term',
        '#title' => $this->t('<strong>Año</strong>'),
        '#description' => $this->t('Búsqueda por año'),
        '#options' => $terminoAnnio,        
      );

      $form['mes'] = array (
        '#type' => 'select',
        '#target_type' => 'taxonomy_term',
        '#title' => $this->t('<strong>Mes</strong>'),
        '#description' => $this->t('Búsqueda por mes.'),
        '#options' => $terminoMes,        
      );

      $form ['submit'] = [
          '#type'  => 'submit',
          '#value' => $this->t('Buscar'),
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

    // VALORES DEL FORMULARIO

    $titulo = $form_state->getValue('titulo');
    $eleAnnio = $form_state->getValue('annio');
    $eleMes = $form_state->getValue('mes');
    //$annio = $eleAnnio[0]['target_id'];
    //$mes = $eleMes[0]['target_id'];

    //var_dump($eleAnnio);


    

    // RECOGERMOS LA FUNCIÓN DE RUTA ABSOLUTA DEL SERVICIO MISCELANEO
    /*
    $nuevoElem = new Miscelaneo ();
    $base_url = $nuevoElem->urlCompleta();

    // COMPLETAMOS LA RUTA FINAL A DONDE MANDAMOS EL FORMULARIO

    $titulo = empty($titulo) ? 'All' : $titulo;
    $annio = empty($annio) ? 'All' : $annio;
    $mes = empty($mes) ? 'All' : $mes;

    $ruta=$base_url.'/imagenes?title='.$titulo.'&field_mes_target_id='.$mes.'&field_a_target_id='.$annio;

    $response = new RedirectResponse($ruta);
    
    $response->send();
    */

    /* PRUEBA   */

    $url = Url::fromRoute('alterar_formulario.galeria-imagenes')
            ->setRouteParameters(array('titulo' => $titulo, 'mes' => $eleMes, 'annio' => $eleAnnio));


    $form_state->setRedirectUrl($url);

    
    return;

  }

}


?>
