<?php
namespace Drupal\gestion_formulario\Controller;

use Drupal;
use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;

class BusquedasController extends ControllerBase {


  public function busqueda_usuario() {
  // Utilizamos el formulario
          $form = $this->formBuilder()->getForm('Drupal\gestion_formulario\Form\BuscarUsuriosForm');
          //ksm($form);
          //drupal_set_message(t('Formulario: '.$nombre), 'status', FALSE);

  // Le pasamos el formulario y demás a la vista (tema configurado en el module)
          return [
              '#theme' => 'busqueda',
              '#titulo' => $this->t('Formulario para la Búsqueda de Usuarios'),
              '#descripcion' => 'Formulario para la búsqueda de usuarios para añadir',
              '#formulario' => $form
          ];


  }

  //simulador_resultado

  public function simulador_resultado($salarioBruto, $horas, $hijos) {

    $mensajes = array();
    //posibles parámetros para la Búsqueda
    $salarioBruto = $salarioBruto;
    $horas = $horas;
    $hijos = $hijos;

    $reduccion = ($horas / 8);
    $salarioReducido = 0;
    $salarioTotal = 0;


    $mensajes['salarioBruto'] = $salarioBruto;
    $mensajes['reduccion'] = $reduccion;
    $mensajes['hijos'] = $hijos;


    // salario en el ERTE los 3 primeros meses

    $salarioBrutoMensual = $salarioBruto/12;
    $mensajes['salarioBrutoMensual'] = self::redondear($salarioBrutoMensual);


    if ($reduccion && $reduccion != 0){
      $salarioReducido = $salarioBrutoMensual * $reduccion ;
      //var_dump('con reducción');
    }else{
      $salarioReducido = $salarioBrutoMensual;
    }

    $mensajes['salarioReducido'] = self::redondear($salarioReducido);


    $seisMeses = ($salarioReducido * 0.70);
    $septimoMes = ($salarioReducido * 0.50);

    $mensajes['seisMeses'] = self::redondear($seisMeses);
    $mensajes['septimoMes'] = self::redondear($septimoMes);



    $thijos = array();
    // Máximos de sueldo por hijos/as
    $thijos['max'][0]= 1098.09;
    $thijos['max'][1]= 1254.96;
    $thijos['max'][2]= 1411.83;
    //Mínimos de sueldos por hijos/as
    $thijos['min'][0]= 501.98;
    $thijos['min'][1]= 671.40;

    //var_dump($seisMeses);
    //var_dump ($thijos['max'][0]);
    //var_dump ($hijos);


    // TOPES SI SUPERAS EL MÁXIMO PRA LOS 6 PRIMEROS MESES
    if ($seisMeses >= $thijos['max'][0] ){ // Si el salario reduccido es mayor igual a 1089 €
        if ($hijos == "1" ){ // si se tiene un hijo es de 1254 €
          if ($seisMeses >= $thijos['max'][1] ){
            $salarioTotalprimero = ($thijos['max'][1] * $reduccion);
            $mensajes['salarioTotalControl1 '] = $salarioTotalprimero;
          }else{
            $salarioTotalprimero = ($seisMeses * $reduccion);
            $mensajes['salarioTotalControl1 '] = $salarioTotalprimero;
          }

        }elseif ($hijos >= 2) { // si se tiene 2 hijos es de 1411 €
          if ($seisMeses >= $thijos['max'][2]){
            $salarioTotalprimero = ($thijos['max'][2] * $reduccion);
            $mensajes['salarioTotalControl2 '] = $salarioTotalprimero;
          }else {
            $salarioTotalprimero = ($seisMeses * $reduccion);
            $mensajes['salarioTotalControl2 '] = $salarioTotalprimero;
          }

        }else{
          $salarioTotalprimero = ($thijos['max'][0] * $reduccion);
          $mensajes['salarioTotalControl3 '] = $salarioTotalprimero;
        }
    }else{
        $salarioTotalprimero = ($seisMeses * $reduccion);
        $mensajes['salarioTotalControl4 '] = $salarioTotalprimero;
        $mensajes['comprobarSeisMeses'] = $seisMeses;
        $mensajes['comprobarReduccion'] = $reduccion;
    }

    // TOPES SI SUPERAS EL MÁXIMO A PARTIR DEL SÉPTIMO MES
    if ($septimoMes >= $thijos['max'][0] ){ // Si el salario reduccido es mayor igual a 1089 €
        if ($hijos == "1" ){ // si se tiene un hijo es de 1254 €
          if ($septimoMes >= $thijos['max'][1] ){
            $salarioTotalsegundo= ($thijos['max'][1] * $reduccion);
            $mensajes['salarioTotalControl5 '] = $salarioTotalsegundo;

          }else{
            $salarioTotalsegundo = ($septimoMes * $reduccion);
            $mensajes['salarioTotalControl5 '] = $salarioTotalsegundo;
          }

        }elseif ($hijos >= 2) { // si se tiene 2 hijos es de 1411 €
          if ($septimoMes >= $thijos['max'][2]){
            $salarioTotalsegundo = ($thijos['max'][2] * $reduccion);
            $mensajes['salarioTotalControl6 '] = $salarioTotalsegundo;
          }else {
            $salarioTotalsegundo = $septimoMes;
            $mensajes['salarioTotalControl6 '] = $salarioTotalsegundo;
          }

        }else{ // máximo  básico 1084 €
          $salarioTotalsegundo = ($thijos['max'][0] * $reduccion);
          $mensajes['salarioTotalControl7 '] = $salarioTotalsegundo;
        }
    } else {
          $salarioTotalsegundo = ($septimoMes * $reduccion);
          $mensajes['salarioTotalControl8 '] = $salarioTotalsegundo;

    }

    // TOPES SI NOS LLEGAS AL MÍMINO EN LOS SEIS PRIMEROS MESES
    if ($seisMeses <= $thijos['min'][1]){
      if ($hijos == 0){
        $salarioTotalprimero = ($thijos['min'][0] * $reduccion);
        $mensajes['salarioTotalControl9 '] = $salarioTotalprimero;
      }else{
        $salarioTotalprimero = ($thijos['min'][1] * $reduccion);
        $mensajes['salarioTotalControl9 '] = $salarioTotalprimero;
      }

    }
    // TOPES SI NOS LLEGAS AL MÍMINO A PARIR DEL SÉPTIMO MES

    if ($septimoMes <= $thijos['min'][1]){

      if ($hijos == 0){
        $salarioTotalsegundo = ($thijos['min'][0] * $reduccion);
        $mensajes['salarioTotalControl10'] = $salarioTotalsegundo;
      }else{
        $salarioTotalsegundo = ($thijos['min'][1] * $reduccion);
        $mensajes['salarioTotalControl10 '] = $salarioTotalsegundo;
      }

    }

    $mensajes['salarioTotalprimero'] = self::redondear($salarioTotalprimero); //Salario total los 6 primeros meses
    $mensajes['salarioTotalsegundo'] = self::redondear($salarioTotalsegundo); //Salario total los 6 primeros meses
    $mensajes['salarioTotal'] = self::redondear($salarioTotal);
    $mensajes['jornada'] = self::redondear($reduccion * 8);

    // var_dump($mensajes);

    // PARAMETROS QUE PASAMOS A LA VISTA PARA PINTAR TODOS LOS RESULTADOS


          return [
              '#theme' => 'resultado',
              '#titulo' => $this->t('Simulador cobro ERTE'),
              '#descripcion' => 'Este formulario sirve para ver cuando cobras en caso de que te manden a un ERTE',
              '#mensajes' => $mensajes
          ];


  }

  public function simulador() {
  // Utilizamos el formulario
          $form = $this->formBuilder()->getForm('Drupal\gestion_formulario\Form\SimuladorErteForm');
          //ksm($form);
          //drupal_set_message(t('Formulario: '.$nombre), 'status', FALSE);

  // Le pasamos el formulario y demás a la vista (tema configurado en el module)
          return [
              '#theme' => 'busqueda',
              '#titulo' => $this->t('Simulador cobro ERTE'),
              '#descripcion' => 'Este formulario sirve para ver cuando cobras en caso de que te manden a un ERTE',
              '#formulario' => $form
          ];


  }

  public function redondear ($numero){
      $redondeo = round($numero * 100) / 100;
      return $redondeo;
  }

}

?>
