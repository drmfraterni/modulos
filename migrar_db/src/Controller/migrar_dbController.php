<?php

namespace Drupal\migrar_db\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\node\Entity\Node;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Datetime\Entity;
//use \Drupal\node\Entity\Node::loadMultiple;


/**
 * Returns responses for Node routes.
 */
class migrar_dbController extends ControllerBase {

  public function listarUsuarios (){


     	$registros = array ();

      // las funciones las he metido en servicios.
      $registros = \Drupal::service('migrardb.migrarall')->bdUsuarios();
      $cantidadReg = count($registros['idUsuario']);
      $elementoNuevo = '<ul>';
      $nCantidadRegistro = 0;




      //Añadimos registros a la base de datos

      for ($i=0; $i < $cantidadReg; $i++){

        $fecha = $registros['fcAlta'][$i];
        $fAlta = date("Y-m-d\TH:i:s", strtotime($fecha));

        $node = Node::create([
          'type' => 'bz_usuarios',
    		  'langcode' => 'es',
    		  // The user ID.
    		  'uid' => 0,
    		  'moderation_state' => 'published',
    		  'title' =>$registros['dsApellido1'][$i]." ". $registros['dsApellido2'][$i].", ".$registros['dsNombre'][$i],
          //'field_id' => $registros['idUsuario'][$i],
          //'field_idusuario' => $registros['idUsuario'][$i],
          'field_idusu' => $registros['idUsuario'][$i],
          'field_apellido1' => $registros['dsApellido1'][$i],
          'field_apellido2' => $registros['dsApellido2'][$i],
          'field_email'=> $registros['dsEmail'][$i],
          'field_fcalta'=>[$fAlta],
          'field_telefono'=> $registros['dsTelefono'][$i],
          //'field_tarjeta'=> $registros['cdTarjeta'][$i],
          'field_cd_tarjeta'=> $registros['cdTarjeta'][$i],

        ]);


        $elemento = $registros['idUsuario'][$i];
        //COMPROBAMOS que no existe en la aplicación.
        $elementos = \Drupal::service('migrardb.migrarall')->comprobarElementos ('bz_usuarios', 'field_idusu', $elemento);

        // SI NO exite el registro en la aplicación se introduce. Si ya existe no hace nada
        if (!$elementos){
            $node->save();
            $elementoNuevo .= '<li>'.$elemento." ".$registros['dsApellido1'][$i]." ".
            $registros['dsApellido2'][$i].", ".$registros['dsNombre'][$i].'</li>';
            $nCantidadRegistro++;
        }

      }

      $elementoNuevo .='</ul>';
      drupal_set_message(t('La cantidad de usuarios son: '. $cantidadReg. 'de los cuales nuevo son '. $elementoNuevo), 'status', FALSE);
      //var_dump($usuarios);

      $registrosVer = array();
      $limite = 0;
      // Nos muestra los 20 primeros y los 20 últimos de los usuarios que tenemos en la base de datos //
      for ($i = 0; $i < $nCantidadRegistro; $i++ ){

          //COMPROBAMOS si hay muchos registros o hay poco.
          ($nCantidadRegistro < $limite ) ? $limite = $nCantidadRegistro : $limite = 15;

          if ($i < $limite){
            $registrosVer []=$registros['idUsuario'][$i]." - ".$registros['dsApellido1'][$i]." ".$registros['dsApellido2'][$i]
            .", ".$registros['dsNombre'][$i];
          }
          if ($i > ($cantidadReg-$limite)){
            $registrosVer []=$registros['idUsuario'][$i]." - ".$registros['dsApellido1'][$i]." ".$registros['dsApellido2'][$i]
            .", ".$registros['dsNombre'][$i];

          }


      }

      // Mandamos a la plantilla los resultados para que nos lo muestre en pantalla.
      return [
          '#theme' => 'migrardb',
          '#titulo' => $this->t('Ver todos los registros'),
          '#descripcion' => 'Vemos todos los registros de una tabla',
          '#registros' => $registrosVer,
          '#cantidadTotal' => $cantidadReg,
      ];




     }
  public function listarNodos($dato){

       //$id = cambiarProducto($dato);

       $id = \Drupal::service('migrardb.migrarall')->cambiarProducto();

       var_dump($id);
       $node = Node::load($id);

      return [
            '#theme' => 'migrardb',
            '#titulo' => $this->t('Ver todos los registros'),
            '#descripcion' => 'Vemos todos los registros de una tabla',
            '#registros' => $registro,
        ];

     }
  public function listarCompras (){

      $registros = array ();
      $registros = \Drupal::service('migrardb.migrarall')->bdCompras();
      $cantidadReg = count($registros['idCompra']);
      $elementoNuevo = '<ul>';
      $nCantidadRegistro = 0;
      $reg = array();



      for ($i=0; $i < $cantidadReg; $i++){
        // ARREGLOS DE CAMPOS DIVERSOS DE LA TABLA COMPRAS

        $fechaInicio = $registros['dtFechaInicio'][$i];
        $fechaFin = $registros['dtFechaFin'][$i];


        $id_usuario = \Drupal::service('migrardb.migrarall')->cambiarUsuario($registros['idUsuario'][$i]);
        $id_producto = \Drupal::service('migrardb.migrarall')->cambiarProducto($registros['idProducto'][$i]);

        //$dateInicio = date("d/m/Y H:i:s", strtotime($fechaInicio));
        //$datefin = date("d/m/Y H:i:s", strtotime($fechaFin));
        $dateInicio = date("Y-m-d\TH:i:s", strtotime($fechaInicio));
        $datefin = date("Y-m-d\TH:i:s", strtotime($fechaFin));



        if ($datefin == "1970-01-01T01:00:00"){
          $datefin = NULL;
        }


        $precio = $registros['nmPrecio'][$i];
        //$autor = \Drupal::currentUser()->id();

        // METEMOS LOS DATOS EN EL REGISTRO DE BZ_COMPRAS

        $node = Node::create([
          'type' => 'bz_compras',
    		  'langcode' => 'es',
    		  'uid' => 0,
    		  'moderation_state' => 'published',
    		  'title' =>"COMPRA - ". $id_usuario." - ".$id_producto,
          'field_idcompra' => $registros['idCompra'][$i],
          'field_producto' => $id_producto,
          'field_idusuario' => $id_usuario,
          //'field_fecha_fin'=> [ $datefin ],
          'field_fecha_end'=> [ $datefin ],
          'field_fecha'=> [ $dateInicio ],
          'field_precio_compra'=>$precio,


          ]);

          //$node->save();
          $elemento = $registros['idCompra'][$i];
          $elementos = \Drupal::service('migrardb.migrarall')->comprobarElementos ('bz_compras', 'field_idcompra', $elemento);


          if (!$elementos){
                $node->save();
                $elementoNuevo .= '<li>'.$elemento." USUARIO: ".$id_usuario." PRODUCTO:".$id_producto.'</li>';
                $reg[] = $i;
                $nCantidadRegistro++;

          }

      }

      $elementoNuevo .='</ul>';
      //drupal_set_message(t('La cantidad de Compras: '. $cantidadReg. 'de los cuales nuevas son '. $elementoNuevo), 'status', FALSE);
      $registrosVer = array();
      $limite = 0;

          // Nos muestra los 20 primeros y los 20 últimos de los usuarios que tenemos en la base de datos //
      for ($i = 0; $i < $nCantidadRegistro; $i++ ){

          // SÓLO registros que no existen en la aplicación.

          $num = $reg[$i];

          ($nCantidadRegistro < $limite ) ? $limite = $nCantidadRegistro : $limite = 15;

          if ($i < $limite){
               $registrosVer[]="COMPRA: ".$registros['idCompra'][$num]." USUARIO: ".
               $registros['idUsuario'][$i]." PRODUCTO: ".$registros['idProducto'][$i];
               //drupal_set_message(t('La cantidad de Compras: '. $nCantidadRegistro. 'de los cuales nuevas son '. $registrosVer[$i]), 'status', FALSE);
          }

          if ($i > ($nCantidadRegistro-$limite)){
               $registrosVer[]="COMPRA: ".$registros['idCompra'][$num]." USUARIO: ".
               $registros['idUsuario'][$i]." PRODUCTO: ".$registros['idProducto'][$i];
               //drupal_set_message(t('La cantidad de Compras: de los cuales nuevas son '. $registrosVer[$i]), 'status', FALSE);

          }




      }
       //var_dump($usuarios);

       return [
           '#theme' => 'migrardb',
           '#titulo' => $this->t('Ver todos los registros'),
           '#descripcion' => 'Vemos todos los registros de una tabla',
           '#registros' => $registrosVer,
           '#cantidadTotal' => $cantidadReg,
       ];

     }
  public function listarProductos (){

       $registros = array ();
       // SACAMOS  los registros de la BBDD
       $registros = \Drupal::service('migrardb.migrarall')->bdProductos();
       $cantidadReg = count($registros['dsProducto']);
       $elementoNuevo = '<ul>';
       $nCantidadRegistro = 0;
       $reg = array();



       for ($i=0; $i < $cantidadReg; $i++){

          $node = Node::create([
            'type' => 'bza_productos',
       		  'langcode' => 'es',
       		  // The user ID.
       		  'uid' => 0,
       		  'moderation_state' => 'published',
       		  'title' => $registros['dsProducto'][$i],
            'field_prod_precio' => $registros['nmPrecio'][$i],
            'field_periodicidad' => $registros['itPeriodicidad'][$i],
            'field_idproducto' => $registros['idProducto'][$i],

         ]);

         $elemento = $registros['idProducto'][$i];
         $elementos = \Drupal::service('migrardb.migrarall')->comprobarElementos ('bza_productos', 'field_idproducto', $elemento);


            if (!$elementos){
                $node->save();
                $elementoNuevo .= '<li>'.$elemento." ".$registros['dsProducto'][$i].'</li>';
                $reg[] = $i;
                $nCantidadRegistro++;

            }

       }
         $elementoNuevo .='</ul>';
         drupal_set_message(t('La cantidad de Productos son: '. $cantidadReg. 'de los cuales nuevo son '. $elementoNuevo), 'status', FALSE);
         //var_dump($usuarios);

        $registrosVer = array();
        $limite = '';
          //$nCantidadRegistro = count($elementos);

      // Nos muestra los 20 primeros y los 20 últimos de los usuarios que tenemos en la base de datos //
        for ($i = 0; $i < $nCantidadRegistro; $i++ ){

            // SÓLO registros que no existen en la aplicación.
            $num = $reg[$i];

            ($nCantidadRegistro < $limite ) ? $limite = $nCantidadRegistro : $limite = 15;

             if ($i < $limite){
               $registrosVer []=$registros['idProducto'][$num]." - ".$registros['dsProducto'][$num]." ".$registros['nmPrecio'][$num];
             }
             if ($i > ($cantidadReg-$limite)){
               $registrosVer []=$registros['idProducto'][$num]." - ".$registros['dsProducto'][$num]." ".$registros['nmPrecio'][$num];

             }


         }
       //var_dump($usuarios);

       return [
           '#theme' => 'migrardb',
           '#titulo' => $this->t('Ver todos los registros'),
           '#descripcion' => 'Vemos todos los registros de una tabla',
           '#registros' => $registrosVer,
           '#cantidadTotal' => $cantidadReg,
       ];

     }
  public function listarRecibos (){

         $registros = array ();
         $registros = \Drupal::service('migrardb.migrarall')->bdRecibos();
         $cantidadReg = count($registros['idRecibo']);
         $elementoNuevo = '<ul>';
         $nCantidadRegistro = 0;
         $reg = array();



         for ($i=0; $i < $cantidadReg; $i++){
           // ARREGLOS DE CAMPOS DIVERSOS DE LA TABLA COMPRAS

           $fecha = $registros['dtFecha'][$i];
           $fechaInicio = $registros['dtPeriodoDesde'][$i];
           $fechaFin = $registros['dtPeriodoHasta'][$i];
           $date = date("Y-m-d\TH:i:s", strtotime($fecha));
           $dateInicio = date("Y-m-d\TH:i:s", strtotime($fechaInicio));
           $datefin = date("Y-m-d\TH:i:s", strtotime($fechaFin));

           if ($datefin == "1970-01-01T01:00:00"){
             $datefin = NULL;
           }

           //$id_compra = \Drupal::service('migrardb.migrarall')->cambiarCompra($registros['idCompra'][$i]);
           $id_compra = \Drupal::service('migrardb.migrarall')->codigoExterno($registros['idCompra'][$i],'bz_compras','field_idcompra');

           // METEMOS LOS DATOS EN EL REGISTRO DE BZ_COMPRAS

           $node = Node::create([
             'type' => 'bz_recibo',
             'langcode' => 'es',
             'uid' => 1,
             //'uid' => 0,
             'moderation_state' => 'published',
             'title' =>"RECIBO - ". $registros['idRecibo'][$i]." - ".$id_compra,
             'field_idrecibo' => $registros['idRecibo'][$i],
             'field_idcomprarec' => $id_compra,
             'field_dtfecha' => [ $date ],
             'field_nmimporte' => $registros['nmImporte'][$i],
             'field_cdtipocargo'=> $registros['cdTipoCargo'][$i],
             'field_cdestado'=> $registros['cdEstado'][$i],
             'body'=>$registros['dsObservaciones'][$i],
             'field_nmiva'=> $registros['nmIVA'][$i],
             'field_dtperiododesde'=> [ $dateInicio ],
             'field_dtperiodohasta'=> [ $datefin ],
             ]);

             //var_dump($registros['nmImporte'][$i]);
             //var_dump($dateInicio);
             //var_dump($datefin);
             //die();

             //$node->save();
             $elemento = $registros['idRecibo'][$i];
             $elementos = \Drupal::service('migrardb.migrarall')->comprobarElementos ('bz_recibo', 'field_idrecibo', $elemento);

             if (!$elementos){

                   $node->save();
                   $elementoNuevo .= '<li>RECIBO'.$elemento." COMPRA: ".$registros['idCompra'][$i].'</li>';
                   $reg[] = $i;
                   $nCantidadRegistro++;

             }

         }

         $elementoNuevo .='</ul>';
         //drupal_set_message(t('La cantidad de Compras: '. $cantidadReg. 'de los cuales nuevas son '. $elementoNuevo), 'status', FALSE);
         $registrosVer = array();
         $limite = 0;

             // Nos muestra los 20 primeros y los 20 últimos de los usuarios que tenemos en la base de datos //
         for ($i = 0; $i < $nCantidadRegistro; $i++ ){

             // SÓLO registros que no existen en la aplicación.

             $num = $reg[$i];

             ($nCantidadRegistro < $limite ) ? $limite = $nCantidadRegistro : $limite = 15;

             if ($i < $limite){
                  $registrosVer[]="RECIBO: ".$elemento." COMPRA: ". $registros['idCompra'][$num];
                  //drupal_set_message(t('La cantidad de Compras: '. $nCantidadRegistro. 'de los cuales nuevas son '. $registrosVer[$i]), 'status', FALSE);
             }

             if ($i > ($nCantidadRegistro-$limite)){
                  $registrosVer[]="RECIBO: ".$elemento." COMPRA: ". $registros['idCompra'][$num];
                  //drupal_set_message(t('La cantidad de Compras: de los cuales nuevas son '. $registrosVer[$i]), 'status', FALSE);

             }




         }
          //var_dump($usuarios);

          return [
              '#theme' => 'migrardb',
              '#titulo' => $this->t('Ver todos los registros'),
              '#descripcion' => 'Vemos todos los registros de una tabla',
              '#registros' => $registrosVer,
              '#cantidadTotal' => $cantidadReg,
          ];

        }
  public function listarPresencia (){

         //VARIABLES PARA MIGRAR TABLA PRESENCIA //
         $registros = array ();
         $registros = \Drupal::service('migrardb.migrarall')->bdPresencia(); // crear esta función
         $cantidadReg = count($registros['idPresencia']);
         $elementoNuevo = '<ul>';
         $nCantidadRegistro = 0;
         $reg = array(); // para localizar los registros nuevos;
         $usu = array(); // para pintar los usuarios;


         for ($i=0; $i < $cantidadReg; $i++){
           // ARREGLOS DE CAMPOS DIVERSOS DE LA TABLA PRESENCIA

           $fecha = $registros['dtFecha'][$i];
           $date = date("Y-m-d\TH:i:s", strtotime($fecha));

           if ($date == "1970-01-01T01:00:00"){
             $date = NULL;
           }


           $id_usuario = \Drupal::service('migrardb.migrarall')->codigoExterno($registros['idUsuario'][$i],'bz_usuarios','field_idusu');
           $id_compra = \Drupal::service('migrardb.migrarall')->codigoExterno($registros['idCompra'][$i],'bz_compras','field_idcompra');

           $usu[] = $id_usuario;

           // METEMOS LOS DATOS EN EL REGISTRO DE BZ_presecia

           $node = Node::create([
             'type' => 'bz_presencia',
             'langcode' => 'es',
             'uid' => 1,
             //'uid' => 0,
             'moderation_state' => 'published',
             'title' =>"ASISTENCIA - ". $registros['idPresencia'][$i]." - ".$id_usuario,
             'field_idpresencia' => $registros['idPresencia'][$i],
             'field_idpusuario' => $id_usuario,
             'field_dtpfecha' => [ $date ],
             'field_cdtipo' => $registros['cdTipo'][$i],
             'field_idpgrupo'=> $registros['idGrupo'][$i],
             'field_idpcompra'=> $id_compra,
             ]);

             //var_dump($registros['nmImporte'][$i]);
             //var_dump($date);
             //var_dump($datefin);
             //die();

             $elemento = $registros['idPresencia'][$i];
             $elementos = \Drupal::service('migrardb.migrarall')->comprobarElementos ('bz_presencia', 'field_idpresencia', $elemento);

             //var_dump($elementos);



             if (!$elementos){

                   $node->save();
                   $elementoNuevo .= '<li>ASISTENCIA'.$elemento." USUARIO: ".$id_usuario.'</li>';
                   $reg[] = $i;
                   $nCantidadRegistro++;

             }

         }



         $elementoNuevo .='</ul>';
         //drupal_set_message(t('La cantidad de Compras: '. $cantidadReg. 'de los cuales nuevas son '. $elementoNuevo), 'status', FALSE);


         $registrosVer = array();
         $limite = 0;

         var_dump($nCantidadRegistro);

             // Nos muestra los 20 primeros y los 20 últimos de los usuarios que tenemos en la base de datos //
         for ($i = 0; $i < $nCantidadRegistro; $i++ ){

             // SÓLO registros que no existen en la aplicación.

             $num = $reg[$i];

             $id_usuario = \Drupal::service('migrardb.migrarall')->codigoExterno($registros['idUsuario'][$num],'bz_usuarios','field_idusu');

             ($nCantidadRegistro < $limite ) ? $limite = $nCantidadRegistro : $limite = 10;

             if ($i < $limite){
                  $registrosVer[]="ASISTENCIA".$registros['idPresencia'][$num]." USUARIO: ".$usu[$num];
                  //drupal_set_message(t('ASISTENCIA: '. $nCantidadRegistro. ' de los cuales nuevas son ASISTENCIA '.$registros['idPresencia'][$num]." USUARIO: ".$usu[$num]), 'status', FALSE);
             }

             if ($i > ($nCantidadRegistro-$limite)){
                  $registrosVer[]="ASISTENCIA".$registros['idPresencia'][$num]." USUARIO: ".$usu[$num];
                  //drupal_set_message(t('ASISTENCIA: '. $nCantidadRegistro. ' de los cuales nuevas son ASISTENCIA '.$registros['idPresencia'][$num]." USUARIO: ".$usu[$num]), 'status', FALSE);

             }


         }
          //var_dump($usuarios);

          return [
              '#theme' => 'migrardb',
              '#titulo' => $this->t('Ver todos los registros'),
              '#descripcion' => 'Vemos todos los registros de una tabla',
              '#registros' => $registrosVer,
              '#cantidadTotal' => $cantidadReg,
          ];

    }
  public function borrarEntidad ($entidad) {

        $nodes = \Drupal::service('migrardb.migrarall')->borrado($entidad);

        // ELIMINA LOS REGISTROS DE LA ENTIDAD SELECCIONADO
        foreach ($nodes as $node) {
          $node->delete();
        }

        $numEliminados = count($nodes);

        drupal_set_message(t('Registros eliminados '.$numEliminados), 'status', FALSE);

      }


  }

?>
