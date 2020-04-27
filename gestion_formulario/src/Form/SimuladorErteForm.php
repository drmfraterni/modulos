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



class SimuladorErteForm extends FormBase {

  protected $envioconf = FALSE;



  public function getFormId(){
    // NOMBRE DEL FORMULARIO
    return 'simulador_erte';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

      $textoHtml = "Horas de reducción.<br> Si el ERTE es toda la jornada sería 8 horas ";


      $form ['salario'] = array (
        '#type'     => 'number',
        '#title'    => $this->t('Salario Bruto Anual'),
        '#description' => $this->t('Introduce el <strong>Sueldo Bruto Anual</strong> '),
        '#required' => TRUE,
      );
      $form ['reduccion'] = array (
        '#type'     => 'number',
        '#title'    => $this->t('Horas de Reducción'),
        '#description' => $this->t($textoHtml),
        '#default_value'  => 8,
        '#required' => TRUE,
      );
      $form ['hijos'] = array (
        '#type'     => 'number',
        '#title'    => $this->t('Número de hijos'),
        '#default_value'  => 0,
        '#description' => $this->t('Introduce el número de hijos '),
        '#required' => TRUE,
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

    $control = FALSE;

    if (is_numeric($form_state->getValue('salario'))){
      $control = TRUE;
    }

    if (is_numeric($form_state->getValue('reduccion')) && $control == TRUE){
      $control = TRUE;
    }

    if (is_numeric($form_state->getValue('hijos')) && $control == TRUE){
      $control = TRUE;
    }


    if ($control==FALSE){
      $form_state->setErrorByName('control', $this->t('Alguno de los campos no es un NÚMERO'));
    }


  }



/**
* {@inheritdoc}
*/

public function submitForm(array &$form, FormStateInterface $form_state) {

      $mensajes = array();
      //posibles parámetros para la Búsqueda
      $salarioBruto = $form_state->getValue('salario');
      $horas = $form_state->getValue('reduccion');
      $reduccion = ($horas / 8);
      $hijos = $form_state->getValue('hijos');
      $salarioReducido = 0;
      $salarioTotal = 0;


      $mensajes['salarioBruto'] = $salarioBruto;
      $mensajes['reduccion'] = $reduccion;
      $mensajes['hijos'] = $form_state->getValue('hijos');


      // salario en el ERTE los 3 primeros meses

      $salarioBrutoMensual = $salarioBruto/12;
      $mensajes['salarioBrutoMensual'] = $salarioBrutoMensual;


      if ($reduccion && $reduccion != 0){
        $salarioReducido = $salarioBrutoMensual * $reduccion ;
        //var_dump('con reducción');
      }else{
        $salarioReducido = $salarioBrutoMensual;
      }

      $mensajes['salarioReducido'] = $salarioReducido;


      $seisMeses = ($salarioReducido * 0.70);
      $septimoMes = ($salarioReducido * 0.50);

      $mensajes['seisMeses'] = $seisMeses;
      $mensajes['septimoMes'] = $septimoMes;



      $thijos = array();
      // Máximos de sueldo por hijos/as
      $thijos['max'][0]= 1089.09;
      $thijos['max'][1]= 1254.86;
      $thijos['max'][2]= 1411.83;
      //Mínimos de sueldos por hijos/as
      $thijos['min'][0]= 501.98;
      $thijos['min'][1]= 671.40;

      //var_dump($seisMeses);
      //var_dump ($thijos['max'][0]);
      //var_dump ($hijos);


      // TOPES SI SUPERAS EL MÁXIMO PRA LOS 6 PRIMEROS MESES
      if ($seisMeses >= $thijos['max'][0] ){ // Si el salario reduccido es mayor igual a 1089 €
          //var_dump($hijos);
          //var_dump($seisMeses);
          if ($hijos == "1" ){ // si se tiene un hijo es de 1254 €
            if ($seisMeses >= $thijos['max'][1] ){
              $salarioTotalprimero = $thijos['max'][1];
              $mensajes['salarioTotalControl1 '] = $salarioTotalprimero;
            }else{
              $salarioTotalprimero = $seisMeses;
              $mensajes['salarioTotalControl1 '] = $salarioTotalprimero;
            }

          }elseif ($hijos >= 2) { // si se tiene 2 hijos es de 1411 €
            if ($seisMeses >= $thijos['max'][2]){
              $salarioTotalprimero = $thijos['max'][2];
              $mensajes['salarioTotalControl2 '] = $salarioTotalprimero;
            }else {
              $salarioTotalprimero = $seisMeses;
              $mensajes['salarioTotalControl2 '] = $salarioTotalprimero;
            }

          }else{
            $salarioTotalprimero = $thijos['max'][0];
            $mensajes['salarioTotalControl3 '] = $salarioTotalprimero;
          }
      }else{
          $salarioTotalprimero = $seisMeses;
      }

      // TOPES SI SUPERAS EL MÁXIMO A PARTIR DEL SÉPTIMO MES
      if ($septimoMes >= $thijos['max'][0] ){ // Si el salario reduccido es mayor igual a 1089 €
          //var_dump($hijos['max'][0]);
          //var_dump($septimoMes);
          if ($hijos == "1" ){ // si se tiene un hijo es de 1254 €
            if ($septimoMes >= $thijos['max'][1] ){
              $salarioTotalsegundo= $thijos['max'][1];
              $mensajes['salarioTotalControl4 '] = $salarioTotalsegundo;
            }else{
              $salarioTotalsegundo = $septimoMes;
              $mensajes['salarioTotalControl4 '] = $salarioTotalsegundo;
            }

          }elseif ($hijos >= 2) { // si se tiene 2 hijos es de 1411 €
            if ($septimoMes >= $thijos['max'][2]){
              $salarioTotalsegundo = $thijos['max'][2];
              $mensajes['salarioTotalControl5 '] = $salarioTotalprimero;
            }else {
              $salarioTotalsegundo = $septimoMes;
              $mensajes['salarioTotalControl5 '] = $salarioTotalprimero;
            }

          }else{ // máximo  básico 1084 €
            $salarioTotalsegundo = $thijos['max'][0];
            $mensajes['salarioTotalControl6 '] = $salarioTotalsegundo;
          }
      } else {
            $salarioTotalsegundo = $septimoMes;

      }

      // TOPES SI NOS LLEGAS AL MÍMINO EN LOS SEIS PRIMEROS MESES
      if ($seisMeses <= $thijos['min'][0]){
        if ($hijos == 0){
          $salarioTotalprimero = $thijos['min'][0];
        }else{
          $salarioTotalprimero = $thijos['min'][1];
        }

      }
      // TOPES SI NOS LLEGAS AL MÍMINO A PARIR DEL SÉPTIMO MES
      if ($septimoMes <= $thijos['min'][0]){
        if ($hijos == 0){
          $salarioTotalsegundo = $thijos['min'][0];
        }else{
          $salarioTotalsegundo = $thijos['min'][1];
        }

      }

      $mensajes['salarioTotalprimero'] = $salarioTotalprimero; //Salario total los 6 primeros meses
      $mensajes['salarioTotalsegundo'] = $salarioTotalsegundo; //Salario total los 6 primeros meses







      $mensajes['salarioTotal'] = $salarioTotal;

      //var_dump($mensajes);
      //die();

      $this->messenger()->addStatus($this->t('Salario Bruto Anual es: @sba', ['@sba' => self::redondear($salarioBruto) ]));
      $this->messenger()->addStatus($this->t('Horas de reduccción: @horas', ['@horas' => self::redondear($horas) ]));
      $this->messenger()->addStatus($this->t('Porcentaje de reducción: @reduccion', ['@reduccion' => self::redondear($reduccion) ]));
      $this->messenger()->addStatus($this->t('El SALARIO BRUTO MENSUAL ES : @salarioBrutoMes', ['@salarioBrutoMes' => self::redondear($salarioBrutoMensual) ]));
      $this->messenger()->addStatus($this->t('El SALARIO CORRESPONDIENTE A LA REDUCCIÓN DE HORAS por horas: @sbrm', ['@sbrm' => self::redondear($salarioReducido) ]));
      $this->messenger()->addStatus($this->t('El SALARIO REDUCIDO AL MES DEL 70% PARA EL SEPE LOS 6 PRIMEROS MESES ES: @sbrmseis', ['@sbrmseis' => self::redondear($seisMeses) ]));
      $this->messenger()->addStatus($this->t('El SALARIO REDUCIDO AL MES AL 50% PARA EL SEPE A PARTIR DEL 7 MES: @sbrmsiete', ['@sbrmsiete' => self::redondear($septimoMes) ]));
      $this->messenger()->addStatus($this->t('SALARIO SEGÚN ARREGLO A TABLAS <strong>los primeros 6 meses</strong> @seismeses', ['@seismeses' => self::redondear($salarioTotalprimero) ]));
      $this->messenger()->addStatus($this->t('SALARIO SEGÚN ARREGLO A TABLAS <strong>a partir del 7 mes</strong>: @septimomes', ['@septimomes' => self::redondear($salarioTotalsegundo) ]));

      
      return;

  }

  public function redondear ($numero){
      $redondeo = round($numero * 100) / 100;
      return $redondeo;
  }

}



?>
