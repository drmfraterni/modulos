<?php get_header(); ?>

<?php
    /// Revisa la base de datos
    while( have_posts() ): the_post();
?>

    <h1><?php the_title(); ?></h1>
    
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
            the_post_thumbnail('mediano');
        endif;
    ?>

    <?php the_content(); ?>

    Escrito por: <?php the_author(); ?>
    Fecha: <?php the_date(); ?>
<?php
    endwhile;
?>

<?php get_footer(); ?>