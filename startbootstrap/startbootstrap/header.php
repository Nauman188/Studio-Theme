<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php wp_head(); ?>
    
</head>

<body <?php body_class(); ?> id="page-top">
<?php
// For themes that support wp_body_open()
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
}
?>

<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/navbar-logo.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'startbootstrap' ); ?>">
            <?php esc_html_e( 'Menu', 'startbootstrap' ); ?> <i class="fas fa-bars ms-1"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">
    <?php
    wp_nav_menu( array(
        'theme_location' => 'main_menu',
        'container'      => false,
        'menu_class'     => 'navbar-nav text-uppercase ms-auto py-4 py-lg-0',
        'fallback_cb'    => 'wp_page_menu',
        'depth'          => 2, // Allows for one level of nesting
        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    ) );
    ?>
</div>
    </div>
</nav>
