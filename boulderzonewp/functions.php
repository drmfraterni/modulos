<?php

    /** Consultas reutilizables  */

    require get_template_directory().'/inc/queries.php';

    //Cuando el tema es activado

    function boulderzone_setup(){

        // Habilitar imagenes destacadas.

        add_theme_support('post-thumbnails');

        // Agregar imagenes de tamaño personalizado

        add_image_size('square', 350, 350, true);
        add_image_size('portrait', 350, 724, true);
        add_image_size('cajas', 400, 375, true);
        add_image_size('mediano', 700, 400, true);
        add_image_size('blog', 966, 644, true);


    }
    add_action('after_setup_theme', 'boulderzone_setup');

    // para crear nuestros menús de navegación
    // para agregar más menús seguir con el arreglo
    
    function boulderzone_menus() {
        register_nav_menu( 'menu-principal', __( 'Menu Principal', 'boulderzonewp' ) );
    }
    add_action( 'init', 'boulderzone_menus' );


    // Scripts y Styles

    function boulderzone_scripts_style(){
    // Cargar las distintas HOJAS DE ESTILO
    // https://developer.wordpress.org/reference/

        wp_enqueue_style('normalize', get_template_directory_uri().'/css/normalize.css', array(), '8.0.1');

        wp_enqueue_style('slicknavCSS', get_template_directory_uri().'/css/slicknav.css', array(), '1.0.0');

        wp_enqueue_style('googleFont', 'https://fonts.googleapis.com/css?family=Open+Sans|Raleway:400,700,900|Staatliches&display=swap', array(), '1.0.0' );

    // https://codex.wordpress.org/Conditional_Tags
    // Sirve para que carge en una página determinada.

    if ( is_page('galeria') ) { 

        wp_enqueue_style('lightboxCSS', get_template_directory_uri().'/css/lightbox.min.css', array(), '2.11.2');
        
    }
        

        wp_enqueue_style('style', get_stylesheet_uri(), array('normalize', 'googleFont'), '1.0.0');

        wp_enqueue_script('slicknavJS', get_template_directory_uri().'/js/jquery.slicknav.min.js', array('jquery'), '1.0.0', true);

        wp_enqueue_script('lightboxJS', get_template_directory_uri().'/js/lightbox.min.js', array('jquery'), '2.11.2', true);

        wp_enqueue_script('scripts', get_template_directory_uri().'/js/scripts.js', array('jquery', 'slicknavJS'), '1.0.0', true);

    }

    add_action('wp_enqueue_scripts', 'boulderzone_scripts_style');


    // Adiccionar Widgets. Definir Zonas de Widgets


    function boulderzone_widgets() {

        register_sidebar( array (
            'name' => 'Sidebar 1',
            'id' => 'sidebar_1',
            'before_widget' => '<div class="widget">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="text-center texto-primario">',
            'after_title' => '</h3>'

        ));

        register_sidebar( array (
            'name' => 'Sidebar 2',
            'id' => 'sidebar_2',
            'before_widget' => '<div class="widget">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="text-center texto-primario">',
            'after_title' => '</h3>'

        ));

    }
    add_action('widgets_init', 'boulderzone_widgets');


    




?>