<?php

namespace Drupal\curso_db\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DbController extends ControllerBase
{

  /**
   * @var Connection
   */
  private $db;

  public function __construct(Connection $database)
  {
    $this->db = $database;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('database')
    );
  }

  public function queryEstatica() {

    /**
     * INSERT EN LA BASE DE DATOS
     */

    /*
    $this->db->query(
      "INSERT INTO {curso_db} ( name, value, nid ) VALUES (:name, :value, :nid)",
      [
        ':name' => 'Carlos',
        ':value' => 'Carlos en el Zoo',
        ':nid' => 1,
      ]
    );
    $this->db->query(
      "INSERT INTO {curso_db} ( name, value) VALUES (:name, :value)",
      [
        ':name' => 'Carlos',
        ':value' => 'Carlos en el Zoo',

      ]
    );
    $this->db->query(
      "INSERT INTO {curso_db} ( name, value) VALUES (:name, :value)",
      [
        ':name' => 'Carlos',
        ':value' => 'Carlos en el Zoo',

      ]
    );

    /**
     * SELECT EN LA BASE DE DATOS
     */

    /**
     * Consulta todos los usuarios
     */

    //$result = $this->db->query("SELECT * FROM {curso_db}");

    /**
     * Filtamos por un solo usuario
     */
    $result = $this->db->query("SELECT * FROM {curso_db} WHERE name = :name" , [':name' => 'David']);
    //dpm($result->fetchAll(), 'Resultado de la consulta');

    $valores = $result->fetchAll();

    $seleccion = array();

    foreach($valores as $valor){
      //dpm($valor->name, 'nombre');
      //var_dump($valor->name);
      $seleccion [] = $valor->name; 
      
    }

    var_dump($seleccion);


    
    return ['#markup' => 'Consultas a base de datos estaticas.'];
  }



  public function selectDinamico() {

    /**
     * Búsquedas en la base de datos dinámicas
     * operadores condiciones para la base de datos de drupal
     * https://www.drupal.org/docs/8/api/database-api/dynamic-queries/conditions#s-supported-operators
     */

    $query = $this->db->select('curso_db', 'c');
    /**
     * Para devolver todos los campos
     * $query->fields('c', ['id', 'name']);
     */
    $query->fields('c', ['id', 'name']);
    $query->orderBy('c.name', 'ASC');

    // PARA HACER JOIN a la tabla NODE
    //$query->join ('node','n', 'c.nid = n.nid');

    // Campos de la tabla NODE
    // $query->fields('n');
    // $query->fields('n', ['type']);

    // TIPOS DE CONDICIÓN
    // $query->condition('name', 'Maria', '<>');
    // $query->condition('name', 'Carlos', '=');
    // si no ponemos operador nos pone por defecto el =
    // $query->condition('name', 'Carlos');

    // $query->isNotNull('nid');

    $nid = TRUE;

    if ($nid) {
       $query->isNotNull('nid');
    }else {
      $query->condition('name', 'Josefina');

    }


    $result = $query->execute();
    //dpm($result->fetchAll(), 'Resultado de la consulta dinámica');
    //var_dump($result->fetchAll());



    return ['#markup' => 'Consultas a base de datos select dinamico.'];
  }

  public function insertDinamico() {

    $values = [
      'name' => 'Margarita',
      'value' => 'Margarita se fue al campo',
    ];
    
    $this->db->insert ('curso_db')
    ->fields($values)
    ->execute();

    //dpm($values['name'], 'nueva usuaria insertada en la base de datos');

    return ['#markup' => 'Consultas a base de datos insert dinamico.'];
  }

  public function updateDinamico() {
    
    $values = [
      'name' => 'Juanita',
      'value' => 'Juanita se fue al campo',
    ];
    
    $this->db->update ('curso_db')
    ->fields($values)
    //->condition('name', 'Virginia', '=')

    ->condition('name', 'Virginia')
    ->execute();
    
    
    return ['#markup' => 'Consultas a base de datos update dinamico.'];

  }

  public function deleteDinamico() {

    $this->db->delete('curso_db')
    ->condition('name', 'Pablo')
    ->execute();

    return ['#markup' => 'Consultas a base de datos delete dinamico.'];
  }

  public function mergeDinamico() {
    
    /**
     * Hacer un consulta si esta devuelve True hace update
     * si devuelve un False hace un insert.
     */
    
    /*$values = [
      'name' => 'Margarita',
      'value' => 'Margarita se fue a la Montaña',
    ];

    $name = 'Margarita';

    $this->db->merge('curso_db')
    ->key('name', $name)
    ->fields($values)
    ->execute();
    
    */
    
    $values_insert = [
      'name' => 'Jesus',
      'value' => 'Jesus está en un barco en el mar Mediterraneo',
    ];

    $values_update = [
      'name' => 'Jesus',
      'value' => 'Jesus está en un barco, con un update especial del merge',
    ];

    $name = 'Jesus';

    $this->db->merge('curso_db')
    ->key('name', $name)
    ->insertFields($values_insert)
    ->updateFields($values_update)
    ->execute();

    
    return ['#markup' => 'Consultas a base de datos con merge.'];
  }

}
