<?php get_header(); ?>

<!-- CONTENIDO PRINCIPAL DE LA PAGINA -->

<main class="contenedor pagina seccion no-sidebar ">
    <div class="text-center">
        
        <?php  get_template_part('template-parts/paginas'); ?>


        <?php  bzalcala_lista_clases(); ?>
    
    </div>

</main>



<?php get_footer(); ?>