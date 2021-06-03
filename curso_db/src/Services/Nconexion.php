<?php

namespace Drupal\curso_db\Services;

/**
* Nueva conexión base de datos externa
*/


class Nconexion{


  private $con;

  public function nuevaConexion(){

    \Drupal\Core\Database\Database::setActiveConnection('aplicacion');
    $this->con = \Drupal\Core\Database\Database::getConnection();

    /*
    $result = $con->query("SELECT idUsuario, dsNombre, dsApellido1, dsApellido2,
         dsEmail, fcAlta, dsTelefono1, cdTarjeta FROM bza_usuarios");

    $registros = array();
    $mens_error = array();
    if ($result){
      while ($row = $result->fetchAssoc()){
        $registros['idUsuario'][] = $row['idUsuario'];
        $registros['dsNombre'][] = $row['dsNombre'];
        $registros['dsApellido1'][] = $row['dsApellido1'];
        $registros['dsApellido2'][] = $row['dsApellido2'];
        $registros['dsEmail'][] = $row['dsEmail'];
        $registros['fcAlta'][] = $row['fcAlta'];
        if (strlen($row['dsTelefono1']) == 9){
         $registros['dsTelefono'][] = $row['dsTelefono1'];
         //$mens_error[] = $row['idUsuario']." - ".$row['dsTelefono1']." - ".strlen($row['dsTelefono1']);
        }else{
          $registros['dsTelefono'][] = NULL;
          $mens_error[] = $row['idUsuario']." - ".$row['dsTelefono1']." - ".strlen($row['dsTelefono1']);
        }
        //$registros['dsTelefono'][] = $row['dsTelefono1'];
        $registros['cdTarjeta'][] = $row['cdTarjeta'];
      }

    }
    */

    return $this->con;
  }

  public function buscarUsuarios($usuario = '') 
  {
    $consulta = $this->con->query("SELECT idUsuario, dsNombre, dsApellido1, dsApellido2,
    dsEmail, fcAlta, dsTelefono1, cdTarjeta FROM bza_usuarios");

    return $consulta;
    
  }

}
