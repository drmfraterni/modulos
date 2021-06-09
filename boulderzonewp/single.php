<?php get_header(); ?>

<?php
    /// Revisa la base de datos
    while( have_posts() ): the_post();
?>

    <h1><?php the_title(); ?></h1>

    <?php the_content(); ?>

    Escrito por: <?php the_author(); ?>
    Fecha: <?php the_date(); ?>
<?php
    endwhile;
?>
<?php get_footer(); ?>