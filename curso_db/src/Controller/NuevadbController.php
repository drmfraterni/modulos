<?php

namespace Drupal\curso_db\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\curso_db\Services\Nconexion;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\Response;
use Psr\Container\ContainerInterface;


class NuevadbController extends ControllerBase
{
    private $nuevaconexion;

    public function __construct(Nconexion $nuevaconexion)
    {
        $this->nuevaconexion = $nuevaconexion;   
    }

    public static function create(ContainerInterface $container) {
        return new static (
            $container->get('curso_db.nuevaconexion')
        );
    }

    public function allUsuarios (){
        
        //$nuevo = new Miscelaneo();

        /**
         * conexión a una base de datos externa a Drupal 
         * Con el alias de 'aplicación'
         * Para ver la configuración hay que ir al setting.php
         */
        $nc = $this->nuevaconexion->nuevaConexion();

        /**
         * Realizamos la consulta a usuarios
         * 
         */

        $query = $nc->select('bza_usuarios', 'u');
        $query->fields('u', ['idUsuario', 'dsNombre', 'dsApellido1', 'dsApellido2', 'dsEmail']);
        $result = $query->execute();
        $usuarios = $result->fetchAll();

        foreach($usuarios as $usuario){
            $allUsuarios['nombre'][] = $usuario->dsNombre;
            $allUsuarios['apellidos'][] = $usuario->dsApellido1. ' '.$usuario->dsApellido2;
            $allUsuarios['correo'][] = $usuario->dsEmail;
        }

        // MANDAR LOS DATOS A LA PLANTILLA DE CORREO
        return [
            '#theme' => 'usuarios',
            '#titulo' => $this->t('TODOS LOS USUARIOS'),
            '#descripcion' => 'mostrar todos los usuarios',
            '#datos' => $allUsuarios
        ];

    }

}


?>