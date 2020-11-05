<?php

namespace Drupal\alterar_formulario\Services;

/**
* Servicio de Calendario
*/


class Miscelaneo {

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

public function urlCompleta () {  // 


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

	



}