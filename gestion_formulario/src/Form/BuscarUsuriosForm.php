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



class BuscarUsuriosForm extends FormBase {

  protected $envioconf = FALSE;



  public function getFormId(){
    // NOMBRE DEL FORMULARIO
    return 'Buscar Usuarios';
  }

  public function buildForm(array $form, FormStateInterface $form_state){


      $form ['nombre'] = array (
        '#type'     => 'textfield',
        '#title'    => $this->t('Nombre'),
        '#required' => FALSE,
      );
      $form ['cd_tarjeta'] = array (
        '#type'     => 'number',
        '#title'    => $this->t('CARNET'),
        '#required' => FALSE,
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


    $control=FALSE;
    $patron_texto = '/^[a-zA-ZáéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙ\s]+$/';
    $patron_carnet='/^[0-9]{9}$/';

    if (empty($form_state->getValue('nombre'))) {
      $control=FALSE;
    }else if (!preg_match($patron_texto, $form_state->getValue('nombre'))){
      $form_state->setErrorByName('nombre', $this->t('No puede contener números'));
    }else{
      $control=TRUE;
    }

    if (empty($form_state->getValue('cd_tarjeta'))&&($control==FALSE)) {
      $control=FALSE;
    }else if (!preg_match($patron_carnet, $form_state->getValue('cd_tarjeta'))&&($control=FALSE)){
      $form_state->setErrorByName('cd_tarjeta', $this->t('Sólo puede contener números'));
    }else{
      $control=TRUE;
    }

    if ($control==FALSE){
      $form_state->setErrorByName('control', $this->t('hay que rellenar alguno de los campos'));
    }


  }



/**
* {@inheritdoc}
*/

  public function submitForm(array &$form, FormStateInterface $form_state) {

    global $base_url;

    //posibles parámetros para la Búsqueda
    $nom=$form_state->getValue('nombre');
    $tarjeta=$form_state->getValue('cd_tarjeta');

    $ruta='/admin/vista-control-usuarios';

    $cond = FALSE;

    if ($nom!=NULL){
      $ruta.='?nombre='.$nom;
      $cond=TRUE;
    }
    //dpm('condicion1: '.$cond);
    if ($tarjeta!=NULL){
      if ($cond==TRUE){
        $ruta.='&tarjeta='.$tarjeta;
      }else{
        $ruta.='?tarjeta='.$tarjeta;

      }
      //dpm('condicion2: '.$cond);
    }


    //dpm('RUTA: '.$ruta);

    $response = new RedirectResponse($base_url."".$ruta);
    $response->send();

    return;

  }

}


?>
