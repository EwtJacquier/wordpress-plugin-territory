<?php
/**
 * Plugin Name:     XP WP Core
 * Plugin URI:      https://github.com/EwtJacquier
 * Description:     Essential functions for plugin territory based plugins
 * Author:          Ewerton
 * Author URI:      https://github.com/EwtJacquier
 * Text Domain:     xp-wp-core
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Xp_Wp_Core
 */

if ( ! defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly.

require_once ( 'includes/XP_WP_Core.php' );

add_action( 'plugins_loaded', array( 'XP_WP_Core', 'get_instance' ) );