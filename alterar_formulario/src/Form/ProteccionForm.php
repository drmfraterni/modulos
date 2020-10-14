<?php

namespace Drupal\alterar_formulario\Form;

use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
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




class ProteccionForm extends FormBase {

  protected $envioconf = FALSE;

  protected $textoHtmlConsent;

  protected $textoHtmlAcceso;

  protected $correoCej;

  protected $valores;

  // protected $datos = [];



  public function getFormId(){
    // NOMBRE DEL FORMULARIO
    return 'proteccion_datos_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

    // Los datos los traemos de la aplicacion y formulario:
    // configuracion > servicio web > proteccion de datos 

    $textHtml = $config = \Drupal::config('alterar_formulario.formsettings');
    $textoResponsable = $textHtml->get('texto_responsable');
    $textoSolicitud = $textHtml->get('texto_solicitud');
    $textoAcceso = $textHtml->get('texto_acceso');
    $textoRectificacion = $textHtml->get('texto_rectificacion');
    $textoSupresion = $textHtml->get('texto_supresion');
    $textOposicion = $textHtml->get('texto_oposicion');
    $textoLimitacion = $textHtml->get('texto_limitacion');
    $textoPortabilidad = $textHtml->get('texto_portabilidad');
    $this->correoCej = $textHtml->get('correo_cej');


    //var_dump($textHtml->get('texto_solicitud'));

    //$this->envioconf = TRUE;

    if (!empty($this->envioconf)){

      $textoConsent ="<p><h3>FORMULARIO DE PROTECCIÓN DE DATOS</h3></p>";

      $textoConsent .= "<p>Estimado/a ".$this->valores['nombre']." ".$this->valores['apellido1']." ".$this->valores['apellido2']."</p>";
      $textoConsent .= "El mensaje se ha enviado de forma correcta.<br/>";
      $textoConsent .= "En breve nos pondremos en contacto con usted.<br/>";

      if ($this->valores['acceso']){
        $textoConsent .= "Usted ha ejercido el derecho a ". $this->valores['acceso']."<br/>";
      }
      if ($this->valores['rectificacion']){
        $textoConsent .= "Usted ha ejercido el derecho a ". $this->valores['rectificacion']."<br/>";
      }
      if ($this->valores['supresion']){
        $textoConsent .= "Usted ha ejercido el derecho a ". $this->valores['supresion']."<br/>";
      }
      if ($this->valores['oposicion']){
        $textoConsent .= "Usted ha ejercido el derecho a ". $this->valores['oposicion']."<br/>";
      }
      if ($this->valores['limitacion']){
        $textoConsent .= "Usted ha ejercido el derecho a ". $this->valores['limitacion']."<br/>";
      }
      if ($this->valores['portabilidad']){
        $textoConsent .= "Usted ha ejercido el derecho a ". $this->valores['portabilidad']."<br/>";
      }

      $textoConsent .= "<p>Y nos ha envido el siguiente mensaje: </p> ";
      $textoConsent .= "<p>".$this->valores[mensaje]."</p>";

      $form ['final']= array (
          '#type' => 'markup',
          '#markup' => $textoConsent,
          '#format' => 'full_html',

      );

      //var_dump($this->valores);

      //drupal_set_message('EL FORMULARIO DE PROTECCIÓN DE DATOS SE HA ENVIDO');



    }else{


      /**  DATOS DE SOLICITANTE **/

      $form ['datos_solicitante']= array (
          '#type'     => 'fieldset',
          '#title'    => $this->t('Datos del Solicitante (Persona Física)'),
      );

      $form ['datos_solicitante']['dni'] = array (
        '#type'     => 'textfield',
        '#title'    => $this->t('D.N.I'),
        '#required' => FALSE,

      );

      $form ['datos_solicitante']['nombre'] = array (
        '#type'     => 'textfield',
        '#title'    => $this->t('Nombre'),
        '#required' => TRUE,
      );

      $form ['datos_solicitante']['apellido1'] = array (
        '#type'     => 'textfield',
        '#title'    => $this->t('Apellido 1'),
        '#required' => TRUE,
      );

      $form ['datos_solicitante']['apellido2'] = array (
        '#type'     => 'textfield',
        '#title'    => $this->t('Apellido 2'),
        '#required' => FALSE,
      );

      $form ['datos_solicitante']['domicilio'] = array (
        '#type'     => 'textfield',
        '#title'    => $this->t('Domicilio'),
        '#required' => TRUE,
      );

      $form ['datos_solicitante']['localidad'] = array (
        '#type'     => 'textfield',
        '#title'    => $this->t('Localidad'),
        '#required' => TRUE,
      );

      $form ['datos_solicitante']['provincia'] = array (
        '#type'     => 'textfield',
        '#title'    => $this->t('Provincia'),
        '#required' => TRUE,
      );

      $form ['datos_solicitante']['cp'] = array (
        '#type'     => 'textfield',
        '#title'    => $this->t('Código Postal'),
        '#required' => TRUE,
      );

      $form ['datos_solicitante']['movil'] = array (
        '#type'     => 'tel',
        '#title'    => $this->t('Teléfono móvil'),
        '#required' => TRUE,
      );

      $form ['datos_solicitante']['fijo'] = array (
        '#type'     => 'tel',
        '#title'    => $this->t('Teléfono Fijo'),
        '#required' => FALSE,
      );

      $form ['datos_solicitante']['email'] = array (
        '#type'     => 'email',
        '#title'    => $this->t('Correo electrónico'),
        '#required' => FALSE,
      );

      /** DATOS DEL RESPONSABLE **/

      $form ['datos_responsable']= array (
          '#type'     => 'fieldset',
          '#title'    => $this->t('RESPONSABLE DEL TRATAMIENTO AL QUE DIRIGE LA SOLICITUD'),
      );
      $form ['datos_responsable']['texto_responsable']= array (
          '#type' => 'markup',
          '#markup' => $this->t($textoResponsable),
          '#format' => 'full_html',

      );


      /** DATOS DE LA SOLICITUD  **/

      $form ['datos_solicitud']= array (
          '#type'     => 'fieldset',
          '#title'    => $this->t('Datos de la solicitud'),
      );

      $form ['datos_solicitud']['texto_solicitud']= array (
          '#type' => 'markup',
          '#markup' => $this->t($textoSolicitud),
          '#format' => 'full_html',

      );

      $form['datos_solicitud']['acceso'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('ACCESO'),
      //'#description' => $this->t('Please read and accept the terms of use'),
      '#required' => FALSE,
      ];

      $form ['datos_solicitud']['texto_acceso']= array (
          '#type' => 'markup',
          '#markup' => $this->t($textoAcceso),
          '#format' => 'full_html',

      );


      $form['datos_solicitud']['rectificacion'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('RECTIFICACIÓN'),
      // '#description' => $this->t('Please read and accept the terms of use'),
      '#required' => FALSE,
      ];

      $form ['datos_solicitud']['texto_rectificacion']= array (
          '#type' => 'markup',
          '#markup' => $this->t($textoRectificacion),
          '#format' => 'full_html',

      );

      $form['datos_solicitud']['supresion'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('SUPRESIÓN'),
      // '#description' => $this->t('Please read and accept the terms of use'),
      '#required' => FALSE,
      ];

      $form ['datos_solicitud']['texto_supresion']= array (
          '#type' => 'markup',
          '#markup' => $this->t($textoSupresion),
          '#format' => 'full_html',

      );

      $form['datos_solicitud']['oposicion'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('OPOSICIÓN'),
      // '#description' => $this->t('Please read and accept the terms of use'),
      '#required' => FALSE,
      ];

      $form ['datos_solicitud']['texto_oposicion']= array (
          '#type' => 'markup',
          '#markup' => $this->t($textOposicion),
          '#format' => 'full_html',

      );

      $form['datos_solicitud']['limitacion'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('LIMITACIÓN DE TRATAMIENTO'),
      // '#description' => $this->t('Please read and accept the terms of use'),
      '#required' => FALSE,
      ];

      $form ['datos_solicitud']['texto_limitacion']= array (
          '#type' => 'markup',
          '#markup' => $this->t($textoLimitacion),
          '#format' => 'full_html',

      );

      $form['datos_solicitud']['portabilidad'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('PORTABILIDAD DE LOS DATOS'),
      // '#description' => $this->t('Please read and accept the terms of use'),
      '#required' => FALSE,
      ];

      $form ['datos_solicitud']['texto_portabilidad']= array (
          '#type' => 'markup',
          '#markup' => $this->t($textoPortabilidad),
          '#format' => 'full_html',

      );

      //************* TEXTAREA PARA INTRODUCIR MENSAJE   *******//

      $form ['datos_mensaje']= array (
          '#type'     => 'fieldset',
          '#title'    => $this->t('MENSAJE'),
      );

      $form ['datos_mensaje']['mensaje'] = array (
        '#type'     => 'textarea',
        '#title'    => $this->t('Texto de la Solicitud'),
        '#required' => FALSE,
      );
      
      /*$form['datos_mensaje']['documentacion'] = array (
          '#type' => 'managed_file',
          '#title' => $this->t('DOCUMENTACIÓN ADJUNTA'),
          '#upload_validators' => array (
            'file_validate_extensions' => array('gif png jpg jpeg pdf doc docx txt'),
            'file_validate_size' => array(25600000),
          ),
          '#upload_location' => 'public://proteccion_datos/',
          '#required' => FALSE,

      );*/


      // ACTION 

      $form['actions']['#type'] = 'actions';
      $form['actions']['submit'] = [
          '#type'  => 'submit',
          '#value' => $this->t('Enviar'),
          '#button_type' => 'primary',
      ];

      $form['#theme'] = 'proteccion';


      

    }

    return $form;

  }

  /**
 * {@inheritdoc}
 */
  public function validateForm(array &$form, FormStateInterface $form_state) {


    $patron_texto = '/^[a-zA-ZáéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙ\s]+$/';
    $patron_tel='/^[0-9]{9}$/';
    $val_dni = TRUE;

    if (!empty($form_state->getValue('dni'))){
      $val_dni = self::validar_dni($form_state->getValue('dni'));
    }

    // Hacemos las validaciones necesarias
    // Validación del MONBRE
    if (empty($form_state->getValue('nombre'))) {
        $form_state->setErrorByName('nombre', $this->t('Es necesario introducir un nombre'));
    }else if (!preg_match($patron_texto, $form_state->getValue('nombre'))){
        //print_r(preg_match($patron_texto, $form_state->getValue('nombre')));
        $form_state->setErrorByName('nombre', $this->t('No puede contener números'));
    }

    if (empty($form_state->getValue('apellido1'))) {
        $form_state->setErrorByName('apellido1', $this->t('Es necesario introducir un apellido1'));
    }else if (!preg_match($patron_texto, $form_state->getValue('apellido1'))){
        //print_r(preg_match($patron_texto, $form_state->getValue('nombre')));
        $form_state->setErrorByName('apellido1', $this->t('No puede contener números'));
    }

    /*if (!preg_match($patron_telf, $form_state->getValue('movil'))){
        //print_r(preg_match($patron_texto, $form_state->getValue('movil')));
        $form_state->setErrorByName('movil', $this->t('No puede contener letras'));
    }*/

    if (empty($form_state->getValue('movil'))) {
        $form_state->setErrorByName('movil', $this->t('Es necesario introducir un nombre'));
    }else if (!preg_match($patron_tel, $form_state->getValue('movil'))){
        //print_r(preg_match($patron_texto, $form_state->getValue('nombre')));
        $form_state->setErrorByName('movil', $this->t('No puede contener números'));
    }

    if (!valid_email_address($form_state->getValue('email'))){

      $form_state->setErrorByName('email', $this->t('Correo erróneo'));


    }

    if ($val_dni == FALSE){

      $form_state->setErrorByName('dni', $this->t('dni incorrecto'));
    }





  }



/**
* {@inheritdoc}
*/

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->envioconf = TRUE;

    $datos['nombre'] = strip_tags($form_state->getValue('nombre'));    
    $datos['apellido1'] = strip_tags($form_state->getValue('apellido1'));
    $datos['apellido2'] = strip_tags($form_state->getValue('apellido2'));
    $datos['domicilio'] = strip_tags($form_state->getValue('domicilio'));
    $datos['localidad'] = strip_tags($form_state->getValue('localidad'));

    $datos['provincia'] = strip_tags($form_state->getValue('provincia'));
    $datos['cp'] = strip_tags($form_state->getValue('cp'));
    $datos['movil'] = strip_tags($form_state->getValue('movil'));
    $datos['fijo'] = strip_tags($form_state->getValue('fijo'));

    $datos['email'] = $form_state->getValue('email');


    //$datos['acceso'] = $form_state->getValue('acceso');
    $datos['acceso'] = self::indicarDerechos('ACCESO',$form_state->getValue('acceso'));
    $datos['rectificacion'] = self::indicarDerechos('RECTIFICACIÓN',$form_state->getValue('rectificacion'));
    $datos['supresion'] = self::indicarDerechos('SUPRESIÓN',$form_state->getValue('supresion'));
    $datos['oposicion'] = self::indicarDerechos('OPOSICIÓN',$form_state->getValue('oposicion'));
    $datos['limitacion'] = self::indicarDerechos('LIMITACIÓN DE TRATAMIENTO',$form_state->getValue('limitacion'));
    $datos['portabilidad'] = self::indicarDerechos('PORTABILIDAD DE DATOS',$form_state->getValue('portabilidad'));

    $datos['mensaje'] = strip_tags($form_state->getValue('mensaje'));

    $nombreCompleto = $datos['nombre'].' '.$datos['apellido1'].' '.$datos['apellido2'];

    /*$datos['documentacion'] = $form_state->getValue('documentacion', 0);

    if (isset($datos['documentacion'][0]) && !empty($datos['documentacion'][0])) {
      $doc = File::load($datos['documentacion'][0]);
      $doc->setPermanent;
      $doc->save();
    }

    $datos['nombredoc'] = $doc->getFileUri();

    */



    //*************ENVIO DE CORREO************************//

    
    $module = 'alterar_formulario';
    $key = 'email_proteccion_datos';
    $to = $this->correoCej; // viene de las variables del formulario de protección de datos
    $params['subject'] = 'FORMULARIO DE SOLICITUD DE EJERCICIO DE DERECHOS DE '.$nombreCompleto;
    $params['titulo']='Protección de dato en el Centro de Estudios Jurídicos';
    $params['message'] =  $datos['mensaje'];

    //$params['attachments'] = $file.$nombrePDF;

    $this->valores = $datos;
    
    $params = $datos + $params;
    

    $from = \Drupal::config('system.site')->get('mail');

    //drupal_set_message('esto es de: '.$to);
    $language_code ='es';

    $form_state->setRebuild();

 
    $result = \Drupal::service('plugin.manager.mail')->mail($module, $key, $to, $language_code, $params, $from, TRUE);
    if ($result['result'] == TRUE) {
    //drupal_set_message($this->t($params['mensaje']));
      drupal_set_message($this->t('Hemos enviado correctamente el mensaje.'));
    }
    else {
      drupal_set_message($this->t('There was a problem sending your message and it was not sent.'), 'error');
    }


    //global $base_url;

    //$ruta='/admin/gestion/vista-all-usuarios/'.$form_state->getValue('nombre');
    //$ruta='/node/add/bz_presencia?id='.$form_state->getValue('nombre');
    //dpm($base_url);

    //$response = new RedirectResponse($base_url."".$ruta);
    //$response->send();
    return;

  }





  function indicarDerechos ($derecho, $valor) {

    $texto = "";

    if ($valor == 1) {
      $texto = "El usuario ejerce el derecho de ". $derecho;      
    }

    return $texto;

  }

  function validar_dni ($dni){
    $letra = substr($dni, -1);
    $numeros = substr($dni, 0, -1);
    $validado = false;

    if (substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros%23, 1) == $letra && strlen($letra) == 1 && strlen($numeros) == 8) {
      $validado = true;
    }

    return $validado;
  }


}


?>
