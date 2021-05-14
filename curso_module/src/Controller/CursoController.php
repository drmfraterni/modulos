<?php

namespace Drupal\curso_module\Controller;

use Drupal;
use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\curso_module\Services\Repetir;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\curso_module\Services\Miscelaneo;
use Psr\Container\ContainerInterface;

class CursoController extends ControllerBase {
   
    /**
     * Para instanciar nuestro servicio tenemos que hacer los siguiente
     * crear la funcion contructor con nuestro servicio y lo que va a recibir
     * crear un contenedor para dejarlo instanciado
     * utilizar el servicio de manera normal
     */

    // variable para el servicio
    private $repetir;

    /**
     * Funcion constructora para instanciar los servicios
     * Por un lado el servicio creado por nosotros llamado Miscelaneo
     * Por otro lado el servicio que nos proporciona Drupal de entityTypeManager
     *     
     * */


    /* public function __construct(Miscelaneo $repetir, EntityTypeManagerInterface $entityTypeManager)
    {
        $this->repetir = $repetir;   
    }*/
    
    public function __construct(Miscelaneo $repetir)
    {
        $this->repetir = $repetir;   
    }

    public static function create(ContainerInterface $container) {
        return new static (
            $container->get('curso_module.repetir')
            //$container->get('entity_type.manager')
        );
    }
    

    public function home() {

        return [
            '#markup' => 'El markup de nuestro controlador'
        ];

    }

    
    /**
     * Introducimos parámetros en un controlador
     */

    public function parametros($pagina) {

        $this->messenger()->addMessage($pagina, 'custom');

        return [
            '#markup' => 'La página es: '. $pagina,
        ];

    }


    /**
     * Introducimos un nodos, la etiqueta
    */

    public function nodos(NodeInterface $node) 
    {

        //Instanciamos un servicio forma que funciona pero incorrecta
        //$prueba = \Drupal::service('curso_module.repetir');
        //$resultado = $prueba->repetir('curso ', 5);

        //Instanciamos el servicio con las funciones contructor y con la función container.
        $resultado = $this->repetir->repetir('curso ', 5);

        return [
            '#theme' => 'curso_plantilla',
            '#etiqueta' => $node->bundle(),
            '#tipo' => $resultado,
        ];

    }

    public function formulario()
    {

        $form = $this->formBuilder()->getForm('Drupal\curso_module\Form\CursoForm');

        $build = [];
        $markup = ['#markup' => 'Esta es la página del formulario',];

        $build[] = $form;
        $build[] = $markup;

        return $build;

        /*
            '#theme' => 'curso_plantilla',
            '#etiqueta' => 'formulario de prueba a través de un controlador',
            '#formulario' => $form,
        */

    }

    /**
     * Formulario de configuración del sistema
     */

    public function configCurso(){

        $config = $this->config('system.site');
        dpm($config);
        dpm($config->get('name'), 'name');

        return ['#markup' => 'ruta de configuración'];
    }
 


}

?>

