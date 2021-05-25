<?php

namespace Drupal\curso_config\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\curso_module\Services\Miscelaneo;
use Symfony\Component\DependencyInjection\ContainerInterface;



class CursoConfigForm extends ConfigFormBase
{

    /**
     * También vamos a implimentar dos servicios en un formulario de configuración
     * El servicio de $config_factory del sistema de Drupal. Este es un servicio especial
     * y por ello tenemos que llamar al constructor origen con el parent como podemos ver
     * abajo.
     * 
     * Y el servicio construido por nosotros llamado Miscelaneo;
     * 
     * Recordar que para instanciar un servicio necesitamos el contructor y el container
     * 
     */


    private $repetir;
    /**
    * Constructs a \Drupal\system\ConfigFormBase object.
    *
    * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
    *   The factory for configuration objects.
    */

    public function __construct(ConfigFactoryInterface $config_factory, Repetir $repetir) {
        parent::__construct($config_factory); // llamada al contructor de la clase origien $config_factory
        $this->repetir = $repetir;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('config.factory'),
            $container->get('curso_module.repetir')
        );
    }

    /** 
     * {@inheritdoc}
     */

    public function getFormId() {
        return 'curso_config_nuestra_form';
    }

    /** 
     * {@inheritdoc}
     */

    protected function getEditableConfigNames() {
        return [
            'curso_config.nuestra_configuracion'
        ];
    }

    /** 
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state) {

        $config = $this->config('curso_config.nuestra_configuracion');

        $form['name'] = [
            '#type' => 'textfield',
            '#title' => 'Name',
            '#default_value' => $config->get('name'),
        ];  

        $form['label'] = [
            '#type' => 'textfield',
            '#title' => 'label',
            '#default_value' => $config->get('label'),
        ];  

        return parent::buildForm($form, $form_state);

    }

    /** 
     * {@inheritdoc}
     */

    public function submitForm(array &$form, FormStateInterface $form_state) {
    

        parent::submitForm($form, $form_state);

        $config = $this->config('curso_config.nuestra_configuracion');
        $config->set('name', $form_state->getValue('name'));
        $config->set('label', $form_state->getValue('label'));
        $config->save();
    }


}



?>