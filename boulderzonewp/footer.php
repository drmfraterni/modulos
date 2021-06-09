
        <footer class="site-footer contenedor">
            <hr>

            <div class="contenido-footer">
            <?php  
                // forma de introducir el menú en wordPress
                $args = array(
                    'theme_location' => 'menu-principal',
                    'container' => 'nav',
                    'container_class' => 'menu-principal'
                );

                wp_nav_menu($args) // cargar el menú;

            ?>
            <p class="copyright">Todos los derechos reservados. <?php echo get_bloginfo('name'). " ". date("Y"); ?></p>

            </div>
        </footer>

        <?php wp_footer();   ?>

    </body>
</html>