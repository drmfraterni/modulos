<?php

namespace Drupal\curso_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityController extends ControllerBase 
{
    /**
     * Ejemplo para cargar entidades
     * drupal8 node_load y user_load
     * drupal9 entitymanager
     */

    public function __construct(EntityTypeManagerInterface $entityTypeManager)
    {
        $this->entityTypeManager = $entityTypeManager;
    }

    public static function create(ContainerInterface $container){
        return new static(
            $container->get('entity_type.manager')
        );
    }

    public function entityLoad() {

        /**
         * Ejemplo de cargar usuarios
         */
        
        //$user = $this->entityTypeManager->getStorage('user')->load(1);

        /**
         * Ver todos los usuarios que tenemos en el Drupal
         */

        $users = $this->entityTypeManager->getStorage('user')->loadMultiple();

        dpm($users, 'Todos los usuarios');

        //dpm($user);

        /**
         * Cargamos un nodo determinado
         */
        
        //$node = $this->entityTypeManager->getStorage('node')->load(1);

        /**
         * Cargamos varios nodos;
         */

        $nodes = $this->entityTypeManager->getStorage('node')->loadMultiple([1,2,3,4]);

        dpm($nodes, 'Nodos múltiple');


        return['#markup' => 'Ruta carga entidades'];
    } 

    public function entityCreate(){

        /**
         * Crear un nuevo nodo
         */

        /*
        $values = [
            'title' => 'Nodo creado en código',
            'type'  => 'page'
        ];

        $node = $this->entityTypeManager->getStorage('node')->create($values);
        $node->save();

        dpm($node);

        */

        /**
         * Crear un nuevo usuario
         */
        /*
        $values = [
            'name'   => 'test',
            'mail'   => 'ejemplo@test.com',
            'pass'   => '123456',
            'status' => 1,
        ];

        $user = $this->entityTypeManager->getStorage('user')->create($values);
        $user->save();
        */

        /**
         * Crear una taxonomía
         */

        $values = [
            'name'   => 'Montaña',
            'vid'   => 'tags',
        ];

        $taxonomia = $this->entityTypeManager->getStorage('taxonomy_term')->create($values);
        //$taxonomia->save();

        return['#markup' => 'Ruta para crear entidades (taxonomia)'];

    }

    public function entityEdit() {

        /*$values = [
            'title' => 'Artículo creado en código',
            'type'  => 'article'
        ];

        $node = $this->entityTypeManager->getStorage('node')->create($values);
        $node->save();
        */

        /**
         * CARGAMOS PRIMERO EL NODO QUE QUEREMOS MODIFICAR
         */


        /** @var NodeInterface $node */
        $node = $this->entityTypeManager->getStorage('node')->load(11);

        /**
         * OBTENER LOS CAMPOS DEL NODO
         * Para obtener el valor:
         * $node->get('title')->value; // nos devuelve el valor del campo o el primer 
         * valor de un campos con valores multiple
         * $node->get('title')->getValue();  // nos devuelve un array para aquellos 
         * campos que tiene varios valores 
         * Para darle un nuevo valor:
         * $node->set('body','nuevo texto')->value; 
         */

         /*

            $node->set('body', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur iaculis et nisi eu tristique. Sed nec felis purus. Nulla facilisi. Ut erat elit, scelerisque quis aliquam sit amet, lobortis a magna. Vivamus ullamcorper suscipit arcu quis rutrum. Aliquam erat volutpat. Suspendisse vel ante cursus, faucibus lectus et, congue dui.');
            $node->save();
            $campo = $node->get('body')->value;
            dpm($campo); 
        */

        /**
         * ASOCIAMOS UNA TAXONOMIA AL ARTÍCULO QUE QUEREMOS MODIFICAR.
         */

        $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadMultiple();

        $node->get('field_tags')->appendItem($terms[2]);
        $node->get('field_tags')->appendItem($terms[3]);
        $node->get('field_tags')->appendItem($terms[4]);
        $node->get('field_tags')->appendItem($terms[5]);
        $node->save();

        /**
         * PARA BORRAR EL CONTENIDO DE UN CAMPO
         */

         // $node->get('field_tags')->removeItem(0);
         // $node->save();




        return['#markup' => 'Ruta para editar entidades'];

    }



}
