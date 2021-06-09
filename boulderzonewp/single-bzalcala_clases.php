<?php get_header(); ?>

<!-- CONTENIDO PRINCIPAL DE LA PAGINA -->

<main class="contenedor pagina seccion con-sidebar">
    <div class="contenido-principal">
        
        <?php  get_template_part('template-parts/paginas'); ?>
    
    </div>

    <!-- BARRA LATERAL -->
    <?php get_sidebar('clases') ?>

</main>



<?php get_footer(); ?>