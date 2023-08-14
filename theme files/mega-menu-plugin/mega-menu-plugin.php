<?php
/**
 * Plugin Name: Mega Menu Plugin
 * Description: Adds a custom Walker class for the mega menu feature.
 * Version: 1.0
 * Author: Your Name
 */

// Load the custom Walker class
require_once plugin_dir_path( __FILE__ ) . 'includes/zamona-walker-nav-menu.php';

// Function to set the custom Walker class for the menu
function mega_menu_plugin_custom_walker( $args ) {
    if ( ! class_exists( 'Zamona_Walker_Nav_Menu' ) ) {
        return $args;
    }

    $args['walker'] = new Zamona_Walker_Nav_Menu();

    return $args;
}
add_filter( 'wp_nav_menu_args', 'mega_menu_plugin_custom_walker' );
