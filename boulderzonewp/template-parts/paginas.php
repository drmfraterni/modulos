<?php while( have_posts() ): the_post(); /// Revisa la base de datos ?>

    <h1 class="text-center texto-primario"><?php the_title(); ?></h1>
            
        <?php 
            // Muestra la imagen de destacados
            // Indica que si hay página destacada la pone
            /**
            * tamaños de imagen para la function the_post_thumbnail();
            * Thumbnail, de 150px x 150px (máximo)
            * Medium, de 300px x 300px (máximo)
            * Large, de 1024px x 1024px (máximo)
            * Full, imagen a tamaño original (tal y como se sube)
            * en functions.php hemos definido más tamaños de imagen
            * Hemos añadido un plugin para regenera la imágenes
            **/
            if(has_post_thumbnail()):
                the_post_thumbnail('mediano', array('class' => 'imagen-destacada'));
            endif;
        ?>

        <?php

            // Revisar si el custom post type es Clases del gimnasio 

            if (get_post_type() === 'bzalcala_clases') {
                // campos del post type
                
                $hora_inicio = get_field('hora_inicio');
                $hora_fin = get_field('hora_fin');
        ?>                        
                <p class="informacion-clase"><?php the_field('dias_clase') ?> - <?php echo $hora_inicio . " a " . $hora_fin; ?></p>
        <?php

            }

        ?>
    <?php the_content(); ?>

<?php endwhile; ?>