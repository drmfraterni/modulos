<?php 
/**
 * Template Name: Template para Galerias
 */

get_header(); ?>

<!-- CONTENIDO PRINCIPAL DE LA PAGINA -->

<main class="contenedor pagina seccion">
    <div class="contenido-principal">
        
    <?php while( have_posts() ): the_post(); /// Revisa la base de datos ?>

        <h1 class="text-center texto-primario"><?php the_title(); ?></h1>

        <?php
            /**
             * Personalizamos el código de galeria
             * Por un lado conseguimos un id de imagen
             * Tenemos que obtener por un lado la imagen grande y otra pequeña.
             * Para acceder al ID tiene una función WP que es: get_the_ID.
             * 
             * En get_post_gallery los parámetros son el ID y 
             * false para que no meta HTML.
             * 
             */

            // Obtener la galería de la página actual
            $galeria = get_post_gallery(get_the_ID(), false);
            // Obtener los ids de la imágenes
            $galeria_imagenes_ID = explode(',', $galeria['ids']);
        ?>
        <ul class="galeria-imagenes">
        
            <?php

                $i = 1;

                foreach($galeria_imagenes_ID as $id):
                    // square la tenemos definida en la functions del worpress
                    // la funcion wp_get_attachment_image_src viene por defecto del wordpress

                    /**
                     * Tenemos que agregar lightbox para agrandar la imagen cuando hacemos clic
                     * Tenemos que agregar css grip para que la imagen 4 y 6 sean de distinto tamaño
                     */
                    $size = ($i == 4 || $i == 6) ? 'portrait' : 'square';

                    // Imagen pequeña
                    $imagenThumb = wp_get_attachment_image_src($id, $size)[0];
                    // Imagen grande cuando pinchamos sobre la pequeña
                    $imagen = wp_get_attachment_image_src($id, 'full')[0];




            ?>

            <li>
                    <a href="<?php echo $imagen; ?>" data-lightbox="galeria" >
                        <img src="<?php echo $imagenThumb; ?>" alt="imagen">
                    </a>
            </li>

            <?php $i++; endforeach ?>

        </ul>

        
                
        

    <?php endwhile; ?>
    
    </div>

</main>



<?php get_footer(); ?>