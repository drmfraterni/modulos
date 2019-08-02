<?php
namespace Drupal\migrar_db;

use Drupal\node\Entity\Node;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDatetime;
use Drupal\rdls_mailing\RdlsMailManager;

/**
 * Class RdlsMailManager.
 *
 * @package Drupal\rdls_mailing
 * @ingroup rdls_api
 */
class MigrardbService {
  use StringTranslationTrait;

  /**
   * Plan all mails for the active subscribers.
   *
   * @return string $message
   *   Message containing result & statistics.
   *
   */

   // FUNCIÓN QUE BUSCA EN LA TABLA USUARIOS DE LA BBDD
  function bdUsuarios(){
         // función para conectar con una base de datos externa al drupal 8

         \Drupal\Core\Database\Database::setActiveConnection('external');
         $con = \Drupal\Core\Database\Database::getConnection();

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

         //var_dump($mens_error);
         //die();

         \Drupal\Core\Database\Database::setActiveConnection();
         return $registros;

     }
     // FUNCIÓN QUE BUSCA EN LA TABLA PRODUCTOS DE LA BBDD
  function bdProductos(){
         // función para conectar con una base de datos externa al drupal 8
        //$con = \Drupal\Core\Database\Database::getConnection();
        \Drupal\Core\Database\Database::setActiveConnection('external');
        $con = \Drupal\Core\Database\Database::getConnection();

         $result = $con->query("SELECT idProducto, dsProducto, nmPrecio, itPeriodicidad FROM bza_productos");
         $registros = array();
         if ($result){
           while ($row = $result->fetchAssoc()){
             $registros['idProducto'][] = $row['idProducto'];
             $registros['dsProducto'][] = $row['dsProducto'];
             $registros['nmPrecio'][] = $row['nmPrecio'];
             $registros['itPeriodicidad'][] = $row['itPeriodicidad'];
           }
         }else{
           drupal_set_message(t('Error en la consulta de Productos'), 'status', FALSE);
         }

         \Drupal\Core\Database\Database::setActiveConnection();
         return $registros;

     }
     // FUNCIÓN QUE BUSCA EN LA TABLA COMPRAS DE LA BBDD
  function bdCompras(){
         // función para conectar con una base de datos externa al drupal 8
         //$con = \Drupal\Core\Database\Database::getConnection();
         \Drupal\Core\Database\Database::setActiveConnection('external');
         $con = \Drupal\Core\Database\Database::getConnection();

         $result = $con->query("SELECT idCompra, idUsuario, idProducto, dtFechaInicio, dtFechaFin,
           nmPrecio FROM bza_compras");
         $registros = array();
         if ($result){
           while ($row = $result->fetchAssoc()){
             $registros['idCompra'][] = $row['idCompra'];
             $registros['idUsuario'][] = $row['idUsuario'];
             $registros['idProducto'][] = $row['idProducto'];
             $registros['dtFechaInicio'][] = $row['dtFechaInicio'];
             $registros['dtFechaFin'][] = $row['dtFechaFin'];
             $registros['nmPrecio'][] = $row['nmPrecio'];
           }
         }else{
           drupal_set_message(t('Error en la consulta de Productos'), 'status', FALSE);
         }

         \Drupal\Core\Database\Database::setActiveConnection();

         return $registros;

     }
     // FUNCIÓN CAMBIA EL ID DEL USUARIO POR ID_NODO DEL NODO TIPO USUARIO
  function codigoExterno($dato, $tipo, $campo){
    //CONSULTA A LA BASE DE DATOS
    //var_dump($dato);
    $query = \Drupal::entityQuery('node')
    ->condition('type', $tipo)
    ->condition($campo, $dato, '=')
    ->execute();
    //var_dump($query);

    //NOS DEVUELVE EL ID DE NODO
    if (!empty($query)) {
      foreach ($query as $cod) {
        $codigo = $cod;
      }
    }else{
        $codigo = 0;
    }
    //var_dump($codigo);
    //die();
    //LO ENVIAMOS A LA TABLA GENERAL
    return $codigo;

  }
  function cambiarUsuario($dato){

           $query = \Drupal::entityQuery('node')
               ->condition('type', 'bz_usuarios')
               //->condition('field_id', $dato, '=')
               //->condition('field_idusuario', $dato, '=')
               ->condition('field_idusu', $dato, '=')
               ->execute();

           if (!empty($query)) {
             foreach ($query as $cod) {
                 //$codigo = intval($cod);
                 $codigo = $cod;
             }
         }



         return $codigo;

       }
       // FUNCIÓN CAMBIA EL ID DEL USUARIO POR ID_NODO DEL NODO TIPO PRODUCTO
  function cambiarProducto($dato){
         $query = \Drupal::entityQuery('node')
               ->condition('type', 'bza_productos')
               ->condition('field_idproducto', $dato, '=')
               ->execute();
           if (!empty($query)) {
             foreach ($query as $cod) {
                 $codigo = $cod;
             }
         }
         return $codigo;
      }

    // FUNCIÓN DE BORRADO DE LAS TABLAS BBDD
  // FUNCIÓN DE BORRADO DE LAS TABLAS BBDD
  function borrado($tipo){

        $types = array($tipo);

        $nids_query = db_select('node', 'n')
        ->fields('n', array('nid'))
        ->condition('n.type', $types, 'IN')
        ->range(0, 1500)
        ->execute();

        $nids = $nids_query->fetchCol();

        entity_delete_multiple('node', $nids);


        $nodes = \Drupal::entityTypeManager()
            ->getStorage('node')
            ->loadByProperties(array('type' => $tipo));

        return $nodes;


    }
  function comprobarElementos ($tipo, $campo, $elemento){

    $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
    $query->condition('type', $tipo);
    $query->condition($campo, $elemento, '=');
    $nids = $query->execute();
    return $nids;


  }
  function bdRecibos(){
         // función para conectar con una base de datos externa al drupal 8

         \Drupal\Core\Database\Database::setActiveConnection('external');
         $con = \Drupal\Core\Database\Database::getConnection();

         $result = $con->query("SELECT idRecibo, idCompra, dtFecha, nmImporte, cdTipoCargo,
           cdEstado, dtEstado, dsObservaciones, nmIVA, dtPeriodoDesde, dtPeriodoHasta
           FROM bza_recibos LIMIT 5631,5900");
         $registros = array();
         $mens_error = array();
         if ($result){
           while ($row = $result->fetchAssoc()){
             $registros['idRecibo'][] = $row['idRecibo'];
             $registros['idCompra'][] = $row['idCompra'];
             $registros['dtFecha'][] = $row['dtFecha'];
             $registros['nmImporte'][] = $row['nmImporte'];
             $registros['cdTipoCargo'][] = $row['cdTipoCargo'];
             $registros['cdEstado'][] = $row['cdEstado'];
             $registros['dsObservaciones'][] = $row['dsObservaciones'];
             $registros['nmIVA'][] = $row['nmIVA'];
             $registros['dtPeriodoDesde'][] = $row['dtPeriodoDesde'];
             $registros['dtPeriodoHasta'][] = $row['dtPeriodoHasta'];
           }
         }

         \Drupal\Core\Database\Database::setActiveConnection();

         return $registros;

     }
  function bdPresencia(){
         // función para conectar con una base de datos externa al drupal 8

         \Drupal\Core\Database\Database::setActiveConnection('external');
         $con = \Drupal\Core\Database\Database::getConnection();

         $result = $con->query("SELECT idPresencia, idUsuario, dtFecha,
         cdTipo, idGrupo, idCompra FROM bza_presencia LIMIT 13001,13500");
         $registros = array();
         $mens_error = array();
         if ($result){
           while ($row = $result->fetchAssoc()){
             $registros['idPresencia'][] = $row['idPresencia'];
             $registros['idUsuario'][] = $row['idUsuario'];
             $registros['dtFecha'][] = $row['dtFecha'];
             $registros['cdTipo'][] = $row['cdTipo'];
             $registros['idGrupo'][] = $row['idGrupo'];
             $registros['idCompra'][] = $row['idCompra'];

           }
         }

         \Drupal\Core\Database\Database::setActiveConnection();

         return $registros;

     }
     // FUNCIÓN QUE BUSCA EN LA TABLA PRODUCTOS DE LA BBDD


}
