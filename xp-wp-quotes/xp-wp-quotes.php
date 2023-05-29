<?php
/**
 * Plugin Name:     XP WP Quotes
 * Plugin URI:      https://github.com/EwtJacquier/wordpress-plugin-territory
 * Description:     Child plugin from XP WP Core plugin
 * Author:          Ewerton
 * Author URI:      https://github.com/EwtJacquier
 * Text Domain:     xp-wp-quotes
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Xp_Wp_Quotes
 */

if ( ! defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly.

$core_file = plugin_dir_path(__FILE__ ) . '../xp-wp-core/xp-wp-core.php';

if ( !file_exists( $core_file ) ){
    add_action( 'plugins_loaded' , 'xp_wp_child_core_missing' );

    function xp_wp_child_core_missing(){
        echo '<div class="notice notice-error"><p>'.esc_html__('XP WP Core Plugin is missing.','xp-wp-core-child').'</p></div>';
    }
}
else{
    require_once ( $core_file );
    require_once ( 'includes/XP_WP_Quotes.php' );

    add_action( 'plugins_loaded' , array('XP_WP_Quotes', 'get_instance') );
}