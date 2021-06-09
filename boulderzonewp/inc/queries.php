<?php

    // https://developer.wordpress.org/reference/classes/wp_query/#category-parameters

    function bzalcala_lista_clases(){

?>

        <ul class="lista-clases">

            <?php

                $args = array(
                    'post_type' => 'bzalcala_clases',
                    'posts_per_page' => 10
                );

                // consulta a la base de datos

                $clases = new WP_Query($args);
                while( $clases->have_posts() ): $clases->the_post(); 
            ?>

                <li class="clase card gradient">
                    <?php the_post_thumbnail('mediano'); ?>
                    <div class="contenido">
                        <a href="<?php the_permalink(); ?>">
                            <h3><?php the_title(); ?></h3>
                        </a>
                        
                        <?php
                            $hora_inicio = get_field('hora_inicio');
                            $hora_fin = get_field('hora_fin');                        
                        ?>
                        <p><?php the_field('dias_clase') ?> - <?php echo $hora_inicio . " a " . $hora_fin; ?></p>
                    </div>
                </li>

            <?php
                
                endwhile;
                wp_reset_postdata(); // dejamos de utilizar el WP_Query

            ?>
        
        
        </ul>
<?php

    }

?>