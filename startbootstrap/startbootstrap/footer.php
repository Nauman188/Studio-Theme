<footer class="footer py-4 bg-dark text-white">
    <div class="container">
        <div class="row align-items-center">
            
            <!-- Left: Copyright -->
            <div class="col-lg-4 text-lg-start mb-3 mb-lg-0">
                &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
            </div>

            <!-- Center: Social Icons -->
            <div class="col-lg-4 text-center mb-3 mb-lg-0">
                <a class="btn btn-dark btn-social mx-1" href="#!" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a class="btn btn-dark btn-social mx-1" href="#!" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a class="btn btn-dark btn-social mx-1" href="#!" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            </div>

            <!-- Right: Footer Menu -->
            <div class="col-lg-4 text-lg-end">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer_menu',
                    'container'      => false,
                    'menu_class'     => 'list-inline mb-0',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'walker'         => new class extends Walker_Nav_Menu {
                        function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
                            $output .= '<li class="list-inline-item"><a class="text-white" href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a></li>';
                        }
                    }
                ));
                ?>
            </div>

        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
