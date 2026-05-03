<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function startbootstrap_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'startbootstrap_setup' );


/**
 * Enqueue scripts and styles.
 */
function startbootstrap_scripts() {

    // Google Fonts Preconnect
    wp_enqueue_style( 'startbootstrap-google-fonts-preconnect', false );
    wp_style_add_data(
        'startbootstrap-google-fonts-preconnect',
        'after',
        "<link rel='preconnect' href='https://fonts.googleapis.com'><link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>"
    );

    // Google Fonts
    wp_enqueue_style(
        'startbootstrap-google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto+Slab:wght@100;300;400;700&display=swap',
        array(),
        null
    );

    // ✅ Font Awesome CSS
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css',
        array(),
        '5.15.4'
    );

    // Bootstrap CSS
    wp_enqueue_style(
        'bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
        array(),
        '5.3.2'
    );

    // Bootstrap JS
    wp_enqueue_script(
        'bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
        array('jquery'), // Added jquery as dependency
        '5.3.2',
        true
    );

    // Theme CSS
    wp_enqueue_style(
        'startbootstrap-style',
        get_stylesheet_directory_uri() . '/css/styles.css',
        array('bootstrap-css', 'startbootstrap-google-fonts'),
        filemtime( get_stylesheet_directory() . '/css/styles.css' )
    );

    // Theme JS
    $script_path = '/js/scripts.js';
    if ( file_exists( get_stylesheet_directory() . $script_path ) ) {
        wp_enqueue_script(
            'startbootstrap-scripts',
            get_stylesheet_directory_uri() . $script_path,
            array('bootstrap-js', 'jquery'),
            filemtime( get_stylesheet_directory() . $script_path ),
            true
        );
    }

    // Pass AJAX URL to your JS file
    wp_localize_script('startbootstrap-scripts', 'contact_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action( 'wp_enqueue_scripts', 'startbootstrap_scripts' );


/**
 * Register menus
 */
function startbootstrap_register_menus() {
    register_nav_menus( array(
        'main_menu'   => __( 'Main Menu', 'startbootstrap' ),
        'footer_menu' => __( 'Footer Menu', 'startbootstrap' ),
    ) );
}
add_action( 'init', 'startbootstrap_register_menus' );


/**
 * Add bootstrap classes to menu
 */
function startbootstrap_nav_menu_css_class( $classes, $item, $args ) {
    if ( isset( $args->theme_location ) && $args->theme_location === 'main_menu' ) {
        $classes[] = 'nav-item';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'startbootstrap_nav_menu_css_class', 10, 3 );

function startbootstrap_nav_menu_link_attributes( $atts, $item, $args ) {
    if ( isset( $args->theme_location ) && $args->theme_location === 'main_menu' ) {
        $atts['class'] = (isset($atts['class']) ? $atts['class'] . ' ' : '') . 'nav-link';
    }
    if ( isset( $args->theme_location ) && $args->theme_location === 'footer_menu' ) {
        $atts['class'] = (isset($atts['class']) ? $atts['class'] . ' ' : '') . 'link-dark text-decoration-none';
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'startbootstrap_nav_menu_link_attributes', 10, 3 );


/* ==========================================================================
   CONTACT FORM SUBMISSION LOGIC
   ========================================================================== */

// 1. Register Custom Post Type for Submissions
function startbootstrap_register_submissions_cpt() {
    $args = array(
        'labels' => array(
            'name' => 'Form Submissions',
            'singular_name' => 'Submission'
        ),
        'public' => false,
        'show_ui' => true,
        'capability_type' => 'post',
        'menu_icon' => 'dashicons-email-alt',
        'supports' => array('title', 'editor')
    );
    register_post_type('contact_submissions', $args);
}
add_action('init', 'startbootstrap_register_submissions_cpt');

// 2. Handle AJAX Saving
add_action('wp_ajax_save_contact_form', 'handle_contact_form_submission');
add_action('wp_ajax_nopriv_save_contact_form', 'handle_contact_form_submission');

function handle_contact_form_submission() {
    // Check security nonce (from the form)
    if (!isset($_POST['contact_form_security']) || !wp_verify_nonce($_POST['contact_form_security'], 'contact_form_nonce')) {
        wp_send_json_error('Security check failed.');
    }

    $name    = sanitize_text_field($_POST['u_name']);
    $email   = sanitize_email($_POST['u_email']);
    $phone   = sanitize_text_field($_POST['u_phone']);
    $message = sanitize_textarea_field($_POST['u_message']);

    $post_id = wp_insert_post(array(
        'post_title'   => $name . ' - ' . date('Y-m-d H:i'),
        'post_content' => $message,
        'post_status'  => 'publish',
        'post_type'    => 'contact_submissions',
    ));

    if ($post_id) {
        update_post_meta($post_id, '_contact_email', $email);
        update_post_meta($post_id, '_contact_phone', $phone);
        wp_send_json_success('Thank you! Your message has been sent.');
    } else {
        wp_send_json_error('Something went wrong. Please try again.');
    }
    wp_die();
}

// 3. Customize Admin Columns for the Submissions List
add_filter('manage_contact_submissions_posts_columns', 'sb_contact_columns');
function sb_contact_columns($columns) {
    $columns['u_email'] = 'Email';
    $columns['u_phone'] = 'Phone';
    return $columns;
}

add_action('manage_contact_submissions_posts_custom_column', 'sb_fill_contact_columns', 10, 2);
function sb_fill_contact_columns($column, $post_id) {
    if ($column === 'u_email') {
        echo get_post_meta($post_id, '_contact_email', true);
    }
    if ($column === 'u_phone') {
        echo get_post_meta($post_id, '_contact_phone', true);
    }
}